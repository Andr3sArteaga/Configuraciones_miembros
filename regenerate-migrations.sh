#!/bin/bash

# Script para regenerar migraciones desde PostgreSQL
# Uso: ./regenerate-migrations.sh
#
# MEJORAS:
# - Lee automáticamente las tablas desde la BD (no hardcodeadas)
# - Usa timestamps fijos para evitar cambios en Git
# - Excluye tablas del sistema de Laravel

set -e

echo "🔄 Regenerando migraciones desde base de datos..."
echo ""

# Verificar que existe la BD configurada
if [ -z "$DB_DATABASE" ]; then
    # Leer desde .env si no está en variable de entorno
    export $(grep -v '^#' .env | xargs)
fi

DB_NAME="${DB_DATABASE:-sistema_bomberos}"
DB_USER="${DB_USERNAME:-angelfrederickpizaojeda}"

echo "📊 Base de datos: $DB_NAME"
echo "👤 Usuario: $DB_USER"
echo ""

# 1. Obtener lista de tablas desde PostgreSQL (excluir tablas del sistema)
echo "🔍 Obteniendo lista de tablas desde PostgreSQL..."
TABLES=$(psql -U "$DB_USER" -d "$DB_NAME" -t -c "
    SELECT string_agg(tablename, ',' ORDER BY tablename)
    FROM pg_tables
    WHERE schemaname = 'public'
    AND tablename NOT IN (
        'migrations',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'password_reset_tokens',
        'personal_access_tokens',
        'spatial_ref_sys'
    );
" | tr -d ' ')

if [ -z "$TABLES" ]; then
    echo "❌ Error: No se encontraron tablas en la base de datos"
    exit 1
fi

echo "📋 Tablas encontradas:"
echo "$TABLES" | tr ',' '\n' | sed 's/^/   - /'
echo ""

# Contar tablas
TABLE_COUNT=$(echo "$TABLES" | tr ',' '\n' | wc -l | tr -d ' ')
echo "📊 Total de tablas: $TABLE_COUNT"
echo ""

# 2. Eliminar migraciones existentes (excepto las base de Laravel y PostGIS)
echo "🗑️  Limpiando migraciones antiguas..."
find database/migrations -type f ! -name "0001_01_01_*" -delete
echo "   ✓ Migraciones antiguas eliminadas"
echo ""

# 3. Generar migraciones desde la base de datos
echo "📦 Generando migraciones desde PostgreSQL..."
php artisan migrate:generate \
    --connection=pgsql \
    --tables="$TABLES"
echo "   ✓ Migraciones generadas"
echo ""

# 4. Normalizar timestamps de las migraciones (para evitar cambios en Git)
echo "🔧 Normalizando timestamps de migraciones..."

# Obtener todas las migraciones nuevas (excepto las que empiezan con 0001_01_01)
MIGRATION_FILES=$(find database/migrations -type f ! -name "0001_01_01_*" -name "*.php" | sort)

# Contador para timestamps secuenciales
COUNTER=1

# Base timestamp: 2024_01_01_000000
BASE_YEAR="2024"
BASE_MONTH="01"
BASE_DAY="01"

for MIGRATION_FILE in $MIGRATION_FILES; do
    # Calcular nuevo timestamp secuencial
    PADDED_COUNTER=$(printf "%06d" $COUNTER)
    NEW_TIMESTAMP="${BASE_YEAR}_${BASE_MONTH}_${BASE_DAY}_${PADDED_COUNTER}"

    # Extraer nombre de la migración (sin el timestamp)
    FILENAME=$(basename "$MIGRATION_FILE")
    MIGRATION_NAME=$(echo "$FILENAME" | sed -E 's/^[0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]{6}_//')

    # Nuevo nombre de archivo
    NEW_FILENAME="${NEW_TIMESTAMP}_${MIGRATION_NAME}"
    NEW_PATH="database/migrations/${NEW_FILENAME}"

    # Renombrar solo si es diferente
    if [ "$MIGRATION_FILE" != "$NEW_PATH" ]; then
        mv "$MIGRATION_FILE" "$NEW_PATH"
        echo "   ✓ $MIGRATION_NAME → $NEW_TIMESTAMP"
    fi

    COUNTER=$((COUNTER + 1))
done

echo "   ✓ Timestamps normalizados ($((COUNTER - 1)) archivos)"
echo ""

# 5. Arreglar UUIDs con DB::raw()
echo "🔧 Corrigiendo defaults de UUID..."
if php artisan list | grep -q "migrations:fix-uuid"; then
    php artisan migrations:fix-uuid
    echo "   ✓ UUIDs corregidos"
else
    echo "   ⚠️  Comando 'migrations:fix-uuid' no disponible (omitiendo)"
fi
echo ""

# 6. Verificar funciones necesarias en BD
echo "🔍 Verificando funciones necesarias en la base de datos..."

# Verificar si existe la función validar_entidad_tipo
FUNCTION_EXISTS=$(psql -U "$DB_USER" -d "$DB_NAME" -t -c "
    SELECT COUNT(*)
    FROM pg_catalog.pg_proc
    WHERE proname = 'validar_entidad_tipo'
    AND pg_catalog.pg_namespace.nspname = 'public'
" 2>/dev/null || echo "0")

if [ "$FUNCTION_EXISTS" = "0" ]; then
    echo "⚠️  Advertencia: Función 'validar_entidad_tipo()' no encontrada en BD"
    echo "   Esto es necesario solo si usas la tabla 'cursos_asignados'"
    echo "   Puedes crearla con:"
    echo ""
    echo "   CREATE FUNCTION public.validar_entidad_tipo() RETURNS trigger"
    echo "       LANGUAGE plpgsql AS \$\$"
    echo "   BEGIN"
    echo "       IF NEW.entidad_tipo NOT IN ('usuario', 'comunario') THEN"
    echo "           RAISE EXCEPTION 'Valor inválido para entidad_tipo';"
    echo "       END IF;"
    echo "       RETURN NEW;"
    echo "   END;"
    echo "   \$\$;"
    echo ""
else
    echo "   ✓ Función validar_entidad_tipo() existe"
fi
echo ""

# Verificar que PostGIS extension existe
if [ ! -f "database/migrations/0001_01_01_000003_enable_postgis_extensions.php" ]; then
    echo "⚠️  Advertencia: Migración de PostGIS no encontrada"
    echo "   Ejecuta: php artisan make:migration enable_postgis_extensions --create=0001_01_01_000003"
    echo ""
fi

echo "✅ Migraciones regeneradas exitosamente"
echo ""
echo "📋 Resumen:"
echo "   - Tablas procesadas: $TABLE_COUNT"
echo "   - Migraciones creadas: $((COUNTER - 1))"
echo "   - Timestamps normalizados: Sí (2024_01_01_xxxxxx)"
echo ""
echo "Para aplicar las migraciones en base de datos de pruebas:"
echo "  php artisan migrate:fresh --database=pruebas"
echo ""
echo "Para ver diferencias en Git:"
echo "  git diff database/migrations/"
echo ""

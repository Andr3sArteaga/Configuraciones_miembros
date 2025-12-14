#!/bin/bash

# Salir si algún comando falla
set -e

# Crear .env si no existe
if [ ! -f .env ]; then
    echo "📄 No existe .env — creando desde .env.example"
    cp .env.example .env
else
    echo "✔️ Archivo .env ya existe — no se copia"
fi

echo "🔑 Generando APP_KEY (si no existe)..."
php artisan key:generate --force || true

echo "🔄 Regenerando autoload con scripts..."
composer dump-autoload --optimize

echo "⚙️ Aplicando permisos..."
chmod -R 777 storage bootstrap/cache

echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force || true

echo "🌱 Ejecutando Seeder..."
php artisan db:seed --force || true

echo "🚀 Iniciando PHP-FPM..."
exec php-fpm

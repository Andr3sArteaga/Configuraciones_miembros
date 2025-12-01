# Database Seeders - Guía de Uso

Este documento describe cómo usar los seeders creados para poblar la base de datos del sistema de bomberos.

## Seeders Creados

### 1. Datos Paramétricos

Los siguientes seeders poblan las tablas con datos de configuración inicial:

-   **TiposSangreSeeder**: Tipos de sangre (A+, A-, B+, B-, AB+, AB-, O+, O-)
-   **NivelesEntrenamientoSeeder**: Niveles de entrenamiento (Básico, Intermedio, Avanzado, Experto)
-   **GenerosSeeder**: Géneros (Masculino, Femenino, Otro, Prefiero no decir)
-   **RolesSeeder**: Roles del sistema (Admin, Coordinador, Bombero, Paramédico, Veterinario)
-   **TiposIncidenteSeeder**: Tipos de incidentes (Forestal, Estructural, Vehicular, Industrial, Otro)
-   **NivelesGravedadSeeder**: Niveles de gravedad (Leve, Moderado, Grave, Crítico)
-   **TiposRecursoSeeder**: Tipos de recursos (Agua, Retardante, Combustible, etc.)
-   **EstadosSistemaSeeder**: Estados del sistema para diferentes entidades (usuarios, equipos, reportes, recursos)
-   **CondicionesClimaticasSeeder**: Condiciones climáticas (Soleado, Nublado, Lluvioso, etc.)

### 2. Usuario Administrador

-   **AdminUserSeeder**: Crea el usuario administrador por defecto
    -   **Email**: admin@sistema.com
    -   **Contraseña**: 12345678
    -   **Rol**: Administrador
    -   **Estado**: Activo

## Comandos de Ejecución

### Ejecutar todos los seeders

```bash
php artisan db:seed
```

Este comando ejecutará todos los seeders en el orden correcto definido en `DatabaseSeeder.php`.

### Ejecutar un seeder específico

```bash
php artisan db:seed --class=TiposSangreSeeder
php artisan db:seed --class=AdminUserSeeder
# etc.
```

### Refrescar la base de datos y ejecutar seeders

```bash
php artisan migrate:fresh --seed
```

**⚠️ ADVERTENCIA**: Este comando eliminará TODOS los datos de la base de datos y recreará las tablas.

## Orden de Ejecución

Es importante ejecutar los seeders en el siguiente orden:

1. **Primero**: Todos los seeders de datos paramétricos (el orden entre ellos no importa)

    - TiposSangreSeeder
    - NivelesEntrenamientoSeeder
    - GenerosSeeder
    - RolesSeeder
    - TiposIncidenteSeeder
    - NivelesGravedadSeeder
    - TiposRecursoSeeder
    - EstadosSistemaSeeder
    - CondicionesClimaticasSeeder

2. **Después**: AdminUserSeeder (depende de los datos paramétricos)

El `DatabaseSeeder.php` ya está configurado con este orden correcto.

## Notas Importantes

-   Todos los seeders usan `firstOrCreate()`, lo que significa que no duplicarán registros si ya existen.
-   Puedes ejecutar los seeders múltiples veces sin problemas.
-   El AdminUserSeeder validará que existan los datos paramétricos necesarios antes de crear el usuario.
-   Si un seeder falla, revisa que la tabla correspondiente exista en la base de datos.

## Verificación

Después de ejecutar los seeders, puedes verificar que los datos se hayan insertado correctamente:

```bash
# Conectarse a la base de datos
php artisan tinker

# Verificar tipos de sangre
\App\Models\TiposSangre::count();

# Verificar roles
\App\Models\Role::all();

# Verificar usuario admin
\App\Models\Usuario::where('email', 'admin@sistema.com')->first();
```

## Credenciales del Administrador

```
Email: admin@sistema.com
Contraseña: 12345678
```

**⚠️ IMPORTANTE**: Cambia esta contraseña en producción por razones de seguridad.

## Solución de Problemas

### Error: "No se encontraron los registros paramétricos necesarios"

Esto ocurre cuando intentas ejecutar `AdminUserSeeder` antes de los seeders paramétricos.

**Solución**: Ejecuta todos los seeders con `php artisan db:seed` que tiene el orden correcto.

### Error: "SQLSTATE[23505]: Unique violation"

Esto significa que ya existe un registro con la misma clave única.

**Solución**: Los seeders usan `firstOrCreate()` para evitar esto, pero si modificaste los seeders, verifica que estés usando este método correctamente.

### Error: "Base table or view not found"

Falta ejecutar las migraciones.

**Solución**:

```bash
php artisan migrate
php artisan db:seed
```

O bien, recrear todo:

```bash
php artisan migrate:fresh --seed
```

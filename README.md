# Sistema de Gestión de Brigadas de Bomberos - Alas Chiquitanas

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

Sistema integral de gestión para brigadas de bomberos voluntarios desarrollado con Laravel 11 y PostgreSQL con PostGIS. Permite la administración de reportes de incendios, equipos de respuesta, capacitación de bomberos y recursos de emergencia.

## 📋 Tabla de Contenidos
- [Características Principales](#características-principales)
- [Requisitos Previos](#requisitos-previos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Uso](#uso)
- [API Endpoints](#api-endpoints)
- [Despliegue a Producción](#despliegue-a-producción)
- [Tecnologías Utilizadas](#tecnologías-utilizadas)
- [Comandos Útiles](#comandos-útiles)
- [Solución de Problemas](#solución-de-problemas)

## ✨ Características Principales

### Gestión de Reportes
- **Reportes de Incendios**: Sistema público para reportar incendios con geolocalización
- **Reportes de Rescate Animal**: Integración con sistema externo para animales heridos
- **Focos de Calor NASA FIRMS**: Datos en tiempo real de focos de calor
- **Exportación**: Generación de reportes en PDF y CSV

### Gestión de Equipos
- **Despliegue de Brigadas**: Asignación de equipos a incendios
- **Seguimiento en Tiempo Real**: Visualización de ubicación de equipos
- **Gestión de Recursos**: Control de mochilas y equipamiento

### Sistema de Capacitación
- **Cursos de Entrenamiento**: Plataforma de cursos con etapas progresivas
- **Inscripciones**: Sistema de inscripción y seguimiento
- **Niveles de Entrenamiento**: Clasificación según capacitación

### Administración
- **Roles y Permisos**: Sistema basado en Spatie Laravel Permission
- **Autenticación API**: Tokens Sanctum para apps móviles
- **Notificaciones**: Sistema de notificaciones en tiempo real
- **API Gateway**: Integración con sistema de trazabilidad

## 🔧 Requisitos Previos

Antes de instalar el proyecto, asegúrate de tener instalado lo siguiente:

### Software Requerido
- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **PostgreSQL** >= 14
- **Git**

### Extensiones PHP Requeridas
```bash
php -m | grep -E 'pdo_pgsql|pgsql|gd|mbstring|xml|curl|zip'
```

### Verificar Instalaciones
```bash
# Verificar versiones
php --version
composer --version
node --version
npm --version
psql --version
```

## 📦 Instalación

### 1. Clonar el Repositorio
```bash
git clone <url-del-repositorio>
cd Configuraciones_miembros
```

### 2. Instalar Dependencias de PHP
```bash
composer install
```

### 3. Instalar Dependencias de Node.js
```bash
npm install
```

### 4. Configurar Variables de Entorno
```bash
# Copiar archivo de ejemplo
cp .env.example .env
```

Editar `.env` con tus configuraciones:
```env
APP_NAME="Sistema Bomberos"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=bomberos_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# Microservicios
ANIMAL_REPORTS_API_URL=http://10.26.13.235:8000
INVENTORY_API_URL=http://10.26.5.25:8000
HELPDESK_API_URL=https://proyecto-de-ultimo-minuto.online
HELPDESK_API_KEY=tu-api-key-aqui
```

### 5. Generar Clave de Aplicación
```bash
php artisan key:generate
```

### 6. Crear la Base de Datos
```sql
-- En PostgreSQL
CREATE DATABASE bomberos_db;
\c bomberos_db
CREATE EXTENSION IF NOT EXISTS postgis;
```

### 7. Ejecutar Migraciones
```bash
php artisan migrate
```

### 8. Ejecutar Seeders (Datos Iniciales)
```bash
# Ejecutar todos los seeders
php artisan db:seed

# O ejecutar seeders específicos
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=TiposIncidenteSeeder
php artisan db:seed --class=NivelesGravedadSeeder
```

### 9. Crear Enlaces Simbólicos para Storage
```bash
php artisan storage:link
```

### 10. Compilar Assets
```bash
# Para desarrollo
npm run dev

# Para producción
npm run build
```

## 🚀 Uso

### Iniciar el Servidor de Desarrollo
```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

### Iniciar Vite (para desarrollo con hot-reload)
```bash
npm run dev
```

### Acceder al Sistema
**Credenciales por defecto:**
- Email: `admin@sistema.com`
- Password: `12345678`

> ⚠️ **Importante**: Cambia estas credenciales en producción

## ⚙️ Configuración

### Configuración de Sesiones
El sistema utiliza sesiones en base de datos:
```bash
php artisan session:table
php artisan migrate
```

### Limpiar Caché
```bash
# Limpiar todo el caché
php artisan optimize:clear

# O limpiar específicamente
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

## 📁 Estructura del Proyecto

```
Configuraciones_miembros/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Controladores
│   │   │   ├── Api/              # Controladores API
│   │   │   └── Auth/             # Autenticación
│   │   └── Middleware/           # Middleware
│   ├── Models/                   # Modelos Eloquent
│   └── Services/                 # Lógica de negocio
├── config/                       # Configuración
├── database/
│   ├── migrations/               # Migraciones
│   └── seeders/                  # Seeders
├── public/                       # Archivos públicos
│   ├── css/                      # Estilos
│   ├── js/                       # JavaScript
│   └── images/                   # Imágenes
├── resources/
│   ├── views/                    # Vistas Blade
│   │   ├── reportes/            # Vistas de reportes
│   │   ├── equipos/             # Vistas de equipos
│   │   ├── cursos/              # Vistas de cursos
│   │   └── layouts/             # Layouts
│   ├── js/                       # JavaScript fuente
│   └── css/                      # CSS fuente
├── routes/
│   ├── web.php                   # Rutas web
│   ├── api.php                   # Rutas API
│   └── console.php               # Comandos
├── storage/                      # Archivos generados
└── tests/                        # Tests
```

## 🔌 API Endpoints

### Autenticación

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "usuario@ejemplo.com",
  "password": "contraseña"
}
```

**Respuesta:**
```json
{
  "token": "1|abc123...",
  "user": {
    "id": "uuid",
    "nombre": "Juan",
    "apellido": "Pérez",
    "email": "usuario@ejemplo.com",
    "rol": {
      "id": "uuid",
      "nombre": "Bombero"
    }
  }
}
```

### Reportes (Públicos)

#### Listar Reportes
```http
GET /api/v1/reportes
```

#### Crear Reporte de Incendio
```http
POST /api/v1/reportes
Content-Type: application/json

{
  "titulo": "Incendio forestal",
  "descripcion": "Descripción del incendio",
  "latitud": -17.7833,
  "longitud": -63.1821,
  "tipo_incidente_id": "uuid",
  "gravedad_id": "uuid"
}
```

#### Obtener Tipos de Incidente
```http
GET /api/v1/tipo-incidentes
```

**Respuesta:**
```json
[
  {
    "id": "e9495982-1791-4769-aa2d-6ca10f77140f",
    "codigo": "FORESTAL",
    "nombre": "Forestal",
    "descripcion": "Incendio en áreas forestales",
    "color": "#228B22",
    "icono": "tree"
  }
]
```

#### Obtener Niveles de Gravedad
```http
GET /api/v1/gravedades
```

**Respuesta:**
```json
[
  {
    "id": "b53cca5b-f443-4534-a9ab-14324d9312aa",
    "codigo": "LEVE",
    "nombre": "Controlado",
    "descripcion": "Situación controlable",
    "orden": 1,
    "color": "#90EE90"
  }
]
```

### Focos de Calor NASA FIRMS

#### Obtener Focos de Calor
```http
GET /api/v1/hotspots
```

#### Estadísticas
```http
GET /api/v1/hotspots/stats
```

#### Datos en Vivo
```http
GET /api/v1/hotspots/live
```

### Equipos (Requiere Autenticación)

#### Listar Equipos
```http
GET /api/equipos
Authorization: Bearer {token}
```

#### Equipos Desplegados
```http
GET /api/v1/equipos/desplegados
```

### Cursos

#### Listar Cursos
```http
GET /api/v1/cursos
```

#### Inscribirse a Curso
```http
POST /api/cursos/{id}/inscribirme
Authorization: Bearer {token}
```

#### Progreso del Curso
```http
GET /api/cursos/{curso}/progress
Authorization: Bearer {token}
```

### Notificaciones

#### Listar Notificaciones
```http
GET /api/notifications
Authorization: Bearer {token}
```

#### Marcar como Leída
```http
POST /api/notifications/{notification}/read
Authorization: Bearer {token}
```

#### Contador de No Leídas
```http
GET /api/notifications/unread-count
Authorization: Bearer {token}
```

## 🚀 Despliegue a Producción

### Preparación del Servidor

#### 1. Configurar Variables de Entorno
```bash
# Editar .env en servidor
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com
```

#### 2. Instalar Dependencias
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

#### 3. Optimizar Aplicación
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

#### 4. Ejecutar Migraciones
```bash
php artisan migrate --force
```

#### 5. Ejecutar Seeders (Primera vez)
```bash
php artisan db:seed --force
```

#### 6. Configurar Permisos
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Actualización con Git

```bash
# Conectar al servidor
ssh usuario@tu-servidor.com
cd /ruta/a/tu/proyecto

# Obtener últimos cambios
git pull origin main

# Actualizar dependencias
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Ejecutar migraciones
php artisan migrate --force

# Optimizar
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Reiniciar servicios
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

### Configuración de Nginx

```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /ruta/a/tu/proyecto/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### SSL con Certbot
```bash
sudo certbot --nginx -d tu-dominio.com
```

## 🛠️ Tecnologías Utilizadas

### Backend
- **Laravel 11** - Framework PHP
- **PostgreSQL 14+** - Base de datos con PostGIS
- **Sanctum** - Autenticación API
- **Spatie Laravel Permission** - Roles y permisos

### Frontend
- **Blade Templates** - Motor de plantillas
- **Bootstrap 5** - Framework CSS
- **AdminLTE 3** - Template de administración
- **Leaflet.js** - Mapas interactivos
- **Chart.js** - Gráficos
- **Select2** - Selectores mejorados
- **DataTables** - Tablas interactivas

### Herramientas
- **Vite** - Build tool
- **Composer** - Gestor de dependencias PHP
- **NPM** - Gestor de dependencias JavaScript
- **Docker** - Contenedorización

### APIs Externas
- **NASA FIRMS API** - Focos de calor
- **Microservicio de Inventario** - Gestión de recursos
- **Microservicio de Reportes Animales** - Rescate animal

## 📝 Comandos Útiles

### Artisan Commands

```bash
# Ver lista de rutas
php artisan route:list

# Ver rutas API
php artisan route:list --path=api

# Crear controlador
php artisan make:controller NombreController

# Crear modelo con migración
php artisan make:model NombreModelo -m

# Crear seeder
php artisan make:seeder NombreSeeder

# Limpiar caché
php artisan optimize:clear
```

### Base de Datos

```bash
# Ejecutar migraciones
php artisan migrate

# Revertir última migración
php artisan migrate:rollback

# Refrescar base de datos
php artisan migrate:refresh

# Refrescar con seeders
php artisan migrate:refresh --seed

# Ejecutar seeder específico
php artisan db:seed --class=NombreSeeder
```

### Desarrollo

```bash
# Iniciar servidor
php artisan serve

# Compilar assets (desarrollo)
npm run dev

# Compilar assets (producción)
npm run build

# Watch mode
npm run watch
```

## 🐛 Solución de Problemas

### Error de permisos en storage/
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Error de conexión a base de datos
1. Verificar que PostgreSQL esté corriendo
2. Verificar credenciales en `.env`
3. Verificar que la extensión PostGIS esté instalada:
```sql
CREATE EXTENSION IF NOT EXISTS postgis;
```

### El CSS/JS no se carga
```bash
# Limpiar caché
php artisan optimize:clear

# Recompilar assets
npm run build

# Verificar enlace simbólico
php artisan storage:link
```

### Error 419 (CSRF Token Mismatch)
```bash
# Limpiar caché de configuración
php artisan config:clear

# Verificar que SESSION_DRIVER esté en 'database'
# Ejecutar migración de sesiones
php artisan session:table
php artisan migrate
```

### Error de migraciones
```bash
# Refrescar migraciones
php artisan migrate:fresh

# Con seeders
php artisan migrate:fresh --seed
```

## 📚 Documentación Adicional

- [Docker.md](Docker.md) - Guía de Docker
- [MIGRATIONS.md](MIGRATIONS.md) - Documentación de migraciones
- [SEEDERS.md](SEEDERS.md) - Documentación de seeders
- [database.md](database.md) - Esquema de base de datos

## 📧 Contacto y Soporte

Para reportar problemas o solicitar nuevas funcionalidades, contacta al equipo de desarrollo.

## 📄 Licencia

Este proyecto es software propietario desarrollado para Alas Chiquitanas.

---

**Desarrollado con ❤️ para las Brigadas de Bomberos Voluntarios**

## Running This Project with Docker

This project includes a Docker setup for local development and testing. The provided `Dockerfile` and `docker-compose.yml` configure a PHP 8.2 FPM environment with Composer dependencies and support for SQLite (default) or MySQL (optional).

### Requirements
- Docker (latest version recommended)
- Docker Compose (v2 or higher)

### Services and Ports
- **php-app**: PHP 8.2 FPM (exposes port `9000`)
  - For use with a web server (e.g., nginx) or debugging
- **mysql-db** (optional): MySQL database (exposes port `3306`)
- **redis** (optional): Redis cache (exposes port `6379`)

### Environment Variables
- The application uses a `.env` file for configuration. Copy `.env.example` to `.env` and adjust as needed before starting the containers.
- The `php-app` service loads environment variables from your local `.env` file.
- If using MySQL or Redis, ensure your `.env` is configured to match the service settings in `docker-compose.yml` (see commented examples in the compose file).

### Build and Run Instructions
1. **Copy the example environment file:**
   ```sh
   cp .env.example .env
   # Edit .env as needed
   ```
2. **Build and start the containers:**
   ```sh
   docker compose up --build
   ```
   This will build the PHP application image and start the `php-app` service. By default, only the PHP-FPM service is enabled. To enable MySQL or Redis, uncomment the relevant sections in `docker-compose.yml` and update your `.env` accordingly.

3. **Accessing the Application:**
   - The PHP-FPM service listens on port `9000`. To serve the application in a browser, you will need to set up a web server (e.g., nginx) that connects to the `php-app` container's FPM port.
   - For CLI access, you can run commands inside the container:
     ```sh
     docker compose exec php-app php artisan migrate
     ```

### Special Configuration
- The Dockerfile ensures `storage` and `bootstrap/cache` are writable by the application user.
- Composer dependencies are installed in a multi-stage build for optimized image size.
- Xdebug is installed for debugging support.
- SQLite is available by default; for MySQL, enable the `mysql-db` service and update your `.env`.

For further customization, review the `docker-compose.yml` and `Dockerfile` for additional options and service configuration.

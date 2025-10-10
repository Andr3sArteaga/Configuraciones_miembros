# syntax=docker/dockerfile:1

# --- Builder stage ---
FROM composer:2.7 AS vendor
WORKDIR /app
# Copy only composer files for dependency install
COPY --link composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# --- Final stage ---
FROM php:8.2-fpm-alpine AS final

# Install system dependencies
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    zlib-dev \
    oniguruma-dev \
    curl \
    bash \
    sqlite-libs \
    && docker-php-ext-install intl pdo pdo_mysql pdo_sqlite zip opcache

# Install additional PHP extensions required by Laravel
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

# Create non-root user
RUN addgroup -S appgroup && adduser -S appuser -G appgroup

WORKDIR /app

# Copy application code, excluding files via .dockerignore
COPY --link . .

# Copy vendor from builder stage
COPY --link --from=vendor /app/vendor ./vendor

# Ensure storage and bootstrap/cache are writable
RUN chown -R appuser:appgroup storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER appuser

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

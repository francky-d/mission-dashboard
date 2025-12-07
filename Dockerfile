# ================================
# Stage 1: Build Frontend Assets
# ================================
FROM node:20-alpine AS frontend

WORKDIR /app

# Copy package files
COPY package.json package-lock.json* ./

# Install dependencies
RUN npm ci --prefer-offline --no-audit

# Copy source files needed for build
COPY resources ./resources
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./

# Build frontend assets
RUN npm run build


# ================================
# Stage 2: Install PHP Dependencies
# ================================
FROM composer:2 AS composer

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies without dev packages
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-autoloader \
    --prefer-dist

# Copy application source
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev


# ================================
# Stage 3: Production Image
# ================================
FROM php:8.4-fpm-alpine AS production

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    pgsql \
    zip \
    gd \
    intl \
    mbstring \
    opcache \
    pcntl \
    bcmath \
    && pecl install redis \
    && docker-php-ext-enable redis

# Configure PHP
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-production.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Configure Nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Configure Supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Copy application from composer stage
COPY --from=composer /app .

# Copy built frontend assets
COPY --from=frontend /app/public/build ./public/build

# Create necessary directories
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    && chmod -R 775 \
    storage \
    bootstrap/cache

# Create storage link script (will run at container start)
RUN echo '#!/bin/sh\nphp artisan storage:link --force 2>/dev/null || true' > /usr/local/bin/storage-link.sh \
    && chmod +x /usr/local/bin/storage-link.sh

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

# Start script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]

# ============================================
# Stage 1 - PHP + Composer
# ============================================
FROM php:8.3-cli AS composer

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        zip \
        intl \
        pdo_mysql \
        bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-scripts \
    --optimize-autoloader

COPY . .

RUN composer dump-autoload --optimize --classmap-authoritative

# ============================================
# Stage 2 - Frontend Build (Vite)
# ============================================
FROM node:22-alpine AS node

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# ============================================
# Stage 3 - Production Runtime
# ============================================
FROM webdevops/php-nginx:8.3

ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_MEMORY_LIMIT=512M
ENV PHP_POST_MAX_SIZE=100M
ENV PHP_UPLOAD_MAX_FILESIZE=100M

WORKDIR /app

# Copy application
COPY . .

# Copy vendor từ Composer stage
COPY --from=composer /app/vendor ./vendor

# Copy frontend build
COPY --from=node /app/public/build ./public/build

# Storage
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

RUN chown -R application:application storage bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache

# Laravel cache (không fail nếu APP_KEY chưa có)
RUN php artisan storage:link || true
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

EXPOSE 80
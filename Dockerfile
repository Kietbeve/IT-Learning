# ============================================
# Stage 1 - Composer (PHP + extensions)
# ============================================
FROM php:8.3-cli AS composer

RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        bcmath \
        intl \
        zip \
        gd \
        dom \
        xml

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# ⚠️ COPY FULL SOURCE TRƯỚC (fix lỗi artisan + scripts)
COPY . .

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader \
    --no-scripts

RUN composer dump-autoload --optimize --classmap-authoritative


# ============================================
# Stage 2 - Node (Vite build)
# ============================================
FROM node:22-alpine AS node

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .

# ⚠️ QUAN TRỌNG: cần vendor để resolve wireui + powergrid
COPY --from=composer /app/vendor ./vendor

RUN npm run build


# ============================================
# Stage 3 - Runtime (Laravel)
# ============================================
# FROM webdevops/php-nginx:8.3

# ENV WEB_DOCUMENT_ROOT=/app/public

# WORKDIR /app

# COPY . .

# COPY --from=composer /app/vendor ./vendor
# COPY --from=node /app/public/build ./public/build

# RUN mkdir -p \
#     storage/framework/cache \
#     storage/framework/sessions \
#     storage/framework/views \
#     storage/logs

# RUN chown -R application:application storage bootstrap/cache
# RUN chmod -R 775 storage bootstrap/cache

# RUN php artisan storage:link || true

# EXPOSE 80

FROM webdevops/php-nginx:8.3

ENV WEB_DOCUMENT_ROOT=/app/public

WORKDIR /app

COPY . .

COPY --from=composer /app/vendor ./vendor
COPY --from=node /app/public/build ./public/build

# RUN mkdir -p \
#     storage/framework/cache \
#     storage/framework/sessions \
#     storage/framework/views \
#     storage/logs

# RUN chown -R application:application storage bootstrap/cache
# RUN chmod -R 775 storage bootstrap/cache

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    && chmod -R 777 storage bootstrap/cache \
    && chmod -R 777 storage/logs \
    && touch storage/logs/laravel.log \
    && chmod 666 storage/logs/laravel.log

RUN php artisan storage:link || true

# # 🔥 QUAN TRỌNG
# USER application

EXPOSE 80

COPY docker/entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["supervisord"]

EXPOSE 80
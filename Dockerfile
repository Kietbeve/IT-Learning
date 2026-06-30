# ============================================
# Stage 1: Build Frontend Assets with Node
# ============================================
FROM node:20-alpine AS node-builder

# Set working directory
WORKDIR /app

# Copy package files
COPY package*.json ./

# Install Node dependencies
# Using --production=false to include devDependencies (needed for Vite build)
RUN npm ci --production=false

# Copy all source files needed for Vite build
# Includes resources/, public/, config files, Blade templates (for Tailwind content scan)
COPY . .

# Ensure public/build directory exists
RUN mkdir -p public/build

# Build Vite assets NOW in Node stage (before PHP stage)
# Vite needs source code and Blade templates, but NOT composer packages
RUN npm run build

# ============================================
# Stage 2: PHP Runtime with Composer
# ============================================
FROM php:8.2-cli-alpine

# Install system dependencies and PHP extensions
# These are essential Laravel extensions
RUN apk add --no-cache \
    # Core utilities
    bash \
    curl \
    git \
    unzip \
    # Image processing
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    # Database
    mysql-client \
    # String processing
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    # Process control
    linux-headers \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mysqli \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && apk del linux-headers

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for better layer caching)
COPY composer.json composer.lock ./

# Install PHP dependencies
# --no-dev: Skip development dependencies
# --optimize-autoloader: Optimize class autoloader for production
# --no-interaction: Run in non-interactive mode
# --prefer-dist: Prefer distribution packages over source
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist \
    --no-scripts

# Copy application code
COPY . .

# Copy built frontend assets from node-builder stage
# This includes the manifest.json and all compiled JS/CSS
COPY --from=node-builder /app/public/build ./public/build

# Set correct permissions for Laravel directories
# storage: Logs, cache, sessions, uploaded files
# bootstrap/cache: Framework cache files
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    && chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Run post-install scripts (if any in composer.json)
RUN composer run-script post-install-cmd --no-interaction

# Optimize Laravel for production
# config:cache - Cache all config files into single file
# route:cache - Cache routes for faster route registration
# view:cache - Compile all Blade templates
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Configure PHP for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# Set recommended PHP settings for production
RUN echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize=50M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size=50M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/custom.ini

# Expose port (Render will set $PORT environment variable)
# Default to 8080 if $PORT is not set
EXPOSE 8080

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

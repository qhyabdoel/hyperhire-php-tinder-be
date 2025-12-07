# Use official PHP with extensions we need for Laravel
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_pgsql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Laravel files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate optimized Laravel cache (optional)
RUN php artisan config:clear || true

EXPOSE 8080

# Start Laravel with php artisan serve
CMD php artisan serve --host=0.0.0.0 --port=8080
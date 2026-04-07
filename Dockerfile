FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd zip intl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Set environment variables for non-interactive composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

# Setup Database BEFORE composer install (to avoid discovery errors)
RUN mkdir -p database && \
    touch database/database.sqlite && \
    chmod 666 database/database.sqlite

# Set document root to public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Build process (override drivers to avoid DB boot issues during discovery)
RUN SESSION_DRIVER=array \
    CACHE_STORE=array \
    QUEUE_CONNECTION=sync \
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader -vvv

# Run migrations and seed data
RUN php artisan migrate --force && \
    php artisan db:seed --class=ProtJundSeeder --force && \
    php artisan db:seed --class=ServiceSeeder --force && \
    php artisan key:generate

EXPOSE 80

CMD ["apache2-foreground"]

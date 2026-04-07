FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Set document root to public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Build process
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Setup Database
RUN mkdir -p database
RUN touch database/database.sqlite
RUN chmod 666 database/database.sqlite
RUN php artisan migrate --force
RUN php artisan db:seed --class=ProtJundSeeder --force
RUN php artisan db:seed --class=ServiceSeeder --force

# Key generation (fallback if not provided)
RUN php artisan key:generate

EXPOSE 80

CMD ["apache2-foreground"]

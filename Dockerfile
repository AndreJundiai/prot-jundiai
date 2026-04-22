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

# Set environment variables for non-interactive composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

# Cache composer dependencies
COPY composer.json composer.lock* ./

# Install dependencies WITHOUT the rest of the code (to ensure clean environment)
# Using --no-scripts, --no-plugins and --ignore-platform-reqs for maximum stability during build
RUN SESSION_DRIVER=array CACHE_STORE=array QUEUE_CONNECTION=sync \
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts --no-plugins --ignore-platform-reqs -vvv

# Copy the rest of the application
COPY . .

# Setup Database
RUN mkdir -p database && \
    touch database/database.sqlite && \
    chmod 666 database/database.sqlite

# Set document root to public and enable AllowOverride for .htaccess
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    sed -ri -e '/<Directory \/var\/www\/html\/public>/,/<\/Directory>/{s/AllowOverride None/AllowOverride All/}' \
    /etc/apache2/apache2.conf || true && \
    printf '<Directory /var/www/html/public>\n\tOptions Indexes FollowSymLinks\n\tAllowOverride All\n\tRequire all granted\n</Directory>\n' \
    >> /etc/apache2/apache2.conf

# Permissions will be set at the end

# Run discovery manually now that the code is copied
RUN SESSION_DRIVER=array CACHE_STORE=array QUEUE_CONNECTION=sync \
    php artisan package:discover --ansi

# Run migrations
RUN php artisan migrate --force -v

# Run seeders
RUN php artisan db:seed --force -v

# Generate key
RUN cp .env.example .env && php artisan key:generate -v

# Fix permissions for all files created during build
RUN chown -R www-data:www-data /var/www/html

# Copy start script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]

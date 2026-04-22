#!/usr/bin/env bash
# exit on error
set -o errexit

echo "--- Starting Build Process ---"

# Install dependencies
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Ensure database exists
mkdir -p database
touch database/database.sqlite

# Run migrations and seeders
echo "--- Running Migrations ---"
php artisan migrate --force

# Seed data
echo "--- Seeding Data ---"
php artisan db:seed --force

echo "--- Build Finished ---"

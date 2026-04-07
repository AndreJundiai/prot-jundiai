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

# Seed demo data
echo "--- Seeding Demo Data ---"
php artisan db:seed --class=ProtJundSeeder --force
php artisan db:seed --class=ServiceSeeder --force

echo "--- Build Finished ---"

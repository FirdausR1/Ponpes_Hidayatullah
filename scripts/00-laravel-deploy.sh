#!/usr/bin/env bash
echo "Setting storage permissions..."
mkdir -p /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/storage/framework/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Running composer..."
composer install --no-dev --working-dir=/var/www/html

echo "Linking storage..."
php artisan storage:link --force

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

echo "Deployment script finished successfully!"

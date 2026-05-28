#!/usr/bin/env bash

echo "Running pre-flight checks and setup..."

# Clear Laravel caches to ensure fresh config is loaded
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Force database migrations to run automatically on start
echo "Migrating database..."
php artisan migrate --force

echo "Setup complete."

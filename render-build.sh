#!/usr/bin/env bash

echo "Running pre-flight checks and setup..."

# Explicitly create an .env file from Render's runtime environment variables
# This guarantees Laravel reads them even if the container drops privileges
echo "APP_NAME=\"${APP_NAME}\"" > /app/.env
echo "APP_ENV=\"${APP_ENV}\"" >> /app/.env
echo "APP_KEY=\"${APP_KEY}\"" >> /app/.env
echo "APP_INSTALLED=\"${APP_INSTALLED}\"" >> /app/.env
echo "APP_DEBUG=\"${APP_DEBUG}\"" >> /app/.env
echo "APP_URL=\"${APP_URL}\"" >> /app/.env
echo "DB_CONNECTION=\"${DB_CONNECTION}\"" >> /app/.env
echo "DB_HOST=\"${DB_HOST}\"" >> /app/.env
echo "DB_PORT=\"${DB_PORT}\"" >> /app/.env
echo "DB_DATABASE=\"${DB_DATABASE}\"" >> /app/.env
echo "DB_USERNAME=\"${DB_USERNAME}\"" >> /app/.env
echo "DB_PASSWORD=\"${DB_PASSWORD}\"" >> /app/.env
echo "QUEUE_CONNECTION=\"${QUEUE_CONNECTION}\"" >> /app/.env
echo "CACHE_DRIVER=\"${CACHE_DRIVER}\"" >> /app/.env
echo "SESSION_DRIVER=\"${SESSION_DRIVER}\"" >> /app/.env
echo "AI_PROVIDER=\"${AI_PROVIDER}\"" >> /app/.env

# Fix permissions on cache and .env
chmod 644 /app/.env
chmod -R 777 /app/bootstrap/cache
chmod -R 777 /app/storage/framework/cache

# Clear Laravel caches to ensure fresh config is loaded
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Force database migrations to run automatically on start
echo "Migrating database..."
php artisan migrate --force

echo "Setup complete."

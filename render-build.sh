#!/usr/bin/env bash

echo "Running pre-flight checks and setup..."

# Explicitly create an .env file from Render's runtime environment variables
# Using fallback syntax (:-"") prevents "unbound variable" errors in strict mode
echo "APP_NAME=\"${APP_NAME:-"Apex Accounting"}\"" > /app/.env
echo "APP_ENV=\"${APP_ENV:-"production"}\"" >> /app/.env
echo "APP_KEY=\"${APP_KEY:-""}\"" >> /app/.env
echo "APP_INSTALLED=\"${APP_INSTALLED:-"true"}\"" >> /app/.env
echo "APP_DEBUG=\"${APP_DEBUG:-"false"}\"" >> /app/.env
echo "APP_URL=\"${APP_URL:-"https://apex-accounting-web.onrender.com"}\"" >> /app/.env
echo "DB_CONNECTION=\"${DB_CONNECTION:-"pgsql"}\"" >> /app/.env
echo "DB_HOST=\"${DB_HOST:-""}\"" >> /app/.env
echo "DB_PORT=\"${DB_PORT:-"5432"}\"" >> /app/.env
echo "DB_DATABASE=\"${DB_DATABASE:-""}\"" >> /app/.env
echo "DB_USERNAME=\"${DB_USERNAME:-""}\"" >> /app/.env
echo "DB_PASSWORD=\"${DB_PASSWORD:-""}\"" >> /app/.env
echo "QUEUE_CONNECTION=\"${QUEUE_CONNECTION:-"database"}\"" >> /app/.env
echo "CACHE_DRIVER=\"${CACHE_DRIVER:-"file"}\"" >> /app/.env
echo "SESSION_DRIVER=\"${SESSION_DRIVER:-"file"}\"" >> /app/.env
echo "AI_PROVIDER=\"${AI_PROVIDER:-"gemini"}\"" >> /app/.env

# Fix permissions on cache and .env
chmod 644 /app/.env
chmod -R 777 /app/bootstrap/cache
chmod -R 777 /app/storage/framework/cache || true

# Clear Laravel caches to ensure fresh config is loaded
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# Force database migrations to run automatically on start
echo "Migrating database..."
php artisan migrate --force

echo "Setup complete."

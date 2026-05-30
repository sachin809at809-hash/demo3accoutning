#!/usr/bin/env bash

echo "Running pre-flight checks and setup..."

# 1. Generate a valid Laravel AES-256-CBC key from the Render generated key
# If the Render key is already base64 encoded, we just ensure it has the base64: prefix.
# Otherwise, we hash the string deterministically to a 32-byte binary and base64 encode it.
export RENDER_APP_KEY="${APP_KEY:-"fallback_default_secret_key_1234"}"
VALID_APP_KEY=$(php -r "
    \$key = getenv('RENDER_APP_KEY');
    if (strpos(\$key, 'base64:') === 0) {
        echo \$key;
    } else {
        echo 'base64:' . base64_encode(hash('sha256', \$key, true));
    }
")

# 2. Rebuild the .env file function
# Create a symlink so hardcoded '/public/...' asset URLs resolve correctly when the document root is secured
ln -sf . /app/public/public

# Ensure a basic manifest.json exists so the IdentifyCompany middleware doesn't crash on it
if [ ! -f /app/public/manifest.json ]; then
    echo '{"name":"Apex Accounting","display":"standalone"}' > /app/public/manifest.json
fi

generate_env() {
    echo "APP_NAME=\"${APP_NAME:-"Apex Accounting"}\"" > /app/.env
    echo "APP_ENV=\"${APP_ENV:-"production"}\"" >> /app/.env
    echo "APP_KEY=\"${VALID_APP_KEY}\"" >> /app/.env
    echo "APP_INSTALLED=\"true\"" >> /app/.env
    echo "APP_DEBUG=\"true\"" >> /app/.env
    echo "APP_URL=\"${RENDER_EXTERNAL_URL:-${APP_URL:-"https://apex-accounting-web.onrender.com"}}\"" >> /app/.env
    echo "DB_CONNECTION=\"${DB_CONNECTION:-"pgsql"}\"" >> /app/.env
    echo "DB_HOST=\"${DB_HOST:-""}\"" >> /app/.env
    echo "DB_PORT=\"${DB_PORT:-"5432"}\"" >> /app/.env
    echo "DB_DATABASE=\"${DB_DATABASE:-""}\"" >> /app/.env
    echo "DB_USERNAME=\"${DB_USERNAME:-""}\"" >> /app/.env
    echo "DB_PASSWORD=\"${DB_PASSWORD:-""}\"" >> /app/.env
    echo "DB_PREFIX=\"ak_\"" >> /app/.env
    echo "QUEUE_CONNECTION=\"${QUEUE_CONNECTION:-"database"}\"" >> /app/.env
    echo "CACHE_DRIVER=\"${CACHE_DRIVER:-"file"}\"" >> /app/.env
    echo "SESSION_DRIVER=\"${SESSION_DRIVER:-"file"}\"" >> /app/.env
    echo "AI_PROVIDER=\"${AI_PROVIDER:-"gemini"}\"" >> /app/.env
    echo "LOG_CHANNEL=stderr" >> /app/.env
    chmod 644 /app/.env
}

# Generate it for the pre-flight checks
generate_env

chmod -R 777 /app/bootstrap/cache || true
chmod -R 777 /app/storage/framework/cache || true

# Clear caches
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# 3. Handle Database Installation / Migrations
if [ -z "${DB_HOST:-}" ]; then
    echo "ERROR: DB_HOST environment variable is not set!"
    echo "Please configure the database environment variables in your Render Dashboard."
    echo "You need: DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD."
    exit 1
fi

# Allow forced reset if the database was partially installed and corrupted
if [ "${RESET_DATABASE:-"false"}" == "true" ]; then
    echo "RESET_DATABASE=true detected! Wiping the database..."
    php artisan db:wipe --force || true
fi

# We check if the users table exists and has rows to determine if the app is already installed.
HAS_USERS=$(php -r "
    require 'vendor/autoload.php';
    \$app = require_once 'bootstrap/app.php';
    \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
    \$kernel->bootstrap();
    try {
        echo DB::table('users')->count();
    } catch (\Exception \$e) {
        echo '0';
    }
")

if [ "$HAS_USERS" == "0" ]; then
    echo "Database is empty. Running initial Akaunting installation..."
    php artisan install \
        --db-host="${DB_HOST}" \
        --db-port="${DB_PORT:-5432}" \
        --db-name="${DB_DATABASE}" \
        --db-username="${DB_USERNAME}" \
        --db-password="${DB_PASSWORD}" \
        --db-prefix="ak_" \
        --company-name="${APP_NAME:-Apex Accounting}" \
        --company-email="admin@example.com" \
        --admin-email="admin@example.com" \
        --admin-password="password" \
        --no-interaction

    # The installer command creates a new .env file and destroys all our custom variables!
    # We must regenerate the .env file to restore APP_URL, LOG_CHANNEL, APP_DEBUG, and our persistent APP_KEY.
    generate_env
    
    echo "Installation complete. Admin credentials: admin@example.com / password"
else
    echo "Database already installed. Running migrations..."
    php artisan migrate --force
fi

# Final cache clear to apply the restored APP_KEY
php artisan config:clear || true

# Fix permissions for all files created during the root artisan install
chown -R application:application /app/storage /app/bootstrap/cache
chmod -R 777 /app/storage /app/bootstrap/cache

echo "Setup complete. Nginx is starting..."

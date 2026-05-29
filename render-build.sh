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

# 2. Rebuild the .env file
# We completely rebuild the .env file on every container boot using the Render Env Vars.
echo "APP_NAME=\"${APP_NAME:-"Apex Accounting"}\"" > /app/.env
echo "APP_ENV=\"${APP_ENV:-"production"}\"" >> /app/.env
echo "APP_KEY=\"${VALID_APP_KEY}\"" >> /app/.env
echo "APP_INSTALLED=\"true\"" >> /app/.env
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

# Fix permissions
chmod 644 /app/.env
chmod -R 777 /app/bootstrap/cache || true
chmod -R 777 /app/storage/framework/cache || true

# Clear caches
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# 3. Handle Database Installation / Migrations
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
        --db-host="${DB_HOST:-""}" \
        --db-port="${DB_PORT:-5432}" \
        --db-name="${DB_DATABASE:-""}" \
        --db-username="${DB_USERNAME:-""}" \
        --db-password="${DB_PASSWORD:-""}" \
        --company-name="${APP_NAME:-Apex Accounting}" \
        --company-email="admin@example.com" \
        --admin-email="admin@example.com" \
        --admin-password="password" \
        --no-interaction

    # The installer command creates a new .env file and overrides our APP_KEY!
    # We must patch the .env file to restore our persistent VALID_APP_KEY.
    sed -i "s|^APP_KEY=.*|APP_KEY=\"${VALID_APP_KEY}\"|g" /app/.env
    sed -i "s|^APP_INSTALLED=.*|APP_INSTALLED=\"true\"|g" /app/.env
    
    echo "Installation complete. Admin credentials: admin@example.com / password"
else
    echo "Database already installed. Running migrations..."
    php artisan migrate --force
fi

# Final cache clear to apply the restored APP_KEY
php artisan config:clear || true

echo "Setup complete. Nginx is starting..."

#!/bin/bash
set -e

# Discover packages (skipped during build, runs here with proper env)
php artisan package:discover --ansi 2>/dev/null || true

echo "==> Waiting for database..."
until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    sleep 2
done
echo "==> Database is ready."

# Generate app key if missing
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "==> Generating APP_KEY..."
    php artisan key:generate --no-interaction --force
fi

# Cache for production
if [ "$APP_ENV" = "production" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Storage symlink (silently skip if already exists)
php artisan storage:link --no-interaction 2>/dev/null || true

exec "$@"

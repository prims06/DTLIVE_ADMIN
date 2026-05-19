#!/bin/bash
set -e

# Discover packages (skipped during build, runs here with proper env)
php artisan package:discover --ansi 2>/dev/null || true

echo "==> Waiting for database..."
until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    sleep 2
done
echo "==> Database is ready."

# Verify APP_KEY is set (must be pre-configured in .env)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "ERROR: APP_KEY is not set in .env — run this to generate one:"
    echo "  docker run --rm php:8.2-apache php -r \"echo 'base64:'.base64_encode(random_bytes(32));\""
    exit 1
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

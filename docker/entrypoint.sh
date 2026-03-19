#!/bin/sh
set -e

cd /var/www/html

# Generate key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations
php artisan migrate --force 2>/dev/null || true

# Seed in non-production if DB is empty
if [ "$APP_ENV" != "production" ]; then
    php artisan db:seed --force 2>/dev/null || true
fi

# Cache config in production
if [ "$APP_ENV" = "production" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

exec "$@"

#!/bin/sh
set -e

cd /var/www/html

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running database migrations..."
    if su -s /bin/sh www-data -c "php artisan migrate --isolated --force" 2>/dev/null; then
        echo "Migrations completed (isolated mode)."
    elif su -s /bin/sh www-data -c "php artisan migrate --force" 2>/dev/null; then
        echo "Migrations completed (standard mode)."
    else
        echo "WARNING: Migrations failed. Check database connection and re-run manually."
    fi
fi

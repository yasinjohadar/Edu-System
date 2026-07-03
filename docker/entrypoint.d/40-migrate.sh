#!/bin/sh
set -e

cd /var/www/html

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running database migrations..."
    su -s /bin/sh www-data -c "php artisan migrate --isolated --force"
fi

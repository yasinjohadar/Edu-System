#!/bin/sh
set -e

cd /var/www/html

if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is missing, generating one..."
    su -s /bin/sh www-data -c "php artisan key:generate --force"
fi

su -s /bin/sh www-data -c "php artisan config:clear"
su -s /bin/sh www-data -c "php artisan route:clear"
su -s /bin/sh www-data -c "php artisan view:clear"

su -s /bin/sh www-data -c "php artisan config:cache"
su -s /bin/sh www-data -c "php artisan route:cache"
su -s /bin/sh www-data -c "php artisan view:cache"
su -s /bin/sh www-data -c "php artisan event:cache"

echo "Application caches warmed."

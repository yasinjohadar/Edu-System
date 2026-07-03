#!/bin/sh
set -e

cd /var/www/html

su -s /bin/sh www-data -c "php artisan storage:link" || true

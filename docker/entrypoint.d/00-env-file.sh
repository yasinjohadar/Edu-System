#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
    chown www-data:www-data .env
fi

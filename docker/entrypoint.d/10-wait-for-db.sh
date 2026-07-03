#!/bin/sh
set -e

cd /var/www/html

if [ -n "$DB_HOST" ]; then
    echo "Waiting for database at $DB_HOST:${DB_PORT:-3306}..."

    until php -r "
        \$host = getenv('DB_HOST');
        \$port = (int) (getenv('DB_PORT') ?: '3306');
        \$errno = 0;
        \$errstr = '';
        \$conn = @fsockopen(\$host, \$port, \$errno, \$errstr, 1);
        if (\$conn) {
            fclose(\$conn);
            exit(0);
        }
        exit(1);
    "; do
        sleep 1
    done

    echo "Database is available."
fi

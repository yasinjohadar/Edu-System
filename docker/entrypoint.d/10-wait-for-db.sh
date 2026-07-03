#!/bin/sh
set -e

cd /var/www/html

if [ "${SKIP_DB_WAIT:-false}" = "true" ]; then
    echo "Skipping database wait (SKIP_DB_WAIT=true)."
    exit 0
fi

if [ -n "$DB_HOST" ]; then
    DB_WAIT_TIMEOUT="${DB_WAIT_TIMEOUT:-90}"
    echo "Waiting for database at $DB_HOST:${DB_PORT:-3306} (max ${DB_WAIT_TIMEOUT}s)..."

    elapsed=0
    while [ "$elapsed" -lt "$DB_WAIT_TIMEOUT" ]; do
        if php -r "
            \$host = getenv('DB_HOST');
            \$port = (int) (getenv('DB_PORT') ?: '3306');
            \$errno = 0;
            \$errstr = '';
            \$conn = @fsockopen(\$host, \$port, \$errno, \$errstr, 2);
            if (\$conn) {
                fclose(\$conn);
                exit(0);
            }
            exit(1);
        "; then
            echo "Database is available."
            exit 0
        fi

        sleep 2
        elapsed=$((elapsed + 2))
    done

    echo "WARNING: Database not reachable after ${DB_WAIT_TIMEOUT}s."
    echo "Continuing startup — migrations may fail until the database is linked and running."
fi

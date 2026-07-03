#!/bin/sh

set -e

echo "========================================"
echo "Laravel Entrypoint"
echo "========================================"

echo "Waiting for database..."

MAX_ATTEMPTS=30
ATTEMPT=1

while [ $ATTEMPT -le $MAX_ATTEMPTS ]
do
    if php artisan db:show >/dev/null 2>&1; then
        echo "✓ Database connected"
        break
    fi

    echo "Attempt $ATTEMPT/$MAX_ATTEMPTS - Database not ready..."
    ATTEMPT=$((ATTEMPT + 1))
    sleep 2
done

if [ $ATTEMPT -gt $MAX_ATTEMPTS ]; then
    echo "✗ Database connection failed"
    exit 1
fi

# echo "Running migrations..."
# php artisan migrate --force || echo "Migration failed, continuing..."

# if [ "${RUN_SEEDERS}" = "true" ]; then
#     echo "Running seeders..."
#     php artisan db:seed --force || echo "Seeder failed, continuing..."
# fi

# echo "Clearing caches..."
# php artisan optimize:clear || true

if [ "${RUN_FRESH_MIGRATION}" = "true" ]; then
    echo "Running migrate:fresh --seed..."
    php artisan migrate:fresh --seed --force || exit 1
else
    echo "Running migrate..."
    php artisan migrate --force || echo "Migration failed, continuing..."

    if [ "${RUN_SEEDERS}" = "true" ]; then
        echo "Running seeders..."
        php artisan db:seed --force || echo "Seeder failed, continuing..."
    fi
fi

echo "Creating storage link..."
php artisan storage:link || true

echo "Starting web server..."

exec /opt/docker/bin/entrypoint.sh "$@"
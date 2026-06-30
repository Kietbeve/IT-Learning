#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

echo "========================================"
echo "Laravel Docker Entrypoint"
echo "========================================"

# Function to check database connection
check_database_connection() {
    echo "Checking database connection..."
    
    # Maximum attempts to connect to database
    max_attempts=30
    attempt=0
    
    while [ $attempt -lt $max_attempts ]; do
        attempt=$((attempt + 1))
        echo "Database connection attempt $attempt/$max_attempts..."
        
        # Try to connect to database using Laravel's php artisan tinker
        if php artisan db:show > /dev/null 2>&1; then
            echo "✓ Database connection successful!"
            return 0
        fi
        
        if [ $attempt -lt $max_attempts ]; then
            echo "Database not ready yet, waiting 2 seconds..."
            sleep 2
        fi
    done
    
    echo "✗ ERROR: Could not connect to database after $max_attempts attempts"
    echo "Please check your database configuration:"
    echo "  - DB_HOST: ${DB_HOST}"
    echo "  - DB_PORT: ${DB_PORT}"
    echo "  - DB_DATABASE: ${DB_DATABASE}"
    echo "  - DB_USERNAME: ${DB_USERNAME}"
    return 1
}

# Function to run migrations
run_migrations() {
    echo "Running database migrations..."
    
    if php artisan migrate --force; then
        echo "✓ Migrations completed successfully!"
        return 0
    else
        echo "✗ ERROR: Migrations failed!"
        echo "This might be due to:"
        echo "  - Invalid database credentials"
        echo "  - Database schema conflicts"
        echo "  - Network connectivity issues"
        return 1
    fi
}

# Function to run seeders
run_seeders() {
    echo "Running database seeders..."
    
    # Check if RUN_SEEDERS env var is set to true
    if [ "${RUN_SEEDERS:-false}" = "true" ]; then
        if php artisan db:seed --force; then
            echo "✓ Seeders completed successfully!"
            return 0
        else
            echo "✗ WARNING: Seeders failed!"
            echo "Continuing anyway (seeders are optional)..."
            return 0
        fi
    else
        echo "⊘ Skipping seeders (set RUN_SEEDERS=true to enable)"
        return 0
    fi
}

# Main execution flow
echo "Step 1: Checking database connection..."
if ! check_database_connection; then
    echo "========================================"
    echo "FATAL: Cannot proceed without database"
    echo "========================================"
    exit 1
fi

echo ""
echo "Step 2: Running migrations..."
if ! run_migrations; then
    echo "========================================"
    echo "WARNING: Migrations failed but continuing..."
    echo "You may need to run migrations manually"
    echo "========================================"
    # Don't exit here - allow app to start even if migrations fail
    # This is useful for debugging connection issues
fi

echo ""
echo "Step 2.5: Running seeders (if enabled)..."
run_seeders

echo ""
echo "Step 3: Clearing application cache..."
php artisan cache:clear || echo "Warning: cache:clear failed (this is usually OK)"
php artisan config:clear || echo "Warning: config:clear failed (this is usually OK)"

echo ""
echo "Step 4: Starting Laravel application..."
echo "========================================"

# Get PORT from environment variable (Render sets this automatically)
# Default to 8080 if not set
PORT=${PORT:-8080}

echo "Starting server on 0.0.0.0:${PORT}"
echo "Environment: ${APP_ENV:-production}"
echo "========================================"

# Start PHP built-in server
# 0.0.0.0 allows external connections (required for Render)
exec php artisan serve --host=0.0.0.0 --port=${PORT}

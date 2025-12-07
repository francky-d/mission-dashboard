#!/bin/sh
set -e

echo "🚀 Starting Mission Dashboard..."

# Create log directories
mkdir -p /var/log/php /var/log/nginx /var/log/supervisor

# Wait for database to be ready
echo "⏳ Waiting for database connection..."
until php artisan db:monitor --max=1 2>/dev/null; do
    echo "Database is unavailable - sleeping"
    sleep 2
done
echo "✅ Database is ready!"

# Run migrations
echo "📦 Running database migrations..."
php artisan migrate --force --no-interaction

# Clear and cache configuration
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# Fix permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "✅ Application is ready!"
echo "🌐 Starting web server..."

# Start supervisor (manages nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

#!/bin/sh
set -e

# Dev bind-mount replaces image /var/www — vendor may be missing on first boot.
if [ ! -f /var/www/vendor/autoload.php ]; then
    echo "aviso: vendor/ ausente; pulando artisan clear (rode composer install)"
    exec "$@"
fi

# Clear configurations to avoid caching issues in development
echo "Clearing configurations..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run the default command (e.g., php-fpm or bash)
exec "$@"

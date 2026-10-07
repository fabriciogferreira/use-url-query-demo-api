#!/bin/sh
set -e

# Seed the shared public/build volume on first start.
# -----------------------------------------------------------
# php-fpm and nginx share laravel-public-assets so both see the
# same Vite manifest. Named volumes start empty and would hide
# the assets baked into this image unless we copy them in once.
# -----------------------------------------------------------
if [ -d /var/www/public-build-init ]; then
  if [ ! -f /var/www/public/build/manifest.json ] && [ ! -f /var/www/public/build/.vite/manifest.json ]; then
    echo "Initializing public/build assets volume..."
    mkdir -p /var/www/public/build
    cp -a /var/www/public-build-init/. /var/www/public/build/
  fi
fi

exec "$@"

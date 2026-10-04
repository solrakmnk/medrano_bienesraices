#!/bin/sh
set -eu
: "${APP_KEY:?Configura APP_KEY en las variables de Railway}"
PORT="${PORT:-8080}"
case "$PORT" in ''|*[!0-9]*) echo 'PORT debe ser numérico' >&2; exit 1;; esac
if [ "$PORT" -lt 1 ] || [ "$PORT" -gt 65535 ]; then
    echo 'PORT debe estar entre 1 y 65535' >&2
    exit 1
fi
printf 'Listen %s\n' "$PORT" > /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-available/000-default.conf
php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache
exec apache2-foreground

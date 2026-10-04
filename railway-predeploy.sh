#!/bin/sh
set -eu
php artisan migrate --force --no-interaction
if [ "${DEMO_SEED_ON_DEPLOY:-0}" = '1' ]; then
    php artisan db:seed --force --no-interaction
fi

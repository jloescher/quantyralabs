#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rwX storage bootstrap/cache

if [ ! -L public/storage ] && [ -d storage/app/public ]; then
    php artisan storage:link --force >/dev/null 2>&1 || true
fi

if [ -z "${APP_KEY:-}" ]; then
    echo "Coolify / runtime: set APP_KEY (e.g. php artisan key:generate --show and paste into secrets)." >&2
    exit 1
fi

if [ "${LARAVEL_SKIP_OPTIMIZE:-0}" != "1" ]; then
    php artisan optimize 2>/dev/null || {
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
    }
fi

exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf

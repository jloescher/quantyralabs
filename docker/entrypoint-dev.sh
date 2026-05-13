#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

if [[ -z "${CLOUDFLARED_TOKEN:-}" ]]; then
	echo "CLOUDFLARED_TOKEN must be set (Cloudflare tunnel token)." >&2
	exit 1
fi

if [[ ! -d vendor ]]; then
	echo "Installing Composer dependencies…"
	composer install --no-interaction --prefer-dist
fi

if [[ ! -f node_modules/.bin/vite ]]; then
	echo "Installing npm dependencies…"
	npm ci
fi

cleanup() {
	local p
	for p in "${VITE_PID:-}" "${LARAVEL_PID:-}" "${CADDY_PID:-}" "${CF_PID:-}"; do
		if [[ -n "${p}" ]] && kill -0 "${p}" 2>/dev/null; then
			kill "${p}" 2>/dev/null || true
		fi
	done
}

trap cleanup EXIT INT TERM

rm -f public/hot

echo "Starting Vite (127.0.0.1:5173)…"
npm run dev -- --host 127.0.0.1 --port 5173 --strictPort &
VITE_PID=$!

echo "Starting Laravel (127.0.0.1:8000)…"
php artisan serve --host=127.0.0.1 --port=8000 &
LARAVEL_PID=$!

sleep 2

echo "Starting Caddy (:8080)…"
caddy run --config /etc/caddy/Caddyfile.dev --adapter caddyfile &
CADDY_PID=$!

echo "Starting cloudflared…"
cloudflared tunnel --no-autoupdate run --token "${CLOUDFLARED_TOKEN}" &
CF_PID=$!

wait -n
exit $?

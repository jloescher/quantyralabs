# Quantyra Labs - Claude Development Guide

## Project Overview

Quantyra Labs LLC is the corporate website for **https://quantyralabs.cc** — a Florida-based company that builds **SaaS products** and supporting software infrastructure. The site is a marketing and compliance presence (terms, privacy, contact).

## Tech Stack

- **Laravel 13** (`laravel/framework` ^13)
- **Livewire 4** + **Volt** (single-file full-page components in `resources/views/pages/`)
- **Flux** (`livewire/flux`) for form primitives, icons, and UI
- **Tailwind CSS 4** via `@tailwindcss/vite`
- **Vite 8** for asset bundling
- **PHP 8.5** (see `composer.json`; dev Docker image uses `Dockerfile.dev`)
- **Argon2id** password hashing ([`config/hashing.php`](config/hashing.php); override with `HASH_DRIVER` in `.env` if needed)

## Project Structure

```
app/
  Models/ContactSubmission.php   # Contact form persistence
config/
  quantyra.php                   # All site copy, nav, legal text, contact, footer
  app.php                        # APP_NAME, etc.
database/migrations/             # contact_submissions table
resources/
  css/app.css                    # Tailwind + Flux imports + Quantyra tokens
  views/
    layouts/app.blade.php        # Shell: nav, {{ $slot }}, footer, Flux/Livewire scripts
    pages/*.blade.php            # Volt pages (home, about, legal, contact)
routes/web.php                  # Volt::route(...) registrations
```

## Configuration

All marketing/legal copy lives in **[config/quantyra.php](config/quantyra.php)**:

- `site` — default title & meta description
- `navigation` — logo, links (with named `route` keys), contact CTA
- `hero` + `home_products` — homepage
- `about` — story, values, location
- `legal` — Terms & Privacy markdown (preserved verbatim)
- `contact` — emails, address, form labels
- `footer` — columns, copyright line
- `mail.contact_notification` — optional `CONTACT_NOTIFICATION_EMAIL` in `.env` for plain-text copies of submissions

## Routes

Defined in [routes/web.php](routes/web.php) with `Livewire\Volt\Volt::route`:

| Path       | Volt view              | Name      |
|-----------|-------------------------|-----------|
| `/`       | `resources/views/pages/home.blade.php`    | `home`    |
| `/about`  | `pages/about.blade.php` | `about`   |
| `/legal`  | `pages/legal.blade.php` | `legal`   |
| `/contact`| `pages/contact.blade.php` | `contact` |

## Design Tokens

Dark theme (see `resources/css/app.css` `@theme`): `#0A0A0B` background, `#3B82F6` / `#06B6D4` accents, **Space Grotesk** + **Inter** (Google Fonts in layout).

## Data stores

- **PostgreSQL** is the only data server: app tables, `sessions`, `cache`, and `jobs` (queue worker uses the `database` driver).
- **Do not configure Redis** for this project; `.env.example` omits Redis variables. Tests still use in-memory SQLite via `phpunit.xml`.
- **Remote / staging Postgres**: if migrations fail with **`permission denied for schema public`**, the DB role cannot create tables in `public` (typical on PostgreSQL 15+ or managed hosts). Set **`DB_SCHEMA`** in `.env` to a schema your role owns (see [`config/database.php`](config/database.php) `search_path`), or ask the operator to grant **`CREATE` on schema `public`**. Avoid **`migrate:fresh`** on shared databases.

## Development Commands

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate
npm install
composer run dev    # php artisan serve + queue + pail + npm run dev (see composer.json)
# or separately:
php artisan serve
npm run dev
```

### Docker (OrbStack) + Cloudflare Tunnel

- **Image**: [`Dockerfile.dev`](Dockerfile.dev) — PHP 8.5, Node 22, Caddy, `cloudflared`, `tini`.
- **Compose**: [`docker-compose.dev.yml`](docker-compose.dev.yml) — bind-mounts the repo, reads `.env`, publishes **8080**.
- **Flow**: Caddy listens on `:8080` and proxies Vite dev paths to `127.0.0.1:5173` and everything else to `php artisan serve` on `8000`. `cloudflared tunnel run --token "$CLOUDFLARED_TOKEN"` runs in the same container.
- **`.env`**: set `CLOUDFLARED_TOKEN`, `APP_URL=https://dev.quantyralabs.cc`, and **`VITE_DEV_SERVER_URL=https://dev.quantyralabs.cc`** (same origin for HMR through Caddy). If you only set `APP_URL`, Vite still picks it up for `public/hot` via [`vite.config.js`](vite.config.js). In Cloudflare Zero Trust, point the tunnel public hostname for dev at **`http://127.0.0.1:8080`** from the perspective of the `cloudflared` process (same network namespace as Caddy in the container).
- **Run**: `docker compose -f docker-compose.dev.yml up --build`
- **`node_modules`**: Compose uses a **named volume** so installs match **Linux** (Rolldown/Vite native bindings). If you change JS dependencies, run `docker compose -f docker-compose.dev.yml run --rm app npm ci` (or `docker compose ... down -v` to reset the volume). Keep **`package-lock.json` committed and in sync** with `package.json` (`npm install` locally); `npm ci` in the container will fail if the lock is incomplete (for example missing transitive entries npm validates on Linux).

### Staging / production (Coolify + VPS)

- **Images**: [`Dockerfile.staging`](Dockerfile.staging) (Composer **with** dev packages) and [`Dockerfile.production`](Dockerfile.production) (Composer **`--no-dev`**). Both are multi-stage: Node builds Vite assets, PHP 8.5 FPM + nginx + Supervisor run the app; **port 8080** (set the same **exposed port** in Coolify’s network settings; the UI often defaults to 3000).
- **Runtime**: [`docker/coolify/entrypoint.sh`](docker/coolify/entrypoint.sh) requires **`APP_KEY`**, fixes `storage` / `bootstrap/cache` permissions, runs `php artisan optimize`, then starts nginx and php-fpm. Health check hits Laravel’s **`/up`** route.
- **Env samples**: [`.env.staging.example`](.env.staging.example) and [`.env.production.example`](.env.production.example) — copy to `.env.staging` / `.env.production` locally if you want file-based reference; **Coolify** normally injects the same variables via the dashboard. `.env.staging` and `.env.production` are gitignored.
- **Migrations / queues**: Run **`php artisan migrate --force`** as a Coolify **post-deployment command** (or a one-off job), not inside the web image entrypoint. For **`database` queues**, add a second Coolify resource or command that runs **`php artisan queue:work --sleep=3 --tries=3`** against the same image and env.
- **Build**: `docker build -f Dockerfile.production -t quantyralabs:prod .` (swap Dockerfile for staging). Dev compose assets stay out of the build context via [`.dockerignore`](.dockerignore).

Quality:

```bash
composer run lint      # Laravel Pint
composer run test      # Pint + Pest
npm run build          # Vite production build
```

## Adding a New Page

1. Add a Volt file under `resources/views/pages/your-page.blade.php` with `layout('layouts.app')` and `title(...)`.
2. Register `Volt::route('/your-path', 'your-page')->name('your.page');` in `routes/web.php`.
3. Add nav/footer links in `config/quantyra.php` using the route name.

## Content Tone

Professional, corporate; emphasize SaaS products, reliability, security, compliance, and North America operations (Florida HQ).

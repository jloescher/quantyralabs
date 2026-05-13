# Quantyra Labs — Website

Corporate site for [Quantyra Labs LLC](https://quantyralabs.cc): SaaS products, company information, legal pages, and contact.

## Stack

- Laravel 13, Livewire 4, Volt, Flux UI, Tailwind CSS 4, Vite 8
- **PostgreSQL** for the app database, sessions, cache, and queued jobs (`QUEUE_CONNECTION=database`, `CACHE_STORE=database`, `SESSION_DRIVER=database`). **Redis is not used.**

## Local setup

Create a PostgreSQL database (for example `quantyralabs`), then:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
composer run dev
```

Adjust `DB_*` in `.env` to match your Postgres user, password, host, and database name.

Then open the URL shown by `php artisan serve` (default `http://127.0.0.1:8000`).

## Production build

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Serve with your web server pointed at the `public/` directory.

## Contact form

Submissions are stored in the `contact_submissions` table (including **department**: general, sales, or support). Optionally set `CONTACT_NOTIFICATION_EMAIL` in `.env` to receive a plain-text email copy when someone submits the form.

## Tests & lint

```bash
composer run test
composer run lint
```

See [CLAUDE.md](CLAUDE.md) for project conventions and where content is configured.

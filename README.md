# Laravel 13 + Inertia.js v3 Beta Demo

A comprehensive demo showcasing **Laravel 13**, **Inertia.js v3 (beta)**, **Vue 3**, **Tailwind CSS 4**, and **Vite**.

## Tech Stack

| Package | Version |
|---|---|
| Laravel | 13.1.x |
| Inertia.js (server) | 3.0.0-beta3 |
| Inertia.js (client) | 3.0.0-beta.3 |
| Vue | 3.5.x |
| Tailwind CSS | 4.x |
| Vite | 8.x |
| PHP | 8.4 |

## Project Structure

```
laravel-inertia/
├── docker/                       Docker infrastructure
│   ├── docker-compose.yml            Dev (hot reload, volume mounts)
│   ├── docker-compose.prod.yml       Production (pre-built assets)
│   ├── docker-compose.staging.yml    Staging
│   ├── nginx.conf                    Nginx site config
│   ├── supervisord-dev.conf          Supervisor for dev (php-fpm + nginx + vite)
│   ├── supervisord-prod.conf         Supervisor for prod (php-fpm + nginx)
│   └── entrypoint.sh                 Container entrypoint (migrate, seed, cache)
│
├── laravel/                      Laravel application
│   ├── Dockerfile                    Multi-stage build (base → deps → dev / prod)
│   ├── .dockerignore                 Docker build exclusions
│   ├── Makefile                      Dev/prod/staging shortcuts
│   ├── .env                          Local environment
│   ├── .env.staging                  Staging environment
│   ├── .env.production               Production environment
│   ├── app/
│   │   ├── Http/Controllers/         Feature demo controllers
│   │   ├── Http/Middleware/           Inertia middleware
│   │   ├── Models/                   Post, Comment, Tag, Notification, User
│   │   └── Providers/                Service providers
│   ├── resources/
│   │   ├── css/app.css               Tailwind entry
│   │   ├── js/app.js                 Vue + Inertia entry
│   │   └── js/
│   │       ├── Components/           FeatureBadge, CodeBlock
│   │       ├── Layouts/              AppLayout (responsive nav, flash messages)
│   │       └── Pages/                Vue pages per feature demo
│   │           └── Posts/            CRUD pages (Index, Show, Create, Edit)
│   ├── config/                       Laravel config files
│   ├── database/
│   │   ├── factories/                Model factories for seeding
│   │   ├── migrations/               Schema definitions
│   │   └── seeders/                  DatabaseSeeder with demo data
│   ├── routes/web.php                All route definitions
│   ├── composer.json                 PHP dependencies
│   ├── package.json                  JS dependencies
│   └── vite.config.js                Vite + Vue + Tailwind config
│
├── .gitignore                    Global ignores (OS, IDE)
└── README.md                     This file
```

## Feature Demos

| Route | Feature | Description |
|---|---|---|
| `/` | Home | Dashboard with stats and feature index |
| `/posts` | CRUD + Prefetching | Full CRUD with `useForm()`, search, pagination, `<Link prefetch>` |
| `/deferred` | Deferred Props | `Inertia::defer()` with grouped loading + `<Deferred>` component |
| `/infinite-scroll` | Infinite Scroll | `Inertia::scroll()` + `<InfiniteScroll>` component |
| `/polling` | Polling | `usePoll()` with manual start/stop, selective prop refresh |
| `/when-visible` | WhenVisible | `Inertia::optional()` + `<WhenVisible>` lazy load on scroll |
| `/merge-props` | Merge Props | `Inertia::merge()` for "Load More" without replacing |
| `/history` | History Encryption | `Inertia::encryptHistory()` for sensitive data |

## Getting Started

All commands run from `laravel/`:

```bash
cd laravel
```

### Local Development

```bash
composer install
bun install
php artisan migrate --seed

# Run everything (server + queue + logs + vite)
composer dev

# Or manually (two terminals)
php artisan serve    # Terminal 1
bun run dev          # Terminal 2
```

Visit http://localhost:8000

### Docker Development

```bash
make dev-build    # First time (builds image + starts container)
make dev          # Subsequent runs
make seed         # Seed database
```

Visit http://localhost (Vite HMR on port 5173)

### Docker Production / Staging

```bash
make prod-build   # Production  → http://localhost
make staging      # Staging     → http://localhost:8080
```

## Make Commands

Run from `laravel/`:

| Command | Description |
|---|---|
| `make dev` | Start dev with hot reload (Vite HMR + volume mounts) |
| `make dev-build` | Build and start dev |
| `make dev-down` | Stop dev containers |
| `make restart` | Restart container (picks up `.env` changes, no rebuild) |
| `make prod` | Start production |
| `make prod-build` | Build and start production |
| `make prod-down` | Stop production containers |
| `make staging` | Start staging |
| `make staging-down` | Stop staging containers |
| `make logs` | Tail container logs |
| `make shell` | Shell into container |
| `make seed` | Fresh migrate + seed |
| `make fresh` | Destroy volumes and rebuild from scratch |

## Environment Configuration

| File | `APP_ENV` | `APP_DEBUG` | `LOG_LEVEL` | `SESSION_ENCRYPT` |
|---|---|---|---|---|
| `.env` | local | true | debug | false |
| `.env.staging` | staging | true | debug | false |
| `.env.production` | production | false | error | true |

- `.env` changes apply with `make restart` — no rebuild needed
- `APP_KEY` is auto-generated at build time if not provided
- Staging/production `.env` files use `${VAR_NAME:-default}` syntax for CI/CD variable injection

### CI/CD Build Args

Pass variables at build time via `--build-arg`:

```bash
# Production
docker compose -f docker/docker-compose.prod.yml build \
  --build-arg APP_URL_PRODUCTION=https://example.com \
  --build-arg DB_HOST_PRODUCTION=db.example.com \
  --build-arg DB_DATABASE_PRODUCTION=laravel \
  --build-arg DB_USERNAME_PRODUCTION=app \
  --build-arg DB_PASSWORD_PRODUCTION=secret

# Staging
docker compose -f docker/docker-compose.staging.yml build \
  --build-arg APP_URL_STAGING=https://staging.example.com \
  --build-arg DB_HOST_STAGING=db-staging.example.com
```

Available build args follow the pattern `{VAR}_{ENVIRONMENT}` (e.g. `APP_URL_PRODUCTION`, `DB_HOST_STAGING`). Unprovided args fall back to defaults defined in the `.env.*` files.

## License

MIT

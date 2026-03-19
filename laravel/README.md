# Laravel 13 + Inertia.js v3 Beta Demo

A comprehensive demo showcasing the latest features of **Laravel 13**, **Inertia.js v3 (beta)**, **Vue 3**, **Tailwind CSS 4**, and **Vite**.

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

### Local Development

```bash
# Install dependencies
composer install
bun install

# Setup environment
php artisan migrate --seed

# Run (two terminals)
php artisan serve    # Terminal 1
bun run dev          # Terminal 2
```

Visit http://localhost:8000

### Docker Development

```bash
# First time (builds image + starts container)
make dev-build

# Subsequent runs
make dev

# Seed database
make seed
```

Visit http://localhost (Vite HMR on port 5173)

### Docker Production

```bash
make prod-build
```

### Docker Staging

```bash
make staging
```

## Docker Multi-Environment

| Command | Description |
|---|---|
| `make dev` | Start dev with hot reload (Vite HMR + volume mounts) |
| `make dev-build` | Build and start dev |
| `make restart` | Restart container (picks up `.env` changes, no rebuild) |
| `make prod` | Start production |
| `make prod-build` | Build and start production |
| `make staging` | Start staging |
| `make logs` | Tail container logs |
| `make shell` | Shell into container |
| `make seed` | Fresh migrate + seed |
| `make fresh` | Destroy volumes and rebuild from scratch |

### Environment Configuration

- **Dev**: Volume mounts for instant code changes, Vite HMR for frontend hot reload
- **Staging/Prod**: Pre-built assets, optimized autoloader, cached config/routes
- `.env` changes apply with `make restart` — no rebuild needed

## Project Structure

```
app/
  Http/Controllers/      # Feature demo controllers
  Models/                 # Post, Comment, Tag, Notification
resources/
  js/
    Components/           # FeatureBadge, CodeBlock
    Layouts/              # AppLayout (responsive nav, flash messages)
    Pages/                # Vue pages per feature demo
      Posts/              # CRUD pages (Index, Show, Create, Edit)
docker/                   # Nginx, Supervisor, entrypoint configs
database/
  factories/              # Model factories for seeding
  migrations/             # Schema definitions
  seeders/                # DatabaseSeeder with demo data
```

## License

MIT

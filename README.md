# project-two

E-commerce monorepo for an online clothing store. One vendor (also the admin), customers register and shop. Built API-first so the same backend serves web today and mobile later.

## Architecture

```
                 ┌────────────────────────┐
                 │   ecom/backend/        │
                 │   Laravel 13           │
                 │   ├─ Admin panel       │  ──► Postgres 16
                 │   │  (Livewire 4)      │  ──► Redis
   Browser ◄──►  │   └─ REST API          │  ──► MinIO (S3)
                 │      /api/v1/...       │  ──► MailHog (SMTP)
                 └────────────────────────┘
                          ▲       ▲
                          │ JSON  │ JSON
                          │       │
          ┌───────────────┘       └────────────────┐
          │                                        │
   ┌──────────────────────┐              ┌──────────────────────┐
   │  ecom/frontend/      │              │  ecom/frontend/      │
   │  store-front/        │              │  customer-panel/     │
   │  Nuxt 4 (SSR)        │              │  Vue 3 + Vite (SPA)  │
   │  Public storefront   │              │  Customer dashboard  │
   └──────────────────────┘              └──────────────────────┘
```

Three independent surfaces, one API:

| App | Stack | Local URL | Purpose |
|-----|-------|-----------|---------|
| **Admin panel** | Laravel + Livewire | http://localhost:8080/admin | Vendor manages products / orders |
| **Storefront** | Nuxt 4 + Tailwind | http://localhost:3000 | Public-facing catalog + checkout |
| **Customer panel** | Vue 3 + Vite + Tailwind | http://localhost:5173 | Logged-in customer dashboard |

Auth split:
- **Admin** → Laravel session auth (the `web` guard).
- **Customer / storefront** → Laravel Sanctum bearer tokens (designed so the same API can serve mobile apps later).

## Prerequisites

Only one: **Docker** (with `docker compose`).

No host installs needed — Node, npm, composer, PHP, and Postgres all run in containers. The user's UID is mapped into containers so files stay owned by the host user.

## Quickstart

```bash
# 1. Start the persistent stack (Laravel, nginx, Postgres, Redis, MinIO, MailHog)
docker compose up -d --build

# 2. Migrate + seed (creates admin user)
docker compose exec app php artisan migrate:fresh --seed

# 3. Build frontend assets for Laravel/Livewire admin UI
docker run --rm -v $(pwd)/ecom/backend:/app -u 1000:1000 -w /app -e HOME=/tmp node:lts npm install
docker run --rm -v $(pwd)/ecom/backend:/app -u 1000:1000 -w /app -e HOME=/tmp node:lts npm run build

# 4. Start the storefront dev server (separate terminal)
docker compose run --rm -w /app/store-front -p 3000:3000 node \
  npm run dev -- --host 0.0.0.0

# 5. Start the customer-panel dev server (separate terminal)
docker compose run --rm -w /app/customer-panel -p 5173:5173 node \
  npm run dev -- --host 0.0.0.0
```

## URLs

| What | URL | Notes |
|------|-----|-------|
| Storefront | http://localhost:3000 | Nuxt SSR |
| Customer panel | http://localhost:5173 | Vite SPA |
| Admin panel (login) | http://localhost:8080/admin/login | `admin@gmail.com` / `11223344` |
| Postgres | `localhost:5432` | DB `ecom`, user `ecom`, password `secret` |
| Redis | `localhost:6379` | |
| MinIO console | http://localhost:9001 | `minioadmin` / `minioadmin` |
| MinIO S3 API | `localhost:9000` | (internal: `minio:9000`) |
| MailHog UI | http://localhost:8025 | Catches all outgoing mail |

## Common commands

```bash
# Stop the stack
docker compose down

# Tail logs for one service
docker compose logs -f app

# Open a shell in the Laravel container (already as UID 1000)
docker compose exec app bash

# Run artisan / composer / tinker
docker compose exec app php artisan migrate
docker compose exec app composer require some/package
docker compose exec app php artisan tinker

# Open Postgres CLI
docker compose exec db psql -U ecom -d ecom
```

## Project layout

```
project-two/
├── docker-compose.yml          # All services
├── Dockerfile                  # Laravel image
├── docker-entrypoint.sh        # PHP-FPM entrypoint (waits for db, runs migrations)
├── docker/nginx/               # nginx vhosts
├── plan.md                     # Architecture record (folder structure, stack, domains)
├── CLAUDE.md                   # Guidance for Claude Code sessions
└── ecom/
    ├── backend/                # Laravel 13 + Livewire 4 — see ecom/backend/README.md
    └── frontend/
        ├── store-front/        # Nuxt 4 — see ecom/frontend/store-front/README.md
        └── customer-panel/     # Vue 3 + Vite — see ecom/frontend/customer-panel/README.md
```

## Production target

Subdomain-per-app, all under `myshop.com`:
- `myshop.com` → storefront (Vercel)
- `app.myshop.com` → customer panel (Vercel)
- `admin.myshop.com` → admin panel (AWS — same Laravel container)
- `api.myshop.com` → REST API (AWS — same Laravel container)

Local dev mirrors this via `/etc/hosts` once subdomain routing is wired up. Not yet active.

## Status

- [x] Docker stack (Laravel + Postgres + Redis + MinIO + MailHog)
- [x] Admin authentication (session-based, with `isAdmin` middleware)
- [x] Database schema (categories, products, images, orders, order_items, sizes, colors + pivots)
- [x] Eloquent models with relations
- [x] Static UI for storefront (4 pages), customer panel (3 pages + auth), admin (5 pages + auth)
- [ ] Customer authentication (Sanctum tokens)
- [ ] REST API endpoints
- [ ] Wire admin Livewire CRUD to real DB (currently uses static data)
- [ ] Subdomain routing locally
- [ ] Deploy

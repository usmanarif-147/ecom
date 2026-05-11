# CLAUDE.md

Repo-wide guidance for Claude Code sessions in this project. Keep this file up to date when conventions change.

## Project at a glance

**project-two** is an e-commerce monorepo: clothing store with one vendor (also the admin).
The user is learning API-first / multi-frontend architecture for the first time — they normally build Laravel + Livewire monoliths. Frame guidance accordingly: explain *why*, not just *what*.

## Layout

```
project-two/
├── docker-compose.yml          ← all services (Laravel, nginx, Postgres, Redis, MinIO, MailHog, node tool)
├── Dockerfile                  ← Laravel image
├── docker-entrypoint.sh
├── docker/nginx/default.conf   ← nginx vhost for Laravel
├── plan.md                     ← user-maintained architecture record
└── ecom/
    ├── backend/                ← Laravel 13 + Livewire 4 (admin panel + future REST API)
    └── frontend/
        ├── store-front/        ← Nuxt 4 (public storefront, port 3000)
        └── customer-panel/     ← Vue 3 + Vite (customer dashboard, port 5173)
```

**Don't restructure this layout** — the user explicitly decided on it. Do not propose moving Laravel out of `ecom/backend/` or creating a root-level `.env`.

## Stack

| Layer | Tech |
|-------|------|
| API + admin panel | Laravel 13, Livewire 4, PHP 8.4-fpm, Tailwind v4 |
| Storefront (SSR) | Nuxt 4, `@nuxtjs/tailwindcss` |
| Customer panel (SPA) | Vue 3 + Vite, Vue Router 4, Tailwind v4 (`@tailwindcss/vite`) |
| Database | Postgres 16 |
| Cache / queue | Redis (alpine) |
| File storage | MinIO (S3-compatible) — `minio:9000` API, `:9001` console |
| Mail (dev) | MailHog — `mailhog:1025` SMTP, `:8025` UI |

## Auth strategy (decided, do not relitigate)

- **Customer / storefront / customer-panel** → Laravel Sanctum **bearer tokens**.
- **Admin panel** → Laravel **session auth** (the `web` guard), gated by custom `isAdmin` middleware (alias `is_admin`).
- One `users` table with a `role` column (`admin`, `customer`).
- Auth guards (`web` vs `sanctum`) enforce separation; tables stay merged.

## Database conventions

- Money columns are `decimal(10, 2)` — **never `float`**.
- Status columns are `tinyInteger` cast to PHP 8.1 enums via Eloquent `casts()`. See `App\Enums\OrderStatus` and `App\Enums\ProductStatus`.
- `order_items.product` is a **JSON snapshot** of the product at sale time (immutable order history). A nullable `product_id` FK exists alongside for analytics queries.
- Pivot tables use Laravel's alphabetical convention: `color_product`, `product_size`.
- Always re-run `php artisan migrate:fresh --seed` after editing existing migrations.

## Livewire convention (user's chosen pattern)

Livewire is used as **small components embedded inside Blade views**, not as full-page components. Routing goes through `Route::view(...)` to a Blade view, which embeds Livewire widgets via `@livewire('admin.foo')`.

Implications:
- `#[Layout]` and `#[Title]` attributes on Livewire components are no-ops in this pattern — the Blade view owns layout/title.
- Middleware is plain Laravel middleware on the routes (no Livewire-specific guard tricks).

## Docker workflow

The user does NOT install Node, npm, composer, or PHP on the host machine — **everything runs in containers**.

| Command | When |
|---------|------|
| `docker compose up -d` | Start the persistent stack (Laravel, nginx, db, redis, minio, mailhog) |
| `docker compose down` | Stop stack |
| `docker compose exec app php artisan ...` | Run artisan in the Laravel container |
| `docker compose exec app composer ...` | Run composer in the Laravel container |
| `docker compose run --rm -w /app/store-front -p 3000:3000 node npm run dev -- --host 0.0.0.0` | Storefront dev server |
| `docker compose run --rm -w /app/customer-panel -p 5173:5173 node npm run dev -- --host 0.0.0.0` | Customer-panel dev server |

**The `node` service mounts only `./ecom/frontend`**. To run npm in the Laravel backend (for Vite builds), use a one-off:

```bash
docker run --rm -v $(pwd)/ecom/backend:/app -u 1000:1000 -w /app -e HOME=/tmp node:lts npm run build
```

Container `www-data` is mapped to host UID 1000 (the user's UID), so files created in containers are owned by the host user. To run interactive shells as that user: `docker exec -it dockerized-ecom bash` (the container's default user is already www-data 1000).

## URLs in dev

| Service | URL |
|---------|-----|
| Laravel (admin panel) | http://localhost:8080 |
| Storefront (Nuxt) | http://localhost:3000 |
| Customer panel (Vite) | http://localhost:5173 |
| MinIO console | http://localhost:9001 (`minioadmin` / `minioadmin`) |
| MailHog UI | http://localhost:8025 |
| Postgres | `localhost:5432` (DB: `ecom`, user: `ecom`, password: `secret`) |
| Redis | `localhost:6379` |

The user's intended production URL pattern (recorded in `plan.md`): `myshop.com`, `app.myshop.com`, `admin.myshop.com`, `api.myshop.com`. Not yet wired locally.

## User preferences (very important)

- **Incremental, file-by-file.** No sweeping multi-file plans. Finish one file, get confirmation, move on.
- **Keep things simple.** Avoid over-engineering. The user explicitly chose simpler-but-imperfect over more-correct-but-complex multiple times.
- **No restructuring.** Layout is decided.
- **Short answers.** When asked a question, lead with the answer; don't pad with caveats.
- **The user does the editing in many cases.** When they say "guide me", they mean tell them exactly what to change; they'll apply the edit and ping back.
- **Don't show plans unless explicitly asked.** When the user says "tell me what to do", they want the answer, not a planning ceremony.

## Project conventions

- **No comments unless necessary.** Code is mostly self-explanatory; comments only for non-obvious *why*.
- **No defensive code for impossible scenarios.** Trust framework guarantees.
- **API namespace will be `/api/v1/...`** with three groups: `public/*`, `customer/*`, `vendor/*` (vendor = admin in single-vendor MVP).
- **Reuse Unsplash image URLs across SPAs** for visual consistency in static-data phases.

## Common gotchas

- Editing an existing migration requires `php artisan migrate:fresh --seed` to take effect.
- `docker compose ps` hides one-off `docker compose run` containers and profile-gated services. Use `docker ps` for the full picture.
- The `node` service in compose is profile-gated (`profiles: ["tools"]`) — it won't start with plain `docker compose up`. Use `docker compose run --rm node ...` to invoke it.
- When two ports conflict (e.g. port 3000), check `docker ps | grep <port>` first and stop the existing container.
- Tailwind v4 setup differs from v3: `@import "tailwindcss";` in CSS and a Vite plugin — no `tailwind.config.js`.

## What to read next

- `README.md` — human-facing project overview and quickstart
- `ecom/backend/README.md` — Laravel app specifics
- `ecom/frontend/store-front/README.md` — storefront SPA specifics
- `ecom/frontend/customer-panel/README.md` — customer panel SPA specifics
- `plan.md` — user's high-level architecture record (folder structure, tech stack list, domain pattern)

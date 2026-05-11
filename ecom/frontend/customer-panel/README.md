# customer-panel

Logged-in customer dashboard. Vue 3 + Vite SPA consuming the Laravel API at `/api/v1/customer/*`.

## Stack

| | |
|---|---|
| Framework | Vue 3 + Vite |
| Routing | Vue Router 4 |
| Styling | Tailwind v4 via `@tailwindcss/vite` |
| State | Pinia (planned — not yet added) |
| HTTP | axios (planned) |

SPA (not SSR) because this app is behind login → no SEO need → SSR is wasted overhead.

## How to run

All commands run from the **project root** (`project-two/`).

```bash
# Install deps (after a fresh clone)
docker compose run --rm -w /app/customer-panel node npm install

# Dev server
docker compose run --rm -w /app/customer-panel -p 5173:5173 node \
  npm run dev -- --host 0.0.0.0
```

Then open http://localhost:5173 → redirects to `/dashboard`.

To stop: `Ctrl+C`, or `docker stop <container-name>`.

## Folder map

```
ecom/frontend/customer-panel/
├── vite.config.js                  # Tailwind plugin registered here
├── package.json                    # vue + vue-router + tailwindcss + @tailwindcss/vite
├── index.html
└── src/
    ├── main.js                     # createApp + register router
    ├── App.vue                     # Just <RouterView />
    ├── style.css                   # @import "tailwindcss";
    ├── router/index.js             # Routes grouped by layout (Auth + Dashboard)
    ├── data/customer.js            # Static customer / orders / favorites (will move to API calls)
    ├── layouts/
    │   ├── AuthLayout.vue          # Centered card for login/register
    │   └── DashboardLayout.vue     # Sidebar + topbar
    ├── components/
    │   ├── AppSidebar.vue
    │   ├── AppTopbar.vue
    │   ├── StatCard.vue
    │   ├── StatusPill.vue
    │   ├── OrderRow.vue
    │   └── FavoriteCard.vue
    └── views/
        ├── Login.vue               # /login
        ├── Register.vue            # /register
        ├── Dashboard.vue           # /dashboard
        ├── Orders.vue              # /orders
        └── Favorites.vue           # /favorites
```

## Routing

Routes are grouped by layout in `src/router/index.js`:

| Layout | Routes |
|--------|--------|
| `AuthLayout` (no sidebar) | `/login`, `/register` |
| `DashboardLayout` (sidebar + topbar) | `/dashboard`, `/orders`, `/favorites` |

Unknown paths redirect to `/dashboard`.

## Current state

Static UI only — no API calls, no auth, no real data persistence. Forms submit to `router.push('/dashboard')` placeholders. Hard-coded data in `src/data/customer.js`.

## Future wiring

When the Laravel API is ready, this app will:
- POST `/api/v1/public/auth/register` and `/auth/login` to get a Sanctum bearer token
- Store token in `localStorage` (key like `authToken`)
- Send `Authorization: Bearer <token>` on every `/api/v1/customer/*` call
- Fetch orders from `GET /api/v1/customer/orders`
- Fetch favorites from `GET /api/v1/customer/favorites`
- Logout → POST `/api/v1/customer/auth/logout` + clear localStorage

The storefront on http://localhost:3000 will share the same `localStorage` key once both apps live under a single domain (e.g. `myshop.com` in production, single nginx vhost locally). Until then, each runs on its own origin.

## Production target

Deploys to **Vercel** as `app.myshop.com`. Pure static SPA build (`vite build`), so any CDN works — Vercel just happens to be the choice for parity with the storefront.

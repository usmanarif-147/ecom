# store-front

Public-facing online store. Nuxt 4 (Vue 3 + SSR) consuming the Laravel API at `/api/v1/public/*`.

## Stack

| | |
|---|---|
| Framework | Nuxt 4 (Vue 3, SSR enabled) |
| Styling | Tailwind via `@nuxtjs/tailwindcss` |
| HTTP | Nuxt's built-in `$fetch` (planned) |
| State | Pinia (planned — not yet added) |

SSR is the reason Nuxt was picked here: the storefront needs SEO (Google needs to index product pages). The customer panel uses plain Vue + Vite SPA precisely because SEO doesn't matter there.

## How to run

All commands run from the **project root** (`project-two/`).

```bash
# Install deps (after a fresh clone)
docker compose run --rm -w /app/store-front node npm install

# Dev server with hot-reload
docker compose run --rm -w /app/store-front -p 3000:3000 node \
  npm run dev -- --host 0.0.0.0
```

Then open http://localhost:3000.

To stop the dev server: `Ctrl+C` in its terminal, or `docker stop <container-name>` from elsewhere (find it with `docker ps`).

## Folder map

```
ecom/frontend/store-front/
├── nuxt.config.ts                  # Tailwind module registered here
├── package.json                    # nuxt + @nuxtjs/tailwindcss + vue-router
└── app/
    ├── app.vue                     # Just <NuxtLayout><NuxtPage/></NuxtLayout>
    ├── data/products.js            # Static product data (will move to API calls)
    ├── layouts/default.vue         # Header + footer wrapper
    ├── components/
    │   ├── AppHeader.vue
    │   ├── AppFooter.vue
    │   ├── ProductCard.vue
    │   └── ProductFilters.vue
    └── pages/
        ├── index.vue               # /            (product grid + filters)
        ├── product/[id].vue        # /product/:id
        ├── cart.vue                # /cart
        └── checkout.vue            # /checkout
```

Nuxt 4 uses the `app/` directory layout (instead of root-level `pages/`, `components/`). File-based routing inside `app/pages/`.

## Current state

Static UI only — no API calls, no auth, no cart persistence. Hard-coded products in `app/data/products.js`. Buttons (Add to Cart, Place Order, qty steppers) are visual placeholders.

## Future wiring

When the Laravel API is ready, this app will:
- Fetch product list from `GET /api/v1/public/products`
- Fetch single product from `GET /api/v1/public/products/:id`
- POST orders to `POST /api/v1/customer/orders` (after customer login)
- Use a Sanctum bearer token for customer-scoped calls
- Token lives in `localStorage`, set via `Authorization: Bearer <token>` header on requests

Sign-in/register links route to `http://localhost:5173/login` (customer panel) — that's where auth forms live, not here.

## Production target

Deploys to **Vercel** as `myshop.com`. Vercel + Nuxt SSR works out of the box. The API will live on AWS at `api.myshop.com`; storefront's SSR calls cross-origin to the API server (CORS allow + token forwarded from the request cookies if needed).

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
├── nuxt.config.ts                  # Tailwind module + runtimeConfig.public.apiBase
├── .env.example                    # NUXT_PUBLIC_API_BASE
├── package.json                    # nuxt + @nuxtjs/tailwindcss + vue-router
└── app/
    ├── app.vue                     # Just <NuxtLayout><NuxtPage/></NuxtLayout>
    ├── composables/
    │   └── useApi.js               # Returns a $fetch instance with baseURL + Accept: application/json
    ├── types/api.js                # JSDoc typedefs for Product / Category / Size / Color / Order / PlaceOrderRequest
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

## API wiring (v1: guest checkout, no auth)

The Laravel API is now live at `/api/v1/public/*`. v1 is **guest-only** — no customer accounts, no Sanctum, no tokens. The storefront will:

- Fetch product list from `GET /api/v1/public/products` (search, category/size/color filters, price sort, paginated 12/page)
- Fetch single product from `GET /api/v1/public/products/{id}` (auto-increments view counter)
- Fetch filter options from `GET /api/v1/public/categories`, `/sizes`, `/colors`
- Hold the cart **client-side** (localStorage; Pinia store, planned)
- Place orders via `POST /api/v1/public/place-order` with customer details + cart payload + `payment_method: "cod"` (Stripe deferred)

All requests go through the `useApi()` composable in `app/composables/`, which reads `NUXT_PUBLIC_API_BASE` from the environment.

## Production target

Deploys to **Vercel** as `myshop.com`. Vercel + Nuxt SSR works out of the box. The API will live on AWS at `api.myshop.com`; storefront's SSR calls cross-origin to the API server (CORS allow + token forwarded from the request cookies if needed).

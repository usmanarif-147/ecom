# REST APIs — Endpoints

JSON API under `/api/v1/*`. Two route groups, each with its own audience and middleware. Auth uses **Laravel Sanctum bearer tokens** (`Authorization: Bearer <token>`).

## Public — `/api/v1/public/*` (no auth)

Used by the storefront for catalog browsing and by both frontends for sign-up / sign-in.

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/products` | List active products with optional filters (category, size, color, max price, sort) |
| GET | `/products/{id}` | Single product with images, sizes, colors, category and stock |
| GET | `/categories` | List all categories |
| GET | `/categories/{id}/products` | Products in one category |
| GET | `/sizes` | All sizes available across the catalog (used for filter UI) |
| GET | `/colors` | All colors available across the catalog (title + hex, for swatches) |
| POST | `/auth/register` | Create a customer account; returns a token + user |
| POST | `/auth/login` | Authenticate; returns a token + user |

## Customer — `/api/v1/customer/*` (auth: sanctum, role: customer)

Used by the customer-panel SPA after sign-in.

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/auth/me` | Current logged-in customer profile |
| POST | `/auth/logout` | Revoke the current bearer token |
| GET | `/profile` | View profile (name, email, address) |
| PATCH | `/profile` | Update profile |
| GET | `/orders` | List the customer's own orders |
| GET | `/orders/{id}` | One order with item snapshots, totals, status |
| POST | `/orders` | Place a new order from a cart payload |
| GET | `/favorites` | List favorited products |
| POST | `/favorites/{productId}` | Add a product to favorites |
| DELETE | `/favorites/{productId}` | Remove a product from favorites |

## Response shape

- Single item → `{ data: { ... } }`
- List → `{ data: [ ... ], meta: { ... } }` (paginated)
- Error → `{ message, errors }` with appropriate 4xx/5xx status

## Deliberately out of scope (v1)

- Vendor / admin API endpoints — admin works through the Livewire panel; no mobile admin app yet.
- Payment processing — orders saved with `status=pending`; charging the customer is a later phase.
- Order cancellation by the customer — admin-only via the panel.
- File uploads via API — product images are uploaded through the admin panel, served as URLs.
- Real-time / websockets.

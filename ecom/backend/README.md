# ecom/backend

Laravel 13 + Livewire 4. Hosts the admin panel (Livewire UI) and will host the REST API consumed by the storefront and customer-panel SPAs.

## Stack

| | |
|---|---|
| PHP | 8.4 (php-fpm container) |
| Framework | Laravel 13 |
| UI for admin | Livewire 4 + Tailwind v4 + Blade |
| Database | Postgres 16 (`pdo_pgsql`) |
| Cache / queue | Redis (phpredis extension) |
| Mail (dev) | MailHog at `mailhog:1025` |
| Files | MinIO (S3-compatible) at `minio:9000` |

## How to run

All commands run from the **project root** (`project-two/`), not from this folder.

```bash
# Start the whole stack
docker compose up -d

# Migrate + seed (creates the admin user)
docker compose exec app php artisan migrate:fresh --seed

# Build CSS/JS for the admin panel (Vite, one-off)
docker run --rm -v $(pwd)/ecom/backend:/app -u 1000:1000 -w /app -e HOME=/tmp node:lts npm run build
```

The Laravel container is named `dockerized-ecom`. Reach it on:
- `http://localhost:8080/admin/login` (admin panel)
- `http://localhost:8080/api/...` (future API)

### Why one-off `docker run` for `npm`?

The `node` service in `docker-compose.yml` mounts only `./ecom/frontend` (since that's where the SPAs live). To run npm/Vite in this Laravel folder, you need the one-off command shown above.

## Admin login

Seeded credentials (see `database/seeders/AdminLoginSeeder.php`):
- email: `admin@gmail.com`
- password: `11223344`

## Folder map

```
ecom/backend/
├── app/
│   ├── Data/AdminStaticData.php       # Hard-coded data for admin pages (will move to DB)
│   ├── Enums/
│   │   ├── OrderStatus.php            # int 0..3 → Pending/Shipped/Delivered/Cancelled
│   │   └── ProductStatus.php          # int 0/1 → Inactive/Active
│   ├── Http/Middleware/
│   │   └── isAdmin.php                # Custom — handles "guest" and "admin" gates via :type param
│   ├── Livewire/Admin/
│   │   ├── Login.php                  # Used inside admin.login Blade view
│   │   ├── Logout.php                 # Used in topbar + sidebar (variant prop)
│   │   ├── Dashboard.php              # Currently static, awaiting DB wiring
│   │   ├── CategoryIndex.php          # DB-backed; list + inline create/edit/delete
│   │   ├── ProductIndex.php
│   │   ├── ProductForm.php
│   │   └── OrderIndex.php
│   └── Models/
│       ├── User.php, Category.php, Product.php, Image.php,
│       └── Order.php, OrderItem.php, Size.php, Color.php
├── database/migrations/               # 5 schema migrations + 2 pivots
├── database/seeders/AdminLoginSeeder.php
├── resources/views/
│   ├── components/layouts/
│   │   ├── admin.blade.php            # Layout with sidebar + topbar
│   │   └── auth.blade.php             # Centered card layout for /admin/login
│   ├── partials/                      # admin-sidebar, admin-topbar
│   ├── admin/                         # Page-level Blade views (Route::view targets)
│   └── livewire/admin/                # Livewire component views
└── routes/
    ├── web.php                        # Just root + requires admin.php
    └── admin.php                      # All /admin/* routes
```

## Routing convention

Admin routes are in `routes/admin.php`, grouped under `Route::prefix('admin')->name('admin.')`.

Pattern: **`Route::view('/foo', 'admin.foo')`** points at a Blade view that *embeds* small Livewire components via `@livewire('admin.bar')`. Full-page Livewire components (`Route::get('/foo', SomeComponent::class)`) are intentionally not used — middleware composition is easier with `Route::view`.

| Middleware on routes | Meaning |
|---|---|
| `is_admin:guest` | Only logged-out users (or non-admins). Redirects logged-in admins to `/admin/dashboard`. |
| `is_admin` | Only authenticated admins. Redirects others to `/admin/login`. |

## Database conventions

- Money: `decimal(10, 2)`, **never `float`**.
- Status: `tinyInteger` cast to PHP enums via Eloquent `casts()`. See `App\Enums\OrderStatus` and `App\Enums\ProductStatus`.
- `order_items.product` is a JSON snapshot of the product at the time of sale (immutable order history); `product_id` is a nullable FK for analytics.
- Pivot tables: `color_product`, `product_size` (Laravel alphabetical convention).
- Editing an existing migration requires `php artisan migrate:fresh --seed` to take effect — no incremental migration for early-stage schema changes.

## Models — relations summary

```
Category   hasMany    Products
Product    belongsTo  Category
Product    hasMany    Images
Product    belongsToMany  Sizes, Colors     (pivots: product_size, color_product)
Image      belongsTo  Product
Order      hasMany    OrderItems
OrderItem  belongsTo  Order
OrderItem  belongsTo  Product               (nullable; FK alongside JSON snapshot)
```

Note: `$orderItem->product` returns the **JSON snapshot** (not the related Product model) because of the `product` column on the row. Use `$orderItem->product()->first()` or eager-load to get the related Product.

## Auth (admin)

Vanilla Laravel session auth on the `web` guard.

- `Auth::attempt([...], $remember)` in `Livewire/Admin/Login.php`
- `Auth::guard('web')->logout()` + `session()->invalidate()` + `session()->regenerateToken()` in `Logout`
- `isAdmin` middleware aliased as `is_admin` in `bootstrap/app.php`

## Future API (not yet implemented)

Will live under `/api/v1/...` using Laravel Sanctum bearer tokens. Three planned route groups:

| Prefix | Middleware | Purpose |
|--------|-----------|---------|
| `/api/v1/public/*` | none | Browse products, register, login |
| `/api/v1/customer/*` | `auth:sanctum` + role check | Cart, orders, profile |
| `/api/v1/vendor/*` | `auth:sanctum` + role check | Vendor CRUD (vendor = admin in single-vendor MVP) |

## Common artisan commands

```bash
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan tinker
docker compose exec app php artisan route:list
docker compose exec app composer require some/package
```

# Admin Panel — Features

Server-rendered Livewire pages under `/admin/*`. Session auth on the `web` guard, gated by `is_admin` middleware. The admin is also the single vendor.

## Pages

| URL | Purpose |
|-----|---------|
| `/admin/login` | Sign in as admin |
| `/admin/dashboard` | Overview: revenue, total orders, total products, recent orders, top products |
| `/admin/categories` | List + create + edit + delete categories |
| `/admin/products` | List products with search and category filter |
| `/admin/products/create` | New product (title, description, price, cost, stock, status, category) |
| `/admin/products/{id}/edit` | Edit a product, including its images, sizes and colors |
| `/admin/sizes` | Manage the master list of sizes (`S`, `M`, `L`, `32`, etc.) — shared across products |
| `/admin/colors` | Manage the master list of colors (title + hex code) |
| `/admin/orders` | List all orders, filter by status (pending / shipped / delivered / cancelled) |
| `/admin/orders/{id}` | View one order with item snapshots, customer info; update status |
| `/admin/customers` | List registered customers; click into their order history |
| (logout button) | Sign out + invalidate session |

## What the admin can do

- Full catalog CRUD (categories, products, images, sizes, colors)
- Adjust stock; activate or deactivate products
- Track and update order status through its lifecycle
- See registered customers and what they bought

## Deliberately out of scope (v1)

- Admin self-registration (admin is seeded, no signup form)
- Refunds and partial cancellations
- Discount codes / promotions
- Bulk import / export
- Multi-admin permissions (single admin role only)

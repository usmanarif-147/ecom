
 project-two/
 ├── ecom/
 │   └── backend/             ← Laravel + Livewire (admin panel + API)
 │   └── frontend/
 │         ├── storefront/      ← public store SPA — port 8080
 │         └── customer-panel/  ← customer dashboard SPA — port 8082
 ├── docker/
 │   └── nginx/
 │       ├── default.conf       (existing — Laravel/admin)
 │       ├── storefront.conf    (new)
 │       └── customer.conf      (new)
 ├── Dockerfile         ← Laravel image (existing)
 ├── docker-compose.yml ← add 2 SPA services
 └── docker-entrypoint.sh

## Tech Stack

1: Admin Panel
 - Laravel
 - Livewire
 - Tailwind CSS

2: Storefront
 - Nuxt 3
 - Pinia
 - Tailwind CSS

3: Customer Panel
 - Vue 3 + Vite
 - Pinia
 - Tailwind CSS

## Domain Pattern

- Storefront     → myshop.com
- Admin Panel    → admin.myshop.com
- Customer Panel → app.myshop.com
- API            → api.myshop.com

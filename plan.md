
 project-two/
 ├── ecom/
 │   └── backend/       ← Laravel API (existing)
 │   └── frontend/
 │         ├── storefront/      ← public store SPA — port 8080
 │         ├── admin-panel      ← vendor dashboard SPA — port 8081
 │         ├── customer-panel   ← customer dashboard SPA — port 8082
 ├── docker/
 │   └── nginx/
 │       ├── default.conf       (existing — Laravel)
 │       ├── storefront.conf    (new)
 │       ├── vendor.conf        (new)
 │       └── customer.conf      (new)
 ├── Dockerfile         ← Laravel image (existing)
 ├── docker-compose.yml ← add 3 SPA services
 └── docker-entrypoint.sh7
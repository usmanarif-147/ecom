# Working Pattern — Admin Panel + REST APIs

Roadmap for every entity in `ecom/backend/`. Read this before starting work on any new entity.

```
┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│  PHASE 1     │ →  │  PHASE 2     │ →  │  PHASE 3     │
│  Admin CRUD  │    │  API (TDD)   │    │  Wrap up     │
│  (Livewire)  │    │  (Pest)      │    │  + docs      │
└──────────────┘    └──────────────┘    └──────────────┘
```

Finish one entity end-to-end before starting the next.
Entity order: **Categories → Colors → Sizes → Products.**

---

## Pre-flight

- [ ] Re-read this file
- [ ] Re-read `docs/backend/admin-panel.md` and `docs/backend/rest-apis.md`
- [ ] Check entity dependencies (Product needs Category, Sizes, Colors first)
- [ ] Model factory exists? If not, create it (needed for tests)

---

## Phase 1 — Admin CRUD (Livewire)

```
routes/admin.php
   │
   └─► resources/views/admin/foo/*.blade.php
          │
          └─► @livewire('admin.foo-index')
                 │
                 └─► app/Livewire/Admin/FooIndex.php   ← validation + logic + render
```

- [ ] Add routes to `routes/admin.php`
- [ ] Create blade pages: `admin/foo/{index,create,edit}.blade.php`
- [ ] Create Livewire components: `FooIndex.php` (list+delete), `FooForm.php` (create+edit)
- [ ] Manual test in browser

> Validation and logic stay inside the Livewire component.
> Extract `FooService` only if the component grows past ~150 lines (likely only for Product).

---

## Phase 2 — REST API (TDD)

```
1. WRITE TEST FIRST          tests/Feature/Api/PublicFoosTest.php
       │
       └─► run → RED ✗
              │
2. IMPLEMENT
       │
       ├─► routes/api.php
       ├─► Api\FooController         (thin — 1-5 lines per method)
       ├─► StoreFooRequest           (only if endpoint takes input)
       ├─► FooService                (only if logic > 5 lines)
       ├─► Foo model + scopes        (for complex queries)
       └─► FooResource               (shape JSON output)
              │
3. RUN TEST → GREEN ✓
       │
4. REFACTOR if needed, tests stay GREEN
```

- [ ] Write Pest feature test FIRST → run → expect **RED**
- [ ] Add routes to `routes/api.php`
- [ ] Create `Api\FooController`
- [ ] Add `StoreFooRequest` (if endpoint accepts input)
- [ ] Add `FooService` (if logic > 5 lines)
- [ ] Add scopes to model for any complex queries
- [ ] Create `FooResource` for JSON output
- [ ] Run test → expect **GREEN**
- [ ] Refactor if needed; tests stay green

> No repository layer. Eloquent + model scopes is the data abstraction.

---

## Phase 3 — Wrap up

- [ ] Update `docs/backend/admin-panel.md` if a new admin page was added
- [ ] Update `docs/backend/rest-apis.md` if a new endpoint was added
- [ ] Tick the relevant item in root `README.md` Status section
- [ ] Commit with a clear message

---

## Approaches in use

| Approach | Use? | Where |
|----------|------|-------|
| TDD | ✅ | API endpoints (Pest) — write test first |
| SOLID | ✅ pragmatic | DI via constructor, focused single-responsibility classes |
| DDD | ❌ | Domain is simple CRUD |
| Repository pattern | ❌ | Eloquent + model scopes already abstract the DB |
| Livewire UI tests | ❌ | Not worth it for simple CRUD |

---

## File layout per entity

```
ecom/backend/
├── app/
│   ├── Livewire/Admin/{FooIndex, FooForm}.php
│   ├── Http/
│   │   ├── Controllers/Api/FooController.php
│   │   ├── Requests/StoreFooRequest.php
│   │   └── Resources/FooResource.php
│   ├── Services/FooService.php            (only when needed)
│   └── Models/Foo.php                     (with scopes)
├── resources/views/
│   ├── admin/foo/{index, create, edit}.blade.php
│   └── livewire/admin/{foo-index, foo-form}.blade.php
├── routes/
│   ├── admin.php   ← add admin routes here
│   └── api.php     ← add API routes here
└── tests/Feature/Api/PublicFoosTest.php
```

---

**Living document — update as new conventions emerge.**

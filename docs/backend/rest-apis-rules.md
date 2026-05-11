# REST APIs — Implementation Rules

Pure rules for building API endpoints with Laravel + Sanctum + Pest. **TDD — write the test first.**

## Routes

- All API routes live in `routes/api.php`
- Two route groups:
  - `public/*` — no auth
  - `customer/*` — middleware: `auth:sanctum` + role check (`customer`)
- All endpoints under `/api/v1/...`

## Controllers

- Live under `app/Http/Controllers/Api/`
- One file per resource (`FooController`)
- Methods are **thin (1–5 lines)** — validate via FormRequest, dispatch to service if needed, return a Resource
- Inject services via constructor (DI through the container)
- **Never return a raw Eloquent model** — always wrap in an API Resource

## FormRequests

- One per write action: `StoreFooRequest`, `UpdateFooRequest`
- Live under `app/Http/Requests/`
- Validation rules in `rules()` method
- Authorization in `authorize()` method (returns `true` if the route middleware already guarded it)

## Services

- Live under `app/Services/`
- Create **only when** business logic exceeds ~5 lines or is reused
- Injected into controllers via constructor
- A controller calling 1 model method does NOT need a service

## Models + scopes

- Centralize complex queries as model scopes (`scopeActive`, `scopeInCategory`)
- **No repository layer** — Eloquent is the data abstraction

## API Resources

- One per model: `FooResource` (and `FooCollection` if needed)
- Live under `app/Http/Resources/`
- Hide internal columns (`cost`, `views`) from public endpoints
- Cast money to string with 2 decimals (preserves precision in JSON)
- Cast enums to their string name (`status: 'pending'`), not int

## Tests (TDD — mandatory)

- Live under `tests/Feature/Api/`
- One file per endpoint group: `PublicFoosTest`, `CustomerFoosTest`
- Use Pest syntax + `RefreshDatabase` trait + model factories
- Sequence: **write test → run → RED → implement → run → GREEN → refactor**
- Each test asserts status code + JSON shape

## Auth

- **Customer auth = Sanctum bearer tokens**, not SPA cookies
- Token issued by `POST /api/v1/public/auth/login` and `register`
- Sent as `Authorization: Bearer <token>` on customer routes
- `auth:sanctum` middleware + custom role-check middleware for `customer/*`

## Response shape

| Case | Shape |
|------|-------|
| Single | `{ "data": { ... } }` (auto via API Resource) |
| List | `{ "data": [ ... ], "meta": { ... } }` (paginated) |
| Validation error | 422 + `{ "message": "...", "errors": { "field": ["..."] } }` |
| Auth error | 401 + `{ "message": "Unauthenticated." }` |
| Not found | 404 + `{ "message": "..." }` |

## Don'ts

- ❌ Don't return raw Eloquent models — always use API Resources
- ❌ Don't introduce a repository layer — model scopes are the right tool
- ❌ Don't skip the test — TDD is the rule for APIs
- ❌ Don't use Sanctum SPA-cookie mode — we use bearer tokens only
- ❌ Don't expose admin write operations via API in v1 — admin uses Livewire
- ❌ Don't use `float` for money in responses — always `decimal(10, 2)`, output as string

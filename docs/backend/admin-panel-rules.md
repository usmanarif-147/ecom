# Admin Panel — Implementation Rules

Pure rules for building admin pages with Livewire 4. Read this before writing code.

## Routes

- All admin routes live in `routes/admin.php`
- Wrap in `Route::prefix('admin')->name('admin.')->group(...)`
- Use `Route::view('/path', 'admin.view')` — **not** full-page Livewire components
- Middleware: `is_admin` for authenticated pages, `is_admin:guest` for the login page

## Blade views

- One page per Blade view at `resources/views/admin/<entity>/{index,create,edit}.blade.php`
- Layout: `<x-layouts.admin>...</x-layouts.admin>` wraps the page
- Embed widgets with `@livewire('admin.<entity>-<purpose>')`

## Livewire components

- Live under `app/Livewire/Admin/`
- One component per UI widget (e.g. `FooIndex` for list+delete, `FooForm` for create+edit)
- Component view at `resources/views/livewire/admin/foo-<purpose>.blade.php`
- Validation goes inside the component (use `$this->validate(...)` or `#[Validate]` attributes)
- Business logic stays in the component **until** it exceeds ~150 lines — then extract a `FooService` in `app/Services/`
- Use `wire:submit`, `wire:click`, `wire:model.live` for interactions
- Show errors via `@error('field') {{ $message }} @enderror` in the view
- Share form components across create + edit by accepting `mount($id = null)`

## Auth

- Login: `Auth::attempt([...], $remember)` then `session()->regenerate()` then `redirect()->intended(...)`
- Failure: surface via `$this->addError('email', '...')` — never just redirect silently
- Logout: `Auth::guard('web')->logout()` then `session()->invalidate()` then `session()->regenerateToken()`

## Don'ts

- ❌ Don't use full-page Livewire components (`Route::get('/admin/foo', FooComponent::class)`)
- ❌ Don't use `#[Layout]` or `#[Title]` attributes on components — they are no-ops in this embedded pattern; the Blade view owns layout/title
- ❌ Don't use `session()->flush()` for logout — too aggressive
- ❌ Don't create new auth middleware — extend `is_admin` if needed
- ❌ Don't add comments unless the *why* is non-obvious
- ❌ Don't introduce a service class upfront — wait until the component grows

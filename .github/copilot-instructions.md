npm run build                           # Build Vite assets for production
npm run dev                             # Watch mode for Vite (included in composer run dev)
# Copilot Instructions for Laravel Helpdesk

## Project Overview
This is a **Laravel 12 helpdesk ticketing system** for IT support, user ticketing, and robust admin management. The project uses a backend REST API and a modern frontend with Gentelella Admin Template and Bootstrap 5.

---

## Tech Stack

- **Backend**: Laravel 12 (PHP ^8.2), Laravel Spatie (roles/permissions), PostgreSQL
- **Frontend**: Gentelella Admin Template (Bootstrap 5), JavaScript, CSS, HTML
- **Testing**: Pest 4.3+ (modern PHPUnit alternative)
- **DevOps/Hosting**: Apache, Let’s Encrypt (SSL), Git (version control)
- **Code Quality**: Laravel Pint for PHP formatting, ESLint 9+ for JavaScript

---

## Development Workflows

### Essential Commands
```bash
# Initial setup
composer setup                          # Install PHP & npm deps, generate key, migrate

# Local development (concurrent server, queue, and Vite)
composer run dev                        # Runs: artisan serve, queue:listen, Vite build

# Testing
composer test                           # Runs Pest test suite with coverage

# Code quality
./vendor/bin/pint                       # Format PHP code (auto-fix)
npm run build                           # Build Vite assets for production
npm run dev                             # Watch mode for Vite (included in composer run dev)
```

### Development Server Details
- **Laravel API**: `http://localhost:8000` (artisan serve)
- **Frontend**: Gentelella Admin Template (Bootstrap 5), served via Apache
- **Queue Worker**: Runs in sync mode (testing) or configured driver in production
- **Use `composer run dev`** for full development experience

---

## Architecture Patterns

### Directory Structure & Responsibilities
- `app/Http/Controllers/` - RESTful request handlers
- `app/Models/` - Eloquent models (User, Ticket, etc.)
- `routes/web.php` - Web route definitions
- `resources/js/app.js` + `resources/css/app.css` - Frontend entry points
- `storage/app/private|public/` - User-uploaded files
- `database/migrations/` - Version-controlled schema changes
- `database/seeders/` - Database seeding for development data

### Service Providers
- `AppServiceProvider.php` is the main bootstrap point for custom service registration and binding
- Use `boot()` for publishing assets or listeners
- Use `register()` for binding interfaces to implementations

### Database Patterns
- Eloquent ORM only (no raw queries unless performance-critical)
- Factories in `database/factories/` for seeding/testing
- Migrations are immutable once deployed; create new migrations for changes
- PostgreSQL for production, SQLite for dev/test

---

## Coding Standards

- Use Laravel Pint for PHP formatting
- Use ESLint for JavaScript
- Follow PSR standards for PHP
- Use clear, descriptive variable and function names
- Avoid inline PHP logic in Blade templates; use view composers or helpers

---

## UI/UX Conventions

- Use Gentelella Admin Template for admin and IT support dashboards
- Bootstrap 5 for layout and components
- Responsive, accessible, and user-friendly interfaces
- Use Tailwind CSS utilities if needed for custom styling
- Pass data via controller to Blade views
- Use Blade directives (`@if`, `@foreach`, `@include`, `@extends`)
- No inline PHP logic in views

---

## API & Functional Guidelines

- All endpoints return JSON responses using Laravel helpers
- Use appropriate HTTP status codes (201 for creation, 204 for deletion, etc.)
- Error formatting: `response()->json(['message' => 'error'], 422)`
- Authentication via Laravel (email as user ID)
- Use Spatie for roles/permissions
- File uploads restricted to jpg, jpeg, png, video, pdf
- Unlimited support layers for ticket escalation

---

## Task List Management

Follow the protocol in `/docs/dev/process-tasks-list.md`:
- Work on one sub-task at a time; pause for user approval before next
- Mark completed sub-tasks `[x]` immediately
- When all subtasks under a parent are `[x]`, run tests, stage, clean up, and commit with conventional message
- Keep "Relevant Files" section updated
- Add new tasks as they emerge
- Update task list file after each significant change

---

## Future Considerations

- Redis for caching/session/queueing
- Docker for containerized deployments
- Kubernetes for orchestration
- CI/CD pipelines for automated testing/deployment

---

## Common Pitfalls to Avoid

1. Do not modify migrations after deployment
2. Do not hardcode environment values; use `env()` or config
3. Do not use `dd()` or `var_dump()` in production
4. Do not mix Blade and JavaScript templating
5. Do not forget `@csrf` token in forms
6. Do not commit `.env` or build artifacts

---

## File References for Common Tasks

| Task | File(s) |
|------|---------|
| Add route | routes/web.php |
| Create model | `php artisan make:model ModelName --migration` |
| Add middleware | `php artisan make:middleware MiddlewareName` |
| Test template | tests/Feature/ExampleTest.php |
| Configuration | config/ (app.php, database.php, mail.php) |
| Frontend styling | resources/css/app.css + Tailwind utilities |

---

*These instructions reflect the latest requirements, tech stack, coding standards, UI/UX conventions, and task management guidelines as described in project documentation.*
### Testing Patterns
1. **Unit Tests**: `tests/Unit/` - Test isolated business logic with Mockery
2. **Feature Tests**: `tests/Feature/` - Test HTTP endpoints and full request/response cycles
3. **Use Pest DSL** over PHPUnit syntax (e.g., `test('can do X', function() { ... })`)
4. Test environment auto-configured with array cache, sync queue, and in-memory DB

## Critical Developer Patterns

### API Route Convention
All new endpoints should:
- Return JSON responses using Laravel's response helpers
- Use appropriate HTTP status codes (201 for creation, 204 for deletion, etc.)
- Include error formatting: `response()->json(['message' => 'error'], 422)`

### Frontend Asset Management
- **Tailwind CSS** is pre-configured; use utility-first approach
- **Bootstrap Icons** available via CDN in Vite config
- **Vite HMR** auto-refreshes on blade file changes when using `@vite()` helper
- Always use `@vite()` in blade templates to inject assets with cache-busting

### Blade Template Conventions
- Location: `resources/views/` (use kebab-case for filenames)
- Use Blade directives: `@if`, `@foreach`, `@include`, `@extends`
- Pass data via controller: `return view('template', ['data' => $value])`
- No inline PHP logic; use view composers or helper functions instead

### Authentication & Authorization
- User model extends `Authenticatable` trait from Laravel
- Use `auth()` helper in routes/controllers
- Consider gate definitions in `AppServiceProvider->boot()` for complex permissions
- Never hardcode auth checks; use middleware and gates

## Integration & External Dependencies

### Gentelella Admin Template
- Located at `/public/gentelella/` with separate Vite config (`public/gentelella/vite.config.js`)
- **Note**: This appears to be a template reference, not integrated into main build
- Contains 33+ pre-built admin dashboard pages using Bootstrap 5
- Modern alternatives: Consider integrating directly into Laravel views if building admin panel

### Common Extensions (if expanded)
- **Mail**: Configure in `config/mail.php`; use `Mailable` classes in `app/Mail/`
- **Queues**: Set `QUEUE_CONNECTION` in `.env`; use `dispatch()` for jobs
- **Caching**: Default to `array` store in tests, Redis/Memcached in production

## Common Pitfalls to Avoid

1. **Don't** modify `database/migrations/` after pushing to team
2. **Don't** hardcode environment values; always use `env()` or config
3. **Don't** use `dd()` or `var_dump()` in production code
4. **Don't** mix Blade and JavaScript templating; use separate concerns
5. **Don't** forget `@csrf` token in forms; Laravel auto-validates when not present
6. **Don't** commit `.env` file or build artifacts (`node_modules/`, `vendor/`)

## File References for Common Tasks

| Task | File(s) |
|------|---------|
| Add route | [routes/web.php](routes/web.php) |
| Create model | `php artisan make:model ModelName --migration` |
| Add middleware | `php artisan make:middleware MiddlewareName` |
| Test template | [tests/Feature/ExampleTest.php](tests/Feature/ExampleTest.php) |
| Configuration | [config/](config/) (app.php, database.php, mail.php) |
| Frontend styling | [resources/css/app.css](resources/css/app.css) + Tailwind utilities |

## Questions to Ask Before Code Generation

- What HTTP method and status codes should this endpoint return?
- Should this require authentication or specific permissions?
- Is this data user-specific or shared across the application?
- Should this trigger background jobs or real-time updates?

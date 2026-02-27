# AGENTS.md

## Cursor Cloud specific instructions

### Overview

TLC 2.0 is a Laravel 12.x + Vite application using SQLite. No external services (Redis, Docker, databases) are required.

### Prerequisites (installed by snapshot)

- PHP 8.2 with extensions: sqlite3, mbstring, xml, curl, zip, gd, bcmath, intl
- Composer (global at `/usr/local/bin/composer`)
- Node.js v22 + npm

### Running the app

- **All-in-one dev:** `composer dev` (runs Laravel server, queue worker, log viewer, and Vite concurrently via `npx concurrently`)
- **Individual services:** see `CLAUDE.md` or `README.md` for `php artisan serve`, `npm run dev`, etc.
- **Build assets:** `npm run build` is required to see UI changes (landing page uses Three.js starfield that needs the production build)

### Admin login (seeded)

- URL: `http://localhost:8000/admin/login`
- Email: `admin@aes.ac.in` / Password: `admin123`
- Regular user login requires Google OAuth (not available in cloud environment)

### Database

- SQLite at `database/database.sqlite`
- Reset: `php artisan migrate:fresh --seed`
- The `.env` defaults (`DB_CONNECTION=sqlite`, `QUEUE_CONNECTION=database`, `CACHE_STORE=database`, `SESSION_DRIVER=database`) work out of the box

### Testing

- `composer test` or `./vendor/bin/phpunit` — 15/17 tests pass; 2 pre-existing failures:
  - `ExampleTest::test_the_application_returns_a_successful_response` — test uses in-memory SQLite without `RefreshDatabase`, missing `pl_wednesday_settings` table
  - `FullSessionEnrollmentTest::test_user_cannot_join_full_ttt_session` — references undefined route `spring.ttt.join`

### Lint

- `./vendor/bin/pint --test` checks style; `./vendor/bin/pint` auto-fixes

### Gotchas

- Apache2 is installed as a PHP dependency but is not used; the app runs via `php artisan serve`
- The `.env` file and `database/database.sqlite` must exist before running artisan commands; `cp .env.example .env && php artisan key:generate` if missing

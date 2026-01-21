# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

TLC 2.0 is a Laravel 12.x web application for managing Professional Development activities at the American Embassy School (AES). It handles PD Days, Wellness Sessions, PL Wednesday sessions, and Collaborative Community Learning Sessions (CCL) programs.

## Development Commands

```bash
# Start development environment (runs server, queue, logs, Vite concurrently)
composer dev

# Individual commands
php artisan serve              # Start dev server at localhost:8000
npm run dev                    # Run Vite dev server for frontend assets
php artisan migrate            # Run database migrations
php artisan db:seed            # Seed database with sample data
php artisan migrate:fresh --seed  # Reset and reseed database

# Testing
composer test                  # Run all PHPUnit tests
./vendor/bin/phpunit --filter=TestName  # Run specific test
./vendor/bin/phpunit tests/Feature/SomeTest.php  # Run single test file

# Code quality
./vendor/bin/pint              # Fix code style with Laravel Pint
```

## Architecture

### Authentication System
- **Regular users**: Google OAuth 2.0 via Laravel Socialite - validates against AES email domains
- **Admin users**: Traditional email/password login at `/admin/login`
- **Middleware**: `AdminMiddleware` (admin routes), `UserOnly` (user routes), `NoAdminAccess` (blocks admin from user routes)
- Division auto-detected from email patterns (es@, ms@, hs@)

### Feature Toggle Pattern
Each major feature has a settings model that enables/disables it globally:
- `PLDaysSetting` - Fall/Spring PD Days
- `WellnessSetting` - Wellness Sessions
- `PLWednesdaySetting` - PL Wednesday
- `CCLSetting` - Collaborative Community Learning Sessions

Check toggle state before rendering features: `PLWednesdaySetting::first()->is_active`

### Polymorphic "My PL" System
`UserSelectedSession` uses polymorphic `selectable` relation to track user selections across all session types:
- `selectable_type`: Model class (ScheduleItem, WellnessSession, PLWednesdaySession, CCLSession)
- `selectable_id`: Session primary key

### Layout System
- `layouts/app.blade.php` - Admin layout (dark slate header)
- `layouts/user.blade.php` - User-facing layout
- Views organized by feature: `admin/[feature]/`, `schedule/`, `wellness/`, `pl-wednesday/`, `my-pl/`

### Key Model Relationships
- `PDDay` has many `ScheduleItem`, `WellnessSession`, `CCLSession`
- `ScheduleItem` belongs to many `Division` via pivot table
- `ScheduleItem` has many `ScheduleItemLink`
- Sessions morph many `UserSelectedSession`

## Database Conventions

- Table names: snake_case with `p_d_days` pattern for abbreviations
- Foreign keys: `user_id`, `p_d_day_id`, etc.
- Feature toggles: `[feature]_settings` tables with `is_active` boolean
- Links tables: `[feature]_links` with `url`, `label`, polymorphic parent

## Route Organization

```
/                           # Home/dashboard
/fall-pl-day, /spring-pl-days  # PD Day schedules (user)
/wellness                   # Wellness sessions (user)
/pl-wednesday               # PL Wednesday (user)
/my-pl                      # User's selected sessions
/admin/*                    # All admin CRUD routes
```

Routes use implicit model binding and named routes throughout.

## Frontend Stack

- Tailwind CSS v4 via Vite
- Font Awesome 6.0.0 for icons
- Vanilla JS with Axios for HTTP requests
- Brand colors: Navy (#1e3a5f), Cream (#f5f0e1), Gold (#c9a227), Orange (#e07c24)

## CSV Import

Schedule items support CSV bulk import. Template columns:
- Required: title, date, start_time, end_time
- Optional: description, presenter, location, capacity, divisions, link_url, link_label

Divisions use pipe separator: `ES|MS|HS`

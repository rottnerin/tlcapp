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
npm run build                  # Build frontend assets for production; required to see all UI/UX changes (e.g. landing page)
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

### User Enrollment Behavior
Session enrollment works differently for admin and regular users:

**Admin Users** (for testing and management):
- Can join multiple sessions across all types (Wellness, CCL, etc.)
- Can unjoin any session they're enrolled in via red "Unjoin" button
- For Wellness: When joining a new session, previous enrollment is automatically cancelled
- See an "Unjoin" button on enrolled sessions (red button, top-right of card)
- Can freely switch between sessions for testing purposes
- Identified by `is_admin = true` in users table

**Regular End Users**:
- **Wellness Sessions**: Can only be in ONE wellness session at a time
  - Clicking "Join" on a different wellness session automatically cancels their current enrollment and enrolls them in the new one (they can switch sessions)
  - All wellness sessions show "Join Session" when not enrolled in that specific session
- **CCL Sessions**: Can join ONE CCL session per time slot
  - Users can join multiple CCL sessions if they're at different times (e.g., one at 10:00 AM, one at 1:00 PM)
  - Cannot join two CCL sessions at the same time (e.g., both at 10:00 AM)
  - Attempting to join a second session at same time shows error: "You are already enrolled in a CCL session at this time"
  - Time conflict check also prevents joining CCL if it overlaps with enrolled Wellness session
- **Cannot unjoin any sessions** - Do NOT see "Unjoin" button
  - Must contact admin to be removed from sessions
  - Admin must manually cancel enrollment from admin panel

**Important Implementation Notes**:
- `WellnessController@enroll` auto-cancels previous wellness enrollment when joining a new session (same for all users)
- `WellnessController@unjoin` restricted to admin users only
- `CCLController@unjoin` also restricted to admin users only
- Eager loading filters: `->where('status', '!=', 'cancelled')` to show only active enrollments
- View logic uses `$isAdmin` to conditionally render the unjoin button
- Tests: See `tests/Feature/WellnessUserTypesTest.php` for behavioral differences

**Session deletion (Well-being and CCL)**:
- **Well-being (Wellness) sessions** and **CCL sessions** must not be deleted without **double confirmation** from an admin (e.g. confirm dialog plus a second step, or type-to-confirm). Implement and preserve this in admin delete flows.

**Joined-state visual feedback (fundamental — do not change)**:
- When a user joins a Wellness or CCL session, the session card **must** change to a distinct “joined” state so it is obvious which session they are in.
- **Wellness**: Enrolled cards use the `.joined` class — green gradient, “✓ JOINED” badge (top-right), “Enrolled” disabled button. Same for end users and admins; admins also see the red “Unjoin” button.
- **CCL**: Enrolled cards use the `.joined` class — amber/gold gradient, “✓ JOINED” badge (top-right), “Joined” disabled button. Same for end users and admins; admins also see the red “Unjoin” button.
- This behavior is fundamental to the app and should not be removed or altered (e.g. do not remove the card color change or badge, or make joined state less visible).

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

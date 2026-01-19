# TLC 2.0 - Professional Learning Management System

A Laravel-based web application for managing Professional Development activities at the American Embassy School (AES). The system handles PD Days, Wellness Sessions, PL Wednesday sessions, and Teachers Teaching Teachers (TTT) programs.

![TLC 2.0 Dashboard](docs/screenshots/schedule-view.png)

## Features

### For Users

| Feature | Description |
|---------|-------------|
| **My PL** | Track and manage your selected professional learning sessions |
| **Fall PL Day** | Browse schedule and wellness sessions for Fall PD Day |
| **Spring PL Days** | Browse schedule, wellness, and TTT sessions for Spring PD Days |
| **PL Wednesday** | View Professional Learning sessions (Wed 3:00-5:00pm) |
| **Google Sign-In** | Secure authentication via Google OAuth |

### For Administrators

| Feature | Description |
|---------|-------------|
| **PL Days Management** | Create, edit, activate, and archive PD Days |
| **Schedule Management** | Bulk operations, CSV import, copy between PL Days |
| **Wellness Sessions** | Enrollment tracking, user transfers, capacity management |
| **TTT Sessions** | Teachers Teaching Teachers session management |
| **PL Wednesday** | Global toggle, session management with multiple links |
| **Reports** | Enrollments, capacity utilization, division summaries |
| **User Management** | View users, manage admin access |

## Screenshots

### User Schedule View
The schedule view shows sessions organized by day with division filtering:

![Schedule View](docs/screenshots/schedule-view.png)

### Admin PL Days Management
Manage PL Days with active/inactive/archived status:

![Admin PL Days](docs/screenshots/admin-pddays.png)

### Archive Navigation
Archived PL Days appear in the sub-navigation for easy access:

![Archive Navigation](docs/screenshots/archive-nav.png)

## Quick Start

```bash
# Clone and install
git clone https://github.com/rottnerin/tlcapp.git
cd tlcapp
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Set up database
php artisan migrate
php artisan db:seed

# Start development
composer dev
```

The `composer dev` command runs the server, queue worker, logs, and Vite concurrently.

## Technology Stack

- **Framework**: Laravel 12.x
- **Frontend**: Blade Templates, Tailwind CSS v4, Vite
- **Authentication**: Google OAuth (users), Email/Password (admins)
- **Database**: SQLite / MySQL / PostgreSQL
- **PHP**: 8.2+

## Environment Configuration

Configure your `.env` file:

```env
# Database
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Google OAuth
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

## Architecture

### Authentication
- **Regular users**: Google OAuth via Laravel Socialite (validates AES email domains)
- **Admin users**: Traditional email/password at `/admin/login`

### Feature Toggles
Each feature can be enabled/disabled globally:
- PL Days (Fall/Spring)
- Wellness Sessions
- PL Wednesday
- Teachers Teaching Teachers (TTT)

### PL Day Archive System
PL Days have three states:
- **Active** (green): Currently shown to users
- **Inactive** (gray): Not visible, can be edited
- **Archived** (purple): Read-only, accessible via navigation tabs

### My PL System
Users can add sessions to "My PL" across all session types using a polymorphic relationship system.

## Database Structure

| Table | Description |
|-------|-------------|
| `users` | User accounts with division info |
| `p_d_days` | Professional Development Days |
| `schedule_items` | Schedule items linked to PD Days |
| `wellness_sessions` | Wellness session offerings |
| `ttt_sessions` | Teachers Teaching Teachers sessions |
| `pl_wednesday_sessions` | PL Wednesday sessions |
| `user_selected_sessions` | Polymorphic "My PL" selections |
| `divisions` | ES, MS, HS, All School |

## Development Commands

```bash
# Start development environment
composer dev

# Run tests
composer test

# Fix code style
./vendor/bin/pint

# Clear all caches
php artisan cache:clear && php artisan config:clear && php artisan view:clear
```

## Brand Colors

| Color | Hex | Usage |
|-------|-----|-------|
| Navy | `#1e3a5f` | Primary headers, accents |
| Cream | `#f5f0e1` | Backgrounds |
| Gold | `#c9a227` | Highlights, active states |
| Orange | `#e07c24` | CTAs, buttons |

## License

Proprietary software for the American Embassy School.

## Support

For issues or questions, contact the AES development team.

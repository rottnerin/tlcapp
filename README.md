# TLC 2.0 - Teaching and Learning Conference Application

A Laravel-based web application for managing Professional Development (PD) Days, Wellness Sessions, and Professional Learning Wednesday sessions at the American Embassy School (AES).

## Features

### User Features

- **Schedule View**: Browse and view schedule items for PL Days, organized by day (Day 1/Day 2)
- **Wellness Sessions**: View available wellness sessions and enroll in sessions
- **PL Wednesday**: View Professional Learning sessions scheduled for Wednesday afternoons (3:00-5:00pm)
- **Profile Management**: Update user profile information
- **Google OAuth**: Secure authentication via Google OAuth

### Admin Features

- **User Management**: View users, toggle admin status, and manage passwords
- **PL Days Management**: Create, edit, and manage Professional Development Days
- **Schedule Management**: Create and manage schedule items, bulk operations, CSV upload, and copy schedules between PL Days
- **Wellness Session Management**: Create, edit, and manage wellness sessions with enrollment tracking and user transfers
- **PL Wednesday Management**: 
  - Activate/deactivate the PL Wednesday feature globally
  - Create, edit, and manage individual PL Wednesday sessions
  - Add multiple links/resources to each session
  - Set session dates, times, locations, and descriptions
- **Reports**: Generate various reports including:
  - Wellness enrollments
  - Unenrolled users
  - Capacity utilization
  - Division summary
  - User activity

## PL Wednesday Feature

The PL Wednesday feature allows administrators to manage Professional Learning sessions that occur every Wednesday afternoon from 3:00-5:00pm, starting August 6th, 2025 and ending December 16th, 2025.

### Admin Capabilities

- **Global Toggle**: Activate or deactivate the entire PL Wednesday feature
- **Session Management**: Create sessions with:
  - Title and description
  - Location
  - Date (must be a Wednesday within the date range)
  - Start and end times
  - Multiple links/resources per session
- **Session Status**: Activate or deactivate individual sessions

### User Experience

- All authenticated users can view PL Wednesday sessions via the "PL Wednesday" navigation tab
- Sessions are displayed in descending order by date (most recent first)
- Within each date, sessions are ordered by start time (earliest first)
- Each session is displayed in its own card with AES black and gold color scheme
- Users can view session details including all associated links and resources

## Technology Stack

- **Framework**: Laravel 11.x
- **Frontend**: Blade Templates with Tailwind CSS
- **Authentication**: Google OAuth for users, traditional login for admins
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **PHP**: 8.2+

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd tlcapp
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Copy the environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your `.env` file with database credentials and Google OAuth settings:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

7. Run migrations:
```bash
php artisan migrate
```

8. Seed the database (optional):
```bash
php artisan db:seed
```

9. Build frontend assets:
```bash
npm run build
```

10. Start the development server:
```bash
php artisan serve
```

11. In another terminal, start the Vite dev server (for development):
```bash
npm run dev
```

## Database Structure

### Key Tables

- `users`: User accounts with division and authentication information
- `p_d_days`: Professional Development Days
- `schedule_items`: Schedule items for PL Days
- `wellness_sessions`: Wellness session offerings
- `user_sessions`: User enrollments in wellness sessions
- `pl_wednesday_sessions`: PL Wednesday session information
- `pl_wednesday_links`: Links/resources associated with PL Wednesday sessions
- `pl_wednesday_settings`: Global settings for PL Wednesday feature
- `divisions`: User divisions (ES, MS, HS)

## Admin Access

Admins can log in at `/admin/login` using credentials set up in the database. Regular users authenticate via Google OAuth.

## Development

### Running Tests
```bash
php artisan test
```

### Clearing Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Database Seeding
The application includes several seeders for testing:
- `AdminUserSeeder`: Creates admin user
- `DivisionSeeder`: Creates divisions
- `PDDaySeeder`: Creates sample PL Days
- `ScheduleItemSeeder`: Creates sample schedule items
- `WellnessSessionSeeder`: Creates sample wellness sessions
- `PLWednesdaySessionSeeder`: Creates sample PL Wednesday sessions

## License

This application is proprietary software for the American Embassy School.

## Support

For issues or questions, please contact the development team.

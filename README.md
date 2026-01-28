# TLC 2.0 - Professional Learning Management System

A Laravel-based platform for managing professional development at the American Embassy School. Teachers discover sessions, enroll in programs, and track their learning journey across PD Days, Wellness Sessions, PL Wednesday, and CCL (Collaborative Community Learning).

## Table of Contents

- [Overview](#overview)
- [Information Flow](#information-flow)
  - [User Journey](#user-journey)
  - [Admin Workflow](#admin-workflow)
  - [Data Architecture](#data-architecture)
- [Core Features](#core-features)
- [Quick Start](#quick-start)
- [System Architecture](#system-architecture)
- [Technology Stack](#technology-stack)
- [Architecture Details](#architecture-details)
- [Database Schema](#database-schema)
- [Development Commands](#development-commands)

## Overview

TLC 2.0 centralizes professional learning management with dual interfaces: a teacher portal for browsing and enrolling in sessions, and an admin dashboard for program coordination. The system supports multiple PD formats with division-specific filtering (ES/MS/HS) and capacity management.

**Key Capabilities:**
- 🎯 **Session Discovery** - Teachers browse PD sessions by division and time
- 📅 **My PL Dashboard** - Unified view of all enrolled sessions across programs
- 🔄 **Flexible Enrollment** - Add/remove sessions with real-time capacity tracking
- 📊 **Admin Reporting** - Track enrollments, capacity, and participation
- 🏷️ **Feature Toggles** - Enable/disable programs globally (PD Days, Wellness, PL Wed, CCL)

## Information Flow

### User Journey

```mermaid
graph LR
    A[Teacher] -->|Google OAuth| B[Home Dashboard]
    B --> C[Browse Sessions]
    C -->|PD Days| D[Schedule Items]
    C -->|Wellness| E[Wellness Sessions]
    C -->|PL Wednesday| F[PL Wed Sessions]
    C -->|CCL| G[CCL Sessions]
    D --> H[Enroll]
    E --> H
    F --> H
    G --> H
    H --> I[My PL Dashboard]
    I -->|View/Print| J[Personal Schedule]
```

### Admin Workflow

```mermaid
graph TD
    A[Admin] -->|Email/Password| B[Admin Dashboard]
    B --> C[Create PD Day]
    C --> D[Add Sessions]
    D -->|Schedule Items| E[Set Divisions]
    D -->|Wellness| F[Set Capacity]
    D -->|CCL| G[Configure Links]
    E --> H[Activate PD Day]
    F --> H
    G --> H
    H --> I[Teachers See Sessions]
    I --> J[Enrollment Tracking]
    J --> K[Generate Reports]
    K -->|Capacity| L[Analytics]
    K -->|Division Summary| L
    K -->|Participant Lists| L
```

### Data Architecture

```mermaid
erDiagram
    USERS ||--o{ USER_SELECTED_SESSIONS : enrolls
    USERS }o--|| DIVISIONS : belongs_to
    P_D_DAYS ||--o{ SCHEDULE_ITEMS : contains
    P_D_DAYS ||--o{ WELLNESS_SESSIONS : contains
    P_D_DAYS ||--o{ CCL_SESSIONS : contains
    SCHEDULE_ITEMS }o--o{ DIVISIONS : targets
    USER_SELECTED_SESSIONS }o--|| SCHEDULE_ITEMS : polymorphic
    USER_SELECTED_SESSIONS }o--|| WELLNESS_SESSIONS : polymorphic
    USER_SELECTED_SESSIONS }o--|| PL_WEDNESDAY_SESSIONS : polymorphic
    USER_SELECTED_SESSIONS }o--|| CCL_SESSIONS : polymorphic
```

## Core Features

### 👥 For Teachers
- **My PL** - Personal dashboard showing all enrolled sessions across programs
- **Session Browser** - Filter by division (ES/MS/HS), date, and session type
- **One-Click Enrollment** - Add sessions with real-time capacity updates
- **Print Schedule** - Export personal schedule for offline reference

### 🛠️ For Administrators
- **PD Day Management** - Create/edit/archive PD Days with active/inactive states
- **Bulk Operations** - CSV import, copy sessions between PD Days
- **Enrollment Management** - Transfer users, adjust capacity, track waitlists
- **Reporting Suite** - Capacity utilization, division summaries, participant lists, CCL enrollments
- **Feature Toggles** - Enable/disable entire programs globally

## Quick Start

```bash
# 1. Clone and install dependencies
git clone https://github.com/rottnerin/tlcapp.git
cd tlcapp
composer install && npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Set up database
php artisan migrate --seed

# 4. Start development (runs server, queue, logs, Vite)
composer dev
```

Access the application at `http://localhost:8000`

### Environment Setup

```env
# Database
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# Google OAuth (Required for teacher login)
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

## System Architecture

```mermaid
graph TB
    subgraph "Frontend"
        A[Blade Templates]
        B[Tailwind CSS v4]
        C[Vanilla JS + Axios]
    end

    subgraph "Application Layer"
        D[Laravel 12.x]
        E[Authentication]
        F[Controllers]
        G[Feature Toggles]
    end

    subgraph "Data Layer"
        H[Eloquent ORM]
        I[(Database)]
        J[Migrations/Seeders]
    end

    subgraph "External Services"
        K[Google OAuth 2.0]
    end

    A --> F
    B --> A
    C --> F
    F --> H
    E --> K
    E --> F
    G --> F
    H --> I
    J --> I

    style D fill:#0d3b66,color:#fff
    style I fill:#f4d35e,color:#0d3b66
    style K fill:#ee964b,color:#fff
```

## Technology Stack

| Layer | Technology | Purpose |
|-------|-----------|---------|
| **Framework** | Laravel 12.x | Backend application framework |
| **Frontend** | Blade, Tailwind CSS v4, Vite | Server-rendered templates with utility CSS |
| **JavaScript** | Vanilla JS, Axios | Minimal client-side interactivity |
| **Authentication** | Google OAuth, Email/Password | Dual auth system (users/admins) |
| **Database** | SQLite / MySQL / PostgreSQL | Flexible data storage |
| **PHP** | 8.2+ | Server runtime |


## Architecture Details

### Authentication Flow

```mermaid
sequenceDiagram
    participant T as Teacher
    participant G as Google OAuth
    participant S as TLC System
    participant A as Admin

    T->>S: Click "Continue with Google"
    S->>G: Redirect to Google Auth
    G->>T: Enter credentials
    G->>S: Return user profile
    S->>S: Validate AES email domain
    S->>T: Grant access (auto-assign division)

    A->>S: Navigate to /admin/login
    S->>A: Show admin login form
    A->>S: Submit email/password
    S->>S: Verify credentials
    S->>A: Grant admin access
```

### PD Day Lifecycle

```mermaid
stateDiagram-v2
    [*] --> Created: Admin creates PD Day
    Created --> Inactive: Initial state
    Inactive --> Active: Admin activates
    Active --> Inactive: Admin deactivates
    Inactive --> Archived: Admin archives
    Active --> Archived: Admin archives
    Archived --> [*]: Read-only access

    note right of Active
        Visible to teachers
        Enrollment enabled
    end note

    note right of Inactive
        Hidden from teachers
        Admin can edit
    end note

    note right of Archived
        Read-only
        Historical reference
    end note
```

### Feature Toggle System

Each program has a global toggle controlling visibility:

| Feature | Setting Model | Controls |
|---------|---------------|----------|
| PL Days | `PLDaysSetting` | Fall/Spring PD Day access |
| Wellness | `WellnessSetting` | Wellness session enrollment |
| PL Wednesday | `PLWednesdaySetting` | Wednesday PL sessions |
| CCL | `CCLSetting` | Collaborative Community Learning |

### My PL Polymorphic System

The `UserSelectedSession` model uses polymorphic relationships to unify enrollments:

```php
$userSelection->selectable_type  // 'ScheduleItem', 'WellnessSession', etc.
$userSelection->selectable_id    // Session primary key
```

This enables a single "My PL" dashboard showing all enrollments regardless of session type.

### Enrollment & Capacity Flow

```mermaid
graph TD
    A[Teacher Clicks Enroll] --> B{Check Capacity}
    B -->|Spots Available| C[Create Enrollment]
    B -->|At Capacity| D[Show Full Message]
    C --> E[Update Capacity Count]
    E --> F[Add to My PL]
    F --> G[Show Success Message]

    H[Teacher Clicks Unenroll] --> I[Remove Enrollment]
    I --> J[Decrease Capacity Count]
    J --> K[Update My PL]
    K --> L[Open Spot for Others]

    style C fill:#f4d35e,color:#0d3b66
    style D fill:#ee964b,color:#fff
    style F fill:#0d3b66,color:#fff
```

## Database Schema

**Core Tables:**
- `users` - Teacher/admin accounts with division assignment
- `p_d_days` - PD Day definitions (Fall/Spring)
- `divisions` - ES, MS, HS, All School

**Session Types:**
- `schedule_items` - PD Day schedule sessions
- `wellness_sessions` - Wellness offerings
- `pl_wednesday_sessions` - Wednesday PL sessions
- `ccl_sessions` - Collaborative Community Learning

**Enrollment:**
- `user_selected_sessions` - Polymorphic enrollment tracking (My PL)

**Settings:**
- `*_settings` tables - Feature toggles for each program

## Development Commands

| Command | Purpose |
|---------|---------|
| `composer dev` | Start server + queue + logs + Vite |
| `composer test` | Run PHPUnit test suite |
| `./vendor/bin/pint` | Fix code style (Laravel Pint) |
| `php artisan cache:clear` | Clear application cache |

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

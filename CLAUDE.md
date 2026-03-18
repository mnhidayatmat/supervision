# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**ResearchFlow** is a Student Research Supervision Management System built with Laravel 13 and PHP 8.3. It manages the complete research supervision lifecycle for postgraduate students, including task management, progress reporting, meetings, file management, and AI-powered assistance.

## Development Commands

```bash
# Initial setup (installs dependencies, generates .env, runs migrations, builds assets)
composer run setup

# Full development environment (server, queue, logs, vite in parallel)
composer run dev

# Individual services
php artisan serve              # Laravel server on port 8000
php artisan queue:listen       # Queue worker
php artisan pail               # Log monitoring
npm run dev                    # Vite dev server

# Build production assets
npm run build

# Run tests
composer test
php artisan test

# Laravel Pint (code formatting)
./vendor/bin/pint

# Database
php artisan migrate:fresh --seed
```

## Architecture

### Multi-Role System

Users have a single `role` field with four possible values:
- **admin** - Full system access
- **supervisor** - Primary academic supervisor
- **cosupervisor** - Secondary academic supervisor
- **student** - Research student

Routes are organized by role with middleware protection:
- `admin/*` → `middleware(['auth', 'role:admin'])`
- `supervisor/*` → `middleware(['auth', 'role:supervisor,cosupervisor'])`
- `student/*` → `middleware(['auth', 'role:student'])`

### Authorization Patterns

1. **Role-based routing** - Uses `RoleMiddleware` for role checking
2. **Policy-based access** - Laravel policies for resource-level authorization (Student, Task, ProgressReport)
3. **Audit logging** - `AuditActivity` middleware tracks user actions

Key authorization rules:
- Admins can access everything
- Supervisors can view/manage their assigned students
- Students can only view their own data
- Cosupervisors share supervisor privileges for their assigned students

### Core Models and Relationships

```
User (has role field)
├── Student (links user to programme, has supervisor_id and cosupervisor_id)
│   └── ResearchJourney (student's research timeline with milestones)
│       └── Task (hierarchical, belongs to milestone)
│           ├── TaskDependency (depends_on relationship)
│           └── Revision (task revision history)
├── Programme (academic programmes)
├── ProgressReport (periodic progress submissions with revisions)
├── Meeting (supervisory meetings with action items)
├── File/Folder (document management with versioning)
└── AiConversation/AiMessage (AI chat integration)
```

### Task Status Workflow

Tasks follow a strict status progression:
```
backlog → planned → in_progress → waiting_review → revision → completed
```

The `TaskPolicy` defines who can review tasks (only supervisors/admins).

### Route Organization

**routes/web.php** is organized into four sections:

1. **Guest routes** - Login/register (lines 15-21)
2. **Admin routes** - `/admin/*` prefix (lines 37-54)
3. **Supervisor routes** - `/supervisor/*` prefix (lines 56-61)
4. **Student routes** - `/student/*` prefix (lines 63-66)
5. **Shared resource routes** - Policy-based access for tasks, reports, meetings, files (lines 68-109)

**routes/api.php** - API endpoints for frontend (Kanban, Gantt, AI Chat, Notifications)

### Blade Component Structure

```
resources/views/
├── layouts/
│   ├── app.blade.php          # Main authenticated layout
│   ├── sidebar.blade.php      # Navigation sidebar
│   └── guest.blade.php        # Non-authenticated layout
├── components/
│   ├── layouts/               # Layout wrappers
│   ├── stat-card.blade.php    # Dashboard stat cards
│   ├── card.blade.php         # Generic card container
│   ├── button.blade.php       # Button component
│   ├── status-badge.blade.php # Status indicator
│   └── ...
└── {role}/                    # Role-specific views
    ├── dashboard.blade.php
    └── ...
```

### Tailwind CSS Configuration

The app uses Tailwind CSS 4 via CDN with a custom color palette defined in `layouts/app.blade.php`:
- `surface: #F7F7F5` - Main background
- `card: #FFFFFF` - Card backgrounds
- `border: #E5E7EB` - Borders
- `primary: #1F2937` - Main text
- `secondary: #6B7280` - Muted text
- `accent: #D97706` - Amber for actions/highlights

### Frontend Stack

- **Vite** for asset bundling
- **Tailwind CSS 4** via CDN
- **Alpine.js** for reactive components
- **Axios** for HTTP requests
- **Chart.js** for data visualization

### Key Controllers by Role

| Role | Controllers | Location |
|------|-------------|----------|
| Admin | DashboardController, StudentManagementController, ProgrammeController, JourneyTemplateController, SettingsController | `app/Http/Controllers/Admin/` |
| Supervisor | DashboardController, StudentViewController | `app/Http/Controllers/Supervisor/` |
| Student | DashboardController | `app/Http/Controllers/Student/` |
| Shared | TaskController, ProgressReportController, MeetingController, FileController, AiChatPageController | `app/Http/Controllers/` |

### Settings Architecture

Admin settings are stored in `config/settings.php`:
- `storage.*` - File storage configuration
- `ai.*` - AI service configuration (provider, API key, model)
- Settings can be updated via admin panel and are persisted to config file

### File Versioning

Files support versioning through the `revisions` table. When uploading a new version:
1. Original file is preserved
2. New version stored with incremented version number
3. Revision record tracks who uploaded and when

# DetergentPlanning Project Context

## Project Overview

**DetergentPlanning** is a daily planning and productivity application built with the **Laravel** framework. It appears to implement a specific planning methodology (possibly "ETP" - Effective Time Planning) that emphasizes:

*   **Prioritization:** Distinguishing between **Major** (Top Priority) and **Minor** tasks.
*   **Time Blocking:** Allocating specific time slots for tasks to ensure focus.
*   **Interrupt Management:** Explicitly tracking unexpected interruptions (bugs, urgent requests) to measure their impact on the schedule.
*   **Daily Review:** A dashboard-centric view (`DayController`) that aggregates tasks, time blocks, and notes for a specific date.

## Architecture & Tech Stack

The project follows the standard **Laravel MVC** architecture:

*   **Backend:** PHP 8.2+, Laravel Framework 12.x.
*   **Frontend:** Blade Templates, **Tailwind CSS 4.x**, managed via **Vite**.
*   **Database:** Relational database (Schema configured via standard Laravel Migrations). Default setup likely uses SQLite.
*   **Testing:** PHPUnit 11.x.

### Key Entities (Models)

*   **Day**: The central anchor for planning.
*   **Task**: Work items linked to a Day. Fields include `priority` ('major', 'minor'), `status`, and time estimates.
*   **TimeBlock**: Scheduled chunks of time on a Day, optionally linked to a Task.
*   **Interrupt**: Unplanned events linked to a Day. Tracks `requester`, `origin` (email, phone, etc.), and `duration`.
*   **Note**: Free-form text notes for a Day.
*   **Setting**: User preferences (Theme, Time Block Size).

## Development Workflow

### Prerequisites

*   PHP 8.2 or higher
*   Composer
*   Node.js & NPM

### Setup & Running

1.  **Install Dependencies:**
    ```bash
    composer install
    npm install
    ```

2.  **Environment Setup:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    touch database/database.sqlite # If using SQLite
    ```

3.  **Database Migration:**
    ```bash
    php artisan migrate
    ```

4.  **Build Frontend Assets:**
    ```bash
    npm run build # For production
    # OR
    npm run dev   # For development (hot reload)
    ```

5.  **Run Server:**

* dev server runs on http://detergentplanning.test/ via Laravel Valet

### Testing

Run the test suite using standard Laravel commands:

```bash
php artisan test
```

## Directory Structure Highlights

*   `app/Http/Controllers/`: Contains the logic for Days, Tasks, Interrupts, etc.
*   `app/Models/`: Eloquent models defining the relationships between Days, Tasks, etc.
*   `database/migrations/`: Defines the database schema.
*   `resources/views/`: Blade templates for the UI. `layouts/etp.blade.php` is the main layout.
*   `routes/web.php`: Defines the application routes.

## Conventions

*   **Styling:** Utility-first CSS using Tailwind. Dark mode is supported via a custom variant in `app.css`.
*   **Routing:** Resourceful routing is used where possible (e.g., `Route::resource('days.tasks', ...)`).
*   **Code Style:** Follows standard Laravel PSR-4 autoloading and coding standards.

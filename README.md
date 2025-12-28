# Detergent Planning

![Detergent Planning](assets/detergent.jpg)

**Detergent Planning** is a focused, distraction-aware daily planner built for clarity and efficiency. It implements the "Effective Time Planning" methodology, helping you scrub away the chaos of a busy workday by compartmentalizing tasks, managing interruptions, and enforcing realistic time blocking.

Built with **Laravel 12** and **Tailwind CSS 4**.

---

## 🌟 Key Features

*   **Smart Time Grid**: Automatically populate your day with time blocks based on your work hours and break preferences.
*   **Task Categorization**: Distinctly manage **Major Tasks** (Deep Work), **Minor Tasks** (Quick wins), **Meetings**, and **Leisure** time.
*   **Interrupt Tracking**: Explicitly record and schedule unexpected interruptions to visualize their impact on your productivity.
*   **Drag & Drop Scheduling**:
    *   **Assign**: Drag tasks directly onto time blocks to schedule them.
    *   **Merge**: Drag adjacent time blocks onto each other to combine them into longer sessions.
*   **Dark Mode**: A fully supported, eye-friendly dark theme for late-night planning.
*   **Daily Context**: A persistent "Notes & Scribbles" area for every day to capture fleeting thoughts.

## 🛠️ Installation

### Prerequisites
*   PHP 8.2 or higher
*   Composer
*   Node.js & NPM

### Setup Guide

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/pforret/DetergentPlanning.git
    cd DetergentPlanning
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Install Frontend dependencies:**
    ```bash
    npm install
    ```

4.  **Environment Configuration:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    touch database/database.sqlite
    ```

5.  **Run Migrations:**
    ```bash
    php artisan migrate
    ```

6.  **Build Assets:**
    ```bash
    npm run build
    ```

7.  **Start the Server:**
    ```bash
    php artisan serve
    ```
    Visit `http://localhost:8000` in your browser.

## 🚀 Usage

### 1. Configure Your Day
Head to the **Settings** (gear icon) to define your typical workday:
*   Set your **Start** and **End** times.
*   Define your **Lunch** window.
*   Enable automated **Morning** and **Afternoon breaks**.
*   Choose your preferred **Time Block Size** (e.g., 15 minutes).

### 2. Plan the Day
*   **Populate**: Click the "Populate" button on the dashboard to generate available time slots based on your settings.
*   **Create Tasks**: Use the specific "Add" buttons to create Major Tasks, Meetings, or record Interrupts.
*   **Schedule**: Drag your tasks onto the available slots in the Time Grid.
*   **Adjust**: Need a longer slot? Drag an empty block onto an adjacent one to merge them instantly.

### 3. Track & Adapt
*   **Interrupts**: When the unexpected happens, record it as an "Interrupt". You can then drag it onto the grid to show exactly when it happened and how long it took.
*   **Cleanup**: Use the "Cleanup" button to remove any unused time blocks at the end of the day for a clean view.

## 🎨 Customization

Detergent Planning supports both **Light** and **Dark** themes, respecting your system preferences or manually togglable via Settings.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

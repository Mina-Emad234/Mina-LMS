# 🎓 Mini-LMS

A modern, full-featured Learning Management System built with **Laravel 13**, **Livewire/Volt**, and **Filament v5** for the admin panel. The student-facing UI was designed and generated using **Google Stitch** for a polished, editorial aesthetic.

🔗 **Repository:** [github.com/Mina-Emad234/Mina-LMS](https://github.com/Mina-Emad234/Mina-LMS.git)

---

## 📋 Table of Contents

- [Installation](#-installation)
- [Admin Credentials](#-admin-credentials)
- [Running Tests](#-running-tests)
- [Project Flow](#-project-flow)
- [Technical Decisions & Trade-offs](#-technical-decisions--trade-offs)

---

1. **Clone the repository** to your local machine and navigate into the project directory:
```bash
git clone https://github.com/Mina-Emad234/Mina-LMS.git
cd Mina-LMS
```

2. **Install PHP dependencies** using Composer. This will install Laravel and all required backend packages:
```bash
composer install
```

3. **Install JavaScript dependencies** using NPM. This is required for the frontend and Filament assets:
```bash
npm install
```

4. **Copy the environment files**. You need a `.env` for the main application and a `.env.test` for running the Pest test suite:
```bash
cp .env.example .env
cp .env.example .env.test
```

5. **Generate the application keys** for both your development and testing environments:
```bash
php artisan key:generate
php artisan key:generate --env=test
```

6. **Run migrations and seed the database**. This will create the table structure and populate it with categories, levels, and the admin user:
```bash
php artisan migrate --seed
```

7. **Build frontend assets**. This compiles the CSS and JS for production-like performance:
```bash
npm run build
```

8. **Start the development server**. Once running, you can access the LMS at `http://localhost:8000`:
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`.

---

## 🔑 Admin Credentials

| Role  | Email             | Password   |
|-------|-------------------|------------|
| Admin | `admin@admin.com` | `password` |

Admin panel is accessible at `/admin`, or by clicking **"Admin Panel"** in the profile dropdown on the top navigation bar (only visible to admin users).

---

## 🧪 Running Tests

```bash
./vendor/bin/pest
```

Test suites include:
- **CourseServiceTest** — Filament CRUD operations (list, create, edit, delete, validate, view)
- **EnrolementServiceTest** — Student enrollment flow via Livewire
- **ExampleTest** — Smoke test

---

## 🔄 Project Flow

### Guest (Unauthenticated)

```
Courses Index (/courses)
  │
  ├── Browse & filter courses (category, level, sort)
  ├── Search courses by title
  │
  └── Course Detail (/courses/{slug})
        ├── View course info, instructor, curriculum
        └── "Sign In to Enroll" button → redirects to Login
```

### Authentication

```
Register (/register)
  └── Create account (name, email, phone, password)
        └── Redirect to Courses Index

Login (/login)
  └── Enter credentials
        └── Redirect to Courses Index
```

### Authenticated Student

```
Courses Index (/courses)
  │
  ├── Browse courses with progress indicators
  │
  └── Course Detail (/courses/{slug})
        │
        ├── [Not Enrolled] → "Enroll Now" → Enrollment confirmation modal
        │
        ├── [Enrolled] → Progress bar + "Resume Learning"
        │     └── Lesson Page (/courses/{slug}/lessons/{lesson})
        │           ├── Video player (via Spatie Media)
        │           ├── Mark lesson as complete
        │           └── Navigate between lessons
        │
        └── [Completed 100%] → Download PDF Certificate + Review Course
```

### Admin Panel (`/admin`)

```
Dashboard
  ├── Users        — Manage student & admin accounts
  ├── Categories   — Course categories
  ├── Levels       — Beginner, Intermediate, Advanced, Expert
  ├── Instructors  — Instructor profiles with media
  └── Courses      — Full CRUD with:
        ├── Sections (repeater)
        ├── Lessons (relation manager + video upload)
        ├── Enrollments (relation manager)
        └── Ratings (relation manager)
```

---

## ⚖ Technical Decisions & Trade-offs

- **Filament v5** — Used for the admin panel to get production-ready CRUD, relation managers, and form builders out of the box, avoiding a custom admin build.
- **Livewire Volt** — Student-facing pages use single-file components (`⚡*.blade.php`) to co-locate PHP logic and Blade templates in one file for simplicity.
- **Google Stitch** — The student UI was designed and generated using Google Stitch, providing a Material Design 3 aesthetic that accelerated frontend development.
- **Spatie Media Library** — Handles all file uploads (images, videos) with storage abstraction. Currently uses `public` disk; switch to S3 by changing `->disk('s3')` in form components and adding AWS credentials to `.env` — no migration changes needed.
- **Laravel Fortify** — Headless authentication backend for login/register, paired with custom Livewire views for full UI control.
- **DomPDF** — Generates landscape A4 PDF certificates on course completion.
- **SQLite in-memory** — Tests run against `:memory:` SQLite for speed (~8s for 14 tests) with zero external DB setup.

---

## 🛠 Tech Stack

| Layer          | Technology                                      |
|----------------|--------------------------------------------------|
| Framework      | Laravel 13                                       |
| PHP            | 8.3+                                             |
| Frontend       | Livewire 4 / Volt + Alpine.js + Tailwind CSS     |
| Admin Panel    | Filament v5                                      |
| Auth           | Laravel Fortify                                  |
| Media          | Spatie Media Library (local / S3)                |
| PDF            | barryvdh/laravel-dompdf                          |
| Testing        | Pest PHP                                         |
| Database       | MySQL (dev) / SQLite (testing)                   |
| UI Design      | Google Stitch (Material Design 3)                |


# MediQueue HMS — Laravel MVC Project
## MVC Programming · Hospital Queue, Bed Management & Inventory System

---

## Project Structure

```
mediqueue-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          ← Login / Logout (Laravel Auth)
│   │   │   ├── DashboardController.php     ← Main dashboard summary
│   │   │   ├── OpdController.php           ← OPD Queue (M/M/c model)
│   │   │   ├── BedController.php           ← Bed availability management
│   │   │   ├── PatientController.php       ← Patient CRUD + Admit/Discharge
│   │   │   ├── InventoryController.php     ← Medicines & consumables
│   │   │   └── ReportController.php        ← Analytics & reports
│   │   └── Middleware/                     ← Auth middleware (built-in)
│   └── Models/
│       ├── Models.php                      ← All Eloquent models
│       │   (Patient, OpdToken, Bed,
│       │    Admission, Inventory,
│       │    InventoryLog)
│
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php                   ← Master layout (sidebar, topbar)
│   ├── auth/
│   │   └── login.blade.php                 ← Login page
│   ├── dashboard/
│   │   └── index.blade.php                 ← Dashboard
│   ├── opd/
│   │   ├── index.blade.php                 ← OPD queue list
│   │   └── create.blade.php                ← Issue token
│   ├── beds/
│   │   ├── index.blade.php                 ← Bed management
│   │   ├── create.blade.php                ← Add bed
│   │   └── edit.blade.php                  ← Edit bed
│   ├── patients/
│   │   ├── index.blade.php                 ← Patient registry
│   │   ├── create.blade.php                ← Register patient
│   │   ├── show.blade.php                  ← Patient profile + Admit/Discharge
│   │   └── edit.blade.php                  ← Edit patient
│   ├── inventory/
│   │   ├── index.blade.php                 ← Stock list
│   │   ├── create.blade.php                ← Add item
│   │   └── edit.blade.php                  ← Edit + Dispense
│   └── reports/
│       ├── index.blade.php                 ← Reports overview
│       ├── opd.blade.php                   ← OPD analytics
│       ├── beds.blade.php                  ← Bed & admission report
│       └── inventory.blade.php             ← Inventory & dispensing log
│
├── routes/
│   └── web.php                             ← All application routes
│
├── database/
│   ├── migrations/
│   │   └── migrations.php                  ← All 7 migration tables
│   └── seeders/
│       └── DatabaseSeeder.php              ← Sample data seeder
│
└── .env.example                            ← Environment config template
```

---

## Setup Instructions (Run on Localhost)

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL (XAMPP / WAMP / Laragon)
- Node.js (optional, for Vite)

---

### Step 1 — Create Laravel Project

```bash
composer create-project laravel/laravel mediqueue-hms
cd mediqueue-hms
```

### Step 2 — Copy Project Files

Copy all files from this project into the corresponding Laravel directories:

```
app/Http/Controllers/   ← All 6 controllers
app/Models/             ← Split Models.php into individual files
resources/views/        ← All blade templates
routes/web.php          ← Replace default web.php
database/migrations/    ← Split into individual migration files
database/seeders/       ← DatabaseSeeder.php
.env.example → .env     ← Configure your DB credentials
```

### Step 3 — Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```
DB_DATABASE=mediqueue_hms
DB_USERNAME=root
DB_PASSWORD=           # your MySQL password
```

### Step 4 — Create Database

```sql
CREATE DATABASE mediqueue_hms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 5 — Run Migrations & Seed

```bash
php artisan migrate
php artisan db:seed
```

### Step 6 — Split Models File

Create individual model files in `app/Models/`:
- `Patient.php`
- `OpdToken.php`
- `Bed.php`
- `Admission.php`
- `Inventory.php`
- `InventoryLog.php`

(Copy each class from `Models.php` into its own file with proper namespace header)

### Step 7 — Start the Application

```bash
php artisan serve
```

Open browser: **http://localhost:8000**

---

## Login Credentials (After Seeding)

| Role          | Email                    | Password   |
|---------------|--------------------------|------------|
| Administrator | admin@hospital.com       | admin123   |
| Doctor        | doctor@hospital.com      | doc123     |
| Receptionist  | staff@hospital.com       | staff123   |

---

## Laravel Concepts Used (INT221 Syllabus)

| Concept                        | Where Used                                  |
|-------------------------------|---------------------------------------------|
| Laravel MVC Architecture       | Controllers → Models → Blade Views          |
| Named Routes                   | `route('opd.index')`, `route('patients.show', $id)` |
| Route Groups + Middleware      | `Route::middleware('auth')->group(...)`     |
| Resource Controllers           | All 6 controllers with CRUD methods         |
| Blade Templating               | `@extends`, `@section`, `@yield`           |
| Template Inheritance           | `layouts/app.blade.php` as master layout    |
| Data Passing to Views          | `compact()`, `with()`                      |
| Form Handling + CSRF           | `@csrf`, `@method('PUT')`, `@method('DELETE')` |
| Validation + Custom Messages   | `Validator::make()` with custom messages    |
| Schema Builder + Migrations    | 7 migration tables with indexes             |
| Eloquent ORM + Relationships   | `hasMany`, `belongsTo`, `hasOne`           |
| Session Management             | Flash messages: `session('success')`        |
| Auth Facade                    | `Auth::attempt()`, `Auth::user()`, `Auth::id()` |
| Pagination                     | `->paginate(15)` + `{{ $items->links() }}` |
| Query Scopes / Raw Queries     | `whereRaw('current_stock <= reorder_level')`|
| Eager Loading                  | `->with(['patient', 'bed'])`               |
| Accessors (Computed Properties)| `getAgeAttribute()`, `getStatusBadgeAttribute()` |

---

## Module Summary

### Module 1 — OPD Queue Management
- Token generation with auto-incrementing daily number
- M/M/c queuing model for estimated wait time calculation
- Priority levels: Emergency → Senior → Regular
- Call next patient, mark consultation complete
- Real-time department-wise queue load display

### Module 2 — Bed Management
- Ward-wise bed tracking: General, ICU, Surgical, Pediatrics, Maternity, Cardiology
- Status: Available / Occupied / Maintenance / Reserved
- Instant bed release on patient discharge

### Module 3 — Patient Admission System
- Auto-generated patient ID (P-YYYY-NNNNN)
- Full registration with emergency contact
- Admit to specific bed with diagnosis and doctor
- Discharge with summary — bed auto-released
- Full admission history per patient

### Module 4 — Inventory Management
- Medicines and consumables tracking
- Automatic low-stock alerts (stock ≤ reorder level)
- Expiry date tracking with 30-day warning
- Dispense logging with patient linkage
- Category-wise analytics in reports

---

## Future Scope (as per project spec)
- City-wide NIC module REST API integration
- AI-based queue prediction (ML model)
- Mobile application (Laravel API + React Native)
- Real-time WebSocket dashboards (Laravel Echo + Pusher)
- Email/SMS notifications for low stock (Laravel Mail + Twilio)

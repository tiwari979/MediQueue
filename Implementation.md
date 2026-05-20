# MediQueue Implementation Roadmap

This document summarizes the current implementation of MediQueue and the future implementation plan required to make it a stronger production-style hospital workflow platform.

## 1. Project Goal

MediQueue is intended to become a hospital workflow platform that connects outpatient queue management, patient records, bed/admission management, medicine inventory, reporting, and role-based hospital operations.

The desired production workflow is:

1. Patient is registered.
2. Reception issues OPD token.
3. Patient waits in department queue.
4. Doctor calls and completes consultation.
5. Patient is discharged, referred, given medicine, or admitted.
6. If admitted, ward staff allocates bed.
7. Pharmacy dispenses medicine and stock is updated.
8. Patient is discharged and bed becomes available again.
9. Admin monitors queues, beds, inventory, admissions, and reports.

## 2. Current Implementation

### 2.1 Technology Stack

- Backend framework: Laravel 12
- Frontend rendering: Blade templates
- Styling/build: Vite, Tailwind-related setup
- Database: Laravel migrations and seeders
- Authentication: Custom `AuthController`
- Main route file: `routes/web.php`

### 2.2 Authentication

Implemented:

- Login page.
- Login validation.
- Session regeneration after login.
- Logout flow.
- All main operational routes are inside `auth` middleware.
- Seeded users include admin, doctor, and receptionist-style staff.

Current limitations:

- User roles exist, but route-level role permissions are not enforced yet.
- There is no dedicated middleware such as `role:admin` or policy-based access control.
- No user management screen for admins.
- No password reset, email verification, account lock, or active/inactive login restriction flow.

### 2.3 Dashboard

Implemented:

- Main dashboard with OPD, bed, inventory, patient, admission, and discharge summary metrics.
- Recent active admissions.
- OPD queue count grouped by department.
- Bed occupancy grouped by ward.

Current limitations:

- Dashboard is shared for all logged-in users.
- No role-specific dashboard views.
- No realtime updates.
- Metrics are useful but still operationally basic.

### 2.4 Patient Management

Implemented:

- Patient list with search by name, patient ID, and phone.
- Filter patients by admitted or OPD status.
- Create patient.
- Edit patient.
- Show patient profile.
- Auto-generated patient ID.
- Patient relationships with OPD tokens and admissions.
- Direct admission and discharge actions from patient profile.

Current limitations:

- Duplicate patient detection is limited.
- Patient ID generation uses current max ID logic and is not concurrency-safe.
- Patient admission is direct rather than request-based from doctor consultation.
- No medical history timeline beyond basic OPD/admission records.
- No allergies, chronic conditions, prescriptions, vitals, documents, or clinical notes module.

### 2.5 OPD Queue Management

Implemented:

- OPD token listing.
- Department and status filters.
- OPD token creation.
- Department list in controller.
- Priority values: normal, senior, emergency.
- Estimated wait calculation based on waiting count, doctor count, and service rate.
- Emergency patient wait time is reduced to zero.
- Senior patient wait time is reduced.
- Call patient into consultation.
- Complete consultation with doctor notes.
- Cancel/delete token.
- Recalculate wait times after completion or deletion.

Current limitations:

- Token generation uses `max(token_number) + 1`, which is not safe under concurrent requests.
- Queue ordering is not fully reliable because priority is sorted as a plain string in one helper.
- There is no strict status transition state machine.
- OPD consultation does not produce structured diagnosis, prescription, referral, follow-up, or admission request.
- OPD is department-based, not doctor-based.
- No waiting-room display or doctor-specific workspace.
- No WebSocket/live update flow.

### 2.6 Bed Management

Implemented:

- Bed list with ward/status filters.
- Bed summary by ward.
- Add bed.
- Edit bed.
- Release occupied bed.
- Bed model relation to current admission.
- Bed statuses include available, occupied, maintenance, and reserved-like fallback behavior in model badges.

Current limitations:

- Bed release automatically marks active admission discharged with minimal discharge workflow.
- Bed allocation is handled through patient admission rather than a ward admission queue.
- No bed cleaning workflow.
- No reserved bed workflow.
- No transfer bed workflow.
- No transaction protection around admission and bed occupancy update.

### 2.7 Admission Management

Implemented:

- Admission model.
- Patient admission from patient profile.
- Admission stores patient, bed, diagnosis, doctor name, admitted time, admitting user, status.
- Patient discharge updates admission and frees bed.
- Dashboard and reports read admission data.

Current limitations:

- No dedicated AdmissionController.
- No admission request queue.
- No doctor-to-ward handoff.
- No structured discharge summary workflow.
- No prevention of multiple active admissions for the same patient.
- No prevention of edge cases such as two users allocating the same bed at the same time.
- Doctor is stored as plain text instead of a relation to a doctors/users table.

### 2.8 Inventory Management

Implemented:

- Inventory list with search, category filter, low-stock alert, and expiring-soon alert.
- Add inventory item.
- Edit inventory item.
- Delete inventory item.
- Dispense inventory item.
- Stock decrement on dispense.
- Inventory log creation for add, restock/adjustment, and dispense.
- Inventory reports show logs and dispensed quantity by category.

Current limitations:

- Dispensing is not connected to OPD prescriptions or admissions.
- No structured prescription model.
- No purchase order/vendor workflow.
- No batch-level stock management.
- No transaction protection around stock decrement and log creation.
- No strong prevention of race-condition stock over-dispense.
- Inventory logs do not store stock before/after values.

### 2.9 Reports

Implemented:

- Reports index summary.
- OPD report by date range and department.
- Bed report with ward stats and admissions.
- Inventory report with inventory logs and dispensed category summary.

Current limitations:

- Reports are mostly operational summaries.
- No export to PDF/CSV.
- No date filtering for all reports.
- Some report logic may depend on database-specific SQL functions.
- No role-based report access.

### 2.10 Seed Data

Implemented:

- Seeded admin, doctor, and receptionist users.
- Seeded wards and beds.
- Seeded patients.
- Seeded OPD tokens.
- Seeded inventory.

Current limitations:

- Demo data contains fixed credentials and should not be used in production.
- Seeder roles do not yet map to enforced permissions.

### 2.11 Testing

Implemented:

- Basic default feature and unit tests exist.

Current limitations:

- Current test coverage is not aligned with hospital workflows.
- `tests/Feature/ExampleTest.php` expects `/` to return 200, but `/` redirects to login, so the assertion should be updated.
- No tests for authentication, OPD token creation, patient admission, bed release, inventory dispense, or reports in the current root project.

## 3. Main Production Gaps

The most important gaps are:

1. Role-based authorization.
2. Transaction-safe patient ID, token, admission, bed, and inventory operations.
3. Connected workflow from OPD consultation to admission and pharmacy.
4. Dedicated admission workflow.
5. Structured prescription and medicine dispensing flow.
6. Audit trail for sensitive operations.
7. Workflow-specific tests.
8. Realtime queue and dashboard updates.
9. Cleaner domain model for doctors, departments, prescriptions, and medicine batches.
10. Better production hardening: logging, backups, validation, monitoring, deployment config.

## 4. Target Future Architecture

### 4.1 Core Modules

The project should evolve into these core modules:

- Auth and user management
- Role and permission management
- Patient registry
- OPD queue
- Doctor consultation
- Admission requests
- Bed management
- Pharmacy and inventory
- Reports and analytics
- Notifications
- Audit logs
- Admin settings

### 4.2 Important Domain Entities

Current entities:

- User
- Patient
- OpdToken
- Bed
- Admission
- Inventory
- InventoryLog

Recommended future entities:

- Doctor
- Department
- Consultation
- Prescription
- PrescriptionItem
- AdmissionRequest
- BedTransfer
- BedCleaningTask
- MedicineBatch or InventoryBatch
- Supplier
- PurchaseOrder
- AuditLog
- Notification

### 4.3 Recommended Workflow Events

Use Laravel events/listeners for workflow automation:

- `PatientRegistered`
- `OpdTokenIssued`
- `PatientCalled`
- `ConsultationCompleted`
- `AdmissionRequested`
- `BedAllocated`
- `BedReleased`
- `PrescriptionCreated`
- `MedicineDispensed`
- `InventoryLow`
- `PatientDischarged`

## 5. Phase-Wise Implementation Plan

## Phase 0: Stabilize Current Codebase

Goal: make the existing project reliable before adding new workflows.

Tasks:

1. Fix `tests/Feature/ExampleTest.php` so it expects redirect to login or follows the redirect.
2. Clean broken encoding characters in comments, README, and Blade templates.
3. Run and stabilize `php artisan test`.
4. Add smoke tests for login and protected dashboard access.
5. Confirm all route names and view links are working.
6. Review migrations against models to ensure fields match.

Deliverable:

- Existing app runs cleanly and tests pass.

## Phase 1: Role-Based Access Control

Goal: prevent every logged-in user from accessing every operational module.

Tasks:

1. Create role middleware.
2. Define roles:
   - admin
   - doctor
   - receptionist
   - ward_staff
   - pharmacist
3. Protect routes by role.
4. Add helper methods or policies for user permissions.
5. Add admin-only user management screen.
6. Prevent inactive users from logging in.
7. Add tests for authorized and unauthorized access.

Suggested access:

- Admin: full access.
- Receptionist: patients and OPD token issue.
- Doctor: OPD consultation and patient history.
- Ward staff: beds and admissions.
- Pharmacist: inventory and dispensing.

Deliverable:

- Real staff separation with permission tests.

## Phase 2: Strong OPD Workflow

Goal: improve OPD from basic queue management to proper consultation workflow.

Tasks:

1. Make token generation transaction-safe.
2. Add per-day unique token sequence.
3. Add duplicate active token prevention for same patient and department.
4. Add strict status transitions:
   - waiting to in_consultation
   - in_consultation to served
   - waiting to cancelled
5. Replace hardcoded doctor counts with a Department/Doctor model.
6. Add consultation form fields:
   - diagnosis
   - treatment notes
   - follow-up date
   - referral
   - outcome
7. Add outcome options:
   - discharged
   - admitted
   - referred
   - follow_up
8. Create `Consultation` table instead of storing all clinical data on `opd_tokens`.
9. Add tests for token creation, emergency priority, consultation completion, and cancellation.

Deliverable:

- OPD can support a realistic reception-to-doctor workflow.

## Phase 3: Admission Request and Bed Allocation

Goal: connect doctor consultation to ward operations.

Tasks:

1. Create `admission_requests` table.
2. When OPD outcome is admitted, create an admission request.
3. Add ward dashboard for pending admission requests.
4. Ward staff selects available bed.
5. Use database transaction to:
   - lock selected bed
   - create admission
   - mark bed occupied
   - mark request completed
6. Prevent multiple active admissions for same patient.
7. Prevent multiple active admissions for same bed.
8. Add bed statuses:
   - available
   - reserved
   - occupied
   - cleaning
   - maintenance
9. Add discharge workflow:
   - discharge summary
   - discharge diagnosis
   - discharged by
   - bed status changes to cleaning
10. Add bed cleaning release action to change cleaning to available.

Deliverable:

- Doctor can request admission, ward can allocate bed, discharge can safely release bed.

## Phase 4: Pharmacy and Prescription Workflow

Goal: connect doctor treatment to medicine inventory.

Tasks:

1. Create `prescriptions` table.
2. Create `prescription_items` table.
3. Link prescription to:
   - patient
   - consultation or admission
   - doctor
4. Add structured medicine selection from inventory.
5. Add dosage, frequency, duration, and instructions.
6. Add pharmacy dashboard for pending prescriptions.
7. Dispensing should use database transaction to:
   - lock inventory item or batch
   - verify stock
   - decrement stock
   - create inventory log with stock before and after
   - mark prescription item dispensed
8. Add partial dispensing support.
9. Add low-stock and out-of-stock alerts.
10. Add inventory batch support for expiry-aware dispensing.

Deliverable:

- Medicine dispensing becomes connected, auditable, and safer.

## Phase 5: Audit, Logs, and Operational Safety

Goal: make critical hospital actions traceable.

Tasks:

1. Create `audit_logs` table.
2. Log key actions:
   - patient created/updated
   - token issued/cancelled
   - consultation completed
   - admission requested
   - bed allocated/released
   - patient discharged
   - stock adjusted/dispensed
3. Store:
   - user ID
   - action
   - model type
   - model ID
   - old values
   - new values
   - IP address
   - user agent
4. Add reason fields for sensitive operations.
5. Add admin audit log viewer.

Deliverable:

- Important operations become traceable for accountability.

## Phase 6: Reports and Analytics Upgrade

Goal: support hospital decision-making.

Tasks:

1. Add date filters consistently across reports.
2. Add CSV export.
3. Add PDF export.
4. Add OPD metrics:
   - average wait by department
   - served count by day
   - emergency count
   - peak hours
5. Add bed metrics:
   - occupancy rate
   - average length of stay
   - admissions/discharges by ward
6. Add inventory metrics:
   - low stock
   - expiring stock
   - usage by category
   - top dispensed medicines
7. Add role-restricted report access.

Deliverable:

- Reports become useful for operations and administration.

## Phase 7: Realtime Workflow

Goal: make queues and dashboards update live.

Tasks:

1. Add Laravel broadcasting.
2. Use Laravel Reverb or Pusher-compatible driver.
3. Broadcast:
   - token issued
   - patient called
   - consultation completed
   - admission requested
   - bed allocated
   - inventory low
4. Add waiting-room queue display.
5. Add doctor live queue screen.
6. Add admin live dashboard counters.

Deliverable:

- Queue and operations feel live without manual refresh.

## Phase 8: Production Hardening

Goal: prepare the platform for real deployment.

Tasks:

1. Configure production `.env` safely.
2. Use secure cookies and HTTPS.
3. Add rate limiting for login.
4. Add database backups.
5. Add scheduled tasks:
   - mark expired inventory
   - notify low stock
   - cleanup stale sessions
6. Add centralized logging.
7. Add error monitoring.
8. Add health check route.
9. Add deployment documentation.
10. Add database indexes for high-traffic filters.

Deliverable:

- App is safer to deploy and maintain.

## Phase 9: Multi-Hospital Readiness

Goal: prepare for a city-wide or multi-branch platform.

Tasks:

1. Add `hospitals` table.
2. Add `hospital_id` to users, patients, beds, OPD tokens, admissions, and inventory.
3. Scope data access by hospital.
4. Add central admin role.
5. Add shared bed availability API.
6. Add external integration layer.
7. Add API authentication using Laravel Sanctum.

Deliverable:

- Architecture can scale beyond one hospital.

## 6. Recommended Immediate Sprint

Start implementation with this sprint:

1. Fix tests and encoding issues.
2. Add role middleware and protect routes properly.
3. Add workflow tests for current patient, OPD, bed, and inventory actions.
4. Make OPD token generation transaction-safe.
5. Create consultation outcome flow.
6. Create admission request flow from OPD outcome `admitted`.
7. Add transaction-safe bed allocation.

This sprint will turn the current app from a module-based project into the beginning of a connected hospital workflow.

## 7. Definition of Good Project Quality

The project should be considered strong when:

- Every important route is authenticated and authorized.
- Every critical workflow has tests.
- Every stock, bed, and admission operation is transaction-safe.
- Every sensitive action is audit logged.
- OPD, admission, bed, and inventory workflows are connected.
- Dashboards are role-specific.
- Reports support real operational decisions.
- The app can be deployed with documented environment setup, backups, and monitoring.
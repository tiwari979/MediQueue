# MediQueue Documentation

## 1. Project Objective
MediQueue is designed to be a comprehensive hospital workflow platform that seamlessly connects and streamlines various hospital operations. The primary objective is to facilitate an efficient, role-based workflow encompassing:
- **Outpatient (OPD) Queue Management:** Registering patients and managing waiting queues for consultations.
- **Patient Records:** Organizing and preserving patient profiles and their medical timelines.
- **Bed & Admission Management:** Managing ward occupancies, patient admissions, and discharges.
- **Inventory & Pharmacy Management:** Monitoring medicine stock, managing dispensing actions, and keeping logs.
- **Hospital Reporting:** Extracting operational summaries to monitor performance and capacity.

### The Desired Production Workflow:
1. Patient arrives and is registered by the reception.
2. Reception issues an OPD token.
3. Patient waits in the department-specific queue (prioritized by emergency or senior status).
4. Doctor calls the patient and completes the consultation, adding clinical notes.
5. Patient is either discharged, prescribed medicine, referred, or requested for admission.
6. If admitted, ward staff allocates an available bed to the patient.
7. Pharmacy dispenses prescribed medicines and the inventory stock is updated automatically.
8. Upon recovery, the patient is discharged, freeing the allocated bed in real-time.
9. Administrators continually monitor queues, bed occupancy, inventory metrics, and generate detailed reports.

---

## 2. Current Implementation

### 2.1 Technology Stack
- **Backend Framework:** Laravel 12 (PHP)
- **Frontend Rendering:** Blade templates
- **Styling/Build Tools:** Tailwind CSS configured with Vite
- **Database:** Managed via Laravel migrations and seeders
- **Authentication:** Custom `AuthController` with session-based login/logout architecture
- **Routing:** Centralized in `routes/web.php`

### 2.2 User Authentication & Roles
- Session-based Login and Logout functionality is fully active.
- Access to operational workflows is guarded behind an `auth` middleware.
- Seeded test users encompass roles such as `admin`, `doctor`, and `receptionist`.
*Note: Granular route-level role enforcement, password resets, and user management screens are pending.*

### 2.3 Dashboard Analytics
- Displays aggregated metrics: active OPD queues, bed occupancy, inventory alerts, and live admission/discharge summaries.
- Shows recent active admissions.
- Provides visual groupings of OPD counts by department and bed occupancies by ward.
*Note: Currently, the dashboard view is shared globally for all roles; role-specific customized views are prioritized for future updates.*

### 2.4 Patient Management (`Patient` Model)
- Features complete CRUD operations to handle patient information.
- A patient listing page with search filters (by Name, Patient ID, Phone) and status tracking (Admitted vs. OPD status).
- Auto-generation of Patient IDs.
- Seamless integrations of patient profile linking directly to their historical OPD tokens and admission records.

### 2.5 OPD Queue Management (`OpdToken` Model)
- Creation and real-time management of outpatient department tokens.
- Supports distinct priority values: `normal`, `senior`, and `emergency`.
- Computes estimated wait times dynamically using queue lengths, doctor counts, and service rates (fast-tracking emergency and senior queues).
- Interactive statuses enabling doctors to "Call" a patient or "Complete" the consultation (which automatically recalculates wait times for remaining patients).

### 2.6 Bed Management (`Bed` Model)
- Full visibility of hospital beds formatted with ward and status filters (available, occupied, maintenance).
- CRUD capabilities for beds with responsive status badges indicating availability.
- Bed release functionality mapping directly to a patient's discharge.

### 2.7 Admission Management (`Admission` Model)
- Links Patients, Beds, Doctors, and Admission metadata together.
- Direct admission assignments from a patient's profile.
- Tracks admitted time, diagnosis details, and the authorizing user.
- Handles the discharge process which automatically restores bed availability.
*Note: Moving forwards, an admission request queue replacing direct admissions will be implemented to separate doctor requests from ward allocations.*

### 2.8 Inventory Management (`Inventory`, `InventoryLog` Models)
- Comprehensive item listing paired with status alerts (low stock, expiring soon).
- Facilitates the addition, editing, deletion, and dispensing of medical items.
- Dispensations execute automatic stock decrements alongside corresponding `InventoryLog` creations keeping an immutable audit trail of adjustments and consumptions.

### 2.9 Reporting
- Contains macro-level operational reports segmented across predefined domains.
- Dedicated reports generated for: OPD traffic by date range/department, Bed operations displaying ward admission stats, and Inventory utilization mapping dispensed summaries per category.

## 3. Future Scope
As MediQueue iterates toward a production-ready state, several foundational updates are planned:
- Implementation of RBAC (Role-Based Access Control) to limit visibility and execution permissions depending upon the authenticated user's assigned role.
- Adopting Database transactions ensuring atomic operations (e.g., locking inventory stock upon dispensation, concurrency-safe unique ID generation).
- Introducing WebSockets for real-time live queue and dashboard data pushes.
- Detailed medical histories spanning allergy records, comprehensive clinical notes modules, and digital prescription schemas.
- Full cycle module to support a Purchase-Order vendor workflow for automated inventory restocks.
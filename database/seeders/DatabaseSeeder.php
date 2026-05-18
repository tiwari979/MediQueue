<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Patient;
use App\Models\Bed;
use App\Models\Inventory;
use App\Models\OpdToken;
use App\Models\Admission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──────────────────────────────────────────────────────────────
        $admin = User::create([
            'name'       => 'Dr. Rajesh Kumar',
            'email'      => 'admin@hospital.com',
            'password'   => Hash::make('admin123'),
            'role'       => 'admin',
            'phone'      => '9876543210',
            'department' => 'Administration',
        ]);
        User::create([
            'name'       => 'Dr. Priya Mehta',
            'email'      => 'doctor@hospital.com',
            'password'   => Hash::make('doc123'),
            'role'       => 'doctor',
            'phone'      => '9876543211',
            'department' => 'Cardiology',
        ]);
        User::create([
            'name'       => 'Anita Sharma',
            'email'      => 'staff@hospital.com',
            'password'   => Hash::make('staff123'),
            'role'       => 'receptionist',
            'phone'      => '9876543212',
            'department' => 'Reception',
        ]);

        // ── Beds ───────────────────────────────────────────────────────────────
        $wards = [
            'General'    => ['A', 40],
            'ICU'        => ['B', 12],
            'Surgical'   => ['C', 18],
            'Pediatrics' => ['D', 20],
            'Maternity'  => ['E', 24],
            'Cardiology' => ['F', 16],
        ];
        foreach ($wards as $ward => [$prefix, $count]) {
            for ($i = 1; $i <= $count; $i++) {
                Bed::create([
                    'bed_number' => $prefix . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'ward'       => $ward,
                    'bed_type'   => $ward === 'ICU' ? 'icu' : 'general',
                    'status'     => $i <= ($count * 0.65) ? 'occupied' : 'available',
                ]);
            }
        }

        // ── Patients ───────────────────────────────────────────────────────────
        $patientsData = [
            ['Priya Sharma',   '1990-03-15', 'female', 'B+',  '9811234567'],
            ['Arjun Mehta',    '1996-07-22', 'male',   'O+',  '9822345678'],
            ['Sunita Patel',   '1972-11-08', 'female', 'A-',  '9833456789'],
            ['Ramesh Gupta',   '1957-02-14', 'male',   'AB+', '9844567890'],
            ['Kavita Singh',   '1983-09-30', 'female', 'O-',  '9855678901'],
            ['Mohan Das',      '1966-05-17', 'male',   'B-',  '9866789012'],
            ['Nisha Verma',    '2001-12-01', 'female', 'A+',  '9877890123'],
            ['Suresh Yadav',   '1959-04-25', 'male',   'O+',  '9888901234'],
            ['Anita Kapoor',   '1988-08-19', 'female', 'B+',  '9899012345'],
            ['Vikram Chauhan', '1975-01-11', 'male',   'AB-', '9810123456'],
        ];
        foreach ($patientsData as $i => [$name, $dob, $gender, $blood, $phone]) {
            Patient::create([
                'patient_id'   => 'P-2024-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'name'         => $name,
                'dob'          => $dob,
                'gender'       => $gender,
                'blood_group'  => $blood,
                'phone'        => $phone,
                'registered_by'=> $admin->id,
            ]);
        }

        // ── OPD Tokens ─────────────────────────────────────────────────────────
        $depts     = ['General Medicine', 'Cardiology', 'Orthopedics', 'Pediatrics', 'ENT'];
        $priorities = ['normal', 'normal', 'normal', 'senior', 'emergency'];
        $patients  = Patient::all();
        foreach ($depts as $di => $dept) {
            for ($t = 1; $t <= 5; $t++) {
                OpdToken::create([
                    'patient_id'     => $patients->random()->id,
                    'department'     => $dept,
                    'priority'       => $priorities[array_rand($priorities)],
                    'token_number'   => ($di * 10) + $t,
                    'status'         => $t <= 3 ? 'waiting' : ($t === 4 ? 'in_consultation' : 'served'),
                    'estimated_wait' => rand(5, 45),
                    'issued_by'      => $admin->id,
                ]);
            }
        }

        // ── Inventory ──────────────────────────────────────────────────────────
        $items = [
            ['Paracetamol 500mg',    'Analgesic',      'Tablet',  2400, 500,  1.50,  '2026-06-30'],
            ['Insulin (Regular)',    'Antidiabetic',   'Vial',    48,   100,  85.00, '2025-03-15'],
            ['Normal Saline 500ml',  'IV Fluid',       'Bag',     310,  400,  45.00, '2026-08-20'],
            ['Surgical Gloves (L)',  'PPE',            'Box',     1850, 200,  120.00, null],
            ['Metformin 500mg',      'Antidiabetic',   'Tablet',  90,   500,  3.00,  '2025-08-10'],
            ['Amoxicillin 250mg',    'Antibiotic',     'Capsule', 820,  300,  4.50,  '2025-09-30'],
            ['Amlodipine 5mg',       'Antihypertensive','Tablet', 1200, 300,  5.00,  '2026-04-15'],
            ['Ringer Lactate 500ml', 'IV Fluid',       'Bag',     200,  300,  55.00, '2026-10-01'],
            ['Surgical Mask N95',    'PPE',            'Box',     440,  100,  350.00, null],
            ['Ceftriaxone 1g',       'Antibiotic',     'Vial',    150,  100,  65.00, '2025-12-31'],
        ];
        foreach ($items as [$name, $cat, $unit, $stock, $reorder, $price, $expiry]) {
            Inventory::create([
                'name'          => $name,
                'category'      => $cat,
                'unit'          => $unit,
                'current_stock' => $stock,
                'reorder_level' => $reorder,
                'unit_price'    => $price,
                'expiry_date'   => $expiry,
                'supplier'      => 'MediSupply India Pvt. Ltd.',
                'batch_number'  => 'BT-' . rand(10000, 99999),
            ]);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Patient;
use App\Models\Bed;
use App\Models\Admission;

class PatientController extends Controller
{
    /** GET /patients */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Patient::with('latestAdmission');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('patient_id', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        if ($status === 'admitted') {
            $query->whereHas('admissions', fn($q) => $q->where('status', 'admitted'));
        } elseif ($status === 'opd') {
            $query->whereHas('opdTokens', fn($q) => $q->where('status', 'waiting'));
        }

        $patients = $query->latest()->paginate(15);
        return view('patients.index', compact('patients', 'search', 'status'));
    }

    /** GET /patients/create */
    public function create()
    {
        return view('patients.create');
    }

    /** POST /patients/store */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:100',
            'dob'        => 'required|date|before:today',
            'gender'     => 'required|in:male,female,other',
            'blood_group'=> 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'phone'      => 'required|digits_between:10,12',
            'email'      => 'nullable|email|unique:patients,email',
            'address'    => 'nullable|string|max:300',
            'emergency_contact_name'  => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|digits_between:10,12',
        ], [
            'name.required'     => 'Patient name is required.',
            'dob.required'      => 'Date of birth is required.',
            'dob.before'        => 'Date of birth must be in the past.',
            'phone.required'    => 'Phone number is required.',
            'phone.digits_between' => 'Phone must be 10–12 digits.',
            'email.unique'      => 'This email is already registered.',
            'blood_group.required' => 'Blood group is required.',
        ]);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        // Auto-generate patient ID: P-YYYY-XXXXX
        $lastId = Patient::whereYear('created_at', now()->year)->max('id') ?? 0;
        $patientId = 'P-' . now()->year . '-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        $patient = Patient::create([
            'patient_id'              => $patientId,
            'name'                    => $request->name,
            'dob'                     => $request->dob,
            'gender'                  => $request->gender,
            'blood_group'             => $request->blood_group,
            'phone'                   => $request->phone,
            'email'                   => $request->email,
            'address'                 => $request->address,
            'emergency_contact_name'  => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'registered_by'           => Auth::id(),
        ]);

        return redirect()->route('patients.show', $patient->id)
            ->with('success', "Patient registered successfully. ID: $patientId");
    }

    /** GET /patients/{id} */
    public function show($id)
    {
        $patient    = Patient::with(['admissions.bed', 'opdTokens'])->findOrFail($id);
        $availBeds  = Bed::where('status', 'available')->orderBy('ward')->get();
        return view('patients.show', compact('patient', 'availBeds'));
    }

    /** GET /patients/{id}/edit */
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.edit', compact('patient'));
    }

    /** PUT /patients/{id} */
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:100',
            'phone'      => 'required|digits_between:10,12',
            'blood_group'=> 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'address'    => 'nullable|string|max:300',
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        $patient->update($request->only('name', 'phone', 'blood_group', 'address',
                                        'emergency_contact_name', 'emergency_contact_phone'));
        return redirect()->route('patients.show', $id)->with('success', 'Patient record updated.');
    }

    /** POST /patients/{id}/admit */
    public function admit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bed_id'    => 'required|exists:beds,id',
            'diagnosis' => 'required|string|max:300',
            'doctor'    => 'required|string|max:100',
        ], [
            'bed_id.required'    => 'Please select a bed.',
            'bed_id.exists'      => 'Selected bed does not exist.',
            'diagnosis.required' => 'Diagnosis is required.',
            'doctor.required'    => 'Please enter the attending doctor name.',
        ]);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $bed = Bed::findOrFail($request->bed_id);
        if ($bed->status !== 'available') {
            return back()->with('error', 'Selected bed is no longer available. Please choose another.');
        }

        Admission::create([
            'patient_id'  => $id,
            'bed_id'      => $request->bed_id,
            'diagnosis'   => $request->diagnosis,
            'doctor'      => $request->doctor,
            'admitted_at' => now(),
            'admitted_by' => Auth::id(),
            'status'      => 'admitted',
        ]);

        $bed->update(['status' => 'occupied']);

        return redirect()->route('patients.show', $id)
            ->with('success', 'Patient admitted to bed ' . $bed->bed_number . '.');
    }

    /** POST /patients/{id}/discharge */
    public function discharge(Request $request, $id)
    {
        $admission = Admission::where('patient_id', $id)->where('status', 'admitted')->firstOrFail();
        $admission->update([
            'status'            => 'discharged',
            'discharged_at'     => now(),
            'discharge_summary' => $request->input('summary'),
        ]);
        Bed::find($admission->bed_id)?->update(['status' => 'available']);

        return redirect()->route('patients.show', $id)->with('success', 'Patient discharged successfully.');
    }
}
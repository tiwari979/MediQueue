<?php
// ============================================================
// BedController.php
// ============================================================
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Bed;
use App\Models\Admission;

class BedController extends Controller
{
    private array $wards = ['General', 'ICU', 'Surgical', 'Pediatrics', 'Maternity', 'Cardiology'];

    /** GET /beds */
    public function index(Request $request)
    {
        $ward   = $request->get('ward');
        $status = $request->get('status');

        $query = Bed::with('currentAdmission.patient');
        if ($ward)   $query->where('ward', $ward);
        if ($status) $query->where('status', $status);

        $beds = $query->orderBy('ward')->orderBy('bed_number')->paginate(20);

        $wardSummary = Bed::selectRaw('ward,
            COUNT(*) as total,
            SUM(CASE WHEN status="available" THEN 1 ELSE 0 END) as available,
            SUM(CASE WHEN status="occupied"  THEN 1 ELSE 0 END) as occupied,
            SUM(CASE WHEN status="reserved"  THEN 1 ELSE 0 END) as reserved')
            ->groupBy('ward')->get()->keyBy('ward');

        $wards = $this->wards;
        return view('beds.index', compact('beds', 'wardSummary', 'wards', 'ward', 'status'));
    }

    /** GET /beds/create */
    public function create()
    {
        $wards = $this->wards;
        return view('beds.create', compact('wards'));
    }

    /** POST /beds/store */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bed_number' => 'required|string|max:20|unique:beds,bed_number',
            'ward'       => 'required|in:' . implode(',', $this->wards),
            'bed_type'   => 'required|in:general,icu,special',
        ], [
            'bed_number.unique'   => 'This bed number already exists.',
            'bed_number.required' => 'Bed number is required.',
            'ward.required'       => 'Please select a ward.',
        ]);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        Bed::create([
            'bed_number' => $request->bed_number,
            'ward'       => $request->ward,
            'bed_type'   => $request->bed_type,
            'status'     => 'available',
        ]);

        return redirect()->route('beds.index')->with('success', 'Bed ' . $request->bed_number . ' added successfully.');
    }

    /** GET /beds/{id}/edit */
    public function edit($id)
    {
        $bed   = Bed::findOrFail($id);
        $wards = $this->wards;
        return view('beds.edit', compact('bed', 'wards'));
    }

    /** PUT /beds/{id} */
    public function update(Request $request, $id)
    {
        $bed = Bed::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'ward'     => 'required|in:' . implode(',', $this->wards),
            'bed_type' => 'required|in:general,icu,special',
            'status'   => 'required|in:available,occupied,maintenance',
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        $bed->update($request->only('ward', 'bed_type', 'status'));
        return redirect()->route('beds.index')->with('success', 'Bed updated successfully.');
    }

    /** POST /beds/{id}/release */
    public function release($id)
    {
        $bed = Bed::findOrFail($id);
        if ($bed->status !== 'occupied') return back()->with('error', 'Bed is not currently occupied.');
        $bed->update(['status' => 'available']);
        Admission::where('bed_id', $id)->where('status', 'admitted')
                 ->update(['status' => 'discharged', 'discharged_at' => now()]);
        return back()->with('success', 'Bed released and patient discharged.');
    }
}
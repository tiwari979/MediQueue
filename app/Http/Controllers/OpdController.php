<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\OpdToken;
use App\Models\Patient;

class OpdController extends Controller
{
    // Departments list (used in views and validation)
    private array $departments = [
        'General Medicine', 'Cardiology', 'Orthopedics',
        'Pediatrics', 'ENT', 'Dermatology', 'Neurology', 'Gynecology',
    ];

    /**
     * Display all OPD queues.
     * GET /opd
     */
    public function index(Request $request)
    {
        $dept   = $request->get('dept');
        $status = $request->get('status', 'waiting');

        $query = OpdToken::with('patient')->latest('created_at');

        if ($dept)   $query->where('department', $dept);
        if ($status) $query->where('status', $status);

        $tokens = $query->paginate(15);

        // Queue summary per department
        $deptSummary = OpdToken::where('status', 'waiting')
            ->selectRaw('department, COUNT(*) as count, AVG(estimated_wait) as avg_wait, MAX(estimated_wait) as max_wait')
            ->groupBy('department')
            ->get()
            ->keyBy('department');

        $departments = $this->departments;

        return view('opd.index', compact('tokens', 'deptSummary', 'departments', 'dept', 'status'));
    }

    /**
     * Show token issue form.
     * GET /opd/create
     */
    public function create()
    {
        $patients    = Patient::orderBy('name')->get(['id', 'name', 'patient_id']);
        $departments = $this->departments;

        // Generate next token number
        $lastToken = OpdToken::whereDate('created_at', today())->max('token_number');
        $nextToken = ($lastToken ?? 0) + 1;

        return view('opd.create', compact('patients', 'departments', 'nextToken'));
    }

    /**
     * Store new OPD token.
     * POST /opd/store
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id'  => 'required|exists:patients,id',
            'department'  => 'required|in:' . implode(',', $this->departments),
            'priority'    => 'required|in:normal,senior,emergency',
            'symptoms'    => 'nullable|string|max:500',
        ], [
            'patient_id.required' => 'Please select a patient.',
            'patient_id.exists'   => 'Selected patient does not exist.',
            'department.required' => 'Please select a department.',
            'department.in'       => 'Invalid department selected.',
            'priority.required'   => 'Please select a priority level.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Calculate estimated wait based on M/M/c queue model
        $waiting      = OpdToken::where('department', $request->department)->where('status', 'waiting')->count();
        $serviceRate  = 6; // avg patients per hour per doctor
        $doctors      = $this->getDoctorCount($request->department);
        $estimatedWait = $doctors > 0 ? round(($waiting / ($doctors * $serviceRate)) * 60) : 0;

        // Emergency patients jump queue
        if ($request->priority === 'emergency') {
            $estimatedWait = 0;
        } elseif ($request->priority === 'senior') {
            $estimatedWait = max(0, $estimatedWait - 10);
        }

        // Generate token number
        $lastToken = OpdToken::whereDate('created_at', today())->max('token_number');

        OpdToken::create([
            'patient_id'     => $request->patient_id,
            'department'     => $request->department,
            'priority'       => $request->priority,
            'symptoms'       => $request->symptoms,
            'token_number'   => ($lastToken ?? 0) + 1,
            'status'         => 'waiting',
            'estimated_wait' => $estimatedWait,
            'issued_by'      => Auth::id(),
        ]);

        return redirect()->route('opd.index')
            ->with('success', 'Token issued successfully. Estimated wait: ' . $estimatedWait . ' minutes.');
    }

    /**
     * Show single token details.
     * GET /opd/{id}
     */
    public function show($id)
    {
        $token = OpdToken::with('patient')->findOrFail($id);
        return view('opd.show', compact('token'));
    }

    /**
     * Call next patient in a department.
     * POST /opd/{id}/call-next
     */
    public function callNext($id)
    {
        $token = OpdToken::findOrFail($id);

        if ($token->status !== 'waiting') {
            return back()->with('error', 'This token is no longer in the waiting queue.');
        }

        $token->update(['status' => 'in_consultation', 'called_at' => now()]);

        return back()->with('success', 'Token #' . $token->token_number . ' called for consultation.');
    }

    /**
     * Mark consultation complete.
     * POST /opd/{id}/complete
     */
    public function complete(Request $request, $id)
    {
        $token = OpdToken::findOrFail($id);
        $token->update([
            'status'       => 'served',
            'completed_at' => now(),
            'doctor_notes' => $request->input('notes'),
        ]);

        // Recalculate wait times for remaining patients in same department
        $this->recalculateWaitTimes($token->department);

        return back()->with('success', 'Consultation marked complete.');
    }

    /**
     * Cancel / delete a token.
     * DELETE /opd/{id}
     */
    public function destroy($id)
    {
        $token = OpdToken::findOrFail($id);
        $dept  = $token->department;
        $token->delete();

        $this->recalculateWaitTimes($dept);

        return redirect()->route('opd.index')->with('success', 'Token cancelled successfully.');
    }

    // ─── Private Helpers ───────────────────────────────────────────────────────

    private function getDoctorCount(string $dept): int
    {
        $map = [
            'General Medicine' => 3, 'Cardiology' => 2,
            'Orthopedics' => 2, 'Pediatrics' => 2,
            'ENT' => 1, 'Dermatology' => 1,
            'Neurology' => 2, 'Gynecology' => 2,
        ];
        return $map[$dept] ?? 1;
    }

    private function recalculateWaitTimes(string $dept): void
    {
        $waiting     = OpdToken::where('department', $dept)->where('status', 'waiting')
                                ->orderBy('priority', 'desc')->orderBy('created_at')->get();
        $serviceRate = 6;
        $doctors     = $this->getDoctorCount($dept);
        $position    = 0;

        foreach ($waiting as $token) {
            $wait = $doctors > 0 ? round(($position / ($doctors * $serviceRate)) * 60) : 0;
            $token->update(['estimated_wait' => $wait]);
            $position++;
        }
    }
}
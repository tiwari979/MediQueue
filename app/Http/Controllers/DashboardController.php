<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OpdToken;
use App\Models\Bed;
use App\Models\Patient;
use App\Models\Inventory;
use App\Models\Admission;

class DashboardController extends Controller
{
    /**
     * Show main dashboard with all summary statistics.
     * GET /dashboard
     */
    public function index()
    {
        // OPD stats
        $totalWaiting   = OpdToken::where('status', 'waiting')->count();
        $servedToday    = OpdToken::whereDate('created_at', today())->where('status', 'served')->count();
        $avgWaitMinutes = OpdToken::where('status', 'waiting')->avg('estimated_wait') ?? 0;

        // Bed stats
        $totalBeds     = Bed::count();
        $availableBeds = Bed::where('status', 'available')->count();
        $occupiedBeds  = Bed::where('status', 'occupied')->count();

        // Inventory alerts
        $lowStockCount  = Inventory::whereRaw('current_stock <= reorder_level')->count();
        $expiringCount  = Inventory::where('expiry_date', '<=', now()->addDays(30))
                                   ->where('expiry_date', '>=', today())
                                   ->count();

        // Patient stats
        $patientsToday   = Patient::whereDate('created_at', today())->count();
        $admittedToday   = Admission::whereDate('admitted_at', today())->where('status', 'admitted')->count();
        $dischargedToday = Admission::whereDate('discharged_at', today())->where('status', 'discharged')->count();

        // Recent admissions
        $recentAdmissions = Admission::with(['patient', 'bed'])
            ->where('status', 'admitted')
            ->latest('admitted_at')
            ->take(6)
            ->get();

        // OPD queue per department
        $opdByDept = OpdToken::where('status', 'waiting')
            ->selectRaw('department, COUNT(*) as count, AVG(estimated_wait) as avg_wait')
            ->groupBy('department')
            ->get();

        // Bed occupancy per ward
        $bedsByWard = Bed::selectRaw('ward, COUNT(*) as total,
                         SUM(CASE WHEN status="available" THEN 1 ELSE 0 END) as available,
                         SUM(CASE WHEN status="occupied"  THEN 1 ELSE 0 END) as occupied')
            ->groupBy('ward')
            ->get();

        return view('dashboard.index', compact(
            'totalWaiting', 'servedToday', 'avgWaitMinutes',
            'totalBeds', 'availableBeds', 'occupiedBeds',
            'lowStockCount', 'expiringCount',
            'patientsToday', 'admittedToday', 'dischargedToday',
            'recentAdmissions', 'opdByDept', 'bedsByWard'
        ));
    }
}
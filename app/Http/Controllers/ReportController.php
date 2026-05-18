<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OpdToken;
use App\Models\Bed;
use App\Models\Patient;
use App\Models\Admission;
use App\Models\Inventory;
use App\Models\InventoryLog;

class ReportController extends Controller
{
    /** GET /reports */
    public function index()
    {
        $totalPatients    = Patient::count();
        $totalAdmissions  = Admission::count();
        $avgStayDays      = Admission::whereNotNull('discharged_at')
            ->selectRaw('AVG(DATEDIFF(discharged_at, admitted_at)) as avg_days')
            ->value('avg_days');
        $totalTokensToday = OpdToken::whereDate('created_at', today())->count();
        $bedUtilization   = Bed::count() > 0
            ? round((Bed::where('status', 'occupied')->count() / Bed::count()) * 100)
            : 0;
        $lowStockItems    = Inventory::whereRaw('current_stock <= reorder_level')->count();
        $dispensedToday   = InventoryLog::whereDate('created_at', today())->where('action', 'dispensed')->sum('quantity');

        return view('reports.index', compact(
            'totalPatients', 'totalAdmissions', 'avgStayDays',
            'totalTokensToday', 'bedUtilization', 'lowStockItems', 'dispensedToday'
        ));
    }

    /** GET /reports/opd */
    public function opd(Request $request)
    {
        $from = $request->get('from', now()->subDays(7)->toDateString());
        $to   = $request->get('to', today()->toDateString());

        $daily = OpdToken::whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total,
                         SUM(CASE WHEN status="served" THEN 1 ELSE 0 END) as served,
                         AVG(estimated_wait) as avg_wait')
            ->groupBy('date')->orderBy('date')->get();

        $byDept = OpdToken::whereBetween('created_at', [$from, $to])
            ->selectRaw('department, COUNT(*) as total,
                         SUM(CASE WHEN priority="emergency" THEN 1 ELSE 0 END) as emergency')
            ->groupBy('department')->orderByDesc('total')->get();

        return view('reports.opd', compact('daily', 'byDept', 'from', 'to'));
    }

    /** GET /reports/beds */
    public function beds(Request $request)
    {
        $from = $request->get('from', now()->subDays(30)->toDateString());
        $to   = $request->get('to', today()->toDateString());

        $wardStats = Bed::selectRaw('ward, COUNT(*) as total,
            SUM(CASE WHEN status="available" THEN 1 ELSE 0 END) as available,
            SUM(CASE WHEN status="occupied"  THEN 1 ELSE 0 END) as occupied')
            ->groupBy('ward')->get();

        $admissions = Admission::whereBetween('admitted_at', [$from, $to])
            ->with(['patient', 'bed'])
            ->latest('admitted_at')->paginate(15);

        return view('reports.beds', compact('wardStats', 'admissions', 'from', 'to'));
    }

    /** GET /reports/inventory */
    public function inventory(Request $request)
    {
        $logs = InventoryLog::with(['inventory', 'user'])
            ->latest()->paginate(20);

        $dispensedByCategory = InventoryLog::where('action', 'dispensed')
            ->join('inventories', 'inventory_logs.inventory_id', '=', 'inventories.id')
            ->selectRaw('inventories.category, SUM(inventory_logs.quantity) as total')
            ->groupBy('inventories.category')
            ->orderByDesc('total')->get();

        return view('reports.inventory', compact('logs', 'dispensedByCategory'));
    }
}
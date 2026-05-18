<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Inventory;
use App\Models\InventoryLog;

class InventoryController extends Controller
{
    private array $categories = [
        'Analgesic', 'Antibiotic', 'Antidiabetic', 'Antihypertensive',
        'Cardiac', 'IV Fluid', 'Vaccine', 'PPE', 'Consumable', 'Surgical', 'Other',
    ];

    private array $units = ['Tablet', 'Capsule', 'Vial', 'Bottle', 'Bag', 'Box', 'Strip', 'Pair', 'Piece'];

    /** GET /inventory */
    public function index(Request $request)
    {
        $search   = $request->get('search');
        $category = $request->get('category');
        $alert    = $request->get('alert'); // 'low' | 'expiring'

        $query = Inventory::latest();

        if ($search)   $query->where('name', 'like', "%$search%");
        if ($category) $query->where('category', $category);
        if ($alert === 'low')      $query->whereRaw('current_stock <= reorder_level');
        if ($alert === 'expiring') $query->where('expiry_date', '<=', now()->addDays(30))
                                         ->where('expiry_date', '>=', today());

        $items      = $query->paginate(15);
        $lowCount   = Inventory::whereRaw('current_stock <= reorder_level')->count();
        $expCount   = Inventory::where('expiry_date', '<=', now()->addDays(30))
                               ->where('expiry_date', '>=', today())->count();
        $categories = $this->categories;

        return view('inventory.index', compact('items', 'lowCount', 'expCount', 'categories', 'search', 'category', 'alert'));
    }

    /** GET /inventory/create */
    public function create()
    {
        $categories = $this->categories;
        $units      = $this->units;
        return view('inventory.create', compact('categories', 'units'));
    }

    /** POST /inventory/store */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:150',
            'category'      => 'required|in:' . implode(',', $this->categories),
            'unit'          => 'required|in:' . implode(',', $this->units),
            'current_stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit_price'    => 'required|numeric|min:0',
            'expiry_date'   => 'nullable|date|after:today',
            'supplier'      => 'nullable|string|max:150',
            'batch_number'  => 'nullable|string|max:60',
        ], [
            'name.required'          => 'Item name is required.',
            'category.required'      => 'Please select a category.',
            'current_stock.required' => 'Current stock quantity is required.',
            'current_stock.integer'  => 'Stock must be a whole number.',
            'reorder_level.required' => 'Reorder level is required.',
            'unit_price.required'    => 'Unit price is required.',
            'expiry_date.after'      => 'Expiry date must be in the future.',
        ]);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $item = Inventory::create($request->only(
            'name', 'category', 'unit', 'current_stock',
            'reorder_level', 'unit_price', 'expiry_date', 'supplier', 'batch_number'
        ));

        // Log initial stock entry
        InventoryLog::create([
            'inventory_id' => $item->id,
            'action'       => 'added',
            'quantity'     => $request->current_stock,
            'notes'        => 'Initial stock entry',
            'done_by'      => Auth::id(),
        ]);

        return redirect()->route('inventory.index')
            ->with('success', "$item->name added to inventory successfully.");
    }

    /** GET /inventory/{id}/edit */
    public function edit($id)
    {
        $item       = Inventory::findOrFail($id);
        $categories = $this->categories;
        $units      = $this->units;
        return view('inventory.edit', compact('item', 'categories', 'units'));
    }

    /** PUT /inventory/{id} */
    public function update(Request $request, $id)
    {
        $item = Inventory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'current_stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit_price'    => 'required|numeric|min:0',
            'expiry_date'   => 'nullable|date',
            'supplier'      => 'nullable|string|max:150',
        ]);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $oldStock = $item->current_stock;
        $item->update($request->only('current_stock', 'reorder_level', 'unit_price', 'expiry_date', 'supplier'));

        // Log stock change
        if ($oldStock !== (int)$request->current_stock) {
            InventoryLog::create([
                'inventory_id' => $id,
                'action'       => $request->current_stock > $oldStock ? 'restocked' : 'adjusted',
                'quantity'     => abs($request->current_stock - $oldStock),
                'notes'        => $request->input('notes', 'Stock updated'),
                'done_by'      => Auth::id(),
            ]);
        }

        return redirect()->route('inventory.index')->with('success', 'Inventory item updated.');
    }

    /** POST /inventory/{id}/dispense */
    public function dispense(Request $request, $id)
    {
        $item = Inventory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'quantity'   => 'required|integer|min:1|max:' . $item->current_stock,
            'patient_id' => 'nullable|exists:patients,id',
            'notes'      => 'nullable|string|max:200',
        ], [
            'quantity.required' => 'Quantity to dispense is required.',
            'quantity.min'      => 'Quantity must be at least 1.',
            'quantity.max'      => 'Cannot dispense more than available stock (' . $item->current_stock . ').',
        ]);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $item->decrement('current_stock', $request->quantity);

        InventoryLog::create([
            'inventory_id' => $id,
            'action'       => 'dispensed',
            'quantity'     => $request->quantity,
            'patient_id'   => $request->patient_id,
            'notes'        => $request->notes,
            'done_by'      => Auth::id(),
        ]);

        $msg = $request->quantity . ' ' . $item->unit . '(s) of ' . $item->name . ' dispensed.';
        if ($item->current_stock - $request->quantity <= $item->reorder_level) {
            $msg .= ' ⚠️ Stock now below reorder level!';
        }

        return back()->with('success', $msg);
    }

    /** DELETE /inventory/{id} */
    public function destroy($id)
    {
        Inventory::findOrFail($id)->delete();
        return redirect()->route('inventory.index')->with('success', 'Item removed from inventory.');
    }
}
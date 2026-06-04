<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DepartmentInventory;
use App\Models\Material;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all();
        $selectedDepartment = request('department_id');

        $query = DepartmentInventory::with(['department', 'material']);

        if ($selectedDepartment) {
            $query->where('department_id', $selectedDepartment);
        }

        if(Auth::user()->department_id)
        {
            $query->where('department_id','=',Auth::user()->department_id);
        }

        $inventories = $query->paginate(15);

        return view('department-inventories.index', compact('inventories', 'departments', 'selectedDepartment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not needed - inventory auto-created when request is approved
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not needed - inventory auto-created when request is approved
        abort(403);
    }
    public function show(DepartmentInventory $departmentInventory)
    {
        $departmentInventory->load(['department', 'material']);

        return view('department-inventories.show', compact('departmentInventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DepartmentInventory $departmentInventory)
    {
        $departments = Department::all();
        $materials = Material::where('is_active', true)->get();

        return view('department-inventories.edit', compact('departmentInventory', 'departments', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DepartmentInventory $departmentInventory)
    {
        $validated = $request->validate([
            'current_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'reorder_quantity' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $departmentInventory->update($validated);

        return redirect()->route('department-inventories.show', $departmentInventory)
            ->with('success', 'Inventory berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DepartmentInventory $departmentInventory)
    {
        // Don't delete - just deactivate via status
        abort(403, 'Cannot delete inventory. Deactivate via edit instead.');
    }

    /**
     * Update stock quantity
     */
    public function updateStock(Request $request, DepartmentInventory $departmentInventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer',
            'type' => 'required|in:in,out', // in = stok masuk, out = stok keluar
            'notes' => 'nullable|string',
        ]);

        $currentStock = $departmentInventory->current_stock;

        if ($validated['type'] === 'in') {
            $departmentInventory->update([
                'current_stock' => $currentStock + $validated['quantity'],
                'last_counted_at' => now(),
            ]);
            $message = "Stok masuk {$validated['quantity']} unit berhasil dicatat.";
        } else {
            if ($validated['quantity'] > $currentStock) {
                return back()->withErrors(['quantity' => 'Stok tidak cukup untuk dikurangi.']);
            }

            $departmentInventory->update([
                'current_stock' => $currentStock - $validated['quantity'],
                'last_counted_at' => now(),
            ]);
            $message = "Stok keluar {$validated['quantity']} unit berhasil dicatat.";
        }

        return back()->with('success', $message);
    }

    /**
     * Sync or create inventory from request approval
     * Called when a request is approved
     */
    public static function syncFromRequest($requestModel)
    {
        // Get request items
        $requestItems = $requestModel->items()->get();

        foreach ($requestItems as $item) {
            $inventory = DepartmentInventory::firstOrCreate(
                [
                    'department_id' => $requestModel->department_id,
                    'material_id' => $item->material_id,
                ],
                [
                    'current_stock' => 0,
                    'unit' => $item->material->unit ?? 'kg',
                    'status' => 'active',
                ]
            );

            // Update stock
            $inventory->increment('current_stock', $item->quantity);
            $inventory->update(['last_counted_at' => now()]);

            // Record stock movement
            StockMovement::create([
                'inventory_id' => null, // or reference appropriately
                'request_id' => $requestModel->id,
                'type' => 'in',
                'quantity' => $item->quantity,
                'notes' => "Auto-sync from approved request #{$requestModel->id}",
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Material;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['material', 'user', 'request']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by material
        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('movement_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('movement_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('material', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $movements = $query->latest('movement_date')->latest('id')->paginate(15);
        $materials = Material::orderBy('name')->get();
        $types = ['in' => 'Stock In', 'out' => 'Stock Out', 'adjustment' => 'Adjustment'];

        // Statistics
        $stats = [
            'total_movements' => StockMovement::count(),
            'stock_in_today' => StockMovement::where('type', 'in')->whereDate('movement_date', today())->sum('quantity'),
            'stock_out_today' => StockMovement::where('type', 'out')->whereDate('movement_date', today())->sum('quantity'),
            'adjustments_today' => StockMovement::where('type', 'adjustment')->whereDate('movement_date', today())->count(),
        ];

        return view('stock-movements.index', compact('movements', 'materials', 'types', 'stats'));
    }

    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load([
            'material',
            'user',
            'request.job',
            'request.department'
        ]);

        return view('stock-movements.show', compact('stockMovement'));
    }

    public function export(Request $request)
    {
        // Implement export functionality (CSV/Excel)
        // This is a placeholder for export feature
        return redirect()->back()->with('info', 'Export feature coming soon');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Material;
use App\Models\Department;
use Illuminate\Http\Request as HttpRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_requests' => Request::where('user_id', $user->id)->count(),
            'pending_requests' => Request::where('user_id', $user->id)
                ->whereIn('status', ['submitted', 'checking_stock'])->count(),
            'approved_requests' => Request::where('user_id', $user->id)
                ->whereIn('status', ['approved', 'ready_for_pickup'])->count(),
            'completed_requests' => Request::where('user_id', $user->id)
                ->where('status', 'completed')->count(),
        ];

        if ($user->isWarehouse() || $user->isAdmin()) {
            $stats = [
                'total_requests' => Request::count(),
                'pending_requests' => Request::whereIn('status', ['submitted', 'checking_stock'])->count(),
                'low_stock_materials' => Material::whereRaw('current_stock <= min_stock')->count(),
                'total_materials' => Material::count(),
            ];
        }

        $recent_requests = Request::with(['department', 'user'])
            ->when(!$user->isWarehouse() && !$user->isAdmin(), function($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        $low_stock = Material::whereRaw('current_stock <= min_stock')
            ->where('is_active', true)
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_requests', 'low_stock'));
    }
}

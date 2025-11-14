<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Rental;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = auth()->user();

        // Get statistics for admin's items only
        $stats = [
            'total_items' => $admin->items()->count(),
            'total_rentals' => Rental::whereHas('item', function($query) use ($admin) {
                $query->where('user_id', $admin->id);
            })->count(),
            'active_rentals' => Rental::whereHas('item', function($query) use ($admin) {
                $query->where('user_id', $admin->id);
            })->where('status', 'active')->count(),
            'total_revenue' => Rental::whereHas('item', function($query) use ($admin) {
                $query->where('user_id', $admin->id);
            })->where('status', 'completed')->sum('total_amount'),
        ];

        // Get recent rentals for admin's items
        $recent_rentals = Rental::with(['item', 'user'])
            ->whereHas('item', function($query) use ($admin) {
                $query->where('user_id', $admin->id);
            })
            ->latest()
            ->take(10)
            ->get();

        // Get admin's items with category
        $myItems = $admin->items()
            ->with('category')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_rentals', 'myItems'));
    }
}
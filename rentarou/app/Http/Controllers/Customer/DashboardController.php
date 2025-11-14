<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Rental;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get statistics
        $stats = [
            'active_rentals' => $user->rentals()->where('status', 'active')->count(),
            'past_rentals' => $user->rentals()->where('status', 'completed')->count(),
            'total_spent' => $user->rentals()->where('status', 'completed')->sum('total_amount'),
        ];

        // Get active rentals with items
        $activeRentals = $user->rentals()
            ->with('item')
            ->where('status', 'active')
            ->orderBy('end_date', 'asc')
            ->get();

        // Get categories for filter
        $categories = Category::withCount('items')->get();

        // Get search and filter parameters
        $search = $request->get('search');
        $categoryFilter = $request->get('category');

        // Query available items
        $availableItems = Item::with('category')
            ->where('status', 'available')
            ->where('available_quantity', '>', 0)
            ->when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($categoryFilter, function($query, $categoryFilter) {
                return $query->where('category_id', $categoryFilter);
            })
            ->latest()
            ->paginate(12);

        // Get upcoming returns (within 3 days)
        $upcomingReturns = $user->rentals()
            ->with('item')
            ->where('status', 'active')
            ->whereBetween('end_date', [Carbon::today(), Carbon::today()->addDays(3)])
            ->get();

        // Get overdue rentals
        $overdueRentals = $user->rentals()
            ->with('item')
            ->where('status', 'active')
            ->where('end_date', '<', Carbon::today())
            ->get();

        return view('customer.dashboard', compact(
            'stats',
            'activeRentals',
            'categories',
            'availableItems',
            'upcomingReturns',
            'overdueRentals',
            'search',
            'categoryFilter'
        ));
    }
}
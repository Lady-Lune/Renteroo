<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Placeholder stats (will add real data later)
        $stats = [
            'total_items' => 0,
            'total_rentals' => 0,
            'active_rentals' => 0,
            'total_revenue' => 0,
        ];

        // Placeholder data
        $recent_rentals = [];

        return view('admin.dashboard', compact('stats', 'recent_rentals'));
    }
}
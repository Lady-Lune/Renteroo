<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Placeholder stats
        $stats = [
            'active_rentals' => 0,
            'past_rentals' => 0,
            'total_spent' => 0,
        ];

        // Placeholder data
        $active_rentals = [];
        $available_items = [];

        return view('customer.dashboard', compact('stats', 'active_rentals', 'available_items'));
    }
}
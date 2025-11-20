<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rentals = auth()->user()->rentals()
            ->with('item.category', 'invoice')
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('customer.rentals', compact('rentals'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function show(Rental $rental)
    {
        // Ensure the user is authorized to see this rental
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }

        $rental->load('item.category', 'invoice', 'damageReports');

        return view('customer.rental_show', compact('rental'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Item;
use App\Models\User;
use App\Models\Invoice;
use Carbon\Carbon;

class RentalController extends Controller
{
    /**
     * Display a listing of rentals
     */
    public function index()
    {
        $rentals = Rental::with(['user', 'item'])
            ->whereHas('item', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->paginate(15);

        return view('admin.rentals.index', compact('rentals'));
    }

    /**
     * Show the form for creating a new rental
     */
    public function create()
    {
        $items = Item::where('user_id', auth()->id())
            ->where('status', 'available')
            ->where('available_quantity', '>', 0)
            ->with('category')
            ->get();

        $customers = User::where('role', 'customer')->get();

        return view('admin.rentals.create', compact('items', 'customers'));
    }

    /**
     * Store a newly created rental
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        // Check availability
        if ($item->available_quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough quantity available!')->withInput();
        }

        // Calculate rental days
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $days = $startDate->diffInDays($endDate) + 1;

        // Calculate total amount
        $totalAmount = $item->rental_rate * $days * $validated['quantity'];

        // Create rental
        $rental = Rental::create([
            'user_id' => $validated['user_id'],
            'item_id' => $validated['item_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'quantity' => $validated['quantity'],
            'daily_rate' => $item->rental_rate,
            'total_amount' => $totalAmount,
            'status' => 'active',
            'notes' => $validated['notes'],
        ]);

        // Update item availability
        $item->decrement('available_quantity', $validated['quantity']);

        // Create invoice
        Invoice::create([
            'rental_id' => $rental->id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'subtotal' => $totalAmount,
            'total' => $totalAmount,
            'due_date' => $validated['end_date'],
            'status' => 'pending',
        ]);

        return redirect()->route('admin.rentals.index')
            ->with('success', 'Rental created successfully!');
    }

    /**
     * Display the specified rental
     */
    public function show(Rental $rental)
    {
        $rental->load(['user', 'item.category', 'invoice']);
        return view('admin.rentals.show', compact('rental'));
    }
}
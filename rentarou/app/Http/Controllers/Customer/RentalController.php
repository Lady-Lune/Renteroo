<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Item;
use App\Models\Invoice;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RentalController extends Controller
{
    /**
     * Display a listing of customer's rentals
     */
    public function index()
    {
        $rentals = Rental::with(['item.category'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('customer.rentals.index', compact('rentals'));
    }

    /**
     * Display the specified rental
     */
    public function show(Rental $rental)
    {
        // Authorization: Only customer who owns the rental can view it
        if ($rental->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $rental->load(['item.category', 'invoice']);
        return view('customer.rentals.show', compact('rental'));
    }

    /**
     * Download invoice PDF for customer's rental
     */
    public function downloadInvoice(Rental $rental)
    {
        // Authorization: Only customer who owns the rental can download invoice
        if ($rental->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if rental has an invoice
        if (!$rental->invoice) {
            return redirect()->back()->with('error', 'No invoice found for this rental.');
        }

        $rental->load(['item.category', 'invoice', 'user']);

        $pdf = Pdf::loadView('admin.invoices.rental', compact('rental'));
        
        return $pdf->download('invoice-' . $rental->invoice->invoice_number . '.pdf');
    }

    /**
     * Store a newly created rental for customer
     */
    public function store(Request $request)
    {
        // Validate the rental request
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        // Check if item is available and has enough quantity
        if ($item->status !== 'available') {
            return back()->with('error', 'This item is not available for rental.')->withInput();
        }

        if ($item->available_quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough quantity available. Only ' . $item->available_quantity . ' units available.')->withInput();
        }

        // Calculate rental days
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $days = $startDate->diffInDays($endDate) + 1;

        // Calculate total amount
        $totalAmount = $item->rental_rate * $days * $validated['quantity'];

        // Create rental for authenticated customer
        $rental = Rental::create([
            'user_id' => auth()->id(),
            'item_id' => $validated['item_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'quantity' => $validated['quantity'],
            'daily_rate' => $item->rental_rate,
            'total_amount' => $totalAmount,
            'status' => 'active',
            'notes' => $validated['notes'] ?? null,
            'is_guest' => false,
        ]);

        // Update item availability
        $item->decrement('available_quantity', $validated['quantity']);

        // Create invoice
        Invoice::create([
            'rental_id' => $rental->id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'subtotal' => $totalAmount,
            'total' => $totalAmount,
            'due_date' => Carbon::parse($validated['end_date'])->addDays(7), // Payment due 7 days after rental end
            'status' => 'pending',
        ]);

        return redirect()->route('customer.rentals.show', $rental->id)
            ->with('success', 'Rental booked successfully! Your booking confirmation and invoice details are below.');
    }
}


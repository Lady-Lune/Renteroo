<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Item;
use App\Models\User;
use App\Models\Invoice;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
        // Validate based on whether it's a guest rental
        $rules = [
            'item_id' => 'required|exists:items,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'is_guest' => 'boolean',
        ];

        if ($request->is_guest) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_phone'] = 'required|string|max:20';
            $rules['guest_email'] = 'nullable|email|max:255';
            $rules['guest_id_number'] = 'required|string|max:50';
        } else {
            $rules['user_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

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

        // Prepare rental data
        $rentalData = [
            'item_id' => $validated['item_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'quantity' => $validated['quantity'],
            'daily_rate' => $item->rental_rate,
            'total_amount' => $totalAmount,
            'status' => 'active',
            'notes' => $validated['notes'],
            'is_guest' => $request->is_guest ?? false,
        ];

        // Add user_id or guest info
        if ($request->is_guest) {
            $rentalData['guest_name'] = $validated['guest_name'];
            $rentalData['guest_phone'] = $validated['guest_phone'];
            $rentalData['guest_email'] = $validated['guest_email'] ?? null;
            $rentalData['guest_id_number'] = $validated['guest_id_number'];
        } else {
            $rentalData['user_id'] = $validated['user_id'];
        }

        // Create rental
        $rental = Rental::create($rentalData);

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
     * Store a quick customer registration
     */
    public function quickRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => 'customer',
        ]);

        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'Customer registered successfully!'
        ]);
    }

    /**
     * Display the specified rental
     */
    public function show(Rental $rental)
    {
        $rental->load(['user', 'item.category', 'invoice']);
        return view('admin.rentals.show', compact('rental'));
    }


    /**
     * Download PDF invoice for the rental
     */
    public function downloadInvoice(Rental $rental)
    {
        // Ensure admin can only download invoices for their own items
        if ($rental->item->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this rental.');
        }

        // Load necessary relationships
        $rental->load(['user', 'item.category', 'invoice']);

        // Check if invoice exists
        if (!$rental->invoice) {
            return back()->with('error', 'No invoice found for this rental.');
        }

        // Generate PDF
        $pdf = Pdf::loadView('admin.invoices.rental', compact('rental'));

        // Download with proper filename
        return $pdf->download('invoice_' . $rental->invoice->invoice_number . '.pdf');
    }

    /**
     * Cancel a rental
     */
    public function cancel(Rental $rental)
    {
        // Authorization: Only admin who owns the rented item can cancel
        if ($rental->item->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow cancellation for pending or active rentals
        if (!in_array($rental->status, ['pending', 'active'])) {
            return back()->with('error', 'Only pending or active rentals can be cancelled.');
        }

        // Restore item availability
        $rental->item->increment('available_quantity', $rental->quantity);

        // Update rental status
        $rental->update([
            'status' => 'cancelled',
            'notes' => ($rental->notes ? $rental->notes . "\n\n" : '') . 
                      'Rental cancelled on ' . now()->format('M d, Y H:i') . ' by admin.'
        ]);

        // For pending invoices, we keep them but they become irrelevant since rental is cancelled
        // The invoice can be used for record keeping purposes

        return back()->with('success', 'Rental has been cancelled successfully. Item availability has been restored.');
    }
}

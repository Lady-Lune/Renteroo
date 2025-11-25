<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * List invoices for authenticated user
     * Customers see only their invoices; admins see all
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $invoices = Invoice::with('rental.user', 'rental.item')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Customer sees only their invoices
            $invoices = Invoice::whereHas('rental', function ($query) {
                $query->where('user_id', auth()->id());
            })
                ->with('rental.user', 'rental.item')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        
        return view('invoices.index', ['invoices' => $invoices]);
    }

    /**
     * Show a single invoice
     */
    public function show(Invoice $invoice)
    {
        // Authorization: customer can only view their own invoices; admin can view all
        if (!auth()->user()->isAdmin() && $invoice->rental->user_id !== auth()->id()) {
            abort(403, 'Unauthorized to view this invoice.');
        }

        $invoice->load('rental.user', 'rental.item');
        return view('invoices.show', ['invoice' => $invoice]);
    }

    /**
     * Mark invoice as paid (admin only)
     */
    public function markPaid(Invoice $invoice, Request $request)
    {
        // Admin only
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only admins can mark invoices as paid.');
        }

        $validated = $request->validate([
            'transaction_id' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:manual,transfer,check,cash',
        ]);

        $invoice->update([
            'status' => 'paid',
            'paid_date' => now()->toDateString(),
            'transaction_id' => $validated['transaction_id'],
            'payment_method' => $validated['payment_method'],
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice marked as paid.');
    }

    /**
     * Download invoice as PDF
     * Uses barryvdh/laravel-dompdf package
     */
    public function download(Invoice $invoice)
    {
        // Authorization: customer can only view their own invoices; admin can view all
        if (!auth()->user()->isAdmin() && $invoice->rental->user_id !== auth()->id()) {
            abort(403, 'Unauthorized to download this invoice.');
        }

        $invoice->load('rental.user', 'rental.item.category');

        // Use the existing admin.invoices.rental view for PDF generation
        try {
            $pdf = \PDF::loadView('admin.invoices.rental', ['rental' => $invoice->rental]);
            return $pdf->download('invoice_' . $invoice->invoice_number . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to generate PDF. Please try again.');
        }
    }
}

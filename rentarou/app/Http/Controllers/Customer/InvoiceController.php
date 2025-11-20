<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = auth()->user()->invoices()
            ->with('rental.item')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.invoices', compact('invoices'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        // Ensure the user is authorized to see this invoice
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }

        $invoice->load('rental.item.category', 'damageReports');

        return view('customer.invoice_show', compact('invoice'));
    }
}

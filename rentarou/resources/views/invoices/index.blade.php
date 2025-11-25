@extends('layouts.app')

@section('content')
<style>
    .invoice-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .invoice-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transform: translateX(5px);
    }

    .invoice-card.paid {
        border-left-color: #28a745;
    }

    .invoice-card.overdue {
        border-left-color: #dc3545;
    }

    .invoice-card.pending {
        border-left-color: #ffc107;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin: -24px -12px 2rem -12px;
        border-radius: 0 0 20px 20px;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }
</style>

<div class="page-header">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">
            <i class="bi bi-receipt"></i> My Invoices
        </h1>
        <p class="lead mb-0">View and manage all your rental invoices</p>
    </div>
</div>

<div class="container">
    @if($invoices->count() > 0)
        <div class="row">
            @foreach($invoices as $invoice)
                <div class="col-12">
                    <div class="invoice-card {{ strtolower($invoice->status) }}">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h5 class="mb-1">Invoice #{{ $invoice->invoice_number }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-box"></i> {{ $invoice->rental->item->name }}
                                    <span class="ms-2">
                                        <i class="bi bi-tag"></i> {{ $invoice->rental->item->category->name }}
                                    </span>
                                </p>
                                <div>
                                    <span class="badge bg-info">
                                        <i class="bi bi-calendar"></i> 
                                        {{ $invoice->rental->start_date->format('M d') }} - {{ $invoice->rental->end_date->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-3 text-lg-center mt-3 mt-lg-0">
                                <div class="mb-2">
                                    <span class="badge 
                                        @if($invoice->status == 'paid') bg-success
                                        @elseif($invoice->status == 'pending') bg-warning
                                        @else bg-danger
                                        @endif">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </div>
                                <div class="mb-1">
                                    <strong>Amount: Rs {{ number_format($invoice->total, 2) }}</strong>
                                </div>
                                <small class="text-muted">Due: {{ $invoice->due_date->format('M d, Y') }}</small>
                            </div>
                            <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('customer.rentals.show', $invoice->rental->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i> View Rental
                                    </a>
                                    <a href="{{ route('customer.rentals.download-invoice', $invoice->rental->id) }}" 
                                       class="btn btn-primary btn-sm" target="_blank">
                                        <i class="bi bi-download"></i> Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $invoices->links('custom-pagination') }}
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-receipt"></i>
            <h4>No invoices yet</h4>
            <p>You don't have any rental invoices yet. Start by booking a rental!</p>
            <a href="{{ route('customer.dashboard') }}" class="btn btn-primary">Browse Items</a>
        </div>
    @endif
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection
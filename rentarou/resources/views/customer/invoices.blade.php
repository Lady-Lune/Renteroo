@extends('layouts.app')

@section('content')
<style>
    .customer-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin: -24px -12px 2rem -12px;
        border-radius: 0 0 20px 20px;
    }
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
    .section-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #212529;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        background: #f8f9fa;
        border-radius: 15px;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 4rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }
</style>

<!-- Hero Section -->
<div class="customer-hero">
    <div class="container">
        <h1 class="display-5 fw-bold">My Invoices</h1>
        <p class="lead">A complete history of all your invoices.</p>
    </div>
</div>

<div class="container">

    <div class="mb-4">
        <h2 class="section-title">
            <i class="bi bi-list-ul"></i> All My Invoices
        </h2>

        @if($invoices->count() > 0)
            @foreach($invoices as $invoice)
                <div class="invoice-card">
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center d-none d-md-block">
                            <i class="bi bi-receipt" style="font-size: 2.5rem; color: #667eea;"></i>
                        </div>
                        <div class="col-md-5">
                            <h5 class="mb-1">Invoice #{{ $invoice->id }}</h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-calendar-check"></i> 
                                Issued: {{ $invoice->created_at->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="col-md-3 mt-3 mt-md-0">
                            <div class="fw-bold">Total Amount</div>
                            <div class="text-primary" style="font-size: 1.2rem; font-weight: 700;">
                                Rs {{ number_format($invoice->amount, 2) }}
                            </div>
                        </div>
                        <div class="col-md-3 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('customer.invoices.show', $invoice->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                            <a href="#" class="btn btn-outline-secondary btn-sm mt-1">
                                <i class="bi bi-download"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-4">
                {{ $invoices->links() }}
            </div>

        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>No Invoices Found</h4>
                <p>You don't have any invoices yet.</p>
            </div>
        @endif
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection

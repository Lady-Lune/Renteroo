@extends('layouts.app')

@section('content')
<style>
    .invoice-container {
        max-width: 800px;
        margin: auto;
        padding: 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .invoice-header {
        text-align: center;
        margin-bottom: 2rem;
        border-bottom: 2px solid #eee;
        padding-bottom: 1rem;
    }
    .invoice-header h1 {
        font-size: 2.5rem;
        color: #333;
    }
    .invoice-details, .rental-details, .item-details {
        margin-bottom: 2rem;
    }
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    .detail-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
    }
    .detail-item strong {
        display: block;
        margin-bottom: 0.5rem;
        color: #555;
    }
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #667eea;
        border-bottom: 2px solid #667eea;
        padding-bottom: 0.5rem;
    }
    .invoice-actions {
        text-align: center;
        margin-top: 2rem;
    }
</style>

<div class="container py-5">
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Invoice Details</h1>
            <p class="text-muted">Invoice #{{ $invoice->id }}</p>
        </div>

        <div class="invoice-details">
            <h2 class="section-title">Invoice Information</h2>
            <div class="details-grid">
                <div class="detail-item">
                    <strong>Invoice ID</strong>
                    <span>#{{ $invoice->id }}</span>
                </div>
                <div class="detail-item">
                    <strong>Issued Date</strong>
                    <span>{{ $invoice->created_at->format('F d, Y') }}</span>
                </div>
                <div class="detail-item">
                    <strong>Total Amount</strong>
                    <span style="font-size: 1.2rem; font-weight: 700; color: #667eea;">
                        Rs {{ number_format($invoice->amount, 2) }}
                    </span>
                </div>
            </div>
        </div>

        @if($invoice->rental)
        <div class="rental-details">
            <h2 class="section-title">Rental Information</h2>
            <div class="details-grid">
                <div class="detail-item">
                    <strong>Rental ID</strong>
                    <span>#{{ $invoice->rental->id }}</span>
                </div>
                <div class="detail-item">
                    <strong>Start Date</strong>
                    <span>{{ $invoice->rental->start_date->format('F d, Y') }}</span>
                </div>
                <div class="detail-item">
                    <strong>End Date</strong>
                    <span>{{ $invoice->rental->end_date->format('F d, Y') }}</span>
                </div>
            </div>
        </div>

        <div class="item-details">
            <h2 class="section-title">Item Information</h2>
            <div class="details-grid">
                <div class="detail-item">
                    <strong>Item Name</strong>
                    <span>{{ $invoice->rental->item->name }}</span>
                </div>
                <div class="detail-item">
                    <strong>Category</strong>
                    <span>{{ $invoice->rental->item->category->name ?? 'Uncategorized' }}</span>
                </div>
                <div class="detail-item">
                    <strong>Daily Rate</strong>
                    <span>Rs {{ number_format($invoice->rental->item->daily_rate, 2) }}</span>
                </div>
            </div>
        </div>
        @endif

        <div class="invoice-actions">
            <a href="#" class="btn btn-primary btn-lg">
                <i class="bi bi-download"></i> Download as PDF
            </a>
            <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline-secondary btn-lg ms-2">
                <i class="bi bi-arrow-left"></i> Back to Invoices
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection

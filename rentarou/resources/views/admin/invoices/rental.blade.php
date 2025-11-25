<!DOCTYPE html>
<html>
<head>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .invoice-details {
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .customer-info, .rental-info {
            width: 48%;
            float: left;
        }
        
        .rental-info {
            float: right;
        }
        
        .info-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            color: #667eea;
        }
        
        .rental-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .rental-table th,
        .rental-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        
        .rental-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        
        .total-row {
            margin-bottom: 5px;
        }
        
        .grand-total {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #667eea;
            padding-top: 10px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            background-color: #e9ecef;
            color: #495057;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Rentarou</div>
        <div>Rental Management System</div>
        <div class="invoice-title">INVOICE</div>
    </div>

    <div class="invoice-details clearfix">
        <div class="customer-info">
            <div class="info-title">Customer Information</div>
            @if($rental->is_guest)
                <p><strong>Name:</strong> {{ $rental->guest_name }}</p>
                <p><strong>Phone:</strong> {{ $rental->guest_phone }}</p>
                @if($rental->guest_email)
                    <p><strong>Email:</strong> {{ $rental->guest_email }}</p>
                @endif
                <p><strong>ID Number:</strong> {{ $rental->guest_id_number }}</p>
                <p><span class="status-badge">Guest Customer</span></p>
            @else
                <p><strong>Name:</strong> {{ $rental->user->name }}</p>
                <p><strong>Email:</strong> {{ $rental->user->email }}</p>
                <p><strong>Customer ID:</strong> #{{ $rental->user->id }}</p>
                <p><span class="status-badge">Registered Customer</span></p>
            @endif
        </div>

        <div class="rental-info">
            <div class="info-title">Invoice Details</div>
            <p><strong>Invoice #:</strong> {{ $rental->invoice->invoice_number }}</p>
            <p><strong>Rental ID:</strong> #{{ $rental->id }}</p>
            <p><strong>Issue Date:</strong> {{ $rental->invoice->created_at->format('d/m/Y') }}</p>
            <p><strong>Due Date:</strong> {{ $rental->invoice->due_date->format('d/m/Y') }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge">{{ ucfirst($rental->invoice->status) }}</span>
            </p>
        </div>
    </div>

    <table class="rental-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Category</th>
                <th>Rental Period</th>
                <th>Quantity</th>
                <th>Daily Rate</th>
                <th>Days</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $rental->item->name }}</td>
                <td>{{ $rental->item->category->name }}</td>
                <td>{{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}</td>
                <td>{{ $rental->quantity }}</td>
                <td>Rs {{ number_format($rental->daily_rate, 2) }}</td>
                <td>{{ $rental->getRentalDays() }}</td>
                <td>Rs {{ number_format($rental->total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if($rental->notes)
        <p><strong>Notes:</strong> {{ $rental->notes }}</p>
    @endif

    <div class="total-section">
        <div class="total-row">
            <strong>Subtotal: Rs {{ number_format($rental->invoice->subtotal, 2) }}</strong>
        </div>
        
        @if($rental->late_fee > 0)
            <div class="total-row">
                <strong>Late Fee: Rs {{ number_format($rental->late_fee, 2) }}</strong>
            </div>
        @endif
        
        @if($rental->damage_fee > 0)
            <div class="total-row">
                <strong>Damage Fee: Rs {{ number_format($rental->damage_fee, 2) }}</strong>
            </div>
        @endif
        
        <div class="grand-total">
            <strong>Total Amount: Rs {{ number_format($rental->invoice->total, 2) }}</strong>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for choosing Rentarou!</p>
        <p>For any questions regarding this invoice, please contact us.</p>
        <p>Generated on {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
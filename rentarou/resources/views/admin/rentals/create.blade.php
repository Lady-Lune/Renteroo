@extends('layouts.app')

@section('content')
<style>
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .item-preview {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        background: #f8f9fa;
    }

    .calculation-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 15px;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-calendar-plus"></i> Create New Rental</h2>
            <p class="text-muted mb-0">Create a rental booking for a customer</p>
        </div>
        <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.rentals.store') }}" method="POST" id="rentalForm">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <!-- Customer & Item Selection -->
                <div class="form-card">
                    <h5 class="mb-4">Booking Details</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer *</label>
                            <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" required>
                                <option value="">Select customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Item *</label>
                            <select class="form-select @error('item_id') is-invalid @enderror" name="item_id" id="itemSelect" required onchange="updateItemPreview()">
                                <option value="">Select item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" 
                                            data-rate="{{ $item->rental_rate }}" 
                                            data-available="{{ $item->available_quantity }}"
                                            data-category="{{ $item->category->name }}"
                                            {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }} ({{ $item->available_quantity }} available)
                                    </option>
                                @endforeach
                            </select>
                            @error('item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Start Date *</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" id="startDate" value="{{ old('start_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required onchange="calculateTotal()">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">End Date *</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" id="endDate" value="{{ old('end_date') }}" required onchange="calculateTotal()">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Quantity *</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1" required onchange="calculateTotal()">
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Item Preview -->
                <div class="form-card">
                    <h5 class="mb-3">Item Preview</h5>
                    <div class="item-preview text-center" id="itemPreview">
                        <i class="bi bi-box-seam" style="font-size: 3rem; color: #6c757d; opacity: 0.3;"></i>
                        <p class="text-muted mt-2 mb-0">Select an item to see details</p>
                    </div>
                </div>

                <!-- Calculation -->
                <div class="calculation-box">
                    <h5 class="mb-3">Rental Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Rate per day:</span>
                        <strong>Rs <span id="rateDisplay">0.00</span></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Number of days:</span>
                        <strong><span id="daysDisplay">0</span></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Quantity:</span>
                        <strong><span id="qtyDisplay">0</span></strong>
                    </div>
                    <hr style="border-color: rgba(255,255,255,0.3);">
                    <div class="d-flex justify-content-between">
                        <h5>Total Amount:</h5>
                        <h4>Rs <span id="totalDisplay">0.00</span></h4>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mt-3">
                    <i class="bi bi-check-circle"></i> Create Rental
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function updateItemPreview() {
        const select = document.getElementById('itemSelect');
        const option = select.options[select.selectedIndex];
        const preview = document.getElementById('itemPreview');
        
        if (option.value) {
            const category = option.dataset.category;
            const available = option.dataset.available;
            const rate = option.dataset.rate;
            
            preview.innerHTML = `
                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                <h6 class="mt-3 mb-1">${option.text.split(' (')[0]}</h6>
                <p class="text-muted small mb-2">${category}</p>
                <p class="mb-0"><strong>Rs ${rate}</strong> per day</p>
                <p class="text-success small mb-0">${available} available</p>
            `;
        } else {
            preview.innerHTML = `
                <i class="bi bi-box-seam" style="font-size: 3rem; color: #6c757d; opacity: 0.3;"></i>
                <p class="text-muted mt-2 mb-0">Select an item to see details</p>
            `;
        }
        
        calculateTotal();
    }

    function calculateTotal() {
        const select = document.getElementById('itemSelect');
        const option = select.options[select.selectedIndex];
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const quantity = parseInt(document.getElementById('quantity').value) || 0;
        
        if (option.value && startDate && endDate && quantity > 0) {
            const rate = parseFloat(option.dataset.rate);
            const start = new Date(startDate);
            const end = new Date(endDate);
            const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
            
            if (days > 0) {
                const total = rate * days * quantity;
                document.getElementById('rateDisplay').textContent = rate.toFixed(2);
                document.getElementById('daysDisplay').textContent = days;
                document.getElementById('qtyDisplay').textContent = quantity;
                document.getElementById('totalDisplay').textContent = total.toFixed(2);
                return;
            }
        }
        
        document.getElementById('rateDisplay').textContent = '0.00';
        document.getElementById('daysDisplay').textContent = '0';
        document.getElementById('qtyDisplay').textContent = '0';
        document.getElementById('totalDisplay').textContent = '0.00';
    }
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection
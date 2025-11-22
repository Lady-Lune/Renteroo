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

    .guest-section {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .item-preview {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        background: #f8f9fa;
        text-align: center;
    }

    .calculation-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 15px;
    }

    .customer-option {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .customer-option:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .customer-option input[type="radio"] {
        width: 20px;
        height: 20px;
    }

    .customer-option.selected {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.1);
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
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.rentals.store') }}" method="POST" id="rentalForm">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <!-- Customer Selection -->
                <div class="form-card">
                    <h5 class="mb-4">Customer Selection</h5>

                    <!-- Customer Type Options -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="customer-option" id="registeredOption">
                                <input type="radio" name="customer_type" value="registered" checked onchange="toggleCustomerType()">
                                <div>
                                    <strong><i class="bi bi-person-check"></i> Registered Customer</strong>
                                    <p class="text-muted small mb-0">Select from existing customers</p>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="customer-option" id="guestOption">
                                <input type="radio" name="customer_type" value="guest" onchange="toggleCustomerType()">
                                <div>
                                    <strong><i class="bi bi-person-plus"></i> Guest Rental</strong>
                                    <p class="text-muted small mb-0">Walk-in customer (no account)</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Registered Customer Section -->
                    <div id="registeredSection">
                        <label class="form-label">Select Customer *</label>
                        <div class="input-group mb-3">
                            <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" id="userSelect">
                                <option value="">Select a customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#quickRegisterModal">
                                <i class="bi bi-plus-circle"></i> New Customer
                            </button>
                        </div>
                        @error('user_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Guest Customer Section -->
                    <div id="guestSection" style="display: none;">
                        <input type="hidden" name="is_guest" id="isGuestInput" value="0">
                        
                        <div class="guest-section">
                            <h6 class="mb-3"><i class="bi bi-person-badge"></i> Guest Customer Details</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('guest_name') is-invalid @enderror" name="guest_name" value="{{ old('guest_name') }}" placeholder="Enter customer name">
                                    @error('guest_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="text" class="form-control @error('guest_phone') is-invalid @enderror" name="guest_phone" value="{{ old('guest_phone') }}" placeholder="Enter phone number">
                                    @error('guest_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email (Optional)</label>
                                    <input type="email" class="form-control @error('guest_email') is-invalid @enderror" name="guest_email" value="{{ old('guest_email') }}" placeholder="Enter email">
                                    @error('guest_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ID/License Number *</label>
                                    <input type="text" class="form-control @error('guest_id_number') is-invalid @enderror" name="guest_id_number" value="{{ old('guest_id_number') }}" placeholder="For verification">
                                    @error('guest_id_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item & Date Selection -->
                <div class="form-card">
                    <h5 class="mb-4">Rental Details</h5>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Item to Rent *</label>
                            <select class="form-select @error('item_id') is-invalid @enderror" name="item_id" id="itemSelect" required onchange="updateItemPreview()">
                                <option value="">Select an item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" 
                                            data-rate="{{ $item->rental_rate }}" 
                                            data-available="{{ $item->available_quantity }}"
                                            data-category="{{ $item->category->name }}"
                                            data-icon="{{ $item->category->icon }}"
                                            {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }} - Rs {{ number_format($item->rental_rate, 2) }}/day ({{ $item->available_quantity }} available)
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
                    <div class="item-preview" id="itemPreview">
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

<!-- Quick Register Modal -->
<div class="modal fade" id="quickRegisterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill"></i> Quick Customer Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickRegisterForm">
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="quick_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" id="quick_email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone *</label>
                        <input type="text" class="form-control" id="quick_phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Temporary Password *</label>
                        <input type="password" class="form-control" id="quick_password" value="password123">
                        <small class="text-muted">Customer can change this after first login</small>
                    </div>
                </form>
                <div id="quickRegisterMessage"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="registerQuickCustomer()">
                    <i class="bi bi-check-circle"></i> Register Customer
                </button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<script>
    // Toggle between registered and guest customer
    function toggleCustomerType() {
        const type = document.querySelector('input[name="customer_type"]:checked').value;
        const registeredSection = document.getElementById('registeredSection');
        const guestSection = document.getElementById('guestSection');
        const isGuestInput = document.getElementById('isGuestInput');
        const userSelect = document.getElementById('userSelect');

        if (type === 'guest') {
            registeredSection.style.display = 'none';
            guestSection.style.display = 'block';
            isGuestInput.value = '1';
            userSelect.removeAttribute('required');
            
            // Update visual selection
            document.getElementById('guestOption').classList.add('selected');
            document.getElementById('registeredOption').classList.remove('selected');
        } else {
            registeredSection.style.display = 'block';
            guestSection.style.display = 'none';
            isGuestInput.value = '0';
            userSelect.setAttribute('required', 'required');
            
            // Update visual selection
            document.getElementById('registeredOption').classList.add('selected');
            document.getElementById('guestOption').classList.remove('selected');
        }
    }

    // Quick customer registration
    function registerQuickCustomer() {
        const name = document.getElementById('quick_name').value;
        const email = document.getElementById('quick_email').value;
        const phone = document.getElementById('quick_phone').value;
        const password = document.getElementById('quick_password').value;

        fetch('{{ route("admin.rentals.quickRegister") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name, email, phone, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new customer to dropdown
                const option = new Option(data.user.name + ' (' + data.user.email + ')', data.user.id, true, true);
                document.getElementById('userSelect').add(option);
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('quickRegisterModal')).hide();
                
                // Show success message
                alert('Customer registered successfully!');
                
                // Reset form
                document.getElementById('quickRegisterForm').reset();
            }
        })
        .catch(error => {
            document.getElementById('quickRegisterMessage').innerHTML = 
                '<div class="alert alert-danger">Error: ' + error.message + '</div>';
        });
    }

    // Item preview and calculation functions
    function updateItemPreview() {
        const select = document.getElementById('itemSelect');
        const option = select.options[select.selectedIndex];
        const preview = document.getElementById('itemPreview');
        
        if (option.value) {
            const category = option.dataset.category;
            const available = option.dataset.available;
            const rate = option.dataset.rate;
            const icon = option.dataset.icon;
            
            preview.innerHTML = `
                <i class="${icon} text-primary" style="font-size: 3rem;"></i>
                <h6 class="mt-3 mb-1">${option.text.split(' - ')[0]}</h6>
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
                
                // Update end date min
                document.getElementById('endDate').min = startDate;
                return;
            }
        }
        
        document.getElementById('rateDisplay').textContent = '0.00';
        document.getElementById('daysDisplay').textContent = '0';
        document.getElementById('qtyDisplay').textContent = '0';
        document.getElementById('totalDisplay').textContent = '0.00';
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('registeredOption').classList.add('selected');
    });
</script>
@endsection
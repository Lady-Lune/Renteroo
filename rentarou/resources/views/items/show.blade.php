<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->name }} - Rentarou</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .item-image-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }

        .main-image {
            width: 100%;
            height: 500px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 8rem;
        }

        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px;
        }

        .item-details-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
        }

        .stock-badge.in-stock {
            background: #d1f2eb;
            color: #0f5132;
        }

        .stock-badge.out-of-stock {
            background: #f8d7da;
            color: #842029;
        }

        .stock-badge.limited-stock {
            background: #fff3cd;
            color: #664d03;
        }

        .price-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
        }

        .price-large {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
        }

        .booking-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            margin-top: 2rem;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .btn-book {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-book:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .calculation-box {
            background: white;
            border: 2px solid #e9ecef;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 1.25rem;
        }

        .related-item-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .related-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        }

        .related-image {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }

        .related-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rating-stars {
            color: #ffc107;
            font-size: 1.25rem;
        }

        .feature-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #212529;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-box-seam"></i> Rentarou
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('items.index') }}">Browse Items</a>
                    </li>
                    @auth
                        @if(auth()->user()->isCustomer())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.dashboard') }}">My Dashboard</a>
                            </li>
                        @elseif(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('items.index') }}">Items</a></li>
                <li class="breadcrumb-item"><a href="{{ route('items.index', ['category' => $item->category_id]) }}">{{ $item->category->name }}</a></li>
                <li class="breadcrumb-item active">{{ $item->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Left Column - Image -->
            <div class="col-lg-6 mb-4">
                <div class="item-image-section">
                    <div class="main-image">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
                        @else
                            <i class="{{ $item->category->icon }}"></i>
                        @endif
                    </div>
                </div>

                <!-- Item Features -->
                <div class="item-details-section mt-4">
                    <h5 class="mb-3">Features & Details</h5>
                    <div class="feature-badge">
                        <i class="bi bi-shield-check"></i> Verified Item
                    </div>
                    <div class="feature-badge">
                        <i class="bi bi-truck"></i> Same Day Pickup
                    </div>
                    <div class="feature-badge">
                        <i class="bi bi-arrow-return-left"></i> Flexible Returns
                    </div>
                    <div class="feature-badge">
                        <i class="bi bi-star-fill"></i> {{ number_format($stats['average_rating'], 1) }} Rating
                    </div>
                </div>
            </div>

            <!-- Right Column - Details & Booking -->
            <div class="col-lg-6">
                <div class="item-details-section">
                    <!-- Category Badge -->
                    <div class="mb-3">
                        <span class="badge bg-primary">
                            <i class="{{ $item->category->icon }}"></i> {{ $item->category->name }}
                        </span>
                    </div>

                    <!-- Item Name -->
                    <h1 class="mb-3">{{ $item->name }}</h1>

                    <!-- Rating -->
                    <div class="rating-stars mb-3">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                        <span class="text-muted ms-2">({{ $stats['total_rentals'] }} rentals)</span>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-4">
                        @if($item->available_quantity > 5)
                            <span class="stock-badge in-stock">
                                <i class="bi bi-check-circle-fill"></i>
                                In Stock ({{ $item->available_quantity }} available)
                            </span>
                        @elseif($item->available_quantity > 0)
                            <span class="stock-badge limited-stock">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Limited Stock ({{ $item->available_quantity }} left)
                            </span>
                        @else
                            <span class="stock-badge out-of-stock">
                                <i class="bi bi-x-circle-fill"></i>
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    <!-- Price Section -->
                    <div class="price-section">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1" style="opacity: 0.9;">Rental Rate</p>
                                <div class="price-large">Rs {{ number_format($item->rental_rate, 0) }}</div>
                                <p class="mb-0" style="opacity: 0.9;">per day</p>
                            </div>
                            <div class="text-end">
                                <i class="bi bi-calendar-check" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="mb-3">Description</h5>
                        <p class="text-muted">{{ $item->description ?: 'No description available.' }}</p>
                    </div>

                    <!-- Item Information -->
                    <div class="mb-4">
                        <h5 class="mb-3">Item Information</h5>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <strong>Total Quantity</strong>
                                <p class="text-muted mb-0">{{ $item->quantity }} units</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div>
                                <strong>Available Now</strong>
                                <p class="text-muted mb-0">{{ $item->available_quantity }} units</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <strong>Currently Rented</strong>
                                <p class="text-muted mb-0">{{ $stats['active_rentals'] }} units</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <div>
                                <strong>Total Rentals</strong>
                                <p class="text-muted mb-0">{{ $stats['total_rentals'] }} times rented</p>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Form -->
                    @auth
                        @if(auth()->user()->isCustomer())
                            @if($item->available_quantity > 0)
                                <div class="booking-form">
                                    <h5 class="mb-4">Book This Item</h5>
                                    
                                    <form action="{{ route('customer.rentals.store') }}" method="POST" id="bookingForm">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" class="form-control" name="start_date" id="startDate" min="{{ date('Y-m-d') }}" required onchange="calculateTotal()">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">End Date</label>
                                                <input type="date" class="form-control" name="end_date" id="endDate" required onchange="calculateTotal()">
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" class="form-control" name="quantity" id="quantity" value="1" min="1" max="{{ $item->available_quantity }}" required onchange="calculateTotal()">
                                            </div>
                                        </div>

                                        <div class="calculation-box">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Rate per day:</span>
                                                <strong>Rs {{ number_format($item->rental_rate, 2) }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Number of days:</span>
                                                <strong><span id="daysDisplay">0</span></strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Quantity:</span>
                                                <strong><span id="qtyDisplay">1</span></strong>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <h5>Total Amount:</h5>
                                                <h4 class="text-primary">Rs <span id="totalDisplay">0.00</span></h4>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn-book mt-3">
                                            <i class="bi bi-calendar-check"></i> Book Now
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i> This item is currently out of stock. Please check back later.
                                </div>
                            @endif
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Please <a href="{{ route('login') }}">login</a> to book this item.
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Related Items -->
        @if($relatedItems->count() > 0)
            <div class="mt-5">
                <h2 class="section-title">
                    <i class="bi bi-grid"></i> Related Items
                </h2>

                <div class="row g-4">
                    @foreach($relatedItems as $related)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="related-item-card">
                                <a href="{{ route('items.show', $related->id) }}" class="text-decoration-none text-dark">
                                    <div class="related-image">
                                        @if($related->image)
                                            <img src="{{ Storage::url($related->image) }}" alt="{{ $related->name }}">
                                        @else
                                            <i class="{{ $related->category->icon }}"></i>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title mb-2">{{ $related->name }}</h6>
                                        <p class="text-muted small mb-3">{{ Str::limit($related->description, 50) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="text-primary mb-0">Rs {{ number_format($related->rental_rate, 0) }}</h5>
                                                <small class="text-muted">per day</small>
                                            </div>
                                            <span class="badge bg-success">{{ $related->available_quantity }} available</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Calculation Script -->
    <script>
        const dailyRate = {{ $item->rental_rate }};

        function calculateTotal() {
            const startDate = document.getElementById('startDate')?.value;
            const endDate = document.getElementById('endDate')?.value;
            const quantity = parseInt(document.getElementById('quantity')?.value) || 1;

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;

                if (days > 0) {
                    const total = dailyRate * days * quantity;
                    document.getElementById('daysDisplay').textContent = days;
                    document.getElementById('qtyDisplay').textContent = quantity;
                    document.getElementById('totalDisplay').textContent = total.toFixed(2);
                    
                    // Update end date min
                    document.getElementById('endDate').min = startDate;
                    return;
                }
            }

            document.getElementById('daysDisplay').textContent = '0';
            document.getElementById('qtyDisplay').textContent = quantity;
            document.getElementById('totalDisplay').textContent = '0.00';
        }

        // Set start date min on page load
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const startDateInput = document.getElementById('startDate');
            if (startDateInput) {
                startDateInput.min = today;
            }
        });
    </script>
</body>
</html>
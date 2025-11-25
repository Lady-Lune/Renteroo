<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentarou - Inventory & Rental Management</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/rentarou.css', 'resources/js/rentarou.js'])

    <style>

    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-box-seam"></i> Rentarou
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a href="/login" class="btn btn-login">Login</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="/register" class="btn btn-register">Get Started</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">Manage Your Rentals with Ease</h1>
                    <p class="hero-subtitle">Complete inventory and rental management system for businesses of all sizes. Track items, manage rentals, and generate invoices automatically.</p>
                    <div class="hero-buttons">
                        <a href="/register" class="btn btn-primary-large me-3 mb-3">Start Free Trial</a>
                        <a href="#features" class="btn btn-secondary-large mb-3">Learn More</a>
                    </div>
                    <div class="mt-4" style="color: rgba(255,255,255,0.8);">
                        <i class="bi bi-check-circle-fill me-2"></i> No credit card required
                        <i class="bi bi-check-circle-fill ms-4 me-2"></i> Free 14-day trial
                    </div>
                </div>
                <div class="col-lg-6 hero-image">
                    <div class="text-center">
                        <i class="bi bi-laptop" style="font-size: 20rem; color: white; opacity: 0.9;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Active Users</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Items Managed</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">100K+</div>
                        <div class="stat-label">Rentals Processed</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Uptime</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Powerful Features</h2>
                <p class="section-subtitle">Everything you need to manage your rental business</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h3 class="feature-title">Inventory Management</h3>
                        <p class="feature-description">Track all your items in one place. Add images, set prices, manage availability, and categorize with ease.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h3 class="feature-title">Smart Rental System</h3>
                        <p class="feature-description">Automated booking system with date validation, availability checking, and rental period calculations.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <h3 class="feature-title">Invoice Generation</h3>
                        <p class="feature-description">Automatically generate professional PDF invoices with detailed breakdowns and late payment penalties.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h3 class="feature-title">Analytics Dashboard</h3>
                        <p class="feature-description">Real-time insights into your business performance with revenue tracking and rental statistics.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-bell"></i>
                        </div>
                        <h3 class="feature-title">Notifications</h3>
                        <p class="feature-description">Automated email reminders for overdue rentals, upcoming returns, and payment confirmations.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="feature-title">Secure & Reliable</h3>
                        <p class="feature-description">Role-based access control, data encryption, and regular backups to keep your data safe.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works-section" id="how-it-works">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle">Get started in 3 simple steps</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3 class="step-title">Create Your Account</h3>
                        <p class="step-description">Sign up for free and choose between Admin or Customer role. No credit card required for the trial.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3 class="step-title">Add Your Items</h3>
                        <p class="step-description">Upload your inventory with photos, descriptions, pricing, and availability. Organize with categories.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3 class="step-title">Start Renting</h3>
                        <p class="step-description">Customers can browse, book items, and manage rentals. Invoices are generated automatically!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Ready to Transform Your Rental Business?</h2>
                <p class="cta-subtitle">Join thousands of businesses already using Rentarou</p>
                <a href="/register" class="btn btn-primary-large">Get Started Free</a>
                <p class="mt-4" style="opacity: 0.8;">
                    <i class="bi bi-check-circle me-2"></i> 14-day free trial
                    <i class="bi bi-check-circle ms-3 me-2"></i> No setup fees
                    <i class="bi bi-check-circle ms-3 me-2"></i> Cancel anytime
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h3 class="footer-title">
                        <i class="bi bi-box-seam"></i> Rentarou
                    </h3>
                    <p style="opacity: 0.7;">The complete inventory and rental management solution for modern businesses.</p>
                    <div class="social-links mt-4">
                        <a href="mailto:support@rentarou.com" title="Contact Us"><i class="bi bi-envelope-fill"></i></a>
                        <a href="tel:+15551234567" title="Call Us"><i class="bi bi-phone-fill"></i></a>
                        <a href="#contact" title="Get in Touch"><i class="bi bi-chat-dots-fill"></i></a>
                        <a href="{{ route('items.index') }}" title="Browse Items"><i class="bi bi-box-seam-fill"></i></a>
                    </div>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h4 class="footer-title">Product</h4>
                    <a href="#features" class="footer-link">Features</a>
                    <a href="{{ route('customer.dashboard') }}" class="footer-link">Dashboard</a>
                    <a href="#how-it-works" class="footer-link">How It Works</a>
                    <a href="mailto:support@rentarou.com?subject=FAQ" class="footer-link">FAQ</a>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h4 class="footer-title">Company</h4>
                    <a href="#contact" class="footer-link">About Us</a>
                    <a href="mailto:careers@rentarou.com" class="footer-link">Careers</a>
                    <a href="#features" class="footer-link">News</a>
                    <a href="#contact" class="footer-link">Contact</a>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h4 class="footer-title">Contact Us</h4>
                    <p style="opacity: 0.7;">
                        <i class="bi bi-envelope me-2"></i> support@rentarou.com<br>
                        <i class="bi bi-phone me-2"></i> +1 (555) 123-4567<br>
                        <i class="bi bi-geo-alt me-2"></i> 123 Business St, City, Country
                    </p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 Rentarou. All rights reserved. | <a href="mailto:legal@rentarou.com?subject=Privacy Policy" class="footer-link d-inline">Privacy Policy</a> | <a href="mailto:legal@rentarou.com?subject=Terms of Service" class="footer-link d-inline">Terms of Service</a></p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
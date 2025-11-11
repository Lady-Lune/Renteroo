<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Rentarou</title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Auth CSS -->
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <!-- Logo -->
        <div class="auth-logo">
            <div class="auth-logo-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="auth-logo-text">Rentarou</div>
            <div class="auth-logo-subtitle">Inventory & Rental Management</div>
        </div>

        <!-- Register Card -->
        <div class="auth-card">
            <div class="auth-card-header">
                <h2 class="auth-card-title">Create Account</h2>
                <p class="auth-card-subtitle">Join thousands of users managing their rentals</p>
            </div>

            <!-- Display Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong><i class="bi bi-exclamation-triangle"></i> Oops!</strong>
                    <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Full Name -->
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="bi bi-person"></i> Full Name
                    </label>
                    <input 
                        type="text" 
                        id="name"
                        name="name" 
                        class="form-control @error('name') is-invalid @enderror" 
                        placeholder="Enter your full name"
                        value="{{ old('name') }}"
                        required 
                        autofocus
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> Email Address
                    </label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        placeholder="Enter your email"
                        value="{{ old('email') }}"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock"></i> Password
                    </label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Create a password"
                            required
                        >
                        <i class="bi bi-eye password-toggle-icon" onclick="togglePassword('password')"></i>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small style="color: #6c757d; font-size: 0.85rem; display: block; margin-top: 0.25rem;">
                        Must be at least 8 characters
                    </small>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        <i class="bi bi-lock-fill"></i> Confirm Password
                    </label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            id="password_confirmation"
                            name="password_confirmation" 
                            class="form-control" 
                            placeholder="Confirm your password"
                            required
                        >
                        <i class="bi bi-eye password-toggle-icon" onclick="togglePassword('password_confirmation')"></i>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="role-selection">
                    <label class="form-label">
                        <i class="bi bi-person-badge"></i> I want to register as:
                    </label>
                    <div class="role-options">
                        <div class="role-option">
                            <input type="radio" id="role_customer" name="role" value="customer" checked required>
                            <label for="role_customer">
                                <i class="bi bi-person"></i>
                                <div>Customer</div>
                                <div class="role-description">Rent items</div>
                            </label>
                        </div>
                        <div class="role-option">
                            <input type="radio" id="role_admin" name="role" value="admin" required>
                            <label for="role_admin">
                                <i class="bi bi-shield-check"></i>
                                <div>Shop Owner</div>
                                <div class="role-description">Manage rentals</div>
                            </label>
                        </div>
                    </div>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Create Account
                </button>
            </form>

            <!-- Login Link -->
            <div class="auth-links">
                <p style="color: #6c757d; margin: 0;">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="auth-link">Sign in</a>
                </p>
            </div>

            <!-- Features -->
            <div class="auth-features">
                <h6>What you'll get</h6>
                <div class="features-list">
                    <span class="feature-badge">
                        <i class="bi bi-check-circle"></i> Free 14-day trial
                    </span>
                    <span class="feature-badge">
                        <i class="bi bi-credit-card"></i> No credit card
                    </span>
                    <span class="feature-badge">
                        <i class="bi bi-x-circle"></i> Cancel anytime
                    </span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            <p>&copy; 2025 Rentarou. All rights reserved.</p>
        </div>
    </div>

    <!-- Password Toggle Script -->
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
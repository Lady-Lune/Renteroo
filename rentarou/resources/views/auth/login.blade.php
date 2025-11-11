<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rentarou</title>
    
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

        <!-- Login Card -->
        <div class="auth-card">
            <div class="auth-card-header">
                <h2 class="auth-card-title">Welcome Back!</h2>
                <p class="auth-card-subtitle">Sign in to continue to your account</p>
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

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

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
                        autofocus
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
                            placeholder="Enter your password"
                            required
                        >
                        <i class="bi bi-eye password-toggle-icon" onclick="togglePassword('password')"></i>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <div class="form-check">
                        <input type="checkbox" id="remember" name="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Remember me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}">Forgot password?</a>
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </button>
            </form>

            <!-- Register Link -->
            <div class="auth-links">
                <p style="color: #6c757d; margin: 0;">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="auth-link">Create one now</a>
                </p>
            </div>

            <!-- Features -->
            <div class="auth-features">
                <h6>Why Rentarou?</h6>
                <div class="features-list">
                    <span class="feature-badge">
                        <i class="bi bi-shield-check"></i> Secure
                    </span>
                    <span class="feature-badge">
                        <i class="bi bi-lightning"></i> Fast
                    </span>
                    <span class="feature-badge">
                        <i class="bi bi-clock"></i> 24/7 Support
                    </span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            <p>&copy; 2024 Rentarou. All rights reserved.</p>
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
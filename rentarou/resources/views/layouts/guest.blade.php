<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Rentarou') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Rentarou</h1>
                <p class="text-gray-600 text-sm">Inventory & Rental Management</p>
            </div>

            <!-- Content -->
            <div class="bg-white shadow-md rounded-lg p-8">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="text-center mt-6 text-sm text-gray-600">
                <p>© 2024 Rentarou. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
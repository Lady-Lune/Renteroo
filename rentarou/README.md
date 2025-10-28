# Create new Laravel project
composer create-project --prefer-dist laravel/laravel rentarou

# Navigate to project
cd rentarou

# Install Laravel UI
composer require laravel/ui

# Generate authentication scaffolding with Bootstrap
php artisan ui bootstrap --auth

# Install Node dependencies
npm install cross-env
npm install
npm run dev

php artisan migrate
php artisan serve


php artisan config:clear
php artisan cache:clear
php artisan config:cache

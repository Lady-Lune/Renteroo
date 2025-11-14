<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        
        $sportsCategory = Category::where('name', 'Sports Equipment')->first();
        $toolsCategory = Category::where('name', 'Construction Tools')->first();
        $audioCategory = Category::where('name', 'Audio/Video Equipment')->first();
        $partyCategory = Category::where('name', 'Party & Events')->first();
        $electronicsCategory = Category::where('name', 'Electronics')->first();
        $booksCategory = Category::where('name', 'Books & Media')->first();

        $items = [
            // Sports Equipment
            [
                'category_id' => $sportsCategory->id,
                'name' => 'Mountain Bike',
                'description' => 'Professional mountain bike with 21 gears, perfect for trails and rough terrain.',
                'rental_rate' => 25.00,
                'quantity' => 5,
                'available_quantity' => 5,
                'status' => 'available',
            ],
            [
                'category_id' => $sportsCategory->id,
                'name' => 'Tennis Racket Set',
                'description' => 'Professional tennis racket set with 3 balls and carrying case.',
                'rental_rate' => 15.00,
                'quantity' => 10,
                'available_quantity' => 10,
                'status' => 'available',
            ],
            [
                'category_id' => $sportsCategory->id,
                'name' => 'Camping Tent (4-person)',
                'description' => 'Waterproof 4-person camping tent with easy setup.',
                'rental_rate' => 30.00,
                'quantity' => 3,
                'available_quantity' => 3,
                'status' => 'available',
            ],

            // Construction Tools
            [
                'category_id' => $toolsCategory->id,
                'name' => 'Electric Drill',
                'description' => 'Heavy-duty electric drill with multiple drill bits included.',
                'rental_rate' => 20.00,
                'quantity' => 8,
                'available_quantity' => 8,
                'status' => 'available',
            ],
            [
                'category_id' => $toolsCategory->id,
                'name' => 'Ladder (12ft)',
                'description' => 'Aluminum 12ft extension ladder, lightweight and sturdy.',
                'rental_rate' => 18.00,
                'quantity' => 4,
                'available_quantity' => 4,
                'status' => 'available',
            ],

            // Audio/Video Equipment
            [
                'category_id' => $audioCategory->id,
                'name' => 'DSLR Camera',
                'description' => 'Professional DSLR camera with 18-55mm lens and accessories.',
                'rental_rate' => 50.00,
                'quantity' => 3,
                'available_quantity' => 3,
                'status' => 'available',
            ],
            [
                'category_id' => $audioCategory->id,
                'name' => 'PA Speaker System',
                'description' => 'Professional PA system with microphones, perfect for events.',
                'rental_rate' => 75.00,
                'quantity' => 2,
                'available_quantity' => 2,
                'status' => 'available',
            ],

            // Party & Events
            [
                'category_id' => $partyCategory->id,
                'name' => 'Folding Chairs (Set of 10)',
                'description' => 'White folding chairs, perfect for weddings and events.',
                'rental_rate' => 40.00,
                'quantity' => 5,
                'available_quantity' => 5,
                'status' => 'available',
            ],
            [
                'category_id' => $partyCategory->id,
                'name' => 'Party Tent (10x10)',
                'description' => 'Large 10x10 party tent with sidewalls.',
                'rental_rate' => 100.00,
                'quantity' => 2,
                'available_quantity' => 2,
                'status' => 'available',
            ],

            // Electronics
            [
                'category_id' => $electronicsCategory->id,
                'name' => 'MacBook Pro',
                'description' => 'Latest MacBook Pro 14" with M3 chip, perfect for video editing.',
                'rental_rate' => 60.00,
                'quantity' => 4,
                'available_quantity' => 4,
                'status' => 'available',
            ],
            [
                'category_id' => $electronicsCategory->id,
                'name' => 'Projector + Screen',
                'description' => 'HD projector with 100" portable screen.',
                'rental_rate' => 45.00,
                'quantity' => 3,
                'available_quantity' => 3,
                'status' => 'available',
            ],

            // Books & Media
            [
                'category_id' => $booksCategory->id,
                'name' => 'Laravel Programming Books Set',
                'description' => 'Complete set of Laravel and PHP programming books.',
                'rental_rate' => 10.00,
                'quantity' => 15,
                'available_quantity' => 15,
                'status' => 'available',
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sports Equipment',
                'description' => 'All kinds of sports gear and equipment',
                'icon' => 'bi-bicycle',
            ],
            [
                'name' => 'Construction Tools',
                'description' => 'Professional construction and power tools',
                'icon' => 'bi-tools',
            ],
            [
                'name' => 'Audio/Video Equipment',
                'description' => 'Cameras, microphones, speakers, and more',
                'icon' => 'bi-camera-video',
            ],
            [
                'name' => 'Party & Events',
                'description' => 'Tables, chairs, decorations, and party supplies',
                'icon' => 'bi-balloon',
            ],
            [
                'name' => 'Electronics',
                'description' => 'Laptops, projectors, and electronic devices',
                'icon' => 'bi-laptop',
            ],
            [
                'name' => 'Books & Media',
                'description' => 'Books, magazines, and educational materials',
                'icon' => 'bi-book',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
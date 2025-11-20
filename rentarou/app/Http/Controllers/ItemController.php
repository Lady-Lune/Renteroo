<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{
    /**
     * Display a listing of items (public catalog)
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');

        $items = Item::with('category')
            ->where('status', 'available')
            ->where('available_quantity', '>', 0)
            ->when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($category, function($query, $category) {
                return $query->where('category_id', $category);
            })
            ->latest()
            ->paginate(12);

        $categories = Category::withCount('items')->get();

        return view('items.index', compact('items', 'categories', 'search', 'category'));
    }

    /**
     * Display the specified item
     */
    public function show($id)
    {
        $item = Item::with(['category', 'owner'])
            ->findOrFail($id);

        // Get related items from same category
        $relatedItems = Item::where('category_id', $item->category_id)
            ->where('id', '!=', $item->id)
            ->where('status', 'available')
            ->where('available_quantity', '>', 0)
            ->limit(4)
            ->get();

        // Get rental statistics
        $stats = [
            'total_rentals' => $item->rentals()->count(),
            'active_rentals' => $item->rentals()->where('status', 'active')->count(),
            'average_rating' => 4.5, // Placeholder for future reviews
        ];

        return view('items.show', compact('item', 'relatedItems', 'stats'));
    }
}
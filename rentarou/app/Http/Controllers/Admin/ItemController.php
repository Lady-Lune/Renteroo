<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of items
     */
    public function index(Request $request)
{
    $search = $request->get('search');
    $category = $request->get('category');

    // ⭐ FILTER BY LOGGED-IN ADMIN
    $items = Item::where('user_id', auth()->id())
        ->with('category')
        ->when($search, function($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
        })
        ->when($category, function($query, $category) {
            return $query->where('category_id', $category);
        })
        ->latest()
        ->paginate(12);

    // Also get rentals for the admin's items (for display purposes if the view needs it)
    $rentals = \App\Models\Rental::whereHas('item', function($query) {
        $query->where('user_id', auth()->id());
    })->get();

    $categories = Category::all();

    return view('admin.items.index', compact('items', 'categories', 'search', 'category', 'rentals'));
}
    /**
     * Show the form for creating a new item
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.items.create', compact('categories'));
    }

    /**
     * Store a newly created item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rental_rate' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:available,unavailable,maintenance',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
            $validated['image'] = $imagePath;
        }

         $validated['user_id'] = auth()->id();

        // Set available quantity same as quantity initially
        $validated['available_quantity'] = $validated['quantity'];

        Item::create($validated);

        return redirect()->route('admin.items.index')
            ->with('success', 'Item created successfully!');
    }

    /**
     * Display the specified item
     */
    public function show(Item $item)
    {
        $item->load('category', 'rentals.user');
        
        // Get rental statistics
        $stats = [
            'total_rentals' => $item->rentals()->count(),
            'active_rentals' => $item->rentals()->where('status', 'active')->count(),
            'total_revenue' => $item->rentals()->where('status', 'completed')->sum('total_amount'),
        ];

        return view('admin.items.show', compact('item', 'stats'));
    }

    /**
     * Show the form for editing item
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified item
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rental_rate' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:available,unavailable,maintenance',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            
            $imagePath = $request->file('image')->store('items', 'public');
            $validated['image'] = $imagePath;
        }

        // Update available quantity
        $quantityDiff = $validated['quantity'] - $item->quantity;
        $validated['available_quantity'] = $item->available_quantity + $quantityDiff;

        $item->update($validated);

        return redirect()->route('admin.items.index')
            ->with('success', 'Item updated successfully!');
    }

    /**
     * Remove the specified item
     */
    public function destroy(Item $item)
    {
        // Check if item has active rentals
        if ($item->rentals()->whereIn('status', ['active', 'pending'])->exists()) {
            return back()->with('error', 'Cannot delete item with active rentals!');
        }

        // Delete image
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('admin.items.index')
            ->with('success', 'Item deleted successfully!');
    }
}
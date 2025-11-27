<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'image',
        'rental_rate',
        'quantity',
        'available_quantity',
        'status',
    ];

    protected $casts = [
        'rental_rate' => 'decimal:2',
    ];

    /**
     * Get the admin who owns this item
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category of this item
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all rentals for this item
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Check if item is available
     */
    public function isAvailable()
    {
        return $this->status === 'available' && $this->available_quantity > 0;
    }

    /**
     * Get active rentals count
     */
    public function activeRentalsCount()
    {
        return $this->rentals()->whereIn('status', ['pending', 'active'])->sum('quantity');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'description',
        'repair_cost',
        'status',
    ];

    protected $casts = [
        'repair_cost' => 'decimal:2',
    ];

    /**
     * Get the rental
     */
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
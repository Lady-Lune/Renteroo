<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'guest_name',      
    'guest_email',     
    'guest_phone',     
    'guest_id_number', 
    'is_guest',        
    'item_id',
    'start_date',
    'end_date',
    'actual_return_date',
    'quantity',
    'daily_rate',
    'total_amount',
    'late_fee',
    'damage_fee',
    'status',
    'notes',
];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_return_date' => 'date',
        'daily_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'damage_fee' => 'decimal:2',
    ];

    public function getCustomerName()
{
    return $this->is_guest ? $this->guest_name : $this->user->name;
}

public function getCustomerEmail()
{
    return $this->is_guest ? $this->guest_email : $this->user->email;
}

public function getCustomerPhone()
{
    return $this->is_guest ? $this->guest_phone : ($this->user->phone ?? 'N/A');
}
    /**
     * Get the user who made this rental
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the rented item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the invoice for this rental
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Get damage reports for this rental
     */
    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }

    /**
     * Calculate rental days
     */
    public function getRentalDays()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Check if rental is overdue
     */
    public function isOverdue()
    {
        return $this->status === 'active' && Carbon::today()->greaterThan($this->end_date);
    }

    /**
     * Calculate late days
     */
    public function getLateDays()
    {
        if ($this->actual_return_date) {
            return max(0, $this->end_date->diffInDays($this->actual_return_date));
        }
        
        if ($this->isOverdue()) {
            return $this->end_date->diffInDays(Carbon::today());
        }
        
        return 0;
    }

    /**
     * Get days until return (negative if overdue)
     */
    public function getDaysUntilReturn()
    {
        if ($this->status === 'active') {
            return Carbon::today()->diffInDays($this->end_date, false);
        }
        return null;
    }
}
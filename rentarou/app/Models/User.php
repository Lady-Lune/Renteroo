<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Get all rentals by this user
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Get active rentals only
     */
    public function activeRentals()
    {
        return $this->rentals()->where('status', 'active');
    }

    /**
     * Get completed rentals
     */
    public function completedRentals()
    {
        return $this->rentals()->where('status', 'completed');
    }

    /**
     * Get pending rentals
     */
    public function pendingRentals()
    {
        return $this->rentals()->where('status', 'pending');
    }

    /**
     * Get overdue rentals
     */
    public function overdueRentals()
    {
        return $this->rentals()->where('status', 'overdue');
    }

    /**
     * Get rental history (completed + cancelled)
     */
    public function rentalHistory()
    {
        return $this->rentals()->whereIn('status', ['completed', 'cancelled']);
    }

    /**
     * Get total amount spent on rentals
     */
    public function getTotalSpentAttribute()
    {
        return $this->completedRentals()->sum('total_amount');
    }

    /**
     * Get count of active rentals
     */
    public function getActiveRentalsCountAttribute()
    {
        return $this->activeRentals()->count();
    }

    /**
 * Get all items owned by this admin
 */
public function items()
{
    return $this->hasMany(Item::class);
}
}
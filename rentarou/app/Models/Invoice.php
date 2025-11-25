<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'invoice_number',
        'subtotal',
        'late_fee',
        'damage_fee',
        'total',
        'status',
        'due_date',
        'paid_date',
        'transaction_id',
        'payment_method',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'damage_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    /**
     * Get the rental
     */
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    /**
     * Generate unique invoice number
     */
    public static function generateInvoiceNumber()
    {
        $latest = self::latest('id')->first();
        $number = $latest ? $latest->id + 1 : 1;
        return 'INV-' . date('Y') . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
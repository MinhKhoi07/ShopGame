<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'order_id',
        'invoice_number',
        'invoice_code',
        'issued_at',
        'tax_code',
        'company_name',
        'total_amount',
        'customer_name',
        'customer_email',
        'customer_phone',
        'subtotal',
        'tax',
        'total',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'issued_at' => 'datetime',
    ];

    /**
     * Quan hệ: Invoice thuộc về Order (1-1)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

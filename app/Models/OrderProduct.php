<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'order_products';

    public $incrementing = true;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'subtotal',
        'total_tax',
        'total',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

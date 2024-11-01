<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'base_price',
        'tax_rate',
        'company_id',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
            ->withPivot('quantity', 'subtotal', 'total_tax', 'total')
            ->withTimestamps();
    }

    public function getPriceWithTax()
    {
        return $this->base_price * (1 + $this->tax_rate / 100);
    }
}

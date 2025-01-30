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
        'base_price_1',
        'base_price_2',
        'base_price_3',
        'tax_rate',
        'company_id',
    ];

    protected $casts = [
        'base_price_1' => 'decimal:2',
        'base_price_2' => 'decimal:2',
        'base_price_3' => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
            ->withPivot('price_final', 'quantity', 'subtotal', 'total_tax', 'total')
            ->withTimestamps();
    }

    public function getPriceWithTax($priceType)
    {
        // Verifica que el campo del precio base exista en el modelo
        if (!in_array($priceType, [1, 2, 3]) || !$this->{"base_price_$priceType"}) {
            $toCompareP1 = $this->base_price_1;
            $toCompareP2 = $this->base_price_2;
            $toCompareP3 = $this->base_price_3;

            if ($priceType == $toCompareP1) {
                $priceSelected = 'base_price_1';
            } elseif ($priceType == $toCompareP2) {
                $priceSelected = 'base_price_2';
            } elseif ($priceType == $toCompareP3) {
                $priceSelected = 'base_price_3';
            }

            $price = $this->$priceSelected;
            return $price * (1 + $this->tax_rate / 100);
        }

        // Mapea el número al nombre del precio base correspondiente
        $priceField = 'base_price_' . $priceType;

        // Obtiene el precio base correspondiente
        $price = $this->$priceField;

        // Retorna el precio con impuesto aplicado
        return $price * (1 + $this->tax_rate / 100);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
   protected $fillable = [
        'sale_id', 'product_id', 'quantity',
        'unit_price', 'total_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relations
    public function sale()
    {
        return $this->belongsTo(Sales::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

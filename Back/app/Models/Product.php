<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $fillable = [
        'name', 'barcode', 'description', 'price',
        'quantity', 'low_stock_threshold', 'category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Méthode utile pour les alertes 
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->low_stock_threshold;
    }
}

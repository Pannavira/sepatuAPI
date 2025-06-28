<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSizes extends Model
{
    use HasFactory;
    
    protected $table = 'product_sizes';
    
    // Nonaktifkan timestamps karena tabel tidak punya created_at dan updated_at
    public $timestamps = false;
    
    protected $fillable = [
        'product_id',
        'size_id',
        'stock_per_size'
    ];
    
    protected $casts = [
        'product_id' => 'integer',
        'size_id' => 'integer',
        'stock_per_size' => 'integer'
    ];
    
    // Relationships (optional - add if you have Product and Size models)
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
    
    public function size()
{
    return $this->belongsTo(\App\Models\Sizes::class, 'size_id');
}

protected static function booted()
{
    static::created(function ($productSize) {
        self::updateProductStock($productSize->product_id);
    });

    static::updated(function ($productSize) {
        self::updateProductStock($productSize->product_id);
    });

    static::deleted(function ($productSize) {
        self::updateProductStock($productSize->product_id);
    });
}

public static function updateProductStock($productId)
{
    $totalStock = self::where('product_id', $productId)->sum('stock_per_size');

    \App\Models\Products::where('id', $productId)->update([
        'stock' => $totalStock
    ]);
}
}
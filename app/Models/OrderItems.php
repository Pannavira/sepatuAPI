<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;
    
    protected $table = 'order_items';
    
    // Disable timestamps jika tabel tidak punya created_at dan updated_at
    public $timestamps = false;
    
    protected $fillable = [
        'order_id',
        'product_id', 
        'size_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2'
    ];

    /**
     * Get the order that owns the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the order item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the size that owns the order item.
     */
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
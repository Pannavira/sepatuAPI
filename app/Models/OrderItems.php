<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;
    
    protected $table = 'order_items';
    
    // Disable timestamps since table doesn't have created_at and updated_at columns
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
        return $this->belongsTo(Orders::class, 'order_id');
    }

    /**
     * Get the product that owns the order item.
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    /**
     * Get the size that owns the order item.
     */
    public function size()
    {
        return $this->belongsTo(Sizes::class, 'size_id');
    }

    /**
     * Get the total price for this item (quantity * price)
     */
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }
}
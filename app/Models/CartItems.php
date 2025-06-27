<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{
    use HasFactory;
    protected $table = 'cart_items';

    protected $fillable = [
        'cart_id',
        'product_id',
        'size_id',
        'quantity'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function size()
    {
        return $this->belongsTo(Sizes::class, 'size_id');
    }

}

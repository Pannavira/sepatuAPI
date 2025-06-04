<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    
    protected $table = 'products';
    
    protected $fillable = [
        'name',
        'description', 
        'price',
        'stock',
        'category_id',
        'brand_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'category_id' => 'integer',
        'brand_id' => 'integer'
    ];
}
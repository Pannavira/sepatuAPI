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

public function images()
{
    return $this->hasMany(ProductImages::class, 'product_id');
}

public function sizes()
{
    return $this->hasMany(ProductSizes::class, 'product_id')->with('size');
}

public function brand()
{
    return $this->belongsTo(Brands::class, 'brand_id');
}

public function category()
{
    return $this->belongsTo(Categories::class, 'category_id');
}

public function reviews()
{
    return $this->hasMany(Reviews::class, 'product_id')->with('user');
}




}
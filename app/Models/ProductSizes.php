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
        return $this->belongsTo(Product::class);
    }
    
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
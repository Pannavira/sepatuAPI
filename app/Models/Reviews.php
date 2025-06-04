<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;
    
    protected $table = 'reviews';
    
    // Nonaktifkan timestamps karena tabel hanya punya created_at
    public $timestamps = false;
    
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'created_at'
    ];
    
    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime'
    ];
    
    // Relationships (optional - add if you have User and Product models)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $fillable = [
        'user_id',
    ];
    public $timestamps = false;

    public function items()
    {
        return $this->hasMany(CartItems::class, 'cart_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}

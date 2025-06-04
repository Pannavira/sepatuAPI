<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    
    protected $table = 'orders';
    
    // Menonaktifkan timestamps karena tidak ada kolom created_at dan updated_at
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'order_date',
        'total_price',
        'status',
        'shipping_address',
        'payment_method',
        'payment_status'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    // Relationships (optional - jika ada tabel users)
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
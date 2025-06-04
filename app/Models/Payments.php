<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $table = 'payments';
    
    // Disable automatic timestamp management
    public $timestamps = false;

    // Kolom yang bisa diisi
    protected $fillable = [
        'order_id',
        'payment_date',
        'payment_method',
        'amount',
        'payment_status'
    ];

    // Relasi ke tabel orders (jika ada)
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
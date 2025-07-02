<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    // Tidak menggunakan timestamps created_at dan updated_at
    public $timestamps = false;
    protected $table = 'orders';
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

    // Daftar status pesanan
    public static $statuses = [
        'pending',
        'paid', 
        'shipped',
        'delivered',
        'cancelled'
    ];

    // Daftar status pembayaran
    public static $paymentStatuses = [
        'unpaid',
        'paid'
    ];

    // Validasi untuk status pesanan
    public static function getStatusRules()
    {
        return 'required|in:' . implode(',', self::$statuses);
    }

    // Validasi untuk status pembayaran
    public static function getPaymentStatusRules()
    {
        return 'required|in:' . implode(',', self::$paymentStatuses);
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke item pesanan
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    /**
     * Relasi ke pembayaran
     */
    public function payment()
    {
        return $this->hasOne(Payments::class, 'order_id');
    }

    /**
     * ID format 000001
     */
    public function getFormattedIdAttribute()
    {
        return str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Kelas badge status pesanan
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => 'badge-warning',
            'paid' => 'badge-info',
            'shipped' => 'badge-primary',
            'delivered' => 'badge-success',
            'cancelled' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Kelas badge status pembayaran
     */
    public function getPaymentStatusBadgeAttribute()
    {
        return match ($this->payment_status) {
            'paid' => 'badge-success',
            'unpaid' => 'badge-warning',
            default => 'badge-secondary',
        };
    }

    /**
     * Cek apakah sudah dibayar
     */
    public function getIsPaidAttribute()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Label status (uppercase first)
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Label status pembayaran
     */
    public function getPaymentStatusLabelAttribute()
    {
        return $this->is_paid ? 'Paid' : 'Unpaid';
    }
}

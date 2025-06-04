<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'product_images';

    /**
     * Indicates if the model should be timestamped.
     * Set to false if your table doesn't have created_at and updated_at columns
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'image_url'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'product_id' => 'integer'
    ];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the full URL for the image.
     */
    public function getImageUrlAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http')) {
            return asset('storage/' . $value);
        }
        return $value;
    }

    /**
     * Scope to filter by product ID.
     */
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope to search images.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('id', 'like', '%' . $search . '%')
              ->orWhere('product_id', 'like', '%' . $search . '%')
              ->orWhere('image_url', 'like', '%' . $search . '%');
        });
    }
}
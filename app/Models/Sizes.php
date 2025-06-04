<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sizes extends Model
{
    use HasFactory;

    // Nonaktifkan timestamps
    public $timestamps = false;

    protected $fillable = [
        'size_label'
    ];

    // Atau jika ingin menggunakan mass assignment di controller
    protected $guarded = [];
}
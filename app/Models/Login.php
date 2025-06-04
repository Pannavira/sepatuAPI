<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Login extends Model
{
    use HasFactory;
    
    protected $table = 'login';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'username',
        'password',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the formatted last login date
     */
    public function getFormattedLastLoginAttribute()
    {
        return $this->last_login ? $this->last_login->format('d-m-Y H:i:s') : null;
    }

    /**
     * Get the formatted created at date
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d-m-Y H:i:s') : null;
    }

    /**
     * Get the formatted updated at date
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d-m-Y H:i:s') : null;
    }

    /**
     * Scope a query to only include active logins (logged in within last 30 days).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('last_login', '>=', Carbon::now()->subDays(30));
    }

    /**
     * Scope a query to search by username.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $username
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUsername($query, $username)
    {
        return $query->where('username', 'like', '%' . $username . '%');
    }
}
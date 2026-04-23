<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Tenant extends Authenticatable
{
    use HasApiTokens;

    // fillable
    protected $fillable = [
        'name',
        'email',
        'phone',
        'img',
        'password',
    ];

    // Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

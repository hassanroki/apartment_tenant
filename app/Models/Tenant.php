<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Tenant extends Model
{
    use HasApiTokens;

    // fillable
    protected $fillable = [
        'name',
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

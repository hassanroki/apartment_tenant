<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    // fillable
    protected $fillable = [
        'name',
        'phone',
        'img',
    ];

    // Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

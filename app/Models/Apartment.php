<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Symfony\Component\Clock\now;

class Apartment extends Model
{
    use HasFactory;

    // Fillable
    protected $fillable = [
        'name',
        'rent',
        'img',
        'status',
        'descriptions',
    ];

    // Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Current Booking
    public function currentBooking()
    {
        return $this->hasOne(Booking::class)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }

    public function activeOrUpcomingBooking()
    {
        return $this->hasOne(Booking::class)
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->limit(1);
    }

    // Protected Cast
    protected $casts = [
        'rent' => 'decimal:2',
    ];
}

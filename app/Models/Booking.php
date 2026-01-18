<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // fillable
    protected $fillable = [
        'apartment_id',
        'tenant_id',
        'start_date',
        'end_date',
    ];

    // Apartment
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    // Tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}

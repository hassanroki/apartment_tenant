<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Apartment\ApartmentResource;
use App\Models\Apartment;
use App\Models\Tenant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // summary
    public function summary()
    {
        return response()->json([
            'totalApartment' => Apartment::count(),
            'totalTenant' => Tenant::count(),
            'bookedApartment' => ApartmentResource::collection(
                Apartment::whereHas('currentBooking')->get()
            ),
            'vacantApartment' => ApartmentResource::collection(
                Apartment::whereDoesntHave('currentBooking')->get()
            )
        ]);
    }
}

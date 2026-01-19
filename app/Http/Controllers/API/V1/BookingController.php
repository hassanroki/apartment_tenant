<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Resources\Booking\BookingCollection;
use App\Http\Resources\Booking\BookingResource;
use App\Models\Booking;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // Booking List
    public function index()
    {
        $bookings = Booking::with('apartment', 'tenant')->get();

        return response()->json([
            'message' => 'Booking Retrieve Successfully!',
            'data' => new BookingCollection($bookings),
        ]);
    }

    // Store Booking
    public function store(StoreBookingRequest $request)
    {
        // Option 1
        // return new BookingResource(
        //     Booking::create($request->validated())
        // );

        // Option 2
        $booking = Booking::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data'    => new BookingResource($booking),
        ], 201);
    }
}

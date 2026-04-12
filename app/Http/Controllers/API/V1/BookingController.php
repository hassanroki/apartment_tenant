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
use Illuminate\Support\Facades\Log;

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

        //     // Option 2
        //     $booking = Booking::create($request->validated());

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Booking created successfully',
        //         'data'    => new BookingResource($booking),
        //     ], 201);
        try {
            // Start transaction
            DB::beginTransaction();

            // Get authenticated tenant
            $tenantId = auth('sanctum')->id();
            if (!$tenantId) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Create booking
            $booking = Booking::create([
                'tenant_id'    => $tenantId,
                'apartment_id' => $request->apartment_id,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
                'status'       => true,
            ]);

            // Update apartment status
            $booking->apartment()->update([
                'status' => 1
            ]);

            // Commit transaction
            DB::commit();

            return new BookingResource($booking);
        } catch (Exception $e) {
            // Rollback transaction
            DB::rollBack();

            // Optional: log error
            Log::error('Booking failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Booking failed. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

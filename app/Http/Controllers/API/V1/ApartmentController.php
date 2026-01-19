<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apartment\StoreApartmentRequest;
use App\Http\Resources\Apartment\ApartmentCollection;
use App\Models\Apartment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApartmentController extends Controller
{
    // Apartment List
    public function index()
    {
        $apartment = Apartment::with('currentBooking.tenant')->get();
        return response()->json([
            'message' => 'Data Retrieve Success!',
            'data' => new ApartmentCollection($apartment),
        ]);
    }

    // Apartment Store
    public function store(StoreApartmentRequest $request)
    {
        $validatedData = $request->validated();
        $imagePath = null;

        DB::beginTransaction();

        try {
            if ($request->hasFile('img')) {
                $imagePath = $request->file('img')->store('apartments', 'public');
            }

            $apartment = Apartment::create([
                'name' => $validatedData['name'],
                'rent' => $validatedData['rent'],
                'img'  => $imagePath,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Apartment created successfully',
                'data' => $apartment,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return response()->json([
                'message' => 'Apartment creation failed',
                'errors' => $e->getMessage(),
            ], 422);
        }
    }
}

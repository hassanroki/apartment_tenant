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
                'status' => $validatedData['status'],
                'descriptions' => $validatedData['descriptions'],
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

    // Apartment Edit
    public function edit($id)
    {
        $apartment = Apartment::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $apartment,
        ]);
    }

    // Apartment Update
    public function update(StoreApartmentRequest $request, $id)
    {
        $validatedData = $request->validated();

        try {
            $apartment = Apartment::findOrFail($id);

            // Update basic fields
            $apartment->name = $validatedData['name'];
            $apartment->rent = $validatedData['rent'];
            $apartment->status = $validatedData['status'];
            $apartment->descriptions = $validatedData['descriptions'];

            // Image handling
            if ($request->hasFile('img')) {
                // Old image delete
                if ($apartment->img) {
                    Storage::disk('public')->delete($apartment->img);
                }

                // Save new image
                $apartment->img = $request->file('img')->store('apartments', 'public');
            }

            $apartment->save();

            return response()->json([
                'message' => 'Apartment updated successfully',
                'data' => $apartment
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Apartment update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Delete Apartment
    public function destroy($id)
    {
        $apartment = Apartment::findOrFail($id);

        // Delete image if exists
        if ($apartment->img && Storage::disk('public')->exists($apartment->img)) {
            Storage::disk('public')->delete($apartment->img);
        }

        $apartment->delete();

        return response()->json([
            'message' => 'Apartment deleted successfully',
        ], 200);
    }
}

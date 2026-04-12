<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreTenantRequest;
use App\Http\Requests\Tenant\TenantLoginRequest;
use App\Http\Resources\Booking\BookingCollection;
use App\Http\Resources\Tenant\TenantCollection;
use App\Http\Resources\Tenant\TenantResource;
use App\Models\Booking;
use App\Models\Tenant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TenantController extends Controller
{
    // Booking

    public function bookings()
    {
        try {
            $tenantId = auth('sanctum')->id();
            $booking = Booking::with(['apartment', 'tenant'])->where('tenant_id', $tenantId)->get();

            return response()->json([
                'message' => 'Booking retrieved successfully',
                'data' => new BookingCollection($booking),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Tenant List
    public function index()
    {
        $tenants = Tenant::all();
        return response()->json([
            'message' => 'Tenants Retrieve Successfully!',
            'data' => new TenantCollection($tenants),
        ]);
    }

    // Tenant Store
    public function register(StoreTenantRequest $request)
    {
        $validatedData = $request->validated();
        $imagePath = null;

        DB::beginTransaction();

        try {
            // Image Upload
            if ($request->hasFile('img')) {
                $imagePath = $request->file('img')->store('tenants', 'public');
            }

            // Create Tenant
            $tenant = Tenant::create([
                'name'     => $validatedData['name'],
                'phone'    => $validatedData['phone'],
                'img'      => $imagePath,
                'password' => Hash::make($validatedData['password']),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Tenant created successfully.',
                'data'    => new TenantResource($tenant),
            ], 201);
        } catch (Throwable $e) {

            DB::rollBack();

            // Delete uploaded image if exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return response()->json([
                'message' => 'Tenant creation failed.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // Login
    public function login(TenantLoginRequest $request)
    {
        $tenant = Tenant::where('phone', $request->phone)->first();

        if (!$tenant || !Hash::check($request->password, $tenant->password)) {
            return response()->json([
                'message' => 'Invalidated credentials!',
            ], 401);
        }

        $token = $tenant->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Successfully!',
            'data' => new TenantResource($tenant),
            'token' => $token,
        ], 200);
    }

    // logout
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Logout successfully!',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Logout Failed!',
                'errors' => $e->getMessage(),
            ]);
        }
    }
}

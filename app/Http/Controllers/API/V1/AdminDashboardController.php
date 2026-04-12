<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Models\Apartment;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
    // Admin Login
    public function adminLogin(AdminLoginRequest $request)
    {
        $admin = User::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'Invalidated credentials!',
            ], 401);
        }

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Successfully!',
            'access_token' => $token,
        ], 200);
    }

    // Get All Apartments
    public function data()
    {
        try {
            $apartments = Apartment::get();

            return response()->json([
                'message' => 'Apartments retrieved successfully',
                'data' => $apartments,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Logout successfully!',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Logout Failed!',
                'errors' => $e->getMessage(),
            ]);
        }
    }
}

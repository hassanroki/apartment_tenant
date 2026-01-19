<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreTenantRequest;
use App\Http\Resources\Tenant\TenantCollection;
use App\Models\Tenant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TenantController extends Controller
{
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
    public function store(StoreTenantRequest $request)
    {
        $validatorData = $request->validated();

        $imagePath = null;
        DB::beginTransaction();

        try {
            if ($request->hasFile('img')) {
                $imagePath = $request->file('img')->store('tenant', 'public');
            }

            $tenant = Tenant::create([
                'name' => $validatorData['name'],
                'phone' => $validatorData['phone'],
                'img' => $imagePath,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Tenant Created Success!',
                'data' => $tenant,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            return response()->json([
                'message' => 'Tenant Created Failed!',
                'errors' => $e->getMessage(),
            ], 422);
        }
    }
}

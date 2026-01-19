<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tenantName' => $this->name,
            'phoneNumber' => $this->phone,
            'url'   => $this->img? asset('/storage/' . $this->img) : null,
        ];
    }
}

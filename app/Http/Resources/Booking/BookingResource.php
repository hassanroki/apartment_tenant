<?php

namespace App\Http\Resources\Booking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'bookingId' => $this->id,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'apartment' => [
                'apartmentId' => $this->apartment->id,
                'apartmentName' => $this->apartment->name,
                'apartmentRent' => $this->apartment->rent,
            ],
            'tenant' => [
                'tenantId' => $this->tenant->id,
                'tenantName' => $this->tenant->name,
            ]
        ];
    }
}

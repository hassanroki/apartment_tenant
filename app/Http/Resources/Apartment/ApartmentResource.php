<?php

namespace App\Http\Resources\Apartment;

use App\Http\Resources\Booking\BookingResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'apartmentName' => $this->name,
            'apartmentPrice' => $this->rent,
            'url'   => $this->img ? asset('/storage/' . $this->img) : null,
            'status' => $this->currentBooking ? 'Booked' : 'Vacant',
            'currentBooking' => $this->when(
                $this->relationLoaded('currentBooking') && $this->currentBooking,
                fn() => new BookingResource($this->currentBooking)
            ),
        ];
    }
}

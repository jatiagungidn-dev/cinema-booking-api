<?php

namespace App\Http\Resources;

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
            'id' => $this->id,
            'showtime_id' => $this->showtime_id,
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'seats' => $this->whenLoaded('bookingSeats', fn () => BookingSeatResource::collection($this->bookingSeats)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

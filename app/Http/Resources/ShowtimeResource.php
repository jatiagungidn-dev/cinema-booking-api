<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowtimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cinema = $this->studio?->cinema;

        return [
            'id' => $this->id,
            'movie' => [
                'id' => $this->movie->id,
                'title' => $this->movie->title,
                'duration_minutes' => $this->movie->duration_minutes,
            ],
            'cinema' => [
                'id' => $cinema?->id,
                'name' => $cinema?->name,
                'city' => $cinema?->city,
            ],
            'studio' => [
                'id' => $this->studio->id,
                'name' => $this->studio->name,
            ],
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

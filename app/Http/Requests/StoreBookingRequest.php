<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'showtime_id' => [
                'required',
                'integer',
                'exists:showtimes,id',
            ],
            'seat_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'seat_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:seats,id',
            ],
        ];
    }
}

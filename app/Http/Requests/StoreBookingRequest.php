<?php

namespace App\Http\Requests;

use App\Models\Showtime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $showtime = Showtime::find($this->integer('showtime_id'));

                if (! $showtime) {
                    return;
                }

                $invalidSeatExists = $showtime->studio->seats()->whereIn('id', $this->input('seat_ids'))->count() !== count($this->input('seat_ids'));

                if ($invalidSeatExists) {
                    $validator->errors()->add('seat_ids', 'One or more selected seats are not available in this studios');
                }
            },
        ];
    }
}

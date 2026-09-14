<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): AnonymousResourceCollection
    {
        $bookings = Booking::query()->where('user_id', $request->user()->id)->with('bookingSeats')->latest()->paginate(10);

        return BookingResource::collection($bookings);
    }

    public function show(Request $request, Booking $booking): BookingResource
    {
        $this->authorize('view', $booking);

        $booking->load('bookingSeats');

        return new BookingResource($booking);
    }

    public function store(StoreBookingRequest $request): BookingResource
    {
        $data = $request->validated();

        $showtime = Showtime::findOrFail($data['showtime_id']);

        $seats = Seat::query()->whereIn('id', $data['seat_ids'])->get();

        $totalAmount = $seats->count() * $showtime->price;

        $booking = DB::transaction(function () use (
            $request, $showtime, $seats, $totalAmount
        ) {
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'showtime_id' => $showtime->id,
                'status' => 'PENDING',
                'total_amount' => $totalAmount,
            ]);

            foreach ($seats as $seat) {
                $booking->bookingSeats()->create([
                    'showtime_id' => $showtime->id,
                    'seat_id' => $seat->id,
                    'price' => $showtime->price,
                ]);
            }

            return $booking;
        });

        $booking->load('bookingSeats');

        return new BookingResource($booking);
    }
}

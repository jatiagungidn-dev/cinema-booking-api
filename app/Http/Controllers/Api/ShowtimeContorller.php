<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShowtimeResource;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShowtimeContorller extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $showtimes = Showtime::query()->with(['movie', 'studio.cinema'])->when($request->filled('movie_id'), fn ($query) => $query->where('movie_id', $request->integer('movie_id')))->when($request->filled('cinema_id'), fn ($query) => $query->whereHas('studio', fn ($query) => $query->where('cinema_id', $request->integer('cinema_id'))))->when($request->filled('date'), fn ($query) => $query->whereDate('starts_at', $request->date('date')))->orderBy('starts_at')->paginate(10);

        return ShowtimeResource::collection($showtimes);
    }

    public function show(Showtime $showtime): ShowtimeResource
    {
        $showtime->load(['movie', 'studio.cinema']);

        return new ShowtimeResource($showtime);
    }
}

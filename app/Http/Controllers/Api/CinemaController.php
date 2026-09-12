<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CinemaResource;
use App\Http\Resources\StudioResource;
use App\Models\Cinema;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CinemaController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $cinemas = Cinema::query()->orderby('name')->paginate(10);

        return CinemaResource::collection($cinemas);
    }

    public function show(Cinema $cinema): CinemaResource
    {
        return new CinemaResource($cinema);
    }

    public function studios(Cinema $cinema): AnonymousResourceCollection
    {
        $studios = $cinema->studios()->orderBy('name')->get();

        return StudioResource::collection($studios);
    }
}

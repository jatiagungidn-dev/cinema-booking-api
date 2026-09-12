<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SeatResource;
use App\Models\Studio;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StudioController extends Controller
{
    public function seats(Studio $studio): AnonymousResourceCollection
    {
        $seats = $studio->seats()->orderBy('row')->orderBy('number')->get();

        return SeatResource::collection($seats);
    }
}

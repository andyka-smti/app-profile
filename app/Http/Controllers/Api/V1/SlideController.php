<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SlideResource;
use App\Models\Slide;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class SlideController extends Controller
{
    /**
     * Display a listing of currently active slides.
     */
    public function index(): AnonymousResourceCollection
    {
        $slides = Cache::remember('app_active_slides', 1800, fn () => Slide::active()->get());

        return SlideResource::collection($slides);
    }
}

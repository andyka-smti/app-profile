<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MenuResource;
use App\Models\Menu;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    /**
     * Display a menu by location (header, footer, sidebar).
     */
    public function show(string $location): MenuResource
    {
        $menu = Cache::remember("app_menu_{$location}", 3600, function () use ($location) {
            return Menu::where('location', $location)
                ->where('is_active', true)
                ->with(['rootItems' => function ($q) {
                    $q->where('is_active', true)->with(['children' => fn ($cq) => $cq->where('is_active', true)]);
                }])
                ->firstOrFail();
        });

        return new MenuResource($menu);
    }
}

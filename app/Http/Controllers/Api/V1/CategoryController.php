<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * Display a listing of active categories.
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = Cache::remember('app_active_categories', 3600, function () {
            return Category::active()
                ->withCount(['posts' => fn ($q) => $q->published()])
                ->get();
        });

        return CategoryResource::collection($categories);
    }

    /**
     * Display a specific category by slug.
     */
    public function show(string $slug): CategoryResource
    {
        $category = Category::active()
            ->withCount(['posts' => fn ($q) => $q->published()])
            ->where('slug', $slug)
            ->firstOrFail();

        return new CategoryResource($category);
    }
}

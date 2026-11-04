<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SettingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SettingController extends Controller
{
    /**
     * Display all public site settings or filter by group.
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $settings = Setting::getAllCached();

        if ($request->filled('group')) {
            $settings = $settings->where('group', $request->query('group'))->values();
        }

        // If 'format=map' is requested, return key-value object format suitable for Flutter
        if ($request->query('format') === 'map') {
            $map = [];
            foreach ($settings as $setting) {
                $val = $setting->value;
                if ($setting->type === 'image' && $val) {
                    $val = url('storage/'.$val);
                }
                $map[$setting->key] = $val;
            }

            return response()->json(['data' => $map]);
        }

        return SettingResource::collection($settings);
    }
}

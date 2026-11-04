<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

#[Fillable(['group', 'key', 'value', 'type'])]
class Setting extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('app_settings_all'));
        static::deleted(fn () => Cache::forget('app_settings_all'));
    }

    /**
     * Get all settings cached.
     */
    public static function getAllCached(): Collection
    {
        return Cache::rememberForever('app_settings_all', fn () => static::all());
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::getAllCached()->firstWhere('key', $key);

        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'text'): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
            ]
        );
    }

    /**
     * Get settings by group as key-value pairs.
     */
    public static function byGroup(string $group): Collection
    {
        return static::where('group', $group)->pluck('value', 'key');
    }
}

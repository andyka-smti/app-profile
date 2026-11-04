<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

#[Fillable(['name', 'location', 'is_active'])]
class Menu extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(function ($menu) {
            Cache::forget("app_menu_{$menu->location}");
        });
        static::deleted(function ($menu) {
            Cache::forget("app_menu_{$menu->location}");
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    public function rootItems(): HasMany
    {
        return $this->items()->whereNull('parent_id')->with('children');
    }
}

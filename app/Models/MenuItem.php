<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'menu_id',
    'parent_id',
    'label',
    'url',
    'type',
    'linkable_id',
    'linkable_type',
    'sort_order',
    'is_active',
])]
class MenuItem extends Model
{
    use HasFactory;
    use HasTranslations;

    /**
     * @var list<string>
     */
    public array $translatable = [
        'label',
    ];

    protected static function booted(): void
    {
        static::saved(function ($item) {
            if ($item->menu) {
                Cache::forget("app_menu_{$item->menu->location}");
            }
        });
        static::deleted(function ($item) {
            if ($item->menu) {
                Cache::forget("app_menu_{$item->menu->location}");
            }
        });
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }
}

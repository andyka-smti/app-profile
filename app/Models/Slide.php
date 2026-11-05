<?php

namespace App\Models;

use Database\Factories\SlideFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'title',
    'description',
    'image',
    'link_url',
    'sort_order',
    'is_active',
    'start_date',
    'end_date',
])]
class Slide extends Model
{
    /** @use HasFactory<SlideFactory> */
    use HasFactory;

    use HasTranslations;

    /**
     * @var list<string>
     */
    public array $translatable = [
        'title',
        'description',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('app_active_slides'));
        static::deleted(fn () => Cache::forget('app_active_slides'));
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->orderBy('sort_order');
    }
}

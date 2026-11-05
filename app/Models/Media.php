<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'user_id',
    'file_name',
    'file_path',
    'mime_type',
    'file_size',
    'alt_text',
    'disk',
])]
class Media extends Model
{
    use HasFactory;
    use HasTranslations;

    /**
     * @var list<string>
     */
    public array $translatable = [
        'alt_text',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::disk($this->disk)->url($this->file_path),
        );
    }

    protected function humanFileSize(): Attribute
    {
        return Attribute::make(
            get: function () {
                $bytes = $this->file_size;
                if ($bytes >= 1073741824) {
                    return number_format($bytes / 1073741824, 2).' GB';
                }
                if ($bytes >= 1048576) {
                    return number_format($bytes / 1048576, 2).' MB';
                }
                if ($bytes >= 1024) {
                    return number_format($bytes / 1024, 2).' KB';
                }

                return $bytes.' B';
            }
        );
    }
}

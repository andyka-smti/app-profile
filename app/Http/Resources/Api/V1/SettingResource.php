<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $value = $this->value;
        if ($this->type === 'image' && $value) {
            $value = Storage::disk('public')->url($value);
        }

        return [
            'id' => $this->id,
            'group' => $this->group,
            'key' => $this->key,
            'value' => $value,
            'type' => $this->type,
        ];
    }
}

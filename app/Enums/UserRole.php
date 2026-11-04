<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasColor, HasLabel
{
    case Admin = 'admin';
    case Editor = 'editor';
    case Viewer = 'viewer';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Admin => __('Administrator'),
            self::Editor => __('Editor'),
            self::Viewer => __('Viewer'),
        };
    }

    public function label(): string
    {
        return $this->getLabel() ?? $this->value;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Admin => 'danger',
            self::Editor => 'info',
            self::Viewer => 'gray',
        };
    }

    public function color(): string
    {
        return (string) $this->getColor();
    }
}

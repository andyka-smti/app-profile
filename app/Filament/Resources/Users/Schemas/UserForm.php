<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('User Information'))
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(__('Full Name'))
                                ->required()
                                ->maxLength(255),

                            TextInput::make('email')
                                ->label(__('Email Address'))
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            Select::make('role')
                                ->label(__('Role / Access Rights'))
                                ->options(UserRole::class)
                                ->default(UserRole::Editor)
                                ->required()
                                ->native(false),

                            TextInput::make('password')
                                ->label(__('Password'))
                                ->password()
                                ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->maxLength(255)
                                ->helperText(__('Leave blank if you do not want to change the password when editing')),

                            FileUpload::make('avatar')
                                ->label(__('Profile Photo (Avatar)'))
                                ->avatar()
                                ->directory('avatars')
                                ->visibility('public')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}

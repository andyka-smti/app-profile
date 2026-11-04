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
            ->components([
                Section::make('Informasi Pengguna')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('email')
                                ->label('Alamat Email')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            Select::make('role')
                                ->label('Peran / Hak Akses')
                                ->options(UserRole::class)
                                ->default(UserRole::Editor)
                                ->required()
                                ->native(false),

                            TextInput::make('password')
                                ->label('Password')
                                ->password()
                                ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->maxLength(255)
                                ->helperText('Kosongkan jika tidak ingin mengubah password saat edit'),

                            FileUpload::make('avatar')
                                ->label('Foto Profil (Avatar)')
                                ->avatar()
                                ->directory('avatars')
                                ->visibility('public')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}

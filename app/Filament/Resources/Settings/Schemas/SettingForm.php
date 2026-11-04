<?php

namespace App\Filament\Resources\Settings\Schemas;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengaturan')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('group')
                                ->label('Grup')
                                ->options([
                                    'general' => 'General (Umum)',
                                    'contact' => 'Kontak',
                                    'social' => 'Media Sosial',
                                    'maps' => 'Peta & Lokasi',
                                    'footer' => 'Footer',
                                ])
                                ->required()
                                ->native(false),

                            Select::make('type')
                                ->label('Tipe Nilai')
                                ->options([
                                    'text' => 'Teks Pendek',
                                    'textarea' => 'Teks Panjang',
                                    'image' => 'Gambar / Logo',
                                    'boolean' => 'Boolean (1 / 0)',
                                    'json' => 'JSON',
                                ])
                                ->default('text')
                                ->required()
                                ->live()
                                ->native(false),

                            TextInput::make('key')
                                ->label('Kunci (Key)')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->disabled(fn (?Setting $record) => $record !== null)
                                ->helperText('Identifier unik, contoh: site_name, contact_email')
                                ->columnSpanFull(),

                            TextInput::make('value')
                                ->label('Nilai')
                                ->visible(fn (Get $get) => in_array($get('type') ?? 'text', ['text', 'boolean', 'json']))
                                ->columnSpanFull(),

                            Textarea::make('value')
                                ->label('Nilai')
                                ->rows(4)
                                ->visible(fn (Get $get) => $get('type') === 'textarea')
                                ->columnSpanFull(),

                            FileUpload::make('value')
                                ->label('Gambar / File')
                                ->image()
                                ->directory('settings')
                                ->visibility('public')
                                ->visible(fn (Get $get) => $get('type') === 'image')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}

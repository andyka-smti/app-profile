<?php

namespace App\Filament\Resources\Menus\Schemas;

use App\Models\Category;
use App\Models\Page;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Menu')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Menu')
                                ->required()
                                ->maxLength(255),

                            Select::make('location')
                                ->label('Lokasi Penempatan')
                                ->options([
                                    'header' => 'Header (Navigasi Atas)',
                                    'footer' => 'Footer (Navigasi Bawah)',
                                    'sidebar' => 'Sidebar (Bilah Samping)',
                                ])
                                ->required()
                                ->native(false),

                            Toggle::make('is_active')
                                ->label('Menu Aktif')
                                ->default(true)
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Daftar Item Menu')
                    ->description('Kelola tautan dan item navigasi pada menu ini')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->orderColumn('sort_order')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('label')
                                        ->label('Label Menu')
                                        ->required(),

                                    Select::make('type')
                                        ->label('Tipe Tautan')
                                        ->options([
                                            'custom' => 'Tautan Kustom / URL',
                                            'page' => 'Halaman Statis',
                                            'category' => 'Kategori Post',
                                        ])
                                        ->default('custom')
                                        ->required()
                                        ->live()
                                        ->native(false),

                                    TextInput::make('url')
                                        ->label('URL Tujuan')
                                        ->placeholder('/tentang-kami atau https://...')
                                        ->required(fn (Get $get) => $get('type') === 'custom')
                                        ->visible(fn (Get $get) => $get('type') === 'custom'),

                                    Select::make('linkable_id')
                                        ->label('Pilih Halaman')
                                        ->options(fn () => Page::pluck('title', 'id'))
                                        ->searchable()
                                        ->visible(fn (Get $get) => $get('type') === 'page'),

                                    Select::make('linkable_id')
                                        ->label('Pilih Kategori')
                                        ->options(fn () => Category::pluck('name', 'id'))
                                        ->searchable()
                                        ->visible(fn (Get $get) => $get('type') === 'category'),

                                    TextInput::make('sort_order')
                                        ->label('Urutan')
                                        ->numeric()
                                        ->default(0),

                                    Toggle::make('is_active')
                                        ->label('Aktif')
                                        ->default(true),
                                ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Slides\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Slide Banner')
                    ->schema([
                        Grid::make(2)->schema([
                            FileUpload::make('image')
                                ->label('Gambar Slide (Landscape disarankan)')
                                ->image()
                                ->directory('slides')
                                ->visibility('public')
                                ->required()
                                ->columnSpanFull(),

                            TextInput::make('title')
                                ->label('Judul Banner')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('link_url')
                                ->label('Tautan Tujuan (Opsional)')
                                ->placeholder('https://... atau /posts/...')
                                ->maxLength(255),

                            DatePicker::make('start_date')
                                ->label('Tanggal Mulai Tayang'),

                            DatePicker::make('end_date')
                                ->label('Tanggal Selesai Tayang'),

                            TextInput::make('sort_order')
                                ->label('Urutan')
                                ->numeric()
                                ->default(0),

                            Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true),

                            Textarea::make('description')
                                ->label('Deskripsi Singkat')
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}

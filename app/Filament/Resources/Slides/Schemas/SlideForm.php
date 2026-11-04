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
            ->columns(1)
            ->components([
                Section::make(__('Slide Banner Information'))
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            FileUpload::make('image')
                                ->label(__('Slide Image (Landscape recommended)'))
                                ->image()
                                ->directory('slides')
                                ->visibility('public')
                                ->required()
                                ->columnSpanFull(),

                            TextInput::make('title')
                                ->label(__('Banner Title'))
                                ->required()
                                ->maxLength(255),

                            TextInput::make('link_url')
                                ->label(__('Target Link (Optional)'))
                                ->placeholder(__('https://... or /posts/...'))
                                ->maxLength(255),

                            DatePicker::make('start_date')
                                ->label(__('Start Date')),

                            DatePicker::make('end_date')
                                ->label(__('End Date')),

                            TextInput::make('sort_order')
                                ->label(__('Sort Order'))
                                ->numeric()
                                ->default(0),

                            Toggle::make('is_active')
                                ->label(__('Active'))
                                ->default(true),

                            Textarea::make('description')
                                ->label(__('Short Description'))
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}

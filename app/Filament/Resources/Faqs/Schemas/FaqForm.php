<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('FAQ Information'))
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('topic_id')
                                ->label(__('FAQ Topic'))
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload()
                                ->native(false),

                            TextInput::make('sort_order')
                                ->label(__('Display Order'))
                                ->numeric()
                                ->default(0),

                            Toggle::make('is_active')
                                ->label(__('Active / Displayed'))
                                ->default(true)
                                ->columnSpanFull(),

                            TextInput::make('question')
                                ->label(__('Question (Question)'))
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            RichEditor::make('answer')
                                ->label(__('Answer (Answer)'))
                                ->required()
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}

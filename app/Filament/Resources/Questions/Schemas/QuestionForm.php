<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Enums\QuestionStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Inquirer Identity'))
                    ->description(__('Public data of the person submitting the question'))
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label(__('Full Name'))
                                ->required(),

                            TextInput::make('email')
                                ->label(__('Email Address'))
                                ->email()
                                ->required(),

                            TextInput::make('phone')
                                ->label(__('WhatsApp / Phone Number'))
                                ->tel(),
                        ]),
                    ]),

                Section::make(__('Question Details'))
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('topic_id')
                                ->label(__('Question Topic'))
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload()
                                ->native(false),

                            Select::make('status')
                                ->label(__('Question Status'))
                                ->options(QuestionStatus::class)
                                ->default(QuestionStatus::Pending)
                                ->required()
                                ->native(false),

                            TextInput::make('subject')
                                ->label(__('Question Subject'))
                                ->required()
                                ->columnSpanFull(),

                            Textarea::make('body')
                                ->label(__('Question Description'))
                                ->rows(5)
                                ->required()
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Enums\PageStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Section::make(__('Page Content'))
                    ->schema([
                        Hidden::make('user_id')
                            ->default(fn () => auth()->id()),

                        TextInput::make('title')
                            ->label(__('Page Title'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                'slug',
                                Str::slug($state ?? '')
                            )),

                        TextInput::make('slug')
                            ->label(__('URL Slug'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        RichEditor::make('content')
                            ->label(__('Page Body Content'))
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(['default' => 1, 'lg' => 2]),

                Section::make(__('Settings & Banner'))
                    ->schema([
                        Select::make('status')
                            ->label(__('Status'))
                            ->options(PageStatus::class)
                            ->default(PageStatus::Published)
                            ->required()
                            ->native(false),

                        TextInput::make('sort_order')
                            ->label(__('Sort Order'))
                            ->numeric()
                            ->default(0),

                        Toggle::make('show_in_menu')
                            ->label(__('Show in Navigation Menu'))
                            ->default(false),

                        FileUpload::make('image')
                            ->label(__('Banner Image'))
                            ->image()
                            ->directory('pages')
                            ->visibility('public'),
                    ])
                    ->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}

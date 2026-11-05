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
            ->columns(1)
            ->components([
                Section::make(__('Menu Information'))
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(__('Menu Name'))
                                ->required()
                                ->maxLength(255),

                            Select::make('location')
                                ->label(__('Placement Location'))
                                ->options([
                                    'header' => __('Header (Top Navigation)'),
                                    'footer' => __('Footer (Bottom Navigation)'),
                                    'sidebar' => __('Sidebar (Side Navigation)'),
                                ])
                                ->required()
                                ->native(false),

                            Toggle::make('is_active')
                                ->label(__('Active Menu'))
                                ->default(true)
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make(__('Menu Item List'))
                    ->description(__('Manage navigation links and items in this menu'))
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->orderColumn('sort_order')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('label')
                                        ->label(__('Menu Label'))
                                        ->required(),

                                    Select::make('type')
                                        ->label(__('Link Type'))
                                        ->options([
                                            'custom' => __('Custom Link / URL'),
                                            'page' => __('Static Page'),
                                            'category' => __('Post Category'),
                                        ])
                                        ->default('custom')
                                        ->required()
                                        ->live()
                                        ->native(false),

                                    TextInput::make('url')
                                        ->label(__('Target URL'))
                                        ->placeholder(__('/about-us or https://...'))
                                        ->required(fn (Get $get) => $get('type') === 'custom')
                                        ->visible(fn (Get $get) => $get('type') === 'custom'),

                                    Select::make('linkable_id')
                                        ->label(__('Select Page'))
                                        ->options(fn () => Page::all()->pluck('title', 'id'))
                                        ->searchable()
                                        ->visible(fn (Get $get) => $get('type') === 'page'),

                                    Select::make('linkable_id')
                                        ->label(__('Select Category'))
                                        ->options(fn () => Category::all()->pluck('name', 'id'))
                                        ->searchable()
                                        ->visible(fn (Get $get) => $get('type') === 'category'),

                                    TextInput::make('sort_order')
                                        ->label(__('Sort Order'))
                                        ->numeric()
                                        ->default(0),

                                    Toggle::make('is_active')
                                        ->label(__('Active'))
                                        ->default(true),
                                ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

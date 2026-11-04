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
            ->columns(1)
            ->components([
                Section::make(__('Setting Information'))
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('group')
                                ->label(__('Group'))
                                ->options([
                                    'general' => __('General (General)'),
                                    'contact' => __('Contact'),
                                    'social' => __('Social Media'),
                                    'maps' => __('Maps & Location'),
                                    'footer' => __('Footer'),
                                ])
                                ->required()
                                ->native(false),

                            Select::make('type')
                                ->label(__('Value Type'))
                                ->options([
                                    'text' => __('Short Text'),
                                    'textarea' => __('Long Text'),
                                    'image' => __('Image / Logo'),
                                    'boolean' => __('Boolean (1 / 0)'),
                                    'json' => 'JSON',
                                ])
                                ->default('text')
                                ->required()
                                ->live()
                                ->native(false),

                            TextInput::make('key')
                                ->label(__('Key'))
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->disabled(fn (?Setting $record) => $record !== null)
                                ->helperText(__('Unique identifier, e.g.: site_name, contact_email'))
                                ->columnSpanFull(),

                            TextInput::make('value')
                                ->label(__('Value'))
                                ->visible(fn (Get $get) => in_array($get('type') ?? 'text', ['text', 'boolean', 'json']))
                                ->columnSpanFull(),

                            Textarea::make('value')
                                ->label(__('Value'))
                                ->rows(4)
                                ->visible(fn (Get $get) => $get('type') === 'textarea')
                                ->columnSpanFull(),

                            FileUpload::make('value')
                                ->label(__('Image / File'))
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

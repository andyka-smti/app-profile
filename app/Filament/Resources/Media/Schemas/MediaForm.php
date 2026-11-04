<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Upload Media File'))
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            Hidden::make('user_id')
                                ->default(fn () => auth()->id()),

                            Hidden::make('disk')
                                ->default('public'),

                            Hidden::make('mime_type'),

                            Hidden::make('file_size'),

                            FileUpload::make('file_path')
                                ->label(__('Choose File'))
                                ->directory('media')
                                ->visibility('public')
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (Set $set, $state) {
                                    if ($state instanceof TemporaryUploadedFile) {
                                        $set('file_name', $state->getClientOriginalName());
                                        $set('mime_type', $state->getMimeType());
                                        $set('file_size', $state->getSize());
                                    }
                                })
                                ->columnSpanFull(),

                            TextInput::make('file_name')
                                ->label(__('File Name'))
                                ->required()
                                ->maxLength(255),

                            TextInput::make('alt_text')
                                ->label(__('Alternative Text (Alt Text)'))
                                ->placeholder(__('Image description for accessibility and SEO'))
                                ->maxLength(255),
                        ]),
                    ]),
            ]);
    }
}

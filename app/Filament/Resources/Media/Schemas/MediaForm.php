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
            ->components([
                Section::make('Unggah File Media')
                    ->schema([
                        Grid::make(2)->schema([
                            Hidden::make('user_id')
                                ->default(fn () => auth()->id()),

                            Hidden::make('disk')
                                ->default('public'),

                            Hidden::make('mime_type'),

                            Hidden::make('file_size'),

                            FileUpload::make('file_path')
                                ->label('Pilih File')
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
                                ->label('Nama File')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('alt_text')
                                ->label('Teks Alternatif (Alt Text)')
                                ->placeholder('Deskripsi gambar untuk aksesibilitas dan SEO')
                                ->maxLength(255),
                        ]),
                    ]),
            ]);
    }
}

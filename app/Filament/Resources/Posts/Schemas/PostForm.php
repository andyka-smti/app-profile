<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\PostStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make('Konten Artikel')
                        ->schema([
                            Hidden::make('user_id')
                                ->default(fn () => auth()->id()),

                            TextInput::make('title')
                                ->label('Judul Artikel')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                    'slug',
                                    Str::slug($state ?? '')
                                )),

                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            Textarea::make('summary')
                                ->label('Ringkasan Singkat')
                                ->rows(3)
                                ->placeholder('Ringkasan artikel untuk preview...')
                                ->columnSpanFull(),

                            RichEditor::make('content')
                                ->label('Isi Artikel')
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->columnSpan(2),

                    Section::make('Publikasi & Pengaturan')
                        ->schema([
                            Select::make('category_id')
                                ->label('Kategori')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->native(false),

                            Select::make('status')
                                ->label('Status')
                                ->options(PostStatus::class)
                                ->default(PostStatus::Draft)
                                ->required()
                                ->native(false),

                            DateTimePicker::make('published_at')
                                ->label('Tanggal Tayang')
                                ->default(now()),

                            FileUpload::make('image')
                                ->label('Gambar Utama')
                                ->image()
                                ->directory('posts')
                                ->visibility('public')
                                ->imageEditor(),

                            Toggle::make('is_featured')
                                ->label('Jadikan Unggulan (Featured)')
                                ->helperText('Artikel akan diprioritaskan di beranda'),
                        ])
                        ->columnSpan(1),
                ]),
            ]);
    }
}

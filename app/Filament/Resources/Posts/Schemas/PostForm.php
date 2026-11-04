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
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Section::make(__('Post Content'))
                    ->schema([
                        Hidden::make('user_id')
                            ->default(fn () => auth()->id()),

                        TextInput::make('title')
                            ->label(__('Post Title'))
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

                        Textarea::make('summary')
                            ->label(__('Short Summary'))
                            ->rows(3)
                            ->placeholder(__('Article summary for preview...'))
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label(__('Article Content'))
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(['default' => 1, 'lg' => 2]),

                Section::make(__('Publication & Settings'))
                    ->schema([
                        Select::make('category_id')
                            ->label(__('Category'))
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false),

                        Select::make('status')
                            ->label(__('Status'))
                            ->options(PostStatus::class)
                            ->default(PostStatus::Draft)
                            ->required()
                            ->native(false),

                        DateTimePicker::make('published_at')
                            ->label(__('Publish Date'))
                            ->default(now()),

                        FileUpload::make('image')
                            ->label(__('Featured Image'))
                            ->image()
                            ->directory('posts')
                            ->visibility('public')
                            ->imageEditor(),

                        Toggle::make('is_featured')
                            ->label(__('Make Featured'))
                            ->helperText(__('Article will be prioritized on homepage')),
                    ])
                    ->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label(__('Preview'))
                    ->disk('public')
                    ->square(),

                TextColumn::make('file_name')
                    ->label(__('File Name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('mime_type')
                    ->label(__('Type'))
                    ->badge()
                    ->color('gray'),

                TextColumn::make('human_file_size')
                    ->label(__('Size')),

                TextColumn::make('user.name')
                    ->label(__('Uploader'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('Uploaded At'))
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

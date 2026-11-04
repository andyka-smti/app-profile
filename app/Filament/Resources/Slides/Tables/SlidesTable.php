<?php

namespace App\Filament\Resources\Slides\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label(__('Banner'))
                    ->square()
                    ->size(70),

                TextColumn::make('title')
                    ->label(__('Slide Title'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('link_url')
                    ->label(__('Link'))
                    ->limit(25)
                    ->color('gray'),

                ToggleColumn::make('is_active')
                    ->label(__('Active'))
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label(__('Start Broadcast'))
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label(__('End Broadcast'))
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label(__('Sort Order'))
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('Active Status')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}

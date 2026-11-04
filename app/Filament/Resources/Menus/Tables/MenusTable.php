<?php

namespace App\Filament\Resources\Menus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Menu Name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('location')
                    ->label(__('Location'))
                    ->badge()
                    ->colors([
                        'primary' => 'header',
                        'gray' => 'footer',
                        'warning' => 'sidebar',
                    ])
                    ->sortable(),

                TextColumn::make('items_count')
                    ->label(__('Item Count'))
                    ->counts('items')
                    ->badge()
                    ->color('info'),

                ToggleColumn::make('is_active')
                    ->label(__('Active Status'))
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('location')
                    ->label(__('Location'))
                    ->options([
                        'header' => __('Header'),
                        'footer' => __('Footer'),
                        'sidebar' => __('Sidebar'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

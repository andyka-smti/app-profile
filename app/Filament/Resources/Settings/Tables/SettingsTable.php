<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->label(__('Group'))
                    ->badge()
                    ->colors([
                        'primary' => 'general',
                        'info' => 'contact',
                        'warning' => 'social',
                        'success' => 'maps',
                        'gray' => 'footer',
                    ])
                    ->sortable()
                    ->searchable(),

                TextColumn::make('key')
                    ->label(__('Key'))
                    ->fontFamily('mono')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('value')
                    ->label(__('Value'))
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->color('gray'),

                TextColumn::make('updated_at')
                    ->label(__('Last Modified'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label(__('Filter Group'))
                    ->options([
                        'general' => __('General (General)'),
                        'contact' => __('Contact'),
                        'social' => __('Social Media'),
                        'maps' => __('Maps & Location'),
                        'footer' => __('Footer'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('group');
    }
}

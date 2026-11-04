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
                    ->label('Grup')
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
                    ->label('Kunci (Key)')
                    ->fontFamily('mono')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('value')
                    ->label('Nilai')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label('Filter Grup')
                    ->options([
                        'general' => 'General (Umum)',
                        'contact' => 'Kontak',
                        'social' => 'Media Sosial',
                        'maps' => 'Peta & Lokasi',
                        'footer' => 'Footer',
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

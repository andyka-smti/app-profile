<?php

namespace App\Filament\Resources\QuestionTopics\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class QuestionTopicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Topic Name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->fontFamily('mono')
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('questions_count')
                    ->label(__('Total Questions'))
                    ->counts('questions')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('faqs_count')
                    ->label(__('Total FAQs'))
                    ->counts('faqs')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label(__('Active Status'))
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

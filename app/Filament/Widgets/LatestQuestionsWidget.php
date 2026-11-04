<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Questions\QuestionResource;
use App\Models\Question;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestQuestionsWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return __('Latest Public Questions');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Question::with('topic')->latest()->limit(5))
            ->columns([
                TextColumn::make('name')
                    ->label(__('Inquirer Name'))
                    ->weight('bold'),

                TextColumn::make('subject')
                    ->label(__('Question Subject'))
                    ->limit(40),

                TextColumn::make('topic.name')
                    ->label(__('Topic'))
                    ->badge()
                    ->color('info'),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),

                TextColumn::make('created_at')
                    ->label(__('Received Time'))
                    ->since(),
            ])
            ->recordActions([
                Action::make('open')
                    ->label(__('Respond'))
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (Question $record): string => QuestionResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}

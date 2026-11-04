<?php

namespace App\Filament\Resources\Questions\RelationManagers;

use App\Enums\QuestionAnswerStatus;
use App\Enums\QuestionStatus;
use App\Models\QuestionAnswer;
use App\Notifications\QuestionAnsweredNotification;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Officer Responses / Answers');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Hidden::make('user_id')
                    ->default(fn () => auth()->id()),

                Hidden::make('answered_at')
                    ->default(fn () => now()),

                Select::make('status')
                    ->label(__('Answer Status'))
                    ->options(QuestionAnswerStatus::class)
                    ->default(QuestionAnswerStatus::Published)
                    ->required()
                    ->native(false),

                Toggle::make('is_published')
                    ->label(__('Publish to Public'))
                    ->default(true),

                RichEditor::make('body')
                    ->label(__('Response / Answer Content'))
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('Responding Officer'))
                    ->weight('bold'),

                TextColumn::make('body')
                    ->label(__('Response Content'))
                    ->html()
                    ->limit(70),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),

                IconColumn::make('is_published')
                    ->label(__('Public'))
                    ->boolean(),

                TextColumn::make('answered_at')
                    ->label(__('Answer Time'))
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('Write Answer'))
                    ->after(function (QuestionAnswer $record) {
                        $question = $this->getOwnerRecord();
                        $question->update(['status' => QuestionStatus::Answered]);

                        // Kirim notifikasi email ke penanya jika alamat email tersedia
                        if (! empty($question->email) && filter_var($question->email, FILTER_VALIDATE_EMAIL)) {
                            try {
                                Notification::route('mail', $question->email)
                                    ->notify(new QuestionAnsweredNotification($question, $record));
                            } catch (\Throwable $e) {
                                // Ignore email transport failure in local dev
                            }
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

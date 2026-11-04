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
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Notification;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    protected static ?string $title = 'Tanggapan / Jawaban Petugas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Hidden::make('user_id')
                        ->default(fn () => auth()->id()),

                    Hidden::make('answered_at')
                        ->default(fn () => now()),

                    Select::make('status')
                        ->label('Status Jawaban')
                        ->options(QuestionAnswerStatus::class)
                        ->default(QuestionAnswerStatus::Published)
                        ->required()
                        ->native(false),

                    Toggle::make('is_published')
                        ->label('Publikasikan ke Publik')
                        ->default(true),

                    RichEditor::make('body')
                        ->label('Isi Tanggapan / Jawaban')
                        ->required()
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Petugas Penjawab')
                    ->weight('bold'),

                TextColumn::make('body')
                    ->label('Isi Tanggapan')
                    ->html()
                    ->limit(70),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                IconColumn::make('is_published')
                    ->label('Publik')
                    ->boolean(),

                TextColumn::make('answered_at')
                    ->label('Waktu Menjawab')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tulis Jawaban')
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

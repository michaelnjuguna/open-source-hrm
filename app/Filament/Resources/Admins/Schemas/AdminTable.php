<?php
namespace App\Filament\Resources\Admin\Schemas;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\{ActionGroup, EditAction, ViewAction, DeleteAction, BulkActionGroup, DeleteBulkAction};
use Filament\Notifications\Notification;
class AdminTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    ViewAction::make(),
                    DeleteAction::make()
                        ->hidden(fn($record) => auth()->id() === $record->id)
                    ,
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($action, $records) {
                            if ($records->contains(fn($record) => $record->id === auth()->id())) {
                                Notification::make()->title('You cannot delete your own account, try again')
                                    ->warning()->send();
                                $action->cancel();
                            }
                        })
                    ,
                ]),
            ]);
    }

}
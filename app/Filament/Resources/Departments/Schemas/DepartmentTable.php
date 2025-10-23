<?php

namespace App\Filament\Resources\Department\Schemas;
use Filament\Tables\Table;
use Filament\Actions\{ActionGroup, EditAction, ViewAction, DeleteAction, BulkActionGroup, DeleteBulkAction};
use Filament\Tables\Columns\TextColumn;
use App\Models\{Department};


class DepartmentTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Department::query()->with('manager')->latest()

            )
            ->columns([
                //
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->limit(10)
                    ->label('Department Code'),
                TextColumn::make('description')
                    ->limit(50)
                    ->label('Description'),
                TextColumn::make('manager_id')
                    ->formatStateUsing(fn($record) => $record->manager?->full_name ?? 'No Manager')
                    ->label('Manager')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([

                    EditAction::make(),
                    ViewAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Organization';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Department Name')
                    ->placeholder('Enter department name'),
                Forms\Components\TextInput::make('code')
                    ->maxLength(50)
                    ->label('Department Code')
                    ->placeholder('Enter department code'),
                Forms\Components\Textarea::make('description')
                    ->maxLength(500)
                    ->label('Description')
                    ->placeholder('Enter department description'),
                Forms\Components\Select::make('manager_id')
                    ->options(function () {
                        return \App\Models\Employee::all()->pluck('full_name', 'id');
                    })
                    ->label('Manager')
                    ->searchable(

                    )
                    ->placeholder('Select a manager')
                    ->preload()
                    ->nullable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                static::getEloquentQuery()->with('manager')->latest()

            )
            ->columns([
                //
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->label('Department Code'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->label('Description'),
                Tables\Columns\TextColumn::make('manager_id')
                    ->formatStateUsing(fn($record) => $record->manager?->full_name ?? 'No Manager')
                    ->label('Manager')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            // 'create' => Pages\CreateDepartment::route('/create'),
            // 'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}

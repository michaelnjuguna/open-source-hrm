<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;
use App\Filament\Resources\LeaveResource\RelationManagers;
use App\Models\Employee;
use App\Models\Leave;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-minus';
    protected static ?string $navigationGroup = 'HR Management';
    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Leave Requests';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //

                Forms\Components\Select::make('employee_id')
                    ->options(function () {
                        return Employee::all()->pluck('full_name', 'id');
                    })
                    ->searchable(
                        [
                            'first_name',
                            'last_name',
                        ]
                    )
                    ->required()
                    ->label('Employee'),
                Forms\Components\Select::make('leave_type')
                    ->options([
                        'Sick Leave' => 'Sick Leave',
                        'Vacation' => 'Vacation',
                        'Personal Leave' => 'Personal Leave',
                        'Maternity Leave' => 'Maternity Leave',
                        'Paternity Leave' => 'Paternity Leave',
                        'Bereavement Leave' => 'Bereavement Leave',
                        'Other' => 'Other',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->required()
                    ->label('Start Date'),
                Forms\Components\DatePicker::make('end_date')
                    ->required()
                    ->label('End Date'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ])
                    ->default('Pending')
                    ->required(),
                Forms\Components\Textarea::make('rejection_reason')
                    ->nullable()
                    ->columnSpan('full')
                    ->label('Rejection Reason'),
                Forms\Components\Textarea::make('notes')
                    ->nullable()
                    ->columnSpan('full')
                    ->label('Notes'),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                static::getEloquentQuery()
                    ->with(['employee'])

                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('employee.employee_number')
                    ->label('Employee Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->searchable([
                        'employees.first_name',
                        'employees.last_name',
                    ])
                    ->sortable([
                        'employees.first_name',
                        'employees.last_name',
                    ]),
                Tables\Columns\TextColumn::make('leave_type')
                    ->label('Leave Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->label('Start Date'),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->label('End Date'),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration(Days)'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                ,
                Tables\Columns\TextColumn::make('rejection_reason')
                    ->label('Rejection Reason')
                    ->default('N/A')
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->default('N/A')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created At'),
            ])
            ->filters(
                [
                    //
                    Tables\Filters\SelectFilter::make('employee_id')
                        ->label('Employee')
                        ->searchable()
                        ->options(
                            // Employee::all()->pluck('full_name', 'id')
                            Leave::query()
                                ->with('employee')
                                ->get()
                                ->pluck('employee.full_name', 'employee.id')
                        )
                        ->default(null),
                    Tables\Filters\SelectFilter::make('status')
                        ->label('Status')
                        ->options([
                            'Pending' => 'Pending',
                            'Approved' => 'Approved',
                            'Rejected' => 'Rejected',
                        ])
                        ->default(null),
                    Tables\Filters\SelectFilter::make('leave_type')
                        ->label('Leave Type')
                        ->options([
                            'Sick Leave' => 'Sick Leave',
                            'Vacation' => 'Vacation',
                            'Personal Leave' => 'Personal Leave',
                            'Maternity Leave' => 'Maternity Leave',
                            'Paternity Leave' => 'Paternity Leave',
                            'Bereavement Leave' => 'Bereavement Leave',
                            'Other' => 'Other',
                        ])
                        ->default(null),

                ]

            )
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListLeaves::route('/'),
            // 'create' => Pages\CreateLeave::route('/create'),
            // 'view' => Pages\ViewLeave::route('/{record}'),
            // 'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Leaves;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Leaves\Pages\ListLeaves;
use App\Filament\Resources\LeaveResource\Pages;
use App\Filament\Resources\LeaveResource\RelationManagers;
use App\Models\Employee;
use App\Models\Leave;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-minus';
    protected static string | \UnitEnum | null $navigationGroup = 'HR Management';
    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Leave Requests';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //

                Select::make('employee_id')
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
                Select::make('leave_type')
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
                DatePicker::make('start_date')
                    ->required()
                    ->label('Start Date'),
                DatePicker::make('end_date')
                    ->required()
                    ->label('End Date'),
                Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ])
                    ->default('Pending')
                    ->required(),
                Textarea::make('rejection_reason')
                    ->nullable()
                    ->columnSpan('full')
                    ->label('Rejection Reason'),
                Textarea::make('notes')
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
                TextColumn::make('employee.employee_number')
                    ->label('Employee No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->searchable([
                        'employees.first_name',
                        'employees.last_name',
                    ])
                    ->sortable([
                        'employees.first_name',
                        'employees.last_name',
                    ]),
                TextColumn::make('leave_type')
                    ->label('Leave Type')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->label('Start Date'),
                TextColumn::make('end_date')
                    ->date()
                    ->label('End Date'),
                TextColumn::make('duration')
                    ->label('Duration(Days)'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'secondary',
                    })
                    ->label('Status')
                ,
                TextColumn::make('rejection_reason')
                    ->label('Rejection Reason')
                    ->default('N/A')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->default('N/A')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created At'),
            ])
            ->filters(
                [
                    //
                    SelectFilter::make('employee_id')
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
                    SelectFilter::make('status')
                        ->label('Status')
                        ->options([
                            'Pending' => 'Pending',
                            'Approved' => 'Approved',
                            'Rejected' => 'Rejected',
                        ])
                        ->default(null),
                    SelectFilter::make('leave_type')
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
            ->recordActions([
                ActionGroup::make([

                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),

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
            'index' => ListLeaves::route('/'),
            // 'create' => Pages\CreateLeave::route('/create'),
            // 'view' => Pages\ViewLeave::route('/{record}'),
            // 'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}

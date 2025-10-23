<?php
namespace App\Filament\Resources\Leaves\Schemas;

use App\Models\Leave;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\{ActionGroup, ViewAction, EditAction, DeleteAction, BulkActionGroup, DeleteBulkAction};
class LeaveTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Leave::query()
                    ->with(['employee'])

                    ->latest()
            )
            ->columns([
                TextColumn::make('employee.employee_number')
                    ->label('Employee No.')
                    ->searchable(

                    )
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->searchable([
                        'first_name',
                        'last_name',
                    ])
                    ->sortable([
                        'first_name',
                        'last_name',
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
}
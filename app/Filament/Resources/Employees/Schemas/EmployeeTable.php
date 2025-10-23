<?php
namespace App\Filament\Resources\Employees\Schemas;

use Filament\Tables\Table;
use App\Models\{Employee, Position, Department};
use Filament\Tables\Filters\{Filter, SelectFilter};
use Filament\Tables\Columns\{TextColumn, ToggleColumn, };
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\{ActionGroup, EditAction, ViewAction, DeleteAction, BulkActionGroup, DeleteBulkAction};
class EmployeeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Employee::query()
                    ->with(['department'])
                    ->latest()
            )
            ->filters(
                [


                    Filter::make('is_active')
                        ->label('Active Employees')
                        // ->toggle()
                        ->query(fn(Builder $query): Builder => $query->where('is_active', true))
                        ->default(false),
                    Filter::make('is_inactive')
                        ->label('Inactive Employees')
                        // ->toggle()
                        ->query(fn(Builder $query): Builder => $query->where('is_active', false))
                        ->default(false),
                    SelectFilter::make('department_id')
                        ->label('Department')
                        ->options(
                            fn() => Department::all()->pluck('name', 'id')
                        )
                        ->searchable(),
                    SelectFilter::make('employment_type')
                        ->label('Employment Type')
                        ->options([
                            'Permanent' => 'Permanent',
                            'Contract' => 'Contract',
                            'Casual' => 'Casual',
                        ]),
                    SelectFilter::make('position_id')
                        ->label('Position')
                        ->options(
                            Position::all()->pluck('title', 'id')
                        )
                        ->searchable(),




                ],

            )
            ->columns([
                //
                TextColumn::make('employee_number')
                    ->label('Employee No.')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable(
                        [
                            'first_name',
                            'last_name'
                        ]
                    )
                    ->sortable([
                        'first_name',
                        'last_name'
                    ]),
                TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position.title')
                    ->label('Position')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('national_id')
                    ->label('National ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('kra_pin')
                    ->label('KRA PIN')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('employment_type')
                    ->label('Employment Type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Is Active')

                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                TextColumn::make('date_of_birth')
                    ->label('Date of Birth')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('termination_date')
                    ->label('Termination Date')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('hire_date')
                    ->label('Hire Date')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

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

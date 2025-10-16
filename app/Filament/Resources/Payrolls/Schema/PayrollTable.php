<?php
namespace App\Filament\Resources\Payrolls\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\{SelectFilter, Filter};
use Filament\Forms\Components\Select;
use App\Models\Employee;
use Filament\Actions\{BulkActionGroup, DeleteBulkAction};
class PayrollTable
{
    public static function configure(Table $table): Table
    {


        return $table
            ->columns([
                TextColumn::make('employee.employee_number')
                    ->label('Emp No.')
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->tooltip('Employee number')
                    ->searchable(),


                TextColumn::make('employee.full_name')
                    ->label('Name')
                    ->searchable([
                        'first_name',
                        'last_name',
                    ])
                    ->sortable(
                        [
                            'first_name',
                            'last_name',
                        ]
                    ),
                TextColumn::make('pay_date')
                    ->date()
                    ->label('Pay Date')
                    ->sortable(),
                TextColumn::make('period')
                    ->label('Period')
                    ->searchable()
                    ->limit(10)
                    ->sortable(),
                TextColumn::make('gross_pay')
                    ->label('Gross Pay')
                    ->sortable()
                    ->money('KSH', true),
                TextColumn::make('net_pay')
                    ->label('Net Pay')
                    ->sortable()
                    ->money('KSH', true),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    })
                    ->label('Status'),



            ])
            ->filters([
                //
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('Status'),
                Filter::make('employee')

                    ->schema([
                        Select::make('employee_id')
                            ->label('Employee')
                            ->options(function () {
                                return Employee::all()->pluck('full_name', 'id');
                            })
                            ->searchable()
                            ->required(),

                    ]),


            ])
            ->recordActions([
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\ViewAction::make(),
                    \Filament\Actions\EditAction::make(),
                    \Filament\Actions\DeleteAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }


}
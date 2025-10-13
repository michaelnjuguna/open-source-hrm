<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\PayrollResource\Pages\ListPayrolls;
use App\Filament\Resources\PayrollResource\Pages;
use App\Filament\Resources\PayrollResource\RelationManagers;
use App\Models\Payroll;
// use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Employee;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\{ActionGroup, DeleteAction, EditAction, ViewAction};


class PayrollResource extends Resource
{
    // TODO: Global search
    // TODO: Add icons
    protected static ?string $model = Payroll::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-banknotes';
    protected static string | \BackedEnum | null $activeNavigationIcon = 'heroicon-s-banknotes';

    protected static string | \UnitEnum | null $navigationGroup = 'HR Management';
    protected static ?int $navigationSort = 4;

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
                DatePicker::make('pay_date')
                    ->label('Pay Date')
                    ->required(),
                TextInput::make('period')
                    ->label('Period')
                    ->placeholder('e.g., 2025-01')
                    ->required()
                    ->maxLength(255),
                TextInput::make('gross_pay')
                    ->label('Gross Pay')
                    ->required()
                    ->numeric(),

                TextInput::make('net_pay')
                    ->label('Net Pay')
                    ->required()
                    ->numeric(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending'),
                KeyValue::make('deductions')
                    ->label('Deductions')
                    ->keyLabel('Type')

                    ->valueLabel('Amount'),

                KeyValue::make('allowances')
                    ->label('Allowances')
                    ->keyLabel('Type')

                    ->valueLabel('Amount'),
                KeyValue::make('bonuses')
                    ->label('Bonuses')
                    ->keyLabel('Type')

                    ->valueLabel('Amount'),
                Textarea::make('notes')
                    ->label('Notes')
                    ->nullable()
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.employee_number')
                    ->label('Employee No.')
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->searchable(),


                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->searchable([
                        'employees.first_name',
                        'employees.last_name',
                    ])
                    ->sortable(
                        [
                            'employees.first_name',
                            'employees.last_name',
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayrolls::route('/'),
            // 'create' => Pages\CreatePayroll::route('/create'),
            // 'edit' => Pages\EditPayroll::route('/{record}/edit'),
        ];
    }
}

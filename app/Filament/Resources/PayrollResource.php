<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayrollResource\Pages;
use App\Filament\Resources\PayrollResource\RelationManagers;
use App\Models\Payroll;
// use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Employee;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

class PayrollResource extends Resource
{
    // TODO: Global search
    protected static ?string $model = Payroll::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'HR Management';
    protected static ?int $navigationSort = 4;

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
                Forms\Components\DatePicker::make('pay_date')
                    ->label('Pay Date')
                    ->required(),
                Forms\Components\TextInput::make('period')
                    ->label('Period')
                    ->placeholder('e.g., 2025-01')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gross_pay')
                    ->label('Gross Pay')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('net_pay')
                    ->label('Net Pay')
                    ->required()
                    ->numeric(),

                Forms\Components\Select::make('status')
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
                Forms\Components\Textarea::make('notes')
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


                Tables\Columns\TextColumn::make('employee.full_name')
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
                Tables\Columns\TextColumn::make('pay_date')
                    ->date()
                    ->label('Pay Date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('period')
                    ->label('Period')
                    ->searchable()
                    ->limit(10)
                    ->sortable(),
                Tables\Columns\TextColumn::make('gross_pay')
                    ->label('Gross Pay')
                    ->sortable()
                    ->money('KSH', true),
                Tables\Columns\TextColumn::make('net_pay')
                    ->label('Net Pay')
                    ->sortable()
                    ->money('KSH', true),
                Tables\Columns\TextColumn::make('status')
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('Status'),
                Tables\Filters\Filter::make('employee')

                    ->form([
                        Forms\Components\Select::make('employee_id')
                            ->label('Employee')
                            ->options(function () {
                                return Employee::all()->pluck('full_name', 'id');
                            })
                            ->searchable()
                            ->required(),

                    ]),


            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            'index' => Pages\ListPayrolls::route('/'),
            // 'create' => Pages\CreatePayroll::route('/create'),
            // 'edit' => Pages\EditPayroll::route('/{record}/edit'),
        ];
    }
}

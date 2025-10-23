<?php

namespace App\Filament\Resources\Payrolls\Schema;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{KeyValue};
use Filament\Forms\Components\{Select, DatePicker, TextInput, Textarea};
use App\Models\{Employee};
class PayrollForm
{
    public static function configure(Schema $schema): Schema
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
}
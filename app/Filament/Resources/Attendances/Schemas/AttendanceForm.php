<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\{TextInput, DatePicker, Select, TimePicker, Textarea};
use Filament\Schemas\Schema;
use App\Models\{shift, Employee, Attendance};
use Filament\Schemas\Components\{Section, Grid};

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->options(function () {
                        return Employee::all()->pluck('full_name', 'id');
                    })
                    ->label('Employee')
                    ->required()
                    ->searchable([
                        'first_name',
                        'last_name'
                    ]),
                Select::make('shift_id')
                    ->options(function () {
                        return Shift::all()->pluck('name', 'id');
                    })
                    ->preload()
                    ->label('Shift')
                    ->searchable()
                    ->createOptionForm(
                        [
                            TextInput::make('name')
                                ->required()
                                ->label('Shift Name')

                            ,
                            Grid::make(2)->schema([

                                TimePicker::make('start_time')
                                    ->required()
                                    ->label('Start Time')
                                    ->time(),
                                TimePicker::make('end_time')
                                    ->required()
                                    ->label('End Time')
                                    ->time(),
                            ]),



                        ]
                    )
                    ->createOptionUsing(function (array $data) {
                        return Shift::create([
                            'name' => $data['name'],
                            'start_time' => $data['start_time'],
                            'end_time' => $data['end_time'],
                            'is_default' => $data['is_default'] ?? false,
                        ])->id;
                    })
                ,
                DatePicker::make('date')
                    ->unique(
                        table: Attendance::class,
                        column: 'date',
                        ignoreRecord: true
                    )
                    ->required()
                    ->label('Attendance Date'),
                TimePicker::make('clock_in')
                    ->required()
                    ->label('Clock In Time')
                    ->time(),
                TimePicker::make('clock_out')
                    // ->required()
                    ->label('Clock Out Time')
                    ->time(),

                Textarea::make('remarks')
                    ->label('Remarks')
                    ->maxLength(255)
                    ->nullable()
                    ->autosize()
                    ->columnSpanFull()
                ,
            ]);
    }
}
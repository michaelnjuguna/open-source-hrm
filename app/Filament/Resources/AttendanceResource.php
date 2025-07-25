<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use App\Models\Shift;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'HR Management';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('employee_id')
                    ->options(function () {
                        return \App\Models\Employee::all()->pluck('full_name', 'id');
                    })
                    ->label('Employee')
                    ->required()
                    ->searchable(),
                Select::make('shift_id')
                    ->options(function () {
                        return \App\Models\Shift::all()->pluck('name', 'id');
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
                Forms\Components\DatePicker::make('date')
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

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                static::getEloquentQuery()
                    ->with(['employee', 'shift'])
                    ->withoutGlobalScopes([SoftDeletingScope::class])
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('employee.employee_number')
                    ->label('Employee No.')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->searchable([
                        'employees.first_name',
                        'employees.last_name',

                    ])
                    ->sortable(
                        [
                            'employees.first_name',
                            'employees.last_name',
                        ]
                    )
                    ->label('Name')
                ,
                Tables\Columns\TextColumn::make('shift.name')
                    ->label('Shift'),
                Tables\Columns\TextColumn::make('date')
                    ->date()

                    ->label(' Date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('clock_in')
                    ->dateTime('H:i')
                    ->label('Clock In Time'),
                Tables\Columns\TextColumn::make('clock_out')
                    ->dateTime('H:i')
                    ->label('Clock Out Time'),
                Tables\Columns\TextColumn::make('hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->label('Hours'),
                Tables\Columns\TextColumn::make('remarks')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Remarks'),
            ])
            ->filters(
                [
                    SelectFilter::make('employee_id')
                        ->label('Employee')
                        ->searchable()
                        ->options(
                            \App\Models\Employee::all()->pluck('full_name', 'id')
                        ),
                    SelectFilter::make('shift_id')
                        ->label('Shift')
                        ->options(
                            \App\Models\Shift::all()->pluck('name', 'id')
                        ),
                    Tables\Filters\Filter::make('date')
                        ->form([
                            Forms\Components\DatePicker::make('date')
                                ->label('Select Date')
                                ->required()
                            // ->default(now())
                        ])
                        ->query(function (Builder $query, array $data) {
                            if (isset($data['date'])) {
                                return $query->whereDate('date', $data['date']);
                            }
                            return $query;
                        })

                ]

            )
            ->actions([
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListAttendances::route('/'),
            // 'create' => Pages\CreateAttendance::route('/create'),
            // 'view' => Pages\ViewAttendance::route('/{record}'),
            // 'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}

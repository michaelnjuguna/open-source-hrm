<?php

namespace App\Filament\Resources\Attendances;

use App\Filament\Resources\Attendances\Schemas\AttendanceForm;
use Filament\Schemas\Schema;
use App\Models\Employee;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Attendances\Pages\ListAttendances;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use App\Models\Shift;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
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

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static string|\UnitEnum|null $navigationGroup = 'HR Management';
    protected static ?int $navigationSort = 2;


    public static function form(Schema $schema): Schema
    {
        return AttendanceForm::configure($schema);
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
                TextColumn::make('employee.employee_number')
                    ->label('Employee No.')
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
                TextColumn::make('employee.full_name')
                    ->searchable([
                        'first_name',
                        'last_name',

                    ])
                    ->sortable(
                        [
                            'first_name',
                            'last_name',
                        ]
                    )
                    ->label('Name')
                ,
                TextColumn::make('shift.name')
                    ->label('Shift')
                    ->searchable()
                ,
                TextColumn::make('date')
                    ->date()

                    ->label(' Date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('clock_in')
                    ->dateTime('H:i')
                    ->searchable()
                    ->sortable()
                    ->label('Clock In Time'),
                TextColumn::make('clock_out')
                    ->dateTime('H:i')
                    ->searchable()
                    ->sortable()
                    ->label('Clock Out Time'),
                TextColumn::make('hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()

                    ->label('Hours'),
                TextColumn::make('remarks')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Remarks'),
            ])
            ->filters(
                [
                    SelectFilter::make('employee_id')
                        ->label('Employee')
                        ->searchable(
                            [
                                'first_name',
                                'last_name'
                            ]
                        )
                        ->options(
                            Employee::all()->pluck('full_name', 'id')
                        ),
                    SelectFilter::make('shift_id')
                        ->label('Shift')
                        ->options(
                            Shift::all()->pluck('name', 'id')
                        ),
                    Filter::make('date')
                        ->schema([
                            DatePicker::make('date')
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
            'index' => ListAttendances::route('/'),
            // 'create' => Pages\CreateAttendance::route('/create'),
            // 'view' => Pages\ViewAttendance::route('/{record}'),
            // 'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}

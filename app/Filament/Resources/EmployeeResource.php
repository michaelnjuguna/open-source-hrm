<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use App\Models\Position;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use App\Models\Department;
use Filament\Forms\Components\Grid;


class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'HR Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Basic Information')
                    ->collapsible()

                    ->schema([
                        TextInput::make('employee_number')
                            ->required()
                            ->maxLength(50)
                            ->label('Employee Number')
                            ->placeholder('Enter employee number'),
                        TextInput::make('first_name')
                            ->required(),
                        TextInput::make('last_name')
                            ->required(),
                        DatePicker::make('date_of_birth'),
                        Select::make('gender')
                            ->options(['Male' => 'Male', 'Female' => 'Female']),
                        Select::make('marital_status')
                            ->options([
                                'Single' => 'Single',
                                'Married' => 'Married',
                                'Divorced' => 'Divorced',
                                'Widowed' => 'Widowed'
                            ]),

                    ])
                    ->columns(2),
                Section::make('Contact Information')
                    ->collapsible()
                    ->schema([
                        TextInput::make('email')->email(),
                        TextInput::make('phone')->tel()->required(),
                        TextInput::make('national_id')->required()->unique(ignoreRecord: true)
                            ->integer()
                        ,
                        TextInput::make('kra_pin'),
                    ])
                    ->columns(2),
                Section::make('Emergency Contact')
                    ->collapsible()
                    ->schema([
                        TextInput::make('emergency_contact_name'),
                        TextInput::make('emergency_contact_phone'),
                    ])
                    ->columns(2),
                Section::make('Next of Kin')
                    ->collapsible()
                    ->schema([
                        TextInput::make('next_of_kin_name')
                            ->label('Name')
                            ->required(),
                        TextInput::make('next_of_kin_relationship')
                            ->label('Relationship')
                            ->required(),
                        TextInput::make('next_of_kin_phone')
                            ->required()
                            ->tel()
                            ->label('Phone'),
                        TextInput::make('next_of_kin_email')
                            ->label('Email')
                            ->email(),
                    ])
                    ->columns(2),
                Section::make('Employment Details')
                    ->collapsible()
                    ->schema([
                        Select::make('department_id')
                            ->relationship(
                                name: 'department',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->select('id', 'name')->orderBy('name', 'asc')
                            )
                            ->label('Department')
                            ->searchable()
                            ->placeholder('Select a department')
                            ->preload()
                            // ->columnSpanFull()
                            ->nullable(),
                        Select::make('position_id')
                            ->options(
                                Position::all()->pluck('title', 'id')

                            )
                            ->label('Position')
                            ->searchable()
                            ->placeholder('Select a position')
                            ->preload()
                            ->nullable()
                            ->createOptionForm([
                                TextInput::make('title')
                                    ->required()
                                    ->label('Position Title'),
                                Select::make('department_id')
                                    ->options(
                                        Department::all()->pluck('name', 'id')
                                    ),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('code')
                                            ->label('Position Code')
                                            ->unique(ignoreRecord: true)
                                            ->nullable(),
                                        TextInput::make('salary')
                                            ->label('Salary')
                                            ->numeric()
                                            ->nullable(),
                                    ]),
                                Textarea::make('description')
                                    ->label('Description')
                                    ->nullable()
                                    ->maxLength(255),

                            ])
                            ->createOptionUsing(function (array $data) {
                                return Position::create([
                                    'title' => $data['title'],
                                    'department_id' => $data['department_id'],
                                    'code' => $data['code'] ?? null,
                                    'salary' => $data['salary'] ?? null,
                                    'description' => $data['description'] ?? null,
                                ])->id;
                            })
                            ->native(false),
                        Select::make('employment_type')
                            ->options([
                                'Permanent' => 'Permanent',
                                'Contract' => 'Contract',
                                'Casual' => 'Casual',
                            ])
                            ->required(),
                        DatePicker::make('hire_date')->required(),
                        DatePicker::make('termination_date'),
                        Toggle::make('is_active')->default(true),
                    ])

                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                static::getEloquentQuery()
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
                            fn() => \App\Models\Department::all()->pluck('name', 'id')
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
                Tables\Columns\TextColumn::make('employee_number')
                    ->label('Employee No.')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('full_name')
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
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position.title')
                    ->label('Position')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('national_id')
                    ->label('National ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('kra_pin')
                    ->label('KRA PIN')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('employment_type')
                    ->label('Employment Type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Is Active')

                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Date of Birth')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('termination_date')
                    ->label('Termination Date')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('hire_date')
                    ->label('Hire Date')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

            ])

            ->actions([
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListEmployees::route('/'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            // 'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}

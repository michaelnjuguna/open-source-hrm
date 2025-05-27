<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Human Resources';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Basic Information')
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
                    ->schema([
                        TextInput::make('email')->email(),
                        TextInput::make('phone'),
                        TextInput::make('national_id')->required()->unique(ignoreRecord: true)
                            ->integer()
                        ,
                        TextInput::make('kra_pin'),
                    ])
                    ->columns(2),
                Section::make('Emergency Contact')
                    ->schema([
                        TextInput::make('emergency_contact_name'),
                        TextInput::make('emergency_contact_phone'),
                    ])
                    ->columns(2),
                Section::make('Employment Details')
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
                            ->columnSpanFull()
                            ->nullable(),
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
            ->columns([
                //
                Tables\Columns\TextColumn::make('employee_number')
                    ->label('Employee Number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable(false)
                    ->sortable(false),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            // 'create' => Pages\CreateEmployee::route('/create'),
            // 'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}

<?php
namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use App\Models\Employee;
class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Department Name')
                    ->placeholder('Enter department name'),
                TextInput::make('code')
                    ->maxLength(50)
                    ->label('Department Code')
                    ->placeholder('Enter department code'),
                Textarea::make('description')
                    ->maxLength(500)
                    ->label('Description')
                    ->placeholder('Enter department description'),
                Select::make('manager_id')
                    ->options(function () {
                        return Employee::all()->pluck('full_name', 'id');
                    })
                    ->label('Manager')
                    ->searchable(

                    )
                    ->placeholder('Select a manager')
                    ->preload()
                    ->nullable(),

            ]);
    }
}
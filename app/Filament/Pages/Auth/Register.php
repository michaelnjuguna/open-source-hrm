<?php

namespace App\Filament\Pages\Auth;

use App\Models\Employee;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
// use Filament\Pages\Auth\Register as BaseRegister;
// use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class Register extends BaseRegister
{

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([

                        TextInput::make('first_name')
                            ->required()
                            ->autofocus()
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                    ]),
                Grid::make()
                    ->schema([
                        TextInput::make('employee_code'),
                        TextInput::make('phone'),
                    ]),

                // $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
    protected function handleRegistration(array $data): Employee
    {
        $admin = $this->createUser($data);
        $admin->assignRole('admin');
        $this->redirect('/');
        return $admin;
    }
    protected function createUser(array $data): Employee
    {
        $employee = Employee::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'employee_code' => $data['employee_code'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);


        return $employee;
    }


}
<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
// use Filament\Pages\Auth\Register as BaseRegister;
// use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

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

    // protected function getForms(): array
    // {
    //     return [
    //         'form' => $this->form(
    //             $this->makeForm()
    //                 ->schema([
    //                     TextInput::make('first_name')
    //                         ->label('First Name')
    //                         ->required()
    //                         ->maxLength(255)
    //                         ->autofocus(),
    //                     TextInput::make('last_name')
    //                         ->label('Last Name')
    //                         ->required()
    //                         ->maxLength(255),
    //                     $this->getEmailFormComponent(),
    //                     $this->getPasswordFormComponent(),
    //                     $this->getPasswordConfirmationFormComponent(),
    //                 ])
    //                 ->statePath('data'),
    //         ),
    //     ];
    // }
}
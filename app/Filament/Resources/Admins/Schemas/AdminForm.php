<?php
namespace App\Filament\Resources\Admins\Schemas;
use Filament\Forms\Components\{TextInput};
use Filament\Schemas\Schema;
class AdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                ,
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                ,

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required()
                    ->same('password_confirmation')
                    ->maxLength(255),
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->revealable()
                    ->required()
                    ->maxLength(255)
                    ->same('password')

            ]);
    }
}
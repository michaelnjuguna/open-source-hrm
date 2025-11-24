<?php
namespace App\Filament\Resources\Messages\Schemas;
use Filament\Forms\Components\{TextInput, Select, RichEditor};
use Filament\Schemas\Schema;
use App\Models\{Employee, User};

class MessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            //
            TextInput::make("subject")
                ->required()
                ->maxLength(255)
                ->columnSpanFull()
                ->label("Subject"),
            Select::make("receiver_id")
                ->label("receiver")
                ->required()
                ->multiple()

                ->options(
                    collect()
                        ->merge(
                            Employee::all()->mapWithKeys(
                                fn($employee) => [
                                    "Employee_" . $employee->id =>
                                        $employee->email
                                ],
                            ),
                        )
                        ->merge(
                            User::all()->mapWithKeys(
                                fn($user) => [
                                    "User_" . $user->id =>
                                        $user->email
                                ],
                            ),
                        ),
                )
                ->columnSpanFull()
                ->searchable(["email"]),
            Select::make("receiver_type")
                ->options([Employee::class, User::class])
                ->hidden(),
            RichEditor::make("content")
                // ->extraAttributes(['style' => 'height: 400px;'])
                ->columnSpanFull(),
        ]);
    }
}
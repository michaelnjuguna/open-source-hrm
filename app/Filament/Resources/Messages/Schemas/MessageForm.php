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
                    Employee::all()->mapWithKeys(
                        fn($employee) => [
                            $employee->id =>
                                $employee->email
                        ],
                    )

                )
                ->columnSpanFull()
                ->searchable(["email"]),

            RichEditor::make("content")
                // ->extraAttributes(['style' => 'height: 400px;'])
                ->columnSpanFull(),
        ]);
    }
}
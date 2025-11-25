<?php
namespace App\Filament\Resources\Messages\Schemas;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\{TextEntry, RepeatableEntry};
use Filament\Forms\Components\{RichEditor as FRichEditor};
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;
use Filament\Actions\{Action as FAction};
use Filament\Schemas\Components\Actions;
use App\Models\{Message};
class MessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema

            ->components([
                RepeatableEntry::make('message')
                    ->poll('3s')
                    ->label('')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(5)

                            ->schema([
                                TextEntry::make('sender.name')
                                    ->hiddenLabel()
                                    ->helperText(fn($record) => $record->sender->email)
                                    ->weight(FontWeight::Bold)

                                    ->icon('heroicon-s-user-circle')
                                    ->columnSpan(2)
                                ,
                                TextEntry::make('created_at')
                                    ->label('')
                                    ->columnSpan(2)

                                    ->formatStateUsing(
                                        fn($state) => $state->format('D, M-d-Y H:i A ') . '(' . $state->diffForHumans() . ')'
                                    ),
                                Actions::make([
                                    FAction::make('delete')
                                        ->action(function ($record, $livewire) {
                                            Message::destroy($record->id);
                                            $livewire->record->load('message');
                                            $livewire->refresh();
                                            Notification::make()
                                                ->title('Message deleted')
                                                ->success()
                                                ->send();
                                        })
                                        ->icon('heroicon-o-trash')
                                        ->color('danger')
                                        ->label('')
                                        ->requiresConfirmation()
                                        ->tooltip('Delete')
                                        ->modalHeading('Confirm deletion')
                                        ->modalSubheading('Are you sure you want to delete this message?')
                                        ->modalButton('Yes, Delete')
                                        ->modalIconColor('danger')
                                        ->modalIcon('heroicon-o-trash')
                                        ->iconButton()

                                ])->alignEnd()->columnSpan(1)


                            ]),
                        TextEntry::make('content')->label('')->html()

                    ])->columnSpanFull()
                ,
                Actions::make([
                    FAction::make('Reply')
                        ->label('Reply')
                        ->schema([
                            FRichEditor::make('content')
                                ->required()
                                ->autofocus()
                            // ->extraAttributes(['style' => 'height: 400px;'])
                        ])

                        ->action(function ($data, $livewire) {
                            Message::create([
                                'topic_id' => $livewire->record->id,
                                'sender_type' => auth()->user()->getMorphClass(),
                                'sender_id' => auth()->id(),
                                'content' => $data['content'],
                            ]);


                            $livewire->record->load('message');
                            $livewire->refresh();
                            Notification::make()
                                ->title('Reply sent successfully')
                                ->success()
                                ->send();


                        })

                ]),

            ]);
    }
}
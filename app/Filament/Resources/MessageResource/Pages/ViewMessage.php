<?php

namespace App\Filament\Resources\MessageResource\Pages;

use App\Filament\Resources\MessageResource;
use Filament\Actions\{CreateAction, Action as FAction};
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\{Action};
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Models\{Topic, Message};
use Filament\Forms\Components\{RichEditor as FRichEditor};
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\{TextEntry, RepeatableEntry, Group, Grid, RichEditor};
use Filament\Support\Enums\FontWeight;


class ViewMessage extends ViewRecord
{
    protected static string $resource = MessageResource::class;
    public function getTitle(): string
    {
        return 'View Conversation';
    }
    public function mount($record): void
    {
        parent::mount($record);

        Message::where('topic_id', $this->record->id)
            ->whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->update([
                'read_at' => now()
            ]);
    }
    public function getSubheading(): string
    {

        // return 'Subject: ' . optional($this->record->topic)->subject;
        return 'Subject: ' . $this->record->subject;
    }
    protected function getHeaderActions(): array
    {
        return [
            FAction::make('MarkUnread')->label('Mark as unread')
                ->color('gray')
                ->action(function () {
                    Message::where('topic_id', $this->record->id)
                        // ->where('receiver_id', auth()->id())
                        ->update(['read_at' => null]);
                    Notification::make()
                        ->title('Messages marked as unread')
                        ->success()
                        ->send();

                    return $this->redirect(MessageResource::getUrl('index'));

                })
                ->visible(
                    fn() =>
                    $this->record->receiver_id === auth()->id()
                    && $this->record->receiver_type === auth()->user()->getMorphClass()
                )


            ,
            FAction::make('Reply')->label('Reply')
                ->form([
                    FRichEditor::make('content')
                        ->required()
                ])
                ->action(function ($data) {
                    // $data['topic_id'] = $this->record->topic_id;
                    // $data['sender_type'] = $this->record->topic_id;
        
                    // $data['sender_id'] = auth()->id();
                    Message::create([
                        'topic_id' => $this->record->id,
                        'sender_type' => auth()->user()->getMorphClass(),
                        'sender_id' => auth()->id(),
                        'content' => $data['content'],
                    ]);
                }),
        ];
    }



    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            RepeatableEntry::make('message')
                ->label('')
                ->schema([
                    Grid::make(5)

                        ->schema([
                            TextEntry::make('sender.name')
                                ->label('')
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
                                Action::make('delete')
                                    ->action(function ($record) {
                                        Message::destroy($record->id);
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
                                    ->iconButton(),
                                Action::make('edit')
                                    ->iconButton()
                                    ->label('')
                                    ->icon('heroicon-o-pencil-square')
                                    ->color('gray')
                                    ->tooltip('Edit')
                            ])->alignEnd()->columnSpan(1)


                        ]),
                    TextEntry::make('content')->label('')->html()

                ])->columnSpanFull(),
            Actions::make([
                Action::make('CreateAction')
                    ->label('Reply')
                    ->form([
                        FRichEditor::make('content')
                            ->required()
                    ])
                    ->action(function ($data) {
                        Message::create([
                            'topic_id' => $this->record->id,
                            'sender_type' => auth()->user()->getMorphClass(),
                            'sender_id' => auth()->id(),
                            'content' => $data['content'],
                        ]);
                    }),

            ])
        ]);
    }
}
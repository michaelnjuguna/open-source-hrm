<?php

namespace App\Filament\Resources\MessageResource\Pages;

use App\Filament\Resources\MessageResource;
use Filament\Actions\{CreateAction, Action as FAction};
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\{Action};
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
                })
        ];
    }



    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            RepeatableEntry::make('message')
                ->label('')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('sender.name')
                            ->label('')
                            ->weight(FontWeight::Bold)

                            ->icon('heroicon-s-user-circle')

                        ,
                        TextEntry::make('created_at')
                            ->label('')
                            ->formatStateUsing(
                                fn($state) => $state->format('D, M-d-Y H:i A ') . '(' . $state->diffForHumans() . ')'
                            ),
                        // Action::make('delete')
                        //     ->icon('heroicon-s-trash')

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
                    })
            ])
        ]);
    }
}
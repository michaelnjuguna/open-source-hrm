<?php

namespace App\Filament\Resources\Messages\Pages;

use App\Filament\Resources\Messages\Schemas\MessageInfolist;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use App\Filament\Resources\Messages\MessageResource;
use Filament\Actions\{CreateAction, Action as FAction};
use Filament\Infolists\Components\Actions\{Action};
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Models\{Topic, Message};
use Filament\Forms\Components\{RichEditor as FRichEditor};
use Filament\Infolists;
use Filament\Infolists\Components\{TextEntry, RepeatableEntry, Group, Grid, RichEditor};
use Filament\Support\Enums\FontWeight;


class ViewMessage extends ViewRecord
{
    protected static string $resource = MessageResource::class;
    public $poll = '3s';
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
    public function refreshInfo()
    {
        $this->refresh();
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
                ->schema([
                    FRichEditor::make('content')
                        ->required()
                        ->autofocus()
                    // ->extraAttributes(['style' => 'height: 400px;'])
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
                    $this->record->load('message');
                    $this->refresh();
                    Notification::make()
                        ->title('Reply sent successfully')
                        ->success()
                        ->send();
                }),
            FAction::make('refresh')
                ->label(' ')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn() => $this->refresh()),
        ];
    }



    public function infolist(Schema $schema): Schema
    {
        return MessageInfolist::configure($schema);
    }
}
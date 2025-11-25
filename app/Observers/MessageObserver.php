<?php

namespace App\Observers;

use App\Filament\Resources\Messages\MessageResource;
use App\Models\{Message, Employee, User};
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {
        //
        $topic = $message->topic;
        $recipient = null;
        if ($message->sender->is($topic->creator)) {
            $recipient = $topic->receiver;
        } elseif ($message->sender->is($topic->receiver)) {
            $recipient = $topic->creator;
        }
        $url = MessageResource::getUrl('view', ['record' => $topic]);

        if ($recipient instanceof User && $message->sender instanceof Employee) {
            $url = str_replace('/portal', '', $url);
        } elseif ($recipient instanceof Employee && $message->sender instanceof User) {
            $parsed = parse_url(MessageResource::getUrl('view', ['record' => $topic]));
            $url = url('/portal' . $parsed['path']);
        }



        Notification::make()
            ->title('New message')
            ->body("{$topic->subject}")
            ->actions([
                Action::make('view')
                    ->url($url)
                    ->markAsRead()
                    ->close()
                    ->label('View Conversation'),
            ])
            ->info()
            ->sendToDatabase($recipient);

    }

    /**
     * Handle the Message "updated" event.
     */
    public function updated(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "deleted" event.
     */
    public function deleted(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "restored" event.
     */
    public function restored(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     */
    public function forceDeleted(Message $message): void
    {
        //
    }
}

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
        $recipient = Employee::find($topic->receiver_id);
        $sender = Employee::find($message->sender_id);

        $url = MessageResource::getUrl('view', ['record' => $topic]);

        if ($recipient->hasRole('admin') && $sender->hasRole('employee')) {
            $url = str_replace('/portal', '', $url);
        } elseif ($recipient->hasRole('employee') && $sender->hasRole('admin')) {
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

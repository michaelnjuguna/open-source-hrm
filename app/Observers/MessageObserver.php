<?php

namespace App\Observers;

use App\Filament\Resources\Messages\MessageResource;
use App\Models\{Message, Employee, User};
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
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
        $url = null;
        if ($recipient instanceof Employee) {
            $parsed = parse_url(MessageResource::getUrl('view', ['record' => $topic]));
            $url = url('/portal' . $parsed['path']);

        } else {

            $baseUrl = MessageResource::getUrl('view', ['record' => $topic]);
            $parsed = parse_url($baseUrl);
            $path = $parsed['path'];

            // Remove /portal prefix if present
            $path = preg_replace('#^/portal#', '', $path);
            $url = url($path);

        }
        // dd($recipient, $url);

        if ($message->sender->is($topic->creator)) {

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
        } elseif ($message->sender->is($topic->receiver)) {
            Notification::make()
                ->title('New message')
                ->body("{$topic->subject}")
                ->actions([
                    Action::make('view')
                        ->markAsRead()
                        ->url($url)
                        ->close()

                        ->label('View Conversation'),
                ])
                ->info()

                ->sendToDatabase($recipient);
        }
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

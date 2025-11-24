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

        $url = null;
        if (!$topic->receiver instanceof User && $topic->receiver instanceof Employee) {
            $parsed = parse_url(MessageResource::getUrl('view', ['record' => $topic]));
            $url = url('/portal' . $parsed['path']);

        } else {
            $url = MessageResource::getUrl('view', ['record' => $topic]);

        }
        // dd($topic->receiver::class, $url);

        if ($message->sender->is($topic->creator)) {

            Notification::make()
                ->title('New message')
                ->body("{$topic->subject}")
                ->actions([
                    Action::make('view')
                        ->url($url)
                        ->label('View Conversation'),
                ])
                ->info()
                ->sendToDatabase($topic->receiver);
        } elseif ($message->sender->is($topic->receiver)) {
            Notification::make()
                ->title('New message')
                ->body("{$topic->subject}")
                ->actions([
                    Action::make('view')
                        ->url($url)
                        ->label('View Conversation'),
                ])
                ->info()
                ->sendToDatabase($topic->creator);
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

<?php

namespace App\Filament\Resources\Messages\Pages;

use App\Filament\Resources\Messages\MessageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\{Message, Topic, User, Employee};


class CreateMessage extends CreateRecord
{

    protected static string $resource = MessageResource::class;
    protected function handleRecordCreation(array $data): topic
    {

        // $topic = null;
        foreach ($data['receiver'] as $receiverId) {

            $topic = Topic::create([
                'subject' => $data['subject'],
                'creator_id' => auth()->id(),
                'receiver_id' => $receiverId,
            ]);
            Message::create(
                [
                    'topic_id' => $topic->id,
                    'sender_id' => auth()->id(),
                    'content' => $data['content'],
                    'read_at' => null,
                ]
            );
        }
        return $topic;

    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}

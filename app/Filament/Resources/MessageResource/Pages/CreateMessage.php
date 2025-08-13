<?php

namespace App\Filament\Resources\MessageResource\Pages;

use App\Filament\Resources\MessageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\{Message, Topic, User, Employee};


class CreateMessage extends CreateRecord
{

    protected static string $resource = MessageResource::class;
    protected function handleRecordCreation(array $data): topic
    {

        $topic = null;
        foreach ($data['receiver_id'] as $receiverId) {

            $receiverType = Employee::where('id', $receiverId)->exists() ? Employee::class : User::class;
            $topic = Topic::create([
                'subject' => $data['Subject'],
                'creator_type' => auth()->user() instanceof Employee ? Employee::class : User::class,
                'creator_id' => auth()->id(),
                'receiver_type' => $receiverType,
                'receiver_id' => $receiverId,
            ]);
            // TODO: Create messages
            $data['topic_id'] = $topic->id;
            $data['sender_type'] = auth()->user() instanceof Employee ? Employee::class : User::class;
            $data['sender_id'] = auth()->id();
            $data['read_at'] = null;
            $data['content'] = $data['content'] ?? '';
            $message = Message::create($data);

        }
        return $topic;

    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}

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

        $topic = null;
        foreach ($data['receiver_id'] as $receiverId) {



            if (str_starts_with($receiverId, 'Employee_')) {
                $actualId = str_replace('Employee_', '', $receiverId);
                $receiverType = Employee::class;
            } else {
                $actualId = str_replace('User_', '', $receiverId);
                $receiverType = User::class;
            }
            $topic = Topic::create([
                'subject' => $data['subject'],
                'creator_type' => auth()->user() instanceof Employee ? Employee::class : User::class,
                'creator_id' => auth()->id(),
                'receiver_type' => $receiverType,
                'receiver_id' => $actualId,
            ]);
            Message::create(
                [

                    'topic_id' => $topic->id,
                    'sender_type' => auth()->user() instanceof Employee ? Employee::class : User::class,
                    'sender_id' => auth()->id(),
                    'read_at' => null,
                    'content' => $data['content']
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

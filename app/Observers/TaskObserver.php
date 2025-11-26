<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\Task;
use App\Filament\Pages\TaskBoard;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {

        $this->sendTaskNotification($task, 'New Task Assigned');

    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        //
        $this->sendTaskNotification($task, 'Task Updated');
    }

    private function sendTaskNotification(Task $task, string $title): void
    {
        if ($task->assignee) {
            $recipient = $task->assignee;
            $sender = Auth::user();
            $url = TaskBoard::getUrl();
            if ($recipient instanceof Employee && $sender instanceof User) {
                $parsedUrl = parse_url(TaskBoard::getUrl());
                $url = url('/portal' . $parsedUrl['path']);
            } elseif ($sender instanceof Employee && $recipient instanceof User) {
                $url = str_replace('/portal', '', $url);
            }
            Notification::make()
                ->title($title)
                ->body("{$task->title}")
                ->actions([
                    Action::make('view')
                        ->url($url)
                        ->markAsRead()
                        ->label('View Task'),
                ])
                ->info()
                ->sendToDatabase($task->assignee);
        }
    }
    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}

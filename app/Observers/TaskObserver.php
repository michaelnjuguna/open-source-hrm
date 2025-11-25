<?php

namespace App\Observers;

use App\Models\Task;
use App\Filament\Pages\TaskBoard;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        //
        if ($task->assignee) {
            Notification::make()
                ->title('New Task Assigned')
                ->body("{$task->title}")
                ->actions([
                    Action::make('view')
                        ->url(TaskBoard::getUrl())
                        ->markAsRead()
                        ->label('View Task'),
                ])
                ->success()
                ->sendToDatabase($task->assignee);
        }

    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        //
        if ($task->assignee) {
            Notification::make()
                ->title('Task Updated')
                ->body("{$task->title}")
                ->actions([
                    Action::make('view')
                        ->url(TaskBoard::getUrl())
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

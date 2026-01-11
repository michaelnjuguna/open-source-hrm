<?php

namespace App\Observers;

use App\Models\Department;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use App\Filament\Resources\Departments\DepartmentResource;
class DepartmentObserver
{
    /**
     * Handle the Department "created" event.
     */
    public function created(Department $department): void
    {
        //
        $this->sendManagerNotification($department);
    }

    /**
     * Handle the Department "updated" event.
     */
    public function updated(Department $department): void
    {
        //
        $this->sendManagerNotification($department);
    }
    private function sendManagerNotification(Department $department): void
    {
        if (!$department->manager) {
            return;
        }
        if ($department->manager) {
            Notification::make()
                ->title('You have been assigned a department')
                ->body($department->name)
                ->info()
                ->sendToDatabase($department->manager);
        }
    }
    /**
     * Handle the Department "deleted" event.
     */
    public function deleted(Department $department): void
    {
        //
    }

    /**
     * Handle the Department "restored" event.
     */
    public function restored(Department $department): void
    {
        //
    }

    /**
     * Handle the Department "force deleted" event.
     */
    public function forceDeleted(Department $department): void
    {
        //
    }
}

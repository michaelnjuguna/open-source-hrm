<?php

namespace App\Observers;

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeObserver
{
    /**
     * Handle the Employee "created" event.
     */
    public function creating(Employee $employee): void
    {
        if (empty($employee->password)) {
            $employee->password = Hash::make($employee->email);
        }
    }
    public function created(Employee $employee): void
    {
        //

    }

    /**
     * Handle the Employee "updated" event.
     */
    public function updated(Employee $employee): void
    {
        //
    }

    /**
     * Handle the Employee "deleted" event.
     */
    public function deleted(Employee $employee): void
    {
        //
    }

    /**
     * Handle the Employee "restored" event.
     */
    public function restored(Employee $employee): void
    {
        //
    }

    /**
     * Handle the Employee "force deleted" event.
     */
    public function forceDeleted(Employee $employee): void
    {
        //
    }
}

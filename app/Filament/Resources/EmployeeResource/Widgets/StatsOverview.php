<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public function redirectToEmployees()
    {
        return redirect()->to('/employees');
    }

    protected function getStats(): array
    {
        $commonAttributes = [
            'class' => 'cursor-pointer',
            'wire:click' => "redirectToEmployees()",
        ];
        return [
            //
            Stat::make('Total Employees', \App\Models\Employee::count())
                ->label('Total Employees')
                ->color('primary')
                ->description('Total number of employees in the organization')
                ->extraAttributes($commonAttributes)
                ->icon('heroicon-o-user-group'),
            Stat::make('Active Employees', \App\Models\Employee::where('is_active', true)->count())
                ->color('success')
                ->label('Active Employees')
                ->extraAttributes($commonAttributes)
                ->description('Number of employees currently active employees')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Inactive Employees', \App\Models\Employee::where('is_active', false)->count())
                ->label('Inactive Employees')
                ->description('Number of employees who are no longer active')
                ->color('danger')
                ->extraAttributes($commonAttributes)
                ->icon('heroicon-o-x-circle'),
        ];
    }
}

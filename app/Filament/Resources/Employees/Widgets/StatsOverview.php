<?php

namespace App\Filament\Resources\Employees\Widgets;

use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public function redirectToEmployees()
    {
        return redirect()->to('employees?tableFilters[is_active][isActive]=false&tableFilters[is_inactive][isActive]=false');
    }
    public function redirectToInactiveEmployees()
    {
        return redirect()->to('employees?tableFilters[is_active][isActive]=false&tableFilters[is_inactive][isActive]=true');
    }
    public function redirectToActiveEmployees()
    {
        return redirect()->to('employees?tableFilters[is_active][isActive]=true&tableFilters[is_inactive][isActive]=false');
    }

    protected function getStats(): array
    {
        $commonAttributes = [
            'class' => 'cursor-pointer',
            'wire:click' => "redirectToEmployees()",
        ];

        return [
            //
            Stat::make('Total Employees', Employee::count())
                ->label('Total Employees')
                ->color('primary')
                ->description('Total number of employees in the organization')
                ->extraAttributes($commonAttributes)
                ->icon('heroicon-o-user-group'),
            Stat::make('Active Employees', Employee::where('is_active', true)->count())
                ->color('success')
                ->label('Active Employees')
                ->extraAttributes(
                    [
                        'class' => 'cursor-pointer',
                        'wire:click' => "redirectToActiveEmployees()"
                    ]
                )
                ->description('Number of employees currently active employees')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Inactive Employees', Employee::where('is_active', false)->count())
                ->label('Inactive Employees')
                ->description('Number of employees who are no longer active')
                ->color('danger')
                ->extraAttributes(
                    [
                        'class' => 'cursor-pointer',
                        'wire:click' => "redirectToInactiveEmployees()"
                    ]
                )
                ->icon('heroicon-o-x-circle'),
        ];
    }
}

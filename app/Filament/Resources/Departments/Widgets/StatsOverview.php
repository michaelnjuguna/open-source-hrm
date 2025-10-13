<?php

namespace App\Filament\Resources\Departments\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Department;

class StatsOverview extends BaseWidget
{
    public function redirectToDepartments()
    {
        return redirect()->to('/departments');
    }
    public function redirectToAdmins()
    {
        return redirect()->to('/admins');
    }

    protected function getStats(): array
    {
        return [
            //
            Stat::make('Total Departments', Department::count())
                ->label('Total Departments')
                ->color('primary')
                ->description('Total number of departments in the organization')
                ->icon('heroicon-o-rectangle-group')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "redirectToDepartments()",
                ])
            // ->url(route('filament.admin.resources.departments.index')),
            ,
            Stat::make('HR Admins', User::Count())
                ->label('Total HR Admins')
                ->color('success')
                ->description('Total number of HR admins in the organization')
                ->icon('heroicon-o-user-group')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "redirectToAdmins()",
                ])




        ];
    }
}

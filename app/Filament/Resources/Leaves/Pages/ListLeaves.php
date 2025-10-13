<?php

namespace App\Filament\Resources\Leaves\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Leaves\LeaveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaves extends ListRecords
{
    protected static string $resource = LeaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

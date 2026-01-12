<?php

namespace App\Filament\Resources\Shield\Permissions\Pages;

use App\Filament\Resources\Shield\Permissions\PermissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
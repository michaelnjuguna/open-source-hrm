<?php

namespace App\Filament\Resources\Shield\Permissions\Pages;

use App\Filament\Resources\Shield\Permissions\PermissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
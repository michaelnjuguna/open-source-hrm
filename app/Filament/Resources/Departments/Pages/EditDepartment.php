<?php

namespace App\Filament\Resources\Departments\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Departments\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepartment extends EditRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

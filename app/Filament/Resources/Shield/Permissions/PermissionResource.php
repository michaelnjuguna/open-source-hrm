<?php

namespace App\Filament\Resources\Shield\Permissions;

use App\Filament\Resources\Shield\Permission\Pages\EditPermission;
use App\Filament\Resources\Shield\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Shield\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Shield\Permissions\Schemas\PermissionForm;
use App\Filament\Resources\Shield\Permissions\Tables\PermissionTable;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Permission;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan Sistem';

    protected static ?string $label = 'Izin Akses';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermissionTable::configure($table);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'edit' => EditPermission::route('/{record}/edit'),
        ];
    }
}
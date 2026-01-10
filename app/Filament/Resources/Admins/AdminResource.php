<?php

namespace App\Filament\Resources\Admins;

use App\Filament\Resources\Admins\Schemas\AdminTable;
use App\Filament\Resources\Admins\Schemas\AdminForm;
use Filament\Schemas\Schema;

use App\Filament\Resources\Admins\Pages\ListAdmins;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Models\Employee;



class AdminResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $label = 'Admin';
    protected static ?string $pluralLabel = 'Admins';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';
    protected static string|\UnitEnum|null $navigationGroup = 'Organization';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return AdminForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdminTable::configure($table);
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
            'index' => ListAdmins::route('/'),
            // 'create' => Pages\CreateAdmin::route('/create'),
            // 'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}

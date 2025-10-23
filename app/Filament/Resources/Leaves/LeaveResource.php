<?php

namespace App\Filament\Resources\Leaves;

use App\Filament\Resources\Leaves\Schemas\LeaveForm;
use App\Filament\Resources\Leaves\Schemas\LeaveTable;
use Filament\Schemas\Schema;
use App\Filament\Resources\Leaves\Pages\ListLeaves;
use App\Models\Leave;
use Filament\Resources\Resource;
use Filament\Tables\Table;
class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-minus';
    protected static string|\UnitEnum|null $navigationGroup = 'HR Management';
    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Leave Requests';

    public static function form(Schema $schema): Schema
    {
        return LeaveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveTable::configure($table);
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
            'index' => ListLeaves::route('/'),
            // 'create' => Pages\CreateLeave::route('/create'),
            // 'view' => Pages\ViewLeave::route('/{record}'),
            // 'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}

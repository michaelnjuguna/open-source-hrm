<?php

namespace App\Filament\Resources\Admins;

use App\Filament\Resources\Admins\Schemas\AdminForm;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Admins\Pages\ListAdmins;
use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class AdminResource extends Resource
{
    protected static ?string $model = User::class;
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
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    ViewAction::make(),
                    DeleteAction::make()
                        ->hidden(fn($record) => auth()->id() === $record->id)
                    ,
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($action, $records) {
                            if ($records->contains(fn($record) => $record->id === auth()->id())) {
                                Notification::make()->title('You cannot delete your own account, try again')
                                    ->warning()->send();
                                $action->cancel();
                            }
                        })
                    ,
                ]),
            ]);
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

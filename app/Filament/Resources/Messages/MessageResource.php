<?php

namespace App\Filament\Resources\Messages;

use App\Filament\Resources\Messages\Schemas\MessageForm;
use Filament\Schemas\Schema;
use App\Filament\Resources\Messages\Pages\ListMessages;
use App\Filament\Resources\Messages\Pages\CreateMessage;
use App\Filament\Resources\Messages\Pages\ViewMessage;
use App\Models\{Message, Topic, User, Employee};
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Resources\Messages\Schemas\MessageTable;

class MessageResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static string|\BackedEnum|null $navigationIcon = "heroicon-o-envelope";

    protected static string|\BackedEnum|null $activeNavigationIcon = "heroicon-o-envelope-open";
    protected static ?string $navigationLabel = "Inbox";
    protected static ?string $label = "Message";
    protected static ?string $pluralModelLabel = "Messages";

    protected static string|\UnitEnum|null $navigationGroup = "Work space";
    // protected static ?string $navigationBadgeTooltip = "Unread messages";

    // public static function getNavigationBadge(): ?string
    // {
    //     $user = Auth::user();
    //     if (!$user) {
    //         return null; // Return null if no user is authenticated
    //     }

    //     $unreadMessages = Message::where('receiver_id', $user->id)
    //         ->whereNull('read_at')->count();
    //     return $unreadMessages;
    // }

    public static function form(Schema $schema): Schema
    {
        return MessageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {

        return MessageTable::configure($table);
    }



    public static function getPages(): array
    {
        return [
            "index" => ListMessages::route("/"),
            "create" => CreateMessage::route("/create"),
            "view" => ViewMessage::route("/{record}"),
            // "edit" => Pages\EditMessage::route("/{record}/edit"),
        ];
    }
}

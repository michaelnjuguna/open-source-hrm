<?php

namespace App\Filament\Resources\Messages;

use App\Filament\Resources\Messages\Schemas\MessageForm;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Messages\Pages\ListMessages;
use App\Filament\Resources\Messages\Pages\CreateMessage;
use App\Filament\Resources\Messages\Pages\ViewMessage;
// use App\Filament\Resources\MessageResource\Pages;
// use App\Filament\Resources\MessageResource\RelationManagers;
use Filament\Forms\Components\RichEditor;
use Filament\Support\Enums\FontWeight;
use App\Models\{Message, Topic, User, Employee};
use Filament\Tables\Actions\{ViewAction, EditAction, ActionGroup, DeleteAction};
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;

use Filament\Tables\Filters\Filter;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\{TextInput, Textarea, Select, DateTimePicker};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Message\Schemas\MessageTable;

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

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        if (!$user) {
            return null; // Return null if no user is authenticated
        }

        $receiverType = get_class($user);

        $unreadCount = Message::where("read_at", null)
            ->join("topics", "messages.topic_id", "=", "topics.id")
            ->where(function ($query) use ($user, $receiverType) {
                // Case 1: User is the receiver of the topic
                $query
                    ->where(function ($q) use ($user, $receiverType) {
                    $q->where("topics.receiver_id", $user->id)
                        ->where("topics.receiver_type", $receiverType)
                        ->whereNot(function ($q2) use ($user) {
                            $q2->where(
                                "messages.sender_id",
                                $user->id,
                            )->where(
                                    "messages.sender_type",
                                    get_class($user),
                                );
                        });
                })
                    // Case 2: User is the sender of the topic
                    ->orWhere(function ($q) use ($user, $receiverType) {
                    $q->where("topics.creator_id", $user->id)
                        ->where("topics.creator_type", $receiverType)
                        ->whereNot(function ($q2) use ($user) {
                            $q2->where(
                                "messages.sender_id",
                                $user->id,
                            )->where(
                                    "messages.sender_type",
                                    get_class($user),
                                );
                        });
                });
            })
            ->count();

        return $unreadCount > 0 ? (string) $unreadCount : null;
    }

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

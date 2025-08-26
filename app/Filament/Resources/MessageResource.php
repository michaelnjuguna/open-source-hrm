<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Filament\Resources\MessageResource\RelationManagers;
use Filament\Forms\Components\RichEditor;
use Filament\Support\Enums\FontWeight;
use App\Models\{Message, Topic, User, Employee};
use Filament\Tables\Actions\{ViewAction, EditAction, ActionGroup};
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;

use Filament\Tables\Filters\Filter;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\{
    TextInput,
    Textarea,
    Select,
    DateTimePicker,

};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
// TODO: Filters for sent and received messages
class MessageResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $activeNavigationIcon = 'heroicon-o-envelope-open';
    protected static ?string $navigationLabel = 'Inbox';
    protected static ?string $label = 'Message';
    protected static ?string $pluralModelLabel = 'Messages';

    protected static ?string $navigationGroup = 'Work space';
    protected static ?string $navigationBadgeTooltip = 'Unread messages';

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        if (!$user) {
            return null; // Return null if no user is authenticated
        }

        $receiverType = get_class($user);

        $unreadCount = Message::where('read_at', null)
            ->join('topics', 'messages.topic_id', '=', 'topics.id')
            ->where(function ($query) use ($user, $receiverType) {
                // Case 1: User is the receiver of the topic
                $query->where(function ($q) use ($user, $receiverType) {
                    $q->where('topics.receiver_id', $user->id)
                        ->where('topics.receiver_type', $receiverType)
                        ->whereNot(function ($q2) use ($user) {
                            $q2->where('messages.sender_id', $user->id)
                                ->where('messages.sender_type', get_class($user));
                        });
                })
                    // Case 2: User is the sender of the topic
                    ->orWhere(function ($q) use ($user, $receiverType) {
                    $q->where('topics.creator_id', $user->id)
                        ->where('topics.creator_type', $receiverType)
                        ->whereNot(function ($q2) use ($user) {
                            $q2->where('messages.sender_id', $user->id)
                                ->where('messages.sender_type', get_class($user));
                        });
                });
            })
            ->count();

        return $unreadCount > 0 ? (string) $unreadCount : null;
    }




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('Subject')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->label('Subject'),
                Select::make('receiver_id')
                    ->label('receiver')
                    ->required()
                    ->multiple()

                    ->options(
                        collect()
                            ->merge(
                                Employee::all()->mapWithKeys(fn($employee) => [
                                    'Employee_' . $employee->id => 'Employee: ' . $employee->name

                                ])
                            )
                            ->merge(
                                User::all()->mapWithKeys(fn($user) => [
                                    'User_' . $user->id => 'Admin: ' . $user->name

                                ])
                            )

                    )
                    ->columnSpanFull()
                    ->searchable([
                        'first_name',
                        'last_name',
                    ]),
                Select::make('receiver_type')
                    ->options([
                        Employee::class,
                        User::class
                    ])
                    ->hidden()
                ,
                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull()
            ]);
    }



    public static function table(Table $table): Table
    {

        return $table


            ->modifyQueryUsing(function ($query) {
                $userId = Auth::id();
                return $query->where(function ($query) use ($userId) {
                    $query->where('creator_id', $userId)->orWhere('receiver_id', $userId);
                });
            })
            ->columns([
                //
                TextColumn::make('creator.name')
                    ->sortable()
                    ->searchable()
                    ->label('Sender')
                    ->weight(function ($record) {
                        return $record->message()->whereNull('read_at')->exists() ? FontWeight::Bold : FontWeight::Light;
                    })
                    ->color(function ($record) {
                        return $record->message()->whereNull('read_at')->exists() ? 'light' : 'gray';
                    })
                ,
                TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->limit(20)
                    ->color(color: function ($record) {
                        return $record->message()->whereNull('read_at')->exists() ? 'light' : 'gray';
                    })
                    ->weight(function ($record) {
                        return $record->message()->whereNull('read_at')->exists() ? FontWeight::Bold : FontWeight::Light;
                    })
                // ->extraAttributes(function ($record) {
                //     return $record->message()->whereNull('read_at')->exists() ? ['class' => 'font-bold text-blue-500'] : [];
                // })
                ,
                TextColumn::make('created_at')
                    ->label('Created at')
                    // ->formatStateUsing(fn($state) =>$state->format('d-m-Y H:i A') . '(' $state->diffForHumans() .')')
                    ->formatStateUsing(fn($state) => $state->format('D, M-d-Y H:i A'))
                    ->weight(function ($record) {
                        return $record->message()->whereNull('read_at')->exists() ? FontWeight::Bold : FontWeight::Light;
                    })
                    ->color(function ($record) {
                        return $record->message()->whereNull('read_at')->exists() ? 'light' : 'gray';
                    })

            ])


            ->actions([
                ActionGroup::make([

                    EditAction::make(),
                    ViewAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListMessages::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            'view' => Pages\ViewMessage::route('/{record}'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
}

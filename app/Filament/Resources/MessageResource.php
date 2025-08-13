<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Filament\Resources\MessageResource\RelationManagers;
use App\Models\{Message, Topic, User, Employee};
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\{ViewAction, EditAction, ActionGroup};
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;
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

class MessageResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $activeNavigationIcon = 'heroicon-o-envelope-open';
    protected static ?string $navigationLabel = 'Inbox';
    protected static ?string $label = 'Message';
    protected static ?string $pluralModelLabel = 'Messages';

    protected static ?string $navigationGroup = 'Work space';
    protected static ?string $navigationBadgeTooltip = 'The number of unread messages';

    public static function getNavigationBadge(): ?string
    {
        return (string) Topic::where('receiver_id', Auth::id())
            ->where('receiver_type', Auth::user() instanceof \App\Models\Employee ? \App\Models\Employee::class : \App\Models\User::class)
            ->whereHas('message', function ($query) {
                $query->whereNull('read_at');
            })
            ->count();
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
                                Employee::all()->map(fn($employee) => [
                                    $employee->id => 'Employee: ' . $employee->name,
                                    Employee::class
                                ])
                            )
                            ->merge(
                                User::all()->map(fn($user) => [
                                    $user->id => 'Admin: ' . $user->name,
                                    User::class
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
            ->columns([
                //
                TextColumn::make('creator.name')
                    ->sortable()
                    ->searchable()
                    ->label('Sender'),
                TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->limit(20),
                TextColumn::make('created_at')
                    ->label('Created at')
                    // ->formatStateUsing(fn($state) =>$state->format('d-m-Y H:i A') . '(' $state->diffForHumans() .')')
                    ->formatStateUsing(fn($state) => $state->format('D, M-d-Y H:i A'))

            ])
            ->filters([
                //
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

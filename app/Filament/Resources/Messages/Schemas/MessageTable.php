<?php
namespace App\Filament\Resources\Message\Schemas;

use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\FontWeight;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
class MessageTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('1s')
            ->modifyQueryUsing(function ($query) {
                $userId = Auth::id();
                return $query->where(function ($query) use ($userId) {
                    $query
                        ->where("creator_id", $userId)
                        ->orWhere("receiver_id", $userId);
                });
            })
            ->columns([
                //
                TextColumn::make("creator.name")
                    ->sortable()
                    ->searchable()
                    ->label("Sender")
                    ->weight(function ($record) {
                        return $record
                            ->message()
                            ->whereNull("read_at")
                            ->exists()
                            ? FontWeight::Bold
                            : FontWeight::Light;
                    })
                    ->color(function ($record) {
                        return $record
                            ->message()
                            ->whereNull("read_at")
                            ->exists()
                            ? "light"
                            : "gray";
                    }),
                TextColumn::make("subject")
                    ->label("Subject")
                    ->searchable()
                    ->limit(20)
                    ->color(
                        color: function ($record) {
                            return $record
                                ->message()
                                ->whereNull("read_at")
                                ->exists()
                                ? "light"
                                : "gray";
                        },
                    )
                    ->weight(function ($record) {
                        return $record
                            ->message()
                            ->whereNull("read_at")
                            ->exists()
                            ? FontWeight::Bold
                            : FontWeight::Light;
                    }),

                TextColumn::make("created_at")
                    ->label("Created at")

                    ->formatStateUsing(
                        fn($state) => $state->format("D, M-d-Y H:i A"),
                    )
                    ->weight(function ($record) {
                        return $record
                            ->message()
                            ->whereNull("read_at")
                            ->exists()
                            ? FontWeight::Bold
                            : FontWeight::Light;
                    })
                    ->color(function ($record) {
                        return $record
                            ->message()
                            ->whereNull("read_at")
                            ->exists()
                            ? "light"
                            : "gray";
                    }),
            ])

            ->recordActions([
                \Filament\Actions\ActionGroup::make([

                    \Filament\Actions\ViewAction::make(),
                    \Filament\Actions\DeleteAction::make(

                    )
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort("created_at", "desc");
    }
}
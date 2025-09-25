<?php

namespace App\Filament\Resources\MessageResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\{MessageResource};
use App\Models\{Employee, User};
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;

class ListMessages extends ListRecords
{
    protected static string $resource = MessageResource::class;

    public function getTabs(): array
    {
        $user = Auth::user();
        $type = $user instanceof Employee ? Employee::class : User::class;
        return [
            'all' => Tab::make()

            ,
            'Sent' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query) => $query
                        ->where('creator_id', $user->id)
                        ->where('creator_type', $type)
                ),
            'Received' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query) => $query
                        ->where('receiver_id', $user->id)
                        ->where('receiver_type', $type)
                )
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

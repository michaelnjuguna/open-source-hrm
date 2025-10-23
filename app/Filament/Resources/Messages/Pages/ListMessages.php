<?php

namespace App\Filament\Resources\Messages\Pages;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Messages\{MessageResource};
use App\Models\{Employee, User};
use Filament\Actions;
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
            CreateAction::make(),
            Actions\Action::make('refresh')
                ->label(' ')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn() => $this->refresh()),
        ];
    }
}

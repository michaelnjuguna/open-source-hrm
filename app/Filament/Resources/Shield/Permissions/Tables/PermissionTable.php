<?php

namespace App\Filament\Resources\Shield\Permissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PermissionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Izin')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->fontFamily(FontFamily::Mono) // Font koding
                    ->copyable()
                    ->icon('heroicon-m-key'),

                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('gray'),
                
                TextColumn::make('roles_count')
                    ->label('Digunakan di')
                    ->counts('roles')
                    ->suffix(' Peran')
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
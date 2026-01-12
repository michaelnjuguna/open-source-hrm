<?php

namespace App\Filament\Resources\Shield\Permissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Izin')
                    ->description('Buat identifier unik untuk izin akses sistem.')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Izin')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->placeholder('Contoh: delete_employees')
                                ->prefixIcon('heroicon-m-key')
                                ->helperText('Gunakan huruf kecil dan underscore (snake_case).'),

                            TextInput::make('guard_name')
                                ->label('Guard')
                                ->required()
                                ->default('web')
                                ->maxLength(255)
                                ->prefixIcon('heroicon-m-shield-check'),
                        ]),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Shield\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Peran')
                    ->description('Tentukan nama peran dan guard.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Peran')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->placeholder('Contoh: HR Manager')
                                ->prefixIcon('heroicon-m-user-group'),

                            TextInput::make('guard_name')
                                ->label('Guard')
                                ->required()
                                ->default('web')
                                ->maxLength(255)
                                ->prefixIcon('heroicon-m-shield-check'),
                        ]),
                    ]),

                // Bagian untuk mencentang Permission
                Section::make('Hak Akses (Permissions)')
                    ->description('Pilih izin apa saja yang dimiliki oleh peran ini.')
                    ->icon('heroicon-o-finger-print')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('') // Label kosong agar lebih bersih
                            ->relationship('permissions', 'name') // Relasi Many-to-Many
                            ->searchable() // Bisa cari nama izin
                            ->bulkToggleable() // Tombol Select All / Deselect All
                            ->columns(2) // Tampilan 2 kolom
                            ->gridDirection('row')
                            ->helperText('Centang izin yang diperbolehkan untuk peran ini.'),
                    ]),
            ]);
    }
}

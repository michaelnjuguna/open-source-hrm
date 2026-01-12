<?php
namespace App\Filament\Resources\Admins\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Promosikan Pegawai')
                    ->description('Pilih pegawai yang sudah terdaftar untuk dijadikan Administrator.')
                    ->icon('heroicon-o-user-plus')
                    ->schema([
                        Select::make('employee_id')
                            ->label('Cari Pegawai')
                            ->placeholder('Ketik nama pegawai...')
                            ->options(function () {
                                // Hanya tampilkan pegawai yang BELUM jadi admin
                                return Employee::query()
                                    ->whereDoesntHave('roles', fn ($q) => $q->where('name', 'admin'))
                                    ->pluck('first_name', 'id') // Sesuaikan 'first_name' jika ada accessor full_name
                                    ->map(fn ($name, $id) => Employee::find($id)->name ?? $name);
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Pegawai yang dipilih akan mendapatkan hak akses penuh sebagai Admin.'),
                    ])
            ]);
    }
}
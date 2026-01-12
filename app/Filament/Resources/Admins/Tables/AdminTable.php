<?php

namespace App\Filament\Resources\Admins\Tables;

use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;

class AdminTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Employee::query()->latest()->whereHas('roles', function ($query) {
                    return $query->where('name', 'admin');
                })
            )
            ->columns([
                TextColumn::make('no')
                    ->label('No.')
                    ->rowIndex()
                    ->color('gray')
                    ->width(50),

                TextColumn::make('employee_code')
                    ->label('NIP')
                    ->searchable()
                    ->sortable()
                    ->copyable() // Bisa dicopy dengan 1 klik
                    ->fontFamily(FontFamily::Mono)
                    ->weight(FontWeight::Bold),
                TextColumn::make('name') // Asumsi ada accessor ini
                    ->label('Nama Admin')
                    ->weight(FontWeight::Bold)
                    ->searchable(['first_name', 'last_name']),

                TextColumn::make('department.name')
                    ->label('Departemen Asal')
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('remove_admin')
                    ->label('Cabut Akses Admin')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Akses Admin')
                    ->modalDescription('Apakah Anda yakin? Pegawai ini akan kembali menjadi pegawai biasa dan tidak bisa login ke panel admin.')
                    ->action(function (Employee $record) {
                        // Cek jangan sampai hapus diri sendiri
                        if ($record->id === auth()->id()) {
                            Notification::make()
                                ->danger()->title('Gagal')->body('Tidak bisa mencabut akses akun sendiri!')->send();
                            return;
                        }

                        // Hapus role admin
                        $record->removeRole('admin');

                        Notification::make()
                            ->success()->title('Sukses')->body('Akses admin telah dicabut.')->send();
                    })
                    ->hidden(fn(Employee $record): bool => $record->id === auth()->id()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($action, $records) {
                            if ($records->contains(fn($record) => $record->id === auth()->id())) {
                                Notification::make()->title('You cannot delete your own account, try again')
                                    ->warning()->send();
                                $action->cancel();
                            }
                        }),
                ]),
            ]);
    }
}

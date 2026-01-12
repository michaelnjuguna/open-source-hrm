<?php

namespace App\Filament\Resources\Admins\Pages;

use App\Filament\Resources\Admins\AdminResource;
use App\Models\Employee;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;
    
    // Kita override fungsi ini agar tidak membuat record baru,
    // tapi mengambil record lama dan memberinya Role.
    protected function handleRecordCreation(array $data): Model
    {
        // 1. Cari pegawai berdasarkan ID yang dipilih di form
        $employee = Employee::find($data['employee_id']);

        // 2. Berikan Role Admin (pastikan spatie/laravel-permission sudah terinstall)
        $employee->assignRole('admin');

        // 3. Kembalikan model pegawai tersebut
        return $employee;
    }

    // Ubah pesan sukses agar lebih relevan
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Akses Diberikan')
            ->body('Pegawai berhasil dipromosikan menjadi Admin.');
    }

    public function canCreateAnother(): bool
    {
        return false;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}

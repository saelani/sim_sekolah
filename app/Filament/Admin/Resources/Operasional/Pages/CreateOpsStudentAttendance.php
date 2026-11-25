<?php

namespace App\Filament\Admin\Resources\Operasional\Pages;

use App\Filament\Admin\Resources\Operasional\OpsStudentAttendanceResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Teacher;
use App\Models\SchoolClass;
use Filament\Notifications\Notification;

class CreateOpsStudentAttendance extends CreateRecord
{
    protected static string $resource = OpsStudentAttendanceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        
        // 2. Ambil data guru berdasarkan user yang sedang login
        $teacher = $user ? Teacher::where('user_id', $user->id)->first() : null;

        // 3. Ambil data kelas dari tabel acad_classes di mana teacher_id adalah guru yang login
        $schoolClass = $teacher ? SchoolClass::where('teacher_id', $teacher->id)->first() : null;

        // 4. Validasi apakah guru tersebut memiliki kelas binaan
        if ($teacher && $schoolClass) {
            $data['class_id'] = $schoolClass->id; // Ambil ID dari kelas
            $data['teacher_id'] = $teacher->id;
        } else {
            Notification::make()
                ->title('Gagal Menyimpan')
                ->body('Akun Anda tidak terdaftar sebagai Wali Kelas atau belum memiliki kelas binaan di database.')
                ->danger()
                ->send();

            throw new \Exception('Akses ditolak: User yang login bukan merupakan Wali Kelas yang valid.');
        }

        $data['user_id'] = $user->id;

        return $data;
    }
}

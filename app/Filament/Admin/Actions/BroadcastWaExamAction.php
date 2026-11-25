<?php

namespace App\Filament\Admin\Actions;

use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Services\WhatsAppService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class BroadcastWaExamAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'broadcastWaExam';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Broadcast Token WA')
            ->icon('heroicon-o-paper-airplane')
            ->color('success')
            ->modalHeading('Broadcast Token Ujian via WhatsApp')
            ->modalDescription('Pilih kelas tujuan untuk mengirim jadwal pelaksanaan dan token ujian.')
            ->schema([
                Select::make('class_id')
                    ->label('Pilih Kelas Tujuan')
                    ->options(fn () => SchoolClass::query()->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload()
                    ->required(),
            ])
            ->action(function (array $data, Exam $record) {
                $enrollments = Enrollment::with('student')
                    ->where('class_id', $data['class_id'])
                    ->get();

                if ($enrollments->isEmpty()) {
                    Notification::make()
                        ->title('Siswa Tidak Ditemukan')
                        ->body('Tidak ada siswa yang terdaftar di kelas yang dipilih.')
                        ->warning()
                        ->send();

                    return;
                }

                $successCount = 0;
                $failedCount = 0;
                $waService = app(WhatsAppService::class);
                $className = SchoolClass::find($data['class_id'])?->name ?? 'Kelas';

                foreach ($enrollments as $enrollment) {
                    $student = $enrollment->student;
                    $phone = $student?->parent_phone ?? $student?->phone;

                    if (! $phone) {
                        $failedCount++;
                        continue;
                    }

                    $pesan = "📢 *PEMBERITAHUAN UJIAN CBT*\n\n"
                        . "Yth. Siswa/Wali Murid Kelas *{$className}*\n"
                        . "Berikut informasi ujian CBT yang akan dilaksanakan:\n\n"
                        . "👤 Nama Siswa: *{$student->name}*\n"
                        . "📝 Judul Ujian: *{$record->title}*\n"
                        . "📚 Mapel: " . ($record->subject?->name ?? '-') . "\n"
                        . "🔑 TOKEN: *{$record->token}*\n"
                        . "⏱️ Durasi: {$record->duration_minutes} Menit\n"
                        . "📅 Mulai: " . ($record->start_time?->format('d M Y, H:i') ?? '-') . "\n"
                        . "📅 Selesai: " . ($record->end_time?->format('d M Y, H:i') ?? '-') . "\n\n"
                        . "Harap persiapkan perangkat dan koneksi internet dengan baik.\n"
                        . "_Sistem Informasi Sekolah_";

                    if ($waService->sendMessage($phone, $pesan)) {
                        $successCount++;
                    } else {
                        $failedCount++;
                    }
                }

                Notification::make()
                    ->title('Broadcast WA Selesai')
                    ->body("Berhasil: {$successCount}, Gagal: {$failedCount}")
                    ->success()
                    ->send();
            });
    }
}
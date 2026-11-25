<?php

namespace App\Filament\Admin\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;

class BackupManager extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-circle-stack';

    protected static string | UnitEnum | null $navigationGroup = 'Manajemen Sekolah';
    // protected static ?string $navigationGroup = 'Manajemen Sekolah';

    protected static ?string $navigationLabel = 'Backup & Restore DB';

    protected static ?string $title = 'Manajemen Keamanan Database';

    // protected static ?int $navigationSort = 2;

    protected  string $view = 'filament.admin.pages.backup-manager';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user) {
            \Illuminate\Support\Facades\Log::warning('[BackupManager] Akses ditolak: User belum login.');
            return false;
        }

        // Ambil role_type langsung dengan query ke tabel acad_teachers berdasarkan user_id atau teacher_id
        $teacherRole = '';
        
        // Cek jika relasi user memiliki teacher_id
        if (!empty($user->teacher_id)) {
            $teacher = \App\Models\Teacher::find($user->teacher_id);
            if ($teacher && !empty($teacher->role_type)) {
                $teacherRole = strtolower(trim((string) $teacher->role_type));
            }
        } 
        
        // Alternatif: Cek jika tabel acad_teachers menggunakan kolom user_id
        if (empty($teacherRole)) {
            $teacherByUserId = \App\Models\Teacher::where('user_id', $user->id)->first();
            if ($teacherByUserId && !empty($teacherByUserId->role_type)) {
                $teacherRole = strtolower(trim((string) $teacherByUserId->role_type));
            }
        }

        $userRole = strtolower(trim((string) ($user->role ?? $user->type ?? '')));
        $fullRoleString = $userRole . ' ' . $teacherRole;

        \Illuminate\Support\Facades\Log::info('[BackupManager] User ID: ' . $user->id . ' | Role Terdeteksi: "' . $fullRoleString . '"');

        // Berikan izin jika role mengandung unsur headmaster, kepala sekolah, admin, atau super
        if (
            str_contains($fullRoleString, 'headmaster') || 
            str_contains($fullRoleString, 'kepala') || 
            str_contains($fullRoleString, 'admin') || 
            str_contains($fullRoleString, 'super')
        ) {
            return true;
        }

        return false;
    }

    protected function getHeaderActions(): array
    {
        return [
            // EXPORT DATABASE (Struktur + Seluruh Isi Data)
            Action::make('exportDatabase')
                ->label('Backup / Download Seluruh Database')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Unduh Backup Seluruh Database?')
                ->modalDescription('Proses ini akan mengekspor STRUKTUR TABEL & SELURUH DATA sekolah ke dalam satu file dump (.sql).')
                ->action(function () {
                    $dbHost = config('database.connections.mysql.host');
                    $dbPort = config('database.connections.mysql.port', 3306);
                    $dbName = config('database.connections.mysql.database');
                    $dbUser = config('database.connections.mysql.username');
                    $dbPass = config('database.connections.mysql.password');

                    $fileName = 'backup-full-database-'.date('Y-m-d_H-i-s').'.sql';
                    $filePath = storage_path('app/'.$fileName);
                    $logPath  = storage_path('app/mysqldump_error.log');

                    if (! file_exists(storage_path('app'))) {
                        mkdir(storage_path('app'), 0755, true);
                    }

                    $envPass = $dbPass ? 'MYSQL_PWD='.escapeshellarg($dbPass).' ' : '';

                    $command = sprintf(
                        '%smysqldump --user=%s --host=%s --port=%s --opt --single-transaction --quick --routines --triggers %s > %s 2> %s',
                        $envPass,
                        escapeshellarg($dbUser),
                        escapeshellarg($dbHost),
                        escapeshellarg($dbPort),
                        escapeshellarg($dbName),
                        escapeshellarg($filePath),
                        escapeshellarg($logPath)
                    );

                    exec($command, $output, $returnVar);

                    $errorLog = file_exists($logPath) ? file_get_contents($logPath) : '';
                    @unlink($logPath);

                    if ($returnVar !== 0 || ! file_exists($filePath) || filesize($filePath) === 0) {
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }

                        Notification::make()
                            ->title('Gagal Export Database')
                            ->body(! empty($errorLog) ? trim($errorLog) : 'Perintah mysqldump gagal dieksekusi.')
                            ->danger()
                            ->send();

                        return;
                    }

                    return response()->download($filePath)->deleteFileAfterSend(true);
                }),

            // RESTORE / IMPORT DATABASE
            Action::make('importDatabase')
                ->label('Restore / Timpa Database')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('danger')
                ->schema([
                    FileUpload::make('backup_file')
                        ->label('Upload File Backup (.sql)')
                        ->acceptedFileTypes(['application/sql', 'text/plain', 'text/x-sql', 'application/octet-stream'])
                        ->directory('temp-backups')
                        ->required(),
                ])
                ->modalHeading('Restore Database Sekolah')
                ->modalDescription('⚠️ PERHATIAN: Memulihkan database akan MENIMPA SELURUH DATA saat ini.')
                ->requiresConfirmation()
                ->action(function (array $data) {
                    // Di Filament v4, file upload disimpan di disk publik atau default storage
                    $filePath = storage_path('app/public/'.$data['backup_file']);
                    if (! file_exists($filePath)) {
                        $filePath = storage_path('app/'.$data['backup_file']);
                    }

                    if (! file_exists($filePath)) {
                        Notification::make()->title('File backup tidak ditemukan pada direktori server.')->danger()->send();

                        return;
                    }

                    $dbHost = config('database.connections.mysql.host');
                    $dbPort = config('database.connections.mysql.port', 3306);
                    $dbName = config('database.connections.mysql.database');
                    $dbUser = config('database.connections.mysql.username');
                    $dbPass = config('database.connections.mysql.password');

                    $logPath = storage_path('app/mysql_restore_error.log');

                    $envPass = $dbPass ? 'MYSQL_PWD='.escapeshellarg($dbPass).' ' : '';

                    $command = sprintf(
                        '%smysql --user=%s --host=%s --port=%s %s < %s 2> %s',
                        $envPass,
                        escapeshellarg($dbUser),
                        escapeshellarg($dbHost),
                        escapeshellarg($dbPort),
                        escapeshellarg($dbName),
                        escapeshellarg($filePath),
                        escapeshellarg($logPath)
                    );

                    exec($command, $output, $returnVar);

                    $errorLog = file_exists($logPath) ? file_get_contents($logPath) : '';
                    @unlink($logPath);
                    @unlink($filePath);

                    if ($runtimeVar ?? $returnVar === 0) {
                        Notification::make()->title('Database Berhasil Dipulihkan beserta Seluruh Datanya!')->success()->send();
                    } else {
                        Notification::make()
                            ->title('Gagal Restore Database')
                            ->body(! empty($errorLog) ? trim($errorLog) : 'Eksekusi pemulihan database gagal.')
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
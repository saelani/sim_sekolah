<?php

namespace App\Filament\Admin\Resources\Schedules\Pages;

use App\Filament\Admin\Resources\Schedules\ScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('downloadSchedulePdf')
                ->label('Cetak Jadwal PDF')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\Select::make('class_id')
                        ->label('Pilih Kelas')
                        ->options(function () {
                            $user = auth()->user();

                            // Jika Admin atau Headmaster, ambil semua kelas
                            if ($user->role === 'admin' || $user->role === 'headmaster') {
                                return \App\Models\SchoolClass::pluck('name', 'id');
                            }

                            // Jika guru biasa, ambil ID guru dan hanya tampilkan kelas miliknya
                            $teacherId = optional($user->teacher()->first())->id;

                            return \App\Models\SchoolClass::where('teacher_id', $teacherId)
                                ->pluck('name', 'id');
                        })
                        ->required()
                        ->searchable()
                        ->preload(),
                ])
                ->action(function (array $data) {
                    // Mengarahkan ke route PDF dengan menyertakan parameter class_id
                    $url = route('pdf.schedules', [
                        'class_id' => $data['class_id'],
                    ]);

                    // return redirect()->away($url);
                    $this->js("window.open('{$url}', '_blank');");
                }),
        ]; 
    }
}
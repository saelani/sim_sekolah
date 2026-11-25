<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget as BaseWidget;

class PdfPreviewWidget extends BaseWidget
{
    protected string $view = 'pdf.preview-widget';

    public string $reportType = '';
    public int $classId = 0;
    public ?int $year = null;
    public ?int $month = null;
    public ?string $startDate = null;
    public ?string $endDate = null;
    public ?int $semester = null;
    public ?int $subjectId = null;
    public ?string $level = null;

    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function preview()
    {
        $url = route('student-age.preview', [
            'class_id' => $this->classId,
            'month' => $this->month,
            'year' => $this->year,
        ]);
        return redirect()->away($url);
    }

    public function download()
    {
        $url = route('student-age.preview', [
            'class_id' => $this->classId,
            'month' => $this->month,
            'year' => $this->year,
        ]);
        return redirect()->away($url);
    }

    public function print()
    {
        $url = route('student-age.preview', [
            'class_id' => $this->classId,
            'month' => $this->month,
            'year' => $this->year,
        ]);
        return redirect()->away($url);
    }
}

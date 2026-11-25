<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Http\Traits\PdfPreviewable;
use App\Models\LearningObjective;
use App\Models\SchoolProfile;
use App\Models\Subject;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class LearningObjectiveReportController extends Controller
{
    use PdfPreviewable;

    /**
     * Helper untuk validasi hak akses dan filter data berdasarkan guru yang login
     */
    private function authorizeAndFilterQuery(Request $request, $query)
    {
        $user = auth()->user();
        $teacher = null;
        if ($user) {
            $teacher = $user->teacher ?? (isset($user->teacher_id) ? Teacher::find($user->teacher_id) : null);
        }
        if ($teacher instanceof \Illuminate\Support\Collection) {
            $teacher = $teacher->first();
        }

        // Cek apakah user adalah Admin atau Headmaster
        $isAdminOrHeadmaster = false;
        if ($user) {
            if (method_exists($user, 'hasRole') && ($user->role === 'admin' || $user->role === 'headmaster')) {
                $isAdminOrHeadmaster = true;
            } elseif ($teacher && $teacher->role_type === 'headmaster') {
                $isAdminOrHeadmaster = true;
            }
        }

        // Jika bukan Admin / Headmaster, terapkan filter ketat berdasarkan teacher_id pada LearningObjective atau SumativeScope
        if (!$isAdminOrHeadmaster && $teacher) {
            $teacherId = $teacher->id;
            
            $query->where(function ($subQuery) use ($teacherId) {
                $subQuery->where('teacher_id', $teacherId)
                         ->orWhereHas('sumativeScope', function ($scopeQuery) use ($teacherId) {
                             $scopeQuery->where('teacher_id', $teacherId);
                         });
            });
        } elseif (!$isAdminOrHeadmaster) {
            // Jika bukan admin/headmaster dan data teacher tidak valid
            abort(403, 'Unauthorized. Anda tidak memiliki hak akses.');
        }

        return [$teacher, $isAdminOrHeadmaster];
    }

    public function generatePdf(Request $request)
    {
        $request->validate([
            'subject_id' => ['required', Rule::exists('acad_subjects', 'id')],
            'level'      => 'nullable',
            'semester'   => 'nullable',
        ]);

        Carbon::setLocale('id');

        $subject = Subject::findOrFail($request->subject_id);
        
        $school = SchoolProfile::first() ?? new SchoolProfile();
        
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }

        $query = LearningObjective::with(['sumativeScope', 'subject'])
            ->where('subject_id', $request->subject_id);

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Terapkan filter keamanan kepemilikan data
        [$teacher, $isAdminOrHeadmaster] = $this->authorizeAndFilterQuery($request, $query);

        $learningObjectives = $query->orderBy('level', 'asc')
            ->orderBy('semester', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Jika data kosong setelah difilter dan bukan admin/headmaster, cegah akses
        if ($learningObjectives->isEmpty() && !$isAdminOrHeadmaster) {
            abort(403, 'Anda tidak memiliki hak akses atau data ini bukan milik Anda.');
        }

        $city = $school->city ?? $school->district ?? 'Sekolah';
        $formattedDate = Carbon::now()->translatedFormat('d F Y');
        $titiMangsa = "{$city}, {$formattedDate}";

        $pdf = Pdf::loadView('pdf.learning-objective-report', [
            'school'             => $school,
            'subject'            => $subject,
            'learningObjectives' => $learningObjectives,
            'headmaster'         => $headmaster,
            'teacher'            => $teacher,
            'titiMangsa'         => $titiMangsa,
            'selectedLevel'      => $request->level,
            'selectedSemester'   => $request->semester,
            'doc_heading'        => 'PEMETAAN TUJUAN PEMBELAJARAN (TP)',
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isRemoteEnabled'      => true,
            'isHtml5ParserEnabled' => true,
        ]);

        return $pdf->stream("Laporan_TP_{$subject->name}.pdf");
    }

    /**
     * Preview PDF di browser (inline) — sebelum print
     */
    public function preview(Request $request)
    {
        $request->validate([
            'subject_id' => ['required', Rule::exists('acad_subjects', 'id')],
            'level'      => 'nullable',
            'semester'   => 'nullable',
        ]);

        Carbon::setLocale('id');

        $subject = Subject::findOrFail($request->subject_id);
        
        $school = SchoolProfile::first() ?? new SchoolProfile();
        
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }

        $query = LearningObjective::with(['sumativeScope', 'subject'])
            ->where('subject_id', $request->subject_id);

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Terapkan filter keamanan kepemilikan data
        [$teacher, $isAdminOrHeadmaster] = $this->authorizeAndFilterQuery($request, $query);

        $learningObjectives = $query->orderBy('level', 'asc')
            ->orderBy('semester', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if ($learningObjectives->isEmpty() && !$isAdminOrHeadmaster) {
            abort(403, 'Anda tidak memiliki hak akses atau preview data ini bukan milik Anda.');
        }

        $city = $school->city ?? $school->district ?? 'Sekolah';
        $formattedDate = Carbon::now()->translatedFormat('d F Y');
        $titiMangsa = "{$city}, {$formattedDate}";

        return $this->previewPdf('pdf.learning-objective-report', [
            'school'             => $school,
            'subject'            => $subject,
            'learningObjectives' => $learningObjectives,
            'headmaster'         => $headmaster,
            'teacher'            => $teacher,
            'titiMangsa'         => $titiMangsa,
            'selectedLevel'      => $request->level,
            'selectedSemester'   => $request->semester,
            'doc_heading'        => 'PEMETAAN TUJUAN PEMBELAJARAN (TP)',
        ], "Laporan_TP_{$subject->name}", 'portrait');
    }
}
<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Http\Traits\PdfPreviewable;
use App\Models\SchoolProfile;
use App\Models\Subject;
use App\Models\SumativeScope;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SumativeScopeReportController extends Controller
{
    use PdfPreviewable;

    /**
     * Helper untuk validasi hak akses dan filter kepemilikan data guru yang login
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

        // Jika bukan Admin / Headmaster, terapkan filter ketat berdasarkan teacher_id pada SumativeScope
        if (!$isAdminOrHeadmaster && $teacher) {
            $query->where('teacher_id', $teacher->id);
        } elseif (!$isAdminOrHeadmaster) {
            abort(403, 'Unauthorized. Anda tidak memiliki hak akses.');
        }

        return [$teacher, $isAdminOrHeadmaster];
    }

    public function generatePdf(Request $request)
    {
        $request->validate([
            'subject_id' => ['required', Rule::exists('acad_subjects', 'id')],
            'phase'      => 'nullable',
            'semester'   => 'nullable',
        ]);

        Carbon::setLocale('id');

        $subject = Subject::findOrFail($request->subject_id);
        
        $school = SchoolProfile::first() ?? new SchoolProfile();
        
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }

        $query = SumativeScope::with(['subject', 'teacher', 'schoolClass'])
            ->where('subject_id', $request->subject_id);

        if ($request->filled('phase')) {
            $query->where('phase', $request->phase);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // 🛡️ Terapkan filter keamanan kepemilikan data
        [$teacher, $isAdminOrHeadmaster] = $this->authorizeAndFilterQuery($request, $query);

        $sumativeScopes = $query->orderBy('phase', 'asc')
            ->orderBy('semester', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Jika data kosong setelah difilter dan bukan admin/headmaster, cegah akses
        if ($sumativeScopes->isEmpty() && !$isAdminOrHeadmaster) {
            abort(403, 'Anda tidak memiliki hak akses atau data ini bukan milik Anda.');
        }

        // ⚠️ Penyuntikan properti agar master layout tidak error saat membaca collection
        $sumativeScopes->front_title = 'PEMETAAN LINGKUP MATERI SUMATIF';

        $city = $school->city ?? $school->district ?? 'Sekolah';
        $formattedDate = Carbon::now()->translatedFormat('d F Y');
        $titiMangsa = "{$city}, {$formattedDate}";

        $pdf = Pdf::loadView('pdf.sumative-scope-report', [
            'school'           => $school,
            'subject'          => $subject,
            'sumativeScopes'   => $sumativeScopes,
            'headmaster'       => $headmaster,
            'teacher'          => $teacher,
            'titiMangsa'       => $titiMangsa,
            'selectedPhase'    => $request->phase,
            'selectedSemester' => $request->semester,
            'front_title'      => 'PEMETAAN LINGKUP MATERI SUMATIF',
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isRemoteEnabled'      => true,
            'isHtml5ParserEnabled' => true,
        ]);

        return $pdf->stream("Laporan_Lingkup_Materi_{$subject->name}.pdf");
    }

    /**
     * Preview PDF di browser (inline) — sebelum print
     */
    public function preview(Request $request)
    {
        $request->validate([
            'subject_id' => ['required', Rule::exists('acad_subjects', 'id')],
            'phase'      => 'nullable',
            'semester'   => 'nullable',
        ]);

        Carbon::setLocale('id');

        $subject = Subject::findOrFail($request->subject_id);
        
        $school = SchoolProfile::first() ?? new SchoolProfile();
        
        // Pastikan headmaster berupa objek tunggal, bukan collection
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }

        $query = SumativeScope::with(['subject', 'teacher', 'schoolClass'])
            ->where('subject_id', $request->subject_id);

        if ($request->filled('phase')) {
            $query->where('phase', $request->phase);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // 🛡️ Terapkan filter keamanan kepemilikan data
        [$teacher, $isAdminOrHeadmaster] = $this->authorizeAndFilterQuery($request, $query);

        $sumativeScopes = $query->orderBy('phase', 'asc')
            ->orderBy('semester', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if ($sumativeScopes->isEmpty() && !$isAdminOrHeadmaster) {
            abort(403, 'Anda tidak memiliki hak akses atau preview data ini bukan milik Anda.');
        }

        // ⚠️ Penyuntikan properti agar master layout tidak error saat membaca collection
        $sumativeScopes->front_title = 'PEMETAAN LINGKUP MATERI SUMATIF';

        $city = $school->city ?? $school->district ?? 'Sekolah';
        $formattedDate = Carbon::now()->translatedFormat('d F Y');
        $titiMangsa = "{$city}, {$formattedDate}";

        return $this->previewPdf('pdf.sumative-scope-report', [
            'school'           => $school,
            'subject'          => $subject,
            'sumativeScopes'   => $sumativeScopes,
            'headmaster'       => $headmaster,
            'teacher'          => $teacher,
            'titiMangsa'       => $titiMangsa,
            'selectedPhase'    => $request->phase,
            'selectedSemester' => $request->semester,
            'front_title'      => 'PEMETAAN LINGKUP MATERI SUMATIF',
        ], "Laporan_Lingkup_Materi_{$subject->name}", 'portrait');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StudentExamController extends Controller
{
    private function authorizeStudent(string $nis): ?Student
    {
        $user = Auth::user();
        $student = Student::where('nis', $nis)->first();

        if (! $user || ! $student || (int) $user->id !== (int) $student->user_id) {
            return null;
        }

        return $student;
    }

    public function index($nis)
    {
        $student = $this->authorizeStudent((string) $nis);
        if (! $student) {
            return redirect('/siswa/login')->with('error', 'Akses ditolak.');
        }

        $exams = Exam::with('subject')
            ->where('is_active', 1)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->where(function ($q) use ($student) {
                $q->where('class_room_id', $student->class_id)
                    ->orWhereNull('class_room_id');
            })
            ->orderBy('start_time')
            ->get();

        return view('student.exam.index', [
            'student' => $student,
            'classes' => $student->schoolClass,
            'exams'   => $exams,
        ]);
    }

    public function take(Request $request, $nis, $token, $id)
    {
        $student = $this->authorizeStudent((string) $nis);
        if (! $student) {
            return redirect('/siswa/login')->with('error', 'Akses ditolak.');
        }

        $exam = Exam::with(['subject', 'schoolClass', 'questions.options'])
            ->where('id', $id)
            ->where('token', $token)
            ->first();

        if (! $exam) {
            return redirect()->route('student.exam', ['nis' => $student->nis])
                ->with('error', 'Ujian tidak ditemukan atau token tidak valid.');
        }

        $now = now();
        if (! $exam->is_active || Carbon::parse($exam->start_time)->gt($now) || Carbon::parse($exam->end_time)->lt($now)) {
            return redirect()->route('student.exam', ['nis' => $student->nis])
                ->with('error', 'Ujian belum dimulai, sudah berakhir, atau sedang tidak aktif.');
        }

        if ($exam->class_room_id !== null && (int) $exam->class_room_id !== (int) $student->class_id) {
            return redirect()->route('student.exam', ['nis' => $student->nis])
                ->with('error', 'Ujian ini bukan untuk kelas Anda.');
        }

        $questions = $exam->questions;
        if ($exam->randomize_questions) {
            $questions = $questions->shuffle()->values();
        }

        if ($exam->randomize_options) {
            $questions = $questions->map(function ($q) {
                if ($q->relationLoaded('options') && $q->options) {
                    $q->setRelation('options', $q->options->shuffle()->values());
                }

                return $q;
            });
        }

        $session = ExamSession::firstOrCreate(
            [
                'cbt_exam_id' => $exam->id,
                'student_id'  => $student->id,
            ],
            [
                'start_time' => now(),
                'status'     => 'in_progress',
            ]
        );

        return view('student.exam.take', [
            'student'   => $student,
            'exam'      => $exam,
            'questions' => $questions,
            'session'   => $session,
        ]);
    }

    public function submit(Request $request, $nis, $token, $id)
    {
        $student = $this->authorizeStudent((string) $nis);
        if (! $student) {
            return redirect('/siswa/login')->with('error', 'Akses ditolak.');
        }

        $exam = Exam::with(['subject', 'questions.options'])
            ->where('id', $id)
            ->where('token', $token)
            ->first();

        if (! $exam) {
            return redirect()->route('student.exam', ['nis' => $student->nis])
                ->with('error', 'Ujian tidak ditemukan atau token tidak valid.');
        }

        $submittedAnswers = $request->input('answers', []);
        if (! is_array($submittedAnswers)) {
            $submittedAnswers = [];
        }

        $earnedScore = 0.0;
        $maxScore = 0.0;
        $correctCount = 0;
        $answeredCount = 0;

        foreach ($exam->questions as $question) {
            $weight = (float) ($question->score_weight ?? 1.0);
            $maxScore += $weight;
            $ans = $submittedAnswers[$question->id] ?? null;

            if (($question->type ?? 'multiple_choice') === 'essay') {
                if (is_string($ans) && trim($ans) !== '') {
                    $answeredCount++;
                }
                continue;
            }

            if ($ans !== null && $ans !== '') {
                $answeredCount++;
                $selectedOption = $question->options->firstWhere('id', (int) $ans);
                if ($selectedOption && (bool) $selectedOption->is_correct) {
                    $earnedScore += $weight;
                    $correctCount++;
                }
            }
        }

        $finalScore = $maxScore > 0 ? round(($earnedScore / $maxScore) * 100, 2) : 0.0;

        $session = ExamSession::firstOrCreate(
            [
                'cbt_exam_id' => $exam->id,
                'student_id'  => $student->id,
            ],
            [
                'start_time' => now(),
                'status'     => 'in_progress',
            ]
        );

        if (! in_array($session->status, ['submitted', 'completed'], true)) {
            $session->update([
                'status'       => 'submitted',
                'submitted_at' => $session->submitted_at ?? now(),
                'score'        => $finalScore,
                'total_score'  => round($earnedScore, 2),
            ]);
        }

        return view('student.exam.result', [
            'student'        => $student,
            'exam'           => $exam,
            'session'        => $session->fresh(),
            'answeredCount'  => $answeredCount,
            'totalQuestions' => $exam->questions->count(),
            'correctCount'   => $correctCount,
            'earnedScore'    => round($earnedScore, 2),
            'maxScore'       => round($maxScore, 2),
            'finalScore'     => $finalScore,
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\SchoolProfile;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\Post;
use App\Models\Gallery;
use Illuminate\Http\Request;

class PublicWebsiteController extends Controller
{
    /** Halaman utama / beranda web sekolah (HTML). */
    public function home()
    {
        $profile = SchoolProfile::first();
        $active  = AcademicYear::where('is_active', true)->first();
        $classes = SchoolClass::with(['academicYear', 'homeroomTeacher'])
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->orderBy('phase', 'asc')
            ->orderBy('level', 'asc')
            ->get();
        $teachers = Teacher::with(['user', 'teacherSubjectClass.subject', 'teacherSubjectClass.schoolClass'])
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
        $subjects = Subject::orderBy('order_number', 'asc')->get();
        $latestNews = Post::where('is_published', true)
            ->with(['PostCategory', 'User'])
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();
        $latestGalleries = Gallery::with('GalleryItem')->orderBy('created_at', 'desc')->limit(3)->get();
        $stats = [
            'students' => \App\Models\Student::count(),
            'teachers' => Teacher::where('is_active', true)->count(),
            'classes'  => $classes->count(),
            'subjects' => Subject::count(),
            'posts'    => Post::where('is_published', true)->count(),
            'galleries'=> Gallery::count(),
        ];

        return view('public.index', compact('profile', 'active', 'classes', 'teachers', 'subjects', 'stats', 'latestNews', 'latestGalleries'));
    }

    /** Tentang Kami - profil sekolah lengkap (HTML). */
    public function about(Request $request)
    {
        $profile = SchoolProfile::first();
        if (! $profile) {
            return response()->view('errors.notfound', ['message' => 'Profile sekolah belum diisi.'], 404);
        }
        return view('public.about', compact('profile'));
    }

    /** Daftar guru (HTML). */
    public function teachers(Request $request)
    {
        $teachers = Teacher::with(['user', 'teacherSubjectClass.subject', 'teacherSubjectClass.schoolClass'])
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
        $profile = SchoolProfile::first();
        return view('public.teachers', compact('teachers', 'profile'));
    }

    /** Detail satu guru (HTML). */
    public function teacher(Request $request, int $id)
    {
        $teacher = Teacher::with(['user', 'teacherSubjectClass.subject', 'teacherSubjectClass.schoolClass'])->find($id);
        $profile = SchoolProfile::first();
        if (! $teacher) {
            return response()->view('errors.notfound', ['message' => 'Guru tidak ditemukan.'], 404);
        }
        return view('public.teacher', compact('teacher', 'profile'));
    }

    /** Daftar kelas (HTML). */
    public function classes(Request $request)
    {
        $classes = SchoolClass::with(['academicYear', 'homeroomTeacher'])
            ->orderBy('phase', 'asc')
            ->orderBy('level', 'asc')
            ->get();
        $profile = SchoolProfile::first();
        return view('public.classes', compact('classes', 'profile'));
    }

    /** Daftar mata pelajaran (HTML). */
    public function subjects(Request $request)
    {
        $subjects = Subject::orderBy('order_number', 'asc')->get();
        $profile = SchoolProfile::first();
        return view('public.subjects', compact('subjects', 'profile'));
    }

    /** Daftar berita (artikel) terbitan (HTML). */
    public function news(Request $request)
    {
        $profile = SchoolProfile::first();
        $query = Post::where('is_published', true)
            ->with(['PostCategory', 'User'])
            ->orderBy('published_at', 'desc');

        if ($search = $request->get('q')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $posts = $query->get();

        return view('public.berita', compact('posts', 'profile'));
    }

    /** Detail satu berita (HTML). */
    public function news_show(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->with(['PostCategory', 'User'])
            ->first();

        if (! $post) {
            return response()->view('errors.notfound', ['message' => 'Artikel tidak ditemukan.'], 404);
        }

        \Illuminate\Support\Facades\DB::table('web_posts')->where('id', $post->id)->increment('views_count');

        $profile = SchoolProfile::first();

        return view('public.berita.show', compact('post', 'profile'));
    }

    /** Daftar galeri (HTML). */
    public function gallery(Request $request)
    {
        $profile = SchoolProfile::first();
        $galleries = Gallery::withCount('GalleryItem')->orderBy('created_at', 'desc')->get();

        return view('public.galeri', compact('galleries', 'profile'));
    }

    /** Detail satu galeri beserta item (HTML). */
    public function gallery_show(Request $request, string $slug)
    {
        $gallery = Gallery::where('slug', $slug)->first();

        if (! $gallery) {
            return response()->view('errors.notfound', ['message' => 'Galeri tidak ditemukan.'], 404);
        }

        $items = \App\Models\GalleryItem::where('gallery_id', $gallery->id)->orderBy('id', 'asc')->get();

        $profile = SchoolProfile::first();

        return view('public.galeri.show', compact('gallery', 'items', 'profile'));
    }
}

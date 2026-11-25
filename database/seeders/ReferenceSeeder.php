<?php

namespace Database\Seeders;

use App\Models\SchoolProfile;
use App\Models\AcademicYear;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReferenceSeeder extends Seeder
{
    /**
     * Seed the reference data required to make the app usable.
     *
     * @return void
     */
    public function run(): void
    {
        $this->seedUsers();
        $this->seedSchoolProfile();
        $this->seedAcademicYear();
        $this->seedSubjects();
        $this->seedClasses();
        $this->seedTeachers();
        $this->seedStudents();
    }

    private function seedUsers(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@simsekolah.com'],
            [
                'name' => 'Administrator',
                'role' => 'admin',
                'email_verified_at' => now(),
                'password' => bcrypt('admin123'),
                'remember_token' => Str::random(10),
            ]
        );

        // Headmaster
        User::firstOrCreate(
            ['email' => 'headmaster@simsekolah.com'],
            [
                'name' => 'Kepala Sekolah',
                'role' => 'headmaster',
                'email_verified_at' => now(),
                'password' => bcrypt('headmaster123'),
                'remember_token' => Str::random(10),
            ]
        );
    }

    private function seedSchoolProfile(): void
    {
        SchoolProfile::firstOrCreate(
            ['id' => 1],
            [
                'npsn' => '123456789',
                'school_name' => 'SDN Sim Sekolah',
                'headmaster_name' => 'Bapak Ahmad S.',
                'accreditation' => 'B',
                'address' => 'Jalan Panelasi No. 10, Jakarta',
                'phone' => '021-555-0199',
                'email' => 'admin@simsekolah.com',
                'website' => 'https://simsekolah.sch.id',
                'vision' => 'Mewujudkan generasi berkarakter dan terampil.',
                'mission' => 'Menyelenggarakan pembelajaran yang partisipatif, marmar, dan berpusat pada peserta didik.',
                'logo_path' => null,
                'maps_embed' => 'https://maps.google.com/?q=Panelasi,Jakarta',
            ]
        );
    }

    private function seedAcademicYear(): void
    {
        AcademicYear::firstOrCreate(
            ['id' => 1],
            [
                'year' => '2025/2026',
                'semester' => '1',
                'is_active' => 1,
            ]
        );

        // Fallback active year (any is_active = 1)
        if (! AcademicYear::where('is_active', 1)->exists()) {
            AcademicYear::create([
                'year' => '2025/2026',
                'semester' => '1',
                'is_active' => 1,
            ]);
        }
    }

    private function seedSubjects(): void
    {
        $subjects = [
            ['code' => 'BIND', 'name' => 'Bahasa Indonesia', 'category' => 'umum', 'order_number' => 1],
            ['code' => 'MTK', 'name' => 'Matematika', 'category' => 'umum', 'order_number' => 2],
            ['code' => 'IPA', 'name' => 'Ilmu Pengetahuan Alam', 'category' => 'umum', 'order_number' => 3],
            ['code' => 'IPS', 'name' => 'Ilmu Pengetahuan Sosial', 'category' => 'umum', 'order_number' => 4],
            ['code' => 'PJOK', 'name' => 'Penjasorkes', 'category' => 'umum', 'order_number' => 5],
            ['code' => 'AGAMA', 'name' => 'Pendidikan Agama Islam', 'category' => 'agama', 'order_number' => 6],
        ];

        foreach ($subjects as $s) {
            Subject::firstOrCreate(['code' => $s['code']], $s);
        }
    }

    private function seedClasses(): void
    {
        $year = AcademicYear::where('is_active', 1)->first();
        $phases = ['A', 'B', 'C'];
        $levels = [1, 2, 3, 4, 5, 6];
        $names = ['Iqro', 'Cicihoyong', 'Pegantan', 'Tegalrejo', 'Cibogog', 'Mancak'];

        $count = 0;
        foreach ($phases as $p) {
            foreach ($levels as $l) {
                if ($count >= 6) break 2;
                $name = $names[$count] ?? 'Kelas '.strtoupper($p).'-'.$l;
                SchoolClass::firstOrCreate(
                    ['id' => $count + 1],
                    [
                        'academic_year_id' => $year->id,
                        'name' => $name,
                        'phase' => $p,
                        'level' => $l,
                        'teacher_id' => null,
                    ]
                );
                $count++;
            }
        }
    }

    private function seedTeachers(): void
    {
        $teachers = [
            ['name' => 'Ibu Siti Nurhaliza', 'front_title' => 'Ibu', 'gender' => 'P', 'role_type' => 'class_teacher', 'nip' => '197803032005012001'],
            ['name' => 'Bapak Ahmad Suryo', 'front_title' => 'Bapak', 'gender' => 'L', 'role_type' => 'subject_teacher', 'nip' => '198001121999011001'],
            ['name' => 'Ibu Dewi Lestari', 'front_title' => 'Ibu', 'gender' => 'P', 'role_type' => 'subject_teacher', 'nip' => '198205052007012001'],
        ];

        foreach ($teachers as $t) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace(' ', '', $t['name'])).'@simsekolah.com'],
                [
                    'name' => $t['name'],
                    'role' => 'teacher',
                    'email_verified_at' => now(),
                    'password' => bcrypt('teacher123'),
                    'remember_token' => Str::random(10),
                ]
            );
            Teacher::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $t['name'],
                    'front_title' => $t['front_title'],
                    'gender' => $t['gender'],
                    'role_type' => $t['role_type'],
                    'nip' => $t['nip'],
                    'is_active' => 1,
                ]
            );
        }
    }

    private function seedStudents(): void
    {
        $classes = SchoolClass::all();
        $students = [
            ['name' => 'Budi Santoso', 'gender' => 'L', 'religion' => 'Islam', 'pob' => 'Jakarta', 'dob' => '2014-03-15', 'address' => 'Jl. Merdeka No. 5', 'parent_name' => 'Bapak H. Santoso', 'nis' => '001', 'nisn' => '1234567890'],
            ['name' => 'Siti Aisyah', 'gender' => 'P', 'religion' => 'Islam', 'pob' => 'Jakarta', 'dob' => '2014-07-22', 'address' => 'Jl. Veteran No. 12', 'parent_name' => 'Ibu S. Lestari', 'nis' => '002', 'nisn' => '1234567891'],
            ['name' => 'Andi Pratama', 'gender' => 'L', 'religion' => 'Islam', 'pob' => 'Jakarta', 'dob' => '2013-11-02', 'address' => 'Jl. Diponegoro No. 20', 'parent_name' => 'Bapak M. Pratama', 'nis' => '003', 'nisn' => '1234567892'],
            ['name' => 'Rina Wulandari', 'gender' => 'P', 'religion' => 'Kristen', 'pob' => 'Jakarta', 'dob' => '2014-01-30', 'address' => 'Jl. Gatot Subroto No. 33', 'parent_name' => 'Bapak D. Wulandari', 'nis' => '004', 'nisn' => '1234567893'],
            ['name' => 'Eka Prasetyo', 'gender' => 'L', 'religion' => 'Hindu', 'pob' => 'Jakarta', 'dob' => '2013-09-10', 'address' => 'Jl. Thamrin No. 7', 'parent_name' => 'Ibu Y. Prasetyo', 'nis' => '005', 'nisn' => '1234567894'],
            ['name' => 'Bella Kurnia', 'gender' => 'P', 'religion' => 'Islam', 'pob' => 'Jakarta', 'dob' => '2014-05-05', 'address' => 'Jl. Samboroto No. 99', 'parent_name' => 'Bapak A. Kurnia', 'nis' => '006', 'nisn' => '1234567895'],
        ];

        foreach ($students as $i => $s) {
            $user = User::firstOrCreate(
                ['email' => 'student'.$i.'@simsekolah.com'],
                [
                    'name' => $s['name'],
                    'role' => 'student',
                    'email_verified_at' => now(),
                    'password' => bcrypt('student123'),
                    'remember_token' => Str::random(10),
                ]
            );
            $class = $classes->isNotEmpty() ? $classes->skip($i % $classes->count())->first() : null;
            Student::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($s, [
                    'class_id' => $class ? $class->id : null,
                ])
            );
        }
    }
}

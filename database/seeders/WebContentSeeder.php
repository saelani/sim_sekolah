<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebContentSeeder extends Seeder
{
    /**
     * Seed data untuk Berita & Galeri.
     */
    public function run(): void
    {
        // Reset tabel
        DB::table('web_post_categories')->delete();
        DB::table('web_posts')->delete();
        DB::table('web_galleries')->delete();
        DB::table('web_gallery_items')->delete();

        // ===== Kategori =====
        $categories = [
            ['name' => 'Berita Sekolah', 'slug' => 'berita-sekolah'],
            ['name' => 'Kegiatan', 'slug' => 'kegiatan'],
            ['name' => 'Berita Penting', 'slug' => 'berita-penting'],
        ];
        DB::table('web_post_categories')->insert($categories);

        // ===== Berita =====
        $posts = [
            [
                'category_id' => 1,
                'user_id' => 57,
                'title' => 'Pengumuman Hari Raya Idul Fitri 1447 H',
                'slug' => 'pengumuman-hari-raya-idul-fitri',
                'excerpt' => 'MUUUL fitri, rahmatillah, rahmatan allil alamin. Sekolah berharap semoga omnobotienulla kaffah. Berikut jadwal libur akademik dan berkah Idul Fitri.',
                'content' => 'Assalamu\'alaikum warahmatullahi Wabarakatuh.<br><br>MUUUL fitri, rahmatillah, rahmatan allil alamin.<br><br>Semoga Allah Rabbubakum fawz..., rahmatan lil aalamiin, rahmatan lil muslimeen...<br><br>Semoga Azab waraqotun illa.<br><br>Jadwal libur akademik: ...',
                'thumbnail_path' => 'posts/thumb-post-1.png',
                'views_count' => 0,
                'is_published' => 1,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'user_id' => 57,
                'title' => 'Kegiatan Olahraga Antarkan Satu Sekolah 2026',
                'slug' => 'olahraga-antarkan-satu-sekolah-2026',
                'excerpt' => 'Posyandu, gamat, lari dan sebagainya. Siswa-siswi kelas ... akan dilatih saat ...',
                'content' => 'Pada hari ... tanggal 2027, siswa-siswi kelas ... akan mengikuti lomba...',
                'thumbnail_path' => 'posts/thumb-post-2.png',
                'views_count' => 0,
                'is_published' => 1,
                'published_at' => now()->subDays(6),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'user_id' => 57,
                'title' => 'Raport Siswa Bulan Juni 2026',
                'slug' => 'raport-siswa-bulans-juni-2026',
                'excerpt' => 'Raport siswa akan diterbitkan setiap bulan. ...',
                'content' => 'Raport siswa akan diterbitkan setiap bulan. ...',
                'thumbnail_path' => 'posts/thumb-post-3.png',
                'views_count' => 0,
                'is_published' => 1,
                'published_at' => now()->subDays(12),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'user_id' => 57,
                'title' => 'Acara Semeriah Lebaran di SDN 2 Babakanmulya',
                'slug' => 'acara-semeriah-lebaran-di-sdn-2-babakanmulya',
                'excerpt' => 'MUUUL fitri, rahmatillah, rahmatan allil alamin, wabarakatuh. ...',
                'content' => 'Assalamu\'alaikum warahmatullahi Wabarakatuh.<br><br>MUUUL fitri, rahmatillah, rahmatan allil alamin, wabarakatuh.<br><br>Salam istiqomah...',
                'thumbnail_path' => 'posts/thumb-post-4.png',
                'views_count' => 0,
                'is_published' => 1,
                'published_at' => now()->subDays(9),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'user_id' => 57,
                'title' => 'Pelatihan Guru Pembelajaran Inklusif TP 2026',
                'slug' => 'pelatihan-guru-pembelajaran-inklusif-tp-2026',
                'excerpt' => 'Sehari ini sedang pelatihan guru ... diberikan ...',
                'content' => 'Waktu kita masih perlu ... Jadi kita harus berusaha ...',
                'thumbnail_path' => 'posts/thumb-post-5.png',
                'views_count' => 0,
                'is_published' => 1,
                'published_at' => now()->subDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('web_posts')->insert($posts);

        // ===== Galeri =====
        $galleries = [
            ['title' => 'Kegiatan Ekstrakurikuler', 'slug' => 'ekstrakurikuler', 'description' => 'Kegiatan siswa setelah jam pelajaran selesai.'],
            ['title' => 'Upacara Bendera Harian', 'slug' => 'upacara-bendera-harian', 'description' => 'Pembinaan karakter melalui upacara bendera setiap pagi.' ],
            ['title' => 'Pameran Hasil Karya Siswa', 'slug' => 'pameran-hasil-karya-siswa', 'description' => 'Karya seni dan proyek kelompok siswa.' ],
        ];
        DB::table('web_galleries')->insert($galleries);

        // ===== Item galeri =====
        $items = [];
        $galleryIds = [1, 1, 1, 1, 2, 2, 2, 3, 3];
        $files = ['galleries/gal-11.jpg', 'galleries/gal-12.jpg', 'galleries/gal-13.jpg', 'galleries/gal-14.jpg',
            'galleries/gal-15.jpg', 'galleries/gal-16.jpg', 'galleries/gal-17.jpg',
            'galleries/gal-18.jpg', 'galleries/gal-19.jpg'];
        $captions = [
            'Kegiatan olahraga antar kelas', 'Lomba bakso, lari dan sebagainya', 'Senam sehat', 'Posyandu', 'Gamat', 'Lari', 'Pembentukan santri', 'Tempat ibadah', 'Posyandu', 'Gawat', 'Jumat besar', 'Rukun jum\'at', 'Tempat sholat', 'Pameran','Karya siswa'
        ];

        foreach ($galleryIds as $idx => $gid) {
            $items[] = [
                'gallery_id' => $gid,
                'type' => 'image',
                'file_or_url' => $files[$idx],
                'caption' => $captions[$idx],
            ];
        }
        DB::table('web_gallery_items')->insert($items);

        echo 'WebContentSeeder completed.' . PHP_EOL;
    }
}

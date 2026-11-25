<?php
/**
 * Buat placeholder gambar untuk seeder Berita & Galeri.
 * Menulis ke storage/app/public/{posts|galleries}/
 */
$base = __DIR__ . '/../storage/app/public';
mkdir($base . '/posts', 0777, true);
mkdir($base . '/galleries', 0777, true);

function make_image($path, $color, $text)
{
    $w = $h = 600;
    $img = imagecreatetruecolor($w, $h);

    // background gelap
    $bg = imagecolorallocate($img, 26, 26, 26);
    imagefill($img, 0, 0, $bg);

    // color box
    $r = $color[0]; $g = $color[1]; $b = $color[2];
    $box = imagecolorallocate($img, $r, $g, $b);
    imagefilledrectangle($img, 50, 50, $w - 50, $h - 50, $box);

    // text
    $white = imagecolorallocate($img, 255, 255, 255);
    $font = __DIR__ . '/vendor/composer/autoload_psr4.php'; // placeholder font path (will fallback)
    imagestring($img, 5, 50, $h / 2 - 12, $text, $white);

    imagepng($img, $path);
    imagedestroy($img);
    echo "created $path" . PHP_EOL;
}

$colors = [
    [26, 58, 92],   // primary dark
    [100, 60, 120], // purple
    [232, 163, 61], // amber
    [46, 134, 87],  // emerald
    [198, 40, 40],  // red
];
$texts = ['SDN 2 Babakanmulya', 'Wahyu', 'Kegiatan', 'Siswa', 'Sekolah', 'Galeri', 'Foto', 'Ekskul'];

$file = 1;
foreach (['posts', 'galleries'] as $dir) {
    $out_dir = $base . '/' . $dir;
    for ($i = 1; $i <= 10; $i++) {
        $c = $colors[($i - 1) % count($colors)];
        $t = $texts[($i - 1) % count($texts)];
        $ext = ($dir === 'posts') ? 'png' : 'jpg';
        $fname = ($dir === 'posts')
            ? "thumb-post-{$i}.{$ext}"
            : "gal-{$file}.{$ext}";
        $path = $out_dir . '/' . $fname;
        if ($dir === 'galleries') {
            // make jpeg for galleries
            $img = imagecreatetruecolor(400, 300);
            $bg = imagecolorallocate($img, $c[0], $c[1], $c[2]);
            imagefill($img, 0, 0, $bg);
            $white = imagecolorallocate($img, 255, 255, 255);
            imagestring($img, 5, 20, 140, "Galeri Item #$file", $white);
            imagejpeg($img, $path, 80);
            imagedestroy($img);
        } else {
            make_image($path, $c, $t);
        }
        $file++;
    }
}
echo "DONE" . PHP_EOL;

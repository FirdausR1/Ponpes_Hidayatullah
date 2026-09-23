<?php
// Script bantuan untuk ekstrak update_terbaru.zip atau deploy_ponpes.zip di Hostinger
ini_set('max_execution_time', 300);
ini_set('memory_limit', '512M');

$zipFile = file_exists(__DIR__ . '/update_terbaru.zip') ? (__DIR__ . '/update_terbaru.zip') : (__DIR__ . '/deploy_ponpes.zip');

if (!file_exists($zipFile)) {
    die("<h2 style='color:red;'>File update_terbaru.zip (atau deploy_ponpes.zip) tidak ditemukan di folder ini.</h2>");
}

$zip = new ZipArchive;
if ($zip->open($zipFile) === TRUE) {
    $zip->extractTo(__DIR__);
    $zip->close();

    // Hapus cache view Laravel
    $viewsPath = __DIR__ . '/storage/framework/views';
    $count = 0;
    if (is_dir($viewsPath)) {
        $files = glob($viewsPath . '/*');
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                @unlink($file);
                $count++;
            }
        }
    }
    @unlink(__DIR__ . '/bootstrap/cache/config.php');
    @unlink(__DIR__ . '/bootstrap/cache/routes-v7.php');

    echo "<div style='font-family:sans-serif; max-width:650px; margin:40px auto; padding:25px; border-radius:12px; background:#f0fdf4; border:1px solid #bbf7d0; color:#166534;'>";
    echo "<h2 style='margin-top:0;'>Alhamdulillah, Update Sukses Diterapkan!</h2>";
    echo "<p>Semua file pembaruan sistem (Pemisahan SOT & Syahriyah RAB, Template Upload Santri Massal Baru, Peningkatan Panjang Asrama, Pemisahan Soal CBT, Tab Alumni Kasir, Cetak Kwitansi, dll.) berhasil diekstrak dan <b>{$count}</b> cache view lama telah dibersihkan otomatis.</p>";
    echo "<div style='margin-top:20px; display:flex; gap:10px; flex-wrap:wrap;'>";
    echo "<a href='/run_migrate.php' style='display:inline-block; padding:10px 18px; background:#0f766e; color:white; text-decoration:none; border-radius:8px; font-weight:bold;'>1. Jalankan Update Database &rarr;</a>";
    echo "<a href='/' style='display:inline-block; padding:10px 18px; background:#15803d; color:white; text-decoration:none; border-radius:8px; font-weight:bold;'>2. Buka Website</a>";
    echo "</div>";
    echo "<p style='margin-top:20px; font-size:12px; color:#4b5563;'>*Setelah selesai dan database terupdate, silakan hapus file <code>unzip.php</code>, <code>run_migrate.php</code>, dan <code>update_terbaru.zip</code> dari File Manager demi keamanan.</p>";
    echo "</div>";
} else {
    echo "<h2 style='color:red;'>GAGAL membuka file zip.</h2>";
}


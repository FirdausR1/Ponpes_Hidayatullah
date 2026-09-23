<?php

$zipFileName = __DIR__ . '/update_terbaru.zip';

// Hapus zip lama jika ada
if (file_exists($zipFileName)) {
    unlink($zipFileName);
}

$zip = new ZipArchive();
if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Gagal membuat file zip: $zipFileName\n");
}

// Folder-folder yang harus dimasukkan ke dalam paket update
$directoriesToInclude = [
    'app',
    'routes',
    'resources/views',
    'database/migrations',
    'database/seeders',
    'public/.htaccess',
];

// File perorangan penting
$filesToInclude = [
    'run_migrate.php',
    'unzip.php',
    '.htaccess',
    'public/googleab93c42a8094409a.html',
    'Buku_Panduan_Sistem_Ponpes_Hidayatullah.pdf',
];

$count = 0;

foreach ($directoriesToInclude as $target) {
    $fullPath = __DIR__ . '/' . $target;
    if (is_file($fullPath)) {
        $zip->addFile($fullPath, $target);
        $count++;
    } elseif (is_dir($fullPath)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($fullPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen(__DIR__) + 1);
                // Normalisasi path separator untuk ZIP standard
                $relativePath = str_replace('\\', '/', $relativePath);
                $zip->addFile($filePath, $relativePath);
                $count++;
            }
        }
    }
}

foreach ($filesToInclude as $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        $zip->addFile($fullPath, $file);
        $count++;
    }
}

$zip->close();

$sizeKb = round(filesize($zipFileName) / 1024, 2);
echo "SUCCESS: update_terbaru.zip berhasil dibuat!\n";
echo "Total file dimasukkan: $count file\n";
echo "Ukuran file ZIP: {$sizeKb} KB\n";

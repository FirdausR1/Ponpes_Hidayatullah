<?php

/**
 * Helper one-click migration for Hostinger / cPanel shared hosting.
 * Hapus file ini setelah selesai dijalankan demi keamanan.
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use App\Models\Setting;

header('Content-Type: text/html; charset=utf-8');
echo '<div style="font-family: sans-serif; max-width: 650px; margin: 40px auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">';
echo '<h2 style="color: #0d3b1e; margin-top: 0;">🚀 Menjalankan Pembaruan Sistem & Database Hostinger...</h2>';

try {
    // 1. Migrasi Database
    Artisan::call('migrate', ['--force' => true]);
    $migrateOutput = Artisan::output();

    // 2. Sinkronisasi Setting Biaya Bulanan RAB (Pemisahan SOT & Syahriyah)
    $biayaBulananData = [
        [
            'komponen'  => 'Uang Makan 3x Sehari',
            'mts_mukim' => 'Rp 300.000',
            'mts_laju'  => '—',
            'ma_mukim'  => 'Rp 300.000',
            'ma_laju'   => '—',
            'is_total'  => false,
        ],
        [
            'komponen'  => 'Syahriyah Pendidikan',
            'mts_mukim' => 'Rp 30.000',
            'mts_laju'  => '—',
            'ma_mukim'  => 'Rp 30.000',
            'ma_laju'   => '—',
            'is_total'  => false,
        ],
        [
            'komponen'  => 'Iuran SOT',
            'mts_mukim' => 'Rp 55.000',
            'mts_laju'  => 'Rp 55.000',
            'ma_mukim'  => 'Rp 75.000',
            'ma_laju'   => 'Rp 75.000',
            'is_total'  => false,
        ],
        [
            'komponen'  => 'Tabungan Wajib Santri',
            'mts_mukim' => 'Rp 25.000',
            'mts_laju'  => 'Rp 25.000',
            'ma_mukim'  => 'Rp 25.000',
            'ma_laju'   => 'Rp 25.000',
            'is_total'  => false,
        ],
        [
            'komponen'  => 'TOTAL IURAN BULANAN',
            'mts_mukim' => 'Rp 410.000 / bln',
            'mts_laju'  => 'Rp 80.000 / bln',
            'ma_mukim'  => 'Rp 430.000 / bln',
            'ma_laju'   => 'Rp 100.000 / bln',
            'is_total'  => true,
        ],
    ];
    Setting::set('biaya_bulanan_json', json_encode($biayaBulananData, JSON_PRETTY_PRINT), 'biaya');

    // 3. Bersihkan Cache Laravel
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');

    echo '<div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 14px; border-radius: 8px; margin-bottom: 16px;">';
    echo '<strong>Alhamdulillah, Berhasil!</strong> Migrasi database dan sinkronisasi tarif RAB keuangan (Pemisahan SOT & Syahriyah) sukses diterapkan.';
    echo '</div>';
    echo '<div style="font-size:13px; font-weight:600; margin-bottom:6px; color:#334155;">Log Migrasi:</div>';
    echo '<pre style="background: #f8fafc; padding: 12px; border-radius: 6px; font-size: 13px; border: 1px solid #cbd5e1; white-space:pre-wrap;">' . htmlspecialchars($migrateOutput) . '</pre>';
    echo '<div style="margin-top:20px; display:flex; gap:10px;">';
    echo '<a href="/" style="display:inline-block; padding:10px 18px; background:#15803d; color:white; text-decoration:none; border-radius:8px; font-weight:bold;">&larr; Buka Website Pondok</a>';
    echo '</div>';
    echo '<p style="color: #64748b; font-size: 12px; margin-top: 20px;"><strong>Catatan Keamanan:</strong> Demi keamanan data, silakan hapus file <code>run_migrate.php</code>, <code>unzip.php</code>, dan <code>update_terbaru.zip</code> dari File Manager Hostinger setelah ini.</p>';
} catch (\Throwable $e) {
    echo '<div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px; border-radius: 8px;">';
    echo '<strong>Gagal:</strong> ' . htmlspecialchars($e->getMessage());
    echo '</div>';
}

echo '</div>';

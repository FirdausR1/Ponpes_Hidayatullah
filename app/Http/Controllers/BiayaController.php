<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class BiayaController extends Controller
{
    /**
     * Halaman Publik Rincian Biaya Resmi Pendidikan & Masuk Santri Baru.
     */
    public function index()
    {
        $biayaAwalData = json_decode(Setting::get('biaya_awal_json', 'null'), true) ?: [
            ['komponen' => 'Uang Pangkal', 'mts_mukim' => 'Rp 1.200.000', 'mts_laju' => 'Rp 1.200.000', 'ma_mukim' => 'Rp 1.400.000', 'ma_laju' => 'Rp 1.400.000', 'is_total' => false],
            ['komponen' => 'Uang Gedung', 'mts_mukim' => 'Rp 500.000', 'mts_laju' => 'Rp 500.000', 'ma_mukim' => 'Rp 500.000', 'ma_laju' => 'Rp 500.000', 'is_total' => false],
            ['komponen' => 'Almari & Fasilitas Kamar', 'mts_mukim' => 'Rp 350.000', 'mts_laju' => '—', 'ma_mukim' => 'Rp 350.000', 'ma_laju' => '—', 'is_total' => false],
            ['komponen' => 'Kertas / Evaluasi Belajar (1 Tahun)', 'mts_mukim' => 'Rp 140.000', 'mts_laju' => 'Rp 140.000', 'ma_mukim' => 'Rp 160.000', 'ma_laju' => 'Rp 160.000', 'is_total' => false],
            ['komponen' => 'Kesehatan Santri (1 Tahun)', 'mts_mukim' => 'Rp 200.000', 'mts_laju' => 'Rp 200.000', 'ma_mukim' => 'Rp 200.000', 'ma_laju' => 'Rp 200.000', 'is_total' => false],
            ['komponen' => 'Kegiatan Santri (1 Tahun)', 'mts_mukim' => 'Rp 300.000', 'mts_laju' => 'Rp 300.000', 'ma_mukim' => 'Rp 300.000', 'ma_laju' => 'Rp 300.000', 'is_total' => false],
            ['komponen' => 'Syahriyah (Bulan Pertama)', 'mts_mukim' => 'Rp 85.000', 'mts_laju' => 'Rp 55.000', 'ma_mukim' => 'Rp 105.000', 'ma_laju' => 'Rp 75.000', 'is_total' => false],
            ['komponen' => 'Uang Makan (Bulan Pertama)', 'mts_mukim' => 'Rp 300.000', 'mts_laju' => '—', 'ma_mukim' => 'Rp 300.000', 'ma_laju' => '—', 'is_total' => false],
            ['komponen' => 'TOTAL BIAYA DAFTAR ULANG', 'mts_mukim' => 'Rp 3.225.000', 'mts_laju' => 'Rp 2.395.000', 'ma_mukim' => 'Rp 3.315.000', 'ma_laju' => 'Rp 2.635.000', 'is_total' => true],
        ];

        $biayaBulananData = json_decode(Setting::get('biaya_bulanan_json', 'null'), true) ?: [
            ['komponen' => 'Uang Makan 3x Sehari', 'mts_mukim' => 'Rp 300.000', 'mts_laju' => '—', 'ma_mukim' => 'Rp 300.000', 'ma_laju' => '—', 'is_total' => false],
            ['komponen' => 'Syahriyah Pendidikan', 'mts_mukim' => 'Rp 85.000', 'mts_laju' => 'Rp 55.000', 'ma_mukim' => 'Rp 105.000', 'ma_laju' => 'Rp 75.000', 'is_total' => false],
            ['komponen' => 'Tabungan Wajib Santri', 'mts_mukim' => 'Rp 25.000', 'mts_laju' => 'Rp 25.000', 'ma_mukim' => 'Rp 25.000', 'ma_laju' => 'Rp 25.000', 'is_total' => false],
            ['komponen' => 'TOTAL IURAN BULANAN', 'mts_mukim' => 'Rp 410.000 / bln', 'mts_laju' => 'Rp 80.000 / bln', 'ma_mukim' => 'Rp 430.000 / bln', 'ma_laju' => 'Rp 100.000 / bln', 'is_total' => true],
        ];

        return view('biaya', compact('biayaAwalData', 'biayaBulananData'));
    }
}

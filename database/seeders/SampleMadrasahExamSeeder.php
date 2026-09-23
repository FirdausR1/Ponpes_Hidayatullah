<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MadrasahExam;
use App\Models\MadrasahExamResult;

class SampleMadrasahExamSeeder extends Seeder
{
    public function run()
    {
        $exam = MadrasahExam::firstOrCreate(
            ['nama_ujian' => '[12] - [KEAGAMAAN] - UJIAN MADRASAH MA HIDAYATULLAH'],
            [
                'mata_pelajaran' => 'Al Quran Hadis',
                'nama_guru'      => 'LATIF MAHMUD A',
                'jenjang'        => 'MA',
                'tingkat_kelas'  => '12',
                'jurusan'        => 'KEAGAMAAN',
                'tahun_ajaran'   => '2024/2025',
                'semester'       => 'Genap',
                'jumlah_soal'    => 50,
                'durasi_menit'   => 90,
                'kkm'            => 75.00,
                'tanggal_ujian'  => '2025-03-13',
                'status'         => 'selesai',
                'keterangan'     => 'Hasil Ujian Madrasah Aliyah Hidayatullah sesuai sampel fisik.',
            ]
        );

        $students = [
            ['nama' => 'SELVIA STEVANNY', 'benar' => 38, 'nilai' => 76.00],
            ['nama' => 'ATA NUR RIFQI', 'benar' => 37, 'nilai' => 74.00],
            ['nama' => 'AZIZAH SALSABILA AZ ZAHRA', 'benar' => 33, 'nilai' => 66.00],
            ['nama' => 'RIA PUSPITA ANGGRAENI', 'benar' => 31, 'nilai' => 62.00],
            ['nama' => 'HIMATUL IZZAH', 'benar' => 30, 'nilai' => 60.00],
            ['nama' => 'ALAN LUTFI WIBOWO', 'benar' => 30, 'nilai' => 60.00],
            ['nama' => 'TALITHA EKA MAULIDYA', 'benar' => 29, 'nilai' => 58.00],
            ['nama' => 'ILHAM SETIA SYIFAURRAHMAN', 'benar' => 28, 'nilai' => 56.00],
            ['nama' => 'VIVI AYU ISTIQOMAH', 'benar' => 28, 'nilai' => 56.00],
            ['nama' => 'LAELA KHOIRUNISA', 'benar' => 27, 'nilai' => 54.00],
            ['nama' => 'INDRA RAHMAWATI', 'benar' => 25, 'nilai' => 50.00],
            ['nama' => 'TUHU KUNCORO', 'benar' => 23, 'nilai' => 46.00],
            ['nama' => 'AHMAD THOIFUROHMAN', 'benar' => 22, 'nilai' => 44.00],
            ['nama' => 'SYIFA MAGHFIROTON NISA', 'benar' => 22, 'nilai' => 44.00],
            ['nama' => 'AMANDA PUTRI LADUNI', 'benar' => 22, 'nilai' => 44.00],
            ['nama' => 'NAFISATUL ULYA KHOIRUNNISA', 'benar' => 22, 'nilai' => 44.00],
            ['nama' => 'KHANIFA QOLBIYANA HAMIDA', 'benar' => 21, 'nilai' => 42.00],
            ['nama' => 'FAIQ FAIRUZY', 'benar' => 21, 'nilai' => 42.00],
            ['nama' => 'MUHAMAD ULUL ALBAB', 'benar' => 21, 'nilai' => 42.00],
            ['nama' => 'ARDO ALDIFARIYATNO', 'benar' => 20, 'nilai' => 40.00],
            ['nama' => 'MOHAMAD SOKHA RIFAI', 'benar' => 20, 'nilai' => 40.00],
            ['nama' => 'KHALID HANI PRASETIYO', 'benar' => 19, 'nilai' => 38.00],
            ['nama' => 'DEA MAYLINDA', 'benar' => 19, 'nilai' => 38.00],
            ['nama' => 'DAFA ARIYADI', 'benar' => 19, 'nilai' => 38.00],
            ['nama' => 'AHMAD YUSUF RIZKI WINARTO', 'benar' => 19, 'nilai' => 38.00],
            ['nama' => 'PANJI SATRIO PAMUNGKAS', 'benar' => 18, 'nilai' => 36.00],
            ['nama' => 'FAJRI IRAWAN', 'benar' => 17, 'nilai' => 34.00],
            ['nama' => 'PUTRA ZUANA', 'benar' => 16, 'nilai' => 32.00],
            ['nama' => 'AHMAD MUQORROBIIN AL MUNAWAR', 'benar' => 12, 'nilai' => 24.00],
            ['nama' => 'NICHOLAS MAULANA ADJI PRASETYA', 'benar' => 12, 'nilai' => 24.00],
            ['nama' => 'ROHMAD MAQFUROHIM', 'benar' => 11, 'nilai' => 22.00],
        ];

        foreach ($students as $st) {
            $salah = 50 - $st['benar'];
            MadrasahExamResult::updateOrCreate(
                [
                    'madrasah_exam_id' => $exam->id,
                    'nama_peserta'     => $st['nama'],
                ],
                [
                    'kelas'            => '12',
                    'jurusan'          => 'KEAGAMAAN',
                    'jumlah_benar'     => $st['benar'],
                    'jumlah_salah'     => $salah,
                    'nilai'            => $st['nilai'],
                    'status'           => 'selesai',
                ]
            );
        }
    }
}

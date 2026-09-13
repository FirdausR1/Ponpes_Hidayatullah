<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // PROFIL & SEJARAH
            [
                'key' => 'profil_judul',
                'value' => 'Profil Pesantren Hidayatullah Tuksongo',
                'group' => 'profil',
            ],
            [
                'key' => 'sejarah_paragraf_1',
                'value' => 'Pondok Pesantren Hidayatullah Tuksongo didirikan pada tahun 1999 di atas tanah wakaf bersertifikat seluas 2.000 m² di Dusun Tuksongo RT 01/RW 01, Desa Nglorog, Kecamatan Pringsurat, Kabupaten Temanggung, Jawa Tengah.',
                'group' => 'profil',
            ],
            [
                'key' => 'sejarah_paragraf_2',
                'value' => 'Berawal dari majelis pengajian kyai bersama santri mukim yang terus bertambah, pesantren berkembang menjadi pusat peradaban ilmu. Pesantren ini dipimpin oleh para asatidz alumni Pondok Modern Darussalam Gontor yang memadukan kedisiplinan modern, tradisi Khutbatul \'Arsy (Pekan Perkenalan), panggung ekspresi santri, penguasaan dwibahasa Arab-Inggris, serta kekayaan kitab turats salafiyah.',
                'group' => 'profil',
            ],
            [
                'key' => 'visi',
                'value' => 'Unggul dalam Keislaman, Keilmuan, dan Kemasyarakatan',
                'group' => 'profil',
            ],
            [
                'key' => 'misi',
                'value' => "Terwujudnya kurikulum pesantren berbasis integrasi keagamaan dan kurikulum nasional yang unggul.\nPeningkatan prestasi akademik sains dan non-akademik keagamaan.\nTerbentuknya santri berkarakter kokoh, beradab mulia, dan siap guna di tengah masyarakat luas.\nMenjadi lembaga pendidikan berkualitas dengan jejaring dalam maupun luar negeri.",
                'group' => 'profil',
            ],
            [
                'key' => 'nspp',
                'value' => '512032304095',
                'group' => 'profil',
            ],
            [
                'key' => 'mts_npsn',
                'value' => '69994052',
                'group' => 'profil',
            ],
            [
                'key' => 'ma_npsn',
                'value' => '69881435',
                'group' => 'profil',
            ],
            [
                'key' => 'status_tanah',
                'value' => 'Wakaf Bersertifikat 2.000 m²',
                'group' => 'profil',
            ],
            [
                'key' => 'sambutan_quote',
                'value' => 'Pendidikan di Pondok Pesantren Hidayatullah Tuksongo bukan sekadar transfer ilmu pengetahuan, melainkan penanaman adab, pembiasaan ibadah istiqomah, dan pembentukan karakter kepemimpinan umat.',
                'group' => 'profil',
            ],
            [
                'key' => 'sambutan_nama',
                'value' => 'Dewan Pengasuh & Pimpinan',
                'group' => 'profil',
            ],
            [
                'key' => 'sambutan_jabatan',
                'value' => 'Pengasuh Pesantren Hidayatullah Tuksongo',
                'group' => 'profil',
            ],

            // JADWAL RUTINITAS SANTRI 24 JAM
            [
                'key' => 'jadwal_santri_json',
                'value' => json_encode([
                    ["waktu" => "03.00 - 04.30", "judul" => "Qiyamul Lail & Shalat Subuh", "keterangan" => "Bangun pagi, tahajud, doa mustajab & shalat subuh berjamaah di masjid jami'"],
                    ["waktu" => "04.30 - 05.30", "judul" => "Al-Ma'tsurat & Mufrodat Bahasa", "keterangan" => "Dzikir pagi, pemberian kosa kata dwibahasa (Arab & Inggris) santri"],
                    ["waktu" => "05.30 - 06.30", "judul" => "Mandi, Sarapan & Persiapan KBM", "keterangan" => "Piket kebersihan kamar asrama, sarapan pagi sehat, seragam madrasah rapi"],
                    ["waktu" => "07.00 - 13.30", "judul" => "Kegiatan Belajar Mengajar (KBM)", "keterangan" => "Madrasah formal MTs & MA (Kurikulum Kemenag, sains lab & kitab turats)"],
                    ["waktu" => "13.30 - 15.00", "judul" => "Makan Siang & Qailulah", "keterangan" => "Makan siang bersama di asrama & istirahat tidur siang sejenak"],
                    ["waktu" => "15.00 - 16.30", "judul" => "Shalat Ashar & Halaqah Tahfidz", "keterangan" => "Shalat berjamaah, setoran hafalan Al-Qur'an mutqin bersama musyrif"],
                    ["waktu" => "16.30 - 17.30", "judul" => "Ekstrakurikuler & Olahraga", "keterangan" => "Pencak silat, futsal, panahan, kepanduan Pramuka, dan mandi sore"],
                    ["waktu" => "17.30 - 19.30", "judul" => "Maghrib, Khotmil Qur'an & Isya", "keterangan" => "Shalat Maghrib, membaca Al-Qur'an bersama, kajian kitab & shalat Isya"],
                    ["waktu" => "19.30 - 20.30", "judul" => "Makan Malam & Belajar Terbimbing", "keterangan" => "Makan malam santri & muthola'ah (belajar malam) mandiri di kelas"],
                    ["waktu" => "20.30 - 21.45", "judul" => "Muhadhoroh / Mudzakarah Kitab", "keterangan" => "Latihan pidato 3 bahasa, mudzakarah turats, atau bimbingan takhassus"],
                    ["waktu" => "21.45 - 22.00", "judul" => "Absensi Asrama & Istirahat Malam", "keterangan" => "Pemeriksaan kehadiran santri, doa bersama sebelum tidur malam"]
                ], JSON_PRETTY_PRINT),
                'group' => 'jadwal',
            ],

            // MEDIA SOSIAL & KONTAK
            [
                'key' => 'sosmed_instagram',
                'value' => 'https://instagram.com',
                'group' => 'kontak',
            ],
            [
                'key' => 'sosmed_youtube',
                'value' => 'https://youtube.com',
                'group' => 'kontak',
            ],
            [
                'key' => 'sosmed_facebook',
                'value' => 'https://facebook.com',
                'group' => 'kontak',
            ],
            [
                'key' => 'sosmed_tiktok',
                'value' => 'https://tiktok.com',
                'group' => 'kontak',
            ],
            [
                'key' => 'kontak_hotline',
                'value' => '0813-9110-9966',
                'group' => 'kontak',
            ],
            [
                'key' => 'kontak_wa_psb',
                'value' => '0852-9042-9617',
                'group' => 'kontak',
            ],
            [
                'key' => 'kontak_pengasuhan_putra',
                'value' => '0882-1521-7462',
                'group' => 'kontak',
            ],
            [
                'key' => 'kontak_pengasuhan_putri',
                'value' => '0821-3631-8239',
                'group' => 'kontak',
            ],
            [
                'key' => 'kontak_kbm_putra',
                'value' => '0856-0920-1330',
                'group' => 'kontak',
            ],
            [
                'key' => 'kontak_kbm_putri',
                'value' => '0813-2617-4337',
                'group' => 'kontak',
            ],
            [
                'key' => 'kontak_keuangan_spp',
                'value' => '0852-9042-9617',
                'group' => 'kontak',
            ],
            [
                'key' => 'kontak_infaq_saku',
                'value' => '0821-3342-5328',
                'group' => 'kontak',
            ],
            [
                'key' => 'alamat_kampus',
                'value' => 'Dusun Tuksongo RT 01 / RW 01, Desa Nglorog, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah 56272',
                'group' => 'kontak',
            ],

            // TAHUN AJARAN & FOOTER
            [
                'key' => 'tahun_ajaran',
                'value' => '2026/2027',
                'group' => 'general',
            ],
            [
                'key' => 'footer_title',
                'value' => 'Pondok Pesantren Hidayatullah Tuksongo',
                'group' => 'kontak',
            ],
            [
                'key' => 'footer_description',
                'value' => 'Membentuk generasi Qur\'ani yang berakhlak mulia, berwawasan global, mandiri, dan berakar kuat pada nilai-nilai Panca Jiwa Pesantren.',
                'group' => 'kontak',
            ],
            [
                'key' => 'footer_accreditation',
                'value' => 'Terakreditasi B (BAN-SM Kemenag) • NSPP: 512032304095',
                'group' => 'kontak',
            ],
            [
                'key' => 'footer_hotline',
                'value' => '0813-9110-9966',
                'group' => 'kontak',
            ],
            [
                'key' => 'footer_website',
                'value' => 'tuksongo.ponpes.id',
                'group' => 'kontak',
            ],
            [
                'key' => 'footer_copyright',
                'value' => '© 2026 Yayasan Hidayatullah Tuksongo Pringsurat Temanggung. Hak Cipta Dilindungi.',
                'group' => 'kontak',
            ],
            // HERO SLIDER & VISUAL SETTINGS
            [
                'key' => 'hero_image',
                'value' => '/uploads/settings/hero_slide_1.jpg',
                'group' => 'hero',
            ],
            [
                'key' => 'hero_slider_animation',
                'value' => 'fade',
                'group' => 'hero',
            ],
            [
                'key' => 'hero_slider_duration',
                'value' => '5',
                'group' => 'hero',
            ],
            [
                'key' => 'hero_slide_count',
                'value' => '5',
                'group' => 'hero',
            ],
            [
                'key' => 'hero_slides_json',
                'value' => json_encode([
                    [
                        'id' => 1,
                        'image' => '/uploads/settings/hero_slide_1.jpg',
                        'caption' => 'Kampus Alam Tuksongo Madani',
                        'subcaption' => 'Dusun Tuksongo, Nglorog, Pringsurat — Asri, hening, dan kondusif untuk tholabul \'ilmi',
                        'active' => true,
                    ],
                    [
                        'id' => 2,
                        'image' => '/uploads/settings/hero_slide_2.jpg',
                        'caption' => 'Halaqah Tahfidzul Qur\'an Bersanad',
                        'subcaption' => 'Bimbingan intensif mutqin bersama asatidz penghafal Al-Qur\'an',
                        'active' => true,
                    ],
                    [
                        'id' => 3,
                        'image' => '/uploads/settings/hero_slide_3.jpg',
                        'caption' => 'Kompleks Asrama & Kampus Modern',
                        'subcaption' => 'Lingkungan hunian santri yang bersih, tertib, sehat, dan islami 24 jam',
                        'active' => true,
                    ],
                    [
                        'id' => 4,
                        'image' => '/uploads/settings/hero_slide_4.jpg',
                        'caption' => 'Majelis Asatidz & Pendidik Amanah',
                        'subcaption' => 'Kaderisasi alumni Gontor & salafiyah berdedikasi mengabdi',
                        'active' => true,
                    ],
                    [
                        'id' => 5,
                        'image' => '/uploads/settings/hero_slide_5.jpg',
                        'caption' => 'Laboratorium CBT & Penunjang Digital',
                        'subcaption' => 'Fasilitas ujian mandiri dan penguasaan sains teknologi modern',
                        'active' => true,
                    ],
                ], JSON_PRETTY_PRINT),
                'group' => 'hero',
            ],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(
                ['key' => $s['key']],
                ['value' => $s['value'], 'group' => $s['group']]
            );
        }
    }
}

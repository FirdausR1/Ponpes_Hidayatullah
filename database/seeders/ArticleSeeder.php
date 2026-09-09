<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\PsbRegistration;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        Article::truncate();
        PsbRegistration::truncate();

        Article::create([
            'title' => "Kajian Ba'da Subuh: Menyelami Hadits Riyadhus Shalihin Bersama Pengasuh",
            'slug' => 'kajian-bada-subuh-riyadhus-shalihin',
            'category' => 'Kajian Subuh',
            'image' => 'https://lh3.googleusercontent.com/aida/AEtjO1VW8sKD3qKNjB17hrBMukEsjIz41v74a9jRjeC_EjQnjdVbz2exuZVPllgVCQvEqn3VhAfe8_eyNr1LoqK-0P2jNkggkd4p1OFkcGaA_1CeqW_74ktBQdtWn4zxr8XIvy5kG53aERTopwni1ufewEwwqhXcdRSaTPfhBvbKnL0oRKZwafElElsyve0gpMzovOceavOjfTl_8xRv6qGv_mdYO8BrmafkkKji2gVHMv_mt4s_A46QLEZBUiI',
            'excerpt' => 'Ratusan santri memenuhi serambi masjid utama mengkaji hadits nabawi bersama dewan asatidz sebelum memulai pemberian mufrodat dwibahasa.',
            'content' => "<p>Suasana fajar di Kampus Pondok Pesantren Hidayatullah Tuksongo senantiasa diselimuti kekhusyukan dzikir dan tilawah. Selepas pelaksanaan sholat Shubuh berjamaah, seluruh santri berkumpul di serambi masjid jami' untuk mengikuti halaqah pengajian kitab hadits monumentil <em>Riyadhus Shalihin</em> karya Imam An-Nawawi rahimahullah yang diampu langsung oleh pimpinan pondok pesantren.</p>

<p>Dalam taushiyahnya, pimpinan pondok menekankan betapa pentingnya menjaga kemurnian niat dan ketundukan hati dalam menuntut ilmu syar'i. <em>'Menuntut ilmu di pesantren bukan semata-mata mencari gelar atau sanjungan duniawi, melainkan melatih diri kita agar senantiasa ikhlas, tawadhu', dan mengamalkan setiap petunjuk Rasulullah shallallahu 'alaihi wa sallam dalam kehidupan sehari-hari,'</em> tegas beliau di hadapan para santri.</p>

<p>Kajian rutin ini merupakan agenda wajib harian yang diselenggarakan setiap ba'da Shubuh sebelum para santri beranjak ke agenda pemberian <em>mufrodat</em> (kosakata harian bahasa Arab dan Inggris). Melalui tradisi talaqqi ini, nilai-nilai akhlakul karimah dan kecintaan pada sunnah nabi senantiasa tertanam kuat dalam sanubari setiap santri sejak usia dini.</p>",
            'author' => 'Biro Penerangan Pesantren',
            'views' => 142,
            'status' => 'published',
            'published_at' => now()->subHours(5),
        ]);

        Article::create([
            'title' => 'Santri Hidayatullah Tuksongo Raih Prestasi Pencak Silat & Pidato Bahasa Arab',
            'slug' => 'santri-hidayatullah-raih-prestasi-silat-dan-pidato',
            'category' => 'Prestasi',
            'image' => 'https://lh3.googleusercontent.com/aida/AEtjO1V5RKWI0PI7YE8hSgP6qBH9LYEV_z2Y25wOveEvtjCWdCW71htHFGmlrx_E8VPEZGuA8HmvzP2JeXzqM2NH0eeASlAc_m-0AL7qkYM3jLa9HXTugd43Fq60R3BwZo6H-Yv0cUxDD0PL5NFttqKM_Qd76wHYv2buO2DJs_AISV59gjbJknMarzbPL-Y-uhHic7fN-eewZyVUak0wQck6DxbFrisCWMpVIp0kWfIul7UVp5qWz2z3njcYiMc',
            'excerpt' => 'Mengasah ketangkasan fisik dan kemampuan public speaking dalam Muhadhoroh tiga bahasa, kontingen santri berhasil mengukir prestasi gemilang tingkat daerah.',
            'content' => "<p>Kabar membanggakan kembali datang dari kontingen santri Pondok Pesantren Hidayatullah Tuksongo. Dalam ajang festival seni bela diri dan lomba kebahasaan yang diselenggarakan tingkat karesidenan, para santri berhasil memborong sejumlah piala kejuaraan pada cabang olahraga Pencak Silat Tapak Suci serta Lomba Khitobah (Pidato) Bahasa Arab.</p>

<p>Pencapaian ini membuktikan bahwa pembinaan terpadu antara keilmuan akademis, penguasaan dwibahasa aktif, dan ketahanan jasmani melalui kegiatan ekstrakurikuler di pesantren membuahkan hasil nyata. Keberanian santri tampil berpidato di hadapan dewan juri tanpa teks merupakan buah dari pembiasaan agenda rutin <em>Muhadhoroh Tiga Bahasa</em> (Arab, Inggris, Indonesia) yang diselenggarakan setiap Kamis siang di pondok.</p>

<p>Kepala Bagian Pengasuhan Santri menyampaikan apresiasi tinggi atas kerja keras para santri dan bimbingan telaten para pelatih. Beliau berpesan agar prestasi ini menjadi pemicu semangat bagi seluruh santri untuk terus berprestasi, tetap rendah hati, dan senantiasa bersyukur kepada Allah Subhanahu wa Ta'ala.</p>",
            'author' => 'Humas & Publikasi',
            'views' => 289,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        Article::create([
            'title' => "Halaqah Mudzakarah Kitab Turats & Muthola'ah di Ruang Perpustakaan",
            'slug' => 'halaqah-mudzakarah-kitab-turats-mutholaah',
            'category' => 'Literasi Turats',
            'image' => 'https://lh3.googleusercontent.com/aida/AEtjO1W5isZb65w-vt4ig5AjJywVzDovBv76VTjwGyKDusFibvFDU0utOINs01IgR3utqqm-4EllcdOy7J5jsYnCnvKxBSJzzM18NQFkfUdiY4JuCYhVvQGTaRp8OGVjJb6pigfX9rTMOv23wDRaok2jHJVr4GP8iISMEXHSKNuIWnPrzVThYoc8OUDpsrGHaxXFrgKLXhcp18e6OD3p2_tmid_DqSJz-Ssqxqn1dM-tUftlFIcKbf4QbfrivHQ',
            'excerpt' => "Aktivitas literasi intensif santri mengkaji referensi kitab Nahwu, Shorof, Fiqih Ghoyatu Taqrib, dan kitab hadits bersama asatidz pengampu.",
            'content' => "<p>Literasi keilmuan Islam klasik (turats) merupakan salah satu fondasi utama kurikulum di Pondok Pesantren Hidayatullah Tuksongo. Setiap sore dan malam hari, ruang perpustakaan pesantren dipenuhi santri yang khusyuk mengikuti kegiatan <em>Mudzakarah</em> dan <em>Muthola'ah</em> kitab kuning bersama asatidz pembimbing.</p>

<p>Kitab-kitab yang dikaji meliputi disiplin ilmu tata bahasa Arab (Nahwu & Shorof), fikih madzhab Syafi'i seperti <em>Matan Al-Ghoyah wat Taqrib</em> karya Al-Qadhi Abu Syuja', serta ilmu hadits dan tafsir tematik. Metode pembelajaran memadukan tradisi sorogan dan bandongan klasik dengan diskusi interaktif modern, sehingga santri tidak hanya hafal kaidah, tetapi juga mahir memahami konteks dan istinbath hukum secara bijaksana.</p>

<p>Dengan fasilitas ribuan koleksi kitab klasik yang lengkap di perpustakaan kampus, para santri dibekali kunci pemahaman terhadap warisan agung para ulama salafush shalih sebagai bekal dakwah di masa mendatang.</p>",
            'author' => 'Dewan Asatidz',
            'views' => 195,
            'status' => 'published',
            'published_at' => now()->subDays(2),
        ]);

        Article::create([
            'title' => 'Sosialisasi Tata Tertib & Buku Panduan Santri Baru Tahun Ajaran 2025/2026',
            'slug' => 'sosialisasi-tata-tertib-dan-buku-panduan-2025',
            'category' => 'Informasi PSB',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCRLDfaZnczeAOBMNdv3-O4fNJHuDHanm-hedGFiVj5hrUReVRsvzSgPxwKkbDQARwwCVj_-x201mE14Tj0x4r9wU1TIAWEkbejJX13PHjnbc0EMVq0strXuHCkm6RrQ7tXgFYIMTenYyYWuIW9h3zuoLAIWOfO-5Ihuu7qRkCD85qKWlmjCKqaQQFUDKhO25Yb18hCopf6DEGrLFi6iHqD7S18tpGA2HC8TgOqEcsZZocUAxzsq0lq',
            'excerpt' => 'Sekretariat Pimpinan dan Pengasuhan Santri menerbitkan Buku Panduan Santri edisi terbaru 81 halaman sebagai pedoman resmi santri dan wali santri.',
            'content' => "<p>Menyambut Tahun Ajaran Baru 2025/2026, Pondok Pesantren Hidayatullah Tuksongo Pringsurat resmi menerbitkan Buku Panduan Santri edisi terbaru setebal 81 halaman. Buku pedoman ini memuat secara komprehensif aturan tata tertib kedisiplinan santri, kode etik, daftar perlengkapan santri putra dan putri, jadwal kegiatan 24 jam, serta rincian biaya transparan.</p>

<p>Kepala Bagian Pengasuhan Santri menjelaskan bahwa buku pedoman ini dirancang agar tercipta kesepahaman yang utuh antara pihak pesantren, santri, dan orang tua wali santri. Buku panduan resmi ini dapat diunduh secara gratis dalam format PDF melalui website resmi pesantren.</p>",
            'author' => 'Sekretariat Pimpinan',
            'views' => 312,
            'status' => 'published',
            'published_at' => now()->subDays(3),
        ]);

        // Sample PSB Applicants
        PsbRegistration::create([
            'nama_lengkap' => 'Muhammad Rizky Ramadhan',
            'nisn' => '0098765432',
            'jenjang' => 'MTs Mukim',
            'jenis_kelamin' => 'Laki-laki',
            'nama_wali' => 'Bambang Sutrisno',
            'no_whatsapp' => '081234567890',
            'asal_sekolah' => 'SD Negeri 1 Pringsurat',
            'alamat' => 'Kec. Pringsurat, Kab. Temanggung',
            'status' => 'Menunggu',
        ]);

        PsbRegistration::create([
            'nama_lengkap' => 'Aisyah Nur Fathimah',
            'nisn' => '0087654321',
            'jenjang' => 'MA Mukim',
            'jenis_kelamin' => 'Perempuan',
            'nama_wali' => 'Ahmad Hidayat',
            'no_whatsapp' => '082198765432',
            'asal_sekolah' => 'MTs Negeri Temanggung',
            'alamat' => 'Kabupaten Magelang',
            'status' => 'Diterima',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\PsbRegistration;
use App\Models\Setting;

class AdminCbtController extends Controller
{
    /**
     * Daftar Bank Soal CBT
     */
    public function soalIndex(Request $request)
    {
        $kategori = $request->query('kategori');
        $search = $request->query('q');

        $query = Question::query();

        if (!empty($kategori)) {
            $query->where('kategori', $kategori);
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('soal', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $questions = $query->orderBy('kategori')->orderBy('id')->paginate(15)->withQueryString();

        $categories = Question::select('kategori')->distinct()->pluck('kategori');
        $totalQuestions = Question::count();
        $totalMath = Question::where('is_math', true)->count();
        $totalArabic = Question::where('is_arabic', true)->count();

        return view('admin.cbt.soal.index', compact(
            'questions', 'categories', 'kategori', 'search', 'totalQuestions', 'totalMath', 'totalArabic'
        ));
    }

    /**
     * Form Tambah Soal Manual
     */
    public function soalCreate()
    {
        $categories = $this->getCbtCategories();
        return view('admin.cbt.soal.create', compact('categories'));
    }

    /**
     * Simpan Soal Baru
     */
    public function soalStore(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:100',
            'soal' => 'required|string',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'is_math' => 'nullable|boolean',
            'is_arabic' => 'nullable|boolean',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'opsi_c' => 'required|string',
            'opsi_d' => 'required|string',
            'opsi_e' => 'nullable|string',
            'kunci_jawaban' => 'required|in:A,B,C,D,E',
            'bobot' => 'required|integer|min:1|max:100',
            'pembahasan' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_math'] = $request->has('is_math');
        $validated['is_arabic'] = $request->has('is_arabic');
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('gambar_file')) {
            $dest = public_path('uploads/cbt_soal');
            if (!file_exists($dest)) {
                mkdir($dest, 0777, true);
            }
            $file = $request->file('gambar_file');
            $filename = 'soal_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($dest, $filename);
            $validated['gambar'] = '/uploads/cbt_soal/' . $filename;
        }

        Question::create($validated);

        return redirect()->route('admin.cbt.soal.index')->with('success', 'Soal berhasil ditambahkan ke Bank Soal CBT!');
    }

    /**
     * Form Edit Soal
     */
    public function soalEdit($id)
    {
        $question = Question::findOrFail($id);
        $categories = $this->getCbtCategories();
        return view('admin.cbt.soal.edit', compact('question', 'categories'));
    }

    /**
     * Update Soal
     */
    public function soalUpdate(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $validated = $request->validate([
            'kategori' => 'required|string|max:100',
            'soal' => 'required|string',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'hapus_gambar' => 'nullable|boolean',
            'is_math' => 'nullable|boolean',
            'is_arabic' => 'nullable|boolean',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'opsi_c' => 'required|string',
            'opsi_d' => 'required|string',
            'opsi_e' => 'nullable|string',
            'kunci_jawaban' => 'required|in:A,B,C,D,E',
            'bobot' => 'required|integer|min:1|max:100',
            'pembahasan' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_math'] = $request->has('is_math');
        $validated['is_arabic'] = $request->has('is_arabic');
        $validated['is_active'] = $request->has('is_active');

        if ($request->has('hapus_gambar') && $request->hapus_gambar == '1') {
            if ($question->gambar && file_exists(public_path($question->gambar))) {
                @unlink(public_path($question->gambar));
            }
            $validated['gambar'] = null;
        }

        if ($request->hasFile('gambar_file')) {
            if ($question->gambar && file_exists(public_path($question->gambar))) {
                @unlink(public_path($question->gambar));
            }
            $dest = public_path('uploads/cbt_soal');
            if (!file_exists($dest)) {
                mkdir($dest, 0777, true);
            }
            $file = $request->file('gambar_file');
            $filename = 'soal_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($dest, $filename);
            $validated['gambar'] = '/uploads/cbt_soal/' . $filename;
        }

        $question->update($validated);

        return redirect()->route('admin.cbt.soal.index')->with('success', 'Soal berhasil diperbarui!');
    }

    /**
     * Hapus Soal
     */
    public function soalDestroy($id)
    {
        $question = Question::findOrFail($id);
        if ($question->gambar && file_exists(public_path($question->gambar))) {
            @unlink(public_path($question->gambar));
        }
        $question->delete();

        return redirect()->route('admin.cbt.soal.index')->with('success', 'Soal berhasil dihapus dari Bank Soal.');
    }

    /**
     * Muat Template Soal Siap Pakai (Matematika KaTeX, Bahasa Arab Harakat & Gundul, Bahasa Indo, Bahasa Inggris, PAI)
     */
    public function loadTemplate(Request $request)
    {
        $mode = $request->input('mode', 'append'); // 'append' or 'replace'

        if ($mode === 'replace') {
            Question::truncate();
        }

        $templates = [
            // ===== 1. MATEMATIKA (KaTeX Formulas) =====
            [
                'kategori' => 'Matematika',
                'soal' => 'Hasil perhitungan dari penjumlahan dan pengurangan pecahan berikut adalah:\n$$\\frac{3}{4} + \\frac{2}{5} - \\frac{1}{2} = \\dots$$',
                'is_math' => true,
                'is_arabic' => false,
                'opsi_a' => '$\\frac{13}{20}$',
                'opsi_b' => '$\\frac{7}{20}$',
                'opsi_c' => '$\\frac{11}{20}$',
                'opsi_d' => '$\\frac{9}{20}$',
                'opsi_e' => '$\\frac{17}{20}$',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Samakan penyebut ke 20: 15/20 + 8/20 - 10/20 = 13/20.',
            ],
            [
                'kategori' => 'Matematika',
                'soal' => 'Akar-akar penyelesaian dari persamaan kuadrat berikut adalah:\n$$x^2 - 5x + 6 = 0$$',
                'is_math' => true,
                'is_arabic' => false,
                'opsi_a' => '$x = 2$ atau $x = 3$',
                'opsi_b' => '$x = -2$ atau $x = -3$',
                'opsi_c' => '$x = 1$ atau $x = 6$',
                'opsi_d' => '$x = -1$ atau $x = 6$',
                'opsi_e' => '$x = 0$ atau $x = 5$',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Faktorisasi: (x - 2)(x - 3) = 0, sehingga x = 2 atau x = 3.',
            ],
            [
                'kategori' => 'Matematika',
                'soal' => 'Hitunglah nilai operasi bentuk akar berikut:\n$$\\sqrt{144} + \\sqrt{225} - \\sqrt{81} = \\dots$$',
                'is_math' => true,
                'is_arabic' => false,
                'opsi_a' => '18',
                'opsi_b' => '20',
                'opsi_c' => '22',
                'opsi_d' => '24',
                'opsi_e' => '16',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'sqrt(144) = 12, sqrt(225) = 15, sqrt(81) = 9. Maka 12 + 15 - 9 = 18.',
            ],
            [
                'kategori' => 'Matematika',
                'soal' => 'Sebuah bak penampungan air wudhu santri berbentuk balok memiliki panjang $p = 4\\text{ m}$, lebar $l = 2{,}5\\text{ m}$, dan kedalaman $t = 1{,}2\\text{ m}$. Volume air maksimal yang dapat ditampung adalah...',
                'is_math' => true,
                'is_arabic' => false,
                'opsi_a' => '$12\\text{ m}^3$ ($12.000\\text{ liter}$)',
                'opsi_b' => '$10\\text{ m}^3$ ($10.000\\text{ liter}$)',
                'opsi_c' => '$15\\text{ m}^3$ ($15.000\\text{ liter}$)',
                'opsi_d' => '$8\\text{ m}^3$ ($8.000\\text{ liter}$)',
                'opsi_e' => '$14\\text{ m}^3$ ($14.000\\text{ liter}$)',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'V = p x l x t = 4 x 2.5 x 1.2 = 12 m3 = 12.000 liter.',
            ],
            [
                'kategori' => 'Matematika',
                'soal' => 'Di sebuah asrama pesantren, persediaan beras cukup untuk $60$ santri selama $24$ hari. Jika jumlah santri bertambah menjadi $80$ santri, persediaan beras tersebut akan habis dalam waktu...',
                'is_math' => true,
                'is_arabic' => false,
                'opsi_a' => '18 hari',
                'opsi_b' => '20 hari',
                'opsi_c' => '16 hari',
                'opsi_d' => '22 hari',
                'opsi_e' => '15 hari',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Perbandingan berbalik nilai: (60 x 24) / 80 = 1440 / 80 = 18 hari.',
            ],

            // ===== 2. BAHASA ARAB (Berharakat & Gundul) =====
            [
                'kategori' => 'Bahasa Arab',
                'soal' => 'مَا مَعْنَى قَوْلِهِ تَعَالَى فِي سُوْرَةِ الشَّرْحِ: ﴿فَإِنَّ مَعَ الْعُسْرِ يُسْرًا ۝ إِنَّ مَعَ الْعُسْرِ يُسْرًا﴾؟',
                'is_math' => false,
                'is_arabic' => true,
                'opsi_a' => 'Sesungguhnya beserta kesulitan ada kemudahan',
                'opsi_b' => 'Dan hanya kepada Tuhanmulah hendaknya kamu berharap',
                'opsi_c' => 'Bukankah Kami telah melapangkan dadamu wahai Muhammad',
                'opsi_d' => 'Dan Kami telah menurunkan beban berat darimu',
                'opsi_e' => 'Maka apabila engkau telah selesai urusan, tetaplah bekerja keras',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Al-Usr = kesulitan, Yusr = kemudahan.',
            ],
            [
                'kategori' => 'Bahasa Arab',
                'soal' => 'مَا هُوَ إِعْرَابُ كَلِمَةِ «الْقُرْآنَ» فِي الْجُمْلَةِ التَّالِيَةِ:\n﴿قَرَأَ التِّلْمِيْذُ النَّجِيْبُ الْقُرْآنَ بِالتَّرْتِيْلِ﴾؟',
                'is_math' => false,
                'is_arabic' => true,
                'opsi_a' => 'مَفْعُوْلٌ بِهِ مَنْصُوْبٌ وَعَلَامَةُ نَصْبِهِ الْفَتْحَةُ (Maf\'ul bih manshub)',
                'opsi_b' => 'فَاعِلٌ مَرْفُوْعٌ وَعَلَامَةُ رَفْعِهِ الضَّمَّةُ (Fa\'il marfu\')',
                'opsi_c' => 'مُبْتَدَأٌ مَرْفُوْعٌ وَعَلَامَةُ رَفْعِهِ الضَّمَّةُ (Mubtada\' marfu\')',
                'opsi_d' => 'خَبَرٌ مَرْفُوْعٌ وَعَلَامَةُ رَفْعِهِ الضَّمَّةُ (Khabar marfu\')',
                'opsi_e' => 'نَعْتٌ مَجْرُوْرٌ بِالْكَسْرَةِ (Na\'at majrur)',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Al-Qur\'an berkedudukan sebagai objek (maf\'ul bih) dari kata kerja qara\'a.',
            ],
            [
                'kategori' => 'Bahasa Arab',
                'soal' => 'قال رسول الله صلى الله عليه وسلم: «طلب العلم فريضة على كل مسلم».\nالمعنى الصحيح والواضح لكلمة (فريضة) في هذا الحديث الشريف هو...',
                'is_math' => false,
                'is_arabic' => true,
                'opsi_a' => 'Kewajiban mutlak yang harus dilaksanakan',
                'opsi_b' => 'Amalan sunnah yang sangat dianjurkan',
                'opsi_c' => 'Perkara mubah yang boleh dipilih',
                'opsi_d' => 'Amalan sedekah yang bersifat sukarela',
                'opsi_e' => 'Budi pekerti pelengkap kehidupan',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Faridhah berarti kewajiban mutlak (fardhu).',
            ],
            [
                'kategori' => 'Bahasa Arab',
                'soal' => 'ذهب الطلاب في الصباح الباكر الى المسجد الجامع لاداء صلاة الفجر ثم جلسوا في حلقة حفظ القرآن مع الاساتذة.\nالسؤال: متى ذهب الطلاب الى المسجد؟',
                'is_math' => false,
                'is_arabic' => true,
                'opsi_a' => 'في الصباح الباكر قبل طلوع الشمس (Pagi-pagi buta)',
                'opsi_b' => 'في وقت الظهيرة بعد الزوال (Siang hari)',
                'opsi_c' => 'في المساء بعد صلاة المغرب (Malam hari)',
                'opsi_d' => 'في يوم العطلة الاسبوعية (Hari libur)',
                'opsi_e' => 'في منتصف الليل (Tengah malam)',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Teks Arab gundul menyatakan: dzahaba ath-thullab fi ash-shabaah al-baakir...',
            ],
            [
                'kategori' => 'Bahasa Arab',
                'soal' => 'مَا هُوَ مُفْرَدُ كَلِمَةِ «أَسَاتِذَةٌ» (Asaatidzatun) فِي اللُّغَةِ الْعَرَبِيَّةِ؟',
                'is_math' => false,
                'is_arabic' => true,
                'opsi_a' => 'أُسْتَاذٌ (Ustadz / Guru laki-laki)',
                'opsi_b' => 'تِلْمِيْذٌ (Murid laki-laki)',
                'opsi_c' => 'كِتَابٌ (Buku bacaan)',
                'opsi_d' => 'مَدْرَسَةٌ (Gedung sekolah)',
                'opsi_e' => 'مَجْلِسٌ (Tempat duduk)',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Asaatidzatun adalah bentuk jamak taksir dari mufrod Ustaadzun.',
            ],

            // ===== 3. BAHASA INDONESIA =====
            [
                'kategori' => 'Bahasa Indonesia',
                'soal' => 'Bacalah kutipan paragraf berikut dengan cermat:\n"Pendidikan pondok pesantren memadukan keteladanan akhlak mulia, bimbingan hafalan Al-Qur\'an secara intensif, dan pembelajaran kurikulum sains modern. Para santri dilatih untuk mandiri mengelola waktu, membersihkan lingkungan, dan berdisiplin tinggi dalam menuntut ilmu."\n\nIde pokok dari kutipan paragraf di atas adalah...',
                'is_math' => false,
                'is_arabic' => false,
                'opsi_a' => 'Keterpaduan pendidikan karakter, tahfidz, dan kemandirian santri di pondok pesantren',
                'opsi_b' => 'Kewajiban santri membersihkan asrama dan lingkungan pondok',
                'opsi_c' => 'Jadwal belajar santri yang sangat ketat dan menguras tenaga',
                'opsi_d' => 'Perbedaan antara kurikulum umum dan kurikulum pesantren salaf',
                'opsi_e' => 'Sarana dan prasarana laboratorium modern di lingkungan santri',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Paragraf membahas konsep pendidikan integral dan pembentukan karakter mandiri santri.',
            ],
            [
                'kategori' => 'Bahasa Indonesia',
                'soal' => 'Deretan kata berikut yang semuanya merupakan kata baku sesuai Pedoman Umum Ejaan Bahasa Indonesia (PUEBI) adalah...',
                'is_math' => false,
                'is_arabic' => false,
                'opsi_a' => 'Ijazah, nasihat, kualitas, praktik, apotek',
                'opsi_b' => 'Ijasah, nasehat, kwalitas, praktek, apotik',
                'opsi_c' => 'Ijazah, nasehat, kualitas, praktek, apotek',
                'opsi_d' => 'Ijasah, nasihat, kwalitas, praktik, apotik',
                'opsi_e' => 'Ijazah, nasihat, kwalitet, praktek, apotik',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Kata baku menurut KBBI/PUEBI: ijazah, nasihat, kualitas, praktik, apotek.',
            ],
            [
                'kategori' => 'Bahasa Indonesia',
                'soal' => '"Buku dan ilmu pengetahuan adalah jendela dunia yang menuntun manusia keluar dari kegelapan kebodohan."\nMajas yang digunakan pada kalimat tersebut adalah...',
                'is_math' => false,
                'is_arabic' => false,
                'opsi_a' => 'Metafora',
                'opsi_b' => 'Personifikasi',
                'opsi_c' => 'Hiperbola',
                'opsi_d' => 'Litotes',
                'opsi_e' => 'Ironi',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Metafora membandingkan buku secara langsung dengan jendela dunia tanpa kata pembanding.',
            ],

            // ===== 4. BAHASA INGGRIS =====
            [
                'kategori' => 'Bahasa Inggris',
                'soal' => 'Choose the correct verb to complete the sentence:\n"Every dawn, before the morning class begins, all santris ... the holy Qur\'an together in the mosque."',
                'is_math' => false,
                'is_arabic' => false,
                'opsi_a' => 'recite',
                'opsi_b' => 'recites',
                'opsi_c' => 'recited',
                'opsi_d' => 'reciting',
                'opsi_e' => 'has recited',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Subject "all santris" is plural, with present habit "Every dawn", so use base verb "recite".',
            ],
            [
                'kategori' => 'Bahasa Inggris',
                'soal' => 'Read the short conversation:\nFatih: "Excuse me, Sir. Could you tell me where the Computer CBT Laboratory is?"\nMr. Hanif: "Go straight down this hall, turn left at the library, and it is the second door on your right."\n\nWhat is Mr. Hanif doing?',
                'is_math' => false,
                'is_arabic' => false,
                'opsi_a' => 'Giving direction to a location',
                'opsi_b' => 'Asking for help from a student',
                'opsi_c' => 'Inviting someone to study in the library',
                'opsi_d' => 'Reporting an academic violation',
                'opsi_e' => 'Opening the examination portal',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Mr. Hanif provides step-by-step directions to the CBT room.',
            ],
            [
                'kategori' => 'Bahasa Inggris',
                'soal' => '"The Islamic boarding school strongly upholds the values of integrity, honesty, and mutual brotherhood."\nThe word "integrity" in the sentence is closest in meaning to...',
                'is_math' => false,
                'is_arabic' => false,
                'opsi_a' => 'Moral uprightness and honesty',
                'opsi_b' => 'Physical strength and speed',
                'opsi_c' => 'Financial wealth and fame',
                'opsi_d' => 'Strict physical punishment',
                'opsi_e' => 'Technical machine operation',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Integrity means moral uprightness, honesty, and sincerity.',
            ],

            // ===== 5. PENDIDIKAN AGAMA ISLAM & TAJWID =====
            [
                'kategori' => 'Tajwid & Al-Qur\'an',
                'soal' => 'Hukum bacaan tajwid nun mati atau sukun (نْ) bertemu dengan huruf Ba (ب) disertai ghunnah (mendengung) disebut dengan...',
                'is_math' => false,
                'is_arabic' => false,
                'opsi_a' => 'Iqlab',
                'opsi_b' => 'Idzhar Halqi',
                'opsi_c' => 'Idgham Bighunnah',
                'opsi_d' => 'Ikhfa Haqiqi',
                'opsi_e' => 'Idgham Bilaghunnah',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Iqlab terjadi ketika nun sukun atau tanwin bertemu huruf ba, suara nun berganti menjadi mim.',
            ],
            [
                'kategori' => 'Pendidikan Agama Islam',
                'soal' => 'Di antara 25 nabi dan rasul yang wajib diimani, terdapat 5 nabi dan rasul yang bergelar "Ulul \'Azmi" karena keteguhan hati dan kesabaran luar biasa dalam mengemban dakwah, yaitu...',
                'is_math' => false,
                'is_arabic' => false,
                'opsi_a' => 'Nuh AS, Ibrahim AS, Musa AS, Isa AS, dan Muhammad SAW',
                'opsi_b' => 'Adam AS, Idris AS, Nuh AS, Ibrahim AS, dan Ismail AS',
                'opsi_c' => 'Musa AS, Harun AS, Daud AS, Sulaiman AS, dan Isa AS',
                'opsi_d' => 'Yusuf AS, Yunus AS, Zakariya AS, Yahya AS, dan Muhammad SAW',
                'opsi_e' => 'Luth AS, Ya\'qub AS, Syu\'aib AS, Ayyub AS, dan Dzulkifli AS',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => '5 Rasul Ulul Azmi disingkat NIMIM: Nuh, Ibrahim, Musa, Isa, Muhammad SAW.',
            ],
            [
                'kategori' => 'Kepesantrenan',
                'soal' => 'Pondok pesantren menanamkan 5 pilar filosofis yang menjadi pondasi karakter seluruh santri, yaitu keikhlasan, kesederhanaan, kemandirian (berdikari), ukhuwah Islamiyah, dan kebebasan yang bertanggung jawab. Kelima pilar tersebut dikenal dengan istilah...',
                'is_math' => false,
                'is_arabic' => false,
                'opsi_a' => 'Panca Jiwa Pondok Pesantren',
                'opsi_b' => 'Panca Jangka Pesantren',
                'opsi_c' => 'Catur Asas Kepemimpinan',
                'opsi_d' => 'Trisatya Penuntut Ilmu',
                'opsi_e' => 'Dasa Darma Santri Mukim',
                'kunci_jawaban' => 'A',
                'bobot' => 1,
                'pembahasan' => 'Panca Jiwa Pesantren adalah 5 nilai dasar yang membentuk akhlak santri.',
            ],
        ];

        foreach ($templates as $idx => $t) {
            $t['urutan'] = $idx + 1;
            $t['is_active'] = true;
            Question::create($t);
        }

        $count = count($templates);
        return redirect()->route('admin.cbt.soal.index')->with('success', "Berhasil memuat {$count} template soal seleksi (Matematika KaTeX, Bahasa Arab Harakat & Gundul, Bahasa Indonesia, Bahasa Inggris, PAI & Tajwid)!");
    }

    /**
     * Halaman Pengaturan Ujian CBT
     */
    public function pengaturan()
    {
        $settings = [
            'cbt_status' => Setting::get('cbt_status', 'buka'),
            'cbt_duration_minutes' => (int) Setting::get('cbt_duration_minutes', 60),
            'cbt_question_count' => (int) Setting::get('cbt_question_count', 0),
            'cbt_shuffle_questions' => Setting::get('cbt_shuffle_questions', '1'),
            'cbt_shuffle_options' => Setting::get('cbt_shuffle_options', '1'),
            'cbt_passing_grade' => (int) Setting::get('cbt_passing_grade', 70),
            'cbt_max_violations' => (int) Setting::get('cbt_max_violations', 3),
            'cbt_exam_title' => Setting::get('cbt_exam_title', 'Ujian Masuk Seleksi Santri Baru TA ' . Setting::get('tahun_ajaran', '2026/2027')),
            'cbt_instructions' => Setting::get('cbt_instructions', "1. Berdoalah sebelum memulai ujian.\n2. Dilarang membuka tab lain, mencari jawaban, atau beralih aplikasi selama ujian.\n3. Setiap indikasi kecurangan akan terdeteksi oleh sistem pengawas otomatis.\n4. Ujian akan otomatis di-submit jika Anda melanggar lebih dari 3 kali atau saat waktu habis."),
            'cbt_enable_schedule' => Setting::get('cbt_enable_schedule', '0'),
            'cbt_start_date' => Setting::get('cbt_start_date', now()->format('Y-m-d\T08:00')),
            'cbt_end_date' => Setting::get('cbt_end_date', now()->addDays(7)->format('Y-m-d\T23:59')),
            'cbt_max_attempts' => (int) Setting::get('cbt_max_attempts', 1),
        ];

        $totalQuestionsInBank = Question::where('is_active', true)->count();

        // Semua kategori materi ujian
        $allCategories = $this->getCbtCategories();

        // Konfigurasi Jadwal Per Materi Soal
        $storedCategorySchedules = json_decode(Setting::get('cbt_category_schedules', '[]'), true) ?: [];
        $categorySchedules = [];

        foreach ($allCategories as $cat) {
            $questionCountInCat = Question::where('kategori', $cat)->where('is_active', true)->count();
            $conf = $storedCategorySchedules[$cat] ?? [];

            $categorySchedules[$cat] = [
                'kategori' => $cat,
                'status' => $conf['status'] ?? 'buka',
                'enable_schedule' => !empty($conf['enable_schedule']),
                'start_date' => $conf['start_date'] ?? now()->format('Y-m-d\T08:00'),
                'end_date' => $conf['end_date'] ?? now()->addDays(7)->format('Y-m-d\T23:59'),
                'duration_minutes' => (int) ($conf['duration_minutes'] ?? 60),
                'question_count' => (int) ($conf['question_count'] ?? 0),
                'total_questions_in_bank' => $questionCountInCat,
            ];
        }

        return view('admin.cbt.pengaturan', compact(
            'settings', 'totalQuestionsInBank', 'categorySchedules', 'allCategories'
        ));
    }

    /**
     * Simpan Pengaturan Ujian CBT
     */
    public function pengaturanUpdate(Request $request)
    {
        $validated = $request->validate([
            'cbt_status' => 'required|in:buka,tutup',
            'cbt_duration_minutes' => 'required|integer|min:5|max:300',
            'cbt_question_count' => 'required|integer|min:0',
            'cbt_passing_grade' => 'required|integer|min:0|max:100',
            'cbt_max_violations' => 'required|integer|min:1|max:10',
            'cbt_exam_title' => 'required|string|max:255',
            'cbt_instructions' => 'nullable|string',
            'cbt_enable_schedule' => 'nullable',
            'cbt_start_date' => 'nullable|string',
            'cbt_end_date' => 'nullable|string',
        ]);

        Setting::set('cbt_status', $validated['cbt_status'], 'cbt');
        Setting::set('cbt_duration_minutes', $validated['cbt_duration_minutes'], 'cbt');
        Setting::set('cbt_question_count', $validated['cbt_question_count'], 'cbt');
        Setting::set('cbt_shuffle_questions', $request->has('cbt_shuffle_questions') ? '1' : '0', 'cbt');
        Setting::set('cbt_shuffle_options', $request->has('cbt_shuffle_options') ? '1' : '0', 'cbt');
        Setting::set('cbt_passing_grade', $validated['cbt_passing_grade'], 'cbt');
        Setting::set('cbt_max_violations', $validated['cbt_max_violations'], 'cbt');
        Setting::set('cbt_exam_title', $validated['cbt_exam_title'], 'cbt');
        Setting::set('cbt_instructions', $validated['cbt_instructions'] ?? '', 'cbt');
        Setting::set('cbt_enable_schedule', $request->has('cbt_enable_schedule') ? '1' : '0', 'cbt');
        Setting::set('cbt_start_date', $request->input('cbt_start_date', ''), 'cbt');
        Setting::set('cbt_end_date', $request->input('cbt_end_date', ''), 'cbt');
        Setting::set('cbt_max_attempts', (string) $request->input('cbt_max_attempts', '1'), 'cbt');

        // Simpan Jadwal Khusus Per Materi (Matematika, Bahasa Arab, dll)
        if ($request->has('category_schedules') && is_array($request->category_schedules)) {
            $savedCategorySchedules = [];
            foreach ($request->category_schedules as $catName => $catData) {
                $savedCategorySchedules[$catName] = [
                    'kategori' => $catName,
                    'status' => $catData['status'] ?? 'buka',
                    'enable_schedule' => !empty($catData['enable_schedule']),
                    'start_date' => $catData['start_date'] ?? '',
                    'end_date' => $catData['end_date'] ?? '',
                    'duration_minutes' => max(5, (int) ($catData['duration_minutes'] ?? 60)),
                    'question_count' => max(0, (int) ($catData['question_count'] ?? 0)),
                ];
            }
            Setting::set('cbt_category_schedules', json_encode($savedCategorySchedules), 'cbt');
        }

        return back()->with('success', 'Pengaturan ujian CBT, rentang jadwal per materi (Matematika, B. Arab, dll), passing grade (KKM), dan sistem anti-contek berhasil disimpan!');
    }

    /**
     * Halaman Khusus Menu Dongkrak Nilai Siswa Biar Lulus
     */
    public function dongkrakIndex(Request $request)
    {
        $kkm = (int) Setting::get('cbt_passing_grade', 70);
        $search = $request->query('q');
        $filter = $request->query('filter', 'belum_lulus'); // 'belum_lulus', 'semua', 'lulus'

        $query = PsbRegistration::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_registrasi', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($filter === 'belum_lulus') {
            $query->where(function($q) use ($kkm) {
                $q->where(function($sub) use ($kkm) {
                    $sub->whereNull('status_kelulusan_override')
                        ->where(function($s) use ($kkm) {
                            $s->whereNull('nilai_ujian')->orWhere('nilai_ujian', '<', $kkm);
                        });
                })->orWhere('status_kelulusan_override', 'Tidak Lulus');
            });
        } elseif ($filter === 'lulus') {
            $query->where(function($q) use ($kkm) {
                $q->where('status_kelulusan_override', 'Lulus')
                  ->orWhere(function($sub) use ($kkm) {
                      $sub->whereNull('status_kelulusan_override')
                          ->where('nilai_ujian', '>=', $kkm);
                  });
            });
        }

        $students = $query->latest()->paginate(25)->withQueryString();

        $totalStudents = PsbRegistration::count();
        $totalBelumLulus = PsbRegistration::where(function($q) use ($kkm) {
            $q->where(function($sub) use ($kkm) {
                $sub->whereNull('status_kelulusan_override')
                    ->where(function($s) use ($kkm) {
                        $s->whereNull('nilai_ujian')->orWhere('nilai_ujian', '<', $kkm);
                    });
            })->orWhere('status_kelulusan_override', 'Tidak Lulus');
        })->count();

        $totalLulus = PsbRegistration::where(function($q) use ($kkm) {
            $q->where('status_kelulusan_override', 'Lulus')
              ->orWhere(function($sub) use ($kkm) {
                  $sub->whereNull('status_kelulusan_override')
                      ->where('nilai_ujian', '>=', $kkm);
              });
        })->count();

        return view('admin.cbt.dongkrak', compact('students', 'kkm', 'search', 'filter', 'totalStudents', 'totalBelumLulus', 'totalLulus'));
    }

    /**
     * Aksi Massal (Bulk) Dongkrak Nilai Siswa
     */
    public function bulkDongkrak(Request $request)
    {
        $kkm = (int) Setting::get('cbt_passing_grade', 70);
        $targetScore = max(0, min(100, (int) $request->input('target_score', $kkm)));
        $alasan = $request->input('alasan_afirmasi', 'Afirmasi panitia seleksi agar memenuhi kualifikasi kelulusan santri');
        $mode = $request->input('mode', 'selected'); // 'selected' or 'all_failing'

        if ($mode === 'all_failing') {
            $failing = PsbRegistration::where(function($q) use ($kkm) {
                $q->where(function($sub) use ($kkm) {
                    $sub->whereNull('status_kelulusan_override')
                        ->where(function($s) use ($kkm) {
                            $s->whereNull('nilai_ujian')->orWhere('nilai_ujian', '<', $kkm);
                        });
                })->orWhere('status_kelulusan_override', 'Tidak Lulus');
            })->get();

            $count = 0;
            foreach ($failing as $reg) {
                $reg->update([
                    'nilai_ujian' => $targetScore,
                    'status_ujian' => 'Selesai',
                    'status_kelulusan_override' => 'Lulus',
                    'catatan_penguji' => $alasan,
                    'ujian_selesai_at' => $reg->ujian_selesai_at ?: now(),
                ]);
                $count++;
            }

            return back()->with('success', "Berhasil mendongkrak nilai {$count} santri menjadi {$targetScore} dengan status LULUS!");
        }

        $ids = $request->input('student_ids', []);
        if (empty($ids)) {
            return back()->with('warning', 'Pilih minimal satu santri yang ingin didongkrak nilainya.');
        }

        $count = 0;
        foreach ($ids as $id) {
            $reg = PsbRegistration::find($id);
            if ($reg) {
                $reg->update([
                    'nilai_ujian' => $targetScore,
                    'status_ujian' => 'Selesai',
                    'status_kelulusan_override' => 'Lulus',
                    'catatan_penguji' => $alasan,
                    'ujian_selesai_at' => $reg->ujian_selesai_at ?: now(),
                ]);
                $count++;
            }
        }

        return back()->with('success', "Berhasil mendongkrak nilai {$count} santri terpilih menjadi {$targetScore} dengan status LULUS!");
    }

    /**
     * Rekapitulasi Hasil Nilai & Kelulusan CBT
     */
    public function hasilIndex(Request $request)
    {
        $statusFilter = $request->query('status');
        $kelulusanFilter = $request->query('kelulusan');
        $search = $request->query('q');

        $query = PsbRegistration::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_registrasi', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if (!empty($statusFilter)) {
            $query->where('status_ujian', $statusFilter);
        }

        $students = $query->latest()->paginate(20)->withQueryString();

        $kkm = (int) Setting::get('cbt_passing_grade', 70);

        $totalParticipants = PsbRegistration::count();
        $totalFinished = PsbRegistration::where('status_ujian', 'Selesai')->count();
        $totalPassed = PsbRegistration::where('status_ujian', 'Selesai')
            ->where(function($q) use ($kkm) {
                $q->where('status_kelulusan_override', 'Lulus')
                  ->orWhere(function($sub) use ($kkm) {
                      $sub->whereNull('status_kelulusan_override')
                          ->where('nilai_ujian', '>=', $kkm);
                  });
            })->count();

        $totalViolations = PsbRegistration::sum('pelanggaran_curang_count');

        return view('admin.cbt.hasil.index', compact(
            'students', 'statusFilter', 'kelulusanFilter', 'search', 'kkm',
            'totalParticipants', 'totalFinished', 'totalPassed', 'totalViolations'
        ));
    }

    /**
     * Override Nilai & Status Kelulusan oleh Dewan Penguji (Mendukung Dongkrak & Tambah Poin)
     */
    public function hasilUpdate(Request $request, $id)
    {
        $reg = PsbRegistration::findOrFail($id);

        $request->validate([
            'nilai_ujian' => 'nullable|integer|min:0|max:100',
            'tambah_poin' => 'nullable|integer',
            'dongkrak_kkm' => 'nullable',
            'status_kelulusan_override' => 'nullable|in:Lulus,Tidak Lulus,Lulus Bersyarat,Cadangan',
            'catatan_penguji' => 'nullable|string|max:1000',
        ]);

        $kkm = (int) Setting::get('cbt_passing_grade', 70);
        $updateData = [];

        // 1. Jika klik tombol "Dongkrak ke KKM (Otomatis Lulus)"
        if ($request->has('dongkrak_kkm')) {
            $targetScore = max($kkm, (int) ($request->input('nilai_ujian', $kkm)));
            $updateData['nilai_ujian'] = $targetScore;
            $updateData['status_ujian'] = 'Selesai';
            $updateData['status_kelulusan_override'] = 'Lulus';
            $updateData['catatan_penguji'] = $request->input('catatan_penguji') ?: "Nilai telah didongkrak menjadi {$targetScore} (Memenuhi KKM) oleh dewan penguji.";
            $updateData['ujian_selesai_at'] = $reg->ujian_selesai_at ?: now();
        }
        // 2. Jika menambah poin (+5, +10, +15, dll)
        elseif ($request->filled('tambah_poin')) {
            $tambah = (int) $request->tambah_poin;
            $baseScore = $reg->nilai_ujian !== null ? (int) $reg->nilai_ujian : 0;
            $newScore = max(0, min(100, $baseScore + $tambah));
            $updateData['nilai_ujian'] = $newScore;
            $updateData['status_ujian'] = 'Selesai';
            $updateData['catatan_penguji'] = $request->input('catatan_penguji') ?: "Penambahan nilai afirmasi +{$tambah} poin (dari {$baseScore} menjadi {$newScore}).";
            if ($newScore >= $kkm && empty($request->status_kelulusan_override)) {
                $updateData['status_kelulusan_override'] = 'Lulus';
            }
            $updateData['ujian_selesai_at'] = $reg->ujian_selesai_at ?: now();
        }
        // 3. Jika input nilai manual
        elseif ($request->has('nilai_ujian') && $request->nilai_ujian !== null && $request->nilai_ujian !== '') {
            $updateData['nilai_ujian'] = (int) $request->nilai_ujian;
            $updateData['status_ujian'] = 'Selesai';
            $updateData['status_kelulusan_override'] = $request->status_kelulusan_override ?: ($updateData['nilai_ujian'] >= $kkm ? 'Lulus' : 'Tidak Lulus');
            $updateData['catatan_penguji'] = $request->catatan_penguji;
            $updateData['ujian_selesai_at'] = $reg->ujian_selesai_at ?: now();
        } else {
            if ($request->has('status_kelulusan_override')) {
                $updateData['status_kelulusan_override'] = $request->status_kelulusan_override ?: null;
            }
            if ($request->has('catatan_penguji')) {
                $updateData['catatan_penguji'] = $request->catatan_penguji;
            }
        }

        $reg->update($updateData);

        return back()->with('success', "Data nilai dan kelulusan santri an. {$reg->nama_lengkap} ({$reg->no_registrasi}) berhasil diperbarui!");
    }

    /**
     * Cetak Lembar Soal Resmi (Print / PDF)
     */
    public function cetakSoal(Request $request)
    {
        $kategori = $request->query('kategori');
        $query = Question::where('is_active', true);
        if ($kategori) {
            $query->where('kategori', $kategori);
        }
        $questions = $query->orderBy('kategori')->orderBy('id')->get();
        $title = Setting::get('cbt_exam_title', 'Lembar Soal Seleksi Masuk Calon Santri Baru');

        return view('admin.cbt.cetak_soal', compact('questions', 'title', 'kategori'));
    }

    /**
     * Cetak Rekapitulasi Nilai & Kelulusan (Print / PDF)
     */
    public function cetakNilai(Request $request)
    {
        $kkm = (int) Setting::get('cbt_passing_grade', 70);
        $students = PsbRegistration::where('status_ujian', 'Selesai')
            ->orderByDesc('nilai_ujian')
            ->get();

        $title = 'Rekapitulasi Nilai Ujian Seleksi Masuk (CBT) TA ' . Setting::get('tahun_ajaran', '2026/2027');

        return view('admin.cbt.cetak_nilai', compact('students', 'kkm', 'title'));
    }

    // =========================================================
    //  KELOLA KATEGORI MATERI UJIAN (CRUD)
    // =========================================================

    /**
     * Dapatkan daftar seluruh kategori materi ujian.
     */
    public function getCbtCategories()
    {
        $raw = Setting::get('cbt_categories');
        if ($raw !== null) {
            $cats = json_decode($raw, true);
            if (is_array($cats)) {
                return array_values(array_filter($cats));
            }
        }
        $defaultCategories = ['Matematika', 'Bahasa Arab', 'Bahasa Indonesia', 'Bahasa Inggris', 'Pendidikan Agama Islam', 'Tajwid & Al-Qur\'an', 'Kepesantrenan'];
        $custom = json_decode(Setting::get('cbt_custom_categories', '[]'), true) ?: [];
        $merged = array_values(array_unique(array_merge($defaultCategories, $custom)));
        Setting::set('cbt_categories', json_encode($merged), 'cbt');
        return $merged;
    }

    /**
     * Tambah kategori materi baru.
     */
    public function kategoriStore(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        $nama = trim($request->input('nama_kategori'));
        $cats = $this->getCbtCategories();

        if (!in_array($nama, $cats)) {
            $cats[] = $nama;
            Setting::set('cbt_categories', json_encode(array_values($cats)), 'cbt');
        }

        return redirect()->route('admin.cbt.pengaturan')
            ->with('success', "Kategori '{$nama}' berhasil ditambahkan.");
    }

    /**
     * Ubah / rename nama kategori materi.
     */
    public function kategoriUpdate(Request $request)
    {
        $request->validate([
            'old_nama' => 'required|string|max:100',
            'new_nama' => 'required|string|max:100',
        ]);

        $old = trim($request->input('old_nama'));
        $new = trim($request->input('new_nama'));
        $cats = $this->getCbtCategories();

        $idx = array_search($old, $cats);
        if ($idx !== false) {
            $cats[$idx] = $new;
            $cats = array_values(array_unique($cats));
            Setting::set('cbt_categories', json_encode($cats), 'cbt');

            // Perbarui kategori di soal-soal terkait
            Question::where('kategori', $old)->update(['kategori' => $new]);

            // Perbarui jadwal jika ada
            $stored = json_decode(Setting::get('cbt_category_schedules', '[]'), true) ?: [];
            if (isset($stored[$old])) {
                $stored[$new] = $stored[$old];
                unset($stored[$old]);
                Setting::set('cbt_category_schedules', json_encode($stored), 'cbt');
            }
        }

        return redirect()->route('admin.cbt.pengaturan')
            ->with('success', "Kategori '{$old}' berhasil diperbarui menjadi '{$new}'.");
    }

    /**
     * Hapus kategori materi dari daftar.
     */
    public function kategoriDestroy(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        $nama = trim($request->input('nama_kategori'));
        $cats = $this->getCbtCategories();

        $cats = array_values(array_filter($cats, fn($c) => $c !== $nama));
        Setting::set('cbt_categories', json_encode($cats), 'cbt');

        // Hapus konfigurasi jadwal khusus untuk kategori ini jika ada
        $stored = json_decode(Setting::get('cbt_category_schedules', '[]'), true) ?: [];
        if (isset($stored[$nama])) {
            unset($stored[$nama]);
            Setting::set('cbt_category_schedules', json_encode($stored), 'cbt');
        }

        return redirect()->route('admin.cbt.pengaturan')
            ->with('success', "Kategori '{$nama}' berhasil dihapus dari daftar materi.");
    }

    // =========================================================
    //  IMPORT SOAL VIA CSV
    // =========================================================

    /**
     * Proses upload dan impor soal dari file CSV.
     *
     * Format kolom CSV (header baris pertama):
     * kategori, soal, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, kunci_jawaban, bobot, pembahasan, is_active
     *
     * Contoh kunci_jawaban: A / B / C / D / E
     */
    public function soalImport(Request $request)
    {
        $request->validate([
            'kategori_import' => 'required|string|max:100',
            'csv_file'        => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $kategori = trim($request->input('kategori_import'));
        $file     = $request->file('csv_file');

        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return back()->with('error', 'Gagal membuka file CSV.');
        }

        $header  = null;
        $count   = 0;
        $errors  = [];
        $rowNum  = 0;

        while (($row = fgetcsv($handle, 2000, ',')) !== false) {
            $rowNum++;

            // Baris pertama = header, skip
            if ($rowNum === 1) {
                $header = array_map('trim', $row);
                continue;
            }

            if (count($row) < 4) continue; // baris kosong

            // Buat associative array jika ada header, else pakai index
            $data = $header ? array_combine($header, array_pad($row, count($header), '')) : $row;

            // Soal wajib ada
            $soalText = trim($data['soal'] ?? $data[0] ?? '');
            if (empty($soalText)) continue;

            // Override kategori dengan pilihan admin
            $opsiA  = trim($data['opsi_a'] ?? $data[2] ?? '');
            $opsiB  = trim($data['opsi_b'] ?? $data[3] ?? '');
            $opsiC  = trim($data['opsi_c'] ?? $data[4] ?? '');
            $opsiD  = trim($data['opsi_d'] ?? $data[5] ?? '');
            $opsiE  = trim($data['opsi_e'] ?? $data[6] ?? '') ?: null;
            $kunci  = strtoupper(trim($data['kunci_jawaban'] ?? $data[7] ?? 'A'));
            $bobot  = max(1, (int) ($data['bobot'] ?? $data[8] ?? 1));
            $pembahasan = trim($data['pembahasan'] ?? $data[9] ?? '');

            if (empty($opsiA) || empty($opsiB) || empty($opsiC) || empty($opsiD)) {
                $errors[] = "Baris {$rowNum}: Opsi A-D tidak lengkap, dilewati.";
                continue;
            }

            if (!in_array($kunci, ['A', 'B', 'C', 'D', 'E'])) {
                $errors[] = "Baris {$rowNum}: Kunci jawaban '{$kunci}' tidak valid, dilewati.";
                continue;
            }

            // Auto-detect Arabic & Math flags dari seluruh konten soal + opsi
            $allText = $soalText . ' ' . $opsiA . ' ' . $opsiB . ' ' . $opsiC . ' ' . $opsiD . ' ' . ($opsiE ?? '') . ' ' . $pembahasan;
            [$isMath, $isArabic] = $this->detectTextFlags($allText, $kategori);

            // Izinkan override eksplisit dari kolom CSV jika ada
            if (!empty($data['is_math'])) {
                $isMath = in_array(strtolower(trim($data['is_math'])), ['1', 'true', 'yes', 'ya']);
            }
            if (!empty($data['is_arabic'])) {
                $isArabic = in_array(strtolower(trim($data['is_arabic'])), ['1', 'true', 'yes', 'ya']);
            }

            Question::create([
                'kategori'       => $kategori,
                'soal'           => $soalText,
                'is_math'        => $isMath,
                'is_arabic'      => $isArabic,
                'opsi_a'         => $opsiA,
                'opsi_b'         => $opsiB,
                'opsi_c'         => $opsiC,
                'opsi_d'         => $opsiD,
                'opsi_e'         => $opsiE,
                'kunci_jawaban'  => $kunci,
                'bobot'          => $bobot,
                'pembahasan'     => $pembahasan ?: null,
                'is_active'      => true,
            ]);

            $count++;
        }

        fclose($handle);

        $msg = "{$count} soal berhasil diimpor ke kategori '{$kategori}'";
        if (!empty($errors)) {
            $msg .= '. Beberapa baris dilewati: ' . implode(' | ', $errors);
        }

        return redirect()->route('admin.cbt.soal.index', ['kategori' => $kategori])
            ->with('success', $msg);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AUTO-DETECT HELPER
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Deteksi otomatis apakah teks mengandung konten Arab atau Matematika/IPA.
     *
     * @param  string $text     Gabungan seluruh teks soal + opsi
     * @param  string $kategori Nama kategori soal
     * @return array  [bool $isMath, bool $isArabic]
     */
    private function detectTextFlags(string $text, string $kategori): array
    {
        // ── Deteksi Bahasa Arab ───────────────────────────────────────────────
        // Karakter Arab: U+0600–U+06FF (Arab), U+0750–U+077F (Arab Supplement),
        // U+08A0–U+08FF (Arab Extended-A), U+FB50–U+FDFF (Presentasi Arab-A),
        // U+FE70–U+FEFF (Presentasi Arab-B)
        $isArabic = (bool) preg_match('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u', $text);

        // Nama kategori yang umumnya mengandung Arab
        if (!$isArabic) {
            $arabicCategories = ['bahasa arab', 'arab', 'fiqih', 'aqidah', 'akidah', 'quran', "al-qur'an", 'alquran', 'tajwid', 'kepesantrenan', 'nahwu', 'shorof', 'tafsir', 'hadits'];
            $katLower = mb_strtolower($kategori);
            foreach ($arabicCategories as $kw) {
                if (str_contains($katLower, $kw)) {
                    $isArabic = true;
                    break;
                }
            }
        }

        // ── Deteksi Matematika / IPA ──────────────────────────────────────────
        $isMath = false;

        // 1. Sintaks LaTeX / KaTeX
        if (preg_match('/\\\\[a-zA-Z]+|\$[^$]+\$|\\\\frac|\\\\sqrt|\\\\sum|\\\\int|\\\\lim|\\\\infty|\\\\alpha|\\\\beta|\\\\theta|\\\\pi|\^[{\d]|_[{\d]/', $text)) {
            $isMath = true;
        }

        // 2. Operator dan simbol matematika umum
        if (!$isMath && preg_match('/[∑∫√∞≠≤≥±×÷π²³°∆∇∈∉⊂⊃∪∩→←↔αβγδεζηθλμσφψω]/', $text)) {
            $isMath = true;
        }

        // 3. Ekspresi aljabar/aritmetika (x^2, 2x+3, sin(x), log, dll)
        if (!$isMath && preg_match('/\b(?:sin|cos|tan|log|ln|lim|\bx\^\d|\d+x|\bx\b.*[=+\-\*\/]|\d+\/\d+|[a-z]\^\d|\d+[a-zA-Z]\d*)\b/i', $text)) {
            $isMath = true;
        }

        // 4. Rumus kimia (H2O, CO2, NaCl, H2SO4, dll)
        if (!$isMath && preg_match('/\b[A-Z][a-z]?\d+|[A-Z]{2}\d|\b(?:OH|CO2|H2O|NaCl|H2SO4|HCl|NH3|C6H12O6)\b/', $text)) {
            $isMath = true;
        }

        // 5. Satuan fisika/IPA (m/s, km/h, Newton, Joule, Watt, mol, dll)
        if (!$isMath && preg_match('/\b\d+(?:[.,]\d+)?\s*(?:m\/s|km\/h|m\/s²|kg|Newton|Joule|Watt|Ohm|Ampere|Volt|mol|L|mL|°C|°K|atm|Pa|Hz|rpm)\b/i', $text)) {
            $isMath = true;
        }

        // 6. Nama kategori yang umumnya perlu render math
        if (!$isMath) {
            $mathCategories = ['matematika', 'fisika', 'kimia', 'biologi', 'ipa', 'sains', 'berhitung', 'aljabar', 'geometri', 'statistika', 'kalkulus', 'aritmatika'];
            $katLower = mb_strtolower($kategori);
            foreach ($mathCategories as $kw) {
                if (str_contains($katLower, $kw)) {
                    $isMath = true;
                    break;
                }
            }
        }

        return [$isMath, $isArabic];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // LIVE MONITORING CBT
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Halaman Dashboard Live Monitor Ujian.
     */
    public function monitoringIndex()
    {
        $totalPeserta   = PsbRegistration::whereNotNull('status_ujian')->count();
        $sedangUjian    = PsbRegistration::where('status_ujian', 'Sedang Ujian')->count();
        $selesaiUjian   = PsbRegistration::where('status_ujian', 'Selesai')->count();
        $belumUjian     = PsbRegistration::where(function($q) {
            $q->where('status_ujian', 'Belum Ujian')->orWhereNull('status_ujian');
        })->count();

        return view('admin.cbt.monitoring.index', compact(
            'totalPeserta', 'sedangUjian', 'selesaiUjian', 'belumUjian'
        ));
    }

    /**
     * Endpoint JSON Poll untuk Live Monitor (setiap 5 detik).
     */
    public function monitoringData(Request $request)
    {
        $filter = $request->query('filter', 'active'); // active | all | selesai

        $query = PsbRegistration::select([
            'id', 'no_registrasi', 'nama_lengkap', 'asal_sekolah',
            'status_ujian', 'nilai_ujian', 'cbt_last_activity_at',
            'cbt_current_question_index', 'jawaban_santri_json',
            'cbt_extra_time_minutes', 'pelanggaran_curang_count',
            'cbt_ragu_json', 'pelanggaran_curang_log', 'ujian_selesai_at',
        ]);

        if ($filter === 'active') {
            $query->where('status_ujian', 'Sedang Ujian');
        } elseif ($filter === 'selesai') {
            $query->where('status_ujian', 'Selesai');
        } else {
            $query->whereIn('status_ujian', ['Sedang Ujian', 'Selesai', 'Belum Ujian'])
                  ->orWhereNull('status_ujian');
        }

        $rows = $query->orderByRaw("FIELD(status_ujian, 'Sedang Ujian', 'Selesai', 'Belum Ujian')")
                      ->orderBy('cbt_last_activity_at', 'desc')
                      ->get();

        $now = now();
        $data = $rows->map(function ($reg) use ($now) {
            $answers    = !empty($reg->jawaban_santri_json) ? json_decode($reg->jawaban_santri_json, true) : [];
            $raguList   = !empty($reg->cbt_ragu_json)       ? json_decode($reg->cbt_ragu_json, true)       : [];
            $totalAnswered = count(array_filter($answers, fn($v) => isset($v['jawaban']) ? !empty($v['jawaban']) : !empty($v)));

            $lastSeen = $reg->cbt_last_activity_at;
            $idleSeconds = $lastSeen ? $now->diffInSeconds($lastSeen) : null;
            $isOnline = $reg->status_ujian === 'Sedang Ujian' && $idleSeconds !== null && $idleSeconds < 60;

            return [
                'id'              => $reg->id,
                'no_reg'          => $reg->no_registrasi,
                'nama'            => $reg->nama_lengkap,
                'asal'            => $reg->asal_sekolah ?? '-',
                'status_ujian'    => $reg->status_ujian ?? 'Belum Ujian',
                'nilai'           => $reg->nilai_ujian,
                'current_q'       => ($reg->cbt_current_question_index ?? 0) + 1,
                'total_answered'  => $totalAnswered,
                'total_ragu'      => count($raguList),
                'violations'      => $reg->pelanggaran_curang_count ?? 0,
                'extra_time'      => $reg->cbt_extra_time_minutes ?? 0,
                'last_activity'   => $lastSeen ? $lastSeen->toDateTimeString() : null,
                'idle_seconds'    => $idleSeconds,
                'is_online'       => $isOnline,
                'selesai_at'      => $reg->ujian_selesai_at ? $reg->ujian_selesai_at->toDateTimeString() : null,
            ];
        });

        return response()->json([
            'success' => true,
            'count'   => $data->count(),
            'data'    => $data,
            'now'     => $now->toDateTimeString(),
        ]);
    }

    /**
     * Aksi Admin: Tambah Waktu, Kirim Pesan, Paksa Selesai, Reset Ujian.
     */
    public function monitoringAction(Request $request)
    {
        $request->validate([
            'reg_id' => 'required|exists:psb_registrations,id',
            'action' => 'required|in:extra_time,send_message,force_finish,reset_violations,open_retake',
        ]);

        $reg = PsbRegistration::findOrFail($request->reg_id);
        $action = $request->action;

        switch ($action) {
            case 'extra_time':
                $minutes = max(1, (int) $request->input('minutes', 10));
                $current = (int) ($reg->cbt_extra_time_minutes ?? 0);
                $reg->update([
                    'cbt_extra_time_minutes' => $current + $minutes,
                    'cbt_proctor_message' => "⏱️ Pengawas menambahkan {$minutes} menit waktu ekstra untuk Anda.",
                ]);
                return response()->json(['success' => true, 'message' => "Tambah {$minutes} menit berhasil untuk {$reg->nama_lengkap}."]);

            case 'send_message':
                $msg = trim($request->input('message', ''));
                if (empty($msg)) {
                    return response()->json(['success' => false, 'message' => 'Pesan tidak boleh kosong.'], 422);
                }
                $reg->update(['cbt_proctor_message' => "📢 Pesan Pengawas: {$msg}"]);
                return response()->json(['success' => true, 'message' => "Pesan terkirim ke {$reg->nama_lengkap}."]);

            case 'force_finish':
                $reg->update([
                    'status_ujian'    => 'Selesai',
                    'ujian_selesai_at' => now(),
                    'cbt_proctor_message' => '🔒 Ujian Anda telah dihentikan oleh pengawas.',
                ]);
                return response()->json(['success' => true, 'message' => "{$reg->nama_lengkap} berhasil dipaksa selesai."]);

            case 'reset_violations':
                $reg->update([
                    'pelanggaran_curang_count' => 0,
                    'pelanggaran_curang_log'   => null,
                    'cbt_proctor_message'      => '✅ Catatan pelanggaran Anda telah direset oleh pengawas.',
                ]);
                return response()->json(['success' => true, 'message' => "Pelanggaran {$reg->nama_lengkap} berhasil direset."]);

            case 'open_retake':
                $reg->update([
                    'status_ujian'             => 'Sedang Ujian',
                    'pelanggaran_curang_count' => 0,
                    'pelanggaran_curang_log'   => null,
                    'jawaban_santri_json'       => null,
                    'cbt_ragu_json'             => null,
                    'cbt_current_question_index' => 0,
                    'cbt_extra_time_minutes'    => 0,
                    'cbt_proctor_message'       => '🔄 Pengawas membuka kesempatan ujian ulang untuk Anda.',
                ]);
                return response()->json(['success' => true, 'message' => "{$reg->nama_lengkap} dibuka untuk ujian ulang."]);
        }

        return response()->json(['success' => false, 'message' => 'Aksi tidak valid.'], 400);
    }
}



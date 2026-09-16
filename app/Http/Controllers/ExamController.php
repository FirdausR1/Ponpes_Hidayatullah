<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PsbRegistration;
use App\Models\Question;
use App\Models\Setting;

class ExamController extends Controller
{
    /**
     * Halaman Formulir Masuk Ujian (Nomor Pendaftaran & Nama Lengkap).
     */
    public function index()
    {
        $status = Setting::get('cbt_status', 'buka');
        $duration = (int) Setting::get('cbt_duration_minutes', 60);
        $totalQuestions = Question::where('is_active', true)->count();
        $passingGrade = (int) Setting::get('cbt_passing_grade', 70);
        $examTitle = Setting::get('cbt_exam_title', 'Ujian Masuk Seleksi Santri Baru TA ' . Setting::get('tahun_ajaran', '2026/2027'));
        $instructions = Setting::get('cbt_instructions', "1. Berdoalah sebelum memulai ujian.\n2. Dilarang membuka tab lain, mencari jawaban, atau beralih aplikasi selama ujian.\n3. Setiap indikasi kecurangan akan terdeteksi oleh sistem pengawas otomatis.\n4. Ujian akan otomatis di-submit jika Anda melanggar lebih dari 3 kali atau saat waktu habis.");

        // Pengaturan Rentang Jadwal Pelaksanaan Ujian (Global)
        $enableSchedule = Setting::get('cbt_enable_schedule', '0') === '1';
        $startDateStr = Setting::get('cbt_start_date', '');
        $endDateStr = Setting::get('cbt_end_date', '');
        $now = now();

        $scheduleStatus = 'open'; // 'open' (bebas), 'active' (dalam rentang), 'upcoming' (belum mulai), 'ended' (telah lewat)
        $startDateFormatted = null;
        $endDateFormatted = null;
        $startDateObj = null;
        $endDateObj = null;

        if ($enableSchedule && !empty($startDateStr) && !empty($endDateStr)) {
            $startDateObj = \Carbon\Carbon::parse($startDateStr);
            $endDateObj = \Carbon\Carbon::parse($endDateStr);
            $startDateFormatted = $startDateObj->translatedFormat('d M Y, H:i') . ' WIB';
            $endDateFormatted = $endDateObj->translatedFormat('d M Y, H:i') . ' WIB';

            if ($now->lt($startDateObj)) {
                $scheduleStatus = 'upcoming';
            } elseif ($now->gt($endDateObj)) {
                $scheduleStatus = 'ended';
            } else {
                $scheduleStatus = 'active';
            }
        }

        // Pengaturan Jadwal Khusus Per Materi Ujian (Matematika, Bahasa Arab, dll)
        $rawCatSchedules = json_decode(Setting::get('cbt_category_schedules', '[]'), true) ?: [];
        $activeCategories = Question::where('is_active', true)->distinct()->pluck('kategori')->toArray();
        if (empty($activeCategories)) {
            $activeCategories = ['Matematika', 'Bahasa Arab', 'Bahasa Indonesia', 'Bahasa Inggris', 'Pendidikan Agama Islam', 'Tajwid & Al-Qur\'an', 'Kepesantrenan'];
        }

        $categorySchedules = [];
        foreach ($activeCategories as $cat) {
            $conf = $rawCatSchedules[$cat] ?? [];
            $catIsCustom = !empty($conf['enable_schedule']);
            $catStatus = $conf['status'] ?? 'buka';
            $catStart = !empty($conf['start_date']) ? \Carbon\Carbon::parse($conf['start_date']) : null;
            $catEnd = !empty($conf['end_date']) ? \Carbon\Carbon::parse($conf['end_date']) : null;
            $catSchedState = 'open';

            if ($catStatus === 'tutup') {
                $catSchedState = 'closed';
            } elseif ($catIsCustom && $catStart && $catEnd) {
                if ($now->lt($catStart)) {
                    $catSchedState = 'upcoming';
                } elseif ($now->gt($catEnd)) {
                    $catSchedState = 'ended';
                } else {
                    $catSchedState = 'active';
                }
            }

            $categorySchedules[$cat] = [
                'kategori' => $cat,
                'status' => $catStatus,
                'enable_schedule' => $catIsCustom,
                'start_date_obj' => $catStart,
                'end_date_obj' => $catEnd,
                'start_date_formatted' => $catStart ? $catStart->translatedFormat('d M Y, H:i') . ' WIB' : null,
                'end_date_formatted' => $catEnd ? $catEnd->translatedFormat('d M Y, H:i') . ' WIB' : null,
                'duration_minutes' => (int) ($conf['duration_minutes'] ?? $duration),
                'question_count' => (int) ($conf['question_count'] ?? 0),
                'sched_state' => $catSchedState,
                'total_questions' => Question::where('kategori', $cat)->where('is_active', true)->count(),
            ];
        }

        return view('ujian.index', compact(
            'status', 'duration', 'totalQuestions', 'passingGrade', 'examTitle', 'instructions',
            'enableSchedule', 'scheduleStatus', 'startDateFormatted', 'endDateFormatted', 'startDateObj', 'endDateObj',
            'categorySchedules', 'activeCategories'
        ));
    }

    /**
     * Proses Verifikasi & Login ke Ruang Ujian.
     */
    public function login(Request $request)
    {
        $status = Setting::get('cbt_status', 'buka');
        if ($status !== 'buka') {
            return back()->withErrors([
                'auth' => 'Mohon maaf, portal ujian masuk saat ini sedang DITUTUP oleh panitia seleksi. Silakan pantau pengumuman resmi pesantren.'
            ])->withInput();
        }

        $now = now();
        $selectedCategory = 'all';

        // Cek Pembatasan Rentang Tanggal Pelaksanaan Ujian Global
        $enableSchedule = Setting::get('cbt_enable_schedule', '0') === '1';
        if ($enableSchedule) {
            $startStr = Setting::get('cbt_start_date', '');
            $endStr = Setting::get('cbt_end_date', '');

            if (!empty($startStr) && !empty($endStr)) {
                $startDate = \Carbon\Carbon::parse($startStr);
                $endDate = \Carbon\Carbon::parse($endStr);

                if ($now->lt($startDate)) {
                    return back()->withErrors([
                        'auth' => 'Ujian seleksi CBT belum dibuka. Jadwal pengerjaan baru dimulai pada ' . $startDate->translatedFormat('d F Y, H:i') . ' WIB.'
                    ])->withInput();
                }

                if ($now->gt($endDate)) {
                    return back()->withErrors([
                        'auth' => 'Masa pengerjaan ujian seleksi CBT telah ditutup pada ' . $endDate->translatedFormat('d F Y, H:i') . ' WIB. Silakan hubungi panitia PSB jika Anda memerlukan bantuan.'
                    ])->withInput();
                }
            }
        }

        $request->validate([
            'no_registrasi' => 'required|string',
            'nama_lengkap' => 'required|string',
        ], [
            'no_registrasi.required' => 'Nomor Pendaftaran wajib diisi.',
            'nama_lengkap.required' => 'Nama Lengkap calon santri wajib diisi.',
        ]);

        $noReg = trim($request->no_registrasi);
        $nama = trim($request->nama_lengkap);

        // Cari pendaftar yang cocok
        $reg = PsbRegistration::where(function($q) use ($noReg) {
            $q->where('no_registrasi', $noReg)
              ->orWhere('no_registrasi', 'like', "%{$noReg}%");
        })->where('nama_lengkap', 'like', "%{$nama}%")->first();

        if (!$reg) {
            return back()->withErrors([
                'auth' => 'Nomor Pendaftaran dan Nama Lengkap tidak cocok dengan data pendaftaran kami. Pastikan nomor dan ejaan nama sesuai dengan bukti formulir Anda.'
            ])->withInput();
        }

        // Cek Verifikasi Pembayaran Pendaftaran (Rp 200.000) oleh Admin
        if ($reg->status_pembayaran !== 'Lunas') {
            $statusBayar = $reg->status_pembayaran ?: 'Menunggu Verifikasi';
            return back()->withErrors([
                'auth' => "Mohon maaf, status pembayaran infaq pendaftaran (Rp 200.000) Anda masih \"{$statusBayar}\". Ujian Seleksi Masuk (CBT Online) hanya dapat diakses setelah pembayaran diverifikasi oleh Admin Pesantren. Silakan pantau status pendaftaran Anda secara berkala di menu Cek Status PSB."
            ])->withInput();
        }

        // Simpan id registrasi dan kategori ke session
        session([
            'psb_exam_reg_id' => $reg->id,
            'psb_exam_category' => 'all',
        ]);

        $maxAttempts = (int) Setting::get('cbt_max_attempts', 1);
        $attemptsCount = (int) ($reg->cbt_attempts_count ?? ($reg->status_ujian === 'Selesai' ? 1 : 0));

        if ($maxAttempts > 0 && $attemptsCount >= $maxAttempts && $reg->status_ujian === 'Selesai') {
            return redirect()->route('ujian.result')->with('info', "Anda telah menggunakan seluruh batas kesempatan pengerjaan ujian ({$attemptsCount} dari {$maxAttempts} kali).");
        }

        return redirect()->route('ujian.room');
    }

    /**
     * Ruang Ujian Online (CBT).
     */
    public function room()
    {
        $regId = session('psb_exam_reg_id');
        if (!$regId) {
            return redirect()->route('ujian.index')->with('warning', 'Silakan masukkan Nomor Pendaftaran dan Nama Anda terlebih dahulu.');
        }

        $reg = PsbRegistration::findOrFail($regId);

        // Pastikan status pembayaran Lunas
        if ($reg->status_pembayaran !== 'Lunas') {
            return redirect()->route('ujian.index')->with('warning', 'Pembayaran pendaftaran Anda belum diverifikasi oleh admin. Ujian CBT belum dapat dimulai.');
        }

        $maxAttempts = (int) Setting::get('cbt_max_attempts', 1);
        $attemptsCount = (int) ($reg->cbt_attempts_count ?? ($reg->status_ujian === 'Selesai' ? 1 : 0));

        // Jika sudah selesai ujian dan sudah mencapai batas maksimal kesempatan
        if ($maxAttempts > 0 && $attemptsCount >= $maxAttempts && $reg->status_ujian === 'Selesai' && $reg->nilai_ujian !== null) {
            return redirect()->route('ujian.result');
        }

        // Ambil pengaturan ujian
        $status = Setting::get('cbt_status', 'buka');
        if ($status !== 'buka') {
            return redirect()->route('ujian.index')->with('warning', 'Sesi ujian sedang dinonaktifkan oleh pengawas.');
        }

        // Cek Pembatasan Rentang Tanggal Ujian Global
        $now = now();
        $enableSchedule = Setting::get('cbt_enable_schedule', '0') === '1';
        if ($enableSchedule) {
            $startStr = Setting::get('cbt_start_date', '');
            $endStr = Setting::get('cbt_end_date', '');
            if (!empty($startStr) && !empty($endStr)) {
                $startDate = \Carbon\Carbon::parse($startStr);
                $endDate = \Carbon\Carbon::parse($endStr);
                if ($now->lt($startDate) || $now->gt($endDate)) {
                    return redirect()->route('ujian.index')->with('warning', 'Akses pengerjaan soal ditutup karena di luar rentang jadwal resmi.');
                }
            }
        }

        // Tandai status sedang ujian
        if ($reg->status_ujian !== 'Sedang Ujian') {
            $reg->update(['status_ujian' => 'Sedang Ujian']);
        }

        // Pengaturan Ujian
        $durationMinutes = (int) Setting::get('cbt_duration_minutes', 60);
        $shuffleQuestions = Setting::get('cbt_shuffle_questions', '1') === '1';
        $shuffleOptions = Setting::get('cbt_shuffle_options', '1') === '1';
        $questionCountSetting = (int) Setting::get('cbt_question_count', 0);
        $maxViolations = (int) Setting::get('cbt_max_violations', 3);
        $examTitle = Setting::get('cbt_exam_title', 'Ujian Masuk Seleksi Santri Baru');

        // Deteksi Jenjang Santri (MTs atau MA)
        $santriJenjangRaw = strtoupper($reg->jenjang ?: '');
        $santriJenjang = str_contains($santriJenjangRaw, 'MA') ? 'MA' : (str_contains($santriJenjangRaw, 'MTS') ? 'MTs' : 'Semua');
        $examTitle .= " — Jenjang " . ($santriJenjang !== 'Semua' ? $santriJenjang : 'Terpadu');

        // Pastikan ada soal di bank soal, jika belum ada generate template default
        if (Question::where('is_active', true)->count() === 0) {
            $cbtCtrl = new AdminCbtController();
            $cbtCtrl->loadTemplate(new Request(['mode' => 'append']));
        }

        // Ambil atau inisialisasi daftar soal untuk sesi siswa ini
        $sessionKey = 'cbt_questions_' . $reg->id . '_' . $santriJenjang;
        $assignedIds = [];

        // Prioritas 1: Ambil dari rekaman urutan soal santri di database (jika sudah diinisialisasi)
        if (!empty($reg->cbt_soal_urutan_json)) {
            $decoded = json_decode($reg->cbt_soal_urutan_json, true);
            if (is_array($decoded) && !empty($decoded)) {
                $assignedIds = $decoded;
                session([$sessionKey => $assignedIds]);
            }
        }

        // Prioritas 2: Ambil dari session jika ada
        if (empty($assignedIds) && session()->has($sessionKey)) {
            $assignedIds = session($sessionKey, []);
        }

        // Prioritas 3: Generate susunan soal baru untuk jenjang santri ini (Semua materi sekaligus & acak soal jika aktif)
        if (empty($assignedIds)) {
            $query = Question::where('is_active', true);
            if ($santriJenjang === 'MTs') {
                $query->whereIn('jenjang', ['MTs', 'Semua']);
            } elseif ($santriJenjang === 'MA') {
                $query->whereIn('jenjang', ['MA', 'Semua']);
            }

            if ($shuffleQuestions) {
                // Acak soal dinamis per peserta ujian
                $query->inRandomOrder();
            } else {
                $query->orderBy('kategori')->orderBy('id');
            }

            if ($questionCountSetting > 0) {
                $query->take($questionCountSetting);
            }

            $assignedIds = $query->pluck('id')->toArray();

            // Jika acak soal aktif, shuffle lagi memastikan urutan setiap peserta benar-benar berbeda
            if ($shuffleQuestions && count($assignedIds) > 1) {
                shuffle($assignedIds);
            }

            session([$sessionKey => $assignedIds]);
            $reg->update(['cbt_soal_urutan_json' => json_encode($assignedIds)]);
        }

        $questionsCollection = Question::whereIn('id', $assignedIds)->get();

        // Urutkan kembali sesuai urutan di session / database
        $questions = [];
        foreach ($assignedIds as $qId) {
            $found = $questionsCollection->firstWhere('id', $qId);
            if ($found) {
                $questions[] = $found;
            }
        }

        // Kelompokkan data per kategori / mata pelajaran untuk navigasi & seksi tampilan di view
        $categoriesGrouped = [];
        $globalIndex = 1;
        foreach ($questions as $q) {
            $catName = $q->kategori ?: 'Umum';
            if (!isset($categoriesGrouped[$catName])) {
                $categoriesGrouped[$catName] = [
                    'kategori' => $catName,
                    'startIndex' => $globalIndex,
                    'endIndex' => $globalIndex,
                    'items' => [],
                ];
            }
            $categoriesGrouped[$catName]['items'][] = [
                'question' => $q,
                'globalIndex' => $globalIndex,
            ];
            $categoriesGrouped[$catName]['endIndex'] = $globalIndex;
            $globalIndex++;
        }

        // Hitung Timer dan Tambahan Waktu Pengawas
        $timerSessionKey = 'cbt_start_time_' . $reg->id;
        if (!session()->has($timerSessionKey)) {
            session([$timerSessionKey => now()->timestamp]);
        }
        $startTime = session($timerSessionKey);
        $elapsedSeconds = max(0, now()->timestamp - $startTime);
        $extraTimeMinutes = (int) ($reg->cbt_extra_time_minutes ?? 0);
        $totalDurationSeconds = ($durationMinutes + $extraTimeMinutes) * 60;
        $remainingSeconds = max(0, $totalDurationSeconds - $elapsedSeconds);

        // Jawaban tersimpan dari database (Autosave recovery)
        $savedAnswersRaw = !empty($reg->jawaban_santri_json) ? json_decode($reg->jawaban_santri_json, true) : [];
        $savedAnswers = [];
        foreach ($savedAnswersRaw as $k => $v) {
            $savedAnswers[$k] = is_array($v) ? ($v['jawaban'] ?? null) : $v;
        }

        // Daftar Ragu-Ragu
        $savedRagu = !empty($reg->cbt_ragu_json) ? json_decode($reg->cbt_ragu_json, true) : [];
        $currentIndex = (int) ($reg->cbt_current_question_index ?? 0);

        // Update heartbeat aktivitas peserta
        $reg->update([
            'status_ujian' => 'Sedang Ujian',
            'cbt_last_activity_at' => now(),
        ]);

        return view('ujian.room', compact(
            'reg', 'questions', 'categoriesGrouped', 'durationMinutes', 'remainingSeconds',
            'maxViolations', 'examTitle', 'shuffleOptions',
            'savedAnswers', 'savedRagu', 'currentIndex', 'extraTimeMinutes'
        ));
    }

    /**
     * Endpoint AJAX Autosave Jawaban, Status Ragu-Ragu & Posisi Soal.
     */
    public function autosave(Request $request)
    {
        $regId = session('psb_exam_reg_id');
        if (!$regId) {
            return response()->json(['success' => false, 'message' => 'Sesi berakhir'], 401);
        }

        $reg = PsbRegistration::find($regId);
        if (!$reg) {
            return response()->json(['success' => false, 'message' => 'Peserta tidak ditemukan'], 404);
        }

        // Jika pengawas telah mengunci ujian secara paksa
        if ($reg->status_ujian === 'Selesai') {
            return response()->json([
                'success' => true,
                'force_finish' => true,
                'redirect' => route('ujian.result')
            ]);
        }

        $questionId = $request->input('question_id');
        $answer = $request->input('answer');
        $isRagu = $request->boolean('ragu');
        $currentIndex = (int) $request->input('current_index', 0);

        // Update jawaban tersimpan
        $answers = !empty($reg->jawaban_santri_json) ? json_decode($reg->jawaban_santri_json, true) : [];
        if ($questionId) {
            if ($answer !== null && trim($answer) !== '') {
                $answers[$questionId] = [
                    'jawaban' => strtoupper(trim($answer)),
                    'waktu_jawab' => now()->toDateTimeString(),
                ];
            } else {
                unset($answers[$questionId]);
            }
        }

        // Update daftar ragu-ragu
        $raguList = !empty($reg->cbt_ragu_json) ? json_decode($reg->cbt_ragu_json, true) : [];
        if ($questionId) {
            if ($isRagu) {
                if (!in_array($questionId, $raguList)) {
                    $raguList[] = $questionId;
                }
            } else {
                $raguList = array_values(array_filter($raguList, fn($id) => $id != $questionId));
            }
        }

        $proctorMessage = $reg->cbt_proctor_message;
        
        $updateData = [
            'jawaban_santri_json' => json_encode($answers),
            'cbt_ragu_json' => json_encode($raguList),
            'cbt_current_question_index' => $currentIndex,
            'cbt_last_activity_at' => now(),
        ];

        // Kosongkan pesan jika sudah terkirim ke klien
        if (!empty($proctorMessage)) {
            $updateData['cbt_proctor_message'] = null;
        }

        $reg->update($updateData);

        return response()->json([
            'success' => true,
            'total_answered' => count($answers),
            'total_ragu' => count($raguList),
            'extra_time_minutes' => (int) ($reg->cbt_extra_time_minutes ?? 0),
            'proctor_message' => $proctorMessage,
            'force_finish' => false,
        ]);
    }

    /**
     * Endpoint Heartbeat Ping dari Ruang Ujian (Setiap 15 Detik).
     */
    public function ping(Request $request)
    {
        $regId = session('psb_exam_reg_id');
        if (!$regId) {
            return response()->json(['success' => false, 'session_expired' => true]);
        }

        $reg = PsbRegistration::find($regId);
        if (!$reg) {
            return response()->json(['success' => false, 'session_expired' => true]);
        }

        if ($reg->status_ujian === 'Selesai') {
            return response()->json([
                'success' => true,
                'force_finish' => true,
                'redirect' => route('ujian.result')
            ]);
        }

        $proctorMessage = $reg->cbt_proctor_message;
        $updateData = [
            'cbt_last_activity_at' => now(),
        ];
        if (!empty($proctorMessage)) {
            $updateData['cbt_proctor_message'] = null;
        }
        $reg->update($updateData);

        return response()->json([
            'success' => true,
            'extra_time_minutes' => (int) ($reg->cbt_extra_time_minutes ?? 0),
            'proctor_message' => $proctorMessage,
            'force_finish' => false,
        ]);
    }

    /**
     * Endpoint AJAX Pencatatan Pelanggaran Kecurangan (Anti-Cheat Log).
     */
    public function logViolation(Request $request)
    {
        $regId = session('psb_exam_reg_id');
        if (!$regId) {
            return response()->json(['error' => 'Sesi tidak valid'], 403);
        }

        $reg = PsbRegistration::find($regId);
        if (!$reg) {
            return response()->json(['error' => 'Peserta tidak ditemukan'], 404);
        }

        $type = $request->input('type', 'tab_switch');
        $detail = $request->input('message', 'Beralih tab browser atau membuka aplikasi lain');

        $maxViolations = (int) Setting::get('cbt_max_violations', 3);

        // Ambil log yang sudah ada
        $currentLog = $reg->pelanggaran_log_array;
        $currentLog[] = [
            'waktu' => now()->format('H:i:s d/m/Y'),
            'tipe' => $type,
            'pesan' => $detail,
        ];

        $newCount = $reg->pelanggaran_curang_count + 1;

        $reg->update([
            'pelanggaran_curang_count' => $newCount,
            'pelanggaran_curang_log' => json_encode($currentLog),
            'cbt_last_activity_at' => now(),
        ]);

        $isMaxReached = $newCount >= $maxViolations;

        return response()->json([
            'success' => true,
            'violation_count' => $newCount,
            'max_violations' => $maxViolations,
            'auto_submit' => $isMaxReached,
            'message' => $isMaxReached 
                ? "Batas toleransi pelanggaran ({$maxViolations}x) telah habis! Ujian Anda otomatis dikunci dan dikirim." 
                : "Peringatan kecurangan ({$newCount}/{$maxViolations}): {$detail} tercatat oleh sistem pengawas!",
        ]);
    }

    /**
     * Submit Jawaban Ujian & Kalkulasi Skor.
     */
    public function submit(Request $request)
    {
        $regId = session('psb_exam_reg_id');
        if (!$regId) {
            return redirect()->route('ujian.index');
        }

        $reg = PsbRegistration::findOrFail($regId);

        $santriJenjangRaw = strtoupper($reg->jenjang ?: '');
        $santriJenjang = str_contains($santriJenjangRaw, 'MA') ? 'MA' : (str_contains($santriJenjangRaw, 'MTS') ? 'MTs' : 'Semua');
        $sessionKey = 'cbt_questions_' . $reg->id . '_' . $santriJenjang;
        $assignedIds = session($sessionKey, session('cbt_questions_' . $reg->id, []));

        if (empty($assignedIds) && !empty($reg->cbt_soal_urutan_json)) {
            $assignedIds = json_decode($reg->cbt_soal_urutan_json, true) ?: [];
        }

        if (empty($assignedIds)) {
            $questionsQuery = Question::where('is_active', true);
            if ($santriJenjang === 'MTs') {
                $questionsQuery->whereIn('jenjang', ['MTs', 'Semua']);
            } elseif ($santriJenjang === 'MA') {
                $questionsQuery->whereIn('jenjang', ['MA', 'Semua']);
            }
            $questions = $questionsQuery->get();
        } else {
            $questions = Question::whereIn('id', $assignedIds)->get();
        }

        // Ambil jawaban: gabungkan dari input POST dan yang sudah tersimpan di autosave
        $requestAnswers = $request->input('jawaban', []);
        $savedAnswersRaw = !empty($reg->jawaban_santri_json) ? json_decode($reg->jawaban_santri_json, true) : [];
        $savedAnswers = [];
        foreach ($savedAnswersRaw as $k => $v) {
            $savedAnswers[$k] = is_array($v) ? ($v['jawaban'] ?? null) : $v;
        }
        $mergedAnswers = array_merge($savedAnswers, is_array($requestAnswers) ? $requestAnswers : []);

        $correctCount = 0;
        $totalQuestions = count($questions);
        $totalBobotEarned = 0;
        $totalBobotMax = 0;

        $answerSheet = [];
        $perMapelResults = [];

        foreach ($questions as $q) {
            $qId = $q->id;
            $userAns = isset($mergedAnswers[$qId]) ? strtoupper(trim($mergedAnswers[$qId])) : null;
            $correctAns = strtoupper(trim($q->kunci_jawaban));
            $isCorrect = ($userAns !== null && $userAns === $correctAns);

            $bobot = max(1, (int) $q->bobot);
            $totalBobotMax += $bobot;

            if ($isCorrect) {
                $correctCount++;
                $totalBobotEarned += $bobot;
            }

            $catName = $q->kategori ?: 'Umum';
            if (!isset($perMapelResults[$catName])) {
                $perMapelResults[$catName] = [
                    'kategori' => $catName,
                    'total_soal' => 0,
                    'benar' => 0,
                    'salah' => 0,
                    'bobot_earned' => 0,
                    'bobot_max' => 0,
                ];
            }
            $perMapelResults[$catName]['total_soal']++;
            $perMapelResults[$catName]['bobot_max'] += $bobot;
            if ($isCorrect) {
                $perMapelResults[$catName]['benar']++;
                $perMapelResults[$catName]['bobot_earned'] += $bobot;
            } else {
                $perMapelResults[$catName]['salah']++;
            }

            $answerSheet[$qId] = [
                'jawaban' => $userAns,
                'kunci' => $correctAns,
                'benar' => $isCorrect,
                'kategori' => $catName,
            ];
        }

        $score = $totalBobotMax > 0 ? round(($totalBobotEarned / $totalBobotMax) * 100) : 0;
        $kkm = (int) Setting::get('cbt_passing_grade', 70);

        foreach ($perMapelResults as $k => &$stat) {
            $stat['skor'] = $stat['bobot_max'] > 0 ? round(($stat['bobot_earned'] / $stat['bobot_max']) * 100) : 0;
            $stat['kkm'] = $kkm;
            $stat['status'] = $stat['skor'] >= $kkm ? 'Tuntas' : 'Belum Tuntas';
        }

        $currentAttempts = (int) ($reg->cbt_attempts_count ?? 0);
        $newAttemptsCount = $currentAttempts + 1;

        // Penentuan Nilai Berdasarkan Kebijakan Ujian Ulang (Nilai Terbaik/Tertinggi, Terakhir, atau Rata-Rata)
        $previousScore = $reg->nilai_ujian;
        $retakeRule = Setting::get('cbt_retake_score_rule', 'tertinggi');

        $finalScore = $score;
        $attemptNote = null;

        if ($previousScore !== null && $currentAttempts > 0) {
            if ($retakeRule === 'tertinggi') {
                $finalScore = max((int) $previousScore, $score);
                if ($score >= (int) $previousScore) {
                    $attemptNote = "Ujian Ulang ke-{$newAttemptsCount}: Nilai meningkat dari {$previousScore} menjadi {$score}.";
                } else {
                    $attemptNote = "Ujian Ulang ke-{$newAttemptsCount}: Skor pengerjaan={$score}. Nilai terbaik ({$finalScore}) tetap dipertahankan.";
                }
            } elseif ($retakeRule === 'rata_rata') {
                $finalScore = round(((int) $previousScore + $score) / 2);
                $attemptNote = "Ujian Ulang ke-{$newAttemptsCount}: Nilai akhir rata-rata ({$previousScore} dan {$score}) = {$finalScore}.";
            } else {
                // 'terakhir'
                $finalScore = $score;
                $attemptNote = "Ujian Ulang ke-{$newAttemptsCount}: Menggunakan nilai terakhir = {$score} (sebelumnya {$previousScore}).";
            }
        }

        $updateFields = [
            'status_ujian' => 'Selesai',
            'cbt_attempts_count' => $newAttemptsCount,
            'nilai_ujian' => $finalScore,
            'ujian_selesai_at' => now(),
            'jawaban_santri_json' => json_encode($answerSheet),
            'cbt_nilai_per_mapel_json' => json_encode($perMapelResults),
            'cbt_last_activity_at' => now(),
        ];

        if ($attemptNote) {
            $updateFields['catatan_penguji'] = $attemptNote;
        }

        // Otomatis Diterima jika nilai mencapai KKM dan sudah mengunggah bukti pembayaran (serta belum ditolak admin)
        if ($finalScore >= $kkm && !empty($reg->bukti_transfer) && $reg->status !== 'Ditolak') {
            $updateFields['status'] = 'Diterima';
        }

        $reg->update($updateFields);

        return redirect()->route('ujian.result')->with('success', 'Ujian Seleksi Masuk Berhasil Diselesaikan dan Dikirim!');
    }

    /**
     * Memulai Kesempatan Ujian Berikutnya (Remedial / Retake).
     */
    public function retake()
    {
        $regId = session('psb_exam_reg_id');
        if (!$regId) {
            return redirect()->route('ujian.index');
        }

        $reg = PsbRegistration::findOrFail($regId);
        $maxAttempts = (int) Setting::get('cbt_max_attempts', 1);
        $attemptsCount = (int) ($reg->cbt_attempts_count ?? 1);

        if ($maxAttempts > 0 && $attemptsCount >= $maxAttempts) {
            return redirect()->route('ujian.result')->with('warning', "Batas kesempatan pengerjaan ({$maxAttempts} kali) telah habis.");
        }

        // Buka kembali sesi ujian santri & reset urutan soal agar diacak ulang
        $reg->update([
            'status_ujian' => 'Sedang Ujian',
            'pelanggaran_curang_count' => 0,
            'pelanggaran_curang_log' => null,
            'cbt_soal_urutan_json' => null,
        ]);

        // Reset timer dan daftar soal di sesi agar diacak ulang
        $selectedCategory = session('psb_exam_category', 'all');
        session()->forget('cbt_questions_' . $reg->id . '_' . md5($selectedCategory));
        session()->forget('cbt_questions_' . $reg->id);
        session()->forget('cbt_start_time_' . $reg->id);

        return redirect()->route('ujian.room')->with('success', 'Memulai kesempatan pengerjaan berikutnya.');
    }

    /**
     * Halaman Pengumuman Hasil / Tanda Bukti Selesai Ujian.
     */
    public function result()
    {
        $regId = session('psb_exam_reg_id');
        if (!$regId) {
            return redirect()->route('ujian.index');
        }

        $reg = PsbRegistration::findOrFail($regId);
        $kkm = (int) Setting::get('cbt_passing_grade', 70);
        $examTitle = Setting::get('cbt_exam_title', 'Ujian Masuk Seleksi Santri Baru');
        $maxAttempts = (int) Setting::get('cbt_max_attempts', 1);
        $attemptsCount = (int) ($reg->cbt_attempts_count ?? ($reg->status_ujian === 'Selesai' ? 1 : 0));

        // Jawaban santri
        $answerSheet = !empty($reg->jawaban_santri_json) ? json_decode($reg->jawaban_santri_json, true) : [];
        $totalQuestions = count($answerSheet);
        $correctCount = 0;
        foreach ($answerSheet as $ans) {
            if (!empty($ans['benar'])) {
                $correctCount++;
            }
        }

        // Pengaturan apakah nilai ditampilkan langsung atau dirahasiakan sementara (default: 0 = dirahasiakan dari santri)
        $publishScores = Setting::get('cbt_publish_scores', '0') === '1';
        $subjectResults = !empty($reg->cbt_nilai_per_mapel_json) ? json_decode($reg->cbt_nilai_per_mapel_json, true) : [];

        return view('ujian.result', compact('reg', 'kkm', 'examTitle', 'totalQuestions', 'correctCount', 'maxAttempts', 'attemptsCount', 'subjectResults', 'publishScores'));
    }

    /**
     * Cek data calon santri secara instan berdasarkan Nomor Pendaftaran
     * Otomatis mendeteksi Nama Lengkap dan Jenjang (MTs / MA).
     */
    public function checkCandidate(Request $request)
    {
        $noReg = trim($request->input('no_reg', ''));
        if (empty($noReg)) {
            return response()->json(['found' => false]);
        }

        $reg = PsbRegistration::where('no_registrasi', $noReg)
            ->orWhere('no_registrasi', 'like', "%{$noReg}%")
            ->first();

        if (!$reg) {
            return response()->json(['found' => false]);
        }

        $santriJenjangRaw = strtoupper($reg->jenjang ?: '');
        $tipeJenjang = str_contains($santriJenjangRaw, 'MA') ? 'MA' : (str_contains($santriJenjangRaw, 'MTS') ? 'MTs' : 'Semua');

        return response()->json([
            'found' => true,
            'no_reg' => $reg->no_registrasi,
            'nama_lengkap' => $reg->nama_lengkap,
            'jenjang' => $reg->jenjang,
            'tipe_jenjang' => $tipeJenjang,
        ]);
    }
}

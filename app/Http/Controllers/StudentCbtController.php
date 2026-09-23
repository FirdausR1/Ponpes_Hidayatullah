<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MadrasahExam;
use App\Models\MadrasahExamResult;
use App\Models\MadrasahExamQuestion;
use Carbon\Carbon;

class StudentCbtController extends Controller
{
    /**
     * Daftar Ujian CBT Madrasah untuk Santri yang sedang login
     */
    public function index()
    {
        $student = Auth::guard('santri')->user();
        if (!$student) {
            return redirect()->route('santri.login');
        }

        // Cari hasil ujian yang didaftarkan untuk santri ini
        $results = MadrasahExamResult::with('exam')
            ->where(function ($q) use ($student) {
                $q->where('student_id', $student->id)
                  ->orWhere('nomor_peserta', $student->nis);
            })
            ->get();

        // Cari juga sesi ujian aktif sesuai tingkat kelas santri (9 MTs atau 12 MA)
        // yang mungkin belum di-generate resultnya agar otomatis dibuatkan result
        $studentTingkat = null;
        if (preg_match('/(12|XII)/i', $student->kelas)) {
            $studentTingkat = '12';
        } elseif (preg_match('/(9|IX)/i', $student->kelas)) {
            $studentTingkat = '9';
        }

        if ($studentTingkat) {
            $activeExams = MadrasahExam::where('status', 'Aktif')
                ->where('tingkat_kelas', $studentTingkat)
                ->get();

            foreach ($activeExams as $exam) {
                $hasResult = $results->where('madrasah_exam_id', $exam->id)->first();
                if (!$hasResult) {
                    $newResult = MadrasahExamResult::create([
                        'madrasah_exam_id' => $exam->id,
                        'student_id' => $student->id,
                        'nomor_peserta' => $student->nis,
                        'nama_peserta' => strtoupper($student->nama_lengkap),
                        'kelas' => $studentTingkat,
                        'jurusan' => $exam->jurusan,
                        'status_pengerjaan' => 'Belum Mulai',
                        'attempt_number' => 0,
                        'jumlah_pelanggaran' => 0,
                        'sisa_detik' => $exam->durasi_menit * 60,
                    ]);
                    $results->push($newResult->load('exam'));
                }
            }
        }

        return view('santri.cbt.index', compact('student', 'results'));
    }

    /**
     * Masuk ke Ruang Ujian CBT
     */
    public function room(Request $request, $id)
    {
        $student = Auth::guard('santri')->user();
        if (!$student) {
            return redirect()->route('santri.login');
        }

        $exam = MadrasahExam::with(['questions' => function ($q) {
            $q->orderBy('nomor_urut', 'asc');
        }])->findOrFail($id);

        if ($exam->status !== 'Aktif') {
            return redirect()->route('santri.cbt.index')->with('error', "Sesi ujian {$exam->mata_pelajaran} saat ini tidak aktif.");
        }

        $result = MadrasahExamResult::where('madrasah_exam_id', $exam->id)
            ->where(function ($q) use ($student) {
                $q->where('student_id', $student->id)
                  ->orWhere('nomor_peserta', $student->nis);
            })
            ->first();

        if (!$result) {
            // Auto-enroll jika tingkat kelas cocok
            $studentTingkat = (preg_match('/(12|XII)/i', $student->kelas)) ? '12' : ((preg_match('/(9|IX)/i', $student->kelas)) ? '9' : null);
            if ($studentTingkat === $exam->tingkat_kelas) {
                $result = MadrasahExamResult::create([
                    'madrasah_exam_id' => $exam->id,
                    'student_id' => $student->id,
                    'nomor_peserta' => $student->nis,
                    'nama_peserta' => strtoupper($student->nama_lengkap),
                    'kelas' => $exam->tingkat_kelas,
                    'jurusan' => $exam->jurusan,
                    'status_pengerjaan' => 'Belum Mulai',
                    'attempt_number' => 0,
                    'jumlah_pelanggaran' => 0,
                    'sisa_detik' => $exam->durasi_menit * 60,
                ]);
            } else {
                return redirect()->route('santri.cbt.index')->with('error', "Anda tidak terdaftar dalam sesi ujian ini.");
            }
        }

        // Cek Token Ujian jika ada
        if (!empty($exam->token_ujian)) {
            $inputToken = strtoupper(trim($request->input('token') ?? ''));
            if ($result->status_pengerjaan === 'Belum Mulai' && empty($inputToken)) {
                return view('santri.cbt.token', compact('exam', 'result', 'student'));
            }

            if (!empty($inputToken) && $inputToken !== strtoupper(trim($exam->token_ujian))) {
                return back()->with('error', 'Token ujian yang Anda masukkan salah. Hubungi pengawas ujian.');
            }
        }

        // Cek Terkunci
        $isLocked = ($result->status_pengerjaan === 'Terkunci' || ($result->jumlah_pelanggaran >= ($exam->max_violations ?? 3) && $result->status_pengerjaan !== 'Selesai'));
        if ($isLocked) {
            return redirect()->route('santri.cbt.index')->with('error', "Ujian Anda sedang TERKUNCI karena terdeteksi melakukan pelanggaran aturan anti-cheat ({$result->jumlah_pelanggaran}x). Silakan lapor kepada pengawas ruang.");
        }

        // Cek Batas Percobaan Pengerjaan (Max Attempts)
        $maxAttempts = $exam->max_attempts ?? 1;
        if ($result->status_pengerjaan === 'Selesai') {
            if (($result->attempt_number ?? 1) >= $maxAttempts) {
                return redirect()->route('santri.cbt.index')->with('error', "Anda telah menyelesaikan ujian ini dan batas kesempatan pengerjaan ({$maxAttempts}x) telah habis.");
            }
            // Jika masih ada sisa kesempatan (misal max 2x), mulai attempt baru
            $result->attempt_number = ($result->attempt_number ?? 1) + 1;
            $result->status_pengerjaan = 'Mengerjakan';
            $result->answers_json = null;
            $result->ragu_ragu_json = null;
            $result->jumlah_pelanggaran = 0;
            $result->log_pelanggaran_json = null;
            $result->waktu_mulai = now();
            $result->waktu_selesai = null;
            $result->sisa_detik = $exam->durasi_menit * 60;
            $result->save();
        } elseif ($result->status_pengerjaan === 'Belum Mulai') {
            $result->status_pengerjaan = 'Mengerjakan';
            $result->attempt_number = 1;
            $result->waktu_mulai = now();
            $result->sisa_detik = $exam->durasi_menit * 60;
            $result->save();
        }

        // Pastikan sisa detik valid
        if ($result->sisa_detik === null || $result->sisa_detik <= 0) {
            $result->sisa_detik = $exam->durasi_menit * 60;
            $result->save();
        }

        $questions = $exam->questions;
        if ($exam->acak_soal) {
            // Urutan acak stabil berdasarkan id santri + id ujian
            $questions = $questions->shuffle();
        }

        $answers = $result->answers_array;
        $raguList = $result->ragu_array;

        return view('santri.cbt.room', compact('student', 'exam', 'result', 'questions', 'answers', 'raguList'));
    }

    /**
     * Autosave Jawaban Santri
     */
    public function autosave(Request $request)
    {
        $student = Auth::guard('santri')->user();
        if (!$student) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $resultId = $request->input('result_id');
        $result = MadrasahExamResult::with('exam')->findOrFail($resultId);

        // Verifikasi kepemilikan
        if ($result->student_id != $student->id && $result->nomor_peserta != $student->nis) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        // Jika ujian sudah terkunci
        if ($result->status_pengerjaan === 'Terkunci' || $result->status_pengerjaan === 'Selesai') {
            return response()->json([
                'force_finish' => true,
                'redirect' => route('santri.cbt.index'),
                'message' => 'Sesi ujian telah selesai atau dikunci oleh pengawas.',
            ]);
        }

        // Update sisa detik
        if ($request->has('sisa_detik')) {
            $result->sisa_detik = max(0, intval($request->input('sisa_detik')));
        }

        // Simpan jawaban per soal jika dikirimkan
        $answers = $result->answers_array;
        $ragu = $result->ragu_array;

        if ($request->filled('question_id')) {
            $qId = $request->input('question_id');
            $ans = $request->input('answer');
            $isRagu = $request->boolean('ragu');

            if ($ans !== null && $ans !== '') {
                $answers[$qId] = strtoupper($ans);
            }

            if ($isRagu) {
                if (!in_array($qId, $ragu)) {
                    $ragu[] = $qId;
                }
            } else {
                $ragu = array_values(array_filter($ragu, fn($id) => $id != $qId));
            }

            $result->answers_json = json_encode($answers);
            $result->ragu_ragu_json = json_encode($ragu);
        }

        $result->save();

        return response()->json([
            'success' => true,
            'terjawab_count' => count($answers),
            'sisa_detik' => $result->sisa_detik,
        ]);
    }

    /**
     * Catat Pelanggaran Anti-Cheat dari Sisi Klien Siswa
     */
    public function logViolation(Request $request)
    {
        $student = Auth::guard('santri')->user();
        if (!$student) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $resultId = $request->input('result_id');
        $result = MadrasahExamResult::with('exam')->findOrFail($resultId);

        if ($result->student_id != $student->id && $result->nomor_peserta != $student->nis) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $type = $request->input('type', 'Beralih Aplikasi');
        $message = $request->input('message', 'Terdeteksi beralih aplikasi atau tab browser saat ujian berlangsung.');

        $alasan = "[{$type}] {$message}";
        $result->tambahPelanggaran($alasan);

        $exam = $result->exam;
        $maxViolations = $exam->max_violations ?? 3;
        $currentCount = $result->jumlah_pelanggaran;

        $autoSubmit = false;
        if ($currentCount >= $maxViolations) {
            $result->status_pengerjaan = 'Terkunci';
            $result->save();
            $autoSubmit = true;
        }

        return response()->json([
            'success' => true,
            'violation_count' => $currentCount,
            'max_violations' => $maxViolations,
            'auto_submit' => $autoSubmit,
            'message' => $autoSubmit 
                ? "Toleransi pelanggaran habis ({$currentCount}/{$maxViolations}). Ujian dikunci dan dialihkan ke pengawas!" 
                : "Peringatan Pelanggaran ({$currentCount}/{$maxViolations}): {$message}",
        ]);
    }

    /**
     * Submit / Selesaikan Ujian
     */
    public function submit(Request $request)
    {
        $student = Auth::guard('santri')->user();
        if (!$student) {
            return redirect()->route('santri.login');
        }

        $resultId = $request->input('result_id');
        $result = MadrasahExamResult::with('exam.questions')->findOrFail($resultId);

        if ($result->student_id != $student->id && $result->nomor_peserta != $student->nis) {
            return redirect()->route('santri.cbt.index')->with('error', 'Akses ditolak.');
        }

        $exam = $result->exam;

        // Ambil semua jawaban form jika disubmit via form POST konvensional
        $rawAnswers = $request->input('answers', []);
        $currentAnswers = $result->answers_array;
        foreach ($rawAnswers as $qId => $val) {
            if (!empty($val)) {
                $currentAnswers[$qId] = strtoupper($val);
            }
        }
        $result->answers_json = json_encode($currentAnswers);
        $result->status_pengerjaan = 'Selesai';
        $result->waktu_selesai = now();
        $result->sisa_detik = 0;

        // Hitung nilai
        $controller = new MadrasahExamController();
        $controller->kalkulasiNilaiResult($exam, $result);
        $result->save();

        $infoNilai = ($exam->tampilkan_nilai) ? " Nilai Anda: {$result->nilai}." : "";
        return redirect()->route('santri.cbt.index')->with('success', "Alhamdulillah, ujian {$exam->mata_pelajaran} berhasil diselesaikan.{$infoNilai}");
    }
}

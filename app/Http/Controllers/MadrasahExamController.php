<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MadrasahExam;
use App\Models\MadrasahExamResult;
use App\Models\MadrasahExamQuestion;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Setting;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Reader\Xls as XlsReader;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Str;

class MadrasahExamController extends Controller
{
    /**
     * Daftar Sesi Ujian Madrasah (Kelas 9 MTs & Kelas 12 MA)
     */
    public function index(Request $request)
    {
        $jenjang = $request->query('jenjang');
        $tingkat = $request->query('tingkat');
        $jurusan = $request->query('jurusan');
        $q = $request->query('q');

        $query = MadrasahExam::withCount(['results', 'questions'])->latest();

        if (!empty($jenjang) && in_array($jenjang, ['MA', 'MTs'])) {
            $query->where('jenjang', $jenjang);
        }

        if (!empty($tingkat) && in_array($tingkat, ['9', '12'])) {
            $query->where('tingkat_kelas', $tingkat);
        }

        if (!empty($jurusan)) {
            $query->where('jurusan', $jurusan);
        }

        if (!empty($q)) {
            $query->where(function($sub) use ($q) {
                $sub->where('nama_ujian', 'like', "%{$q}%")
                    ->orWhere('mata_pelajaran', 'like', "%{$q}%")
                    ->orWhere('nama_guru', 'like', "%{$q}%");
            });
        }

        $exams = $query->paginate(12)->withQueryString();

        $totalExams = MadrasahExam::count();
        $totalMa = MadrasahExam::where('tingkat_kelas', '12')->orWhere('jenjang', 'MA')->count();
        $totalMts = MadrasahExam::where('tingkat_kelas', '9')->orWhere('jenjang', 'MTs')->count();
        $totalPeserta = MadrasahExamResult::count();

        return view('admin.cbt.madrasah.index', compact(
            'exams', 'jenjang', 'tingkat', 'jurusan', 'q',
            'totalExams', 'totalMa', 'totalMts', 'totalPeserta'
        ));
    }

    /**
     * Form Buat Sesi Ujian Baru
     */
    public function create()
    {
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');
        $defaultToken = strtoupper(Str::random(6));
        return view('admin.cbt.madrasah.create', compact('tahunAjaran', 'defaultToken'));
    }

    /**
     * Simpan Sesi Ujian Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ujian' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:150',
            'nama_guru' => 'nullable|string|max:150',
            'jenjang' => 'required|in:MA,MTs',
            'tingkat_kelas' => 'required|in:12,9',
            'jurusan' => 'required|string|max:100', // Bebas diedit / custom
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
            'jumlah_soal' => 'required|integer|min:1|max:200',
            'durasi_menit' => 'required|integer|min:10|max:300',
            'max_attempts' => 'nullable|integer|min:1|max:10',
            'max_violations' => 'nullable|integer|min:1|max:20',
            'token_ujian' => 'nullable|string|max:20',
            'acak_soal' => 'nullable|boolean',
            'acak_opsi' => 'nullable|boolean',
            'tampilkan_nilai' => 'nullable|boolean',
            'kkm' => 'required|numeric|min:0|max:100',
            'tanggal_ujian' => 'nullable|date',
            'status' => 'required|in:Draft,Aktif,Selesai',
            'keterangan' => 'nullable|string',
        ]);

        $validated['max_attempts'] = $request->input('max_attempts', 1) ?: 1;
        $validated['max_violations'] = $request->input('max_violations', 3) ?: 3;
        $validated['token_ujian'] = strtoupper(trim($request->input('token_ujian', '') ?: Str::random(6)));
        $validated['acak_soal'] = $request->has('acak_soal') ? (bool) $request->acak_soal : true;
        $validated['acak_opsi'] = $request->has('acak_opsi') ? (bool) $request->acak_opsi : false;
        $validated['tampilkan_nilai'] = $request->has('tampilkan_nilai') ? (bool) $request->tampilkan_nilai : true;

        $exam = MadrasahExam::create($validated);

        return redirect()->route('admin.cbt.madrasah.show', $exam->id)
            ->with('success', "Sesi ujian {$exam->nama_ujian} ({$exam->mata_pelajaran}) berhasil dibuat! Token Ujian: {$exam->token_ujian}.");
    }

    /**
     * Kelola Peserta & Hasil Nilai Ujian
     */
    public function show($id, Request $request)
    {
        $exam = MadrasahExam::withCount('questions')->findOrFail($id);

        $results = $exam->results;
        $classrooms = Classroom::where('jenjang', $exam->jenjang)->orderBy('nama_kelas')->pluck('nama_kelas');

        $statTertinggi = $results->max('nilai') ?? 0;
        $statTerendah = $results->min('nilai') ?? 0;
        $statRataRata = $results->avg('nilai') ? round($results->avg('nilai'), 2) : 0;
        $totalPeserta = $results->count();

        return view('admin.cbt.madrasah.show', compact(
            'exam', 'results', 'classrooms',
            'statTertinggi', 'statTerendah', 'statRataRata', 'totalPeserta'
        ));
    }

    /**
     * Form Edit Sesi Ujian
     */
    public function edit($id)
    {
        $exam = MadrasahExam::findOrFail($id);
        return view('admin.cbt.madrasah.edit', compact('exam'));
    }

    /**
     * Update Sesi Ujian
     */
    public function update(Request $request, $id)
    {
        $exam = MadrasahExam::findOrFail($id);

        $validated = $request->validate([
            'nama_ujian' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:150',
            'nama_guru' => 'nullable|string|max:150',
            'jenjang' => 'required|in:MA,MTs',
            'tingkat_kelas' => 'required|in:12,9',
            'jurusan' => 'required|string|max:100', // Bebas diedit / custom
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
            'jumlah_soal' => 'required|integer|min:1|max:200',
            'durasi_menit' => 'required|integer|min:10|max:300',
            'max_attempts' => 'nullable|integer|min:1|max:10',
            'max_violations' => 'nullable|integer|min:1|max:20',
            'token_ujian' => 'nullable|string|max:20',
            'kkm' => 'required|numeric|min:0|max:100',
            'tanggal_ujian' => 'nullable|date',
            'status' => 'required|in:Draft,Aktif,Selesai',
            'keterangan' => 'nullable|string',
        ]);

        $validated['max_attempts'] = $request->input('max_attempts', 1) ?: 1;
        $validated['max_violations'] = $request->input('max_violations', 3) ?: 3;
        if ($request->filled('token_ujian')) {
            $validated['token_ujian'] = strtoupper(trim($request->token_ujian));
        }
        $validated['acak_soal'] = $request->has('acak_soal');
        $validated['acak_opsi'] = $request->has('acak_opsi');
        $validated['tampilkan_nilai'] = $request->has('tampilkan_nilai');

        $exam->update($validated);

        return redirect()->route('admin.cbt.madrasah.show', $exam->id)
            ->with('success', "Data sesi ujian {$exam->mata_pelajaran} berhasil diperbarui.");
    }

    /**
     * Hapus Sesi Ujian & Nilainya
     */
    public function destroy($id)
    {
        $exam = MadrasahExam::findOrFail($id);
        $title = $exam->nama_ujian;
        $exam->delete();

        return redirect()->route('admin.cbt.madrasah.index')
            ->with('success', "Sesi ujian '{$title}' dan seluruh data nilainya berhasil dihapus.");
    }

    /**
     * Tarik Santri Aktif Otomatis Masuk ke Ujian
     */
    public function tarikSantri(Request $request, $id)
    {
        $exam = MadrasahExam::findOrFail($id);

        $request->validate([
            'pilihan_kelas' => 'required|string',
        ]);

        $pilihan = $request->pilihan_kelas;
        $query = Student::where('status', 'Aktif');

        if ($pilihan === '__ALL_TINGKAT__') {
            if ($exam->tingkat_kelas == '12') {
                $query->where(function($q) {
                    $q->where('kelas', 'like', 'XII%')->orWhere('kelas', 'like', '12%');
                });
            } else {
                $query->where(function($q) {
                    $q->where('kelas', 'like', 'IX%')->orWhere('kelas', 'like', '9%');
                });
            }
        } else {
            $query->where('kelas', $pilihan);
        }

        $students = $query->orderBy('nama_lengkap')->get();

        if ($students->isEmpty()) {
            return back()->with('error', "Tidak ditemukan santri aktif pada kelas yang dipilih ({$pilihan}).");
        }

        $inserted = 0;
        foreach ($students as $st) {
            $exists = MadrasahExamResult::where('madrasah_exam_id', $exam->id)
                ->where('student_id', $st->id)
                ->exists();

            if (!$exists) {
                MadrasahExamResult::create([
                    'madrasah_exam_id' => $exam->id,
                    'student_id' => $st->id,
                    'nomor_peserta' => $st->nis,
                    'nama_peserta' => strtoupper($st->nama_lengkap),
                    'kelas' => $exam->tingkat_kelas,
                    'jurusan' => $exam->jurusan,
                    'jumlah_soal' => $exam->jumlah_soal,
                    'jumlah_benar' => 0,
                    'jumlah_salah' => $exam->jumlah_soal,
                    'nilai' => 0.00,
                    'status' => 'Hadir',
                ]);
                $inserted++;
            }
        }

        return back()->with('success', "Berhasil menambahkan {$inserted} santri aktif ke daftar peserta ujian.");
    }

    /**
     * Tambah Peserta Ujian Tunggal (Manual)
     */
    public function storePeserta(Request $request, $id)
    {
        $exam = MadrasahExam::findOrFail($id);

        $validated = $request->validate([
            'nama_peserta' => 'required|string|max:150',
            'nomor_peserta' => 'nullable|string|max:50',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'required|string|max:50',
            'jumlah_benar' => 'required|integer|min:0|max:' . $exam->jumlah_soal,
            'status' => 'required|in:Hadir,Susulan,Tidak Hadir',
        ]);

        $benar = (int) $validated['jumlah_benar'];
        $totalSoal = $exam->jumlah_soal;
        $nilai = MadrasahExamResult::hitungNilai($benar, $totalSoal);

        MadrasahExamResult::create([
            'madrasah_exam_id' => $exam->id,
            'nama_peserta' => strtoupper($validated['nama_peserta']),
            'nomor_peserta' => $validated['nomor_peserta'] ?? null,
            'kelas' => $validated['kelas'],
            'jurusan' => $validated['jurusan'],
            'jumlah_soal' => $totalSoal,
            'jumlah_benar' => $benar,
            'jumlah_salah' => max(0, $totalSoal - $benar),
            'nilai' => $nilai,
            'status' => $validated['status'],
        ]);

        return back()->with('success', "Peserta {$validated['nama_peserta']} dengan nilai {$nilai} berhasil ditambahkan!");
    }

    /**
     * Simpan Pembaruan Nilai Peserta Secara Massal
     */
    public function simpanNilaiBulk(Request $request, $id)
    {
        $exam = MadrasahExam::findOrFail($id);

        $resultsData = $request->input('results', []);
        $updated = 0;

        foreach ($resultsData as $resId => $data) {
            $item = MadrasahExamResult::where('madrasah_exam_id', $exam->id)->find($resId);
            if ($item) {
                $totalSoal = $exam->jumlah_soal;
                $benar = isset($data['jumlah_benar']) ? (int) $data['jumlah_benar'] : $item->jumlah_benar;
                $benar = min($benar, $totalSoal);

                // Jika diisi nilai langsung, gunakan nilai tersebut, jika tidak hitung dari jumlah benar
                if (isset($data['nilai']) && is_numeric($data['nilai']) && $data['nilai'] > 0 && !isset($data['jumlah_benar'])) {
                    $nilai = round((float) $data['nilai'], 2);
                    $benar = round(($nilai / 100) * $totalSoal);
                } else {
                    $nilai = MadrasahExamResult::hitungNilai($benar, $totalSoal);
                }

                $item->update([
                    'jumlah_soal' => $totalSoal,
                    'jumlah_benar' => $benar,
                    'jumlah_salah' => max(0, $totalSoal - $benar),
                    'nilai' => $nilai,
                    'status' => $data['status'] ?? $item->status,
                ]);
                $updated++;
            }
        }

        return back()->with('success', "Alhamdulillah! Nilai {$updated} peserta ujian berhasil diperbarui.");
    }

    /**
     * Hapus Peserta dari Ujian
     */
    public function destroyPeserta($id, $resultId)
    {
        $result = MadrasahExamResult::where('madrasah_exam_id', $id)->findOrFail($resultId);
        $nama = $result->nama_peserta;
        $result->delete();

        return back()->with('success', "Peserta {$nama} berhasil dihapus dari daftar ujian.");
    }

    /**
     * Import Nilai Ujian dari File Excel
     */
    public function importExcel(Request $request, $id)
    {
        $exam = MadrasahExam::findOrFail($id);

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:4096',
        ], [
            'excel_file.required' => 'Pilih file Excel yang berisi data nilai peserta.',
            'excel_file.mimes' => 'File harus berformat .xlsx, .xls, atau .csv.',
        ]);

        $file = $request->file('excel_file');
        $ext = strtolower($file->getClientOriginalExtension());

        $reader = ($ext === 'xls') ? new XlsReader() : new XlsxReader();
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        if (count($rows) < 2) {
            return back()->with('error', 'File Excel kosong atau tidak memiliki baris data.');
        }

        // Cari baris header
        $headerRow = 1;
        $colNama = null;
        $colBenar = null;
        $colNilai = null;
        $colKelas = null;
        $colJurusan = null;

        foreach ($rows as $rIdx => $row) {
            foreach ($row as $colKey => $cellVal) {
                $val = strtolower(trim((string)$cellVal));
                if (str_contains($val, 'nama') && !$colNama) $colNama = $colKey;
                if ((str_contains($val, 'benar') || str_contains($val, 'jml benar')) && !$colBenar) $colBenar = $colKey;
                if ((str_contains($val, 'nilai') || str_contains($val, 'skor')) && !$colNilai) $colNilai = $colKey;
                if (str_contains($val, 'kelas') && !$colKelas) $colKelas = $colKey;
                if (str_contains($val, 'jurusan') && !$colJurusan) $colJurusan = $colKey;
            }
            if ($colNama && ($colBenar || $colNilai)) {
                $headerRow = $rIdx;
                break;
            }
        }

        // Fallback kolom standar A=Nama, B=Jumlah Benar, C=Nilai, D=Kelas, E=Jurusan jika tidak ada header
        if (!$colNama) $colNama = 'B';
        if (!$colBenar && !$colNilai) $colBenar = 'C';

        $totalSoal = $exam->jumlah_soal;
        $imported = 0;

        foreach ($rows as $rIdx => $row) {
            if ($rIdx <= $headerRow) continue;

            $nama = trim((string)($row[$colNama] ?? ''));
            if (empty($nama) || is_numeric($nama)) continue;

            $benar = 0;
            $nilai = 0.00;

            if ($colBenar && isset($row[$colBenar]) && is_numeric($row[$colBenar])) {
                $benar = (int) $row[$colBenar];
                $nilai = MadrasahExamResult::hitungNilai($benar, $totalSoal);
            } elseif ($colNilai && isset($row[$colNilai]) && is_numeric($row[$colNilai])) {
                $nilai = round((float) $row[$colNilai], 2);
                $benar = round(($nilai / 100) * $totalSoal);
            }

            $kelas = ($colKelas && !empty($row[$colKelas])) ? trim((string)$row[$colKelas]) : $exam->tingkat_kelas;
            $jurusan = ($colJurusan && !empty($row[$colJurusan])) ? trim((string)$row[$colJurusan]) : $exam->jurusan;

            // Cari santri yang cocok atau update existing
            $existing = MadrasahExamResult::where('madrasah_exam_id', $exam->id)
                ->where('nama_peserta', strtoupper($nama))
                ->first();

            if ($existing) {
                $existing->update([
                    'jumlah_soal' => $totalSoal,
                    'jumlah_benar' => $benar,
                    'jumlah_salah' => max(0, $totalSoal - $benar),
                    'nilai' => $nilai,
                    'kelas' => $kelas,
                    'jurusan' => $jurusan,
                ]);
            } else {
                MadrasahExamResult::create([
                    'madrasah_exam_id' => $exam->id,
                    'nama_peserta' => strtoupper($nama),
                    'kelas' => $kelas,
                    'jurusan' => $jurusan,
                    'jumlah_soal' => $totalSoal,
                    'jumlah_benar' => $benar,
                    'jumlah_salah' => max(0, $totalSoal - $benar),
                    'nilai' => $nilai,
                    'status' => 'Hadir',
                ]);
            }
            $imported++;
        }

        return back()->with('success', "Alhamdulillah! Berhasil mengimpor/memperbarui {$imported} nilai peserta ujian dari Excel.");
    }

    /**
     * Download Template Import Nilai Excel
     */
    public function downloadTemplate($id)
    {
        $exam = MadrasahExam::findOrFail($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Nilai');

        $headers = ['No', 'Nama Peserta', 'Jumlah Benar', 'Nilai (Opsional)', 'Kelas', 'Jurusan'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach ($headers as $idx => $h) {
            $sheet->setCellValue($cols[$idx] . '1', $h);
            $sheet->getStyle($cols[$idx] . '1')->getFont()->setBold(true);
            $sheet->getStyle($cols[$idx] . '1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD1FAE5');
        }

        // Contoh Data
        $sampleData = [
            [1, 'SELVIA STEVANNY', 38, 76.00, $exam->tingkat_kelas, $exam->jurusan],
            [2, 'ATA NUR RIFQI', 37, 74.00, $exam->tingkat_kelas, $exam->jurusan],
            [3, 'AZIZAH SALSABILA AZ ZAHRA', 33, 66.00, $exam->tingkat_kelas, $exam->jurusan],
            [4, 'RIA PUSPITA ANGGRAENI', 31, 62.00, $exam->tingkat_kelas, $exam->jurusan],
            [5, 'HIMATUL IZZAH', 30, 60.00, $exam->tingkat_kelas, $exam->jurusan],
        ];

        $r = 2;
        foreach ($sampleData as $row) {
            foreach ($row as $cIdx => $val) {
                $sheet->setCellValue($cols[$cIdx] . $r, $val);
            }
            $r++;
        }

        foreach ($cols as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $filename = 'Template_Nilai_' . str_replace(' ', '_', $exam->mata_pelajaran) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Cetak Laporan Hasil Ujian Resmi (Sesuai Foto Format Klien)
     */
    public function cetak($id)
    {
        $exam = MadrasahExam::findOrFail($id);
        $results = $exam->results; // sudah diurutkan dari nilai tertinggi

        $statTertinggi = $results->max('nilai') ?? 0;
        $statTerendah = $results->min('nilai') ?? 0;
        $statRataRata = $results->avg('nilai') ? round($results->avg('nilai'), 2) : 0;
        $totalPeserta = $results->count();

        // Identitas Madrasah Sesuai Jenjang
        $isMa = ($exam->jenjang === 'MA' || $exam->tingkat_kelas == '12');
        $namaMadrasah = $isMa ? 'MADRASAH ALIYAH HIDAYATULLAH' : 'MADRASAH TSANAWIYAH HIDAYATULLAH';
        $npsn = $isMa ? Setting::get('ma_npsn', '69994052') : Setting::get('mts_npsn', '69994052');
        $nsm = '121233230034';
        $email = Setting::get('footer_email', 'pondoktuksong@gmail.com');
        $telepon = Setting::get('footer_hotline', '0813 9101 9966');
        $alamat = Setting::get('alamat_kampus', 'JL. Pingit Sumowono KM . Tuksongo ,Nglorog, Pringsurat, Temanggung KODE POS 56272');

        return view('admin.cbt.madrasah.cetak', compact(
            'exam', 'results', 'statTertinggi', 'statTerendah', 'statRataRata', 'totalPeserta',
            'namaMadrasah', 'npsn', 'nsm', 'email', 'telepon', 'alamat', 'isMa'
        ));
    }

    /**
     * Live Monitoring Ujian CBT Madrasah
     */
    public function monitoring($id)
    {
        $exam = MadrasahExam::with(['results' => function ($q) {
            $q->orderBy('nama_peserta', 'asc');
        }])->findOrFail($id);

        return view('admin.cbt.madrasah.monitoring', compact('exam'));
    }

    /**
     * Endpoint Data Real-Time Monitoring (JSON Polling)
     */
    public function monitoringData($id)
    {
        $exam = MadrasahExam::findOrFail($id);
        $results = MadrasahExamResult::where('madrasah_exam_id', $exam->id)
            ->orderBy('nama_peserta', 'asc')
            ->get();

        $data = $results->map(function ($r) use ($exam) {
            $isLocked = ($r->status_pengerjaan === 'Terkunci' || ($r->jumlah_pelanggaran >= ($exam->max_violations ?? 3) && $r->status_pengerjaan !== 'Selesai'));
            return [
                'id' => $r->id,
                'nomor_peserta' => $r->nomor_peserta,
                'nama_peserta' => $r->nama_peserta,
                'kelas' => $r->kelas,
                'status_pengerjaan' => $isLocked ? 'Terkunci' : ($r->status_pengerjaan ?? 'Belum Mulai'),
                'attempt_number' => $r->attempt_number ?? 0,
                'max_attempts' => $exam->max_attempts ?? 1,
                'terjawab_count' => $r->terjawab_count,
                'total_soal' => $exam->jumlah_soal,
                'sisa_detik' => $r->sisa_detik ?? ($exam->durasi_menit * 60),
                'jumlah_pelanggaran' => $r->jumlah_pelanggaran ?? 0,
                'max_violations' => $exam->max_violations ?? 3,
                'log_pelanggaran' => $r->log_pelanggaran_array,
                'nilai' => $r->nilai !== null ? number_format($r->nilai, 2) : '-',
                'is_locked' => $isLocked,
                'waktu_mulai' => $r->waktu_mulai ? $r->waktu_mulai->format('H:i:s') : '-',
                'waktu_selesai' => $r->waktu_selesai ? $r->waktu_selesai->format('H:i:s') : '-',
            ];
        });

        return response()->json([
            'success' => true,
            'exam' => [
                'id' => $exam->id,
                'nama_ujian' => $exam->nama_ujian,
                'mata_pelajaran' => $exam->mata_pelajaran,
                'token_ujian' => $exam->token_ujian,
                'durasi_menit' => $exam->durasi_menit,
                'max_violations' => $exam->max_violations,
            ],
            'results' => $data,
            'summary' => [
                'total' => $results->count(),
                'belum_mulai' => $results->where('status_pengerjaan', 'Belum Mulai')->count(),
                'mengerjakan' => $results->where('status_pengerjaan', 'Mengerjakan')->count(),
                'selesai' => $results->where('status_pengerjaan', 'Selesai')->count(),
                'terkunci' => $results->filter(fn($r) => $r->status_pengerjaan === 'Terkunci' || ($r->jumlah_pelanggaran >= ($exam->max_violations ?? 3) && $r->status_pengerjaan !== 'Selesai'))->count(),
                'melanggar' => $results->where('jumlah_pelanggaran', '>', 0)->count(),
            ]
        ]);
    }

    /**
     * Aksi Pengawas dari Live Monitoring (Buka Kunci, Reset Pelanggaran, Force Submit, Reset Attempt)
     */
    public function monitoringAction(Request $request, $id)
    {
        $exam = MadrasahExam::findOrFail($id);
        $action = $request->input('action');
        $resultId = $request->input('result_id');

        $result = MadrasahExamResult::where('madrasah_exam_id', $exam->id)->findOrFail($resultId);

        if ($action === 'unlock') {
            $result->status_pengerjaan = 'Mengerjakan';
            if ($result->jumlah_pelanggaran >= ($exam->max_violations ?? 3)) {
                $result->jumlah_pelanggaran = max(0, ($exam->max_violations ?? 3) - 1);
            }
            $result->save();
            return response()->json(['success' => true, 'message' => "Ujian santri {$result->nama_peserta} berhasil dibuka kembali."]);
        } elseif ($action === 'reset_violation') {
            $result->jumlah_pelanggaran = 0;
            if ($result->status_pengerjaan === 'Terkunci') {
                $result->status_pengerjaan = 'Mengerjakan';
            }
            $result->save();
            return response()->json(['success' => true, 'message' => "Riwayat pelanggaran santri {$result->nama_peserta} berhasil direset."]);
        } elseif ($action === 'force_submit') {
            $result->status_pengerjaan = 'Selesai';
            $result->waktu_selesai = now();
            $this->kalkulasiNilaiResult($exam, $result);
            $result->save();
            return response()->json(['success' => true, 'message' => "Ujian santri {$result->nama_peserta} berhasil dipaksa selesai dan dinilai."]);
        } elseif ($action === 'reset_attempt') {
            $result->status_pengerjaan = 'Belum Mulai';
            $result->answers_json = null;
            $result->ragu_ragu_json = null;
            $result->jumlah_pelanggaran = 0;
            $result->log_pelanggaran_json = null;
            $result->waktu_mulai = null;
            $result->waktu_selesai = null;
            $result->sisa_detik = $exam->durasi_menit * 60;
            $result->save();
            return response()->json(['success' => true, 'message' => "Percobaan ujian santri {$result->nama_peserta} berhasil direset."]);
        }

        return response()->json(['success' => false, 'message' => 'Aksi pengawas tidak dikenali.'], 400);
    }

    /**
     * Kelola Butir Soal Ujian
     */
    public function soalIndex($id)
    {
        $exam = MadrasahExam::with(['questions' => function ($q) {
            $q->orderBy('nomor_urut', 'asc');
        }])->findOrFail($id);

        return view('admin.cbt.madrasah.soal', compact('exam'));
    }

    /**
     * Tambah / Edit Butir Soal Ujian
     */
    public function soalStore(Request $request, $id)
    {
        $exam = MadrasahExam::findOrFail($id);

        $validated = $request->validate([
            'question_id' => 'nullable|integer',
            'nomor_urut' => 'nullable|integer',
            'pertanyaan' => 'required|string',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'opsi_c' => 'required|string',
            'opsi_d' => 'required|string',
            'opsi_e' => 'nullable|string',
            'kunci_jawaban' => 'required|in:A,B,C,D,E',
            'bobot' => 'nullable|numeric|min:0',
            'pembahasan' => 'nullable|string',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar_file')) {
            $dest = public_path('uploads/cbt_madrasah_soal');
            if (!file_exists($dest)) {
                mkdir($dest, 0777, true);
            }
            $file = $request->file('gambar_file');
            $filename = 'soal_m_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($dest, $filename);
            $gambarPath = '/uploads/cbt_madrasah_soal/' . $filename;
        }

        if (!empty($validated['question_id'])) {
            $question = MadrasahExamQuestion::where('madrasah_exam_id', $exam->id)->findOrFail($validated['question_id']);
            $dataUpdate = [
                'pertanyaan' => $validated['pertanyaan'],
                'opsi_a' => $validated['opsi_a'],
                'opsi_b' => $validated['opsi_b'],
                'opsi_c' => $validated['opsi_c'],
                'opsi_d' => $validated['opsi_d'],
                'opsi_e' => $validated['opsi_e'] ?? null,
                'kunci_jawaban' => $validated['kunci_jawaban'],
                'bobot' => $validated['bobot'] ?? 1.00,
                'pembahasan' => $validated['pembahasan'] ?? null,
            ];
            if (!empty($validated['nomor_urut'])) {
                $dataUpdate['nomor_urut'] = $validated['nomor_urut'];
            }
            if ($gambarPath) {
                $dataUpdate['gambar'] = $gambarPath;
            }
            $question->update($dataUpdate);
            $msg = "Butir soal #{$question->nomor_urut} berhasil diperbarui.";
        } else {
            $maxNo = MadrasahExamQuestion::where('madrasah_exam_id', $exam->id)->max('nomor_urut') ?? 0;
            $nextNo = $validated['nomor_urut'] ?? ($maxNo + 1);

            $question = MadrasahExamQuestion::create([
                'madrasah_exam_id' => $exam->id,
                'nomor_urut' => $nextNo,
                'pertanyaan' => $validated['pertanyaan'],
                'gambar' => $gambarPath,
                'opsi_a' => $validated['opsi_a'],
                'opsi_b' => $validated['opsi_b'],
                'opsi_c' => $validated['opsi_c'],
                'opsi_d' => $validated['opsi_d'],
                'opsi_e' => $validated['opsi_e'] ?? null,
                'kunci_jawaban' => $validated['kunci_jawaban'],
                'bobot' => $validated['bobot'] ?? 1.00,
                'pembahasan' => $validated['pembahasan'] ?? null,
            ]);
            $msg = "Soal nomor #{$nextNo} berhasil ditambahkan.";
        }

        // Sinkronkan jumlah_soal sesi ujian jika ada penambahan
        $currentCount = MadrasahExamQuestion::where('madrasah_exam_id', $exam->id)->count();
        if ($currentCount > $exam->jumlah_soal) {
            $exam->update(['jumlah_soal' => $currentCount]);
        }

        return redirect()->route('admin.cbt.madrasah.soal.index', $exam->id)->with('success', $msg);
    }

    /**
     * Hapus Butir Soal Ujian
     */
    public function soalDestroy($id, $questionId)
    {
        $exam = MadrasahExam::findOrFail($id);
        $question = MadrasahExamQuestion::where('madrasah_exam_id', $exam->id)->findOrFail($questionId);
        $nomor = $question->nomor_urut;
        $question->delete();

        // Rapatkan nomor urut
        $questions = MadrasahExamQuestion::where('madrasah_exam_id', $exam->id)->orderBy('nomor_urut', 'asc')->get();
        $idx = 1;
        foreach ($questions as $q) {
            $q->update(['nomor_urut' => $idx++]);
        }

        return redirect()->route('admin.cbt.madrasah.soal.index', $exam->id)
            ->with('success', "Soal nomor #{$nomor} berhasil dihapus.");
    }

    /**
     * Unduh Template Excel Import Soal
     */
    public function soalDownloadTemplate($id)
    {
        $exam = MadrasahExam::findOrFail($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Soal CBT');

        $headers = [
            'A1' => 'NO',
            'B1' => 'PERTANYAAN',
            'C1' => 'OPSI_A',
            'D1' => 'OPSI_B',
            'E1' => 'OPSI_C',
            'D1' => 'OPSI_D',
            'F1' => 'OPSI_E',
            'G1' => 'KUNCI (A/B/C/D/E)',
            'H1' => 'BOBOT',
            'I1' => 'PEMBAHASAN (OPSIONAL)',
        ];

        // Koreksi header keys
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        $titles = ['NO', 'PERTANYAAN', 'OPSI_A', 'OPSI_B', 'OPSI_C', 'OPSI_D', 'OPSI_E', 'KUNCI', 'BOBOT'];

        foreach ($cols as $idx => $col) {
            $sheet->setCellValue($col . '1', $titles[$idx]);
            $sheet->getStyle($col . '1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $sheet->getStyle($col . '1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('15803D');
            $sheet->getStyle($col . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $sampleData = [
            [1, 'Surah dalam Al-Quran yang pertama kali diturunkan adalah...', 'Al-Fatihah', 'Al-Alaq 1-5', 'Al-Baqarah', 'Al-Ikhlas', 'An-Nas', 'B', 1],
            [2, 'Hukum bacaan mim sukun bertemu huruf ba adalah...', 'Ikhfa Syafawi', 'Idgham Mimi', 'Izhar Syafawi', 'Iqlab', 'Ghunnah', 'A', 1],
        ];

        $r = 2;
        foreach ($sampleData as $row) {
            foreach ($row as $cIdx => $val) {
                $sheet->setCellValue($cols[$cIdx] . $r, $val);
            }
            $r++;
        }

        foreach ($cols as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $filename = 'Template_Soal_' . str_replace(' ', '_', $exam->mata_pelajaran) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Import Butir Soal dari File Excel
     */
    public function soalImport(Request $request, $id)
    {
        $exam = MadrasahExam::findOrFail($id);

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        $file = $request->file('excel_file');
        $extension = strtolower($file->getClientOriginalExtension());

        $reader = ($extension === 'xls') ? new XlsReader() : new XlsxReader();
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $imported = 0;
        $maxNo = MadrasahExamQuestion::where('madrasah_exam_id', $exam->id)->max('nomor_urut') ?? 0;

        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex === 1) continue; // skip header

            $pertanyaan = trim($row['B'] ?? '');
            if (empty($pertanyaan)) continue;

            $opsiA = trim($row['C'] ?? '');
            $opsiB = trim($row['D'] ?? '');
            $opsiC = trim($row['E'] ?? '');
            $opsiD = trim($row['F'] ?? '');
            $opsiE = trim($row['G'] ?? '');
            $kunci = strtoupper(trim($row['H'] ?? 'A'));
            $bobot = floatval($row['I'] ?? 1.00);
            if (!in_array($kunci, ['A', 'B', 'C', 'D', 'E'])) {
                $kunci = 'A';
            }

            $maxNo++;
            MadrasahExamQuestion::create([
                'madrasah_exam_id' => $exam->id,
                'nomor_urut' => $maxNo,
                'pertanyaan' => $pertanyaan,
                'opsi_a' => $opsiA,
                'opsi_b' => $opsiB,
                'opsi_c' => $opsiC,
                'opsi_d' => $opsiD,
                'opsi_e' => $opsiE ?: null,
                'kunci_jawaban' => $kunci,
                'bobot' => $bobot ?: 1.00,
            ]);

            $imported++;
        }

        $totalQuestions = MadrasahExamQuestion::where('madrasah_exam_id', $exam->id)->count();
        if ($totalQuestions > $exam->jumlah_soal) {
            $exam->update(['jumlah_soal' => $totalQuestions]);
        }

        return redirect()->route('admin.cbt.madrasah.soal.index', $exam->id)
            ->with('success', "Berhasil mengimpor {$imported} butir soal ke dalam ujian {$exam->mata_pelajaran}.");
    }

    /**
     * Hitung Nilai Otomatis Berdasarkan Soal & Kunci Jawaban
     */
    public function kalkulasiNilaiResult(MadrasahExam $exam, MadrasahExamResult $result)
    {
        $questions = $exam->questions;
        if ($questions->isEmpty()) {
            return;
        }

        $answers = $result->answers_array;
        $totalQuestions = $questions->count();
        $totalBobot = $questions->sum('bobot') ?: $totalQuestions;
        $totalSkor = 0;
        $benar = 0;
        $salah = 0;

        foreach ($questions as $q) {
            $ans = strtoupper(trim($answers[$q->id] ?? ''));
            $kunci = strtoupper(trim($q->kunci_jawaban));
            if ($ans !== '' && $ans === $kunci) {
                $totalSkor += (float) ($q->bobot ?: 1);
                $benar++;
            } elseif ($ans !== '') {
                $salah++;
            }
        }

        $nilai = ($totalBobot > 0) ? round(($totalSkor / $totalBobot) * 100, 2) : 0;
        $result->nilai = $nilai;
        $result->jumlah_benar = $benar;
        $result->jumlah_salah = $salah;
        $result->save();
    }
}

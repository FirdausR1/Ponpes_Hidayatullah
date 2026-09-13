<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Models\StudentPaymentItem;
use App\Models\StudentBill;
use App\Models\StudentDiscount;
use App\Models\PsbRegistration;
use App\Models\Classroom;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PaymentController extends Controller
{
    /**
     * Daftar Pos Biaya Standar Spreadsheet Pembukuan Bendahara
     */
    public const POS_BIAYA_LIST = [
        'MAKAN' => 'Uang Makan 3x Sehari',
        'SYAHRIYAH' => 'Syahriyah Pendidikan (SPP)',
        'SOT' => 'Iuran SOT',
        'TAB' => 'Tabungan Wajib Santri',
        'KENAIKAN' => 'Kenaikan Kelas',
        'KESEHATAN' => 'Kesehatan Santri (1 Thn)',
        'KEGIATAN' => 'Kegiatan Santri (1 Thn)',
        'PENDAFTARAN' => 'Pendaftaran Santri Baru',
        'PG' => 'PG',
        'PANGKAL' => 'Uang Pangkal',
        'GEDUNG' => 'Uang Gedung',
        'KERTAS' => 'Kertas / Evaluasi Belajar',
        'SERAGAM' => 'Seragam Santri',
        'ALMARI' => 'Almari & Fasilitas Asrama',
        'IJAZAH' => 'Ijazah',
        'LKS' => 'Buku LKS',
        'KITAB' => 'Kitab Turats / Diniyah',
        'PUZZLE' => 'Puzzle & Alat Peraga',
        'WISUDA KHATAMAN' => 'Wisuda Khataman Al-Qur\'an',
        'MANASIK' => 'Manasik Haji Santri',
        'AKHIRUSANAH/PENGAJIAN' => 'Akhirusanah / Pengajian',
        'ZIARAH' => 'Ziarah Religi (Kls X/IX)',
        'STUDY TOUR' => 'Study Tour Santri',
        'SISWA AKHIR' => 'Kegiatan Siswa Akhir',
        'WISUDA' => 'Wisuda Madrasah (Kls 9/12)',
        'SAKU' => 'Titipan Uang Saku',
        'KUNJUNGAN' => 'Kunjungan Edukasi',
        'UJIAN KLS 3' => 'Ujian Akhir Kelas 3',
        'LDK' => 'LDK Kepemimpinan',
        'JAMBORE' => 'Jambore Kepanduan',
        'AMBIL TABUNGAN' => 'Pengambilan Tabungan',
        'DA' => 'Dewan Ambalan Pramuka',
        'KALENDER' => 'Kalender Pesantren',
        'OUTING CLASS' => 'Outing Class',
        'PENGEMBANGAN PONDOK' => 'Infaq Pengembangan Pondok',
        'JUZ AMMA' => 'Ujian Juz Amma',
        'BINADZOR' => 'Ujian Binadzor',
        'BILGHOIB' => 'Ujian Bilghoib',
        'SUMUR BUR' => 'Infaq Fasilitas Sumur Bor',
        'KUNJUNGAN GONTOR' => 'Kunjungan Pondok Gontor',
    ];

    /**
     * Dashboard & Kasir Pembayaran Santri
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'kasir'); // default: 'kasir', opsi lain: 'santri', 'psb'
        $santriId = $request->query('santri_id');

        // 1. KPI Keuangan & Scope PSB Lolos/Diterima
        $kkm = (int) Setting::get('cbt_passing_grade', 70);
        $filterKelulusan = $request->query('filter_kelulusan', 'lulus'); // 'lulus' (default) atau 'semua'

        $eligiblePsbScope = function ($q) use ($kkm) {
            $q->where(function ($sub) use ($kkm) {
                // Jalur 1: Diluluskan secara manual/kebijakan oleh admin
                $sub->where(function ($s) {
                    $s->where('status', 'Diterima')
                      ->orWhereIn('status_kelulusan_override', ['Lulus', 'Lulus Bersyarat']);
                })
                // Jalur 2: Lolos seleksi nilai CBT (>= KKM) DAN ada bukti transfer/pembayaran infaq
                ->orWhere(function ($s) use ($kkm) {
                    $s->where(function ($sc) use ($kkm) {
                        $sc->where('nilai_ujian', '>=', $kkm)
                           ->orWhere('status_kelulusan_override', 'Lulus');
                    })
                    ->where(function ($b) {
                        $b->whereNotNull('bukti_transfer')
                          ->orWhere('status_pembayaran', 'Lunas');
                    });
                });
            })
            // Pastikan calon santri yang Ditolak oleh admin atau Tidak Lulus TIDAK dimasukkan
            ->where(function ($sq) {
                $sq->whereNull('status')->orWhere('status', '!=', 'Ditolak');
            })
            ->where(function ($sq) {
                $sq->whereNull('status_kelulusan_override')->orWhere('status_kelulusan_override', '!=', 'Tidak Lulus');
            });
        };

        $totalPemasukanPsb = PsbRegistration::where('status_pembayaran', 'Lunas')
            ->where($eligiblePsbScope)
            ->sum('nominal_pembayaran');
        if (!$totalPemasukanPsb) {
            $countLunasPsb = PsbRegistration::where('status_pembayaran', 'Lunas')
                ->where($eligiblePsbScope)
                ->count();
            $totalPemasukanPsb = $countLunasPsb * 3225000;
        }

        $totalPemasukanSpp = StudentPayment::where('status', 'Lunas')->sum('nominal');
        $totalTunggakan = StudentBill::where('status', '!=', 'Lunas')->sum('sisa_tagihan');
        $psbPendingVerify = PsbRegistration::whereNotNull('bukti_transfer')
            ->where($eligiblePsbScope)
            ->where(function($q) {
                $q->whereNull('status_pembayaran')->orWhere('status_pembayaran', '!=', 'Lunas');
            })->count();
        $totalTransaksiSantri = StudentPayment::count();

        // 2. Data Pembayaran PSB (Hanya yang lulus seleksi / diluluskan admin secara default)
        $psbQuery = PsbRegistration::with('student')->latest();
        if ($filterKelulusan !== 'semua') {
            $psbQuery->where($eligiblePsbScope);
        }

        if ($request->filled('q_psb')) {
            $q = $request->q_psb;
            $psbQuery->where(function($w) use ($q) {
                $w->where('nama_lengkap', 'like', "%{$q}%")
                  ->orWhere('no_registrasi', 'like', "%{$q}%")
                  ->orWhere('no_whatsapp', 'like', "%{$q}%");
            });
        }
        if ($request->filled('status_psb')) {
            $psbQuery->where('status_pembayaran', $request->status_psb);
        }
        $psbPayments = $psbQuery->paginate(15, ['*'], 'page_psb')->withQueryString();

        // 3. Data Pembayaran SPP / Iuran Santri Aktif
        $santriQuery = StudentPayment::with(['student', 'items'])->latest();
        if ($request->filled('q_santri')) {
            $q = $request->q_santri;
            $santriQuery->where(function($w) use ($q) {
                $w->where('no_transaksi', 'like', "%{$q}%")
                  ->orWhereHas('student', function($sq) use ($q) {
                      $sq->where('nama_lengkap', 'like', "%{$q}%")
                         ->orWhere('nis', 'like', "%{$q}%")
                         ->orWhere('kelas', 'like', "%{$q}%");
                  });
            });
        }
        if ($request->filled('kelas')) {
            $santriQuery->whereHas('student', function($sq) use ($request) {
                $sq->where('kelas', $request->kelas);
            });
        }
        if ($request->filled('tanggal_mulai')) {
            $santriQuery->whereDate('tanggal_bayar', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $santriQuery->whereDate('tanggal_bayar', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('jenis_pembayaran')) {
            $santriQuery->where('jenis_pembayaran', 'like', "%{$request->jenis_pembayaran}%");
        }
        if ($request->filled('status_santri')) {
            $santriQuery->where('status', $request->status_santri);
        }
        $studentPayments = $santriQuery->paginate(15, ['*'], 'page_santri')->withQueryString();

        // Santri aktif untuk kasir
        $activeStudents = Student::where('status', 'Aktif')
            ->orderBy('nama_lengkap')
            ->get(['id', 'nis', 'nama_lengkap', 'kelas', 'jenjang', 'kamar_asrama', 'foto'])
            ->map(function ($s) {
                $s->saldo_tabungan = $s->saldo_tabungan; // Append property dynamically
                return $s;
            });

        // Calon Santri Baru (PSB) yang berhak bayar (bisa dicari di Kasir POS)
        $psbCandidates = PsbRegistration::whereNull('status')->orWhere('status', '!=', 'Ditolak')
            ->where(function($q) {
                $q->whereNull('status_kelulusan_override')->orWhere('status_kelulusan_override', '!=', 'Tidak Lulus');
            })
            ->orderBy('nama_lengkap')
            ->get(['id', 'no_registrasi', 'nama_lengkap', 'jenjang', 'status_pembayaran', 'nominal_pembayaran', 'no_whatsapp', 'pas_foto']);

        $classrooms = Classroom::orderBy('jenjang')->orderBy('nama_kelas')->get();
        $posBiayaList = self::POS_BIAYA_LIST;

        return view('admin.pembayaran.index', compact(
            'tab',
            'santriId',
            'totalPemasukanPsb',
            'totalPemasukanSpp',
            'totalTunggakan',
            'psbPendingVerify',
            'totalTransaksiSantri',
            'psbPayments',
            'studentPayments',
            'activeStudents',
            'psbCandidates',
            'classrooms',
            'posBiayaList',
            'kkm',
            'filterKelulusan'
        ));
    }

    /**
     * AJAX: Ambil tagihan aktif yang belum lunas milik santri (dikelompokkan & tarif standar).
     */
    public function getStudentBillsAjax($studentId)
    {
        $student = Student::with(['classroom', 'dormitory'])->findOrFail($studentId);

        $bills = StudentBill::where('student_id', $studentId)
            ->where('status', '!=', 'Lunas')
            ->where('penangguhan_wisuda', false)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->orderBy('pos_biaya')
            ->get();

        $discounts = StudentDiscount::where('student_id', $studentId)
            ->where('status', 'Aktif')
            ->get();

        // Standar tarif bulanan santri ini (berdasarkan mukim/laju & MTs/MA)
        $isMukim = !empty($student->kamar_asrama) && !str_contains(strtolower($student->kamar_asrama), 'laju');
        $isMA = strtoupper($student->jenjang ?? '') === 'MA' || str_contains(strtoupper($student->jenjang ?? ''), 'MA');

        $tarifMakan = $isMukim ? 300000 : 0;
        $tarifSyahriyah = $isMA ? ($isMukim ? 105000 : 75000) : ($isMukim ? 85000 : 55000);
        $tarifTabungan = 25000;
        $tarifSot = $isMA ? 75000 : 55000;

        $curMonth = date('F');
        $indoMonths = [
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
            'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
            'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
            'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
        ];
        $curMonthIndo = $indoMonths[$curMonth] ?? $curMonth;
        $curYear = date('Y');

        $monthOrder = [
            'januari' => 1, 'january' => 1,
            'februari' => 2, 'february' => 2,
            'maret' => 3, 'march' => 3,
            'april' => 4,
            'mei' => 5, 'may' => 5,
            'juni' => 6, 'june' => 6,
            'juli' => 7, 'july' => 7,
            'agustus' => 8, 'august' => 8,
            'september' => 9,
            'oktober' => 10, 'october' => 10,
            'november' => 11,
            'desember' => 12, 'december' => 12,
        ];
        $curMonthNum = date('n');

        $categorizedBills = $bills->map(function($b) use ($curMonth, $curMonthIndo, $curYear, $monthOrder, $curMonthNum) {
            $isBulanan = $b->kategori === 'bulanan';
            $isCurrent = false;
            $isPast = false;

            if ($isBulanan && $b->bulan) {
                $bBulanLower = strtolower(trim($b->bulan));
                $bMonthNum = $monthOrder[$bBulanLower] ?? 0;
                $bTahun = intval($b->tahun ?: $curYear);

                if ($bTahun < intval($curYear) || ($bTahun == intval($curYear) && $bMonthNum > 0 && $bMonthNum < $curMonthNum)) {
                    $isPast = true;
                } elseif ($bTahun == intval($curYear) && ($bMonthNum == $curMonthNum || in_array($bBulanLower, [strtolower($curMonth), strtolower($curMonthIndo)]))) {
                    $isCurrent = true;
                }
            }

            return [
                'id' => $b->id,
                'pos_biaya' => $b->pos_biaya,
                'judul_tagihan' => $b->judul_tagihan,
                'kategori' => $b->kategori,
                'bulan' => $b->bulan,
                'tahun' => $b->tahun,
                'nominal_tagihan' => (float)$b->nominal_tagihan,
                'nominal_bayar' => (float)$b->nominal_bayar,
                'sisa_tagihan' => (float)$b->sisa_tagihan,
                'nominal_potongan' => (float)$b->nominal_potongan,
                'alasan_potongan' => $b->alasan_potongan,
                'status' => $b->status,
                'is_past' => $isPast,
                'is_current' => $isCurrent,
                'is_incidental' => !$isBulanan,
                'nominal_input' => 0, // default tidak memaksa checked semua
                'checked' => false,
            ];
        });

        // Kelompokkan tunggakan per periode/bulan secara detail
        $tunggakanList = $categorizedBills->filter(fn($b) => $b['sisa_tagihan'] > 0);
        $tunggakanByMonth = [];
        foreach ($tunggakanList as $tb) {
            $key = $tb['bulan'] ? ($tb['bulan'] . ' ' . ($tb['tahun'] ?: $curYear)) : ($tb['tahun'] ? 'Tahun ' . $tb['tahun'] : 'Biaya Tambahan / Insidental');
            if (!isset($tunggakanByMonth[$key])) {
                $tunggakanByMonth[$key] = [
                    'periode' => $key,
                    'is_past' => $tb['is_past'],
                    'is_current' => $tb['is_current'],
                    'total_sisa' => 0,
                    'items' => [],
                ];
            }
            $tunggakanByMonth[$key]['total_sisa'] += $tb['sisa_tagihan'];
            $tunggakanByMonth[$key]['items'][] = [
                'id' => $tb['id'],
                'pos_biaya' => $tb['pos_biaya'],
                'judul_tagihan' => $tb['judul_tagihan'],
                'nominal_tagihan' => $tb['nominal_tagihan'],
                'nominal_bayar' => $tb['nominal_bayar'],
                'sisa_tagihan' => $tb['sisa_tagihan'],
                'status' => $tb['status'],
                'is_past' => $tb['is_past'],
            ];
        }

        return response()->json([
            'student' => $student,
            'saldo_tabungan' => $student->saldo_tabungan,
            'bills' => $categorizedBills,
            'discounts' => $discounts,
            'total_tunggakan' => $bills->sum('sisa_tagihan'),
            'formatted_total_tunggakan' => 'Rp ' . number_format($bills->sum('sisa_tagihan'), 0, ',', '.'),
            'tunggakan_count' => $tunggakanList->count(),
            'tunggakan_by_month' => array_values($tunggakanByMonth),
            'standard_tariffs' => [
                'MAKAN' => $tarifMakan,
                'SYAHRIYAH' => $tarifSyahriyah,
                'SOT' => $tarifSot,
                'TAB' => $tarifTabungan,
            ],
            'current_month' => $curMonthIndo,
            'current_year' => $curYear,
        ]);
    }

    /**
     * AJAX: Ambil data calon santri baru PSB untuk Kasir POS.
     */
    public function getPsbBillsAjax($psbId)
    {
        $psb = PsbRegistration::findOrFail($psbId);
        $standardNominal = 3225000;
        $terbayar = floatval($psb->nominal_pembayaran ?? 0);
        $sisa = max(0, $standardNominal - $terbayar);

        return response()->json([
            'psb' => $psb,
            'standard_nominal' => $standardNominal,
            'terbayar' => $terbayar,
            'sisa' => $sisa,
            'status_pembayaran' => $psb->status_pembayaran ?: ($terbayar > 0 ? 'Cicilan' : 'Belum Bayar'),
        ]);
    }

    /**
     * Catat Transaksi Pembayaran Kasir (Mendukung Santri Aktif & Calon PSB, Parsial, Skip Bulan, & Advance).
     */
    public function storeStudentPayment(Request $request)
    {
        $validated = $request->validate([
            'payment_type' => 'nullable|string|in:santri,psb',
            'student_id' => 'nullable|exists:students,id',
            'psb_id' => 'nullable|exists:psb_registrations,id',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|string|in:Tunai,Transfer Bank',
            'catatan' => 'nullable|string|max:255',
            'keterangan_periode' => 'nullable|string|max:100',
            'bukti_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'items' => 'nullable|array',
            'single_pos' => 'nullable|string',
            'single_nominal' => 'nullable|numeric|min:1000',
            'psb_nominal' => 'nullable|numeric|min:1000',
        ]);

        // Upload bukti transfer jika ada
        $buktiPath = null;
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $filename = time() . '_bayar_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pembayaran'), $filename);
            $buktiPath = '/uploads/pembayaran/' . $filename;
        }

        // ==========================================
        // JALUR A: PEMBAYARAN CALON SANTRI BARU PSB
        // ==========================================
        if ($request->filled('psb_id') || $request->input('payment_type') === 'psb') {
            $reg = PsbRegistration::findOrFail($request->psb_id);
            $nominalBayar = floatval($request->input('psb_nominal', $request->input('single_nominal', 3225000)));

            if ($nominalBayar <= 0) {
                return redirect()->back()->with('error', 'Masukkan nominal pembayaran PSB yang valid!');
            }

            $standardPsb = 3225000;
            $currentNominal = floatval($reg->nominal_pembayaran ?? 0) + $nominalBayar;
            $statusBayar = $currentNominal >= $standardPsb ? 'Lunas' : 'Cicilan';

            $reg->update([
                'status_pembayaran' => $statusBayar,
                'nominal_pembayaran' => $currentNominal,
                'tanggal_bayar' => $validated['tanggal_bayar'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'catatan_pembayaran' => $validated['catatan'] ?: 'Pembayaran Administrasi & Daftar Ulang PSB',
                'bukti_transfer' => $buktiPath ?: $reg->bukti_transfer,
            ]);

            // Cek apakah calon santri sudah terdaftar di data students
            $student = Student::where('psb_registration_id', $reg->id)->first();

            // Generate No Transaksi Unik
            $todayPrefix = 'BYR-' . date('Ymd');
            $lastPaymentToday = StudentPayment::where('no_transaksi', 'like', "{$todayPrefix}%")->count();
            $noTransaksi = $todayPrefix . '-' . str_pad($lastPaymentToday + 1, 4, '0', STR_PAD_LEFT);

            $payment = StudentPayment::create([
                'student_id' => $student?->id,
                'psb_registration_id' => $reg->id,
                'user_id' => auth()->id(),
                'no_transaksi' => $noTransaksi,
                'jenis_pembayaran' => 'PENDAFTARAN & DAFTAR ULANG PSB',
                'bulan' => null,
                'tahun' => date('Y'),
                'nominal' => $nominalBayar,
                'tanggal_bayar' => $validated['tanggal_bayar'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status' => 'Lunas',
                'bukti_bayar' => $buktiPath,
                'catatan' => ($validated['catatan'] ?? null) ?: "Pembayaran Masuk PSB: {$reg->no_registrasi}",
                'penerima_nama' => auth()->user()->name ?? 'Bendahara Pesantren',
            ]);

            StudentPaymentItem::create([
                'payment_id' => $payment->id,
                'pos_biaya' => 'PENDAFTARAN',
                'nominal' => $nominalBayar,
                'keterangan' => "PSB: {$reg->no_registrasi} - {$reg->nama_lengkap}",
            ]);

            $fmtNominal = 'Rp ' . number_format($nominalBayar, 0, ',', '.');
            return redirect()->route('admin.pembayaran.index', ['tab' => 'kasir'])
                ->with('success', "Pembayaran PSB sebesar {$fmtNominal} untuk {$reg->nama_lengkap} ({$reg->no_registrasi}) berhasil dicatat. No. Kwitansi: {$noTransaksi}")
                ->with('last_payment_id', $payment->id)
                ->with('last_psb_id', $reg->id);
        }

        // ==========================================
        // JALUR B: PEMBAYARAN SANTRI AKTIF / LAMA
        // ==========================================
        if (!$request->filled('student_id')) {
            return redirect()->back()->with('error', 'Silakan pilih santri atau calon PSB pembayar terlebih dahulu!');
        }

        $student = Student::findOrFail($request->student_id);

        $paymentItemsData = [];
        $totalNominal = 0;

        if (!empty($validated['items']) && is_array($validated['items'])) {
            foreach ($validated['items'] as $key => $val) {
                $nom = 0;
                if (is_array($val)) {
                    $rawPos = strtoupper(trim($val['pos_biaya'] ?? ($val['custom_name'] ?? $key)));
                    if (($rawPos === '__CUSTOM__' || $rawPos === 'KUSTOM') && !empty($val['custom_name'])) {
                        $pos = strtoupper(preg_replace('/[^a-zA-Z0-9_\-\s]/', '', trim($val['custom_name'])));
                    } else {
                        $pos = strtoupper(preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $rawPos));
                    }
                    if (empty($pos)) {
                        $pos = 'LAIN-LAIN';
                    }
                    $billId = !empty($val['bill_id']) ? $val['bill_id'] : null;
                    $isAdvance = !empty($val['is_advance']);
                    $targetBulan = $val['bulan'] ?? null;
                    $targetTahun = $val['tahun'] ?? null;
                    $keteranganItem = $val['keterangan'] ?? null;
                    $rawNom = $val['nominal'] ?? 0;
                    $nom = is_numeric($rawNom) ? floatval($rawNom) : floatval(preg_replace('/[^0-9.]/', '', (string)$rawNom));
                } else {
                    $nom = is_numeric($val) ? floatval($val) : floatval(preg_replace('/[^0-9.]/', '', (string)$val));
                    $pos = strtoupper(trim($key));
                    $billId = null;
                    $isAdvance = false;
                    $targetBulan = null;
                    $targetTahun = null;
                    $keteranganItem = null;
                }

                if ($nom > 0) {
                    $paymentItemsData[] = [
                        'pos_biaya' => $pos,
                        'nominal' => $nom,
                        'bill_id' => $billId,
                        'is_advance' => $isAdvance,
                        'target_bulan' => $targetBulan,
                        'target_tahun' => $targetTahun,
                        'keterangan' => $keteranganItem,
                    ];
                    $totalNominal += $nom;
                }
            }
        }

        // Fallback jika pembayaran single nominal dari form sederhana
        if (empty($paymentItemsData) && !empty($request->single_nominal) && floatval($request->single_nominal) > 0) {
            $pos = strtoupper(trim($request->single_pos ?: 'SYAHRIYAH'));
            $nom = floatval($request->single_nominal);
            $paymentItemsData[] = [
                'pos_biaya' => $pos,
                'nominal' => $nom,
                'bill_id' => $request->single_bill_id ?: null,
                'is_advance' => false,
                'target_bulan' => null,
                'target_tahun' => null,
                'keterangan' => null,
            ];
            $totalNominal += $nom;
        }

        if ($totalNominal <= 0) {
            return redirect()->back()->with('error', 'Gagal mencatat pembayaran! Centang pos tagihan atau masukkan nominal pembayaran minimal pada salah satu pos biaya.');
        }

        // Generate Nomor Transaksi Unik: BYR-20260912-0001
        $todayPrefix = 'BYR-' . date('Ymd');
        $lastPaymentToday = StudentPayment::where('no_transaksi', 'like', "{$todayPrefix}%")->count();
        $noTransaksi = $todayPrefix . '-' . str_pad($lastPaymentToday + 1, 4, '0', STR_PAD_LEFT);

        $posSummary = count($paymentItemsData) === 1 
            ? $paymentItemsData[0]['pos_biaya'] 
            : 'Multi-Pos (' . count($paymentItemsData) . ' Pos)';

        $payment = StudentPayment::create([
            'student_id' => $student->id,
            'user_id' => auth()->id(),
            'no_transaksi' => $noTransaksi,
            'jenis_pembayaran' => $posSummary,
            'bulan' => $request->input('bulan', date('F')),
            'tahun' => $request->input('tahun', date('Y')),
            'nominal' => $totalNominal,
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status' => 'Lunas',
            'bukti_bayar' => $buktiPath,
            'catatan' => ($validated['catatan'] ?? null) ?: ($request->input('keterangan_periode') ?: null),
            'penerima_nama' => auth()->user()->name ?? 'Bendahara Pesantren',
        ]);

        // Simpan masing-masing item pembayaran & sesuaikan tagihan secara presisi
        foreach ($paymentItemsData as $item) {
            $bill = null;

            // Kasus 1: Ada bill_id spesifik (tagihan bulan ini, kekurangan bulan kemarin / cicilan)
            if (!empty($item['bill_id'])) {
                $bill = StudentBill::find($item['bill_id']);
            }

            // Kasus 2: Bayar bulan depan / advance payment
            if (!$bill && !empty($item['target_bulan'])) {
                $tBulan = $item['target_bulan'];
                $tTahun = $item['target_tahun'] ?: date('Y');

                $bill = StudentBill::where('student_id', $student->id)
                    ->where('pos_biaya', $item['pos_biaya'])
                    ->where('bulan', $tBulan)
                    ->where('tahun', $tTahun)
                    ->first();

                if (!$bill) {
                    $bill = StudentBill::create([
                        'student_id' => $student->id,
                        'kategori' => 'bulanan',
                        'pos_biaya' => $item['pos_biaya'],
                        'judul_tagihan' => "Pembayaran {$item['pos_biaya']} ({$tBulan} {$tTahun})",
                        'bulan' => $tBulan,
                        'tahun' => $tTahun,
                        'nominal_asli' => $item['nominal'],
                        'nominal_potongan' => 0,
                        'nominal_tagihan' => $item['nominal'],
                        'nominal_bayar' => $item['nominal'],
                        'sisa_tagihan' => 0,
                        'status' => 'Lunas',
                        'created_by' => auth()->user()->name ?? 'Bendahara',
                    ]);
                }
            }

            // Catat detail transaksi item
            StudentPaymentItem::create([
                'payment_id' => $payment->id,
                'student_bill_id' => $bill?->id,
                'pos_biaya' => $item['pos_biaya'],
                'nominal' => $item['nominal'],
                'keterangan' => $item['keterangan'] ?? ($validated['keterangan_periode'] ?? null),
            ]);

            if ($bill) {
                if (!$bill->wasRecentlyCreated) {
                    $bill->nominal_bayar += $item['nominal'];
                    $bill->sisa_tagihan = max(0, $bill->nominal_tagihan - $bill->nominal_bayar);
                    $bill->status = $bill->sisa_tagihan <= 0 ? 'Lunas' : 'Cicilan';
                    $bill->save();
                }
            } else {
                // Pembayaran langsung pos baru tanpa tagihan awal (kecuali penarikan tabungan)
                if ($item['pos_biaya'] !== 'AMBIL TABUNGAN') {
                    StudentBill::create([
                        'student_id' => $student->id,
                        'kategori' => 'bulanan',
                        'pos_biaya' => $item['pos_biaya'],
                        'judul_tagihan' => "Pembayaran {$item['pos_biaya']} (" . ($validated['keterangan_periode'] ?? date('F Y')) . ")",
                        'bulan' => $request->input('bulan', date('F')),
                        'tahun' => $request->input('tahun', date('Y')),
                        'nominal_asli' => $item['nominal'],
                        'nominal_potongan' => 0,
                        'nominal_tagihan' => $item['nominal'],
                        'nominal_bayar' => $item['nominal'],
                        'sisa_tagihan' => 0,
                        'status' => 'Lunas',
                        'created_by' => auth()->user()->name ?? 'Bendahara',
                    ]);
                }
            }
        }

        $fmtNominal = 'Rp ' . number_format($totalNominal, 0, ',', '.');
        return redirect()->route('admin.pembayaran.index', ['tab' => 'kasir'])
            ->with('success', "Pembayaran sebesar {$fmtNominal} untuk santri {$student->nama_lengkap} ({$student->kelas}) berhasil dicatat. No. Kwitansi: {$noTransaksi}")
            ->with('last_payment_id', $payment->id)
            ->with('last_student_id', $student->id);
    }

    /**
     * Halaman Kelola Tagihan Santri (Bulanan, Sekali Bayar, & Tambahan Insidental).
     */
    public function tagihanIndex(Request $request)
    {
        $statusFilter = $request->input('status', 'all');
        $kategoriFilter = $request->input('kategori', 'all');
        $posFilter = $request->input('pos_biaya');
        $kelasFilter = $request->input('kelas');
        $search = $request->input('q');

        $query = StudentBill::with('student')->latest();

        if ($statusFilter === 'Ditangguhkan (Wisuda)' || $statusFilter === 'Ditangguhkan') {
            $query->where(function($q) {
                $q->where('penangguhan_wisuda', true)
                  ->orWhere('status', 'like', '%Ditangguhkan%');
            });
        } elseif ($statusFilter !== 'all' && !empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        if ($kategoriFilter !== 'all' && !empty($kategoriFilter)) {
            $query->where('kategori', $kategoriFilter);
        }

        if (!empty($posFilter)) {
            $query->where('pos_biaya', $posFilter);
        }

        if (!empty($kelasFilter)) {
            $query->whereHas('student', function($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            });
        }

        if (!empty($search)) {
            $query->where(function($w) use ($search) {
                $w->where('judul_tagihan', 'like', "%{$search}%")
                  ->orWhere('pos_biaya', 'like', "%{$search}%")
                  ->orWhereHas('student', function($sq) use ($search) {
                      $sq->where('nama_lengkap', 'like', "%{$search}%")
                         ->orWhere('nis', 'like', "%{$search}%");
                  });
            });
        }

        $bills = $query->paginate(20)->withQueryString();

        // Metrik Statistik Tagihan
        $allBills = StudentBill::all();
        $totalTagihanRp = $allBills->sum('nominal_tagihan');
        $totalTerbayarRp = $allBills->sum('nominal_bayar');
        $totalTunggakanRp = $allBills->where('status', '!=', 'Lunas')->where('penangguhan_wisuda', false)->sum('sisa_tagihan');
        $totalDitangguhkanRp = $allBills->where('penangguhan_wisuda', true)->sum('sisa_tagihan');
        $totalSantriNunggak = StudentBill::where('status', '!=', 'Lunas')->where('penangguhan_wisuda', false)->distinct('student_id')->count('student_id');

        $classrooms = Classroom::orderBy('jenjang')->orderBy('nama_kelas')->get();
        $activeStudents = Student::where('status', 'Aktif')->orderBy('nama_lengkap')->get(['id', 'nis', 'nama_lengkap', 'kelas', 'jenjang']);
        $posBiayaList = self::POS_BIAYA_LIST;

        return view('admin.pembayaran.tagihan', compact(
            'bills',
            'statusFilter',
            'kategoriFilter',
            'posFilter',
            'kelasFilter',
            'search',
            'totalTagihanRp',
            'totalTerbayarRp',
            'totalTunggakanRp',
            'totalDitangguhkanRp',
            'totalSantriNunggak',
            'classrooms',
            'activeStudents',
            'posBiayaList'
        ));
    }

    /**
     * Terbitkan Tagihan Rutin Bulanan (Otomatis Sesuai Setting & Potongan SKTM/Beasiswa).
     */
    public function terbitkanTagihanBulanan(Request $request)
    {
        $validated = $request->validate([
            'bulan' => 'required|string|max:30', // Januari s.d. Desember
            'tahun' => 'required|string|max:10',
            'target_jenjang' => 'required|string|in:all,MTs,MA',
            'jatuh_tempo' => 'nullable|date',
        ]);

        $bulan = $validated['bulan'];
        $tahun = $validated['tahun'];
        $jatuhTempo = $validated['jatuh_tempo'] ?: Carbon::parse("{$tahun}-" . date('m') . "-10");

        $query = Student::where('status', 'Aktif');
        if ($validated['target_jenjang'] !== 'all') {
            $query->where('jenjang', $validated['target_jenjang']);
        }
        $students = $query->get();

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada santri aktif yang ditemukan untuk jenjang tersebut.');
        }

        // Ambil tarif bulanan default
        // MTs Mukim: Makan 300.000, Syahriyah 85.000, Tabungan 25.000, SOT 55.000
        // MTs Laju: Makan 0, Syahriyah 55.000, Tabungan 25.000, SOT 55.000
        // MA Mukim: Makan 300.000, Syahriyah 105.000, Tabungan 25.000, SOT 75.000
        // MA Laju: Makan 0, Syahriyah 75.000, Tabungan 25.000, SOT 75.000
        $createdCount = 0;

        foreach ($students as $st) {
            $isMukim = !empty($st->kamar_asrama) && !str_contains(strtolower($st->kamar_asrama), 'laju');
            $isMA = strtoupper($st->jenjang) === 'MA';

            $tarifMakan = $isMukim ? 300000 : 0;
            $tarifSyahriyah = $isMA ? ($isMukim ? 105000 : 75000) : ($isMukim ? 85000 : 55000);
            $tarifTabungan = 25000;
            $tarifSot = $isMA ? 75000 : 55000;

            // Cek apakah ada potongan / beasiswa / SKTM aktif untuk santri ini
            $discounts = StudentDiscount::where('student_id', $st->id)
                ->where('status', 'Aktif')
                ->get();

            $posItems = [
                ['pos' => 'MAKAN', 'nominal' => $tarifMakan, 'judul' => "Uang Makan ({$bulan} {$tahun})"],
                ['pos' => 'SYAHRIYAH', 'nominal' => $tarifSyahriyah, 'judul' => "Syahriyah Pendidikan ({$bulan} {$tahun})"],
                ['pos' => 'SOT', 'nominal' => $tarifSot, 'judul' => "Iuran SOT ({$bulan} {$tahun})"],
                ['pos' => 'TAB', 'nominal' => $tarifTabungan, 'judul' => "Tabungan Wajib ({$bulan} {$tahun})"],
            ];

            foreach ($posItems as $item) {
                if ($item['nominal'] <= 0) {
                    continue;
                }

                // Cek apakah tagihan pos ini di bulan tersebut sudah pernah diterbitkan sebelumnya
                $exists = StudentBill::where('student_id', $st->id)
                    ->where('pos_biaya', $item['pos'])
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->exists();

                if ($exists) {
                    continue;
                }

                // Hitung potongan jika ada
                $potongan = 0;
                $alasanPotongan = null;
                foreach ($discounts as $disc) {
                    if ($disc->pos_biaya === 'Semua Bulanan' || $disc->pos_biaya === $item['pos']) {
                        if ($disc->tipe_nilai === 'persen') {
                            $p = ($disc->nilai / 100) * $item['nominal'];
                        } else {
                            $p = min($item['nominal'], $disc->nilai);
                        }
                        if ($p > $potongan) {
                            $potongan = $p;
                            $alasanPotongan = "{$disc->jenis_potongan} " . ($disc->no_surat_miskin ? "({$disc->no_surat_miskin})" : "");
                        }
                    }
                }

                $nominalTagihan = max(0, $item['nominal'] - $potongan);
                $status = $nominalTagihan == 0 ? 'Lunas' : 'Belum Bayar';

                StudentBill::create([
                    'student_id' => $st->id,
                    'kategori' => 'bulanan',
                    'pos_biaya' => $item['pos'],
                    'judul_tagihan' => $item['judul'],
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nominal_asli' => $item['nominal'],
                    'nominal_potongan' => $potongan,
                    'alasan_potongan' => $alasanPotongan,
                    'nominal_tagihan' => $nominalTagihan,
                    'nominal_bayar' => $nominalTagihan == 0 ? $item['nominal'] : 0,
                    'sisa_tagihan' => $nominalTagihan,
                    'status' => $status,
                    'jatuh_tempo' => $jatuhTempo,
                    'created_by' => auth()->user()->name ?? 'Bendahara',
                ]);

                $createdCount++;
            }
        }

        return redirect()->route('admin.pembayaran.tagihan.index')
            ->with('success', "Tagihan bulanan periode {$bulan} {$tahun} berhasil diterbitkan ({$createdCount} data).");
    }

    /**
     * Terbitkan Tagihan Tambahan / Insidental (Ziarah, Wisuda, Ujian, Study Tour, dll.).
     */
    public function terbitkanTagihanTambahan(Request $request)
    {
        $validated = $request->validate([
            'pos_biaya' => 'required|string|max:50',
            'pos_biaya_kustom' => 'nullable|string|max:50',
            'judul_tagihan' => 'required|string|max:150',
            'nominal' => 'required|numeric|min:1000',
            'kategori' => 'required|string|in:tambahan,sekali_bayar,tahunan',
            'sasaran_tipe' => 'required|string|in:semua,tingkat,kelas,santri',
            'sasaran_nilai' => 'nullable|string', // Contoh: X, IX, VII-A, atau ID santri
            'jatuh_tempo' => 'nullable|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $rawPos = trim($validated['pos_biaya']);
        if ($rawPos === '__CUSTOM__' || $rawPos === 'KUSTOM' || $rawPos === 'LAINNYA') {
            $customInput = $request->input('pos_biaya_kustom');
            $pos = strtoupper(preg_replace('/[^a-zA-Z0-9_\-\s]/', '', trim($customInput ?: 'LAIN-LAIN')));
            if (empty($pos)) {
                $pos = 'LAIN-LAIN';
            }
        } else {
            $pos = strtoupper($rawPos);
        }

        $nominal = floatval($validated['nominal']);
        $judul = trim($validated['judul_tagihan']);
        $jatuhTempo = ($validated['jatuh_tempo'] ?? null) ?: now()->addDays(30)->toDateString();

        // Tentukan daftar santri target
        $studentsQuery = Student::where('status', 'Aktif');

        if ($validated['sasaran_tipe'] === 'tingkat') {
            $tingkat = $validated['sasaran_nilai'];
            // Cari kelas-kelas dengan tingkat tersebut
            $matchingClasses = Classroom::where('tingkat', $tingkat)->pluck('nama_kelas')->toArray();
            $studentsQuery->whereIn('kelas', $matchingClasses);
        } elseif ($validated['sasaran_tipe'] === 'kelas') {
            $studentsQuery->where('kelas', $validated['sasaran_nilai']);
        } elseif ($validated['sasaran_tipe'] === 'santri') {
            $studentsQuery->where('id', $validated['sasaran_nilai']);
        }

        $targetStudents = $studentsQuery->get();

        if ($targetStudents->isEmpty()) {
            return redirect()->back()->with('error', 'Gagal menerbitkan tagihan! Tidak ditemukan santri aktif pada sasaran tersebut.');
        }

        $createdCount = 0;
        $skippedCount = 0;
        foreach ($targetStudents as $st) {
            // Cek apakah santri sudah memiliki tagihan dengan pos_biaya dan judul yang sama di tahun ini
            // Agar tidak terduplikasi jika admin mengklik atau menerbitkan ulang
            $exists = StudentBill::where('student_id', $st->id)
                ->where('pos_biaya', $pos)
                ->where('judul_tagihan', $judul)
                ->where('tahun', date('Y'))
                ->exists();

            if ($exists) {
                $skippedCount++;
                continue;
            }

            // Cek apakah santri punya beasiswa/SKTM untuk pos ini
            $discount = StudentDiscount::where('student_id', $st->id)
                ->where('status', 'Aktif')
                ->where('pos_biaya', $pos)
                ->first();

            $potongan = 0;
            $alasan = null;
            if ($discount) {
                $potongan = $discount->tipe_nilai === 'persen' 
                    ? ($discount->nilai / 100) * $nominal 
                    : min($nominal, $discount->nilai);
                $alasan = "{$discount->jenis_potongan} " . ($discount->no_surat_miskin ? "({$discount->no_surat_miskin})" : "");
            }

            $tagihanBersih = max(0, $nominal - $potongan);
            $status = $tagihanBersih == 0 ? 'Lunas' : 'Belum Bayar';

            StudentBill::create([
                'student_id' => $st->id,
                'kategori' => $validated['kategori'],
                'pos_biaya' => $pos,
                'judul_tagihan' => $judul,
                'bulan' => null,
                'tahun' => date('Y'),
                'nominal_asli' => $nominal,
                'nominal_potongan' => $potongan,
                'alasan_potongan' => $alasan,
                'nominal_tagihan' => $tagihanBersih,
                'nominal_bayar' => $tagihanBersih == 0 ? $nominal : 0,
                'sisa_tagihan' => $tagihanBersih,
                'status' => $status,
                'jatuh_tempo' => $jatuhTempo,
                'created_by' => auth()->user()->name ?? 'Bendahara',
            ]);

            $createdCount++;
        }

        if ($createdCount === 0 && $skippedCount > 0) {
            return redirect()->route('admin.pembayaran.tagihan.index')
                ->with('warning', "Tagihan '{$judul}' ({$pos}) sudah pernah diterbitkan sebelumnya untuk {$skippedCount} santri sasaran tersebut (tidak dibuat duplikat).");
        }

        $msg = "Tagihan '{$judul}' berhasil diterbitkan untuk {$createdCount} santri.";
        if ($skippedCount > 0) {
            $msg .= " ({$skippedCount} santri dilewati karena sudah ada).";
        }

        return redirect()->route('admin.pembayaran.tagihan.index')->with('success', $msg);
    }

    /**
     * Hapus Tagihan Santri (Hanya jika belum ada pembayaran).
     */
    public function destroyBill($id)
    {
        $bill = StudentBill::findOrFail($id);

        if ($bill->nominal_bayar > 0) {
            return redirect()->back()->with('error', "Gagal menghapus! Tagihan {$bill->judul_tagihan} sudah memiliki riwayat pembayaran sebesar {$bill->formatted_bayar}.");
        }

        $judul = $bill->judul_tagihan;
        $bill->delete();

        return redirect()->back()->with('success', "Tagihan '{$judul}' berhasil dihapus.");
    }

    /**
     * Halaman Kelola Potongan Biaya (SKTM / Beasiswa Pintar).
     */
    public function potonganIndex(Request $request)
    {
        $discounts = StudentDiscount::with('student')->latest()->paginate(20);
        $activeStudents = Student::where('status', 'Aktif')->orderBy('nama_lengkap')->get(['id', 'nis', 'nama_lengkap', 'kelas']);
        $posBiayaList = self::POS_BIAYA_LIST;

        $totalPenerima = StudentDiscount::where('status', 'Aktif')->distinct('student_id')->count('student_id');
        $totalSktm = StudentDiscount::where('status', 'Aktif')->where('jenis_potongan', 'like', '%Miskin%')->count();
        $totalPrestasi = StudentDiscount::where('status', 'Aktif')->where('jenis_potongan', 'like', '%Prestasi%')->count();

        return view('admin.pembayaran.potongan', compact(
            'discounts',
            'activeStudents',
            'posBiayaList',
            'totalPenerima',
            'totalSktm',
            'totalPrestasi'
        ));
    }

    /**
     * Simpan Keringanan / Beasiswa Santri.
     */
    public function potonganStore(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'jenis_potongan' => 'required|string|max:50',
            'no_surat_miskin' => 'nullable|string|max:100',
            'tipe_nilai' => 'required|string|in:nominal,persen',
            'nilai' => 'required|numeric|min:1',
            'pos_biaya' => 'required|string|max:50',
            'berlaku_mulai' => 'nullable|date',
            'berlaku_sampai' => 'nullable|date',
            'catatan' => 'nullable|string|max:255',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $filename = time() . '_sktm_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/sktm'), $filename);
            $filePath = '/uploads/sktm/' . $filename;
        }

        $student = Student::findOrFail($validated['student_id']);

        // Cek apakah santri sudah memiliki potongan aktif untuk pos biaya ini agar tidak terduplikasi
        $existing = StudentDiscount::where('student_id', $student->id)
            ->where('pos_biaya', $validated['pos_biaya'])
            ->where('status', 'Aktif')
            ->first();

        if ($existing) {
            $existing->update([
                'jenis_potongan' => $validated['jenis_potongan'],
                'no_surat_miskin' => $validated['no_surat_miskin'] ?? $existing->no_surat_miskin,
                'file_surat_miskin' => $filePath ?: $existing->file_surat_miskin,
                'tipe_nilai' => $validated['tipe_nilai'],
                'nilai' => $validated['nilai'],
                'berlaku_mulai' => $validated['berlaku_mulai'] ?? null,
                'berlaku_sampai' => $validated['berlaku_sampai'] ?? null,
                'catatan' => $validated['catatan'] ?? $existing->catatan,
            ]);

            return redirect()->route('admin.pembayaran.potongan.index')
                ->with('success', "Potongan pos {$validated['pos_biaya']} untuk {$student->nama_lengkap} berhasil diperbarui.");
        }

        StudentDiscount::create([
            'student_id' => $student->id,
            'jenis_potongan' => $validated['jenis_potongan'],
            'no_surat_miskin' => $validated['no_surat_miskin'] ?? null,
            'file_surat_miskin' => $filePath,
            'tipe_nilai' => $validated['tipe_nilai'],
            'nilai' => $validated['nilai'],
            'pos_biaya' => $validated['pos_biaya'],
            'berlaku_mulai' => $validated['berlaku_mulai'] ?? null,
            'berlaku_sampai' => $validated['berlaku_sampai'] ?? null,
            'status' => 'Aktif',
            'catatan' => $validated['catatan'] ?? null,
            'created_by' => auth()->user()->name ?? 'Bendahara',
        ]);

        return redirect()->route('admin.pembayaran.potongan.index')
            ->with('success', "Keringanan {$validated['jenis_potongan']} untuk {$student->nama_lengkap} berhasil disimpan.");
    }

    /**
     * Hapus Keringanan Santri.
     */
    public function potonganDestroy($id)
    {
        $discount = StudentDiscount::findOrFail($id);
        $discount->delete();

        return redirect()->back()->with('success', 'Data potongan biaya berhasil dihapus.');
    }

    /**
     * Buku Kas & Jurnal Matriks Spreadsheet Pembukuan Bendahara.
     */
    public function jurnalIndex(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', date('Y-m-01'));
        $tglSelesai = $request->input('tgl_selesai', date('Y-m-t'));
        $kelasFilter = $request->input('kelas');

        $query = StudentPayment::with(['student', 'psbRegistration', 'items'])
            ->whereBetween('tanggal_bayar', [$tglMulai, $tglSelesai])
            ->orderBy('tanggal_bayar')
            ->orderBy('id');

        if (!empty($kelasFilter)) {
            $query->whereHas('student', function($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            });
        }

        $payments = $query->get();

        // Siapkan Kolom Pos Biaya yang Aktif / Memiliki Transaksi di Periode Ini
        $posBiayaList = self::POS_BIAYA_LIST;

        // Hitung total per kolom pos biaya
        $colTotals = [];
        $grandTotal = 0;
        foreach ($posBiayaList as $key => $label) {
            $colTotals[$key] = 0;
        }

        foreach ($payments as $p) {
            $grandTotal += $p->nominal;
            foreach ($p->items as $item) {
                $pos = $item->pos_biaya;
                if (isset($colTotals[$pos])) {
                    $colTotals[$pos] += $item->nominal;
                } else {
                    $colTotals[$pos] = ($colTotals[$pos] ?? 0) + $item->nominal;
                }
            }
        }

        $classrooms = Classroom::orderBy('jenjang')->orderBy('nama_kelas')->get();

        return view('admin.pembayaran.jurnal', compact(
            'payments',
            'posBiayaList',
            'colTotals',
            'grandTotal',
            'tglMulai',
            'tglSelesai',
            'kelasFilter',
            'classrooms'
        ));
    }

    /**
     * Ekspor Jurnal Matriks Spreadsheet ke Format Excel (.xlsx).
     */
    public function exportJurnalExcel(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', date('Y-m-01'));
        $tglSelesai = $request->input('tgl_selesai', date('Y-m-t'));
        $kelasFilter = $request->input('kelas');

        $query = StudentPayment::with(['student', 'psbRegistration', 'items'])
            ->whereBetween('tanggal_bayar', [$tglMulai, $tglSelesai])
            ->orderBy('tanggal_bayar')
            ->orderBy('id');

        if (!empty($kelasFilter)) {
            $query->whereHas('student', function($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            });
        }

        $payments = $query->get();
        $posBiayaList = self::POS_BIAYA_LIST;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Jurnal Pembayaran Kas');

        // Header Title
        $sheet->setCellValue('A1', 'PONDOK PESANTREN HIDAYATULLAH TUKSONGO PRINGSURAT');
        $sheet->setCellValue('A2', "BUKU KAS / JURNAL PEMBAYARAN ADMINISTRASI SANTRI (Periode: {$tglMulai} s.d. {$tglSelesai})");
        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);

        // Header Tabel Kolom
        $headers = ['NO', 'TANGGAL', 'NAMA SANTRI', 'KELAS', 'KETERANGAN'];
        $posKeys = array_keys($posBiayaList);
        foreach ($payments as $p) {
            foreach ($p->items as $it) {
                if ($it->pos_biaya && !in_array($it->pos_biaya, $posKeys)) {
                    $posKeys[] = $it->pos_biaya;
                }
            }
        }
        foreach ($posKeys as $k) {
            $headers[] = $k;
        }
        $headers[] = 'TOTAL PEMBAYARAN';

        // Tulis Header ke Baris 4
        $colIndex = 1;
        foreach ($headers as $h) {
            $cellCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) . '4';
            $sheet->setCellValue($cellCoord, $h);
            $colIndex++;
        }

        $lastColString = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));

        // Style Header
        $sheet->getStyle("A4:{$lastColString}4")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E7D42']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Isi Data Baris per Transaksi
        $row = 5;
        $no = 1;
        $colTotals = array_fill(0, count($posKeys), 0);
        $totalAll = 0;

        foreach ($payments as $p) {
            $namaSantri = $p->student->nama_lengkap ?? ($p->psbRegistration->nama_lengkap ?? '—');
            $kelasSantri = $p->student->kelas ?? ($p->psbRegistration ? 'Calon PSB (' . $p->psbRegistration->jenjang . ')' : '—');

            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", optional($p->tanggal_bayar)->format('d/m/Y'));
            $sheet->setCellValue("C{$row}", $namaSantri);
            $sheet->setCellValue("D{$row}", $kelasSantri);
            $sheet->setCellValue("E{$row}", $p->catatan ?: ($p->bulan ? "{$p->bulan} {$p->tahun}" : '—'));

            // Map item nominals
            $itemMap = [];
            foreach ($p->items as $it) {
                $itemMap[$it->pos_biaya] = ($itemMap[$it->pos_biaya] ?? 0) + $it->nominal;
            }

            $cIdx = 6;
            foreach ($posKeys as $idx => $posKey) {
                $val = $itemMap[$posKey] ?? 0;
                $colCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx) . $row;
                if ($val > 0) {
                    $sheet->setCellValue($colCoord, $val);
                    $colTotals[$idx] += $val;
                } else {
                    $sheet->setCellValue($colCoord, '');
                }
                $cIdx++;
            }

            // Kolom Total
            $totalCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx) . $row;
            $sheet->setCellValue($totalCoord, $p->nominal);
            $totalAll += $p->nominal;

            $row++;
        }

        // Baris Total / Rekapitulasi Akhir
        $sheet->setCellValue("A{$row}", 'TOTAL KESELURUHAN');
        $sheet->mergeCells("A{$row}:E{$row}");
        $cIdx = 6;
        foreach ($posKeys as $idx => $posKey) {
            $colCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx) . $row;
            $sheet->setCellValue($colCoord, $colTotals[$idx] > 0 ? $colTotals[$idx] : '');
            $cIdx++;
        }
        $totalCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx) . $row;
        $sheet->setCellValue($totalCoord, $totalAll);

        // Style Total Row
        $sheet->getStyle("A{$row}:{$lastColString}{$row}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '0D3B1E']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D4F5DE']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Grid Borders untuk seluruh tabel data
        $sheet->getStyle("A4:{$lastColString}{$row}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'B0BEC5'],
                ],
            ],
        ]);

        // Format angka Rupiah untuk semua kolom nominal (Kolom F s/d Kolom Terakhir)
        $sheet->getStyle("F5:{$lastColString}{$row}")->getNumberFormat()->setFormatCode('#,##0');

        // Perataan teks kolom data
        $sheet->getStyle("A5:A" . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B5:B" . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C5:C" . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("D5:D" . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E5:E" . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F5:{$lastColString}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Atur tinggi baris
        $sheet->getRowDimension(4)->setRowHeight(30);
        for ($r = 5; $r <= $row; $r++) {
            $sheet->getRowDimension($r)->setRowHeight(22);
        }

        // Freeze Panes pada F5 (agar kolom Nama & Kelas tetap terlihat saat scroll ke kanan 40 pos)
        $sheet->freezePane('F5');

        // Auto width kolom menyesuaikan teks dengan sempurna
        for ($i = 1; $i <= count($headers); $i++) {
            $cStr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($cStr)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Jurnal_Kas_Pembayaran_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Ekspor Buku Kas Tahunan Multi-Sheet (1 File Excel dengan 13 Sheet: Rekap Tahunan + 12 Sheet Bulanan).
     */
    public function exportJurnalTahunanExcel(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $tipeTahun = $request->input('tipe_tahun', 'kalender'); // 'kalender' (Jan-Des) atau 'ajaran' (Jul-Jun)
        $kelasFilter = $request->input('kelas');

        $posBiayaList = self::POS_BIAYA_LIST;

        // Tentukan 12 bulan berdasarkan tipe tahun
        $monthsConfig = [];
        if ($tipeTahun === 'ajaran') {
            // Tahun Ajaran: Juli {tahun} s.d. Juni {tahun+1}
            $nextYear = intval($tahun) + 1;
            $namaBulanIndo = [
                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni'
            ];
            $seq = 1;
            foreach ($namaBulanIndo as $mNum => $mName) {
                $y = ($mNum >= 7) ? intval($tahun) : $nextYear;
                $start = sprintf('%04d-%02d-01', $y, $mNum);
                $end = date('Y-m-t', strtotime($start));
                $sheetCode = sprintf('%02d - %s', $seq++, $mName);
                $monthsConfig[] = [
                    'm_num' => $mNum,
                    'm_name' => $mName,
                    'year' => $y,
                    'start' => $start,
                    'end' => $end,
                    'sheet_title' => $sheetCode,
                    'label' => "{$mName} {$y}",
                ];
            }
            $startDateTotal = sprintf('%04d-07-01', $tahun);
            $endDateTotal = sprintf('%04d-06-30', $nextYear);
            $periodeTitle = "Tahun Ajaran {$tahun}/{$nextYear} (Juli {$tahun} s.d. Juni {$nextYear})";
        } else {
            // Tahun Kalender: Januari {tahun} s.d. Desember {tahun}
            $namaBulanIndo = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            foreach ($namaBulanIndo as $mNum => $mName) {
                $start = sprintf('%04d-%02d-01', $tahun, $mNum);
                $end = date('Y-m-t', strtotime($start));
                $sheetCode = sprintf('%02d - %s', $mNum, $mName);
                $monthsConfig[] = [
                    'm_num' => $mNum,
                    'm_name' => $mName,
                    'year' => $tahun,
                    'start' => $start,
                    'end' => $end,
                    'sheet_title' => $sheetCode,
                    'label' => "{$mName} {$tahun}",
                ];
            }
            $startDateTotal = sprintf('%04d-01-01', $tahun);
            $endDateTotal = sprintf('%04d-12-31', $tahun);
            $periodeTitle = "Tahun {$tahun} (Januari s.d. Desember {$tahun})";
        }

        // Ambil semua transaksi tahun tersebut
        $queryAll = StudentPayment::with(['student', 'psbRegistration', 'items'])
            ->whereBetween('tanggal_bayar', [$startDateTotal, $endDateTotal])
            ->orderBy('tanggal_bayar')
            ->orderBy('id');

        if (!empty($kelasFilter)) {
            $queryAll->whereHas('student', function($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            });
        }
        $allPayments = $queryAll->get();

        // Kumpulkan semua pos biaya standar + kustom yang ada di tahun ini
        $posKeys = array_keys($posBiayaList);
        foreach ($allPayments as $p) {
            foreach ($p->items as $it) {
                if ($it->pos_biaya && !in_array($it->pos_biaya, $posKeys)) {
                    $posKeys[] = $it->pos_biaya;
                }
            }
        }

        $spreadsheet = new Spreadsheet();

        // ==========================================
        // SHEET 1: REKAPITULASI TAHUNAN
        // ==========================================
        $sheetRekap = $spreadsheet->getActiveSheet();
        $sheetRekap->setTitle('REKAP TAHUNAN');

        // Header Title
        $sheetRekap->setCellValue('A1', 'PONDOK PESANTREN HIDAYATULLAH TUKSONGO PRINGSURAT');
        $sheetRekap->setCellValue('A2', "BUKU KAS / REKAPITULASI PEMBAYARAN ADMINISTRASI SANTRI ({$periodeTitle})");
        $sheetRekap->mergeCells('A1:L1');
        $sheetRekap->mergeCells('A2:L2');
        $sheetRekap->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);

        // Header Kolom Rekap
        $rekapHeaders = ['NO', 'BULAN / PERIODE', 'JML TRANSAKSI'];
        foreach ($posKeys as $k) {
            $rekapHeaders[] = $k;
        }
        $rekapHeaders[] = 'TOTAL PENERIMAAN';

        $cIdx = 1;
        foreach ($rekapHeaders as $h) {
            $coord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx) . '4';
            $sheetRekap->setCellValue($coord, $h);
            $cIdx++;
        }
        $lastRekapCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($rekapHeaders));

        $sheetRekap->getStyle("A4:{$lastRekapCol}4")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E7D42']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $rRow = 5;
        $rNo = 1;
        $rekapGrandTotal = 0;
        $rekapPosTotals = array_fill(0, count($posKeys), 0);
        $rekapCountTotal = 0;

        foreach ($monthsConfig as $mCfg) {
            // Filter payments for this month
            $mPayments = $allPayments->filter(function($p) use ($mCfg) {
                $tgl = $p->tanggal_bayar ? $p->tanggal_bayar->format('Y-m-d') : null;
                return $tgl >= $mCfg['start'] && $tgl <= $mCfg['end'];
            });

            $sheetRekap->setCellValue("A{$rRow}", $rNo++);
            $sheetRekap->setCellValue("B{$rRow}", $mCfg['label']);
            $sheetRekap->setCellValue("C{$rRow}", $mPayments->count());
            $rekapCountTotal += $mPayments->count();

            // Total per pos di bulan ini
            $mPosSums = array_fill_keys($posKeys, 0);
            $mTotal = 0;
            foreach ($mPayments as $mp) {
                $mTotal += $mp->nominal;
                foreach ($mp->items as $it) {
                    if (isset($mPosSums[$it->pos_biaya])) {
                        $mPosSums[$it->pos_biaya] += $it->nominal;
                    }
                }
            }

            $colIdx = 4;
            foreach ($posKeys as $idx => $pk) {
                $val = $mPosSums[$pk] ?? 0;
                $coord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx) . $rRow;
                $sheetRekap->setCellValue($coord, $val > 0 ? $val : '');
                $rekapPosTotals[$idx] += $val;
                $colIdx++;
            }

            $totCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx) . $rRow;
            $sheetRekap->setCellValue($totCoord, $mTotal > 0 ? $mTotal : 0);
            $rekapGrandTotal += $mTotal;

            $rRow++;
        }

        // Baris Total Rekap
        $sheetRekap->setCellValue("A{$rRow}", 'TOTAL PENERIMAAN 1 TAHUN');
        $sheetRekap->mergeCells("A{$rRow}:B{$rRow}");
        $sheetRekap->setCellValue("C{$rRow}", $rekapCountTotal);

        $colIdx = 4;
        foreach ($posKeys as $idx => $pk) {
            $coord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx) . $rRow;
            $sheetRekap->setCellValue($coord, $rekapPosTotals[$idx] > 0 ? $rekapPosTotals[$idx] : '');
            $colIdx++;
        }
        $totCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx) . $rRow;
        $sheetRekap->setCellValue($totCoord, $rekapGrandTotal);

        $sheetRekap->getStyle("A{$rRow}:{$lastRekapCol}{$rRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '0D3B1E']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D4F5DE']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheetRekap->getStyle("A{$rRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheetRekap->getStyle("C{$rRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheetRekap->getStyle("A4:{$lastRekapCol}{$rRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'B0BEC5']]],
        ]);
        $sheetRekap->getStyle("D5:{$lastRekapCol}{$rRow}")->getNumberFormat()->setFormatCode('#,##0');
        $sheetRekap->getStyle("A5:A" . ($rRow - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheetRekap->getStyle("B5:B" . ($rRow - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheetRekap->getStyle("C5:C" . ($rRow - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheetRekap->getStyle("D5:{$lastRekapCol}{$rRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheetRekap->getRowDimension(4)->setRowHeight(28);
        $sheetRekap->freezePane('D5');
        for ($i = 1; $i <= count($rekapHeaders); $i++) {
            $sheetRekap->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }

        // ==========================================
        // SHEET 2 S.D. 13: MASING-MASING BULAN
        // ==========================================
        $monthHeaders = ['NO', 'TANGGAL', 'NAMA SANTRI', 'KELAS', 'KETERANGAN'];
        foreach ($posKeys as $k) {
            $monthHeaders[] = $k;
        }
        $monthHeaders[] = 'TOTAL PEMBAYARAN';
        $lastMonthCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($monthHeaders));

        foreach ($monthsConfig as $mCfg) {
            $mSheet = $spreadsheet->createSheet();
            $mSheet->setTitle(substr($mCfg['sheet_title'], 0, 31)); // Max sheet title 31 chars

            $mSheet->setCellValue('A1', 'PONDOK PESANTREN HIDAYATULLAH TUKSONGO PRINGSURAT');
            $mSheet->setCellValue('A2', "BUKU KAS / JURNAL PEMBAYARAN BULAN " . strtoupper($mCfg['label']));
            $mSheet->mergeCells('A1:L1');
            $mSheet->mergeCells('A2:L2');
            $mSheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);

            $cIdx = 1;
            foreach ($monthHeaders as $h) {
                $coord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx) . '4';
                $mSheet->setCellValue($coord, $h);
                $cIdx++;
            }

            $mSheet->getStyle("A4:{$lastMonthCol}4")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E7D42']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);

            $mPayments = $allPayments->filter(function($p) use ($mCfg) {
                $tgl = $p->tanggal_bayar ? $p->tanggal_bayar->format('Y-m-d') : null;
                return $tgl >= $mCfg['start'] && $tgl <= $mCfg['end'];
            });

            $mRow = 5;
            $mNo = 1;
            $colMonthTotals = array_fill(0, count($posKeys), 0);
            $totalMonthAll = 0;

            if ($mPayments->isEmpty()) {
                $mSheet->setCellValue("A5", 'Belum ada transaksi pembayaran pada bulan ini.');
                $mSheet->mergeCells("A5:{$lastMonthCol}5");
                $mSheet->getStyle("A5")->getFont()->setItalic(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('888888'));
                $mSheet->getStyle("A5")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $mRow = 6;
            } else {
                foreach ($mPayments as $p) {
                    $namaSantri = $p->student->nama_lengkap ?? ($p->psbRegistration->nama_lengkap ?? '—');
                    $kelasSantri = $p->student->kelas ?? ($p->psbRegistration ? 'Calon PSB (' . $p->psbRegistration->jenjang . ')' : '—');

                    $mSheet->setCellValue("A{$mRow}", $mNo++);
                    $mSheet->setCellValue("B{$mRow}", optional($p->tanggal_bayar)->format('d/m/Y'));
                    $mSheet->setCellValue("C{$mRow}", $namaSantri);
                    $mSheet->setCellValue("D{$mRow}", $kelasSantri);
                    $mSheet->setCellValue("E{$mRow}", $p->catatan ?: ($p->bulan ? "{$p->bulan} {$p->tahun}" : '—'));

                    $itemMap = [];
                    foreach ($p->items as $it) {
                        $itemMap[$it->pos_biaya] = ($itemMap[$it->pos_biaya] ?? 0) + $it->nominal;
                    }

                    $cCol = 6;
                    foreach ($posKeys as $idx => $pk) {
                        $val = $itemMap[$pk] ?? 0;
                        $coord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cCol) . $mRow;
                        if ($val > 0) {
                            $mSheet->setCellValue($coord, $val);
                            $colMonthTotals[$idx] += $val;
                        } else {
                            $mSheet->setCellValue($coord, '');
                        }
                        $cCol++;
                    }

                    $totCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cCol) . $mRow;
                    $mSheet->setCellValue($totCoord, $p->nominal);
                    $totalMonthAll += $p->nominal;
                    $mRow++;
                }

                // Baris Total Bulan
                $mSheet->setCellValue("A{$mRow}", 'TOTAL ' . strtoupper($mCfg['label']));
                $mSheet->mergeCells("A{$mRow}:E{$mRow}");

                $cCol = 6;
                foreach ($posKeys as $idx => $pk) {
                    $coord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cCol) . $mRow;
                    $mSheet->setCellValue($coord, $colMonthTotals[$idx] > 0 ? $colMonthTotals[$idx] : '');
                    $cCol++;
                }
                $totCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cCol) . $mRow;
                $mSheet->setCellValue($totCoord, $totalMonthAll);

                $mSheet->getStyle("A{$mRow}:{$lastMonthCol}{$mRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '0D3B1E']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D4F5DE']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $mSheet->getStyle("A{$mRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }

            $mSheet->getStyle("A4:{$lastMonthCol}{$mRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'B0BEC5']]],
            ]);
            $mSheet->getStyle("F5:{$lastMonthCol}{$mRow}")->getNumberFormat()->setFormatCode('#,##0');
            $mSheet->getStyle("A5:A" . ($mRow - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $mSheet->getStyle("B5:B" . ($mRow - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $mSheet->getStyle("C5:C" . ($mRow - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $mSheet->getStyle("D5:D" . ($mRow - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $mSheet->getStyle("E5:E" . ($mRow - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $mSheet->getStyle("F5:{$lastMonthCol}{$mRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $mSheet->getRowDimension(4)->setRowHeight(28);
            $mSheet->freezePane('F5');
            for ($i = 1; $i <= count($monthHeaders); $i++) {
                $mSheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
            }
        }

        // Set active sheet back to Sheet 1 (Rekap)
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $cleanTahunLabel = str_replace(['/', ' '], '_', $tahun);
        $fileName = "Buku_Kas_Tahunan_Hidayatullah_{$cleanTahunLabel}_" . date('Ymd_His') . ".xlsx";

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Verifikasi Pembayaran Calon Santri PSB (Mendukung Cash/Tunai & Upload Bukti oleh Admin).
     */
    public function psbVerify(Request $request, $id)
    {
        $reg = PsbRegistration::findOrFail($id);

        $status = $request->input('status', 'Lunas');
        $data = ['status_pembayaran' => $status];

        if ($request->filled('nominal_pembayaran')) {
            $cleanNominal = preg_replace('/[^0-9]/', '', $request->nominal_pembayaran);
            $data['nominal_pembayaran'] = (float) $cleanNominal;
        }

        if ($request->filled('metode_pembayaran')) {
            $data['metode_pembayaran'] = $request->input('metode_pembayaran');
        }

        if ($request->filled('tanggal_bayar')) {
            $data['tanggal_bayar'] = $request->input('tanggal_bayar');
        } elseif ($status === 'Lunas' && empty($reg->tanggal_bayar)) {
            $data['tanggal_bayar'] = date('Y-m-d');
        }

        if ($request->filled('catatan_pembayaran')) {
            $data['catatan_pembayaran'] = $request->input('catatan_pembayaran');
        }

        // Upload bukti transfer / kwitansi fisik jika admin mengunggah file
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_psb_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/bukti_transfer'), $filename);
            $data['bukti_transfer'] = '/uploads/bukti_transfer/' . $filename;
        }

        $reg->update($data);

        $metode = $data['metode_pembayaran'] ?? $reg->metode_pembayaran ?? 'Tunai';
        $msg = $status === 'Lunas' 
            ? "Pembayaran PSB ({$metode}) untuk {$reg->nama_lengkap} ({$reg->no_registrasi}) berhasil diverifikasi."
            : "Status pembayaran {$reg->nama_lengkap} diubah menjadi Belum Lunas.";

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Cetak Bukti Setoran Resmi Pembayaran PSB.
     */
    public function kwitansiPsb($id)
    {
        $reg = PsbRegistration::findOrFail($id);

        $payment = (object)[
            'id' => $reg->id,
            'no_transaksi' => 'PSB-' . ($reg->no_registrasi ?: $reg->id),
            'tanggal_bayar' => $reg->tanggal_bayar ? Carbon::parse($reg->tanggal_bayar) : ($reg->created_at ?: now()),
            'metode_pembayaran' => $reg->metode_pembayaran ?: 'Tunai',
            'nominal' => $reg->nominal_pembayaran ?: 3225000,
            'catatan' => $reg->catatan_pembayaran ?: 'Biaya Pendaftaran & Daftar Ulang PSB TA 2026/2027',
            'penerima_nama' => auth()->user()->name ?? 'Panitia PSB & Bendahara',
            'bulan' => null,
            'tahun' => date('Y'),
            'student' => (object)[
                'nama_lengkap' => $reg->nama_lengkap,
                'nis' => $reg->no_registrasi,
                'kelas' => 'Calon Santri Baru',
                'jenjang' => $reg->jenjang,
                'nama_wali' => $reg->nama_wali ?: ($reg->ayah_nama ?: $reg->ibu_nama),
                'alamat' => $reg->alamat_lengkap ?: ($reg->alamat ?: 'Pringsurat, Kab. Temanggung'),
                'no_whatsapp' => $reg->no_whatsapp,
                'no_hp' => $reg->no_whatsapp,
            ],
            'items' => collect([
                (object)[
                    'pos_biaya' => 'PENDAFTARAN & DAFTAR ULANG PSB',
                    'nominal' => $reg->nominal_pembayaran ?: 3225000,
                ]
            ]),
        ];

        return view('admin.pembayaran.kwitansi', [
            'payment' => $payment,
            'sisaTunggakanSantri' => 0
        ]);
    }

    /**
     * Bendahara Menyetujui / Mengonfirmasi Pembayaran Transfer Mandiri yang Diunggah Santri.
     */
    public function konfirmasiStudentPayment(Request $request, $id)
    {
        $payment = StudentPayment::with(['student', 'items.bill'])->findOrFail($id);

        if ($payment->status === 'Lunas') {
            return redirect()->back()->with('error', 'Pembayaran ini sudah berstatus Lunas.');
        }

        $payment->status = 'Lunas';
        $payment->penerima_nama = auth()->user()->name ?? 'Bendahara Pesantren';
        if (empty($payment->tanggal_bayar)) {
            $payment->tanggal_bayar = now()->toDateString();
        }
        $payment->save();

        // Update tagihan-tagihan terkait
        foreach ($payment->items as $item) {
            if ($item->student_bill_id) {
                $bill = StudentBill::find($item->student_bill_id);
                if ($bill) {
                    $bill->nominal_bayar = min($bill->nominal_tagihan, $bill->nominal_bayar + $item->nominal);
                    $bill->sisa_tagihan = max(0, $bill->nominal_tagihan - $bill->nominal_bayar);
                    $bill->status = $bill->sisa_tagihan <= 0 ? 'Lunas' : ($bill->nominal_bayar > 0 ? 'Cicilan' : 'Belum Bayar');
                    $bill->save();
                }
            }
        }

        return redirect()->back()->with('success', 
            "Pembayaran {$payment->no_transaksi} santri {$payment->student->nama_lengkap} berhasil dikonfirmasi lunas."
        );
    }

    /**
     * Bendahara Menolak Bukti Transfer Santri yang Tidak Valid.
     */
    public function tolakStudentPayment(Request $request, $id)
    {
        $payment = StudentPayment::findOrFail($id);
        $alasan = $request->input('alasan', 'Bukti transfer tidak valid atau dana belum masuk rekening pesantren.');
        
        $payment->status = 'Ditolak';
        $payment->catatan = ($payment->catatan ? $payment->catatan . " | " : "") . "DITOLAK: {$alasan}";
        $payment->save();

        return redirect()->back()->with('success', "Bukti transfer {$payment->no_transaksi} telah ditolak.");
    }

    /**
     * Hapus Catatan Pembayaran Santri.
     */
    public function destroyStudentPayment($id)
    {
        $payment = StudentPayment::with('items')->findOrFail($id);

        // Revert bills payment status if any
        foreach ($payment->items as $item) {
            if ($item->student_bill_id) {
                $bill = StudentBill::find($item->student_bill_id);
                if ($bill) {
                    $bill->nominal_bayar = max(0, $bill->nominal_bayar - $item->nominal);
                    $bill->sisa_tagihan = min($bill->nominal_tagihan, $bill->nominal_tagihan - $bill->nominal_bayar);
                    $bill->status = $bill->sisa_tagihan <= 0 ? 'Lunas' : ($bill->nominal_bayar > 0 ? 'Cicilan' : 'Belum Bayar');
                    $bill->save();
                }
            }
        }

        $no = $payment->no_transaksi;
        $payment->delete();

        return redirect()->back()->with('success', "Data pembayaran {$no} berhasil dibatalkan dan dihapus.");
    }

    /**
     * Cetak Kwitansi Resmi Pembayaran Santri (Print View).
     */
    public function kwitansi($id)
    {
        $payment = StudentPayment::with(['student.classroom', 'items.bill', 'user'])->findOrFail($id);

        // Hitung total sisa tunggakan santri setelah pembayaran ini
        $sisaTunggakanSantri = StudentBill::where('student_id', $payment->student_id)
            ->where('status', '!=', 'Lunas')
            ->sum('sisa_tagihan');

        // Hitung total sisa saldo tabungan santri setelah transaksi ini
        $sisaTabunganSantri = $payment->student ? (float)$payment->student->saldo_tabungan : 0;

        return view('admin.pembayaran.kwitansi', compact('payment', 'sisaTunggakanSantri', 'sisaTabunganSantri'));
    }

    /**
     * Helper filter pencarian data pembayaran santri (untuk riwayat, kwitansi massal & rekap register).
     */
    private function buildPaymentFilterQuery(Request $request)
    {
        $query = StudentPayment::with(['student.classroom', 'items.bill', 'user'])->latest('tanggal_bayar')->latest('id');

        // Filter jika user mencentang kwitansi tertentu dari tabel riwayat (checkbox IDs)
        if ($request->filled('ids')) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $ids = array_filter(array_map('intval', $ids));
            if (!empty($ids)) {
                return $query->whereIn('id', $ids);
            }
        }

        // Filter kata kunci pencarian (santri, NIS, no transaksi)
        if ($request->filled('q_santri')) {
            $q = $request->q_santri;
            $query->where(function($w) use ($q) {
                $w->where('no_transaksi', 'like', "%{$q}%")
                  ->orWhereHas('student', function($sq) use ($q) {
                      $sq->where('nama_lengkap', 'like', "%{$q}%")
                         ->orWhere('nis', 'like', "%{$q}%")
                         ->orWhere('kelas', 'like', "%{$q}%");
                  });
            });
        }

        // Filter kelas
        if ($request->filled('kelas')) {
            $kelas = $request->kelas;
            $query->whereHas('student', function($sq) use ($kelas) {
                $sq->where('kelas', $kelas);
            });
        }

        // Filter pos biaya / jenis pembayaran
        if ($request->filled('jenis_pembayaran')) {
            $jp = $request->jenis_pembayaran;
            $query->where(function($w) use ($jp) {
                $w->where('jenis_pembayaran', 'like', "%{$jp}%")
                  ->orWhereHas('items', function($iq) use ($jp) {
                      $iq->where('pos_biaya', 'like', "%{$jp}%");
                  });
            });
        }

        // Filter metode pembayaran (Tunai / Transfer Bank)
        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        // Filter rentang tanggal transaksi
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_bayar', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_bayar', '<=', $request->tanggal_akhir);
        }

        // Filter status (default hanya transaksi Lunas untuk kwitansi resmi)
        if ($request->filled('status_santri')) {
            $query->where('status', $request->status_santri);
        } else {
            $query->where('status', 'Lunas');
        }

        return $query;
    }

    /**
     * Cetak Kwitansi Massal Sekaligus (Bulk Print Receipts)
     */
    public function kwitansiMassal(Request $request)
    {
        $payments = $this->buildPaymentFilterQuery($request)->get();

        if ($payments->isEmpty()) {
            return redirect()->route('admin.pembayaran.index', ['tab' => 'santri'])
                ->with('error', 'Tidak ada data kwitansi yang ditemukan untuk dicetak berdasarkan filter/pilihan yang dipilih.');
        }

        // Ambil sisa tunggakan untuk semua santri yang terlibat
        $studentIds = $payments->pluck('student_id')->filter()->unique();
        $tunggakanMap = StudentBill::whereIn('student_id', $studentIds)
            ->where('status', '!=', 'Lunas')
            ->groupBy('student_id')
            ->selectRaw('student_id, SUM(sisa_tagihan) as total_sisa')
            ->pluck('total_sisa', 'student_id')
            ->toArray();

        return view('admin.pembayaran.kwitansi_massal', compact('payments', 'tunggakanMap'));
    }

    /**
     * Rekapitulasi Register Kwitansi Pembayaran Santri (Print View)
     */
    public function rekapKwitansi(Request $request)
    {
        $payments = $this->buildPaymentFilterQuery($request)->get();

        $totalNominal = $payments->sum('nominal');
        $totalTransaksi = $payments->count();

        // Rincian Akumulasi Nominal per Pos Biaya
        $breakdownPos = [];
        $breakdownMetode = [
            'Tunai' => 0,
            'Transfer Bank' => 0,
        ];

        foreach ($payments as $p) {
            $metode = $p->metode_pembayaran ?: 'Tunai';
            $breakdownMetode[$metode] = ($breakdownMetode[$metode] ?? 0) + (float)$p->nominal;

            if ($p->items && $p->items->count() > 0) {
                foreach ($p->items as $it) {
                    $pos = strtoupper(trim($it->pos_biaya ?: 'LAINNYA'));
                    $breakdownPos[$pos] = ($breakdownPos[$pos] ?? 0) + (float)$it->nominal;
                }
            } else {
                $pos = strtoupper(trim($p->jenis_pembayaran ?: 'LAINNYA'));
                $breakdownPos[$pos] = ($breakdownPos[$pos] ?? 0) + (float)$p->nominal;
            }
        }
        arsort($breakdownPos);

        return view('admin.pembayaran.rekap_kwitansi', compact(
            'payments',
            'totalNominal',
            'totalTransaksi',
            'breakdownPos',
            'breakdownMetode'
        ));
    }

    /**
     * Ekspor Rekapitulasi Register Kwitansi ke Excel (.xlsx)
     */
    public function exportRekapKwitansiExcel(Request $request)
    {
        $payments = $this->buildPaymentFilterQuery($request)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Register Kwitansi');

        // Judul Laporan
        $sheet->setCellValue('A1', 'REKAPITULASI REGISTER KWITANSI PEMBAYARAN SANTRI');
        $sheet->setCellValue('A2', 'PONDOK PESANTREN HIDAYATULLAH TUKSONGO TEMANGGUNG');
        
        $periodeText = 'Semua Periode';
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $periodeText = Carbon::parse($request->tanggal_mulai)->format('d/m/Y') . ' s/d ' . Carbon::parse($request->tanggal_akhir)->format('d/m/Y');
        } elseif ($request->filled('tanggal_mulai')) {
            $periodeText = 'Mulai ' . Carbon::parse($request->tanggal_mulai)->format('d/m/Y');
        } elseif ($request->filled('tanggal_akhir')) {
            $periodeText = 'Sampai ' . Carbon::parse($request->tanggal_akhir)->format('d/m/Y');
        }
        $kelasText = $request->filled('kelas') ? ' | Kelas: ' . $request->kelas : '';
        $sheet->setCellValue('A3', 'Periode: ' . $periodeText . $kelasText . ' | Dicetak: ' . date('d/m/Y H:i') . ' WIB');

        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10);

        // Header Tabel
        $headers = ['No', 'No. Kwitansi', 'Tanggal Bayar', 'NIS', 'Nama Santri', 'Kelas', 'Rincian Pos Biaya', 'Metode', 'Petugas Kasir', 'Status', 'Nominal (Rp)'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

        foreach ($headers as $idx => $h) {
            $c = $cols[$idx];
            $sheet->setCellValue($c . '5', $h);
            $sheet->getStyle($c . '5')->getFont()->setBold(true);
            $sheet->getStyle($c . '5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF208075');
            $sheet->getStyle($c . '5')->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle($c . '5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $rowNum = 6;
        $no = 1;
        $grandTotal = 0;

        foreach ($payments as $p) {
            $rincian = [];
            if ($p->items && $p->items->count() > 0) {
                foreach ($p->items as $it) {
                    $rincian[] = $it->pos_biaya . ': Rp ' . number_format($it->nominal, 0, ',', '.');
                }
            } else {
                $rincian[] = $p->jenis_pembayaran;
            }
            $rincianText = implode(', ', $rincian);

            $grandTotal += (float)$p->nominal;

            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $p->no_transaksi);
            $sheet->setCellValue('C' . $rowNum, optional($p->tanggal_bayar)->format('d/m/Y') ?: '-');
            $sheet->setCellValue('D' . $rowNum, $p->student?->nis ?: '-');
            $sheet->setCellValue('E' . $rowNum, $p->student?->nama_lengkap ?: '-');
            $sheet->setCellValue('F' . $rowNum, $p->student?->kelas ?: '-');
            $sheet->setCellValue('G' . $rowNum, $rincianText);
            $sheet->setCellValue('H' . $rowNum, $p->metode_pembayaran ?: 'Tunai');
            $sheet->setCellValue('I' . $rowNum, $p->penerima_nama ?: ($p->user?->name ?: 'Bendahara'));
            $sheet->setCellValue('J' . $rowNum, $p->status);
            $sheet->setCellValue('K' . $rowNum, (float)$p->nominal);

            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

            $rowNum++;
        }

        // Total Row
        $sheet->setCellValue('A' . $rowNum, 'TOTAL PENERIMAAN KAS');
        $sheet->mergeCells("A{$rowNum}:J{$rowNum}");
        $sheet->setCellValue('K' . $rowNum, $grandTotal);
        $sheet->getStyle("A{$rowNum}:K{$rowNum}")->getFont()->setBold(true);
        $sheet->getStyle("A{$rowNum}:K{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('K' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

        foreach ($cols as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $filename = 'Rekap_Register_Kwitansi_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tempPath = tempnam(sys_get_temp_dir(), 'rekap_kwitansi_');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Tangguhkan Tagihan Santri Sampai Wisuda Kelulusan.
     */
    public function tangguhkanKeWisuda(Request $request, $id)
    {
        $bill = StudentBill::with('student')->findOrFail($id);

        if ($bill->status === 'Lunas') {
            return redirect()->back()->with('error', 'Tagihan yang sudah Lunas tidak dapat ditangguhkan.');
        }

        $catatan = $request->input('catatan_penangguhan') ?: 'Disepakati ditangguhkan pembayarannya sampai wisuda/kelulusan madrasah.';

        $bill->penangguhan_wisuda = true;
        $bill->status = 'Ditangguhkan (Wisuda)';
        $bill->catatan_penangguhan = $catatan;
        $bill->ditangguhkan_at = now();
        $bill->save();

        $namaSantri = $bill->student->nama_lengkap ?? 'Santri';
        return redirect()->back()->with('success', "Tagihan '{$bill->judul_tagihan}' untuk {$namaSantri} berhasil ditangguhkan.");
    }

    /**
     * Batalkan Penangguhan Tagihan (Kembalikan ke Tagihan Aktif Rutin).
     */
    public function batalkanPenangguhan($id)
    {
        $bill = StudentBill::with('student')->findOrFail($id);

        $bill->penangguhan_wisuda = false;
        $bill->status = $bill->nominal_bayar > 0 ? 'Cicilan' : 'Belum Bayar';
        $bill->save();

        $namaSantri = $bill->student->nama_lengkap ?? 'Santri';
        return redirect()->back()->with('success', "Penangguhan tagihan '{$bill->judul_tagihan}' untuk {$namaSantri} berhasil dibatalkan.");
    }

    /**
     * Pemutihan / Nol-kan Tagihan Santri dengan Catatan Resmi Kebijakan Pesantren.
     */
    public function nolkanTagihan(Request $request, $id)
    {
        $request->validate([
            'catatan_pembebasan' => 'required|string|max:255',
        ], [
            'catatan_pembebasan.required' => 'Wajib mengisi catatan/alasan pembebasan (nol-kan) tagihan.',
        ]);

        $bill = StudentBill::with('student')->findOrFail($id);
        $alasan = $request->input('catatan_pembebasan');

        $bill->nominal_potongan = $bill->nominal_asli;
        $bill->nominal_tagihan = 0;
        $bill->sisa_tagihan = 0;
        $bill->status = 'Lunas';
        $bill->alasan_potongan = $alasan;
        $bill->catatan_pembebasan = $alasan;
        $bill->save();

        $namaSantri = $bill->student->nama_lengkap ?? 'Santri';
        return redirect()->back()->with('success', "Tagihan '{$bill->judul_tagihan}' untuk {$namaSantri} berhasil dinolkan (pemutihan).");
    }

    /**
     * Admin Meminta Santri Mengunggah Berkas / Surat Keringanan (SKTM, dll.).
     * Otomatis memunculkan kartu/menu upload di login santri bersangkutan.
     */
    public function mintaSuratDispensasi(Request $request, $id)
    {
        $request->validate([
            'jenis_surat_diminta' => 'required|string|max:150',
            'instruksi_surat' => 'nullable|string|max:500',
        ], [
            'jenis_surat_diminta.required' => 'Tentukan jenis berkas/surat yang diminta dari santri (contoh: SKTM Kelurahan).',
        ]);

        $bill = StudentBill::with('student')->findOrFail($id);

        $bill->status_dispensasi = 'diminta_surat';
        $bill->jenis_surat_diminta = $request->input('jenis_surat_diminta');
        $bill->instruksi_surat = $request->input('instruksi_surat') ?: 'Mohon unggah foto/scan surat asli yang telah ditandatangani dan dicap resmi.';
        $bill->surat_diminta_at = now();
        $bill->save();

        $namaSantri = $bill->student->nama_lengkap ?? 'Santri';
        return redirect()->back()->with('success', "Permintaan berkas '{$bill->jenis_surat_diminta}' untuk {$namaSantri} berhasil dikirim.");
    }

    /**
     * Admin Menyetujui Berkas Surat Dispensasi yang Diunggah Santri & Nol-kan Tagihan.
     */
    public function setujuiSuratDispensasi(Request $request, $id)
    {
        $bill = StudentBill::with('student')->findOrFail($id);

        $catatanPersetujuan = $request->input('catatan_persetujuan') ?: ("Disetujui berdasarkan berkas " . ($bill->jenis_surat_diminta ?: "Surat Keringanan") . " yang diunggah santri.");

        $bill->status_dispensasi = 'disetujui';
        $bill->nominal_potongan = $bill->nominal_asli;
        $bill->nominal_tagihan = 0;
        $bill->sisa_tagihan = 0;
        $bill->status = 'Lunas';
        $bill->alasan_potongan = $catatanPersetujuan;
        $bill->catatan_pembebasan = $catatanPersetujuan;
        $bill->save();

        $namaSantri = $bill->student->nama_lengkap ?? 'Santri';
        return redirect()->back()->with('success', "Surat dispensasi disetujui. Tagihan '{$bill->judul_tagihan}' berhasil dinolkan.");
    }

    /**
     * Admin Menolak Berkas Surat Dispensasi Santri.
     */
    public function tolakSuratDispensasi(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:255',
        ], [
            'alasan_penolakan.required' => 'Tuliskan alasan penolakan berkas surat.',
        ]);

        $bill = StudentBill::with('student')->findOrFail($id);
        $alasan = $request->input('alasan_penolakan');

        $bill->status_dispensasi = 'ditolak';
        $bill->alasan_penolakan_surat = $alasan;
        $bill->save();

        $namaSantri = $bill->student->nama_lengkap ?? 'Santri';
        return redirect()->back()->with('info', "Berkas surat santri {$namaSantri} ditolak dengan alasan: {$alasan}. Santri dapat mengunggah ulang surat perbaikan di portal santri.");
    }

    /**
     * Halaman Rekapitulasi Tunggakan & Kekurangan Keuangan Santri (Fitur Khusus Bendahara)
     */
    public function rekapTunggakan(Request $request)
    {
        $kelasFilter = $request->input('kelas');
        $posFilter = $request->input('pos_biaya');
        $jenjangFilter = $request->input('jenjang');
        $search = $request->input('q');

        // Query seluruh tagihan yang belum lunas (menunggak)
        $unpaidQuery = StudentBill::with(['student.classroom'])
            ->where('status', '!=', 'Lunas')
            ->where('sisa_tagihan', '>', 0)
            ->where('penangguhan_wisuda', false);

        if (!empty($posFilter)) {
            $unpaidQuery->where('pos_biaya', $posFilter);
        }

        if (!empty($kelasFilter)) {
            $unpaidQuery->whereHas('student', function($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            });
        }

        if (!empty($jenjangFilter)) {
            $unpaidQuery->whereHas('student', function($q) use ($jenjangFilter) {
                $q->where('jenjang', $jenjangFilter);
            });
        }

        if (!empty($search)) {
            $unpaidQuery->where(function($w) use ($search) {
                $w->where('judul_tagihan', 'like', "%{$search}%")
                  ->orWhere('pos_biaya', 'like', "%{$search}%")
                  ->orWhereHas('student', function($sq) use ($search) {
                      $sq->where('nama_lengkap', 'like', "%{$search}%")
                         ->orWhere('nis', 'like', "%{$search}%");
                  });
            });
        }

        $unpaidBills = $unpaidQuery->get();

        // 1. Ringkasan Global
        $totalTunggakan = (float) $unpaidBills->sum('sisa_tagihan');
        $totalSantriMenunggak = $unpaidBills->pluck('student_id')->unique()->count();
        $totalItemTagihan = $unpaidBills->count();
        $totalDitangguhkan = (float) StudentBill::where('penangguhan_wisuda', true)->sum('sisa_tagihan');

        // 2. Rekap Tunggakan Per Pos Biaya (Menjawab: Pos biaya apa saja yang paling banyak kurang?)
        $rekapPerPos = $unpaidBills->groupBy('pos_biaya')->map(function($items, $pos) {
            return [
                'pos' => $pos,
                'total_sisa' => (float) $items->sum('sisa_tagihan'),
                'count_tagihan' => $items->count(),
                'count_santri' => $items->pluck('student_id')->unique()->count(),
            ];
        })->sortByDesc('total_sisa');

        // 3. Rekap Tunggakan Per Kelas (Menjawab: Kelas mana yang paling banyak kurang?)
        $rekapPerKelas = $unpaidBills->groupBy(function($b) {
            return $b->student?->kelas ?: 'Lainnya';
        })->map(function($items, $kelas) {
            return [
                'kelas' => $kelas,
                'jenjang' => $items->first()->student?->jenjang ?? '-',
                'total_sisa' => (float) $items->sum('sisa_tagihan'),
                'count_santri' => $items->pluck('student_id')->unique()->count(),
            ];
        })->sortByDesc('total_sisa');

        // 4. Daftar Rincian Santri yang Menunggak Beserta Tagihannya
        $santriGrouped = $unpaidBills->groupBy('student_id')->map(function($bills, $studentId) {
            $student = $bills->first()->student;
            return [
                'student' => $student,
                'bills' => $bills,
                'total_tunggakan' => (float) $bills->sum('sisa_tagihan'),
                'item_count' => $bills->count(),
            ];
        })->sortByDesc('total_tunggakan');

        // Manual Pagination untuk santri
        $page = (int) $request->input('page', 1);
        $perPage = 25;
        $santriList = new \Illuminate\Pagination\LengthAwarePaginator(
            $santriGrouped->forPage($page, $perPage),
            $santriGrouped->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $classrooms = Classroom::orderBy('jenjang')->orderBy('nama_kelas')->get();
        $posBiayaList = self::POS_BIAYA_LIST;

        return view('admin.pembayaran.rekap_tunggakan', compact(
            'totalTunggakan',
            'totalSantriMenunggak',
            'totalItemTagihan',
            'totalDitangguhkan',
            'rekapPerPos',
            'rekapPerKelas',
            'santriList',
            'classrooms',
            'posBiayaList',
            'kelasFilter',
            'posFilter',
            'jenjangFilter',
            'search'
        ));
    }

    /**
     * Ekspor Rekap Tunggakan Santri ke Excel (.xlsx)
     */
    public function exportRekapTunggakanExcel(Request $request)
    {
        $kelasFilter = $request->input('kelas');
        $posFilter = $request->input('pos_biaya');

        $unpaidQuery = StudentBill::with('student')
            ->where('status', '!=', 'Lunas')
            ->where('sisa_tagihan', '>', 0)
            ->where('penangguhan_wisuda', false);

        if (!empty($posFilter)) {
            $unpaidQuery->where('pos_biaya', $posFilter);
        }
        if (!empty($kelasFilter)) {
            $unpaidQuery->whereHas('student', function($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            });
        }

        $unpaidBills = $unpaidQuery->get();
        $santriGrouped = $unpaidBills->groupBy('student_id')->sortByDesc(fn($b) => $b->sum('sisa_tagihan'));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Tunggakan');

        // Header Title
        $sheet->setCellValue('A1', 'REKAPITULASI KEKURANGAN & TUNGGAKAN BIAYA SANTRI');
        $sheet->setCellValue('A2', 'PONDOK PESANTREN HIDAYATULLAH TUKSONGO');
        $sheet->setCellValue('A3', 'Tanggal Unduh: ' . date('d F Y, H:i') . ' WIB' . ($kelasFilter ? " | Kelas: {$kelasFilter}" : '') . ($posFilter ? " | Pos: {$posFilter}" : ''));
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(13);

        // Header Columns
        $headers = ['No', 'NIS', 'Nama Santri', 'Kelas', 'Wali Santri', 'No. WhatsApp', 'Rincian Tagihan Belum Lunas', 'Total Kekurangan (Rp)'];
        $colLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        foreach ($headers as $idx => $h) {
            $col = $colLetters[$idx];
            $sheet->setCellValue($col . '5', $h);
            $sheet->getStyle($col . '5')->getFont()->setBold(true);
            $sheet->getStyle($col . '5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');
        }

        $rowNum = 6;
        $no = 1;
        $grandTotal = 0;

        foreach ($santriGrouped as $bills) {
            $student = $bills->first()->student;
            $rincian = $bills->map(fn($b) => $b->judul_tagihan . ' (Rp ' . number_format($b->sisa_tagihan, 0, ',', '.') . ')')->implode(', ');
            $totalSisa = (float) $bills->sum('sisa_tagihan');
            $grandTotal += $totalSisa;

            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $student?->nis ?: '-');
            $sheet->setCellValue('C' . $rowNum, $student?->nama_lengkap ?: '-');
            $sheet->setCellValue('D' . $rowNum, $student?->kelas ?: '-');
            $sheet->setCellValue('E' . $rowNum, $student?->nama_wali ?: '-');
            $sheet->setCellValue('F' . $rowNum, $student?->no_whatsapp ?: '-');
            $sheet->setCellValue('G' . $rowNum, $rincian);
            $sheet->setCellValue('H' . $rowNum, $totalSisa);
            $sheet->getStyle('H' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

            $rowNum++;
        }

        // Total Row
        $sheet->setCellValue('A' . $rowNum, 'TOTAL KEKURANGAN');
        $sheet->mergeCells("A{$rowNum}:G{$rowNum}");
        $sheet->setCellValue('H' . $rowNum, $grandTotal);
        $sheet->getStyle("A{$rowNum}:H{$rowNum}")->getFont()->setBold(true);
        $sheet->getStyle("A{$rowNum}:H{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');
        $sheet->getStyle('H' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

        foreach ($colLetters as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $filename = 'Rekap_Tunggakan_Santri_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tempPath = tempnam(sys_get_temp_dir(), 'rekap_tunggakan_');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }
}

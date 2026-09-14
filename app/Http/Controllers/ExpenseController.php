<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperationalExpense;
use App\Models\StudentPayment;
use App\Models\StudentPaymentItem;
use App\Models\PsbRegistration;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExpenseController extends Controller
{
    /**
     * Halaman Buku Kas Keluar (Beban Operasional Pesantren)
     */
    public function index(Request $request)
    {
        $kategoriFilter = $request->input('kategori');
        $sumberPosFilter = $request->input('sumber_pos');
        $jenjangFilter = $request->input('jenjang');
        $metodeFilter = $request->input('metode_kas');
        $bulanFilter = $request->input('bulan', date('m'));
        $tahunFilter = $request->input('tahun', date('Y'));
        $search = $request->input('search');

        $query = OperationalExpense::with('user');

        if (!empty($kategoriFilter)) {
            $query->where('kategori', $kategoriFilter);
        }

        if (!empty($sumberPosFilter) && $sumberPosFilter !== 'all') {
            $query->where('sumber_pos', $sumberPosFilter);
        }

        if (!empty($jenjangFilter) && $jenjangFilter !== 'all') {
            $query->where('jenjang', $jenjangFilter);
        }

        if (!empty($metodeFilter)) {
            $query->where('metode_kas', $metodeFilter);
        }

        if (!empty($bulanFilter) && $bulanFilter !== 'all') {
            $query->whereMonth('tanggal_keluar', $bulanFilter);
        }

        if (!empty($tahunFilter) && $tahunFilter !== 'all') {
            $query->whereYear('tanggal_keluar', $tahunFilter);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_pengeluaran', 'like', "%{$search}%")
                  ->orWhere('no_referensi', 'like', "%{$search}%")
                  ->orWhere('penerima_dana', 'like', "%{$search}%")
                  ->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        $expenses = $query->orderBy('tanggal_keluar', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Statistik Kas Keluar Bulan Ini & Tahun Ini
        $totalBulanIni = (float) OperationalExpense::whereMonth('tanggal_keluar', date('m'))
            ->whereYear('tanggal_keluar', date('Y'))
            ->sum('nominal');

        $totalBulanIniMts = (float) OperationalExpense::whereMonth('tanggal_keluar', date('m'))
            ->whereYear('tanggal_keluar', date('Y'))
            ->where('jenjang', 'MTs')
            ->sum('nominal');

        $totalBulanIniMa = (float) OperationalExpense::whereMonth('tanggal_keluar', date('m'))
            ->whereYear('tanggal_keluar', date('Y'))
            ->where('jenjang', 'MA')
            ->sum('nominal');

        $totalBulanIniBersama = (float) OperationalExpense::whereMonth('tanggal_keluar', date('m'))
            ->whereYear('tanggal_keluar', date('Y'))
            ->where(function($q) {
                $q->where('jenjang', 'Semua')->orWhereNull('jenjang');
            })
            ->sum('nominal');

        $totalTahunIni = (float) OperationalExpense::whereYear('tanggal_keluar', date('Y'))
            ->sum('nominal');

        $totalTransaksiBulanIni = OperationalExpense::whereMonth('tanggal_keluar', date('m'))
            ->whereYear('tanggal_keluar', date('Y'))
            ->count();

        // Kategori Terbesar Bulan Ini
        $topKategori = OperationalExpense::whereMonth('tanggal_keluar', date('m'))
            ->whereYear('tanggal_keluar', date('Y'))
            ->selectRaw('kategori, SUM(nominal) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->first();

        $kategoriList = OperationalExpense::KATEGORI_LIST;
        $jenjangList = OperationalExpense::JENJANG_LIST;
        $sumberPosList = OperationalExpense::SUMBER_POS_LIST;

        // Hitung Saldo & Perhitungan Sumber Dana (Uang Masuk, Terpakai, Sisa Saldo)
        $sumberDanaBalances = self::calculateSumberDanaBalances($jenjangFilter);
        $totalSaldoTersediaSemuaSumber = array_sum(array_column($sumberDanaBalances, 'saldo'));
        $totalMasukSemuaSumber = array_sum(array_column($sumberDanaBalances, 'masuk'));
        $totalKeluarSemuaSumber = array_sum(array_column($sumberDanaBalances, 'keluar'));

        return view('admin.pembayaran.pengeluaran', compact(
            'expenses',
            'kategoriList',
            'jenjangList',
            'sumberPosList',
            'sumberDanaBalances',
            'totalSaldoTersediaSemuaSumber',
            'totalMasukSemuaSumber',
            'totalKeluarSemuaSumber',
            'kategoriFilter',
            'sumberPosFilter',
            'jenjangFilter',
            'metodeFilter',
            'bulanFilter',
            'tahunFilter',
            'search',
            'totalBulanIni',
            'totalBulanIniMts',
            'totalBulanIniMa',
            'totalBulanIniBersama',
            'totalTahunIni',
            'totalTransaksiBulanIni',
            'topKategori'
        ));
    }

    /**
     * Hitung ringkasan saldo per Pos Sumber Dana (Pemasukan, Pengeluaran, Sisa Saldo)
     */
    public static function calculateSumberDanaBalances($jenjang = null)
    {
        $sumberList = OperationalExpense::SUMBER_POS_LIST;
        $results = [];

        // Base query pembayaran santri (tidak ditolak)
        $paymentItemsQuery = function ($posFilter) use ($jenjang) {
            $q = StudentPaymentItem::whereHas('payment', function ($pq) use ($jenjang) {
                $pq->where(function ($pqq) {
                    $pqq->whereNull('status')->orWhere('status', '!=', 'Ditolak');
                });
                if ($jenjang && $jenjang !== 'all' && $jenjang !== 'Semua') {
                    $pq->whereHas('student', function ($sq) use ($jenjang) {
                        $sq->where('jenjang', 'like', "%{$jenjang}%");
                    });
                }
            });
            if (is_callable($posFilter)) {
                $q->where($posFilter);
            }
            return (float) $q->sum('nominal');
        };

        // Query pengeluaran kas operasional
        $expensesQuery = function ($posKey) use ($jenjang) {
            $q = OperationalExpense::query();
            if ($posKey === 'Kas Umum') {
                $q->where(function ($eq) {
                    $eq->where('sumber_pos', 'Kas Umum')
                       ->orWhereNull('sumber_pos')
                       ->orWhere('sumber_pos', '');
                });
            } else {
                $q->where('sumber_pos', $posKey);
            }
            if ($jenjang && $jenjang !== 'all' && $jenjang !== 'Semua') {
                $q->where('jenjang', $jenjang);
            }
            return (float) $q->sum('nominal');
        };

        // Query PSB
        $psbIncome = function () use ($jenjang) {
            $q = PsbRegistration::where('status_pembayaran', 'Lunas');
            if ($jenjang && $jenjang !== 'all' && $jenjang !== 'Semua') {
                $q->where('jenjang', 'like', "%{$jenjang}%");
            }
            return (float) $q->sum('nominal_pembayaran');
        };

        foreach ($sumberList as $key => $label) {
            $masuk = 0;
            switch ($key) {
                case 'Uang Makan':
                    $masuk = $paymentItemsQuery(fn($q) => $q->where('pos_biaya', 'like', '%MAKAN%'));
                    break;
                case 'Syahriyah':
                    $masuk = $paymentItemsQuery(fn($q) => $q->where('pos_biaya', 'like', '%SYAHRIYAH%')->orWhere('pos_biaya', 'like', '%SPP%'));
                    break;
                case 'SOT':
                    $masuk = $paymentItemsQuery(fn($q) => $q->where('pos_biaya', 'like', '%SOT%'));
                    break;
                case 'Tabungan':
                    $setor = $paymentItemsQuery(fn($q) => $q->where('pos_biaya', 'TAB')->orWhere('pos_biaya', 'like', '%TABUNGAN%')->where('pos_biaya', '!=', 'AMBIL TABUNGAN'));
                    $ambil = $paymentItemsQuery(fn($q) => $q->where('pos_biaya', 'AMBIL TABUNGAN'));
                    $masuk = max(0, $setor - $ambil);
                    break;
                case 'Uang Gedung':
                    $masuk = $paymentItemsQuery(fn($q) => $q->where('pos_biaya', 'like', '%GEDUNG%')->orWhere('pos_biaya', 'like', '%BANGUNAN%'));
                    break;
                case 'Uang Pangkal':
                    $masuk = $paymentItemsQuery(fn($q) => $q->where('pos_biaya', 'like', '%PANGKAL%')->orWhere('pos_biaya', 'like', '%DAFTAR%')) + $psbIncome();
                    break;
                case 'Kesehatan':
                    $masuk = $paymentItemsQuery(fn($q) => $q->where('pos_biaya', 'like', '%KESEHATAN%')->orWhere('pos_biaya', 'like', '%UKS%'));
                    break;
                case 'Kegiatan':
                    $masuk = $paymentItemsQuery(fn($q) => $q->where('pos_biaya', 'like', '%KEGIATAN%')->orWhere('pos_biaya', 'like', '%PHBI%')->orWhere('pos_biaya', 'like', '%ZIARAH%')->orWhere('pos_biaya', 'like', '%QURBAN%'));
                    break;
                case 'Listrik & Sarana':
                    $masuk = $paymentItemsQuery(fn($q) => $q->where('pos_biaya', 'like', '%LISTRIK%')->orWhere('pos_biaya', 'like', '%SARANA%')->orWhere('pos_biaya', 'like', '%AIR%'));
                    break;
                case 'Kantor & ATK':
                    $masuk = $paymentItemsQuery(fn($q) => $q->where('pos_biaya', 'like', '%KANTOR%')->orWhere('pos_biaya', 'like', '%ATK%'));
                    break;
                case 'Kas Umum':
                    $masuk = $paymentItemsQuery(fn($q) => $q->where('pos_biaya', 'like', '%KAS UMUM%')->orWhere('pos_biaya', 'like', '%INFAQ UMUM%')->orWhere('pos_biaya', 'like', '%DONASI%'));
                    break;
            }

            $keluar = $expensesQuery($key);
            $saldo = $masuk - $keluar;

            $results[$key] = [
                'key' => $key,
                'label' => $label,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'saldo' => $saldo,
                'formatted_masuk' => 'Rp ' . number_format($masuk, 0, ',', '.'),
                'formatted_keluar' => 'Rp ' . number_format($keluar, 0, ',', '.'),
                'formatted_saldo' => 'Rp ' . number_format($saldo, 0, ',', '.'),
            ];
        }

        return $results;
    }

    /**
     * Simpan Pengeluaran Kas Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'sumber_pos' => 'nullable|string',
            'jenjang' => 'nullable|string',
            'judul_pengeluaran' => 'required|string|max:255',
            'nominal' => 'required',
            'tanggal_keluar' => 'required|date',
            'metode_kas' => 'required|string',
            'penerima_dana' => 'nullable|string|max:255',
            'bukti_nota' => 'nullable|image|max:3072', // Max 3MB
            'catatan' => 'nullable|string',
        ]);

        $cleanNominal = preg_replace('/[^0-9]/', '', $request->nominal);

        $data = [
            'no_referensi' => OperationalExpense::generateNoReferensi($request->tanggal_keluar),
            'kategori' => $request->kategori,
            'sumber_pos' => $request->sumber_pos ?: 'Kas Umum',
            'jenjang' => $request->jenjang ?: 'Semua',
            'judul_pengeluaran' => $request->judul_pengeluaran,
            'nominal' => (float) $cleanNominal,
            'tanggal_keluar' => $request->tanggal_keluar,
            'metode_kas' => $request->metode_kas,
            'penerima_dana' => $request->penerima_dana,
            'user_id' => auth()->id(),
            'catatan' => $request->catatan,
        ];

        // Upload bukti nota jika ada
        if ($request->hasFile('bukti_nota')) {
            $file = $request->file('bukti_nota');
            $filename = time() . '_nota_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/nota_pengeluaran'), $filename);
            $data['bukti_nota'] = '/uploads/nota_pengeluaran/' . $filename;
        }

        OperationalExpense::create($data);

        return redirect()->back()->with('success', "Pengeluaran {$data['no_referensi']} ({$data['judul_pengeluaran']}) sebesar Rp " . number_format($data['nominal'], 0, ',', '.') . " berhasil dicatat.");
    }

    /**
     * Update Pengeluaran Kas
     */
    public function update(Request $request, $id)
    {
        $expense = OperationalExpense::findOrFail($id);

        $request->validate([
            'kategori' => 'required|string',
            'sumber_pos' => 'nullable|string',
            'jenjang' => 'nullable|string',
            'judul_pengeluaran' => 'required|string|max:255',
            'nominal' => 'required',
            'tanggal_keluar' => 'required|date',
            'metode_kas' => 'required|string',
            'penerima_dana' => 'nullable|string|max:255',
            'bukti_nota' => 'nullable|image|max:3072',
            'catatan' => 'nullable|string',
        ]);

        $cleanNominal = preg_replace('/[^0-9]/', '', $request->nominal);

        $data = [
            'kategori' => $request->kategori,
            'sumber_pos' => $request->sumber_pos ?: 'Kas Umum',
            'jenjang' => $request->jenjang ?: 'Semua',
            'judul_pengeluaran' => $request->judul_pengeluaran,
            'nominal' => (float) $cleanNominal,
            'tanggal_keluar' => $request->tanggal_keluar,
            'metode_kas' => $request->metode_kas,
            'penerima_dana' => $request->penerima_dana,
            'catatan' => $request->catatan,
        ];

        if ($request->hasFile('bukti_nota')) {
            // Hapus file lama jika ada
            if ($expense->bukti_nota && file_exists(public_path($expense->bukti_nota))) {
                @unlink(public_path($expense->bukti_nota));
            }

            $file = $request->file('bukti_nota');
            $filename = time() . '_nota_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/nota_pengeluaran'), $filename);
            $data['bukti_nota'] = '/uploads/nota_pengeluaran/' . $filename;
        }

        $expense->update($data);

        return redirect()->back()->with('success', "Data pengeluaran {$expense->no_referensi} berhasil diperbarui.");
    }

    /**
     * Hapus Pengeluaran Kas
     */
    public function destroy($id)
    {
        $expense = OperationalExpense::findOrFail($id);

        if ($expense->bukti_nota && file_exists(public_path($expense->bukti_nota))) {
            @unlink(public_path($expense->bukti_nota));
        }

        $noRef = $expense->no_referensi;
        $expense->delete();

        return redirect()->back()->with('success', "Data pengeluaran {$noRef} berhasil dihapus.");
    }

    /**
     * Laporan Arus Kas Terpadu (Cashflow Statement: Kas Masuk vs Kas Keluar)
     */
    public function cashflow(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        // 1. Kas Masuk Santri Aktif dalam rentang tanggal
        $studentPayments = StudentPayment::with(['student', 'items'])
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->where(function ($q) {
                $q->whereNull('status')
                  ->orWhere('status', '!=', 'Ditolak');
            })
            ->get();

        $totalMasukSantri = (float) $studentPayments->sum('nominal');

        // Pemasukan Santri MTs vs MA
        $masukSantriMts = (float) $studentPayments->filter(function($p) {
            return stripos($p->student->jenjang ?? '', 'MTs') !== false;
        })->sum('nominal');

        $masukSantriMa = (float) $studentPayments->filter(function($p) {
            return stripos($p->student->jenjang ?? '', 'MA') !== false;
        })->sum('nominal');

        $masukSantriLainnya = $totalMasukSantri - ($masukSantriMts + $masukSantriMa);

        // 2. Kas Masuk PSB dalam rentang tanggal
        $psbPayments = PsbRegistration::whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->where('status_pembayaran', 'Lunas')
            ->get();

        $totalMasukPsb = (float) $psbPayments->sum(function($p) {
            return $p->nominal_pembayaran ?: 3225000;
        });

        $masukPsbMts = (float) $psbPayments->filter(function($p) {
            return stripos($p->jenjang ?? '', 'MTs') !== false;
        })->sum(fn($p) => $p->nominal_pembayaran ?: 3225000);

        $masukPsbMa = (float) $psbPayments->filter(function($p) {
            return stripos($p->jenjang ?? '', 'MA') !== false;
        })->sum(fn($p) => $p->nominal_pembayaran ?: 3225000);

        $masukPsbLainnya = $totalMasukPsb - ($masukPsbMts + $masukPsbMa);

        // Total Kas Masuk per Jenjang & Grand Total
        $totalMasukMts = $masukSantriMts + $masukPsbMts;
        $totalMasukMa = $masukSantriMa + $masukPsbMa;
        $totalKasMasuk = $totalMasukSantri + $totalMasukPsb;

        // 3. Kas Keluar Operasional dalam rentang tanggal
        $expenses = OperationalExpense::whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->orderBy('tanggal_keluar', 'desc')
            ->get();

        $totalKasKeluar = (float) $expenses->sum('nominal');

        // Kas Keluar per Jenjang (MTs, MA, dan Operasional Bersama/Umum)
        $keluarMts = (float) $expenses->where('jenjang', 'MTs')->sum('nominal');
        $keluarMa = (float) $expenses->where('jenjang', 'MA')->sum('nominal');
        $keluarBersama = (float) $expenses->filter(function($e) {
            return empty($e->jenjang) || $e->jenjang === 'Semua';
        })->sum('nominal');

        // 4. Saldo Kas Bersih (Net Cashflow / Surplus-Defisit)
        $saldoKasBersih = $totalKasMasuk - $totalKasKeluar;
        $saldoBersihMts = $totalMasukMts - $keluarMts;
        $saldoBersihMa = $totalMasukMa - $keluarMa;

        // 5. Rincian Pengeluaran per Kategori Operasional
        $kategoriBreakdown = $expenses->groupBy('kategori')->map(function ($items, $kat) use ($totalKasKeluar) {
            $sum = (float) $items->sum('nominal');
            $pct = $totalKasKeluar > 0 ? round(($sum / $totalKasKeluar) * 100, 1) : 0;
            return [
                'kategori' => $kat,
                'total' => $sum,
                'persentase' => $pct,
                'count' => $items->count(),
            ];
        })->sortByDesc('total');

        // 5b. Rincian Pengeluaran per Sumber Pos Dana (Uang Makan, Syahriyah, SOT, Kas Umum, dsb)
        $sumberPosBreakdown = $expenses->groupBy(function($e) {
            return $e->sumber_pos ?: 'Kas Umum';
        })->map(function ($items, $sp) use ($totalKasKeluar) {
            $sum = (float) $items->sum('nominal');
            $pct = $totalKasKeluar > 0 ? round(($sum / $totalKasKeluar) * 100, 1) : 0;
            return [
                'sumber_pos' => $sp,
                'total' => $sum,
                'persentase' => $pct,
                'count' => $items->count(),
            ];
        })->sortByDesc('total');

        // 6. Rincian Pemasukan per Pos Biaya (MTs, MA, dan Total)
        $posPemasukanBreakdown = [];
        $posPemasukanMts = [];
        $posPemasukanMa = [];

        foreach ($studentPayments as $sp) {
            $j = 'Lainnya';
            if (stripos($sp->student->jenjang ?? '', 'MTs') !== false) {
                $j = 'MTs';
            } elseif (stripos($sp->student->jenjang ?? '', 'MA') !== false) {
                $j = 'MA';
            }

            foreach ($sp->items as $it) {
                $pos = $it->pos_biaya ?: 'LAINNYA';
                $posPemasukanBreakdown[$pos] = ($posPemasukanBreakdown[$pos] ?? 0) + $it->nominal;
                if ($j === 'MTs') {
                    $posPemasukanMts[$pos] = ($posPemasukanMts[$pos] ?? 0) + $it->nominal;
                } elseif ($j === 'MA') {
                    $posPemasukanMa[$pos] = ($posPemasukanMa[$pos] ?? 0) + $it->nominal;
                }
            }
        }

        if ($totalMasukPsb > 0) {
            $posPemasukanBreakdown['PSB (SANTRI BARU)'] = ($posPemasukanBreakdown['PSB (SANTRI BARU)'] ?? 0) + $totalMasukPsb;
            if ($masukPsbMts > 0) {
                $posPemasukanMts['PSB (SANTRI BARU)'] = ($posPemasukanMts['PSB (SANTRI BARU)'] ?? 0) + $masukPsbMts;
            }
            if ($masukPsbMa > 0) {
                $posPemasukanMa['PSB (SANTRI BARU)'] = ($posPemasukanMa['PSB (SANTRI BARU)'] ?? 0) + $masukPsbMa;
            }
        }

        arsort($posPemasukanBreakdown);
        arsort($posPemasukanMts);
        arsort($posPemasukanMa);

        // 7. Kas Keluar Tunai vs Transfer
        $keluarTunai = (float) $expenses->where('metode_kas', 'Kas Tunai')->sum('nominal');
        $keluarBank = (float) $expenses->where('metode_kas', '!=', 'Kas Tunai')->sum('nominal');

        return view('admin.pembayaran.arus_kas', compact(
            'startDate',
            'endDate',
            'totalKasMasuk',
            'totalMasukSantri',
            'totalMasukPsb',
            'totalMasukMts',
            'totalMasukMa',
            'masukSantriMts',
            'masukSantriMa',
            'masukPsbMts',
            'masukPsbMa',
            'totalKasKeluar',
            'keluarMts',
            'keluarMa',
            'keluarBersama',
            'saldoKasBersih',
            'saldoBersihMts',
            'saldoBersihMa',
            'kategoriBreakdown',
            'sumberPosBreakdown',
            'posPemasukanBreakdown',
            'posPemasukanMts',
            'posPemasukanMa',
            'expenses',
            'keluarTunai',
            'keluarBank'
        ));
    }

    /**
     * Ekspor Laporan Komprehensif Kas Masuk & Kas Keluar ke Excel (.xlsx)
     * Menghasilkan 3 Sheet:
     * Sheet 1: Rekap Sumber Dana & Saldo Kas
     * Sheet 2: Catatan Uang Keluar (Yang Terpakai & Sumber Dananya)
     * Sheet 3: Catatan Uang Masuk (Rincian Pembayaran Santri & PSB)
     */
    public function exportCashflowExcel(Request $request)
    {
        // 1. Parsing parameter filter tanggal
        $jenjang = $request->input('jenjang');
        if ($jenjang === 'all' || $jenjang === 'Semua') {
            $jenjang = null;
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
        } elseif ($request->filled('tahun')) {
            $tahun = (int) $request->input('tahun');
            $bulan = $request->input('bulan');
            if ($bulan && $bulan !== 'all') {
                $startDate = sprintf('%04d-%02d-01', $tahun, (int) $bulan);
                $endDate = date('Y-m-t', strtotime($startDate));
            } else {
                $startDate = sprintf('%04d-01-01', $tahun);
                $endDate = sprintf('%04d-12-31', $tahun);
            }
        } else {
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
        }

        $sumberPosFilter = $request->input('sumber_pos');
        $kategoriFilter = $request->input('kategori');

        $spreadsheet = new Spreadsheet();

        // =========================================================================
        // SHEET 1: REKAPITULASI SUMBER DANA & SALDO KAS
        // =========================================================================
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Rekap Sumber Dana & Saldo');

        $sheet1->setCellValue('A1', 'LAPORAN REKAPITULASI SUMBER DANA & ARUS KAS');
        $sheet1->setCellValue('A2', 'PONDOK PESANTREN HIDAYATULLAH TUKSONGO');
        $infoSubtitle = 'Periode: ' . date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate));
        if ($jenjang) {
            $infoSubtitle .= ' | Jenjang: ' . $jenjang;
        }
        $infoSubtitle .= ' | Dicetak: ' . date('d/m/Y H:i') . ' WIB';
        $sheet1->setCellValue('A3', $infoSubtitle);
        $sheet1->getStyle('A1:A2')->getFont()->setBold(true)->setSize(13);

        // Section 1: Perhitungan Saldo per Sumber Pos Dana
        $sheet1->setCellValue('A5', 'I. PERHITUNGAN SALDO KAS PER SUMBER POS DANA');
        $sheet1->getStyle('A5')->getFont()->setBold(true)->setSize(11);

        $headers1 = ['No', 'Sumber Pos Dana', 'Peruntukan / Keterangan Pos', 'Total Uang Masuk (Rp)', 'Total Terpakai / Keluar (Rp)', 'Sisa Saldo Tersedia (Rp)', '% Terpakai', 'Status Saldo'];
        $cols1 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        foreach ($headers1 as $idx => $h) {
            $sheet1->setCellValue($cols1[$idx] . '6', $h);
            $sheet1->getStyle($cols1[$idx] . '6')->getFont()->setBold(true);
            $sheet1->getStyle($cols1[$idx] . '6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');
            $sheet1->getStyle($cols1[$idx] . '6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        }

        $sumberBalances = self::calculateSumberDanaBalances($jenjang);
        $rowNum1 = 7;
        $no1 = 1;
        $totMasuk1 = 0;
        $totKeluar1 = 0;
        $totSaldo1 = 0;

        foreach ($sumberBalances as $key => $b) {
            $pct = $b['masuk'] > 0 ? round(($b['keluar'] / $b['masuk']) * 100, 1) : 0;
            $status = $b['saldo'] > 0 ? 'Tersedia' : ($b['saldo'] < 0 ? 'Defisit' : 'Nol / Habis');

            $sheet1->setCellValue('A' . $rowNum1, $no1++);
            $sheet1->setCellValue('B' . $rowNum1, $b['key']);
            $sheet1->setCellValue('C' . $rowNum1, $b['label']);
            $sheet1->setCellValue('D' . $rowNum1, $b['masuk']);
            $sheet1->setCellValue('E' . $rowNum1, $b['keluar']);
            $sheet1->setCellValue('F' . $rowNum1, $b['saldo']);
            $sheet1->setCellValue('G' . $rowNum1, $pct . '%');
            $sheet1->setCellValue('H' . $rowNum1, $status);

            $sheet1->getStyle('D' . $rowNum1 . ':F' . $rowNum1)->getNumberFormat()->setFormatCode('#,##0');
            $sheet1->getStyle('A' . $rowNum1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle('G' . $rowNum1 . ':H' . $rowNum1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $totMasuk1 += $b['masuk'];
            $totKeluar1 += $b['keluar'];
            $totSaldo1 += $b['saldo'];
            $rowNum1++;
        }

        // Total Row Section 1
        $sheet1->setCellValue('A' . $rowNum1, 'TOTAL KESELURUHAN SUMBER DANA');
        $sheet1->mergeCells("A{$rowNum1}:C{$rowNum1}");
        $sheet1->setCellValue('D' . $rowNum1, $totMasuk1);
        $sheet1->setCellValue('E' . $rowNum1, $totKeluar1);
        $sheet1->setCellValue('F' . $rowNum1, $totSaldo1);
        $sheet1->setCellValue('G' . $rowNum1, $totMasuk1 > 0 ? round(($totKeluar1 / $totMasuk1) * 100, 1) . '%' : '0%');
        $sheet1->setCellValue('H' . $rowNum1, $totSaldo1 >= 0 ? 'Surplus / Aman' : 'Defisit');

        $sheet1->getStyle("A{$rowNum1}:H{$rowNum1}")->getFont()->setBold(true);
        $sheet1->getStyle("A{$rowNum1}:H{$rowNum1}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');
        $sheet1->getStyle('D' . $rowNum1 . ':F' . $rowNum1)->getNumberFormat()->setFormatCode('#,##0');
        $sheet1->getStyle("A{$rowNum1}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet1->getStyle("G{$rowNum1}:H{$rowNum1}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Section 2: Ringkasan Arus Kas Per Jenjang Sekolah (Periode Terpilih)
        $rowNum1 += 3;
        $sheet1->setCellValue('A' . $rowNum1, 'II. RINGKASAN ARUS KAS PER JENJANG SEKOLAH (PERIODE: ' . date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate)) . ')');
        $sheet1->getStyle('A' . $rowNum1)->getFont()->setBold(true)->setSize(11);

        $rowNum1++;
        $headersJenjang = ['Jenjang Sekolah', 'Total Kas Masuk (Rp)', 'Total Kas Keluar (Rp)', 'Saldo Kas Bersih (Rp)', 'Keterangan'];
        $colsJenjang = ['A', 'B', 'C', 'D', 'E'];

        foreach ($headersJenjang as $idx => $hj) {
            $sheet1->setCellValue($colsJenjang[$idx] . $rowNum1, $hj);
            $sheet1->getStyle($colsJenjang[$idx] . $rowNum1)->getFont()->setBold(true);
            $sheet1->getStyle($colsJenjang[$idx] . $rowNum1)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFCBD5E1');
        }

        // Query kas masuk periode
        $studentPaymentsPeriod = StudentPayment::with(['student'])
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'Ditolak');
            })->get();

        $masukMtsSantri = (float) $studentPaymentsPeriod->filter(fn($p) => stripos($p->student->jenjang ?? '', 'MTs') !== false)->sum('nominal');
        $masukMaSantri = (float) $studentPaymentsPeriod->filter(fn($p) => stripos($p->student->jenjang ?? '', 'MA') !== false)->sum('nominal');

        $psbPeriod = PsbRegistration::whereBetween('tanggal_bayar', [$startDate, $endDate])->where('status_pembayaran', 'Lunas')->get();
        $masukMtsPsb = (float) $psbPeriod->filter(fn($p) => stripos($p->jenjang ?? '', 'MTs') !== false)->sum(fn($p) => $p->nominal_pembayaran ?: 3225000);
        $masukMaPsb = (float) $psbPeriod->filter(fn($p) => stripos($p->jenjang ?? '', 'MA') !== false)->sum(fn($p) => $p->nominal_pembayaran ?: 3225000);

        $totMasukMtsPeriod = $masukMtsSantri + $masukMtsPsb;
        $totMasukMaPeriod = $masukMaSantri + $masukMaPsb;

        // Query kas keluar periode
        $expensesPeriod = OperationalExpense::whereBetween('tanggal_keluar', [$startDate, $endDate])->get();
        $keluarMtsPeriod = (float) $expensesPeriod->where('jenjang', 'MTs')->sum('nominal');
        $keluarMaPeriod = (float) $expensesPeriod->where('jenjang', 'MA')->sum('nominal');
        $keluarBersamaPeriod = (float) $expensesPeriod->filter(fn($e) => empty($e->jenjang) || $e->jenjang === 'Semua')->sum('nominal');

        $grandMasukPeriod = $totMasukMtsPeriod + $totMasukMaPeriod;
        $grandKeluarPeriod = $keluarMtsPeriod + $keluarMaPeriod + $keluarBersamaPeriod;
        $grandSaldoPeriod = $grandMasukPeriod - $grandKeluarPeriod;

        $rowNum1++;
        // 1. MTs
        $sheet1->setCellValue('A' . $rowNum1, '1. MTs (Madrasah Tsanawiyah)');
        $sheet1->setCellValue('B' . $rowNum1, $totMasukMtsPeriod);
        $sheet1->setCellValue('C' . $rowNum1, $keluarMtsPeriod);
        $sheet1->setCellValue('D' . $rowNum1, $totMasukMtsPeriod - $keluarMtsPeriod);
        $sheet1->setCellValue('E' . $rowNum1, ($totMasukMtsPeriod - $keluarMtsPeriod >= 0) ? 'Surplus Operasional MTs' : 'Defisit MTs');
        $sheet1->getStyle('B' . $rowNum1 . ':D' . $rowNum1)->getNumberFormat()->setFormatCode('#,##0');

        $rowNum1++;
        // 2. MA
        $sheet1->setCellValue('A' . $rowNum1, '2. MA (Madrasah Aliyah)');
        $sheet1->setCellValue('B' . $rowNum1, $totMasukMaPeriod);
        $sheet1->setCellValue('C' . $rowNum1, $keluarMaPeriod);
        $sheet1->setCellValue('D' . $rowNum1, $totMasukMaPeriod - $keluarMaPeriod);
        $sheet1->setCellValue('E' . $rowNum1, ($totMasukMaPeriod - $keluarMaPeriod >= 0) ? 'Surplus Operasional MA' : 'Defisit MA');
        $sheet1->getStyle('B' . $rowNum1 . ':D' . $rowNum1)->getNumberFormat()->setFormatCode('#,##0');

        $rowNum1++;
        // 3. Bersama
        $sheet1->setCellValue('A' . $rowNum1, '3. Operasional Bersama / Pesantren');
        $sheet1->setCellValue('B' . $rowNum1, 0);
        $sheet1->setCellValue('C' . $rowNum1, $keluarBersamaPeriod);
        $sheet1->setCellValue('D' . $rowNum1, -$keluarBersamaPeriod);
        $sheet1->setCellValue('E' . $rowNum1, 'Beban Bersama Pesantren');
        $sheet1->getStyle('B' . $rowNum1 . ':D' . $rowNum1)->getNumberFormat()->setFormatCode('#,##0');

        $rowNum1++;
        // 4. Total Konsolidasi
        $sheet1->setCellValue('A' . $rowNum1, 'TOTAL KONSOLIDASI (PERIODE INI)');
        $sheet1->setCellValue('B' . $rowNum1, $grandMasukPeriod);
        $sheet1->setCellValue('C' . $rowNum1, $grandKeluarPeriod);
        $sheet1->setCellValue('D' . $rowNum1, $grandSaldoPeriod);
        $sheet1->setCellValue('E' . $rowNum1, $grandSaldoPeriod >= 0 ? 'Surplus Kas Bersih' : 'Defisit Kas Bersih');
        $sheet1->getStyle('A' . $rowNum1 . ':E' . $rowNum1)->getFont()->setBold(true);
        $sheet1->getStyle('A' . $rowNum1 . ':E' . $rowNum1)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($grandSaldoPeriod >= 0 ? 'FFDCFCE7' : 'FFFEE2E2');
        $sheet1->getStyle('B' . $rowNum1 . ':D' . $rowNum1)->getNumberFormat()->setFormatCode('#,##0');

        foreach ($cols1 as $c) {
            $sheet1->getColumnDimension($c)->setAutoSize(true);
        }

        // =========================================================================
        // SHEET 2: CATATAN UANG KELUAR (YANG TERPAKAI)
        // =========================================================================
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Catatan Uang Keluar');

        $sheet2->setCellValue('A1', 'BUKU CATATAN PENGELUARAN KAS (UANG KELUAR YANG TERPAKAI)');
        $sheet2->setCellValue('A2', 'PONDOK PESANTREN HIDAYATULLAH TUKSONGO');
        $infoKeluar = 'Periode: ' . date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate));
        if ($sumberPosFilter) {
            $infoKeluar .= ' | Sumber Pos: ' . $sumberPosFilter;
        }
        if ($kategoriFilter) {
            $infoKeluar .= ' | Kategori: ' . $kategoriFilter;
        }
        if ($jenjang) {
            $infoKeluar .= ' | Jenjang: ' . $jenjang;
        }
        $sheet2->setCellValue('A3', $infoKeluar);
        $sheet2->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);

        $headers2 = [
            'No',
            'No. BKK',
            'Tanggal Keluar',
            'Jenjang Sekolah',
            'Sumber Dana (Dari Uang Apa)',
            'Digunakan Untuk (Dibuat Apa)',
            'Kategori Pengeluaran',
            'Rincian / Catatan Belanja',
            'Penerima Dana / Vendor',
            'Metode Kas',
            'Nominal Terpakai (Rp)'
        ];
        $cols2 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

        foreach ($headers2 as $idx => $h) {
            $sheet2->setCellValue($cols2[$idx] . '5', $h);
            $sheet2->getStyle($cols2[$idx] . '5')->getFont()->setBold(true);
            $sheet2->getStyle($cols2[$idx] . '5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');
            $sheet2->getStyle($cols2[$idx] . '5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        }

        $expensesQuery = OperationalExpense::whereBetween('tanggal_keluar', [$startDate, $endDate])->orderBy('tanggal_keluar');
        if ($sumberPosFilter) {
            $expensesQuery->where('sumber_pos', $sumberPosFilter);
        }
        if ($kategoriFilter) {
            $expensesQuery->where('kategori', $kategoriFilter);
        }
        if ($jenjang) {
            $expensesQuery->where('jenjang', $jenjang);
        }
        $expensesList = $expensesQuery->get();

        $rowNum2 = 6;
        $no2 = 1;
        $sumKeluar2 = 0;

        foreach ($expensesList as $exp) {
            $sumberLabel = OperationalExpense::SUMBER_POS_LIST[$exp->sumber_pos] ?? ($exp->sumber_pos ?: 'Kas Umum Pesantren');
            $sheet2->setCellValue('A' . $rowNum2, $no2++);
            $sheet2->setCellValue('B' . $rowNum2, $exp->no_referensi);
            $sheet2->setCellValue('C' . $rowNum2, optional($exp->tanggal_keluar)->format('d/m/Y'));
            $sheet2->setCellValue('D' . $rowNum2, $exp->jenjang ?: 'Semua (Bersama)');
            $sheet2->setCellValue('E' . $rowNum2, $sumberLabel);
            $sheet2->setCellValue('F' . $rowNum2, $exp->judul_pengeluaran);
            $sheet2->setCellValue('G' . $rowNum2, $exp->kategori);
            $sheet2->setCellValue('H' . $rowNum2, $exp->catatan ?: '-');
            $sheet2->setCellValue('I' . $rowNum2, $exp->penerima_dana ?: '-');
            $sheet2->setCellValue('J' . $rowNum2, $exp->metode_kas);
            $sheet2->setCellValue('K' . $rowNum2, $exp->nominal);
            $sheet2->getStyle('K' . $rowNum2)->getNumberFormat()->setFormatCode('#,##0');
            $sheet2->getStyle('A' . $rowNum2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle('C' . $rowNum2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sumKeluar2 += (float) $exp->nominal;
            $rowNum2++;
        }

        // Total Kas Keluar
        $sheet2->setCellValue('A' . $rowNum2, 'TOTAL KAS KELUAR TERPAKAI');
        $sheet2->mergeCells("A{$rowNum2}:J{$rowNum2}");
        $sheet2->setCellValue('K' . $rowNum2, $sumKeluar2);
        $sheet2->getStyle("A{$rowNum2}:K{$rowNum2}")->getFont()->setBold(true);
        $sheet2->getStyle("A{$rowNum2}:K{$rowNum2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFCA5A5');
        $sheet2->getStyle('K' . $rowNum2)->getNumberFormat()->setFormatCode('#,##0');

        // Sub-rekap 1: Pengelompokan Berdasarkan Sumber Dana (Dari Uang Apa)
        $rowNum2 += 2;
        $sheet2->setCellValue('A' . $rowNum2, 'RINGKASAN PENGELUARAN BERDASARKAN SUMBER DANA (DARI UANG APA):');
        $sheet2->getStyle('A' . $rowNum2)->getFont()->setBold(true)->setSize(11);
        $rowNum2++;

        $sheet2->setCellValue('A' . $rowNum2, 'No');
        $sheet2->setCellValue('B' . $rowNum2, 'Sumber Dana (Dari Uang Apa)');
        $sheet2->mergeCells("B{$rowNum2}:E{$rowNum2}");
        $sheet2->setCellValue('F' . $rowNum2, 'Total Pengeluaran (Rp)');
        $sheet2->mergeCells("F{$rowNum2}:H{$rowNum2}");
        $sheet2->setCellValue('I' . $rowNum2, '% Dari Total');
        $sheet2->mergeCells("I{$rowNum2}:K{$rowNum2}");
        $sheet2->getStyle("A{$rowNum2}:K{$rowNum2}")->getFont()->setBold(true);
        $sheet2->getStyle("A{$rowNum2}:K{$rowNum2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');

        $bySumber = $expensesList->groupBy(fn($item) => $item->sumber_pos ?: 'Kas Umum');
        $sNo = 1;
        $rowNum2++;
        foreach ($bySumber as $sKey => $items) {
            $sTot = (float) $items->sum('nominal');
            $sPct = $sumKeluar2 > 0 ? round(($sTot / $sumKeluar2) * 100, 1) : 0;
            $sLabel = OperationalExpense::SUMBER_POS_LIST[$sKey] ?? $sKey;

            $sheet2->setCellValue('A' . $rowNum2, $sNo++);
            $sheet2->setCellValue('B' . $rowNum2, $sLabel);
            $sheet2->mergeCells("B{$rowNum2}:E{$rowNum2}");
            $sheet2->setCellValue('F' . $rowNum2, $sTot);
            $sheet2->mergeCells("F{$rowNum2}:H{$rowNum2}");
            $sheet2->setCellValue('I' . $rowNum2, $sPct . '%');
            $sheet2->mergeCells("I{$rowNum2}:K{$rowNum2}");
            $sheet2->getStyle('F' . $rowNum2)->getNumberFormat()->setFormatCode('#,##0');
            $sheet2->getStyle('A' . $rowNum2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle('I' . $rowNum2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $rowNum2++;
        }

        // Sub-rekap 2: Pengelompokan Berdasarkan Kategori (Dibuat Apa)
        $rowNum2++;
        $sheet2->setCellValue('A' . $rowNum2, 'RINGKASAN PENGELUARAN BERDASARKAN KATEGORI (DIBUAT APA):');
        $sheet2->getStyle('A' . $rowNum2)->getFont()->setBold(true)->setSize(11);
        $rowNum2++;

        $sheet2->setCellValue('A' . $rowNum2, 'No');
        $sheet2->setCellValue('B' . $rowNum2, 'Kategori Keperluan (Dibuat Apa)');
        $sheet2->mergeCells("B{$rowNum2}:E{$rowNum2}");
        $sheet2->setCellValue('F' . $rowNum2, 'Total Pengeluaran (Rp)');
        $sheet2->mergeCells("F{$rowNum2}:H{$rowNum2}");
        $sheet2->setCellValue('I' . $rowNum2, '% Dari Total');
        $sheet2->mergeCells("I{$rowNum2}:K{$rowNum2}");
        $sheet2->getStyle("A{$rowNum2}:K{$rowNum2}")->getFont()->setBold(true);
        $sheet2->getStyle("A{$rowNum2}:K{$rowNum2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');

        $byKategori = $expensesList->groupBy(fn($item) => $item->kategori ?: 'Lainnya');
        $kNo = 1;
        $rowNum2++;
        foreach ($byKategori as $kKey => $items) {
            $kTot = (float) $items->sum('nominal');
            $kPct = $sumKeluar2 > 0 ? round(($kTot / $sumKeluar2) * 100, 1) : 0;

            $sheet2->setCellValue('A' . $rowNum2, $kNo++);
            $sheet2->setCellValue('B' . $rowNum2, $kKey);
            $sheet2->mergeCells("B{$rowNum2}:E{$rowNum2}");
            $sheet2->setCellValue('F' . $rowNum2, $kTot);
            $sheet2->mergeCells("F{$rowNum2}:H{$rowNum2}");
            $sheet2->setCellValue('I' . $rowNum2, $kPct . '%');
            $sheet2->mergeCells("I{$rowNum2}:K{$rowNum2}");
            $sheet2->getStyle('F' . $rowNum2)->getNumberFormat()->setFormatCode('#,##0');
            $sheet2->getStyle('A' . $rowNum2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle('I' . $rowNum2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $rowNum2++;
        }

        foreach ($cols2 as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }

        // =========================================================================
        // SHEET 3: CATATAN UANG MASUK
        // =========================================================================
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Catatan Uang Masuk');

        $sheet3->setCellValue('A1', 'BUKU CATATAN PENERIMAAN KAS (UANG MASUK SANTRI & PSB)');
        $sheet3->setCellValue('A2', 'PONDOK PESANTREN HIDAYATULLAH TUKSONGO');
        $infoMasuk = 'Periode: ' . date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate));
        if ($jenjang) {
            $infoMasuk .= ' | Jenjang: ' . $jenjang;
        }
        $sheet3->setCellValue('A3', $infoMasuk);
        $sheet3->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);

        $headers3 = ['No', 'No. Transaksi', 'Tanggal', 'Nama Santri / Pembayar', 'Jenjang', 'Kelas', 'Alokasi Sumber Pos / Pos Biaya', 'Keterangan Item', 'Metode Bayar', 'Nominal (Rp)'];
        $cols3 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

        foreach ($headers3 as $idx => $h) {
            $sheet3->setCellValue($cols3[$idx] . '5', $h);
            $sheet3->getStyle($cols3[$idx] . '5')->getFont()->setBold(true);
            $sheet3->getStyle($cols3[$idx] . '5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD1FAE5');
        }

        $paymentsQuery = StudentPayment::with(['student', 'items'])
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'Ditolak');
            });
        if ($jenjang) {
            $paymentsQuery->whereHas('student', function ($sq) use ($jenjang) {
                $sq->where('jenjang', 'like', "%{$jenjang}%");
            });
        }
        $paymentsList = $paymentsQuery->orderBy('tanggal_bayar')->get();

        $rowNum3 = 6;
        $no3 = 1;
        $sumMasuk3 = 0;

        foreach ($paymentsList as $p) {
            foreach ($p->items as $it) {
                $sheet3->setCellValue('A' . $rowNum3, $no3++);
                $sheet3->setCellValue('B' . $rowNum3, $p->no_transaksi);
                $sheet3->setCellValue('C' . $rowNum3, optional($p->tanggal_bayar)->format('d/m/Y'));
                $sheet3->setCellValue('D' . $rowNum3, $p->student?->nama_lengkap ?: '-');
                $sheet3->setCellValue('E' . $rowNum3, $p->student?->jenjang ?: '-');
                $sheet3->setCellValue('F' . $rowNum3, $p->student?->kelas ?: '-');
                $sheet3->setCellValue('G' . $rowNum3, $it->pos_biaya);
                $sheet3->setCellValue('H' . $rowNum3, $it->keterangan ?: ($p->catatan ?: '-'));
                $sheet3->setCellValue('I' . $rowNum3, $p->metode_pembayaran ?: 'Kas Tunai');
                $sheet3->setCellValue('J' . $rowNum3, $it->nominal);
                $sheet3->getStyle('J' . $rowNum3)->getNumberFormat()->setFormatCode('#,##0');
                $sheet3->getStyle('A' . $rowNum3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet3->getStyle('C' . $rowNum3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sumMasuk3 += (float) $it->nominal;
                $rowNum3++;
            }
        }

        // Data PSB
        $psbQuery = PsbRegistration::whereBetween('tanggal_bayar', [$startDate, $endDate])->where('status_pembayaran', 'Lunas');
        if ($jenjang) {
            $psbQuery->where('jenjang', 'like', "%{$jenjang}%");
        }
        $psbList = $psbQuery->get();

        foreach ($psbList as $pb) {
            $nomPsb = $pb->nominal_pembayaran ?: 3225000;
            $sheet3->setCellValue('A' . $rowNum3, $no3++);
            $sheet3->setCellValue('B' . $rowNum3, $pb->nomor_pendaftaran ?: 'PSB-' . $pb->id);
            $sheet3->setCellValue('C' . $rowNum3, $pb->tanggal_bayar ? date('d/m/Y', strtotime($pb->tanggal_bayar)) : '-');
            $sheet3->setCellValue('D' . $rowNum3, $pb->nama_lengkap . ' (Santri Baru PSB)');
            $sheet3->setCellValue('E' . $rowNum3, $pb->jenjang ?: '-');
            $sheet3->setCellValue('F' . $rowNum3, 'Calon Santri Baru');
            $sheet3->setCellValue('G' . $rowNum3, 'UANG PANGKAL / PSB');
            $sheet3->setCellValue('H' . $rowNum3, 'Pendaftaran Santri Baru & Biaya Masuk');
            $sheet3->setCellValue('I' . $rowNum3, $pb->metode_pembayaran ?: 'Transfer Bank');
            $sheet3->setCellValue('J' . $rowNum3, $nomPsb);
            $sheet3->getStyle('J' . $rowNum3)->getNumberFormat()->setFormatCode('#,##0');
            $sheet3->getStyle('A' . $rowNum3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet3->getStyle('C' . $rowNum3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sumMasuk3 += (float) $nomPsb;
            $rowNum3++;
        }

        // Total Kas Masuk
        $sheet3->setCellValue('A' . $rowNum3, 'TOTAL KAS MASUK');
        $sheet3->mergeCells("A{$rowNum3}:I{$rowNum3}");
        $sheet3->setCellValue('J' . $rowNum3, $sumMasuk3);
        $sheet3->getStyle("A{$rowNum3}:J{$rowNum3}")->getFont()->setBold(true);
        $sheet3->getStyle("A{$rowNum3}:J{$rowNum3}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF6EE7B7');
        $sheet3->getStyle('J' . $rowNum3)->getNumberFormat()->setFormatCode('#,##0');

        foreach ($cols3 as $col) {
            $sheet3->getColumnDimension($col)->setAutoSize(true);
        }

        // Set active sheet back to Sheet 1
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Laporan_Kas_Masuk_dan_Keluar_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tempPath = tempnam(sys_get_temp_dir(), 'cashflow_');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Ekspor Khusus Buku Catatan Kas Keluar (Beban Operasional) ke Excel (.xlsx)
     * Kolom memuat secara rinci:
     * - No
     * - No. BKK (Nomor Bukti Kas Keluar)
     * - Tanggal Keluar
     * - Jenjang Sekolah (MTs / MA / Bersama)
     * - Sumber Dana (Dari Uang Apa: Uang Makan, Syahriyah, SOT, Tabungan, Kas Umum, dll)
     * - Digunakan Untuk (Dibuat Apa / Keperluan Lengkap)
     * - Kategori Pengeluaran
     * - Rincian / Catatan Belanja
     * - Penerima Dana / Vendor
     * - Metode Kas
     * - Nominal Terpakai (Rp)
     * 
     * Serta Sheet 2: Rekapitulasi Saldo Sumber Pos Dana & Alokasi Pemakaian
     */
    public function exportPengeluaranExcel(Request $request)
    {
        $kategoriFilter = $request->input('kategori');
        $sumberPosFilter = $request->input('sumber_pos');
        $jenjang = $request->input('jenjang');
        if ($jenjang === 'all' || $jenjang === 'Semua') {
            $jenjang = null;
        }
        $metodeFilter = $request->input('metode_kas');
        $bulanFilter = $request->input('bulan');
        $tahunFilter = $request->input('tahun');
        $search = $request->input('search');

        $query = OperationalExpense::query();

        if (!empty($kategoriFilter)) {
            $query->where('kategori', $kategoriFilter);
        }

        if (!empty($sumberPosFilter) && $sumberPosFilter !== 'all') {
            $query->where('sumber_pos', $sumberPosFilter);
        }

        if (!empty($jenjang)) {
            $query->where('jenjang', $jenjang);
        }

        if (!empty($metodeFilter)) {
            $query->where('metode_kas', $metodeFilter);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->whereBetween('tanggal_keluar', [$startDate, $endDate]);
        } elseif (!empty($tahunFilter) && !empty($bulanFilter) && $bulanFilter !== 'all') {
            $startDate = sprintf('%04d-%02d-01', (int) $tahunFilter, (int) $bulanFilter);
            $endDate = date('Y-m-t', strtotime($startDate));
            $query->whereBetween('tanggal_keluar', [$startDate, $endDate]);
        } elseif (!empty($tahunFilter)) {
            $startDate = sprintf('%04d-01-01', (int) $tahunFilter);
            $endDate = sprintf('%04d-12-31', (int) $tahunFilter);
            $query->whereBetween('tanggal_keluar', [$startDate, $endDate]);
        } else {
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('no_referensi', 'like', "%{$search}%")
                    ->orWhere('judul_pengeluaran', 'like', "%{$search}%")
                    ->orWhere('catatan', 'like', "%{$search}%")
                    ->orWhere('penerima_dana', 'like', "%{$search}%");
            });
        }

        $expensesList = $query->orderBy('tanggal_keluar', 'desc')->orderBy('id', 'desc')->get();

        $spreadsheet = new Spreadsheet();

        // -------------------------------------------------------------
        // SHEET 1: BUKU KAS KELUAR (DETAIL PENGELUARAN)
        // -------------------------------------------------------------
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Buku Kas Keluar');

        $sheet1->setCellValue('A1', 'BUKU CATATAN PENGELUARAN KAS (UANG KELUAR)');
        $sheet1->setCellValue('A2', 'PONDOK PESANTREN HIDAYATULLAH TUKSONGO');
        $infoText = 'Periode: ' . date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate));
        if ($jenjang) $infoText .= ' | Jenjang: ' . $jenjang;
        if ($sumberPosFilter) $infoText .= ' | Sumber Pos: ' . $sumberPosFilter;
        if ($kategoriFilter) $infoText .= ' | Kategori: ' . $kategoriFilter;
        $infoText .= ' | Dicetak: ' . date('d/m/Y H:i') . ' WIB';
        $sheet1->setCellValue('A3', $infoText);
        $sheet1->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);

        $headers = [
            'No',
            'No. BKK',
            'Tanggal Keluar',
            'Jenjang Sekolah',
            'Sumber Dana (Dari Uang Apa)',
            'Digunakan Untuk (Dibuat Apa)',
            'Kategori Pengeluaran',
            'Rincian / Catatan Belanja',
            'Penerima Dana / Vendor',
            'Metode Kas',
            'Nominal Terpakai (Rp)'
        ];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

        foreach ($headers as $idx => $h) {
            $sheet1->setCellValue($cols[$idx] . '5', $h);
            $sheet1->getStyle($cols[$idx] . '5')->getFont()->setBold(true);
            $sheet1->getStyle($cols[$idx] . '5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');
            $sheet1->getStyle($cols[$idx] . '5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        }

        $rowNum = 6;
        $no = 1;
        $totalNominal = 0;

        foreach ($expensesList as $exp) {
            $sumberLabel = OperationalExpense::SUMBER_POS_LIST[$exp->sumber_pos] ?? ($exp->sumber_pos ?: 'Kas Umum Pesantren');

            $sheet1->setCellValue('A' . $rowNum, $no++);
            $sheet1->setCellValue('B' . $rowNum, $exp->no_referensi);
            $sheet1->setCellValue('C' . $rowNum, optional($exp->tanggal_keluar)->format('d/m/Y'));
            $sheet1->setCellValue('D' . $rowNum, $exp->jenjang ?: 'Semua (Bersama)');
            $sheet1->setCellValue('E' . $rowNum, $sumberLabel);
            $sheet1->setCellValue('F' . $rowNum, $exp->judul_pengeluaran);
            $sheet1->setCellValue('G' . $rowNum, $exp->kategori);
            $sheet1->setCellValue('H' . $rowNum, $exp->catatan ?: '-');
            $sheet1->setCellValue('I' . $rowNum, $exp->penerima_dana ?: '-');
            $sheet1->setCellValue('J' . $rowNum, $exp->metode_kas);
            $sheet1->setCellValue('K' . $rowNum, $exp->nominal);

            $sheet1->getStyle('K' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $sheet1->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle('C' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $totalNominal += (float) $exp->nominal;
            $rowNum++;
        }

        // Total Row
        $sheet1->setCellValue('A' . $rowNum, 'TOTAL KAS KELUAR TERPAKAI');
        $sheet1->mergeCells("A{$rowNum}:J{$rowNum}");
        $sheet1->setCellValue('K' . $rowNum, $totalNominal);
        $sheet1->getStyle("A{$rowNum}:K{$rowNum}")->getFont()->setBold(true);
        $sheet1->getStyle("A{$rowNum}:K{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFCA5A5');
        $sheet1->getStyle('K' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

        // Sub-rekap 1: Pengelompokan Berdasarkan Sumber Dana (Dari Uang Apa)
        $rowNum += 2;
        $sheet1->setCellValue('A' . $rowNum, 'RINGKASAN PENGELUARAN BERDASARKAN SUMBER DANA (DARI UANG APA):');
        $sheet1->getStyle('A' . $rowNum)->getFont()->setBold(true)->setSize(11);
        $rowNum++;

        $sheet1->setCellValue('A' . $rowNum, 'No');
        $sheet1->setCellValue('B' . $rowNum, 'Sumber Dana (Dari Uang Apa)');
        $sheet1->mergeCells("B{$rowNum}:E{$rowNum}");
        $sheet1->setCellValue('F' . $rowNum, 'Total Pengeluaran (Rp)');
        $sheet1->mergeCells("F{$rowNum}:H{$rowNum}");
        $sheet1->setCellValue('I' . $rowNum, '% Dari Total');
        $sheet1->mergeCells("I{$rowNum}:K{$rowNum}");
        $sheet1->getStyle("A{$rowNum}:K{$rowNum}")->getFont()->setBold(true);
        $sheet1->getStyle("A{$rowNum}:K{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');

        $bySumber = $expensesList->groupBy(fn($item) => $item->sumber_pos ?: 'Kas Umum');
        $sNo = 1;
        $rowNum++;
        foreach ($bySumber as $sKey => $items) {
            $sTot = (float) $items->sum('nominal');
            $sPct = $totalNominal > 0 ? round(($sTot / $totalNominal) * 100, 1) : 0;
            $sLabel = OperationalExpense::SUMBER_POS_LIST[$sKey] ?? $sKey;

            $sheet1->setCellValue('A' . $rowNum, $sNo++);
            $sheet1->setCellValue('B' . $rowNum, $sLabel);
            $sheet1->mergeCells("B{$rowNum}:E{$rowNum}");
            $sheet1->setCellValue('F' . $rowNum, $sTot);
            $sheet1->mergeCells("F{$rowNum}:H{$rowNum}");
            $sheet1->setCellValue('I' . $rowNum, $sPct . '%');
            $sheet1->mergeCells("I{$rowNum}:K{$rowNum}");
            $sheet1->getStyle('F' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $sheet1->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle('I' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $rowNum++;
        }

        // Sub-rekap 2: Pengelompokan Berdasarkan Kategori (Dibuat Apa)
        $rowNum++;
        $sheet1->setCellValue('A' . $rowNum, 'RINGKASAN PENGELUARAN BERDASARKAN KATEGORI (DIBUAT APA):');
        $sheet1->getStyle('A' . $rowNum)->getFont()->setBold(true)->setSize(11);
        $rowNum++;

        $sheet1->setCellValue('A' . $rowNum, 'No');
        $sheet1->setCellValue('B' . $rowNum, 'Kategori Keperluan (Dibuat Apa)');
        $sheet1->mergeCells("B{$rowNum}:E{$rowNum}");
        $sheet1->setCellValue('F' . $rowNum, 'Total Pengeluaran (Rp)');
        $sheet1->mergeCells("F{$rowNum}:H{$rowNum}");
        $sheet1->setCellValue('I' . $rowNum, '% Dari Total');
        $sheet1->mergeCells("I{$rowNum}:K{$rowNum}");
        $sheet1->getStyle("A{$rowNum}:K{$rowNum}")->getFont()->setBold(true);
        $sheet1->getStyle("A{$rowNum}:K{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');

        $byKategori = $expensesList->groupBy(fn($item) => $item->kategori ?: 'Lainnya');
        $kNo = 1;
        $rowNum++;
        foreach ($byKategori as $kKey => $items) {
            $kTot = (float) $items->sum('nominal');
            $kPct = $totalNominal > 0 ? round(($kTot / $totalNominal) * 100, 1) : 0;

            $sheet1->setCellValue('A' . $rowNum, $kNo++);
            $sheet1->setCellValue('B' . $rowNum, $kKey);
            $sheet1->mergeCells("B{$rowNum}:E{$rowNum}");
            $sheet1->setCellValue('F' . $rowNum, $kTot);
            $sheet1->mergeCells("F{$rowNum}:H{$rowNum}");
            $sheet1->setCellValue('I' . $rowNum, $kPct . '%');
            $sheet1->mergeCells("I{$rowNum}:K{$rowNum}");
            $sheet1->getStyle('F' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $sheet1->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle('I' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $rowNum++;
        }

        foreach ($cols as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }

        // -------------------------------------------------------------
        // SHEET 2: REKAP SALDO SUMBER POS DANA
        // -------------------------------------------------------------
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Rekap Saldo Sumber Pos Dana');

        $sheet2->setCellValue('A1', 'REKAPITULASI KETERSEDIAAN SALDO PER SUMBER POS DANA');
        $sheet2->setCellValue('A2', 'PONDOK PESANTREN HIDAYATULLAH TUKSONGO');
        $sheet2->setCellValue('A3', 'Data Akumulasi Kas Masuk vs Kas Keluar Seluruh Pos');
        $sheet2->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);

        $headers2 = ['No', 'Sumber Pos Dana', 'Keterangan Pos / Dari Uang Apa', 'Total Uang Masuk (Rp)', 'Total Terpakai / Keluar (Rp)', 'Sisa Saldo Tersedia (Rp)', '% Terpakai', 'Status Saldo'];
        $cols2 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        foreach ($headers2 as $idx => $h) {
            $sheet2->setCellValue($cols2[$idx] . '5', $h);
            $sheet2->getStyle($cols2[$idx] . '5')->getFont()->setBold(true);
            $sheet2->getStyle($cols2[$idx] . '5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');
        }

        $sumberBalances = self::calculateSumberDanaBalances($jenjang);
        $r2 = 6;
        $no2 = 1;
        $totM = 0; $totK = 0; $totS = 0;

        foreach ($sumberBalances as $key => $b) {
            $pct = $b['masuk'] > 0 ? round(($b['keluar'] / $b['masuk']) * 100, 1) : 0;
            $status = $b['saldo'] > 0 ? 'Tersedia' : ($b['saldo'] < 0 ? 'Defisit' : 'Habis');

            $sheet2->setCellValue('A' . $r2, $no2++);
            $sheet2->setCellValue('B' . $r2, $b['key']);
            $sheet2->setCellValue('C' . $r2, $b['label']);
            $sheet2->setCellValue('D' . $r2, $b['masuk']);
            $sheet2->setCellValue('E' . $r2, $b['keluar']);
            $sheet2->setCellValue('F' . $r2, $b['saldo']);
            $sheet2->setCellValue('G' . $r2, $pct . '%');
            $sheet2->setCellValue('H' . $r2, $status);

            $sheet2->getStyle('D' . $r2 . ':F' . $r2)->getNumberFormat()->setFormatCode('#,##0');
            $sheet2->getStyle('A' . $r2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle('G' . $r2 . ':H' . $r2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $totM += $b['masuk'];
            $totK += $b['keluar'];
            $totS += $b['saldo'];
            $r2++;
        }

        $sheet2->setCellValue('A' . $r2, 'TOTAL SEMUA SUMBER POS DANA');
        $sheet2->mergeCells("A{$r2}:C{$r2}");
        $sheet2->setCellValue('D' . $r2, $totM);
        $sheet2->setCellValue('E' . $r2, $totK);
        $sheet2->setCellValue('F' . $r2, $totS);
        $sheet2->setCellValue('G' . $r2, $totM > 0 ? round(($totK / $totM) * 100, 1) . '%' : '0%');
        $sheet2->setCellValue('H' . $r2, $totS >= 0 ? 'Surplus Kas' : 'Defisit');
        $sheet2->getStyle("A{$r2}:H{$r2}")->getFont()->setBold(true);
        $sheet2->getStyle("A{$r2}:H{$r2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFCBD5E1');
        $sheet2->getStyle("D{$r2}:F{$r2}")->getNumberFormat()->setFormatCode('#,##0');

        foreach ($cols2 as $c) {
            $sheet2->getColumnDimension($c)->setAutoSize(true);
        }

        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Buku_Kas_Keluar_PP_Hidayatullah_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tempPath = tempnam(sys_get_temp_dir(), 'kas_keluar_');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }
}

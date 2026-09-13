<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperationalExpense;
use App\Models\StudentPayment;
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
        $metodeFilter = $request->input('metode_kas');
        $bulanFilter = $request->input('bulan', date('m'));
        $tahunFilter = $request->input('tahun', date('Y'));
        $search = $request->input('search');

        $query = OperationalExpense::with('user');

        if (!empty($kategoriFilter)) {
            $query->where('kategori', $kategoriFilter);
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

        // Statistik
        $totalBulanIni = (float) OperationalExpense::whereMonth('tanggal_keluar', date('m'))
            ->whereYear('tanggal_keluar', date('Y'))
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

        return view('admin.pembayaran.pengeluaran', compact(
            'expenses',
            'kategoriList',
            'kategoriFilter',
            'metodeFilter',
            'bulanFilter',
            'tahunFilter',
            'search',
            'totalBulanIni',
            'totalTahunIni',
            'totalTransaksiBulanIni',
            'topKategori'
        ));
    }

    /**
     * Simpan Pengeluaran Kas Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
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

        // 2. Kas Masuk PSB dalam rentang tanggal
        $psbPayments = PsbRegistration::whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->where('status_pembayaran', 'Lunas')
            ->get();

        $totalMasukPsb = (float) $psbPayments->sum(function($p) {
            return $p->nominal_pembayaran ?: 3225000;
        });

        $totalKasMasuk = $totalMasukSantri + $totalMasukPsb;

        // 3. Kas Keluar Operasional dalam rentang tanggal
        $expenses = OperationalExpense::whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->orderBy('tanggal_keluar', 'desc')
            ->get();

        $totalKasKeluar = (float) $expenses->sum('nominal');

        // 4. Saldo Kas Bersih (Net Cashflow / Surplus-Defisit)
        $saldoKasBersih = $totalKasMasuk - $totalKasKeluar;

        // 5. Rincian Pengeluaran per Kategori
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

        // 6. Rincian Pemasukan per Pos Biaya
        $posPemasukanBreakdown = [];
        foreach ($studentPayments as $sp) {
            foreach ($sp->items as $it) {
                $pos = $it->pos_biaya ?: 'LAINNYA';
                $posPemasukanBreakdown[$pos] = ($posPemasukanBreakdown[$pos] ?? 0) + $it->nominal;
            }
        }
        if ($totalMasukPsb > 0) {
            $posPemasukanBreakdown['PSB (SANTRI BARU)'] = ($posPemasukanBreakdown['PSB (SANTRI BARU)'] ?? 0) + $totalMasukPsb;
        }
        arsort($posPemasukanBreakdown);

        // 7. Kas Keluar Tunai vs Transfer
        $keluarTunai = (float) $expenses->where('metode_kas', 'Kas Tunai')->sum('nominal');
        $keluarBank = (float) $expenses->where('metode_kas', '!=', 'Kas Tunai')->sum('nominal');

        return view('admin.pembayaran.arus_kas', compact(
            'startDate',
            'endDate',
            'totalKasMasuk',
            'totalMasukSantri',
            'totalMasukPsb',
            'totalKasKeluar',
            'saldoKasBersih',
            'kategoriBreakdown',
            'posPemasukanBreakdown',
            'expenses',
            'keluarTunai',
            'keluarBank'
        ));
    }

    /**
     * Ekspor Laporan Arus Kas ke Excel (.xlsx)
     */
    public function exportCashflowExcel(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        // Ambil Data
        $studentPayments = StudentPayment::with(['student', 'items'])
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->where(function ($q) {
                $q->whereNull('status')
                  ->orWhere('status', '!=', 'Ditolak');
            })
            ->get();
        $totalMasukSantri = (float) $studentPayments->sum('nominal');

        $psbPayments = PsbRegistration::whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->where('status_pembayaran', 'Lunas')
            ->get();
        $totalMasukPsb = (float) $psbPayments->sum(fn($p) => $p->nominal_pembayaran ?: 3225000);
        $totalKasMasuk = $totalMasukSantri + $totalMasukPsb;

        $expenses = OperationalExpense::whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->orderBy('tanggal_keluar', 'asc')
            ->get();
        $totalKasKeluar = (float) $expenses->sum('nominal');
        $saldoKasBersih = $totalKasMasuk - $totalKasKeluar;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Arus Kas');

        // Header Title
        $sheet->setCellValue('A1', 'LAPORAN ARUS KAS (CASHFLOW STATEMENT)');
        $sheet->setCellValue('A2', 'PONDOK PESANTREN HIDAYATULLAH TUKSONGO');
        $sheet->setCellValue('A3', 'Periode: ' . date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate)) . ' | Dicetak: ' . date('d/m/Y H:i') . ' WIB');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(13);

        // Ringkasan Total Box
        $sheet->setCellValue('A5', 'RINGKASAN ARUS KAS');
        $sheet->setCellValue('B5', 'NOMINAL (RP)');
        $sheet->getStyle('A5:B5')->getFont()->setBold(true);
        $sheet->getStyle('A5:B5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');

        $sheet->setCellValue('A6', '1. Total Kas Masuk (Pembayaran Santri & PSB)');
        $sheet->setCellValue('B6', $totalKasMasuk);
        $sheet->setCellValue('A7', '2. Total Kas Keluar (Beban Operasional Pesantren)');
        $sheet->setCellValue('B7', $totalKasKeluar);
        $sheet->setCellValue('A8', '3. SISA SALDO KAS BERSIH (NET CASHFLOW)');
        $sheet->setCellValue('B8', $saldoKasBersih);
        $sheet->getStyle('A8:B8')->getFont()->setBold(true);
        $sheet->getStyle('A8:B8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($saldoKasBersih >= 0 ? 'FFDCFCE7' : 'FFFEE2E2');
        $sheet->getStyle('B6:B8')->getNumberFormat()->setFormatCode('#,##0');

        // Tabel Rincian Pengeluaran Kas
        $sheet->setCellValue('A11', 'RINCIAN KAS KELUAR (BEBAN OPERASIONAL)');
        $sheet->getStyle('A11')->getFont()->setBold(true)->setSize(11);

        $headers = ['No', 'No. BKK', 'Tanggal', 'Kategori Pengeluaran', 'Keterangan / Rincian', 'Penerima', 'Metode Kas', 'Nominal (Rp)'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        foreach ($headers as $idx => $h) {
            $sheet->setCellValue($cols[$idx] . '12', $h);
            $sheet->getStyle($cols[$idx] . '12')->getFont()->setBold(true);
            $sheet->getStyle($cols[$idx] . '12')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFCBD5E1');
        }

        $rowNum = 13;
        $no = 1;
        foreach ($expenses as $exp) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $exp->no_referensi);
            $sheet->setCellValue('C' . $rowNum, optional($exp->tanggal_keluar)->format('d/m/Y'));
            $sheet->setCellValue('D' . $rowNum, $exp->kategori);
            $sheet->setCellValue('E' . $rowNum, $exp->judul_pengeluaran);
            $sheet->setCellValue('F' . $rowNum, $exp->penerima_dana ?: '-');
            $sheet->setCellValue('G' . $rowNum, $exp->metode_kas);
            $sheet->setCellValue('H' . $rowNum, $exp->nominal);
            $sheet->getStyle('H' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $rowNum++;
        }

        // Total Kas Keluar Row
        $sheet->setCellValue('A' . $rowNum, 'TOTAL KAS KELUAR');
        $sheet->mergeCells("A{$rowNum}:G{$rowNum}");
        $sheet->setCellValue('H' . $rowNum, $totalKasKeluar);
        $sheet->getStyle("A{$rowNum}:H{$rowNum}")->getFont()->setBold(true);
        $sheet->getStyle("A{$rowNum}:H{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');
        $sheet->getStyle('H' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

        foreach ($cols as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $filename = 'Laporan_Arus_Kas_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tempPath = tempnam(sys_get_temp_dir(), 'cashflow_');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }
}

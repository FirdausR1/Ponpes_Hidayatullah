@extends('admin.layout')

@section('title', 'Laporan Arus Kas (Cashflow Statement) — Pondok Pesantren Hidayatullah')

@section('styles')
<style>
    @media print {
        body { background: #fff !important; font-size: 11px !important; }
        aside, header, #filter-card, .no-print, .ta-btn-primary, .ta-btn-outline, nav { display: none !important; }
        .print-only { display: block !important; }
        .ta-card { border: none !important; box-shadow: none !important; padding: 0 !important; }
        table { width: 100% !important; border-collapse: collapse !important; }
        th, td { border: 1px solid #999 !important; padding: 6px 8px !important; }
    }
</style>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200">Keuangan &amp; Akuntansi</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">Laporan Arus Kas</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Laporan Arus Kas (Cashflow)</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Perbandingan komprehensif antara <strong>Kas Masuk</strong> (pembayaran santri &amp; PSB) dengan <strong>Kas Keluar</strong> (beban operasional pondok) untuk memantau saldo kas riil pesantren.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5 no-print">
            <a href="{{ route('admin.pengeluaran.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-900 transition">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Buku Kas Keluar</span>
            </a>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-900 transition">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / PDF</span>
            </button>
            <a href="{{ route('admin.arusKas.export', request()->query()) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-emerald-700 transition">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Unduh Excel (.xlsx)</span>
            </a>
        </div>
    </div>

    <!-- Print Header Only (Tampak saat cetak) -->
    <div class="hidden print-only mb-6 text-center border-b pb-4">
        <h2 class="text-xl font-bold uppercase tracking-wider">Pondok Pesantren Hidayatullah Tuksongo</h2>
        <p class="text-xs text-gray-600">Jl. Raya Tuksongo, Kec. Borobudur, Kab. Magelang / Temanggung, Jawa Tengah</p>
        <h3 class="text-base font-bold text-gray-900 mt-2 underline">LAPORAN ARUS KAS (CASHFLOW STATEMENT)</h3>
        <p class="text-xs text-gray-500">
            Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }} • Dicetak: {{ date('d/m/Y H:i') }} WIB oleh {{ auth()->user()->name ?? 'Bendahara' }}
        </p>
    </div>

    <!-- FILTER RENTANG PERIODE -->
    <div id="filter-card" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs no-print">
        <form method="GET" action="{{ route('admin.arusKas.index') }}" class="flex flex-col sm:flex-row sm:items-end gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="h-10 px-3 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="h-10 px-3 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="h-10 px-5 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition">
                    Terapkan Periode
                </button>
                <a href="{{ route('admin.arusKas.index', ['start_date' => date('Y-m-01'), 'end_date' => date('Y-m-t')]) }}" class="h-10 px-3.5 rounded-lg border border-gray-300 bg-white text-gray-700 text-xs font-medium flex items-center hover:bg-gray-50 transition" title="Set ke Bulan Ini">
                    Bulan Ini
                </a>
                <a href="{{ route('admin.arusKas.index', ['start_date' => date('Y-01-01'), 'end_date' => date('Y-12-31')]) }}" class="h-10 px-3.5 rounded-lg border border-gray-300 bg-white text-gray-700 text-xs font-medium flex items-center hover:bg-gray-50 transition" title="Set ke Tahun Ini">
                    Tahun Ini
                </a>
            </div>
        </form>
    </div>

    <!-- 3 KARTU UTAMA ARUS KAS (INFLOW vs OUTFLOW = NET BALANCE) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Card 1: Total Kas Masuk -->
        <div class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-white to-emerald-50/40 p-5 sm:p-6 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </span>
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Total Kas Masuk (Inflow)</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">PEMASUKAN</span>
            </div>
            <div class="text-3xl font-bold font-mono text-emerald-700">
                Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-emerald-100 flex items-center justify-between text-xs text-gray-600">
                <span>Santri Aktif: <strong>Rp {{ number_format($totalMasukSantri, 0, ',', '.') }}</strong></span>
                <span>PSB: <strong>Rp {{ number_format($totalMasukPsb, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        <!-- Card 2: Total Kas Keluar -->
        <div class="rounded-2xl border border-rose-200 bg-gradient-to-br from-white to-rose-50/40 p-5 sm:p-6 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    </span>
                    <span class="text-xs font-bold text-rose-800 uppercase tracking-wider">Total Kas Keluar (Outflow)</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">PENGELUARAN</span>
            </div>
            <div class="text-3xl font-bold font-mono text-rose-600">
                Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t border-rose-100 flex items-center justify-between text-xs text-gray-600">
                <span>Kas Tunai: <strong>Rp {{ number_format($keluarTunai, 0, ',', '.') }}</strong></span>
                <span>Transfer Bank: <strong>Rp {{ number_format($keluarBank, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        <!-- Card 3: Saldo Kas Bersih (Surplus / Defisit) -->
        <div class="rounded-2xl border {{ $saldoKasBersih >= 0 ? 'border-blue-200 bg-gradient-to-br from-white to-blue-50/40' : 'border-rose-300 bg-rose-50' }} p-5 sm:p-6 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg {{ $saldoKasBersih >= 0 ? 'bg-blue-100 text-blue-700' : 'bg-rose-200 text-rose-800' }} flex items-center justify-center font-bold text-xs">
                        =
                    </span>
                    <span class="text-xs font-bold {{ $saldoKasBersih >= 0 ? 'text-blue-800' : 'text-rose-900' }} uppercase tracking-wider">Saldo Bersih (Net Cashflow)</span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $saldoKasBersih >= 0 ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-200 text-rose-900 border border-rose-300' }}">
                    {{ $saldoKasBersih >= 0 ? 'SURPLUS KAS' : 'DEFISIT KAS' }}
                </span>
            </div>
            <div class="text-3xl font-bold font-mono {{ $saldoKasBersih >= 0 ? 'text-blue-700' : 'text-rose-700' }}">
                Rp {{ number_format($saldoKasBersih, 0, ',', '.') }}
            </div>
            <div class="mt-3 pt-3 border-t {{ $saldoKasBersih >= 0 ? 'border-blue-100' : 'border-rose-200' }} text-xs {{ $saldoKasBersih >= 0 ? 'text-blue-600' : 'text-rose-800' }} font-medium">
                {{ $saldoKasBersih >= 0 ? '✓ Sisa saldo kas riil pesantren dalam kondisi surplus.' : '⚠ Pengeluaran melebihi kas masuk pada periode ini.' }}
            </div>
        </div>
    </div>

    <!-- 2 KOLOM: RINCIAN SUMBER PEMASUKAN VS RINCIAN BEBAN PENGELUARAN -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Kolom Kiri: Rincian Kas Masuk -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>Sumber Pemasukan Kas (Inflow)</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ count($posPemasukanBreakdown) }} Pos
                        </span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Rincian pendapatan berdasarkan pos pembayaran santri &amp; PSB</p>
                </div>
            </div>
            <div class="p-4 max-h-80 overflow-y-auto space-y-3">
                @forelse($posPemasukanBreakdown as $pos => $nom)
                @php
                    $pctIn = $totalKasMasuk > 0 ? round(($nom / $totalKasMasuk) * 100, 1) : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-semibold text-gray-800">{{ $pos }}</span>
                        <div class="text-right">
                            <span class="font-mono font-bold text-gray-900">Rp {{ number_format($nom, 0, ',', '.') }}</span>
                            <span class="text-[10px] text-gray-400 font-mono ml-1">({{ $pctIn }}%)</span>
                        </div>
                    </div>
                    <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $pctIn }}%"></div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-xs text-gray-400">
                    Tidak ada catatan kas masuk pada rentang tanggal ini.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Kas Keluar per Kategori -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>Penggunaan Kas Keluar (Beban)</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            {{ $kategoriBreakdown->count() }} Kategori
                        </span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Alokasi belanja operasional pondok dalam periode ini</p>
                </div>
            </div>
            <div class="p-4 max-h-80 overflow-y-auto space-y-3">
                @forelse($kategoriBreakdown as $item)
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <div class="flex items-center gap-2 truncate max-w-xs">
                            <span class="font-semibold text-gray-800 truncate">{{ $item['kategori'] }}</span>
                            <span class="text-[10px] text-gray-400">({{ $item['count'] }}x)</span>
                        </div>
                        <div class="text-right whitespace-nowrap">
                            <span class="font-mono font-bold text-rose-600">Rp {{ number_format($item['total'], 0, ',', '.') }}</span>
                            <span class="text-[10px] text-gray-400 font-mono ml-1">({{ $item['persentase'] }}%)</span>
                        </div>
                    </div>
                    <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full bg-rose-500 rounded-full" style="width: {{ $item['persentase'] }}%"></div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-xs text-gray-400">
                    Tidak ada catatan pengeluaran pada rentang tanggal ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- TABEL RINCIAN PENGELUARAN KAS PADA PERIODE INI -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Rincian Transaksi Pengeluaran Kas Periode Ini</h3>
                <p class="text-xs text-gray-500 mt-0.5">Detail bukti kas keluar yang dicairkan dalam rentang tanggal terpilih</p>
            </div>
            <span class="font-mono font-bold text-xs text-rose-600">
                Total: Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 w-10 text-center">No</th>
                        <th class="px-4 py-3">No. BKK</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Kategori Beban</th>
                        <th class="px-4 py-3">Rincian / Keterangan</th>
                        <th class="px-4 py-3">Penerima</th>
                        <th class="px-4 py-3 text-center">Metode</th>
                        <th class="px-4 py-3 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($expenses as $idx => $e)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-center text-gray-500 font-mono">{{ $idx + 1 }}</td>
                        <td class="px-4 py-3 font-mono font-bold text-gray-900 whitespace-nowrap">{{ $e->no_referensi }}</td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ optional($e->tanggal_keluar)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $e->kategori }}</td>
                        <td class="px-4 py-3 text-gray-700 max-w-sm">{{ $e->judul_pengeluaran }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $e->penerima_dana ?: '—' }}</td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $e->metode_kas === 'Kas Tunai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                {{ $e->metode_kas }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-mono font-bold text-rose-600 whitespace-nowrap">
                            Rp {{ number_format($e->nominal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-400">
                            Tidak ada pengeluaran kas dalam rentang tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($expenses->isNotEmpty())
                <tfoot class="bg-gray-50 font-bold border-t border-gray-200">
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-right text-gray-700 uppercase tracking-wider text-[11px]">TOTAL KAS KELUAR:</td>
                        <td class="px-4 py-3 text-right font-mono font-bold text-rose-600 text-sm">
                            Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>
@endsection

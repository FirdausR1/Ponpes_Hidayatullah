@extends('admin.layout')

@section('title', 'Laporan Arus Kas (Cashflow Statement) — Pondok Pesantren Hidayatullah')

@section('styles')
<style>
    @media print {
        body { background: #fff !important; font-size: 10px !important; }
        aside, header, #filter-card, .no-print, .ta-btn-primary, .ta-btn-outline, nav { display: none !important; }
        .print-only { display: block !important; }
        .ta-card { border: none !important; box-shadow: none !important; padding: 0 !important; }
        table { width: 100% !important; border-collapse: collapse !important; }
        th, td { border: 1px solid #999 !important; padding: 4px 6px !important; }
    }
</style>
@endsection

@section('content')
<div class="space-y-6" x-data="{ tabMasuk: 'semua' }">

    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200">Keuangan &amp; Akuntansi</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">Laporan Arus Kas</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Laporan Arus Kas (Cashflow Statement)</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Laporan komprehensif kas masuk &amp; kas keluar yang <strong>dipisah per jenjang sekolah (MTs vs MA)</strong> serta total konsolidasinya.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5 no-print">
            <a href="{{ route('admin.pengeluaran.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-900 transition">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Buku Kas Keluar</span>
            </a>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-900 transition cursor-pointer">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / PDF</span>
            </button>
            <a href="{{ route('admin.arusKas.export', request()->query()) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-emerald-700 transition" title="Unduh Catatan Laporan Uang Masuk, Uang Keluar, dan Rekapitulasi Saldo Sumber Dana dalam format Excel">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Unduh Laporan Masuk &amp; Keluar (.xlsx)</span>
            </a>
        </div>
    </div>

    <!-- Print Header Only (Tampak saat cetak) -->
    <div class="hidden print-only mb-6 text-center border-b pb-4">
        <h2 class="text-xl font-bold uppercase tracking-wider">Pondok Pesantren Hidayatullah Tuksongo</h2>
        <p class="text-xs text-gray-600">Dusun Tuksongo, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah</p>
        <h3 class="text-base font-bold text-gray-900 mt-2 underline">LAPORAN ARUS KAS TERPADU (MTs &amp; MA)</h3>
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
                <button type="submit" class="h-10 px-5 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition cursor-pointer">
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

    <!-- 3 KARTU UTAMA ARUS KAS (INFLOW vs OUTFLOW = NET BALANCE DENGAN BREAKDOWN MTs & MA) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Card 1: Total Kas Masuk -->
        <div class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-white to-emerald-50/40 p-5 sm:p-6 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </span>
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Total Kas Masuk</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">PEMASUKAN</span>
            </div>
            <div class="text-3xl font-bold font-mono text-emerald-700">
                Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}
            </div>
            <!-- Pisah MTs dan MA -->
            <div class="mt-3 pt-3 border-t border-emerald-100 space-y-1.5 text-xs">
                <div class="flex justify-between items-center text-gray-700">
                    <span class="flex items-center gap-1.5 font-medium"><span class="w-2.5 h-2.5 rounded-full bg-sky-500 inline-block"></span> Masuk MTs:</span>
                    <span class="font-mono font-bold text-sky-800">Rp {{ number_format($totalMasukMts, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-700">
                    <span class="flex items-center gap-1.5 font-medium"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Masuk MA:</span>
                    <span class="font-mono font-bold text-emerald-800">Rp {{ number_format($totalMasukMa, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Kas Keluar -->
        <div class="rounded-2xl border border-rose-200 bg-gradient-to-br from-white to-rose-50/40 p-5 sm:p-6 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    </span>
                    <span class="text-xs font-bold text-rose-800 uppercase tracking-wider">Total Kas Keluar</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">PENGELUARAN</span>
            </div>
            <div class="text-3xl font-bold font-mono text-rose-600">
                Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
            </div>
            <!-- Pisah MTs, MA, dan Bersama -->
            <div class="mt-3 pt-3 border-t border-rose-100 space-y-1.5 text-xs">
                <div class="flex justify-between items-center text-gray-700">
                    <span class="flex items-center gap-1.5 font-medium"><span class="w-2.5 h-2.5 rounded-full bg-sky-500 inline-block"></span> Beban MTs:</span>
                    <span class="font-mono font-bold text-gray-800">Rp {{ number_format($keluarMts, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-700">
                    <span class="flex items-center gap-1.5 font-medium"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Beban MA:</span>
                    <span class="font-mono font-bold text-gray-800">Rp {{ number_format($keluarMa, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-700">
                    <span class="flex items-center gap-1.5 font-medium"><span class="w-2.5 h-2.5 rounded-full bg-purple-500 inline-block"></span> Bersama/Umum:</span>
                    <span class="font-mono font-bold text-gray-800">Rp {{ number_format($keluarBersama, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Saldo Kas Bersih (Surplus / Defisit) -->
        <div class="rounded-2xl border {{ $saldoKasBersih >= 0 ? 'border-blue-200 bg-gradient-to-br from-white to-blue-50/40' : 'border-rose-300 bg-rose-50' }} p-5 sm:p-6 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg {{ $saldoKasBersih >= 0 ? 'bg-blue-100 text-blue-700' : 'bg-rose-200 text-rose-800' }} flex items-center justify-center font-bold text-xs">
                        =
                    </span>
                    <span class="text-xs font-bold {{ $saldoKasBersih >= 0 ? 'text-blue-800' : 'text-rose-900' }} uppercase tracking-wider">Saldo Kas Bersih</span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $saldoKasBersih >= 0 ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-200 text-rose-900 border border-rose-300' }}">
                    {{ $saldoKasBersih >= 0 ? 'SURPLUS KAS' : 'DEFISIT KAS' }}
                </span>
            </div>
            <div class="text-3xl font-bold font-mono {{ $saldoKasBersih >= 0 ? 'text-blue-700' : 'text-rose-700' }}">
                Rp {{ number_format($saldoKasBersih, 0, ',', '.') }}
            </div>
            <!-- Pisah Net MTs dan MA -->
            <div class="mt-3 pt-3 border-t {{ $saldoKasBersih >= 0 ? 'border-blue-100' : 'border-rose-200' }} space-y-1.5 text-xs">
                <div class="flex justify-between items-center text-gray-700">
                    <span class="font-medium">Net Jenjang MTs:</span>
                    <span class="font-mono font-bold {{ $saldoBersihMts >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                        Rp {{ number_format($saldoBersihMts, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-gray-700">
                    <span class="font-medium">Net Jenjang MA:</span>
                    <span class="font-mono font-bold {{ $saldoBersihMa >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                        Rp {{ number_format($saldoBersihMa, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TABEL REKAPITULASI ARUS KAS PER JENJANG SEKOLAH (MTs vs MA vs TOTAL)     -->
    <!-- ========================================================================= -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <span>Rekapitulasi Arus Kas Per Jenjang Sekolah</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">MTs &bull; MA &bull; Total</span>
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Pemisahan arus kas masuk, keluar, dan saldo bersih per unit madrasah</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead class="bg-gray-100 text-gray-700 uppercase text-[10px] tracking-wider font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3">Unit / Jenjang Sekolah</th>
                        <th class="px-5 py-3 text-right">Kas Masuk Santri (Rp)</th>
                        <th class="px-5 py-3 text-right">Kas Masuk PSB (Rp)</th>
                        <th class="px-5 py-3 text-right bg-emerald-50/70 text-emerald-900 font-black">Total Kas Masuk (Rp)</th>
                        <th class="px-5 py-3 text-right bg-rose-50/70 text-rose-900 font-black">Total Kas Keluar (Rp)</th>
                        <th class="px-5 py-3 text-right bg-blue-50/70 text-blue-900 font-black">Saldo Kas Bersih (Rp)</th>
                        <th class="px-5 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Baris MTs -->
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="px-5 py-3.5 font-bold text-gray-900">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-md bg-sky-500 inline-block"></span>
                                <div>
                                    <span class="block">MTs (Madrasah Tsanawiyah)</span>
                                    <span class="text-[10px] text-gray-400 font-normal">Santri Kelas 7, 8, 9</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono text-gray-800">
                            Rp {{ number_format($masukSantriMts, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono text-gray-800">
                            Rp {{ number_format($masukPsbMts, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-700 bg-emerald-50/30">
                            Rp {{ number_format($totalMasukMts, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 bg-rose-50/30">
                            Rp {{ number_format($keluarMts, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono font-bold {{ $saldoBersihMts >= 0 ? 'text-emerald-700 bg-emerald-50/40' : 'text-rose-700 bg-rose-50/40' }}">
                            Rp {{ number_format($saldoBersihMts, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $saldoBersihMts >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $saldoBersihMts >= 0 ? 'Surplus' : 'Defisit' }}
                            </span>
                        </td>
                    </tr>

                    <!-- Baris MA -->
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="px-5 py-3.5 font-bold text-gray-900">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-md bg-emerald-500 inline-block"></span>
                                <div>
                                    <span class="block">MA (Madrasah Aliyah)</span>
                                    <span class="text-[10px] text-gray-400 font-normal">Santri Kelas 10, 11, 12</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono text-gray-800">
                            Rp {{ number_format($masukSantriMa, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono text-gray-800">
                            Rp {{ number_format($masukPsbMa, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-700 bg-emerald-50/30">
                            Rp {{ number_format($totalMasukMa, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 bg-rose-50/30">
                            Rp {{ number_format($keluarMa, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono font-bold {{ $saldoBersihMa >= 0 ? 'text-emerald-700 bg-emerald-50/40' : 'text-rose-700 bg-rose-50/40' }}">
                            Rp {{ number_format($saldoBersihMa, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $saldoBersihMa >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $saldoBersihMa >= 0 ? 'Surplus' : 'Defisit' }}
                            </span>
                        </td>
                    </tr>

                    <!-- Baris Operasional Bersama / Pesantren -->
                    <tr class="hover:bg-gray-50/80 transition bg-slate-50/40">
                        <td class="px-5 py-3.5 font-bold text-gray-700">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-md bg-purple-500 inline-block"></span>
                                <div>
                                    <span class="block">Operasional Bersama / Umum</span>
                                    <span class="text-[10px] text-gray-400 font-normal">Listrik PLN, Sarana Gabungan &amp; Kasir</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono text-gray-400">—</td>
                        <td class="px-5 py-3.5 text-right font-mono text-gray-400">—</td>
                        <td class="px-5 py-3.5 text-right font-mono text-gray-400 bg-emerald-50/30">—</td>
                        <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 bg-rose-50/30">
                            Rp {{ number_format($keluarBersama, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 bg-rose-50/40">
                            - Rp {{ number_format($keluarBersama, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600">
                                Bersama
                            </span>
                        </td>
                    </tr>
                </tbody>

                <!-- FOOTER TOTAL KONSOLIDASI GABUNGAN -->
                <tfoot class="bg-gray-900 text-white font-black text-xs border-t-2 border-gray-900">
                    <tr>
                        <td class="px-5 py-4 tracking-wider uppercase">
                            TOTAL KONSOLIDASI (SEMUA JENJANG)
                        </td>
                        <td class="px-5 py-4 text-right font-mono">
                            Rp {{ number_format($totalMasukSantri, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-right font-mono">
                            Rp {{ number_format($totalMasukPsb, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-right font-mono text-emerald-300">
                            Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-right font-mono text-rose-300">
                            Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-right font-mono text-sm {{ $saldoKasBersih >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            Rp {{ number_format($saldoKasBersih, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black {{ $saldoKasBersih >= 0 ? 'bg-emerald-500 text-white' : 'bg-rose-600 text-white' }}">
                                {{ $saldoKasBersih >= 0 ? 'SURPLUS' : 'DEFISIT' }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- 2 KOLOM: RINCIAN SUMBER PEMASUKAN VS RINCIAN BEBAN PENGELUARAN -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Kolom Kiri: Rincian Kas Masuk dengan Tab Jenjang -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>Sumber Pemasukan Kas (Inflow)</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Rincian pendapatan berdasarkan pos pembayaran santri &amp; PSB</p>
                </div>
                <!-- Filter Tab Jenjang -->
                <div class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-0.5 text-xs font-semibold">
                    <button type="button" @click="tabMasuk = 'semua'" :class="tabMasuk === 'semua' ? 'bg-white shadow-xs text-gray-900' : 'text-gray-500 hover:text-gray-900'" class="px-2.5 py-1 rounded-md transition cursor-pointer">
                        Semua
                    </button>
                    <button type="button" @click="tabMasuk = 'mts'" :class="tabMasuk === 'mts' ? 'bg-white shadow-xs text-sky-700' : 'text-gray-500 hover:text-gray-900'" class="px-2.5 py-1 rounded-md transition cursor-pointer">
                        MTs
                    </button>
                    <button type="button" @click="tabMasuk = 'ma'" :class="tabMasuk === 'ma' ? 'bg-white shadow-xs text-emerald-700' : 'text-gray-500 hover:text-gray-900'" class="px-2.5 py-1 rounded-md transition cursor-pointer">
                        MA
                    </button>
                </div>
            </div>

            <!-- Tab 1: Semua -->
            <div x-show="tabMasuk === 'semua'" class="p-4 max-h-80 overflow-y-auto space-y-3">
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

            <!-- Tab 2: MTs -->
            <div x-show="tabMasuk === 'mts'" x-cloak class="p-4 max-h-80 overflow-y-auto space-y-3">
                @forelse($posPemasukanMts as $pos => $nom)
                @php
                    $pctMts = $totalMasukMts > 0 ? round(($nom / $totalMasukMts) * 100, 1) : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-semibold text-gray-800">{{ $pos }}</span>
                        <div class="text-right">
                            <span class="font-mono font-bold text-sky-800">Rp {{ number_format($nom, 0, ',', '.') }}</span>
                            <span class="text-[10px] text-gray-400 font-mono ml-1">({{ $pctMts }}%)</span>
                        </div>
                    </div>
                    <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full bg-sky-500 rounded-full" style="width: {{ $pctMts }}%"></div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-xs text-gray-400">
                    Tidak ada pemasukan untuk jenjang MTs pada rentang tanggal ini.
                </div>
                @endforelse
            </div>

            <!-- Tab 3: MA -->
            <div x-show="tabMasuk === 'ma'" x-cloak class="p-4 max-h-80 overflow-y-auto space-y-3">
                @forelse($posPemasukanMa as $pos => $nom)
                @php
                    $pctMa = $totalMasukMa > 0 ? round(($nom / $totalMasukMa) * 100, 1) : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-semibold text-gray-800">{{ $pos }}</span>
                        <div class="text-right">
                            <span class="font-mono font-bold text-emerald-800">Rp {{ number_format($nom, 0, ',', '.') }}</span>
                            <span class="text-[10px] text-gray-400 font-mono ml-1">({{ $pctMa }}%)</span>
                        </div>
                    </div>
                    <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $pctMa }}%"></div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-xs text-gray-400">
                    Tidak ada pemasukan untuk jenjang MA pada rentang tanggal ini.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Pengeluaran Kas per Sumber Pos Dana & Kategori -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden" x-data="{ tabKeluar: 'sumber_pos' }">
            <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>Alokasi Kas Keluar (Beban)</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Rincian belanja berdasarkan sumber pos dana dan kategori</p>
                </div>
                <!-- Filter Tab Keluar -->
                <div class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-0.5 text-xs font-semibold">
                    <button type="button" @click="tabKeluar = 'sumber_pos'" :class="tabKeluar === 'sumber_pos' ? 'bg-white shadow-xs text-rose-700' : 'text-gray-500 hover:text-gray-900'" class="px-2.5 py-1 rounded-md transition cursor-pointer">
                        Sumber Dana
                    </button>
                    <button type="button" @click="tabKeluar = 'kategori'" :class="tabKeluar === 'kategori' ? 'bg-white shadow-xs text-gray-900' : 'text-gray-500 hover:text-gray-900'" class="px-2.5 py-1 rounded-md transition cursor-pointer">
                        Kategori Beban
                    </button>
                </div>
            </div>

            <!-- Tab Sumber Pos Dana -->
            <div x-show="tabKeluar === 'sumber_pos'" class="p-4 max-h-80 overflow-y-auto space-y-3">
                @forelse($sumberPosBreakdown as $sp)
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <div class="flex items-center gap-2 truncate max-w-xs">
                            <span class="font-semibold text-gray-800 truncate">{{ $sp['sumber_pos'] }}</span>
                            <span class="text-[10px] text-gray-400">({{ $sp['count'] }}x)</span>
                        </div>
                        <div class="text-right whitespace-nowrap">
                            <span class="font-mono font-bold text-rose-600">Rp {{ number_format($sp['total'], 0, ',', '.') }}</span>
                            <span class="text-[10px] text-gray-400 font-mono ml-1">({{ $sp['persentase'] }}%)</span>
                        </div>
                    </div>
                    <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: {{ $sp['persentase'] }}%"></div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-xs text-gray-400">
                    Tidak ada catatan pengeluaran pada rentang tanggal ini.
                </div>
                @endforelse
            </div>

            <!-- Tab Kategori Beban -->
            <div x-show="tabKeluar === 'kategori'" x-cloak class="p-4 max-h-80 overflow-y-auto space-y-3">
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
        <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-gray-900">Rincian Transaksi Pengeluaran Kas Periode Ini</h3>
                <p class="text-xs text-gray-500 mt-0.5">Detail bukti kas keluar (BKK) yang dicairkan dalam rentang tanggal terpilih</p>
            </div>
            <span class="font-mono font-bold text-xs text-rose-600">
                Total Kas Keluar: Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 w-10 text-center">No</th>
                        <th class="px-4 py-3">No. BKK</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Jenjang &amp; Sumber Dana</th>
                        <th class="px-4 py-3">Kategori Beban</th>
                        <th class="px-4 py-3">Rincian / Keterangan</th>
                        <th class="px-4 py-3">Penerima</th>
                        <th class="px-4 py-3 text-center">Metode</th>
                        <th class="px-4 py-3 text-right">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($expenses as $idx => $e)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-center text-gray-500 font-mono">{{ $idx + 1 }}</td>
                        <td class="px-4 py-3 font-mono font-bold text-gray-900 whitespace-nowrap">{{ $e->no_referensi }}</td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ optional($e->tanggal_keluar)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $e->jenjang === 'MTs' ? 'bg-sky-50 text-sky-700 border border-sky-200' : ($e->jenjang === 'MA' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-purple-50 text-purple-700 border border-purple-200') }}">
                                {{ $e->jenjang ?: 'Semua' }}
                            </span>
                            <span class="text-[11px] text-gray-500 ml-1">({{ $e->sumber_pos ?: 'Kas Umum' }})</span>
                        </td>
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
                        <td colspan="9" class="py-8 text-center text-gray-400">
                            Tidak ada pengeluaran kas dalam rentang tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($expenses->isNotEmpty())
                <tfoot class="bg-gray-50 font-bold border-t border-gray-200">
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-right text-gray-700 uppercase tracking-wider text-[11px]">TOTAL KAS KELUAR:</td>
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

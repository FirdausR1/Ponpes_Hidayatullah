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
<div class="space-y-6" x-data="{
    tabMasuk: 'semua',
    modalTransfer: false,
    modalSaldoAwal: false,
    saldoAwalForm: {
        tanggal: '{{ $saldoAwalTanggal ?: date('Y-m-d') }}',
        tunai: '{{ $saldoAwalTunai > 0 ? (int)$saldoAwalTunai : '' }}',
        bank: '{{ $saldoAwalBank > 0 ? (int)$saldoAwalBank : '' }}',
        keterangan: '{{ addslashes($saldoAwalKeterangan) }}'
    },
    transferData: {
        tanggal: '{{ date('Y-m-d') }}',
        dari_kas: 'Transfer Bank',
        ke_kas: 'Kas Tunai',
        nominal: '',
        keterangan: ''
    },
    setArah(arah) {
        if (arah === 'bank_ke_tunai') {
            this.transferData.dari_kas = 'Transfer Bank';
            this.transferData.ke_kas = 'Kas Tunai';
        } else {
            this.transferData.dari_kas = 'Kas Tunai';
            this.transferData.ke_kas = 'Transfer Bank';
        }
    }
}">

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
                Laporan komprehensif kas masuk &amp; keluar dipisah <strong>Kas Tunai vs Rekening Bank</strong>, jenjang MTs vs MA, dan fitur mutasi kas.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5 no-print">
            <button type="button" @click="modalTransfer = true" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-indigo-700 transition cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <span>Pindah Dana Antar Kas</span>
            </button>
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

    <!-- ========================================================================= -->
    <!-- KARTU SALDO LIVE: KAS TUNAI (CASH) VS REKENING BANK (TRANSFER)           -->
    <!-- ========================================================================= -->
    <div class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50/40 via-white to-blue-50/40 p-5 sm:p-6 shadow-theme-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-4 border-b border-indigo-100/70">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h2 class="text-sm sm:text-base font-bold text-gray-900 uppercase tracking-wider">
                        Posisi Saldo Kas Real-time Saat Ini (Live Balances)
                    </h2>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Saldo riil fisik di brankas bendahara dan rekening bank resmi pondok (akumulasi seluruh transaksi &amp; mutasi kas).</p>
            </div>
            <div class="flex items-center gap-2 no-print flex-wrap">
                <button type="button" @click="modalSaldoAwal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-emerald-300 hover:bg-emerald-50 text-emerald-800 rounded-lg text-xs font-bold shadow-sm transition cursor-pointer" title="Atur Saldo Kas Awal Pesantren (Cut-Off Pembukuan)">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Atur Saldo Awal (Cut-Off)</span>
                </button>
                <button type="button" @click="modalTransfer = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold shadow-sm transition cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    <span>Pindah Dana Antar Kas</span>
                </button>
            </div>
        </div>

        @if($saldoAwalTunai > 0 || $saldoAwalBank > 0)
        <div class="mb-4 px-3.5 py-2 bg-white/90 border border-emerald-200 rounded-xl flex flex-wrap items-center justify-between gap-2 text-xs text-emerald-950 shadow-2xs">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="font-bold text-emerald-700 flex items-center gap-1">
                    <span>📌</span> Saldo Awal Cut-Off:
                </span>
                <span>Tunai: <strong>Rp {{ number_format($saldoAwalTunai, 0, ',', '.') }}</strong></span>
                <span class="text-gray-300">•</span>
                <span>Bank: <strong>Rp {{ number_format($saldoAwalBank, 0, ',', '.') }}</strong></span>
                <span class="text-gray-300">•</span>
                <span class="text-gray-500">Per: {{ date('d/m/Y', strtotime($saldoAwalTanggal)) }} @if($saldoAwalKeterangan)({{ $saldoAwalKeterangan }})@endif</span>
            </div>
            <button type="button" @click="modalSaldoAwal = true" class="text-emerald-700 font-bold underline hover:text-emerald-900 text-[11px] no-print cursor-pointer">
                Ubah Saldo Awal
            </button>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Kas Tunai (Fisik) -->
            <div class="rounded-xl border border-emerald-200 bg-white p-4 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                            💵
                        </span>
                        <div>
                            <span class="text-xs font-bold text-emerald-900 block">KAS TUNAI (CASH FISIK)</span>
                            <span class="text-[10px] text-gray-400">Dompet / Brankas Bendahara</span>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">TUNAI</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black font-mono {{ $saldoKasTunaiKumulatif >= 0 ? 'text-emerald-700' : 'text-rose-600' }} mt-2">
                    Rp {{ number_format($saldoKasTunaiKumulatif, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-gray-500 mt-2.5 pt-2 border-t border-gray-100 space-y-1">
                    <div class="flex justify-between">
                        <span>Pemasukan Tunai Periode:</span>
                        <strong class="font-mono text-emerald-700">Rp {{ number_format($totalMasukTunaiPeriode, 0, ',', '.') }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Pengeluaran Tunai Periode:</span>
                        <strong class="font-mono text-rose-600">Rp {{ number_format($keluarTunai, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Kas Bank (Transfer) -->
            <div class="rounded-xl border border-blue-200 bg-white p-4 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                            🏦
                        </span>
                        <div>
                            <span class="text-xs font-bold text-blue-900 block">KAS BANK (TRANSFER)</span>
                            <span class="text-[10px] text-gray-400">Rekening Resmi Pesantren</span>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">TRANSFER</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black font-mono {{ $saldoKasBankKumulatif >= 0 ? 'text-blue-700' : 'text-rose-600' }} mt-2">
                    Rp {{ number_format($saldoKasBankKumulatif, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-gray-500 mt-2.5 pt-2 border-t border-gray-100 space-y-1">
                    <div class="flex justify-between">
                        <span>Transfer Masuk Periode:</span>
                        <strong class="font-mono text-blue-700">Rp {{ number_format($totalMasukBankPeriode, 0, ',', '.') }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Transfer Keluar Periode:</span>
                        <strong class="font-mono text-rose-600">Rp {{ number_format($keluarBank, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Total Gabungan Seluruh Kas -->
            <div class="rounded-xl border border-indigo-200 bg-gradient-to-br from-indigo-700 to-indigo-900 text-white p-4 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center font-bold text-sm">
                            ⚖️
                        </span>
                        <div>
                            <span class="text-xs font-bold text-white block">TOTAL SALDO LIKUID</span>
                            <span class="text-[10px] text-indigo-200">Kas Tunai + Kas Bank</span>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/20 text-white">KONSOLIDASI</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black font-mono text-white mt-2">
                    Rp {{ number_format($totalSaldoKumulatif, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-indigo-200 mt-2.5 pt-2 border-t border-white/20 space-y-1">
                    <div class="flex justify-between">
                        <span>Mutasi Bank ➔ Tunai Periode:</span>
                        <strong class="font-mono text-emerald-300">Rp {{ number_format($mutasiBankKeTunaiPeriode, 0, ',', '.') }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Mutasi Tunai ➔ Bank Periode:</span>
                        <strong class="font-mono text-sky-300">Rp {{ number_format($mutasiTunaiKeBankPeriode, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
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
                <div class="flex justify-between items-center text-gray-700 pt-2 border-t border-dashed border-emerald-200">
                    <span class="flex items-center gap-1.5 font-bold text-emerald-950"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span> Penerimaan SOT:</span>
                    <div class="text-right">
                        <span class="font-mono font-black text-emerald-900">Rp {{ number_format($totalSotMasuk ?? 0, 0, ',', '.') }}</span>
                        <div class="text-[10px] text-gray-500 font-mono">
                            MTs: Rp {{ number_format($totalSotMasukMts ?? 0, 0, ',', '.') }} &bull; MA: Rp {{ number_format($totalSotMasukMa ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center text-gray-700 pt-1.5 border-t border-dashed border-emerald-200">
                    <span class="flex items-center gap-1.5 font-bold text-emerald-950"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500 inline-block"></span> Kenaikan Kelas:</span>
                    <div class="text-right">
                        <span class="font-mono font-black text-emerald-900">Rp {{ number_format($totalKenaikanMasuk ?? 0, 0, ',', '.') }}</span>
                        <div class="text-[10px] text-gray-500 font-mono">
                            MTs: Rp {{ number_format($totalKenaikanMasukMts ?? 0, 0, ',', '.') }} &bull; MA: Rp {{ number_format($totalKenaikanMasukMa ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center text-gray-700 pt-1.5 border-t border-dashed border-emerald-200">
                    <span class="flex items-center gap-1.5 font-bold text-emerald-950"><span class="w-2.5 h-2.5 rounded-full bg-teal-500 inline-block"></span> Pengembangan Pondok:</span>
                    <div class="text-right">
                        <span class="font-mono font-black text-emerald-900">Rp {{ number_format($totalPengembanganMasuk ?? 0, 0, ',', '.') }}</span>
                        <div class="text-[10px] text-gray-500 font-mono">
                            MTs: Rp {{ number_format($totalPengembanganMasukMts ?? 0, 0, ',', '.') }} &bull; MA: Rp {{ number_format($totalPengembanganMasukMa ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
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

    <!-- ========================================================================= -->
    <!-- TABEL MUTASI DANA / PINDAH DANA ANTAR KAS (BANK <-> TUNAI)                -->
    <!-- ========================================================================= -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <span>Riwayat Perpindahan Dana Antar Kas (Mutasi Internal)</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">Bank ⇄ Kas Tunai</span>
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Catatan tarik tunai dari rekening bank atau setor tunai dari kas fisik ke rekening bank</p>
            </div>
            <div class="flex items-center gap-2 no-print">
                <button type="button" @click="modalTransfer = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-theme-xs transition cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Catat Pindah Dana Baru</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead class="bg-gray-100 text-gray-700 uppercase text-[10px] tracking-wider font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 w-10 text-center">No</th>
                        <th class="px-4 py-3">No. Mutasi</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Dari Kas (Asal)</th>
                        <th class="px-4 py-3 text-center">Arah</th>
                        <th class="px-4 py-3">Ke Kas (Tujuan)</th>
                        <th class="px-4 py-3">Keperluan / Keterangan</th>
                        <th class="px-4 py-3 text-right">Nominal (Rp)</th>
                        <th class="px-4 py-3 text-center">Petugas</th>
                        <th class="px-4 py-3 text-center no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($allTransfers as $idx => $trf)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-center text-gray-400 font-mono">{{ $idx + 1 }}</td>
                        <td class="px-4 py-3 font-mono font-bold text-gray-900 whitespace-nowrap">{{ $trf->no_transfer }}</td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $trf->formatted_tanggal }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $trf->dari_kas === 'Transfer Bank' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                {{ $trf->dari_kas }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-gray-400">→</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $trf->ke_kas === 'Kas Tunai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                {{ $trf->ke_kas }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $trf->keterangan ?: 'Mutasi dana internal' }}
                            @if($trf->bukti_file)
                                <a href="{{ asset($trf->bukti_file) }}" target="_blank" class="ml-2 text-indigo-600 underline font-medium text-[11px] no-print">Lihat Bukti</a>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-mono font-bold text-indigo-700 whitespace-nowrap text-sm">
                            Rp {{ number_format($trf->nominal, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center text-gray-500 whitespace-nowrap">
                            {{ $trf->user->name ?? 'Bendahara' }}
                        </td>
                        <td class="px-4 py-3 text-center no-print whitespace-nowrap">
                            <form action="{{ route('admin.arusKas.transferDestroy', $trf->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus mutasi dana ini? Saldo kas akan dikembalikan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 p-1 rounded hover:bg-rose-50 transition cursor-pointer" title="Batalkan Mutasi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-8 text-center text-gray-400 text-xs">
                            Belum ada riwayat mutasi / pemindahan dana antar kas yang dicatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL PINDAH DANA ANTAR KAS (MUTASI KAS)                                  -->
    <!-- ========================================================================= -->
    <div x-show="modalTransfer" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm no-print" @keydown.escape.window="modalTransfer = false">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-gray-100" @click.outside="modalTransfer = false">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                        🔄
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Pindah Dana Antar Kas (Mutasi Internal)</h3>
                        <p class="text-xs text-gray-500">Tarik tunai dari Bank ke Kas Tunai atau sebaliknya</p>
                    </div>
                </div>
                <button type="button" @click="modalTransfer = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold p-1 cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('admin.arusKas.transferStore') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf

                <!-- Pilihan Arah Cepat -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Jenis Perpindahan Dana <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="setArah('bank_ke_tunai')" :class="transferData.dari_kas === 'Transfer Bank' ? 'border-indigo-600 bg-indigo-50/70 text-indigo-900 ring-2 ring-indigo-500/20' : 'border-gray-200 bg-gray-50 text-gray-700 hover:bg-gray-100'" class="p-3 rounded-xl border text-left transition flex flex-col gap-1 cursor-pointer">
                            <span class="text-xs font-bold flex items-center gap-1.5">
                                <span>🏦 ➔ 💵</span>
                                <span>Tarik Tunai</span>
                            </span>
                            <span class="text-[10px] text-gray-500 leading-tight">Dari Rekening Bank ke Kas Tunai Fisik</span>
                        </button>

                        <button type="button" @click="setArah('tunai_ke_bank')" :class="transferData.dari_kas === 'Kas Tunai' ? 'border-indigo-600 bg-indigo-50/70 text-indigo-900 ring-2 ring-indigo-500/20' : 'border-gray-200 bg-gray-50 text-gray-700 hover:bg-gray-100'" class="p-3 rounded-xl border text-left transition flex flex-col gap-1 cursor-pointer">
                            <span class="text-xs font-bold flex items-center gap-1.5">
                                <span>💵 ➔ 🏦</span>
                                <span>Setor Tunai</span>
                            </span>
                            <span class="text-[10px] text-gray-500 leading-tight">Dari Kas Tunai Fisik ke Rekening Bank</span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Dari Kas (Asal) <span class="text-rose-500">*</span></label>
                        <select name="dari_kas" x-model="transferData.dari_kas" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="Transfer Bank">Transfer Bank (Rekening)</option>
                            <option value="Kas Tunai">Kas Tunai (Fisik)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Ke Kas (Tujuan) <span class="text-rose-500">*</span></label>
                        <select name="ke_kas" x-model="transferData.ke_kas" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="Kas Tunai">Kas Tunai (Fisik)</option>
                            <option value="Transfer Bank">Transfer Bank (Rekening)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Tanggal Mutasi <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" x-model="transferData.tanggal" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Nominal (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nominal" x-model="transferData.nominal" min="1" step="1" required placeholder="Contoh: 1500000" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Keperluan / Keterangan Mutasi</label>
                    <input type="text" name="keterangan" x-model="transferData.keterangan" placeholder="Contoh: Tarik tunai dari rekening untuk belanja logistik dapur & sayuran" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Foto Bukti / Struk / Slip ATM (Opsional)</label>
                    <input type="file" name="bukti_file" accept="image/*,application/pdf" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalTransfer = false" class="px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-medium text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-theme-xs transition cursor-pointer flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Konfirmasi Pindah Dana</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL POPUP: ATUR SALDO KAS AWAL (CUT-OFF PEMBUKUAN)                     -->
    <!-- ========================================================================= -->
    <div x-show="modalSaldoAwal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs transition-opacity" style="display: none;">
        <div @click.away="modalSaldoAwal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 relative max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Atur Saldo Awal (Cut-Off Kas)</h3>
                        <p class="text-xs text-gray-500">Inisialisasi posisi saldo awal pembukuan sebelum sistem beroperasi</p>
                    </div>
                </div>
                <button type="button" @click="modalSaldoAwal = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Panduan Praktis Cut-Off -->
            <div class="mb-4 p-3.5 bg-emerald-50/80 border border-emerald-200 rounded-xl text-xs text-emerald-900 space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-emerald-800">
                    <span>💡</span> Metode Cut-Off Akuntansi Standar:
                </div>
                <p class="text-[11px] leading-relaxed text-emerald-800/90">
                    Tidak perlu repot menginput ribuan kuitansi uang masuk/keluar masa lalu satu per satu. Cukup hitung <strong>uang fisik di brankas</strong> dan <strong>saldo rekening bank</strong> per tanggal cut-off. Selanjutnya sistem otomatis menghitung arus kas berjalan.
                </p>
            </div>

            <form action="{{ route('admin.arusKas.saldoAwalStore') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Tanggal Cut-Off / Mulai Berlaku <span class="text-rose-500">*</span></label>
                    <input type="date" name="saldo_awal_tanggal" x-model="saldoAwalForm.tanggal" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    <p class="text-[10px] text-gray-400 mt-1">Tanggal penetapan saldo awal kas pesantren.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Saldo Kas Tunai (Fisik) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-gray-400">Rp</span>
                            <input type="number" name="saldo_awal_kas_tunai" x-model="saldoAwalForm.tunai" min="0" step="1" required placeholder="0" class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold font-mono text-emerald-700 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">Uang riil di brankas/laci bendahara.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Saldo Kas Bank (Rekening) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-gray-400">Rp</span>
                            <input type="number" name="saldo_awal_kas_bank" x-model="saldoAwalForm.bank" min="0" step="1" required placeholder="0" class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold font-mono text-blue-700 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">Saldo rekening koran bank resmi pondok.</p>
                    </div>
                </div>

                <!-- Preview Total Saldo Awal -->
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between text-xs">
                    <span class="font-semibold text-gray-600">Total Modal Saldo Awal:</span>
                    <strong class="text-sm font-black font-mono text-gray-900">
                        Rp <span x-text="(((parseFloat(saldoAwalForm.tunai) || 0) + (parseFloat(saldoAwalForm.bank) || 0))).toLocaleString('id-ID')">0</span>
                    </strong>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Keterangan / Berita Acara</label>
                    <input type="text" name="saldo_awal_keterangan" x-model="saldoAwalForm.keterangan" placeholder="Contoh: Saldo Cut-Off Pembukuan per 1 Maret 2026" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalSaldoAwal = false" class="px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-medium text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-theme-xs transition cursor-pointer flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Saldo Awal</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

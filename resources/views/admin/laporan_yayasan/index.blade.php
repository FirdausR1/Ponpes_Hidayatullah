@extends('admin.layout')

@section('title', 'Laporan Pimpinan Pondok Pesantren')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Pimpinan Pondok Pesantren</h1>
            <p class="text-sm text-gray-500 mt-1">Laporan pertanggungjawaban keuangan berkala Pondok Pesantren Hidayatullah untuk Pimpinan Pondok Pesantren.</p>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('admin.laporanYayasan.cetak', array_merge(request()->all(), ['bulan' => $bulanStr, 'tahun' => $tahunStr])) }}" target="_blank" class="ta-btn-primary">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Cetak Lembar Resmi</span>
            </a>
            <a href="{{ route('admin.arusKas.index') }}" class="ta-btn-outline">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <span>Arus Kas Umum</span>
            </a>
        </div>
    </div>

    <!-- Filter Periode (Bulan & Tahun) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
        <form method="GET" action="{{ route('admin.laporanYayasan.index') }}" class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pilih Bulan</label>
                    <select name="bulan" class="h-10 px-3 rounded-lg border border-gray-300 text-xs font-medium text-gray-800 bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                        @foreach($namaBulanList as $num => $nama)
                            <option value="{{ $num }}" {{ $bulanStr === $num ? 'selected' : '' }}>
                                {{ $num }} — {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pilih Tahun</label>
                    <select name="tahun" class="h-10 px-3 rounded-lg border border-gray-300 text-xs font-medium text-gray-800 bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                        @for($y = 2024; $y <= 2028; $y++)
                            <option value="{{ $y }}" {{ (int)$tahunStr === $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="h-10 px-4 rounded-lg bg-brand-500 text-white text-xs font-semibold hover:bg-brand-600 transition flex items-center gap-2 cursor-pointer shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <span>Tampilkan</span>
                </button>
            </div>

            <div class="flex items-center gap-2 text-xs">
                <span class="text-gray-400">Pilihan Cepat:</span>
                <a href="{{ route('admin.laporanYayasan.index', ['bulan' => date('m'), 'tahun' => date('Y')]) }}" class="px-2.5 py-1.5 rounded-md transition {{ empty(request('benchmark')) && $bulanStr === date('m') && $tahunStr === date('Y') ? 'bg-brand-50 text-brand-600 font-semibold border border-brand-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    Bulan Berjalan ({{ $namaBulanList[date('m')] ?? '' }} {{ date('Y') }})
                </a>
                <a href="{{ route('admin.laporanYayasan.index', ['bulan' => '03', 'tahun' => '2026', 'benchmark' => '1']) }}" class="px-2.5 py-1.5 rounded-md transition {{ request('benchmark') == '1' && $bulanStr === '03' && $tahunStr === '2026' ? 'bg-amber-50 text-amber-700 font-semibold border border-amber-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    Contoh Arsip Cetak (Maret 2026)
                </a>
            </div>
        </form>
    </div>

    <!-- Status Periode Aktif -->
    <div class="flex items-center justify-between px-1">
        <div class="flex items-center gap-2">
            <span class="text-sm font-bold text-gray-800">Periode: {{ $labelBulan }} {{ $tahunStr }}</span>
            <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-medium text-emerald-700 border border-emerald-200">
                Dokumen Pimpinan Pondok Pesantren
            </span>
        </div>
        <span class="text-xs text-gray-400">Posisi per {{ $tglAkhir }} {{ $labelBulan }} {{ $tahunStr }}</span>
    </div>

    <!-- METRICS CARDS (TailAdmin Structure) -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
        <!-- Metric 1: Penerimaan -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-center w-11 h-11 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                </svg>
            </div>
            <div class="mt-4">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block">Total Penerimaan Kas</span>
                <h4 class="mt-1 font-bold text-gray-800 text-xl tracking-tight">Rp {{ number_format($grandTotalPenerimaan, 0, ',', '.') }}</h4>
                <div class="mt-2 text-xs text-gray-500 flex justify-between border-t border-gray-100 pt-2">
                    <span>Tunai: Rp {{ number_format($totPenerimaanTunai, 0, ',', '.') }}</span>
                    <span>Bank: Rp {{ number_format($totPenerimaanTransfer, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Metric 2: Pengeluaran -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-center w-11 h-11 bg-rose-50 text-rose-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                </svg>
            </div>
            <div class="mt-4">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block">Total Pengeluaran Kas</span>
                <h4 class="mt-1 font-bold text-gray-800 text-xl tracking-tight">Rp {{ number_format($grandTotalPengeluaran, 0, ',', '.') }}</h4>
                <div class="mt-2 text-xs text-gray-500 flex justify-between border-t border-gray-100 pt-2">
                    <span>Tunai: Rp {{ number_format($totPengeluaranTunai, 0, ',', '.') }}</span>
                    <span>Bank: Rp {{ number_format($totPengeluaranTransfer, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Metric 3: Saldo Kas Akhir -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-center w-11 h-11 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <div class="mt-4">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block">Saldo Kas Operasional</span>
                <h4 class="mt-1 font-bold text-gray-800 text-xl tracking-tight">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h4>
                <div class="mt-2 text-xs text-gray-500 flex justify-between border-t border-gray-100 pt-2">
                    <span>Awal: Rp {{ number_format($saldoAwal, 0, ',', '.') }}</span>
                    <span>Akhir: Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Metric 4: Sisa Bank -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-center w-11 h-11 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
            </div>
            <div class="mt-4">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block">Sisa di Rekening Bank</span>
                <h4 class="mt-1 font-bold text-gray-800 text-xl tracking-tight">Rp {{ number_format($bankStats['belum_ditarik'], 0, ',', '.') }}</h4>
                <div class="mt-2 text-xs text-gray-500 flex justify-between border-t border-gray-100 pt-2">
                    <span>Masuk: Rp {{ number_format($bankStats['uang_masuk'], 0, ',', '.') }}</span>
                    <span>Tarik: Rp {{ number_format($bankStats['sudah_ditarik'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigasi Seksi Laporan (Clean Tabs) -->
    <div class="flex items-center gap-1 border-b border-gray-200 pb-1 overflow-x-auto text-xs">
        <a href="#section-penerimaan" class="px-4 py-2 font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-brand-500 transition whitespace-nowrap">
            1. Penerimaan
        </a>
        <a href="#section-pengeluaran" class="px-4 py-2 font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-brand-500 transition whitespace-nowrap">
            2. Pengeluaran
        </a>
        <a href="#section-harian" class="px-4 py-2 font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-brand-500 transition whitespace-nowrap">
            3. Kas Harian
        </a>
        <a href="#section-pinjaman" class="px-4 py-2 font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-brand-500 transition whitespace-nowrap">
            4. Pinjaman &amp; Kas
        </a>
        <a href="#section-santri" class="px-4 py-2 font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-brand-500 transition whitespace-nowrap">
            5. Santri &amp; Diagram
        </a>
        <a href="#section-bank" class="px-4 py-2 font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-brand-500 transition whitespace-nowrap">
            6. Rekening Bank
        </a>
    </div>

    <!-- 1. REKAP PENERIMAAN KAS -->
    <div id="section-penerimaan" class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800 text-sm">1. Rekap Penerimaan Bulan {{ $labelBulan }} {{ $tahunStr }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ count($penerimaan) }} transaksi tercatat</p>
            </div>
            <span class="rounded-full bg-slate-100 text-slate-700 text-xs px-2.5 py-0.5 font-medium border border-slate-200">
                Total: Rp {{ number_format($grandTotalPenerimaan, 0, ',', '.') }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/70 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-center w-12">No</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-center w-28">Tanggal</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Keterangan</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-center w-28">Metode</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-right w-36">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($penerimaan as $idx => $p)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-4 py-2.5 text-xs text-center text-gray-400">{{ $idx + 1 }}</td>
                        <td class="px-4 py-2.5 text-xs text-center text-gray-600">{{ $p['tanggal'] }}</td>
                        <td class="px-4 py-2.5 text-xs font-medium text-gray-800">
                            <span class="font-semibold">{{ $p['keterangan'] }}</span>
                            @if(($p['count'] ?? 1) > 1)
                                <span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-normal text-gray-400 bg-gray-100 border border-gray-200" title="{{ $p['count'] }} transaksi digabung pada tanggal ini">
                                    {{ $p['count'] }} transaksi
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 text-xs text-center">
                            @if($p['metode'] === 'Tunai')
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Tunai</span>
                            @else
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-200">Transfer</span>
                            @endif
                        </td>
                        <td class="px-6 py-2.5 text-xs text-right font-medium text-gray-900">
                            Rp {{ number_format($p['jumlah'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-xs text-gray-400">
                            Tidak ada transaksi penerimaan kas yang tercatat untuk bulan {{ $labelBulan }} {{ $tahunStr }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50/80 font-semibold text-gray-800 text-xs border-t border-gray-200">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right">Total Penerimaan:</td>
                        <td class="px-4 py-3 text-center text-[11px] text-gray-500">
                            T: Rp {{ number_format($totPenerimaanTunai, 0, ',', '.') }} | B: Rp {{ number_format($totPenerimaanTransfer, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 text-right text-emerald-700 text-sm font-bold">
                            Rp {{ number_format($grandTotalPenerimaan, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- 2. REKAP PENGELUARAN KAS -->
    <div id="section-pengeluaran" class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800 text-sm">2. Rekap Pengeluaran Bulan {{ $labelBulan }} {{ $tahunStr }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ count($pengeluaran) }} transaksi tercatat</p>
            </div>
            <span class="rounded-full bg-slate-100 text-slate-700 text-xs px-2.5 py-0.5 font-medium border border-slate-200">
                Total: Rp {{ number_format($grandTotalPengeluaran, 0, ',', '.') }}
            </span>
        </div>

        <div class="overflow-x-auto max-h-[480px]">
            <table class="w-full text-left">
                <thead class="bg-gray-50/70 border-b border-gray-200 sticky top-0">
                    <tr>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-center w-12">No</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-center w-28">Tanggal</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Keterangan</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-center w-28">Metode</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-right w-36">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengeluaran as $idx => $k)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-4 py-2.5 text-xs text-center text-gray-400">{{ $idx + 1 }}</td>
                        <td class="px-4 py-2.5 text-xs text-center text-gray-600">{{ $k['tanggal'] }}</td>
                        <td class="px-4 py-2.5 text-xs font-medium text-gray-800">{{ $k['keterangan'] }}</td>
                        <td class="px-4 py-2.5 text-xs text-center">
                            @if($k['metode'] === 'Tunai')
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">Tunai</span>
                            @else
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-200">Transfer</span>
                            @endif
                        </td>
                        <td class="px-6 py-2.5 text-xs text-right font-medium text-gray-900">
                            Rp {{ number_format($k['jumlah'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-xs text-gray-400">
                            Tidak ada transaksi pengeluaran kas yang tercatat untuk bulan {{ $labelBulan }} {{ $tahunStr }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50/80 font-semibold text-gray-800 text-xs border-t border-gray-200 sticky bottom-0">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right">Total Pengeluaran:</td>
                        <td class="px-4 py-3 text-center text-[11px] text-gray-500">
                            T: Rp {{ number_format($totPengeluaranTunai, 0, ',', '.') }} | B: Rp {{ number_format($totPengeluaranTransfer, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 text-right text-rose-700 text-sm font-bold">
                            Rp {{ number_format($grandTotalPengeluaran, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- 3. BUKU KAS HARIAN -->
    <div id="section-harian" class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800 text-sm">3. Laporan Buku Kas Harian Bulan {{ $labelBulan }} {{ $tahunStr }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ count($harian) }} hari kalender berjalan</p>
            </div>
            <span class="rounded-full bg-slate-100 text-slate-700 text-xs px-2.5 py-0.5 font-medium border border-slate-200">
                Saldo Akhir: Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
            </span>
        </div>

        <div class="overflow-x-auto max-h-[480px]">
            <table class="w-full text-left">
                <thead class="bg-gray-50/70 border-b border-gray-200 sticky top-0">
                    <tr>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-center w-12">No</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-center w-28">Tanggal</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-right">Penerimaan</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-right">Pengeluaran</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-right">Saldo Harian</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-right">Saldo Berjalan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($harian as $idx => $h)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-4 py-2.5 text-xs text-center text-gray-400">{{ $idx + 1 }}</td>
                        <td class="px-4 py-2.5 text-xs text-center text-gray-600">{{ $h['tgl'] }}</td>
                        <td class="px-4 py-2.5 text-xs text-right {{ $h['masuk'] > 0 ? 'text-emerald-700 font-semibold' : 'text-gray-400' }}">
                            {{ $h['masuk'] > 0 ? 'Rp ' . number_format($h['masuk'], 0, ',', '.') : 'Rp 0' }}
                        </td>
                        <td class="px-4 py-2.5 text-xs text-right {{ $h['keluar'] > 0 ? 'text-rose-700 font-semibold' : 'text-gray-400' }}">
                            {{ $h['keluar'] > 0 ? 'Rp ' . number_format($h['keluar'], 0, ',', '.') : 'Rp 0' }}
                        </td>
                        <td class="px-4 py-2.5 text-xs text-right font-medium {{ $h['saldo_harian'] < 0 ? 'text-rose-600' : ($h['saldo_harian'] > 0 ? 'text-emerald-600' : 'text-gray-400') }}">
                            {{ $h['saldo_harian'] < 0 ? '-Rp ' . number_format(abs($h['saldo_harian']), 0, ',', '.') : 'Rp ' . number_format($h['saldo_harian'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-2.5 text-xs text-right font-bold text-gray-800">
                            Rp {{ number_format($h['saldo'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50/80 font-semibold text-gray-800 text-xs border-t border-gray-200 sticky bottom-0">
                    <tr>
                        <td colspan="2" class="px-4 py-3 text-right">Total Bulan:</td>
                        <td class="px-4 py-3 text-right text-emerald-700">Rp {{ number_format(array_sum(array_column($harian, 'masuk')), 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-rose-700">Rp {{ number_format(array_sum(array_column($harian, 'keluar')), 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format(array_sum(array_column($harian, 'saldo_harian')), 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- 4. REKAP PINJAMAN UANG SOT & SALDO AKHIR -->
    <div id="section-pinjaman" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs space-y-4">
        <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 text-sm">4. Rekap Pinjaman Uang SOT &amp; Posisi Kas Akhir</h3>
            <span class="rounded-full bg-slate-100 text-slate-700 text-xs px-2.5 py-0.5 font-medium border border-slate-200">
                Nihil Pinjaman
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-50/70 border-b border-gray-200 text-xs font-medium text-gray-500">
                    <tr>
                        <th class="px-4 py-2.5 text-center w-12">No</th>
                        <th class="px-4 py-2.5 text-center w-28">Tanggal</th>
                        <th class="px-4 py-2.5">Keterangan Pinjaman</th>
                        <th class="px-4 py-2.5 text-right w-36">Uang Makan</th>
                        <th class="px-6 py-2.5 text-right w-36">Syahriyah</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-700">
                    <tr>
                        <td class="px-4 py-2.5 text-center text-gray-400">-</td>
                        <td class="px-4 py-2.5 text-center text-gray-400">-</td>
                        <td class="px-4 py-2.5 font-medium">Tidak ada pinjaman uang SOT (Nihil)</td>
                        <td class="px-4 py-2.5 text-right">Rp 0</td>
                        <td class="px-6 py-2.5 text-right">Rp 0</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-50/70 border-b border-gray-200 text-xs font-medium text-gray-500">
                    <tr>
                        <th class="px-4 py-2.5 text-center w-12">No</th>
                        <th class="px-4 py-2.5 text-center w-32">Tanggal Acuan</th>
                        <th class="px-4 py-2.5">Uraian Saldo Kas</th>
                        <th class="px-6 py-2.5 text-right w-48">Nominal Saldo</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-800">
                    <tr class="bg-gray-50/50">
                        <td class="px-4 py-2.5 text-center font-bold">1</td>
                        <td class="px-4 py-2.5 text-center">{{ $tglAkhir }} {{ $labelBulan }} {{ $tahunStr }}</td>
                        <td class="px-4 py-2.5 font-medium">Saldo Akhir Kas Operasional Bulan {{ $labelBulan }} {{ $tahunStr }}</td>
                        <td class="px-6 py-2.5 text-right font-bold text-sm text-emerald-700 tracking-tight">
                            Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 5. REKAP PEMBAYARAN SANTRI & DIAGRAM PIE 3D -->
    <div id="section-santri" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div class="border-b border-gray-100 pb-3 mb-5 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 text-sm">5. Rekapitulasi Pembayaran Santri Per Kelas</h3>
            <span class="rounded-full bg-slate-100 text-slate-700 text-xs px-2.5 py-0.5 font-medium border border-slate-200">
                {{ $totalSantriLunas }} dari {{ $grandTotalSantri }} Lunas ({{ round(($totalSantriLunas / ($grandTotalSantri ?: 1)) * 100) }}%)
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <!-- Kolom Tabel -->
            <div class="lg:col-span-7 overflow-x-auto">
                <table class="w-full text-left border border-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50/70 border-b border-gray-200 text-xs uppercase font-medium text-gray-500">
                        <tr>
                            <th class="px-4 py-2.5 text-center w-20">Kelas</th>
                            <th class="px-4 py-2.5 text-center text-emerald-700">Lunas</th>
                            <th class="px-4 py-2.5 text-center text-rose-700">Belum Lunas</th>
                            <th class="px-4 py-2.5 text-center">Jumlah</th>
                            <th class="px-4 py-2.5 text-center">% Lunas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                        @foreach($santriStats as $s)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-2.5 text-center font-bold text-gray-800">Kelas {{ $s['kelas'] }}</td>
                            <td class="px-4 py-2.5 text-center font-semibold text-emerald-700">{{ $s['lunas'] }}</td>
                            <td class="px-4 py-2.5 text-center text-rose-600">{{ $s['belum_lunas'] }}</td>
                            <td class="px-4 py-2.5 text-center">{{ $s['total'] }}</td>
                            <td class="px-4 py-2.5 text-center font-medium">{{ $s['persen_lunas'] }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold text-gray-800 text-xs border-t border-gray-200">
                        <tr>
                            <td class="px-4 py-2.5 text-center">JUMLAH</td>
                            <td class="px-4 py-2.5 text-center text-emerald-700">{{ $totalSantriLunas }}</td>
                            <td class="px-4 py-2.5 text-center text-rose-700">{{ $totalSantriBelum }}</td>
                            <td class="px-4 py-2.5 text-center">{{ $grandTotalSantri }}</td>
                            <td class="px-4 py-2.5 text-center">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Kolom Diagram Pie 3D -->
            <div class="lg:col-span-5 rounded-xl border border-gray-200 bg-gray-50/40 p-4 text-center">
                <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Jumlah Santri yang Sudah Lunas</h4>
                <div class="flex justify-center my-2">
                    {!! $pieChartSvg !!}
                </div>
                <p class="text-[11px] text-gray-400 mt-1">
                    Distribusi rasio kelunasan santri dari total {{ $totalSantriLunas }} santri yang telah lunas pada periode ini.
                </p>
            </div>
        </div>
    </div>

    <!-- 6. LAPORAN PENERIMAAN MELALUI TRANSFER BANK -->
    <div id="section-bank" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs space-y-4">
        <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 text-sm">6. Laporan Penerimaan Pembayaran Melalui Transfer Bank</h3>
            <span class="rounded-full bg-slate-100 text-slate-700 text-xs px-2.5 py-0.5 font-medium border border-slate-200">
                Rekonsiliasi Bank
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right border border-gray-200 rounded-xl overflow-hidden">
                <thead class="bg-gray-50/70 border-b border-gray-200 text-xs uppercase font-medium text-gray-500">
                    <tr>
                        <th class="px-4 py-3 w-1/3">Total Uang Masuk Bank</th>
                        <th class="px-4 py-3 w-1/3">Sudah Ditarik ke Kas</th>
                        <th class="px-4 py-3 w-1/3 text-brand-600 bg-brand-50/30">Sisa Saldo Belum Ditarik</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    <tr class="text-sm font-bold tracking-tight">
                        <td class="px-4 py-3 text-gray-800">Rp {{ number_format($bankStats['uang_masuk'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-gray-500">Rp {{ number_format($bankStats['sudah_ditarik'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-brand-600 bg-brand-50/20">Rp {{ number_format($bankStats['belum_ditarik'], 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Lembar Pengesahan -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div class="border-b border-gray-100 pb-3 mb-6">
            <h3 class="font-bold text-gray-800 text-sm">Lembar Pengesahan Laporan Keuangan</h3>
            <p class="text-xs text-gray-500">Pejabat berwenang Pondok Pesantren Hidayatullah</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 max-w-2xl mx-auto text-center">
            <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/40">
                <p class="text-xs font-semibold text-gray-600">Dibuat Oleh:</p>
                <div class="h-16"></div>
                <p class="text-sm font-bold text-gray-800 underline">{{ $pejabat['pembuat_nama'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $pejabat['pembuat_jabatan'] }}</p>
            </div>
            <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/40">
                <p class="text-xs font-semibold text-gray-600">Disetujui Oleh:</p>
                <div class="h-16"></div>
                <p class="text-sm font-bold text-gray-800 underline">{{ $pejabat['pimpinan_nama'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $pejabat['pimpinan_jabatan'] }}</p>
            </div>
        </div>
    </div>

</div>
@endsection

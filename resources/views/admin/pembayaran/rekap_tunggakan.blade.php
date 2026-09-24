@extends('admin.layout')

@section('title', 'Rekapitulasi Tunggakan & Kekurangan Biaya Santri — Pondok Pesantren Hidayatullah')

@section('styles')
<style>
    @media print {
        body { background: #fff !important; font-size: 11px !important; }
        aside, header, #filter-card, .no-print, .ta-btn-primary, .ta-btn-outline, nav { display: none !important; }
        .print-only { display: block !important; }
        .ta-card { border: none !important; box-shadow: none !important; padding: 0 !important; }
        table { width: 100% !important; border-collapse: collapse !important; }
        th, td { border: 1px solid #999 !important; padding: 6px 8px !important; }
        .badge-pos { border: 1px solid #ccc !important; background: transparent !important; color: #000 !important; }
    }
</style>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-rose-50 px-2 py-0.5 text-xs font-semibold text-rose-700 border border-rose-200">Keuangan &amp; Pembayaran</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">Rekapitulasi Keuangan</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Rekap Tunggakan &amp; Kekurangan Santri</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Rekap komprehensif tagihan yang belum lunas: pantau pos biaya terbanyak, kelas menunggak, dan kirim pengingat tagihan via WhatsApp dengan satu klik.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5 no-print">
            <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-emerald-700 transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Buka Kasir POS</span>
            </a>
            <a href="{{ route('admin.pembayaran.tagihan.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-amber-300 bg-amber-50 px-3.5 py-2.5 text-xs font-semibold text-amber-900 shadow-theme-xs hover:bg-amber-100 hover:border-amber-400 transition" title="Input massal data santri & upload tunggakan masa lalu">
                <svg class="w-4 h-4 text-amber-600 fill-current" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M8.5 13.5l2 2.5-2 2.5h1.5l1.25-1.75L12.5 18.5H14l-2-2.5 2-2.5h-1.5l-1.25 1.75L10 13.5H8.5z"/></svg>
                <span>Input / Upload Tunggakan</span>
            </a>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-900 transition">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
            <a href="{{ route('admin.pembayaran.rekapTunggakan.export', request()->query()) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-emerald-700 transition">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Unduh Excel (.xlsx)</span>
            </a>
        </div>
    </div>

    <!-- Print Header Only (Tampak saat cetak) -->
    <div class="hidden print-only mb-6 text-center border-b pb-4">
        <h2 class="text-xl font-bold uppercase tracking-wider">Pondok Pesantren Hidayatullah Tuksongo</h2>
        <p class="text-xs text-gray-600">Dusun Tuksongo, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah</p>
        <h3 class="text-base font-bold text-gray-900 mt-2 underline">LAPORAN REKAPITULASI TUNGGAKAN &amp; KEKURANGAN KEUANGAN SANTRI</h3>
        <p class="text-xs text-gray-500">Dicetak pada: {{ date('d F Y, H:i') }} WIB oleh {{ auth()->user()->name ?? 'Bendahara' }}</p>
    </div>

    <!-- 4 KARTU STATISTIK KEUANGAN -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Kekurangan Aktif -->
        <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Kekurangan Aktif</span>
                <span class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-base">
                    Rp
                </span>
            </div>
            <div class="text-2xl font-bold font-mono text-rose-600">
                Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
            </div>
            <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                <span class="flex items-center gap-1">
                    <span class="inline-block w-2 h-2 rounded-full bg-rose-500"></span>
                    <span>Tingkat Akhir:</span>
                </span>
                <span class="font-mono font-bold text-purple-700">Rp {{ number_format($totalTunggakanTingkatAkhir ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Card 2: Total Uang Masuk (Terbayar) -->
        <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Uang Masuk (Terbayar)</span>
                <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold font-mono text-emerald-600">
                Rp {{ number_format($totalUangMasukGlobal ?? 0, 0, ',', '.') }}
            </div>
            <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                <span class="flex items-center gap-1">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Total Tagihan:</span>
                </span>
                <span class="font-mono font-semibold text-gray-700">Rp {{ number_format($totalTagihanGlobal ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Card 3: Jumlah Santri Menunggak -->
        <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Santri Menunggak</span>
                <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold font-mono text-amber-600">
                {{ number_format($totalSantriMenunggak, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500 font-sans">Santri</span>
            </div>
            <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                <span class="flex items-center gap-1">
                    <span class="inline-block w-2 h-2 rounded-full bg-purple-500"></span>
                    <span>Tingkat Akhir:</span>
                </span>
                <span class="font-bold text-purple-700">{{ $totalSantriTingkatAkhir ?? 0 }} Santri</span>
            </div>
        </div>

        <!-- Card 4: Ditangguhkan ke Wisuda -->
        <div class="rounded-2xl border border-purple-100 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ditangguhkan (Wisuda)</span>
                <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold font-mono text-purple-600">
                Rp {{ number_format($totalDitangguhkan, 0, ',', '.') }}
            </div>
            <div class="mt-2 flex items-center gap-1.5 text-xs text-gray-500">
                <span class="inline-block w-2 h-2 rounded-full bg-purple-500"></span>
                <span>Ditangguhkan pelunasannya saat wisuda</span>
            </div>
        </div>
    </div>

    <!-- QUICK FILTER TABS: TINGKAT AKHIR (KELAS 9 MTs & KELAS 12 MA) -->
    <div class="flex flex-wrap items-center gap-2 no-print">
        <span class="text-xs font-bold text-gray-500 mr-1 flex items-center gap-1.5">
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            <span>Pintasan Angkatan:</span>
        </span>

        <!-- Tab Semua Santri -->
        <a href="{{ route('admin.pembayaran.rekapTunggakan', array_merge(request()->except(['tingkat_akhir', 'page']), [])) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ empty($tingkatAkhirFilter) ? 'bg-gray-900 text-white shadow-xs' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
            <span>Semua Santri</span>
        </a>

        <!-- Tab Tingkat Akhir (9 & 12) -->
        <a href="{{ route('admin.pembayaran.rekapTunggakan', array_merge(request()->except(['page']), ['tingkat_akhir' => 'all_final'])) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $tingkatAkhirFilter === 'all_final' ? 'bg-purple-600 text-white shadow-xs ring-2 ring-purple-300' : 'bg-white border border-purple-200 text-purple-700 hover:bg-purple-50' }}">
            <span>🎓 Siswa Tingkat Akhir (Kelas 9 MTs &amp; 12 MA)</span>
            @if(($totalSantriTingkatAkhir ?? 0) > 0)
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tingkatAkhirFilter === 'all_final' ? 'bg-white text-purple-700 font-bold' : 'bg-purple-100 text-purple-800 font-bold' }}">
                    {{ $totalSantriTingkatAkhir }}
                </span>
            @endif
        </a>

        <!-- Tab Kelas 9 MTs -->
        <a href="{{ route('admin.pembayaran.rekapTunggakan', array_merge(request()->except(['page']), ['tingkat_akhir' => '9_mts'])) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $tingkatAkhirFilter === '9_mts' ? 'bg-sky-600 text-white shadow-xs ring-2 ring-sky-300' : 'bg-white border border-sky-200 text-sky-700 hover:bg-sky-50' }}">
            <span>📘 Kelas 9 MTs (Tingkat Akhir)</span>
        </a>

        <!-- Tab Kelas 12 MA -->
        <a href="{{ route('admin.pembayaran.rekapTunggakan', array_merge(request()->except(['page']), ['tingkat_akhir' => '12_ma'])) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $tingkatAkhirFilter === '12_ma' ? 'bg-emerald-600 text-white shadow-xs ring-2 ring-emerald-300' : 'bg-white border border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
            <span>📗 Kelas 12 MA (Tingkat Akhir)</span>
        </a>
    </div>

    <!-- FILTER & PENCARIAN BAR (TailAdmin Form Style) -->
    <div id="filter-card" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs no-print">
        <form method="GET" action="{{ route('admin.pembayaran.rekapTunggakan') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
            <!-- Search Keyword -->
            <div class="lg:col-span-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Santri / Tagihan</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Ketik nama santri, NIS, atau judul pos..." class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
            </div>

            <!-- Filter Kelas -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Kelas</label>
                <select name="kelas" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-brand-500 cursor-pointer">
                    <option value="">Semua Kelas</option>
                    @foreach($classrooms as $cls)
                        <option value="{{ $cls->nama_kelas }}" {{ $kelasFilter === $cls->nama_kelas ? 'selected' : '' }}>
                            Kelas {{ $cls->nama_kelas }} ({{ $cls->jenjang }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Pos Biaya -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Pos Biaya</label>
                <select name="pos_biaya" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-brand-500 cursor-pointer">
                    <option value="">Semua Pos Biaya</option>
                    @foreach($posBiayaList as $key => $title)
                        <option value="{{ $key }}" {{ $posFilter === $key ? 'selected' : '' }}>
                            {{ $key }} — {{ $title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Tingkat Akhir -->
            <div class="lg:col-span-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Tingkat / Angkatan</label>
                <select name="tingkat_akhir" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-brand-500 cursor-pointer">
                    <option value="">Semua Angkatan Santri</option>
                    <option value="all_final" {{ $tingkatAkhirFilter === 'all_final' ? 'selected' : '' }}>🎓 Khusus Tingkat Akhir (Kls 9 MTs &amp; 12 MA)</option>
                    <option value="9_mts" {{ $tingkatAkhirFilter === '9_mts' ? 'selected' : '' }}>📘 Khusus Kelas 9 MTs</option>
                    <option value="12_ma" {{ $tingkatAkhirFilter === '12_ma' ? 'selected' : '' }}>📗 Khusus Kelas 12 MA</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="lg:col-span-2 flex items-end gap-1.5">
                <button type="submit" class="flex-1 h-10 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition">
                    Filter
                </button>
                <a href="{{ route('admin.pembayaran.rekapTunggakan') }}" class="px-3 h-10 rounded-lg border border-gray-300 bg-white text-gray-600 text-xs font-medium flex items-center justify-center hover:bg-gray-50 transition" title="Reset Filter">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- 2-KOLOM RINGKASAN: REKAP PER POS & REKAP PER KELAS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Kolom Kiri: Rekap Per Pos Biaya -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>Rekapitulasi Per Pos Biaya</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">{{ $rekapPerPos->count() }} Pos</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Rincian per pos: Tagihan, Uang Masuk (Terbayar), dan Sisa Kekurangan</p>
                </div>
            </div>
            <div class="overflow-x-auto max-h-80 overflow-y-auto">
                <table class="w-full text-xs text-left whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] tracking-wider font-semibold sticky top-0 z-10 border-b border-gray-100">
                        <tr>
                            <th class="px-3.5 py-2.5">Pos Biaya</th>
                            <th class="px-2 py-2.5 text-center">Santri</th>
                            <th class="px-3 py-2.5 text-right">Tagihan (Rp)</th>
                            <th class="px-3 py-2.5 text-right text-emerald-700 bg-emerald-50/50">Uang Masuk (Rp)</th>
                            <th class="px-3 py-2.5 text-right text-rose-700 bg-rose-50/50">Kekurangan (Rp)</th>
                            <th class="px-2 py-2.5 text-center">% Lunas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rekapPerPos as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-3.5 py-2.5 font-bold text-gray-900">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800 font-mono text-[11px] font-bold border border-gray-200">{{ $item['pos'] }}</span>
                                    <span class="text-gray-500 font-normal text-[11px] truncate max-w-[150px]" title="{{ $item['pos_label'] }}">{{ $item['pos_label'] }}</span>
                                </div>
                            </td>
                            <td class="px-2 py-2.5 text-center text-gray-600 font-medium">
                                {{ $item['count_santri'] }}
                            </td>
                            <td class="px-3 py-2.5 text-right font-mono text-gray-700 font-medium">
                                Rp {{ number_format($item['total_tagihan'], 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-2.5 text-right font-mono font-bold text-emerald-600 bg-emerald-50/30">
                                Rp {{ number_format($item['total_masuk'], 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-2.5 text-right font-mono font-bold text-rose-600 bg-rose-50/30">
                                Rp {{ number_format($item['total_sisa'], 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-2.5 text-center">
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $item['persen_lunas'] >= 100 ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($item['persen_lunas'] > 0 ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                    {{ $item['persen_lunas'] }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                                Tidak ada data tunggakan untuk pos biaya ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($rekapPerPos->count() > 0)
                    <tfoot class="bg-gray-50 border-t border-gray-200 font-bold text-gray-800 sticky bottom-0">
                        <tr>
                            <td class="px-3.5 py-2.5 uppercase text-[10px] tracking-wider">TOTAL</td>
                            <td class="px-2 py-2.5 text-center font-mono">{{ $totalSantriMenunggak }}</td>
                            <td class="px-3 py-2.5 text-right font-mono">Rp {{ number_format($totalTagihanGlobal, 0, ',', '.') }}</td>
                            <td class="px-3 py-2.5 text-right font-mono text-emerald-700">Rp {{ number_format($totalUangMasukGlobal, 0, ',', '.') }}</td>
                            <td class="px-3 py-2.5 text-right font-mono text-rose-600">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</td>
                            <td class="px-2 py-2.5 text-center font-mono text-[10px] text-emerald-700">
                                {{ $totalTagihanGlobal > 0 ? round(($totalUangMasukGlobal / $totalTagihanGlobal) * 100, 1) : 100 }}%
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- Kolom Kanan: Rekap Per Kelas -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>Rekapitulasi Per Kelas</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">{{ $rekapPerKelas->count() }} Kelas</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Peringkat kelas dengan jumlah kekurangan terbesar</p>
                </div>
            </div>
            <div class="overflow-x-auto max-h-80 overflow-y-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] tracking-wider font-semibold sticky top-0 z-10 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-2.5">Kelas &amp; Jenjang</th>
                            <th class="px-3 py-2.5 text-center">Santri Menunggak</th>
                            <th class="px-4 py-2.5 text-right">Total Kekurangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rekapPerKelas as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2.5 font-bold text-gray-900 flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold border border-blue-200 text-[11px]">Kelas {{ $item['kelas'] }}</span>
                                <span class="text-gray-400 font-normal text-[11px]">({{ $item['jenjang'] }})</span>
                            </td>
                            <td class="px-3 py-2.5 text-center text-gray-600 font-semibold">{{ $item['count_santri'] }} orang</td>
                            <td class="px-4 py-2.5 text-right font-mono font-bold text-rose-600">
                                Rp {{ number_format($item['total_sisa'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-400">
                                Tidak ada data tunggakan untuk kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- REKAPITULASI ARUS UANG MASUK KASIR & BANK PER POS BIAYA -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-emerald-50/20">
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Rekapitulasi Penerimaan Uang Masuk Kas Per Pos Biaya</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">{{ $rekapUangMasukPerPos->count() }} Pos</span>
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Catatan seluruh arus uang masuk pembayaran santri terkelompok per pos biaya resmi (Kas Tunai &amp; Transfer Bank)</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500">Total Masuk Kasir:</span>
                <span class="font-mono font-black text-sm text-emerald-700 bg-emerald-100/80 px-3 py-1 rounded-lg border border-emerald-200">
                    Rp {{ number_format($totalPenerimaanKasir ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="overflow-x-auto max-h-80 overflow-y-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] tracking-wider font-semibold sticky top-0 z-10 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-2.5">Pos Biaya</th>
                        <th class="px-4 py-2.5 text-right">Total Uang Masuk (Rp)</th>
                        <th class="px-3 py-2.5 text-right text-blue-700">Kas Tunai (Rp)</th>
                        <th class="px-3 py-2.5 text-right text-indigo-700">Transfer Bank (Rp)</th>
                        <th class="px-3 py-2.5 text-center">Jml Transaksi</th>
                        <th class="px-3 py-2.5 text-center">Santri Bayar</th>
                        <th class="px-4 py-2.5 text-center no-print">Aksi Laporan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rekapUangMasukPerPos as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2.5 font-bold text-gray-900">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 font-mono text-[11px] font-bold border border-emerald-200">{{ $item['pos'] }}</span>
                                <span class="text-gray-600 font-normal text-xs">{{ $item['pos_label'] }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2.5 text-right font-mono font-black text-emerald-700 text-sm">
                            Rp {{ number_format($item['total_masuk'], 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-2.5 text-right font-mono font-semibold text-blue-700">
                            Rp {{ number_format($item['nominal_tunai'], 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-2.5 text-right font-mono font-semibold text-indigo-700">
                            Rp {{ number_format($item['nominal_bank'], 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-2.5 text-center font-medium text-gray-600">
                            {{ $item['count_transaksi'] }} kuitansi
                        </td>
                        <td class="px-3 py-2.5 text-center font-medium text-gray-600">
                            {{ $item['count_santri'] }} orang
                        </td>
                        <td class="px-4 py-2.5 text-center no-print">
                            <a href="{{ route('admin.pembayaran.jurnal.index') }}" class="text-[11px] text-brand-600 hover:text-brand-800 font-semibold hover:underline">
                                Buka Jurnal &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                            Belum ada catatan uang masuk pada filter yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($rekapUangMasukPerPos->count() > 0)
                <tfoot class="bg-emerald-50/40 border-t border-emerald-200 font-bold text-gray-900 sticky bottom-0">
                    <tr>
                        <td class="px-4 py-2.5 uppercase text-[10px] tracking-wider text-emerald-900">TOTAL ARUS UANG MASUK</td>
                        <td class="px-4 py-2.5 text-right font-mono font-black text-emerald-800 text-sm">Rp {{ number_format($totalPenerimaanKasir, 0, ',', '.') }}</td>
                        <td class="px-3 py-2.5 text-right font-mono font-bold text-blue-800">Rp {{ number_format($rekapUangMasukPerPos->sum('nominal_tunai'), 0, ',', '.') }}</td>
                        <td class="px-3 py-2.5 text-right font-mono font-bold text-indigo-800">Rp {{ number_format($rekapUangMasukPerPos->sum('nominal_bank'), 0, ',', '.') }}</td>
                        <td class="px-3 py-2.5 text-center font-mono font-bold text-gray-700">{{ $rekapUangMasukPerPos->sum('count_transaksi') }}</td>
                        <td class="px-3 py-2.5 text-center font-mono font-bold text-gray-700">-</td>
                        <td class="px-4 py-2.5 no-print"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- TABEL UTAMA: DAFTAR SANTRI MENUNGGAK & RINCIANNYA (MODE RINGKAS & EXPANDABLE) -->
    <div x-data="{
        allExpanded: false,
        openRows: {},
        toggleRow(id) {
            this.openRows[id] = !this.openRows[id];
        },
        isOpen(id) {
            return this.allExpanded || !!this.openRows[id];
        },
        expandAll() {
            this.allExpanded = true;
        },
        collapseAll() {
            this.allExpanded = false;
            this.openRows = {};
        }
    }" class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        
        <!-- Header Tabel & Switch Mode Tampilan -->
        <div class="px-5 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <span>Daftar Santri Menunggak</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">{{ $santriList->total() }} Santri</span>
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Tampilan ringkas &amp; cepat dipantau. Klik tombol <strong>Rincian</strong> untuk melihat detail tagihan per-bulan, atau gunakan tombol buka/tutup semua.</p>
            </div>

            <!-- Kontrol Mode Tampilan -->
            <div class="flex items-center gap-2 no-print shrink-0">
                <div class="inline-flex rounded-lg p-1 bg-gray-100 border border-gray-200 text-xs">
                    <button type="button" 
                            @click="collapseAll()" 
                            :class="!allExpanded ? 'bg-white text-gray-900 shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900'" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md transition text-xs font-medium cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <span>Mode Ringkas</span>
                    </button>
                    <button type="button" 
                            @click="expandAll()" 
                            :class="allExpanded ? 'bg-white text-gray-900 shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900'" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md transition text-xs font-medium cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <span>Buka Semua Detail</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 w-12 text-center">No</th>
                        <th class="px-4 py-3 min-w-[200px]">Data Santri</th>
                        <th class="px-4 py-3 min-w-[140px]">Kontak Wali</th>
                        <th class="px-4 py-3 min-w-[280px]">Bulan &amp; Ringkasan Tagihan</th>
                        <th class="px-4 py-3 text-right min-w-[130px]">Total Tunggakan</th>
                        <th class="px-4 py-3 text-center no-print min-w-[180px]">Aksi Bendahara</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($santriList as $idx => $row)
                    @php
                        $st = $row['student'];
                        $bills = $row['bills'];
                        $totalSisa = $row['total_tunggakan'];
                        $monthlyList = $row['monthly'];
                        $nonMonthlyList = $row['non_monthly'];
                        $bulanCount = $row['bulan_count'];
                        $bulanListText = $row['bulan_list_text'];
                        $isTingkatAkhir = $row['is_tingkat_akhir'];
                        $labelTingkatAkhir = $row['label_tingkat_akhir'];
                        $waNum = $st ? ($st->no_whatsapp ?: ($st->no_hp ?: $st->no_hp_wali)) : null;
                        $cleanWa = $waNum ? preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $waNum)) : '';

                        // Format pesan WhatsApp penagihan yang santun & profesional
                        $tagihanBlocks = [];
                        if ($monthlyList->count() > 0) {
                            $tagihanBlocks[] = "🗓️ *Bulan Yang Menunggak ({$bulanCount} Bulan):*";
                            foreach ($monthlyList as $mIdx => $m) {
                                $subtotalFmt = number_format($m['total'], 0, ',', '.');
                                $tagihanBlocks[] = ($mIdx + 1) . ". *Bulan {$m['periode']}* (Subtotal: Rp {$subtotalFmt}):";
                                foreach ($m['items'] as $item) {
                                    $nomFmt = number_format($item->sisa_tagihan, 0, ',', '.');
                                    $tagihanBlocks[] = "   • {$item->judul_tagihan}: Rp {$nomFmt}";
                                }
                            }
                        }
                        if ($nonMonthlyList->count() > 0) {
                            $tagihanBlocks[] = "\n📌 *Tagihan Kegiatan / Non-Bulanan:*";
                            foreach ($nonMonthlyList as $nm) {
                                $nomFmt = number_format($nm['total'], 0, ',', '.');
                                $tagihanBlocks[] = "   • {$nm['judul']}: Rp {$nomFmt}";
                            }
                        }

                        $tagihanText = implode("\n", $tagihanBlocks);
                        $totalSisaFmt = number_format($totalSisa, 0, ',', '.');
                        $tingkatInfo = $isTingkatAkhir ? " ({$labelTingkatAkhir})" : "";

                        $rekBriNo = \App\Models\Setting::get('rek_admin_bri_no', '010201022009537');
                        $rekBriAn = \App\Models\Setting::get('rek_admin_bri_an', 'DIKY FACHRI HUSEIN');
                        $rekBcaNo = \App\Models\Setting::get('rek_admin_bca_no', '1221220167');
                        $rekBcaAn = \App\Models\Setting::get('rek_admin_bca_an', 'DIKY FACHRI HUSEIN');
                        $rekKonfPhone = \App\Models\Setting::get('rek_admin_konfirmasi_phone', '085290429617');
                        $rekKonfNama = \App\Models\Setting::get('rek_admin_konfirmasi_nama', 'Ustdh. Harsih Nur A');

                        $waMessage = "Assalamu'alaikum Wr. Wb.\n\n"
                            . "Yth. Bapak/Ibu Wali Santri dari ananda *{$st?->nama_lengkap}* (Kelas {$st?->kelas}{$tingkatInfo})\n\n"
                            . "Semoga senantiasa dalam limpahan rahmat dan keberkahan Allah SWT.\n\n"
                            . "Melalui pesan ini, kami dari Bendahara Pondok Pesantren Hidayatullah Tuksongo menginformasikan rekapitulasi administrasi/tagihan yang belum terselesaikan:\n\n"
                            . "{$tagihanText}\n\n"
                            . "━━━━━━━━━━━━━━━━━━━━\n"
                            . "*TOTAL KEKURANGAN: Rp {$totalSisaFmt}*\n"
                            . "━━━━━━━━━━━━━━━━━━━━\n\n"
                            . "Pembayaran dapat dilakukan melalui:\n"
                            . "1. Kasir Kantor Bendahara Pesantren (Tunai)\n"
                            . "2. Transfer ke Rekening Resmi Administrasi Pesantren:\n"
                            . "   • BRI: *{$rekBriNo}* (a.n. {$rekBriAn})\n"
                            . "   • BCA: *{$rekBcaNo}* (a.n. {$rekBcaAn})\n\n"
                            . "KONFIRMASI BUKTI TRANSFER DENGAN MENYERTAKAN DATA SEBAGAI BERIKUT:\n"
                            . "• NAMA : {$st?->nama_lengkap}\n"
                            . "• KELAS : {$st?->kelas}\n"
                            . "• JENIS PEMBAYARAN : Pembayaran Tagihan Administrasi Santri\n"
                            . "• NOMINAL : Rp {$totalSisaFmt}\n\n"
                            . "Kirim bukti transfer ke WA Keuangan: {$rekKonfPhone} ({$rekKonfNama})\n\n"
                            . "⚠️ *Wajib Melakukan Konfirmasi*\n"
                            . "Demi terciptanya komunikasi yang tertib dan menghindari miskomunikasi, setiap keperluan transfer harap selalu diawali dengan konfirmasi kepada pihak terkait.\n\n"
                            . "Atas perhatian dan kerjasamanya kami haturkan jazakumullahu khairan katsiran.\n\n"
                            . "Wassalamu'alaikum Wr. Wb.\n"
                            . "Bendahara Pondok Pesantren Hidayatullah Tuksongo";
                        
                        $waUrl = $cleanWa ? "https://wa.me/{$cleanWa}?text=" . rawurlencode($waMessage) : null;
                        $rowId = $st?->id ?: $idx;
                    @endphp

                    <!-- BARIS RINGKAS (COMPACT ROW) -->
                    <tr class="hover:bg-amber-50/30 transition group" :class="isOpen('{{ $rowId }}') ? 'bg-amber-50/40' : ''">
                        <!-- No -->
                        <td class="px-4 py-3.5 text-center text-gray-500 font-mono align-middle">
                            {{ ($santriList->currentPage() - 1) * $santriList->perPage() + $idx + 1 }}
                        </td>

                        <!-- Data Santri -->
                        <td class="px-4 py-3.5 align-middle">
                            <div class="flex items-center gap-3">
                                @if($st?->foto)
                                    <img src="{{ $st->foto }}" alt="{{ $st->nama_lengkap }}" class="w-9 h-9 rounded-full object-cover border border-gray-200 shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-700 font-bold flex items-center justify-center text-xs border border-emerald-200 shrink-0">
                                        {{ strtoupper(substr($st?->nama_lengkap ?? 'S', 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.siswa.show', $st?->id ?? 0) }}" class="font-bold text-gray-900 hover:text-brand-500 transition block">
                                        {{ $st?->nama_lengkap ?? 'Santri Tidak Ditemukan' }}
                                    </a>
                                    <div class="flex items-center flex-wrap gap-1.5 text-[11px] text-gray-500 mt-0.5">
                                        <span class="font-mono">NIS: {{ $st?->nis ?: '—' }}</span>
                                        <span>•</span>
                                        <span class="px-1.5 py-0.2 rounded bg-blue-50 text-blue-700 font-semibold border border-blue-100">Kelas {{ $st?->kelas }}</span>
                                        @if($isTingkatAkhir)
                                            <span class="px-1.5 py-0.2 rounded bg-purple-100 text-purple-800 font-extrabold text-[10px] border border-purple-200">🎓 {{ $labelTingkatAkhir }}</span>
                                        @endif
                                        @if($st?->kamar_asrama)
                                            <span>•</span>
                                            <span class="text-gray-400">Asrama: {{ $st->kamar_asrama }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Kontak Wali -->
                        <td class="px-4 py-3.5 align-middle whitespace-nowrap">
                            <div class="text-gray-900 font-semibold text-xs">
                                {{ $st?->nama_wali ?: ($st?->ayah_nama ?: ($st?->ibu_nama ?: '—')) }}
                            </div>
                            <div class="text-gray-500 text-[11px] font-mono mt-0.5 flex items-center gap-1">
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span>{{ $waNum ?: '—' }}</span>
                            </div>
                        </td>

                        <!-- Bulan & Ringkasan Tagihan (COMPACT DENGAN TOMBOL EXPAND) -->
                        <td class="px-4 py-3.5 align-middle">
                            <div class="flex items-center flex-wrap gap-1.5">
                                @if($bulanCount > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-50 text-amber-900 border border-amber-300 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>{{ $bulanCount }} Bulan:</span>
                                        <span class="font-normal text-amber-800 text-[10.5px] max-w-[190px] truncate" title="{{ $bulanListText }}">{{ $bulanListText }}</span>
                                    </span>
                                @endif

                                @if($nonMonthlyList->count() > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-semibold bg-sky-50 text-sky-800 border border-sky-200">
                                        <span>📌 +{{ $nonMonthlyList->count() }} Kegiatan</span>
                                    </span>
                                @endif

                                <!-- Tombol Toggle Rincian -->
                                <button type="button" 
                                        @click="toggleRow('{{ $rowId }}')" 
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold transition cursor-pointer border shadow-2xs"
                                        :class="isOpen('{{ $rowId }}') ? 'bg-gray-900 text-white border-gray-900' : 'bg-white hover:bg-gray-100 text-gray-700 border-gray-200'">
                                    <span x-text="isOpen('{{ $rowId }}') ? 'Tutup Rincian' : 'Lihat Rincian'"></span>
                                    <span class="text-[10px] px-1.5 py-0.2 rounded-full font-bold" :class="isOpen('{{ $rowId }}') ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-600'">
                                        {{ $row['item_count'] }} Pos
                                    </span>
                                    <svg class="w-3 h-3 transition-transform duration-200" :class="isOpen('{{ $rowId }}') ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </td>

                        <!-- Total Tunggakan -->
                        <td class="px-4 py-3.5 text-right font-mono font-bold text-sm text-rose-600 whitespace-nowrap align-middle">
                            Rp {{ number_format($totalSisa, 0, ',', '.') }}
                        </td>

                        <!-- Aksi Bendahara -->
                        <td class="px-4 py-3.5 text-center no-print whitespace-nowrap align-middle">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- Tombol WA -->
                                @if($waUrl)
                                    <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-medium transition" title="Kirim Tagihan Otomatis via WhatsApp">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.772.78 2.791.78h.001c3.181 0 5.768-2.587 5.768-5.766.001-3.181-2.585-5.766-5.769-5.766zm3.377 8.21c-.143.403-.834.774-1.157.825-.323.051-.735.084-2.18-.517-1.446-.601-2.385-2.072-2.457-2.168-.072-.096-.583-.777-.583-1.481 0-.704.368-1.05.5-1.193.132-.143.288-.179.384-.179.096 0 .192.001.276.005.09.004.21-.034.329.252.126.3.432 1.05.47 1.128.038.078.064.168.013.269-.051.101-.077.164-.153.253-.077.089-.161.199-.23.267-.077.076-.157.159-.067.313.09.154.4 1.082 1.206 1.8 1.036.924 1.91 1.21 2.181 1.344.271.134.43.117.59-.068.16-.185.688-.802.871-1.076.183-.274.367-.229.617-.137.25.092 1.584.747 1.856.883.272.136.453.204.519.317.066.113.066.657-.077 1.06z"/></svg>
                                        <span>WA Tagihan</span>
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-2 py-1.5 rounded-lg bg-gray-50 text-gray-400 border border-gray-200 text-[11px]" title="Nomor WhatsApp wali belum terdaftar">
                                        No WA (-)
                                    </span>
                                @endif

                                <!-- Tombol Bayar di Kasir -->
                                <a href="{{ route('admin.pembayaran.index', ['santri_id' => $st?->id]) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-semibold shadow-sm transition" title="Buka di Kasir POS">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span>Bayar Kasir</span>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- SUB-ROW DETAIL RINCIAN YANG DAPAT DI-EXPAND (ACCORDION DRAWER) -->
                    <tr x-show="isOpen('{{ $rowId }}')" x-cloak class="bg-amber-50/20 border-b border-gray-200 transition">
                        <td colspan="6" class="p-3 sm:p-5">
                            <div class="rounded-xl border border-amber-200/80 bg-white p-4 sm:p-5 shadow-xs space-y-4">
                                <!-- Header Rincian Santri -->
                                <div class="flex flex-wrap items-center justify-between pb-3 border-b border-gray-100 gap-2">
                                    <div class="flex items-center flex-wrap gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                        <span class="font-bold text-gray-900 text-xs sm:text-sm">Rincian Lengkap Tunggakan: {{ $st?->nama_lengkap }} (Kelas {{ $st?->kelas }})</span>
                                        <span class="px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200 font-mono">Total: Rp {{ number_format($totalSisa, 0, ',', '.') }}</span>
                                        <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[11px] font-medium">{{ $row['item_count'] }} Pos Tagihan</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="toggleRow('{{ $rowId }}')" class="text-xs text-gray-500 hover:text-gray-800 font-medium flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                            <span>Sembunyikan Rincian</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Grid Kartu Tagihan Bulanan -->
                                @if($monthlyList->count() > 0)
                                    <div>
                                        <div class="text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>Tagihan Rutin Bulanan ({{ $monthlyList->count() }} Bulan Menunggak)</span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                                            @foreach($monthlyList as $m)
                                                <div class="rounded-xl border border-amber-200/90 bg-amber-50/15 p-3 flex flex-col justify-between hover:shadow-xs transition">
                                                    <div>
                                                        <div class="flex items-center justify-between font-bold border-b border-amber-100 pb-1.5 mb-2">
                                                            <span class="text-gray-900 text-xs flex items-center gap-1.5">
                                                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                                                <span>Bulan {{ $m['periode'] }}</span>
                                                            </span>
                                                            <span class="font-mono text-xs font-bold text-rose-600">Rp {{ number_format($m['total'], 0, ',', '.') }}</span>
                                                        </div>
                                                        <div class="space-y-1 text-[11px]">
                                                            @foreach($m['items'] as $item)
                                                                <div class="flex items-center justify-between text-gray-600 py-0.5 border-b border-dashed border-gray-100 last:border-0">
                                                                    <span class="truncate pr-2 font-medium" title="{{ $item->judul_tagihan }}">
                                                                        <span class="text-gray-400 font-mono text-[10px]">[{{ $item->pos_biaya }}]</span> {{ $item->judul_tagihan }}
                                                                    </span>
                                                                    <span class="font-mono font-semibold text-gray-900 shrink-0">Rp {{ number_format($item->sisa_tagihan, 0, ',', '.') }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Tagihan Kegiatan & Non-Bulanan -->
                                @if($nonMonthlyList->count() > 0)
                                    <div class="pt-2 border-t border-gray-100">
                                        <div class="text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                            <span>Tagihan Non-Bulanan / Kegiatan ({{ $nonMonthlyList->count() }} Tagihan)</span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                            @foreach($nonMonthlyList as $nm)
                                                <div class="p-2.5 rounded-lg border border-sky-200/80 bg-sky-50/30 flex items-center justify-between text-xs">
                                                    <div class="font-medium text-gray-800 flex items-center gap-1.5 truncate pr-2">
                                                        <span class="text-sky-600">📌</span>
                                                        <span class="truncate" title="{{ $nm['judul'] }}">{{ $nm['judul'] }}</span>
                                                        <span class="text-[10px] text-gray-400 font-mono shrink-0">({{ $nm['pos'] }})</span>
                                                    </div>
                                                    <span class="font-mono font-bold text-rose-600 shrink-0">Rp {{ number_format($nm['total'], 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Quick Action Bar -->
                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100 no-print">
                                    @if($waUrl)
                                        <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.772.78 2.791.78h.001c3.181 0 5.768-2.587 5.768-5.766.001-3.181-2.585-5.766-5.769-5.766zm3.377 8.21c-.143.403-.834.774-1.157.825-.323.051-.735.084-2.18-.517-1.446-.601-2.385-2.072-2.457-2.168-.072-.096-.583-.777-.583-1.481 0-.704.368-1.05.5-1.193.132-.143.288-.179.384-.179.096 0 .192.001.276.005.09.004.21-.034.329.252.126.3.432 1.05.47 1.128.038.078.064.168.013.269-.051.101-.077.164-.153.253-.077.089-.161.199-.23.267-.077.076-.157.159-.067.313.09.154.4 1.082 1.206 1.8 1.036.924 1.91 1.21 2.181 1.344.271.134.43.117.59-.068.16-.185.688-.802.871-1.076.183-.274.367-.229.617-.137.25.092 1.584.747 1.856.883.272.136.453.204.519.317.066.113.066.657-.077 1.06z"/></svg>
                                            <span>Kirim Tagihan WA</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.pembayaran.index', ['santri_id' => $st?->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-semibold shadow-xs transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span>Buka di Kasir POS</span>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <div class="max-w-xs mx-auto space-y-2">
                                <svg class="w-10 h-10 text-emerald-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm font-bold text-gray-700">Alhamdulillah, Tidak Ada Tunggakan</p>
                                <p class="text-xs text-gray-400">Semua santri pada filter ini telah melunasi tagihannya.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($santriList->hasPages())
        <div class="p-4 border-t border-gray-100 no-print">
            {{ $santriList->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

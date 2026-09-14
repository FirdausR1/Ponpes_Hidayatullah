@extends('admin.layout')

@section('title', 'Buku Kas Keluar (Beban Operasional) — Pondok Pesantren Hidayatullah')

@section('content')
<div class="space-y-6" x-data="{
    modalTambah: false,
    modalEdit: false,
    modalPreview: false,
    previewImgUrl: '',
    previewTitle: '',

    // Data Saldo Sumber Dana dari Backend
    balances: {{ json_encode($sumberDanaBalances) }},

    // Form Tambah
    tambahSumberPos: 'Uang Makan',
    tambahNominal: '',

    // Data Form Edit
    editData: {
        id: '',
        no_ref: '',
        kategori: '',
        sumber_pos: 'Kas Umum',
        jenjang: 'Semua',
        judul: '',
        nominal: '',
        tanggal: '',
        metode: 'Kas Tunai',
        penerima: '',
        catatan: '',
        bukti_url: ''
    },

    getBalance(posKey) {
        if (!posKey) posKey = 'Kas Umum';
        return this.balances[posKey] || {
            key: posKey,
            label: posKey,
            masuk: 0,
            keluar: 0,
            saldo: 0,
            formatted_masuk: 'Rp 0',
            formatted_keluar: 'Rp 0',
            formatted_saldo: 'Rp 0'
        };
    },

    formatNumber(val) {
        let num = Number(val) || 0;
        return num.toLocaleString('id-ID');
    },

    openEdit(item) {
        this.editData = {
            id: item.id,
            no_ref: item.no_referensi,
            kategori: item.kategori,
            sumber_pos: item.sumber_pos || 'Kas Umum',
            jenjang: item.jenjang || 'Semua',
            judul: item.judul_pengeluaran,
            nominal: item.nominal,
            tanggal: item.tanggal_keluar ? item.tanggal_keluar.substring(0, 10) : '{{ date('Y-m-d') }}',
            metode: item.metode_kas || 'Kas Tunai',
            penerima: item.penerima_dana || '',
            catatan: item.catatan || '',
            bukti_url: item.bukti_nota || ''
        };
        this.modalEdit = true;
    },

    openPreview(url, title) {
        this.previewImgUrl = url;
        this.previewTitle = title;
        this.modalPreview = true;
    }
}">

    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-rose-50 px-2 py-0.5 text-xs font-semibold text-rose-700 border border-rose-200">Keuangan &amp; Kas Keluar</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">Buku Kas Keluar</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Buku Kas Keluar (Beban Operasional)</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Pencatatan kas keluar pesantren per jenjang (MTs / MA / Bersama) dengan alokasi sumber dana (Uang Makan, Syahriyah, SOT, Tabungan, dll).
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.pengeluaran.export', request()->query()) }}" class="inline-flex items-center gap-2 rounded-lg border border-rose-300 bg-rose-50 px-3.5 py-2.5 text-xs font-semibold text-rose-800 shadow-theme-xs hover:bg-rose-600 hover:text-white transition group" title="Unduh Catatan Uang Keluar (Lengkap dengan Sumber Dana Dari Uang Apa &amp; Dibuat Apa)">
                <svg class="w-4 h-4 text-rose-700 group-hover:text-white transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Unduh Excel Kas Keluar</span>
            </a>
            <a href="{{ route('admin.arusKas.export', request()->query()) }}" class="inline-flex items-center gap-2 rounded-lg border border-emerald-300 bg-emerald-50 px-3.5 py-2.5 text-xs font-semibold text-emerald-800 shadow-theme-xs hover:bg-emerald-600 hover:text-white transition group" title="Unduh Catatan Laporan Uang Masuk, Uang Keluar, dan Rekapitulasi Saldo Sumber Dana dalam format Excel (3 Sheet)">
                <svg class="w-4 h-4 text-emerald-700 group-hover:text-white transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Laporan Masuk &amp; Keluar (Arus Kas)</span>
            </a>
            <a href="{{ route('admin.arusKas.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-emerald-700 transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <span>Laporan Arus Kas</span>
            </a>
            <button type="button" @click="modalTambah = true" class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-rose-700 transition cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Catat Kas Keluar</span>
            </button>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-3.5 text-xs text-emerald-900 shadow-theme-xs flex items-center gap-2.5">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-lg border border-rose-200 bg-rose-50 p-3.5 text-xs text-rose-900 flex items-center gap-2.5 shadow-theme-xs">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- 4 KARTU STATISTIK KAS KELUAR -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Pengeluaran Bulan Ini (Total & Breakdown Jenjang) -->
        <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Kas Keluar Bulan Ini</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs">
                    Rp
                </span>
            </div>
            <div class="text-2xl font-bold font-mono text-rose-600">
                Rp {{ number_format($totalBulanIni, 0, ',', '.') }}
            </div>
            <!-- Pisah Jenjang MTs vs MA vs Bersama -->
            <div class="mt-3 pt-2.5 border-t border-gray-100 space-y-1 text-[11px]">
                <div class="flex justify-between items-center text-gray-600">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-sky-500 inline-block"></span> MTs:</span>
                    <span class="font-mono font-bold text-gray-900">Rp {{ number_format($totalBulanIniMts ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-600">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> MA:</span>
                    <span class="font-mono font-bold text-gray-900">Rp {{ number_format($totalBulanIniMa ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-600">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-purple-500 inline-block"></span> Bersama:</span>
                    <span class="font-mono font-bold text-gray-900">Rp {{ number_format($totalBulanIniBersama ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Pengeluaran Tahun Ini -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengeluaran Tahun Ini</span>
                <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold font-mono text-gray-900">
                Rp {{ number_format($totalTahunIni, 0, ',', '.') }}
            </div>
            <div class="mt-2 text-xs text-gray-500">
                Akumulasi Seluruh Beban Tahun {{ date('Y') }}
            </div>
        </div>

        <!-- Card 3: Total Transaksi Bulan Ini -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Transaksi</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold font-mono text-gray-900">
                {{ number_format($totalTransaksiBulanIni, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500 font-sans">BKK</span>
            </div>
            <div class="mt-2 text-xs text-gray-500">
                Bukti Kas Keluar bulan {{ Carbon\Carbon::now()->translatedFormat('F') }}
            </div>
        </div>

        <!-- Card 4: Kategori Terbesar -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pos Beban Terbesar</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </span>
            </div>
            <div class="text-sm font-bold text-gray-900 truncate" title="{{ $topKategori->kategori ?? 'Belum ada data' }}">
                {{ $topKategori->kategori ?? 'Belum Ada Transaksi' }}
            </div>
            <div class="mt-2 font-mono font-bold text-xs text-amber-700">
                Rp {{ number_format($topKategori->total ?? 0, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- KARTU OVERVIEW PERHITUNGAN & SALDO KAS PER SUMBER DANA -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-gray-900">Perhitungan Saldo Kas Per Sumber Pos Dana</h2>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">
                    Informasi ketersediaan saldo dan perhitungan dana masuk vs keluar untuk setiap pos anggaran (Uang Makan, Syahriyah, SOT, Tabungan, dll).
                </p>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="text-gray-500 font-medium">Total Saldo Semua Sumber:</span>
                <span class="font-mono font-extrabold text-sm {{ ($totalSaldoTersediaSemuaSumber ?? 0) >= 0 ? 'text-emerald-700 bg-emerald-50 border border-emerald-200' : 'text-rose-700 bg-rose-50 border border-rose-200' }} px-3 py-1 rounded-lg">
                    Rp {{ number_format($totalSaldoTersediaSemuaSumber ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Grid Cards Sumber Pos -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5">
            @foreach($sumberDanaBalances as $posKey => $info)
            @php
                $isPositive = $info['saldo'] > 0;
                $isFiltered = $sumberPosFilter === $posKey;
            @endphp
            <div class="relative group rounded-xl border {{ $isFiltered ? 'border-brand-500 ring-2 ring-brand-200 bg-brand-50/20' : ($isPositive ? 'border-emerald-100 bg-emerald-50/20 hover:border-emerald-300' : 'border-gray-200 bg-gray-50/40') }} p-3 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-1 mb-1.5">
                        <span class="font-bold text-xs text-gray-900 truncate" title="{{ $info['label'] }}">
                            {{ $posKey }}
                        </span>
                        @if($isPositive)
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" title="Dana Tersedia"></span>
                        @else
                            <span class="w-2 h-2 rounded-full bg-gray-300 shrink-0" title="Saldo Kosong / Belum Ada Pemasukan"></span>
                        @endif
                    </div>
                    <!-- Saldo Utama -->
                    <div class="text-sm font-mono font-bold {{ $isPositive ? 'text-emerald-700' : 'text-gray-500' }}">
                        {{ $info['formatted_saldo'] }}
                    </div>
                </div>

                <!-- Detail Masuk & Keluar -->
                <div class="mt-2.5 pt-2 border-t border-gray-100 text-[10px] space-y-0.5 text-gray-500">
                    <div class="flex justify-between">
                        <span>Masuk:</span>
                        <span class="font-mono font-semibold text-emerald-600">{{ $info['formatted_masuk'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Keluar:</span>
                        <span class="font-mono font-semibold text-rose-500">{{ $info['formatted_keluar'] }}</span>
                    </div>
                </div>

                <!-- Quick Filter Link -->
                <a href="{{ route('admin.pengeluaran.index', array_merge(request()->query(), ['sumber_pos' => $posKey])) }}" class="mt-2 text-[10px] text-center font-semibold text-brand-600 hover:text-brand-800 hover:underline block pt-1 border-t border-dashed border-gray-200">
                    {{ $isFiltered ? '✓ Sedang Difilter' : 'Filter Kas Keluar' }}
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- FILTER BAR (TailAdmin Form Style) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
        <form method="GET" action="{{ route('admin.pengeluaran.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
            <!-- Search Keyword -->
            <div class="lg:col-span-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Keterangan / No. BKK</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Ketik kata kunci..." class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 placeholder:text-gray-400 focus:border-brand-500 outline-none">
            </div>

            <!-- Filter Jenjang -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Jenjang Sekolah</label>
                <select name="jenjang" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-brand-500 cursor-pointer">
                    <option value="">Semua Jenjang</option>
                    <option value="MTs" {{ $jenjangFilter === 'MTs' ? 'selected' : '' }}>MTs (Madrasah Tsanawiyah)</option>
                    <option value="MA" {{ $jenjangFilter === 'MA' ? 'selected' : '' }}>MA (Madrasah Aliyah)</option>
                    <option value="Semua" {{ $jenjangFilter === 'Semua' ? 'selected' : '' }}>Bersama / Gabungan</option>
                </select>
            </div>

            <!-- Filter Sumber Pos Dana -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Sumber Pos Dana</label>
                <select name="sumber_pos" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-brand-500 cursor-pointer">
                    <option value="">Semua Sumber Dana</option>
                    @foreach($sumberPosList as $sKey => $sLabel)
                        <option value="{{ $sKey }}" {{ $sumberPosFilter === $sKey ? 'selected' : '' }}>
                            {{ $sKey }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kategori -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori Beban</label>
                <select name="kategori" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-brand-500 cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kKey => $kLabel)
                        <option value="{{ $kKey }}" {{ $kategoriFilter === $kKey ? 'selected' : '' }}>
                            {{ $kKey }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Bulan -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Bulan</label>
                <select name="bulan" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-brand-500 cursor-pointer">
                    <option value="all" {{ $bulanFilter === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        @php $mStr = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $mStr }}" {{ $bulanFilter == $mStr ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Button Submit Filter -->
            <div class="lg:col-span-1 flex items-end">
                <button type="submit" class="w-full h-10 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition cursor-pointer">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- TABEL UTAMA BUKU KAS KELUAR -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <span>Daftar Transaksi Kas Keluar</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">{{ $expenses->total() }} Transaksi</span>
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Riwayat pengeluaran kas pesantren yang tercatat dalam sistem</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 w-10 text-center">No</th>
                        <th class="px-4 py-3">No. BKK &amp; Tgl</th>
                        <th class="px-4 py-3">Jenjang &amp; Sumber Dana</th>
                        <th class="px-4 py-3">Kategori Beban</th>
                        <th class="px-4 py-3">Keterangan / Rincian</th>
                        <th class="px-4 py-3">Penerima Dana</th>
                        <th class="px-4 py-3 text-center">Metode Kas</th>
                        <th class="px-4 py-3 text-right">Nominal (Rp)</th>
                        <th class="px-4 py-3 text-center">Nota</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($expenses as $idx => $exp)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- No -->
                        <td class="px-4 py-3.5 text-center text-gray-500 font-mono">
                            {{ ($expenses->currentPage() - 1) * $expenses->perPage() + $idx + 1 }}
                        </td>

                        <!-- No BKK & Tanggal -->
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <span class="font-mono font-bold text-gray-900 block">{{ $exp->no_referensi }}</span>
                            <span class="text-[11px] text-gray-500">{{ optional($exp->tanggal_keluar)->format('d/m/Y') }}</span>
                        </td>

                        <!-- Jenjang & Sumber Dana -->
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <div class="flex flex-col gap-1 items-start">
                                @if($exp->jenjang === 'MTs')
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                        MTs
                                    </span>
                                @elseif($exp->jenjang === 'MA')
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        MA
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        Bersama / Umum
                                    </span>
                                @endif
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                    Dari: {{ $exp->sumber_pos ?: 'Kas Umum' }}
                                </span>
                            </div>
                        </td>

                        <!-- Kategori -->
                        <td class="px-4 py-3.5">
                            <span class="px-2 py-0.5 rounded-md font-medium text-[11px] bg-slate-100 text-slate-800 border border-slate-200 block sm:inline-block">
                                {{ $exp->kategori }}
                            </span>
                        </td>

                        <!-- Judul / Keterangan -->
                        <td class="px-4 py-3.5 max-w-xs">
                            <span class="font-bold text-gray-900 block">{{ $exp->judul_pengeluaran }}</span>
                            @if($exp->catatan)
                                <span class="text-[11px] text-gray-500 block truncate" title="{{ $exp->catatan }}">{{ $exp->catatan }}</span>
                            @endif
                        </td>

                        <!-- Penerima Dana -->
                        <td class="px-4 py-3.5 text-gray-700 font-medium whitespace-nowrap">
                            {{ $exp->penerima_dana ?: '—' }}
                        </td>

                        <!-- Metode Kas -->
                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $exp->metode_kas === 'Kas Tunai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                {{ $exp->metode_kas }}
                            </span>
                        </td>

                        <!-- Nominal -->
                        <td class="px-4 py-3.5 text-right font-mono font-bold text-rose-600 text-sm whitespace-nowrap">
                            Rp {{ number_format($exp->nominal, 0, ',', '.') }}
                        </td>

                        <!-- Bukti Nota -->
                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                            @if($exp->bukti_nota)
                                <button type="button" @click="openPreview('{{ $exp->bukti_nota }}', '{{ addslashes($exp->judul_pengeluaran) }} ({{ $exp->no_referensi }})')" class="inline-flex items-center gap-1 text-xs text-brand-600 hover:text-brand-700 font-medium underline cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Lihat</span>
                                </button>
                            @else
                                <span class="text-gray-400 text-[11px]">—</span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button" @click="openEdit({{ json_encode($exp) }})" class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition cursor-pointer" title="Edit Pengeluaran">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <form action="{{ route('admin.pengeluaran.destroy', $exp->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan pengeluaran {{ $exp->no_referensi }} ({{ addslashes($exp->judul_pengeluaran) }})?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Hapus Pengeluaran">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-12 text-center text-gray-400">
                            <div class="max-w-xs mx-auto space-y-2">
                                <svg class="w-10 h-10 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-sm font-semibold text-gray-600">Belum Ada Transaksi Kas Keluar</p>
                                <p class="text-xs text-gray-400">Gunakan tombol "+ Catat Kas Keluar" untuk memasukkan data pengeluaran operasional.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL FORM TAMBAH KAS KELUAR                                             -->
    <!-- ========================================================================= -->
    <div x-show="modalTambah" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalTambah = false" class="ta-modal max-w-lg">
            <div class="ta-modal-header">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Catat Kas Keluar Baru</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Input beban operasional pesantren (Bukti Kas Keluar / BKK)</p>
                </div>
                <button type="button" @click="modalTambah = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <form action="{{ route('admin.pengeluaran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="ta-modal-body space-y-3.5 text-xs">
                    <!-- Jenjang Sekolah & Sumber Pos Dana -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Jenjang Sekolah <span class="text-rose-500">*</span></label>
                            <select name="jenjang" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                                <option value="Semua">Semua / Gabungan (Bersama)</option>
                                <option value="MTs">MTs (Madrasah Tsanawiyah)</option>
                                <option value="MA">MA (Madrasah Aliyah)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Ambil dari Sumber Dana <span class="text-rose-500">*</span></label>
                            <select name="sumber_pos" x-model="tambahSumberPos" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                                @foreach($sumberPosList as $sKey => $sLabel)
                                    <option value="{{ $sKey }}">{{ $sLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Live Perhitungan & Informasi Saldo Sumber Pos -->
                    <div class="rounded-xl border p-3.5 bg-gradient-to-br from-emerald-50/70 via-white to-amber-50/60 border-emerald-200 shadow-xs space-y-2.5">
                        <div class="flex items-center justify-between flex-wrap gap-1">
                            <div class="flex items-center gap-1.5 font-bold text-gray-800 text-xs">
                                <span class="text-sm">💰</span>
                                <span>Perhitungan Sumber Dana:</span>
                                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-extrabold text-[11px]" x-text="tambahSumberPos"></span>
                            </div>
                            <div class="text-xs font-bold px-2 py-0.5 rounded-full"
                                 :class="getBalance(tambahSumberPos).saldo > 0 ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300'">
                                Sisa Saldo: <span class="font-mono font-black" x-text="getBalance(tambahSumberPos).formatted_saldo"></span>
                            </div>
                        </div>

                        <!-- 3 Kolom Indikator -->
                        <div class="grid grid-cols-3 gap-2 text-center text-[11px] pt-1 border-t border-emerald-100/70">
                            <div class="bg-white/90 rounded-lg p-2 border border-emerald-100 shadow-2xs">
                                <div class="text-gray-500 text-[10px]">Total Uang Masuk</div>
                                <div class="font-mono font-bold text-emerald-700" x-text="getBalance(tambahSumberPos).formatted_masuk"></div>
                            </div>
                            <div class="bg-white/90 rounded-lg p-2 border border-rose-100 shadow-2xs">
                                <div class="text-gray-500 text-[10px]">Total Terpakai</div>
                                <div class="font-mono font-bold text-rose-600" x-text="getBalance(tambahSumberPos).formatted_keluar"></div>
                            </div>
                            <div class="bg-white/90 rounded-lg p-2 border border-blue-100 shadow-2xs">
                                <div class="text-gray-500 text-[10px]">Sisa Tersedia</div>
                                <div class="font-mono font-bold text-blue-700" x-text="getBalance(tambahSumberPos).formatted_saldo"></div>
                            </div>
                        </div>

                        <!-- Formula Text -->
                        <div class="text-[11px] text-gray-600 bg-white/80 px-2.5 py-1.5 rounded-lg border border-gray-200/70 flex items-center justify-between flex-wrap gap-1">
                            <span>Perhitungan: <strong>Masuk</strong> (<span x-text="getBalance(tambahSumberPos).formatted_masuk"></span>) - <strong>Terpakai</strong> (<span x-text="getBalance(tambahSumberPos).formatted_keluar"></span>)</span>
                            <span class="font-bold text-gray-900">= <span class="text-emerald-700 font-mono" x-text="getBalance(tambahSumberPos).formatted_saldo"></span></span>
                        </div>

                        <!-- Warning jika melebihi saldo -->
                        <template x-if="tambahNominal && Number(tambahNominal) > getBalance(tambahSumberPos).saldo">
                            <div class="p-2 rounded-lg bg-rose-50 border border-rose-300 text-rose-800 text-[11px] font-medium flex items-center gap-1.5 animate-pulse">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span><strong>Peringatan:</strong> Pengeluaran Rp <span x-text="formatNumber(tambahNominal)"></span> melebihi sisa dana yang tersedia (<span x-text="getBalance(tambahSumberPos).formatted_saldo"></span>)!</span>
                            </div>
                        </template>
                    </div>

                    <!-- Kategori Beban -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Kategori Beban Pengeluaran <span class="text-rose-500">*</span></label>
                        <select name="kategori" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                            <option value="">-- Pilih Kategori Beban --</option>
                            @foreach($kategoriList as $kKey => $kLabel)
                                <option value="{{ $kKey }}">{{ $kLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Judul / Keterangan Singkat -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Keterangan / Rincian Pengeluaran <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul_pengeluaran" required placeholder="Contoh: Belanja beras &amp; lauk dapur santri MTs/MA 1 pekan" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                    </div>

                    <!-- Grid Nominal & Tanggal -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Nominal (Rp) <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400 font-bold text-xs pointer-events-none">Rp</span>
                                <input type="number" name="nominal" x-model="tambahNominal" required placeholder="0" min="1" class="w-full pl-8 pr-3 py-2 rounded-lg border border-gray-300 font-mono font-bold text-xs text-gray-900 focus:border-brand-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Tanggal Keluar <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_keluar" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                        </div>
                    </div>

                    <!-- Grid Metode Kas & Penerima -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Metode Kas <span class="text-rose-500">*</span></label>
                            <select name="metode_kas" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                                <option value="Kas Tunai">Kas Tunai (Kasir)</option>
                                <option value="Transfer Bank">Transfer Bank (Rekening)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Penerima Dana (Opsional)</label>
                            <input type="text" name="penerima_dana" placeholder="Toko Sayur / Ustadz / PLN" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                        </div>
                    </div>

                    <!-- Upload Foto Bukti Nota -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Foto Bukti Nota / Kwitansi Fisik <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="file" name="bukti_nota" accept="image/*" class="w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                    </div>

                    <!-- Catatan Tambahan -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Keterangan detail pengeluaran..." class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none"></textarea>
                    </div>
                </div>

                <div class="ta-modal-footer">
                    <button type="button" @click="modalTambah = false" class="ta-btn-outline text-xs cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-theme-xs transition cursor-pointer">
                        Simpan Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL FORM EDIT KAS KELUAR                                               -->
    <!-- ========================================================================= -->
    <div x-show="modalEdit" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalEdit = false" class="ta-modal max-w-lg">
            <div class="ta-modal-header">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Edit Catatan Kas Keluar</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Ubah data pengeluaran <span class="font-mono font-bold text-gray-800" x-text="editData.no_ref"></span></p>
                </div>
                <button type="button" @click="modalEdit = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <form :action="'{{ url('/admin/pengeluaran') }}/' + editData.id" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="ta-modal-body space-y-3.5 text-xs">
                    <!-- Jenjang Sekolah & Sumber Pos Dana -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Jenjang Sekolah <span class="text-rose-500">*</span></label>
                            <select name="jenjang" x-model="editData.jenjang" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                                <option value="Semua">Semua / Gabungan (Bersama)</option>
                                <option value="MTs">MTs (Madrasah Tsanawiyah)</option>
                                <option value="MA">MA (Madrasah Aliyah)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Ambil dari Sumber Dana <span class="text-rose-500">*</span></label>
                            <select name="sumber_pos" x-model="editData.sumber_pos" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                                @foreach($sumberPosList as $sKey => $sLabel)
                                    <option value="{{ $sKey }}">{{ $sLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Live Perhitungan & Informasi Saldo Sumber Pos (Edit) -->
                    <div class="rounded-xl border p-3.5 bg-gradient-to-br from-emerald-50/70 via-white to-amber-50/60 border-emerald-200 shadow-xs space-y-2.5">
                        <div class="flex items-center justify-between flex-wrap gap-1">
                            <div class="flex items-center gap-1.5 font-bold text-gray-800 text-xs">
                                <span class="text-sm">💰</span>
                                <span>Perhitungan Sumber Dana:</span>
                                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-extrabold text-[11px]" x-text="editData.sumber_pos"></span>
                            </div>
                            <div class="text-xs font-bold px-2 py-0.5 rounded-full"
                                 :class="getBalance(editData.sumber_pos).saldo > 0 ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300'">
                                Sisa Saldo: <span class="font-mono font-black" x-text="getBalance(editData.sumber_pos).formatted_saldo"></span>
                            </div>
                        </div>

                        <!-- 3 Kolom Indikator -->
                        <div class="grid grid-cols-3 gap-2 text-center text-[11px] pt-1 border-t border-emerald-100/70">
                            <div class="bg-white/90 rounded-lg p-2 border border-emerald-100 shadow-2xs">
                                <div class="text-gray-500 text-[10px]">Total Uang Masuk</div>
                                <div class="font-mono font-bold text-emerald-700" x-text="getBalance(editData.sumber_pos).formatted_masuk"></div>
                            </div>
                            <div class="bg-white/90 rounded-lg p-2 border border-rose-100 shadow-2xs">
                                <div class="text-gray-500 text-[10px]">Total Terpakai</div>
                                <div class="font-mono font-bold text-rose-600" x-text="getBalance(editData.sumber_pos).formatted_keluar"></div>
                            </div>
                            <div class="bg-white/90 rounded-lg p-2 border border-blue-100 shadow-2xs">
                                <div class="text-gray-500 text-[10px]">Sisa Tersedia</div>
                                <div class="font-mono font-bold text-blue-700" x-text="getBalance(editData.sumber_pos).formatted_saldo"></div>
                            </div>
                        </div>

                        <!-- Formula Text -->
                        <div class="text-[11px] text-gray-600 bg-white/80 px-2.5 py-1.5 rounded-lg border border-gray-200/70 flex items-center justify-between flex-wrap gap-1">
                            <span>Perhitungan: <strong>Masuk</strong> (<span x-text="getBalance(editData.sumber_pos).formatted_masuk"></span>) - <strong>Terpakai</strong> (<span x-text="getBalance(editData.sumber_pos).formatted_keluar"></span>)</span>
                            <span class="font-bold text-gray-900">= <span class="text-emerald-700 font-mono" x-text="getBalance(editData.sumber_pos).formatted_saldo"></span></span>
                        </div>

                        <!-- Warning jika melebihi saldo -->
                        <template x-if="editData.nominal && Number(editData.nominal) > getBalance(editData.sumber_pos).saldo">
                            <div class="p-2 rounded-lg bg-rose-50 border border-rose-300 text-rose-800 text-[11px] font-medium flex items-center gap-1.5 animate-pulse">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span><strong>Peringatan:</strong> Pengeluaran Rp <span x-text="formatNumber(editData.nominal)"></span> melebihi sisa dana yang tersedia (<span x-text="getBalance(editData.sumber_pos).formatted_saldo"></span>)!</span>
                            </div>
                        </template>
                    </div>

                    <!-- Kategori Beban -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Kategori Beban Pengeluaran <span class="text-rose-500">*</span></label>
                        <select name="kategori" x-model="editData.kategori" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                            @foreach($kategoriList as $kKey => $kLabel)
                                <option value="{{ $kKey }}">{{ $kLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Judul / Keterangan Singkat -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Keterangan / Rincian Pengeluaran <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul_pengeluaran" x-model="editData.judul" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                    </div>

                    <!-- Grid Nominal & Tanggal -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Nominal (Rp) <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400 font-bold text-xs pointer-events-none">Rp</span>
                                <input type="number" name="nominal" x-model="editData.nominal" required min="1" class="w-full pl-8 pr-3 py-2 rounded-lg border border-gray-300 font-mono font-bold text-xs text-gray-900 focus:border-brand-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Tanggal Keluar <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_keluar" x-model="editData.tanggal" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                        </div>
                    </div>

                    <!-- Grid Metode Kas & Penerima -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Metode Kas <span class="text-rose-500">*</span></label>
                            <select name="metode_kas" x-model="editData.metode" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                                <option value="Kas Tunai">Kas Tunai (Kasir)</option>
                                <option value="Transfer Bank">Transfer Bank (Rekening)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Penerima Dana (Opsional)</label>
                            <input type="text" name="penerima_dana" x-model="editData.penerima" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                        </div>
                    </div>

                    <!-- Upload Ganti Foto Bukti Nota -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Ganti Foto Nota <span class="text-gray-400 font-normal">(Kosongkan jika tidak ingin diubah)</span></label>
                        <input type="file" name="bukti_nota" accept="image/*" class="w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                        <template x-if="editData.bukti_url">
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-[11px] text-gray-500">Nota saat ini:</span>
                                <a :href="editData.bukti_url" target="_blank" class="text-[11px] text-brand-600 underline font-medium">Lihat File</a>
                            </div>
                        </template>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" x-model="editData.catatan" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none"></textarea>
                    </div>
                </div>

                <div class="ta-modal-footer">
                    <button type="button" @click="modalEdit = false" class="ta-btn-outline text-xs cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-gray-900 hover:bg-gray-800 text-white font-semibold text-xs shadow-theme-xs transition cursor-pointer">
                        Perbarui Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL PREVIEW BUKTI NOTA FISIK                                           -->
    <!-- ========================================================================= -->
    <div x-show="modalPreview" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalPreview = false" class="ta-modal max-w-lg">
            <div class="ta-modal-header">
                <h4 class="text-sm font-bold text-gray-900" x-text="previewTitle"></h4>
                <button @click="modalPreview = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>
            <div class="ta-modal-body flex items-center justify-center bg-gray-100 rounded-lg p-2 max-h-[70vh] overflow-y-auto">
                <img :src="previewImgUrl" alt="Bukti Nota" class="max-h-[65vh] object-contain rounded-lg">
            </div>
        </div>
    </div>

</div>
@endsection

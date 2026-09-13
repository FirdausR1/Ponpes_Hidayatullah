@extends('admin.layout')

@section('title', 'Buku Kas Keluar (Beban Operasional) — Pondok Pesantren Hidayatullah')

@section('content')
<div class="space-y-6" x-data="{
    modalTambah: false,
    modalEdit: false,
    modalPreview: false,
    previewImgUrl: '',
    previewTitle: '',

    // Data Form Edit
    editData: {
        id: '',
        no_ref: '',
        kategori: '',
        judul: '',
        nominal: '',
        tanggal: '',
        metode: 'Kas Tunai',
        penerima: '',
        catatan: '',
        bukti_url: ''
    },

    openEdit(item) {
        this.editData = {
            id: item.id,
            no_ref: item.no_referensi,
            kategori: item.kategori,
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
                Pencatatan pengeluaran operasional pesantren: belanja dapur makan santri, listrik/air PAM/internet, gaji ustadz, dan pemeliharaan sarana asrama.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.arusKas.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-emerald-700 transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <span>Laporan Arus Kas (Cashflow)</span>
            </a>
            <button type="button" @click="modalTambah = true" class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-rose-700 transition">
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
        <!-- Card 1: Pengeluaran Bulan Ini -->
        <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengeluaran Bulan Ini</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs">
                    Rp
                </span>
            </div>
            <div class="text-2xl font-bold font-mono text-rose-600">
                Rp {{ number_format($totalBulanIni, 0, ',', '.') }}
            </div>
            <div class="mt-2 text-xs text-gray-500">
                Periode: <strong>{{ Carbon\Carbon::now()->translatedFormat('F Y') }}</strong>
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
                Akumulasi Tahun {{ date('Y') }}
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
                Bukti Kas Keluar tercatat
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

    <!-- FILTER BAR (TailAdmin Form Style) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
        <form method="GET" action="{{ route('admin.pengeluaran.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
            <!-- Search Keyword -->
            <div class="lg:col-span-4">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Keterangan / No. BKK / Penerima</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Ketik kata kunci..." class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 placeholder:text-gray-400 focus:border-brand-500 outline-none">
            </div>

            <!-- Filter Kategori -->
            <div class="lg:col-span-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Kategori Beban</label>
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

            <!-- Filter Metode Kas -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Metode Kas</label>
                <select name="metode_kas" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-brand-500 cursor-pointer">
                    <option value="">Semua Metode</option>
                    <option value="Kas Tunai" {{ $metodeFilter === 'Kas Tunai' ? 'selected' : '' }}>Kas Tunai</option>
                    <option value="Transfer Bank" {{ $metodeFilter === 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="lg:col-span-1 flex items-end gap-1.5">
                <button type="submit" class="w-full h-10 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- TABEL UTAMA BUKU KAS KELUAR -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
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
                                <button type="button" @click="openPreview('{{ $exp->bukti_nota }}', '{{ addslashes($exp->judul_pengeluaran) }} ({{ $exp->no_referensi }})')" class="inline-flex items-center gap-1 text-xs text-brand-600 hover:text-brand-700 font-medium underline">
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
                                <button type="button" @click="openEdit({{ json_encode($exp) }})" class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition" title="Edit Pengeluaran">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <form action="{{ route('admin.pengeluaran.destroy', $exp->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan pengeluaran {{ $exp->no_referensi }} ({{ addslashes($exp->judul_pengeluaran) }})?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Pengeluaran">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-12 text-center text-gray-400">
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
                    <p class="text-xs text-gray-500 mt-0.5">Input biaya operasional pesantren (Bukti Kas Keluar / BKK)</p>
                </div>
                <button type="button" @click="modalTambah = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <form action="{{ route('admin.pengeluaran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="ta-modal-body space-y-3.5 text-xs">
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
                        <input type="text" name="judul_pengeluaran" required placeholder="Contoh: Belanja beras &amp; telur dapur santri 1 minggu" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                    </div>

                    <!-- Grid Nominal & Tanggal -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Nominal (Rp) <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400 font-bold text-xs pointer-events-none">Rp</span>
                                <input type="number" name="nominal" required placeholder="0" min="1" class="w-full pl-8 pr-3 py-2 rounded-lg border border-gray-300 font-mono font-bold text-xs text-gray-900 focus:border-brand-500 outline-none">
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
                    <button type="button" @click="modalTambah = false" class="ta-btn-outline text-xs">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-theme-xs transition">
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
                    <button type="button" @click="modalEdit = false" class="ta-btn-outline text-xs">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-gray-900 hover:bg-gray-800 text-white font-semibold text-xs shadow-theme-xs transition">
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

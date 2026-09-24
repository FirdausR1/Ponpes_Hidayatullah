@extends('admin.layout')

@section('title', 'Kelola Tagihan Santri & Biaya Tambahan')

@section('content')
<div class="space-y-6" x-data="{ 
    modalTagihanUnified: false,
    tabTagihan: 'bulanan',
    targetBulananTipe: 'all',
    modalBulanan: false, 
    modalTambahan: false,
    modalImportTunggakan: false,
    selectedPosTambahan: 'SOT',
    selectedKategori: 'bulanan',
    selectedBulan: '{{ ['January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'][date('F')] ?? 'September' }}',
    selectedTahun: '{{ date('Y') }}',
    useTarifOtomatis: true,
    inputNominal: '',
    judulTagihan: 'Iuran SOT ({{ ['January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'][date('F')] ?? 'September' }} {{ date('Y') }})',
    modalTangguhkan: false,
    modalNolkan: false,
    modalMintaSurat: false,
    modalReviewSurat: false,
    sasaranTipe: 'semua',
    targetBill: {
        id: null,
        judul: '',
        pos: '',
        santri: '',
        nis: '',
        kelas: '',
        sisa: 0,
        sisa_rp: '',
        jenis_surat: '',
        instruksi: '',
        file_surat: ''
    },
    autoFillJudul() {
        let posLabel = {
            'MAKAN': 'Uang Makan 3x Sehari',
            'SYAHRIYAH': 'Syahriyah Pendidikan (SPP)',
            'SOT': 'Iuran SOT',
            'TAB': 'Tabungan Wajib Santri',
            'PANGKAL': 'Uang Pangkal',
            'GEDUNG': 'Uang Gedung',
            'KERTAS': 'Kertas / Evaluasi Belajar',
            'KESEHATAN': 'Kesehatan Santri',
            'KEGIATAN': 'Kegiatan Santri',
            'PG': 'PG',
            'ALMARI': 'Almari & Fasilitas Asrama',
            'PENDAFTARAN': 'Pendaftaran Santri Baru',
            'KENAIKAN': 'Kenaikan Kelas',
            'PENGEMBANGAN PONDOK': 'Infaq Pengembangan Pondok'
        }[this.selectedPosTambahan] || this.selectedPosTambahan;
        if (this.selectedKategori === 'bulanan') {
            this.judulTagihan = posLabel + ' (' + this.selectedBulan + ' ' + this.selectedTahun + ')';
        } else {
            if (this.selectedPosTambahan !== '__CUSTOM__') {
                this.judulTagihan = posLabel + ' ' + this.selectedTahun;
            }
        }
    },
    onPosOrKategoriChange() {
        if (this.selectedPosTambahan === 'SOT') {
            this.useTarifOtomatis = true;
            this.inputNominal = '';
        } else if (this.selectedPosTambahan === 'MAKAN') {
            this.inputNominal = 300000;
            this.useTarifOtomatis = false;
        } else if (this.selectedPosTambahan === 'TAB') {
            this.inputNominal = 25000;
            this.useTarifOtomatis = false;
        } else if (this.selectedPosTambahan === 'SYAHRIYAH') {
            this.useTarifOtomatis = true;
            this.inputNominal = '';
        } else {
            this.useTarifOtomatis = false;
        }
        this.autoFillJudul();
    },
    openModalTambahan(kategori = 'tambahan', pos = 'SOT') {
        this.selectedKategori = kategori;
        this.selectedPosTambahan = pos;
        this.onPosOrKategoriChange();
        this.tabTagihan = 'insidental';
        this.modalTagihanUnified = true;
    },
    openTangguhkan(id, judul, santri, sisaRp) {
        this.targetBill.id = id;
        this.targetBill.judul = judul;
        this.targetBill.santri = santri;
        this.targetBill.sisa_rp = sisaRp;
        this.modalTangguhkan = true;
    },
    openNolkan(id, judul, santri, sisaRp) {
        this.targetBill.id = id;
        this.targetBill.judul = judul;
        this.targetBill.santri = santri;
        this.targetBill.sisa_rp = sisaRp;
        this.modalNolkan = true;
    },
    openMintaSurat(id, judul, santri, sisaRp) {
        this.targetBill.id = id;
        this.targetBill.judul = judul;
        this.targetBill.santri = santri;
        this.targetBill.sisa_rp = sisaRp;
        this.modalMintaSurat = true;
    },
    tipePersetujuanSurat: 'bebas_penuh',
    nominalPotonganSurat: 0,
    openReviewSurat(id, judul, santri, sisaRp, jenisSurat, fileSurat, instruksi, sisaNum = 0) {
        this.targetBill.id = id;
        this.targetBill.judul = judul;
        this.targetBill.santri = santri;
        this.targetBill.sisa_rp = sisaRp;
        this.targetBill.sisa = sisaNum;
        this.targetBill.jenis_surat = jenisSurat;
        this.targetBill.file_surat = fileSurat;
        this.targetBill.instruksi = instruksi;
        this.tipePersetujuanSurat = 'bebas_penuh';
        this.nominalPotonganSurat = sisaNum;
        this.modalReviewSurat = true;
    }
}">

    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200">Keuangan &amp; Pembayaran</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kelola Tagihan Santri &amp; Biaya Tambahan</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Terbitkan tagihan rutin bulanan dengan kalkulasi otomatis potongan SKTM, atau buat tagihan insidental (Ziarah, Wisuda, Ujian, dll.).</p>
        </div>

        <!-- Action Buttons (TailAdmin Standard) -->
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-emerald-700 transition" title="Buka Kasir Pembayaran POS">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <span>Kasir Pembayaran</span>
            </a>
            <button type="button" @click="modalTagihanUnified = true; tabTagihan = 'bulanan'" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-theme-xs hover:bg-emerald-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>+ Buat &amp; Terbitkan Tagihan</span>
            </button>
            <button type="button" @click="modalImportTunggakan = true" class="inline-flex items-center gap-2 rounded-lg border border-amber-300 bg-amber-50 px-3.5 py-2.5 text-xs font-semibold text-amber-900 shadow-theme-xs hover:bg-amber-100 hover:border-amber-400 transition" title="Upload data santri massal beserta tunggakan tagihan masa lalu via Excel">
                <svg class="w-4 h-4 text-amber-600 fill-current" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M8.5 13.5l2 2.5-2 2.5h1.5l1.25-1.75L12.5 18.5H14l-2-2.5 2-2.5h-1.5l-1.25 1.75L10 13.5H8.5z"/></svg>
                <span>Upload Santri &amp; Tunggakan Lalu</span>
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

    <!-- Stats Ringkas (TailAdmin EcommerceMetrics Standard) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Metric 1: Total Tagihan -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block">Total Tagihan</span>
                    <h4 class="mt-2 text-xl font-bold text-gray-800">Rp {{ number_format($totalTagihanRp, 0, ',', '.') }}</h4>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gray-100 text-gray-600">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/><path fill-rule="evenodd" d="M8 9a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1zm0 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-gray-500">
                <span class="font-medium text-gray-700">Seluruh</span> tagihan santri terbit
            </div>
        </div>

        <!-- Metric 2: Total Terbayar -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-emerald-600 uppercase tracking-wider block">Total Terbayar</span>
                    <h4 class="mt-2 text-xl font-bold text-emerald-700">Rp {{ number_format($totalTerbayarRp, 0, ',', '.') }}</h4>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.7071 5.29289a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.5858l7.2929-7.2929a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-emerald-600">
                <span class="font-medium">Pembayaran sah</span> telah diterima
            </div>
        </div>

        <!-- Metric 3: Tunggakan Aktif -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-rose-600 uppercase tracking-wider block">Tunggakan Aktif</span>
                    <h4 class="mt-2 text-xl font-bold text-rose-600">Rp {{ number_format($totalTunggakanRp, 0, ',', '.') }}</h4>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-rose-600">
                <span class="font-medium">Tagihan rutin</span> belum lunas
            </div>
        </div>

        <!-- Metric 4: Ditangguhkan Wisuda -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-blue-600 uppercase tracking-wider block">Ditangguhkan</span>
                    <h4 class="mt-2 text-xl font-bold text-blue-700">Rp {{ number_format($totalDitangguhkanRp ?? 0, 0, ',', '.') }}</h4>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-blue-600">
                <span class="font-medium">Disimpan</span> s.d. wisuda kelulusan
            </div>
        </div>

        <!-- Metric 5: Santri Nunggak -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-amber-600 uppercase tracking-wider block">Santri Nunggak</span>
                    <h4 class="mt-2 text-xl font-bold text-amber-700">{{ $totalSantriNunggak }} <span class="text-xs font-normal text-gray-500">Santri</span></h4>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-amber-600">
                <span class="font-medium">Santri tagihan</span> aktif terbuka
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian Lengkap (TailAdmin Form Style) -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">

        {{-- Tab Status Santri --}}
        <div class="flex items-center gap-1 px-5 pt-4 pb-0 border-b border-gray-100">
            @php $currentStatusSantri = $statusSantri ?? 'aktif'; @endphp
            <a href="{{ request()->fullUrlWithQuery(['status_santri' => 'aktif', 'page' => null]) }}"
               class="px-3 py-2 text-xs font-semibold rounded-t-lg border-b-2 transition-colors
                      {{ $currentStatusSantri === 'aktif' ? 'border-brand-500 text-brand-600 bg-blue-50/50' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                    Santri Aktif
                </span>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status_santri' => 'arsip', 'page' => null]) }}"
               class="px-3 py-2 text-xs font-semibold rounded-t-lg border-b-2 transition-colors
                      {{ $currentStatusSantri === 'arsip' ? 'border-amber-500 text-amber-700 bg-amber-50/50' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                    Arsip Alumni & Mutasi
                </span>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status_santri' => 'all', 'page' => null]) }}"
               class="px-3 py-2 text-xs font-semibold rounded-t-lg border-b-2 transition-colors
                      {{ $currentStatusSantri === 'all' ? 'border-gray-500 text-gray-700 bg-gray-50/50' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Semua
            </a>
            @if($currentStatusSantri === 'arsip')
            <span class="ml-auto mr-2 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M10 12v6M14 12v6"/></svg>
                Mode Arsip — tagihan santri alumni &amp; mutasi
            </span>
            @endif
        </div>

        <form method="GET" action="{{ route('admin.pembayaran.tagihan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 p-5">
            <input type="hidden" name="status_santri" value="{{ $currentStatusSantri }}">
            <!-- Filter Status -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Status Pembayaran</label>
                <select name="status" onchange="this.form.submit()" class="h-10 w-full text-xs rounded-lg px-3 border border-gray-300 bg-white text-gray-700 outline-none cursor-pointer">
                    <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="Belum Bayar" {{ $statusFilter == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="Cicilan" {{ $statusFilter == 'Cicilan' ? 'selected' : '' }}>Cicilan (Sebagian)</option>
                    <option value="Ditangguhkan (Wisuda)" {{ $statusFilter == 'Ditangguhkan (Wisuda)' ? 'selected' : '' }}>Ditangguhkan (Wisuda)</option>
                    <option value="Lunas" {{ $statusFilter == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            <!-- Filter Kategori -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Kategori Tagihan</label>
                <select name="kategori" onchange="this.form.submit()" class="h-10 w-full text-xs rounded-lg px-3 border border-gray-300 bg-white text-gray-700 outline-none cursor-pointer">
                    <option value="all" {{ $kategoriFilter == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                    <option value="bulanan" {{ $kategoriFilter == 'bulanan' ? 'selected' : '' }}>Rutin Bulanan</option>
                    <option value="tambahan" {{ $kategoriFilter == 'tambahan' ? 'selected' : '' }}>Biaya Tambahan</option>
                    <option value="sekali_bayar" {{ $kategoriFilter == 'sekali_bayar' ? 'selected' : '' }}>Sekali Bayar (Pangkal)</option>
                    <option value="tahunan" {{ $kategoriFilter == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>

            <!-- Filter Pos Biaya -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Pos Biaya</label>
                <select name="pos_biaya" onchange="this.form.submit()" class="h-10 w-full text-xs rounded-lg px-3 border border-gray-300 bg-white text-gray-700 outline-none cursor-pointer">
                    <option value="">Semua Pos Biaya</option>
                    @foreach($posBiayaList as $key => $label)
                        <option value="{{ $key }}" {{ $posFilter == $key ? 'selected' : '' }}>{{ $key }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kelas -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Kelas Santri</label>
                <select name="kelas" onchange="this.form.submit()" class="h-10 w-full text-xs rounded-lg px-3 border border-gray-300 bg-white text-gray-700 outline-none cursor-pointer">
                    <option value="">Semua Kelas</option>
                    @foreach($classrooms as $c)
                        <option value="{{ $c->nama_kelas }}" {{ $kelasFilter == $c->nama_kelas ? 'selected' : '' }}>
                            {{ $c->nama_kelas }} ({{ $c->jenjang }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Cari Santri / Tagihan -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Cari Santri / Tagihan</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                    </span>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Nama / NIS / Judul..." class="h-10 w-full pl-9 pr-4 text-xs text-gray-800 bg-white border border-gray-300 rounded-lg focus:border-brand-500 outline-none">
                </div>
            </div>

            <!-- Action Filter & Reset -->
            <div class="flex items-end gap-2">
                <button type="submit" class="h-10 flex-1 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-semibold transition">
                    Filter
                </button>
                @if(request()->anyFilled(['status', 'kategori', 'pos_biaya', 'kelas', 'q']))
                    <a href="{{ route('admin.pembayaran.tagihan.index') }}" class="h-10 px-3 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-600 text-xs font-medium flex items-center justify-center transition" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Data Tagihan Santri (TailAdmin Format) -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="font-bold text-gray-900 text-sm sm:text-base">Daftar Tagihan Santri</h3>
                <p class="text-xs text-gray-500 mt-0.5">Kelola penagihan syahriyah, uang makan, dan biaya operasional pondok</p>
            </div>
            <div class="inline-flex items-center h-8 px-3 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-600 font-medium whitespace-nowrap">
                Total:&nbsp;<strong class="text-gray-900 font-bold">{{ $bills->total() }}</strong>&nbsp;Tagihan
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/70">
                        <th class="px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center w-12">No</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Santri &amp; Rombel</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pos &amp; Tagihan</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Nominal Asli</th>
                        <th class="px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Potongan</th>
                        <th class="px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Harus Bayar</th>
                        <th class="px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Terbayar</th>
                        <th class="px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Sisa Tagihan</th>
                        <th class="px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bills as $idx => $b)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-4 text-xs font-medium text-gray-400 text-center">
                                {{ $bills->firstItem() + $idx }}
                            </td>
                            
                            <!-- Santri & Rombel -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-gray-900 text-xs sm:text-sm">{{ $b->student->nama_lengkap ?? '—' }}</div>
                                <div class="flex items-center gap-1.5 flex-wrap mt-0.5 text-xs text-gray-500">
                                    <span class="font-mono">NIS: {{ $b->student->nis ?? '—' }}</span>
                                    <span>•</span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Kelas {{ $b->student->kelas ?? '—' }}
                                    </span>
                                    @if($b->student)
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-extrabold shadow-2xs {{ $b->student->hunian === 'Mukim' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                            {{ $b->student->kategori_label }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Pos & Tagihan -->
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $b->pos_biaya }}
                                </span>
                                <div class="font-semibold text-gray-900 text-xs sm:text-sm mt-1">{{ $b->judul_tagihan }}</div>
                                @if($b->catatan)
                                    <div class="text-[11px] text-gray-400 italic mt-0.5">{{ $b->catatan }}</div>
                                @endif

                                <!-- Badge Status Permintaan Surat Dispensasi -->
                                @if($b->status_dispensasi === 'diminta_surat')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200 mt-1">
                                        Minta: {{ $b->jenis_surat_diminta }}
                                    </span>
                                @elseif($b->status_dispensasi === 'surat_diunggah')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-purple-100 text-purple-900 border border-purple-300 mt-1 animate-pulse">
                                        Surat Diunggah Santri!
                                    </span>
                                @elseif($b->status_dispensasi === 'disetujui')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 mt-1">
                                        Surat Disetujui
                                    </span>
                                @elseif($b->status_dispensasi === 'ditolak')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 mt-1" title="{{ $b->alasan_penolakan_surat }}">
                                        Surat Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- Periode -->
                            <td class="px-5 py-4 text-xs">
                                @if($b->bulan)
                                    <span class="font-semibold text-gray-800">{{ $b->bulan }} {{ $b->tahun }}</span>
                                @else
                                    <span class="text-gray-500">{{ $b->tahun ?: '—' }}</span>
                                @endif
                                <div class="text-[11px] text-gray-400 uppercase font-medium mt-0.5">
                                    {{ str_replace('_', ' ', $b->kategori) }}
                                </div>
                            </td>

                            <!-- Nominal Asli -->
                            <td class="px-4 py-4 text-right font-mono text-xs text-gray-600">
                                Rp {{ number_format($b->nominal_asli, 0, ',', '.') }}
                            </td>

                            <!-- Potongan -->
                            <td class="px-4 py-4 text-right font-mono text-xs">
                                @if($b->nominal_potongan > 0)
                                    <span class="text-purple-700 font-semibold">- Rp {{ number_format($b->nominal_potongan, 0, ',', '.') }}</span>
                                    <span class="block text-[10px] text-purple-600">{{ $b->alasan_potongan }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            <!-- Harus Bayar -->
                            <td class="px-4 py-4 text-right font-mono font-bold text-xs text-gray-900">
                                Rp {{ number_format($b->nominal_tagihan, 0, ',', '.') }}
                            </td>

                            <!-- Terbayar -->
                            <td class="px-4 py-4 text-right font-mono font-bold text-xs text-emerald-700">
                                Rp {{ number_format($b->nominal_bayar, 0, ',', '.') }}
                            </td>

                            <!-- Sisa Tunggakan -->
                            <td class="px-4 py-4 text-right font-mono font-bold text-xs {{ $b->sisa_tagihan > 0 ? 'text-rose-600' : 'text-gray-400' }}">
                                Rp {{ number_format($b->sisa_tagihan, 0, ',', '.') }}
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                @if($b->penangguhan_wisuda)
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold bg-blue-100 text-blue-800" title="{{ $b->catatan_penangguhan }}">
                                        Ditangguhkan (Wisuda)
                                    </span>
                                    @if($b->catatan_penangguhan)
                                        <span class="block text-[10px] text-blue-600 truncate max-w-[120px] mx-auto mt-0.5" title="{{ $b->catatan_penangguhan }}">{{ $b->catatan_penangguhan }}</span>
                                    @endif
                                @elseif($b->status === 'Lunas')
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-800">
                                        Lunas
                                    </span>
                                    @if($b->catatan_pembebasan)
                                        <span class="block text-[10px] text-emerald-700 font-semibold truncate max-w-[120px] mx-auto mt-0.5" title="{{ $b->catatan_pembebasan }}">Pemutihan: {{ $b->catatan_pembebasan }}</span>
                                    @endif
                                @elseif($b->status === 'Cicilan')
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold bg-amber-100 text-amber-800">
                                        Cicilan
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold bg-rose-100 text-rose-800">
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Tombol Bayar Langsung Kasir -->
                                    <a href="{{ route('admin.pembayaran.index', ['santri_id' => $b->student_id]) }}" title="Buka Kasir untuk Santri Ini" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-semibold transition">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        Bayar
                                    </a>

                                    @if($b->sisa_tagihan > 0)
                                        @php
                                            $st = $b->student;
                                            $targetPhone = $st ? ($st->no_whatsapp ?: ($st->no_hp ?: ($st->no_hp_wali ?: ($st->ayah_telepon ?: $st->ibu_telepon)))) : null;
                                            $cleanWa = $targetPhone ? preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $targetPhone)) : null;
                                            $periodeStr = $b->bulan ? "{$b->bulan} {$b->tahun}" : ($b->tahun ?: '-');
                                            $sisaRp = number_format($b->sisa_tagihan, 0, ',', '.');
                                            $tagihanRp = number_format($b->nominal_tagihan, 0, ',', '.');
                                            $bayarRp = number_format($b->nominal_bayar, 0, ',', '.');

                                            $rekBriNo = \App\Models\Setting::get('rek_admin_bri_no', '010201022009537');
                                            $rekBriAn = \App\Models\Setting::get('rek_admin_bri_an', 'DIKY FACHRI HUSEIN');
                                            $rekBcaNo = \App\Models\Setting::get('rek_admin_bca_no', '1221220167');
                                            $rekBcaAn = \App\Models\Setting::get('rek_admin_bca_an', 'DIKY FACHRI HUSEIN');
                                            $rekKonfPhone = \App\Models\Setting::get('rek_admin_konfirmasi_phone', '085290429617');
                                            $rekKonfNama = \App\Models\Setting::get('rek_admin_konfirmasi_nama', 'Ustdh. Harsih Nur A');

                                            $namaSantri = $st?->nama_lengkap ?? 'Santri';
                                            $kelasSantri = $st?->kelas ?? '-';

                                            $msgBill = "Assalamu'alaikum Wr. Wb.\n\n"
                                                . "Yth. Orang Tua / Wali Santri dari *{$namaSantri}* (Kelas {$kelasSantri})\n\n"
                                                . "Kami dari Bagian Keuangan Pondok Pesantren Hidayatullah Tuksongo menginformasikan rincian tagihan santri:\n\n"
                                                . "• Tagihan: {$b->judul_tagihan} ({$b->pos_biaya})\n"
                                                . "• Periode: {$periodeStr}\n"
                                                . "• Total Biaya: Rp {$tagihanRp}\n"
                                                . "• Terbayar: Rp {$bayarRp}\n"
                                                . "• *Sisa Tunggakan:* *Rp {$sisaRp}*\n\n"
                                                . "Pembayaran dapat dilakukan di Kasir Kantor Pesantren (Tunai) atau melalui Transfer ke Rekening Resmi Administrasi:\n"
                                                . "• BRI: *{$rekBriNo}* (a.n. {$rekBriAn})\n"
                                                . "• BCA: *{$rekBcaNo}* (a.n. {$rekBcaAn})\n\n"
                                                . "KONFIRMASI BUKTI TRANSFER DENGAN MENYERTAKAN DATA SEBAGAI BERIKUT:\n"
                                                . "• NAMA : {$namaSantri}\n"
                                                . "• KELAS : {$kelasSantri}\n"
                                                . "• JENIS PEMBAYARAN : {$b->judul_tagihan} ({$periodeStr})\n"
                                                . "• NOMINAL : Rp {$sisaRp}\n\n"
                                                . "Kirim bukti transfer ke WA Keuangan: {$rekKonfPhone} ({$rekKonfNama})\n\n"
                                                . "⚠️ *Wajib Melakukan Konfirmasi*\n"
                                                . "Demi terciptanya komunikasi yang tertib dan menghindari miskomunikasi, setiap keperluan transfer harap selalu diawali dengan konfirmasi kepada pihak terkait.\n\n"
                                                . "Syukron wa Jazakumullahu Khairan Katsiran.\n"
                                                . "Bendahara Pondok Pesantren Hidayatullah Tuksongo";
                                            $waUrl = $cleanWa ? ("https://wa.me/{$cleanWa}?text=" . rawurlencode($msgBill)) : null;
                                        @endphp
                                        @if($waUrl)
                                            <a href="{{ $waUrl }}" target="_blank" title="Kirim Tagihan via WhatsApp" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-green-50 hover:bg-green-100 text-green-700 border border-green-200 text-xs font-semibold transition">
                                                <svg class="w-3.5 h-3.5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                                WA
                                            </a>
                                        @endif
                                    @endif

                                    <!-- Tombol Review Surat -->
                                    @if($b->status_dispensasi === 'surat_diunggah')
                                        <button type="button" @click="openReviewSurat({{ $b->id }}, '{{ addslashes($b->judul_tagihan) }}', '{{ addslashes($b->student->nama_lengkap ?? '') }}', '{{ number_format($b->sisa_tagihan, 0, ',', '.') }}', '{{ addslashes($b->jenis_surat_diminta ?? 'Surat Dispensasi') }}', '{{ asset($b->file_surat_dispensasi) }}', '{{ addslashes($b->instruksi_surat ?? '') }}', {{ (float)$b->sisa_tagihan }})" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 text-xs font-semibold transition" title="Tinjau berkas surat yang diunggah santri">
                                            <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Review
                                        </button>
                                    @endif

                                    <!-- Tindakan Lanjutan -->
                                    @if($b->status !== 'Lunas')
                                        @if($b->penangguhan_wisuda)
                                            <form method="POST" action="{{ route('admin.pembayaran.tagihan.batalkanTangguh', $b->id) }}" onsubmit="return confirm('Kembalikan tagihan ini ke tagihan aktif rutin?')" class="inline">
                                                @csrf
                                                <button type="submit" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Batalkan Penangguhan Wisuda">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                </button>
                                            </form>
                                        @else
                                            <!-- Tombol Tangguhkan -->
                                            <button type="button" @click="openTangguhkan({{ $b->id }}, '{{ addslashes($b->judul_tagihan) }}', '{{ addslashes($b->student->nama_lengkap ?? '') }}', '{{ number_format($b->sisa_tagihan, 0, ',', '.') }}')" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Tangguhkan s.d. Wisuda">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </button>

                                            <!-- Tombol Nol-kan -->
                                            <button type="button" @click="openNolkan({{ $b->id }}, '{{ addslashes($b->judul_tagihan) }}', '{{ addslashes($b->student->nama_lengkap ?? '') }}', '{{ number_format($b->sisa_tagihan, 0, ',', '.') }}')" class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Nol-kan Tagihan (Pemutihan)">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </button>

                                            <!-- Tombol Minta Surat -->
                                            @if($b->status_dispensasi !== 'surat_diunggah' && $b->status_dispensasi !== 'disetujui')
                                                <button type="button" @click="openMintaSurat({{ $b->id }}, '{{ addslashes($b->judul_tagihan) }}', '{{ addslashes($b->student->nama_lengkap ?? '') }}', '{{ number_format($b->sisa_tagihan, 0, ',', '.') }}')" class="p-1.5 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Minta Santri Upload Berkas Surat">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                                </button>
                                            @endif
                                        @endif

                                        @if($b->nominal_bayar == 0)
                                            <!-- Hapus Tagihan -->
                                            <form method="POST" action="{{ route('admin.pembayaran.tagihan.destroy', $b->id) }}" onsubmit="return confirm('Hapus tagihan {{ $b->judul_tagihan }} untuk {{ $b->student->nama_lengkap ?? '' }}?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Tagihan">
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-12 text-gray-400 text-xs">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="font-medium text-gray-500">Belum ada data tagihan yang sesuai dengan filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $bills->links() }}
        </div>
    </div>

    <!-- MODAL TERPADU: BUAT & TERBITKAN TAGIHAN SANTRI (BULANAN RUTIN & INSIDENTAL) -->
    <div x-show="modalTagihanUnified" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalTagihanUnified = false" class="ta-modal max-w-lg">
            <div class="ta-modal-header">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Buat &amp; Terbitkan Tagihan Santri</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pilih paket tagihan bulanan (massal / per kelas / satu santri) atau tagihan khusus</p>
                </div>
                <button type="button" @click="modalTagihanUnified = false" class="text-gray-400 hover:text-gray-600 transition text-lg">&times;</button>
            </div>

            <!-- Tab Switcher -->
            <div class="px-5 pt-3 bg-gray-50/80 border-b border-gray-200 flex gap-2">
                <button type="button" @click="tabTagihan = 'bulanan'"
                    :class="tabTagihan === 'bulanan' ? 'border-emerald-600 text-emerald-800 bg-white font-bold shadow-xs' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                    class="px-3 py-2 text-xs rounded-t-lg border-b-2 flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Tagihan Bulanan (Massal / Satu Santri)</span>
                </button>
                <button type="button" @click="tabTagihan = 'insidental'"
                    :class="tabTagihan === 'insidental' ? 'border-emerald-600 text-emerald-800 bg-white font-bold shadow-xs' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                    class="px-3 py-2 text-xs rounded-t-lg border-b-2 flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Tagihan Khusus / Insidental</span>
                </button>
            </div>

            <!-- TAB 1: TAGIHAN BULANAN RUTIN (MASSAL / PER KELAS / PERORANGAN) -->
            <form x-show="tabTagihan === 'bulanan'" method="POST" action="{{ route('admin.pembayaran.tagihan.bulanan') }}" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                @csrf
                <div class="ta-modal-body space-y-4 text-xs overflow-y-auto flex-1 min-h-0">
                    <!-- Sasaran Penerima Tagihan -->
                    <div class="p-3 bg-emerald-50/50 border border-emerald-200 rounded-xl space-y-2">
                        <label class="block font-bold text-gray-800 text-[11px] uppercase tracking-wider">Sasaran Santri yang Ditagihkan *</label>
                        <select name="target_jenjang" x-model="targetBulananTipe" required class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-semibold text-gray-800 focus:border-emerald-500 outline-none bg-white">
                            <option value="all">Semua Santri Aktif (MTs &amp; MA Serentak)</option>
                            <option value="MTs">Khusus Santri MTs</option>
                            <option value="MA">Khusus Santri MA</option>
                            <option value="kelas">Per Rombongan Kelas Tertentu</option>
                            <option value="santri">Satu Santri Spesifik (Penunggak / Perorangan)</option>
                        </select>

                        <!-- Pilihan jika per kelas -->
                        <div x-show="targetBulananTipe === 'kelas'" x-cloak class="pt-1">
                            <label class="block font-medium text-gray-700 mb-1">Pilih Kelas</label>
                            <select name="target_kelas" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 bg-white">
                                @foreach($classrooms as $c)
                                    <option value="{{ $c->nama_kelas }}">{{ $c->nama_kelas }} ({{ $c->jenjang }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilihan jika Satu Santri Spesifik -->
                        <div x-show="targetBulananTipe === 'santri'" x-cloak class="pt-1">
                            <label class="block font-bold text-emerald-900 mb-1">Pilih Nama Santri (Termasuk Alumni Berhutang):</label>
                            <select name="target_santri_id" class="h-10 w-full rounded-lg border border-emerald-400 px-3 text-xs font-semibold text-gray-900 bg-white">
                                @foreach($activeStudents as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_lengkap }} (NIS: {{ $s->nis }} - {{ $s->kelas }}) {{ $s->status === 'Alumni' ? '[Alumni]' : '' }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-emerald-700 mt-1 font-medium">Tagihan hanya akan dibuat untuk santri ini. Santri lain yang sudah lunas tidak terganggu.</p>
                        </div>
                    </div>

                    <!-- Periode Bulan & Tahun -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Pilih Bulan *</label>
                            <select name="bulan" required class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none bg-white">
                                @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $m)
                                    <option value="{{ $m }}" {{ date('F') == $m || (date('m') == 5 && $m == 'Mei') || (date('m') == 9 && $m == 'September') ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Tahun *</label>
                            <input type="text" name="tahun" value="{{ date('Y') }}" required class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none bg-white">
                        </div>
                    </div>

                    <!-- Pilihan Pos Biaya Bulanan yang diterbitkan -->
                    <div class="space-y-1.5 p-3 rounded-xl border border-gray-200 bg-gray-50/70">
                        <label class="block font-bold text-gray-800 text-[11px] uppercase tracking-wider mb-2">Pilih Pos Biaya yang Diterbitkan:</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-start gap-2 p-2 rounded-lg bg-white border border-gray-200 cursor-pointer hover:border-emerald-500 transition">
                                <input type="checkbox" name="pos_bulanan[]" value="MAKAN" checked class="mt-0.5 text-emerald-600 rounded">
                                <div class="text-xs">
                                    <span class="font-semibold text-gray-800">Uang Makan</span>
                                    <span class="block text-[10px] text-gray-500">Mukim: Rp 300rb</span>
                                </div>
                            </label>
                            <label class="flex items-start gap-2 p-2 rounded-lg bg-white border border-gray-200 cursor-pointer hover:border-emerald-500 transition">
                                <input type="checkbox" name="pos_bulanan[]" value="TAB" checked class="mt-0.5 text-emerald-600 rounded">
                                <div class="text-xs">
                                    <span class="font-semibold text-gray-800">Tabungan Wajib</span>
                                    <span class="block text-[10px] text-gray-500">Rp 25.000</span>
                                </div>
                            </label>
                            <label class="flex items-start gap-2 p-2 rounded-lg bg-white border border-emerald-200 bg-emerald-50/30 cursor-pointer hover:border-emerald-500 transition">
                                <input type="checkbox" name="pos_bulanan[]" value="SOT" checked class="mt-0.5 text-emerald-600 rounded">
                                <div class="text-xs">
                                    <span class="font-bold text-emerald-900">SOT (Iuran SOT)</span>
                                    <span class="block text-[10px] text-emerald-700 font-semibold">MTs: 55rb | MA: 75rb</span>
                                </div>
                            </label>
                            <label class="flex items-start gap-2 p-2 rounded-lg bg-white border border-gray-200 cursor-pointer hover:border-emerald-500 transition">
                                <input type="checkbox" name="pos_bulanan[]" value="SYAHRIYAH" checked class="mt-0.5 text-emerald-600 rounded">
                                <div class="text-xs">
                                    <span class="font-semibold text-gray-800">Syahriyah SPP</span>
                                    <span class="block text-[10px] text-gray-500">Sesuai Jenjang &amp; Asrama</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Batas Tanggal Jatuh Tempo</label>
                        <input type="date" name="jatuh_tempo" value="{{ date('Y-m-10') }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none bg-white">
                        <p class="text-[11px] text-gray-400 mt-1">Default tanggal 10 pada bulan bersangkutan.</p>
                    </div>

                    <!-- Info Box Otomatisasi SKTM -->
                    <div class="rounded-xl border border-blue-200 bg-blue-50/70 p-3 text-blue-900 space-y-1 text-xs">
                        <div class="font-bold flex items-center gap-1.5 text-blue-800">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Fitur Otomatisasi Terpadu:
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-gray-600 text-[11px] pl-1">
                            <li>Tarif <strong>SOT</strong> otomatis: <strong>MTs = Rp 55.000</strong>, <strong>MA = Rp 75.000</strong>.</li>
                            <li>Bagi santri yang memiliki <strong>SKTM</strong> atau <strong>Beasiswa</strong>, potongan dihitung otomatis.</li>
                            <li>Tagihan pada bulan dan santri yang sama tidak akan terduplikasi.</li>
                        </ul>
                    </div>
                </div>

                <div class="ta-modal-footer">
                    <button type="button" @click="modalTagihanUnified = false" class="ta-btn-outline text-xs">
                        Batal
                    </button>
                    <button type="submit" class="ta-btn-primary text-xs bg-emerald-600 hover:bg-emerald-700">
                        Terbitkan Tagihan Sekarang
                    </button>
                </div>
            </form>

            <!-- TAB 2: TAGIHAN KHUSUS / INSIDENTAL (TAMBAHAN / KUSTOM) -->
            <form x-show="tabTagihan === 'insidental'" method="POST" action="{{ route('admin.pembayaran.tagihan.tambahan') }}" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                @csrf
                <div class="ta-modal-body space-y-3.5 text-xs overflow-y-auto flex-1 min-h-0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Kategori Tagihan -->
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Kategori Tagihan *</label>
                            <select name="kategori" x-model="selectedKategori" @change="onPosOrKategoriChange()" required class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-semibold text-gray-800 focus:border-brand-500 outline-none bg-white">
                                <option value="tambahan">Biaya Tambahan / Insidental</option>
                                <option value="bulanan">Biaya Bulanan (Rutin Satu Pos)</option>
                                <option value="sekali_bayar">Sekali Bayar (Pangkal / Gedung)</option>
                                <option value="tahunan">Biaya Tahunan / Registrasi</option>
                            </select>
                        </div>

                        <!-- Pos Biaya -->
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Pos Biaya (Sesuai Buku Kas) *</label>
                            <select name="pos_biaya" x-model="selectedPosTambahan" @change="onPosOrKategoriChange()" required class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-semibold text-gray-800 focus:border-brand-500 outline-none bg-white">
                                <optgroup label="Pos Biaya Insidental &amp; Lainnya">
                                    @foreach($posBiayaList as $key => $label)
                                        @if(!in_array($key, ['MAKAN', 'TAB', 'SOT', 'SYAHRIYAH']))
                                            <option value="{{ $key }}">{{ $key }} ({{ $label }})</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                                <optgroup label="Biaya Bulanan (Rutin)">
                                    <option value="MAKAN">MAKAN (Uang Makan 3x Sehari)</option>
                                    <option value="TAB">TAB (Tabungan Wajib Santri)</option>
                                    <option value="SOT">SOT (Iuran SOT - MA/MTs Beda)</option>
                                    <option value="SYAHRIYAH">SYAHRIYAH (Syahriyah Pendidikan SPP)</option>
                                </optgroup>
                                <optgroup label="Pos Biaya Baru / Di Luar Standar">
                                    <option value="__CUSTOM__">+ Pos Biaya Baru / Kustom (Lainnya)...</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <!-- Periode Bulan & Tahun (Muncul otomatis jika Kategori Biaya Bulanan dipilih) -->
                    <div x-show="selectedKategori === 'bulanan'" class="p-3 bg-blue-50/70 border border-blue-200 rounded-xl space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-blue-900 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                Periode Tagihan Bulanan
                            </span>
                            <span class="text-[10px] text-blue-600 bg-white px-2 py-0.5 rounded-full border border-blue-200 font-medium">Bulan &amp; Tahun</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Pilih Bulan *</label>
                                <select name="bulan" x-model="selectedBulan" @change="autoFillJudul()" class="h-9 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs font-semibold text-gray-800 outline-none">
                                    @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $m)
                                        <option value="{{ $m }}">{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Tahun *</label>
                                <input type="text" name="tahun" x-model="selectedTahun" @input="autoFillJudul()" class="h-9 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs font-semibold text-gray-800 outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Input Pos Biaya Kustom jika dipilih di luar pos -->
                    <div x-show="selectedPosTambahan === '__CUSTOM__'" x-cloak class="p-3 bg-gray-50 border border-gray-200 rounded-lg space-y-1.5 transition">
                        <label class="block font-medium text-gray-800 text-xs">
                            Nama Pos Biaya Baru / Kustom *
                        </label>
                        <input type="text" name="pos_biaya_kustom" placeholder="Nama pos biaya baru..." class="h-10 w-full uppercase rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-900 focus:border-brand-500 outline-none bg-white">
                    </div>

                    <!-- Judul / Deskripsi Tagihan -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block font-medium text-gray-700">Judul / Deskripsi Tagihan *</label>
                            <button type="button" @click="autoFillJudul()" class="text-[11px] text-emerald-600 hover:text-emerald-700 font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Reset / Auto Judul</span>
                            </button>
                        </div>
                        <input type="text" name="judul_tagihan" x-model="judulTagihan" required placeholder="Judul tagihan..." class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none bg-white">
                    </div>

                    <!-- Nominal Biaya -->
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">
                            Nominal Biaya (Rp) *
                        </label>
                        <input type="number" name="nominal" x-model="inputNominal" required min="0" step="1000" placeholder="0" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-bold text-gray-900 focus:border-brand-500 outline-none bg-white">
                    </div>

                    <!-- Sasaran Target Santri -->
                    <div class="border border-gray-200 rounded-xl p-3 bg-gray-50/70 space-y-2.5">
                        <label class="block font-bold text-gray-800 text-[11px] uppercase tracking-wider">Sasaran Penerima Tagihan</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <label class="flex items-center gap-1.5 p-2 rounded-lg bg-white border border-gray-200 cursor-pointer text-xs font-medium text-gray-700 hover:border-gray-400">
                                <input type="radio" name="sasaran_tipe" value="semua" x-model="sasaranTipe" checked class="text-emerald-600">
                                Semua Santri
                            </label>
                            <label class="flex items-center gap-1.5 p-2 rounded-lg bg-white border border-gray-200 cursor-pointer text-xs font-medium text-gray-700 hover:border-gray-400">
                                <input type="radio" name="sasaran_tipe" value="tingkat" x-model="sasaranTipe" class="text-emerald-600">
                                Tingkat (VII-XII)
                            </label>
                            <label class="flex items-center gap-1.5 p-2 rounded-lg bg-white border border-gray-200 cursor-pointer text-xs font-medium text-gray-700 hover:border-gray-400">
                                <input type="radio" name="sasaran_tipe" value="kelas" x-model="sasaranTipe" class="text-emerald-600">
                                Per Kelas
                            </label>
                            <label class="flex items-center gap-1.5 p-2 rounded-lg bg-white border border-gray-200 cursor-pointer text-xs font-medium text-gray-700 hover:border-gray-400">
                                <input type="radio" name="sasaran_tipe" value="santri" x-model="sasaranTipe" class="text-emerald-600">
                                Satu Santri
                            </label>
                        </div>

                        <!-- Dynamic Value Selector -->
                        <div x-show="sasaranTipe === 'tingkat'" class="pt-1.5">
                            <label class="block font-medium text-gray-700 mb-1">Pilih Tingkat / Angkatan</label>
                            <select name="sasaran_nilai" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 bg-white">
                                <option value="VII">Tingkat VII (Kelas 7 MTs)</option>
                                <option value="VIII">Tingkat VIII (Kelas 8 MTs)</option>
                                <option value="IX">Tingkat IX (Kelas 9 MTs - Wisuda)</option>
                                <option value="X">Tingkat X (Kelas 10 MA - Ziarah)</option>
                                <option value="XI">Tingkat XI (Kelas 11 MA)</option>
                                <option value="XII">Tingkat XII (Kelas 12 MA - Akhirusanah)</option>
                            </select>
                        </div>

                        <div x-show="sasaranTipe === 'kelas'" class="pt-1.5">
                            <label class="block font-medium text-gray-700 mb-1">Pilih Rombongan Belajar (Kelas)</label>
                            <select name="sasaran_nilai" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 bg-white">
                                @foreach($classrooms as $c)
                                    <option value="{{ $c->nama_kelas }}">{{ $c->nama_kelas }} ({{ $c->jenjang }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="sasaranTipe === 'santri'" class="pt-1.5">
                            <label class="block font-medium text-gray-700 mb-1">Pilih Santri Spesifik</label>
                            <select name="sasaran_nilai" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 bg-white">
                                @foreach($activeStudents as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_lengkap }} (NIS: {{ $s->nis }} - {{ $s->kelas }}) {{ $s->status === 'Alumni' ? '[Alumni]' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Tanggal Jatuh Tempo</label>
                            <input type="date" name="jatuh_tempo" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none bg-white">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Keterangan / Catatan</label>
                            <input type="text" name="keterangan" placeholder="Opsional" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none bg-white">
                        </div>
                    </div>
                </div>

                <div class="ta-modal-footer">
                    <button type="button" @click="modalTagihanUnified = false" class="ta-btn-outline text-xs">
                        Batal
                    </button>
                    <button type="submit" class="ta-btn-primary text-xs bg-gray-900 hover:bg-gray-800">
                        Buat Tagihan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: TANGGUHKAN TAGIHAN KE WISUDA -->
    <div x-show="modalTangguhkan" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalTangguhkan = false" class="ta-modal max-w-lg">
            <div class="ta-modal-header">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Tangguhkan Tagihan Sampai Wisuda</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pencatatan penangguhan tagihan sampai kelulusan</p>
                </div>
                <button type="button" @click="modalTangguhkan = false" class="text-gray-400 hover:text-gray-600 transition text-lg">&times;</button>
            </div>

            <form method="POST" :action="'{{ url('/admin/pembayaran-tagihan') }}/' + targetBill.id + '/tangguhkan'">
                @csrf
                <div class="ta-modal-body space-y-4 text-xs">
                    <!-- Info Tagihan Target -->
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg space-y-1">
                        <div class="text-xs text-gray-600">
                            Santri: <strong class="text-gray-900" x-text="targetBill.santri"></strong>
                        </div>
                        <div class="text-xs text-gray-600">
                            Tagihan: <strong class="text-gray-900" x-text="targetBill.judul"></strong>
                        </div>
                        <div class="text-xs text-gray-600">
                            Sisa Tunggakan: <strong class="text-rose-600 font-mono" x-text="'Rp ' + targetBill.sisa_rp"></strong>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Catatan Penangguhan</label>
                        <textarea name="catatan_penangguhan" rows="3" class="w-full rounded-lg border border-gray-300 p-3 text-xs text-gray-800 focus:border-brand-500 outline-none" placeholder="Catatan penangguhan (opsional)..."></textarea>
                    </div>
                </div>

                <div class="ta-modal-footer">
                    <button type="button" @click="modalTangguhkan = false" class="ta-btn-outline text-xs">
                        Batal
                    </button>
                    <button type="submit" class="ta-btn-primary text-xs">
                        Simpan Penangguhan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: PEMUTIHAN TAGIHAN -->
    <div x-show="modalNolkan" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalNolkan = false" class="ta-modal max-w-lg">
            <div class="ta-modal-header">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Pemutihan Tagihan</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Bebaskan sisa tunggakan tagihan santri</p>
                </div>
                <button type="button" @click="modalNolkan = false" class="text-gray-400 hover:text-gray-600 transition text-lg">&times;</button>
            </div>

            <form method="POST" :action="'{{ url('/admin/pembayaran-tagihan') }}/' + targetBill.id + '/nolkan'">
                @csrf
                <div class="ta-modal-body space-y-4 text-xs">
                    <!-- Info Tagihan Target -->
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg space-y-1">
                        <div class="text-xs text-gray-600">
                            Santri: <strong class="text-gray-900" x-text="targetBill.santri"></strong>
                        </div>
                        <div class="text-xs text-gray-600">
                            Tagihan: <strong class="text-gray-900" x-text="targetBill.judul"></strong>
                        </div>
                        <div class="text-xs text-gray-600">
                            Nominal yang Dinolkan: <strong class="text-rose-600 font-mono" x-text="'Rp ' + targetBill.sisa_rp"></strong>
                        </div>
                    </div>

                    <div class="p-2.5 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800">
                        Tagihan yang dinolkan akan berstatus lunas. Catatan pembebasan wajib diisi.
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Alasan Pembebasan Tagihan <span class="text-rose-500">*</span></label>
                        <textarea name="catatan_pembebasan" required rows="3" class="w-full rounded-lg border border-gray-300 p-3 text-xs text-gray-800 focus:border-brand-500 outline-none" placeholder="Alasan pembebasan tagihan..."></textarea>
                    </div>
                </div>

                <div class="ta-modal-footer">
                    <button type="button" @click="modalNolkan = false" class="ta-btn-outline text-xs">
                        Batal
                    </button>
                    <button type="submit" class="ta-btn-primary text-xs">
                        Simpan Pemutihan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 5: MINTA SURAT KERINGANAN / DISPENSASI KE SANTRI -->
    <div x-show="modalMintaSurat" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalMintaSurat = false" class="ta-modal max-w-lg">
            <div class="ta-modal-header">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Permintaan Surat Keringanan</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Minta santri mengunggah surat dispensasi atau SKTM</p>
                </div>
                <button type="button" @click="modalMintaSurat = false" class="text-gray-400 hover:text-gray-600 transition text-lg">&times;</button>
            </div>

            <form method="POST" :action="'{{ url('/admin/pembayaran-tagihan') }}/' + targetBill.id + '/minta-surat'">
                @csrf
                <div class="ta-modal-body space-y-4 text-xs">
                    <!-- Info Tagihan Target -->
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg space-y-1">
                        <div class="text-xs text-gray-600">
                            Santri: <strong class="text-gray-900" x-text="targetBill.santri"></strong>
                        </div>
                        <div class="text-xs text-gray-600">
                            Tagihan: <strong class="text-gray-900" x-text="targetBill.judul"></strong>
                        </div>
                        <div class="text-xs text-gray-600">
                            Sisa Tunggakan: <strong class="text-rose-600 font-mono" x-text="'Rp ' + targetBill.sisa_rp"></strong>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Jenis Surat yang Diminta *</label>
                        <select name="jenis_surat_diminta" required class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 font-medium focus:border-brand-500 outline-none">
                            <option value="Surat Keterangan Tidak Mampu (SKTM) dari Kelurahan/Desa">Surat Keterangan Tidak Mampu (SKTM) Kelurahan/Desa</option>
                            <option value="Surat Kematian Orang Tua (Ayah / Ibu)">Surat Kematian Orang Tua (Ayah / Ibu)</option>
                            <option value="Surat Permohonan Keringanan Wali Santri">Surat Permohonan Keringanan Wali Santri</option>
                            <option value="Kartu Indonesia Pintar (KIP) / PKH">Kartu Indonesia Pintar (KIP) / PKH</option>
                            <option value="Surat Keterangan Musibah / Sakit">Surat Keterangan Musibah / Sakit</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Petunjuk untuk Santri</label>
                        <textarea name="instruksi_surat" rows="3" class="w-full rounded-lg border border-gray-300 p-3 text-xs text-gray-800 focus:border-brand-500 outline-none" placeholder="Petunjuk untuk santri (opsional)..."></textarea>
                    </div>
                </div>

                <div class="ta-modal-footer">
                    <button type="button" @click="modalMintaSurat = false" class="ta-btn-outline text-xs">
                        Batal
                    </button>
                    <button type="submit" class="ta-btn-primary text-xs">
                        Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 6: REVIEW SURAT DISPENSASI SANTRI & TINDAK LANJUT -->
    <div x-show="modalReviewSurat" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalReviewSurat = false" class="ta-modal max-w-xl">
            <div class="ta-modal-header">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Review Berkas Surat Keringanan</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Periksa berkas yang diunggah santri</p>
                </div>
                <button type="button" @click="modalReviewSurat = false" class="text-gray-400 hover:text-gray-600 transition text-lg">&times;</button>
            </div>

            <div class="ta-modal-body space-y-4 text-xs">
                <!-- Info Berkas & Santri -->
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg space-y-1">
                    <div class="text-xs text-gray-600">
                        Santri: <strong class="text-gray-900" x-text="targetBill.santri"></strong>
                    </div>
                    <div class="text-xs text-gray-600">
                        Tagihan: <strong class="text-gray-900" x-text="targetBill.judul"></strong>
                    </div>
                    <div class="text-xs text-gray-600">
                        Jenis Surat: <strong class="text-gray-900" x-text="targetBill.jenis_surat"></strong>
                    </div>
                </div>

                <!-- Preview File Surat (Gambar / PDF) -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 text-center">
                    <span class="text-xs font-semibold text-gray-600 block mb-2">Dokumen Berkas Santri:</span>
                    
                    <template x-if="targetBill.file_surat && targetBill.file_surat.toLowerCase().endsWith('.pdf')">
                        <div class="py-6 space-y-2">
                            <svg class="w-12 h-12 text-rose-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-gray-600 font-medium">Dokumen Format PDF</p>
                            <a :href="targetBill.file_surat" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition">
                                Buka Dokumen PDF di Tab Baru &rarr;
                            </a>
                        </div>
                    </template>

                    <template x-if="targetBill.file_surat && !targetBill.file_surat.toLowerCase().endsWith('.pdf')">
                        <div class="space-y-2">
                            <img :src="targetBill.file_surat" alt="Berkas Surat" class="max-h-72 mx-auto rounded-lg border border-gray-200 shadow-sm object-contain">
                            <a :href="targetBill.file_surat" target="_blank" class="text-brand-600 hover:underline text-xs font-semibold inline-block">
                                Buka Gambar Ukuran Penuh &rarr;
                            </a>
                        </div>
                    </template>
                </div>

                <!-- FORM 1: SETUJUI SURAT (BEBAS PENUH ATAU POTONGAN SEBAGIAN) -->
                <form method="POST" :action="'{{ url('/admin/pembayaran-tagihan') }}/' + targetBill.id + '/setujui-surat'" class="p-3.5 bg-emerald-50/70 border border-emerald-200 rounded-lg space-y-3">
                    @csrf
                    <div class="flex items-center justify-between">
                        <div class="font-bold text-emerald-900 text-xs flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Setujui Berkas &amp; Terapkan Keringanan</span>
                        </div>
                        <span class="text-[11px] font-mono font-bold text-rose-700 bg-white px-2 py-0.5 rounded border border-emerald-200">
                            Sisa Saat Ini: <span x-text="'Rp ' + targetBill.sisa_rp"></span>
                        </span>
                    </div>

                    <!-- Pilihan Tipe Persetujuan -->
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <label class="flex items-start gap-2 p-2 rounded-lg border cursor-pointer transition"
                               :class="tipePersetujuanSurat === 'bebas_penuh' ? 'bg-white border-emerald-500 shadow-xs' : 'bg-white/50 border-gray-200 hover:border-emerald-300'">
                            <input type="radio" name="tipe_persetujuan" value="bebas_penuh" x-model="tipePersetujuanSurat" class="mt-0.5 text-emerald-600">
                            <div>
                                <span class="font-bold text-gray-900 block">Bebas Penuh (100%)</span>
                                <span class="text-[10px] text-gray-500">Nol-kan tagihan jadi Lunas Rp 0</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-2 p-2 rounded-lg border cursor-pointer transition"
                               :class="tipePersetujuanSurat === 'potongan_sebagian' ? 'bg-white border-emerald-500 shadow-xs' : 'bg-white/50 border-gray-200 hover:border-emerald-300'">
                            <input type="radio" name="tipe_persetujuan" value="potongan_sebagian" x-model="tipePersetujuanSurat" class="mt-0.5 text-emerald-600">
                            <div>
                                <span class="font-bold text-gray-900 block">Potongan Sebagian</span>
                                <span class="text-[10px] text-gray-500">Tentukan nominal diskon keringanan</span>
                            </div>
                        </label>
                    </div>

                    <!-- Input Nominal Potongan jika potongan sebagian -->
                    <div x-show="tipePersetujuanSurat === 'potongan_sebagian'" class="p-2.5 rounded-lg bg-white border border-emerald-300 space-y-1">
                        <label class="block font-bold text-gray-800 text-[11px]">Besar Nominal Potongan Keringanan (Rp):</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400 font-bold text-xs pointer-events-none">Rp</span>
                            <input type="number" name="nominal_potongan" x-model="nominalPotonganSurat" :max="targetBill.sisa" min="1000" class="h-9 w-full rounded-lg border border-gray-300 pl-8 pr-3 text-xs font-mono font-bold text-gray-900 focus:border-emerald-500">
                        </div>
                        <p class="text-[10px] text-gray-500">
                            Sisa tagihan setelah potongan: 
                            <strong class="text-rose-600 font-mono" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(Math.max(0, targetBill.sisa - nominalPotonganSurat))"></strong>
                        </p>
                    </div>

                    <div>
                        <input type="text" name="catatan_persetujuan" placeholder="Catatan persetujuan / referensi SK (opsional)" class="h-9 w-full rounded-lg border border-emerald-300 px-3 text-xs bg-white text-gray-800 focus:border-emerald-500">
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-theme-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="tipePersetujuanSurat === 'bebas_penuh' ? 'Setujui &amp; Bebaskan Penuh (Lunas)' : 'Setujui &amp; Terapkan Potongan'"></span>
                    </button>
                </form>

                <!-- FORM 2: TOLAK BERKAS DENGAN ALASAN -->
                <form method="POST" :action="'{{ url('/admin/pembayaran-tagihan') }}/' + targetBill.id + '/tolak-surat'" class="p-3.5 bg-rose-50/70 border border-rose-200 rounded-lg space-y-2.5">
                    @csrf
                    <div class="font-bold text-rose-900 text-xs">
                        Tolak Berkas Surat
                    </div>
                    <p class="text-[11px] text-rose-800">Tuliskan alasan penolakan berkas surat.</p>

                    <div>
                        <input type="text" name="alasan_penolakan" required placeholder="Alasan penolakan..." class="h-9 w-full rounded-lg border border-rose-300 px-3 text-xs bg-white text-gray-800">
                    </div>

                    <button type="submit" class="w-full py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-theme-xs transition">
                        Tolak Berkas
                    </button>
                </form>
            </div>

            <div class="ta-modal-footer">
                <button type="button" @click="modalReviewSurat = false" class="ta-btn-outline text-xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL: UPLOAD SANTRI MASSAL BESERTA TUNGGAKAN LALU (TEMPLATE RESMI TERPADU) -->
    <div x-show="modalImportTunggakan" class="ta-modal-backdrop" style="display: none;" x-cloak>
        <div class="ta-modal max-w-lg" @click.away="modalImportTunggakan = false">
            <div class="ta-modal-header">
                <div>
                    <h3 class="font-bold text-gray-800 text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600 fill-current" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M8.5 13.5l2 2.5-2 2.5h1.5l1.25-1.75L12.5 18.5H14l-2-2.5 2-2.5h-1.5l-1.25 1.75L10 13.5H8.5z"/></svg>
                        <span>Upload Santri &amp; Tagihan Tunggakan Lalu</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Unggah data santri baru/lama serentak via Excel beserta rincian tunggakan masa lalu.</p>
                </div>
                <button type="button" @click="modalImportTunggakan = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
            </div>

            <form action="{{ route('admin.siswa.importExcel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="ta-modal-body space-y-4 text-xs">
                    <!-- Langkah 1: Unduh Template Terpadu -->
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-emerald-950 block">Langkah 1: Unduh Format Template Resmi</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-600 text-white uppercase tracking-wider">Format Resmi Terpadu</span>
                        </div>
                        <p class="text-gray-600 leading-relaxed text-[11px]">
                            Template ini telah disatukan dengan Data Santri dan dilengkapi kolom biodata, alamat, serta tagihan masa lalu:
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[10.5px] text-gray-700 bg-white p-2.5 rounded-lg border border-emerald-100">
                            <div><strong class="text-emerald-700">✓ Kolom A-F:</strong> Data Pokok Wajib (Nama, NIS, L/P, Tgl Lahir, Thn Masuk, Kelas)</div>
                            <div><strong class="text-blue-700">✓ Kolom G-L:</strong> Biodata &amp; Alamat (Tempat Lahir, NIK, Alamat, Asrama)</div>
                            <div><strong class="text-purple-700">✓ Kolom M-Q:</strong> Orang Tua &amp; Sekolah (Wali, WA, Pekerjaan, Ibu, Asal SD/MI)</div>
                            <div><strong class="text-teal-700">✓ Kolom R-U:</strong> 4 Pos Bulanan (Makan, Syahriyah, Sot, Tabungan)</div>
                            <div class="sm:col-span-2"><strong class="text-amber-700">✓ Kolom V-AE:</strong> 10 Pos Tagihan Resmi (Pangkal, Gedung, Kertas, Kesehatan, Kegiatan, Pg, Almari, Pendaftaran, Kenaikan, Pengembangan Pondok)</div>
                            <div class="sm:col-span-2"><strong class="text-slate-700">✓ Kolom AF dst:</strong> Catatan Tagihan. <em>✨ Bisa tambah kolom sendiri di kolom AG, AH, dst jika ada pos biaya baru!</em></div>
                        </div>
                        <div class="pt-1">
                            <a href="{{ route('admin.siswa.downloadTemplate') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs shadow-theme-xs transition">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M8.5 13.5l2 2.5-2 2.5h1.5l1.25-1.75L12.5 18.5H14l-2-2.5 2-2.5h-1.5l-1.25 1.75L10 13.5H8.5z"/></svg>
                                <span>Unduh Template Excel Resmi (.xlsx)</span>
                            </a>
                        </div>
                    </div>

                    <!-- Langkah 2: Upload File -->
                    <div class="space-y-2">
                        <label class="block font-bold text-gray-800 uppercase tracking-wider">Langkah 2: Pilih File Excel yang Telah Diisi</label>
                        <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="ta-input text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
                        <p class="text-[11px] text-gray-500">Mendukung file Microsoft Excel <code>.xlsx</code> atau <code>.xls</code> (Maksimal 15 MB).</p>
                    </div>

                    <!-- Info Integrasi Finansial & Alumni -->
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-[11px] space-y-1">
                        <span class="font-bold block text-amber-950">💡 Panduan Pengisian Bendahara:</span>
                        <ul class="list-disc list-inside space-y-0.5 text-gray-700 text-[10.5px]">
                            <li><strong>Santri Lama / Alumni:</strong> Isi kolom NIS sesuai NIS lama santri agar tagihan langsung tersambung tanpa akun ganda.</li>
                            <li><strong>Santri Baru:</strong> Password otomatis dibuat dari tanggal lahir (format DDMMYYYY).</li>
                            <li><strong>Tagihan Kasir:</strong> Semua tunggakan langsung muncul di Kasir POS dan Rekap Keuangan.</li>
                        </ul>
                    </div>
                </div>

                <div class="ta-modal-footer">
                    <button type="button" @click="modalImportTunggakan = false" class="ta-btn-sm-outline">
                        Batal
                    </button>
                    <button type="submit" class="ta-btn-sm-primary bg-amber-600 hover:bg-amber-700 border-amber-600">
                        Mulai Proses Upload &amp; Terbitkan Tagihan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

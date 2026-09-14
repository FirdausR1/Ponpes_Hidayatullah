@extends('admin.layout')

@section('title', 'Bank & Template Soal CBT')

@section('styles')
<!-- KaTeX CSS untuk rumus Matematika -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
<!-- Google Fonts: Amiri & Scheherazade New untuk Teks Arab Berharakat & Gundul -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
<style>
    .arabic-text {
        font-family: 'Amiri', 'Scheherazade New', serif;
        direction: rtl;
        text-align: right;
        font-size: 1.35rem;
        line-height: 2.2;
    }
    .katex { font-size: 1.15em !important; }
</style>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header & Action Buttons -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Bank Soal CBT</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola bank butir soal ujian seleksi masuk santri baru, format rumus Matematika (KaTeX), dan Bahasa Arab.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.cbt.soal.downloadTemplate') }}" class="ta-btn-outline border-emerald-300 text-emerald-700 hover:bg-emerald-50 bg-white" title="Unduh file template Excel/CSV untuk input soal massal">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download Template CSV
            </a>
            <a href="{{ route('admin.cbt.soal.export', ['kategori' => $kategori]) }}" class="ta-btn-outline" title="Export butir soal bank saat ini ke file CSV">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Soal CSV
            </a>
            <button type="button" onclick="document.getElementById('modalImportCsv').classList.remove('hidden')" class="ta-btn-outline" title="Upload dan impor file CSV berisi banyak soal">
                <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                Import CSV
            </button>
            <button type="button" onclick="document.getElementById('modalTemplate').classList.remove('hidden')" class="ta-btn-outline text-gray-600" title="Isi database dengan paket contoh soal bawaan pesantren">
                <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Muat Soal Bawaan
            </button>
            <a href="{{ route('admin.cbt.cetakSoal', ['kategori' => $kategori]) }}" target="_blank" class="ta-btn-outline">
                <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Soal
            </a>
            <a href="{{ route('admin.cbt.soal.create') }}" class="ta-btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Soal Manual
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Soal Bank</span>
                <span class="badge-primary">Aktif</span>
            </div>
            <div class="text-2xl font-bold text-gray-800 mt-2">{{ $totalQuestions }} <span class="text-xs font-normal text-gray-400">butir</span></div>
        </div>

        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Rumus Matematika &amp; IPA</span>
                <span class="badge-gray">KaTeX LaTeX</span>
            </div>
            <div class="text-2xl font-bold text-gray-800 mt-2">{{ $totalMath }} <span class="text-xs font-normal text-gray-400">soal</span></div>
        </div>

        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Bahasa Arab</span>
                <span class="badge-success">Harakat / Gundul</span>
            </div>
            <div class="text-2xl font-bold text-gray-800 mt-2">{{ $totalArabic }} <span class="text-xs font-normal text-gray-400">soal</span></div>
        </div>
    </div>

    <!-- Filter Kategori & Search -->
    <div class="ta-card p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.cbt.soal.index') }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ empty($kategori) ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua ({{ $totalQuestions }})
            </a>
            @foreach($categories as $cat)
                @php $cCount = $categoryCounts[$cat] ?? 0; @endphp
                <a href="{{ route('admin.cbt.soal.index', ['kategori' => $cat]) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5 {{ $kategori === $cat ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    <span>{{ $cat }}</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full font-bold {{ $kategori === $cat ? 'bg-white/20 text-white' : ($cCount > 0 ? 'bg-gray-200 text-gray-700' : 'bg-rose-100 text-rose-600') }}">
                        {{ $cCount }}
                    </span>
                </a>
            @endforeach
            <a href="{{ route('admin.cbt.pengaturan') }}" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-brand-600 hover:bg-brand-50 border border-brand-200 transition flex items-center gap-1" title="Kelola Kategori Mata Pelajaran di Pengaturan">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>Kelola Kategori</span>
            </a>
        </div>
        <form method="GET" action="{{ route('admin.cbt.soal.index') }}" class="flex items-center gap-2">

            @if(!empty($kategori))
                <input type="hidden" name="kategori" value="{{ $kategori }}">
            @endif
            <div class="relative">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari isi soal..."
                       class="ta-input w-48 sm:w-64 pl-8 py-1.5 text-xs">
                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="ta-btn-sm-primary">
                Cari
            </button>
            @if(!empty($search) || !empty($kategori))
                <a href="{{ route('admin.cbt.soal.index') }}" class="ta-btn-sm-outline">Reset</a>
            @endif
        </form>
    </div>

    <!-- Daftar Soal Cards -->
    <div class="space-y-4" id="soalListContainer">
        @forelse($questions as $index => $q)
            <div class="ta-card p-5">
                <div class="flex items-start justify-between gap-4 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="badge-gray">
                            #{{ $questions->firstItem() + $index }}
                        </span>
                        <span class="badge-primary">
                            {{ $q->kategori }}
                        </span>
                        @if($q->is_math)
                            <span class="badge-gray">
                                Rumus MTK
                            </span>
                        @endif
                        @if($q->is_arabic)
                            <span class="badge-success">
                                Bahasa Arab
                            </span>
                        @endif
                        @if(!empty($q->gambar))
                            <span class="badge-gray">
                                Ada Gambar
                            </span>
                        @endif
                        <span class="text-xs text-gray-400">Bobot: {{ $q->bobot }} poin</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.cbt.soal.edit', $q->id) }}"
                           class="p-1.5 text-gray-400 hover:text-brand-500 hover:bg-gray-100 rounded-lg transition" title="Edit Soal">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>
                        <form action="{{ route('admin.cbt.soal.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini dari Bank Soal?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Soal">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Konten Soal -->
                <div class="py-3 text-gray-800 text-sm md:text-base leading-relaxed render-math {{ $q->is_arabic ? 'arabic-text' : '' }}">
                    {!! nl2br(e($q->soal)) !!}
                </div>

                <!-- Gambar Soal Jika Ada -->
                @if(!empty($q->gambar))
                    <div class="mb-3">
                        <img src="{{ asset($q->gambar) }}" alt="Gambar Soal #{{ $q->id }}" class="max-h-56 max-w-full rounded-lg border border-gray-200 bg-white p-1 object-contain cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src, '_blank')" title="Klik untuk memperbesar gambar">
                    </div>
                @endif

                <!-- Opsi Pilihan Ganda -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 pt-2 border-t border-gray-100 text-sm">
                    @foreach(['A' => $q->opsi_a, 'B' => $q->opsi_b, 'C' => $q->opsi_c, 'D' => $q->opsi_d, 'E' => $q->opsi_e] as $optKey => $optVal)
                        @if(!empty($optVal))
                            <div class="flex items-start gap-2.5 p-2.5 rounded-lg border {{ $q->kunci_jawaban === $optKey ? 'bg-emerald-50/70 border-emerald-300 text-emerald-900 font-medium' : 'bg-gray-50 border-gray-200 text-gray-700' }}">
                                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shrink-0 {{ $q->kunci_jawaban === $optKey ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                                    {{ $optKey }}
                                </span>
                                <div class="render-math {{ $q->is_arabic ? 'arabic-text' : '' }} flex-1">
                                    {{ $optVal }}
                                </div>
                                @if($q->kunci_jawaban === $optKey)
                                    <span class="badge-success ml-auto shrink-0">
                                        Kunci Benar
                                    </span>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>

                @if(!empty($q->pembahasan))
                    <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-900">
                        <strong>Pembahasan:</strong> {{ $q->pembahasan }}
                    </div>
                @endif
            </div>
        @empty
            <div class="ta-card p-12 text-center">
                <div class="w-12 h-12 mx-auto mb-3 bg-gray-100 rounded-full flex items-center justify-center text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-base font-bold text-gray-800">Bank Soal Masih Kosong</h3>
                <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">Anda dapat memuat template soal standar seleksi (MTK KaTeX, Bahasa Arab Harakat/Gundul, Indo, Inggris, PAI) atau menambah soal manual.</p>
                <div class="mt-5 flex items-center justify-center gap-3">
                    <button type="button" onclick="document.getElementById('modalTemplate').classList.remove('hidden')" class="ta-btn-outline">
                        Muat Template Siap Pakai
                    </button>
                    <a href="{{ route('admin.cbt.soal.create') }}" class="ta-btn-primary">
                        Tambah Soal Manual
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($questions->hasPages())
    <div class="mt-6">
        {{ $questions->links() }}
    </div>
    @endif

</div>

<!-- MODAL IMPORT SOAL CSV -->
<div id="modalImportCsv" class="ta-modal-backdrop hidden">
    <div class="ta-modal max-w-lg">
        <div class="ta-modal-header">
            <div>
                <h3 class="text-base font-bold text-gray-800">Upload &amp; Import Soal Banyak (CSV)</h3>
                <p class="text-xs text-gray-500 mt-0.5">Unggah puluhan atau ratusan butir soal sekaligus dari file spreadsheet Excel/CSV.</p>
            </div>
            <button type="button" onclick="document.getElementById('modalImportCsv').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <form action="{{ route('admin.cbt.soal.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="ta-modal-body space-y-4">
                {{-- Download Template Banner --}}
                <div class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between gap-3">
                    <div>
                        <span class="text-xs font-bold text-emerald-900 block">Belum Memiliki Format Template?</span>
                        <p class="text-[11px] text-emerald-700 mt-0.5">Unduh template CSV resmi yang siap dibuka di Excel, lengkap dengan contoh soal IPA, Matematika KaTeX, Arab, dan PAI.</p>
                    </div>
                    <a href="{{ route('admin.cbt.soal.downloadTemplate') }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shrink-0 shadow-xs flex items-center gap-1.5 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download Template</span>
                    </a>
                </div>

                {{-- Pilih Kategori --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Kategori / Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori_import" required class="ta-input text-xs">
                        <option value="sesuai_csv">✨ Gunakan Kolom Kategori di File CSV (Mendukung Campuran/Multi-Pelajaran)</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('kategori') === $cat ? 'selected' : '' }}>Paksa ke Mata Pelajaran: {{ $cat }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-gray-400 mt-1">Pilih "Gunakan Kolom Kategori di File CSV" jika berkas Anda memuat berbagai mata pelajaran.</p>
                </div>

                {{-- Upload File CSV --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        File CSV Soal <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="csv_file" accept=".csv,.txt" required class="ta-input text-xs">
                    <p class="text-[11px] text-gray-400 mt-1">Format: .csv (Bisa disimpan dari Save As CSV di Excel, Maksimal 4 MB)</p>
                </div>

                {{-- Panduan Format Kolom --}}
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs space-y-1.5 text-gray-600">
                    <span class="font-semibold text-gray-700 block">Urutan Kolom CSV (Otomatis Sesuai Template):</span>
                    <code class="block font-mono text-[10.5px] bg-white border border-gray-200 rounded p-1.5 text-gray-800 overflow-x-auto leading-relaxed">
                        kategori, soal, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, kunci_jawaban, bobot, pembahasan, is_math, is_arabic
                    </code>
                    <ul class="text-[11px] text-gray-500 space-y-0.5 list-disc list-inside">
                        <li>Kunci jawaban diisi huruf kapital: <strong>A / B / C / D / E</strong></li>
                        <li>Rumus IPA &amp; Matematika diapit tanda dollar, misal: <code>$v = \frac{s}{t}$</code> atau <code>$\text{H}_2\text{O}$</code></li>
                        <li>Teks Arab dapat diketik langsung dengan harakat maupun gundul</li>
                    </ul>
                </div>
            </div>

            <div class="ta-modal-footer">
                <button type="button" onclick="document.getElementById('modalImportCsv').classList.add('hidden')" class="ta-btn-sm-outline">
                    Batal
                </button>
                <button type="submit" class="ta-btn-sm-primary">
                    Upload &amp; Mulai Import
                </button>
            </div>
        </form>
    </div>
</div>


<!-- MODAL MUAT TEMPLATE SOAL -->
<div id="modalTemplate" class="ta-modal-backdrop hidden">
    <div class="ta-modal">
        <div class="ta-modal-header">
            <div>
                <h3 class="text-base font-bold text-gray-800">Muat Template Soal CBT</h3>
                <p class="text-xs text-gray-500 mt-0.5">Paket soal standar masuk santri baru.</p>
            </div>
            <button type="button" onclick="document.getElementById('modalTemplate').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <form action="{{ route('admin.cbt.soal.loadTemplate') }}" method="POST">
            @csrf
            <div class="ta-modal-body space-y-4">
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-600 space-y-1.5">
                    <span class="font-semibold text-gray-800 block">Mata Pelajaran yang Termasuk:</span>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px] text-gray-600">
                        <li>Matematika (Formula KaTeX)</li>
                        <li>Bahasa Arab (Harakat &amp; Gundul)</li>
                        <li>Bahasa Indonesia &amp; Bahasa Inggris</li>
                        <li>Pendidikan Agama Islam (PAI &amp; Tajwid)</li>
                    </ul>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Metode Pengisian:</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2.5 p-2.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="mode" value="append" checked class="w-4 h-4 text-brand-500 focus:ring-brand-400">
                            <span class="text-xs text-gray-700 font-medium">Tambahkan ke bank soal (Append)</span>
                        </label>
                        <label class="flex items-center gap-2.5 p-2.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="mode" value="replace" class="w-4 h-4 text-brand-500 focus:ring-brand-400">
                            <span class="text-xs text-red-600 font-medium">Ganti seluruh soal lama (Replace)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="ta-modal-footer">
                <button type="button" onclick="document.getElementById('modalTemplate').classList.add('hidden')" class="ta-btn-sm-outline">
                    Batal
                </button>
                <button type="submit" class="ta-btn-sm-primary">
                    Muat Template
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- KaTeX auto-render script -->
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"
        onload="renderMathInElement(document.getElementById('soalListContainer') || document.body, {
            delimiters: [
                {left: '$$', right: '$$', display: true},
                {left: '$', right: '$', display: false},
                {left: '\\(', right: '\\)', display: false},
                {left: '\\[', right: '\\]', display: true}
            ],
            throwOnError: false
        });"></script>
@endsection

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
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Bank Soal CBT</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola butir soal ujian seleksi masuk santri baru. Mendukung rumus KaTeX, Bahasa Arab, dan gambar ilustrasi.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <!-- Download + Upload Template Excel: digabung -->
            <a href="{{ route('admin.cbt.soal.downloadTemplate') }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                Template Excel
            </a>
            <button type="button" onclick="document.getElementById('modalImportCsv').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                Import Excel
            </button>
            <a href="{{ route('admin.cbt.soal.export', ['kategori' => $kategori, 'jenjang' => $jenjang]) }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                Export Excel
            </a>
            <button type="button" onclick="document.getElementById('modalTemplate').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                Soal Demo
            </button>
            <a href="{{ route('admin.cbt.cetakSoal', ['kategori' => $kategori, 'jenjang' => $jenjang]) }}" target="_blank"
               class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                Cetak Soal
            </a>
            <button type="button" onclick="document.getElementById('modalHapusMasal').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-white px-3 py-2 text-xs font-medium text-rose-600 shadow-theme-xs hover:bg-rose-50 transition">
                Hapus Massal
            </button>
            <a href="{{ route('admin.cbt.soal.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-gray-900 px-3.5 py-2 text-xs font-semibold text-white shadow-theme-xs hover:bg-gray-800 transition">
                + Tambah Soal Manual
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

    <!-- Info Bar: Template & Upload -->
    <div class="rounded-xl border border-gray-200 bg-gray-50 px-5 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="text-xs text-gray-600">
            <span class="font-semibold text-gray-800">Upload Soal Massal via Excel:</span>
            Unduh template → isi kolom soal, opsi A-E, kunci jawaban, jenjang, kategori → upload lewat tombol <strong>Import Excel</strong>.
            <span class="text-gray-400 ml-1">Kolom gambar: isi URL gambar eksternal (HTTPS) atau kosongkan.</span>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('admin.cbt.soal.downloadTemplate') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                Template Excel
            </a>
            <button type="button" onclick="document.getElementById('modalImportCsv').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-xs font-semibold transition">
                Upload & Import
            </button>
        </div>
    </div>

    <!-- Filter Jenjang Santri (MTs vs MA vs Semua) -->
    <div class="bg-white border border-gray-200 rounded-xl p-3 flex flex-wrap items-center justify-between gap-3 shadow-xs">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mr-1">Filter Jenjang:</span>
            <a href="{{ route('admin.cbt.soal.index', array_merge(request()->except('jenjang', 'page'), [])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ empty($jenjang) ? 'bg-emerald-700 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua Jenjang ({{ $totalQuestions }})
            </a>
            <a href="{{ route('admin.cbt.soal.index', array_merge(request()->except('page'), ['jenjang' => 'MTs'])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ $jenjang === 'MTs' ? 'bg-cyan-600 text-white shadow-xs' : 'bg-cyan-50 text-cyan-800 hover:bg-cyan-100 border border-cyan-200' }}">
                <span>🏫 Khusus MTs</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full font-bold {{ $jenjang === 'MTs' ? 'bg-white/20 text-white' : 'bg-cyan-200 text-cyan-900' }}">{{ $totalMts }}</span>
            </a>
            <a href="{{ route('admin.cbt.soal.index', array_merge(request()->except('page'), ['jenjang' => 'MA'])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ $jenjang === 'MA' ? 'bg-purple-600 text-white shadow-xs' : 'bg-purple-50 text-purple-800 hover:bg-purple-100 border border-purple-200' }}">
                <span>🎓 Khusus MA</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full font-bold {{ $jenjang === 'MA' ? 'bg-white/20 text-white' : 'bg-purple-200 text-purple-900' }}">{{ $totalMa }}</span>
            </a>
            <a href="{{ route('admin.cbt.soal.index', array_merge(request()->except('page'), ['jenjang' => 'Semua'])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ $jenjang === 'Semua' ? 'bg-slate-700 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <span>🌐 Lintas Jenjang (MTs &amp; MA)</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full font-bold {{ $jenjang === 'Semua' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-800' }}">{{ $totalSemuaJenjang }}</span>
            </a>
        </div>
        <div class="text-xs text-gray-500 italic">
            * Calon santri MTs akan mengerjakan soal MTs + Lintas Jenjang, sedangkan santri MA akan mengerjakan soal MA + Lintas Jenjang.
        </div>
    </div>

    <!-- Filter Kategori & Search -->
    <div class="ta-card p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.cbt.soal.index', array_merge(request()->except('kategori', 'page'), [])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ empty($kategori) ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua Mapel ({{ $totalQuestions }})
            </a>
            @foreach($categories as $cat)
                @php $cCount = $categoryCounts[$cat] ?? 0; @endphp
                <a href="{{ route('admin.cbt.soal.index', array_merge(request()->except('page'), ['kategori' => $cat])) }}"
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
            @if(!empty($jenjang))
                <input type="hidden" name="jenjang" value="{{ $jenjang }}">
            @endif
            <div class="relative">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari isi soal..."
                       class="ta-input w-48 sm:w-64 pl-8 py-1.5 text-xs">
                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="ta-btn-sm-primary">
                Cari
            </button>
            @if(!empty($search) || !empty($kategori) || !empty($jenjang))
                <a href="{{ route('admin.cbt.soal.index') }}" class="ta-btn-sm-outline">Reset</a>
            @endif
        </form>
    </div>

    <!-- Toolbar Seleksi Massal (Pilih Semua / Hapus Terpilih) -->
    <div class="bg-white border border-gray-200 rounded-xl p-3 flex flex-wrap items-center justify-between gap-3 shadow-xs">
        <div class="flex items-center gap-3">
            <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" id="selectAllSoal" onchange="toggleSelectAllSoal(this)" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                <span class="text-xs font-bold text-gray-700">Pilih Semua di Halaman Ini</span>
            </label>
            <span id="selectedCountBadge" class="hidden text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                <span id="selectedCountNum">0</span> butir soal dipilih
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="btnHapusTerpilih" onclick="submitBulkDeleteSelected()" class="hidden inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold shadow-xs transition cursor-pointer">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                <span>Hapus Soal Terpilih</span>
            </button>
            <button type="button" onclick="document.getElementById('modalHapusMasal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-rose-50 text-gray-700 hover:text-rose-700 border border-gray-200 hover:border-rose-200 rounded-lg text-xs font-semibold transition cursor-pointer">
                <svg class="w-3.5 h-3.5 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                <span>Opsi Hapus Massal...</span>
            </button>
        </div>
    </div>

    <!-- Daftar Soal Cards -->
    <div class="space-y-4" id="soalListContainer">
        @forelse($questions as $index => $q)
            <div class="ta-card p-5 transition hover:shadow-md relative" id="card-soal-{{ $q->id }}">
                <div class="flex items-start justify-between gap-4 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <label class="inline-flex items-center cursor-pointer select-none p-1 rounded hover:bg-gray-100" title="Centang untuk hapus massal">
                            <input type="checkbox" name="ids[]" value="{{ $q->id }}" class="soal-checkbox w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300 transition" onchange="updateSelectedCount()">
                        </label>
                        <span class="badge-gray font-mono">
                            #{{ $questions->firstItem() + $index }}
                        </span>
                        <span class="badge-primary">
                            {{ $q->kategori }}
                        </span>
                        @php $qJenjang = $q->jenjang ?: 'Semua'; @endphp
                        @if($qJenjang === 'MTs')
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-cyan-100 text-cyan-800 border border-cyan-200">
                                🏫 Khusus MTs
                            </span>
                        @elseif($qJenjang === 'MA')
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                🎓 Khusus MA
                            </span>
                        @else
                            <span class="text-[11px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                🌐 Semua Jenjang
                            </span>
                        @endif
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
                                <span class="w-6 h-6 rounded flex items-center justify-center text-xs font-bold shrink-0 {{ $q->kunci_jawaban === $optKey ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                    {{ $optKey }}
                                </span>
                                <div class="flex-1 leading-relaxed render-math {{ $q->is_arabic ? 'arabic-text' : '' }}">
                                    {!! nl2br(e($optVal)) !!}
                                    @if($q->kunci_jawaban === $optKey)
                                        <span class="inline-block ml-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-1.5 py-0.2 rounded">Kunci</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Pembahasan Jika Ada -->
                @if(!empty($q->pembahasan))
                    <div class="mt-3 pt-2 border-t border-gray-100 text-xs text-gray-500">
                        <span class="font-semibold text-gray-700">Pembahasan:</span>
                        <div class="mt-1 render-math {{ $q->is_arabic ? 'arabic-text' : '' }}">{!! nl2br(e($q->pembahasan)) !!}</div>
                    </div>
                @endif
            </div>
        @empty
            <div class="ta-card p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-gray-800">Belum Ada Soal di Bank Soal</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-md mx-auto">
                    @if(!empty($search) || !empty($kategori) || !empty($jenjang))
                        Tidak ada butir soal yang sesuai dengan kata kunci pencarian atau filter yang dipilih.
                    @else
                        Mulai dengan menambahkan butir soal satu per satu secara manual, atau upload sekaligus melalui file Excel template (.xlsx).
                    @endif
                </p>
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('admin.cbt.soal.create') }}" class="ta-btn-primary">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Tambah Soal Pertama</span>
                    </a>
                    <button type="button" onclick="document.getElementById('modalImportCsv').classList.remove('hidden')" class="ta-btn-outline">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Upload File Excel (.xlsx)</span>
                    </button>
                    <button type="button" onclick="document.getElementById('modalTemplate').classList.remove('hidden')" class="ta-btn-outline text-amber-700 border-amber-200">
                        <span>⚡ Muat 20 Soal Demo</span>
                    </button>
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

<!-- Hidden Form for Checkbox Bulk Deletion -->
<form id="formBulkDeleteSelected" action="{{ route('admin.cbt.soal.bulkDestroy') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="mode" value="selected">
    <div id="hiddenSelectedIdsContainer"></div>
</form>

<!-- FLOATING ACTION BAR: MUNCUL SAAT ADA SOAL TERPILIH -->
<div id="floatingBulkBar" class="fixed bottom-6 inset-x-0 z-40 max-w-lg mx-auto px-4 hidden transition-all duration-300">
    <div class="bg-gray-900/95 text-white rounded-2xl shadow-2xl p-3.5 flex items-center justify-between gap-3 border border-gray-700 backdrop-blur-md">
        <div class="flex items-center gap-2.5">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-xs font-bold text-white"><span id="floatingCountNum">0</span> Soal Terpilih</span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="cancelBulkSelection()" class="px-3 py-1.5 text-xs text-gray-300 hover:text-white bg-gray-800 hover:bg-gray-700 rounded-xl transition cursor-pointer">
                Batal
            </button>
            <button type="button" onclick="submitBulkDeleteSelected()" class="px-3.5 py-1.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs flex items-center gap-1.5 transition cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                <span>Hapus Terpilih</span>
            </button>
        </div>
    </div>
</div>


<!-- ========================================================================= -->
<!-- MODAL 1: IMPORT SOAL EXCEL / CSV (DESAIN SESUAI TEMPLATE SISWA & PSB)       -->
<!-- ========================================================================= -->
<div id="modalImportCsv" class="ta-modal-backdrop hidden" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="ta-modal max-w-xl rounded-2xl bg-white shadow-2xl border border-gray-100 overflow-hidden" onclick="event.stopPropagation()">
        <div class="ta-modal-header bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-base">Import Soal Massal (Template Excel)</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Unggah data butir soal ujian secara serentak menggunakan file Excel (.xlsx) atau CSV.</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('modalImportCsv').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition text-xl leading-none">&times;</button>
        </div>

        <form action="{{ route('admin.cbt.soal.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="ta-modal-body space-y-4 text-xs">
                
                <!-- Langkah 1: Unduh Format Template Excel -->
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl space-y-2">
                    <span class="font-bold text-emerald-950 block">Langkah 1: Unduh Format Template Excel (.xlsx)</span>
                    <p class="text-gray-600 leading-relaxed text-[11px]">
                        Gunakan file template resmi Microsoft Excel di bawah ini agar susunan 13 kolom sesuai sistem (jenjang MTs/MA, kategori, pertanyaan, opsi A-E, kunci jawaban, dan pembahasan).
                    </p>
                    <a href="{{ route('admin.cbt.soal.downloadTemplate') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs shadow-theme-xs transition">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M8.5 13.5l2 2.5-2 2.5h1.5l1.25-1.75L12.5 18.5H14l-2-2.5 2-2.5h-1.5l-1.25 1.75L10 13.5H8.5z"/></svg>
                        <span>Unduh Format Template Excel (.xlsx)</span>
                    </a>
                </div>

                <!-- Langkah 2: Konfigurasi Jenjang & Mata Pelajaran -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3.5 bg-gray-50 border border-gray-200 rounded-xl">
                    <div>
                        <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Jenjang Sasaran Soal <span class="text-red-500">*</span>
                        </label>
                        <select name="jenjang_import" class="ta-input text-xs">
                            <option value="sesuai_excel">✨ Sesuai Kolom di File Excel</option>
                            <option value="MTs">🏫 Khusus Jenjang MTs</option>
                            <option value="MA">🎓 Khusus Jenjang MA</option>
                            <option value="Semua">🌐 Semua Jenjang (Lintas Jenjang)</option>
                        </select>
                        <p class="text-[10.5px] text-gray-400 mt-1">Gunakan "Sesuai Kolom" jika file memuat MTs &amp; MA sekaligus.</p>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Kategori Mata Pelajaran <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori_import" required class="ta-input text-xs">
                            <option value="sesuai_csv">✨ Sesuai Kolom Kategori di Excel</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('kategori') === $cat ? 'selected' : '' }}>Paksa ke: {{ $cat }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10.5px] text-gray-400 mt-1">Bisa memuat beragam mapel sekaligus dalam 1 file.</p>
                    </div>
                </div>

                <!-- Langkah 3: Pilih File Dokumen Excel -->
                <div class="space-y-2">
                    <label class="block font-bold text-gray-800 uppercase tracking-wider">Langkah 3: Pilih File Excel yang Telah Diisi <span class="text-red-500">*</span></label>
                    <input type="file" name="excel_file" accept=".xlsx, .xls, .csv" required class="ta-input text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    <p class="text-[11px] text-gray-500">Mendukung dokumen resmi Microsoft Excel <code>.xlsx</code>, <code>.xls</code>, atau <code>.csv</code> (Maksimal 15 MB).</p>
                </div>

                <!-- Panduan Kolom File Excel -->
                <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 text-[11px] space-y-2">
                    <span class="font-bold block text-blue-800">📋 Urutan Kolom Template Excel (Baris 1 = Header):</span>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 text-[10.5px]">
                        <div class="bg-white p-1 rounded border border-blue-100"><strong>A:</strong> jenjang <span class="text-emerald-700 font-bold">(MTs/MA/Semua)</span></div>
                        <div class="bg-white p-1 rounded border border-blue-100"><strong>B:</strong> kategori</div>
                        <div class="bg-white p-1 rounded border border-blue-100"><strong>C:</strong> soal</div>
                        <div class="bg-white p-1 rounded border border-blue-100"><strong>D:</strong> opsi_a</div>
                        <div class="bg-white p-1 rounded border border-blue-100"><strong>E:</strong> opsi_b</div>
                        <div class="bg-white p-1 rounded border border-blue-100"><strong>F:</strong> opsi_c</div>
                        <div class="bg-white p-1 rounded border border-blue-100"><strong>G:</strong> opsi_d</div>
                        <div class="bg-white p-1 rounded border border-blue-100"><strong>H:</strong> opsi_e</div>
                        <div class="bg-white p-1 rounded border border-blue-100"><strong>I:</strong> kunci_jawaban <span class="text-blue-700 font-bold">(A-E)</span></div>
                        <div class="bg-white p-1 rounded border border-blue-100"><strong>J:</strong> bobot <span class="text-gray-400">(default 1)</span></div>
                        <div class="bg-white p-1 rounded border border-blue-100"><strong>K:</strong> pembahasan</div>
                        <div class="bg-white p-1 rounded border border-blue-100"><strong>L:</strong> is_math <span class="text-gray-400">(1/0)</span></div>
                    </div>
                </div>

            </div>

            <div class="ta-modal-footer bg-gray-50/80 px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalImportCsv').classList.add('hidden')" class="ta-btn-outline px-4 py-2 text-xs font-semibold rounded-xl">
                    Batal
                </button>
                <button type="submit" class="ta-btn-primary bg-emerald-600 hover:bg-emerald-700 border-emerald-600 px-4 py-2 text-xs font-semibold rounded-xl shadow-xs">
                    Upload &amp; Mulai Import
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ========================================================================= -->
<!-- MODAL 2: MUAT PAKET SOAL DEMO BAWAAN (DESAIN SESUAI TEMPLATE SISWA & PSB) -->
<!-- ========================================================================= -->
<div id="modalTemplate" class="ta-modal-backdrop hidden" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="ta-modal max-w-lg rounded-2xl bg-white shadow-2xl border border-gray-100 overflow-hidden" onclick="event.stopPropagation()">
        <div class="ta-modal-header bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-base">Muat Paket Soal Demo Bawaan (20 Butir)</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Preset contoh butir soal latihan ujian seleksi santri siap pakai.</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('modalTemplate').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition text-xl leading-none">&times;</button>
        </div>

        <form action="{{ route('admin.cbt.soal.loadTemplate') }}" method="POST">
            @csrf
            <div class="ta-modal-body space-y-4 text-xs">
                <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 space-y-1.5">
                    <div class="font-bold flex items-center gap-1.5 text-amber-900">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Kapan Fitur Ini Digunakan?</span>
                    </div>
                    <p class="text-[11.5px] leading-relaxed text-amber-800">
                        Tombol ini memasukkan <strong>20 butir soal latihan contoh bawaan sistem</strong> ke database untuk simulasi ujian santri (menguji rumus matematika, teks Arab, dan sistem penilaian).
                    </p>
                </div>

                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl space-y-1.5 text-gray-600">
                    <span class="font-bold text-gray-800 block">Cakupan 20 Soal Demo:</span>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px] text-gray-600">
                        <li>Matematika (Rumus KaTeX Pecahan, Aljabar, Akar)</li>
                        <li>Bahasa Arab (Teks Harakat &amp; Mufrodat)</li>
                        <li>Bahasa Indonesia &amp; Bahasa Inggris</li>
                        <li>Pendidikan Agama Islam (PAI, Rukun Islam, &amp; Tajwid)</li>
                    </ul>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-2">Metode Pengisian:</label>
                    <div class="space-y-2">
                        <label class="flex items-start gap-2.5 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                            <input type="radio" name="mode" value="append" checked class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 mt-0.5">
                            <div>
                                <span class="font-bold text-gray-800 block">Tambahkan ke bank soal (Append)</span>
                                <span class="text-[11px] text-gray-500">Menambahkan 20 soal demo ini tanpa menghapus butir soal yang sudah ada.</span>
                            </div>
                        </label>
                        <label class="flex items-start gap-2.5 p-3 rounded-xl border border-rose-200 hover:bg-rose-50/40 cursor-pointer transition">
                            <input type="radio" name="mode" value="replace" class="w-4 h-4 text-rose-600 focus:ring-rose-500 mt-0.5">
                            <div>
                                <span class="font-bold text-rose-600 block">Ganti seluruh soal lama (Replace)</span>
                                <span class="text-[11px] text-rose-500">Mengosongkan bank soal saat ini, lalu mengisinya dengan 20 butir soal demo ini.</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="ta-modal-footer bg-gray-50/80 px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalTemplate').classList.add('hidden')" class="ta-btn-outline px-4 py-2 text-xs font-semibold rounded-xl">
                    Batal
                </button>
                <button type="submit" class="ta-btn-primary bg-amber-600 hover:bg-amber-700 border-amber-600 px-4 py-2 text-xs font-semibold rounded-xl shadow-xs">
                    ⚡ Masukkan 20 Soal Demo
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ========================================================================= -->
<!-- MODAL 3: HAPUS SOAL SECARA MASSAL & KOSONGKAN BANK SOAL                    -->
<!-- ========================================================================= -->
<div id="modalHapusMasal" class="ta-modal-backdrop hidden" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="ta-modal max-w-lg rounded-2xl bg-white shadow-2xl border border-gray-100 overflow-hidden" onclick="event.stopPropagation()">
        <div class="ta-modal-header bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-base">Hapus Soal Massal &amp; Reset Bank Soal</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pilih metode penghapusan butir soal secara massal dari sistem CBT.</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('modalHapusMasal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition text-xl leading-none">&times;</button>
        </div>

        <form action="{{ route('admin.cbt.soal.bulkDestroy') }}" method="POST" id="formModalHapusMasal" onsubmit="return validateHapusMasal()">
            @csrf
            <div class="ta-modal-body space-y-4 text-xs">
                
                <div class="space-y-2.5">
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1">Pilih Tindakan Penghapusan:</label>

                    <!-- Pilihan 1: Hapus Soal Terpilih -->
                    <label class="flex items-start gap-2.5 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                        <input type="radio" name="mode" value="selected" id="radioModeSelected" class="w-4 h-4 text-rose-600 focus:ring-rose-500 mt-0.5" onchange="toggleHapusMode()">
                        <div class="flex-1">
                            <span class="font-bold text-gray-800 block">1. Hapus Butir Soal Terpilih (Sesuai Kotak Checkbox)</span>
                            <span class="text-[11px] text-gray-500">Menghapus butir soal yang dicentang pada daftar soal halaman ini.</span>
                            <div id="modalSelectedIndicator" class="mt-1 text-[11px] font-semibold text-emerald-700">
                                Saat ini ada <strong id="modalSelectedCountText">0</strong> soal yang dicentang.
                            </div>
                        </div>
                    </label>

                    <!-- Pilihan 2: Hapus Berdasarkan Filter Saat Ini -->
                    <label class="flex items-start gap-2.5 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                        <input type="radio" name="mode" value="filtered" id="radioModeFiltered" class="w-4 h-4 text-rose-600 focus:ring-rose-500 mt-0.5" onchange="toggleHapusMode()">
                        <div class="flex-1">
                            <span class="font-bold text-gray-800 block">2. Hapus Sesuai Filter Kategori / Jenjang Saat Ini</span>
                            <span class="text-[11px] text-gray-500">Menghapus seluruh butir soal yang cocok dengan filter yang sedang Anda buka.</span>
                            <div class="mt-1.5 p-2 bg-gray-100 rounded-lg text-[11px] text-gray-700 font-mono">
                                Filter aktif: Jenjang = <strong>{{ $jenjang ?: 'Semua' }}</strong>, Mapel = <strong>{{ $kategori ?: 'Semua' }}</strong>
                            </div>
                            <input type="hidden" name="jenjang" value="{{ $jenjang }}">
                            <input type="hidden" name="kategori" value="{{ $kategori }}">
                        </div>
                    </label>

                    <!-- Pilihan 3: Kosongkan Seluruh Bank Soal -->
                    <label class="flex items-start gap-2.5 p-3 rounded-xl border border-rose-200 bg-rose-50/30 hover:bg-rose-50 cursor-pointer transition">
                        <input type="radio" name="mode" value="truncate" id="radioModeTruncate" class="w-4 h-4 text-rose-600 focus:ring-rose-500 mt-0.5" onchange="toggleHapusMode()">
                        <div class="flex-1">
                            <span class="font-bold text-rose-700 block">3. Kosongkan Seluruh Bank Soal (Reset Total)</span>
                            <span class="text-[11px] text-rose-600">Menghapus SELURUH {{ $totalQuestions }} butir butir soal di database tanpa tersisa.</span>
                        </div>
                    </label>
                </div>

                <!-- Konfirmasi Kata Kunci jika Reset Total -->
                <div id="confirmTruncateBox" class="hidden p-3 bg-rose-50 border border-rose-300 rounded-xl space-y-2">
                    <span class="font-bold text-rose-800 block">⚠️ Konfirmasi Keamanan:</span>
                    <p class="text-[11px] text-rose-700">Tindakan ini permanen. Ketik kata <strong>HAPUS</strong> di bawah ini untuk mengonfirmasi:</p>
                    <input type="text" id="inputConfirmTruncate" placeholder="Ketik HAPUS di sini..." class="ta-input text-xs font-mono font-bold text-rose-900 border-rose-300">
                </div>

            </div>

            <div class="ta-modal-footer bg-gray-50/80 px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalHapusMasal').classList.add('hidden')" class="ta-btn-outline px-4 py-2 text-xs font-semibold rounded-xl">
                    Batal
                </button>
                <button type="submit" class="ta-btn-primary bg-rose-600 hover:bg-rose-700 border-rose-600 px-4 py-2 text-xs font-semibold rounded-xl shadow-xs">
                    🗑️ Konfirmasi Hapus Massal
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

<script>
    // ===== FUNGSI SELEKSI MASSAL CHECKBOX =====
    function toggleSelectAllSoal(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.soal-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
        });
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.soal-checkbox:checked');
        const totalCheckboxes = document.querySelectorAll('.soal-checkbox');
        const count = checkedBoxes.length;

        // Visual highlight card
        document.querySelectorAll('.soal-checkbox').forEach(cb => {
            const card = document.getElementById('card-soal-' + cb.value);
            if (card) {
                if (cb.checked) {
                    card.classList.add('ring-2', 'ring-emerald-400', 'bg-emerald-50/20');
                } else {
                    card.classList.remove('ring-2', 'ring-emerald-400', 'bg-emerald-50/20');
                }
            }
        });

        const masterCheckbox = document.getElementById('selectAllSoal');
        if (masterCheckbox && totalCheckboxes.length > 0) {
            masterCheckbox.checked = (count === totalCheckboxes.length);
        }

        const badge = document.getElementById('selectedCountBadge');
        const num = document.getElementById('selectedCountNum');
        const btnHapus = document.getElementById('btnHapusTerpilih');
        const floatingBar = document.getElementById('floatingBulkBar');
        const floatingNum = document.getElementById('floatingCountNum');
        const modalSelectedCountText = document.getElementById('modalSelectedCountText');

        if (modalSelectedCountText) {
            modalSelectedCountText.textContent = count;
        }

        if (count > 0) {
            if (badge) badge.classList.remove('hidden');
            if (num) num.textContent = count;
            if (btnHapus) btnHapus.classList.remove('hidden');
            if (floatingBar) floatingBar.classList.remove('hidden');
            if (floatingNum) floatingNum.textContent = count;
            
            const radioSelected = document.getElementById('radioModeSelected');
            if (radioSelected) radioSelected.checked = true;
        } else {
            if (badge) badge.classList.add('hidden');
            if (btnHapus) btnHapus.classList.add('hidden');
            if (floatingBar) floatingBar.classList.add('hidden');
        }
    }

    function cancelBulkSelection() {
        document.querySelectorAll('.soal-checkbox').forEach(cb => {
            cb.checked = false;
        });
        const masterCheckbox = document.getElementById('selectAllSoal');
        if (masterCheckbox) masterCheckbox.checked = false;
        updateSelectedCount();
    }

    function submitBulkDeleteSelected() {
        const checked = document.querySelectorAll('.soal-checkbox:checked');
        if (checked.length === 0) {
            alert('Silakan centang minimal satu butir soal yang ingin dihapus.');
            return;
        }
        if (!confirm('Apakah Anda yakin ingin menghapus ' + checked.length + ' butir soal terpilih secara permanen?')) {
            return;
        }
        const container = document.getElementById('hiddenSelectedIdsContainer');
        container.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });
        document.getElementById('formBulkDeleteSelected').submit();
    }

    // ===== FUNGSI MODAL HAPUS MASSAL =====
    function toggleHapusMode() {
        const isTruncate = document.getElementById('radioModeTruncate')?.checked;
        const confirmBox = document.getElementById('confirmTruncateBox');
        if (confirmBox) {
            if (isTruncate) {
                confirmBox.classList.remove('hidden');
            } else {
                confirmBox.classList.add('hidden');
            }
        }
    }

    function validateHapusMasal() {
        const radioSelected = document.getElementById('radioModeSelected')?.checked;
        const radioFiltered = document.getElementById('radioModeFiltered')?.checked;
        const radioTruncate = document.getElementById('radioModeTruncate')?.checked;

        if (radioTruncate) {
            const confirmVal = document.getElementById('inputConfirmTruncate')?.value.trim();
            if (confirmVal !== 'HAPUS') {
                alert('Untuk mengosongkan seluruh bank soal, Anda harus mengetik kata HAPUS pada kolom konfirmasi.');
                return false;
            }
            return confirm('PERINGATAN TERAKHIR: Seluruh butir bank soal akan dihapus secara permanen. Apakah Anda benar-benar yakin?');
        }

        if (radioFiltered) {
            return confirm('Apakah Anda yakin ingin menghapus seluruh butir soal yang sesuai dengan kriteria filter saat ini?');
        }

        if (radioSelected) {
            const checked = document.querySelectorAll('.soal-checkbox:checked');
            if (checked.length === 0) {
                alert('Belum ada butir soal yang dicentang. Silakan centang soal pada daftar terlebih dahulu.');
                return false;
            }
            // Append checked IDs to form
            const form = document.getElementById('formModalHapusMasal');
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });
            return confirm('Apakah Anda yakin ingin menghapus ' + checked.length + ' butir soal terpilih?');
        }

        alert('Silakan pilih salah satu metode penghapusan.');
        return false;
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.ta-modal-backdrop').forEach(modal => {
                modal.classList.add('hidden');
            });
        }
    });
</script>
@endsection

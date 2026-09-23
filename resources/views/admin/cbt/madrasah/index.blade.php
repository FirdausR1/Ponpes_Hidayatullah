@extends('admin.layout')

@section('title', 'CBT Ujian Madrasah (Kelas 9 & 12)')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path d="M7 16.5v3.5"/></svg>
                </span>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Ujian Madrasah (Kelas 9 &amp; 12)</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Kelola sesi ujian akhir madrasah (MTs &amp; MA), rekapitulasi nilai per mapel, dan cetak laporan hasil ujian resmi.</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.cbt.madrasah.create') }}" class="ta-btn-primary flex items-center gap-2 px-4 py-2.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                + Buat Sesi Ujian Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="ta-alert ta-alert-success flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">&times;</button>
    </div>
    @endif

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Total Sesi Ujian</span>
            <span class="text-2xl font-bold font-mono text-gray-900 mt-1 block">{{ $totalExams }}</span>
            <span class="text-[11px] text-gray-400 mt-1 block">Mapel Terdaftar</span>
        </div>
        <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider block">Kelas 12 (MA)</span>
            <span class="text-2xl font-bold font-mono text-emerald-700 mt-1 block">{{ $totalMa }}</span>
            <span class="text-[11px] text-emerald-600/70 mt-1 block">Ujian Madrasah Aliyah</span>
        </div>
        <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-xs font-semibold text-sky-600 uppercase tracking-wider block">Kelas 9 (MTs)</span>
            <span class="text-2xl font-bold font-mono text-sky-700 mt-1 block">{{ $totalMts }}</span>
            <span class="text-[11px] text-sky-600/70 mt-1 block">Ujian Madrasah Tsanawiyah</span>
        </div>
        <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-xs font-semibold text-purple-600 uppercase tracking-wider block">Total Peserta Dinilai</span>
            <span class="text-2xl font-bold font-mono text-purple-700 mt-1 block">{{ $totalPeserta }}</span>
            <span class="text-[11px] text-purple-600/70 mt-1 block">Santri Terdaftar</span>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
        <form method="GET" action="{{ route('admin.cbt.madrasah.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Tingkat Kelas</label>
                <select name="tingkat" onchange="this.form.submit()" class="ta-input text-xs font-semibold">
                    <option value="">Semua Tingkat</option>
                    <option value="12" {{ $tingkat == '12' ? 'selected' : '' }}>Kelas 12 (MA)</option>
                    <option value="9" {{ $tingkat == '9' ? 'selected' : '' }}>Kelas 9 (MTs)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Jenjang</label>
                <select name="jenjang" onchange="this.form.submit()" class="ta-input text-xs font-semibold">
                    <option value="">Semua Jenjang</option>
                    <option value="MA" {{ $jenjang == 'MA' ? 'selected' : '' }}>Madrasah Aliyah (MA)</option>
                    <option value="MTs" {{ $jenjang == 'MTs' ? 'selected' : '' }}>Madrasah Tsanawiyah (MTs)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Jurusan</label>
                <select name="jurusan" onchange="this.form.submit()" class="ta-input text-xs font-semibold">
                    <option value="">Semua Jurusan</option>
                    <option value="KEAGAMAAN" {{ $jurusan == 'KEAGAMAAN' ? 'selected' : '' }}>KEAGAMAAN</option>
                    <option value="MIPA" {{ $jurusan == 'MIPA' ? 'selected' : '' }}>MIPA</option>
                    <option value="IPS" {{ $jurusan == 'IPS' ? 'selected' : '' }}>IPS</option>
                    <option value="UMUM" {{ $jurusan == 'UMUM' ? 'selected' : '' }}>UMUM</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Cari Mapel / Guru / Ujian</label>
                <div class="flex gap-2">
                    <input type="text" name="q" value="{{ $q }}" placeholder="Ketik kata kunci..." class="ta-input text-xs flex-1">
                    <button type="submit" class="ta-btn-primary px-3 text-xs">Cari</button>
                    @if(!empty($tingkat) || !empty($jenjang) || !empty($jurusan) || !empty($q))
                    <a href="{{ route('admin.cbt.madrasah.index') }}" class="ta-btn-secondary px-2 text-xs flex items-center justify-center" title="Reset Filter">&times;</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Grid Kartu Ujian -->
    @if($exams->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($exams as $ex)
        @php
            $isMa = ($ex->jenjang === 'MA' || $ex->tingkat_kelas == '12');
            $highest = $ex->results->max('nilai') ?? 0;
            $lowest = $ex->results->min('nilai') ?? 0;
            $avg = $ex->results->avg('nilai') ? round($ex->results->avg('nilai'), 1) : 0;
        @endphp
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-theme-xs hover:shadow-md transition flex flex-col justify-between">
            <div>
                <!-- Badges -->
                <div class="flex items-center justify-between gap-2 mb-2.5">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $isMa ? 'bg-emerald-100 text-emerald-800' : 'bg-sky-100 text-sky-800' }}">
                            {{ $isMa ? 'Kelas 12 MA' : 'Kelas 9 MTs' }}
                        </span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-purple-100 text-purple-800">
                            {{ $ex->jurusan }}
                        </span>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $ex->status === 'Aktif' ? 'bg-green-100 text-green-700' : ($ex->status === 'Selesai' ? 'bg-gray-100 text-gray-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ $ex->status }}
                    </span>
                </div>

                <!-- Judul & Mapel -->
                <h3 class="text-base font-bold text-gray-900 leading-snug hover:text-emerald-600 transition">
                    <a href="{{ route('admin.cbt.madrasah.show', $ex->id) }}">
                        {{ $ex->mata_pelajaran }}
                    </a>
                </h3>
                <p class="text-xs text-gray-500 font-mono mt-0.5 line-clamp-1" title="{{ $ex->nama_ujian }}">
                    {{ $ex->nama_ujian }}
                </p>

                <div class="mt-3 pt-3 border-t border-gray-100 space-y-1.5 text-xs text-gray-600">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Guru Pengampu:</span>
                        <span class="font-semibold text-gray-800">{{ $ex->nama_guru ?: '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Soal &amp; Durasi:</span>
                        <span class="font-mono text-gray-800 font-medium">{{ $ex->jumlah_soal }} Soal &bull; {{ $ex->durasi_menit }} Menit</span>
                    </div>
                </div>

                <!-- Statistik Nilai -->
                <div class="mt-4 p-3 bg-gray-50 border border-gray-100 rounded-xl grid grid-cols-3 text-center gap-1">
                    <div>
                        <span class="text-[10px] text-gray-400 uppercase font-semibold block">Tertinggi</span>
                        <span class="text-sm font-mono font-bold text-emerald-700">{{ number_format($highest, 1) }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 uppercase font-semibold block">Terendah</span>
                        <span class="text-sm font-mono font-bold text-rose-600">{{ number_format($lowest, 1) }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 uppercase font-semibold block">Rata-rata</span>
                        <span class="text-sm font-mono font-bold text-purple-700">{{ number_format($avg, 1) }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-5 pt-3.5 border-t border-gray-100 flex items-center justify-between gap-2">
                <span class="text-xs text-gray-500 font-medium">
                    <strong class="text-gray-900 font-bold font-mono">{{ $ex->results_count }}</strong> Peserta
                </span>
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('admin.cbt.madrasah.show', $ex->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold transition" title="Kelola Peserta & Input Nilai">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Nilai
                    </a>
                    <a href="{{ route('admin.cbt.madrasah.cetak', $ex->id) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 rounded-lg text-xs font-bold transition" title="Cetak Rekap Laporan Hasil Ujian Resmi">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Cetak
                    </a>
                    <a href="{{ route('admin.cbt.madrasah.edit', $ex->id) }}" class="p-1.5 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition" title="Edit Pengaturan Ujian">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $exams->links() }}
    </div>
    @else
    <div class="p-12 bg-white border border-dashed border-gray-300 rounded-2xl text-center text-gray-500">
        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
        <p class="font-bold text-gray-700 text-sm">Belum ada sesi ujian madrasah yang dibuat.</p>
        <p class="text-xs text-gray-400 mt-1 max-w-md mx-auto">Klik tombol "+ Buat Sesi Ujian Baru" di atas untuk menambahkan ujian mata pelajaran kelas 12 MA atau kelas 9 MTs.</p>
        <a href="{{ route('admin.cbt.madrasah.create') }}" class="ta-btn-primary inline-flex items-center gap-2 mt-4 text-xs px-4 py-2">
            + Tambah Ujian Sekarang
        </a>
    </div>
    @endif
</div>
@endsection

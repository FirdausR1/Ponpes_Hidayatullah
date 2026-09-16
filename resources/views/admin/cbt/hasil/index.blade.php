@extends('admin.layout')

@section('title', 'Rekap Hasil Ujian & Kelulusan CBT')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Rekap Hasil Ujian CBT</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau skor ujian masuk santri, pisahkan hasil MTs dan MA, serta kelola izin ujian ulang pelanggaran.</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.cbt.dongkrak.index') }}" class="ta-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Dongkrak Nilai</span>
            </a>

            <!-- Dropdown Download Rekap Nilai Massal (Excel) -->
            <div class="relative inline-block text-left" id="dropdownExportNilaiWrap">
                <button type="button" onclick="toggleDropdown('dropdownExportNilaiMenu')" class="ta-btn-outline font-semibold text-emerald-700 hover:bg-emerald-50 border-emerald-300">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Download Rekap Nilai</span>
                    <svg class="w-3.5 h-3.5 text-gray-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="dropdownExportNilaiMenu" class="hidden absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-30">
                    <div class="px-3.5 py-1.5 text-[10.5px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        Format File Excel (.xlsx)
                    </div>
                    <a href="{{ route('admin.cbt.hasil.exportNilai') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                        <span>Download Semua Jenjang</span>
                    </a>
                    <a href="{{ route('admin.cbt.hasil.exportNilai', ['jenjang' => 'MTs']) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 transition">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>🏫 Download Khusus MTs</span>
                    </a>
                    <a href="{{ route('admin.cbt.hasil.exportNilai', ['jenjang' => 'MA']) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-50 transition">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        <span>🎓 Download Khusus MA</span>
                    </a>
                </div>
            </div>

            <!-- Dropdown Download Data Santri Lulus (Excel) -->
            <div class="relative inline-block text-left" id="dropdownExportLulusWrap">
                <button type="button" onclick="toggleDropdown('dropdownExportLulusMenu')" class="ta-btn-outline font-semibold text-brand-600 hover:bg-brand-50 border-brand-300">
                    <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    <span>Download Santri Lulus</span>
                    <svg class="w-3.5 h-3.5 text-gray-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="dropdownExportLulusMenu" class="hidden absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-30">
                    <div class="px-3.5 py-1.5 text-[10.5px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        Calon Santri Lulus (SK Resmi)
                    </div>
                    <a href="{{ route('admin.cbt.hasil.exportLulus') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Semua Santri yang Lulus</span>
                    </a>
                    <a href="{{ route('admin.cbt.hasil.exportLulus', ['jenjang' => 'MTs']) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 transition">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        <span>🏫 Santri Lulus MTs</span>
                    </a>
                    <a href="{{ route('admin.cbt.hasil.exportLulus', ['jenjang' => 'MA']) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-50 transition">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        <span>🎓 Santri Lulus MA</span>
                    </a>
                </div>
            </div>

            <!-- Dropdown Cetak Rekap Nilai Terpisah Jenjang -->
            <div class="relative inline-block text-left" id="dropdownCetakWrap">
                <button type="button" onclick="toggleDropdown('dropdownCetakMenu')" class="ta-btn-outline">
                    <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    <span>Cetak PDF</span>
                    <svg class="w-3.5 h-3.5 text-gray-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="dropdownCetakMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-30">
                    <a href="{{ route('admin.cbt.cetakNilai') }}" target="_blank" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                        Cetak Semua Jenjang
                    </a>
                    <a href="{{ route('admin.cbt.cetakNilai', ['jenjang' => 'MTs']) }}" target="_blank" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 transition">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        🏫 Cetak Khusus MTs
                    </a>
                    <a href="{{ route('admin.cbt.cetakNilai', ['jenjang' => 'MA']) }}" target="_blank" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-50 transition">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        🎓 Cetak Khusus MA
                    </a>
                </div>
            </div>

            <a href="{{ route('admin.cbt.pengaturan') }}" class="ta-btn-outline">
                <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                Pengaturan
            </a>
        </div>
    </div>

    <!-- KONTROL STATUS PENGUMUMAN NILAI KE SANTRI (OPEN / DIRAHASIAKAN) -->
    <div class="rounded-2xl p-4 sm:p-5 border transition {{ $publishScores ? 'bg-gradient-to-r from-emerald-50 via-emerald-50/50 to-white border-emerald-200' : 'bg-gradient-to-r from-amber-50 via-amber-50/50 to-white border-amber-200' }}">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-sm {{ $publishScores ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white' }}">
                    @if($publishScores)
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    @endif
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm sm:text-base font-bold text-gray-800">
                            Status Pengumuman Nilai: 
                            <span class="{{ $publishScores ? 'text-emerald-700 font-extrabold' : 'text-amber-700 font-extrabold' }}">
                                {{ $publishScores ? 'DIBUKA UNTUK SANTRI (Terbuka)' : 'DIRAHASIAKAN (Santri Belum Tahu Nilainya)' }}
                            </span>
                        </h3>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $publishScores ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }}">
                            {{ $publishScores ? 'Terbuka' : 'Dirahasiakan' }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">
                        @if($publishScores)
                            Santri <strong>dapat melihat skor nilai CBT</strong>, rincian hasil per mata pelajaran, dan status kelulusan di portal mereka masing-masing.
                        @else
                            Nilai ujian <strong>hanya diketahui oleh Anda / dewan penguji</strong>. Santri tidak dapat melihat nilai ataupun kelulusan sebelum Anda membuka pengumuman di sini.
                        @endif
                    </p>
                </div>
            </div>

            <div class="shrink-0 flex items-center gap-2">
                <form action="{{ route('admin.cbt.hasil.togglePublish') }}" method="POST"
                      onsubmit="return confirm('{{ $publishScores ? 'Tutup kembali pengumuman nilai? Calon santri tidak akan bisa melihat nilai hasil ujian mereka.' : 'Buka pengumuman nilai sekarang? Seluruh calon santri akan dapat melihat nilai CBT dan status kelulusan mereka.' }}')">
                    @csrf
                    @if($publishScores)
                        <button type="submit" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Tutup / Rahasiakan Nilai</span>
                        </button>
                    @else
                        <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            <span>📢 Buka Nilai untuk Santri</span>
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- TAB PILIHAN KELOMPOK JENJANG (MTs vs MA) -->
    <div class="flex items-center gap-2 border-b border-gray-200 pb-1 overflow-x-auto">
        <a href="{{ route('admin.cbt.hasil.index', array_merge(request()->except('jenjang', 'page'), [])) }}"
           class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-t-xl transition {{ empty($jenjangFilter) ? 'border-b-2 border-brand-500 text-brand-600 bg-brand-50/40' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
            <span>Semua Jenjang</span>
            <span class="text-[10px] px-2 py-0.5 rounded-full {{ empty($jenjangFilter) ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-600' }}">
                {{ $totalParticipants }}
            </span>
        </a>

        <a href="{{ route('admin.cbt.hasil.index', array_merge(request()->except('page'), ['jenjang' => 'MTs'])) }}"
           class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-t-xl transition {{ $jenjangFilter === 'MTs' ? 'border-b-2 border-emerald-600 text-emerald-700 bg-emerald-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
            <span>🏫 Kelompok MTs</span>
            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $jenjangFilter === 'MTs' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-800' }}">
                {{ $totalMts }} Santri
            </span>
            <span class="text-[10px] text-gray-400 font-normal hidden sm:inline">
                ({{ $finishedMts }} Selesai • {{ $passedMts }} Lulus)
            </span>
        </a>

        <a href="{{ route('admin.cbt.hasil.index', array_merge(request()->except('page'), ['jenjang' => 'MA'])) }}"
           class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-t-xl transition {{ $jenjangFilter === 'MA' ? 'border-b-2 border-indigo-600 text-indigo-700 bg-indigo-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
            <span>🎓 Kelompok MA</span>
            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $jenjangFilter === 'MA' ? 'bg-indigo-600 text-white' : 'bg-indigo-100 text-indigo-800' }}">
                {{ $totalMa }} Santri
            </span>
            <span class="text-[10px] text-gray-400 font-normal hidden sm:inline">
                ({{ $finishedMa }} Selesai • {{ $passedMa }} Lulus)
            </span>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Total {{ $jenjangFilter ? 'Jenjang ' . $jenjangFilter : 'Santri' }}
                </span>
                <span class="badge-gray">Terdaftar</span>
            </div>
            <div class="text-2xl font-bold text-gray-800 mt-2">
                {{ $jenjangFilter === 'MTs' ? $totalMts : ($jenjangFilter === 'MA' ? $totalMa : $totalParticipants) }}
            </div>
        </div>

        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Selesai Ujian</span>
                <span class="badge-success">Tersubmit</span>
            </div>
            <div class="text-2xl font-bold text-emerald-600 mt-2">
                {{ $jenjangFilter === 'MTs' ? $finishedMts : ($jenjangFilter === 'MA' ? $finishedMa : $totalFinished) }}
            </div>
        </div>

        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Memenuhi KKM</span>
                <span class="badge-primary">&ge; {{ $kkm }}</span>
            </div>
            <div class="text-2xl font-bold text-brand-500 mt-2">
                {{ $jenjangFilter === 'MTs' ? $passedMts : ($jenjangFilter === 'MA' ? $passedMa : $totalPassed) }}
            </div>
        </div>

        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggaran Contek</span>
                <span class="{{ $totalViolations > 0 ? 'badge-error' : 'badge-gray' }}">Insiden</span>
            </div>
            <div class="text-2xl font-bold {{ $totalViolations > 0 ? 'text-red-600' : 'text-gray-800' }} mt-2">{{ $totalViolations }}</div>
        </div>
    </div>

    <!-- Filter Status & Search Bar -->
    <div class="ta-card p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.cbt.hasil.index', array_merge(request()->except('status', 'page'), [])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ empty($statusFilter) ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua Status
            </a>
            <a href="{{ route('admin.cbt.hasil.index', array_merge(request()->except('page'), ['status' => 'Selesai'])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $statusFilter === 'Selesai' ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Selesai Ujian
            </a>
            <a href="{{ route('admin.cbt.hasil.index', array_merge(request()->except('page'), ['status' => 'Sedang Ujian'])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $statusFilter === 'Sedang Ujian' ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Sedang Mengerjakan
            </a>
            <a href="{{ route('admin.cbt.hasil.index', array_merge(request()->except('page'), ['status' => 'Belum Ujian'])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $statusFilter === 'Belum Ujian' ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Belum Ujian
            </a>
        </div>

        <form method="GET" action="{{ route('admin.cbt.hasil.index') }}" class="flex items-center gap-2">
            @if(!empty($jenjangFilter))
                <input type="hidden" name="jenjang" value="{{ $jenjangFilter }}">
            @endif
            @if(!empty($statusFilter))
                <input type="hidden" name="status" value="{{ $statusFilter }}">
            @endif
            <div class="relative">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari santri, no. reg..."
                       class="ta-input w-48 sm:w-64 pl-8 py-1.5 text-xs">
                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="ta-btn-sm-primary">
                Cari
            </button>
            @if(!empty($search) || !empty($statusFilter) || !empty($jenjangFilter))
                <a href="{{ route('admin.cbt.hasil.index') }}" class="ta-btn-sm-outline">Reset Filter</a>
            @endif
        </form>
    </div>

    <!-- Table of Results -->
    <div class="ta-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="ta-table">
                <thead>
                    <tr>
                        <th>Santri / No. Reg</th>
                        <th>Jenjang</th>
                        <th>Status Ujian</th>
                        <th class="text-center">Nilai Ujian</th>
                        <th class="text-center">Status Kelulusan</th>
                        <th class="text-center">Proteksi Contek</th>
                        <th class="text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $reg)
                        @php
                            $isMts = str_contains(strtoupper($reg->jenjang ?? ''), 'MTS');
                            $isMa = str_contains(strtoupper($reg->jenjang ?? ''), 'MA');
                        @endphp
                        <tr>
                            <td>
                                <div class="font-semibold text-gray-800 text-sm">{{ $reg->nama_lengkap }}</div>
                                <div class="text-xs text-gray-400 font-mono mt-0.5">{{ $reg->no_registrasi }}</div>
                                @if(!empty($reg->catatan_penguji))
                                    <div class="mt-1 text-[11px] text-gray-600 bg-gray-50 px-2 py-0.5 rounded border border-gray-200 inline-block">
                                        Catatan: {{ $reg->catatan_penguji }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($isMts)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        🏫 {{ $reg->jenjang }}
                                    </span>
                                @elseif($isMa)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        🎓 {{ $reg->jenjang }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $reg->jenjang }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($reg->status_ujian === 'Selesai')
                                    <span class="badge-success">
                                        Selesai
                                    </span>
                                    <span class="text-[10px] text-gray-400 block mt-0.5">
                                        {{ $reg->ujian_selesai_at ? $reg->ujian_selesai_at->format('H:i, d/m/y') : '' }}
                                    </span>
                                @elseif($reg->status_ujian === 'Sedang Ujian')
                                    <span class="badge-warning">
                                        Sedang Ujian
                                    </span>
                                @else
                                    <span class="badge-gray">
                                        Belum Ujian
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($reg->nilai_ujian !== null)
                                    <span class="text-base font-bold {{ $reg->nilai_ujian >= $kkm ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $reg->nilai_ujian }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 block">/ 100</span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php $kel = $reg->status_kelulusan; @endphp
                                @if($kel === 'Lulus')
                                    <span class="badge-success">
                                        Lulus
                                    </span>
                                @elseif($kel === 'Tidak Lulus')
                                    <span class="badge-error">
                                        Tidak Lulus
                                    </span>
                                @elseif($kel === 'Lulus Bersyarat')
                                    <span class="badge-warning">
                                        Lulus Bersyarat
                                    </span>
                                @elseif($kel === 'Cadangan')
                                    <span class="badge-primary">
                                        Cadangan
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif

                                @if($reg->status_kelulusan_override)
                                    <span class="text-[10px] text-brand-500 block mt-0.5 font-medium">Override</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($reg->pelanggaran_curang_count > 0)
                                    <button type="button" onclick="openViolationLogModal({{ json_encode($reg->nama_lengkap) }}, {{ json_encode($reg->no_registrasi) }}, {{ json_encode($reg->pelanggaran_log_array) }})"
                                            class="badge-error cursor-pointer hover:opacity-80 transition" title="Lihat log pelanggaran">
                                        ⚠️ {{ $reg->pelanggaran_curang_count }} Pelanggaran
                                    </button>
                                @else
                                    <span class="badge-gray">
                                        Bersih (0)
                                    </span>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button type="button" onclick="openOverrideModal({{ json_encode($reg) }})"
                                            class="ta-btn-sm-outline" title="Ubah nilai santri">
                                        Ubah Nilai
                                    </button>

                                    <!-- Tombol Izinkan Ujian Ulang / Reset Pelanggaran -->
                                    <form action="{{ route('admin.cbt.hasil.reset', $reg->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Izinkan santri an. {{ addslashes($reg->nama_lengkap) }} ({{ $reg->no_registrasi }}) untuk mengulang ujian CBT?\n\nSesi ujian akan dibuka kembali, log pelanggaran dibersihkan, dan lembar jawaban akan direset.')">
                                        @csrf
                                        <button type="submit" class="p-1.5 text-xs text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg border border-amber-200 transition"
                                                title="Izinkan Ujian Ulang / Reset Pelanggaran">
                                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            <span class="hidden sm:inline ml-1 font-semibold">Ujian Ulang</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-400 text-sm">
                                Belum ada data pendaftar ujian yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            {{ $students->links() }}
        </div>
        @endif
    </div>

</div>

<!-- MODAL UBAH / OVERRIDE NILAI & KELULUSAN -->
<div id="modalOverride" class="ta-modal-backdrop hidden">
    <div class="ta-modal">
        <div class="ta-modal-header">
            <div>
                <h3 class="text-base font-bold text-gray-800">Ubah Nilai &amp; Kelulusan</h3>
                <p id="modalSantriName" class="text-xs text-gray-500 mt-0.5"></p>
            </div>
            <button type="button" onclick="document.getElementById('modalOverride').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <form id="formOverride" method="POST">
            @csrf
            <div class="ta-modal-body space-y-4">
                <!-- Preset Cepat -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Preset Nilai Cepat
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="setOverrideScore({{ $kkm }}, 'Lulus')" class="ta-btn-sm-outline">
                            KKM ({{ $kkm }})
                        </button>
                        <button type="button" onclick="setOverrideScore(75, 'Lulus')" class="ta-btn-sm-outline">
                            Nilai 75
                        </button>
                        <button type="button" onclick="setOverrideScore(80, 'Lulus')" class="ta-btn-sm-outline">
                            Nilai 80
                        </button>
                    </div>
                    <div class="grid grid-cols-4 gap-1.5 mt-2">
                        <button type="button" onclick="addOverrideScore(5)" class="ta-btn-sm-secondary">+5 Poin</button>
                        <button type="button" onclick="addOverrideScore(10)" class="ta-btn-sm-secondary">+10 Poin</button>
                        <button type="button" onclick="addOverrideScore(15)" class="ta-btn-sm-secondary">+15 Poin</button>
                        <button type="button" onclick="addOverrideScore(20)" class="ta-btn-sm-secondary">+20 Poin</button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Nilai Ujian (0 - 100)
                    </label>
                    <input type="number" name="nilai_ujian" id="overrideScore" min="0" max="100"
                           class="ta-input font-bold">
                    <span class="text-[11px] text-gray-400 mt-1 block">Standar KKM saat ini: <strong>{{ $kkm }}</strong>.</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Status Kelulusan (Override)
                    </label>
                    <select name="status_kelulusan_override" id="overrideStatus" class="ta-input font-medium">
                        <option value="">-- Otomatis Berdasarkan Nilai KKM --</option>
                        <option value="Lulus">Lulus</option>
                        <option value="Tidak Lulus">Tidak Lulus</option>
                        <option value="Lulus Bersyarat">Lulus Bersyarat</option>
                        <option value="Cadangan">Cadangan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Catatan Dewan Penguji
                    </label>
                    <textarea name="catatan_penguji" id="overrideCatatan" rows="2" placeholder="Catatan afirmasi atau dispensasi..."
                              class="ta-input"></textarea>
                </div>
            </div>

            <div class="ta-modal-footer">
                <button type="button" onclick="document.getElementById('modalOverride').classList.add('hidden')" class="ta-btn-sm-outline">
                    Batal
                </button>
                <button type="submit" class="ta-btn-sm-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL LIHAT LOG PELANGGARAN CONTEK -->
<div id="modalViolationLog" class="ta-modal-backdrop hidden">
    <div class="ta-modal">
        <div class="ta-modal-header">
            <div>
                <h3 class="text-base font-bold text-gray-800">Log Pelanggaran Ujian</h3>
                <p id="violationSantriInfo" class="text-xs text-gray-500 mt-0.5"></p>
            </div>
            <button type="button" onclick="document.getElementById('modalViolationLog').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <div class="ta-modal-body">
            <div id="violationLogList" class="space-y-2 max-h-72 overflow-y-auto pr-1">
                <!-- Injected via JavaScript -->
            </div>
        </div>

        <div class="ta-modal-footer">
            <button type="button" onclick="document.getElementById('modalViolationLog').classList.add('hidden')" class="ta-btn-sm-outline">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openOverrideModal(reg) {
        document.getElementById('modalSantriName').innerText = `${reg.nama_lengkap} (${reg.no_registrasi})`;
        document.getElementById('overrideScore').value = reg.nilai_ujian !== null ? reg.nilai_ujian : '';
        document.getElementById('overrideStatus').value = reg.status_kelulusan_override || '';
        document.getElementById('overrideCatatan').value = reg.catatan_penguji || '';
        document.getElementById('formOverride').action = `/admin/cbt/hasil/${reg.id}`;
        document.getElementById('modalOverride').classList.remove('hidden');
    }

    function setOverrideScore(score, status) {
        document.getElementById('overrideScore').value = score;
        document.getElementById('overrideStatus').value = status;
        document.getElementById('overrideCatatan').value = `Nilai disesuaikan menjadi ${score} agar memenuhi standar KKM (${score >= {{ $kkm }} ? 'Lulus' : 'Belum Lulus'}).`;
    }

    function addOverrideScore(points) {
        const cur = parseInt(document.getElementById('overrideScore').value) || 0;
        const newScore = Math.min(100, cur + points);
        document.getElementById('overrideScore').value = newScore;
        if (newScore >= {{ $kkm }}) {
            document.getElementById('overrideStatus').value = 'Lulus';
        }
        document.getElementById('overrideCatatan').value = `Penambahan afirmasi dewan penguji +${points} poin (dari ${cur} menjadi ${newScore}).`;
    }

    function openViolationLogModal(nama, noReg, logs) {
        document.getElementById('violationSantriInfo').innerText = `${nama} — ${noReg}`;
        const container = document.getElementById('violationLogList');
        container.innerHTML = '';

        if (!logs || logs.length === 0) {
            container.innerHTML = '<p class="text-xs text-gray-400 italic text-center py-6">Tidak ada catatan pelanggaran tersimpan.</p>';
        } else {
            logs.forEach((item, idx) => {
                const el = document.createElement('div');
                el.className = 'p-3 bg-red-50/70 border border-red-200 rounded-lg text-xs space-y-1';
                el.innerHTML = `
                    <div class="flex items-center justify-between text-red-800 font-semibold">
                        <span>Peringatan #${idx + 1} (${item.tipe || 'Kecurangan'})</span>
                        <span class="text-[11px] text-gray-500 font-mono">${item.waktu || '-'}</span>
                    </div>
                    <p class="text-gray-700">${item.pesan || 'Beralih tab browser atau aplikasi lain.'}</p>
                `;
                container.appendChild(el);
            });
        }

        document.getElementById('modalViolationLog').classList.remove('hidden');
    }

    function toggleDropdown(menuId) {
        const menu = document.getElementById(menuId);
        if (!menu) return;
        const isHidden = menu.classList.contains('hidden');
        ['dropdownExportNilaiMenu', 'dropdownExportLulusMenu', 'dropdownCetakMenu'].forEach(id => {
            const m = document.getElementById(id);
            if (m && id !== menuId) m.classList.add('hidden');
        });
        if (isHidden) {
            menu.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
        }
    }

    document.addEventListener('click', function(e) {
        const wrappers = ['dropdownExportNilaiWrap', 'dropdownExportLulusWrap', 'dropdownCetakWrap'];
        let inside = false;
        wrappers.forEach(id => {
            const wrap = document.getElementById(id);
            if (wrap && wrap.contains(e.target)) inside = true;
        });
        if (!inside) {
            ['dropdownExportNilaiMenu', 'dropdownExportLulusMenu', 'dropdownCetakMenu'].forEach(id => {
                const m = document.getElementById(id);
                if (m) m.classList.add('hidden');
            });
        }
    });
</script>
@endsection

@extends('admin.layout')

@section('title', 'Kelola Nilai Ujian: ' . $exam->mata_pelajaran)

@section('content')
<div class="space-y-6" x-data="{ modalTambah: false, modalImport: false, modalTarik: false }">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.cbt.madrasah.index') }}" class="text-xs text-emerald-700 hover:underline font-semibold flex items-center gap-1">
                    &larr; Kembali ke Daftar Ujian
                </a>
                <span class="text-gray-300">•</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $exam->jenjang === 'MA' ? 'bg-emerald-100 text-emerald-800' : 'bg-sky-100 text-sky-800' }}">
                    Kelas {{ $exam->tingkat_kelas }} {{ $exam->jenjang }}
                </span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800">
                    {{ $exam->jurusan }}
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-snug">
                {{ $exam->mata_pelajaran }}
            </h1>
            <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $exam->nama_ujian }} &bull; Guru: <strong class="text-gray-700">{{ $exam->nama_guru ?: '-' }}</strong></p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.cbt.madrasah.cetak', $exam->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-md transition" title="Buka dan Cetak Format Dokumen Resmi Hasil Ujian">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Cetak Laporan Resmi
            </a>
            <a href="{{ route('admin.cbt.madrasah.edit', $exam->id) }}" class="ta-btn-secondary text-xs px-3.5 py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                Edit
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="ta-alert ta-alert-success flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">&times;</button>
    </div>
    @endif
    @if(session('error'))
    <div class="ta-alert ta-alert-danger flex items-center justify-between">
        <span>{{ session('error') }}</span>
        <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 font-bold">&times;</button>
    </div>
    @endif

    <!-- Kartu Statistik Ujian (Sesuai Rincian Detail Ujian di Dokumen Cetak) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
        <div class="p-3.5 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Jumlah Soal</span>
            <span class="text-xl font-bold font-mono text-gray-900 mt-1 block">{{ $exam->jumlah_soal }} Butir</span>
            <span class="text-[10px] text-gray-400">Durasi: {{ $exam->durasi_menit }} Menit</span>
        </div>
        <div class="p-3.5 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Standar KKM</span>
            <span class="text-xl font-bold font-mono text-amber-700 mt-1 block">{{ number_format($exam->kkm, 1) }}</span>
            <span class="text-[10px] text-gray-400">Batas Kelulusan</span>
        </div>
        <div class="p-3.5 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider block">Tertinggi</span>
            <span class="text-xl font-bold font-mono text-emerald-700 mt-1 block">{{ number_format($statTertinggi, 2) }}</span>
            <span class="text-[10px] text-emerald-600/70">Skor Maksimal</span>
        </div>
        <div class="p-3.5 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-[11px] font-semibold text-rose-600 uppercase tracking-wider block">Terendah</span>
            <span class="text-xl font-bold font-mono text-rose-600 mt-1 block">{{ number_format($statTerendah, 2) }}</span>
            <span class="text-[10px] text-rose-600/70">Skor Minimal</span>
        </div>
        <div class="p-3.5 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-[11px] font-semibold text-purple-600 uppercase tracking-wider block">Rata-rata</span>
            <span class="text-xl font-bold font-mono text-purple-700 mt-1 block">{{ round($statRataRata) }}</span>
            <span class="text-[10px] text-purple-600/70">Mean Kelas ({{ number_format($statRataRata, 2) }})</span>
        </div>
        <div class="p-3.5 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-[11px] font-semibold text-sky-600 uppercase tracking-wider block">Total Peserta</span>
            <span class="text-xl font-bold font-mono text-sky-700 mt-1 block">{{ $totalPeserta }} Santri</span>
            <span class="text-[10px] text-sky-600/70">Telah Mengikuti</span>
        </div>
    </div>

    <!-- Panel Tombol Aksi Peserta & Nilai -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
        <div class="flex items-center gap-2 flex-wrap">
            <button type="button" @click="modalTarik = true" class="ta-btn-primary text-xs px-3.5 py-2 flex items-center gap-1.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Tarik Santri Aktif
            </button>
            <button type="button" @click="modalTambah = true" class="ta-btn-secondary text-xs px-3.5 py-2 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                + Tambah Manual
            </button>
            <button type="button" @click="modalImport = true" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import Excel
            </button>
            <a href="{{ route('admin.cbt.madrasah.downloadTemplate', $exam->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-gray-600 hover:text-gray-900 border border-gray-200 text-xs font-semibold hover:bg-gray-50 transition" title="Unduh Format File Excel untuk Input Nilai">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Template Excel
            </a>
        </div>

        <div>
            <button type="submit" form="formBulkScores" class="ta-btn-primary px-5 py-2 text-xs font-bold shadow-md flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Simpan Semua Nilai
            </button>
        </div>
    </div>

    <!-- Tabel Daftar Peserta & Nilai Ujian -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-theme-xs overflow-hidden">
        <form action="{{ route('admin.cbt.madrasah.simpanNilaiBulk', $exam->id) }}" method="POST" id="formBulkScores">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-600 font-bold uppercase tracking-wider">
                            <th class="p-3.5 w-12 text-center">No</th>
                            <th class="p-3.5 min-w-[200px]">Nama Peserta</th>
                            <th class="p-3.5 w-24 text-center">Kelas</th>
                            <th class="p-3.5 w-32 text-center">Jurusan</th>
                            <th class="p-3.5 w-36 text-center">Jumlah Benar (dari {{ $exam->jumlah_soal }})</th>
                            <th class="p-3.5 w-28 text-center">Nilai Akhir</th>
                            <th class="p-3.5 w-28 text-center">Status</th>
                            <th class="p-3.5 w-16 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($results as $index => $res)
                        @php
                            $isLulus = ($res->nilai >= $exam->kkm);
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="p-3 text-center font-mono font-bold text-gray-500">
                                {{ $index + 1 }}
                            </td>
                            <td class="p-3 font-semibold text-gray-900">
                                <span class="uppercase block font-bold">{{ $res->nama_peserta }}</span>
                                @if($res->nomor_peserta)
                                <span class="text-[10px] text-gray-400 font-mono">NIS: {{ $res->nomor_peserta }}</span>
                                @endif
                            </td>
                            <td class="p-3 text-center font-mono font-semibold text-gray-700">
                                {{ $res->kelas }}
                            </td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    {{ $res->jurusan }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <input type="number" 
                                           name="results[{{ $res->id }}][jumlah_benar]" 
                                           value="{{ $res->jumlah_benar }}" 
                                           min="0" 
                                           max="{{ $exam->jumlah_soal }}"
                                           data-total="{{ $exam->jumlah_soal }}"
                                           data-target="score_{{ $res->id }}"
                                           oninput="recalcScoreInline(this)"
                                           class="w-18 text-center font-mono font-bold text-xs rounded-lg border border-gray-300 py-1 px-2 focus:border-emerald-500 outline-none">
                                    <span class="text-gray-400 font-mono">/ {{ $exam->jumlah_soal }}</span>
                                </div>
                            </td>
                            <td class="p-3 text-center font-mono font-bold">
                                <span id="score_{{ $res->id }}" class="text-sm px-2.5 py-1 rounded-lg {{ $isLulus ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-700' }}">
                                    {{ number_format($res->nilai, 2) }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <select name="results[{{ $res->id }}][status]" class="text-[11px] font-semibold rounded-lg border border-gray-200 bg-gray-50 py-1 px-2 outline-none">
                                    <option value="Hadir" {{ $res->status === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="Susulan" {{ $res->status === 'Susulan' ? 'selected' : '' }}>Susulan</option>
                                    <option value="Tidak Hadir" {{ $res->status === 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                </select>
                            </td>
                            <td class="p-3 text-center">
                                <button type="button" onclick="confirmDeletePeserta({{ $res->id }}, '{{ addslashes($res->nama_peserta) }}')" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Peserta">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-12 text-center text-gray-400 text-xs">
                                Belum ada peserta pada ujian ini. Silakan klik tombol <strong>"Tarik Santri Aktif"</strong> atau <strong>"+ Tambah Manual"</strong> di atas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($results->isNotEmpty())
            <div class="p-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                <span class="text-xs text-gray-500">
                    Menampilkan <strong class="font-bold text-gray-800">{{ $results->count() }}</strong> santri peserta ujian.
                </span>
                <button type="submit" class="ta-btn-primary px-6 py-2 text-xs font-bold shadow-md">
                    Simpan Semua Perubahan Nilai
                </button>
            </div>
            @endif
        </form>
    </div>

    <!-- Hidden Form Delete Peserta -->
    <form id="formDeletePeserta" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal 1: Tarik Santri Aktif Otomatis -->
    <div x-show="modalTarik" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl border border-gray-200 max-w-md w-full p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Tarik Santri Aktif Otomatis
                </h3>
                <button type="button" @click="modalTarik = false" class="text-gray-400 hover:text-gray-700 text-lg font-bold">&times;</button>
            </div>
            <p class="text-xs text-gray-500">Pilih rombongan belajar / kelas untuk menarik santri aktif langsung ke daftar peserta ujian ini:</p>
            <form action="{{ route('admin.cbt.madrasah.tarikSantri', $exam->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Pilih Kelas</label>
                    <select name="pilihan_kelas" required class="ta-input text-xs font-bold">
                        <option value="__ALL_TINGKAT__">Semua Kelas {{ $exam->tingkat_kelas }} (Tingkat {{ $exam->tingkat_kelas }} Lengkap)</option>
                        @foreach($classrooms as $cls)
                            <option value="{{ $cls }}">Kelas {{ $cls }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <button type="button" @click="modalTarik = false" class="ta-btn-secondary text-xs">Batal</button>
                    <button type="submit" class="ta-btn-primary text-xs px-4 py-2 font-bold">
                        Tarik Santri Sekarang &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Tambah Peserta Manual -->
    <div x-show="modalTambah" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl border border-gray-200 max-w-lg w-full p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Peserta Ujian Manual
                </h3>
                <button type="button" @click="modalTambah = false" class="text-gray-400 hover:text-gray-700 text-lg font-bold">&times;</button>
            </div>
            <form action="{{ route('admin.cbt.madrasah.storePeserta', $exam->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap Peserta *</label>
                    <input type="text" name="nama_peserta" required placeholder="Contoh: AHMAD FAUZAN" class="ta-input text-xs font-semibold uppercase">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kelas *</label>
                        <input type="text" name="kelas" required value="{{ $exam->tingkat_kelas }}" class="ta-input text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Jurusan *</label>
                        <input type="text" name="jurusan" required value="{{ $exam->jurusan }}" class="ta-input text-xs font-mono uppercase">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Benar (Maks: {{ $exam->jumlah_soal }}) *</label>
                        <input type="number" name="jumlah_benar" required min="0" max="{{ $exam->jumlah_soal }}" value="0" class="ta-input text-xs font-mono font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Status Kehadiran</label>
                        <select name="status" class="ta-input text-xs font-semibold">
                            <option value="Hadir">Hadir</option>
                            <option value="Susulan">Susulan</option>
                            <option value="Tidak Hadir">Tidak Hadir</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <button type="button" @click="modalTambah = false" class="ta-btn-secondary text-xs">Batal</button>
                    <button type="submit" class="ta-btn-primary text-xs px-4 py-2 font-bold">
                        Simpan Peserta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 3: Import Nilai Excel -->
    <div x-show="modalImport" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl border border-gray-200 max-w-lg w-full p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import Nilai Ujian dari Excel
                </h3>
                <button type="button" @click="modalImport = false" class="text-gray-400 hover:text-gray-700 text-lg font-bold">&times;</button>
            </div>
            <p class="text-xs text-gray-500">Unggah file Excel hasil koreksi atau rekapan nilai santri (.xlsx, .xls). Sistem akan otomatis memperbarui nilai peserta yang cocok atau menambahkan peserta baru.</p>
            <form action="{{ route('admin.cbt.madrasah.importExcel', $exam->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl text-center">
                    <input type="file" name="excel_file" required accept=".xlsx,.xls,.csv" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.cbt.madrasah.downloadTemplate', $exam->id) }}" class="text-xs text-emerald-700 hover:underline font-semibold">
                        &darr; Unduh Format Contoh Excel
                    </a>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="modalImport = false" class="ta-btn-secondary text-xs">Batal</button>
                        <button type="submit" class="ta-btn-primary text-xs px-4 py-2 font-bold">
                            Mulai Import Nilai
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function recalcScoreInline(input) {
        const total = parseFloat(input.dataset.total) || 50;
        const benar = Math.min(Math.max(parseFloat(input.value) || 0, 0), total);
        const score = ((benar / total) * 100).toFixed(2);
        const targetEl = document.getElementById(input.dataset.target);
        if (targetEl) {
            targetEl.innerText = score;
            const kkm = {{ $exam->kkm }};
            if (parseFloat(score) >= kkm) {
                targetEl.className = 'text-sm px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800';
            } else {
                targetEl.className = 'text-sm px-2.5 py-1 rounded-lg bg-rose-100 text-rose-700';
            }
        }
    }

    function confirmDeletePeserta(resId, nama) {
        if (confirm(`Apakah Anda yakin ingin menghapus peserta '${nama}' dari ujian ini?`)) {
            const form = document.getElementById('formDeletePeserta');
            form.action = `/admin/cbt/madrasah/{{ $exam->id }}/peserta/${resId}`;
            form.submit();
        }
    }
</script>
@endsection

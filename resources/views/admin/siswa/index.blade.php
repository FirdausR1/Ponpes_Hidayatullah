@extends('admin.layout')

@section('title', 'Manajemen Siswa & Santri Aktif')

@section('content')
<div class="space-y-6">

    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200">Database Induk Pesantren</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Manajemen Siswa &amp; Santri Aktif</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Kelola data pokok santri, status kelas, angkatan tahun masuk, kenaikan kelas, dan akses login santri.</p>
        </div>
        
        <!-- Action Buttons (TailAdmin Standard) -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- 1. Tombol Export Excel Santri (Sesuai Filter yang Aktif) -->
            <a href="{{ route('admin.siswa.exportExcel', request()->query()) }}" class="inline-flex items-center gap-2 rounded-lg border border-emerald-300 bg-emerald-50 px-3.5 py-2.5 text-xs font-semibold text-emerald-800 shadow-theme-xs hover:bg-emerald-100 transition cursor-pointer" title="Unduh data santri aktif dalam format Excel (.xlsx)">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Export Data Siswa</span>
            </a>

            <!-- 2. Tombol Import Massal Template Excel -->
            <button type="button" onclick="document.getElementById('modalImportExcel').classList.remove('hidden')" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-emerald-700 transition cursor-pointer" title="Import data banyak santri sekaligus dengan template Excel/CSV">
                <svg class="w-4 h-4 text-emerald-600 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                <span>Import Siswa Masal</span>
            </button>

            <!-- Tombol Kenaikan Kelas -->
            <a href="{{ route('admin.siswa.kenaikanKelas') }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-800 px-3.5 py-2.5 text-xs font-semibold shadow-theme-xs transition" title="Proses kenaikan kelas santri">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="17 11 12 6 7 11"></polyline><polyline points="17 18 12 13 7 18"></polyline></svg>
                <span>Kenaikan Kelas</span>
            </a>

            <!-- Tombol Penempatan Kelas Santri Baru -->
            <a href="{{ route('admin.siswa.penempatanKelas') }}" class="inline-flex items-center gap-2 rounded-lg border border-purple-200 bg-purple-50 hover:bg-purple-100 text-purple-800 px-3.5 py-2.5 text-xs font-semibold shadow-theme-xs transition" title="Bagi dan plotting santri baru ke kelas 7 / 10 sesuai peringkat atau manual">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Penempatan Kelas Baru</span>
            </a>

            <!-- 3. Tombol Tarik Santri Baru dari PSB -->
            <button type="button" onclick="document.getElementById('modalBulkImportPsb').classList.remove('hidden')" class="inline-flex items-center gap-2 rounded-lg bg-emerald-50 border border-emerald-300 hover:bg-emerald-100 text-emerald-800 px-3.5 py-2.5 text-xs font-semibold shadow-theme-xs transition cursor-pointer" title="Impor santri yang berstatus DITERIMA dari PSB ke data santri aktif">
                <svg class="w-4 h-4 text-emerald-600 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                <span>Tarik dari PSB</span>
                @if($unimportedPsbCount > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-600 text-white animate-pulse">{{ $unimportedPsbCount }}</span>
                @endif
            </button>

            <!-- 4. Tombol Tambah Manual -->
            <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-gray-800 transition">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25012 3C5.25012 2.58579 5.58591 2.25 6.00012 2.25C6.41433 2.25 6.75012 2.58579 6.75012 3V5.25012L9.00034 5.25012C9.41455 5.25012 9.75034 5.58591 9.75034 6.00012C9.75034 6.41433 9.41455 6.75012 9.00034 6.75012H6.75012V9.00034C6.75012 9.41455 6.41433 9.75034 6.00012 9.75034C5.58591 9.75034 5.25012 9.41455 5.25012 9.00034L5.25012 6.75012H3C2.58579 6.75012 2.25 6.41433 2.25 6.00012C2.25 5.58591 2.58579 5.25012 3 5.25012H5.25012V3Z" fill="currentColor"/>
                </svg>
                <span>Tambah Santri</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi Santri Lulus PSB Siap Diimpor -->
    @if($unimportedPsbCount > 0)
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 shadow-theme-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-lg shrink-0">
                🎓
            </span>
            <div>
                <p class="text-xs sm:text-sm font-bold text-emerald-950">Terdapat {{ $unimportedPsbCount }} Calon Santri DITERIMA di PSB yang Belum Ditarik ke Data Siswa Aktif</p>
                <p class="text-xs text-emerald-800 mt-0.5">Anda dapat memindahkan seluruh santri yang telah lulus seleksi secara massal dalam 1 klik.</p>
            </div>
        </div>
        <button type="button" onclick="document.getElementById('modalBulkImportPsb').classList.remove('hidden')" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-theme-xs transition whitespace-nowrap">
            Add Massal Sekarang &rarr;
        </button>
    </div>
    @endif

    <!-- Stats Ringkas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Metric 1 -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block">Total Santri</span>
                    <h4 class="mt-2 text-2xl font-bold text-gray-800">{{ $stats['total'] }}</h4>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gray-100 text-gray-600">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-gray-500">
                <span class="font-medium text-emerald-600">Terdaftar</span> di sistem pesantren
            </div>
        </div>

        <!-- Metric 2 -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-emerald-600 uppercase tracking-wider block">Santri Aktif</span>
                    <h4 class="mt-2 text-2xl font-bold text-emerald-700">{{ $stats['aktif'] }}</h4>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.7071 5.29289a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.5858l7.2929-7.2929a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-emerald-600">
                <span class="font-medium">Mukim &amp; Laju</span> aktif KBM
            </div>
        </div>

        <!-- Metric 3 -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-blue-600 uppercase tracking-wider block">Jenjang MTs</span>
                    <h4 class="mt-2 text-2xl font-bold text-blue-700">{{ $stats['mts'] }}</h4>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-blue-600">
                <span class="font-medium">Tingkat Menengah</span> Pertama (Kelas VII-IX)
            </div>
        </div>

        <!-- Metric 4 -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-purple-600 uppercase tracking-wider block">Jenjang MA</span>
                    <h4 class="mt-2 text-2xl font-bold text-purple-700">{{ $stats['ma'] }}</h4>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5z"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-purple-600">
                <span class="font-medium">Tingkat Aliyah</span> (Kelas X-XII)
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian Lengkap -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
        <form method="GET" action="{{ route('admin.siswa.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search Input -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari santri, NIS, username..." class="h-10 w-full pl-9 pr-4 text-xs text-gray-800 bg-white border border-gray-300 rounded-lg focus:border-brand-500 outline-none">
            </div>

            <!-- Filter Kelas -->
            <div>
                <select name="kelas" onchange="this.form.submit()" class="h-10 w-full text-xs rounded-lg px-3 border border-gray-300 bg-white text-gray-700 outline-none cursor-pointer">
                    <option value="">Semua Kelas</option>
                    @foreach($allClasses as $kls)
                        <option value="{{ $kls }}" {{ request('kelas') == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Tahun Masuk / Angkatan -->
            <div>
                <select name="tahun_masuk" onchange="this.form.submit()" class="h-10 w-full text-xs rounded-lg px-3 border border-gray-300 bg-white text-gray-700 outline-none cursor-pointer">
                    <option value="">Semua Angkatan</option>
                    @foreach($allYears as $th)
                        <option value="{{ $th }}" {{ request('tahun_masuk') == $th ? 'selected' : '' }}>Angkatan {{ $th }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <select name="status" onchange="this.form.submit()" class="h-10 w-full text-xs rounded-lg px-3 border border-gray-300 bg-white text-gray-700 outline-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Alumni" {{ request('status') == 'Alumni' ? 'selected' : '' }}>Alumni (Lulus)</option>
                    <option value="Mutasi" {{ request('status') == 'Mutasi' ? 'selected' : '' }}>Mutasi</option>
                    <option value="Cuti" {{ request('status') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                </select>
            </div>

            <!-- Action Filter -->
            <div class="flex items-center gap-2">
                <button type="submit" class="h-10 flex-1 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-semibold transition">
                    Filter
                </button>
                @if(request()->anyFilled(['q', 'kelas', 'tahun_masuk', 'status', 'jenjang']))
                    <a href="{{ route('admin.siswa.index') }}" class="h-10 px-3 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-600 text-xs font-medium flex items-center justify-center transition" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Data Santri -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70">
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Santri &amp; Akun Login</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Identitas NIS / NISN</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas &amp; Jenjang</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tahun Masuk (Angkatan)</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Santri</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Wali &amp; Kontak</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-50/60 transition">
                            <!-- Santri & Akun Login -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($student->foto)
                                        <div class="w-10 h-12 overflow-hidden rounded-lg border border-gray-200 shrink-0 bg-gray-100">
                                            <img src="{{ $student->foto }}" alt="{{ $student->nama_lengkap }}" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-10 h-12 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ strtoupper(substr($student->nama_lengkap, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('admin.siswa.show', $student->id) }}" class="font-bold text-gray-900 hover:text-emerald-700 text-sm block transition">
                                            {{ $student->nama_lengkap }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-0.5 text-xs text-gray-500">
                                            <span>{{ $student->jenis_kelamin }}</span>
                                            @if($student->tanggal_lahir)
                                                <span>• Tgl Lahir: {{ $student->tanggal_lahir }}</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-gray-400 font-mono mt-0.5">
                                            User: <strong class="text-gray-700">{{ $student->username ?: $student->nis }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- NIS / NISN -->
                            <td class="px-5 py-4">
                                <span class="font-mono font-bold text-gray-900 text-xs block">{{ $student->nis }}</span>
                                <span class="font-mono text-xs text-gray-400 block mt-0.5">{{ $student->nisn ?: '—' }}</span>
                            </td>

                            <!-- Label Kelas & Jenjang -->
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold {{ str_contains($student->jenjang, 'MA') ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                    Kelas {{ $student->kelas }}
                                </span>
                                <span class="text-xs text-gray-500 block mt-1">
                                    {{ $student->jenjang }}
                                </span>
                            </td>

                            <!-- Label Tahun Masuk / Angkatan -->
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    Angkatan {{ $student->tahun_masuk }}
                                </span>
                                @if($student->kamar_asrama)
                                    <span class="text-[11px] text-gray-400 block mt-1 truncate max-w-[130px]" title="{{ $student->kamar_asrama }}">
                                        🏠 {{ $student->kamar_asrama }}
                                    </span>
                                @endif
                            </td>

                            <!-- Status Santri -->
                            <td class="px-5 py-4">
                                @if($student->status === 'Aktif')
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @elseif($student->status === 'Alumni')
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold bg-purple-100 text-purple-800">
                                        Alumni (Lulus)
                                    </span>
                                @elseif($student->status === 'Mutasi')
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold bg-amber-100 text-amber-800">
                                        Mutasi
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $student->status }}
                                    </span>
                                @endif
                            </td>

                            <!-- Kontak Wali -->
                            <td class="px-5 py-4 text-xs">
                                <span class="font-medium text-gray-800 block">{{ $student->nama_wali ?: '-' }}</span>
                                @if($student->no_whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->no_whatsapp) }}" target="_blank" class="text-emerald-600 hover:text-emerald-700 font-mono inline-flex items-center gap-1 mt-0.5">
                                        <span>💬</span> {{ $student->no_whatsapp }}
                                    </a>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.siswa.show', $student->id) }}" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Lihat Kartu Biodata">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/><path fill-rule="evenodd" d="M.833 10C1.944 5.833 5.556 2.5 10 2.5s8.056 3.333 9.167 7.5c-1.111 4.167-4.722 7.5-9.167 7.5S1.944 14.167.833 10zM14.167 10a4.167 4.167 0 11-8.334 0 4.167 4.167 0 018.334 0z" clip-rule="evenodd"/></svg>
                                    </a>
                                    <a href="{{ route('admin.siswa.edit', $student->id) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Data &amp; Kelas">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M13.586 2.586a2 2 0 012.828 0l1 1a2 2 0 010 2.828l-10 10A2 2 0 016 17H3a1 1 0 01-1-1v-3a2 2 0 01.586-1.414l10-10z" clip-rule="evenodd"/></svg>
                                    </a>
                                    <form action="{{ route('admin.siswa.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Hapus data santri {{ $student->nama_lengkap }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <p class="text-sm font-semibold text-gray-700">Belum ada data santri ditemukan.</p>
                                <p class="text-xs text-gray-400 mt-1">Gunakan tombol Import Siswa Masal atau Tambah Santri untuk mulai mengelola.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                {{ $students->links() }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL 1: IMPORT MASSAL SANTRI DENGAN TEMPLATE EXCEL / CSV -->
<div id="modalImportExcel" class="ta-modal-backdrop hidden">
    <div class="ta-modal max-w-lg">
        <div class="ta-modal-header">
            <div>
                <h3 class="font-bold text-gray-800 text-base">Import Massal Santri (Template Excel)</h3>
                <p class="text-xs text-gray-500 mt-0.5">Unggah data santri secara serentak menggunakan file Excel/CSV.</p>
            </div>
            <button type="button" onclick="document.getElementById('modalImportExcel').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <form action="{{ route('admin.siswa.importExcel') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="ta-modal-body space-y-4 text-xs">
                <!-- Download Template Step -->
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl space-y-2">
                    <span class="font-bold text-emerald-950 block">Langkah 1: Unduh Format Template Excel (.xlsx)</span>
                    <p class="text-gray-600 leading-relaxed text-[11px]">
                        Gunakan file template resmi Microsoft Excel di bawah ini agar susunan kolom sesuai dengan sistem (termasuk kolom nama, tanggal lahir, kelas, kamar asrama, dan wali).
                    </p>
                    <a href="{{ route('admin.siswa.downloadTemplate') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs shadow-theme-xs transition">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M8.5 13.5l2 2.5-2 2.5h1.5l1.25-1.75L12.5 18.5H14l-2-2.5 2-2.5h-1.5l-1.25 1.75L10 13.5H8.5z"/></svg>
                        <span>Unduh Format Template Excel (.xlsx)</span>
                    </a>
                </div>

                <!-- Upload File Step -->
                <div class="space-y-2">
                    <label class="block font-bold text-gray-800 uppercase tracking-wider">Langkah 2: Pilih File Excel yang Telah Diisi</label>
                    <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="ta-input text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
                    <p class="text-[11px] text-gray-500">Mendukung file dokumen Microsoft Excel <code>.xlsx</code> atau <code>.xls</code> (Maksimal 15 MB).</p>
                </div>

                <!-- Otomatisasi Akun Login Info -->
                <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 text-[11px] space-y-1">
                    <span class="font-bold block text-blue-800">🔑 Pembuatan Akun Login Santri Otomatis:</span>
                    <ul class="list-disc list-inside space-y-0.5 text-gray-700">
                        <li><strong>Username:</strong> Otomatis diisi dari kolom Username atau NIS / NISN.</li>
                        <li><strong>Password:</strong> Otomatis menggunakan <strong>tanggal lahir</strong> santri (format: DDMMYYYY, contoh 15 Mei 2010 = <code>15052010</code>).</li>
                    </ul>
                </div>
            </div>

            <div class="ta-modal-footer">
                <button type="button" onclick="document.getElementById('modalImportExcel').classList.add('hidden')" class="ta-btn-sm-outline">
                    Batal
                </button>
                <button type="submit" class="ta-btn-sm-primary">
                    Mulai Proses Import Santri
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: BULK IMPORT DARI PENDAFTAR PSB DITERIMA -->
<div id="modalBulkImportPsb" class="ta-modal-backdrop hidden">
    <div class="ta-modal max-w-md">
        <div class="ta-modal-header">
            <div>
                <h3 class="font-bold text-gray-800 text-base">Tarik Santri Baru dari PSB</h3>
                <p class="text-xs text-gray-500 mt-0.5">Pindahkan calon santri berstatus DITERIMA menjadi Santri Aktif.</p>
            </div>
            <button type="button" onclick="document.getElementById('modalBulkImportPsb').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <form action="{{ route('admin.siswa.bulkImportPsb') }}" method="POST">
            @csrf
            <div class="ta-modal-body space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tahun Masuk / Angkatan</label>
                    <input type="text" name="tahun_masuk" value="{{ date('Y') }}" required class="ta-input font-bold">
                    <span class="text-[11px] text-gray-400 mt-1 block">NIS santri akan digenerate urut secara otomatis (contoh: {{ date('Y') }}0001).</span>
                </div>

                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl space-y-1.5 text-emerald-950 text-[11px] leading-relaxed">
                    <div class="font-bold flex items-center gap-1.5 text-emerald-800">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Otomatisasi Akun Santri:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-emerald-900">
                        <li><strong>Kelas Awal:</strong> Santri MTs -> <strong>VII-A</strong>, Santri MA -> <strong>X-A</strong>.</li>
                        <li><strong>Password:</strong> Otomatis menggunakan <strong>tanggal lahir</strong> (format: DDMMYYYY).</li>
                    </ul>
                    <div class="pt-2 border-t border-emerald-200/60 mt-2">
                        <p class="text-emerald-800 font-medium">
                            💡 Setelah ditarik, Anda dapat mengatur pembagian kelas (VII-A, VII-B, X-A, dll) secara otomatis sesuai peringkat CBT di menu 
                            <a href="{{ route('admin.siswa.penempatanKelas') }}" class="font-bold underline text-emerald-900 hover:text-emerald-950">Penempatan Kelas Santri Baru</a>.
                        </p>
                    </div>
                </div>
            </div>

            <div class="ta-modal-footer">
                <button type="button" onclick="document.getElementById('modalBulkImportPsb').classList.add('hidden')" class="ta-btn-sm-outline">
                    Batal
                </button>
                <button type="submit" class="ta-btn-sm-primary">
                    Tarik Santri ke Data Aktif
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

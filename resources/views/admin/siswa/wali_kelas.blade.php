@extends('admin.layout')

@section('title', 'Atur & Kelola Wali Kelas — Pondok Pesantren Hidayatullah')

@section('content')
<div class="space-y-6" x-data="{
    modalEdit: false,
    modeBatch: false,
    editData: {
        id: '',
        nama_kelas: '',
        jenjang: '',
        tingkat: '',
        wali_kelas: '',
        kontak_wali: '',
        nip_wali: '',
        keterangan: ''
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            nama_kelas: item.nama_kelas,
            jenjang: item.jenjang,
            tingkat: item.tingkat,
            wali_kelas: item.wali_kelas || '',
            kontak_wali: item.kontak_wali || '',
            nip_wali: item.nip_wali || '',
            keterangan: item.keterangan || ''
        };
        this.modalEdit = true;
    },
    selectSuggestion(name) {
        this.editData.wali_kelas = name;
    }
}">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('admin.siswa.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Manajemen Santri</a>
                <span class="text-xs text-gray-300">/</span>
                <a href="{{ route('admin.siswa.kelas.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-700">Master Jenjang &amp; Kelas</a>
                <span class="text-xs text-gray-300">/</span>
                <span class="text-xs font-semibold text-gray-500">Atur Wali Kelas</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
                Pengaturan &amp; Penetapan Wali Kelas
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Tentukan dewan ustadz/ustadzah pembina setiap rombongan belajar, nomor kontak WhatsApp dinas, dan NIP/kode pengenal guru.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.siswa.kelas.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-xs font-semibold text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Master Rombel Kelas
            </a>
            <button type="button" @click="modeBatch = !modeBatch" :class="modeBatch ? 'bg-amber-600 text-white hover:bg-amber-700' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-semibold shadow-theme-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                <span x-text="modeBatch ? 'Keluar Mode Massal' : 'Mode Atur Cepat (Batch)'"></span>
            </button>
        </div>
    </div>

    <!-- Alert Notifikasi Flash -->
    @if(session('success'))
    <div class="rounded-xl border border-emerald-200 bg-emerald-50/90 p-4 text-xs sm:text-sm text-emerald-800 flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="rounded-xl border border-rose-200 bg-rose-50/90 p-4 text-xs sm:text-sm text-rose-800 flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Statistik Ringkas TailAdmin -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-gray-500">Total Rombel</span>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalKelas }} <span class="text-xs font-normal text-gray-500">Kelas</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-400 mt-2">Seluruh rombel MTs &amp; MA</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600">Sudah Berwali</span>
                    <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $totalBerwali }} <span class="text-xs font-normal text-gray-500">Kelas</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-emerald-600 mt-2 font-medium">Ustadz/ah pembina siap</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-600">Belum Ada Wali</span>
                    <h3 class="text-2xl font-bold {{ $totalBelumBerwali > 0 ? 'text-amber-600' : 'text-gray-900' }} mt-1">{{ $totalBelumBerwali }} <span class="text-xs font-normal text-gray-500">Kelas</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-amber-600 mt-2 font-medium">Memerlukan penugasan</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-purple-600">Santri Terbina</span>
                    <h3 class="text-2xl font-bold text-purple-600 mt-1">{{ $totalSantri }} <span class="text-xs font-normal text-gray-500">Santri</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-400 mt-2">Santri aktif seluruh kelas</p>
        </div>
    </div>

    <!-- Filter & Toolbar -->
    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-theme-xs">
        <form method="GET" action="{{ route('admin.siswa.waliKelas.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Tabs Filter Jenjang -->
            <div class="flex flex-wrap items-center gap-1.5 p-1 bg-gray-50 rounded-xl border border-gray-100">
                <a href="{{ route('admin.siswa.waliKelas.index', ['jenjang' => 'all', 'status' => $statusFilter, 'q' => $search]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ $jenjangFilter === 'all' ? 'bg-white text-emerald-700 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    Semua Jenjang
                </a>
                <a href="{{ route('admin.siswa.waliKelas.index', ['jenjang' => 'MTs', 'status' => $statusFilter, 'q' => $search]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ $jenjangFilter === 'MTs' ? 'bg-white text-blue-700 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    MTs
                </a>
                <a href="{{ route('admin.siswa.waliKelas.index', ['jenjang' => 'MA', 'status' => $statusFilter, 'q' => $search]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ $jenjangFilter === 'MA' ? 'bg-white text-purple-700 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    MA
                </a>
                <a href="{{ route('admin.siswa.waliKelas.index', ['jenjang' => 'Tahfidz', 'status' => $statusFilter, 'q' => $search]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ $jenjangFilter === 'Tahfidz' ? 'bg-white text-emerald-700 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    Tahfidz
                </a>
            </div>

            <!-- Search & Status Dropdown -->
            <div class="flex flex-wrap items-center gap-2.5">
                <input type="hidden" name="jenjang" value="{{ $jenjangFilter }}">

                <select name="status" onchange="this.form.submit()" class="h-9 rounded-xl border border-gray-300 bg-white px-3 text-xs text-gray-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="sudah" {{ $statusFilter === 'sudah' ? 'selected' : '' }}>Sudah Ada Wali</option>
                    <option value="belum" {{ $statusFilter === 'belum' ? 'selected' : '' }}>Belum Ada Wali</option>
                </select>

                <div class="relative min-w-[220px]">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari kelas, nama wali, NIP..." class="h-9 w-full rounded-xl border border-gray-300 bg-white pl-8 pr-3 text-xs text-gray-800 placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                    <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <button type="submit" class="h-9 px-3.5 rounded-xl bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition">
                    Cari
                </button>
                @if($search || $statusFilter !== 'all' || $jenjangFilter !== 'all')
                <a href="{{ route('admin.siswa.waliKelas.index') }}" class="h-9 px-3 rounded-xl border border-gray-300 bg-white text-gray-600 text-xs font-semibold hover:bg-gray-50 flex items-center transition" title="Reset Filter">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- MODE 1: FORM PENGATURAN MASSAL (BATCH) -->
    <div x-show="modeBatch" x-cloak class="rounded-2xl border border-amber-200 bg-amber-50/40 p-5 shadow-theme-xs">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-amber-200/80">
            <div>
                <h3 class="text-sm font-bold text-amber-900 flex items-center gap-2">
                    <span>⚡ Mode Atur Wali Kelas Sekaligus (Batch Edit)</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-200 text-amber-800 uppercase">Multi-Edit</span>
                </h3>
                <p class="text-xs text-amber-700 mt-0.5">Ubah nama wali kelas, nomor WhatsApp, dan NIP pada beberapa kelas sekaligus, lalu klik simpan.</p>
            </div>
            <button type="button" @click="modeBatch = false" class="text-xs font-semibold text-amber-800 hover:text-amber-950 underline">
                Tutup Mode Massal
            </button>
        </div>

        <form action="{{ route('admin.siswa.waliKelas.bulkUpdate') }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-amber-200 bg-amber-100/60 text-[11px] font-bold uppercase text-amber-900">
                            <th class="py-2.5 px-4">Kelas &amp; Jenjang</th>
                            <th class="py-2.5 px-4">Nama Wali Kelas (Gelar)</th>
                            <th class="py-2.5 px-4">Nomor WhatsApp Dinas</th>
                            <th class="py-2.5 px-4">NIP / Kode Guru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-200/60 bg-white">
                        @foreach($classrooms as $cls)
                        <tr>
                            <td class="py-2.5 px-4 font-bold text-gray-900 whitespace-nowrap">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] mr-1.5 {{ $cls->jenjang === 'MA' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $cls->jenjang }}
                                </span>
                                {{ $cls->nama_kelas }}
                            </td>
                            <td class="py-2 px-4">
                                <input type="text" name="assignments[{{ $cls->id }}][wali_kelas]" value="{{ $cls->wali_kelas }}" placeholder="Contoh: Ustadz Ahmad, S.Pd" class="h-8.5 w-full rounded-lg border border-gray-300 px-2.5 text-xs text-gray-800 focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                            </td>
                            <td class="py-2 px-4">
                                <input type="text" name="assignments[{{ $cls->id }}][kontak_wali]" value="{{ $cls->kontak_wali }}" placeholder="08123456789" class="h-8.5 w-full rounded-lg border border-gray-300 px-2.5 text-xs text-gray-800 focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                            </td>
                            <td class="py-2 px-4">
                                <input type="text" name="assignments[{{ $cls->id }}][nip_wali]" value="{{ $cls->nip_wali }}" placeholder="NIP/NUPTK" class="h-8.5 w-full rounded-lg border border-gray-300 px-2.5 text-xs text-gray-800 focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-3 border-t border-amber-200 flex items-center justify-between">
                <span class="text-xs text-amber-800">Menyimpan seluruh perubahan input di tabel atas sekaligus.</span>
                <div class="flex items-center gap-2">
                    <button type="button" @click="modeBatch = false" class="px-4 py-2 rounded-xl border border-gray-300 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 shadow-xs transition">
                        Simpan Semua Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- MODE 2: TABEL KELAS & WALI KELAS (DEFAULT) -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-base font-bold text-gray-900">Daftar Wali Kelas Rombongan Belajar</h2>
                <p class="text-xs text-gray-500 mt-0.5">Informasi wali kelas ini otomatis terhubung ke biodata santri dan Portal Santri Mandiri.</p>
            </div>
            <span class="text-xs font-semibold text-gray-400">
                Menampilkan {{ $classrooms->count() }} kelas
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/75 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <th class="py-3.5 px-5">Rombel Kelas</th>
                        <th class="py-3.5 px-5">Wali Kelas</th>
                        <th class="py-3.5 px-5">Kontak WhatsApp</th>
                        <th class="py-3.5 px-5 text-center">Santri Aktif</th>
                        <th class="py-3.5 px-5">Keterangan</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                    @forelse($classrooms as $cls)
                    <tr class="hover:bg-gray-50/80 transition">
                        <!-- Kelas -->
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs {{ $cls->jenjang === 'MA' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                    {{ substr($cls->nama_kelas, 0, 3) }}
                                </span>
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-gray-900 text-sm">{{ $cls->nama_kelas }}</span>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold {{ $cls->jenjang === 'MA' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                            {{ $cls->jenjang }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-gray-400 font-medium">Tingkat {{ $cls->tingkat }} &bull; Kuota: {{ $cls->kapasitas }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Wali Kelas -->
                        <td class="py-4 px-5">
                            @if(!empty($cls->wali_kelas))
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr(preg_replace('/^(ustadz|ustadzah|ust\.|ustzh\.)\s*/i', '', $cls->wali_kelas), 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 block text-xs sm:text-sm">{{ $cls->wali_kelas }}</span>
                                        @if($cls->nip_wali)
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[10px] font-mono text-gray-500 bg-gray-100">
                                                NIP: {{ $cls->nip_wali }}
                                            </span>
                                        @else
                                            <span class="text-[11px] text-emerald-600 font-medium">Wali Kelas Aktif</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span>Belum Ditentukan</span>
                                </div>
                            @endif
                        </td>

                        <!-- Kontak WhatsApp -->
                        <td class="py-4 px-5">
                            @if($cls->whatsapp_url)
                                <div class="flex items-center gap-2">
                                    <a href="{{ $cls->whatsapp_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-600 text-white text-[11px] font-semibold hover:bg-emerald-700 shadow-xs transition" title="Kirim Pesan WhatsApp">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.585 1.961.905 2.796.905 3.184 0 5.769-2.587 5.77-5.766.001-3.18-2.584-5.766-5.77-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.802 0-3.486-.481-4.947-1.319l-7.053 1.847 1.879-6.864c-.934-1.524-1.479-3.32-1.479-5.264 0-5.514 4.486-10 10-10s10 4.486 10 10z"/></svg>
                                        <span>Chat WA</span>
                                    </a>
                                    <span class="font-mono text-xs text-gray-700 font-semibold">{{ $cls->kontak_wali }}</span>
                                </div>
                            @elseif($cls->kontak_wali)
                                <span class="font-mono text-xs text-gray-700">{{ $cls->kontak_wali }}</span>
                            @else
                                <span class="text-gray-400 italic text-[11px]">—</span>
                            @endif
                        </td>

                        <!-- Santri Aktif -->
                        <td class="py-4 px-5 text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $cls->students_count > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-500' }}">
                                {{ $cls->students_count }} Santri
                            </span>
                        </td>

                        <!-- Keterangan -->
                        <td class="py-4 px-5">
                            <span class="text-gray-500 text-xs line-clamp-1">{{ $cls->keterangan ?: '—' }}</span>
                        </td>

                        <!-- Aksi -->
                        <td class="py-4 px-5 text-right">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                <button type="button" 
                                    @click="openEdit({
                                        id: '{{ $cls->id }}',
                                        nama_kelas: '{{ addslashes($cls->nama_kelas) }}',
                                        jenjang: '{{ addslashes($cls->jenjang) }}',
                                        tingkat: '{{ addslashes($cls->tingkat) }}',
                                        wali_kelas: '{{ addslashes($cls->wali_kelas) }}',
                                        kontak_wali: '{{ addslashes($cls->kontak_wali) }}',
                                        nip_wali: '{{ addslashes($cls->nip_wali) }}',
                                        keterangan: '{{ addslashes($cls->keterangan) }}'
                                    })"
                                    class="inline-flex items-center gap-1 text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 text-xs font-semibold px-2.5 py-1.5 rounded-xl border border-emerald-200 shadow-xs transition" 
                                    title="Atur / Ganti Wali Kelas">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Atur Wali
                                </button>

                                @if(!empty($cls->wali_kelas))
                                <form action="{{ route('admin.siswa.waliKelas.update', $cls->id) }}" method="POST" onsubmit="return confirm('Kosongkan wali kelas untuk {{ $cls->nama_kelas }}?');" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="wali_kelas" value="">
                                    <input type="hidden" name="kontak_wali" value="">
                                    <input type="hidden" name="nip_wali" value="">
                                    <button type="submit" class="inline-flex items-center gap-1 text-gray-500 hover:text-rose-700 hover:bg-rose-50 text-xs font-semibold px-2 py-1.5 rounded-lg transition" title="Kosongkan Wali Kelas">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400">
                            Tidak ada rombel kelas yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL POPUP: ATUR / GANTI WALI KELAS -->
    <div x-show="modalEdit" x-cloak class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div @click.away="modalEdit = false" class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">
                            Atur Wali Kelas: <span class="text-emerald-700" x-text="editData.nama_kelas"></span>
                        </h3>
                        <p class="text-xs text-gray-500">
                            Jenjang <span x-text="editData.jenjang"></span> &bull; Tingkat <span x-text="editData.tingkat"></span>
                        </p>
                    </div>
                </div>
                <button @click="modalEdit = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            </div>

            <form :action="'{{ url('/admin/siswa-wali-kelas') }}/' + editData.id" method="POST" class="mt-5 space-y-4">
                @csrf
                @method('PUT')

                <!-- Saran Ustadz Cepat (Quick Suggestions) -->
                @if(!empty($guruSuggestions))
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                        ⚡ Pilih Cepat dari Saran Dewan Guru:
                    </label>
                    <div class="flex flex-wrap gap-1.5 max-h-24 overflow-y-auto p-2 bg-gray-50 rounded-xl border border-gray-200">
                        @foreach($guruSuggestions as $sug)
                        <button type="button" @click="selectSuggestion('{{ addslashes($sug) }}')" class="px-2 py-1 rounded-lg bg-white hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 border border-gray-200 text-[11px] font-medium text-gray-700 transition">
                            {{ $sug }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Input Nama Wali Kelas -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap Wali Kelas &amp; Gelar <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="wali_kelas" x-model="editData.wali_kelas" placeholder="Contoh: Ustadz Ahmad Fauzi, S.Pd.I / Ustadzah Siti Aminah, S.Pd" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                    <p class="text-[11px] text-gray-400 mt-1">Sertakan sebutan (Ustadz/Ustadzah) dan gelar akademik untuk kerapian dokumen.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nomor WhatsApp Dinas -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Nomor WhatsApp / HP
                        </label>
                        <input type="text" name="kontak_wali" x-model="editData.kontak_wali" placeholder="Contoh: 081234567890" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 font-mono focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                        <p class="text-[10px] text-gray-400 mt-1">Akan dibuatkan tombol Chat WA langsung.</p>
                    </div>

                    <!-- NIP / Kode Guru -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            NIP / NUPTK / Kode Guru
                        </label>
                        <input type="text" name="nip_wali" x-model="editData.nip_wali" placeholder="Contoh: 198501102010..." class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 font-mono focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                        <p class="text-[10px] text-gray-400 mt-1">Nomor identitas kepegawaian (opsional).</p>
                    </div>
                </div>

                <!-- Catatan / Keterangan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Keterangan Tambahan / Catatan Khusus
                    </label>
                    <input type="text" name="keterangan" x-model="editData.keterangan" placeholder="Contoh: Bertugas membina tilawah dan ketertiban santri" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                </div>

                <!-- Tombol Aksi Modal -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalEdit = false" class="px-4 py-2 rounded-xl border border-gray-300 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 text-xs font-semibold text-white hover:bg-emerald-700 transition shadow-xs">
                        Simpan Wali Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

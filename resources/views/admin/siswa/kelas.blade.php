@extends('admin.layout')

@section('title', 'Master Jenjang & Kelas — Pondok Pesantren Hidayatullah')

@section('content')
<div class="space-y-6" x-data="{ modalTambah: false, modalEdit: false, editData: { id: '', jenjang: 'MTs', tingkat: '', nama_kelas: '', wali_kelas: '', kontak_wali: '', nip_wali: '', kapasitas: 30, keterangan: '' } }">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('admin.siswa.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Manajemen Santri</a>
                <span class="text-xs text-gray-300">/</span>
                <span class="text-xs font-semibold text-gray-500">Master Data Jenjang &amp; Kelas</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Kelola Jenjang &amp; Rombel Kelas</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Atur nama kelas, tingkatan kelas, wali kelas, serta kapasitas santri untuk jenjang MTs, MA, maupun Tahfidz.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.siswa.waliKelas.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs font-semibold text-emerald-700 shadow-theme-xs hover:bg-emerald-100 transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Atur Wali Kelas
            </a>
            <a href="{{ route('admin.siswa.penempatanKelas') }}" class="inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-xs font-semibold text-blue-700 shadow-theme-xs hover:bg-blue-100 transition">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Bagi / Penempatan Santri Baru
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-xs font-semibold text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Data Santri
            </a>
            <button @click="modalTambah = true" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-emerald-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                + Tambah Kelas Baru
            </button>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
    <div class="rounded-xl border border-emerald-200 bg-emerald-50/90 p-4 text-xs sm:text-sm text-emerald-800 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="rounded-xl border border-rose-200 bg-rose-50/90 p-4 text-xs sm:text-sm text-rose-800 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Statistik Ringkas (TailAdmin Metric Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Rombel Kelas</span>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalKelas }} <span class="text-xs font-normal text-gray-500">Kelas</span></h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Tersedia untuk MTs, MA, &amp; Tahfidz</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Santri Aktif Terdaftar</span>
                    <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $totalSantri }} <span class="text-xs font-normal text-gray-500">Santri</span></h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <p class="text-xs text-emerald-600 mt-3 font-medium">Menempati seluruh rombel kelas aktif</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Daya Tampung</span>
                    <h3 class="text-2xl font-bold text-purple-600 mt-1">{{ $totalKapasitas }} <span class="text-xs font-normal text-gray-500">Kursi</span></h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Kapasitas maksimal ruang kelas KBM</p>
        </div>
    </div>

    <!-- Tabel Daftar Kelas (TailAdmin Table Card) -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-gray-900">Daftar Rombel Kelas &amp; Wali Kelas</h2>
                <p class="text-xs text-gray-500 mt-0.5">Seluruh kelas yang aktif akan otomatis muncul pada dropdown pilihan pendaftaran, mutasi, dan kenaikan kelas.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/75 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <th class="py-3.5 px-5">Nama Kelas</th>
                        <th class="py-3.5 px-5">Jenjang &amp; Tingkat</th>
                        <th class="py-3.5 px-5">Wali Kelas</th>
                        <th class="py-3.5 px-5 text-center">Santri Aktif</th>
                        <th class="py-3.5 px-5 text-center">Kapasitas</th>
                        <th class="py-3.5 px-5">Keterangan</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                    @forelse($classrooms as $cls)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-3.5 px-5">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs {{ $cls->jenjang === 'MA' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ substr($cls->nama_kelas, 0, 3) }}
                                </span>
                                <div>
                                    <span class="font-bold text-gray-900">{{ $cls->nama_kelas }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-5">
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold {{ $cls->jenjang === 'MA' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                    {{ $cls->jenjang }}
                                </span>
                                <span class="text-xs text-gray-500 font-medium">Tingkat {{ $cls->tingkat }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-5">
                            @if($cls->wali_kelas)
                                <div>
                                    <span class="font-medium text-gray-800 block">{{ $cls->wali_kelas }}</span>
                                    @if($cls->kontak_wali)
                                        <span class="text-[11px] text-emerald-600 font-mono">WA: {{ $cls->kontak_wali }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 italic">Belum ditentukan</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $cls->students_count > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $cls->students_count }} Santri
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-center">
                            <span class="text-xs text-gray-600 font-medium">{{ $cls->kapasitas }} Kursi</span>
                        </td>
                        <td class="py-3.5 px-5">
                            <span class="text-gray-500 text-xs">{{ $cls->keterangan ?: '—' }}</span>
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                <button type="button" 
                                    @click="editData = { 
                                        id: '{{ $cls->id }}', 
                                        jenjang: '{{ addslashes($cls->jenjang) }}', 
                                        tingkat: '{{ addslashes($cls->tingkat) }}', 
                                        nama_kelas: '{{ addslashes($cls->nama_kelas) }}', 
                                        wali_kelas: '{{ addslashes($cls->wali_kelas) }}', 
                                        kontak_wali: '{{ addslashes($cls->kontak_wali) }}', 
                                        nip_wali: '{{ addslashes($cls->nip_wali) }}', 
                                        kapasitas: {{ $cls->kapasitas ?: 30 }}, 
                                        keterangan: '{{ addslashes($cls->keterangan) }}' 
                                    }; modalEdit = true;" 
                                    class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 text-xs font-semibold px-2.5 py-1 rounded-lg transition" 
                                    title="Ubah / Edit Data Kelas">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>

                                @if($cls->students_count > 0)
                                    <button type="button" disabled title="Kelas tidak dapat dihapus karena masih ada {{ $cls->students_count }} santri aktif" class="inline-flex items-center gap-1 text-gray-300 cursor-not-allowed text-xs font-semibold px-2 py-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Terkunci
                                    </button>
                                @else
                                    <form action="{{ route('admin.siswa.kelas.destroy', $cls->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas {{ $cls->nama_kelas }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 text-rose-600 hover:text-rose-800 hover:bg-rose-50 text-xs font-semibold px-2.5 py-1 rounded-lg transition" title="Hapus Kelas">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">
                            Belum ada rombel kelas yang terdaftar. Klik "+ Tambah Kelas Baru" di atas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH KELAS BARU -->
    <div x-show="modalTambah" x-cloak class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div @click.away="modalTambah = false" class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        +
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Tambah Rombel Kelas Baru</h3>
                        <p class="text-xs text-gray-500">Daftarkan tingkatan dan nama kelas untuk kegiatan belajar mengajar.</p>
                    </div>
                </div>
                <button @click="modalTambah = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <form action="{{ route('admin.siswa.kelas.store') }}" method="POST" class="mt-5 space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Jenjang Pendidikan <span class="text-rose-500">*</span></label>
                        <select name="jenjang" required class="h-10 w-full rounded-xl border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                            <option value="MTs">MTs (Madrasah Tsanawiyah)</option>
                            <option value="MA">MA (Madrasah Aliyah)</option>
                            <option value="Tahfidz">Tahfidz / Takhassus</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tingkat Kelas <span class="text-rose-500">*</span></label>
                        <input type="text" name="tingkat" required placeholder="Contoh: VII, VIII, IX, X, XI, XII" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Rombel Kelas <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_kelas" required placeholder="Contoh: VII-C, X-IPA, XII-Agama" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                    <p class="text-[11px] text-gray-400 mt-1">Nama ini akan menjadi pengenal unik kelas.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Wali Kelas</label>
                        <input type="text" name="wali_kelas" placeholder="Contoh: Ustadz Ahmad, S.Pd" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Daya Tampung / Kuota</label>
                        <input type="number" name="kapasitas" value="30" min="1" max="100" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">No. WhatsApp Wali</label>
                        <input type="text" name="kontak_wali" placeholder="Contoh: 081234567890" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 font-mono focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">NIP / Kode Guru</label>
                        <input type="text" name="nip_wali" placeholder="Opsional" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 font-mono focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Keterangan Tambahan</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Gedung Timur Lantai 2, Khusus Putra" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalTambah = false" class="px-4 py-2 rounded-xl border border-gray-300 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                        Simpan Rombel Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT / UBAH KELAS -->
    <div x-show="modalEdit" x-cloak class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div @click.away="modalEdit = false" class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Ubah Data Rombel Kelas</h3>
                        <p class="text-xs text-gray-500">Perbarui informasi jenjang, wali kelas, atau kapasitas kelas.</p>
                    </div>
                </div>
                <button @click="modalEdit = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <form :action="'{{ url('/admin/siswa-kelas') }}/' + editData.id" method="POST" class="mt-5 space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Jenjang Pendidikan <span class="text-rose-500">*</span></label>
                        <select name="jenjang" x-model="editData.jenjang" required class="h-10 w-full rounded-xl border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                            <option value="MTs">MTs (Madrasah Tsanawiyah)</option>
                            <option value="MA">MA (Madrasah Aliyah)</option>
                            <option value="Tahfidz">Tahfidz / Takhassus</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tingkat Kelas <span class="text-rose-500">*</span></label>
                        <input type="text" name="tingkat" x-model="editData.tingkat" required placeholder="Contoh: VII, VIII, IX, X, XI, XII" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Rombel Kelas <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_kelas" x-model="editData.nama_kelas" required placeholder="Contoh: VII-C, X-IPA" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                    <p class="text-[11px] text-gray-400 mt-1">Jika nama diubah, data santri di kelas ini otomatis ikut disesuaikan.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Wali Kelas</label>
                        <input type="text" name="wali_kelas" x-model="editData.wali_kelas" placeholder="Contoh: Ustadz Ahmad, S.Pd" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Daya Tampung / Kuota</label>
                        <input type="number" name="kapasitas" x-model="editData.kapasitas" min="1" max="100" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">No. WhatsApp Wali</label>
                        <input type="text" name="kontak_wali" x-model="editData.kontak_wali" placeholder="Contoh: 081234567890" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">NIP / Kode Guru</label>
                        <input type="text" name="nip_wali" x-model="editData.nip_wali" placeholder="Opsional" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Keterangan Tambahan</label>
                    <input type="text" name="keterangan" x-model="editData.keterangan" placeholder="Contoh: Gedung Timur Lantai 2, Khusus Putra" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalEdit = false" class="px-4 py-2 rounded-xl border border-gray-300 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-xs font-semibold text-white hover:bg-blue-700 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

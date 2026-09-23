@extends('admin.layout')

@section('title', 'Mutasi Santri — Pondok Pesantren Hidayatullah')

@section('content')
<div class="space-y-6">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.siswa.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">&larr; Kembali ke Data Santri</a>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">Administrasi &amp; Kesiswaan</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Mutasi Santri</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Kelola riwayat mutasi santri keluar (pindah sekolah / drop out / wafat) dan mutasi masuk (pindahan). Terhubung langsung dengan data santri, kelas, asrama, dan buku kas.
            </p>
        </div>
        <div>
            <button type="button" id="btnOpenModal" onclick="openMutasiModal()"
                class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-theme-xs hover:bg-emerald-700 transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>Catat Mutasi Baru</span>
            </button>
        </div>
    </div>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
    <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/80 text-emerald-900 flex items-center gap-3 text-xs sm:text-sm font-semibold shadow-theme-xs">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 rounded-xl border border-rose-200 bg-rose-50/80 text-rose-900 flex items-center gap-3 text-xs sm:text-sm font-semibold shadow-theme-xs">
        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Total Mutasi Keluar --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-theme-xs p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-black text-gray-900 font-mono">{{ $stats['total_keluar'] }}</div>
                <div class="text-xs text-gray-500 font-semibold mt-0.5">Total Mutasi Keluar (Pindah/DO)</div>
            </div>
        </div>
        {{-- Total Mutasi Masuk --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-theme-xs p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-black text-gray-900 font-mono">{{ $stats['total_masuk'] }}</div>
                <div class="text-xs text-gray-500 font-semibold mt-0.5">Total Mutasi Masuk (Pindahan)</div>
            </div>
        </div>
        {{-- Bulan Ini --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-theme-xs p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-black text-gray-900 font-mono">{{ $stats['bulan_ini'] }}</div>
                <div class="text-xs text-gray-500 font-semibold mt-0.5">Mutasi Bulan Ini ({{ date('F Y') }})</div>
            </div>
        </div>
    </div>

    {{-- ===== FILTER BAR LENGKAP (TERHUBUNG KE TABEL SANTRI & KELAS) ===== --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-theme-xs p-4 sm:p-5">
        <form method="GET" action="{{ route('admin.siswa.mutasi.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3 items-end">
            {{-- Cari --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Cari Santri / Alasan / Sekolah</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama santri, NIS, no stambuk, alasan..."
                        class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>

            {{-- Jenis Mutasi --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Jenis Mutasi</label>
                <select name="jenis" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Semua Jenis</option>
                    <option value="Keluar" {{ request('jenis') === 'Keluar' ? 'selected' : '' }}>Mutasi Keluar</option>
                    <option value="Masuk"  {{ request('jenis') === 'Masuk'  ? 'selected' : '' }}>Mutasi Masuk</option>
                </select>
            </div>

            {{-- Angkatan Santri --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Angkatan</label>
                <select name="angkatan" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Semua Angkatan</option>
                    @foreach($angkatanList as $akt)
                        <option value="{{ $akt }}" {{ request('angkatan') == $akt ? 'selected' : '' }}>Angkatan {{ $akt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Jenjang --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Jenjang</label>
                <select name="jenjang" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Semua Jenjang</option>
                    @foreach($jenjangList as $jen)
                        <option value="{{ $jen }}" {{ request('jenjang') === $jen ? 'selected' : '' }}>{{ $jen }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Kelas --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Kelas</label>
                <select name="kelas" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Semua Kelas</option>
                    @foreach($allClasses as $kls)
                        <option value="{{ $kls }}" {{ request('kelas') === $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tahun & Action Buttons --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tahun Mutasi</label>
                <div class="flex items-center gap-1.5">
                    <select name="tahun" class="flex-1 px-2.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Semua</option>
                        @foreach($tahunList as $thn)
                            <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-3.5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                        Filter
                    </button>
                    @if(request()->anyFilled(['q','jenis','jenjang','kelas','tahun','angkatan']))
                        <a href="{{ route('admin.siswa.mutasi.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- ===== TABEL RIWAYAT MUTASI LENGKAP ===== --}}
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-5 sm:p-6 border-b border-gray-100">
            <div>
                <h2 class="text-base font-bold text-gray-900">Riwayat Mutasi Santri</h2>
                <p class="text-xs text-gray-500 mt-0.5">Daftar rekaman mutasi kesiswaan resmi Pondok Pesantren Hidayatullah.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="rounded-full bg-slate-100 text-slate-700 text-xs px-3 py-1 font-bold border border-slate-200">
                    {{ $mutations->total() }} Catatan Mutasi
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/80 border-b border-gray-200 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="py-3.5 px-4 w-10 text-center">#</th>
                        <th class="py-3.5 px-4 min-w-[200px]">Identitas Santri</th>
                        <th class="py-3.5 px-3 text-center">Jenis</th>
                        <th class="py-3.5 px-3 whitespace-nowrap">Tanggal Mutasi</th>
                        <th class="py-3.5 px-4">Kelas &amp; Asrama Asal</th>
                        <th class="py-3.5 px-4">Alasan / Kelas Tujuan</th>
                        <th class="py-3.5 px-4">Sekolah Asal / Tujuan</th>
                        <th class="py-3.5 px-3 text-center">Status Tagihan</th>
                        <th class="py-3.5 px-3">Dicatat Oleh</th>
                        <th class="py-3.5 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                    @forelse($mutations as $i => $mut)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="py-3 px-4 text-center font-bold text-gray-400">
                            {{ $mutations->firstItem() + $i }}
                        </td>

                        {{-- Identitas Santri --}}
                        <td class="py-3 px-4">
                            @if($mut->student)
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ substr($mut->student->nama_lengkap, 0, 2) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.siswa.show', $mut->student->id) }}"
                                           class="font-bold text-gray-900 hover:text-emerald-700 transition block text-sm leading-tight"
                                           title="Lihat Detail Profil Santri">
                                            {{ $mut->student->nama_lengkap }}
                                        </a>
                                        <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                            <span class="text-[11px] font-mono font-semibold text-gray-500">NIS: {{ $mut->student->nis ?: ($mut->nis ?: '-') }}</span>
                                            @if($mut->student->jenjang || $mut->jenjang)
                                                <span class="text-gray-300">•</span>
                                                <span class="px-1.5 py-0.2 rounded text-[10px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                                    {{ $mut->student->jenjang ?: $mut->jenjang }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div>
                                    <span class="font-bold text-gray-800">{{ $mut->nama_santri ?: 'Santri Terhapus' }}</span>
                                    <div class="text-[11px] text-gray-400 font-mono">NIS: {{ $mut->nis ?: '-' }} (Data telah dihapus)</div>
                                </div>
                            @endif
                        </td>

                        {{-- Jenis Mutasi --}}
                        <td class="py-3 px-3 text-center whitespace-nowrap">
                            @if($mut->jenis_mutasi === 'Keluar')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
                                    <span>Mutasi Keluar</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8l-4 4m0 0l4 4m-4-4h14"/></svg>
                                    <span>Mutasi Masuk</span>
                                </span>
                            @endif
                        </td>

                        {{-- Tanggal --}}
                        <td class="py-3 px-3 font-semibold text-gray-700 whitespace-nowrap">
                            {{ optional($mut->tanggal_mutasi)->format('d M Y') }}
                        </td>

                        {{-- Kelas & Asrama Asal --}}
                        <td class="py-3 px-4">
                            <div class="font-semibold text-gray-900">
                                {{ $mut->kelas_dari ? 'Kelas ' . $mut->kelas_dari : '-' }}
                            </div>
                            @if($mut->kamar_dari)
                                <div class="text-[11px] text-indigo-700 font-medium flex items-center gap-1 mt-0.5">
                                    <span>🛏</span> <span>{{ $mut->kamar_dari }}</span>
                                </div>
                            @endif
                        </td>

                        {{-- Alasan / Kelas Tujuan --}}
                        <td class="py-3 px-4">
                            @if($mut->jenis_mutasi === 'Keluar')
                                <div class="font-bold text-gray-800">
                                    {{ $mut->alasan ?: 'Pindah Sekolah' }}
                                </div>
                            @else
                                <div class="font-bold text-emerald-700 flex items-center gap-1">
                                    <span>➔</span> <span>{{ $mut->kelas_ke ? 'Ke Kelas ' . $mut->kelas_ke : '-' }}</span>
                                </div>
                                @if($mut->kamar_ke)
                                    <div class="text-[11px] text-indigo-700 font-medium flex items-center gap-1 mt-0.5">
                                        <span>🛏</span> <span>{{ $mut->kamar_ke }}</span>
                                    </div>
                                @endif
                            @endif
                        </td>

                        {{-- Sekolah Asal / Tujuan --}}
                        <td class="py-3 px-4 max-w-[200px]">
                            <div class="text-gray-800 font-medium truncate" title="{{ $mut->sekolah_asal_tujuan }}">
                                {{ $mut->sekolah_asal_tujuan ?: '-' }}
                            </div>
                        </td>

                        {{-- Status Keuangan Santri --}}
                        <td class="py-3 px-3 text-center whitespace-nowrap">
                            @if($mut->student)
                                @if($mut->student->total_tunggakan > 0)
                                    <a href="{{ route('admin.pembayaran.index', ['student_id' => $mut->student_id]) }}"
                                       class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100 transition"
                                       title="Ada sisa tunggakan kasir, klik untuk kelola pembayaran">
                                        <span>⚠ Rp {{ number_format($mut->student->total_tunggakan, 0, ',', '.') }}</span>
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span>✓ Lunas</span>
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400 text-[11px]">-</span>
                            @endif
                        </td>

                        {{-- Dicatat Oleh --}}
                        <td class="py-3 px-3 whitespace-nowrap">
                            <div class="text-gray-700 font-semibold text-[11px]">
                                {{ $mut->user->name ?? $mut->dicatat_oleh ?? 'Admin' }}
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Lihat Keterangan --}}
                                @if($mut->keterangan)
                                <button type="button"
                                    onclick="showKeterangan(`{{ addslashes($mut->keterangan) }}`, `{{ addslashes($mut->nama_santri_display) }}`)"
                                    class="p-1.5 rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600 transition cursor-pointer"
                                    title="Lihat Catatan Keterangan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                @endif

                                {{-- Buka Profil Santri --}}
                                @if($mut->student)
                                <a href="{{ route('admin.siswa.show', $mut->student_id) }}"
                                   class="p-1.5 rounded-lg border border-gray-200 hover:bg-emerald-50 hover:text-emerald-700 text-gray-600 transition"
                                   title="Buka Biodata &amp; Riwayat Santri">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </a>
                                @endif

                                {{-- Hapus / Batalkan Mutasi --}}
                                <form action="{{ route('admin.siswa.mutasi.destroy', $mut->id) }}" method="POST"
                                      onsubmit="return confirm('Batalkan catatan mutasi santri ini? Status keaktifan santri dan kamar asrama asalnya akan dipulihkan kembali ke semula.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg border border-rose-200 hover:bg-rose-50 text-rose-600 transition cursor-pointer" title="Batalkan / Hapus Mutasi">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-16 text-gray-400">
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3 text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-gray-700">Belum Ada Riwayat Mutasi Santri</p>
                            <p class="text-xs text-gray-400 mt-1">Gunakan tombol <strong>Catat Mutasi Baru</strong> untuk mencatat perpindahan atau keluarnya santri.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($mutations->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $mutations->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ===== MODAL CATAT MUTASI BARU ===== --}}
<div id="mutasiModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs transition-opacity hidden" onclick="closeMutasiModalOutside(event)">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-gray-100 relative max-h-[92vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Catat Mutasi Santri</h3>
                    <p class="text-xs text-gray-500">Mutasi keluar atau masuk terintegrasi langsung dengan data santri &amp; asrama</p>
                </div>
            </div>
            <button onclick="closeMutasiModal()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.siswa.mutasi.store') }}" id="formMutasi" class="space-y-4">
            @csrf

            {{-- Validasi Error --}}
            @if($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800 space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-rose-900">
                    <span>⚠</span> Terjadi Kesalahan:
                </div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- 1. PILIH SANTRI (AUTOCOMPLETE) --}}
            <div x-data="{
                search: '',
                results: [],
                loading: false,
                selected: null
            }" @click.away="results = []">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Cari Santri <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="text"
                        x-model="search"
                        @input.debounce.300ms="
                            if(search.length >= 2) {
                                loading = true;
                                fetch(`{{ route('admin.siswa.mutasi.search') }}?q=` + encodeURIComponent(search))
                                    .then(r => r.json())
                                    .then(d => { results = d; loading = false; })
                                    .catch(() => { loading = false; })
                            } else { results = []; }
                        "
                        placeholder="Ketik Nama, NIS, atau No. Stambuk santri..."
                        class="w-full pl-3 pr-9 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none"
                        autocomplete="off">

                    <input type="hidden" name="student_id" id="selectedStudentId" value="{{ old('student_id') }}">

                    <span x-show="loading" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                    </span>

                    {{-- Dropdown Hasil Pencarian --}}
                    <div x-show="results.length > 0" x-cloak
                         class="absolute z-50 w-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden max-h-60 overflow-y-auto">
                        <template x-for="s in results" :key="s.id">
                            <button type="button"
                                @click="
                                    selected = s;
                                    search = s.nama_lengkap + ' (NIS: ' + s.nis + ')';
                                    document.getElementById('selectedStudentId').value = s.id;
                                    document.getElementById('cardStudentPreview').classList.remove('hidden');
                                    document.getElementById('pvNama').textContent = s.nama_lengkap;
                                    document.getElementById('pvNis').textContent = 'NIS: ' + (s.nis || '-');
                                    document.getElementById('pvKelas').textContent = s.kelas || '-';
                                    document.getElementById('pvJenjang').textContent = s.jenjang || '-';
                                    document.getElementById('pvKamar').textContent = s.kamar_asrama || 'Tidak ada kamar';
                                    document.getElementById('pvStatus').textContent = s.status || 'Aktif';

                                    if(s.total_tunggakan > 0) {
                                        document.getElementById('pvTunggakanBox').classList.remove('hidden');
                                        document.getElementById('pvTunggakanVal').textContent = 'Rp ' + Number(s.total_tunggakan).toLocaleString('id-ID');
                                    } else {
                                        document.getElementById('pvTunggakanBox').classList.add('hidden');
                                    }

                                    results = [];
                                "
                                class="w-full flex items-start gap-3 px-4 py-2.5 hover:bg-gray-50 text-left border-b border-gray-100 last:border-0 cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                    <span x-text="s.nama_lengkap.charAt(0)"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-bold text-gray-900 truncate" x-text="s.nama_lengkap"></div>
                                    <div class="text-[11px] text-gray-500 font-medium mt-0.5">
                                        <span class="font-mono" x-text="'NIS: ' + (s.nis || '-')"></span> •
                                        <span x-text="s.jenjang || '-'"></span> •
                                        <span x-text="'Kelas ' + (s.kelas || '-')"></span> •
                                        <span class="font-semibold text-emerald-700" x-text="s.status"></span>
                                    </div>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Preview Kartu Santri Terpilih --}}
                <div id="cardStudentPreview" class="hidden mt-2 p-3 bg-gradient-to-r from-emerald-50/50 to-teal-50/30 border border-emerald-200 rounded-xl text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <strong id="pvNama" class="text-gray-900 text-sm"></strong>
                            <span id="pvNis" class="text-gray-500 font-mono text-[11px]"></span>
                        </div>
                        <span id="pvStatus" class="px-2 py-0.5 rounded text-[10px] font-bold bg-white text-emerald-800 border border-emerald-200"></span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-[11px] pt-1 border-t border-emerald-100">
                        <div>
                            <span class="text-gray-400 block">Jenjang:</span>
                            <strong id="pvJenjang" class="text-gray-700"></strong>
                        </div>
                        <div>
                            <span class="text-gray-400 block">Kelas Asal:</span>
                            <strong id="pvKelas" class="text-gray-700"></strong>
                        </div>
                        <div>
                            <span class="text-gray-400 block">Kamar Asrama:</span>
                            <strong id="pvKamar" class="text-indigo-700"></strong>
                        </div>
                    </div>
                    <div id="pvTunggakanBox" class="hidden p-2 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-[11px] flex items-center justify-between">
                        <span>⚠ Santri memiliki sisa tunggakan kasir:</span>
                        <strong id="pvTunggakanVal" class="font-mono text-amber-800"></strong>
                    </div>
                </div>
            </div>

            {{-- 2. JENIS MUTASI --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Jenis Mutasi <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center gap-3 p-3.5 border-2 rounded-xl cursor-pointer transition hover:bg-rose-50/50 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50">
                        <input type="radio" name="jenis_mutasi" value="Keluar" class="accent-rose-600"
                            onchange="toggleJenisMutasi('Keluar')"
                            {{ old('jenis_mutasi', 'Keluar') === 'Keluar' ? 'checked' : '' }}>
                        <div>
                            <div class="text-xs sm:text-sm font-bold text-gray-900">Mutasi Keluar</div>
                            <div class="text-[11px] text-gray-500">Pindah sekolah / DO / Cuti</div>
                        </div>
                    </label>
                    <label class="relative flex items-center gap-3 p-3.5 border-2 rounded-xl cursor-pointer transition hover:bg-emerald-50/50 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                        <input type="radio" name="jenis_mutasi" value="Masuk" class="accent-emerald-600"
                            onchange="toggleJenisMutasi('Masuk')"
                            {{ old('jenis_mutasi') === 'Masuk' ? 'checked' : '' }}>
                        <div>
                            <div class="text-xs sm:text-sm font-bold text-gray-900">Mutasi Masuk</div>
                            <div class="text-[11px] text-gray-500">Santri pindahan dari luar</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- 3. TANGGAL MUTASI --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Tanggal Mutasi <span class="text-rose-500">*</span>
                </label>
                <input type="date" name="tanggal_mutasi" value="{{ old('tanggal_mutasi', date('Y-m-d')) }}"
                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none" required>
            </div>

            {{-- 4. FIELD KHUSUS KELUAR: ALASAN --}}
            <div id="sectionKeluar">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Alasan Mutasi Keluar <span class="text-rose-500">*</span>
                </label>
                <select name="alasan" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">-- Pilih Alasan --</option>
                    @foreach($alasanOptions as $val => $label)
                        <option value="{{ $val }}" {{ old('alasan') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-rose-600 font-medium mt-1">
                    * Status santri akan diset menjadi "Mutasi" dan tempat tidur/kamar asrama akan dikosongkan secara otomatis.
                </p>
            </div>

            {{-- 5. FIELD KHUSUS MASUK: KELAS & KAMAR TUJUAN --}}
            <div id="sectionMasuk" class="hidden space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Kelas yang Dituju <span class="text-rose-500">*</span>
                        </label>
                        <select name="kelas_ke" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($allClasses as $kls)
                                <option value="{{ $kls }}" {{ old('kelas_ke') === $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Kamar Asrama Baru (Opsional)
                        </label>
                        <select name="kamar_ke" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="">-- Tidak / Belum Masuk Asrama --</option>
                            @foreach($allDormitories as $dorm)
                                <option value="{{ $dorm->nama_asrama }} - {{ $dorm->kamar }}" {{ old('kamar_ke') === ($dorm->nama_asrama . ' - ' . $dorm->kamar) ? 'selected' : '' }}>
                                    {{ $dorm->nama_asrama }} — {{ $dorm->kamar }} (Sisa: {{ $dorm->sisa_kapasitas }}/{{ $dorm->kapasitas }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="text-[10px] text-emerald-700 font-medium">
                    * Status santri akan diaktifkan kembali ("Aktif"), kelas dan kamar asrama baru akan langsung ditempatkan.
                </p>
            </div>

            {{-- 6. SEKOLAH ASAL / TUJUAN --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5" id="labelSekolah">
                    Sekolah Tujuan (jika pindah)
                </label>
                <input type="text" name="sekolah_asal_tujuan" value="{{ old('sekolah_asal_tujuan') }}"
                    id="inputSekolah"
                    placeholder="Contoh: SMP Negeri 1 Borobudur / MA Al-Hikmah..."
                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            {{-- 7. KETERANGAN --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Keterangan Tambahan / Berita Acara</label>
                <textarea name="keterangan" rows="3" placeholder="Catatan bebas admin perihal mutasi santri..."
                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none resize-none">{{ old('keterangan') }}</textarea>
            </div>

            <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeMutasiModal()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-medium text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-theme-xs transition cursor-pointer flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Simpan &amp; Sinkronkan Mutasi</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL KETERANGAN ===== --}}
<div id="keteranganModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs transition-opacity hidden" onclick="this.classList.add('hidden')">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-900" id="keteranganTitle">Catatan Mutasi</h3>
            <button onclick="document.getElementById('keteranganModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="text-xs text-gray-700 leading-relaxed space-y-2">
            <p id="keteranganText" class="whitespace-pre-line"></p>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // ===== MODAL OPEN/CLOSE =====
    function openMutasiModal() {
        document.getElementById('mutasiModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeMutasiModal() {
        document.getElementById('mutasiModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    function closeMutasiModalOutside(e) {
        if (e.target === document.getElementById('mutasiModal')) {
            closeMutasiModal();
        }
    }

    // ===== TOGGLE JENIS MUTASI =====
    function toggleJenisMutasi(jenis) {
        const sKeluar  = document.getElementById('sectionKeluar');
        const sMasuk   = document.getElementById('sectionMasuk');
        const lSekolah = document.getElementById('labelSekolah');
        const iSekolah = document.getElementById('inputSekolah');

        if (jenis === 'Keluar') {
            sKeluar.classList.remove('hidden');
            sMasuk.classList.add('hidden');
            lSekolah.textContent = 'Sekolah Tujuan (jika pindah)';
            iSekolah.placeholder = 'Contoh: SMP Negeri 1 Borobudur / MA Al-Hikmah...';
        } else {
            sKeluar.classList.add('hidden');
            sMasuk.classList.remove('hidden');
            lSekolah.textContent = 'Sekolah Asal Santri';
            iSekolah.placeholder = 'Nama sekolah asal santri...';
        }
    }

    // ===== KETERANGAN MODAL =====
    function showKeterangan(keterangan, nama) {
        document.getElementById('keteranganTitle').textContent = 'Catatan Mutasi: ' + nama;
        document.getElementById('keteranganText').textContent = keterangan;
        document.getElementById('keteranganModal').classList.remove('hidden');
    }

    // Buka modal otomatis jika ada error validasi
    @if($errors->any())
        openMutasiModal();
    @endif

    // Set default toggle sesuai old value
    const oldJenis = "{{ old('jenis_mutasi', 'Keluar') }}";
    if (oldJenis) toggleJenisMutasi(oldJenis);
</script>
@endsection

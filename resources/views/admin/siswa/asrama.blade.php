@extends('admin.layout')

@section('title', 'Manajemen & Pengaturan Asrama Santri — Hidayatullah Tuksongo')

@section('content')
<div class="p-4 lg:p-6" x-data="{
    modalTambah: false,
    modalEdit: false,
    modalPlotting: false,
    modalPenghuni: false,
    editData: { id: '', nama_asrama: '', kamar: '', gender: 'Laki-laki', kapasitas: 8, musyrif: '', lokasi: '', keterangan: '' },
    plottingTarget: { id: '', name: '', sisa: 0 },
    penghuniData: { name: '', students: [] },
    openEdit(dorm) {
        this.editData = { ...dorm };
        this.modalEdit = true;
    },
    openPlotting(dorm) {
        this.plottingTarget = { id: dorm.id, name: dorm.nama_asrama + ' - ' + dorm.kamar, sisa: dorm.kapasitas - (dorm.students ? dorm.students.length : 0) };
        this.modalPlotting = true;
    },
    openPenghuni(dorm) {
        this.penghuniData = { name: dorm.nama_asrama + ' - ' + dorm.kamar, students: dorm.students || [] };
        this.modalPenghuni = true;
    }
}">

    <!-- Alert Notifikasi -->
    @if(session('success'))
    <div class="mb-5 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm shadow-sm animate-fade-in">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-5 flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm shadow-sm">
        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">
        <p class="font-bold mb-1">Terjadi kesalahan pengisian form:</p>
        <ul class="list-disc pl-5 space-y-0.5 text-xs">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen &amp; Pengaturan Asrama</h1>
                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Hunian Santri Mukim</span>
            </div>
            <p class="text-sm text-gray-500 mt-1">Kelola data gedung, kamar hunian, kapasitas ranjang, musyrif pembina, dan plotting santri mukim.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.siswa.penempatanKelas') }}" class="ta-btn ta-btn-outline flex items-center gap-2 text-xs">
                <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Penempatan Santri Baru
            </a>
            <button @click="modalTambah = true" class="ta-btn ta-btn-primary flex items-center gap-2 text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Kamar Baru
            </button>
        </div>
    </div>

    <!-- KPI Statistik Asrama -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Kamar -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Total Kamar</p>
                <p class="text-xl font-bold text-gray-900">{{ $totalKamar }} <span class="text-xs font-normal text-gray-400">Kamar</span></p>
                <p class="text-[11px] text-gray-500 mt-0.5">{{ $totalKamarPutra }} Putra · {{ $totalKamarPutri }} Putri</p>
            </div>
        </div>

        <!-- Total Kapasitas -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Daya Tampung</p>
                <p class="text-xl font-bold text-gray-900">{{ $totalKapasitas }} <span class="text-xs font-normal text-gray-400">Ranjang</span></p>
                <p class="text-[11px] text-indigo-600 font-medium mt-0.5">Kapasitas Maksimal</p>
            </div>
        </div>

        <!-- Santri Bermukim -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Santri Terplotting</p>
                <p class="text-xl font-bold text-emerald-600">{{ $totalSantriBermukim }} <span class="text-xs font-normal text-gray-400">Santri</span></p>
                <p class="text-[11px] text-gray-500 mt-0.5">
                    {{ $totalKapasitas > 0 ? round(($totalSantriBermukim / $totalKapasitas) * 100) : 0 }}% Okupansi
                </p>
            </div>
        </div>

        <!-- Sisa Ranjang Kosong -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Ranjang Kosong</p>
                <p class="text-xl font-bold text-amber-600">{{ $sisaRanjang }} <span class="text-xs font-normal text-gray-400">Slot Bed</span></p>
                <p class="text-[11px] text-gray-500 mt-0.5">Tersedia untuk santri baru</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.siswa.asrama.index') }}" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama kamar, gedung, musyrif..." class="ta-input text-xs pl-9 pr-3">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <select name="gender" class="ta-input text-xs w-full sm:w-44" onchange="this.form.submit()">
                    <option value="">Semua Peruntukan (Putra &amp; Putri)</option>
                    <option value="Laki-laki" {{ request('gender') === 'Laki-laki' ? 'selected' : '' }}>Khusus Asrama Putra</option>
                    <option value="Perempuan" {{ request('gender') === 'Perempuan' ? 'selected' : '' }}>Khusus Asrama Putri</option>
                </select>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                @if(request()->hasAny(['q', 'gender']))
                <a href="{{ route('admin.siswa.asrama.index') }}" class="text-xs text-rose-600 hover:underline px-2">Reset Filter</a>
                @endif
                <button type="submit" class="ta-btn ta-btn-secondary text-xs px-4 py-2">Terapkan</button>
            </div>
        </form>
    </div>

    <!-- Grid Kamar Asrama -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        @forelse($dormitories as $dorm)
        @php
            $terisi = $dorm->students ? $dorm->students->count() : 0;
            $kapasitas = $dorm->kapasitas ?: 8;
            $persen = min(100, round(($terisi / $kapasitas) * 100));
            $isPenuh = $terisi >= $kapasitas;
            $isPutra = $dorm->gender === 'Laki-laki';
        @endphp
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col hover:border-gray-300 transition-all duration-200">
            <!-- Header Kartu -->
            <div class="p-4 border-b border-gray-100 flex items-start justify-between gap-3 bg-gray-50/50">
                <div>
                    <span class="text-[11px] font-semibold tracking-wide uppercase {{ $isPutra ? 'text-blue-600' : 'text-pink-600' }}">
                        {{ $dorm->nama_asrama }}
                    </span>
                    <h3 class="text-base font-bold text-gray-900 mt-0.5 leading-tight">{{ $dorm->kamar }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $dorm->lokasi ?: 'Gedung Utama' }}
                    </p>
                </div>
                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full shrink-0 {{ $isPutra ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                    {{ $isPutra ? 'Santri Putra' : 'Santri Putri' }}
                </span>
            </div>

            <!-- Body Kartu: Okupansi & Musyrif -->
            <div class="p-4 flex-1 flex flex-col justify-between space-y-4">
                <!-- Bar Okupansi -->
                <div>
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-gray-500 font-medium">Kapasitas Ranjang:</span>
                        <span class="font-bold {{ $isPenuh ? 'text-rose-600' : 'text-gray-800' }}">
                            {{ $terisi }} / {{ $kapasitas }} Santri
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="h-2.5 rounded-full transition-all duration-500 {{ $isPenuh ? 'bg-rose-500' : ($persen > 70 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                             style="width: {{ $persen }}%"></div>
                    </div>
                    <div class="flex items-center justify-between mt-1 text-[11px]">
                        <span class="text-gray-400">{{ $persen }}% Terisi</span>
                        @if($isPenuh)
                        <span class="font-semibold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">PENUH</span>
                        @else
                        <span class="font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">Sisa {{ $kapasitas - $terisi }} Bed</span>
                        @endif
                    </div>
                </div>

                <!-- Musyrif Info -->
                <div class="p-2.5 bg-gray-50 rounded-xl border border-gray-100 text-xs">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Musyrif / Pembina Kamar</p>
                    <p class="font-semibold text-gray-800 mt-0.5 truncate">{{ $dorm->musyrif ?: 'Belum Ditentukan' }}</p>
                    @if($dorm->keterangan)
                    <p class="text-[11px] text-gray-500 mt-1 line-clamp-1 italic">"{{ $dorm->keterangan }}"</p>
                    @endif
                </div>
            </div>

            <!-- Footer Kartu: Tombol Aksi -->
            @php
                $safePenghuniStudents = $dorm->students ? $dorm->students->map(function($st) {
                    return [
                        'id' => $st->id,
                        'nama_lengkap' => $st->nama_lengkap,
                        'nis' => $st->nis,
                        'kelas' => $st->kelas,
                        'tahun_masuk' => $st->tahun_masuk,
                    ];
                })->values() : [];
            @endphp
            <div class="p-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-1.5">
                <button type="button" @click="penghuniData = { name: '{{ addslashes($dorm->full_name) }}', students: {{ json_encode($safePenghuniStudents) }} }; modalPenghuni = true;" class="ta-btn ta-btn-secondary text-xs px-2.5 py-1.5 flex items-center gap-1 flex-1 justify-center">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Penghuni ({{ $terisi }})
                </button>

                @if(!$isPenuh)
                <button type="button" @click="plottingTarget = { id: {{ $dorm->id }}, name: '{{ addslashes($dorm->full_name) }}', sisa: {{ max(0, $kapasitas - $terisi) }} }; modalPlotting = true;" class="ta-btn ta-btn-primary text-xs px-2.5 py-1.5 flex items-center gap-1" title="Tambah Santri ke Kamar">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Plot
                </button>
                @endif

                <button type="button" @click="editData = { id: {{ $dorm->id }}, nama_asrama: '{{ addslashes($dorm->nama_asrama) }}', kamar: '{{ addslashes($dorm->kamar) }}', gender: '{{ $dorm->gender }}', kapasitas: {{ $dorm->kapasitas ?: 8 }}, musyrif: '{{ addslashes($dorm->musyrif ?: '') }}', lokasi: '{{ addslashes($dorm->lokasi ?: '') }}', keterangan: '{{ addslashes($dorm->keterangan ?: '') }}' }; modalEdit = true;" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Ubah / Edit Kamar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>

                <form method="POST" action="{{ route('admin.siswa.asrama.destroy', $dorm->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar {{ $dorm->full_name }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Kamar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-gray-200 shadow-sm">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
            <p class="text-gray-500 font-medium">Belum ada kamar asrama yang terdaftar.</p>
            <button @click="modalTambah = true" class="ta-btn ta-btn-primary mt-3 text-xs">Tambah Kamar Pertama</button>
        </div>
        @endforelse
    </div>

    <!-- MODAL 1: Tambah Kamar Asrama Baru -->
    <div x-show="modalTambah" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="modalTambah = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Tambah Kamar Asrama Baru</h3>
                <button @click="modalTambah = false" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            <form method="POST" action="{{ route('admin.siswa.asrama.store') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Gedung / Kompleks Asrama <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_asrama" list="daftar_gedung" placeholder="Contoh: Asrama Putra Abu Bakar" required class="ta-input text-xs">
                    <datalist id="daftar_gedung">
                        <option value="Asrama Putra Abu Bakar">
                        <option value="Asrama Putra Umar Bin Khattab">
                        <option value="Asrama Putra Utsman Bin Affan">
                        <option value="Asrama Putri Khadijah Al-Kubra">
                        <option value="Asrama Putri Aisyah Binti Abu Bakar">
                    </datalist>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama / Nomor Kamar <span class="text-rose-500">*</span></label>
                        <input type="text" name="kamar" placeholder="Contoh: Kamar 05 (Ali Bin Abi Thalib)" required class="ta-input text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Peruntukan Gender <span class="text-rose-500">*</span></label>
                        <select name="gender" required class="ta-input text-xs">
                            <option value="Laki-laki">Santri Putra (Laki-laki)</option>
                            <option value="Perempuan">Santri Putri (Perempuan)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kapasitas Maksimal (Ranjang) <span class="text-rose-500">*</span></label>
                        <input type="number" name="kapasitas" value="8" min="1" max="1000" required class="ta-input text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi Lantai / Blok</label>
                        <input type="text" name="lokasi" placeholder="Contoh: Lantai 1 Sayap Timur" class="ta-input text-xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Musyrif / Pembina Kamar</label>
                    <input type="text" name="musyrif" placeholder="Contoh: Ust. Ahmad Fauzan, Lc" class="ta-input text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan / Fasilitas Kamar</label>
                    <textarea name="keterangan" rows="2" placeholder="Contoh: Kamar khusus santri tahfidz, 4 ranjang tingkat, lemari tertutup" class="ta-input text-xs"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" @click="modalTambah = false" class="ta-btn ta-btn-secondary text-xs">Batal</button>
                    <button type="submit" class="ta-btn ta-btn-primary text-xs">Simpan Kamar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: Edit Kamar Asrama -->
    <div x-show="modalEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="modalEdit = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Edit Data Kamar Asrama</h3>
                <button @click="modalEdit = false" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            <form :action="'{{ url('/admin/siswa-asrama') }}/' + editData.id" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Gedung Asrama <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_asrama" x-model="editData.nama_asrama" required class="ta-input text-xs">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama / Nomor Kamar <span class="text-rose-500">*</span></label>
                        <input type="text" name="kamar" x-model="editData.kamar" required class="ta-input text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Peruntukan Gender <span class="text-rose-500">*</span></label>
                        <select name="gender" x-model="editData.gender" required class="ta-input text-xs">
                            <option value="Laki-laki">Santri Putra (Laki-laki)</option>
                            <option value="Perempuan">Santri Putri (Perempuan)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kapasitas Maksimal <span class="text-rose-500">*</span></label>
                        <input type="number" name="kapasitas" x-model="editData.kapasitas" min="1" max="1000" required class="ta-input text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi Lantai / Blok</label>
                        <input type="text" name="lokasi" x-model="editData.lokasi" class="ta-input text-xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Musyrif / Pembina Kamar</label>
                    <input type="text" name="musyrif" x-model="editData.musyrif" class="ta-input text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan / Fasilitas Kamar</label>
                    <textarea name="keterangan" x-model="editData.keterangan" rows="2" class="ta-input text-xs"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" @click="modalEdit = false" class="ta-btn ta-btn-secondary text-xs">Batal</button>
                    <button type="submit" class="ta-btn ta-btn-primary text-xs">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: Plotting Santri ke Kamar -->
    <div x-show="modalPlotting" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="modalPlotting = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Plotting Santri ke Kamar</h3>
                    <p class="text-xs text-blue-600 font-semibold" x-text="plottingTarget.name"></p>
                </div>
                <button @click="modalPlotting = false" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            <form method="POST" action="{{ route('admin.siswa.asrama.assign') }}" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" name="dormitory_id" :value="plottingTarget.id">

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Santri Mukim <span class="text-rose-500">*</span></label>
                    <select name="student_id" required class="ta-input text-xs">
                        <option value="">-- Pilih Santri yang Belum Ada Kamar --</option>
                        @foreach($unassignedStudents as $st)
                        <option value="{{ $st->id }}">
                            {{ $st->nama_lengkap }} (NIS: {{ $st->nis }} · {{ $st->kelas }} · {{ $st->jenis_kelamin }})
                        </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-gray-400 mt-1">Daftar di atas memuat santri aktif yang belum diplot ke kamar asrama manapun.</p>
                </div>

                <div class="p-3 bg-blue-50 rounded-xl text-xs text-blue-800 flex items-center justify-between">
                    <span>Sisa Kapasitas Ranjang:</span>
                    <span class="font-bold" x-text="plottingTarget.sisa + ' Bed Kosong'"></span>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" @click="modalPlotting = false" class="ta-btn ta-btn-secondary text-xs">Batal</button>
                    <button type="submit" class="ta-btn ta-btn-primary text-xs">Tempatkan Santri</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: Rincian Penghuni Kamar -->
    <div x-show="modalPenghuni" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="modalPenghuni = false" class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-gray-100">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Daftar Penghuni Kamar</h3>
                    <p class="text-xs text-blue-600 font-semibold" x-text="penghuniData.name"></p>
                </div>
                <button @click="modalPenghuni = false" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            <div class="mt-4 max-h-[60vh] overflow-y-auto">
                <template x-if="penghuniData.students.length === 0">
                    <div class="py-8 text-center text-gray-400 text-xs">
                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Kamar ini belum memiliki penghuni santri aktif.
                    </div>
                </template>

                <template x-if="penghuniData.students.length > 0">
                    <div class="divide-y divide-gray-100">
                        <template x-for="(st, index) in penghuniData.students" :key="st.id">
                            <div class="py-3 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 text-xs text-gray-400 font-bold" x-text="(index + 1) + '.'"></span>
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0" x-text="st.nama_lengkap.substring(0, 1)"></div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900" x-text="st.nama_lengkap"></p>
                                        <p class="text-[11px] text-gray-400" x-text="'NIS: ' + (st.nis || '-') + ' · Kelas: ' + (st.kelas || '-') + ' · Masuk: ' + (st.tahun_masuk || '-')"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a :href="'{{ url('/admin/siswa') }}/' + st.id" target="_blank" class="text-xs text-blue-600 hover:underline px-2 py-1 bg-blue-50 rounded">
                                        Profil
                                    </a>
                                    <form :action="'{{ url('/admin/siswa-asrama/remove') }}/' + st.id" method="POST" onsubmit="return confirm('Keluarkan santri ini dari kamar asrama?');">
                                        @csrf
                                        <button type="submit" class="text-xs text-rose-600 hover:bg-rose-50 px-2 py-1 rounded transition" title="Keluarkan dari Kamar">
                                            Keluarkan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-100 mt-4">
                <button type="button" @click="modalPenghuni = false" class="ta-btn ta-btn-secondary text-xs">Tutup</button>
            </div>
        </div>
    </div>

</div>
@endsection

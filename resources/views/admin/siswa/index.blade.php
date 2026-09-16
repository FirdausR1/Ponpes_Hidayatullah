@extends('admin.layout')

@section('title', 'Manajemen Siswa & Santri Aktif')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200">Database Induk Pesantren</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Manajemen Siswa &amp; Santri Aktif</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Kelola data pokok santri, kelas, angkatan, kenaikan kelas, dan akses login santri.</p>
        </div>

        <!-- Action Buttons — minimal, text only -->
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('admin.siswa.exportExcel', request()->query()) }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                Export Excel
            </a>

            <button type="button" onclick="document.getElementById('modalImportExcel').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                Import Massal
            </button>

            <a href="{{ route('admin.siswa.kenaikanKelas') }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-800 px-3 py-2 text-xs font-semibold shadow-theme-xs transition">
                Kenaikan Kelas
            </a>

            <a href="{{ route('admin.siswa.penempatanKelas') }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-purple-200 bg-purple-50 hover:bg-purple-100 text-purple-800 px-3 py-2 text-xs font-semibold shadow-theme-xs transition">
                Penempatan Kelas
            </a>

            <button type="button" onclick="document.getElementById('modalBulkImportPsb').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-300 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 px-3 py-2 text-xs font-semibold shadow-theme-xs transition">
                Tarik dari PSB
                @if($unimportedPsbCount > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-600 text-white">{{ $unimportedPsbCount }}</span>
                @endif
            </button>

            <a href="{{ route('admin.siswa.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-gray-900 px-3.5 py-2 text-xs font-semibold text-white shadow-theme-xs hover:bg-gray-800 transition">
                + Tambah Santri
            </a>
        </div>
    </div>

    <!-- Alert PSB -->
    @if($unimportedPsbCount > 0)
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 shadow-theme-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <p class="text-sm font-bold text-emerald-950">Terdapat {{ $unimportedPsbCount }} Calon Santri DITERIMA di PSB yang Belum Ditarik ke Data Siswa Aktif</p>
            <p class="text-xs text-emerald-800 mt-0.5">Pindahkan seluruh santri lulus seleksi secara massal dalam 1 klik.</p>
        </div>
        <button type="button" onclick="document.getElementById('modalBulkImportPsb').classList.remove('hidden')"
                class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-theme-xs transition whitespace-nowrap">
            Add Massal Sekarang &rarr;
        </button>
    </div>
    @endif

    <!-- Stats (6 kartu: total, aktif, MTs, MA, L, P) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-theme-xs">
            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block">Total Santri</span>
            <span class="text-2xl font-bold text-gray-800 block mt-1">{{ $stats['total'] }}</span>
            <span class="text-[11px] text-gray-400 mt-1 block">Terdaftar di sistem</span>
        </div>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-theme-xs">
            <span class="text-[10px] font-semibold text-emerald-600 uppercase tracking-wider block">Santri Aktif</span>
            <span class="text-2xl font-bold text-emerald-700 block mt-1">{{ $stats['aktif'] }}</span>
            <span class="text-[11px] text-emerald-600 mt-1 block">Mukim &amp; Laju KBM</span>
        </div>
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 shadow-theme-xs">
            <span class="text-[10px] font-semibold text-blue-600 uppercase tracking-wider block">Jenjang MTs</span>
            <span class="text-2xl font-bold text-blue-700 block mt-1">{{ $stats['mts'] }}</span>
            <span class="text-[11px] text-blue-500 mt-1 block">Kelas VII – IX</span>
        </div>
        <div class="rounded-2xl border border-purple-200 bg-purple-50 p-4 shadow-theme-xs">
            <span class="text-[10px] font-semibold text-purple-600 uppercase tracking-wider block">Jenjang MA</span>
            <span class="text-2xl font-bold text-purple-700 block mt-1">{{ $stats['ma'] }}</span>
            <span class="text-[11px] text-purple-500 mt-1 block">Kelas X – XII</span>
        </div>
        <a href="{{ route('admin.siswa.index', array_merge(request()->query(), ['jenis_kelamin' => 'Laki-laki'])) }}"
           class="rounded-2xl border border-sky-200 bg-sky-50 p-4 shadow-theme-xs hover:bg-sky-100 transition block">
            <span class="text-[10px] font-semibold text-sky-600 uppercase tracking-wider block">Laki-laki</span>
            <span class="text-2xl font-bold text-sky-700 block mt-1">{{ $stats['laki'] }}</span>
            <span class="text-[11px] text-sky-500 mt-1 block">Santri putra</span>
        </a>
        <a href="{{ route('admin.siswa.index', array_merge(request()->query(), ['jenis_kelamin' => 'Perempuan'])) }}"
           class="rounded-2xl border border-pink-200 bg-pink-50 p-4 shadow-theme-xs hover:bg-pink-100 transition block">
            <span class="text-[10px] font-semibold text-pink-600 uppercase tracking-wider block">Perempuan</span>
            <span class="text-2xl font-bold text-pink-700 block mt-1">{{ $stats['perempuan'] }}</span>
            <span class="text-[11px] text-pink-500 mt-1 block">Santri putri</span>
        </a>
    </div>

    <!-- Filter & Pencarian -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
        <form method="GET" action="{{ route('admin.siswa.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3">
            <!-- Search -->
            <div class="lg:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIS, username..."
                       class="h-10 w-full pl-9 pr-4 text-xs text-gray-800 bg-white border border-gray-300 rounded-lg focus:border-brand-500 outline-none">
            </div>

            <!-- Jenjang -->
            <select name="jenjang" onchange="this.form.submit()" class="h-10 w-full text-xs rounded-lg px-3 border border-gray-300 bg-white text-gray-700 outline-none cursor-pointer">
                <option value="">Semua Jenjang</option>
                <option value="MTs Mukim" {{ request('jenjang') == 'MTs Mukim' ? 'selected' : '' }}>MTs Mukim</option>
                <option value="MTs Laju"  {{ request('jenjang') == 'MTs Laju'  ? 'selected' : '' }}>MTs Laju</option>
                <option value="MA Mukim"  {{ request('jenjang') == 'MA Mukim'  ? 'selected' : '' }}>MA Mukim</option>
                <option value="MA Laju"   {{ request('jenjang') == 'MA Laju'   ? 'selected' : '' }}>MA Laju</option>
            </select>

            <!-- Kelas -->
            <select name="kelas" onchange="this.form.submit()" class="h-10 w-full text-xs rounded-lg px-3 border border-gray-300 bg-white text-gray-700 outline-none cursor-pointer">
                <option value="">Semua Kelas</option>
                @foreach($allClasses as $kls)
                    <option value="{{ $kls }}" {{ request('kelas') == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                @endforeach
            </select>

            <!-- Jenis Kelamin -->
            <select name="jenis_kelamin" onchange="this.form.submit()" class="h-10 w-full text-xs rounded-lg px-3 border border-gray-300 bg-white text-gray-700 outline-none cursor-pointer">
                <option value="">Semua Jenis Kelamin</option>
                <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>

            <!-- Status -->
            <select name="status" onchange="this.form.submit()" class="h-10 w-full text-xs rounded-lg px-3 border border-gray-300 bg-white text-gray-700 outline-none cursor-pointer">
                <option value="">Semua Status</option>
                <option value="Aktif"  {{ request('status') == 'Aktif'  ? 'selected' : '' }}>Aktif</option>
                <option value="Alumni" {{ request('status') == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                <option value="Mutasi" {{ request('status') == 'Mutasi' ? 'selected' : '' }}>Mutasi</option>
                <option value="Cuti"   {{ request('status') == 'Cuti'   ? 'selected' : '' }}>Cuti</option>
            </select>

            <!-- Submit -->
            <div class="flex items-center gap-2">
                <button type="submit" class="h-10 flex-1 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-semibold transition">
                    Filter
                </button>
                @if(request()->anyFilled(['q','kelas','tahun_masuk','status','jenjang','jenis_kelamin']))
                    <a href="{{ route('admin.siswa.index') }}" class="h-10 px-3 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-600 text-xs font-medium flex items-center justify-center transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Active filter tags -->
        @if(request()->anyFilled(['q','kelas','tahun_masuk','status','jenjang','jenis_kelamin']))
        <div class="mt-3 flex flex-wrap items-center gap-2">
            <span class="text-[11px] text-gray-400 font-medium">Filter aktif:</span>
            @if(request('q'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] bg-gray-100 text-gray-700 font-medium">
                    "{{ request('q') }}"
                    <a href="{{ route('admin.siswa.index', array_diff_key(request()->query(), ['q'=>''])) }}" class="text-gray-400 hover:text-gray-700 ml-0.5">&times;</a>
                </span>
            @endif
            @if(request('jenjang'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] bg-blue-100 text-blue-700 font-medium">
                    {{ request('jenjang') }}
                    <a href="{{ route('admin.siswa.index', array_diff_key(request()->query(), ['jenjang'=>''])) }}" class="text-blue-400 hover:text-blue-700 ml-0.5">&times;</a>
                </span>
            @endif
            @if(request('jenis_kelamin'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] {{ request('jenis_kelamin') == 'Laki-laki' ? 'bg-sky-100 text-sky-700' : 'bg-pink-100 text-pink-700' }} font-medium">
                    {{ request('jenis_kelamin') }}
                    <a href="{{ route('admin.siswa.index', array_diff_key(request()->query(), ['jenis_kelamin'=>''])) }}" class="opacity-60 hover:opacity-100 ml-0.5">&times;</a>
                </span>
            @endif
            @if(request('kelas'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] bg-purple-100 text-purple-700 font-medium">
                    Kelas {{ request('kelas') }}
                    <a href="{{ route('admin.siswa.index', array_diff_key(request()->query(), ['kelas'=>''])) }}" class="text-purple-400 hover:text-purple-700 ml-0.5">&times;</a>
                </span>
            @endif
            @if(request('status'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] bg-emerald-100 text-emerald-700 font-medium">
                    {{ request('status') }}
                    <a href="{{ route('admin.siswa.index', array_diff_key(request()->query(), ['status'=>''])) }}" class="text-emerald-400 hover:text-emerald-700 ml-0.5">&times;</a>
                </span>
            @endif
        </div>
        @endif
    </div>

    <!-- Tabel Data Santri -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <!-- Table header count -->
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 bg-gray-50/60">
            <p class="text-xs text-gray-500">
                Menampilkan <strong class="text-gray-800">{{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }}</strong>
                dari <strong class="text-gray-800">{{ $students->total() }}</strong> santri
            </p>
            @if($students->total() > 0)
            <span class="text-[11px] text-gray-400">Halaman {{ $students->currentPage() }} / {{ $students->lastPage() }}</span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70">
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Santri</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIS / NISN</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas &amp; Jenjang</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Angkatan</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Wali &amp; Kontak</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-50/60 transition">
                            <!-- Santri -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($student->foto)
                                        <div class="w-9 h-11 overflow-hidden rounded-lg border border-gray-200 shrink-0 bg-gray-100">
                                            <img src="{{ $student->foto }}" alt="{{ $student->nama_lengkap }}" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-9 h-11 rounded-lg {{ $student->jenis_kelamin === 'Perempuan' ? 'bg-pink-50 text-pink-600 border-pink-200' : 'bg-sky-50 text-sky-600 border-sky-200' }} border flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ strtoupper(substr($student->nama_lengkap, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('admin.siswa.show', $student->id) }}" class="font-semibold text-gray-900 hover:text-emerald-700 text-sm block transition">
                                            {{ $student->nama_lengkap }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-0.5 text-xs text-gray-500">
                                            <span class="{{ $student->jenis_kelamin === 'Perempuan' ? 'text-pink-600' : 'text-sky-600' }} font-medium">
                                                {{ $student->jenis_kelamin }}
                                            </span>
                                            @if($student->tanggal_lahir)
                                                <span class="text-gray-300">•</span>
                                                <span>{{ $student->tanggal_lahir }}</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-gray-400 font-mono mt-0.5">
                                            {{ $student->username ?: $student->nis }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- NIS / NISN -->
                            <td class="px-5 py-4">
                                <span class="font-mono font-bold text-gray-900 text-xs block">{{ $student->nis }}</span>
                                <span class="font-mono text-xs text-gray-400 block mt-0.5">{{ $student->nisn ?: '—' }}</span>
                            </td>

                            <!-- Kelas & Jenjang -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-bold {{ str_contains($student->jenjang, 'MA') ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                        Kelas {{ $student->kelas }}
                                    </span>
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-[10.5px] font-semibold {{ $student->hunian === 'Mukim' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $student->hunian }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-emerald-700 mt-1 font-semibold">
                                    Rp {{ number_format($student->tarif_bulanan['total_bulanan'], 0, ',', '.') }}/bln
                                </div>
                            </td>

                            <!-- Angkatan -->
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    {{ $student->tahun_masuk }}
                                </span>
                                @if($student->kamar_asrama)
                                    <span class="text-[11px] text-gray-400 block mt-1 truncate max-w-[120px]" title="{{ $student->kamar_asrama }}">
                                        {{ $student->kamar_asrama }}
                                    </span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4">
                                @php
                                    $statusClasses = [
                                        'Aktif'  => 'bg-emerald-100 text-emerald-800',
                                        'Alumni' => 'bg-purple-100 text-purple-800',
                                        'Mutasi' => 'bg-amber-100 text-amber-800',
                                        'Cuti'   => 'bg-gray-100 text-gray-600',
                                    ][$student->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold {{ $statusClasses }}">
                                    {{ $student->status }}
                                </span>
                            </td>

                            <!-- Wali & Kontak -->
                            <td class="px-5 py-4 text-xs">
                                <span class="font-medium text-gray-800 block">{{ $student->nama_wali ?: '—' }}</span>
                                @if($student->no_whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->no_whatsapp) }}" target="_blank"
                                       class="text-emerald-600 hover:text-emerald-700 font-mono text-[11px] mt-0.5 block">
                                        {{ $student->no_whatsapp }}
                                    </a>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.siswa.show', $student->id) }}"
                                       class="px-2.5 py-1.5 text-[11px] font-semibold text-gray-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg border border-gray-200 hover:border-emerald-200 transition">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.siswa.edit', $student->id) }}"
                                       class="px-2.5 py-1.5 text-[11px] font-semibold text-gray-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg border border-gray-200 hover:border-blue-200 transition">
                                        Edit
                                    </a>
                                    <a href="{{ route('admin.siswa.mutasi.index', ['q' => $student->nis]) }}"
                                       class="px-2.5 py-1.5 text-[11px] font-semibold text-orange-600 hover:text-orange-800 hover:bg-orange-50 rounded-lg border border-orange-200 transition"
                                       title="Catat atau Lihat Riwayat Mutasi Santri">
                                        Mutasi
                                    </a>
                                    <form action="{{ route('admin.siswa.destroy', $student->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus data santri {{ $student->nama_lengkap }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1.5 text-[11px] font-semibold text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg border border-gray-200 hover:border-rose-200 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <p class="text-sm font-semibold text-gray-700">Belum ada data santri ditemukan.</p>
                                <p class="text-xs text-gray-400 mt-1">Gunakan tombol Import Massal atau Tambah Santri untuk mulai mengelola.</p>
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
</div>

</div>

<!-- MODAL 1: IMPORT MASSAL SANTRI DENGAN TEMPLATE EXCEL / CSV BESERTA TUNGGAKAN LALU -->
<div id="modalImportExcel" class="ta-modal-backdrop hidden">
    <div class="ta-modal max-w-xl">
        <div class="ta-modal-header">
            <div>
                <h3 class="font-bold text-gray-800 text-base">Import Massal Santri &amp; Tagihan Tunggakan Lalu</h3>
                <p class="text-xs text-gray-500 mt-0.5">Unggah data santri baru/lama serentak, lengkap dengan catatan tunggakan tagihan masa lalu.</p>
            </div>
            <button type="button" onclick="document.getElementById('modalImportExcel').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <form action="{{ route('admin.siswa.importExcel') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="ta-modal-body space-y-4 text-xs">
                <!-- Download Template Step -->
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-emerald-950 block">Langkah 1: Unduh Format Template Excel Resmi (.xlsx)</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-600 text-white uppercase tracking-wider">Format Baru</span>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-[11px]">
                        Gunakan file template Microsoft Excel resmi yang sudah disederhanakan (Hanya 10 Kolom Praktis). Sangat cepat dan mudah diisi dari data pembukuan manual lama:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px] text-gray-700 bg-white p-2.5 rounded-lg border border-emerald-100">
                        <div class="flex items-start gap-1.5">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span><strong>Kolom A-G:</strong> Data Pokok (Nama, NIS, L/P, Tgl Lahir, Kelas, Wali, WA)</span>
                        </div>
                        <div class="flex items-start gap-1.5">
                            <span class="text-amber-600 font-bold">✓</span>
                            <span><strong>Kolom H:</strong> Sisa Uang Pangkal / Daftar Ulang Lalu</span>
                        </div>
                        <div class="flex items-start gap-1.5">
                            <span class="text-amber-600 font-bold">✓</span>
                            <span><strong>Kolom I:</strong> Tunggakan SPP Bulanan</span>
                        </div>
                        <div class="flex items-start gap-1.5">
                            <span class="text-amber-600 font-bold">✓</span>
                            <span><strong>Kolom J:</strong> Keterangan / Rincian Tunggakan</span>
                        </div>
                    </div>
                    <div class="pt-1">
                        <a href="{{ route('admin.siswa.downloadTemplate') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs shadow-theme-xs transition">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M8.5 13.5l2 2.5-2 2.5h1.5l1.25-1.75L12.5 18.5H14l-2-2.5 2-2.5h-1.5l-1.25 1.75L10 13.5H8.5z"/></svg>
                            <span>Unduh Template Excel Praktis (10 Kolom)</span>
                        </a>
                    </div>
                </div>

                <!-- Upload File Step -->
                <div class="space-y-2">
                    <label class="block font-bold text-gray-800 uppercase tracking-wider">Langkah 2: Pilih File Excel yang Telah Diisi</label>
                    <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="ta-input text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
                    <p class="text-[11px] text-gray-500">Mendukung file dokumen Microsoft Excel <code>.xlsx</code> atau <code>.xls</code> (Maksimal 15 MB).</p>
                </div>

                <!-- Fitur Pintar Info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 text-[11px] space-y-1">
                        <span class="font-bold block text-blue-800">🔑 Akun Login Santri Baru:</span>
                        <ul class="list-disc list-inside space-y-0.5 text-gray-700 text-[10.5px]">
                            <li><strong>Username:</strong> Dari kolom Username atau NIS/NISN.</li>
                            <li><strong>Password:</strong> Dari <strong>tanggal lahir</strong> santri (format DDMMYYYY, contoh <code>15052012</code>).</li>
                        </ul>
                    </div>
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-[11px] space-y-1">
                        <span class="font-bold block text-amber-800">💰 Santri Lama &amp; Tunggakan:</span>
                        <ul class="list-disc list-inside space-y-0.5 text-gray-700 text-[10.5px]">
                            <li>Jika mengisi NIS santri yang <strong>sudah ada</strong>, tagihan langsung tersambung tanpa duplikasi akun santri.</li>
                            <li>Tagihan tunggakan otomatis muncul di Kasir POS &amp; Rekap Keuangan.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="ta-modal-footer">
                <button type="button" onclick="document.getElementById('modalImportExcel').classList.add('hidden')" class="ta-btn-sm-outline">
                    Batal
                </button>
                <button type="submit" class="ta-btn-sm-primary">
                    Mulai Proses Import Santri &amp; Tagihan
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

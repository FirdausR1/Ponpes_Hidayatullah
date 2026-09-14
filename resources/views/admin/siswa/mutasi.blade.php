@extends('admin.layout')

@section('title', 'Mutasi Santri')

@section('content')
<div class="space-y-6">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.siswa.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">&larr; Kembali ke Data Santri</a>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">Administrasi Kepesantrenan</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Mutasi Santri</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Catat dan kelola riwayat mutasi santri — keluar (pindah/DO) maupun masuk (pindahan dari sekolah lain).
            </p>
        </div>
        <div>
            <button type="button" id="btnOpenModal" onclick="openMutasiModal()"
                class="ta-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Catat Mutasi Baru
            </button>
        </div>
    </div>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
    <div class="ta-alert ta-alert-success">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="ta-alert ta-alert-error">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Total Mutasi Keluar --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_keluar'] }}</div>
                <div class="text-xs text-gray-500 font-medium mt-0.5">Total Mutasi Keluar</div>
            </div>
        </div>
        {{-- Total Mutasi Masuk --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_masuk'] }}</div>
                <div class="text-xs text-gray-500 font-medium mt-0.5">Total Mutasi Masuk</div>
            </div>
        </div>
        {{-- Bulan Ini --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['bulan_ini'] }}</div>
                <div class="text-xs text-gray-500 font-medium mt-0.5">Mutasi Bulan Ini</div>
            </div>
        </div>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
        <form method="GET" action="{{ route('admin.siswa.mutasi.index') }}" class="flex flex-col sm:flex-row gap-3 items-end">
            {{-- Cari --}}
            <div class="flex-1 min-w-0">
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Cari Santri / Alasan</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama, NIS, atau alasan..."
                        class="ta-input pl-9 text-sm">
                </div>
            </div>
            {{-- Jenis --}}
            <div class="w-full sm:w-44">
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Jenis Mutasi</label>
                <select name="jenis" class="ta-input text-sm">
                    <option value="">Semua</option>
                    <option value="Keluar" {{ request('jenis') === 'Keluar' ? 'selected' : '' }}>Keluar</option>
                    <option value="Masuk"  {{ request('jenis') === 'Masuk'  ? 'selected' : '' }}>Masuk</option>
                </select>
            </div>
            {{-- Tahun --}}
            <div class="w-full sm:w-36">
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Tahun</label>
                <select name="tahun" class="ta-input text-sm">
                    <option value="">Semua</option>
                    @foreach($tahunList as $thn)
                        <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Actions --}}
            <div class="flex gap-2">
                <button type="submit" class="ta-btn-primary">Filter</button>
                <a href="{{ route('admin.siswa.mutasi.index') }}" class="ta-btn-outline">Reset</a>
            </div>
        </form>
    </div>

    {{-- ===== TABEL RIWAYAT MUTASI ===== --}}
    <div class="ta-card">
        <div class="ta-card-header">
            <div>
                <h2 class="text-base font-semibold text-gray-900">Riwayat Mutasi</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $mutations->total() }} catatan mutasi ditemukan</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="ta-table">
                <thead>
                    <tr>
                        <th class="w-8">#</th>
                        <th>Santri</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Kelas Dari</th>
                        <th>Alasan / Kelas Ke</th>
                        <th>Sekolah Asal / Tujuan</th>
                        <th>Dicatat Oleh</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mutations as $i => $mut)
                    <tr>
                        <td class="text-gray-400 text-xs">{{ $mutations->firstItem() + $i }}</td>
                        <td>
                            @if($mut->student)
                                <a href="{{ route('admin.siswa.show', $mut->student->id) }}"
                                   class="font-semibold text-gray-900 hover:text-blue-600 text-sm leading-tight">
                                    {{ $mut->student->nama_lengkap }}
                                </a>
                                <div class="text-xs text-gray-400">NIS: {{ $mut->student->nis ?? '-' }}</div>
                            @else
                                <span class="text-xs text-gray-400 italic">Data santri tidak ditemukan</span>
                            @endif
                        </td>
                        <td>
                            @if($mut->jenis_mutasi === 'Keluar')
                                <span class="badge-error">Keluar</span>
                            @else
                                <span class="badge-success">Masuk</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-700 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($mut->tanggal_mutasi)->format('d M Y') }}
                        </td>
                        <td class="text-sm text-gray-600">{{ $mut->kelas_dari ?? '-' }}</td>
                        <td class="text-sm text-gray-700">
                            @if($mut->jenis_mutasi === 'Keluar')
                                {{ $mut->alasan ?? '-' }}
                            @else
                                <span class="font-medium text-emerald-700">→ {{ $mut->kelas_ke ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-600 max-w-[180px] truncate" title="{{ $mut->sekolah_asal_tujuan }}">
                            {{ $mut->sekolah_asal_tujuan ?? '-' }}
                        </td>
                        <td class="text-xs text-gray-500">{{ $mut->dicatat_oleh ?? '-' }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Detail keterangan --}}
                                @if($mut->keterangan)
                                <button type="button"
                                    onclick="showKeterangan(`{{ addslashes($mut->keterangan) }}`, `{{ addslashes($mut->student->nama_lengkap ?? 'Santri') }}`)"
                                    class="ta-btn-sm-outline" title="Lihat Keterangan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                </button>
                                @endif
                                {{-- Hapus --}}
                                <form action="{{ route('admin.siswa.mutasi.destroy', $mut->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus catatan mutasi ini? Status santri akan dipulihkan ke sebelumnya.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ta-btn-sm-danger" title="Hapus Catatan Mutasi">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-16 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                                <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <p class="text-sm font-medium">Belum ada riwayat mutasi</p>
                            <p class="text-xs mt-1">Klik <strong>Catat Mutasi Baru</strong> untuk menambahkan.</p>
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
<div id="mutasiModal" class="ta-modal-backdrop hidden" onclick="closeMutasiModalOutside(event)">
    <div class="ta-modal" style="max-width:600px;" onclick="event.stopPropagation()">
        <div class="ta-modal-header">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Catat Mutasi Santri</h3>
                    <p class="text-xs text-gray-400">Isi data mutasi dengan lengkap dan benar</p>
                </div>
            </div>
            <button onclick="closeMutasiModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.siswa.mutasi.store') }}" id="formMutasi">
            @csrf
            <div class="ta-modal-body space-y-4">

                {{-- Validasi error --}}
                @if($errors->any())
                <div class="ta-alert ta-alert-error">
                    <ul class="text-xs list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Cari Santri --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Santri <span class="text-red-500">*</span>
                    </label>
                    <div class="relative" x-data="{ search: '', results: [], loading: false, selected: null }" @click.away="results = []">
                        <input type="text"
                            x-model="search"
                            @input.debounce.300ms="
                                if(search.length >= 2) {
                                    loading = true;
                                    fetch(`/admin/siswa/mutasi-search?q=` + encodeURIComponent(search))
                                        .then(r => r.json())
                                        .then(d => { results = d; loading = false; })
                                        .catch(() => { loading = false; })
                                } else { results = []; }
                            "
                            placeholder="Ketik nama atau NIS santri..."
                            class="ta-input text-sm pr-8"
                            autocomplete="off">
                        {{-- hidden id --}}
                        <input type="hidden" name="student_id" id="selectedStudentId" value="{{ old('student_id') }}">

                        <span x-show="loading" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                        </span>

                        {{-- Dropdown hasil --}}
                        <div x-show="results.length > 0" x-cloak
                             class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden max-h-60 overflow-y-auto">
                            <template x-for="s in results" :key="s.id">
                                <button type="button"
                                    @click="
                                        search = s.nama_lengkap + ' (NIS: ' + s.nis + ')';
                                        document.getElementById('selectedStudentId').value = s.id;
                                        document.getElementById('kelasDariInfo').textContent = 'Kelas saat ini: ' + (s.kelas ?? '-');
                                        results = [];
                                    "
                                    class="w-full flex items-start gap-3 px-4 py-2.5 hover:bg-gray-50 text-left border-b border-gray-50 last:border-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-xs font-bold text-blue-600" x-text="s.nama_lengkap.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900" x-text="s.nama_lengkap"></div>
                                        <div class="text-xs text-gray-400" x-text="'NIS: ' + (s.nis ?? '-') + ' | ' + (s.kelas ?? '-') + ' | ' + (s.status ?? '-')"></div>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>
                    <p id="kelasDariInfo" class="text-xs text-emerald-700 font-medium mt-1"></p>
                </div>

                {{-- Jenis Mutasi --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Jenis Mutasi <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-colors hover:bg-red-50 has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                            <input type="radio" name="jenis_mutasi" value="Keluar" class="accent-red-500"
                                onchange="toggleJenisMutasi('Keluar')"
                                {{ old('jenis_mutasi', 'Keluar') === 'Keluar' ? 'checked' : '' }}>
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Mutasi Keluar</div>
                                <div class="text-xs text-gray-400">Pindah / DO / Meninggal</div>
                            </div>
                        </label>
                        <label class="relative flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-colors hover:bg-emerald-50 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                            <input type="radio" name="jenis_mutasi" value="Masuk" class="accent-emerald-500"
                                onchange="toggleJenisMutasi('Masuk')"
                                {{ old('jenis_mutasi') === 'Masuk' ? 'checked' : '' }}>
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Mutasi Masuk</div>
                                <div class="text-xs text-gray-400">Santri pindahan dari luar</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Tanggal Mutasi --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Tanggal Mutasi <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_mutasi" value="{{ old('tanggal_mutasi', date('Y-m-d')) }}"
                        class="ta-input text-sm" required>
                </div>

                {{-- Field khusus Keluar: Alasan --}}
                <div id="sectionKeluar">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Alasan Mutasi <span class="text-red-500">*</span>
                    </label>
                    <select name="alasan" class="ta-input text-sm">
                        <option value="">-- Pilih Alasan --</option>
                        @foreach($alasanOptions as $val => $label)
                            <option value="{{ $val }}" {{ old('alasan') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Field khusus Masuk: Kelas Ke --}}
                <div id="sectionMasuk" class="hidden">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Kelas yang Dituju
                    </label>
                    <select name="kelas_ke" class="ta-input text-sm">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($allClasses as $kls)
                            <option value="{{ $kls }}" {{ old('kelas_ke') === $kls ? 'selected' : '' }}>{{ $kls }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Sekolah Asal / Tujuan --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5" id="labelSekolah">
                        Sekolah Tujuan (jika pindah)
                    </label>
                    <input type="text" name="sekolah_asal_tujuan" value="{{ old('sekolah_asal_tujuan') }}"
                        id="inputSekolah"
                        placeholder="Nama sekolah tujuan / asal..."
                        class="ta-input text-sm">
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="3" placeholder="Catatan bebas admin (opsional)..."
                        class="ta-input text-sm resize-none">{{ old('keterangan') }}</textarea>
                </div>

            </div>
            <div class="ta-modal-footer">
                <button type="button" onclick="closeMutasiModal()" class="ta-btn-outline">Batal</button>
                <button type="submit" class="ta-btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Simpan Mutasi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL KETERANGAN ===== --}}
<div id="keteranganModal" class="ta-modal-backdrop hidden" onclick="this.classList.add('hidden')">
    <div class="ta-modal" style="max-width:440px;" onclick="event.stopPropagation()">
        <div class="ta-modal-header">
            <h3 class="text-base font-bold text-gray-900" id="keteranganTitle">Keterangan Mutasi</h3>
            <button onclick="document.getElementById('keteranganModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="ta-modal-body">
            <p id="keteranganText" class="text-sm text-gray-700 leading-relaxed"></p>
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
            iSekolah.placeholder = 'Nama sekolah tujuan...';
        } else {
            sKeluar.classList.add('hidden');
            sMasuk.classList.remove('hidden');
            lSekolah.textContent = 'Sekolah Asal Santri';
            iSekolah.placeholder = 'Nama sekolah asal...';
        }
    }

    // ===== KETERANGAN MODAL =====
    function showKeterangan(keterangan, nama) {
        document.getElementById('keteranganTitle').textContent = 'Keterangan: ' + nama;
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

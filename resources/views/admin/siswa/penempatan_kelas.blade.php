@extends('admin.layout')

@section('title', 'Penempatan Kelas & Asrama Santri Baru — Hidayatullah Tuksongo')

@section('content')
<div class="space-y-6" x-data="{
    modalRanking: false,
    modalManual: false,
    selectedJenjangRanking: 'MTs',
    modeRanking: 'seimbang',
    selectedStudents: [],
    selectAll: false,
    filterJenjangTab: '{{ $jenjangTab }}',
    toggleSelectAll() {
        if (this.selectAll) {
            this.selectedStudents = Array.from(document.querySelectorAll('.student-checkbox')).map(cb => cb.value);
        } else {
            this.selectedStudents = [];
        }
    },
    updateSelectAllState() {
        const allBoxes = Array.from(document.querySelectorAll('.student-checkbox'));
        this.selectAll = allBoxes.length > 0 && allBoxes.every(cb => this.selectedStudents.includes(cb.value));
    },
    quickSaveStudent(studentId, kelasVal, dormVal) {
        fetch('{{ route('admin.siswa.penempatanKelas.quickUpdate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                student_id: studentId,
                kelas: kelasVal,
                dormitory_id: dormVal
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    customClass: { popup: 'rounded-xl text-xs font-sans' }
                });
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan',
                text: 'Terjadi kesalahan sistem saat memperbarui penempatan.',
                customClass: { popup: 'rounded-2xl text-xs font-sans' }
            });
        });
    }
}">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('admin.siswa.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Manajemen Santri</a>
                <span class="text-xs text-gray-300">/</span>
                <span class="text-xs font-semibold text-gray-500">Penempatan Santri Baru</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Penempatan Kelas &amp; Asrama Santri Baru</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Atur pembagian rombel santri baru ke <strong>Kelas 7 (VII-A, VII-B, dll)</strong> atau <strong>Kelas 10 (X-A, X-B, dll)</strong> secara otomatis berdasarkan <strong>Peringkat Nilai CBT</strong> atau bebas <strong>Suka-suka Admin</strong>.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Tombol 1: Bagi Otomatis Sesuai Peringkat CBT -->
            <button @click="modalRanking = true" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-emerald-700 transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                + Bagi Kelas Sesuai Peringkat CBT
            </button>

            <!-- Tombol 2: Master Rombel Kelas -->
            <a href="{{ route('admin.siswa.kelas.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-semibold text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Kelola Kelas
            </a>

            <!-- Tombol 3: Manajemen Asrama -->
            <a href="{{ route('admin.siswa.asrama.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-semibold text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Kelola Asrama
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Santri Baru -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Santri Baru</span>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalSantriBaru }} <span class="text-xs font-normal text-gray-400">Santri</span></h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-2 text-[11px] text-gray-500">
                <span class="text-emerald-700 font-semibold">🏠 {{ $totalMukim ?? 0 }} Mukim</span>
                <span>•</span>
                <span class="text-sky-700 font-semibold">🚶 {{ $totalLaju ?? 0 }} Laju</span>
            </div>
        </div>

        <!-- Card 2: Sudah Ada Kelas -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Sudah Masuk Kelas</span>
                    <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $sudahAdaKelas }} <span class="text-xs font-normal text-gray-400">Santri</span></h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-xs text-emerald-600 mt-2 font-medium">Telah terplotting ke rombel kelas</p>
        </div>

        <!-- Card 3: Belum Ada Kelas -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Belum Ditentukan</span>
                    <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $belumAdaKelas }} <span class="text-xs font-normal text-gray-400">Santri</span></h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
            </div>
            <p class="text-xs text-amber-600 mt-2 font-medium">Menunggu alokasi kelas definitif</p>
        </div>

        <!-- Card 4: Santri Mukim Belum Kamar -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Mukim Belum Ada Kamar</span>
                    <h3 class="text-2xl font-bold text-purple-600 mt-1">{{ $mukimBelumKamar }} <span class="text-xs font-normal text-gray-400">Santri</span></h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
                </div>
            </div>
            <form action="{{ route('admin.siswa.penempatanKelas.autoAsrama') }}" method="POST" class="mt-2 flex items-center justify-between">
                @csrf
                <button type="submit" onclick="return confirm('Plotting otomatis seluruh santri mukim baru ke kamar asrama yang masih kosong?');" class="text-xs font-bold text-purple-600 hover:text-purple-800 hover:underline inline-flex items-center gap-1">
                    <span>Auto Plotting Asrama &rarr;</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Filter & Toolbar Card -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs space-y-4">
        <form method="GET" action="{{ route('admin.siswa.penempatanKelas') }}" class="flex flex-col lg:flex-row gap-3 items-stretch lg:items-center justify-between">
            <!-- Tab Jenjang (Semua, MTs / Kelas 7, MA / Kelas 10) -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.siswa.penempatanKelas', array_merge(request()->except('jenjang'), ['jenjang' => 'all'])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ ($jenjangTab === 'all' || empty($jenjangTab)) ? 'bg-emerald-600 text-white shadow-theme-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Semua Jenjang
                </a>
                <a href="{{ route('admin.siswa.penempatanKelas', array_merge(request()->except('jenjang'), ['jenjang' => 'MTs'])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $jenjangTab === 'MTs' ? 'bg-blue-600 text-white shadow-theme-xs' : 'bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200' }}">
                    MTs (Kelas 7: VII-A, VII-B, dst)
                </a>
                <a href="{{ route('admin.siswa.penempatanKelas', array_merge(request()->except('jenjang'), ['jenjang' => 'MA'])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $jenjangTab === 'MA' ? 'bg-purple-600 text-white shadow-theme-xs' : 'bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200' }}">
                    MA (Kelas 10: X-A, X-B, dst)
                </a>
            </div>

            <!-- Filter Kanan: Search & Dropdowns -->
            <div class="flex flex-wrap items-center gap-2.5">
                <input type="hidden" name="jenjang" value="{{ $jenjangTab }}">

                <div class="relative">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari santri, no stambuk, no reg..." class="h-10 w-44 sm:w-56 pl-9 pr-3 text-xs bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <select name="gender" onchange="this.form.submit()" class="h-10 px-3 text-xs bg-white border border-gray-300 rounded-xl outline-none cursor-pointer">
                    <option value="">Semua Gender</option>
                    <option value="Laki-laki" {{ $genderFilter === 'Laki-laki' ? 'selected' : '' }}>Santri Putra</option>
                    <option value="Perempuan" {{ $genderFilter === 'Perempuan' ? 'selected' : '' }}>Santri Putri</option>
                </select>

                <select name="hunian" onchange="this.form.submit()" class="h-10 px-3 text-xs bg-white border border-gray-300 rounded-xl outline-none cursor-pointer font-medium text-gray-700">
                    <option value="all" {{ ($hunianFilter === 'all' || empty($hunianFilter)) ? 'selected' : '' }}>Semua Hunian (Mukim &amp; Laju)</option>
                    <option value="Mukim" {{ $hunianFilter === 'Mukim' ? 'selected' : '' }}>🏠 Khusus Santri Mukim (Pondok)</option>
                    <option value="Laju" {{ $hunianFilter === 'Laju' ? 'selected' : '' }}>🚶 Khusus Santri Laju (Non-Asrama)</option>
                </select>

                <select name="status_kelas" onchange="this.form.submit()" class="h-10 px-3 text-xs bg-white border border-gray-300 rounded-xl outline-none cursor-pointer">
                    <option value="all">Semua Status Kelas</option>
                    <option value="belum" {{ $statusKelas === 'belum' ? 'selected' : '' }}>Belum Masuk Kelas</option>
                    <option value="sudah" {{ $statusKelas === 'sudah' ? 'selected' : '' }}>Sudah Masuk Kelas</option>
                </select>

                @if(!empty($search) || !empty($genderFilter) || (!empty($hunianFilter) && $hunianFilter !== 'all') || (!empty($statusKelas) && $statusKelas !== 'all'))
                <a href="{{ route('admin.siswa.penempatanKelas', ['jenjang' => $jenjangTab]) }}" class="text-xs text-rose-600 hover:underline px-1">Reset</a>
                @endif
                <button type="submit" class="h-10 px-4 bg-gray-900 text-white rounded-xl text-xs font-semibold hover:bg-gray-800 transition">Cari</button>
            </div>
        </form>

        <!-- Bulk Action Bar (Aktif ketika ada santri yang dicentang) -->
        <div x-show="selectedStudents.length > 0" x-cloak class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs animate-fade-in">
            <div class="flex items-center gap-2 text-emerald-800 font-semibold">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span><strong x-text="selectedStudents.length"></strong> santri terpilih untuk diatur kelasnya secara massal (suka-suka admin).</span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="modalManual = true" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-theme-xs transition">
                    Pilih Kelas Tujuan &rarr;
                </button>
                <button type="button" @click="selectedStudents = []; selectAll = false;" class="text-gray-500 hover:text-gray-700 px-2">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Tabel Santri Baru & Penempatan Kelas -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-base font-bold text-gray-900">Daftar Santri Baru &amp; Alokasi Kelas / Kamar</h2>
                <p class="text-xs text-gray-500 mt-0.5">Urutan santri di bawah otomatis mencerminkan peringkat nilai CBT (dari skor tertinggi). Ubah kelas suka-suka admin langsung dari dropdown atau gunakan tombol bagi otomatis.</p>
            </div>
            <div class="text-xs text-gray-400">
                Menampilkan <strong>{{ $students->count() }}</strong> santri
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/75 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <th class="py-3.5 px-4 w-10 text-center">
                            <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        </th>
                        <th class="py-3.5 px-4 text-center">Peringkat CBT</th>
                        <th class="py-3.5 px-5">Identitas Santri</th>
                        <th class="py-3.5 px-4">Jenjang &amp; Status</th>
                        <th class="py-3.5 px-5">Rombel Kelas (Suka-suka Admin)</th>
                        <th class="py-3.5 px-5">Kamar Asrama (Santri Mukim)</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                    @forelse($students as $st)
                    @php
                        $isMts = str_contains($st->jenjang, 'MTs');
                        $isMa = str_contains($st->jenjang, 'MA');
                        $reg = $st->psbRegistration;
                        $nilai = $reg ? $reg->nilai_ujian : null;
                        $rank = $st->cbt_rank ?? '—';
                        $targetClassrooms = $isMts ? $classroomsMts : ($isMa ? $classroomsMa : $allClassrooms);
                        $targetDorms = $st->jenis_kelamin === 'Perempuan' ? $dormPutri : $dormPutra;
                    @endphp
                    <tr class="hover:bg-gray-50/90 transition" id="row-student-{{ $st->id }}">
                        <!-- Checkbox -->
                        <td class="py-3.5 px-4 text-center">
                            <input type="checkbox" value="{{ $st->id }}" x-model="selectedStudents" @change="updateSelectAllState()" class="student-checkbox rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        </td>

                        <!-- Peringkat CBT & Skor -->
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex flex-col items-center">
                                @if($rank !== '—' && (int)$rank <= 3)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold {{ (int)$rank === 1 ? 'bg-amber-100 text-amber-800 border border-amber-300 shadow-xs' : ((int)$rank === 2 ? 'bg-slate-200 text-slate-800' : 'bg-amber-50 text-amber-700') }}">
                                        🏆 #{{ $rank }}
                                    </span>
                                @elseif($rank !== '—')
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                        #{{ $rank }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif

                                @if($nilai !== null)
                                    <span class="text-[11px] font-bold mt-1 {{ $nilai >= 70 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $nilai }} / 100
                                    </span>
                                @else
                                    <span class="text-[10px] text-gray-400 mt-1">Belum CBT</span>
                                @endif
                            </div>
                        </td>

                        <!-- Identitas Santri -->
                        <td class="py-3.5 px-5">
                            <div class="flex items-center gap-3">
                                @if($st->foto)
                                    <img src="{{ $st->foto }}" alt="{{ $st->nama_lengkap }}" class="w-9 h-11 object-cover rounded-lg border border-gray-200 shrink-0">
                                @else
                                    <div class="w-9 h-11 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center font-bold text-gray-400 text-xs shrink-0">
                                        {{ substr($st->nama_lengkap, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="font-bold text-gray-900 block leading-tight">{{ $st->nama_lengkap }}</span>
                                    <span class="text-[11px] text-gray-500 font-mono block mt-0.5">
                                        NIS: {{ $st->nis }} · {{ $st->jenis_kelamin }}
                                    </span>
                                    @if($reg)
                                    <span class="text-[10px] text-brand-500 font-medium block">
                                        No Reg: {{ $reg->no_registrasi }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Jenjang & Status -->
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold {{ $isMa ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                {{ $st->jenjang }}
                            </span>
                            @if(str_contains($st->jenjang, 'Mukim'))
                                <span class="block text-[10px] text-emerald-600 font-semibold mt-1">● Santri Mukim (Pondok)</span>
                            @else
                                <span class="block text-[10px] text-gray-400 font-medium mt-1">○ Santri Laju (Pulang-Pergi)</span>
                            @endif
                        </td>

                        <!-- Penempatan Kelas (Dropdown Inline Suka-suka Admin) -->
                        <td class="py-3.5 px-5">
                            <div class="flex items-center gap-1.5">
                                <select id="select-kelas-{{ $st->id }}"
                                        @change="quickSaveStudent({{ $st->id }}, $event.target.value, document.getElementById('select-dorm-{{ $st->id }}')?.value || '')"
                                        class="h-9 rounded-xl border border-gray-300 bg-white px-3 text-xs font-semibold text-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition cursor-pointer">
                                    <option value="">-- Pilih Rombel Kelas --</option>
                                    @foreach($targetClassrooms as $tc)
                                    <option value="{{ $tc->nama_kelas }}" {{ $st->kelas === $tc->nama_kelas ? 'selected' : '' }}>
                                        {{ $tc->nama_kelas }} ({{ $tc->jenjang }} · Terisi {{ $tc->students_count }}/{{ $tc->kapasitas }})
                                    </option>
                                    @endforeach
                                    @if(!$targetClassrooms->pluck('nama_kelas')->contains($st->kelas) && !empty($st->kelas))
                                    <option value="{{ $st->kelas }}" selected>{{ $st->kelas }} (Kelas Lain)</option>
                                    @endif
                                </select>
                            </div>
                            @if(!empty($st->kelas) && !str_contains($st->kelas, 'Belum'))
                                <span class="text-[10px] text-emerald-600 font-semibold mt-0.5 block">✓ Aktif di {{ $st->kelas }}</span>
                            @else
                                <span class="text-[10px] text-amber-600 font-medium mt-0.5 block">⚠️ Belum ditentukan</span>
                            @endif
                        </td>

                        <!-- Penempatan Kamar Asrama -->
                        <td class="py-3.5 px-5">
                            @if(str_contains($st->jenjang, 'Mukim') && !str_contains($st->jenjang, 'Laju'))
                            <div class="flex items-center gap-1.5">
                                <select id="select-dorm-{{ $st->id }}"
                                        @change="quickSaveStudent({{ $st->id }}, document.getElementById('select-kelas-{{ $st->id }}').value, $event.target.value)"
                                        class="h-9 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-gray-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/10 transition cursor-pointer max-w-[200px] truncate">
                                    <option value="">-- Pilih Kamar Asrama --</option>
                                    @foreach($targetDorms as $td)
                                    <option value="{{ $td->id }}" {{ $st->dormitory_id == $td->id ? 'selected' : '' }}>
                                        {{ $td->nama_asrama }} - {{ $td->kamar }} (Sisa {{ max(0, $td->kapasitas - $td->students_count) }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @if($st->dormitory)
                                <span class="text-[10px] text-purple-600 font-semibold mt-0.5 block truncate max-w-[190px]">
                                    🏠 {{ $st->dormitory->full_name }}
                                </span>
                            @else
                                <span class="text-[10px] text-rose-500 font-medium mt-0.5 block">⚠️ Belum Ada Kamar</span>
                            @endif
                            @else
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 border border-gray-200 text-gray-600 text-xs font-semibold">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <span>Santri Laju (Non-Asrama)</span>
                            </div>
                            <span class="block text-[10px] text-gray-400 mt-0.5">Pulang-pergi (Tidak Memerlukan Kamar)</span>
                            @endif
                        </td>

                        <!-- Aksi Cepat -->
                        <td class="py-3.5 px-4 text-center">
                            <a href="{{ route('admin.siswa.show', $st->id) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded-lg transition" title="Lihat Profil Santri">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400 text-xs">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Tidak ada data santri baru yang cocok dengan kriteria filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL 1: PEMBAGIAN KELAS SESUAI PERINGKAT CBT -->
    <div x-show="modalRanking" x-cloak class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div @click.away="modalRanking = false" class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl border border-gray-100 max-h-[92vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Bagi Kelas Otomatis Sesuai Peringkat CBT</h3>
                        <p class="text-xs text-gray-500">Santri diurutkan berdasarkan skor CBT tertinggi lalu dialokasikan ke kelas tujuan.</p>
                    </div>
                </div>
                <button @click="modalRanking = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <form action="{{ route('admin.siswa.penempatanKelas.ranking') }}" method="POST" class="mt-5 space-y-4">
                @csrf

                <!-- Pilihan Jenjang Target -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">1. Pilih Jenjang Pendidikan <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition"
                               :class="selectedJenjangRanking === 'MTs' ? 'border-blue-500 bg-blue-50/50 text-blue-900 font-bold' : 'border-gray-200 bg-white text-gray-700'">
                            <input type="radio" name="jenjang" value="MTs" x-model="selectedJenjangRanking" class="text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="text-xs font-bold block">Tingkat MTs (Kelas 7)</span>
                                <span class="text-[11px] text-gray-500 font-normal">Santri baru MTs masuk rombel kelas VII</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition"
                               :class="selectedJenjangRanking === 'MA' ? 'border-purple-500 bg-purple-50/50 text-purple-900 font-bold' : 'border-gray-200 bg-white text-gray-700'">
                            <input type="radio" name="jenjang" value="MA" x-model="selectedJenjangRanking" class="text-purple-600 focus:ring-purple-500">
                            <div>
                                <span class="text-xs font-bold block">Tingkat MA (Kelas 10)</span>
                                <span class="text-[11px] text-gray-500 font-normal">Santri baru MA masuk rombel kelas X</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Pilihan Rombel Kelas Tujuan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">2. Pilih Rombel Kelas Tujuan <span class="text-rose-500">*</span></label>
                    <p class="text-[11px] text-gray-500 mb-2">Centang kelas yang akan diisi oleh santri baru:</p>

                    <!-- Checklist Kelas MTs -->
                    <div x-show="selectedJenjangRanking === 'MTs'" class="space-y-2 border border-gray-200 p-3 rounded-xl max-h-40 overflow-y-auto">
                        @foreach($classroomsMts as $cm)
                        <label class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 cursor-pointer text-xs">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="target_classes[]" value="{{ $cm->nama_kelas }}" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="font-bold text-gray-800">{{ $cm->nama_kelas }}</span>
                                <span class="text-gray-400">({{ $cm->wali_kelas ?: 'Wali kelas belum ditentukan' }})</span>
                            </div>
                            <span class="text-[11px] text-gray-500">Kapasitas: {{ $cm->students_count }}/{{ $cm->kapasitas }} Kursi</span>
                        </label>
                        @endforeach
                    </div>

                    <!-- Checklist Kelas MA -->
                    <div x-show="selectedJenjangRanking === 'MA'" class="space-y-2 border border-gray-200 p-3 rounded-xl max-h-40 overflow-y-auto">
                        @foreach($classroomsMa as $ca)
                        <label class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 cursor-pointer text-xs">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="target_classes[]" value="{{ $ca->nama_kelas }}" checked class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <span class="font-bold text-gray-800">{{ $ca->nama_kelas }}</span>
                                <span class="text-gray-400">({{ $ca->wali_kelas ?: 'Wali kelas belum ditentukan' }})</span>
                            </div>
                            <span class="text-[11px] text-gray-500">Kapasitas: {{ $ca->students_count }}/{{ $ca->kapasitas }} Kursi</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Metode Pembagian Peringkat -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">3. Metode Pembagian Peringkat <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="p-3 border rounded-xl cursor-pointer transition flex items-start gap-2"
                               :class="modeRanking === 'seimbang' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-900' : 'border-gray-200 bg-white'">
                            <input type="radio" name="mode" value="seimbang" x-model="modeRanking" class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <span class="text-xs font-bold block">Paralel Seimbang (Snake)</span>
                                <span class="text-[11px] text-gray-500 leading-tight block mt-0.5">Kemampuan akademik seimbang antar kelas (1->A, 2->B, 3->B, 4->A).</span>
                            </div>
                        </label>

                        <label class="p-3 border rounded-xl cursor-pointer transition flex items-start gap-2"
                               :class="modeRanking === 'unggulan' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-900' : 'border-gray-200 bg-white'">
                            <input type="radio" name="mode" value="unggulan" x-model="modeRanking" class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <span class="text-xs font-bold block">Ranking Unggulan (Top-down)</span>
                                <span class="text-[11px] text-gray-500 leading-tight block mt-0.5">Peringkat 1 s/d kuota masuk ke kelas prioritas pertama, sisanya ke kelas kedua.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Filter Gender (Opsional) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Peruntukan Gender</label>
                        <select name="gender_filter" class="h-10 w-full rounded-xl border border-gray-300 bg-white px-3 text-xs text-gray-800">
                            <option value="all">Semua Gender (Putra &amp; Putri)</option>
                            <option value="Laki-laki">Khusus Santri Putra</option>
                            <option value="Perempuan">Khusus Santri Putri</option>
                        </select>
                    </div>

                    <div class="flex items-center pt-5">
                        <label class="flex items-center gap-2 cursor-pointer text-xs text-gray-700 font-semibold">
                            <input type="checkbox" name="auto_asrama" value="1" checked class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span>Sekaligus Plotting Kamar Asrama</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalRanking = false" class="px-4 py-2 rounded-xl border border-gray-300 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                        Jalankan Pembagian Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: PENEMPATAN MASSAL SUKA-SUKA ADMIN -->
    <div x-show="modalManual" x-cloak class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div @click.away="modalManual = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-gray-100">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Atur Kelas Santri Terpilih</h3>
                    <p class="text-xs text-emerald-600 font-semibold" x-text="selectedStudents.length + ' santri baru dipilih'"></p>
                </div>
                <button @click="modalManual = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <form action="{{ route('admin.siswa.penempatanKelas.manual') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <template x-for="id in selectedStudents" :key="id">
                    <input type="hidden" name="student_ids[]" :value="id">
                </template>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilih Rombel Kelas Tujuan <span class="text-rose-500">*</span></label>
                    <select name="kelas" required class="h-10 w-full rounded-xl border border-gray-300 bg-white px-3 text-xs text-gray-800">
                        <option value="">-- Pilih Kelas Tujuan --</option>
                        <optgroup label="Tingkat VII (MTs)">
                            @foreach($classroomsMts as $cm)
                            <option value="{{ $cm->nama_kelas }}">{{ $cm->nama_kelas }} (Terisi {{ $cm->students_count }}/{{ $cm->kapasitas }})</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Tingkat X (MA)">
                            @foreach($classroomsMa as $ca)
                            <option value="{{ $ca->nama_kelas }}">{{ $ca->nama_kelas }} (Terisi {{ $ca->students_count }}/{{ $ca->kapasitas }})</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Kelas Lainnya">
                            @foreach($allClassrooms as $ac)
                            <option value="{{ $ac->nama_kelas }}">{{ $ac->nama_kelas }} ({{ $ac->jenjang }})</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilih Kamar Asrama (Khusus Santri Mukim)</label>
                    <select name="dormitory_id" class="h-10 w-full rounded-xl border border-gray-300 bg-white px-3 text-xs text-gray-800">
                        <option value="">-- Jangan Ubah Kamar Asrama --</option>
                        <option value="none">-- Kosongkan Kamar Asrama --</option>
                        <optgroup label="Asrama Putra">
                            @foreach($dormPutra as $dp)
                            <option value="{{ $dp->id }}">{{ $dp->nama_asrama }} - {{ $dp->kamar }} (Sisa {{ max(0, $dp->kapasitas - $dp->students_count) }})</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Asrama Putri">
                            @foreach($dormPutri as $dp)
                            <option value="{{ $dp->id }}">{{ $dp->nama_asrama }} - {{ $dp->kamar }} (Sisa {{ max(0, $dp->kapasitas - $dp->students_count) }})</option>
                            @endforeach
                        </optgroup>
                    </select>
                    <div class="mt-2 p-2.5 rounded-xl bg-blue-50 border border-blue-200 text-[11px] text-blue-800 leading-tight">
                        ℹ️ <strong>Santri Laju Otomatis Non-Asrama:</strong> Santri baru yang berstatus <em>Laju (Pulang-Pergi)</em> otomatis tidak akan dimasukkan ke kamar asrama meskipun opsi asrama dipilih.
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalManual = false" class="px-4 py-2 rounded-xl border border-gray-300 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                        Terapkan Penempatan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

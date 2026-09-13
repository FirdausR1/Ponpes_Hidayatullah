@extends('admin.layout')

@section('title', 'Rekap Hasil Ujian & Kelulusan CBT')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Rekap Hasil Ujian CBT</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau skor ujian masuk santri, log pengawasan sistem, dan penetapan status kelulusan dewan penguji.</p>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('admin.cbt.dongkrak.index') }}" class="ta-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Dongkrak Nilai Santri
            </a>
            <a href="{{ route('admin.cbt.cetakNilai') }}" target="_blank" class="ta-btn-outline">
                <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Rekap Nilai
            </a>
            <a href="{{ route('admin.cbt.pengaturan') }}" class="ta-btn-outline">
                <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                Atur Waktu &amp; KKM
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Santri</span>
                <span class="badge-gray">Terdaftar</span>
            </div>
            <div class="text-2xl font-bold text-gray-800 mt-2">{{ $totalParticipants }}</div>
        </div>

        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Selesai Ujian</span>
                <span class="badge-success">Tersubmit</span>
            </div>
            <div class="text-2xl font-bold text-emerald-600 mt-2">{{ $totalFinished }}</div>
        </div>

        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Memenuhi KKM</span>
                <span class="badge-primary">&ge; {{ $kkm }}</span>
            </div>
            <div class="text-2xl font-bold text-brand-500 mt-2">{{ $totalPassed }}</div>
        </div>

        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggaran Contek</span>
                <span class="{{ $totalViolations > 0 ? 'badge-error' : 'badge-gray' }}">Insiden</span>
            </div>
            <div class="text-2xl font-bold {{ $totalViolations > 0 ? 'text-red-600' : 'text-gray-800' }} mt-2">{{ $totalViolations }}</div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="ta-card p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.cbt.hasil.index') }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ empty($statusFilter) ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua Status
            </a>
            <a href="{{ route('admin.cbt.hasil.index', ['status' => 'Selesai']) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $statusFilter === 'Selesai' ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Selesai Ujian
            </a>
            <a href="{{ route('admin.cbt.hasil.index', ['status' => 'Sedang Ujian']) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $statusFilter === 'Sedang Ujian' ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Sedang Mengerjakan
            </a>
            <a href="{{ route('admin.cbt.hasil.index', ['status' => 'Belum Ujian']) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $statusFilter === 'Belum Ujian' ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Belum Ujian
            </a>
        </div>

        <form method="GET" action="{{ route('admin.cbt.hasil.index') }}" class="flex items-center gap-2">
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
            @if(!empty($search) || !empty($statusFilter))
                <a href="{{ route('admin.cbt.hasil.index') }}" class="ta-btn-sm-outline">Reset</a>
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
                            <td class="text-xs font-medium text-gray-700">
                                {{ $reg->jenjang }}
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
                                        {{ $reg->pelanggaran_curang_count }} Pelanggaran
                                    </button>
                                @else
                                    <span class="badge-gray">
                                        Bersih (0)
                                    </span>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <button type="button" onclick="openOverrideModal({{ json_encode($reg) }})"
                                        class="ta-btn-sm-outline">
                                    Ubah Nilai
                                </button>
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
</script>
@endsection

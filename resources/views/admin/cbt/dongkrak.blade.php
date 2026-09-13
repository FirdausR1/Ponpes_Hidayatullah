@extends('admin.layout')

@section('title', 'Dongkrak Nilai Santri')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dongkrak Nilai Santri</h1>
            <p class="text-sm text-gray-500 mt-1">
                Fasilitas dewan penguji untuk menaikkan nilai ujian santri secara cepat maupun massal agar memenuhi standar kelulusan KKM ({{ $kkm }}).
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.cbt.hasil.index') }}" class="ta-btn-sm-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Rekap Hasil Ujian
            </a>
            <a href="{{ route('admin.cbt.pengaturan') }}" class="ta-btn-sm-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                Pengaturan KKM & Waktu
            </a>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="ta-alert ta-alert-info">
        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="text-xs leading-relaxed">
            <span class="font-bold text-gray-800">Petunjuk Dewan Penguji:</span>
            Pilih santri yang nilainya belum mencapai KKM ({{ $kkm }}). Anda dapat menggunakan tombol <strong>Set KKM</strong> untuk langsung meluluskan dengan nilai {{ $kkm }}, atau menambahkan afirmasi poin (+5, +10, +15). Perubahan langsung tersimpan ke portal pengumuman santri.
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Santri</span>
                <span class="badge-gray">Terdata</span>
            </div>
            <div class="text-2xl font-bold text-gray-800 mt-2">{{ $totalStudents }}</div>
        </div>

        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Di Bawah KKM</span>
                <span class="badge-error">Perlu Dongkrak</span>
            </div>
            <div class="text-2xl font-bold text-red-600 mt-2">{{ $totalBelumLulus }}</div>
        </div>

        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sudah Lulus</span>
                <span class="badge-success">Memenuhi</span>
            </div>
            <div class="text-2xl font-bold text-emerald-600 mt-2">{{ $totalLulus }}</div>
        </div>

        <div class="ta-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Standar KKM</span>
                <span class="badge-primary">Minimal</span>
            </div>
            <div class="text-2xl font-bold text-brand-500 mt-2">{{ $kkm }} <span class="text-xs font-normal text-gray-400">Poin</span></div>
        </div>
    </div>

    <!-- Filter Bar & Bulk Actions -->
    <div class="ta-card p-4 space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <!-- Tabs Filter -->
            <div class="flex items-center gap-1.5 p-1 bg-gray-100 rounded-lg text-xs font-medium">
                <a href="{{ route('admin.cbt.dongkrak.index', ['filter' => 'belum_lulus', 'q' => $search]) }}"
                   class="px-3 py-1.5 rounded-md transition {{ $filter === 'belum_lulus' ? 'bg-white text-red-600 font-semibold shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    Di Bawah KKM ({{ $totalBelumLulus }})
                </a>
                <a href="{{ route('admin.cbt.dongkrak.index', ['filter' => 'semua', 'q' => $search]) }}"
                   class="px-3 py-1.5 rounded-md transition {{ $filter === 'semua' ? 'bg-white text-gray-900 font-semibold shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    Semua Santri ({{ $totalStudents }})
                </a>
                <a href="{{ route('admin.cbt.dongkrak.index', ['filter' => 'lulus', 'q' => $search]) }}"
                   class="px-3 py-1.5 rounded-md transition {{ $filter === 'lulus' ? 'bg-white text-emerald-600 font-semibold shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    Sudah Lulus ({{ $totalLulus }})
                </a>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.cbt.dongkrak.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <div class="relative">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari santri, no reg, NISN..."
                           class="ta-input w-56 sm:w-64 pl-8 py-1.5 text-xs">
                    <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button type="submit" class="ta-btn-sm-primary">
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.cbt.dongkrak.index', ['filter' => $filter]) }}" class="ta-btn-sm-outline">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Bulk Action Buttons -->
        <div class="pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-2.5">
            <div class="flex items-center gap-2">
                <span class="text-xs font-medium text-gray-600">Aksi Massal:</span>
                <button type="button" onclick="openBulkSelectedModal()" class="ta-btn-sm-primary">
                    Dongkrak Santri Dicentang (<span id="selectedCount">0</span>)
                </button>
            </div>

            <button type="button" onclick="openBulkAllModal()" class="ta-btn-sm-outline">
                Dongkrak Semua di Bawah KKM ({{ $totalBelumLulus }})
            </button>
        </div>
    </div>

    <!-- Student Table -->
    <div class="ta-card overflow-hidden">
        <form id="bulkForm" action="{{ route('admin.cbt.hasil.bulkDongkrak') }}" method="POST">
            @csrf
            <input type="hidden" name="mode" id="bulkMode" value="selected">
            <input type="hidden" name="target_score" id="bulkTargetScore" value="{{ $kkm }}">
            <input type="hidden" name="alasan_afirmasi" id="bulkAlasan" value="Afirmasi dewan penguji agar memenuhi standar kelulusan KKM">

            <div class="overflow-x-auto">
                <table class="ta-table">
                    <thead>
                        <tr>
                            <th class="w-10 text-center">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"
                                       class="w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-400 cursor-pointer">
                            </th>
                            <th>Santri / No. Registrasi</th>
                            <th>Jenjang & Gelombang</th>
                            <th class="text-center">Nilai Ujian</th>
                            <th class="text-center">Status Kelulusan</th>
                            <th>Catatan Penguji</th>
                            <th class="text-right whitespace-nowrap">Aksi Penyesuaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        @php
                            $score = $student->nilai_ujian;
                            $status = $student->status_kelulusan_override ?: ($score !== null ? ($score >= $kkm ? 'Lulus' : 'Tidak Lulus') : 'Belum Ujian');
                            $isPassed = ($status === 'Lulus');
                        @endphp
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                       onchange="updateSelectedCount()"
                                       class="student-checkbox w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-400 cursor-pointer">
                            </td>

                            <td>
                                <div class="font-semibold text-gray-800 text-sm">{{ $student->nama_lengkap }}</div>
                                <div class="text-[11px] text-gray-400 font-mono mt-0.5 flex items-center gap-2">
                                    <span>{{ $student->no_registrasi }}</span>
                                    @if($student->nisn)
                                        <span>• NISN: {{ $student->nisn }}</span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <div class="font-medium text-gray-700 text-xs">{{ $student->jenjang ?? 'Pondok Pesantren' }}</div>
                                <div class="text-[11px] text-gray-400">{{ $student->gelombang ?? 'Gelombang 1' }}</div>
                            </td>

                            <td class="text-center">
                                @if($score !== null)
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-sm font-bold {{ $score >= $kkm ? 'text-emerald-600' : 'text-red-600' }}">
                                            {{ $score }}
                                        </span>
                                        <span class="text-[10px] text-gray-400">/ 100</span>
                                    </div>
                                @else
                                    <span class="badge-gray">
                                        Belum Ujian
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($status === 'Lulus')
                                    <span class="badge-success">
                                        Lulus
                                    </span>
                                @elseif($status === 'Tidak Lulus')
                                    <span class="badge-error">
                                        Tidak Lulus
                                    </span>
                                @else
                                    <span class="badge-gray">
                                        {{ $status }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($student->catatan_penguji)
                                    <p class="text-[11px] text-gray-600 italic line-clamp-2 max-w-xs" title="{{ $student->catatan_penguji }}">
                                        {{ $student->catatan_penguji }}
                                    </p>
                                @else
                                    <span class="text-[11px] text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5 flex-nowrap">
                                    <!-- Quick: Set KKM -->
                                    <button type="button"
                                            onclick="quickDongkrak({{ $student->id }}, '{{ addslashes($student->nama_lengkap) }}', {{ $kkm }})"
                                            class="ta-btn-sm-success"
                                            title="Jadikan nilai {{ $kkm }} dan Lulus">
                                        Set KKM ({{ $kkm }})
                                    </button>

                                    <!-- Quick: +10 Poin -->
                                    <button type="button"
                                            onclick="quickTambahPoin({{ $student->id }}, '{{ addslashes($student->nama_lengkap) }}', 10, {{ $score ?? 0 }})"
                                            class="ta-btn-sm-primary"
                                            title="Tambah +10 Poin">
                                        +10 Poin
                                    </button>

                                    <!-- Custom / Detail Modal -->
                                    <button type="button"
                                            onclick="openManualModal({{ $student->id }}, '{{ addslashes($student->nama_lengkap) }}', '{{ $student->no_registrasi }}', {{ $score ?? 'null' }}, '{{ $status }}', '{{ addslashes($student->catatan_penguji ?? '') }}')"
                                            class="ta-btn-sm-outline"
                                            title="Ubah manual">
                                        Ubah
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400 text-sm">
                                Tidak ada santri yang ditemukan pada kriteria filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @if($students->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            {{ $students->links() }}
        </div>
        @endif
    </div>

</div>

<!-- MODAL: Dongkrak Satuan / Manual -->
<div id="singleModal" class="ta-modal-backdrop hidden">
    <div class="ta-modal">
        <div class="ta-modal-header">
            <div>
                <h3 class="font-bold text-gray-800 text-base" id="modalStudentName">Penyesuaian Nilai Santri</h3>
                <p class="text-xs text-gray-400 font-mono mt-0.5" id="modalStudentNoReg"></p>
            </div>
            <button type="button" onclick="closeSingleModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <form id="singleForm" method="POST" action="">
            @csrf
            <div class="ta-modal-body space-y-4">
                <!-- Preset Poin Cepat -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Preset Nilai Cepat</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="applySinglePreset({{ $kkm }}, 'Lulus')" class="ta-btn-sm-outline">
                            KKM ({{ $kkm }})
                        </button>
                        <button type="button" onclick="applySinglePreset(75, 'Lulus')" class="ta-btn-sm-outline">
                            Nilai 75
                        </button>
                        <button type="button" onclick="applySinglePreset(80, 'Lulus')" class="ta-btn-sm-outline">
                            Nilai 80
                        </button>
                    </div>
                    <div class="grid grid-cols-4 gap-1.5 mt-2">
                        <button type="button" onclick="addSinglePoints(5)" class="ta-btn-sm-secondary">+5 Poin</button>
                        <button type="button" onclick="addSinglePoints(10)" class="ta-btn-sm-secondary">+10 Poin</button>
                        <button type="button" onclick="addSinglePoints(15)" class="ta-btn-sm-secondary">+15 Poin</button>
                        <button type="button" onclick="addSinglePoints(20)" class="ta-btn-sm-secondary">+20 Poin</button>
                    </div>
                </div>

                <!-- Input Nilai Baru -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nilai Ujian (0 - 100)</label>
                    <input type="number" name="nilai_ujian" id="modalInputScore" min="0" max="100" required
                           class="ta-input font-bold text-gray-800">
                </div>

                <!-- Status Kelulusan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Status Kelulusan</label>
                    <select name="status_kelulusan_override" id="modalInputStatus" class="ta-input font-medium">
                        <option value="Lulus">Lulus</option>
                        <option value="Tidak Lulus">Tidak Lulus</option>
                        <option value="Lulus Bersyarat">Lulus Bersyarat</option>
                        <option value="Cadangan">Cadangan</option>
                    </select>
                </div>

                <!-- Catatan Afirmasi -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Dewan Penguji</label>
                    <textarea name="catatan_penguji" id="modalInputNotes" rows="2"
                              class="ta-input"
                              placeholder="Contoh: Didongkrak memenuhi KKM berdasarkan evaluasi hafalan dan adab."></textarea>
                </div>
            </div>

            <div class="ta-modal-footer">
                <button type="button" onclick="closeSingleModal()" class="ta-btn-sm-outline">
                    Batal
                </button>
                <button type="submit" class="ta-btn-sm-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Bulk Dongkrak Massal -->
<div id="bulkModal" class="ta-modal-backdrop hidden">
    <div class="ta-modal">
        <div class="ta-modal-header">
            <div>
                <h3 class="font-bold text-gray-800 text-base" id="bulkModalTitle">Konfirmasi Dongkrak Massal</h3>
                <p class="text-xs text-gray-500 mt-0.5" id="bulkModalDesc">Terapkan penyesuaian nilai serentak untuk santri.</p>
            </div>
            <button type="button" onclick="closeBulkModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <div class="ta-modal-body space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Target Nilai Baru</label>
                <div class="flex items-center gap-2">
                    <input type="number" id="modalBulkTargetInput" value="{{ $kkm }}" min="0" max="100"
                           class="ta-input font-bold text-emerald-600">
                    <span class="text-xs text-gray-500 whitespace-nowrap">Minimal KKM: {{ $kkm }}</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Afirmasi Panitia</label>
                <textarea id="modalBulkAlasanInput" rows="2"
                          class="ta-input">Afirmasi dewan penguji seleksi PSB agar santri memenuhi standar kelulusan KKM.</textarea>
            </div>

            <div class="ta-alert ta-alert-warning text-xs">
                <div>
                    Seluruh santri yang diproses akan diubah status kelulusannya menjadi <strong>LULUS</strong> dengan nilai ujian disesuaikan ke target di atas.
                </div>
            </div>
        </div>

        <div class="ta-modal-footer">
            <button type="button" onclick="closeBulkModal()" class="ta-btn-sm-outline">
                Batal
            </button>
            <button type="button" onclick="submitBulkForm()" class="ta-btn-sm-primary">
                Terapkan Penyesuaian Nilai
            </button>
        </div>
    </div>
</div>

<script>
    const KKM = {{ $kkm }};

    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.student-checkbox:checked').length;
        document.getElementById('selectedCount').innerText = checked;
    }

    // 1-Click Quick Dongkrak ke KKM
    function quickDongkrak(id, name, score) {
        if (!confirm(`Apakah Anda yakin ingin langsung mendongkrak nilai ${name} menjadi ${score} dan menyatakan LULUS?`)) {
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/cbt/hasil/${id}`;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        const inputDongkrak = document.createElement('input');
        inputDongkrak.type = 'hidden';
        inputDongkrak.name = 'dongkrak_kkm';
        inputDongkrak.value = '1';
        form.appendChild(inputDongkrak);

        const inputScore = document.createElement('input');
        inputScore.type = 'hidden';
        inputScore.name = 'nilai_ujian';
        inputScore.value = score;
        form.appendChild(inputScore);

        document.body.appendChild(form);
        form.submit();
    }

    // Quick Tambah Poin
    function quickTambahPoin(id, name, poin, currentScore) {
        if (!confirm(`Tambahkan +${poin} poin afirmasi untuk ${name}? (Nilai saat ini: ${currentScore})`)) {
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/cbt/hasil/${id}`;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        const inputTambah = document.createElement('input');
        inputTambah.type = 'hidden';
        inputTambah.name = 'tambah_poin';
        inputTambah.value = poin;
        form.appendChild(inputTambah);

        document.body.appendChild(form);
        form.submit();
    }

    // Open Manual Modal
    function openManualModal(id, name, regNo, score, status, notes) {
        document.getElementById('modalStudentName').innerText = name;
        document.getElementById('modalStudentNoReg').innerText = regNo;
        document.getElementById('modalInputScore').value = score !== null ? score : KKM;
        document.getElementById('modalInputStatus').value = status !== 'Belum Ujian' ? status : 'Lulus';
        document.getElementById('modalInputNotes').value = notes || 'Afirmasi dewan penguji seleksi.';
        document.getElementById('singleForm').action = `/admin/cbt/hasil/${id}`;
        document.getElementById('singleModal').classList.remove('hidden');
    }

    function closeSingleModal() {
        document.getElementById('singleModal').classList.add('hidden');
    }

    function applySinglePreset(score, status) {
        document.getElementById('modalInputScore').value = score;
        document.getElementById('modalInputStatus').value = status;
        document.getElementById('modalInputNotes').value = `Nilai disesuaikan menjadi ${score} agar memenuhi standar kelulusan KKM.`;
    }

    function addSinglePoints(points) {
        const cur = parseInt(document.getElementById('modalInputScore').value) || 0;
        const newScore = Math.min(100, cur + points);
        document.getElementById('modalInputScore').value = newScore;
        if (newScore >= KKM) {
            document.getElementById('modalInputStatus').value = 'Lulus';
        }
    }

    // Bulk Modals
    function openBulkSelectedModal() {
        const checked = document.querySelectorAll('.student-checkbox:checked').length;
        if (checked === 0) {
            alert('Pilih minimal satu santri yang ingin didongkrak nilainya.');
            return;
        }
        document.getElementById('bulkMode').value = 'selected';
        document.getElementById('bulkModalTitle').innerText = `Dongkrak ${checked} Santri Terpilih`;
        document.getElementById('bulkModalDesc').innerText = `Nilai seluruh ${checked} santri yang dicentang akan disesuaikan menjadi target di bawah ini.`;
        document.getElementById('bulkModal').classList.remove('hidden');
    }

    function openBulkAllModal() {
        document.getElementById('bulkMode').value = 'all_failing';
        document.getElementById('bulkModalTitle').innerText = `Dongkrak Semua Santri di Bawah KKM`;
        document.getElementById('bulkModalDesc').innerText = `Semua santri yang saat ini berstatus belum lulus / nilai di bawah KKM akan langsung dinaikkan statusnya.`;
        document.getElementById('bulkModal').classList.remove('hidden');
    }

    function closeBulkModal() {
        document.getElementById('bulkModal').classList.add('hidden');
    }

    function submitBulkForm() {
        const target = document.getElementById('modalBulkTargetInput').value;
        const note = document.getElementById('modalBulkAlasanInput').value;
        document.getElementById('bulkTargetScore').value = target;
        document.getElementById('bulkAlasan').value = note;
        document.getElementById('bulkForm').submit();
    }
</script>
@endsection

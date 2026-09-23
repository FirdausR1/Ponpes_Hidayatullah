@extends('admin.layout')

@section('title', 'Live Monitoring Ujian & Anti-Cheat: ' . $exam->mata_pelajaran)

@section('content')
<div class="space-y-6" id="monitoringApp">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.cbt.madrasah.show', $exam->id) }}" class="text-xs text-emerald-700 hover:underline font-semibold flex items-center gap-1">
                    &larr; Kembali ke Kelola Nilai
                </a>
                <span class="text-gray-300">•</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-ping"></span>
                    Live Monitor Aktif
                </span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800">
                    {{ $exam->jurusan }}
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-snug flex items-center gap-2">
                Live Monitoring Ujian: {{ $exam->mata_pelajaran }}
            </h1>
            <p class="text-xs text-gray-500 font-mono mt-0.5">
                {{ $exam->nama_ujian }} &bull; Standar Anti-Cheat CBT Indonesia Kurikulum Merdeka (Toleransi Max: <strong class="text-rose-600">{{ $exam->max_violations ?? 3 }}x</strong>)
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if($exam->token_ujian)
            <div class="px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-xs">
                <span class="text-amber-700 text-[10px] font-semibold block uppercase">Token Sesi</span>
                <span class="font-mono font-bold text-amber-900 tracking-wider text-sm">{{ $exam->token_ujian }}</span>
            </div>
            @endif

            <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs shadow-xs">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-gray-600 font-medium">Auto-Refresh: <strong id="refreshCountdown" class="font-mono text-emerald-700">5</strong>s</span>
                <button type="button" onclick="fetchMonitoringData(true)" class="p-1 hover:bg-gray-100 rounded text-gray-500 transition" title="Refresh Sekarang">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Live Counter Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="p-3.5 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Total Peserta</span>
            <span class="text-2xl font-bold font-mono text-gray-900 mt-1 block" id="statTotal">-</span>
            <span class="text-[10px] text-gray-400">Terdaftar</span>
        </div>
        <div class="p-3.5 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Belum Mulai</span>
            <span class="text-2xl font-bold font-mono text-gray-600 mt-1 block" id="statBelum">-</span>
            <span class="text-[10px] text-gray-400">Menunggu</span>
        </div>
        <div class="p-3.5 bg-white border border-blue-200 rounded-2xl shadow-theme-xs bg-blue-50/20">
            <span class="text-[11px] font-semibold text-blue-600 uppercase tracking-wider block flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-blue-600 animate-ping"></span>
                Mengerjakan
            </span>
            <span class="text-2xl font-bold font-mono text-blue-700 mt-1 block" id="statMengerjakan">-</span>
            <span class="text-[10px] text-blue-500">Sedang Ujian</span>
        </div>
        <div class="p-3.5 bg-white border border-emerald-200 rounded-2xl shadow-theme-xs bg-emerald-50/20">
            <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider block">Selesai</span>
            <span class="text-2xl font-bold font-mono text-emerald-700 mt-1 block" id="statSelesai">-</span>
            <span class="text-[10px] text-emerald-600/70">Telah Mengumpulkan</span>
        </div>
        <div class="p-3.5 bg-white border border-rose-200 rounded-2xl shadow-theme-xs bg-rose-50/30">
            <span class="text-[11px] font-semibold text-rose-600 uppercase tracking-wider block flex items-center gap-1">
                🔒 Terkunci
            </span>
            <span class="text-2xl font-bold font-mono text-rose-700 mt-1 block" id="statTerkunci">-</span>
            <span class="text-[10px] text-rose-600/70">Perlu Buka Kunci</span>
        </div>
        <div class="p-3.5 bg-white border border-amber-200 rounded-2xl shadow-theme-xs bg-amber-50/20">
            <span class="text-[11px] font-semibold text-amber-600 uppercase tracking-wider block">Pelanggaran</span>
            <span class="text-2xl font-bold font-mono text-amber-700 mt-1 block" id="statMelanggar">-</span>
            <span class="text-[10px] text-amber-600/70">Siswa Terdeteksi</span>
        </div>
    </div>

    <!-- Filter & Kontrol Pencarian -->
    <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-theme-xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
            <span class="text-xs font-bold text-gray-500 mr-1">Filter Status:</span>
            <button type="button" onclick="setFilter('ALL')" class="btn-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-900 text-white transition cursor-pointer" data-filter="ALL">
                Semua (<span id="countFilterAll">0</span>)
            </button>
            <button type="button" onclick="setFilter('Mengerjakan')" class="btn-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-700 hover:bg-blue-100 hover:text-blue-800 transition cursor-pointer" data-filter="Mengerjakan">
                Mengerjakan (<span id="countFilterMengerjakan">0</span>)
            </button>
            <button type="button" onclick="setFilter('Terkunci')" class="btn-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-700 hover:bg-rose-100 hover:text-rose-800 transition cursor-pointer" data-filter="Terkunci">
                🔒 Terkunci (<span id="countFilterTerkunci">0</span>)
            </button>
            <button type="button" onclick="setFilter('Melanggar')" class="btn-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-700 hover:bg-amber-100 hover:text-amber-800 transition cursor-pointer" data-filter="Melanggar">
                ⚠️ Ada Pelanggaran (<span id="countFilterMelanggar">0</span>)
            </button>
            <button type="button" onclick="setFilter('Selesai')" class="btn-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-700 hover:bg-emerald-100 hover:text-emerald-800 transition cursor-pointer" data-filter="Selesai">
                Selesai (<span id="countFilterSelesai">0</span>)
            </button>
        </div>

        <div class="w-full sm:w-64">
            <div class="relative">
                <input type="text" id="searchCandidate" oninput="renderTable()" placeholder="Cari nama atau NIS siswa..." class="ta-input text-xs pl-8 py-2">
                <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            </div>
        </div>
    </div>

    <!-- Tabel Live Monitoring Siswa -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-theme-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-600 font-bold uppercase tracking-wider">
                        <th class="p-3.5 w-12 text-center">No</th>
                        <th class="p-3.5 min-w-[200px]">Peserta Ujian</th>
                        <th class="p-3.5 w-24 text-center">Kelas / Jurusan</th>
                        <th class="p-3.5 w-28 text-center">Status Ujian</th>
                        <th class="p-3.5 w-36 text-center">Kemajuan Jawaban</th>
                        <th class="p-3.5 w-28 text-center">Sisa Waktu</th>
                        <th class="p-3.5 w-36 text-center">Pelanggaran Anti-Cheat</th>
                        <th class="p-3.5 w-24 text-center">Nilai</th>
                        <th class="p-3.5 w-48 text-center">Aksi Pengawas</th>
                    </tr>
                </thead>
                <tbody id="candidatesTableBody" class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="9" class="p-12 text-center text-gray-400 text-xs">
                            <div class="inline-flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                                Memuat data real-time peserta ujian...
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Log Pelanggaran Anti-Cheat -->
<div id="violationModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 animate-scale-up">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-1.5">
                    <span class="text-rose-600">⚠️</span> Log Pelanggaran Anti-Cheat
                </h3>
                <p id="violationStudentName" class="text-xs text-gray-500 font-mono mt-0.5">-</p>
            </div>
            <button type="button" onclick="closeViolationModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <div class="max-h-80 overflow-y-auto space-y-2 pr-1" id="violationListContainer">
            <!-- Dynamically populated -->
        </div>

        <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
            <span class="text-[11px] text-gray-400">Aturan: Toleransi max <strong id="modalMaxViolations">3</strong>x</span>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeViolationModal()" class="ta-btn-secondary text-xs">Tutup</button>
                <button type="button" id="btnModalResetViolation" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded-lg text-xs font-bold transition">
                    Reset Pelanggaran
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const examId = {{ $exam->id }};
    const maxViolationsExam = {{ $exam->max_violations ?? 3 }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

    let candidatesData = [];
    let currentFilter = 'ALL';
    let countdownSec = 5;
    let refreshInterval = null;

    function setFilter(filter) {
        currentFilter = filter;
        document.querySelectorAll('.btn-filter').forEach(btn => {
            if (btn.getAttribute('data-filter') === filter) {
                btn.className = 'btn-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-900 text-white transition cursor-pointer';
            } else {
                btn.className = 'btn-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition cursor-pointer';
            }
        });
        renderTable();
    }

    function formatTime(seconds) {
        if (!seconds || seconds <= 0) return '00:00';
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    }

    function fetchMonitoringData(immediate = false) {
        fetch(`{{ url('admin/cbt/madrasah') }}/${examId}/monitoring-data`)
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    candidatesData = res.results || [];
                    updateSummaryCounters(res.summary);
                    renderTable();
                }
            })
            .catch(err => console.error('Error fetching live monitoring data:', err));

        countdownSec = 5;
    }

    function updateSummaryCounters(s) {
        if (!s) return;
        document.getElementById('statTotal').innerText = s.total || 0;
        document.getElementById('statBelum').innerText = s.belum_mulai || 0;
        document.getElementById('statMengerjakan').innerText = s.mengerjakan || 0;
        document.getElementById('statSelesai').innerText = s.selesai || 0;
        document.getElementById('statTerkunci').innerText = s.terkunci || 0;
        document.getElementById('statMelanggar').innerText = s.melanggar || 0;

        document.getElementById('countFilterAll').innerText = s.total || 0;
        document.getElementById('countFilterMengerjakan').innerText = s.mengerjakan || 0;
        document.getElementById('countFilterTerkunci').innerText = s.terkunci || 0;
        document.getElementById('countFilterMelanggar').innerText = s.melanggar || 0;
        document.getElementById('countFilterSelesai').innerText = s.selesai || 0;
    }

    function renderTable() {
        const tbody = document.getElementById('candidatesTableBody');
        const q = (document.getElementById('searchCandidate').value || '').toLowerCase().trim();

        let filtered = candidatesData.filter(item => {
            const matchSearch = item.nama_peserta.toLowerCase().includes(q) || (item.nomor_peserta && item.nomor_peserta.toLowerCase().includes(q));
            if (!matchSearch) return false;

            if (currentFilter === 'Mengerjakan') return item.status_pengerjaan === 'Mengerjakan' && !item.is_locked;
            if (currentFilter === 'Terkunci') return item.is_locked;
            if (currentFilter === 'Melanggar') return item.jumlah_pelanggaran > 0;
            if (currentFilter === 'Selesai') return item.status_pengerjaan === 'Selesai';
            return true;
        });

        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="p-10 text-center text-gray-400 text-xs">
                        Tidak ada siswa yang sesuai dengan filter / pencarian.
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        filtered.forEach((item, idx) => {
            const pct = item.total_soal > 0 ? Math.round((item.terjawab_count / item.total_soal) * 100) : 0;
            const violations = item.jumlah_pelanggaran || 0;
            const maxV = item.max_violations || maxViolationsExam;

            // Status Badge
            let statusBadge = '';
            if (item.is_locked) {
                statusBadge = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300 animate-pulse">🔒 TERKUNCI</span>`;
            } else if (item.status_pengerjaan === 'Mengerjakan') {
                statusBadge = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-300"><span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-ping"></span> Mengerjakan</span>`;
            } else if (item.status_pengerjaan === 'Selesai') {
                statusBadge = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">✓ Selesai</span>`;
            } else {
                statusBadge = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">Belum Mulai</span>`;
            }

            // Pelanggaran badge
            let violationBadge = '';
            if (violations >= maxV || item.is_locked) {
                violationBadge = `
                    <div class="space-y-1">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-600 text-white font-mono animate-bounce">
                            ⚠️ ${violations}/${maxV} (Habis)
                        </span>
                        <button type="button" onclick="showViolationLog(${item.id})" class="text-[10px] text-rose-700 underline font-semibold block">Lihat Log (${violations})</button>
                    </div>
                `;
            } else if (violations > 0) {
                violationBadge = `
                    <div class="space-y-1">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300 font-mono">
                            ⚠️ ${violations}/${maxV} Melanggar
                        </span>
                        <button type="button" onclick="showViolationLog(${item.id})" class="text-[10px] text-amber-800 underline font-semibold block">Lihat Log (${violations})</button>
                    </div>
                `;
            } else {
                violationBadge = `<span class="text-[11px] text-emerald-600 font-semibold">✓ 0 Pelanggaran</span>`;
            }

            // Action buttons
            let actionsHtml = `<div class="flex items-center gap-1 justify-center flex-wrap">`;
            if (item.is_locked) {
                actionsHtml += `
                    <button type="button" onclick="sendMonitoringAction('unlock', ${item.id})" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold shadow-xs transition" title="Buka Kunci agar siswa bisa melanjutkan ujian">
                        🔓 Buka Kunci
                    </button>
                `;
            }

            if (violations > 0) {
                actionsHtml += `
                    <button type="button" onclick="sendMonitoringAction('reset_violation', ${item.id})" class="px-2 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 text-[10px] font-semibold border border-amber-200 transition" title="Reset jumlah pelanggaran ke 0">
                        Reset Pelanggaran
                    </button>
                `;
            }

            if (item.status_pengerjaan === 'Mengerjakan') {
                actionsHtml += `
                    <button type="button" onclick="sendMonitoringAction('force_submit', ${item.id})" class="px-2 py-1 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-700 text-[10px] font-semibold border border-purple-200 transition" title="Paksa selesaikan ujian sekarang">
                        Paksa Selesai
                    </button>
                `;
            }

            actionsHtml += `
                <button type="button" onclick="sendMonitoringAction('reset_attempt', ${item.id})" class="px-2 py-1 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-600 text-[10px] font-semibold border border-gray-200 transition" title="Reset ujian siswa dari awal jika terjadi kendala teknis">
                    Reset Ujian
                </button>
            </div>`;

            html += `
                <tr class="hover:bg-gray-50/70 transition ${item.is_locked ? 'bg-rose-50/20' : ''}">
                    <td class="p-3 text-center font-mono font-bold text-gray-500">${idx + 1}</td>
                    <td class="p-3 font-semibold text-gray-900">
                        <span class="uppercase block font-bold text-xs">${item.nama_peserta}</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[10px] text-gray-400 font-mono">NIS: ${item.nomor_peserta || '-'}</span>
                            <span class="text-[10px] text-purple-600 font-mono font-semibold">Percobaan: ${item.attempt_number}/${item.max_attempts}</span>
                        </div>
                    </td>
                    <td class="p-3 text-center">
                        <span class="font-mono font-bold text-gray-700 block">${item.kelas}</span>
                        <span class="text-[10px] text-purple-700 font-semibold">${item.jurusan || 'UMUM'}</span>
                    </td>
                    <td class="p-3 text-center">${statusBadge}</td>
                    <td class="p-3 text-center">
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-[10px] font-mono text-gray-500">
                                <span>${item.terjawab_count}/${item.total_soal}</span>
                                <span class="font-bold text-gray-700">${pct}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald-600 h-1.5 rounded-full transition-all duration-300" style="width: ${pct}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="p-3 text-center font-mono font-bold text-xs ${item.sisa_detik < 300 && item.status_pengerjaan === 'Mengerjakan' ? 'text-rose-600 animate-pulse' : 'text-gray-700'}">
                        ${formatTime(item.sisa_detik)}
                    </td>
                    <td class="p-3 text-center">${violationBadge}</td>
                    <td class="p-3 text-center font-mono font-bold text-xs">
                        <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800">${item.nilai}</span>
                    </td>
                    <td class="p-3 text-center">${actionsHtml}</td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    function showViolationLog(studentId) {
        const st = candidatesData.find(c => c.id === studentId);
        if (!st) return;

        document.getElementById('violationStudentName').innerText = `${st.nama_peserta} (NIS: ${st.nomor_peserta || '-'})`;
        document.getElementById('modalMaxViolations').innerText = st.max_violations || maxViolationsExam;

        const container = document.getElementById('violationListContainer');
        const logs = st.log_pelanggaran || [];

        if (logs.length === 0) {
            container.innerHTML = `<p class="text-xs text-gray-400 text-center py-4">Tidak ada riwayat pelanggaran tercatat.</p>`;
        } else {
            let listHtml = '';
            logs.forEach((log, index) => {
                listHtml += `
                    <div class="p-2.5 rounded-xl border border-rose-100 bg-rose-50/40 text-xs space-y-0.5">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-rose-700">Pelanggaran #${index + 1}</span>
                            <span class="text-[10px] text-gray-400 font-mono">${log.waktu || '-'}</span>
                        </div>
                        <p class="text-[11px] text-gray-700 font-medium">${log.alasan || 'Pelanggaran anti-cheat terdeteksi'}</p>
                    </div>
                `;
            });
            container.innerHTML = listHtml;
        }

        const btnReset = document.getElementById('btnModalResetViolation');
        btnReset.onclick = function() {
            sendMonitoringAction('reset_violation', st.id);
            closeViolationModal();
        };

        document.getElementById('violationModal').style.display = 'flex';
    }

    function closeViolationModal() {
        document.getElementById('violationModal').style.display = 'none';
    }

    function sendMonitoringAction(action, resultId) {
        const actionNames = {
            'unlock': 'Buka kunci ujian untuk siswa ini?',
            'reset_violation': 'Reset riwayat pelanggaran siswa ini menjadi 0?',
            'force_submit': 'Paksa kumpulkan dan nilai ujian siswa ini sekarang?',
            'reset_attempt': 'PERINGATAN: Reset total pengerjaan ujian siswa ini dari awal?'
        };

        if (!confirm(actionNames[action] || 'Lakukan aksi pengawas ini?')) {
            return;
        }

        fetch(`{{ url('admin/cbt/madrasah') }}/${examId}/monitoring-action`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ action: action, result_id: resultId })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert(res.message);
                fetchMonitoringData(true);
            } else {
                alert('Gagal: ' + (res.message || 'Terjadi kesalahan sistem.'));
            }
        })
        .catch(err => {
            console.error('Action error:', err);
            alert('Terjadi kendala jaringan saat menghubungi server.');
        });
    }

    // Interval Timer Polling
    setInterval(() => {
        countdownSec--;
        const el = document.getElementById('refreshCountdown');
        if (el) el.innerText = countdownSec;
        if (countdownSec <= 0) {
            fetchMonitoringData();
        }
    }, 1000);

    // Initial load
    fetchMonitoringData(true);
</script>
@endsection

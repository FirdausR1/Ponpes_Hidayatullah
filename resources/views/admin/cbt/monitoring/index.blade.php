@extends('admin.layout')

@section('title', 'Live Monitor Ujian CBT')

@section('styles')
<style>
    /* Pulse animation for online indicator */
    @keyframes pulse-dot {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.4); opacity: .7; }
    }
    .online-dot { animation: pulse-dot 1.5s ease-in-out infinite; }

    /* Fade-in rows */
    @keyframes row-in { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:none; } }
    .row-animate { animation: row-in .25s ease both; }

    /* Toast */
    #toast { transition: opacity .3s, transform .3s; }

    /* Scroll fix */
    .monitor-table-wrap { overflow-x: auto; }

    /* Status badge */
    .badge-online  { background:#dcfce7; color:#15803d; }
    .badge-idle    { background:#fef9c3; color:#854d0e; }
    .badge-offline { background:#f1f5f9; color:#64748b; }
    .badge-selesai { background:#dbeafe; color:#1d4ed8; }

    /* Action btn */
    .action-btn { @apply inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium transition; }
</style>
@endsection

@section('content')
<div x-data="monitorApp()" x-init="init()" class="px-4 sm:px-6 lg:px-8 py-6">

    {{-- ── PAGE HEADER ──────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Live Monitor Ujian CBT</h1>
            <p class="text-sm text-gray-500 mt-1">Data diperbarui otomatis setiap 5 detik. Klik baris santri untuk melihat detail dan tindakan pengawas.</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Live indicator --}}
            <span class="badge-success gap-1.5 py-1 px-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 online-dot inline-block"></span>
                LIVE
            </span>
            <span x-text="'Update: ' + lastUpdate" class="text-xs text-gray-400"></span>
        </div>
    </div>

    {{-- ── STAT CARDS ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="ta-card p-5">
            <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider block">Total Peserta</span>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ $totalPeserta }}</p>
        </div>
        <div class="ta-card p-5">
            <span class="text-xs text-emerald-600 font-semibold uppercase tracking-wider block">Sedang Ujian</span>
            <p x-text="stats.active" class="text-2xl font-bold text-emerald-600 mt-2">{{ $sedangUjian }}</p>
        </div>
        <div class="ta-card p-5">
            <span class="text-xs text-brand-500 font-semibold uppercase tracking-wider block">Selesai</span>
            <p x-text="stats.selesai" class="text-2xl font-bold text-brand-500 mt-2">{{ $selesaiUjian }}</p>
        </div>
        <div class="ta-card p-5">
            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Belum Ujian</span>
            <p x-text="stats.belum" class="text-2xl font-bold text-gray-500 mt-2">{{ $belumUjian }}</p>
        </div>
    </div>

    {{-- ── FILTER TABS + SEARCH ─────────────────────────────────── --}}
    <div class="ta-card mb-6">
        <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-b border-gray-100">
            <div class="flex gap-1.5 p-1 bg-gray-100 rounded-lg text-xs font-medium">
                <template x-for="tab in tabs" :key="tab.key">
                    <button @click="setFilter(tab.key)"
                        :class="filter===tab.key
                            ? 'bg-brand-500 text-white shadow-xs'
                            : 'text-gray-600 hover:text-gray-900'"
                        class="px-3 py-1.5 rounded-md font-medium transition"
                        x-text="tab.label">
                    </button>
                </template>
            </div>
            <div class="relative">
                <input x-model="search" type="text" placeholder="Cari santri, no. reg..."
                    class="ta-input w-56 pl-8 py-1.5 text-xs">
                <svg class="absolute left-2.5 top-2.5 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="monitor-table-wrap">
            <table class="ta-table">
                <thead>
                    <tr>
                        <th class="w-10">#</th>
                        <th>Peserta</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Soal ke-</th>
                        <th class="text-center">Dijawab</th>
                        <th class="text-center">Ragu</th>
                        <th class="text-center">Pelanggaran</th>
                        <th class="text-center">+Waktu</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Idle</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loading --}}
                    <tr x-show="loading">
                        <td colspan="11" class="text-center py-12 text-gray-400">
                            <svg class="animate-spin w-6 h-6 mx-auto mb-2 text-brand-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4l-3 3-3-3h4z"/></svg>
                            Memuat data peserta…
                        </td>
                    </tr>
                    {{-- Empty --}}
                    <tr x-show="!loading && filteredRows.length === 0">
                        <td colspan="11" class="text-center py-12 text-gray-400">
                            Tidak ada peserta ditemukan.
                        </td>
                    </tr>
                    {{-- Rows --}}
                    <template x-for="(row, idx) in filteredRows" :key="row.id">
                        <tr class="hover:bg-gray-50 transition cursor-pointer"
                            @click="openAction(row)">
                            <td class="text-gray-400 text-xs" x-text="idx+1"></td>
                            <td>
                                <div class="font-semibold text-gray-800 text-sm" x-text="row.nama"></div>
                                <div class="text-xs text-gray-400 font-mono mt-0.5" x-text="row.no_reg + ' · ' + row.asal"></div>
                            </td>
                            <td class="text-center">
                                <span class="badge-gray"
                                    :class="statusBadge(row)">
                                    <span class="w-1.5 h-1.5 rounded-full"
                                        :class="row.is_online ? 'bg-emerald-500 online-dot' : (row.status_ujian==='Selesai' ? 'bg-blue-400' : 'bg-gray-300')"
                                        x-show="row.status_ujian !== 'Belum Ujian'"></span>
                                    <span x-text="row.is_online ? 'Online' : (row.status_ujian==='Sedang Ujian' ? 'Idle' : (row.status_ujian ?? 'Belum'))"></span>
                                </span>
                            </td>
                            <td class="text-center font-mono font-bold text-gray-700" x-text="row.status_ujian==='Sedang Ujian' ? row.current_q : '—'"></td>
                            <td class="text-center">
                                <span class="font-semibold text-gray-700" x-text="row.total_answered"></span>
                            </td>
                            <td class="text-center">
                                <span x-show="row.total_ragu > 0" class="text-amber-600 font-semibold" x-text="row.total_ragu"></span>
                                <span x-show="row.total_ragu === 0" class="text-gray-300">—</span>
                            </td>
                            <td class="text-center">
                                <span x-show="row.violations > 0"
                                    class="badge-error"
                                    x-text="row.violations + ' Pelanggaran'"></span>
                                <span x-show="row.violations === 0" class="text-gray-300">—</span>
                            </td>
                            <td class="text-center">
                                <span x-show="row.extra_time > 0"
                                    class="text-brand-500 font-semibold text-xs"
                                    x-text="'+' + row.extra_time + ' mnt'"></span>
                                <span x-show="row.extra_time === 0" class="text-gray-300">—</span>
                            </td>
                            <td class="text-center">
                                <span x-show="row.nilai !== null"
                                    :class="row.nilai >= 70 ? 'text-emerald-600' : 'text-red-600'"
                                    class="font-bold text-sm"
                                    x-text="row.nilai"></span>
                                <span x-show="row.nilai === null" class="text-gray-300">—</span>
                            </td>
                            <td class="text-center text-xs font-mono"
                                :class="row.idle_seconds !== null && row.status_ujian==='Sedang Ujian'
                                    ? (row.idle_seconds < 30 ? 'text-emerald-600' : row.idle_seconds < 120 ? 'text-amber-600' : 'text-red-500')
                                    : 'text-gray-300'"
                                x-text="row.idle_seconds !== null && row.status_ujian==='Sedang Ujian' ? fmtIdle(row.idle_seconds) : '—'">
                            </td>
                            <td class="text-center" @click.stop>
                                <button @click="openAction(row)"
                                    class="ta-btn-sm-primary">
                                    Aksi
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="px-4 py-2.5 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
            <span x-text="filteredRows.length + ' peserta ditampilkan'"></span>
            <span x-text="'Total terjawab: ' + totalAnswered + ' soal'"></span>
        </div>
    </div>

    {{-- ── ACTION MODAL ─────────────────────────────────────────── --}}
    <div x-show="showModal" x-cloak
        class="ta-modal-backdrop"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        <div class="ta-modal" @click.outside="closeModal"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            {{-- Modal Header --}}
            <div class="ta-modal-header">
                <div>
                    <h2 class="text-base font-bold text-gray-800" x-text="selected?.nama ?? '—'"></h2>
                    <p class="text-xs text-gray-400 font-mono mt-0.5" x-text="(selected?.no_reg ?? '') + ' · ' + (selected?.asal ?? '')"></p>
                </div>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
            </div>

            {{-- Student Summary --}}
            <div class="px-6 py-3 grid grid-cols-3 gap-3 border-b border-gray-100 bg-gray-50/60">
                <div class="text-center">
                    <div class="text-[11px] text-gray-400 mb-0.5">Status</div>
                    <span class="badge-gray"
                        :class="statusBadge(selected)"
                        x-text="selected?.status_ujian ?? '—'"></span>
                </div>
                <div class="text-center">
                    <div class="text-[11px] text-gray-400 mb-0.5">Dijawab</div>
                    <div class="font-bold text-gray-700 text-sm" x-text="selected?.total_answered ?? '—'"></div>
                </div>
                <div class="text-center">
                    <div class="text-[11px] text-gray-400 mb-0.5">Pelanggaran</div>
                    <div class="font-bold text-sm" :class="(selected?.violations ?? 0) > 0 ? 'text-red-600' : 'text-gray-400'"
                        x-text="selected?.violations ?? 0"></div>
                </div>
            </div>

            {{-- Actions Body --}}
            <div class="ta-modal-body space-y-4">

                {{-- Extra Time --}}
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Tambah Waktu Ekstra</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="min in [5, 10, 15, 30]" :key="min">
                            <button @click="doAction('extra_time', {minutes: min})"
                                class="ta-btn-sm-outline"
                                :disabled="actionLoading"
                                x-text="'+' + min + ' Menit'">
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Send Message --}}
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Kirim Pesan ke Santri</p>
                    <div class="flex gap-2">
                        <input x-model="msgText" type="text" placeholder="Ketik pesan peringatan atau arahan..."
                            class="ta-input text-xs"
                            @keydown.enter="doAction('send_message', {message: msgText})">
                        <button @click="doAction('send_message', {message: msgText})"
                            :disabled="actionLoading || !msgText.trim()"
                            class="ta-btn-sm-primary whitespace-nowrap">
                            Kirim
                        </button>
                    </div>
                    {{-- Quick messages --}}
                    <div class="flex flex-wrap gap-1.5 pt-1">
                        <template x-for="qm in quickMessages" :key="qm">
                            <button @click="msgText = qm"
                                class="text-[11px] bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-0.5 rounded transition"
                                x-text="qm"></button>
                        </template>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="space-y-2 pt-2 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Tindakan Pengawas</p>
                    <div class="flex flex-wrap gap-2">
                        <button @click="confirmAction('force_finish')"
                            :disabled="actionLoading || selected?.status_ujian === 'Selesai'"
                            class="ta-btn-sm-danger">
                            Paksa Selesai
                        </button>
                        <button @click="doAction('reset_violations')"
                            :disabled="actionLoading"
                            class="ta-btn-sm-outline">
                            Reset Pelanggaran
                        </button>
                        <button @click="confirmAction('open_retake')"
                            :disabled="actionLoading"
                            class="ta-btn-sm-secondary">
                            Buka Ujian Ulang
                        </button>
                    </div>
                </div>

                {{-- Action result alert --}}
                <div x-show="actionResult" x-transition
                    class="p-2.5 rounded-lg text-xs font-medium text-center"
                    :class="actionSuccess ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200'"
                    x-text="actionResult">
                </div>
            </div>

            <div class="ta-modal-footer">
                <button type="button" @click="closeModal" class="ta-btn-sm-outline">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    {{-- ── TOAST ────────────────────────────────────────────────── --}}
    <div id="toast" class="fixed bottom-5 right-5 z-[100] px-4 py-3 rounded-xl text-white text-sm font-semibold shadow-xl max-w-xs opacity-0 translate-y-2 pointer-events-none transition">
    </div>

</div>
@endsection

@section('scripts')
<script>
function monitorApp() {
    return {
        // State
        rows: [],
        filter: 'active',
        search: '',
        loading: true,
        lastUpdate: '—',
        showModal: false,
        selected: null,
        msgText: '',
        actionLoading: false,
        actionResult: '',
        actionSuccess: true,
        stats: { active: {{ $sedangUjian }}, selesai: {{ $selesaiUjian }}, belum: {{ $belumUjian }} },

        tabs: [
            { key: 'active', label: '🟢 Sedang Ujian' },
            { key: 'selesai', label: '✅ Selesai' },
            { key: 'all', label: '📋 Semua' },
        ],

        quickMessages: [
            'Harap tidak curang!',
            'Waktu hampir habis, segera selesaikan!',
            'Pastikan koneksi internet stabil.',
            'Tenang, kerjakan dengan teliti.',
        ],

        pollInterval: null,

        init() {
            this.fetchData();
            this.pollInterval = setInterval(() => this.fetchData(), 5000);
        },

        get filteredRows() {
            let s = this.search.toLowerCase().trim();
            return this.rows.filter(r =>
                !s || r.nama.toLowerCase().includes(s) || r.no_reg.toLowerCase().includes(s)
            );
        },

        get totalAnswered() {
            return this.rows.filter(r => r.status_ujian === 'Sedang Ujian')
                          .reduce((sum, r) => sum + (r.total_answered || 0), 0);
        },

        async fetchData() {
            try {
                const res = await fetch(`{{ route('admin.cbt.monitoring.data') }}?filter=${this.filter}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const json = await res.json();
                if (json.success) {
                    this.rows = json.data;
                    this.lastUpdate = new Date().toLocaleTimeString('id-ID');
                    // Refresh stats from rows
                    this.stats.active = json.data.filter(r => r.status_ujian === 'Sedang Ujian').length;
                    this.stats.selesai = json.data.filter(r => r.status_ujian === 'Selesai').length;
                }
            } catch(e) {
                // silent fail — keep last data
            } finally {
                this.loading = false;
            }
        },

        setFilter(f) {
            this.filter = f;
            this.loading = true;
            this.rows = [];
            this.fetchData();
        },

        statusBadge(row) {
            if (!row) return '';
            if (row.is_online) return 'badge-online';
            if (row.status_ujian === 'Selesai') return 'badge-selesai';
            if (row.status_ujian === 'Sedang Ujian') return 'badge-idle';
            return 'badge-offline';
        },

        fmtIdle(sec) {
            if (sec < 60) return sec + 'd';
            return Math.floor(sec/60) + 'm ' + (sec%60) + 'd';
        },

        openAction(row) {
            this.selected = row;
            this.msgText = '';
            this.actionResult = '';
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.selected = null;
            this.actionResult = '';
        },

        async doAction(action, extra = {}) {
            if (!this.selected) return;
            this.actionLoading = true;
            this.actionResult = '';

            const body = new FormData();
            body.append('reg_id', this.selected.id);
            body.append('action', action);
            body.append('_token', '{{ csrf_token() }}');
            for (const [k, v] of Object.entries(extra)) {
                body.append(k, v);
            }

            try {
                const res = await fetch('{{ route('admin.cbt.monitoring.action') }}', {
                    method: 'POST',
                    body,
                });
                const json = await res.json();
                this.actionResult = json.message;
                this.actionSuccess = json.success;
                if (json.success) {
                    this.showToast(json.message, 'success');
                    this.msgText = '';
                    // Refresh data
                    await this.fetchData();
                    // Update selected
                    const updated = this.rows.find(r => r.id === this.selected?.id);
                    if (updated) this.selected = updated;
                } else {
                    this.showToast(json.message, 'error');
                }
            } catch(e) {
                this.actionResult = 'Terjadi kesalahan jaringan.';
                this.actionSuccess = false;
            } finally {
                this.actionLoading = false;
            }
        },

        confirmAction(action) {
            const labels = {
                force_finish: 'Yakin ingin memaksa ujian peserta ini SELESAI sekarang?',
                open_retake: 'Yakin ingin membuka ujian ulang? Jawaban sebelumnya akan dihapus!',
            };
            if (confirm(labels[action] || 'Yakin?')) {
                this.doAction(action);
            }
        },

        showToast(msg, type = 'success') {
            const el = document.getElementById('toast');
            el.textContent = msg;
            el.className = `fixed bottom-5 right-5 z-[100] px-4 py-3 rounded-xl text-white text-sm font-semibold shadow-xl max-w-xs transition ${type === 'success' ? 'bg-emerald-600' : 'bg-red-500'}`;
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
            setTimeout(() => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(8px)';
            }, 3000);
        },
    }
}
</script>
@endsection

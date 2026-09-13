@extends('admin.layout')

@section('title', 'Pengaturan Ujian CBT & Sistem Anti-Contek')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pengaturan Ujian CBT</h1>
            <p class="text-sm text-gray-500 mt-1">Konfigurasi materi ujian, jadwal sesi, durasi pengerjaan, KKM kelulusan, dan batas proteksi anti-contek.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.cbt.soal.index') }}" class="ta-btn-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Ke Bank Soal
            </a>
        </div>
    </div>

    <!-- CARD 1: KELOLA KATEGORI / MATA PELAJARAN UJIAN (CRUD) -->
    <div class="ta-card">
        <div class="ta-card-header">
            <div>
                <h2 class="text-base font-bold text-gray-800">Kategori &amp; Mata Pelajaran Ujian</h2>
                <p class="text-xs text-gray-500 mt-0.5">Daftar mata pelajaran yang digunakan pada Bank Soal dan jadwal ujian seleksi.</p>
            </div>
            <button type="button" onclick="toggleFormTambahKategori()" class="ta-btn-sm-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kategori
            </button>
        </div>

        <div class="ta-card-body space-y-4">
            <!-- Form Tambah Kategori -->
            <div id="formTambahKategori" class="hidden p-4 bg-gray-50 border border-gray-200 rounded-xl">
                <form action="{{ route('admin.cbt.kategori.store') }}" method="POST" class="flex flex-col sm:flex-row sm:items-end gap-3">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Kategori Baru</label>
                        <input type="text" name="nama_kategori" required placeholder="Contoh: Fiqih, IPA, Sejarah..."
                               class="ta-input">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="ta-btn-primary">
                            Simpan
                        </button>
                        <button type="button" onclick="toggleFormTambahKategori()" class="ta-btn-outline">
                            Batal
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form Edit Kategori (Rename) -->
            <div id="formEditKategori" class="hidden p-4 bg-blue-50/60 border border-blue-200 rounded-xl">
                <form action="{{ route('admin.cbt.kategori.update') }}" method="POST" class="flex flex-col sm:flex-row sm:items-end gap-3">
                    @csrf @method('PUT')
                    <input type="hidden" name="old_nama" id="editOldNama">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Ubah Nama Kategori: <span id="labelOldNama" class="text-brand-500 font-bold"></span>
                        </label>
                        <input type="text" name="new_nama" id="editNewNama" required
                               class="ta-input">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="ta-btn-primary">
                            Perbarui
                        </button>
                        <button type="button" onclick="closeEditKategori()" class="ta-btn-outline">
                            Batal
                        </button>
                    </div>
                </form>
            </div>

            <!-- Daftar Tag Kategori -->
            <div class="flex flex-wrap gap-2.5">
                @forelse($allCategories as $cat)
                    @php
                        $qCount = \App\Models\Question::where('kategori', $cat)->count();
                    @endphp
                    <div class="inline-flex items-center gap-2 pl-3 pr-1.5 py-1.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 transition">
                        <span class="font-semibold text-gray-800">{{ $cat }}</span>
                        <span class="badge-gray text-[10px] py-0 px-1.5">
                            {{ $qCount }} soal
                        </span>
                        <!-- Edit Button -->
                        <button type="button" onclick="openEditKategori('{{ addslashes($cat) }}')" title="Ubah nama kategori"
                                class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-brand-500 hover:bg-white rounded transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <!-- Delete Button -->
                        <form action="{{ route('admin.cbt.kategori.destroy') }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus kategori \'{{ addslashes($cat) }}\'?{{ $qCount > 0 ? ' Ada ' . $qCount . ' butir soal yang menggunakan kategori ini.' : '' }}')">
                            @csrf @method('DELETE')
                            <input type="hidden" name="nama_kategori" value="{{ $cat }}">
                            <button type="submit" title="Hapus kategori ini"
                                    class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 py-2">Belum ada kategori materi. Klik tombol "Tambah Kategori" di atas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- FORM UTAMA PENGATURAN CBT -->
    <form action="{{ route('admin.cbt.pengaturan.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- CARD 2: STATUS & DURASI UJIAN GLOBAL -->
        <div class="ta-card">
            <div class="ta-card-header">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Status &amp; Durasi Ujian Global</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Tentukan status akses dan durasi hitung mundur pengerjaan santri.</p>
                </div>
            </div>

            <div class="ta-card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Status Buka / Tutup -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Status Sesi Ujian <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition {{ $settings['cbt_status'] === 'buka' ? 'border-brand-500 bg-brand-50/20' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" name="cbt_status" value="buka" {{ $settings['cbt_status'] === 'buka' ? 'checked' : '' }} class="w-4 h-4 text-brand-500 focus:ring-brand-400">
                                <div>
                                    <span class="block font-semibold text-xs text-gray-800">Buka (Aktif)</span>
                                    <span class="block text-[11px] text-gray-400">Santri dapat login</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition {{ $settings['cbt_status'] === 'tutup' ? 'border-red-400 bg-red-50/20' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" name="cbt_status" value="tutup" {{ $settings['cbt_status'] === 'tutup' ? 'checked' : '' }} class="w-4 h-4 text-red-600 focus:ring-red-500">
                                <div>
                                    <span class="block font-semibold text-xs text-gray-800">Tutup</span>
                                    <span class="block text-[11px] text-gray-400">Portal dikunci</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Durasi Ujian -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Durasi Waktu (Menit) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="cbt_duration_minutes" value="{{ $settings['cbt_duration_minutes'] }}" min="5" max="300" required
                                   class="ta-input pr-16 font-bold">
                            <span class="absolute right-4 top-2.5 text-xs text-gray-400">Menit</span>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1.5">Timer hitung mundur jika tidak ada jadwal khusus per mapel.</p>
                    </div>

                    <!-- Batas Kesempatan Ujian (Attempts) -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Batas Kesempatan Ujian <span class="text-red-500">*</span>
                        </label>
                        <select name="cbt_max_attempts" class="ta-input font-medium">
                            <option value="1" {{ ($settings['cbt_max_attempts'] ?? 1) == 1 ? 'selected' : '' }}>1 Kali Pengerjaan (Standar)</option>
                            <option value="2" {{ ($settings['cbt_max_attempts'] ?? 1) == 2 ? 'selected' : '' }}>2 Kali (Toleransi 1x Remedial)</option>
                            <option value="3" {{ ($settings['cbt_max_attempts'] ?? 1) == 3 ? 'selected' : '' }}>3 Kali Pengerjaan</option>
                            <option value="5" {{ ($settings['cbt_max_attempts'] ?? 1) == 5 ? 'selected' : '' }}>5 Kali Pengerjaan</option>
                            <option value="0" {{ ($settings['cbt_max_attempts'] ?? 1) == 0 ? 'selected' : '' }}>Tanpa Batas (Mode Simulasi)</option>
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1.5">Jumlah kesempatan mengerjakan sebelum akses dikunci.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 3: RENTANG JADWAL PELAKSANAAN UJIAN (GLOBAL) -->
        <div class="ta-card">
            <div class="ta-card-header">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Rentang Jadwal Ujian Global</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Tentukan periode tanggal dan jam pengerjaan ujian secara keseluruhan.</p>
                </div>

                @php
                    $isScheduleActive = ($settings['cbt_enable_schedule'] ?? '0') === '1';
                    $now = now();
                    $startDate = !empty($settings['cbt_start_date']) ? \Carbon\Carbon::parse($settings['cbt_start_date']) : null;
                    $endDate = !empty($settings['cbt_end_date']) ? \Carbon\Carbon::parse($settings['cbt_end_date']) : null;
                @endphp

                @if($isScheduleActive && $startDate && $endDate)
                    @if($now->lt($startDate))
                        <span class="badge-warning">
                            Belum Dimulai (Mulai {{ $startDate->format('d M H:i') }})
                        </span>
                    @elseif($now->gt($endDate))
                        <span class="badge-error">
                            Telah Berakhir (Tutup {{ $endDate->format('d M H:i') }})
                        </span>
                    @else
                        <span class="badge-success">
                            Sedang Berlangsung
                        </span>
                    @endif
                @else
                    <span class="badge-gray">
                        Jadwal Bebas (Kapan Saja)
                    </span>
                @endif
            </div>

            <div class="ta-card-body space-y-4">
                <!-- Toggle Aktifkan Rentang Jadwal -->
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 flex items-start gap-3">
                    <input type="checkbox" name="cbt_enable_schedule" id="cbt_enable_schedule" value="1" {{ ($settings['cbt_enable_schedule'] ?? '0') === '1' ? 'checked' : '' }}
                           class="w-4 h-4 text-brand-500 rounded border-gray-300 focus:ring-brand-400 mt-0.5 cursor-pointer"
                           onchange="toggleScheduleFields(this.checked)">
                    <label for="cbt_enable_schedule" class="cursor-pointer">
                        <span class="font-semibold text-sm text-gray-800 block">Batasi Pengerjaan Berdasarkan Tanggal &amp; Jam</span>
                        <span class="text-xs text-gray-500 block mt-0.5">Santri hanya dapat mengakses soal pada periode ini. Di luar rentang waktu, portal otomatis terkunci.</span>
                    </label>
                </div>

                <!-- Tanggal Mulai & Selesai -->
                <div id="scheduleFields" class="grid grid-cols-1 sm:grid-cols-2 gap-5 transition-opacity" style="opacity: {{ ($settings['cbt_enable_schedule'] ?? '0') === '1' ? '1' : '0.5' }}; pointer-events: {{ ($settings['cbt_enable_schedule'] ?? '0') === '1' ? 'auto' : 'none' }};">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal &amp; Jam Mulai</label>
                        <input type="datetime-local" name="cbt_start_date" id="cbt_start_date" value="{{ $settings['cbt_start_date'] }}"
                               class="ta-input">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal &amp; Jam Selesai</label>
                        <input type="datetime-local" name="cbt_end_date" id="cbt_end_date" value="{{ $settings['cbt_end_date'] }}"
                               class="ta-input">
                    </div>

                    <div class="sm:col-span-2 flex items-center gap-2 pt-1">
                        <span class="text-xs text-gray-500">Preset Cepat:</span>
                        <button type="button" onclick="setPresetDays(7)" class="ta-btn-sm-secondary">1 Minggu</button>
                        <button type="button" onclick="setPresetDays(14)" class="ta-btn-sm-secondary">2 Minggu</button>
                        <button type="button" onclick="setPresetDays(30)" class="ta-btn-sm-secondary">1 Bulan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 4: JADWAL KHUSUS PER MATA PELAJARAN -->
        <div class="ta-card">
            <div class="ta-card-header">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Jadwal Khusus Per Mata Pelajaran</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Atur jadwal pelaksanaan atau batasan jumlah soal spesifik per mapel.</p>
                </div>
                <span class="badge-gray">
                    {{ count($categorySchedules) }} Mata Pelajaran
                </span>
            </div>

            <div class="ta-card-body space-y-3">
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-600">
                    Jika opsi <strong>"Jadwalkan Khusus"</strong> dicentang, materi tersebut akan menggunakan durasi, jadwal, atau jumlah soal tersendiri alih-alih konfigurasi global.
                </div>

                @foreach($categorySchedules as $catName => $sched)
                @php
                    $isCustom = !empty($sched['enable_schedule']);
                    $catStart = !empty($sched['start_date']) ? \Carbon\Carbon::parse($sched['start_date']) : null;
                    $catEnd = !empty($sched['end_date']) ? \Carbon\Carbon::parse($sched['end_date']) : null;
                    $now = now();
                @endphp
                <div class="border rounded-xl p-4 transition {{ $isCustom ? 'border-brand-300 bg-white shadow-xs' : 'border-gray-200 bg-gray-50/50' }}">
                    <div class="flex items-center justify-between flex-wrap gap-3 pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full {{ ($sched['status'] ?? 'buka') === 'buka' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                            <h4 class="text-sm font-semibold text-gray-800">{{ $catName }}</h4>
                            <span class="badge-gray text-[10px] py-0 px-2">
                                {{ $sched['total_questions_in_bank'] }} Soal
                            </span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            @if(($sched['status'] ?? 'buka') === 'tutup')
                                <span class="badge-error">Ditutup</span>
                            @elseif($isCustom && $catStart && $catEnd)
                                @if($now->lt($catStart))
                                    <span class="badge-warning">Mulai: {{ $catStart->format('d M H:i') }}</span>
                                @elseif($now->gt($catEnd))
                                    <span class="badge-gray">Selesai: {{ $catEnd->format('d M H:i') }}</span>
                                @else
                                    <span class="badge-success">Aktif</span>
                                @endif
                            @else
                                <span class="badge-gray">Ikut Global</span>
                            @endif

                            <select name="category_schedules[{{ $catName }}][status]" class="ta-input text-xs py-1 px-2 w-auto">
                                <option value="buka" {{ ($sched['status'] ?? 'buka') === 'buka' ? 'selected' : '' }}>Buka</option>
                                <option value="tutup" {{ ($sched['status'] ?? 'buka') === 'tutup' ? 'selected' : '' }}>Tutup</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3.5 grid grid-cols-1 lg:grid-cols-12 gap-3 items-center">
                        <div class="lg:col-span-3">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="category_schedules[{{ $catName }}][enable_schedule]" value="1" {{ $isCustom ? 'checked' : '' }}
                                       onchange="document.getElementById('sched_inputs_{{ md5($catName) }}').style.opacity = this.checked ? '1' : '0.4'; document.getElementById('sched_inputs_{{ md5($catName) }}').style.pointerEvents = this.checked ? 'auto' : 'none';"
                                       class="w-4 h-4 text-brand-500 rounded border-gray-300 focus:ring-brand-400">
                                <span class="text-xs font-semibold text-gray-700">Jadwalkan Khusus</span>
                            </label>
                        </div>

                        <div id="sched_inputs_{{ md5($catName) }}" class="lg:col-span-9 grid grid-cols-1 sm:grid-cols-4 gap-2 transition-opacity" style="opacity: {{ $isCustom ? '1' : '0.4' }}; pointer-events: {{ $isCustom ? 'auto' : 'none' }};">
                            <div>
                                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Mulai</label>
                                <input type="datetime-local" name="category_schedules[{{ $catName }}][start_date]" value="{{ $sched['start_date'] }}"
                                       class="ta-input text-xs py-1">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Selesai</label>
                                <input type="datetime-local" name="category_schedules[{{ $catName }}][end_date]" value="{{ $sched['end_date'] }}"
                                       class="ta-input text-xs py-1">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Durasi (Menit)</label>
                                <input type="number" name="category_schedules[{{ $catName }}][duration_minutes]" value="{{ $sched['duration_minutes'] }}" min="5" max="300"
                                       class="ta-input text-xs py-1" placeholder="60">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Jml Soal (0=Semua)</label>
                                <input type="number" name="category_schedules[{{ $catName }}][question_count]" value="{{ $sched['question_count'] }}" min="0" max="500"
                                       class="ta-input text-xs py-1" placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- GRID 2 KOLOM: CARD 5 & CARD 6 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- CARD 5: PENGACAKAN & JUMLAH SOAL -->
            <div class="ta-card">
                <div class="ta-card-header">
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Jumlah Soal &amp; Pengacakan</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Atur jumlah butir soal global dan opsi pengacakan.</p>
                    </div>
                </div>

                <div class="ta-card-body space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Jumlah Soal Ditampilkan</label>
                        <div class="relative">
                            <input type="number" name="cbt_question_count" value="{{ $settings['cbt_question_count'] }}" min="0" max="500" required
                                   class="ta-input pr-24 font-bold">
                            <span class="absolute right-4 top-2.5 text-xs text-gray-400">Butir Soal</span>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">Total di bank: <strong>{{ $totalQuestionsInBank }} butir</strong>. Isi 0 untuk memunculkan semua.</p>
                    </div>

                    <div class="space-y-2 pt-1">
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                            <input type="checkbox" name="cbt_shuffle_questions" value="1" {{ $settings['cbt_shuffle_questions'] === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-brand-500 focus:ring-brand-400">
                            <div>
                                <span class="block text-xs font-semibold text-gray-800">Acak Urutan Soal (Shuffle Questions)</span>
                                <span class="block text-[11px] text-gray-500">Santri menerima nomor soal dengan urutan berlainan</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                            <input type="checkbox" name="cbt_shuffle_options" value="1" {{ $settings['cbt_shuffle_options'] === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-brand-500 focus:ring-brand-400">
                            <div>
                                <span class="block text-xs font-semibold text-gray-800">Acak Pilihan Ganda (Shuffle Options)</span>
                                <span class="block text-[11px] text-gray-500">Pilihan opsi A, B, C, D diacak secara otomatis</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- CARD 6: KKM & SISTEM ANTI-CONTEK -->
            <div class="ta-card">
                <div class="ta-card-header">
                    <div>
                        <h2 class="text-base font-bold text-gray-800">KKM &amp; Proteksi Anti-Contek</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Batas kelulusan dan toleransi pelanggaran ujian.</p>
                    </div>
                </div>

                <div class="ta-card-body space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Batas KKM (0-100) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="cbt_passing_grade" value="{{ $settings['cbt_passing_grade'] }}" min="0" max="100" required
                                       class="ta-input pr-16 font-bold">
                                <span class="absolute right-4 top-2.5 text-xs text-gray-400">Poin</span>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1">Nilai &ge; KKM berstatus Lulus.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Toleransi Switch Tab <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="cbt_max_violations" value="{{ $settings['cbt_max_violations'] }}" min="1" max="10" required
                                       class="ta-input pr-16 font-bold">
                                <span class="absolute right-4 top-2.5 text-xs text-gray-400">Kali</span>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1">Melebihi batas, ujian dikunci.</p>
                        </div>
                    </div>

                    <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-xl space-y-2">
                        <span class="text-xs font-semibold text-gray-700 block">Fitur Keamanan Aktif:</span>
                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Layar Penuh (Fullscreen)
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Deteksi Beralih Tab
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Blokir Klik Kanan
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Blokir Shortcut Copy/F12
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- CARD 7: JUDUL & TATA TERTIB UJIAN -->
        <div class="ta-card">
            <div class="ta-card-header">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Judul &amp; Tata Tertib Ujian Santri</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Petunjuk yang ditampilkan pada portal login santri.</p>
                </div>
            </div>

            <div class="ta-card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Judul Ujian <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="cbt_exam_title" value="{{ $settings['cbt_exam_title'] }}" required
                               class="ta-input font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Petunjuk &amp; Tata Tertib
                        </label>
                        <textarea name="cbt_instructions" rows="3"
                                  class="ta-input leading-relaxed">{{ $settings['cbt_instructions'] }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- ACTION FOOTER -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-200">
            <span class="text-xs text-gray-400">Pastikan seluruh konfigurasi jadwal dan standar kelulusan sudah sesuai sebelum disimpan.</span>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.cbt.soal.index') }}" class="ta-btn-outline">
                    Batal
                </a>
                <button type="submit" class="ta-btn-primary">
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>

</div>

<script>
    function toggleFormTambahKategori() {
        const f = document.getElementById('formTambahKategori');
        f.classList.toggle('hidden');
        if (!f.classList.contains('hidden')) {
            f.querySelector('input[name="nama_kategori"]').focus();
            document.getElementById('formEditKategori').classList.add('hidden');
        }
    }

    function openEditKategori(nama) {
        const f = document.getElementById('formEditKategori');
        document.getElementById('editOldNama').value = nama;
        document.getElementById('editNewNama').value = nama;
        document.getElementById('labelOldNama').textContent = '"' + nama + '"';
        f.classList.remove('hidden');
        document.getElementById('formTambahKategori').classList.add('hidden');
        document.getElementById('editNewNama').focus();
    }

    function closeEditKategori() {
        document.getElementById('formEditKategori').classList.add('hidden');
    }

    function toggleScheduleFields(isChecked) {
        const fields = document.getElementById('scheduleFields');
        if (fields) {
            fields.style.opacity = isChecked ? '1' : '0.5';
            fields.style.pointerEvents = isChecked ? 'auto' : 'none';
        }
    }

    function setPresetDays(days) {
        const now = new Date();
        const startStr = now.toISOString().slice(0, 16);

        const future = new Date(now.getTime() + days * 24 * 60 * 60 * 1000);
        future.setHours(23, 59, 0, 0);
        const year = future.getFullYear();
        const month = String(future.getMonth() + 1).padStart(2, '0');
        const day = String(future.getDate()).padStart(2, '0');
        const endStr = `${year}-${month}-${day}T23:59`;

        document.getElementById('cbt_start_date').value = startStr;
        document.getElementById('cbt_end_date').value = endStr;
        const chk = document.getElementById('cbt_enable_schedule');
        chk.checked = true;
        toggleScheduleFields(true);
    }
</script>
@endsection

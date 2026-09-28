@extends('admin.layout')

@section('title', 'Laporan Harian PSB — TA ' . $tahunAjaran)

@section('content')
<div class="space-y-6">

    <!-- Header Page & Navigation Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-indigo-50 px-2 py-0.5 text-xs font-semibold text-indigo-700 border border-indigo-200">
                    Rekapitulasi Harian PSB
                </span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-semibold text-gray-500">TA {{ $tahunAjaran }}</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $modeData === 'contoh' ? 'text-amber-600' : 'text-emerald-600' }}">
                    <span class="w-2 h-2 rounded-full {{ $modeData === 'contoh' ? 'bg-amber-500 animate-pulse' : 'bg-emerald-500' }}"></span>
                    Mode: {{ $modeData === 'contoh' ? 'Contoh Format Permintaan (256 Santri)' : 'Data Riil Database' }}
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Actual Daily Report PSB</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                Laporan perkembangan harian penerimaan santri baru, pergerakan per gelombang/jalur, dan status calon santri.
            </p>
        </div>

        <!-- Action Button Group -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Tombol Kembali ke Tabel Utama -->
            <a href="{{ route('admin.psb.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-3.5 py-2.5 text-xs font-medium shadow-theme-xs transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Tabel PSB</span>
            </a>

            <!-- Tombol Cetak Dokumen Resmi Lengkap Kop Surat -->
            <a href="{{ route('admin.psb.laporan.cetak', request()->all()) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 text-xs font-bold shadow-theme-xs transition">
                <svg class="w-4 h-4 fill-none stroke-currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                <span>🖨️ Cetak Dokumen Resmi (KOP)</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar & Mode Data Switcher -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
        <form method="GET" action="{{ route('admin.psb.laporan') }}" class="flex flex-wrap items-center justify-between gap-4">
            
            <!-- Switcher Mode Data -->
            <div class="flex items-center gap-1.5 p-1 bg-gray-100 rounded-xl">
                <a href="{{ route('admin.psb.laporan', array_merge(request()->except('mode_data'), ['mode_data' => 'contoh', 'per_tanggal' => '2026-07-04'])) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $modeData === 'contoh' ? 'bg-white text-indigo-700 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}"
                   title="Lihat format contoh pelaporan harian persis 256 santri">
                    <svg class="w-3.5 h-3.5 {{ $modeData === 'contoh' ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Contoh Format 256 Santri</span>
                </a>

                <a href="{{ route('admin.psb.laporan', array_merge(request()->except('mode_data'), ['mode_data' => 'real', 'per_tanggal' => date('Y-m-d')])) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $modeData === 'real' ? 'bg-white text-emerald-700 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}"
                   title="Lihat data yang dihitung langsung dari database PSB saat ini ({{ $realCount }} pendaftar)">
                    <svg class="w-3.5 h-3.5 {{ $modeData === 'real' ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                    <span>Data Riil Database ({{ $realCount }})</span>
                </a>
            </div>

            <!-- Filter Tanggal Cut-Off & Tahun Ajaran -->
            <div class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="mode_data" value="{{ $modeData }}">

                <div class="flex items-center gap-2">
                    <label for="perTanggalInput" class="text-xs font-bold uppercase text-gray-500 tracking-wider">Per Tanggal:</label>
                    <input type="date" id="perTanggalInput" name="per_tanggal" value="{{ $perTanggal }}" onchange="this.form.submit()" 
                           class="h-10 px-3 text-xs sm:text-sm font-semibold text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>

                <div class="flex items-center gap-2">
                    <label for="tahunAjaranInput" class="text-xs font-bold uppercase text-gray-500 tracking-wider">Tahun Ajaran:</label>
                    <input type="text" id="tahunAjaranInput" name="tahun_ajaran" value="{{ $tahunAjaran }}" placeholder="2026/2027" 
                           class="h-10 w-28 px-3 text-xs sm:text-sm font-semibold text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    <button type="submit" class="h-10 px-3 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition">
                        Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- DAILY REPORT HIGHLIGHT CARD (Format Sesuai Permintaan Spesifik) -->
    <div class="rounded-2xl border-2 border-indigo-200 bg-gradient-to-br from-indigo-50/50 via-white to-emerald-50/40 p-6 shadow-theme-xs relative overflow-hidden">
        
        <!-- Watermark / Decorative Background Icon -->
        <div class="absolute -right-8 -bottom-8 w-44 h-44 text-indigo-100 pointer-events-none opacity-60">
            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
        </div>

        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4 pb-4 border-b border-indigo-100">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    FORMAT RESMI LAPORAN HARIAN
                </span>
                <h2 class="text-lg font-bold text-gray-900 mt-1">"Actual daily report PSB Tahun Ajaran {{ $tahunAjaran }}"</h2>
                <p class="text-xs text-gray-500">Per tanggal: <strong class="text-gray-800 font-semibold">{{ $tanggalFormatted }}</strong></p>
            </div>

            <!-- Tombol Salin Teks WhatsApp -->
            <div class="flex items-center gap-2">
                <button type="button" onclick="salinFormatLaporan()" id="btnSalinLaporan" 
                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-2 text-xs font-bold shadow-xs transition cursor-pointer"
                        title="Salin format laporan ke clipboard untuk dikirim ke grup WhatsApp pimpinan / yayasan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    <span id="labelSalinText">Salin Teks WhatsApp</span>
                </button>

                <a href="{{ route('admin.psb.laporan.cetak', request()->all()) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-3.5 py-2 text-xs font-semibold shadow-xs transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Buka Lembar Cetak KOP</span>
                </a>
            </div>
        </div>

        <!-- Teks Format Laporan (Monospace / Dispatch View) -->
        <div class="rounded-xl border border-indigo-200/80 bg-white/90 backdrop-blur-xs p-5 font-mono text-xs sm:text-[13px] leading-relaxed text-gray-800 shadow-inner">
            <div class="font-bold text-indigo-950 mb-3 text-sm">
                Actual daily report PSB Tahun Ajaran {{ $tahunAjaran }}
            </div>

            <div class="text-gray-700 mb-3 font-semibold">
                * Per tanggal: <span class="text-indigo-900 font-bold">{{ $tanggalFormatted }}</span>
            </div>

            <!-- Rincian MTs -->
            <div class="mb-4 pl-1">
                <div class="font-bold text-emerald-900 mb-1.5 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                    <span>* Jumlah pendaftar MTs</span>
                </div>
                <div class="pl-5 space-y-0.5 text-gray-700">
                    <div>° Gel. 1 = <strong class="text-gray-900">{{ $mtsData['items']['gel_1']['total'] }} anak</strong> <span class="text-[11px] text-gray-500 font-sans font-normal">(Putra: {{ $mtsData['items']['gel_1']['putra'] }}, Putri: {{ $mtsData['items']['gel_1']['putri'] }})</span></div>
                    <div>° Gel. 2 = <strong class="text-gray-900">{{ $mtsData['items']['gel_2']['total'] }} anak</strong> <span class="text-[11px] text-gray-500 font-sans font-normal">(Putra: {{ $mtsData['items']['gel_2']['putra'] }}, Putri: {{ $mtsData['items']['gel_2']['putri'] }})</span></div>
                    @if(($mtsData['items']['internal']['total'] ?? 0) > 0)
                        <div>° Internal = <strong class="text-gray-900">{{ $mtsData['items']['internal']['total'] }} anak</strong></div>
                    @endif
                    <div class="text-rose-600 font-medium">° Mengundurkan diri = <strong class="text-rose-700">{{ $mtsData['mengundurkan_diri']['total'] }} anak</strong></div>
                    <div class="pt-1 font-bold text-emerald-800 border-t border-dashed border-gray-200 mt-1">
                        Total MTs = <span class="text-base font-extrabold text-emerald-700">{{ $mtsData['total_bersih'] }} anak</span>
                    </div>
                </div>
            </div>

            <!-- Rincian MA -->
            <div class="mb-4 pl-1">
                <div class="font-bold text-blue-900 mb-1.5 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                    <span>• Jumlah pendaftar MA</span>
                </div>
                <div class="pl-5 space-y-0.5 text-gray-700">
                    <div>
                        ° Internal = <strong class="text-gray-900">{{ $maData['items']['internal']['total'] }} anak</strong>
                        <span class="text-indigo-700 font-semibold font-sans text-xs">
                            (putri : {{ $maData['items']['internal']['putri'] }} anak) (Putra : {{ $maData['items']['internal']['putra'] }} anak)
                        </span>
                    </div>
                    <div>° Gel. 1 = <strong class="text-gray-900">{{ $maData['items']['gel_1']['total'] }} anak</strong> <span class="text-[11px] text-gray-500 font-sans font-normal">(Putra: {{ $maData['items']['gel_1']['putra'] }}, Putri: {{ $maData['items']['gel_1']['putri'] }})</span></div>
                    <div>° Gel. 2 = <strong class="text-gray-900">{{ $maData['items']['gel_2']['total'] }} anak</strong> <span class="text-[11px] text-gray-500 font-sans font-normal">(Putra: {{ $maData['items']['gel_2']['putra'] }}, Putri: {{ $maData['items']['gel_2']['putri'] }})</span></div>
                    <div class="text-rose-600 font-medium">° Mengundurkan diri = <strong class="text-rose-700">{{ $maData['mengundurkan_diri']['total'] }} anak</strong></div>
                    <div class="pt-1 font-bold text-blue-800 border-t border-dashed border-gray-200 mt-1">
                        Total MA = <span class="text-base font-extrabold text-blue-700">{{ $maData['total_bersih'] }} anak</span>
                    </div>
                </div>
            </div>

            <!-- Grand Total -->
            <div class="pt-3 border-t-2 border-indigo-200 space-y-1 font-bold">
                <div class="text-gray-800">~ Grand Total Pendaftar = <span class="text-indigo-900 font-extrabold text-sm">{{ $grandTotal['pendaftar'] }} anak</span></div>
                <div class="text-rose-700">~ Grand Total Mengundurkan diri = <span class="font-extrabold text-sm">{{ $grandTotal['mengundurkan_diri'] }} anak</span></div>
                <div class="text-emerald-700 text-sm sm:text-base">~ Grand Total Keseluruhan = <span class="text-emerald-800 font-black text-lg underline decoration-emerald-500">{{ $grandTotal['keseluruhan'] }} anak</span></div>
            </div>
        </div>
    </div>

    <!-- 4 EXECUTIVE STATISTIC CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total MTs -->
        <div class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-theme-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">Total MTs (Bersih)</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xs">MTs</span>
            </div>
            <div class="text-3xl font-extrabold text-emerald-700">{{ $mtsData['total_bersih'] }}</div>
            <div class="text-xs text-gray-500 mt-1">
                Gel. 1: <strong>{{ $mtsData['items']['gel_1']['total'] }}</strong> • Gel. 2: <strong>{{ $mtsData['items']['gel_2']['total'] }}</strong>
            </div>
            <div class="text-[11px] text-rose-600 mt-0.5">
                Mundur: {{ $mtsData['mengundurkan_diri']['total'] }} anak
            </div>
        </div>

        <!-- Card 2: Total MA -->
        <div class="rounded-2xl border border-blue-200 bg-white p-5 shadow-theme-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-800">Total MA (Bersih)</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs">MA</span>
            </div>
            <div class="text-3xl font-extrabold text-blue-700">{{ $maData['total_bersih'] }}</div>
            <div class="text-xs text-gray-500 mt-1">
                Internal: <strong>{{ $maData['items']['internal']['total'] }}</strong> • Gel 1 &amp; 2: <strong>{{ $maData['items']['gel_1']['total'] + $maData['items']['gel_2']['total'] }}</strong>
            </div>
            <div class="text-[11px] text-rose-600 mt-0.5">
                Mundur: {{ $maData['mengundurkan_diri']['total'] }} anak
            </div>
        </div>

        <!-- Card 3: Total Mengundurkan Diri -->
        <div class="rounded-2xl border border-rose-200 bg-white p-5 shadow-theme-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-800">Mengundurkan Diri</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-700 flex items-center justify-center font-bold text-xs">✕</span>
            </div>
            <div class="text-3xl font-extrabold text-rose-600">{{ $grandTotal['mengundurkan_diri'] }}</div>
            <div class="text-xs text-gray-500 mt-1">
                MTs: <strong>{{ $mtsData['mengundurkan_diri']['total'] }}</strong> • MA: <strong>{{ $maData['mengundurkan_diri']['total'] }}</strong>
            </div>
            <div class="text-[11px] text-gray-400 mt-0.5">
                Total pembatalan pendaftaran
            </div>
        </div>

        <!-- Card 4: Grand Total Keseluruhan -->
        <div class="rounded-2xl border-2 border-indigo-500 bg-indigo-900 text-white p-5 shadow-theme-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Grand Total Keseluruhan</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold">AKTIF</span>
            </div>
            <div class="text-3xl font-black text-white">{{ $grandTotal['keseluruhan'] }} <span class="text-sm font-normal text-indigo-300">anak</span></div>
            <div class="text-xs text-indigo-200 mt-1">
                Putra: <strong>{{ $grandTotal['putra'] }}</strong> • Putri: <strong>{{ $grandTotal['putri'] }}</strong>
            </div>
            <div class="text-[11px] text-indigo-300 mt-0.5">
                Total santri baru resmi terdaftar
            </div>
        </div>
    </div>

    <!-- TABEL REKAPITULASI MATRIKS DETAIL -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h3 class="text-base font-bold text-gray-900">Tabel Matriks Distribusi Pendaftar</h3>
                <p class="text-xs text-gray-500">Rincian per jenjang, gelombang penerimaan, jenis kelamin, serta status pengunduran diri.</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700">
                Posisi Cut-Off: {{ $tanggalLengkapFormatted }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/70 text-gray-600 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3 px-4">Jenjang Pendidikan</th>
                        <th class="py-3 px-4">Gelombang / Jalur</th>
                        <th class="py-3 px-3 text-center">Putra (L)</th>
                        <th class="py-3 px-3 text-center">Putri (P)</th>
                        <th class="py-3 px-4 text-center">Terdaftar (Gross)</th>
                        <th class="py-3 px-4 text-center text-rose-600">Mundur</th>
                        <th class="py-3 px-4 text-center font-extrabold text-emerald-800">Total Bersih (Aktif)</th>
                        <th class="py-3 px-4 text-right">Persentase</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    
                    <!-- MTs Gelombang 1 -->
                    <tr class="hover:bg-gray-50/60 transition">
                        <td rowspan="3" class="py-3.5 px-4 font-bold text-emerald-800 bg-emerald-50/30 align-top border-r border-gray-100">
                            Madrasah Tsanawiyah (MTs)
                        </td>
                        <td class="py-3 px-4 font-semibold text-gray-800">
                            Gelombang 1
                        </td>
                        <td class="py-3 px-3 text-center font-mono">{{ $mtsData['items']['gel_1']['putra'] }}</td>
                        <td class="py-3 px-3 text-center font-mono">{{ $mtsData['items']['gel_1']['putri'] }}</td>
                        <td class="py-3 px-4 text-center font-bold font-mono">{{ $mtsData['items']['gel_1']['total'] }}</td>
                        <td class="py-3 px-4 text-center text-rose-600 font-mono">—</td>
                        <td class="py-3 px-4 text-center font-bold text-emerald-700 font-mono">{{ $mtsData['items']['gel_1']['total'] }}</td>
                        <td class="py-3 px-4 text-right text-xs text-gray-500 font-mono">
                            {{ $grandTotal['keseluruhan'] > 0 ? round(($mtsData['items']['gel_1']['total'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%
                        </td>
                    </tr>

                    <!-- MTs Gelombang 2 -->
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3 px-4 font-semibold text-gray-800">
                            Gelombang 2
                        </td>
                        <td class="py-3 px-3 text-center font-mono">{{ $mtsData['items']['gel_2']['putra'] }}</td>
                        <td class="py-3 px-3 text-center font-mono">{{ $mtsData['items']['gel_2']['putri'] }}</td>
                        <td class="py-3 px-4 text-center font-bold font-mono">{{ $mtsData['items']['gel_2']['total'] }}</td>
                        <td class="py-3 px-4 text-center text-rose-600 font-mono">—</td>
                        <td class="py-3 px-4 text-center font-bold text-emerald-700 font-mono">{{ $mtsData['items']['gel_2']['total'] }}</td>
                        <td class="py-3 px-4 text-right text-xs text-gray-500 font-mono">
                            {{ $grandTotal['keseluruhan'] > 0 ? round(($mtsData['items']['gel_2']['total'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%
                        </td>
                    </tr>

                    <!-- MTs Pengurangan / Mundur & Subtotal -->
                    <tr class="bg-emerald-50/20 font-bold border-b-2 border-emerald-100">
                        <td class="py-2.5 px-4 text-rose-700 italic">
                            Pengurangan: Mengundurkan Diri
                        </td>
                        <td class="py-2.5 px-3 text-center font-mono text-rose-600">{{ $mtsData['mengundurkan_diri']['putra'] }}</td>
                        <td class="py-2.5 px-3 text-center font-mono text-rose-600">{{ $mtsData['mengundurkan_diri']['putri'] }}</td>
                        <td class="py-2.5 px-4 text-center font-mono text-gray-500">{{ $mtsData['total_pendaftar_kotor'] }}</td>
                        <td class="py-2.5 px-4 text-center font-mono text-rose-700 font-extrabold">-{{ $mtsData['mengundurkan_diri']['total'] }}</td>
                        <td class="py-2.5 px-4 text-center font-extrabold font-mono text-emerald-800 text-base">
                            {{ $mtsData['total_bersih'] }}
                        </td>
                        <td class="py-2.5 px-4 text-right text-xs text-emerald-700 font-bold font-mono">
                            {{ $grandTotal['keseluruhan'] > 0 ? round(($mtsData['total_bersih'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%
                        </td>
                    </tr>

                    <!-- MA Internal -->
                    <tr class="hover:bg-gray-50/60 transition">
                        <td rowspan="4" class="py-3.5 px-4 font-bold text-blue-800 bg-blue-50/30 align-top border-r border-gray-100">
                            Madrasah Aliyah (MA)
                        </td>
                        <td class="py-3 px-4 font-semibold text-gray-800">
                            <div>Internal (Lanjutan MTs)</div>
                            <div class="text-[11px] text-indigo-600 font-normal">Putri: {{ $maData['items']['internal']['putri'] }} anak, Putra: {{ $maData['items']['internal']['putra'] }} anak</div>
                        </td>
                        <td class="py-3 px-3 text-center font-mono">{{ $maData['items']['internal']['putra'] }}</td>
                        <td class="py-3 px-3 text-center font-mono">{{ $maData['items']['internal']['putri'] }}</td>
                        <td class="py-3 px-4 text-center font-bold font-mono">{{ $maData['items']['internal']['total'] }}</td>
                        <td class="py-3 px-4 text-center text-rose-600 font-mono">—</td>
                        <td class="py-3 px-4 text-center font-bold text-blue-700 font-mono">{{ $maData['items']['internal']['total'] }}</td>
                        <td class="py-3 px-4 text-right text-xs text-gray-500 font-mono">
                            {{ $grandTotal['keseluruhan'] > 0 ? round(($maData['items']['internal']['total'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%
                        </td>
                    </tr>

                    <!-- MA Gelombang 1 -->
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3 px-4 font-semibold text-gray-800">
                            Gelombang 1
                        </td>
                        <td class="py-3 px-3 text-center font-mono">{{ $maData['items']['gel_1']['putra'] }}</td>
                        <td class="py-3 px-3 text-center font-mono">{{ $maData['items']['gel_1']['putri'] }}</td>
                        <td class="py-3 px-4 text-center font-bold font-mono">{{ $maData['items']['gel_1']['total'] }}</td>
                        <td class="py-3 px-4 text-center text-rose-600 font-mono">—</td>
                        <td class="py-3 px-4 text-center font-bold text-blue-700 font-mono">{{ $maData['items']['gel_1']['total'] }}</td>
                        <td class="py-3 px-4 text-right text-xs text-gray-500 font-mono">
                            {{ $grandTotal['keseluruhan'] > 0 ? round(($maData['items']['gel_1']['total'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%
                        </td>
                    </tr>

                    <!-- MA Gelombang 2 -->
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3 px-4 font-semibold text-gray-800">
                            Gelombang 2
                        </td>
                        <td class="py-3 px-3 text-center font-mono">{{ $maData['items']['gel_2']['putra'] }}</td>
                        <td class="py-3 px-3 text-center font-mono">{{ $maData['items']['gel_2']['putri'] }}</td>
                        <td class="py-3 px-4 text-center font-bold font-mono">{{ $maData['items']['gel_2']['total'] }}</td>
                        <td class="py-3 px-4 text-center text-rose-600 font-mono">—</td>
                        <td class="py-3 px-4 text-center font-bold text-blue-700 font-mono">{{ $maData['items']['gel_2']['total'] }}</td>
                        <td class="py-3 px-4 text-right text-xs text-gray-500 font-mono">
                            {{ $grandTotal['keseluruhan'] > 0 ? round(($maData['items']['gel_2']['total'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%
                        </td>
                    </tr>

                    <!-- MA Pengurangan / Mundur & Subtotal -->
                    <tr class="bg-blue-50/20 font-bold border-b-2 border-blue-100">
                        <td class="py-2.5 px-4 text-rose-700 italic">
                            Pengurangan: Mengundurkan Diri
                        </td>
                        <td class="py-2.5 px-3 text-center font-mono text-rose-600">{{ $maData['mengundurkan_diri']['putra'] }}</td>
                        <td class="py-2.5 px-3 text-center font-mono text-rose-600">{{ $maData['mengundurkan_diri']['putri'] }}</td>
                        <td class="py-2.5 px-4 text-center font-mono text-gray-500">{{ $maData['total_pendaftar_kotor'] }}</td>
                        <td class="py-2.5 px-4 text-center font-mono text-rose-700 font-extrabold">-{{ $maData['mengundurkan_diri']['total'] }}</td>
                        <td class="py-2.5 px-4 text-center font-extrabold font-mono text-blue-800 text-base">
                            {{ $maData['total_bersih'] }}
                        </td>
                        <td class="py-2.5 px-4 text-right text-xs text-blue-700 font-bold font-mono">
                            {{ $grandTotal['keseluruhan'] > 0 ? round(($maData['total_bersih'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%
                        </td>
                    </tr>

                </tbody>
                <tfoot>
                    <tr class="bg-gray-900 text-white font-black text-xs sm:text-sm">
                        <td colspan="2" class="py-4 px-4 uppercase tracking-wider text-emerald-300">
                            GRAND TOTAL KESELURUHAN
                        </td>
                        <td class="py-4 px-3 text-center font-mono font-bold">{{ $grandTotal['putra'] }}</td>
                        <td class="py-4 px-3 text-center font-mono font-bold">{{ $grandTotal['putri'] }}</td>
                        <td class="py-4 px-4 text-center font-mono font-bold">{{ $grandTotal['pendaftar_kotor'] }}</td>
                        <td class="py-4 px-4 text-center font-mono text-rose-300 font-bold">-{{ $grandTotal['mengundurkan_diri'] }}</td>
                        <td class="py-4 px-4 text-center font-mono text-emerald-400 font-black text-lg">
                            {{ $grandTotal['keseluruhan'] }}
                        </td>
                        <td class="py-4 px-4 text-right font-mono text-emerald-300">100.0%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- TABEL NOMINATIF SAMPEL / PENDAFTAR CUT-OFF -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Daftar Calon Santri (Hingga {{ $tanggalFormatted }})</h3>
                <p class="text-xs text-gray-500">Menampilkan {{ count($santriList) }} data calon santri yang tercatat dalam pelaporan.</p>
            </div>

            @if($modeData === 'contoh')
            <!-- Tombol Tambahan: Sinkronkan 256 Data Contoh ke Database Riil -->
            <form action="{{ route('admin.psb.laporan.seedContoh') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengisi database dengan 256 data santri contoh sesuai laporan?')">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold transition">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>✨ Masukkan 256 Data Contoh ke Database</span>
                </button>
            </form>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/70 text-gray-600 font-bold uppercase text-[10.5px]">
                        <th class="py-2.5 px-3">No</th>
                        <th class="py-2.5 px-3">No. Registrasi</th>
                        <th class="py-2.5 px-3">Nama Calon Santri</th>
                        <th class="py-2.5 px-3">Jenjang</th>
                        <th class="py-2.5 px-3">Gelombang / Jalur</th>
                        <th class="py-2.5 px-3 text-center">L/P</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($santriList as $idx => $s)
                    <tr class="hover:bg-gray-50/60 transition {{ ($s->status ?? '') === 'Mengundurkan Diri' ? 'bg-rose-50/30' : '' }}">
                        <td class="py-2 px-3 text-gray-400 font-mono">{{ $idx + 1 }}</td>
                        <td class="py-2 px-3 font-mono font-bold text-gray-700">{{ $s->no_registrasi ?? ('ID #' . $s->id) }}</td>
                        <td class="py-2 px-3 font-semibold text-gray-900">{{ $s->nama_lengkap }}</td>
                        <td class="py-2 px-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ str_contains(strtoupper($s->jenjang ?? ''), 'MTS') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                {{ $s->jenjang }}
                            </span>
                        </td>
                        <td class="py-2 px-3 text-gray-600">
                            {{ $s->gelombang ?? 'Gelombang 1' }} ({{ $s->jalur ?? 'Reguler' }})
                        </td>
                        <td class="py-2 px-3 text-center">
                            @if(in_array(strtolower($s->jenis_kelamin ?? ''), ['laki-laki', 'putra', 'l']))
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">L</span>
                            @else
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-pink-100 text-pink-800">P</span>
                            @endif
                        </td>
                        <td class="py-2 px-3 text-center">
                            @if(($s->status ?? '') === 'Diterima')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Diterima</span>
                            @elseif(($s->status ?? '') === 'Mengundurkan Diri')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Mundur</span>
                            @elseif(($s->status ?? '') === 'Ditolak')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-200 text-gray-800">Ditolak</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">
                            Tidak ada data calon santri yang tercatat sebelum tanggal cut-off terpilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Hidden textarea for copying WhatsApp text -->
<textarea id="hiddenBroadcastText" class="sr-only" aria-hidden="true">{{ $waBroadcastText }}</textarea>

<script>
function salinFormatLaporan() {
    const text = document.getElementById('hiddenBroadcastText').value;
    const btn = document.getElementById('btnSalinLaporan');
    const label = document.getElementById('labelSalinText');

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => {
            showSuccessCopy();
        }).catch(() => {
            fallbackCopy(text);
        });
    } else {
        fallbackCopy(text);
    }

    function showSuccessCopy() {
        const oldLabel = label.textContent;
        label.textContent = '✓ Berhasil Disalin!';
        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
        btn.classList.add('bg-indigo-700');
        setTimeout(() => {
            label.textContent = oldLabel;
            btn.classList.remove('bg-indigo-700');
            btn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
        }, 2500);
    }

    function fallbackCopy(str) {
        const ta = document.getElementById('hiddenBroadcastText');
        ta.classList.remove('sr-only');
        ta.select();
        try {
            document.execCommand('copy');
            showSuccessCopy();
        } catch (e) {
            alert('Silakan salin teks secara manual.');
        }
        ta.classList.add('sr-only');
    }
}
</script>
@endsection

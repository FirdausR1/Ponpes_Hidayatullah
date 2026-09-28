@extends('admin.layout')

@section('title', 'Laporan PSB — TA ' . $tahunAjaran)

@section('content')
<div class="space-y-6">

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700 border border-gray-200">
                    Laporan PSB
                </span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-semibold text-gray-600">Tahun Ajaran {{ $tahunAjaran }}</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Laporan Harian Pendaftaran Santri (PSB)</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                Rekapitulasi jumlah pendaftar santri baru jenjang MTs dan MA per gelombang serta santri yang mengundurkan diri.
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('admin.psb.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold shadow-theme-xs transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Data Pendaftar PSB</span>
            </a>

            <a href="{{ route('admin.psb.laporan.cetak', request()->all()) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-theme-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                <span>Cetak Laporan Lengkap KOP</span>
            </a>
        </div>
    </div>

    <!-- Filter Laporan & Pilihan Data -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
        <form method="GET" action="{{ route('admin.psb.laporan') }}" class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            
            <div class="flex flex-wrap items-end gap-3">
                <input type="hidden" name="mode_data" value="{{ $modeData }}">

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tanggal Laporan</label>
                    <input type="date" name="per_tanggal" value="{{ $perTanggal }}" onchange="this.form.submit()" 
                           class="h-10 px-3 text-xs sm:text-sm font-medium text-gray-800 bg-white border border-gray-300 rounded-lg focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" value="{{ $tahunAjaran }}" placeholder="2026/2027" 
                           class="h-10 w-28 px-3 text-xs sm:text-sm font-medium text-gray-800 bg-white border border-gray-300 rounded-lg focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                </div>

                <button type="submit" class="h-10 px-4 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition cursor-pointer">
                    Tampilkan
                </button>
            </div>

            <!-- Tombol Switch Sederhana -->
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500">Tampilan Data:</span>
                <a href="{{ route('admin.psb.laporan', array_merge(request()->except('mode_data'), ['mode_data' => 'contoh', 'per_tanggal' => '2026-07-04'])) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition border {{ $modeData === 'contoh' ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    Contoh Laporan (256 Santri)
                </a>
                <a href="{{ route('admin.psb.laporan', array_merge(request()->except('mode_data'), ['mode_data' => 'real', 'per_tanggal' => date('Y-m-d')])) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition border {{ $modeData === 'real' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    Data Database Saat Ini ({{ $realCount }})
                </a>
            </div>
        </form>
    </div>

    <!-- Kotak Format Laporan Harian (Sesuai Contoh yang Diminta) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div class="flex items-center justify-between gap-4 mb-4 pb-3 border-b border-gray-100">
            <div>
                <h2 class="text-base font-bold text-gray-900">Format Laporan Harian (Actual Daily Report)</h2>
                <p class="text-xs text-gray-500 mt-0.5">Format ringkasan harian untuk pimpinan pondok atau dibagikan ke grup pengurus.</p>
            </div>

            <button type="button" onclick="salinTeksLaporan()" id="btnSalin" 
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-3 py-1.5 text-xs font-semibold shadow-xs transition cursor-pointer"
                    title="Salin teks laporan ke clipboard">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                <span id="labelSalin">Salin Teks untuk WhatsApp</span>
            </button>
        </div>

        <!-- Box Teks Sederhana & Jelas -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 font-mono text-xs sm:text-sm text-gray-800 leading-relaxed">
            <div class="font-bold text-gray-900 mb-2">
                Actual daily report PSB Tahun Ajaran {{ $tahunAjaran }}
            </div>

            <div class="text-gray-700 mb-3 font-semibold">
                * Per tanggal: {{ $tanggalFormatted }}
            </div>

            <!-- MTs -->
            <div class="mb-3">
                <div class="font-bold text-gray-900 mb-1">* Jumlah pendaftar MTs</div>
                <div class="pl-4 space-y-0.5 text-gray-700">
                    <div>° Gel. 1 = {{ $mtsData['items']['gel_1']['total'] }} anak</div>
                    <div>° Gel. 2 = {{ $mtsData['items']['gel_2']['total'] }} anak</div>
                    @if(($mtsData['items']['internal']['total'] ?? 0) > 0)
                        <div>° Internal = {{ $mtsData['items']['internal']['total'] }} anak</div>
                    @endif
                    <div class="text-rose-600">° Mengundurkan diri = {{ $mtsData['mengundurkan_diri']['total'] }} anak</div>
                    <div class="font-bold text-gray-900 pt-0.5">Total MTs = {{ $mtsData['total_bersih'] }} anak</div>
                </div>
            </div>

            <!-- MA -->
            <div class="mb-3">
                <div class="font-bold text-gray-900 mb-1">• Jumlah pendaftar MA</div>
                <div class="pl-4 space-y-0.5 text-gray-700">
                    <div>
                        ° Internal = {{ $maData['items']['internal']['total'] }} anak
                        @if(!empty($maData['items']['internal']['catatan']))
                            <span class="text-gray-600 font-sans font-normal text-xs">(putri : {{ $maData['items']['internal']['putri'] }} anak, putra : {{ $maData['items']['internal']['putra'] }} anak)</span>
                        @endif
                    </div>
                    <div>° Gel. 1 = {{ $maData['items']['gel_1']['total'] }} anak</div>
                    <div>° Gel. 2 = {{ $maData['items']['gel_2']['total'] }} anak</div>
                    <div class="text-rose-600">° Mengundurkan diri = {{ $maData['mengundurkan_diri']['total'] }} anak</div>
                    <div class="font-bold text-gray-900 pt-0.5">Total MA = {{ $maData['total_bersih'] }} anak</div>
                </div>
            </div>

            <!-- Total -->
            <div class="pt-2 border-t border-gray-300 space-y-0.5 font-bold">
                <div>~ Grand Total Pendaftar = {{ $grandTotal['pendaftar'] }} anak</div>
                <div class="text-rose-600">~ Grand Total Mengundurkan diri = {{ $grandTotal['mengundurkan_diri'] }} anak</div>
                <div class="text-emerald-700 text-sm">~ Grand Total Keseluruhan = {{ $grandTotal['keseluruhan'] }} anak</div>
            </div>
        </div>
    </div>

    <!-- 4 Kotak Ringkasan Angka (Standar Template Admin) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- MTs -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Total MTs (Bersih)</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-gray-900">{{ $mtsData['total_bersih'] }}</span>
                <span class="text-xs text-gray-500">anak</span>
            </div>
            <div class="mt-2 text-xs text-gray-500 border-t border-gray-100 pt-2">
                Gel 1: <strong>{{ $mtsData['items']['gel_1']['total'] }}</strong> • Gel 2: <strong>{{ $mtsData['items']['gel_2']['total'] }}</strong> • Mundur: <strong class="text-rose-600">{{ $mtsData['mengundurkan_diri']['total'] }}</strong>
            </div>
        </div>

        <!-- MA -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Total MA (Bersih)</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-gray-900">{{ $maData['total_bersih'] }}</span>
                <span class="text-xs text-gray-500">anak</span>
            </div>
            <div class="mt-2 text-xs text-gray-500 border-t border-gray-100 pt-2">
                Internal: <strong>{{ $maData['items']['internal']['total'] }}</strong> • Gel 1 &amp; 2: <strong>{{ $maData['items']['gel_1']['total'] + $maData['items']['gel_2']['total'] }}</strong> • Mundur: <strong class="text-rose-600">{{ $maData['mengundurkan_diri']['total'] }}</strong>
            </div>
        </div>

        <!-- Mengundurkan Diri -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Mengundurkan Diri</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-rose-600">{{ $grandTotal['mengundurkan_diri'] }}</span>
                <span class="text-xs text-gray-500">anak</span>
            </div>
            <div class="mt-2 text-xs text-gray-500 border-t border-gray-100 pt-2">
                MTs: <strong>{{ $mtsData['mengundurkan_diri']['total'] }}</strong> • MA: <strong>{{ $maData['mengundurkan_diri']['total'] }}</strong>
            </div>
        </div>

        <!-- Grand Total -->
        <div class="rounded-2xl border border-emerald-300 bg-emerald-50/50 p-5 shadow-theme-xs">
            <span class="text-xs font-semibold text-emerald-800 uppercase tracking-wider block">Grand Total Santri Baru</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-emerald-800">{{ $grandTotal['keseluruhan'] }}</span>
                <span class="text-xs text-emerald-700">anak</span>
            </div>
            <div class="mt-2 text-xs text-emerald-800 border-t border-emerald-200 pt-2">
                Putra: <strong>{{ $grandTotal['putra'] }}</strong> • Putri: <strong>{{ $grandTotal['putri'] }}</strong>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Data Lengkap -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-gray-900">Rincian Pendaftar per Gelombang</h3>
            <span class="text-xs text-gray-500">Per tanggal {{ $tanggalFormatted }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 text-gray-600 font-semibold text-xs">
                        <th class="py-3 px-4">Jenjang</th>
                        <th class="py-3 px-4">Gelombang / Jalur</th>
                        <th class="py-3 px-3 text-center">Putra</th>
                        <th class="py-3 px-3 text-center">Putri</th>
                        <th class="py-3 px-4 text-center">Jumlah Daftar</th>
                        <th class="py-3 px-4 text-center text-rose-600">Mundur</th>
                        <th class="py-3 px-4 text-center font-bold text-emerald-800">Total Diterima</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- MTs Gelombang 1 -->
                    <tr>
                        <td rowspan="3" class="py-3 px-4 font-semibold text-gray-900 bg-gray-50/50 align-top border-r border-gray-100">
                            MTs
                        </td>
                        <td class="py-2.5 px-4 text-gray-800">Gelombang 1</td>
                        <td class="py-2.5 px-3 text-center">{{ $mtsData['items']['gel_1']['putra'] }}</td>
                        <td class="py-2.5 px-3 text-center">{{ $mtsData['items']['gel_1']['putri'] }}</td>
                        <td class="py-2.5 px-4 text-center font-semibold">{{ $mtsData['items']['gel_1']['total'] }}</td>
                        <td class="py-2.5 px-4 text-center text-gray-400">—</td>
                        <td class="py-2.5 px-4 text-center font-semibold text-emerald-700">{{ $mtsData['items']['gel_1']['total'] }}</td>
                    </tr>
                    <!-- MTs Gelombang 2 -->
                    <tr>
                        <td class="py-2.5 px-4 text-gray-800">Gelombang 2</td>
                        <td class="py-2.5 px-3 text-center">{{ $mtsData['items']['gel_2']['putra'] }}</td>
                        <td class="py-2.5 px-3 text-center">{{ $mtsData['items']['gel_2']['putri'] }}</td>
                        <td class="py-2.5 px-4 text-center font-semibold">{{ $mtsData['items']['gel_2']['total'] }}</td>
                        <td class="py-2.5 px-4 text-center text-gray-400">—</td>
                        <td class="py-2.5 px-4 text-center font-semibold text-emerald-700">{{ $mtsData['items']['gel_2']['total'] }}</td>
                    </tr>
                    <!-- MTs Total -->
                    <tr class="bg-gray-50/70 font-semibold border-b border-gray-200">
                        <td class="py-2.5 px-4 text-gray-700">Subtotal MTs (dikurangi mundur)</td>
                        <td class="py-2.5 px-3 text-center text-gray-600">{{ $mtsData['items']['gel_1']['putra'] + $mtsData['items']['gel_2']['putra'] }}</td>
                        <td class="py-2.5 px-3 text-center text-gray-600">{{ $mtsData['items']['gel_1']['putri'] + $mtsData['items']['gel_2']['putri'] }}</td>
                        <td class="py-2.5 px-4 text-center text-gray-700">{{ $mtsData['total_pendaftar_kotor'] }}</td>
                        <td class="py-2.5 px-4 text-center text-rose-600 font-bold">-{{ $mtsData['mengundurkan_diri']['total'] }}</td>
                        <td class="py-2.5 px-4 text-center font-bold text-emerald-800">{{ $mtsData['total_bersih'] }} anak</td>
                    </tr>

                    <!-- MA Internal -->
                    <tr>
                        <td rowspan="4" class="py-3 px-4 font-semibold text-gray-900 bg-gray-50/50 align-top border-r border-gray-100">
                            MA
                        </td>
                        <td class="py-2.5 px-4 text-gray-800">
                            Internal (MTs ke MA)
                        </td>
                        <td class="py-2.5 px-3 text-center">{{ $maData['items']['internal']['putra'] }}</td>
                        <td class="py-2.5 px-3 text-center">{{ $maData['items']['internal']['putri'] }}</td>
                        <td class="py-2.5 px-4 text-center font-semibold">{{ $maData['items']['internal']['total'] }}</td>
                        <td class="py-2.5 px-4 text-center text-gray-400">—</td>
                        <td class="py-2.5 px-4 text-center font-semibold text-emerald-700">{{ $maData['items']['internal']['total'] }}</td>
                    </tr>
                    <!-- MA Gelombang 1 -->
                    <tr>
                        <td class="py-2.5 px-4 text-gray-800">Gelombang 1</td>
                        <td class="py-2.5 px-3 text-center">{{ $maData['items']['gel_1']['putra'] }}</td>
                        <td class="py-2.5 px-3 text-center">{{ $maData['items']['gel_1']['putri'] }}</td>
                        <td class="py-2.5 px-4 text-center font-semibold">{{ $maData['items']['gel_1']['total'] }}</td>
                        <td class="py-2.5 px-4 text-center text-gray-400">—</td>
                        <td class="py-2.5 px-4 text-center font-semibold text-emerald-700">{{ $maData['items']['gel_1']['total'] }}</td>
                    </tr>
                    <!-- MA Gelombang 2 -->
                    <tr>
                        <td class="py-2.5 px-4 text-gray-800">Gelombang 2</td>
                        <td class="py-2.5 px-3 text-center">{{ $maData['items']['gel_2']['putra'] }}</td>
                        <td class="py-2.5 px-3 text-center">{{ $maData['items']['gel_2']['putri'] }}</td>
                        <td class="py-2.5 px-4 text-center font-semibold">{{ $maData['items']['gel_2']['total'] }}</td>
                        <td class="py-2.5 px-4 text-center text-gray-400">—</td>
                        <td class="py-2.5 px-4 text-center font-semibold text-emerald-700">{{ $maData['items']['gel_2']['total'] }}</td>
                    </tr>
                    <!-- MA Total -->
                    <tr class="bg-gray-50/70 font-semibold border-b border-gray-200">
                        <td class="py-2.5 px-4 text-gray-700">Subtotal MA (dikurangi mundur)</td>
                        <td class="py-2.5 px-3 text-center text-gray-600">{{ $maData['items']['internal']['putra'] + $maData['items']['gel_1']['putra'] + $maData['items']['gel_2']['putra'] }}</td>
                        <td class="py-2.5 px-3 text-center text-gray-600">{{ $maData['items']['internal']['putri'] + $maData['items']['gel_1']['putri'] + $maData['items']['gel_2']['putri'] }}</td>
                        <td class="py-2.5 px-4 text-center text-gray-700">{{ $maData['total_pendaftar_kotor'] }}</td>
                        <td class="py-2.5 px-4 text-center text-rose-600 font-bold">-{{ $maData['mengundurkan_diri']['total'] }}</td>
                        <td class="py-2.5 px-4 text-center font-bold text-emerald-800">{{ $maData['total_bersih'] }} anak</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 font-bold text-gray-900">
                        <td colspan="2" class="py-3 px-4">GRAND TOTAL KESELURUHAN</td>
                        <td class="py-3 px-3 text-center">{{ $grandTotal['putra'] }}</td>
                        <td class="py-3 px-3 text-center">{{ $grandTotal['putri'] }}</td>
                        <td class="py-3 px-4 text-center">{{ $grandTotal['pendaftar_kotor'] }}</td>
                        <td class="py-3 px-4 text-center text-rose-600">-{{ $grandTotal['mengundurkan_diri'] }}</td>
                        <td class="py-3 px-4 text-center text-emerald-700 text-base font-extrabold">{{ $grandTotal['keseluruhan'] }} anak</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Tabel Data Santri -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Daftar Pendaftar (Hingga {{ $tanggalFormatted }})</h3>
                <p class="text-xs text-gray-500">Terdapat {{ count($santriList) }} data santri.</p>
            </div>

            @if($modeData === 'contoh')
            <form action="{{ route('admin.psb.laporan.seedContoh') }}" method="POST" onsubmit="return confirm('Masukkan 256 data contoh ini ke database?')">
                @csrf
                <button type="submit" class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold transition">
                    Masukkan 256 Data Contoh ke Database
                </button>
            </form>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 text-gray-600 font-semibold">
                        <th class="py-2.5 px-3">No</th>
                        <th class="py-2.5 px-3">No. Registrasi</th>
                        <th class="py-2.5 px-3">Nama Santri</th>
                        <th class="py-2.5 px-3">Jenjang</th>
                        <th class="py-2.5 px-3">Gelombang / Jalur</th>
                        <th class="py-2.5 px-3 text-center">L/P</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($santriList as $idx => $s)
                    <tr class="hover:bg-gray-50/60 transition {{ ($s->status ?? '') === 'Mengundurkan Diri' ? 'bg-rose-50/20' : '' }}">
                        <td class="py-2 px-3 text-gray-500">{{ $idx + 1 }}</td>
                        <td class="py-2 px-3 font-mono font-medium text-gray-700">{{ $s->no_registrasi ?? ('ID #' . $s->id) }}</td>
                        <td class="py-2 px-3 font-semibold text-gray-900">{{ $s->nama_lengkap }}</td>
                        <td class="py-2 px-3 text-gray-700">{{ $s->jenjang }}</td>
                        <td class="py-2 px-3 text-gray-600">{{ $s->gelombang ?? 'Gelombang 1' }} ({{ $s->jalur ?? 'Reguler' }})</td>
                        <td class="py-2 px-3 text-center font-semibold">
                            @if(in_array(strtolower($s->jenis_kelamin ?? ''), ['laki-laki', 'putra', 'l']))
                                <span class="text-blue-700">L</span>
                            @else
                                <span class="text-rose-700">P</span>
                            @endif
                        </td>
                        <td class="py-2 px-3 text-center">
                            @if(($s->status ?? '') === 'Diterima')
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Diterima</span>
                            @elseif(($s->status ?? '') === 'Mengundurkan Diri')
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200">Mundur</span>
                            @elseif(($s->status ?? '') === 'Ditolak')
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-700 border border-gray-200">Ditolak</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-gray-400">
                            Tidak ada data pendaftaran yang tercatat sebelum tanggal terpilih.
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
function salinTeksLaporan() {
    const text = document.getElementById('hiddenBroadcastText').value;
    const btn = document.getElementById('btnSalin');
    const label = document.getElementById('labelSalin');

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => {
            beriTandaSukses();
        }).catch(() => {
            salinManual(text);
        });
    } else {
        salinManual(text);
    }

    function beriTandaSukses() {
        const teksLama = label.textContent;
        label.textContent = '✓ Berhasil Disalin';
        btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
        setTimeout(() => {
            label.textContent = teksLama;
            btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
        }, 2000);
    }

    function salinManual(str) {
        const ta = document.getElementById('hiddenBroadcastText');
        ta.classList.remove('sr-only');
        ta.select();
        try {
            document.execCommand('copy');
            beriTandaSukses();
        } catch (e) {
            alert('Silakan salin teks secara manual.');
        }
        ta.classList.add('sr-only');
    }
}
</script>
@endsection

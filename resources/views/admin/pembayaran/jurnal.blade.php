@extends('admin.layout')

@section('title', 'Buku Kas & Jurnal Matriks Pembukuan')

@section('content')
<div class="space-y-6" x-data="{ 
    modalExportTahunan: false, 
    tahunExport: '{{ date('Y') }}', 
    tipeTahunExport: 'kalender', 
    kelasExport: '' 
}">

    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200">Keuangan &amp; Laporan</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">Spreadsheet Pembukuan</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Buku Kas &amp; Jurnal Matriks</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Format pembukuan kasir terpusat 40 pos biaya pesantren (Makan, Syahriyah, SOT, Tabungan, Wisuda, Ziarah, dll.) sesuai format buku kas resmi.</p>
        </div>

        <!-- Action Buttons (TailAdmin Standard) -->
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-emerald-700 transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <span>Kasir Pembayaran</span>
            </a>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak</span>
            </button>
            <a href="{{ route('admin.pembayaran.jurnal.export', request()->all()) }}" class="inline-flex items-center gap-2 rounded-lg border border-emerald-300 bg-emerald-50 px-3.5 py-2.5 text-xs font-semibold text-emerald-800 shadow-theme-xs hover:bg-emerald-100 transition" title="Ekspor jurnal periode yang sedang difilter">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Ekspor Bulan Ini (.xlsx)</span>
            </a>
            <button type="button" @click="modalExportTahunan = true" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-emerald-700 transition" title="Ekspor pembukuan 1 tahun penuh dalam 1 file Excel multi-sheet">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Ekspor Tahunan (13 Sheet)</span>
            </button>
        </div>
    </div>

    <!-- Filter & Summary Bar (TailAdmin Form Style) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
        <form method="GET" action="{{ route('admin.pembayaran.jurnal.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" value="{{ $tglMulai }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-700 focus:border-brand-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" value="{{ $tglSelesai }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-700 focus:border-brand-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Filter Rombel / Kelas</label>
                <select name="kelas" onchange="this.form.submit()" class="h-10 w-full text-xs rounded-lg px-3 border border-gray-300 bg-white text-gray-700 outline-none cursor-pointer">
                    <option value="">Semua Kelas</option>
                    @foreach($classrooms as $c)
                        <option value="{{ $c->nama_kelas }}" {{ $kelasFilter == $c->nama_kelas ? 'selected' : '' }}>
                            {{ $c->nama_kelas }} ({{ $c->jenjang }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="h-10 flex-1 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-semibold transition">
                    Terapkan
                </button>
                @if(request()->anyFilled(['tgl_mulai', 'tgl_selesai', 'kelas']))
                    <a href="{{ route('admin.pembayaran.jurnal.index') }}" class="h-10 px-3 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-600 text-xs font-medium flex items-center justify-center transition" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>

            <div class="bg-emerald-50/80 border border-emerald-200 rounded-xl p-3 text-right sm:col-span-2 md:col-span-1">
                <div class="text-[10px] uppercase font-bold text-emerald-800 tracking-wider">Total Penerimaan</div>
                <div class="text-sm sm:text-base font-black font-mono text-emerald-700 mt-0.5">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
                <div class="text-[11px] text-gray-500 mt-0.5">{{ $payments->count() }} transaksi kasir</div>
            </div>
        </form>
    </div>

    <!-- Spreadsheet Matrix Table (TailAdmin Style) -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-5 py-4 bg-gray-50/70 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="text-xs font-bold text-gray-800 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                <span>Matriks Kasir 40 Pos Biaya (Periode: {{ date('d/m/Y', strtotime($tglMulai)) }} s/d {{ date('d/m/Y', strtotime($tglSelesai)) }})</span>
            </div>
            <span class="text-[11px] text-gray-500">Geser ke kanan untuk melihat rincian seluruh 40 pos biaya &rarr;</span>
        </div>

        <div class="overflow-x-auto max-h-[75vh] relative">
            <table class="w-full text-left text-xs border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-20 bg-gray-800 text-white font-bold text-[11px] tracking-wider uppercase">
                    <tr>
                        <th class="border border-gray-700 px-3 py-2.5 text-center w-12 sticky left-0 z-30 bg-gray-900">NO</th>
                        <th class="border border-gray-700 px-3 py-2.5 text-center w-24 sticky left-12 z-30 bg-gray-900">TANGGAL</th>
                        <th class="border border-gray-700 px-4 py-2.5 min-w-[180px] sticky left-36 z-30 bg-gray-900">NAMA SANTRI</th>
                        <th class="border border-gray-700 px-3 py-2.5 text-center min-w-[70px]">KELAS</th>
                        <th class="border border-gray-700 px-3 py-2.5 text-center min-w-[60px]">KET</th>
                        
                        <!-- 40 POS BIAYA COLUMNS -->
                        @foreach($posBiayaList as $posKey => $posLabel)
                            <th class="border border-gray-700 px-3 py-2 text-right min-w-[110px] font-mono {{ in_array($posKey, ['MAKAN', 'SYAHRIYAH', 'SOT', 'TAB']) ? 'bg-emerald-900 text-emerald-100' : '' }}" title="{{ $posLabel }}">
                                {{ $posKey }}
                            </th>
                        @endforeach

                        <th class="border border-gray-700 px-4 py-2.5 text-right min-w-[130px] bg-emerald-800 text-white font-bold sticky right-0 z-20">
                            TOTAL
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-800 text-xs">
                    @forelse($payments as $idx => $p)
                        @php
                            // Index items by pos_biaya for quick O(1) lookup
                            $itemsByPos = [];
                            foreach ($p->items as $item) {
                                $itemsByPos[$item->pos_biaya] = ($itemsByPos[$item->pos_biaya] ?? 0) + $item->nominal;
                            }
                            // Fallback jika item kosong (legacy)
                            if (empty($itemsByPos) && $p->nominal > 0) {
                                $legacyPos = strtoupper(trim($p->jenis_pembayaran));
                                $itemsByPos[$legacyPos] = $p->nominal;
                            }
                        @endphp
                        <tr class="hover:bg-amber-50/40 transition">
                            <td class="border border-gray-200 px-3 py-2.5 text-center text-gray-500 sticky left-0 bg-white font-medium">
                                {{ $idx + 1 }}
                            </td>
                            <td class="border border-gray-200 px-3 py-2.5 text-center font-mono text-gray-700 sticky left-12 bg-white">
                                {{ optional($p->tanggal_bayar)->format('d/m/Y') }}
                            </td>
                            <td class="border border-gray-200 px-4 py-2.5 font-bold text-gray-900 sticky left-36 bg-white">
                                <a href="{{ $p->psb_registration_id && !$p->student_id ? route('admin.pembayaran.psb.kwitansi', $p->psb_registration_id) : route('admin.pembayaran.kwitansi', $p->id) }}" target="_blank" class="hover:text-emerald-600 hover:underline flex items-center gap-1.5" title="Buka Kwitansi">
                                    {{ $p->student->nama_lengkap ?? ($p->psbRegistration->nama_lengkap ?? '—') }}
                                    <span class="text-[10px] text-gray-400 font-normal">#{{ substr($p->no_transaksi, -5) }}</span>
                                </a>
                            </td>
                            <td class="border border-gray-200 px-3 py-2.5 text-center font-semibold text-emerald-800 bg-gray-50/50">
                                {{ $p->student->kelas ?? ($p->psbRegistration ? 'Calon (' . $p->psbRegistration->jenjang . ')' : '—') }}
                            </td>
                            <td class="border border-gray-200 px-3 py-2.5 text-center text-gray-600 uppercase font-semibold text-[11px]">
                                {{ $p->bulan ?: 'MEI' }}
                            </td>

                            <!-- 40 Pos Biaya Cells -->
                            @foreach($posBiayaList as $posKey => $posLabel)
                                @php
                                    $amount = $itemsByPos[$posKey] ?? 0;
                                @endphp
                                <td class="border border-gray-200 px-3 py-2.5 text-right font-mono {{ $amount > 0 ? 'text-gray-900 font-bold bg-amber-50/30' : 'text-gray-300' }}">
                                    {{ $amount > 0 ? number_format($amount, 0, ',', '.') : '-' }}
                                </td>
                            @endforeach

                            <!-- Total Row Cell -->
                            <td class="border border-gray-200 px-4 py-2.5 text-right font-mono font-bold text-emerald-800 bg-emerald-50/60 sticky right-0">
                                {{ number_format($p->nominal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($posBiayaList) + 6 }}" class="text-center py-12 text-gray-400 text-xs bg-white">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="font-medium text-gray-500">Tidak ada data transaksi pembayaran pada rentang tanggal dan filter ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="sticky bottom-0 z-20 bg-gray-900 text-white font-bold text-xs">
                    <tr>
                        <td colspan="5" class="border border-gray-700 px-4 py-3 text-right uppercase tracking-wider sticky left-0 z-30 bg-gray-900">
                            TOTAL PENERIMAAN KASIR:
                        </td>

                        <!-- Column Totals -->
                        @foreach($posBiayaList as $posKey => $posLabel)
                            @php
                                $cTotal = $colTotals[$posKey] ?? 0;
                            @endphp
                            <td class="border border-gray-700 px-3 py-3 text-right font-mono {{ $cTotal > 0 ? 'text-emerald-300 font-bold' : 'text-gray-500' }}">
                                {{ $cTotal > 0 ? number_format($cTotal, 0, ',', '.') : '-' }}
                            </td>
                        @endforeach

                        <td class="border border-gray-700 px-4 py-3 text-right font-mono font-black text-amber-300 text-sm bg-gray-950 sticky right-0 z-20">
                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- MODAL EKSPOR BUKU KAS TAHUNAN MULTI-SHEET EXCEL (TailAdmin Modal Style) -->
    <div x-show="modalExportTahunan" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalExportTahunan = false" class="ta-modal max-w-md">
            <div class="ta-modal-header">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Ekspor Kas Tahunan Multi-Sheet</h3>
                    <p class="text-xs text-gray-500 mt-0.5">1 File Excel memuat 13 sheet terpisah rapi</p>
                </div>
                <button type="button" @click="modalExportTahunan = false" class="text-gray-400 hover:text-gray-600 transition text-lg">&times;</button>
            </div>

            <form method="GET" action="{{ route('admin.pembayaran.jurnal.exportTahunan') }}">
                <div class="ta-modal-body space-y-4 text-xs">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Pilih Tahun Pembukuan *</label>
                        <select name="tahun" x-model="tahunExport" required class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-bold text-gray-900 focus:border-brand-500 outline-none bg-white">
                            @for($y = date('Y') + 1; $y >= 2024; $y--)
                                <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>Tahun {{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Format Pembagian 12 Bulan (Sheet) *</label>
                        <div class="grid grid-cols-1 gap-2">
                            <label class="flex items-start gap-2.5 p-3 rounded-xl border cursor-pointer transition" :class="tipeTahunExport === 'kalender' ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-semibold' : 'bg-white border-gray-200 text-gray-700'">
                                <input type="radio" name="tipe_tahun" value="kalender" x-model="tipeTahunExport" class="mt-0.5 text-emerald-600">
                                <div>
                                    <span class="font-bold text-xs block">Tahun Kalender Masehi</span>
                                    <span class="text-[11px] text-gray-500 font-normal">Januari s.d. Desember (Sheet: 01-Januari, ..., 12-Desember)</span>
                                </div>
                            </label>
                            <label class="flex items-start gap-2.5 p-3 rounded-xl border cursor-pointer transition" :class="tipeTahunExport === 'ajaran' ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-semibold' : 'bg-white border-gray-200 text-gray-700'">
                                <input type="radio" name="tipe_tahun" value="ajaran" x-model="tipeTahunExport" class="mt-0.5 text-emerald-600">
                                <div>
                                    <span class="font-bold text-xs block">Tahun Ajaran Pesantren</span>
                                    <span class="text-[11px] text-gray-500 font-normal">Juli s.d. Juni (Sheet: 01-Juli, 02-Agustus, ..., 12-Juni)</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Filter Rombel / Kelas (Opsional)</label>
                        <select name="kelas" x-model="kelasExport" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-700 focus:border-brand-500 bg-white outline-none">
                            <option value="">Semua Kelas (Seluruh Santri)</option>
                            @foreach($classrooms as $c)
                                <option value="{{ $c->nama_kelas }}">{{ $c->nama_kelas }} ({{ $c->jenjang }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Feature Highlighting Box -->
                    <div class="p-3 bg-emerald-50/70 border border-emerald-200 rounded-xl text-xs text-emerald-950 space-y-1">
                        <div class="font-bold flex items-center gap-1.5 text-emerald-900">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Struktur Isi File Excel:</span>
                        </div>
                        <ul class="list-disc list-inside text-gray-600 text-[11px] space-y-0.5 pl-1">
                            <li><strong>Sheet 1 (REKAP TAHUNAN):</strong> Ringkasan total penerimaan seluruh pos per bulan.</li>
                            <li><strong>Sheet 2 s.d. 13:</strong> Buku kas matriks per bulan lengkap dengan perincian pos biaya santri.</li>
                            <li>Auto-fit lebar kolom, freeze panes, dan format Rupiah otomatis.</li>
                        </ul>
                    </div>
                </div>

                <div class="ta-modal-footer">
                    <button type="button" @click="modalExportTahunan = false" class="ta-btn-outline text-xs">
                        Batal
                    </button>
                    <button type="submit" @click="setTimeout(() => modalExportTahunan = false, 1500)" class="ta-btn-primary text-xs bg-emerald-600 hover:bg-emerald-700">
                        Download Buku Kas Tahunan (.xlsx)
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

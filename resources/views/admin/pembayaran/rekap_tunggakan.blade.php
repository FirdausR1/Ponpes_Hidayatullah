@extends('admin.layout')

@section('title', 'Rekapitulasi Tunggakan & Kekurangan Biaya Santri — Pondok Pesantren Hidayatullah')

@section('styles')
<style>
    @media print {
        body { background: #fff !important; font-size: 11px !important; }
        aside, header, #filter-card, .no-print, .ta-btn-primary, .ta-btn-outline, nav { display: none !important; }
        .print-only { display: block !important; }
        .ta-card { border: none !important; box-shadow: none !important; padding: 0 !important; }
        table { width: 100% !important; border-collapse: collapse !important; }
        th, td { border: 1px solid #999 !important; padding: 6px 8px !important; }
        .badge-pos { border: 1px solid #ccc !important; background: transparent !important; color: #000 !important; }
    }
</style>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-rose-50 px-2 py-0.5 text-xs font-semibold text-rose-700 border border-rose-200">Keuangan &amp; Pembayaran</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">Rekapitulasi Keuangan</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Rekap Tunggakan &amp; Kekurangan Santri</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Rekap komprehensif tagihan yang belum lunas: pantau pos biaya terbanyak, kelas menunggak, dan kirim pengingat tagihan via WhatsApp dengan satu klik.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5 no-print">
            <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-emerald-700 transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Buka Kasir POS</span>
            </a>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-900 transition">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
            <a href="{{ route('admin.pembayaran.rekapTunggakan.export', request()->query()) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-emerald-700 transition">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Unduh Excel (.xlsx)</span>
            </a>
        </div>
    </div>

    <!-- Print Header Only (Tampak saat cetak) -->
    <div class="hidden print-only mb-6 text-center border-b pb-4">
        <h2 class="text-xl font-bold uppercase tracking-wider">Pondok Pesantren Hidayatullah Tuksongo</h2>
        <p class="text-xs text-gray-600">Jl. Raya Tuksongo, Kec. Borobudur, Kab. Magelang / Temanggung, Jawa Tengah</p>
        <h3 class="text-base font-bold text-gray-900 mt-2 underline">LAPORAN REKAPITULASI TUNGGAKAN &amp; KEKURANGAN KEUANGAN SANTRI</h3>
        <p class="text-xs text-gray-500">Dicetak pada: {{ date('d F Y, H:i') }} WIB oleh {{ auth()->user()->name ?? 'Bendahara' }}</p>
    </div>

    <!-- 4 KARTU STATISTIK KEUANGAN -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Tunggakan Aktif -->
        <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Kekurangan Aktif</span>
                <span class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-base">
                    Rp
                </span>
            </div>
            <div class="text-2xl font-bold font-mono text-rose-600">
                Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
            </div>
            <div class="mt-2 flex items-center gap-1.5 text-xs text-gray-500">
                <span class="inline-block w-2 h-2 rounded-full bg-rose-500"></span>
                <span>Tagihan santri aktif belum lunas</span>
            </div>
        </div>

        <!-- Card 2: Jumlah Santri Menunggak -->
        <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Santri Menunggak</span>
                <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold font-mono text-amber-600">
                {{ number_format($totalSantriMenunggak, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500 font-sans">Santri</span>
            </div>
            <div class="mt-2 flex items-center gap-1.5 text-xs text-gray-500">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-500"></span>
                <span>Memiliki sisa tagihan &gt; 0</span>
            </div>
        </div>

        <!-- Card 3: Lembar Tagihan Belum Lunas -->
        <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Item Tagihan</span>
                <span class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold font-mono text-blue-600">
                {{ number_format($totalItemTagihan, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500 font-sans">Tagihan</span>
            </div>
            <div class="mt-2 flex items-center gap-1.5 text-xs text-gray-500">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                <span>Belum lunas / dicicil sebagian</span>
            </div>
        </div>

        <!-- Card 4: Ditangguhkan ke Wisuda -->
        <div class="rounded-2xl border border-purple-100 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ditangguhkan (Wisuda)</span>
                <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold font-mono text-purple-600">
                Rp {{ number_format($totalDitangguhkan, 0, ',', '.') }}
            </div>
            <div class="mt-2 flex items-center gap-1.5 text-xs text-gray-500">
                <span class="inline-block w-2 h-2 rounded-full bg-purple-500"></span>
                <span>Ditangguhkan pelunasannya saat wisuda</span>
            </div>
        </div>
    </div>

    <!-- FILTER & PENCARIAN BAR (TailAdmin Form Style) -->
    <div id="filter-card" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs no-print">
        <form method="GET" action="{{ route('admin.pembayaran.rekapTunggakan') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
            <!-- Search Keyword -->
            <div class="lg:col-span-4">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Santri / Tagihan</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Ketik nama santri, NIS, atau judul pos..." class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
            </div>

            <!-- Filter Kelas -->
            <div class="lg:col-span-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Kelas</label>
                <select name="kelas" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-brand-500 cursor-pointer">
                    <option value="">Semua Kelas</option>
                    @foreach($classrooms as $cls)
                        <option value="{{ $cls->nama_kelas }}" {{ $kelasFilter === $cls->nama_kelas ? 'selected' : '' }}>
                            Kelas {{ $cls->nama_kelas }} ({{ $cls->jenjang }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Pos Biaya -->
            <div class="lg:col-span-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Pos Biaya</label>
                <select name="pos_biaya" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-brand-500 cursor-pointer">
                    <option value="">Semua Pos Biaya</option>
                    @foreach($posBiayaList as $key => $title)
                        <option value="{{ $key }}" {{ $posFilter === $key ? 'selected' : '' }}>
                            {{ $key }} — {{ $title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="lg:col-span-2 flex items-end gap-1.5">
                <button type="submit" class="flex-1 h-10 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition">
                    Filter
                </button>
                <a href="{{ route('admin.pembayaran.rekapTunggakan') }}" class="px-3 h-10 rounded-lg border border-gray-300 bg-white text-gray-600 text-xs font-medium flex items-center justify-center hover:bg-gray-50 transition" title="Reset Filter">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- 2-KOLOM RINGKASAN: REKAP PER POS & REKAP PER KELAS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Kolom Kiri: Rekap Per Pos Biaya -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>Rekapitulasi Per Pos Biaya</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">{{ $rekapPerPos->count() }} Pos</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pos pengeluaran santri dengan kekurangan tertinggi</p>
                </div>
            </div>
            <div class="overflow-x-auto max-h-80 overflow-y-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] tracking-wider font-semibold sticky top-0 z-10 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-2.5">Pos Biaya</th>
                            <th class="px-3 py-2.5 text-center">Santri</th>
                            <th class="px-3 py-2.5 text-center">Tagihan</th>
                            <th class="px-4 py-2.5 text-right">Total Kekurangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rekapPerPos as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2.5 font-bold text-gray-900 flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 font-mono text-[11px]">{{ $item['pos'] }}</span>
                                <span class="text-gray-500 font-normal text-[11px]">{{ $posBiayaList[$item['pos']] ?? '' }}</span>
                            </td>
                            <td class="px-3 py-2.5 text-center text-gray-600 font-medium">{{ $item['count_santri'] }}</td>
                            <td class="px-3 py-2.5 text-center text-gray-600 font-medium">{{ $item['count_tagihan'] }}</td>
                            <td class="px-4 py-2.5 text-right font-mono font-bold text-rose-600">
                                Rp {{ number_format($item['total_sisa'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                                Tidak ada data tunggakan untuk pos biaya ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Kolom Kanan: Rekap Per Kelas -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>Rekapitulasi Per Kelas</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">{{ $rekapPerKelas->count() }} Kelas</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Peringkat kelas dengan jumlah kekurangan terbesar</p>
                </div>
            </div>
            <div class="overflow-x-auto max-h-80 overflow-y-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] tracking-wider font-semibold sticky top-0 z-10 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-2.5">Kelas &amp; Jenjang</th>
                            <th class="px-3 py-2.5 text-center">Santri Menunggak</th>
                            <th class="px-4 py-2.5 text-right">Total Kekurangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rekapPerKelas as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2.5 font-bold text-gray-900 flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold border border-blue-200 text-[11px]">Kelas {{ $item['kelas'] }}</span>
                                <span class="text-gray-400 font-normal text-[11px]">({{ $item['jenjang'] }})</span>
                            </td>
                            <td class="px-3 py-2.5 text-center text-gray-600 font-semibold">{{ $item['count_santri'] }} orang</td>
                            <td class="px-4 py-2.5 text-right font-mono font-bold text-rose-600">
                                Rp {{ number_format($item['total_sisa'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-400">
                                Tidak ada data tunggakan untuk kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TABEL UTAMA: DAFTAR SANTRI MENUNGGAK & RINCIANNYA -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <span>Daftar Rincian Santri Menunggak</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">{{ $santriList->total() }} Santri</span>
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Rincian kekurangan tagihan per santri beserta tombol penagihan WhatsApp otomatis dan shortcut Kasir POS</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 w-10 text-center">No</th>
                        <th class="px-4 py-3">Data Santri</th>
                        <th class="px-4 py-3">Kontak Wali</th>
                        <th class="px-4 py-3">Rincian Tagihan Belum Lunas</th>
                        <th class="px-4 py-3 text-right">Total Kekurangan</th>
                        <th class="px-4 py-3 text-center no-print">Aksi Bendahara</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($santriList as $idx => $row)
                    @php
                        $st = $row['student'];
                        $bills = $row['bills'];
                        $totalSisa = $row['total_tunggakan'];
                        $waNum = $st ? ($st->no_whatsapp ?: ($st->no_hp ?: $st->no_hp_wali)) : null;
                        $cleanWa = $waNum ? preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $waNum)) : '';

                        // Format pesan WhatsApp penagihan yang santun & profesional
                        $tagihanLines = [];
                        foreach ($bills as $b) {
                            $tagihanLines[] = "• {$b->judul_tagihan}: Rp " . number_format($b->sisa_tagihan, 0, ',', '.');
                        }
                        $tagihanText = implode("\n", $tagihanLines);
                        $totalSisaFmt = number_format($totalSisa, 0, ',', '.');

                        $waMessage = "Assalamu'alaikum Wr. Wb.\n\n"
                            . "Yth. Bapak/Ibu Wali Santri dari ananda *{$st?->nama_lengkap}* (Kelas {$st?->kelas})\n\n"
                            . "Semoga senantiasa dalam limpahan rahmat dan keberkahan Allah SWT.\n\n"
                            . "Melalui pesan ini, kami dari Bendahara Pondok Pesantren Hidayatullah menginformasikan rekapitulasi administrasi/tagihan yang belum terselesaikan dengan rincian sebagai berikut:\n\n"
                            . "{$tagihanText}\n\n"
                            . "*Total Kekurangan: Rp {$totalSisaFmt}*\n\n"
                            . "Pembayaran dapat dilakukan melalui:\n"
                            . "1. Kasir Kantor Bendahara Pesantren (Tunai)\n"
                            . "2. Transfer Bank ke Rekening Resmi:\n"
                            . "   - BSI: 714 556 7890 (a.n. Pondok Pesantren Hidayatullah)\n"
                            . "   - BRI: 0153 0100 2345 531 (a.n. Ponpes Hidayatullah)\n\n"
                            . "Setelah melakukan pembayaran, mohon konfirmasi ke nomor ini atau upload bukti pembayaran di portal santri. Atas perhatian dan kerjasamanya kami haturkan jazakumullahu khairan katsiran.\n\n"
                            . "Wassalamu'alaikum Wr. Wb.\n"
                            . "Bendahara Pondok Pesantren Hidayatullah Tuksongo";
                        
                        $waUrl = $cleanWa ? "https://wa.me/{$cleanWa}?text=" . rawurlencode($waMessage) : null;
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <!-- No -->
                        <td class="px-4 py-3.5 text-center text-gray-500 font-mono">
                            {{ ($santriList->currentPage() - 1) * $santriList->perPage() + $idx + 1 }}
                        </td>

                        <!-- Santri Info -->
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($st?->foto)
                                    <img src="{{ $st->foto }}" alt="{{ $st->nama_lengkap }}" class="w-9 h-9 rounded-full object-cover border border-gray-200 shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-700 font-bold flex items-center justify-center text-xs border border-emerald-200 shrink-0">
                                        {{ strtoupper(substr($st?->nama_lengkap ?? 'S', 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.siswa.show', $st?->id ?? 0) }}" class="font-bold text-gray-900 hover:text-brand-500 transition block">
                                        {{ $st?->nama_lengkap ?? 'Santri Tidak Ditemukan' }}
                                    </a>
                                    <div class="flex items-center gap-1.5 text-[11px] text-gray-500 mt-0.5">
                                        <span class="font-mono">NIS: {{ $st?->nis ?: '—' }}</span>
                                        <span>•</span>
                                        <span class="px-1.5 py-0.2 rounded bg-blue-50 text-blue-700 font-semibold border border-blue-100">Kelas {{ $st?->kelas }}</span>
                                        @if($st?->kamar_asrama)
                                            <span>•</span>
                                            <span>Asrama: {{ $st->kamar_asrama }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Wali Info -->
                        <td class="px-4 py-3.5">
                            <div class="text-gray-900 font-semibold text-xs">
                                {{ $st?->nama_wali ?: ($st?->ayah_nama ?: ($st?->ibu_nama ?: '—')) }}
                            </div>
                            <div class="text-gray-500 text-[11px] font-mono mt-0.5">
                                {{ $waNum ?: 'No HP Tidak Ada' }}
                            </div>
                        </td>

                        <!-- Rincian Tagihan Belum Lunas -->
                        <td class="px-4 py-3.5 max-w-md">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($bills as $b)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-gray-50 border border-gray-200 text-[11px] font-medium text-gray-800">
                                        <span class="font-mono text-[10px] text-gray-500 font-bold">{{ $b->pos_biaya }}</span>
                                        <span>{{ $b->judul_tagihan }}</span>
                                        <span class="font-mono font-bold text-rose-600 ml-0.5">Rp {{ number_format($b->sisa_tagihan, 0, ',', '.') }}</span>
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        <!-- Total Sisa Tunggakan -->
                        <td class="px-4 py-3.5 text-right font-mono font-bold text-sm text-rose-600 whitespace-nowrap">
                            Rp {{ number_format($totalSisa, 0, ',', '.') }}
                        </td>

                        <!-- Aksi Bendahara -->
                        <td class="px-4 py-3.5 text-center no-print whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- Tombol WA -->
                                @if($waUrl)
                                    <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-medium transition" title="Kirim Tagihan Otomatis via WhatsApp">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.772.78 2.791.78h.001c3.181 0 5.768-2.587 5.768-5.766.001-3.181-2.585-5.766-5.769-5.766zm3.377 8.21c-.143.403-.834.774-1.157.825-.323.051-.735.084-2.18-.517-1.446-.601-2.385-2.072-2.457-2.168-.072-.096-.583-.777-.583-1.481 0-.704.368-1.05.5-1.193.132-.143.288-.179.384-.179.096 0 .192.001.276.005.09.004.21-.034.329.252.126.3.432 1.05.47 1.128.038.078.064.168.013.269-.051.101-.077.164-.153.253-.077.089-.161.199-.23.267-.077.076-.157.159-.067.313.09.154.4 1.082 1.206 1.8 1.036.924 1.91 1.21 2.181 1.344.271.134.43.117.59-.068.16-.185.688-.802.871-1.076.183-.274.367-.229.617-.137.25.092 1.584.747 1.856.883.272.136.453.204.519.317.066.113.066.657-.077 1.06z"/></svg>
                                        <span>WA Tagihan</span>
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-2 py-1.5 rounded-lg bg-gray-50 text-gray-400 border border-gray-200 text-[11px]" title="Nomor WhatsApp wali belum terdaftar">
                                        No WA (-)
                                    </span>
                                @endif

                                <!-- Tombol Bayar di Kasir -->
                                <a href="{{ route('admin.pembayaran.index', ['santri_id' => $st?->id]) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-semibold shadow-sm transition" title="Buka di Kasir POS">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span>Bayar Kasir</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <div class="max-w-xs mx-auto space-y-2">
                                <svg class="w-10 h-10 text-emerald-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm font-bold text-gray-700">Alhamdulillah, Tidak Ada Tunggakan</p>
                                <p class="text-xs text-gray-400">Semua santri pada filter ini telah melunasi tagihannya.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($santriList->hasPages())
        <div class="p-4 border-t border-gray-100 no-print">
            {{ $santriList->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

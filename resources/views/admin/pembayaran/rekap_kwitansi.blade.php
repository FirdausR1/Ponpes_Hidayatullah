@php
if (!function_exists('terbilangAngka')) {
    function terbilangAngka($angka) {
        $angka = abs((float)$angka);
        $huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
        if ($angka < 12) {
            return ' ' . $huruf[$angka];
        } elseif ($angka < 20) {
            return terbilangAngka($angka - 10) . ' Belas';
        } elseif ($angka < 100) {
            return terbilangAngka(floor($angka / 10)) . ' Puluh' . terbilangAngka($angka % 10);
        } elseif ($angka < 200) {
            return ' Seratus' . terbilangAngka($angka - 100);
        } elseif ($angka < 1000) {
            return terbilangAngka(floor($angka / 100)) . ' Ratus' . terbilangAngka($angka % 100);
        } elseif ($angka < 2000) {
            return ' Seribu' . terbilangAngka($angka - 1000);
        } elseif ($angka < 1000000) {
            return terbilangAngka(floor($angka / 1000)) . ' Ribu' . terbilangAngka($angka % 1000);
        } elseif ($angka < 1000000000) {
            return terbilangAngka(floor($angka / 1000000)) . ' Juta' . terbilangAngka($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            return terbilangAngka(floor($angka / 1000000000)) . ' Milyar' . terbilangAngka(fmod($angka, 1000000000));
        }
        return '';
    }
}

$currentUser = auth()->user();
$bendaharaNama = $currentUser ? $currentUser->name : \App\Models\Setting::get('ttd_digital_nama', 'Ust. Ahmad Fauzi, S.Pd.I');
$pimpinanNama = \App\Models\Setting::get('nama_pimpinan_pesantren', 'KH. M. Syukron Katsir, Lc.');

$filterInfo = [];
if (request('tanggal_mulai') && request('tanggal_akhir')) {
    $filterInfo[] = 'Periode: ' . \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') . ' s/d ' . \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y');
} elseif (request('tanggal_mulai')) {
    $filterInfo[] = 'Mulai: ' . \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y');
} elseif (request('tanggal_akhir')) {
    $filterInfo[] = 'Sampai: ' . \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y');
} else {
    $filterInfo[] = 'Semua Periode Transaksi';
}

if (request('kelas')) {
    $filterInfo[] = 'Kelas: ' . request('kelas');
}
if (request('jenis_pembayaran')) {
    $filterInfo[] = 'Pos: ' . request('jenis_pembayaran');
}
if (request('q_santri')) {
    $filterInfo[] = 'Pencarian: "' . request('q_santri') . '"';
}
if (request('ids')) {
    $filterInfo[] = 'Pilihan ' . count(explode(',', request('ids'))) . ' Kwitansi Terpilih';
}
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Register Kwitansi Pembayaran ({{ $totalTransaksi }} Transaksi) - Ponpes Hidayatullah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Amiri:wght@700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #1f2937;
        }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .font-arabic { font-family: 'Amiri', serif; }

        @media print {
            .no-print { display: none !important; }
            body { 
                background: white !important; 
                margin: 0 !important; 
                padding: 5mm !important; 
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .page-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                max-width: 100% !important;
                padding: 0 !important;
            }
            @page {
                size: A4 portrait;
                margin: 8mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 p-3 sm:p-6 min-h-screen text-slate-800">

    <!-- FLOATING TOP TOOLBAR (Hidden when printing) -->
    <header class="no-print max-w-5xl mx-auto mb-6 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-slate-200 p-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.pembayaran.index', ['tab' => 'santri']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-300 text-xs font-bold text-slate-700 transition">
                    &larr; Riwayat Pembayaran
                </a>
                <div>
                    <h1 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                        <span>Rekapitulasi Register Kwitansi</span>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-black bg-teal-100 text-teal-800 border border-teal-300">
                            {{ $totalTransaksi }} Kwitansi
                        </span>
                    </h1>
                    <p class="text-[11px] text-slate-500">
                        Total Dana Masuk: <span class="font-mono font-bold text-emerald-700">Rp {{ number_format($totalNominal, 0, ',', '.') }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- Tombol Cetak Fisik Kwitansi Massal -->
                <a href="{{ route('admin.pembayaran.kwitansiMassal', request()->query()) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-300 text-slate-700 text-xs font-bold transition">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Cetak Lembar Kwitansi (Fisik)</span>
                </a>

                <!-- Tombol Export Excel -->
                <a href="{{ route('admin.pembayaran.rekapKwitansi.export', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export Excel (.xlsx)</span>
                </a>

                <!-- Tombol Print Rekap -->
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#208075] hover:bg-[#18665e] text-white text-xs font-bold shadow-md shadow-[#208075]/25 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Rekap (Print / PDF)</span>
                </button>
            </div>

        </div>
    </header>

    <!-- MAIN REPORT CONTAINER (A4 Paper style) -->
    <div class="page-container max-w-5xl mx-auto bg-white rounded-2xl shadow-xl border border-slate-200 p-6 sm:p-10">
        
        <!-- KOP SURAT RESMI PESANTREN -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-4 mb-5">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center p-1 shrink-0">
                    <img src="/logo.png" alt="Logo" class="w-full h-full object-contain" onerror="this.src='/logo1.png'">
                </div>
                <div>
                    <div class="font-arabic text-sm text-[#208075] font-bold">معهد هداية الله للتربية الإسلامية</div>
                    <h2 class="text-base sm:text-lg font-black tracking-wide text-slate-900 uppercase leading-snug">
                        PONDOK PESANTREN HIDAYATULLAH TUKSONGO
                    </h2>
                    <p class="text-xs font-semibold text-slate-700">MTs &amp; MA Hidayatullah &bull; Unit Administrasi &amp; Bendahara Keuangan</p>
                    <p class="text-[11px] text-slate-500">Dusun Tuksongo, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah 56272</p>
                </div>
            </div>
            <div class="text-right hidden sm:block">
                <span class="inline-block px-3 py-1 rounded-lg bg-teal-50 border border-teal-200 text-[#208075] font-black text-xs uppercase tracking-wider">
                    BUKU REGISTER KWITANSI
                </span>
                <p class="text-[10px] text-slate-400 mt-1">Dicetak: {{ date('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        <!-- JUDUL & METADATA REKAP -->
        <div class="text-center mb-6">
            <h1 class="text-base sm:text-lg font-extrabold text-slate-900 uppercase tracking-wide">
                REKAPITULASI REGISTER KWITANSI PEMBAYARAN SANTRI
            </h1>
            <p class="text-xs font-medium text-slate-600 mt-1">
                {{ implode(' &bull; ', $filterInfo) }}
            </p>
        </div>

        <!-- RINGKASAN REKAPITULASI (KPI BOXES) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                <span class="text-[10px] uppercase font-bold text-slate-500 block">Total Lembar Kwitansi</span>
                <span class="text-lg font-black text-slate-900 font-mono">{{ number_format($totalTransaksi, 0, ',', '.') }}</span>
                <span class="text-[10px] text-slate-400 block">Transaksi Penerimaan</span>
            </div>
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200">
                <span class="text-[10px] uppercase font-bold text-emerald-700 block">Total Kas Diterima</span>
                <span class="text-lg font-black text-emerald-800 font-mono">Rp {{ number_format($totalNominal, 0, ',', '.') }}</span>
                <span class="text-[10px] text-emerald-600 block">Status Lunas</span>
            </div>
            <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200">
                <span class="text-[10px] uppercase font-bold text-blue-700 block">Penerimaan Tunai</span>
                <span class="text-lg font-black text-blue-900 font-mono">Rp {{ number_format($breakdownMetode['Tunai'] ?? 0, 0, ',', '.') }}</span>
                <span class="text-[10px] text-blue-600 block">Kas di Tangan</span>
            </div>
            <div class="p-3.5 rounded-xl bg-indigo-50 border border-indigo-200">
                <span class="text-[10px] uppercase font-bold text-indigo-700 block">Transfer Bank</span>
                <span class="text-lg font-black text-indigo-900 font-mono">Rp {{ number_format($breakdownMetode['Transfer Bank'] ?? 0, 0, ',', '.') }}</span>
                <span class="text-[10px] text-indigo-600 block">Rekening Bank</span>
            </div>
        </div>

        <!-- BREAKDOWN PER POS BIAYA (CHIPS) -->
        @if(count($breakdownPos) > 0)
        <div class="mb-5 p-3 rounded-xl bg-slate-50 border border-slate-200">
            <span class="text-[11px] font-bold text-slate-700 block mb-2">Rincian Akumulasi per Pos Biaya:</span>
            <div class="flex flex-wrap gap-1.5">
                @foreach($breakdownPos as $pos => $nom)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-xs text-slate-800 shadow-2xs">
                        <strong class="font-bold text-slate-700">{{ $pos }}:</strong>
                        <span class="font-mono font-bold text-emerald-700">Rp {{ number_format($nom, 0, ',', '.') }}</span>
                    </span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- TABEL DETAIL REGISTER KWITANSI -->
        <div class="border border-slate-300 rounded-xl overflow-x-auto mb-6">
            <table class="w-full min-w-[800px] text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#208075] text-white font-bold uppercase text-[10px] tracking-wider">
                        <th class="py-2.5 px-3 text-center w-10">No</th>
                        <th class="py-2.5 px-3">No. Kwitansi &amp; Tgl</th>
                        <th class="py-2.5 px-3">Santri &amp; Kelas</th>
                        <th class="py-2.5 px-3">Rincian Pos Biaya</th>
                        <th class="py-2.5 px-3 text-center">Metode</th>
                        <th class="py-2.5 px-3">Kasir / Penerima</th>
                        <th class="py-2.5 px-3 text-right">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($payments as $idx => $p)
                    <tr class="{{ $idx % 2 === 0 ? 'bg-white' : 'bg-slate-50/60' }} hover:bg-slate-100/80 transition">
                        <td class="py-2 px-3 text-center text-slate-500 font-mono text-[11px]">
                            {{ $idx + 1 }}
                        </td>
                        <td class="py-2 px-3">
                            <span class="font-mono font-bold text-slate-900 block text-[11px]">{{ $p->no_transaksi }}</span>
                            <span class="text-[10px] text-slate-500">{{ optional($p->tanggal_bayar)->format('d/m/Y') }}</span>
                        </td>
                        <td class="py-2 px-3">
                            <div class="font-bold text-slate-900">
                                {{ $p->student ? ($p->student->nama_lengkap ?? '—') : ($p->psbRegistration ? ($p->psbRegistration->nama_lengkap ?? 'Calon Santri') : ($p->penerima_nama ?: '—')) }}
                            </div>
                            <div class="text-[10px] text-slate-500">
                                @if($p->student)
                                    NIS: {{ $p->student->nis ?? '—' }} &bull; Kelas: <span class="font-semibold text-slate-700">{{ $p->student->kelas ?? '—' }}</span>
                                @elseif($p->psbRegistration)
                                    Reg: {{ $p->psbRegistration->no_registrasi ?? '—' }} &bull; Jenjang: <span class="font-semibold text-slate-700">{{ $p->psbRegistration->jenjang ?? '—' }}</span>
                                @else
                                    Kategori: Umum / Pembayaran Santri
                                @endif
                            </div>
                        </td>
                        <td class="py-2 px-3">
                            @if($p->items && $p->items->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($p->items as $it)
                                        <span class="text-[10px] bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded border border-slate-200">
                                            <strong>{{ $it->pos_biaya }}</strong>: {{ number_format($it->nominal, 0, ',', '.') }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-[11px] font-semibold text-slate-700">{{ $p->jenis_pembayaran }}</span>
                            @endif
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium {{ $p->metode_pembayaran === 'Transfer Bank' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                {{ $p->metode_pembayaran ?: 'Tunai' }}
                            </span>
                        </td>
                        <td class="py-2 px-3 text-slate-600 text-[11px]">
                            {{ $p->penerima_nama ?: ($p->user?->name ?: 'Bendahara') }}
                        </td>
                        <td class="py-2 px-3 text-right font-mono font-bold text-slate-900 text-xs">
                            Rp {{ number_format($p->nominal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-slate-400">
                            Tidak ada data transaksi kwitansi yang sesuai dengan filter.
                        </td>
                    </tr>
                    @endforelse

                    <!-- TOTAL ROW -->
                    <tr class="bg-emerald-50 font-black border-t-2 border-slate-400">
                        <td colspan="6" class="py-3 px-3 text-right uppercase text-slate-800 tracking-wide text-xs">
                            TOTAL PENERIMAAN KAS
                        </td>
                        <td class="py-3 px-3 text-right font-mono text-sm text-emerald-800 font-black">
                            Rp {{ number_format($totalNominal, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- TERBILANG -->
        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs mb-8 flex items-baseline gap-2">
            <span class="font-bold text-slate-700 shrink-0">Terbilang Total:</span>
            <span class="italic font-semibold text-slate-900">
                "{{ trim(terbilangAngka($totalNominal)) }} Rupiah"
            </span>
        </div>

        <!-- TANDA TANGAN RESMI PENGESAHAN -->
        <div class="grid grid-cols-2 gap-10 text-center text-xs pt-4">
            <div>
                <p class="text-slate-600">Mengetahui,</p>
                <p class="font-semibold text-slate-800">Pengasuh / Pimpinan Pondok Pesantren</p>
                <div class="h-20 flex items-center justify-center">
                    <!-- Space for signature -->
                </div>
                <div class="border-t border-dotted border-slate-700 pt-1 font-bold text-slate-900 inline-block min-w-[200px]">
                    {{ $pimpinanNama }}
                </div>
            </div>

            <div>
                <p class="text-slate-600">Temanggung, {{ date('d F Y') }}</p>
                <p class="font-semibold text-slate-800">Bendahara Pesantren</p>
                <div class="h-20 flex items-center justify-center">
                    @php
                        $userTtd = $currentUser && !empty($currentUser->signature_image) && file_exists(public_path($currentUser->signature_image)) ? $currentUser->signature_image : null;
                    @endphp
                    @if($userTtd)
                        <img src="{{ $userTtd }}" alt="TTD Bendahara" class="h-16 object-contain">
                    @endif
                </div>
                <div class="border-t border-dotted border-slate-700 pt-1 font-bold text-slate-900 inline-block min-w-[200px]">
                    {{ $bendaharaNama }}
                </div>
            </div>
        </div>

        <!-- FOOTER DOKUMEN -->
        <div class="mt-8 pt-3 border-t border-slate-200 text-[10px] text-slate-400 flex items-center justify-between">
            <span>Sistem Administrasi Keuangan Ponpes Hidayatullah Tuksongo</span>
            <span>Dokumen Rekapitulasi Register Kwitansi Pembayaran</span>
        </div>

    </div>

</body>
</html>

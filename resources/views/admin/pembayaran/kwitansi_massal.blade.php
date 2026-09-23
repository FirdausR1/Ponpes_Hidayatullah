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

$stempelImg = \App\Models\Setting::get('ttd_digital_stempel_image');
if (!$stempelImg || !file_exists(public_path($stempelImg))) {
    if (file_exists(public_path('uploads/settings/stempel_1788857044.png'))) {
        $stempelImg = '/uploads/settings/stempel_1788857044.png';
    } elseif (file_exists(public_path('uploads/settings/default_stempel_pesantren.png'))) {
        $stempelImg = '/uploads/settings/default_stempel_pesantren.png';
    } else {
        $stempelImg = '/logo.png';
    }
}

$totalNominalSemua = $payments->sum('nominal');
$totalJumlahKwitansi = $payments->count();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kwitansi Massal ({{ $totalJumlahKwitansi }} Lembar) - Pondok Pesantren Hidayatullah Tuksongo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Amiri:wght@700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style id="page-print-style">
        @page {
            size: 210mm 140mm; /* Standar Ukuran Kwitansi Landscape per lembar */
            margin: 4mm 5mm;
        }
    </style>
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #1f2937;
        }
        .font-arabic { font-family: 'Amiri', serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        .bg-pondok-toska { background-color: #208075; }
        .text-pondok-toska { color: #208075; }
        .border-pondok-toska { border-color: #208075; }

        @media print {
            .no-print { display: none !important; }
            html, body { 
                background: white !important; 
                margin: 0 !important; 
                padding: 0 !important; 
                width: 200mm !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .slip-container {
                box-shadow: none !important;
                border: 1.5px solid #208075 !important;
                margin-left: auto !important;
                margin-right: auto !important;
                width: 200mm !important;
                max-width: 200mm !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            .page-break-always {
                page-break-after: always !important;
                break-after: page !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 p-3 sm:p-6 min-h-screen text-slate-800" x-data="{ mode: 'single' }">

    <!-- FLOATING TOP TOOLBAR (Hidden when printing) -->
    <header class="no-print sticky top-3 z-50 max-w-5xl mx-auto mb-6 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-slate-200 p-4 transition-all">
        <div class="flex flex-wrap items-center justify-between gap-3">
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.pembayaran.index', ['tab' => 'santri']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-300 text-xs font-bold text-slate-700 transition">
                    &larr; Riwayat Pembayaran
                </a>
                <div>
                    <h1 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                        <span>Cetak Kwitansi Massal</span>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                            {{ $totalJumlahKwitansi }} Kwitansi
                        </span>
                    </h1>
                    <p class="text-[11px] text-slate-500">
                        Total Nominal: <span class="font-mono font-bold text-emerald-700">Rp {{ number_format($totalNominalSemua, 0, ',', '.') }}</span>
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Pilihan Ukuran Kertas Cetak -->
                <div class="inline-flex items-center bg-white border border-slate-300 rounded-xl p-1 shadow-xs text-xs">
                    <span class="px-2 text-slate-500 font-semibold">Ukuran:</span>
                    <select id="selectPaperSize" onchange="setPaperSize(this.value)" class="bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-xs font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#208075]">
                        <option value="kwitansi" selected>Kwitansi (210 × 140 mm)</option>
                        <option value="a4">Kertas A4</option>
                        <option value="a5">Kertas A5 Landscape</option>
                    </select>
                </div>

                <!-- Tombol Buka Rekap Register -->
                <a href="{{ route('admin.pembayaran.rekapKwitansi', request()->query()) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-teal-50 hover:bg-teal-100 border border-teal-300 text-teal-800 text-xs font-bold transition">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Lihat Rekap Register</span>
                </a>

                <!-- Tombol Cetak / Print -->
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-[#208075] hover:bg-[#18665e] text-white text-xs font-bold shadow-md shadow-[#208075]/25 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Semua Kwitansi (Print)</span>
                </button>
            </div>

        </div>
    </header>

    <!-- LIST OF ALL RECEIPTS -->
    <main class="max-w-4xl mx-auto space-y-6 print:space-y-0">
        @foreach($payments as $index => $payment)
            @php
            $jenisPembayaran = $payment->jenis_pembayaran ?? '';
            $isPenarikanTabungan = stripos($jenisPembayaran, 'AMBIL TABUNGAN') !== false || stripos($jenisPembayaran, 'Pengambilan Tabungan') !== false;

            // Deteksi petugas pencatat transaksi atau bendahara yang sedang login
            $petugasUser = null;
            if (isset($payment->user) && $payment->user) {
                $petugasUser = $payment->user;
            } elseif (auth()->check()) {
                $petugasUser = auth()->user();
            }

            $ttdImg = null;
            $pejabatNama = null;
            $pejabatJabatan = null;

            if ($petugasUser) {
                $pejabatNama = $petugasUser->name;
                $pejabatJabatan = $petugasUser->jabatan ?: ($isPenarikanTabungan ? 'Petugas Kasir Tabungan' : 'Bendahara Pesantren');
                if (!empty($petugasUser->signature_image) && file_exists(public_path($petugasUser->signature_image))) {
                    $ttdImg = $petugasUser->signature_image;
                }
            }

            if (empty($pejabatNama)) {
                $pejabatNama = ($payment->penerima_nama ?? null) ?: \App\Models\Setting::get('ttd_digital_nama', 'Ust. Ahmad Fauzi, S.Pd.I');
            }
            if (empty($pejabatJabatan)) {
                $pejabatJabatan = $isPenarikanTabungan ? 'Petugas Kasir Tabungan' : \App\Models\Setting::get('ttd_digital_jabatan', 'Bendahara Pesantren');
            }

            // Fallback jika belum upload ttd pribadi
            if (empty($ttdImg)) {
                $ttdImg = \App\Models\Setting::get('ttd_digital_pengurus_image');
                if (!$ttdImg || !file_exists(public_path($ttdImg))) {
                    if (file_exists(public_path('uploads/settings/ttd_canvas_1788857044.png'))) {
                        $ttdImg = '/uploads/settings/ttd_canvas_1788857044.png';
                    } elseif (file_exists(public_path('uploads/settings/default_ttd_pengurus.png'))) {
                        $ttdImg = '/uploads/settings/default_ttd_pengurus.png';
                    }
                }
            }

            // Cap stempel: Utamakan cap stempel pribadi petugas/bendahara pencatat
            $stempelImgItem = $stempelImg;
            if ($petugasUser && !empty($petugasUser->stempel_image) && file_exists(public_path($petugasUser->stempel_image))) {
                $stempelImgItem = $petugasUser->stempel_image;
            }

            // Rincian Pos Pembayaran (minimal 5-6 baris)
            $itemsList = (isset($payment->items) && $payment->items && $payment->items->count() > 0) ? $payment->items : collect();
            if ($itemsList->isEmpty()) {
                $itemsList = collect([
                    (object)[
                        'pos_biaya' => ($payment->jenis_pembayaran ?? '') ?: 'SPP / Syahriyah',
                        'nominal' => $payment->nominal ?? 0,
                    ]
                ]);
            }
            $targetRowCount = max(5, $itemsList->count());

            $sisaTunggakan = $tunggakanMap[$payment->student_id] ?? null;
            $saldoTabungan = $payment->student ? (float)$payment->student->saldo_tabungan : 0;

            $stu = $payment->student;
            $psb = $payment->psbRegistration;

            $namaSantri = $stu ? ($stu->nama_lengkap ?? 'Santri') : ($psb ? ($psb->nama_lengkap ?? 'Calon Santri') : ($payment->penerima_nama ?: 'Santri'));
            $kelasTeks = $stu 
                ? (($stu->kelas ?? '—') . ' (' . ($stu->jenjang ?? 'MTs/MA') . ') • NIS: ' . ($stu->nis ?? '—'))
                : ($psb 
                    ? ('Calon Santri (' . ($psb->jenjang ?? 'MTs/MA') . ') • Reg: ' . ($psb->no_registrasi ?? '—')) 
                    : '—');
            $namaPenyetor = $stu 
                ? ($stu->nama_wali ?: $stu->nama_lengkap) 
                : ($psb 
                    ? ($psb->nama_wali ?: ($psb->wali_nama ?: ($psb->ayah_nama ?: $psb->nama_lengkap))) 
                    : ($payment->penerima_nama ?: 'Wali Santri'));
            $alamatPenyetor = $stu 
                ? ($stu->alamat ?: 'Pringsurat, Kab. Temanggung') 
                : ($psb 
                    ? ($psb->alamat_lengkap ?: ($psb->ayah_alamat ?: 'Pringsurat, Kab. Temanggung')) 
                    : 'Pringsurat, Kab. Temanggung');
            $noHpPenyetor = $stu 
                ? ($stu->no_whatsapp ?: ($stu->no_hp ?: '—')) 
                : ($psb 
                    ? ($psb->no_whatsapp ?: ($psb->ayah_telepon ?: '—')) 
                    : '—');
            @endphp

            <!-- LEMBAR SLIP KWITANSI -->
            <div id="receipt-slip-{{ $payment->id }}" class="slip-container bg-white rounded-xl shadow-lg border-2 border-[#208075] overflow-hidden relative mb-6 print:mb-0">
                
                <!-- HEADER STRIP TOSKA: LOGO, ARABIC, PONDOK TUKSONGO & BUKTI SETORAN/PENARIKAN -->
                <div class="{{ $isPenarikanTabungan ? 'bg-[#b45309]' : 'bg-[#208075]' }} text-white px-5 py-2 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-white/20 border border-white/40 flex items-center justify-center p-1 shrink-0">
                            <img src="/logo.png" alt="Logo" class="w-full h-full object-contain" onerror="this.src='/logo1.png'">
                        </div>
                        <div>
                            <div class="font-arabic text-xs tracking-wide leading-tight text-white/95">معهد هداية الله للتربية الإسلامية</div>
                            <h2 class="text-sm font-black tracking-wider uppercase leading-none mt-0.5">PONDOK TUKSONGO</h2>
                        </div>
                    </div>

                    <div class="text-right flex flex-col items-end">
                        <div class="flex items-center gap-2 mb-0.5">
                            <button type="button" onclick="downloadSingleMassSlip('receipt-slip-{{ $payment->id }}', '{{ $payment->no_transaksi }}', '{{ addslashes($namaSantri) }}')" class="no-print inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-white/20 hover:bg-white/30 text-white text-[10px] font-bold transition cursor-pointer" title="Download kwitansi ini sebagai file gambar PNG">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Unduh Gambar</span>
                            </button>
                            <span class="text-xs text-white/80 font-mono">Lembar #{{ $index + 1 }} dari {{ $totalJumlahKwitansi }}</span>
                        </div>
                        <h3 class="text-base sm:text-xl font-black tracking-widest uppercase">{{ $isPenarikanTabungan ? 'BUKTI PENARIKAN' : 'BUKTI SETORAN' }}</h3>
                    </div>
                </div>

                <!-- BODY SLIP: 2 KOLOM -->
                <div class="p-5 grid grid-cols-1 md:grid-cols-12 gap-5 items-start text-xs">
                    
                    <!-- KOLOM KIRI (6/12): FORM BIODATA SANTRI & TTD BUKTI PENERIMAAN -->
                    <div class="md:col-span-6 space-y-1.5 pr-0 md:pr-2">
                        
                        <!-- Nomor Transaksi -->
                        <div class="flex items-baseline gap-2">
                            <span class="w-28 text-slate-700 font-semibold shrink-0">Nomor</span>
                            <span class="text-slate-400 shrink-0">:</span>
                            <span class="flex-1 font-mono font-bold text-slate-900 border-b border-dotted border-slate-400 pb-0.5 tracking-wide">
                                {{ $payment->no_transaksi }}
                            </span>
                        </div>

                        <!-- Nama Santri -->
                        <div class="flex items-baseline gap-2">
                            <span class="w-28 text-slate-700 font-semibold shrink-0">Nama Santri</span>
                            <span class="text-slate-400 shrink-0">:</span>
                            <span class="flex-1 font-bold text-slate-900 border-b border-dotted border-slate-400 pb-0.5 uppercase">
                                {{ $namaSantri }}
                            </span>
                        </div>

                        <!-- Kelas -->
                        <div class="flex items-baseline gap-2">
                            <span class="w-28 text-slate-700 font-semibold shrink-0">Kelas</span>
                            <span class="text-slate-400 shrink-0">:</span>
                            <span class="flex-1 font-semibold text-slate-800 border-b border-dotted border-slate-400 pb-0.5">
                                {{ $kelasTeks }}
                            </span>
                        </div>

                        <!-- Berita / Keterangan -->
                        <div class="flex items-baseline gap-2">
                            <span class="w-28 text-slate-700 font-semibold shrink-0">Berita/Keterangan</span>
                            <span class="text-slate-400 shrink-0">:</span>
                            <span class="flex-1 text-slate-800 border-b border-dotted border-slate-400 pb-0.5 truncate">
                                @if($payment->catatan)
                                    {{ $payment->catatan }}
                                @elseif($payment->bulan)
                                    Iuran / SPP Bulan {{ $payment->bulan }} {{ $payment->tahun }}
                                @else
                                    {{ $itemsList->pluck('pos_biaya')->implode(', ') ?: 'Pembayaran Santri' }}
                                @endif
                            </span>
                        </div>

                        <!-- Nama Penyetor / Penerima Dana -->
                        <div class="flex items-baseline gap-2">
                            <span class="w-28 text-slate-700 font-semibold shrink-0">{{ $isPenarikanTabungan ? 'Penerima Dana' : 'Nama Penyetor' }}</span>
                            <span class="text-slate-400 shrink-0">:</span>
                            <span class="flex-1 font-medium text-slate-900 border-b border-dotted border-slate-400 pb-0.5">
                                {{ $namaPenyetor }}
                            </span>
                        </div>

                        <!-- Alamat -->
                        <div class="flex items-baseline gap-2">
                            <span class="w-28 text-slate-700 font-semibold shrink-0">Alamat</span>
                            <span class="text-slate-400 shrink-0">:</span>
                            <span class="flex-1 text-slate-700 border-b border-dotted border-slate-400 pb-0.5 truncate">
                                {{ $alamatPenyetor }}
                            </span>
                        </div>

                        <!-- No. HP -->
                        <div class="flex items-baseline gap-2">
                            <span class="w-28 text-slate-700 font-semibold shrink-0">No. HP</span>
                            <span class="text-slate-400 shrink-0">:</span>
                            <span class="flex-1 font-mono text-slate-800 border-b border-dotted border-slate-400 pb-0.5">
                                {{ $noHpPenyetor }}
                            </span>
                        </div>

                        <!-- TANDA TANGAN PENERIMA & PENYETOR (DENGAN CAP PONDOK & TTD DIGITAL) -->
                        <div class="pt-3 grid grid-cols-2 gap-3 text-center">
                            
                            <!-- KIRI (BENDAHARA) -->
                            <div class="relative flex flex-col items-center">
                                <span class="font-semibold text-slate-700 mb-0.5">{{ $isPenarikanTabungan ? 'Yang Menyerahkan' : 'Penerima' }}</span>
                                
                                <div class="relative w-44 h-16 flex items-center justify-between">
                                    <!-- Stempel Cap Pondok Tuksongo -->
                                    <div class="w-16 h-16 flex items-center justify-center shrink-0 pointer-events-none transform -rotate-6 z-0 relative">
                                        @if($stempelImgItem)
                                            <img src="{{ $stempelImgItem }}" alt="Cap Stempel Pondok" class="w-16 h-16 object-contain opacity-85 mix-blend-multiply" onerror="this.style.display='none'; const el = document.getElementById('svgStempelBackup_{{ $payment->id }}'); if (el) el.style.display='block';">
                                            <svg id="svgStempelBackup_{{ $payment->id }}" style="display: none;" class="w-16 h-16 text-[#208075] opacity-60 absolute" viewBox="0 0 100 100" fill="none" stroke="currentColor">
                                                <circle cx="50" cy="50" r="46" stroke-width="2.5" stroke-dasharray="2,2"/>
                                                <circle cx="50" cy="50" r="39" stroke-width="1.5"/>
                                                <circle cx="50" cy="50" r="26" stroke-width="1"/>
                                                <path id="curveTop_{{ $payment->id }}" d="M 18,50 A 32,32 0 1,1 82,50" fill="none"/>
                                                <text font-size="7" font-weight="bold" fill="currentColor"><textPath href="#curveTop_{{ $payment->id }}" startOffset="50%" text-anchor="middle">PP HIDAYATULLAH</textPath></text>
                                                <path id="curveBot_{{ $payment->id }}" d="M 82,50 A 32,32 0 0,1 18,50" fill="none"/>
                                                <text font-size="6.5" font-weight="bold" fill="currentColor"><textPath href="#curveBot_{{ $payment->id }}" startOffset="50%" text-anchor="middle">TUKSONGO TEMANGGUNG</textPath></text>
                                                <text x="50" y="53" font-size="8" font-weight="black" fill="currentColor" text-anchor="middle">LUNAS</text>
                                            </svg>
                                        @else
                                            <svg class="w-16 h-16 text-[#208075] opacity-60" viewBox="0 0 100 100" fill="none" stroke="currentColor">
                                                <circle cx="50" cy="50" r="46" stroke-width="2.5" stroke-dasharray="2,2"/>
                                                <circle cx="50" cy="50" r="39" stroke-width="1.5"/>
                                                <circle cx="50" cy="50" r="26" stroke-width="1"/>
                                                <path id="curveTop_{{ $payment->id }}" d="M 18,50 A 32,32 0 1,1 82,50" fill="none"/>
                                                <text font-size="7" font-weight="bold" fill="currentColor"><textPath href="#curveTop_{{ $payment->id }}" startOffset="50%" text-anchor="middle">PP HIDAYATULLAH</textPath></text>
                                                <path id="curveBot_{{ $payment->id }}" d="M 82,50 A 32,32 0 0,1 18,50" fill="none"/>
                                                <text font-size="6.5" font-weight="bold" fill="currentColor"><textPath href="#curveBot_{{ $payment->id }}" startOffset="50%" text-anchor="middle">TUKSONGO TEMANGGUNG</textPath></text>
                                                <text x="50" y="53" font-size="8" font-weight="black" fill="currentColor" text-anchor="middle">LUNAS</text>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Tanda Tangan Asli Digital -->
                                    <div class="flex-1 flex items-center justify-center -ml-3 z-10">
                                        @if($ttdImg)
                                            <img src="{{ $ttdImg }}" alt="TTD Digital" class="h-14 max-w-[105px] object-contain" onerror="this.style.display='none'">
                                        @endif
                                    </div>
                                </div>

                                <!-- Garis Nama Penerima -->
                                <div class="border-t border-dotted border-slate-600 pt-0.5 w-40 font-bold text-slate-900 text-[10px]">
                                    {{ $pejabatNama }}
                                </div>
                                <span class="text-[9px] text-slate-500">{{ $pejabatJabatan }}</span>
                            </div>

                            <!-- KANAN (WALI / SANTRI) -->
                            <div class="flex flex-col items-center justify-between">
                                <span class="font-semibold text-slate-700">{{ $isPenarikanTabungan ? 'Penerima' : 'Penyetor' }}</span>
                                <div class="h-16 flex items-center justify-center">
                                    <span class="text-[10px] text-slate-300 italic">(Tanda Tangan)</span>
                                </div>
                                <div class="border-t border-dotted border-slate-600 pt-0.5 w-32 font-semibold text-slate-800 text-[10px] truncate">
                                    {{ $namaPenyetor }}
                                </div>
                                <span class="text-[9px] text-slate-500">Wali / Santri</span>
                            </div>

                        </div>

                    </div>

                    <!-- KOLOM KANAN (6/12): TANGGAL, TABEL RINCIAN POS & TERBILANG -->
                    <div class="md:col-span-6 space-y-2">
                        
                        <!-- Tanggal Transaksi -->
                        <div class="text-right text-xs font-semibold text-slate-800 mb-1">
                            Tanggal : <span class="font-bold border-b border-dotted border-slate-400 pb-0.5 px-2">{{ optional($payment->tanggal_bayar)->translatedFormat('d F Y') ?? date('d F Y') }}</span>
                        </div>

                        <!-- TABEL MATRIKS RINCIAN POS PEMBAYARAN -->
                        <div class="border-2 border-[#208075] rounded-lg overflow-hidden">
                            <table class="w-full text-xs border-collapse">
                                <thead>
                                    <tr class="bg-[#208075] text-white font-black uppercase text-[11px] tracking-wider">
                                        <th class="py-1 px-3 text-left border-r border-[#196960] w-7/12">
                                            {{ strtoupper($payment->metode_pembayaran ?: 'TUNAI') }}
                                        </th>
                                        <th class="py-1 px-3 text-right">JUMLAH NOMINAL</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#208075]/30 text-slate-800">
                                     @foreach($itemsList as $it)
                                         <tr class="h-6 hover:bg-slate-50 {{ ($it->nominal ?? 0) < 0 ? 'bg-purple-50/50' : '' }}">
                                             <td class="py-0.5 px-3 border-r border-[#208075]/30 font-bold uppercase text-[10px] {{ ($it->nominal ?? 0) < 0 ? 'text-purple-800' : '' }}">
                                                 <div>{{ $it->pos_biaya }}</div>
                                                 @if(isset($it->bill) && $it->bill && $it->bill->nominal_potongan > 0)
                                                     <div class="text-[8px] font-medium text-purple-700 normal-case tracking-normal">
                                                         (Asli: Rp {{ number_format($it->bill->nominal_asli, 0, ',', '.') }} &bull; Subsidi: -Rp {{ number_format($it->bill->nominal_potongan, 0, ',', '.') }})
                                                     </div>
                                                 @endif
                                             </td>
                                             <td class="py-0.5 px-3 text-right font-mono font-bold text-[11px] {{ ($it->nominal ?? 0) < 0 ? 'text-purple-700' : '' }}">
                                                 {{ ($it->nominal ?? 0) < 0 ? '- Rp ' . number_format(abs($it->nominal), 0, ',', '.') : 'Rp ' . number_format($it->nominal ?? 0, 0, ',', '.') }}
                                             </td>
                                         </tr>
                                     @endforeach

                                    <!-- Padding Baris Kosong agar Persis Buku Slip Cetakan Fisik -->
                                    @for($i = $itemsList->count(); $i < $targetRowCount; $i++)
                                        <tr class="h-5">
                                            <td class="py-0.5 px-3 border-r border-[#208075]/30 text-slate-300"></td>
                                            <td class="py-0.5 px-3 text-right font-mono text-slate-300"></td>
                                        </tr>
                                    @endfor

                                    <!-- BARIS TOTAL -->
                                    <tr class="bg-emerald-50/50 border-t-2 border-[#208075] font-black">
                                        <td class="py-1 px-3 border-r border-[#208075] uppercase text-[#208075] text-[10px] tracking-wider">
                                            TOTAL
                                        </td>
                                        <td class="py-1 px-3 text-right font-mono text-xs text-[#208075] font-black">
                                            Rp {{ number_format($payment->nominal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- TERBILANG -->
                        <div class="pt-1 text-xs">
                            <div class="flex items-baseline gap-1 text-slate-700">
                                <span class="font-bold shrink-0 text-[11px]">Terbilang:</span>
                                <span class="flex-1 font-semibold italic text-slate-900 border-b border-dotted border-slate-500 pb-0.5 text-[11px]">
                                    {{ trim(terbilangAngka($payment->nominal)) }} Rupiah
                                </span>
                            </div>
                        </div>

                        {{-- Sisa Saldo Tabungan & Sisa Tunggakan disembunyikan dari kwitansi (permintaan admin) --}}

                    </div>

                </div>

                <!-- FOOTER RESMI -->
                <div class="bg-slate-50 border-t border-slate-200 px-5 py-1.5 text-[9px] text-slate-400 flex items-center justify-between">
                    <span>Lembar Bukti Sah &bull; Ponpes Hidayatullah Tuksongo</span>
                    <span>Kwitansi {{ $payment->no_transaksi }}</span>
                </div>

            </div>

            <!-- CSS PAGE BREAK FOR PRINTING AFTER EACH RECEIPT -->
            @if(!$loop->last)
                <div class="page-break-always hidden print:block"></div>
            @endif

        @endforeach
    </main>

    <script>
        function setPaperSize(size) {
            const styleTag = document.getElementById('page-print-style');
            if (!styleTag) return;
            if (size === 'a4') {
                styleTag.innerHTML = '@page { size: A4 portrait; margin: 10mm; }';
            } else if (size === 'a5') {
                styleTag.innerHTML = '@page { size: A5 landscape; margin: 5mm; }';
            } else {
                // Standar Kwitansi 210 x 140 mm
                styleTag.innerHTML = '@page { size: 210mm 140mm; margin: 4mm 5mm; }';
            }
        }

        function downloadSingleMassSlip(slipId, noTransaksi, namaSantri) {
            const slip = document.getElementById(slipId);
            if (!slip) return;

            if (typeof html2canvas === 'undefined') {
                alert('Sedang memuat sistem pembuatan gambar, silakan coba sesaat lagi.');
                return;
            }

            html2canvas(slip, {
                scale: 2.5,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff',
                logging: false,
                scrollX: 0,
                scrollY: 0
            }).then(canvas => {
                const link = document.createElement('a');
                const cleanName = (namaSantri || 'Santri').replace(/[^a-zA-Z0-9]/g, '_');
                const cleanNo = (noTransaksi || 'TRX').replace(/[^a-zA-Z0-9_-]/g, '');
                link.download = `Kwitansi_${cleanNo}_${cleanName}.png`;
                link.href = canvas.toDataURL('image/png', 1.0);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }).catch(err => {
                console.error(err);
                alert('Gagal mendownload gambar: ' + err.message);
            });
        }
    </script>
</body>
</html>

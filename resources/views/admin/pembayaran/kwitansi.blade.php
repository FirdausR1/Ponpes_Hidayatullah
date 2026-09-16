@php
$jenisPembayaran = $payment->jenis_pembayaran ?? '';
$isPenarikanTabungan = stripos($jenisPembayaran, 'AMBIL TABUNGAN') !== false || stripos($jenisPembayaran, 'Pengambilan Tabungan') !== false;

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

// Deteksi petugas pencatat transaksi atau bendahara yang sedang login
$petugasUser = null;
if (isset($payment->user) && $payment->user) {
    $petugasUser = $payment->user;
} elseif (auth()->check()) {
    $petugasUser = auth()->user();
}

// Cap stempel: Utamakan cap stempel milik petugas/bendahara yang mencatat, jika tidak ada fallback ke stempel umum pondok
$stempelImg = null;
if ($petugasUser && !empty($petugasUser->stempel_image) && file_exists(public_path($petugasUser->stempel_image))) {
    $stempelImg = $petugasUser->stempel_image;
} else {
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
    $pejabatNama = $payment->penerima_nama ?: \App\Models\Setting::get('ttd_digital_nama', 'Ust. Ahmad Fauzi, S.Pd.I');
}
if (empty($pejabatJabatan)) {
    $pejabatJabatan = $isPenarikanTabungan ? 'Petugas Kasir Tabungan' : \App\Models\Setting::get('ttd_digital_jabatan', 'Bendahara Pesantren');
}

// Fallback jika petugas belum mengunggah TTD sendiri
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

// Persiapkan rows pos pembayaran (minimal 6 baris agar identik dengan fisik bukti setoran)
$itemsList = (isset($payment->items) && $payment->items && $payment->items->count() > 0) ? $payment->items : collect();
if ($itemsList->isEmpty()) {
    $itemsList = collect([
        (object)[
            'pos_biaya' => ($payment->jenis_pembayaran ?? '') ?: 'SPP / Syahriyah',
            'nominal' => $payment->nominal ?? 0,
        ]
    ]);
}
$targetRowCount = max(6, $itemsList->count());

$stu = $payment->student ?? null;
$psb = $payment->psbRegistration ?? null;

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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isPenarikanTabungan ? 'Bukti Penarikan' : 'Bukti Setoran' }} - {{ $payment->no_transaksi }} - Pondok Tuksongo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Amiri:wght@700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style id="page-print-style">
        @page {
            size: 210mm 140mm; /* Standar Ukuran Kwitansi Landscape */
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
        
        /* Tema Warna Toska Resmi Pondok Tuksongo */
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
                margin: 0 auto !important;
                width: 200mm !important;
                max-width: 200mm !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 p-3 sm:p-6 flex flex-col items-center justify-center min-h-screen">

    <!-- Action Toolbar (Hidden When Printing) -->
    <div class="no-print max-w-4xl w-full mb-4 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ auth()->guard('santri')->check() ? route('santri.pembayaran') : route('admin.pembayaran.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-xs transition">
            &larr; Kembali ke Pembayaran
        </a>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Pilihan Ukuran Kertas Cetak -->
            <div class="inline-flex items-center bg-white border border-slate-300 rounded-xl p-1 shadow-xs text-xs">
                <span class="px-2 text-slate-500 font-semibold">Ukuran Cetak:</span>
                <select id="selectPaperSize" onchange="setPaperSize(this.value)" class="bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-xs font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#208075]">
                    <option value="kwitansi" selected>Kwitansi (210 × 140 mm)</option>
                    <option value="a4">Kertas A4 Penuh</option>
                    <option value="a5">Kertas A5 Landscape</option>
                </select>
            </div>

            <!-- Tombol Download Gambar (PNG) -->
            <button type="button" id="btnDownloadImage" onclick="downloadReceiptImage()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span id="downloadBtnText">Download Gambar (PNG)</span>
            </button>

            <!-- Tombol Edit Stempel & TTD -->
            <a href="{{ route('admin.settings.index') }}?open_tab=tab-ttd#tab-ttd"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-700 hover:bg-slate-800 text-white text-xs font-bold shadow-md transition"
               title="Edit gambar stempel dan tanda tangan yang muncul di kwitansi">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M8.5 14.5l1.5-1.5 5-5"/>
                    <path d="M14.5 8.5L16 10"/>
                </svg>
                <span>Edit Stempel & TTD</span>
            </a>

            <!-- Tombol Cetak (Print) -->
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-[#208075] hover:bg-[#1a685f] text-white text-xs font-bold shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Kwitansi (Print)</span>
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- LEMBAR BUKTI SETORAN RESMI (IDENTIK DENGAN SLIP FISIK PONDOK TUKSONGO)     -->
    <!-- ========================================================================= -->
    <div id="receipt-slip" class="slip-container max-w-4xl w-full bg-white rounded-xl shadow-lg border-2 border-[#208075] overflow-hidden relative">
        
        <!-- HEADER STRIP TOSKA: LOGO, ARABIC, PONDOK TUKSONGO & BUKTI SETORAN/PENARIKAN -->
        <div class="{{ $isPenarikanTabungan ? 'bg-[#b45309]' : 'bg-[#208075]' }} text-white px-5 py-2.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 border border-white/40 flex items-center justify-center p-1 shrink-0">
                    <img src="/logo.png" alt="Logo" class="w-full h-full object-contain" onerror="this.src='/logo1.png'">
                </div>
                <div>
                    <div class="font-arabic text-xs sm:text-sm tracking-wide leading-tight text-white/95">معهد هداية الله للتربية الإسلامية</div>
                    <h1 class="text-sm sm:text-base font-black tracking-wider uppercase leading-none mt-0.5">PONDOK TUKSONGO</h1>
                </div>
            </div>

            <div class="text-right">
                <h2 class="text-lg sm:text-2xl font-black tracking-widest uppercase">{{ $isPenarikanTabungan ? 'BUKTI PENARIKAN' : 'BUKTI SETORAN' }}</h2>
            </div>
        </div>

        <!-- BODY SLIP: 2 KOLOM (KIRI: BIODATA & TTD; KANAN: TABEL RINCIAN & TOTAL) -->
        <div class="p-5 sm:p-6 grid grid-cols-1 md:grid-cols-12 gap-5 items-start text-xs">
            
            <!-- KOLOM KIRI (7/12): FORM BIODATA SANTRI & TTD BUKTI PENERIMAAN -->
            <div class="md:col-span-6 space-y-2 pr-0 md:pr-3">
                
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
                    <span class="flex-1 text-slate-800 border-b border-dotted border-slate-400 pb-0.5">
                        @if(!empty($payment->catatan))
                            {{ $payment->catatan }}
                        @elseif(!empty($payment->bulan))
                            Iuran / SPP Bulan {{ $payment->bulan }} {{ $payment->tahun ?? date('Y') }}
                        @else
                            {{ $itemsList->pluck('pos_biaya')->implode(', ') ?: 'Pembayaran Santri' }}
                        @endif
                    </span>
                </div>

                <!-- Nama Penyetor / Penerima Dana -->
                <div class="flex items-baseline gap-2 pt-1">
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
                    <span class="flex-1 text-slate-700 border-b border-dotted border-slate-400 pb-0.5">
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
                <div class="pt-4 grid grid-cols-2 gap-4 text-center">
                    
                    <!-- KIRI (BENDAHARA) -->
                    <div class="relative flex flex-col items-center">
                        <span class="font-semibold text-slate-700 mb-1">{{ $isPenarikanTabungan ? 'Yang Menyerahkan' : 'Penerima' }}</span>
                        
                        <!-- Area TTD Digital & Cap Stempel Pondok: Cap di Sebelah Kiri, TTD di Sebelah Kanan -->
                        <div class="relative w-48 h-20 flex items-center justify-between">
                            
                            <!-- Stempel Cap Pondok Tuksongo (Sebelah Kiri) -->
                            <div class="w-20 h-20 flex items-center justify-center shrink-0 pointer-events-none transform -rotate-6 z-0">
                                <img src="{{ $stempelImg }}" alt="Cap Stempel Pondok" class="w-20 h-20 object-contain opacity-85 mix-blend-multiply" onerror="this.style.display='none'">
                                
                                <!-- Stempel SVG Backup Otentik Jika Image Tidak Tampil -->
                                <svg class="w-20 h-20 text-[#208075] opacity-60 absolute" viewBox="0 0 100 100" fill="none" stroke="currentColor">
                                    <circle cx="50" cy="50" r="46" stroke-width="2.5" stroke-dasharray="2,2"/>
                                    <circle cx="50" cy="50" r="39" stroke-width="1.5"/>
                                    <circle cx="50" cy="50" r="26" stroke-width="1"/>
                                    <path id="curveTop" d="M 18,50 A 32,32 0 1,1 82,50" fill="none"/>
                                    <text font-size="7" font-weight="bold" fill="currentColor"><textPath href="#curveTop" startOffset="50%" text-anchor="middle">PP HIDAYATULLAH</textPath></text>
                                    <path id="curveBot" d="M 82,50 A 32,32 0 0,1 18,50" fill="none"/>
                                    <text font-size="6.5" font-weight="bold" fill="currentColor"><textPath href="#curveBot" startOffset="50%" text-anchor="middle">TUKSONGO TEMANGGUNG</textPath></text>
                                    <text x="50" y="53" font-size="8" font-weight="black" fill="currentColor" text-anchor="middle">LUNAS</text>
                                </svg>
                            </div>

                            <!-- Tanda Tangan Asli Digital (Di Sebelah Kanan Cap, Tidak Ditimpa) -->
                            <div class="flex-1 flex items-center justify-center -ml-4 z-10">
                                @if($ttdImg)
                                    <img src="{{ $ttdImg }}" alt="TTD Digital" class="h-16 max-w-[115px] object-contain" onerror="this.style.display='none'">
                                @endif
                            </div>
                        </div>

                        <!-- Garis Nama Penerima -->
                        <div class="border-t border-dotted border-slate-600 pt-1 w-44 font-bold text-slate-900 text-[11px]">
                            {{ $pejabatNama }}
                        </div>
                        <span class="text-[9px] text-slate-500">{{ $pejabatJabatan }}</span>
                    </div>

                    <!-- KANAN (WALI / SANTRI) -->
                    <div class="flex flex-col items-center justify-between">
                        <span class="font-semibold text-slate-700">{{ $isPenarikanTabungan ? 'Penerima' : 'Penyetor' }}</span>
                        <div class="h-20 flex items-center justify-center">
                            <span class="text-[10px] text-slate-300 italic">(Tanda Tangan)</span>
                        </div>
                        <div class="border-t border-dotted border-slate-600 pt-1 w-32 font-semibold text-slate-800 text-[11px] truncate">
                            {{ $namaPenyetor }}
                        </div>
                        <span class="text-[9px] text-slate-500">Wali / Santri</span>
                    </div>

                </div>

            </div>

            <!-- KOLOM KANAN (5/12): TANGGAL, TABEL POS TUNAI/JUMLAH & TERBILANG -->
            <div class="md:col-span-6 space-y-2">
                
                <!-- Tanggal Transaksi (Right Aligned seperti di contoh slip) -->
                <div class="text-right text-xs font-semibold text-slate-800 mb-1">
                    Tanggal : <span class="font-bold border-b border-dotted border-slate-400 pb-0.5 px-2">{{ isset($payment->tanggal_bayar) && $payment->tanggal_bayar ? (is_string($payment->tanggal_bayar) ? \Carbon\Carbon::parse($payment->tanggal_bayar)->translatedFormat('d F Y') : optional($payment->tanggal_bayar)->translatedFormat('d F Y')) : date('d F Y') }}</span>
                </div>

                <!-- TABEL MATRIKS RINCIAN POS PEMBAYARAN -->
                <div class="border-2 border-[#208075] rounded-lg overflow-hidden">
                    <table class="w-full text-xs border-collapse">
                        <thead>
                            <tr class="bg-[#208075] text-white font-black uppercase text-[11px] tracking-wider">
                                <th class="py-1.5 px-3 text-left border-r border-[#196960] w-7/12">
                                    {{ strtoupper($payment->metode_pembayaran ?: 'TUNAI') }}
                                </th>
                                <th class="py-1.5 px-3 text-right">JUMLAH NOMINAL</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#208075]/30 text-slate-800">
                            
                            <!-- Cetak Seluruh Pos yang Dibayarkan -->
                            @foreach($itemsList as $it)
                                <tr class="h-7 hover:bg-slate-50 {{ ($it->nominal ?? 0) < 0 ? 'bg-purple-50/50' : '' }}">
                                    <td class="py-1 px-3 border-r border-[#208075]/30 font-bold uppercase text-[11px] {{ ($it->nominal ?? 0) < 0 ? 'text-purple-800' : '' }}">
                                        <div>{{ $it->pos_biaya }}</div>
                                        @if(isset($it->bill) && $it->bill && $it->bill->nominal_potongan > 0)
                                            <div class="text-[9px] font-medium text-purple-700 normal-case tracking-normal">
                                                (Tarif Asli: Rp {{ number_format($it->bill->nominal_asli, 0, ',', '.') }} &bull; Subsidi/Potongan: -Rp {{ number_format($it->bill->nominal_potongan, 0, ',', '.') }}{{ $it->bill->alasan_potongan ? ' - ' . $it->bill->alasan_potongan : '' }})
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-1 px-3 text-right font-mono font-bold {{ ($it->nominal ?? 0) < 0 ? 'text-purple-700' : '' }}">
                                        {{ ($it->nominal ?? 0) < 0 ? '- Rp ' . number_format(abs($it->nominal), 0, ',', '.') : 'Rp ' . number_format($it->nominal ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach

                            <!-- Padding Baris Kosong agar Persis Buku Slip Cetakan Fisik -->
                            @for($i = $itemsList->count(); $i < $targetRowCount; $i++)
                                <tr class="h-6">
                                    <td class="py-1 px-3 border-r border-[#208075]/30 text-slate-300"></td>
                                    <td class="py-1 px-3 text-right font-mono text-slate-300"></td>
                                </tr>
                            @endfor

                            <!-- BARIS TOTAL -->
                            <tr class="bg-emerald-50/50 border-t-2 border-[#208075] font-black">
                                <td class="py-2 px-3 border-r border-[#208075] uppercase text-[#208075] text-[11px] tracking-wider">
                                    TOTAL
                                </td>
                                <td class="py-2 px-3 text-right font-mono text-sm text-[#208075] font-black">
                                    Rp {{ number_format($payment->nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- TERBILANG (HURUF RUPIAH) -->
                <div class="pt-2 text-xs">
                    <div class="flex items-baseline gap-1 text-slate-700">
                        <span class="font-bold shrink-0">Terbilang :</span>
                        <span class="flex-1 font-semibold italic text-slate-900 border-b border-dotted border-slate-500 pb-0.5">
                            {{ trim(terbilangAngka($payment->nominal)) }} Rupiah
                        </span>
                    </div>
                </div>

                {{-- Sisa Saldo Tabungan & Sisa Tunggakan disembunyikan dari kwitansi (permintaan admin) --}}

            </div>

        </div>

        <!-- FOOTER RESMI -->
        <div class="bg-slate-50 border-t border-slate-200 px-5 py-2 text-[10px] text-slate-400 flex items-center justify-between">
            <span>Lembar Bukti {{ $isPenarikanTabungan ? 'Penarikan' : 'Setoran' }} Sah &bull; Dicetak otomatis dari Sistem Administrasi Keuangan Ponpes Hidayatullah Tuksongo</span>
            <span>Simpan slip ini sebagai tanda bukti transaksi yang sah</span>
        </div>

    </div>

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

        function downloadReceiptImage() {
            const slip = document.getElementById('receipt-slip');
            const btn = document.getElementById('btnDownloadImage');
            const btnText = document.getElementById('downloadBtnText');
            if (!slip) return;

            if (typeof html2canvas === 'undefined') {
                alert('Library pembuat gambar sedang dimuat, mohon coba sesaat lagi.');
                return;
            }

            const originalHtml = btnText.innerHTML;
            btn.disabled = true;
            btn.classList.add('opacity-75');
            btnText.innerHTML = 'Memproses Gambar...';

            html2canvas(slip, {
                scale: 2.5, // 2.5x HD Resolution untuk hasil tajam
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff',
                logging: false,
                scrollX: 0,
                scrollY: 0
            }).then(canvas => {
                const link = document.createElement('a');
                const rawName = "{{ addslashes($namaSantri) }}";
                const cleanName = rawName.replace(/[^a-zA-Z0-9]/g, '_');
                const noTransaksi = "{{ preg_replace('/[^A-Za-z0-9_-]/', '', $payment->no_transaksi) }}";
                link.download = `Kwitansi_${noTransaksi}_${cleanName}.png`;
                link.href = canvas.toDataURL('image/png', 1.0);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                btnText.innerHTML = '✓ Berhasil Diunduh';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-75');
                    btnText.innerHTML = originalHtml;
                }, 2000);
            }).catch(err => {
                console.error(err);
                alert('Gagal mendownload gambar kwitansi: ' + err.message);
                btn.disabled = false;
                btn.classList.remove('opacity-75');
                btnText.innerHTML = originalHtml;
            });
        }
    </script>
</body>
</html>

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

// Persiapkan rows pos pembayaran
$itemsList = (isset($payment->items) && $payment->items && $payment->items->count() > 0) ? $payment->items : collect();
if ($itemsList->isEmpty()) {
    $itemsList = collect([
        (object)[
            'pos_biaya' => ($payment->jenis_pembayaran ?? '') ?: 'SPP / Syahriyah',
            'nominal' => $payment->nominal ?? 0,
        ]
    ]);
}

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

// Format Tanggal Bayar
$tglBayarRaw = $payment->tanggal_bayar ?? ($payment->created_at ?? now());
if (is_string($tglBayarRaw)) {
    try {
        $tglBayarFormat = \Carbon\Carbon::parse($tglBayarRaw)->translatedFormat('d F Y');
    } catch (\Throwable $e) {
        $tglBayarFormat = date('d F Y');
    }
} else {
    $tglBayarFormat = optional($tglBayarRaw)->translatedFormat('d F Y') ?: date('d F Y');
}

// Catatan Kwitansi
if (!empty($payment->catatan)) {
    $catatanKwitansi = $payment->catatan;
} elseif (!empty($payment->bulan)) {
    $catatanKwitansi = "Iuran / SPP Bulan {$payment->bulan} " . ($payment->tahun ?? date('Y'));
} else {
    $catatanKwitansi = $itemsList->pluck('pos_biaya')->implode(', ') ?: 'Pembayaran Administrasi Pesantren';
}
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style id="page-print-style">
        @page {
            size: A4 portrait;
            margin: 4mm 6mm;
        }
    </style>
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #0f172a;
        }
        .font-arabic { font-family: 'Amiri', serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* KUNCI TATA LETAK SAMA PERSIS: LAYAR & PRINT DIJAMIN 100% SAMA */
        .receipt-card {
            border: 1.5px solid #1a685f;
            border-radius: 8px;
            background: #ffffff;
            overflow: hidden;
        }
        .receipt-card-arsip {
            border: 1.5px solid #334155;
            border-radius: 8px;
            background: #ffffff;
            overflow: hidden;
        }

        /* 2 KOLOM KUNCI: SELALU BERDAMPINGAN KIRI-KANAN (TIDAK BOLEH MENUMPUK KE BAWAH) */
        .receipt-body-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 14px !important;
            padding: 12px 16px !important;
        }
        .receipt-col-left {
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            border-right: 1px solid #e2e8f0 !important;
            padding-right: 14px !important;
            min-width: 0 !important;
        }
        .receipt-col-right {
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            min-width: 0 !important;
        }

        @media print {
            .no-print { display: none !important; }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            html, body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: auto !important;
                min-height: 0 !important;
            }
            .print-sheet {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                padding: 0 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            .receipt-card, .receipt-card-arsip {
                box-shadow: none !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                overflow: hidden !important;
            }
            .receipt-body-grid {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 14px !important;
                padding: 10px 14px !important;
            }
            .receipt-col-left {
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                border-right: 1px solid #cbd5e1 !important;
                padding-right: 14px !important;
            }
            .receipt-col-right {
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
            }
            .cut-line {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 p-2 sm:p-5 flex flex-col items-center justify-start min-h-screen text-slate-800"
      x-data="{
          printMode: localStorage.getItem('kwitansi_print_mode') || 'a4_double',
          setMode(mode) {
              this.printMode = mode;
              localStorage.setItem('kwitansi_print_mode', mode);
              const styleTag = document.getElementById('page-print-style');
              if (!styleTag) return;
              if (mode === 'a5') {
                  styleTag.innerHTML = '@page { size: A5 landscape; margin: 4mm 5mm; }';
              } else if (mode === 'a4_single') {
                  styleTag.innerHTML = '@page { size: A4 portrait; margin: 8mm 10mm; }';
              } else {
                  styleTag.innerHTML = '@page { size: A4 portrait; margin: 4mm 6mm; }';
              }
          }
      }"
      x-init="setMode(printMode)">

    <!-- ========================================================================= -->
    <!-- ACTION TOOLBAR (HANYA MUNCUL DI LAYAR, OTOMATIS HILANG SAAT DICETAK)      -->
    <!-- ========================================================================= -->
    <div class="no-print max-w-4xl w-full mb-3 flex flex-wrap items-center justify-between gap-2.5 bg-white p-3 rounded-2xl border border-slate-200 shadow-sm">
        <a href="{{ auth()->guard('santri')->check() ? route('santri.pembayaran') : route('admin.pembayaran.index') }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
            &larr; Kembali
        </a>

        <!-- Pilihan Mode Cetak Standar -->
        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs">
            <span class="text-[11px] font-bold text-slate-500 px-2">Format:</span>
            <button type="button" 
                    @click="setMode('a4_double')" 
                    :class="printMode === 'a4_double' ? 'bg-[#1a685f] text-white shadow-xs' : 'text-slate-700 hover:bg-white'"
                    class="px-3 py-1 rounded-lg font-bold text-xs transition cursor-pointer flex items-center gap-1">
                <span>🌟 2 Rangkap (A4 Standar)</span>
            </button>
            <button type="button" 
                    @click="setMode('a4_single')" 
                    :class="printMode === 'a4_single' ? 'bg-[#1a685f] text-white shadow-xs' : 'text-slate-700 hover:bg-white'"
                    class="px-2.5 py-1 rounded-lg font-bold text-xs transition cursor-pointer">
                <span>1 Lembar A4</span>
            </button>
            <button type="button" 
                    @click="setMode('a5')" 
                    :class="printMode === 'a5' ? 'bg-[#1a685f] text-white shadow-xs' : 'text-slate-700 hover:bg-white'"
                    class="px-2.5 py-1 rounded-lg font-bold text-xs transition cursor-pointer">
                <span>Kertas A5 (Landscape)</span>
            </button>
        </div>

        <div class="flex items-center gap-2">
            <!-- Tombol Download Gambar (PNG) -->
            <button type="button" id="btnDownloadImage" onclick="downloadReceiptImage()" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span id="downloadBtnText">Unduh PNG</span>
            </button>

            <!-- Tombol Edit Stempel & TTD -->
            <a href="{{ route('admin.settings.index') }}?open_tab=tab-ttd#tab-ttd"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-700 hover:bg-slate-800 text-white text-xs font-bold shadow-xs transition"
               title="Edit tanda tangan digital dan cap stempel">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8.5 14.5l1.5-1.5 5-5"/><path d="M14.5 8.5L16 10"/></svg>
                <span>Edit TTD</span>
            </a>

            <!-- Tombol Cetak (Print) -->
            <button type="button" onclick="window.print()" 
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-[#1a685f] hover:bg-[#14534c] text-white text-xs font-bold shadow-md hover:shadow-lg transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>CETAK (PRINT)</span>
            </button>
        </div>
    </div>

    <!-- Info Bantuan Cetak -->
    <div class="no-print max-w-4xl w-full mb-3 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-[11px] text-emerald-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="font-bold text-emerald-900">💡 Tips Cetak Printer Brother / Standar:</span>
            <span>Gunakan mode <strong>"2 Rangkap (A4 Standar)"</strong> untuk menghasilkan 2 kwitansi sekaligus (Santri & Kasir) dalam 1 lembar kertas A4 tanpa tumpah ke halaman 2.</span>
        </div>
        <span class="text-[10px] text-emerald-700">Pastikan centang <em>"Background graphics"</em> jika ingin warna hijau toska penuh.</span>
    </div>

    <!-- ========================================================================= -->
    <!-- DOKUMEN KWITANSI UTAMA (SESUAI UKURAN STANDAR)                             -->
    <!-- ========================================================================= -->
    <div id="receipt-capture-area" class="print-sheet max-w-4xl w-full space-y-3">

        <!-- ============================================================ -->
        <!-- LEMBAR 1: UNTUK SANTRI / WALI SANTRI                         -->
        <!-- ============================================================ -->
        <div class="receipt-card bg-white rounded-xl shadow-md border-2 border-[#1a685f] overflow-hidden relative">
            
            <!-- HEADER KOP RESMI PESANTREN -->
            <div class="{{ $isPenarikanTabungan ? 'bg-[#b45309]' : 'bg-[#1a685f]' }} text-white px-4 py-2 flex items-center justify-between border-b-2 border-emerald-900">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-white/15 border border-white/30 flex items-center justify-center p-0.5 shrink-0">
                        <img src="/logo.png" alt="Logo" class="w-full h-full object-contain" onerror="this.src='/logo1.png'">
                    </div>
                    <div>
                        <div class="font-arabic text-[11px] leading-tight text-white/95">مَعْهَدُ هِدَايَةِ اللهِ لِلتَّرْبِيَةِ الإِسْلَامِيَّةِ</div>
                        <h1 class="text-xs font-black tracking-wider uppercase leading-none mt-0.5">PONDOK PESANTREN HIDAYATULLAH TUKSONGO</h1>
                        <p class="text-[9px] text-emerald-100/90 leading-tight">Desa Tuksongo, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah &bull; Telp/WA: 0812-3456-7890</p>
                    </div>
                </div>

                <div class="text-right shrink-0">
                    <div class="text-xs font-black tracking-widest uppercase leading-tight">
                        {{ $isPenarikanTabungan ? 'BUKTI PENARIKAN TABUNGAN' : 'BUKTI SETORAN PEMBAYARAN' }}
                    </div>
                    <div class="inline-block mt-0.5 px-2 py-0.5 rounded bg-white/20 text-[9px] font-bold tracking-wider uppercase border border-white/30">
                        LEMBAR 1 (UNTUK WALI / SANTRI)
                    </div>
                </div>
            </div>

            <!-- BODY KWITANSI: 2 KOLOM MUTLAK BERDAMPINGAN (SAMA PERSIS DI LAYAR MAUPUN PRINT) -->
            <div class="receipt-body-grid text-xs">
                
                <!-- KOLOM KIRI: DATA TRANSAKSI & TANDA TANGAN -->
                <div class="receipt-col-left space-y-2">
                    
                    <div class="space-y-1">
                        <div class="flex items-baseline text-[11px]">
                            <span class="w-24 text-slate-500 font-semibold shrink-0">No. Kwitansi</span>
                            <span class="text-slate-400 mr-1 shrink-0">:</span>
                            <span class="font-mono font-bold text-slate-900 text-[11.5px] border-b border-dotted border-slate-400 flex-1">{{ $payment->no_transaksi }}</span>
                        </div>
                        <div class="flex items-baseline text-[11px]">
                            <span class="w-24 text-slate-500 font-semibold shrink-0">{{ $isPenarikanTabungan ? 'Diberikan Kepada' : 'Telah Terima Dari' }}</span>
                            <span class="text-slate-400 mr-1 shrink-0">:</span>
                            <span class="font-bold text-slate-900 uppercase text-[11px] border-b border-dotted border-slate-400 flex-1">{{ $namaPenyetor }}</span>
                        </div>
                        <div class="flex items-baseline text-[11px]">
                            <span class="w-24 text-slate-500 font-semibold shrink-0">Nama Santri</span>
                            <span class="text-slate-400 mr-1 shrink-0">:</span>
                            <span class="font-bold text-[#1a685f] uppercase text-[11px] border-b border-dotted border-slate-400 flex-1">{{ $namaSantri }}</span>
                        </div>
                        <div class="flex items-baseline text-[11px]">
                            <span class="w-24 text-slate-500 font-semibold shrink-0">Kelas / Jenjang</span>
                            <span class="text-slate-400 mr-1 shrink-0">:</span>
                            <span class="font-medium text-slate-800 text-[11px] border-b border-dotted border-slate-400 flex-1">{{ $kelasTeks }}</span>
                        </div>
                        <div class="flex items-baseline text-[11px]">
                            <span class="w-24 text-slate-500 font-semibold shrink-0">Keterangan</span>
                            <span class="text-slate-400 mr-1 shrink-0">:</span>
                            <span class="text-slate-800 text-[11px] border-b border-dotted border-slate-400 flex-1 leading-snug">{{ $catatanKwitansi }}</span>
                        </div>
                    </div>

                    <!-- TANDA TANGAN BERDAMPINGAN DENGAN LEBAR SEIMBANG -->
                    <div class="pt-2 border-t border-slate-200 flex items-end justify-between gap-3 text-center">
                        <!-- Penyetor -->
                        <div class="w-[48%] flex flex-col items-center">
                            <span class="text-[10px] font-semibold text-slate-600 mb-0.5">{{ $isPenarikanTabungan ? 'Penerima Dana' : 'Penyetor / Wali' }}</span>
                            <div class="h-12 flex items-center justify-center">
                                <span class="text-[9px] text-slate-300 italic">(Tanda Tangan)</span>
                            </div>
                            <div class="border-t border-dotted border-slate-600 w-full pt-0.5 text-[10px] font-bold text-slate-800 truncate">
                                {{ $namaPenyetor }}
                            </div>
                            <span class="text-[8.5px] text-slate-500">Wali / Santri</span>
                        </div>

                        <!-- Penerima / Bendahara -->
                        <div class="w-[48%] flex flex-col items-center relative">
                            <span class="text-[10px] font-semibold text-slate-600 mb-0.5">{{ $isPenarikanTabungan ? 'Yang Menyerahkan' : 'Bendahara / Kasir' }}</span>
                            <div class="h-12 w-full relative flex items-center justify-center">
                                @if($stempelImg)
                                    <img src="{{ $stempelImg }}" alt="Cap Stempel" class="absolute left-1 w-12 h-12 object-contain opacity-80 pointer-events-none transform -rotate-6 z-0" onerror="this.style.display='none'">
                                @endif
                                @if($ttdImg)
                                    <img src="{{ $ttdImg }}" alt="TTD Digital" class="h-11 max-w-[95px] object-contain relative z-10 -ml-1" onerror="this.style.display='none'">
                                @endif
                            </div>
                            <div class="border-t border-dotted border-slate-600 w-full pt-0.5 text-[10px] font-bold text-slate-900 truncate">
                                {{ $pejabatNama }}
                            </div>
                            <span class="text-[8.5px] text-slate-500">{{ $pejabatJabatan }}</span>
                        </div>
                    </div>

                </div>

                <!-- KOLOM KANAN: TANGGAL, TABEL POS PEMBAYARAN, TOTAL & TERBILANG -->
                <div class="receipt-col-right space-y-2">
                    <div>
                        <!-- Tanggal & Metode Bayar -->
                        <div class="flex items-center justify-between text-[11px] mb-1.5 pb-1 border-b border-slate-200">
                            <span class="text-slate-500">Metode: <strong class="text-slate-800 uppercase font-mono">{{ $payment->metode_pembayaran ?: 'Tunai' }}</strong></span>
                            <span class="text-slate-500">Tanggal: <strong class="text-slate-900 font-semibold">{{ $tglBayarFormat }}</strong></span>
                        </div>

                        <!-- TABEL RINCIAN POS PEMBAYARAN -->
                        <div class="border border-[#1a685f] rounded overflow-hidden">
                            <table class="w-full text-left border-collapse text-[10.5px]">
                                <thead>
                                    <tr class="bg-[#1a685f] text-white uppercase text-[9.5px] font-bold tracking-wider">
                                        <th class="py-1 px-2.5 border-r border-[#15544d]">POS BIAYA / ITEM</th>
                                        <th class="py-1 px-2.5 text-right w-28">JUMLAH (RP)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 text-slate-800">
                                    @foreach($itemsList as $it)
                                    <tr class="h-6 hover:bg-slate-50">
                                        <td class="py-0.5 px-2.5 font-bold uppercase text-[10.5px] text-slate-800 border-r border-slate-200">
                                            {{ $it->pos_biaya }}
                                        </td>
                                        <td class="py-0.5 px-2.5 text-right font-mono font-bold text-slate-900">
                                            Rp {{ number_format($it->nominal ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @for($i = $itemsList->count(); $i < 3; $i++)
                                    <tr class="h-5">
                                        <td class="py-0.5 px-2.5 border-r border-slate-200 text-slate-200"></td>
                                        <td class="py-0.5 px-2.5 text-right font-mono text-slate-200"></td>
                                    </tr>
                                    @endfor
                                    <!-- Baris Total -->
                                    <tr class="bg-emerald-50/80 font-black border-t-2 border-[#1a685f]">
                                        <td class="py-1 px-2.5 uppercase text-[#1a685f] text-[10px] tracking-wider border-r border-slate-200">
                                            TOTAL DITERIMA
                                        </td>
                                        <td class="py-1 px-2.5 text-right font-mono font-black text-emerald-900 text-xs">
                                            Rp {{ number_format($payment->nominal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- KOTAK TERBILANG -->
                    <div class="bg-slate-50 border border-slate-200 rounded p-1.5 text-[10px]">
                        <span class="text-slate-500 font-semibold">Terbilang : </span>
                        <span class="font-bold text-slate-900 italic"># {{ trim(terbilangAngka($payment->nominal)) }} Rupiah #</span>
                    </div>
                </div>

            </div>

            <!-- FOOTER RESMI -->
            <div class="bg-slate-50 border-t border-slate-200 px-4 py-1 text-[8.5px] text-slate-400 flex items-center justify-between">
                <span>Bukti {{ $isPenarikanTabungan ? 'Penarikan' : 'Setoran' }} Sah &bull; Dicetak otomatis dari SIM Keuangan Ponpes Hidayatullah Tuksongo</span>
                <span class="font-mono">{{ date('d/m/Y H:i') }}</span>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- GARIS POTONG PEMBATAS (HANYA MUNCUL DI MODE 2 RANGKAP)        -->
        <!-- ============================================================ -->
        <div x-show="printMode === 'a4_double'" 
             class="cut-line w-full my-2 flex items-center justify-center text-slate-400 font-mono text-[9px] print:my-1.5">
            <div class="border-b border-dashed border-slate-400 flex-1"></div>
            <div class="px-3 bg-white text-slate-500 font-bold uppercase tracking-wider flex items-center gap-1.5">
                ✂️ POTONG DI SINI &bull; LEMBAR ARSIP BENDAHARA
            </div>
            <div class="border-b border-dashed border-slate-400 flex-1"></div>
        </div>

        <!-- ============================================================ -->
        <!-- LEMBAR 2: UNTUK ARSIP BENDAHARA / KASIR (HANYA 2 RANGKAP)    -->
        <!-- ============================================================ -->
        <div x-show="printMode === 'a4_double'" 
             class="receipt-card-arsip bg-white rounded-xl shadow-md border-2 border-slate-600 overflow-hidden relative">
            
            <!-- HEADER STRIP KOP LEMBAR ARSIP -->
            <div class="bg-slate-700 text-white px-4 py-2 flex items-center justify-between border-b-2 border-slate-900">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-white/15 border border-white/30 flex items-center justify-center p-0.5 shrink-0">
                        <img src="/logo.png" alt="Logo" class="w-full h-full object-contain" onerror="this.src='/logo1.png'">
                    </div>
                    <div>
                        <div class="font-arabic text-[11px] leading-tight text-white/95">مَعْهَدُ هِدَايَةِ اللهِ لِلتَّرْبِيَةِ الإِسْلَامِيَّةِ</div>
                        <h2 class="text-xs font-black tracking-wider uppercase leading-none mt-0.5">PONDOK PESANTREN HIDAYATULLAH TUKSONGO</h2>
                        <p class="text-[9px] text-slate-200/90 leading-tight">Desa Tuksongo, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah &bull; Telp/WA: 0812-3456-7890</p>
                    </div>
                </div>

                <div class="text-right shrink-0">
                    <div class="text-xs font-black tracking-widest uppercase leading-tight">
                        {{ $isPenarikanTabungan ? 'BUKTI PENARIKAN TABUNGAN' : 'BUKTI SETORAN PEMBAYARAN' }}
                    </div>
                    <div class="inline-block mt-0.5 px-2 py-0.5 rounded bg-white/20 text-[9px] font-bold tracking-wider uppercase border border-white/30">
                        LEMBAR 2 (UNTUK ARSIP BENDAHARA / KASIR)
                    </div>
                </div>
            </div>

            <!-- BODY KWITANSI SALINAN ARSIP (2 KOLOM MUTLAK BERDAMPINGAN) -->
            <div class="receipt-body-grid text-xs">
                
                <!-- KOLOM KIRI: DATA TRANSAKSI & TANDA TANGAN -->
                <div class="receipt-col-left space-y-2">
                    
                    <div class="space-y-1">
                        <div class="flex items-baseline text-[11px]">
                            <span class="w-24 text-slate-500 font-semibold shrink-0">No. Kwitansi</span>
                            <span class="text-slate-400 mr-1 shrink-0">:</span>
                            <span class="font-mono font-bold text-slate-900 text-[11.5px] border-b border-dotted border-slate-400 flex-1">{{ $payment->no_transaksi }}</span>
                        </div>
                        <div class="flex items-baseline text-[11px]">
                            <span class="w-24 text-slate-500 font-semibold shrink-0">{{ $isPenarikanTabungan ? 'Diberikan Kepada' : 'Telah Terima Dari' }}</span>
                            <span class="text-slate-400 mr-1 shrink-0">:</span>
                            <span class="font-bold text-slate-900 uppercase text-[11px] border-b border-dotted border-slate-400 flex-1">{{ $namaPenyetor }}</span>
                        </div>
                        <div class="flex items-baseline text-[11px]">
                            <span class="w-24 text-slate-500 font-semibold shrink-0">Nama Santri</span>
                            <span class="text-slate-400 mr-1 shrink-0">:</span>
                            <span class="font-bold text-slate-900 uppercase text-[11px] border-b border-dotted border-slate-400 flex-1">{{ $namaSantri }}</span>
                        </div>
                        <div class="flex items-baseline text-[11px]">
                            <span class="w-24 text-slate-500 font-semibold shrink-0">Kelas / Jenjang</span>
                            <span class="text-slate-400 mr-1 shrink-0">:</span>
                            <span class="font-medium text-slate-800 text-[11px] border-b border-dotted border-slate-400 flex-1">{{ $kelasTeks }}</span>
                        </div>
                        <div class="flex items-baseline text-[11px]">
                            <span class="w-24 text-slate-500 font-semibold shrink-0">Keterangan</span>
                            <span class="text-slate-400 mr-1 shrink-0">:</span>
                            <span class="text-slate-800 text-[11px] border-b border-dotted border-slate-400 flex-1 leading-snug">{{ $catatanKwitansi }}</span>
                        </div>
                    </div>

                    <!-- TANDA TANGAN BERDAMPINGAN DENGAN LEBAR SEIMBANG -->
                    <div class="pt-2 border-t border-slate-200 flex items-end justify-between gap-3 text-center">
                        <!-- Penyetor -->
                        <div class="w-[48%] flex flex-col items-center">
                            <span class="text-[10px] font-semibold text-slate-600 mb-0.5">{{ $isPenarikanTabungan ? 'Penerima Dana' : 'Penyetor / Wali' }}</span>
                            <div class="h-12 flex items-center justify-center">
                                <span class="text-[9px] text-slate-300 italic">(Tanda Tangan)</span>
                            </div>
                            <div class="border-t border-dotted border-slate-600 w-full pt-0.5 text-[10px] font-bold text-slate-800 truncate">
                                {{ $namaPenyetor }}
                            </div>
                            <span class="text-[8.5px] text-slate-500">Wali / Santri</span>
                        </div>

                        <!-- Penerima / Bendahara -->
                        <div class="w-[48%] flex flex-col items-center relative">
                            <span class="text-[10px] font-semibold text-slate-600 mb-0.5">{{ $isPenarikanTabungan ? 'Yang Menyerahkan' : 'Bendahara / Kasir' }}</span>
                            <div class="h-12 w-full relative flex items-center justify-center">
                                @if($stempelImg)
                                    <img src="{{ $stempelImg }}" alt="Cap Stempel" class="absolute left-1 w-12 h-12 object-contain opacity-80 pointer-events-none transform -rotate-6 z-0" onerror="this.style.display='none'">
                                @endif
                                @if($ttdImg)
                                    <img src="{{ $ttdImg }}" alt="TTD Digital" class="h-11 max-w-[95px] object-contain relative z-10 -ml-1" onerror="this.style.display='none'">
                                @endif
                            </div>
                            <div class="border-t border-dotted border-slate-600 w-full pt-0.5 text-[10px] font-bold text-slate-900 truncate">
                                {{ $pejabatNama }}
                            </div>
                            <span class="text-[8.5px] text-slate-500">{{ $pejabatJabatan }}</span>
                        </div>
                    </div>

                </div>

                <!-- KOLOM KANAN: TANGGAL, TABEL POS PEMBAYARAN, TOTAL & TERBILANG -->
                <div class="receipt-col-right space-y-2">
                    <div>
                        <!-- Tanggal & Metode Bayar -->
                        <div class="flex items-center justify-between text-[11px] mb-1.5 pb-1 border-b border-slate-200">
                            <span class="text-slate-500">Metode: <strong class="text-slate-800 uppercase font-mono">{{ $payment->metode_pembayaran ?: 'Tunai' }}</strong></span>
                            <span class="text-slate-500">Tanggal: <strong class="text-slate-900 font-semibold">{{ $tglBayarFormat }}</strong></span>
                        </div>

                        <!-- TABEL RINCIAN POS PEMBAYARAN -->
                        <div class="border border-slate-600 rounded overflow-hidden">
                            <table class="w-full text-left border-collapse text-[10.5px]">
                                <thead>
                                    <tr class="bg-slate-700 text-white uppercase text-[9.5px] font-bold tracking-wider">
                                        <th class="py-1 px-2.5 border-r border-slate-600">POS BIAYA / ITEM</th>
                                        <th class="py-1 px-2.5 text-right w-28">JUMLAH (RP)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 text-slate-800">
                                    @foreach($itemsList as $it)
                                    <tr class="h-6 hover:bg-slate-50">
                                        <td class="py-0.5 px-2.5 font-bold uppercase text-[10.5px] text-slate-800 border-r border-slate-200">
                                            {{ $it->pos_biaya }}
                                        </td>
                                        <td class="py-0.5 px-2.5 text-right font-mono font-bold text-slate-900">
                                            Rp {{ number_format($it->nominal ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @for($i = $itemsList->count(); $i < 3; $i++)
                                    <tr class="h-5">
                                        <td class="py-0.5 px-2.5 border-r border-slate-200 text-slate-200"></td>
                                        <td class="py-0.5 px-2.5 text-right font-mono text-slate-200"></td>
                                    </tr>
                                    @endfor
                                    <!-- Baris Total -->
                                    <tr class="bg-slate-100 font-black border-t-2 border-slate-600">
                                        <td class="py-1 px-2.5 uppercase text-slate-700 text-[10px] tracking-wider border-r border-slate-200">
                                            TOTAL DITERIMA
                                        </td>
                                        <td class="py-1 px-2.5 text-right font-mono font-black text-slate-900 text-xs">
                                            Rp {{ number_format($payment->nominal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- KOTAK TERBILANG -->
                    <div class="bg-slate-50 border border-slate-200 rounded p-1.5 text-[10px]">
                        <span class="text-slate-500 font-semibold">Terbilang : </span>
                        <span class="font-bold text-slate-900 italic"># {{ trim(terbilangAngka($payment->nominal)) }} Rupiah #</span>
                    </div>
                </div>

            </div>

            <!-- FOOTER RESMI -->
            <div class="bg-slate-50 border-t border-slate-200 px-4 py-1 text-[8.5px] text-slate-400 flex items-center justify-between">
                <span>Arsip Kasir / Bendahara Pesantren &bull; Simpan dokumen ini pada bundel arsip keuangan pondok</span>
                <span class="font-mono">{{ date('d/m/Y H:i') }}</span>
            </div>
        </div>

    </div>

    <!-- Script Download Gambar -->
    <script>
        function downloadReceiptImage() {
            const area = document.getElementById('receipt-capture-area');
            const btn = document.getElementById('btnDownloadImage');
            const btnText = document.getElementById('downloadBtnText');
            if (!area) return;

            if (typeof html2canvas === 'undefined') {
                alert('Library pembuat gambar sedang dimuat, mohon coba sesaat lagi.');
                return;
            }

            const originalHtml = btnText.innerHTML;
            btn.disabled = true;
            btn.classList.add('opacity-75');
            btnText.innerHTML = 'Memproses...';

            html2canvas(area, {
                scale: 2.5,
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

                btnText.innerHTML = '✓ Berhasil';
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

@php
$isPenarikanTabungan = stripos($payment->jenis_pembayaran, 'AMBIL TABUNGAN') !== false || stripos($payment->jenis_pembayaran, 'Pengambilan Tabungan') !== false;

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
$itemsList = $payment->items && $payment->items->count() > 0 ? $payment->items : collect();
if ($itemsList->isEmpty()) {
    $itemsList = collect([
        (object)[
            'pos_biaya' => $payment->jenis_pembayaran ?: 'SPP / Syahriyah',
            'nominal' => $payment->nominal,
        ]
    ]);
}
$targetRowCount = max(6, $itemsList->count());
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
            body { 
                background: white !important; 
                margin: 0; 
                padding: 0; 
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .slip-container {
                box-shadow: none !important;
                border: 1px solid #208075 !important;
                margin: 0 auto !important;
                page-break-inside: avoid;
            }
            @page {
                size: 210mm 140mm; /* Standar Slip Setoran Landscape */
                margin: 6mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 p-3 sm:p-6 flex flex-col items-center justify-center min-h-screen">

    <!-- Action Toolbar (Hidden When Printing) -->
    <div class="no-print max-w-4xl w-full mb-4 flex items-center justify-between gap-3">
        <a href="{{ auth()->guard('santri')->check() ? route('santri.pembayaran') : route('admin.pembayaran.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-xs transition">
            &larr; Kembali ke Pembayaran
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-[#208075] hover:bg-[#1a685f] text-white text-xs font-bold shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Bukti Setoran (Print)</span>
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- LEMBAR BUKTI SETORAN RESMI (IDENTIK DENGAN SLIP FISIK PONDOK TUKSONGO)     -->
    <!-- ========================================================================= -->
    <div class="slip-container max-w-4xl w-full bg-white rounded-xl shadow-lg border-2 border-[#208075] overflow-hidden relative">
        
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
                        {{ $payment->student->nama_lengkap ?? '—' }}
                    </span>
                </div>

                <!-- Kelas -->
                <div class="flex items-baseline gap-2">
                    <span class="w-28 text-slate-700 font-semibold shrink-0">Kelas</span>
                    <span class="text-slate-400 shrink-0">:</span>
                    <span class="flex-1 font-semibold text-slate-800 border-b border-dotted border-slate-400 pb-0.5">
                        {{ $payment->student->kelas ?? '—' }} ({{ $payment->student->jenjang ?? 'MTs/MA' }}) &bull; NIS: {{ $payment->student->nis ?? '—' }}
                    </span>
                </div>

                <!-- Berita / Keterangan -->
                <div class="flex items-baseline gap-2">
                    <span class="w-28 text-slate-700 font-semibold shrink-0">Berita/Keterangan</span>
                    <span class="text-slate-400 shrink-0">:</span>
                    <span class="flex-1 text-slate-800 border-b border-dotted border-slate-400 pb-0.5">
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
                <div class="flex items-baseline gap-2 pt-1">
                    <span class="w-28 text-slate-700 font-semibold shrink-0">{{ $isPenarikanTabungan ? 'Penerima Dana' : 'Nama Penyetor' }}</span>
                    <span class="text-slate-400 shrink-0">:</span>
                    <span class="flex-1 font-medium text-slate-900 border-b border-dotted border-slate-400 pb-0.5">
                        {{ $payment->student->nama_wali ?: $payment->student->nama_lengkap }}
                    </span>
                </div>

                <!-- Alamat -->
                <div class="flex items-baseline gap-2">
                    <span class="w-28 text-slate-700 font-semibold shrink-0">Alamat</span>
                    <span class="text-slate-400 shrink-0">:</span>
                    <span class="flex-1 text-slate-700 border-b border-dotted border-slate-400 pb-0.5">
                        {{ $payment->student->alamat ?: 'Pringsurat, Kab. Temanggung' }}
                    </span>
                </div>

                <!-- No. HP -->
                <div class="flex items-baseline gap-2">
                    <span class="w-28 text-slate-700 font-semibold shrink-0">No. HP</span>
                    <span class="text-slate-400 shrink-0">:</span>
                    <span class="flex-1 font-mono text-slate-800 border-b border-dotted border-slate-400 pb-0.5">
                        {{ $payment->student->no_whatsapp ?: ($payment->student->no_hp ?: '—') }}
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
                            {{ $payment->student->nama_wali ?: $payment->student->nama_lengkap }}
                        </div>
                        <span class="text-[9px] text-slate-500">Wali / Santri</span>
                    </div>

                </div>

            </div>

            <!-- KOLOM KANAN (5/12): TANGGAL, TABEL POS TUNAI/JUMLAH & TERBILANG -->
            <div class="md:col-span-6 space-y-2">
                
                <!-- Tanggal Transaksi (Right Aligned seperti di contoh slip) -->
                <div class="text-right text-xs font-semibold text-slate-800 mb-1">
                    Tanggal : <span class="font-bold border-b border-dotted border-slate-400 pb-0.5 px-2">{{ optional($payment->tanggal_bayar)->translatedFormat('d F Y') ?? date('d F Y') }}</span>
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
                                <tr class="h-7 hover:bg-slate-50">
                                    <td class="py-1 px-3 border-r border-[#208075]/30 font-bold uppercase text-[11px]">
                                        {{ $it->pos_biaya }}
                                    </td>
                                    <td class="py-1 px-3 text-right font-mono font-bold">
                                        Rp {{ number_format($it->nominal, 0, ',', '.') }}
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

                <!-- Status Bebas Tunggakan / Sisa Tunggakan & Sisa Tabungan (Keterangan Transparansi Santri) -->
                <div class="pt-2 space-y-1 text-[11px] border-t border-slate-200 mt-2">
                    @php
                        $tabunganSantri = isset($sisaTabunganSantri) ? $sisaTabunganSantri : ($payment->student->saldo_tabungan ?? 0);
                    @endphp
                    @if(isset($payment->student) && $payment->student)
                        <div class="flex items-center justify-between {{ $isPenarikanTabungan ? 'bg-amber-50 px-2 py-1 rounded border border-amber-200' : 'text-slate-600' }}">
                            <span class="font-semibold {{ $isPenarikanTabungan ? 'text-amber-900' : 'text-slate-600' }}">
                                Sisa Saldo Tabungan {{ $isPenarikanTabungan ? 'Saat Ini' : '' }}:
                            </span>
                            <span class="font-mono font-bold text-emerald-700 text-xs">
                                Rp {{ number_format($tabunganSantri, 0, ',', '.') }}
                            </span>
                        </div>
                    @endif

                    @if(isset($sisaTunggakanSantri))
                        <div class="flex items-center justify-between text-slate-500">
                            <span>Sisa Tunggakan Aktif:</span>
                            <span class="font-mono font-bold {{ $sisaTunggakanSantri > 0 ? 'text-rose-600' : 'text-emerald-700' }}">
                                {{ $sisaTunggakanSantri > 0 ? 'Rp ' . number_format($sisaTunggakanSantri, 0, ',', '.') : 'Lunas Bebas Tunggakan' }}
                            </span>
                        </div>
                    @endif
                </div>

            </div>

        </div>

        <!-- FOOTER RESMI -->
        <div class="bg-slate-50 border-t border-slate-200 px-5 py-2 text-[10px] text-slate-400 flex items-center justify-between">
            <span>Lembar Bukti {{ $isPenarikanTabungan ? 'Penarikan' : 'Setoran' }} Sah &bull; Dicetak otomatis dari Sistem Administrasi Keuangan Ponpes Hidayatullah Tuksongo</span>
            <span>Simpan slip ini sebagai tanda bukti transaksi yang sah</span>
        </div>

    </div>

</body>
</html>

@extends('admin.layout')

@section('title', 'Biodata Lengkap Santri: ' . $student->nama_lengkap)

@section('styles')
<style>
    /* ===== PRINT STYLES ===== */
    @media print {
        /* Hide all web UI elements */
        #sidebar, header, footer, .no-print,
        .backdrop, [x-show], [x-data="{ sidebarOpen: false }"] > aside,
        [x-data="{ sidebarOpen: false }"] > div:first-of-type + div > header,
        [x-data="{ sidebarOpen: false }"] > div:first-of-type + div > footer {
            display: none !important;
        }

        /* Reset layout for print */
        body {
            background: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Segoe UI', 'Arial', sans-serif !important;
            font-size: 10pt !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .lg\:ml-\[290px\] { margin-left: 0 !important; }
        main { padding: 0 !important; }

        /* Print container */
        #printableArea {
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Header area */
        .print-header {
            display: flex !important;
            border-bottom: 3px double #000 !important;
            padding-bottom: 12pt !important;
            margin-bottom: 16pt !important;
        }
        .print-header-logo { width: 60pt; height: 60pt; object-fit: contain; }
        .print-header-text { text-align: center; flex: 1; }
        .print-header-text h1 { font-size: 14pt; font-weight: 800; letter-spacing: 1pt; margin: 0; }
        .print-header-text h2 { font-size: 11pt; font-weight: 700; margin: 2pt 0; }
        .print-header-text p { font-size: 8pt; color: #333; margin: 0; }

        .print-title {
            text-align: center;
            font-size: 13pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2pt;
            margin-bottom: 14pt;
            padding-bottom: 4pt;
            border-bottom: 1px solid #999;
        }

        /* Photo section */
        .print-photo-section {
            display: flex !important;
            gap: 16pt;
            margin-bottom: 14pt;
            align-items: flex-start;
        }
        .print-photo {
            width: 90pt !important;
            height: 120pt !important;
            border: 1px solid #999 !important;
            border-radius: 4pt !important;
            object-fit: cover;
            flex-shrink: 0;
        }
        .print-photo-placeholder {
            width: 90pt !important;
            height: 120pt !important;
            border: 1px solid #999 !important;
            border-radius: 4pt !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28pt;
            font-weight: 700;
            color: #999;
            background: #f5f5f5 !important;
            flex-shrink: 0;
        }
        .print-identity-summary {
            flex: 1;
        }
        .print-identity-summary h3 {
            font-size: 14pt;
            font-weight: 800;
            margin: 0 0 4pt;
        }
        .print-identity-summary table td {
            padding: 2pt 8pt 2pt 0;
            font-size: 9pt;
            vertical-align: top;
        }
        .print-identity-summary table td:first-child {
            color: #555;
            white-space: nowrap;
        }
        .print-identity-summary table td:last-child {
            font-weight: 600;
        }

        /* Section styling */
        .print-section-title {
            font-size: 10pt !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5pt !important;
            margin: 12pt 0 6pt !important;
            padding-bottom: 3pt !important;
            border-bottom: 1.5pt solid #333 !important;
            color: #000 !important;
        }

        /* Data grid */
        .print-data-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 0 24pt !important;
        }
        .print-data-table {
            width: 100% !important;
            font-size: 9pt !important;
            border-collapse: collapse !important;
        }
        .print-data-table td {
            padding: 3pt 4pt !important;
            border-bottom: 0.5pt dotted #ccc !important;
            vertical-align: top !important;
        }
        .print-data-table td:first-child {
            color: #444 !important;
            width: 45% !important;
        }
        .print-data-table td:last-child {
            font-weight: 600 !important;
            color: #000 !important;
        }

        /* Parent cards */
        .print-parent-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr 1fr !important;
            gap: 8pt !important;
        }
        .print-parent-card {
            border: 0.5pt solid #999 !important;
            border-radius: 4pt !important;
            padding: 8pt !important;
            font-size: 8.5pt !important;
            page-break-inside: avoid !important;
        }
        .print-parent-card h5 {
            font-size: 9pt;
            font-weight: 700;
            border-bottom: 1pt solid #ccc;
            padding-bottom: 3pt;
            margin: 0 0 4pt;
        }
        .print-parent-card p {
            margin: 1.5pt 0;
        }
        .print-parent-card .label { color: #555; }
        .print-parent-card .value { font-weight: 600; }

        /* Payment table */
        .print-payment-table {
            width: 100% !important;
            font-size: 8.5pt !important;
            border-collapse: collapse !important;
            margin-top: 6pt !important;
        }
        .print-payment-table th {
            background: #e5e5e5 !important;
            font-weight: 700 !important;
            font-size: 8pt !important;
            text-transform: uppercase !important;
            padding: 4pt 6pt !important;
            border: 0.5pt solid #999 !important;
            text-align: left;
        }
        .print-payment-table td {
            padding: 3pt 6pt !important;
            border: 0.5pt solid #ccc !important;
        }

        /* Login / footer */
        .print-login-section {
            margin-top: 12pt !important;
            padding: 8pt !important;
            border: 0.5pt solid #999 !important;
            border-radius: 4pt !important;
            font-size: 9pt !important;
            background: #f9f9f9 !important;
        }

        /* Signature area */
        .print-signature {
            display: flex !important;
            justify-content: flex-end;
            margin-top: 24pt;
            page-break-inside: avoid;
        }
        .print-signature-box {
            text-align: center;
            font-size: 9pt;
            min-width: 180pt;
        }
        .print-signature-box .sig-line {
            margin-top: 50pt;
            border-top: 1pt solid #000;
            padding-top: 4pt;
            font-weight: 700;
        }

        /* Page */
        @page {
            size: A4;
            margin: 15mm 18mm;
        }
        .page-break-avoid { page-break-inside: avoid; }
    }

    /* Screen-only: Hide print-only elements */
    @media screen {
        .print-only { display: none !important; }
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Action Top Bar (hidden on print) -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-gray-600 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl shadow-theme-xs transition">
            &larr; Kembali ke Daftar Santri
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="ta-btn-sm-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Biodata
            </button>
            <a href="{{ route('admin.siswa.mutasi.index', ['q' => $student->nis]) }}" class="ta-btn-sm-outline text-orange-600 hover:text-orange-700 hover:bg-orange-50 border-orange-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Mutasi Santri
            </a>
            <a href="{{ route('admin.siswa.edit', $student->id) }}" class="ta-btn-sm-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Data Santri
            </a>
        </div>
    </div>

    <!-- Main Biodata Card -->
    <div class="ta-card" id="printableArea">

        <!-- ===== PRINT-ONLY: Formal Document Header ===== -->
        <div class="print-only print-header">
            <img src="/logo.png" alt="Logo" class="print-header-logo">
            <div class="print-header-text">
                <h1>PONDOK PESANTREN HIDAYATULLAH</h1>
                <h2>TUKSONGO — PRINGSURAT — TEMANGGUNG</h2>
                <p>Jl. Tuksongo, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah</p>
            </div>
            <img src="/logo.png" alt="Logo" class="print-header-logo" style="visibility:hidden;">
        </div>
        <div class="print-only print-title">Biodata Santri</div>

        <!-- ===== SCREEN: Card Header with Banner ===== -->
        <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 p-6 sm:p-8 text-white relative no-print">
            <div class="flex flex-col sm:flex-row items-center gap-6 relative z-10">
                @if($student->foto)
                    <img src="{{ $student->foto }}" alt="{{ $student->nama_lengkap }}" class="w-24 h-32 sm:w-28 sm:h-36 object-cover rounded-xl border-4 border-white/20 shadow-lg shrink-0">
                @else
                    <div class="w-24 h-32 sm:w-28 sm:h-36 bg-white/10 rounded-xl border-4 border-white/20 flex items-center justify-center text-white text-3xl font-bold shrink-0">
                        {{ strtoupper(substr($student->nama_lengkap, 0, 1)) }}
                    </div>
                @endif
                <div class="text-center sm:text-left flex-1">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold shadow-xs {{ $student->hunian === 'Mukim' ? 'bg-emerald-300 text-emerald-950' : 'bg-blue-300 text-blue-950' }}">
                            {{ $student->kategori_label }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-400 text-amber-950">
                            Kelas {{ $student->kelas }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $student->status === 'Aktif' ? 'bg-emerald-400 text-emerald-950' : 'bg-gray-200 text-gray-800' }}">
                            {{ $student->status }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-white/20 text-white">
                            Angkatan {{ $student->tahun_masuk }}
                        </span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">{{ $student->nama_lengkap }}</h2>
                    <p class="text-emerald-200 text-xs mt-1 font-mono">
                        NIS: <strong>{{ $student->nis }}</strong>
                        @if($student->nisn) &bull; No. Stambuk: <strong>{{ $student->nisn }}</strong> @endif
                    </p>
                    <p class="text-emerald-100 text-xs mt-2 opacity-90">
                        Pondok Pesantren Hidayatullah Tuksongo &bull; Pringsurat Temanggung
                    </p>
                </div>
            </div>
        </div>

        <!-- Banner Info Keuangan & Kategori Tagihan Bulanan Resmi Sesuai Brosur -->
        <div class="m-6 sm:m-8 p-4 bg-emerald-50/70 border border-emerald-200 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4 no-print shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-900">Kategori Tagihan &amp; Hunian:</h4>
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-extrabold {{ $student->hunian === 'Mukim' ? 'bg-emerald-200 text-emerald-950' : 'bg-blue-200 text-blue-950' }}">
                            {{ $student->kategori_label }}
                        </span>
                    </div>
                    <div class="text-xs text-emerald-950 mt-1 flex flex-wrap items-center gap-2">
                        <span>Standar Iuran: <strong>Rp {{ number_format($student->tarif_bulanan['total_bulanan'], 0, ',', '.') }} / bulan</strong></span>
                        <span>&bull;</span>
                        <span>Uang Makan 3x: <strong>Rp {{ number_format($student->tarif_bulanan['uang_makan'], 0, ',', '.') }}</strong> {{ $student->hunian === 'Laju' ? '(Non-Asrama / Rp 0)' : '' }}</span>
                        <span>&bull;</span>
                        <span>Syahriah: <strong>Rp {{ number_format($student->tarif_bulanan['syahriah'], 0, ',', '.') }}</strong></span>
                        <span>&bull;</span>
                        <span>Tabungan: <strong>Rp {{ number_format($student->tarif_bulanan['tabungan_wajib'], 0, ',', '.') }}</strong></span>
                    </div>
                </div>
            </div>
            <div class="shrink-0 flex items-center gap-2">
                <a href="{{ route('admin.pembayaran.index', ['tab' => 'kasir', 'q' => $student->nis]) }}" class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Buka Kasir Santri</span>
                </a>
            </div>
        </div>

        @php
            // Format tanggal lahir properly & safely (handles d/m/Y, Y-m-d, and text)
            $tglLahirFormatted = '—';
            if ($student->tanggal_lahir) {
                try {
                    $rawTgl = trim($student->tanggal_lahir);
                    if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $rawTgl)) {
                        $tglLahirFormatted = \Carbon\Carbon::createFromFormat('d/m/Y', $rawTgl)->translatedFormat('d F Y');
                    } else {
                        $tglLahirFormatted = \Carbon\Carbon::parse($rawTgl)->translatedFormat('d F Y');
                    }
                } catch (\Throwable $e) {
                    $tglLahirFormatted = $student->tanggal_lahir;
                }
            }
        @endphp

        <!-- ===== PRINT-ONLY: Photo + Identity Summary ===== -->
        <div class="print-only print-photo-section">
            @if($student->foto)
                <img src="{{ $student->foto }}" alt="{{ $student->nama_lengkap }}" class="print-photo">
            @else
                <div class="print-photo-placeholder">{{ strtoupper(substr($student->nama_lengkap, 0, 1)) }}</div>
            @endif
            <div class="print-identity-summary">
                <h3>{{ strtoupper($student->nama_lengkap) }}</h3>
                <table>
                    <tr><td>NIS</td><td>: {{ $student->nis }}</td></tr>
                    <tr><td>No. Stambuk</td><td>: {{ $student->nisn ?: '—' }}</td></tr>
                    <tr><td>Jenjang</td><td>: {{ $student->jenjang }}</td></tr>
                    <tr><td>Kelas</td><td>: {{ $student->kelas }}</td></tr>
                    <tr><td>Status</td><td>: {{ $student->status }}</td></tr>
                    <tr><td>Angkatan</td><td>: {{ $student->tahun_masuk }}</td></tr>
                    <tr><td>Jenis Kelamin</td><td>: {{ $student->jenis_kelamin }}</td></tr>
                    <tr>
                        <td>Tempat, Tgl Lahir</td>
                        <td>: {{ $student->tempat_lahir ?: '—' }}, {{ $tglLahirFormatted }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Detail Information -->
        <div class="p-6 sm:p-8 space-y-6">

            <!-- Grid: Section 1 & 2 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 print-data-grid">
                <!-- 1. Data Akademik -->
                <div class="space-y-3 page-break-avoid">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700 pb-2 border-b border-gray-100 print-section-title">1. Data Pokok &amp; Akademik</h4>
                    <table class="w-full text-xs print-data-table">
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500 w-[45%]">Nomor Induk Santri (NIS)</td>
                            <td class="py-1.5 font-mono font-bold text-gray-800">{{ $student->nis }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500">Nomor Stambuk</td>
                            <td class="py-1.5 font-mono text-gray-800">{{ $student->nisn ?: '—' }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500">Jenjang &amp; Kategori</td>
                            <td class="py-1.5 font-semibold text-gray-800 flex items-center gap-1.5">
                                <span>{{ $student->jenjang }}</span>
                                <span class="px-2 py-0.5 rounded text-[10.5px] font-extrabold shadow-2xs {{ $student->hunian === 'Mukim' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $student->kategori_label }}
                                </span>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500">Kelas Aktif</td>
                            <td class="py-1.5 font-semibold text-emerald-700">Kelas {{ $student->kelas }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500">Wali Kelas</td>
                            <td class="py-1.5 font-semibold text-gray-800">{{ $student->classroom?->wali_kelas ?: '—' }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500">Kamar Asrama</td>
                            <td class="py-1.5 text-gray-800">{{ $student->kamar_asrama ?: '— (Santri Laju)' }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500">Tahun Masuk / Angkatan</td>
                            <td class="py-1.5 text-gray-800 font-semibold">{{ $student->tahun_masuk }}</td>
                        </tr>
                        <tr>
                            <td class="py-1.5 text-gray-500">Jenis Kelamin</td>
                            <td class="py-1.5 text-gray-800">{{ $student->jenis_kelamin }}</td>
                        </tr>
                    </table>
                </div>

                <!-- 2. Data Pribadi Santri -->
                <div class="space-y-3 page-break-avoid">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700 pb-2 border-b border-gray-100 print-section-title">2. Identitas Diri Lengkap</h4>
                    <table class="w-full text-xs print-data-table">
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500 w-[45%]">NIK Santri</td>
                            <td class="py-1.5 font-mono font-bold text-gray-800">{{ $student->nik ?: '—' }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500">Nomor KK</td>
                            <td class="py-1.5 font-mono text-gray-800">{{ $student->nomor_kk ?: '—' }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500">Tempat, Tanggal Lahir</td>
                            <td class="py-1.5 text-gray-800 font-medium">
                                {{ $student->tempat_lahir ?: '—' }}, {{ $tglLahirFormatted }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500">Anak Ke- / Jml Saudara</td>
                            <td class="py-1.5 text-gray-800">{{ $student->anak_ke ?: '—' }} dari {{ $student->jumlah_saudara ?: '—' }} bersaudara</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500">Golongan Darah</td>
                            <td class="py-1.5 font-bold text-gray-800">{{ $student->golongan_darah ?: '—' }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500">Hobi / Minat Bakat</td>
                            <td class="py-1.5 text-gray-800">{{ $student->hobi ?: '—' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1.5 text-gray-500">Riwayat Sakit / Alergi</td>
                            <td class="py-1.5 {{ $student->riwayat_penyakit ? 'text-rose-600 font-medium' : 'text-gray-400' }}">
                                {{ $student->riwayat_penyakit ?: 'Tidak ada catatan' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Grid: Section 3 & 4 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-gray-100 print-data-grid">
                <div class="space-y-3 page-break-avoid">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700 pb-2 border-b border-gray-100 print-section-title">3. Riwayat Asal Sekolah</h4>
                    <table class="w-full text-xs print-data-table">
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500 w-[45%]">Nama Asal Sekolah</td>
                            <td class="py-1.5 font-semibold text-gray-800">{{ $student->nama_sekolah ?: '—' }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500">Tahun Lulus</td>
                            <td class="py-1.5 text-gray-800">{{ $student->tahun_lulus ?: '—' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1.5 text-gray-500">Alamat Asal Sekolah</td>
                            <td class="py-1.5 text-gray-800">{{ $student->alamat_sekolah ?: '—' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="space-y-3 page-break-avoid">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700 pb-2 border-b border-gray-100 print-section-title">4. Alamat &amp; Kontak Santri</h4>
                    <table class="w-full text-xs print-data-table">
                        <tr class="border-b border-gray-50">
                            <td class="py-1.5 text-gray-500 w-[45%]">WhatsApp / HP</td>
                            <td class="py-1.5 font-mono text-emerald-700 font-bold">
                                @if($student->no_whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->no_whatsapp) }}" target="_blank" class="hover:underline no-print-link">
                                        {{ $student->no_whatsapp }}
                                    </a>
                                    <span class="print-only">{{ $student->no_whatsapp }}</span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1.5 text-gray-500">Alamat Domisili</td>
                            <td class="py-1.5 text-gray-800 leading-relaxed">{{ $student->alamat_lengkap ?: ($student->alamat ?: '—') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- 5. Data Orang Tua & Wali -->
            <div class="pt-4 border-t border-gray-100 page-break-avoid">
                <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700 pb-3 print-section-title">5. Data Orang Tua &amp; Wali</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 print-parent-grid">
                    <!-- Ayah -->
                    <div class="rounded-xl border border-gray-200 bg-gray-50/75 p-4 text-xs space-y-1.5 print-parent-card">
                        <h5 class="font-bold uppercase text-gray-900 block pb-1 border-b border-gray-200">Ayah Kandung</h5>
                        <p class="text-gray-800 font-semibold">{{ $student->ayah_nama ?: '—' }}</p>
                        <p><span class="label text-gray-500">NIK:</span> <span class="value font-mono text-gray-700">{{ $student->ayah_nik ?: '—' }}</span></p>
                        <p><span class="label text-gray-500">Pekerjaan:</span> <span class="value text-gray-700">{{ $student->ayah_pekerjaan ?: '—' }}</span></p>
                        <p><span class="label text-gray-500">Pendidikan:</span> <span class="value text-gray-700">{{ $student->ayah_pendidikan ?: '—' }}</span></p>
                        <p><span class="label text-gray-500">Penghasilan:</span> <span class="value text-gray-700">{{ $student->ayah_penghasilan ?: '—' }}</span></p>
                        <p><span class="label text-gray-500">No HP:</span> <span class="value font-mono text-emerald-700 font-bold">{{ $student->ayah_telepon ?: '—' }}</span></p>
                    </div>

                    <!-- Ibu -->
                    <div class="rounded-xl border border-gray-200 bg-gray-50/75 p-4 text-xs space-y-1.5 print-parent-card">
                        <h5 class="font-bold uppercase text-gray-900 block pb-1 border-b border-gray-200">Ibu Kandung</h5>
                        <p class="text-gray-800 font-semibold">{{ $student->ibu_nama ?: '—' }}</p>
                        <p><span class="label text-gray-500">NIK:</span> <span class="value font-mono text-gray-700">{{ $student->ibu_nik ?: '—' }}</span></p>
                        <p><span class="label text-gray-500">Pekerjaan:</span> <span class="value text-gray-700">{{ $student->ibu_pekerjaan ?: '—' }}</span></p>
                        <p><span class="label text-gray-500">Pendidikan:</span> <span class="value text-gray-700">{{ $student->ibu_pendidikan ?: '—' }}</span></p>
                        <p><span class="label text-gray-500">Penghasilan:</span> <span class="value text-gray-700">{{ $student->ibu_penghasilan ?: '—' }}</span></p>
                        <p><span class="label text-gray-500">No HP:</span> <span class="value font-mono text-emerald-700 font-bold">{{ $student->ibu_telepon ?: '—' }}</span></p>
                    </div>

                    <!-- Wali -->
                    <div class="rounded-xl border border-gray-200 bg-gray-50/75 p-4 text-xs space-y-1.5 print-parent-card">
                        <h5 class="font-bold uppercase text-gray-900 block pb-1 border-b border-gray-200">Wali Santri</h5>
                        <p class="text-gray-800 font-semibold">{{ $student->nama_wali ?: '—' }}</p>
                        <p><span class="label text-gray-500">Hubungan:</span> <span class="value text-gray-700">{{ $student->wali_hubungan ?: '—' }}</span></p>
                        <p><span class="label text-gray-500">NIK Wali:</span> <span class="value font-mono text-gray-700">{{ $student->wali_nik ?: '—' }}</span></p>
                        <p><span class="label text-gray-500">Pekerjaan:</span> <span class="value text-gray-700">{{ $student->wali_pekerjaan ?: '—' }}</span></p>
                        <p><span class="label text-gray-500">Penghasilan:</span> <span class="value text-gray-700">{{ $student->wali_penghasilan ?: '—' }}</span></p>
                        <p><span class="label text-gray-500">No HP:</span> <span class="value font-mono text-emerald-700 font-bold">{{ $student->wali_telepon ?: ($student->no_whatsapp ?: '—') }}</span></p>
                    </div>
                </div>
            </div>

            <!-- 6. Dokumen & Berkas Santri (KK, KTP, Akte) -->
            <div class="pt-4 border-t border-gray-100 page-break-avoid">
                <div class="flex items-center justify-between pb-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700 print-section-title" style="margin:0;padding:0;border:none;">
                        6. Dokumen &amp; Berkas Santri (Arsip Digital)
                    </h4>
                    <a href="{{ route('admin.siswa.edit', $student->id) }}" class="text-xs font-semibold text-emerald-600 hover:underline no-print flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Upload / Ganti Berkas &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Kartu Keluarga -->
                    <div class="rounded-xl border border-gray-200 bg-white p-3.5 flex flex-col justify-between space-y-2.5">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Kartu Keluarga (KK)
                                </span>
                                @if($student->berkas_kk)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-500">
                                        Belum Ada
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1">No. KK: <strong class="font-mono text-gray-700">{{ $student->nomor_kk ?: '—' }}</strong></p>
                        </div>
                        <div>
                            @if($student->berkas_kk)
                                <a href="{{ asset($student->berkas_kk) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold hover:bg-indigo-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Buka File KK &rarr;
                                </a>
                            @else
                                <a href="{{ route('admin.siswa.edit', $student->id) }}" class="w-full inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg border border-dashed border-gray-300 text-gray-500 text-xs font-medium hover:border-emerald-500 hover:text-emerald-700 no-print transition">
                                    + Upload KK
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- KTP Orang Tua / Wali -->
                    <div class="rounded-xl border border-gray-200 bg-white p-3.5 flex flex-col justify-between space-y-2.5">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                    KTP Orang Tua / Wali
                                </span>
                                @if($student->berkas_ktp_ortu)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-500">
                                        Belum Ada
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1">NIK: <strong class="font-mono text-gray-700">{{ $student->ayah_nik ?: ($student->ibu_nik ?: ($student->wali_nik ?: '—')) }}</strong></p>
                        </div>
                        <div>
                            @if($student->berkas_ktp_ortu)
                                <a href="{{ asset($student->berkas_ktp_ortu) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold hover:bg-emerald-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Buka File KTP &rarr;
                                </a>
                            @else
                                <a href="{{ route('admin.siswa.edit', $student->id) }}" class="w-full inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg border border-dashed border-gray-300 text-gray-500 text-xs font-medium hover:border-emerald-500 hover:text-emerald-700 no-print transition">
                                    + Upload KTP
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Akta Kelahiran -->
                    <div class="rounded-xl border border-gray-200 bg-white p-3.5 flex flex-col justify-between space-y-2.5">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    Akta Kelahiran
                                </span>
                                @if($student->berkas_akta)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-500">
                                        Belum Ada
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1">NIK Santri: <strong class="font-mono text-gray-700">{{ $student->nik ?: '—' }}</strong></p>
                        </div>
                        <div>
                            @if($student->berkas_akta)
                                <a href="{{ asset($student->berkas_akta) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 text-xs font-bold hover:bg-amber-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Buka File Akta &rarr;
                                </a>
                            @else
                                <a href="{{ route('admin.siswa.edit', $student->id) }}" class="w-full inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg border border-dashed border-gray-300 text-gray-500 text-xs font-medium hover:border-emerald-500 hover:text-emerald-700 no-print transition">
                                    + Upload Akta
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- 7. Tagihan & Tunggakan Aktif Santri -->
            <div class="pt-4 border-t border-gray-100 page-break-avoid no-print">
                <div class="flex items-center justify-between pb-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-rose-700 print-section-title" style="margin:0;padding:0;border:none;">7. Tagihan &amp; Tunggakan Santri</h4>
                    <a href="{{ route('admin.pembayaran.tagihan.index', ['q' => $student->nis]) }}" class="text-xs font-semibold text-rose-600 hover:underline">
                        Kelola di Buku Tagihan &rarr;
                    </a>
                </div>

                @php
                    $activeBills = $student->bills->where('status', '!=', 'Lunas')->where('penangguhan_wisuda', false);
                    $totalSisa = $activeBills->sum('sisa_tagihan');
                    $totalBills = $student->bills->count();
                @endphp

                @if($totalBills > 0)
                    <!-- Summary Pills -->
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            {{ $totalBills }} Total Tagihan
                        </span>
                        @if($activeBills->count() > 0)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                            {{ $activeBills->count() }} Belum Lunas
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-extrabold bg-rose-600 text-white">
                            Sisa: Rp {{ number_format($totalSisa, 0, ',', '.') }}
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                            ✓ Semua Tagihan Lunas
                        </span>
                        @endif
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-200">
                                <tr>
                                    <th class="py-2.5 px-4">Judul Tagihan</th>
                                    <th class="py-2.5 px-4">Pos / Kategori</th>
                                    <th class="py-2.5 px-4">Nominal</th>
                                    <th class="py-2.5 px-4">Sisa</th>
                                    <th class="py-2.5 px-4">Jatuh Tempo</th>
                                    <th class="py-2.5 px-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($student->bills->take(10) as $bill)
                                <tr class="{{ $bill->status !== 'Lunas' && !$bill->penangguhan_wisuda ? 'bg-rose-50/30' : '' }}">
                                    <td class="py-2.5 px-4 font-semibold text-gray-900">
                                        {{ Str::limit($bill->judul_tagihan, 45) }}
                                    </td>
                                    <td class="py-2.5 px-4">
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-700">
                                            {{ $bill->pos_biaya }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4 font-mono text-gray-700">Rp {{ number_format($bill->nominal_tagihan, 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-4 font-mono font-bold {{ $bill->sisa_tagihan > 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                                        Rp {{ number_format($bill->sisa_tagihan, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-4 text-gray-500">
                                        {{ $bill->jatuh_tempo ? $bill->jatuh_tempo->format('d/m/Y') : '—' }}
                                    </td>
                                    <td class="py-2.5 px-4">
                                        @if($bill->penangguhan_wisuda)
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">Ditangguhkan</span>
                                        @elseif($bill->status === 'Lunas')
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Lunas</span>
                                        @elseif($bill->status === 'Cicilan')
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Cicilan</span>
                                        @else
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">Belum Bayar</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($student->bills->count() > 10)
                    <p class="text-[11px] text-gray-400 mt-2 text-center">
                        Menampilkan 10 dari {{ $student->bills->count() }} tagihan. 
                        <a href="{{ route('admin.pembayaran.tagihan.index', ['q' => $student->nis]) }}" class="text-emerald-600 font-semibold hover:underline">Lihat semua &rarr;</a>
                    </p>
                    @endif
                @else
                <div class="rounded-xl border border-dashed border-gray-200 p-6 text-center text-gray-400 text-xs">
                    Belum ada tagihan yang diterbitkan untuk santri ini.
                    <a href="{{ route('admin.pembayaran.tagihan.index') }}" class="text-emerald-600 font-semibold hover:underline ml-1">Terbitkan Tagihan &rarr;</a>
                </div>
                @endif
            </div>

            <!-- 8. Riwayat Pembayaran Santri -->

            <div class="pt-4 border-t border-gray-100 page-break-avoid">
                <div class="flex items-center justify-between pb-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700 print-section-title" style="margin:0;padding:0;border:none;">8. Riwayat Pembayaran SPP &amp; Iuran</h4>
                    <a href="{{ route('admin.pembayaran.index', ['tab' => 'santri', 'q_santri' => $student->nis]) }}" class="text-xs font-semibold text-emerald-600 hover:underline no-print">
                        Lihat di Manajemen Pembayaran &rarr;
                    </a>
                </div>

                @if($student->payments && $student->payments->count() > 0)
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-left text-xs print-payment-table">
                        <thead class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-200">
                            <tr>
                                <th class="py-2.5 px-4">No. Kwitansi</th>
                                <th class="py-2.5 px-4">Tanggal</th>
                                <th class="py-2.5 px-4">Pos Pembayaran</th>
                                <th class="py-2.5 px-4">Periode</th>
                                <th class="py-2.5 px-4">Nominal</th>
                                <th class="py-2.5 px-4">Status</th>
                                <th class="py-2.5 px-4 text-right no-print">Kwitansi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($student->payments as $p)
                            <tr>
                                <td class="py-2.5 px-4 font-mono font-bold text-gray-900">{{ $p->no_transaksi }}</td>
                                <td class="py-2.5 px-4 text-gray-600">{{ optional($p->tanggal_bayar)->format('d/m/Y') }}</td>
                                <td class="py-2.5 px-4 font-semibold text-gray-800">{{ $p->jenis_pembayaran }}</td>
                                <td class="py-2.5 px-4 text-gray-600">{{ $p->bulan ?: '—' }} {{ $p->tahun }}</td>
                                <td class="py-2.5 px-4 font-bold text-emerald-700">{{ $p->formatted_nominal }}</td>
                                <td class="py-2.5 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold {{ $p->status === 'Lunas' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4 text-right no-print">
                                    <a href="{{ route('admin.pembayaran.kwitansi', $p->id) }}" target="_blank" class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-800 font-semibold text-xs">
                                        Cetak
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="rounded-xl border border-dashed border-gray-200 p-6 text-center text-gray-400 text-xs">
                    Belum ada riwayat pembayaran yang tercatat untuk santri ini.
                </div>
                @endif
            </div>

            <!-- 9. Riwayat Mutasi Santri -->
            <div class="pt-4 border-t border-gray-100 page-break-avoid">
                <div class="flex items-center justify-between pb-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-orange-700 print-section-title" style="margin:0;padding:0;border:none;">9. Riwayat Mutasi Santri</h4>
                    <a href="{{ route('admin.siswa.mutasi.index', ['q' => $student->nis]) }}" class="text-xs font-semibold text-orange-600 hover:underline no-print">
                        Buka di Menu Mutasi &rarr;
                    </a>
                </div>

                @if($student->mutations && $student->mutations->count() > 0)
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-200">
                            <tr>
                                <th class="py-2.5 px-4">Tanggal</th>
                                <th class="py-2.5 px-4">Jenis Mutasi</th>
                                <th class="py-2.5 px-4">Kelas &amp; Asrama Asal</th>
                                <th class="py-2.5 px-4">Alasan / Tujuan</th>
                                <th class="py-2.5 px-4">Sekolah Asal / Tujuan</th>
                                <th class="py-2.5 px-4">Dicatat Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($student->mutations as $m)
                            <tr>
                                <td class="py-2.5 px-4 font-semibold text-gray-700 whitespace-nowrap">{{ optional($m->tanggal_mutasi)->format('d/m/Y') }}</td>
                                <td class="py-2.5 px-4">
                                    @if($m->jenis_mutasi === 'Keluar')
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Mutasi Keluar</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Mutasi Masuk</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 text-gray-700">
                                    <span>Kelas {{ $m->kelas_dari ?: '-' }}</span>
                                    @if($m->kamar_dari)
                                        <span class="block text-[11px] text-indigo-700">🛏 {{ $m->kamar_dari }}</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 font-medium text-gray-900">
                                    @if($m->jenis_mutasi === 'Keluar')
                                        {{ $m->alasan ?: '-' }}
                                    @else
                                        Ke Kelas {{ $m->kelas_ke ?: '-' }}
                                        @if($m->kamar_ke)
                                            <span class="block text-[11px] text-indigo-700">🛏 {{ $m->kamar_ke }}</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 text-gray-700">{{ $m->sekolah_asal_tujuan ?: '-' }}</td>
                                <td class="py-2.5 px-4 text-gray-500 font-medium text-[11px]">{{ $m->user->name ?? $m->dicatat_oleh ?? 'Admin' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="rounded-xl border border-dashed border-gray-200 p-4 text-center text-gray-400 text-xs">
                    Belum ada riwayat mutasi keluar/masuk untuk santri ini.
                    <a href="{{ route('admin.siswa.mutasi.index') }}" class="text-orange-600 font-semibold hover:underline ml-1">Catat Mutasi Santri &rarr;</a>
                </div>
                @endif
            </div>

            <!-- 9. Akun Login & Kredensial -->
            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs bg-gray-50 p-4 rounded-xl print-login-section">
                <div>
                    <span class="text-gray-500">Username Login Santri:</span>
                    <strong class="font-mono text-gray-800 ml-1">{{ $student->username ?: $student->nis }}</strong>
                    <span class="text-gray-400 mx-2">&bull;</span>
                    <span class="text-gray-500">Password Default:</span>
                    <strong class="font-mono text-emerald-700 ml-1">{{ $student->getFormattedBirthdatePassword() }} (Tgl Lahir)</strong>
                </div>
                <div class="flex items-center gap-2 no-print">
                    <form action="{{ route('admin.siswa.resetPassword', $student->id) }}" method="POST" onsubmit="return confirm('Reset password santri ke tanggal lahir?');" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-emerald-700 hover:underline">
                            Reset ke Tanggal Lahir
                        </button>
                    </form>
                </div>
            </div>

            <!-- PRINT-ONLY: Signature Area -->
            <div class="print-only print-signature">
                <div class="print-signature-box">
                    <p>Temanggung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p style="margin-top:4pt;">Kepala Pondok Pesantren</p>
                    <div class="sig-line">
                        (..................................)
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

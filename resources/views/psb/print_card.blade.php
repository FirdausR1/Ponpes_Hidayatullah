<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Biodata Santri (CV) — {{ $reg->nama_lengkap }} ({{ $reg->no_registrasi }})</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts: Plus Jakarta Sans & EB Garamond -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=EB+Garamond:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .font-serif {
            font-family: 'EB Garamond', Georgia, serif;
        }

        /* Printable Sheet Simulation */
        .sheet {
            width: 210mm;
            min-height: 297mm;
            padding: 14mm 16mm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            position: relative;
        }

        .kop-line {
            border-top: 3px solid #0f172a;
            border-bottom: 1px solid #0f172a;
            height: 5px;
            margin-top: 10px;
            margin-bottom: 14px;
        }

        /* Print Media Styles */
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }

        @media print {
            body {
                background: transparent !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .sheet {
                width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                page-break-after: always;
                break-after: page;
            }
            .sheet:last-child {
                page-break-after: auto;
                break-after: auto;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="min-h-screen pb-16">

    <!-- Screen-Only Top Action Bar (Tampilan Layar / Download & Print Control) -->
    <header class="no-print sticky top-0 z-50 bg-slate-900 text-white border-b border-slate-800 shadow-lg px-4 py-3">
        <div class="max-w-5xl mx-auto flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('psb.checkStatus', ['identifier' => $reg->no_registrasi]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition text-xs font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Status</span>
                </a>
                <div class="hidden sm:block">
                    <h1 class="text-xs sm:text-sm font-bold flex items-center gap-2">
                        <span>Kartu Santri Resmi: {{ $reg->nama_lengkap }}</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-mono font-bold">
                            {{ $reg->no_registrasi }}
                        </span>
                    </h1>
                </div>
            </div>

            <!-- Switch Mode & Action Buttons -->
            <div class="flex items-center gap-2.5">
                <div class="bg-slate-800 p-0.5 rounded-lg border border-slate-700 flex text-xs font-medium">
                    <a href="{{ route('psb.printCard', ['id' => $reg->id, 'mode' => 'cv']) }}" class="px-2.5 py-1 rounded transition text-[11px] {{ ($mode ?? 'cv') == 'cv' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:text-white' }}">
                        Kartu CV (1 Lembar)
                    </a>
                    <a href="{{ route('psb.printCard', ['id' => $reg->id, 'mode' => 'all']) }}" class="px-2.5 py-1 rounded transition text-[11px] {{ ($mode ?? 'cv') == 'all' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:text-white' }}">
                        CV + Lampiran Berkas
                    </a>
                </div>

                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    <span>Cetak / Unduh PDF</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Printable Container -->
    <div class="max-w-5xl mx-auto px-2">

        <!-- ========================================== -->
        <!-- SHEET 1: KARTU BIODATA CALON SANTRI (CV) -->
        <!-- ========================================== -->
        <div class="sheet">
            
            <!-- KOP SURAT RESMI PESANTREN -->
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 shrink-0 flex items-center justify-center">
                    <img src="/logo.png" alt="Logo Pesantren" class="max-w-full max-h-full object-contain">
                </div>
                <div class="flex-1 text-center">
                    <h4 class="text-[11px] font-bold tracking-wider text-slate-700 uppercase">Yayasan Pondok Pesantren Hidayatullah Temanggung</h4>
                    <h2 class="text-xl font-serif font-bold text-[#0d3b1e] tracking-wide uppercase">Pondok Pesantren Hidayatullah Tuksongo</h2>
                    <p class="text-[11px] font-semibold text-slate-800 mt-0.5">
                        Madrasah Tsanawiyah (MTs) &bull; Madrasah Aliyah (MA) Tahfidz & Sains Al-Qur'an
                    </p>
                    <p class="text-[9.5px] text-slate-500 mt-0.5 leading-tight">
                        Jl. Magelang - Semarang KM 14, Tuksongo, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah 56272<br>
                        Hotline PSB / WhatsApp: 0852-9042-9617 &bull; Website: ponpeshidayatullahtuksongo.com
                    </p>
                </div>
                <div class="w-20 shrink-0 text-right">
                    <!-- Barcode / Reg Stamp -->
                    <div class="border border-slate-300 rounded p-1 text-center bg-slate-50">
                        <span class="text-[8px] font-mono text-slate-400 block">FORMULIR</span>
                        <span class="text-[10px] font-mono font-bold text-slate-800">F-PSB/25</span>
                    </div>
                </div>
            </div>

            <!-- Garis Kop Resmi -->
            <div class="kop-line"></div>

            <!-- Title of the Document -->
            <div class="text-center mb-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 bg-slate-100 py-1 px-4 rounded-md inline-block border border-slate-200">
                    Biodata Calon Santri Baru (CV Santri) — TA 2025/2026
                </h3>
                <div class="flex items-center justify-center gap-4 text-[10.5px] text-slate-600 mt-1.5">
                    <span>No. Reg: <strong class="font-mono text-emerald-800">{{ $reg->no_registrasi }}</strong></span>
                    <span>&bull;</span>
                    <span>Jalur: <strong>{{ $reg->jalur ?: 'Reguler' }}</strong></span>
                    <span>&bull;</span>
                    <span>Jenjang: <strong class="text-blue-800">{{ $reg->jenjang }}</strong></span>
                    <span>&bull;</span>
                    <span>Status: <strong class="{{ $reg->status == 'Diterima' ? 'text-emerald-700' : ($reg->status == 'Ditolak' ? 'text-rose-700' : 'text-amber-700') }}">{{ $reg->status ?: 'Menunggu Verifikasi' }}</strong></span>
                </div>
            </div>

            <!-- Main CV Body (Side Photo + Structured Data) -->
            <div class="flex gap-4 items-start mb-3">
                
                <!-- Left Column: Pas Foto 3x4 Box & Label -->
                <div class="w-32 shrink-0 space-y-2 text-center">
                    <div class="w-32 h-44 rounded-lg border-2 {{ ($reg->foto_status ?? 'Sesuai') === 'Perlu Perbaikan' ? 'border-amber-500' : 'border-red-600' }} p-1 bg-red-600 shadow-sm relative overflow-hidden flex items-center justify-center">
                        @if($reg->pas_foto)
                            <img src="{{ $reg->pas_foto }}" alt="{{ $reg->nama_lengkap }}" class="w-full h-full object-cover rounded">
                        @else
                            <div class="w-full h-full bg-slate-100 flex flex-col items-center justify-center text-slate-400 p-2">
                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="text-[9px] font-bold">FOTO 3X4</span>
                                <span class="text-[8px]">Background Merah</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-[10px] text-center pt-0.5">
                        <span class="font-bold text-slate-700 tracking-wider uppercase">PAS FOTO 3X4</span>
                    </div>
                </div>

                <!-- Right Column: Primary Identity Table -->
                <div class="flex-1">
                    <table class="w-full text-left text-[11px] border-collapse">
                        <tbody>
                            <tr class="border-b border-slate-200">
                                <td class="py-1 px-2 font-bold text-slate-500 w-36 bg-slate-50/70">Nama Lengkap</td>
                                <td class="py-1 px-2 font-bold text-slate-900 text-[12px] uppercase">{{ $reg->nama_lengkap }}</td>
                            </tr>
                            <tr class="border-b border-slate-200">
                                <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">Jenis Kelamin</td>
                                <td class="py-1 px-2 text-slate-800">{{ $reg->jenis_kelamin }}</td>
                            </tr>
                            <tr class="border-b border-slate-200">
                                <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">NISN / NIK</td>
                                <td class="py-1 px-2 font-mono text-slate-800">{{ $reg->nisn ?: '-' }} / {{ $reg->nik ?: '-' }}</td>
                            </tr>
                            <tr class="border-b border-slate-200">
                                <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">No. Kartu Keluarga (KK)</td>
                                <td class="py-1 px-2 font-mono text-slate-800">{{ $reg->nomor_kk ?: '-' }}</td>
                            </tr>
                            <tr class="border-b border-slate-200">
                                <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">Tempat, Tanggal Lahir</td>
                                <td class="py-1 px-2 text-slate-800">
                                    {{ $reg->tempat_lahir ?: '-' }}, {{ $reg->tanggal_lahir ? $reg->tanggal_lahir->translatedFormat('d F Y') : '-' }}
                                </td>
                            </tr>
                            <tr class="border-b border-slate-200">
                                <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">Urutan Anak</td>
                                <td class="py-1 px-2 text-slate-800">
                                    Anak ke-<strong>{{ $reg->anak_ke ?: '-' }}</strong> dari <strong>{{ $reg->jumlah_saudara ?: '-' }}</strong> bersaudara
                                </td>
                            </tr>
                            <tr class="border-b border-slate-200">
                                <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">Hobi & Minat</td>
                                <td class="py-1 px-2 text-slate-800">{{ $reg->hobi ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70 align-top">Alamat Lengkap</td>
                                <td class="py-1 px-2 text-slate-800 leading-snug">{{ $reg->alamat_lengkap ?: $reg->alamat ?: '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section: Pendidikan Asal -->
            <div class="mb-3">
                <div class="bg-emerald-900 text-white px-3 py-0.5 text-[10.5px] font-bold uppercase tracking-wider rounded-t">
                    I. Data Pendidikan Asal (Sekolah / Madrasah)
                </div>
                <table class="w-full text-left text-[11px] border border-t-0 border-slate-200">
                    <tbody>
                        <tr class="border-b border-slate-200">
                            <td class="py-1 px-2 font-semibold text-slate-500 w-36 bg-slate-50/70">Nama Sekolah Asal</td>
                            <td class="py-1 px-2 font-semibold text-slate-800">{{ $reg->nama_sekolah ?: $reg->asal_sekolah ?: '-' }}</td>
                            <td class="py-1 px-2 font-semibold text-slate-500 w-28 bg-slate-50/70">Tahun Lulus</td>
                            <td class="py-1 px-2 font-mono text-slate-800 w-24">{{ $reg->tahun_lulus ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">Alamat Sekolah</td>
                            <td colspan="3" class="py-1 px-2 text-slate-700">{{ $reg->alamat_sekolah ?: '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Section: Data Orang Tua / Wali -->
            <div class="mb-3">
                <div class="bg-emerald-900 text-white px-3 py-0.5 text-[10.5px] font-bold uppercase tracking-wider rounded-t">
                    II. Data Orang Tua / Wali Santri
                </div>
                <table class="w-full text-left text-[10.5px] border border-t-0 border-slate-200">
                    <thead class="bg-slate-100 text-slate-600 font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-1 px-2 w-32">Keterangan</th>
                            <th class="py-1 px-2 w-1/2">Data Ayah Kandung</th>
                            <th class="py-1 px-2 w-1/2">Data Ibu Kandung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr>
                            <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">Nama Lengkap</td>
                            <td class="py-1 px-2 font-bold text-slate-800">{{ $reg->ayah_nama ?: '-' }}</td>
                            <td class="py-1 px-2 font-bold text-slate-800">{{ $reg->ibu_nama ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">NIK</td>
                            <td class="py-1 px-2 font-mono text-slate-700">{{ $reg->ayah_nik ?: '-' }}</td>
                            <td class="py-1 px-2 font-mono text-slate-700">{{ $reg->ibu_nik ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">Pendidikan Terakhir</td>
                            <td class="py-1 px-2 text-slate-700">{{ $reg->ayah_pendidikan ?: '-' }}</td>
                            <td class="py-1 px-2 text-slate-700">{{ $reg->ibu_pendidikan ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">Pekerjaan</td>
                            <td class="py-1 px-2 text-slate-700">{{ $reg->ayah_pekerjaan ?: '-' }}</td>
                            <td class="py-1 px-2 text-slate-700">{{ $reg->ibu_pekerjaan ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">Penghasilan / Bln</td>
                            <td class="py-1 px-2 text-slate-700">{{ $reg->ayah_penghasilan ?: '-' }}</td>
                            <td class="py-1 px-2 text-slate-700">{{ $reg->ibu_penghasilan ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">No. WhatsApp / HP</td>
                            <td class="py-1 px-2 font-mono text-emerald-800 font-semibold">{{ $reg->ayah_telepon ?: $reg->no_whatsapp ?: '-' }}</td>
                            <td class="py-1 px-2 font-mono text-emerald-800 font-semibold">{{ $reg->ibu_telepon ?: '-' }}</td>
                        </tr>
                        @if($reg->wali_nama)
                        <tr class="bg-blue-50/40">
                            <td class="py-1 px-2 font-semibold text-blue-900 bg-blue-100/50">Wali (Jika Berbeda)</td>
                            <td colspan="2" class="py-1 px-2 text-blue-950">
                                <strong>{{ $reg->wali_nama }}</strong> (Hubungan: {{ $reg->wali_hubungan ?: 'Wali' }}) &bull; Pekerjaan: {{ $reg->wali_pekerjaan ?: '-' }} &bull; Telp: {{ $reg->wali_telepon ?: '-' }}
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Section: Bantuan Sosial & Berkas Status -->
            <div class="mb-4">
                <div class="bg-emerald-900 text-white px-3 py-0.5 text-[10.5px] font-bold uppercase tracking-wider rounded-t">
                    III. Kelayakan Sosial & Berkas Pendukung
                </div>
                <table class="w-full text-left text-[11px] border border-t-0 border-slate-200">
                    <tbody>
                        <tr class="border-b border-slate-200">
                            <td class="py-1 px-2 font-semibold text-slate-500 w-36 bg-slate-50/70">Program Bansos</td>
                            <td class="py-1 px-2 text-slate-800">
                                {{ $reg->bantuan_sosial ?: 'Tidak Ada Program Bantuan Sosial' }}
                            </td>
                            <td class="py-1 px-2 font-semibold text-slate-500 w-36 bg-slate-50/70">Bukti Biaya Pendaftaran</td>
                            <td class="py-1 px-2 font-semibold {{ $reg->bukti_transfer ? 'text-emerald-700' : 'text-slate-400' }}">
                                {{ $reg->bukti_transfer ? '✓ Terunggah (Rp 200.000)' : '✕ Belum Terunggah' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">Kesesuaian Pas Foto</td>
                            <td class="py-1 px-2 text-slate-800">
                                <strong class="{{ ($reg->foto_status ?? 'Sesuai') === 'Sesuai' ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $reg->foto_status ?: 'Sesuai Ketentuan' }}
                                </strong>
                            </td>
                            <td class="py-1 px-2 font-semibold text-slate-500 bg-slate-50/70">Tanggal Registrasi</td>
                            <td class="py-1 px-2 font-mono text-slate-800">
                                {{ $reg->created_at ? $reg->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tanda Tangan Pengesahan (Signature Box) -->
            <div class="mt-8 pt-4 border-t border-slate-200">
                <div class="grid grid-cols-2 gap-8 text-center text-xs items-end">
                    <!-- Pihak Santri / Orang Tua -->
                    <div class="space-y-12">
                        <p class="text-slate-600">
                            Calon Santri / Orang Tua / Wali,<br>
                            <span class="text-[10px] text-slate-400">Menyatakan data di atas adalah benar dan sah</span>
                        </p>
                        <div>
                            <p class="font-bold text-slate-900 border-b border-slate-400 inline-block px-8 pb-1">
                                {{ $reg->ayah_nama ?: $reg->nama_wali ?: $reg->nama_lengkap }}
                            </p>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Nama Terang Wali Santri</span>
                        </div>
                    </div>

                    <!-- Panitia PSB & Pengurus Pesantren (TTD Digital & Stempel Resmi) -->
                    <div class="space-y-1 relative text-center">
                        <p class="text-slate-700 text-[11px] leading-tight">
                            {{ \App\Models\Setting::get('ttd_digital_kota', 'Pringsurat') }}, {{ $reg->created_at ? $reg->created_at->translatedFormat('d F Y') : date('d F Y') }}<br>
                            <strong class="text-slate-900 uppercase text-[11px]">{{ \App\Models\Setting::get('ttd_digital_jabatan', 'Pengurus & Panitia Seleksi PSB') }}</strong><br>
                            <span class="text-[9.5px] text-slate-500">Pondok Pesantren Hidayatullah Tuksongo</span>
                        </p>

                        <!-- Wadah Tanda Tangan Digital & Stempel Realistis -->
                        <div class="relative h-24 my-1 flex items-center justify-center">
                            @php
                                $stempelImg = \App\Models\Setting::get('ttd_digital_stempel_image', '/uploads/settings/default_stempel_pesantren.png');
                                $ttdImg = \App\Models\Setting::get('ttd_digital_pengurus_image', '/uploads/settings/default_ttd_pengurus.png');
                                $showStempel = \App\Models\Setting::get('ttd_digital_show_stempel', '1') == '1';
                            @endphp

                            <!-- Stempel Resmi di Lapisan Bawah (Agak Kiri Overlap) -->
                            @if($showStempel && $stempelImg)
                                <img src="{{ $stempelImg }}" alt="Stempel Resmi" class="absolute w-24 h-24 object-contain opacity-80 left-4 pointer-events-none transform -rotate-6">
                            @endif

                            <!-- Tanda Tangan Asli Pengurus -->
                            @if($ttdImg)
                                <img src="{{ $ttdImg }}" alt="Tanda Tangan Pengurus" class="relative z-10 h-20 max-w-[170px] object-contain mx-auto">
                            @else
                                <div class="mx-auto py-1 px-3 border border-emerald-600 rounded-lg bg-emerald-50/70 inline-flex items-center gap-2">
                                    <span class="text-[9px] font-mono font-bold text-emerald-800 uppercase">TERVERIFIKASI ONLINE</span>
                                </div>
                            @endif
                        </div>

                        <div>
                            <p class="font-bold text-slate-900 border-b border-slate-400 inline-block px-4 pb-0.5 text-xs">
                                {{ \App\Models\Setting::get('ttd_digital_nama', 'Ust. Ahmad Fauzi, S.Pd.I') }}
                            </p>
                            @if(\App\Models\Setting::get('ttd_digital_nip'))
                                <span class="text-[9.5px] font-mono text-slate-500 block mt-0.5">
                                    {{ \App\Models\Setting::get('ttd_digital_nip') }}
                                </span>
                            @else
                                <span class="text-[9.5px] text-slate-400 block mt-0.5">Petugas Verifikator Berkas PSB</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- SHEET 2: LAMPIRAN BUKTI DOKUMEN PENDAFTARAN -->
        <!-- ========================================== -->
        @if(($mode ?? 'cv') == 'all')
        <div class="sheet">
            
            <!-- Header Lampiran Mini -->
            <div class="flex items-center justify-between border-b-2 border-emerald-800 pb-2 mb-4">
                <div class="flex items-center gap-3">
                    <img src="/logo.png" alt="Logo" class="w-10 h-10 object-contain">
                    <div>
                        <h4 class="text-xs font-bold uppercase text-emerald-950">Lampiran Bukti Dokumen Pendaftaran</h4>
                        <p class="text-[10px] text-slate-500">Pondok Pesantren Hidayatullah Tuksongo &bull; No. Reg: <strong class="font-mono text-slate-800">{{ $reg->no_registrasi }}</strong></p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-slate-900 block">{{ $reg->nama_lengkap }}</span>
                    <span class="text-[10px] text-slate-500">{{ $reg->jenjang }} &bull; {{ $reg->jalur }}</span>
                </div>
            </div>

            <!-- Grid Bukti Berkas -->
            <div class="space-y-4">
                
                <!-- 1. Bukti Pembayaran / Transfer -->
                <div class="border border-slate-200 rounded-lg p-3 bg-slate-50/50">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-800 uppercase flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                            1. Bukti Transfer Biaya Pendaftaran (Rp 200.000)
                        </span>
                        <span class="text-[10px] font-mono text-slate-400">
                            {{ $reg->bukti_transfer ? 'Terlampir' : 'Tidak Ada' }}
                        </span>
                    </div>

                    @if($reg->bukti_transfer)
                        @php
                            $isPdf = str_ends_with(strtolower($reg->bukti_transfer), '.pdf');
                        @endphp
                        @if($isPdf)
                            <div class="p-3 bg-white rounded border border-slate-200 text-xs flex items-center justify-between">
                                <span class="font-semibold text-slate-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9.5 8.5h-2v-3h2c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5zm5 2h-1.5V10h1.5c.83 0 1.5.67 1.5 1.5v.5c0 .83-.67 1.5-1.5 1.5zm-5-3.5h-1v1h1c.28 0 .5-.22.5-.5s-.22-.5-.5-.5zm5 1.5h-1v1h1c.28 0 .5-.22.5-.5v-.5c0-.28-.22-.5-.5-.5z"/></svg>
                                    Dokumen Bukti Transfer (Format PDF)
                                </span>
                                <a href="{{ $reg->bukti_transfer }}" target="_blank" class="px-2.5 py-1 bg-slate-800 text-white rounded text-[10px] font-semibold no-print">Buka File</a>
                            </div>
                        @else
                            <div class="max-h-[300px] overflow-hidden rounded border border-slate-200 bg-white flex items-center justify-center p-1">
                                <img src="{{ $reg->bukti_transfer }}" alt="Bukti Transfer" class="max-h-[290px] object-contain mx-auto">
                            </div>
                        @endif
                    @else
                        <div class="p-4 text-center text-xs text-slate-400 bg-white rounded border border-dashed border-slate-300">
                            Bukti transfer biaya pendaftaran belum diunggah.
                        </div>
                    @endif
                </div>

                <!-- 2. Bukti Prestasi / Tahfidz (Jika ada) -->
                @if($reg->jalur === 'Prestasi' || $reg->jalur === 'Tahfidz' || $reg->bukti_prestasi_tahfidz)
                <div class="border border-slate-200 rounded-lg p-3 bg-slate-50/50">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-800 uppercase flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            2. Bukti Sertifikat Prestasi / Tahfidz
                        </span>
                        <span class="text-[10px] font-mono text-slate-400">
                            {{ $reg->bukti_prestasi_tahfidz ? 'Terlampir' : 'Tidak Ada' }}
                        </span>
                    </div>

                    @if($reg->bukti_prestasi_tahfidz)
                        @php
                            $isPdf = str_ends_with(strtolower($reg->bukti_prestasi_tahfidz), '.pdf');
                        @endphp
                        @if($isPdf)
                            <div class="p-3 bg-white rounded border border-slate-200 text-xs flex items-center justify-between">
                                <span class="font-semibold text-slate-700">Sertifikat Prestasi (Format PDF)</span>
                                <a href="{{ $reg->bukti_prestasi_tahfidz }}" target="_blank" class="px-2.5 py-1 bg-slate-800 text-white rounded text-[10px] font-semibold no-print">Buka File</a>
                            </div>
                        @else
                            <div class="max-h-[280px] overflow-hidden rounded border border-slate-200 bg-white flex items-center justify-center p-1">
                                <img src="{{ $reg->bukti_prestasi_tahfidz }}" alt="Bukti Prestasi" class="max-h-[270px] object-contain mx-auto">
                            </div>
                        @endif
                    @else
                        <div class="p-4 text-center text-xs text-slate-400 bg-white rounded border border-dashed border-slate-300">
                            Tidak ada file sertifikat prestasi terlampir.
                        </div>
                    @endif
                </div>
                @endif

                <!-- 3. Bukti Bantuan Sosial (Jika ada) -->
                @if($reg->bukti_bantuan_sosial)
                <div class="border border-slate-200 rounded-lg p-3 bg-slate-50/50">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-800 uppercase flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                            3. Kartu / Surat Keterangan Bantuan Sosial
                        </span>
                        <span class="text-[10px] font-mono text-slate-400">Terlampir</span>
                    </div>

                    <div class="max-h-[260px] overflow-hidden rounded border border-slate-200 bg-white flex items-center justify-center p-1">
                        <img src="{{ $reg->bukti_bantuan_sosial }}" alt="Bukti Bansos" class="max-h-[250px] object-contain mx-auto">
                    </div>
                </div>
                @endif

            </div>

            <!-- Footer Sheet 2 -->
            <div class="mt-8 pt-3 border-t border-slate-200 flex justify-between items-center text-[10px] text-slate-400">
                <span>Dokumen dicetak secara mandiri oleh pendaftar / wali santri &bull; Ponpes Hidayatullah Tuksongo</span>
                <span>Halaman 2 / 2</span>
            </div>

        </div>
        @endif

    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Santri & Berkas PSB — Ponpes Hidayatullah</title>
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

    <!-- Screen-Only Top Action Bar With Integrated Filters -->
    <header class="no-print sticky top-0 z-50 bg-slate-900 text-white border-b border-slate-800 shadow-lg px-4 py-2.5">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.psb.index', request()->all()) }}" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition" title="Kembali ke Tabel PSB">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-xs sm:text-sm font-bold flex items-center gap-2">
                        <span>Cetak Kartu & Dokumen PSB</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-mono font-bold">
                            {{ count($registrations) }} Santri
                        </span>
                    </h1>
                </div>
            </div>

            <!-- Quick Filters On Print Page -->
            <form action="{{ route('admin.psb.print') }}" method="GET" class="flex flex-wrap items-center gap-2 text-xs">
                @if(request()->filled('ids'))
                    <input type="hidden" name="ids" value="{{ is_array(request('ids')) ? implode(',', request('ids')) : request('ids') }}">
                @endif
                <input type="hidden" name="mode" value="{{ $mode ?? 'all' }}">

                <!-- Filter Tahun -->
                <select name="tahun" onchange="this.form.submit()" class="bg-slate-800 text-white border border-slate-700 rounded px-2 py-1 outline-none text-[11px]">
                    <option value="">Semua Tahun</option>
                    @foreach($years ?? [] as $yr)
                        <option value="{{ $yr }}" {{ request('tahun') == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                    @endforeach
                </select>

                <!-- Filter Gender -->
                <select name="jenis_kelamin" onchange="this.form.submit()" class="bg-slate-800 text-white border border-slate-700 rounded px-2 py-1 outline-none text-[11px]">
                    <option value="">Semua Gender</option>
                    <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki (Putra)</option>
                    <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan (Putri)</option>
                </select>

                <!-- Filter Jenjang -->
                <select name="jenjang" onchange="this.form.submit()" class="bg-slate-800 text-white border border-slate-700 rounded px-2 py-1 outline-none text-[11px]">
                    <option value="">Semua Jenjang</option>
                    <option value="MTs Mukim" {{ request('jenjang') == 'MTs Mukim' ? 'selected' : '' }}>MTs Mukim</option>
                    <option value="MTs Laju" {{ request('jenjang') == 'MTs Laju' ? 'selected' : '' }}>MTs Laju</option>
                    <option value="MA Mukim" {{ request('jenjang') == 'MA Mukim' ? 'selected' : '' }}>MA Mukim</option>
                    <option value="MA Laju" {{ request('jenjang') == 'MA Laju' ? 'selected' : '' }}>MA Laju</option>
                </select>

                <!-- Filter Status -->
                <select name="status" onchange="this.form.submit()" class="bg-slate-800 text-white border border-slate-700 rounded px-2 py-1 outline-none text-[11px]">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </form>

            <!-- Print Mode Switches & Print Action -->
            <div class="flex items-center gap-2">
                @php
                    $currentQuery = request()->all();
                @endphp
                <div class="bg-slate-800 p-0.5 rounded-lg border border-slate-700 flex text-xs font-medium">
                    <a href="{{ route('admin.psb.print', array_merge($currentQuery, ['mode' => 'all'])) }}" class="px-2.5 py-1 rounded transition text-[11px] {{ ($mode ?? 'all') == 'all' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:text-white' }}">
                        CV + Berkas
                    </a>
                    <a href="{{ route('admin.psb.print', array_merge($currentQuery, ['mode' => 'cv'])) }}" class="px-2.5 py-1 rounded transition text-[11px] {{ ($mode ?? 'all') == 'cv' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:text-white' }}">
                        CV Saja
                    </a>
                    <a href="{{ route('admin.psb.print', array_merge($currentQuery, ['mode' => 'berkas'])) }}" class="px-2.5 py-1 rounded transition text-[11px] {{ ($mode ?? 'all') == 'berkas' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:text-white' }}">
                        Berkas Saja
                    </a>
                </div>

                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    <span>Cetak (Ctrl+P)</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Container For Sheets -->
    <main class="max-w-4xl mx-auto px-4 mt-6 print:m-0 print:p-0 print:max-w-none">

        @if(count($registrations) == 0)
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 shadow-sm max-w-lg mx-auto my-12">
                <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">Tidak Ada Data Santri</h3>
                <p class="text-xs text-slate-500 mb-4">Tidak ada data calon santri yang sesuai dengan filter yang dipilih.</p>
                <a href="{{ route('admin.psb.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-slate-900 text-white text-xs font-semibold">
                    Kembali ke Tabel PSB
                </a>
            </div>
        @endif

        @foreach($registrations as $reg)

            <!-- ========================================== -->
            <!-- SHEET 1: KARTU BIODATA CALON SANTRI (CV) -->
            <!-- ========================================== -->
            @if(($mode ?? 'all') == 'all' || ($mode ?? 'all') == 'cv')
            <div class="sheet">
                
                <!-- KOP SURAT RESMI PESANTREN -->
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 shrink-0 flex items-center justify-center">
                        <img src="/logo.png" alt="Logo" class="max-w-full max-h-full object-contain">
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
                    
                    <!-- Left Column: Pas Foto 3x4 Box & Status -->
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
            @endif

            <!-- ========================================== -->
            <!-- SHEET 2: LAMPIRAN BUKTI DOKUMEN PENDAFTARAN -->
            <!-- ========================================== -->
            @if(($mode ?? 'all') == 'all' || ($mode ?? 'all') == 'berkas')
            <div class="sheet">
                
                <!-- Header Lampiran Mini -->
                <div class="flex items-center justify-between border-b-2 border-emerald-800 pb-2 mb-4">
                    <div class="flex items-center gap-3">
                        <img src="/logo.png" alt="Logo" class="w-10 h-10 object-contain">
                        <div>
                            <h3 class="text-xs font-serif font-bold text-emerald-950 uppercase">Pondok Pesantren Hidayatullah Tuksongo</h3>
                            <p class="text-[10px] text-slate-500">Lampiran Dokumen Verifikasi Calon Santri Baru TA 2025/2026</p>
                        </div>
                    </div>
                    <div class="text-right text-xs">
                        <span class="text-[10px] text-slate-400 block">NO. REGISTRASI</span>
                        <strong class="font-mono text-emerald-800">{{ $reg->no_registrasi }}</strong>
                    </div>
                </div>

                <!-- Applicant Summary Strip -->
                <div class="bg-slate-100 rounded-lg p-2.5 mb-4 text-xs flex flex-wrap items-center justify-between gap-2 border border-slate-200">
                    <div>
                        <span class="text-slate-400 text-[10px] block">NAMA CALON SANTRI</span>
                        <strong class="text-slate-900 uppercase">{{ $reg->nama_lengkap }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] block">JENJANG & JALUR</span>
                        <span class="font-semibold text-slate-800">{{ $reg->jenjang }} &bull; Jalur {{ $reg->jalur }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] block">WHATSAPP WALI</span>
                        <span class="font-mono font-semibold text-emerald-800">{{ $reg->ayah_telepon ?: $reg->no_whatsapp ?: '-' }}</span>
                    </div>
                </div>

                <!-- Grid Bukti Berkas Dokumen -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    
                    <!-- 1. Bukti Transfer Biaya Pendaftaran -->
                    <div class="border border-slate-300 rounded-xl p-3 bg-white space-y-2">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                            <span class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                Bukti Transfer Biaya Pendaftaran
                            </span>
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded {{ $reg->bukti_transfer ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                {{ $reg->bukti_transfer ? 'TERUNGGAH' : 'BELUM' }}
                            </span>
                        </div>
                        <div class="h-64 border border-dashed border-slate-200 rounded-lg bg-slate-50 flex items-center justify-center overflow-hidden">
                            @if($reg->bukti_transfer)
                                <img src="{{ $reg->bukti_transfer }}" alt="Bukti Transfer" class="max-w-full max-h-full object-contain">
                            @else
                                <div class="text-center p-4 text-slate-400">
                                    <svg class="w-8 h-8 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                                    <span class="text-xs">Belum ada bukti transfer</span>
                                </div>
                            @endif
                        </div>
                        <p class="text-[9.5px] text-slate-400 text-center">Biaya Pendaftaran: Rp 200.000 (Infaq Pendaftaran Santri Baru)</p>
                    </div>

                    <!-- 2. Pas Foto 3x4 Calon Santri -->
                    <div class="border border-slate-300 rounded-xl p-3 bg-white space-y-2">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                            <span class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-red-600"></span>
                                Pas Foto Santri (3x4)
                            </span>
                            <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded {{ ($reg->foto_status ?? 'Sesuai') === 'Sesuai' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $reg->foto_status ?: 'Belum Diperiksa' }}
                            </span>
                        </div>
                        <div class="h-64 border border-dashed border-slate-200 rounded-lg bg-slate-50 flex items-center justify-center overflow-hidden p-2">
                            @if($reg->pas_foto)
                                <img src="{{ $reg->pas_foto }}" alt="Pas Foto" class="max-w-full max-h-full object-contain shadow rounded">
                            @else
                                <div class="text-center p-4 text-slate-400">
                                    <svg class="w-8 h-8 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="text-xs">Pas foto belum diunggah</span>
                                </div>
                            @endif
                        </div>
                        @php
                            $catatanSheet2 = $reg->foto_catatan;
                            if (str_contains($catatanSheet2, 'Terverifikasi otomatis')) {
                                $catatanSheet2 = '';
                            }
                        @endphp
                        <p class="text-[9.5px] text-slate-500 text-center">
                            {{ $catatanSheet2 ?: 'Ketentuan Pas Foto: Baju putih, peci hitam/jilbab, latar merah (3x4)' }}
                        </p>
                    </div>

                    <!-- 3. Berkas Sertifikat Prestasi / Tahfidz -->
                    <div class="border border-slate-300 rounded-xl p-3 bg-white space-y-2">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                            <span class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Sertifikat Prestasi / Syahadah Tahfidz
                            </span>
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded {{ $reg->bukti_prestasi_tahfidz ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-500' }}">
                                {{ $reg->bukti_prestasi_tahfidz ? 'TERUNGGAH' : 'TIDAK ADA' }}
                            </span>
                        </div>
                        <div class="h-56 border border-dashed border-slate-200 rounded-lg bg-slate-50 flex items-center justify-center overflow-hidden">
                            @if($reg->bukti_prestasi_tahfidz)
                                <img src="{{ $reg->bukti_prestasi_tahfidz }}" alt="Prestasi / Tahfidz" class="max-w-full max-h-full object-contain">
                            @else
                                <div class="text-center p-4 text-slate-400">
                                    <svg class="w-8 h-8 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                                    <span class="text-xs">Tidak ada berkas prestasi</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 4. Bukti Kartu Bantuan Sosial (KIP/PKH/KKS/KIS) -->
                    <div class="border border-slate-300 rounded-xl p-3 bg-white space-y-2">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                            <span class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                Kartu Bantuan Sosial (KIP / PKH / KKS)
                            </span>
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded {{ $reg->bukti_bantuan_sosial ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-500' }}">
                                {{ $reg->bukti_bantuan_sosial ? 'TERUNGGAH' : 'TIDAK ADA' }}
                            </span>
                        </div>
                        <div class="h-56 border border-dashed border-slate-200 rounded-lg bg-slate-50 flex items-center justify-center overflow-hidden">
                            @if($reg->bukti_bantuan_sosial)
                                <img src="{{ $reg->bukti_bantuan_sosial }}" alt="Bukti Bansos" class="max-w-full max-h-full object-contain">
                            @else
                                <div class="text-center p-4 text-slate-400">
                                    <svg class="w-8 h-8 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line></svg>
                                    <span class="text-xs">Tidak ada kartu bantuan sosial</span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Verification Notes By Committee -->
                <div class="border border-slate-300 rounded-xl p-3 bg-slate-50 text-xs">
                    <strong class="text-slate-800 block mb-1">Catatan & Lembar Kerja Verifikasi Panitia PSB:</strong>
                    <div class="grid grid-cols-3 gap-2 pt-1 text-[11px]">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-slate-300"> Bukti Transfer Valid
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-slate-300"> Pas Foto Sesuai Syarat
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-slate-300"> Biodata Telah Terverifikasi
                        </label>
                    </div>
                </div>

            </div>
            @endif

        @endforeach

    </main>

</body>
</html>

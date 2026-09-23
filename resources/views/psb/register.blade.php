<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran Santri Baru TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }} — Hidayatullah Tuksongo</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts: Plus Jakarta Sans, EB Garamond & Grenze -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=EB+Garamond:ital,wght@0,500;0,600;0,700;1,400&family=Grenze:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Grenze"', '"EB Garamond"', 'serif'],
                    },
                    colors: {
                        pondok: {
                            50: '#edfbf2',
                            100: '#d4f5de',
                            200: '#a3e4b8',
                            500: '#228b4c',
                            600: '#1e7d42',
                            700: '#1a6b38',
                            800: '#145a2e',
                            900: '#0d3b1e',
                            950: '#082412',
                        },
                        gold: {
                            300: '#e8cc5a',
                            400: '#dbb930',
                            500: '#c8a415',
                            600: '#b5920d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8faf9; color: #1a1c2b; }
        .icon-svg {
            width: 20px; height: 20px; stroke: currentColor; stroke-width: 2;
            stroke-linecap: round; stroke-linejoin: round; fill: none;
            display: inline-block; vertical-align: middle;
        }
        .step-pill.active {
            background: #145a2e;
            color: #ffffff;
            border-color: #145a2e;
        }
        .step-pill.completed {
            background: #d4f5de;
            color: #145a2e;
            border-color: #a3e4b8;
        }
        .step-pane { display: none; }
        .step-pane.active { display: block; animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-slate-50 antialiased">

    <!-- TOP HEADER (SPACIOUS & ELEGANT) -->
    <header class="bg-white border-b border-slate-200/90 sticky top-0 z-40 shadow-xs py-5 sm:py-6 px-6 sm:px-12 lg:px-20 transition-all">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                <img src="/logo.png" alt="Logo Pondok Pesantren Hidayatullah" class="w-11 h-11 sm:w-12 sm:h-12 object-contain shrink-0 group-hover:scale-105 transition">
                <div class="flex flex-col justify-center">
                    <img src="/logo1.png" alt="معهد هداية الله للتربية الإسلامية" class="h-6 sm:h-8 w-auto object-contain object-left filter contrast-105">
                    <span class="font-sans text-[11px] sm:text-xs font-semibold text-slate-600 tracking-tight">Pondok Pesantren Hidayatullah Tuksongo</span>
                </div>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('psb.checkStatus') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 hover:text-pondok-800 transition shadow-2xs">
                    <svg class="icon-svg w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <span>Cek Status Pendaftaran</span>
                </a>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full border border-emerald-200/80 bg-emerald-50/80 hover:bg-emerald-100 text-xs sm:text-sm font-semibold text-emerald-900 transition shadow-2xs">
                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-1 py-10 sm:py-16 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto space-y-8">

            <!-- Title & Hero Banner -->
            <div class="bg-gradient-to-br from-pondok-900 to-pondok-800 rounded-2xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
                <div class="relative z-10 space-y-2">
                    <div class="flex items-center gap-2 flex-wrap">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-xs text-xs font-semibold text-gold-300 border border-white/20">
                            <span class="w-2 h-2 rounded-full bg-gold-400 animate-pulse"></span>
                            TAHUN AJARAN {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 backdrop-blur-xs text-xs font-bold text-emerald-200 border border-emerald-400/30">
                            <span>🌊 {{ $gelombang ?? \App\Models\Setting::get('psb_gelombang_aktif', 'Gelombang 1') }}</span>
                        </div>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold tracking-tight">Formulir Pendaftaran Santri Baru</h1>
                    <p class="text-xs sm:text-sm text-slate-200 max-w-2xl leading-relaxed">
                        Pondok Pesantren Hidayatullah Tuksongo — Pringsurat, Temanggung. Mohon isikan identitas calon santri dan wali sesuai dengan data dokumen resmi sebenarnya.
                    </p>
                </div>
            </div>

            <!-- Error Notification -->
            @if ($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-sm space-y-1.5 shadow-sm">
                    <div class="flex items-center gap-2 font-bold text-rose-900">
                        <svg class="icon-svg w-5 h-5 text-rose-600 shrink-0" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        Mohon periksa kolom yang belum lengkap:
                    </div>
                    <ul class="list-disc pl-6 text-xs space-y-0.5 text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- STEP INDICATOR -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
                <button type="button" onclick="goToStep(1)" id="pillStep1" class="step-pill active px-3 py-2.5 rounded-xl border text-xs font-bold transition flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">1</span>
                    <span class="truncate">Jalur & Jenjang</span>
                </button>
                <button type="button" onclick="goToStep(2)" id="pillStep2" class="step-pill px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-xs font-bold transition flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-[10px]">2</span>
                    <span class="truncate">Data Diri Santri</span>
                </button>
                <button type="button" onclick="goToStep(3)" id="pillStep3" class="step-pill px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-xs font-bold transition flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-[10px]">3</span>
                    <span class="truncate">Sekolah & Orang Tua</span>
                </button>
                <button type="button" onclick="goToStep(4)" id="pillStep4" class="step-pill px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-xs font-bold transition flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-[10px]">4</span>
                    <span class="truncate">Pembayaran & Berkas</span>
                </button>
            </div>

            <!-- MAIN FORM WRAPPER -->
            <form id="psbForm" action="{{ route('psb.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateAllDocumentsBeforeSubmit(event)" class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                @csrf
                <input type="hidden" name="gelombang" value="{{ $gelombang ?? \App\Models\Setting::get('psb_gelombang_aktif', 'Gelombang 1') }}">

                <!-- ==================== STEP 1: JALUR & JENJANG PENDIDIKAN ==================== -->
                <div id="step1" class="step-pane active p-6 sm:p-8 space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">PILIHAN JALUR & JENJANG PENDIDIKAN</h2>
                        <p class="text-xs text-slate-500">Pilih program pendidikan dan jalur penerimaan santri baru sesuai kualifikasi.</p>
                    </div>

                    <!-- Gelombang Info Badge Card -->
                    <div class="p-3.5 bg-emerald-50/70 border border-emerald-200/80 rounded-xl flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shrink-0">🌊</span>
                            <div>
                                <span class="text-xs font-bold text-emerald-950 block">Gelombang Pendaftaran Aktif: {{ $gelombang ?? \App\Models\Setting::get('psb_gelombang_aktif', 'Gelombang 1') }}</span>
                                <span class="text-[11px] text-emerald-700">Formulir pendaftaran Anda akan otomatis diproses pada {{ $gelombang ?? \App\Models\Setting::get('psb_gelombang_aktif', 'Gelombang 1') }}.</span>
                            </div>
                        </div>
                        <span class="hidden sm:inline-block px-2.5 py-1 rounded-full bg-emerald-600 text-white text-[10px] font-bold tracking-wider uppercase">Aktif</span>
                    </div>

                    <!-- Pilihan Jalur & Jenjang -->
                    <div class="space-y-4">
                        <div>
                            <label for="jalurSelect" class="block text-sm font-bold text-slate-800 mb-1">
                                Pilihan Jalur Pendaftaran <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-xs text-slate-500 mb-2">Pilih jalur seleksi yang akan diikuti oleh calon santri.</p>
                            <select id="jalurSelect" name="jalur" onchange="toggleJalurBerkas(this.value)" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">
                                <option value="Reguler" {{ old('jalur') == 'Reguler' ? 'selected' : '' }}>Jalur Reguler (Tes Kemampuan Dasar & Wawancara)</option>
                                <option value="Prestasi" {{ old('jalur') == 'Prestasi' ? 'selected' : '' }}>Jalur Prestasi (Minimal Juara 1, 2, atau 3 Tingkat Kecamatan)</option>
                                <option value="Tahfidz" {{ old('jalur') == 'Tahfidz' ? 'selected' : '' }}>Jalur Tahfidz (Memiliki Syahadah Tahfidzul Qur'an)</option>
                            </select>
                        </div>

                        <div>
                            <label for="jenjangSelect" class="block text-sm font-bold text-slate-800 mb-1">
                                Jenjang Madrasah & Jalur Hunian <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-xs text-slate-500 mb-2">Tersedia pilihan mukim di asrama pesantren atau laju bagi santri sekitar kampus.</p>
                            <select id="jenjangSelect" name="jenjang" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">
                                <option value="MTs Mukim" {{ old('jenjang') == 'MTs Mukim' ? 'selected' : '' }}>MTs — Mukim (Tinggal di Asrama Pesantren)</option>
                                <option value="MTs Laju" {{ old('jenjang') == 'MTs Laju' ? 'selected' : '' }}>MTs — Laju (Pulang-Pergi / Non-Asrama)</option>
                                <option value="MA Mukim" {{ old('jenjang') == 'MA Mukim' ? 'selected' : '' }}>MA — Mukim (Tinggal di Asrama Pesantren)</option>
                                <option value="MA Laju" {{ old('jenjang') == 'MA Laju' ? 'selected' : '' }}>MA — Laju (Pulang-Pergi / Non-Asrama)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Petunjuk Pengisian -->
                    <div class="bg-pondok-50/70 border border-pondok-200 rounded-xl p-5 space-y-2">
                        <div class="flex items-center gap-2 text-pondok-800 font-bold text-xs uppercase tracking-wider">
                            <svg class="icon-svg w-4 h-4 text-pondok-600" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            Petunjuk Pengisian Formulir
                        </div>
                        <ul class="text-xs text-slate-600 space-y-1.5 list-disc pl-5">
                            <li>Isi identitas santri dan orang tua secara lengkap pada Langkah 2 dan 3.</li>
                            <li>Pada Langkah 4 (Terakhir), Anda akan diminta mengunggah <strong>Pas Foto 3x4 Santri</strong> dan <strong>Bukti Infaq Pendaftaran Rp 200.000</strong> lengkap dengan nomor rekening tujuan transfer resmi.</li>
                        </ul>
                    </div>

                    <!-- Navigation -->
                    <div class="pt-4 flex justify-end">
                        <button type="button" onclick="validateAndGoStep(1, 2)" class="px-6 py-3 bg-pondok-700 hover:bg-pondok-800 text-white font-semibold rounded-xl text-sm transition shadow-md flex items-center gap-2">
                            Lanjut: Data Diri Santri
                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>
                </div>

                <!-- ==================== STEP 2: DATA DIRI CALON SANTRI ==================== -->
                <div id="step2" class="step-pane p-6 sm:p-8 space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">DATA DIRI CALON SANTRI BARU</h2>
                        <p class="text-xs text-slate-500">Isikan identitas calon santri baru sesuai data akta kelahiran dan kartu keluarga.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label for="nama_lengkap" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap Santri <span class="text-rose-500">*</span></label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Muhammad Rayhan Al-Fatih" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki (Ikhwan / Putra)</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan (Akhwat / Putri)</option>
                            </select>
                        </div>

                        <div>
                            <label for="nisn" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">NISN (10 Digit) <span class="text-rose-500">*</span></label>
                            <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" required placeholder="Contoh: 0098765432" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">
                        </div>

                        <div>
                            <label for="nik" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">NIK Santri (16 Digit) <span class="text-rose-500">*</span></label>
                            <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required placeholder="Contoh: 3323012345670001" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">
                        </div>

                        <div>
                            <label for="nomor_kk" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nomor Kartu Keluarga (KK) <span class="text-rose-500">*</span></label>
                            <input type="text" id="nomor_kk" name="nomor_kk" value="{{ old('nomor_kk') }}" required placeholder="Contoh: 3323012345670002" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">
                        </div>

                        <div>
                            <label for="tempat_lahir" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tempat Lahir <span class="text-rose-500">*</span></label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Contoh: Temanggung" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">
                        </div>

                        <div>
                            <label for="anak_ke" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Anak Ke- <span class="text-rose-500">*</span></label>
                            <input type="number" id="anak_ke" name="anak_ke" value="{{ old('anak_ke', 1) }}" min="1" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">
                        </div>

                        <div>
                            <label for="jumlah_saudara" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Jumlah Saudara Kandung <span class="text-rose-500">*</span></label>
                            <input type="number" id="jumlah_saudara" name="jumlah_saudara" value="{{ old('jumlah_saudara', 1) }}" min="0" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">
                        </div>

                        <div class="md:col-span-2">
                            <label for="hobi" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Hobi Santri <span class="text-rose-500">*</span></label>
                            <input type="text" id="hobi" name="hobi" value="{{ old('hobi') }}" required placeholder="Contoh: Membaca, Olahraga Futsal, Kaligrafi, Panahan" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat_lengkap" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Alamat Domisili Lengkap Santri <span class="text-rose-500">*</span></label>
                            <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" required placeholder="Nama jalan, RT/RW, Dusun, Desa/Kelurahan, Kecamatan, Kabupaten, Provinsi, dan sertakan Kode Pos" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-pondok-500 outline-none transition">{{ old('alamat_lengkap') }}</textarea>
                        </div>

                        <!-- Program Bantuan Sosial (Checkboxes) -->
                        <div class="md:col-span-2 p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                                Apakah Memiliki Program Bantuan Sosial Berikut? (Centang Jika Punya)
                            </span>
                            <p class="text-xs text-slate-500">
                                Berkas kartu bantuan sosial (KIP, PKH, KKS, dll.) dapat diunggah pada Langkah 4 atau difotokopi saat tes wawancara.
                            </p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-1">
                                @php
                                    $bansosOptions = [
                                        'KKS' => 'KKS (Kartu Keluarga Sejahtera)',
                                        'KPS/KPH' => 'KPS / KPH',
                                        'KIP' => 'KIP (Kartu Indonesia Pintar)',
                                        'KIS' => 'KIS (Kartu Indonesia Sehat)',
                                        'BPJS' => 'BPJS',
                                        'PKH' => 'PKH (Program Keluarga Harapan)',
                                        'PIP' => 'PIP (Program Indonesia Pintar)',
                                    ];
                                @endphp
                                @foreach($bansosOptions as $val => $lbl)
                                    <label class="flex items-center gap-2 text-xs text-slate-700 bg-white p-2.5 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-100/70 transition">
                                        <input type="checkbox" name="bantuan_sosial[]" value="{{ $val }}" onchange="checkBansosSelected()" class="bansos-check h-4 w-4 text-pondok-600 rounded border-slate-300">
                                        <span>{{ $lbl }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="pt-4 flex flex-col-reverse sm:flex-row items-center justify-between gap-3">
                        <button type="button" onclick="goToStep(1)" class="w-full sm:w-auto px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition text-center">
                            ← Kembali
                        </button>
                        <button type="button" onclick="validateAndGoStep(2, 3)" class="w-full sm:w-auto px-6 py-3 bg-pondok-700 hover:bg-pondok-800 text-white font-semibold rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                            Lanjut: Sekolah &amp; Orang Tua
                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>
                </div>

                <!-- ==================== STEP 3: IDENTITAS SEKOLAH ASAL & ORANG TUA ==================== -->
                <div id="step3" class="step-pane p-6 sm:p-8 space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">SEKOLAH ASAL & DATA ORANG TUA</h2>
                        <p class="text-xs text-slate-500">Isikan identitas sekolah asal dan identitas orang tua kandung sesuai data dokumen resmi.</p>
                    </div>

                    <!-- 1. IDENTITAS SEKOLAH ASAL -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-200 text-pondok-800 font-bold text-sm">
                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            SEKOLAH ASAL
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label for="nama_sekolah" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Sekolah Asal <span class="text-rose-500">*</span></label>
                                <input type="text" id="nama_sekolah" name="nama_sekolah" value="{{ old('nama_sekolah') }}" required placeholder="Contoh: SD Negeri 1 Pringsurat / MI Ma'arif Temanggung" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                            <div>
                                <label for="alamat_sekolah" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Alamat Sekolah Asal <span class="text-rose-500">*</span></label>
                                <textarea id="alamat_sekolah" name="alamat_sekolah" rows="2" required placeholder="Kota/Kabupaten & Kecamatan sekolah" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">{{ old('alamat_sekolah') }}</textarea>
                            </div>
                            <div>
                                <label for="tahun_lulus" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tahun Lulus <span class="text-rose-500">*</span></label>
                                <input type="text" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus', '2025') }}" required placeholder="Contoh: 2025" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                        </div>
                    </div>

                    @php
                        $pekerjaanList = [
                            'Buruh', 'Karyawan Swasta', 'Petani/Pekebun', 'Pedagang Besar',
                            'Pedagang Kecil', 'TKI', 'PNS/TNI/POLRI', 'Pensiunan',
                            'Wirausaha', 'Wiraswasta', 'Meninggal'
                        ];
                        $pendidikanList = ['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'];
                        $penghasilanList = [
                            '< Rp 1.000.000',
                            'Rp 1.000.000 - Rp 2.000.000',
                            'Rp 2.000.000 - Rp 5.000.000',
                            'Rp 5.000.000 - Rp 10.000.000',
                            '> Rp 10.000.000',
                            'Tidak Berpenghasilan'
                        ];
                    @endphp

                    <!-- 2. AYAH KANDUNG -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-200 text-pondok-800 font-bold text-sm">
                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            AYAH KANDUNG
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap Ayah <span class="text-rose-500">*</span></label>
                                <input type="text" name="ayah_nama" value="{{ old('ayah_nama') }}" required placeholder="Nama lengkap ayah" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">NIK Ayah <span class="text-rose-500">*</span></label>
                                <input type="text" name="ayah_nik" value="{{ old('ayah_nik') }}" required placeholder="16 digit NIK" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tahun Lahir <span class="text-rose-500">*</span></label>
                                <input type="text" name="ayah_tahun_lahir" value="{{ old('ayah_tahun_lahir') }}" required placeholder="Contoh: 1978" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nomor Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                                <input type="text" name="ayah_telepon" value="{{ old('ayah_telepon') }}" required placeholder="Contoh: 08123456789" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Alamat Lengkap Ayah <span class="text-rose-500">*</span></label>
                                <textarea name="ayah_alamat" rows="2" required placeholder="Jalan, RT/RW, Dusun, Kelurahan, Kecamatan, Kab/Kota, Kode Pos" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">{{ old('ayah_alamat') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Ijazah Terakhir <span class="text-rose-500">*</span></label>
                                <select name="ayah_pendidikan" required class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                                    <option value="">-- Pilih Pendidikan --</option>
                                    @foreach($pendidikanList as $pend)
                                        <option value="{{ $pend }}" {{ old('ayah_pendidikan') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Pekerjaan Ayah <span class="text-rose-500">*</span></label>
                                <select name="ayah_pekerjaan" required class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                                    <option value="">-- Pilih Pekerjaan --</option>
                                    @foreach($pekerjaanList as $pek)
                                        <option value="{{ $pek }}" {{ old('ayah_pekerjaan') == $pek ? 'selected' : '' }}>{{ $pek }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Penghasilan Perbulan <span class="text-rose-500">*</span></label>
                                <select name="ayah_penghasilan" required class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                                    <option value="">-- Pilih Rentang Penghasilan --</option>
                                    @foreach($penghasilanList as $pengh)
                                        <option value="{{ $pengh }}" {{ old('ayah_penghasilan') == $pengh ? 'selected' : '' }}>{{ $pengh }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- 3. IBU KANDUNG -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-200 text-pondok-800 font-bold text-sm">
                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            IBU KANDUNG
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap Ibu <span class="text-rose-500">*</span></label>
                                <input type="text" name="ibu_nama" value="{{ old('ibu_nama') }}" required placeholder="Nama lengkap ibu" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">NIK Ibu <span class="text-rose-500">*</span></label>
                                <input type="text" name="ibu_nik" value="{{ old('ibu_nik') }}" required placeholder="16 digit NIK" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tahun Lahir <span class="text-rose-500">*</span></label>
                                <input type="text" name="ibu_tahun_lahir" value="{{ old('ibu_tahun_lahir') }}" required placeholder="Contoh: 1982" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nomor Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                                <input type="text" name="ibu_telepon" value="{{ old('ibu_telepon') }}" required placeholder="Contoh: 081329942998" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Alamat Lengkap Ibu <span class="text-rose-500">*</span></label>
                                <textarea name="ibu_alamat" rows="2" required placeholder="Jalan, RT/RW, Dusun, Kelurahan, Kecamatan, Kab/Kota, Kode Pos" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">{{ old('ibu_alamat') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Ijazah Terakhir <span class="text-rose-500">*</span></label>
                                <select name="ibu_pendidikan" required class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                                    <option value="">-- Pilih Pendidikan --</option>
                                    @foreach($pendidikanList as $pend)
                                        <option value="{{ $pend }}" {{ old('ibu_pendidikan') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Pekerjaan Ibu <span class="text-rose-500">*</span></label>
                                <select name="ibu_pekerjaan" required class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                                    <option value="">-- Pilih Pekerjaan --</option>
                                    @foreach($pekerjaanList as $pek)
                                        <option value="{{ $pek }}" {{ old('ibu_pekerjaan') == $pek ? 'selected' : '' }}>{{ $pek }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Penghasilan Perbulan <span class="text-rose-500">*</span></label>
                                <select name="ibu_penghasilan" required class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                                    <option value="">-- Pilih Rentang Penghasilan --</option>
                                    @foreach($penghasilanList as $pengh)
                                        <option value="{{ $pengh }}" {{ old('ibu_penghasilan') == $pengh ? 'selected' : '' }}>{{ $pengh }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- 4. JIKA WALI BUKAN AYAH ATAU IBU (OPSIONAL) -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                            <div class="flex items-center gap-2 text-slate-700 font-bold text-sm">
                                <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                <span>JIKA WALI BUKAN AYAH ATAU IBU (OPSIONAL)</span>
                            </div>
                            <span class="text-[11px] bg-slate-200 text-slate-700 px-2 py-0.5 rounded font-semibold">Hanya Diisi Jika Ada</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Hubungan Wali dengan Calon Santri</label>
                                <input type="text" name="wali_hubungan" value="{{ old('wali_hubungan') }}" placeholder="Contoh: Paman, Bibi, Kakek, Orang Tua Angkat" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap Wali</label>
                                <input type="text" name="wali_nama" value="{{ old('wali_nama') }}" placeholder="Nama lengkap wali" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">NIK Wali</label>
                                <input type="text" name="wali_nik" value="{{ old('wali_nik') }}" placeholder="16 digit NIK" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nomor Telepon / WhatsApp Wali</label>
                                <input type="text" name="wali_telepon" value="{{ old('wali_telepon') }}" placeholder="Nomor aktif wali" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-pondok-500">
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="pt-4 flex flex-col-reverse sm:flex-row items-center justify-between gap-3">
                        <button type="button" onclick="goToStep(2)" class="w-full sm:w-auto px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition text-center">
                            ← Kembali
                        </button>
                        <button type="button" onclick="validateAndGoStep(3, 4)" class="w-full sm:w-auto px-6 py-3 bg-pondok-700 hover:bg-pondok-800 text-white font-semibold rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                            Lanjut: Pembayaran &amp; Upload Berkas
                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>
                </div>

                <!-- ==================== STEP 4: PEMBAYARAN INFAQ & UNGGAH DOKUMEN (BAGIAN AKHIR) ==================== -->
                <div id="step4" class="step-pane p-6 sm:p-8 space-y-8">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">REKENING PEMBAYARAN & UNGGAH DOKUMEN</h2>
                        <p class="text-xs text-slate-500">Lakukan pembayaran infaq pendaftaran ke rekening resmi dan unggah dokumen pendukung calon santri baru.</p>
                    </div>

                    <!-- BOX INFORMASI REKENING TUJUAN TRANSFER RESMI -->
                    <div class="bg-gradient-to-br from-pondok-900 to-pondok-800 text-white rounded-2xl p-5 sm:p-6 shadow-md space-y-4">
                        <div class="flex items-start justify-between gap-4 border-b border-white/15 pb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gold-500 text-slate-900 flex items-center justify-center font-bold shrink-0 shadow-sm">
                                    <svg class="icon-svg w-5 h-5" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                </div>
                                <div>
                                    <span class="text-[11px] font-bold tracking-widest text-gold-300 uppercase block">Keterangan Transfer Resmi</span>
                                    <h3 class="font-bold text-base text-white leading-tight">Rekening Tujuan Infaq Pendaftaran Santri Baru</h3>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-[10px] text-slate-300 block">Biaya Infaq:</span>
                                <span class="text-base sm:text-lg font-extrabold text-gold-300 font-mono">Rp 200.000</span>
                            </div>
                        </div>

                        <p class="text-xs text-slate-200 leading-relaxed">
                            Silakan transfer infaq pendaftaran sebesar <strong>Rp 200.000</strong> ke salah satu rekening panitia resmi berikut:
                        </p>

                        <!-- Pilihan Kartu Rekening Bank -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <!-- Rekening BRI -->
                            <div class="bg-white/10 backdrop-blur-xs border border-white/20 p-4 rounded-xl space-y-2 hover:bg-white/15 transition">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 rounded bg-blue-600 text-white text-[11px] font-bold">BANK BRI</span>
                                    <button type="button" onclick="copyRek('{{ \App\Models\Setting::get('rek_admin_bri_no', '010201022009537') }}', this)" class="text-[11px] bg-white/20 hover:bg-white/30 text-gold-300 px-2.5 py-1 rounded-md font-semibold transition flex items-center gap-1">
                                        <svg class="icon-svg w-3 h-3" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                        <span>Salin No. Rek</span>
                                    </button>
                                </div>
                                <div>
                                    <span class="text-lg font-mono font-bold tracking-wider text-white block">{{ \App\Models\Setting::get('rek_admin_bri_no', '010201022009537') }}</span>
                                    <span class="text-xs text-slate-300 block">Atas Nama: <strong>{{ \App\Models\Setting::get('rek_admin_bri_an', 'DIKY FACHRI HUSEIN') }}</strong> (Panitia PSB)</span>
                                </div>
                            </div>

                            <!-- Rekening BCA -->
                            <div class="bg-white/10 backdrop-blur-xs border border-white/20 p-4 rounded-xl space-y-2 hover:bg-white/15 transition">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 rounded bg-indigo-600 text-white text-[11px] font-bold">BANK BCA</span>
                                    <button type="button" onclick="copyRek('{{ \App\Models\Setting::get('rek_admin_bca_no', '1221220167') }}', this)" class="text-[11px] bg-white/20 hover:bg-white/30 text-gold-300 px-2.5 py-1 rounded-md font-semibold transition flex items-center gap-1">
                                        <svg class="icon-svg w-3 h-3" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                        <span>Salin No. Rek</span>
                                    </button>
                                </div>
                                <div>
                                    <span class="text-lg font-mono font-bold tracking-wider text-white block">{{ \App\Models\Setting::get('rek_admin_bca_no', '1221220167') }}</span>
                                    <span class="text-xs text-slate-300 block">Atas Nama: <strong>{{ \App\Models\Setting::get('rek_admin_bca_an', 'DIKY FACHRI HUSEIN') }}</strong> (Panitia PSB)</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-300/90 pt-1 flex items-center justify-between flex-wrap gap-2">
                            <span>* Pembayaran tunai dapat dilakukan di Sekretariat PSB Pondok (Kampus 2) dan difoto nota kwitansinya.</span>
                            <span class="text-gold-300 font-medium">CS Konfirmasi WA: {{ \App\Models\Setting::get('rek_admin_konfirmasi_phone', '0852-9042-9617') }} ({{ \App\Models\Setting::get('rek_admin_konfirmasi_nama', 'Ustdh. Harsih Nur A') }})</span>
                        </div>
                    </div>

                    <!-- DOKUMEN 1: UNGGAH BUKTI INFAQ PENDAFTARAN -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-3 transition" id="card_doc_transfer">
                        <div class="flex items-center justify-between">
                            <label for="bukti_transfer" class="block text-sm font-bold text-slate-800">
                                1. Unggah Bukti Infaq Pendaftaran (Rp 200.000) <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Wajib Upload</span>
                        </div>
                        <p class="text-xs text-slate-500">
                            Upload struk transfer Bank BRI/BCA atau nota kwitansi pembayaran tunai. Format gambar (JPG, PNG, WEBP) atau PDF maks 10 MB.
                        </p>
                        <input type="file" id="bukti_transfer" name="bukti_transfer" accept="image/*,application/pdf" required onchange="previewAndVerifyDoc(event, 'transfer')" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pondok-600 file:text-white hover:file:bg-pondok-700 file:cursor-pointer border border-slate-200 bg-white rounded-xl p-1.5">

                        <!-- Preview & Status Bukti Transfer -->
                        <div id="preview_transfer_box" class="hidden rounded-xl border border-slate-200 bg-white p-3 flex flex-col sm:flex-row items-center gap-3 shadow-xs">
                            <div class="w-20 h-24 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 shrink-0 flex items-center justify-center relative shadow-xs">
                                <img id="preview_transfer_img" src="" alt="Preview Bukti Transfer" class="hidden w-full h-full object-cover">
                                <div id="preview_transfer_pdf" class="hidden flex flex-col items-center justify-center text-rose-600">
                                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg>
                                    <span class="text-[9px] font-bold uppercase mt-0.5">PDF</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 space-y-1 w-full text-left">
                                <div id="status_transfer_badge" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold"></div>
                                <p id="status_transfer_text" class="text-xs leading-relaxed text-slate-600"></p>
                                <span id="status_transfer_info" class="text-[10px] text-slate-400 block font-mono"></span>
                            </div>
                        </div>
                    </div>

                    <!-- DOKUMEN 2: UNGGAH PAS FOTO 3X4 CALON SANTRI -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-3 transition" id="card_doc_foto">
                        <div class="flex items-center justify-between">
                            <label for="pas_foto" class="block text-sm font-bold text-slate-800">
                                2. Unggah Pas Foto 3x4 Calon Santri <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Wajib Upload</span>
                        </div>
                        
                        <!-- Ketentuan Foto Box -->
                        <div class="p-3 bg-red-50 border border-red-200 rounded-xl space-y-1 text-xs text-red-900">
                            <span class="font-bold block">Ketentuan Pas Foto Santri:</span>
                            <ol class="list-decimal pl-4 space-y-0.5 text-xs text-red-800">
                                <li>Memakai kemeja/baju putih rapi</li>
                                <li>Memakai peci hitam (putra) / kerudung putih (putri)</li>
                                <li><strong>Background / Latar Belakang berwarna MERAH</strong></li>
                                <li>Posisi foto tegak lurus (tidak miring/selfie), rasio 3x4</li>
                            </ol>
                        </div>

                        <div class="flex flex-col sm:flex-row items-start gap-4 pt-1">
                            <div class="flex-1 w-full">
                                <input type="file" id="pas_foto" name="pas_foto" accept="image/*" required onchange="previewAndVerifyDoc(event, 'foto')" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pondok-600 file:text-white hover:file:bg-pondok-700 file:cursor-pointer border border-slate-200 bg-white rounded-xl p-1.5">
                                <span class="text-[11px] text-slate-400 block mt-1">Format gambar didukung: JPG, PNG, WEBP. Maks 10 MB.</span>
                            </div>
                            <div id="preview_foto_box" class="hidden w-20 h-26 bg-red-600 rounded-lg overflow-hidden border-2 border-red-500 shadow-sm shrink-0 text-center">
                                <img id="preview_foto_img" src="" alt="Preview 3x4" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <!-- Status Badge Pas Foto -->
                        <div id="preview_foto_status_box" class="hidden rounded-xl border border-slate-200 bg-white p-2.5 space-y-1">
                            <div id="status_foto_badge" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold"></div>
                            <p id="status_foto_text" class="text-xs text-slate-600 leading-relaxed"></p>
                            <span id="status_foto_info" class="text-[10px] text-slate-400 block font-mono"></span>
                        </div>
                    </div>

                    <!-- DOKUMEN 3: UNGGAH SCAN / FOTO AKTA KELAHIRAN -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-3 transition" id="card_doc_akta">
                        <div class="flex items-center justify-between">
                            <label for="file_akta_kelahiran" class="block text-sm font-bold text-slate-800">
                                3. Unggah Scan / Foto Akta Kelahiran Calon Santri <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Wajib Upload</span>
                        </div>

                        <div class="p-2.5 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-900 flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div class="text-[11px] leading-relaxed text-emerald-800">
                                <strong>Ketentuan Akta Kelahiran:</strong> Foto/scan asli lembaran Akta Kelahiran posisi <strong>tegak (portrait)</strong>. Pastikan nama santri, tanggal lahir, dan nama orang tua terbaca jelas.
                            </div>
                        </div>

                        <input type="file" id="file_akta_kelahiran" name="file_akta_kelahiran" accept="image/*,application/pdf" required onchange="previewAndVerifyDoc(event, 'akta')" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pondok-600 file:text-white hover:file:bg-pondok-700 file:cursor-pointer border border-slate-200 bg-white rounded-xl p-1.5">

                        <!-- Preview & Status Akta -->
                        <div id="preview_akta_box" class="hidden rounded-xl border border-slate-200 bg-white p-3 flex flex-col sm:flex-row items-center gap-3 shadow-xs">
                            <div class="w-20 h-26 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 shrink-0 flex items-center justify-center relative shadow-xs">
                                <img id="preview_akta_img" src="" alt="Preview Akta" class="hidden w-full h-full object-cover">
                                <div id="preview_akta_pdf" class="hidden flex flex-col items-center justify-center text-rose-600">
                                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg>
                                    <span class="text-[9px] font-bold uppercase mt-0.5">PDF</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 space-y-1 w-full text-left">
                                <div id="status_akta_badge" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold"></div>
                                <p id="status_akta_text" class="text-xs leading-relaxed text-slate-600"></p>
                                <span id="status_akta_info" class="text-[10px] text-slate-400 block font-mono"></span>
                            </div>
                        </div>
                    </div>

                    <!-- DOKUMEN 4: UNGGAH SCAN / FOTO KARTU KELUARGA (KK) -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-3 transition" id="card_doc_kk">
                        <div class="flex items-center justify-between">
                            <label for="file_kk" class="block text-sm font-bold text-slate-800">
                                4. Unggah Scan / Foto Kartu Keluarga (KK) <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Wajib Upload</span>
                        </div>

                        <div class="p-2.5 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900 flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div class="text-[11px] leading-relaxed text-blue-800">
                                <strong>Ketentuan Kartu Keluarga:</strong> Foto/scan seluruh lembaran KK secara <strong>mendatar (landscape)</strong>. Pastikan Nomor KK dan data baris anggota keluarga terbaca jelas (tidak buram/terpotong).
                            </div>
                        </div>

                        <input type="file" id="file_kk" name="file_kk" accept="image/*,application/pdf" required onchange="previewAndVerifyDoc(event, 'kk')" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pondok-600 file:text-white hover:file:bg-pondok-700 file:cursor-pointer border border-slate-200 bg-white rounded-xl p-1.5">

                        <!-- Preview & Status KK -->
                        <div id="preview_kk_box" class="hidden rounded-xl border border-slate-200 bg-white p-3 flex flex-col sm:flex-row items-center gap-3 shadow-xs">
                            <div class="w-28 h-20 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 shrink-0 flex items-center justify-center relative shadow-xs">
                                <img id="preview_kk_img" src="" alt="Preview KK" class="hidden w-full h-full object-cover">
                                <div id="preview_kk_pdf" class="hidden flex flex-col items-center justify-center text-rose-600">
                                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg>
                                    <span class="text-[9px] font-bold uppercase mt-0.5">PDF</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 space-y-1 w-full text-left">
                                <div id="status_kk_badge" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold"></div>
                                <p id="status_kk_text" class="text-xs leading-relaxed text-slate-600"></p>
                                <span id="status_kk_info" class="text-[10px] text-slate-400 block font-mono"></span>
                            </div>
                        </div>
                    </div>

                    <!-- DOKUMEN 5: UNGGAH SCAN / FOTO KTP ORANG TUA / WALI -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-3 transition" id="card_doc_ktp">
                        <div class="flex items-center justify-between">
                            <label for="file_ktp_ortu" class="block text-sm font-bold text-slate-800">
                                5. Unggah Scan / Foto KTP Orang Tua (Ayah / Ibu / Wali) <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Wajib Upload</span>
                        </div>

                        <div class="p-2.5 bg-purple-50 border border-purple-200 rounded-xl text-xs text-purple-900 flex items-start gap-2">
                            <svg class="w-4 h-4 text-purple-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div class="text-[11px] leading-relaxed text-purple-800">
                                <strong>Ketentuan Foto KTP:</strong> Foto fisik e-KTP secara <strong>mendatar / landscape</strong> (bukan foto selfie/tegak). Pastikan NIK, nama, dan foto terlihat jelas tanpa pantulan silau lampu.
                            </div>
                        </div>

                        <input type="file" id="file_ktp_ortu" name="file_ktp_ortu" accept="image/*,application/pdf" required onchange="previewAndVerifyDoc(event, 'ktp')" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pondok-600 file:text-white hover:file:bg-pondok-700 file:cursor-pointer border border-slate-200 bg-white rounded-xl p-1.5">

                        <!-- Preview & Status KTP -->
                        <div id="preview_ktp_box" class="hidden rounded-xl border border-slate-200 bg-white p-3 flex flex-col sm:flex-row items-center gap-3 shadow-xs">
                            <div class="w-28 h-20 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 shrink-0 flex items-center justify-center relative shadow-xs">
                                <img id="preview_ktp_img" src="" alt="Preview KTP" class="hidden w-full h-full object-cover">
                                <div id="preview_ktp_pdf" class="hidden flex flex-col items-center justify-center text-rose-600">
                                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg>
                                    <span class="text-[9px] font-bold uppercase mt-0.5">PDF</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 space-y-1 w-full text-left">
                                <div id="status_ktp_badge" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold"></div>
                                <p id="status_ktp_text" class="text-xs leading-relaxed text-slate-600"></p>
                                <span id="status_ktp_info" class="text-[10px] text-slate-400 block font-mono"></span>
                            </div>
                        </div>
                    </div>

                    <!-- DOKUMEN 6: KONDISIONAL BERKAS JALUR PRESTASI / TAHFIDZ -->
                    <div id="berkasKhususCard" class="hidden p-5 bg-amber-50/70 rounded-2xl border-2 border-amber-300 space-y-3">
                        <div class="flex items-center gap-2 text-amber-900 font-bold text-sm">
                            <svg class="icon-svg w-4 h-4 text-amber-600" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                            <span id="berkasKhususTitle">6. UNGGAH BUKTI JALUR PRESTASI / TAHFIDZ</span>
                        </div>
                        <p id="berkasKhususDesc" class="text-xs text-amber-800 leading-relaxed">
                            Silakan upload sertifikat piagam kejuaraan (minimal juara 1, 2, atau 3 tingkat kecamatan) atau Syahadah Tahfidzul Qur'an.
                        </p>
                        <div>
                            <label for="bukti_prestasi_tahfidz" class="block text-xs font-bold text-slate-800 mb-1">
                                File Sertifikat / Syahadah <span class="text-rose-500">*</span>
                            </label>
                            <input type="file" id="bukti_prestasi_tahfidz" name="bukti_prestasi_tahfidz" accept="image/*,application/pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 file:cursor-pointer border border-amber-200 bg-white rounded-xl p-1.5">
                            <span class="text-[11px] text-amber-700 block mt-1">Upload 1 file: Gambar/PDF. Maks 10 MB.</span>
                        </div>
                    </div>

                    <!-- DOKUMEN 7: BUKTI BANTUAN SOSIAL (JIKA MEMILIKI) -->
                    <div id="berkasBansosCard" class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="bukti_bantuan_sosial" class="block text-sm font-bold text-slate-800">
                                7. Unggah Bukti Bantuan Sosial (KIP / PKH / KKS / KIS)
                            </label>
                            <span class="text-[11px] font-semibold text-slate-500 bg-slate-200 px-2 py-0.5 rounded">Jika Memiliki</span>
                        </div>
                        <p class="text-xs text-slate-500">
                            Upload foto kartu KIP, KKS, PKH, atau KIS. Lewati jika calon santri tidak memiliki program bantuan sosial.
                        </p>
                        <input type="file" id="bukti_bantuan_sosial" name="bukti_bantuan_sosial" accept="image/*,application/pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-600 file:text-white hover:file:bg-slate-700 file:cursor-pointer border border-slate-200 bg-white rounded-xl p-1.5">
                    </div>

                    <!-- Final Agreement & Submit Button -->
                    <div class="pt-4 border-t border-slate-200 space-y-4">
                        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200 flex items-start gap-3">
                            <input id="setujuSyarat" type="checkbox" required class="h-4 w-4 mt-0.5 text-pondok-600 rounded border-slate-300 cursor-pointer">
                            <label for="setujuSyarat" class="text-xs text-emerald-950 leading-relaxed cursor-pointer select-none">
                                Saya menyatakan dengan sesungguhnya bahwa seluruh data yang diisikan dalam formulir pendaftaran ini adalah <strong>benar, lengkap, dan dapat dipertanggungjawabkan</strong> sesuai dokumen asli.
                            </label>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-3 pt-2">
                            <button type="button" onclick="goToStep(3)" class="w-full sm:w-auto px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition text-center">
                                ← Kembali
                            </button>
                            <button type="submit" id="btnSubmitForm" class="w-full sm:w-auto px-8 py-3.5 bg-pondok-700 hover:bg-pondok-800 text-white font-bold rounded-xl text-sm transition shadow-lg flex items-center justify-center gap-2">
                                <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                Kirim Formulir Pendaftaran Sekarang
                            </button>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-6 border-t border-slate-200 bg-white text-center text-xs text-slate-500 px-4">
        <p>© {{ date('Y') }} Pondok Pesantren Hidayatullah Tuksongo Pringsurat Temanggung. Hotline Panitia PSB: {{ \App\Models\Setting::get('kontak_hotline', \App\Models\Setting::get('kontak_hotline_1', '0813-9110-9966')) }}</p>
    </footer>

    <!-- JAVASCRIPT WIZARD, CLIPBOARD & PREVIEW -->
    <script>
        let currentStep = 1;

        function goToStep(step) {
            document.querySelectorAll('.step-pane').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.step-pill').forEach((pill, idx) => {
                pill.classList.remove('active');
                if (idx + 1 < step) pill.classList.add('completed');
                else pill.classList.remove('completed');
            });

            document.getElementById('step' + step).classList.add('active');
            document.getElementById('pillStep' + step).classList.add('active');
            currentStep = step;
            window.scrollTo({ top: 120, behavior: 'smooth' });
        }

        function validateAndGoStep(fromStep, toStep) {
            const pane = document.getElementById('step' + fromStep);
            const inputs = pane.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            let firstInvalid = null;

            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    isValid = false;
                    input.classList.add('border-rose-500', 'bg-rose-50');
                    if (!firstInvalid) firstInvalid = input;
                } else {
                    input.classList.remove('border-rose-500', 'bg-rose-50');
                }
            });

            if (!isValid) {
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.reportValidity();
                }
                return;
            }

            goToStep(toStep);
        }

        function toggleJalurBerkas(jalur) {
            const card = document.getElementById('berkasKhususCard');
            const fileInput = document.getElementById('bukti_prestasi_tahfidz');
            const title = document.getElementById('berkasKhususTitle');
            const desc = document.getElementById('berkasKhususDesc');

            if (jalur === 'Prestasi') {
                card.classList.remove('hidden');
                title.textContent = '6. UNGGAH BUKTI PRESTASI KEJUARAAN';
                desc.textContent = 'Silakan upload bukti sertifikat prestasi minimal tingkat kecamatan (Juara 1, 2, atau 3).';
                fileInput.required = true;
            } else if (jalur === 'Tahfidz') {
                card.classList.remove('hidden');
                title.textContent = '6. UNGGAH SYAHADAH HAFALAN TAHFIDZ';
                desc.textContent = 'Silakan upload Syahadah / Piagam hafalan Al-Qur\'an dari madrasah atau halaqah tahfidz sebelumnya.';
                fileInput.required = true;
            } else {
                // Reguler
                card.classList.add('hidden');
                fileInput.required = false;
                fileInput.value = '';
            }
        }

        // Tracking status verifikasi tiap dokumen
        const docVerificationState = {
            transfer: { valid: false, message: 'Belum ada file bukti transfer dipilih' },
            foto: { valid: false, message: 'Belum ada file pas foto dipilih' },
            akta: { valid: false, message: 'Belum ada file akta kelahiran dipilih' },
            kk: { valid: false, message: 'Belum ada file kartu keluarga dipilih' },
            ktp: { valid: false, message: 'Belum ada file KTP orang tua dipilih' }
        };

        function previewAndVerifyDoc(event, type) {
            const input = event.target;
            const file = input.files ? input.files[0] : null;

            const box = document.getElementById(`preview_${type}_box`);
            const imgEl = document.getElementById(`preview_${type}_img`);
            const pdfEl = document.getElementById(`preview_${type}_pdf`);
            const badgeEl = document.getElementById(`status_${type}_badge`);
            const textEl = document.getElementById(`status_${type}_text`);
            const infoEl = document.getElementById(`status_${type}_info`);
            const cardEl = document.getElementById(`card_doc_${type}`);

            // Khusus foto ada status box tersendiri
            const fotoStatusBox = document.getElementById('preview_foto_status_box');
            if (type === 'foto' && fotoStatusBox) {
                fotoStatusBox.classList.remove('hidden');
            }

            if (!file) {
                if (box) box.classList.add('hidden');
                if (fotoStatusBox) fotoStatusBox.classList.add('hidden');
                docVerificationState[type] = { valid: false, message: 'File belum dipilih' };
                return;
            }

            if (box) box.classList.remove('hidden');

            const fileSizeKB = (file.size / 1024).toFixed(1);
            const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');

            // 1. Validasi Ukuran Minimal (Mencegah file dummy kosong / corrupt < 25 KB)
            if (file.size < 25000) {
                setDocStatus(type, false, 
                    `Ukuran file terlalu kecil (${fileSizeKB} KB). File terindikasi kosong, buram, atau tidak terbaca. Harap gunakan foto/scan dokumen asli yang jelas.`,
                    `Ukuran: ${fileSizeKB} KB (Minimal 25 KB)`
                );
                return;
            }

            // 2. Jika Dokumen PDF
            if (isPdf) {
                if (imgEl) imgEl.classList.add('hidden');
                if (pdfEl) pdfEl.classList.remove('hidden');

                if (type === 'foto') {
                    // Pas foto WAJIB format gambar
                    setDocStatus(type, false, 
                        'Pas foto santri wajib berformat gambar (JPG, PNG, WEBP), tidak boleh berformat PDF.',
                        `Format: PDF (${fileSizeKB} KB)`
                    );
                    return;
                }

                setDocStatus(type, true, 
                    `Dokumen PDF siap diunggah (${fileSizeKB} KB).`,
                    `Format: Dokumen PDF | Ukuran: ${fileSizeKB} KB`
                );
                return;
            }

            // 3. Jika Dokumen Gambar (JPG, PNG, WEBP)
            if (pdfEl) pdfEl.classList.add('hidden');
            if (imgEl) imgEl.classList.remove('hidden');

            const reader = new FileReader();
            reader.onload = function(e) {
                const dataUrl = e.target.result;
                if (imgEl) imgEl.src = dataUrl;

                const tempImg = new Image();
                tempImg.onload = function() {
                    const width = tempImg.naturalWidth || tempImg.width;
                    const height = tempImg.naturalHeight || tempImg.height;
                    const ratio = width / height;

                    const issues = [];

                    // 1. Cek Apakah Malah Mengunggah Pas Foto Berlatar Merah ke Kolom Dokumen (KK/KTP/Akta/Transfer)
                    if (type !== 'foto') {
                        const isRed = checkCanvasRedBackground(tempImg);
                        if (isRed) {
                            issues.push('Terdeteksi Pas Foto santri (background merah)! Kolom ini untuk dokumen fisik asli, dilarang mengunggah pas foto santri.');
                        }
                    }

                    // 2. Cek Apakah File Ini Sama dengan File Dokumen Lain (Mencegah upload pas foto berkali-kali)
                    const otherDocCheck = [
                        { key: 'foto', name: 'Pas Foto 3x4', id: 'pas_foto' },
                        { key: 'transfer', name: 'Bukti Transfer', id: 'bukti_transfer' },
                        { key: 'akta', name: 'Akta Kelahiran', id: 'file_akta_kelahiran' },
                        { key: 'kk', name: 'Kartu Keluarga (KK)', id: 'file_kk' },
                        { key: 'ktp', name: 'KTP Orang Tua', id: 'file_ktp_ortu' }
                    ];
                    for (const od of otherDocCheck) {
                        if (od.key !== type) {
                            const oel = document.getElementById(od.id);
                            if (oel && oel.files && oel.files[0]) {
                                if (oel.files[0].size === file.size && oel.files[0].name === file.name) {
                                    issues.push(`File ini SAMA PERSIS dengan yang diunggah di ${od.name}. Dilarang mengunggah file pas foto yang sama untuk dokumen berbeda!`);
                                    break;
                                }
                            }
                        }
                    }

                    // 3. Aturan Spesifik Tiap Dokumen
                    if (type === 'foto') {
                        // Pas Foto: Rasio 3x4 portrait (0.60 s/d 0.88)
                        if (ratio >= 0.95) {
                            issues.push('Foto terdeteksi miring / mendatar (landscape). Wajib tegak lurus 3x4.');
                        } else if (ratio < 0.55 || ratio > 0.90) {
                            issues.push('Proporsi foto tidak sesuai rasio 3x4.');
                        }

                        // Background Merah Check
                        const isRed = checkCanvasRedBackground(tempImg);
                        if (!isRed) {
                            issues.push('Latar belakang (background) belum berwarna MERAH sesuai ketentuan.');
                        }
                    } else if (type === 'ktp') {
                        // KTP: Kartu e-KTP Indonesia wajib LANDSCAPE (mendatar)
                        if (ratio < 0.95) {
                            issues.push('Foto KTP terdeteksi tegak / foto selfie! Harap foto fisik kartu e-KTP secara mendatar (landscape).');
                        }
                        if (width < 350 || height < 200) {
                            issues.push(`Resolusi terlalu kecil (${width}x${height} px). NIK dan nama rawan tidak terbaca.`);
                        }
                    } else if (type === 'kk') {
                        // Kartu Keluarga: Lembaran lebar wajib resolusi cukup
                        if (width < 450 || height < 300) {
                            issues.push(`Resolusi terlalu kecil (${width}x${height} px). Tabel baris anggota keluarga rawan buram.`);
                        }
                    } else if (type === 'akta') {
                        // Akta Kelahiran
                        if (width < 300 || height < 400) {
                            issues.push(`Resolusi terlalu kecil (${width}x${height} px). Teks kutipan akta rawan tidak terbaca.`);
                        }
                    } else if (type === 'transfer') {
                        if (width < 250 || height < 250) {
                            issues.push(`Resolusi bukti transfer terlalu kecil (${width}x${height} px).`);
                        }
                    }

                    // Cek Kegelapan / Gambar Polos Kosong
                    const lumCheck = checkCanvasLuminance(tempImg);
                    if (lumCheck.isTooDark) {
                        issues.push('Foto terdeteksi terlalu gelap / hitam pekat. Dokumen tidak terlihat jelas.');
                    } else if (lumCheck.isTooBlank) {
                        issues.push('Foto terdeteksi polos / kosong.');
                    }

                    if (issues.length === 0) {
                        let successMsg = `Dokumen terverifikasi tajam (${width}x${height} px) dan orientasi sesuai.`;
                        if (type === 'foto') successMsg = `Pas foto terverifikasi: Posisi tegak (3x4) dan background merah terdeteksi.`;
                        setDocStatus(type, true, successMsg, `Resolusi: ${width}x${height} px | Ukuran: ${fileSizeKB} KB`);
                    } else {
                        setDocStatus(type, false, issues.join(' '), `Resolusi: ${width}x${height} px | Ukuran: ${fileSizeKB} KB`);
                    }
                };
                tempImg.src = dataUrl;
            };
            reader.readAsDataURL(file);
        }

        // Helper update badge UI
        function setDocStatus(type, isValid, message, info) {
            docVerificationState[type] = { valid: isValid, message: message };

            const badgeEl = document.getElementById(`status_${type}_badge`);
            const textEl = document.getElementById(`status_${type}_text`);
            const infoEl = document.getElementById(`status_${type}_info`);
            const cardEl = document.getElementById(`card_doc_${type}`);

            if (badgeEl) {
                if (isValid) {
                    badgeEl.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300';
                    badgeEl.innerHTML = `<svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Dokumen Terverifikasi`;
                } else {
                    badgeEl.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300 animate-pulse';
                    badgeEl.innerHTML = `<svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> Perlu Perbaikan`;
                }
            }

            if (textEl) {
                textEl.textContent = message;
                textEl.className = isValid ? 'text-xs text-emerald-800 font-medium' : 'text-xs text-rose-700 font-semibold';
            }

            if (infoEl) {
                infoEl.textContent = info || '';
            }

            if (cardEl) {
                if (isValid) {
                    cardEl.classList.remove('border-rose-300', 'bg-rose-50/40');
                    cardEl.classList.add('border-emerald-300', 'bg-emerald-50/30');
                } else {
                    cardEl.classList.remove('border-emerald-300', 'bg-emerald-50/30');
                    cardEl.classList.add('border-rose-300', 'bg-rose-50/40');
                }
            }
        }

        // Sampling Canvas untuk Background Merah (Pas Foto)
        function checkCanvasRedBackground(img) {
            try {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = 100;
                canvas.height = 133;
                ctx.drawImage(img, 0, 0, 100, 133);

                // Sample titik di sudut atas kiri & kanan
                const samplePoints = [
                    [8, 8], [15, 8], [8, 16], [15, 16],
                    [85, 8], [92, 8], [85, 16], [92, 16],
                    [20, 12], [80, 12]
                ];
                let redCount = 0;
                for (const [x, y] of samplePoints) {
                    const pixel = ctx.getImageData(x, y, 1, 1).data;
                    const r = pixel[0], g = pixel[1], b = pixel[2];
                    if (r >= 95 && r > (g * 1.28) && r > (b * 1.28)) {
                        redCount++;
                    }
                }
                return (redCount / samplePoints.length) >= 0.40;
            } catch (e) {
                return true; // fallback jika canvas dibatasi browser
            }
        }

        // Sampling Canvas untuk Luminance / Kecerahan
        function checkCanvasLuminance(img) {
            try {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = 30;
                canvas.height = 30;
                ctx.drawImage(img, 0, 0, 30, 30);

                const imgData = ctx.getImageData(0, 0, 30, 30).data;
                let totalLum = 0;
                let minLum = 255;
                let maxLum = 0;
                const totalPixels = 30 * 30;

                for (let i = 0; i < imgData.length; i += 4) {
                    const r = imgData[i], g = imgData[i+1], b = imgData[i+2];
                    const lum = (0.299 * r) + (0.587 * g) + (0.114 * b);
                    totalLum += lum;
                    if (lum < minLum) minLum = lum;
                    if (lum > maxLum) maxLum = lum;
                }

                const avgLum = totalLum / totalPixels;
                const variance = maxLum - minLum;

                return {
                    isTooDark: avgLum < 22,
                    isTooBlank: (avgLum > 250 && variance < 15)
                };
            } catch (e) {
                return { isTooDark: false, isTooBlank: false };
            }
        }

        // Validasi Seluruh Dokumen Sebelum Submit Form
        function validateAllDocumentsBeforeSubmit(e) {
            const requiredDocs = [
                { key: 'transfer', name: '1. Bukti Infaq Pendaftaran', id: 'bukti_transfer' },
                { key: 'foto', name: '2. Pas Foto 3x4 Santri', id: 'pas_foto' },
                { key: 'akta', name: '3. Scan/Foto Akta Kelahiran', id: 'file_akta_kelahiran' },
                { key: 'kk', name: '4. Scan/Foto Kartu Keluarga (KK)', id: 'file_kk' },
                { key: 'ktp', name: '5. Scan/Foto KTP Orang Tua', id: 'file_ktp_ortu' }
            ];

            const errors = [];

            // Cek file duplikat antar dokumen (mencegah pas foto diupload ke semua berkas)
            const seenFileSignatures = {};
            for (const doc of requiredDocs) {
                const input = document.getElementById(doc.id);
                if (input && input.files && input.files[0]) {
                    const f = input.files[0];
                    const sig = `${f.name}_${f.size}`;
                    if (seenFileSignatures[sig]) {
                        errors.push(`File "${f.name}" diunggah ganda pada "${seenFileSignatures[sig]}" dan "${doc.name}". Dilarang mengunggah pas foto / file yang sama untuk dokumen berbeda!`);
                    } else {
                        seenFileSignatures[sig] = doc.name;
                    }
                }
            }

            for (const doc of requiredDocs) {
                const input = document.getElementById(doc.id);
                if (!input || !input.files || input.files.length === 0) {
                    errors.push(`${doc.name}: Belum ada file yang diunggah.`);
                } else if (docVerificationState[doc.key] && !docVerificationState[doc.key].valid) {
                    errors.push(`${doc.name}: ${docVerificationState[doc.key].message}`);
                }
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert("MOHON PERIKSA KELENGKAPAN & KUALITAS DOKUMEN ANDA:\n\nBeberapa dokumen belum memenuhi syarat atau terindikasi tidak valid:\n\n• " + errors.join("\n• ") + "\n\nSilakan perbaiki file yang bertanda merah sebelum mengirim formulir.");
                goToStep(4);
                return false;
            }

            // Tampilkan loading button jika semua valid
            const btnSubmit = document.getElementById('btnSubmitForm');
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = `<svg class="animate-spin w-4 h-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memverifikasi & Menyimpan Berkas...`;
            }
            return true;
        }

        function copyRek(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const span = btn.querySelector('span');
                const oldText = span.textContent;
                span.textContent = 'Tersalin!';
                btn.classList.add('bg-emerald-500', 'text-white');
                setTimeout(() => {
                    span.textContent = oldText;
                    btn.classList.remove('bg-emerald-500', 'text-white');
                }, 2000);
            }).catch(err => {
                prompt('Salin nomor rekening:', text);
            });
        }

        function checkBansosSelected() {
            // Optional visual highlight when bansos is checked
        }

        // Initialize state on load
        document.addEventListener('DOMContentLoaded', () => {
            const curJalur = document.getElementById('jalurSelect').value;
            toggleJalurBerkas(curJalur);
        });
    </script>
</body>
</html>

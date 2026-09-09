<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran Santri Baru TA 2025/2026 — Hidayatullah Tuksongo</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts: Plus Jakarta Sans & EB Garamond -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=EB+Garamond:ital,wght@0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"EB Garamond"', 'serif'],
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

    <!-- TOP HEADER -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-18 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="/logo.png" alt="Logo Ponpes" class="w-10 h-10 object-contain group-hover:scale-105 transition">
                <div>
                    <strong class="font-bold text-slate-900 text-sm sm:text-base leading-tight block">Hidayatullah Tuksongo</strong>
                    <span class="text-xs text-pondok-700 font-medium">Pringsurat Temanggung</span>
                </div>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-500 hover:text-pondok-800 transition flex items-center gap-1.5">
                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span class="hidden sm:inline">Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-1 py-8 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto space-y-6">

            <!-- Title & Hero Banner -->
            <div class="bg-gradient-to-br from-pondok-900 to-pondok-800 rounded-2xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
                <div class="relative z-10 space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-xs text-xs font-semibold text-gold-300 border border-white/20">
                        <span class="w-2 h-2 rounded-full bg-gold-400 animate-pulse"></span>
                        TAHUN AJARAN 2025/2026
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
            <form id="psbForm" action="{{ route('psb.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                @csrf

                <!-- ==================== STEP 1: JALUR & JENJANG PENDIDIKAN ==================== -->
                <div id="step1" class="step-pane active p-6 sm:p-8 space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">PILIHAN JALUR & JENJANG PENDIDIKAN</h2>
                        <p class="text-xs text-slate-500">Pilih program pendidikan dan jalur penerimaan santri baru sesuai kualifikasi.</p>
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
                                <option value="MTs Mukim" {{ old('jenjang') == 'MTs Mukim' ? 'selected' : '' }}>MTs (SMP Islam) — Mukim (Tinggal di Asrama Pesantren)</option>
                                <option value="MTs Laju" {{ old('jenjang') == 'MTs Laju' ? 'selected' : '' }}>MTs (SMP Islam) — Laju (Pulang-Pergi / Non-Asrama)</option>
                                <option value="MA Mukim" {{ old('jenjang') == 'MA Mukim' ? 'selected' : '' }}>MA (SMA Islam) — Mukim (Tinggal di Asrama Pesantren)</option>
                                <option value="MA Laju" {{ old('jenjang') == 'MA Laju' ? 'selected' : '' }}>MA (SMA Islam) — Laju (Pulang-Pergi / Non-Asrama)</option>
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
                    <div class="pt-4 flex items-center justify-between">
                        <button type="button" onclick="goToStep(1)" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition">
                            ← Kembali
                        </button>
                        <button type="button" onclick="validateAndGoStep(2, 3)" class="px-6 py-3 bg-pondok-700 hover:bg-pondok-800 text-white font-semibold rounded-xl text-sm transition shadow-md flex items-center gap-2">
                            Lanjut: Sekolah & Orang Tua
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
                    <div class="pt-4 flex items-center justify-between">
                        <button type="button" onclick="goToStep(2)" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition">
                            ← Kembali
                        </button>
                        <button type="button" onclick="validateAndGoStep(3, 4)" class="px-6 py-3 bg-pondok-700 hover:bg-pondok-800 text-white font-semibold rounded-xl text-sm transition shadow-md flex items-center gap-2">
                            Lanjut: Pembayaran & Upload Berkas
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
                                    <button type="button" onclick="copyRek('010201022009537', this)" class="text-[11px] bg-white/20 hover:bg-white/30 text-gold-300 px-2.5 py-1 rounded-md font-semibold transition flex items-center gap-1">
                                        <svg class="icon-svg w-3 h-3" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                        <span>Salin No. Rek</span>
                                    </button>
                                </div>
                                <div>
                                    <span class="text-lg font-mono font-bold tracking-wider text-white block">0102-01-022009-53-7</span>
                                    <span class="text-xs text-slate-300 block">Atas Nama: <strong>Diky Fachri Husein</strong> (Panitia PSB)</span>
                                </div>
                            </div>

                            <!-- Rekening BCA -->
                            <div class="bg-white/10 backdrop-blur-xs border border-white/20 p-4 rounded-xl space-y-2 hover:bg-white/15 transition">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 rounded bg-indigo-600 text-white text-[11px] font-bold">BANK BCA</span>
                                    <button type="button" onclick="copyRek('1221220167', this)" class="text-[11px] bg-white/20 hover:bg-white/30 text-gold-300 px-2.5 py-1 rounded-md font-semibold transition flex items-center gap-1">
                                        <svg class="icon-svg w-3 h-3" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                        <span>Salin No. Rek</span>
                                    </button>
                                </div>
                                <div>
                                    <span class="text-lg font-mono font-bold tracking-wider text-white block">1221220167</span>
                                    <span class="text-xs text-slate-300 block">Atas Nama: <strong>Diky Fachri Husein</strong> (Panitia PSB)</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-300/90 pt-1 flex items-center justify-between flex-wrap gap-2">
                            <span>* Pembayaran tunai dapat dilakukan di Sekretariat PSB Pondok (Kampus 2) dan difoto nota kwitansinya.</span>
                            <span class="text-gold-300 font-medium">CS Konfirmasi WA: 0852-9042-9617</span>
                        </div>
                    </div>

                    <!-- DOKUMEN 1: UNGGAH BUKTI INFAQ PENDAFTARAN -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <label for="bukti_transfer" class="block text-sm font-bold text-slate-800">
                                1. Unggah Bukti Infaq Pendaftaran (Rp 200.000) <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Wajib Upload</span>
                        </div>
                        <p class="text-xs text-slate-500">
                            Upload struk transfer Bank BRI/BCA atau nota kwitansi pembayaran tunai. Format gambar (JPG, PNG, WEBP) atau PDF maks 10 MB.
                        </p>
                        <input type="file" id="bukti_transfer" name="bukti_transfer" accept="image/*,application/pdf" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pondok-600 file:text-white hover:file:bg-pondok-700 file:cursor-pointer border border-slate-200 bg-white rounded-xl p-1.5">
                    </div>

                    <!-- DOKUMEN 2: UNGGAH PAS FOTO 3X4 CALON SANTRI -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
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
                                <input type="file" id="pas_foto" name="pas_foto" accept="image/*" required onchange="previewFoto(event)" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pondok-600 file:text-white hover:file:bg-pondok-700 file:cursor-pointer border border-slate-200 bg-white rounded-xl p-1.5">
                                <span class="text-[11px] text-slate-400 block mt-1">Format gambar didukung: JPG, PNG, WEBP. Maks 10 MB.</span>
                            </div>
                            <div id="fotoPreviewBox" class="hidden w-20 h-26 bg-red-600 rounded-lg overflow-hidden border-2 border-red-500 shadow-sm shrink-0 text-center">
                                <img id="fotoPreviewImg" src="" alt="Preview 3x4" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <!-- DOKUMEN 3: KONDISIONAL BERKAS JALUR PRESTASI / TAHFIDZ -->
                    <div id="berkasKhususCard" class="hidden p-5 bg-amber-50/70 rounded-2xl border-2 border-amber-300 space-y-3">
                        <div class="flex items-center gap-2 text-amber-900 font-bold text-sm">
                            <svg class="icon-svg w-4 h-4 text-amber-600" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                            <span id="berkasKhususTitle">3. UNGGAH BUKTI JALUR PRESTASI / TAHFIDZ</span>
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

                    <!-- DOKUMEN 4: BUKTI BANTUAN SOSIAL (JIKA MEMILIKI) -->
                    <div id="berkasBansosCard" class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="bukti_bantuan_sosial" class="block text-sm font-bold text-slate-800">
                                4. Unggah Bukti Bantuan Sosial (KIP / PKH / KKS / KIS)
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

                        <div class="flex items-center justify-between pt-2">
                            <button type="button" onclick="goToStep(3)" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition">
                                ← Kembali
                            </button>
                            <button type="submit" id="btnSubmitForm" class="px-8 py-3.5 bg-pondok-700 hover:bg-pondok-800 text-white font-bold rounded-xl text-sm transition shadow-lg flex items-center gap-2">
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
    <footer class="py-6 border-t border-slate-200 bg-white text-center text-xs text-slate-500">
        <p>© 2025 Pondok Pesantren Hidayatullah Tuksongo Pringsurat Temanggung. Hotline Panitia PSB: 0852-9042-9617</p>
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
                title.textContent = '3. UNGGAH BUKTI PRESTASI KEJUARAAN';
                desc.textContent = 'Silakan upload bukti sertifikat prestasi minimal tingkat kecamatan (Juara 1, 2, atau 3).';
                fileInput.required = true;
            } else if (jalur === 'Tahfidz') {
                card.classList.remove('hidden');
                title.textContent = '3. UNGGAH SYAHADAH HAFALAN TAHFIDZ';
                desc.textContent = 'Silakan upload Syahadah / Piagam hafalan Al-Qur\'an dari madrasah atau halaqah tahfidz sebelumnya.';
                fileInput.required = true;
            } else {
                // Reguler
                card.classList.add('hidden');
                fileInput.required = false;
                fileInput.value = '';
            }
        }

        function previewFoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('fotoPreviewImg').src = e.target.result;
                    document.getElementById('fotoPreviewBox').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
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

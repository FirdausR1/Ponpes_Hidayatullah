<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biodata & Dokumen Santri — {{ $student->nama_lengkap }} (NIS: {{ $student->nis }})</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        pondok: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#0d3b1e',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-slate-50 flex flex-col justify-between font-sans antialiased text-slate-800">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 px-3 sm:px-6 lg:px-8 py-2.5 sm:py-3">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-2 sm:gap-4">
            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                <a href="{{ route('santri.dashboard') }}" class="shrink-0">
                    <img src="/logo.png" alt="Logo" class="w-8 h-8 sm:w-9 sm:h-9 object-contain">
                </a>
                <div>
                    <h2 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight truncate max-w-[150px] sm:max-w-none">Pesantren Hidayatullah</h2>
                    <span class="text-[10px] sm:text-[11px] text-emerald-700 font-medium">Portal Santri</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto py-1">
                <a href="{{ route('santri.dashboard') }}" class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 text-xs font-semibold whitespace-nowrap transition">
                    Dasbor
                </a>
                <a href="{{ route('santri.cbt.index') }}" class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 text-xs font-semibold whitespace-nowrap transition flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Ujian CBT
                </a>
                <a href="{{ route('santri.profil') }}" class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-emerald-700 text-white text-xs font-semibold whitespace-nowrap shadow-xs">
                    Biodata &amp; Berkas
                </a>
                <a href="{{ route('santri.pembayaran') }}" class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 text-xs font-semibold whitespace-nowrap transition">
                    Tagihan
                </a>
                <form action="{{ route('santri.logout') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 text-xs font-semibold text-slate-600 whitespace-nowrap transition cursor-pointer">
                        Keluar
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-5xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-5 sm:space-y-6">

        <!-- Breadcrumb & Header Title -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-200">
            <div>
                <a href="{{ route('santri.dashboard') }}" class="text-xs font-semibold text-emerald-700 hover:underline inline-flex items-center gap-1 mb-1">
                    &larr; Kembali ke Dasbor
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Kelola Biodata &amp; Dokumen Santri</h1>
                <p class="text-xs text-slate-500 mt-0.5">Lengkapi data diri, data keluarga, serta berkas resmi (KK, KTP, Akta Kelahiran) secara mandiri.</p>
            </div>
            
            <div class="shrink-0 flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 shadow-xs">
                    Kelas: <strong class="ml-1 text-emerald-700">{{ $student->kelas }}</strong> ({{ $student->jenjang }})
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="p-3.5 sm:p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-3.5 sm:p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs space-y-1">
                <div class="font-bold">Mohon periksa kembali formulir:</div>
                @foreach($errors->all() as $err)
                    <p>• {{ $err }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('santri.profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 sm:space-y-6">
            @csrf

            <!-- Section 1: Dokumen Berkas Digital (KK, KTP, Akta) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-6 shadow-xs space-y-4 sm:space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-sm font-bold text-slate-900">1. Unggah Berkas Dokumen Resmi</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Unggah foto atau scan dokumen asli (format PDF, JPG, PNG maks 10MB).</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Kartu Keluarga (KK) -->
                    <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 space-y-3 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-xs font-bold text-slate-800">Kartu Keluarga (KK)</label>
                                @if($student->berkas_kk)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Sudah Ada
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-200 text-slate-600">
                                        Belum Ada
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1">No. KK: <span class="font-mono font-medium text-slate-700">{{ $student->nomor_kk ?: '—' }}</span></p>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-slate-200/60">
                            @if($student->berkas_kk)
                                <a href="{{ asset($student->berkas_kk) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-emerald-300 text-emerald-800 hover:bg-emerald-50 text-xs font-semibold transition">
                                    <span>Lihat File KK</span>
                                    <span class="text-[10px]">&rarr;</span>
                                </a>
                            @endif

                            <label class="block cursor-pointer">
                                <span class="text-[11px] font-semibold text-slate-600 block mb-1">
                                    {{ $student->berkas_kk ? 'Ganti File KK:' : 'Pilih File KK:' }}
                                </span>
                                <div class="flex items-center justify-between gap-2 p-2 rounded-xl border border-slate-200 hover:border-emerald-600 bg-white transition group">
                                    <span class="text-xs text-slate-500 truncate" id="label-kk">Format PDF / JPG / PNG</span>
                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 group-hover:bg-emerald-700 group-hover:text-white group-hover:border-emerald-700 text-[11px] font-semibold transition shrink-0">
                                        Pilih File
                                    </span>
                                </div>
                                <input type="file" name="file_kk" accept="image/*,application/pdf" class="hidden" onchange="document.getElementById('label-kk').textContent = this.files[0] ? this.files[0].name : 'Format PDF / JPG / PNG'">
                            </label>
                        </div>
                    </div>

                    <!-- KTP Orang Tua / Wali -->
                    <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 space-y-3 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-xs font-bold text-slate-800">KTP Orang Tua / Wali</label>
                                @if($student->berkas_ktp_ortu)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Sudah Ada
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-200 text-slate-600">
                                        Belum Ada
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1">Scan/Foto KTP Ayah, Ibu, atau Wali</p>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-slate-200/60">
                            @if($student->berkas_ktp_ortu)
                                <a href="{{ asset($student->berkas_ktp_ortu) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-emerald-300 text-emerald-800 hover:bg-emerald-50 text-xs font-semibold transition">
                                    <span>Lihat File KTP</span>
                                    <span class="text-[10px]">&rarr;</span>
                                </a>
                            @endif

                            <label class="block cursor-pointer">
                                <span class="text-[11px] font-semibold text-slate-600 block mb-1">
                                    {{ $student->berkas_ktp_ortu ? 'Ganti File KTP:' : 'Pilih File KTP:' }}
                                </span>
                                <div class="flex items-center justify-between gap-2 p-2 rounded-xl border border-slate-200 hover:border-emerald-600 bg-white transition group">
                                    <span class="text-xs text-slate-500 truncate" id="label-ktp">Format PDF / JPG / PNG</span>
                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 group-hover:bg-emerald-700 group-hover:text-white group-hover:border-emerald-700 text-[11px] font-semibold transition shrink-0">
                                        Pilih File
                                    </span>
                                </div>
                                <input type="file" name="file_ktp_ortu" accept="image/*,application/pdf" class="hidden" onchange="document.getElementById('label-ktp').textContent = this.files[0] ? this.files[0].name : 'Format PDF / JPG / PNG'">
                            </label>
                        </div>
                    </div>

                    <!-- Akta Kelahiran -->
                    <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 space-y-3 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-xs font-bold text-slate-800">Akta Kelahiran Santri</label>
                                @if($student->berkas_akta)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Sudah Ada
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-200 text-slate-600">
                                        Belum Ada
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1">NIK: <span class="font-mono font-medium text-slate-700">{{ $student->nik ?: '—' }}</span></p>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-slate-200/60">
                            @if($student->berkas_akta)
                                <a href="{{ asset($student->berkas_akta) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-emerald-300 text-emerald-800 hover:bg-emerald-50 text-xs font-semibold transition">
                                    <span>Lihat File Akta</span>
                                    <span class="text-[10px]">&rarr;</span>
                                </a>
                            @endif

                            <label class="block cursor-pointer">
                                <span class="text-[11px] font-semibold text-slate-600 block mb-1">
                                    {{ $student->berkas_akta ? 'Ganti File Akta:' : 'Pilih File Akta:' }}
                                </span>
                                <div class="flex items-center justify-between gap-2 p-2 rounded-xl border border-slate-200 hover:border-emerald-600 bg-white transition group">
                                    <span class="text-xs text-slate-500 truncate" id="label-akta">Format PDF / JPG / PNG</span>
                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 group-hover:bg-emerald-700 group-hover:text-white group-hover:border-emerald-700 text-[11px] font-semibold transition shrink-0">
                                        Pilih File
                                    </span>
                                </div>
                                <input type="file" name="file_akta_kelahiran" accept="image/*,application/pdf" class="hidden" onchange="document.getElementById('label-akta').textContent = this.files[0] ? this.files[0].name : 'Format PDF / JPG / PNG'">
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Pas Foto Upload -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-4 bg-slate-50/70 p-4 rounded-xl">
                    <div class="w-14 h-18 rounded-lg overflow-hidden bg-slate-200 border border-slate-300 shrink-0 flex items-center justify-center shadow-xs">
                        @if($student->foto)
                            <img src="{{ asset($student->foto) }}" alt="Foto Santri" class="w-full h-full object-cover">
                        @else
                            <span class="text-slate-400 font-bold text-[10px]">No Foto</span>
                        @endif
                    </div>
                    <div class="flex-1 space-y-1.5 text-center sm:text-left w-full">
                        <label class="block text-xs font-bold text-slate-800">Pas Foto Santri (Format 3x4 Berbusana Muslim)</label>
                        <p class="text-[11px] text-slate-500">Maksimal ukuran 5 MB (format JPG, PNG, WEBP).</p>
                        <label class="inline-block cursor-pointer">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white border border-slate-300 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 text-slate-700 text-xs font-semibold transition">
                                <span id="label-foto">Pilih Foto 3x4</span>
                            </span>
                            <input type="file" name="foto_file" accept="image/*" class="hidden" onchange="document.getElementById('label-foto').textContent = this.files[0] ? this.files[0].name : 'Pilih Foto 3x4'">
                        </label>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Pokok Akademik (Read-Only) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-6 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wider">2. Data Pokok Pesantren (Terkunci)</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Untuk perubahan data akademik, silakan menghubungi administrator pondok.</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 sm:gap-3 text-xs">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Nama Lengkap</span>
                        <span class="font-bold text-slate-900">{{ $student->nama_lengkap }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Nomor Induk Santri (NIS)</span>
                        <span class="font-mono font-bold text-slate-800">{{ $student->nis }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Jenis Kelamin</span>
                        <span class="font-medium text-slate-800">{{ $student->jenis_kelamin }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Jenjang Pendidikan</span>
                        <span class="font-medium text-slate-800">{{ $student->jenjang }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Tingkat Kelas</span>
                        <span class="font-bold text-emerald-700">Kelas {{ $student->kelas }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Kamar Asrama</span>
                        <span class="font-medium text-slate-800">{{ $student->kamar_asrama ?: 'Non-Asrama' }}</span>
                    </div>
                </div>
            </div>

            <!-- Section 3: Data Pribadi Santri -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-6 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wider">3. Identitas Diri Lengkap</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Pastikan NIK dan Nomor KK sesuai dengan dokumen resmi.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK Santri (16 Digit)</label>
                        <input type="text" name="nik" value="{{ old('nik', $student->nik) }}" placeholder="Nomor NIK Santri" class="w-full h-10 px-3 text-xs rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 font-mono text-slate-900 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Kartu Keluarga (KK)</label>
                        <input type="text" name="nomor_kk" value="{{ old('nomor_kk', $student->nomor_kk) }}" placeholder="16 Digit Nomor KK" class="w-full h-10 px-3 text-xs rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 font-mono text-slate-900 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp Aktif</label>
                        <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $student->no_whatsapp) }}" placeholder="Contoh: 081234567890" class="w-full h-10 px-3 text-xs rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 font-mono text-slate-900 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $student->tempat_lahir) }}" placeholder="Kota / Kabupaten Lahir" class="w-full h-10 px-3 text-xs rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 text-slate-900 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $student->tanggal_lahir) }}" class="w-full h-10 px-3 text-xs rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 text-slate-900 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Golongan Darah</label>
                        <select name="golongan_darah" class="w-full h-10 px-3 text-xs rounded-xl border border-slate-300 bg-white focus:border-emerald-600 text-slate-900 outline-none transition">
                            <option value="">-- Pilih --</option>
                            <option value="A" {{ old('golongan_darah', $student->golongan_darah) === 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('golongan_darah', $student->golongan_darah) === 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ old('golongan_darah', $student->golongan_darah) === 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ old('golongan_darah', $student->golongan_darah) === 'O' ? 'selected' : '' }}>O</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Anak Ke-</label>
                        <input type="number" name="anak_ke" value="{{ old('anak_ke', $student->anak_ke) }}" min="1" class="w-full h-10 px-3 text-xs rounded-xl border border-slate-300 focus:border-emerald-600 text-slate-900 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Saudara</label>
                        <input type="number" name="jumlah_saudara" value="{{ old('jumlah_saudara', $student->jumlah_saudara) }}" min="0" class="w-full h-10 px-3 text-xs rounded-xl border border-slate-300 focus:border-emerald-600 text-slate-900 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Hobi / Minat</label>
                        <input type="text" name="hobi" value="{{ old('hobi', $student->hobi) }}" placeholder="Contoh: Tilawah, Kaligrafi" class="w-full h-10 px-3 text-xs rounded-xl border border-slate-300 focus:border-emerald-600 text-slate-900 outline-none transition">
                    </div>

                    <div class="sm:col-span-2 md:col-span-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Riwayat Sakit / Alergi (Jika Ada)</label>
                        <input type="text" name="riwayat_penyakit" value="{{ old('riwayat_penyakit', $student->riwayat_penyakit) }}" placeholder="Kosongkan jika tidak ada keluhan kesehatan" class="w-full h-10 px-3 text-xs rounded-xl border border-slate-300 focus:border-emerald-600 text-slate-900 outline-none transition">
                    </div>

                    <div class="sm:col-span-2 md:col-span-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap Domisili</label>
                        <textarea name="alamat_lengkap" rows="2" placeholder="Dusun/Jalan, RT/RW, Desa/Kelurahan, Kecamatan, Kab/Kota, Provinsi" class="w-full p-3 text-xs rounded-xl border border-slate-300 focus:border-emerald-600 text-slate-900 outline-none transition">{{ old('alamat_lengkap', $student->alamat_lengkap ?: $student->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 4: Data Orang Tua & Wali -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-6 shadow-xs space-y-4 sm:space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wider">4. Data Orang Tua &amp; Wali</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Informasi orang tua kandung atau wali santri.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                    <!-- Ayah Kandung -->
                    <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1.5">
                            Data Ayah Kandung
                        </h4>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Ayah</label>
                            <input type="text" name="ayah_nama" value="{{ old('ayah_nama', $student->ayah_nama) }}" placeholder="Nama sesuai KTP" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 focus:border-emerald-600 bg-white outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">NIK Ayah</label>
                            <input type="text" name="ayah_nik" value="{{ old('ayah_nik', $student->ayah_nik) }}" placeholder="16 Digit NIK Ayah" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 font-mono focus:border-emerald-600 bg-white outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nomor Telepon / WA Ayah</label>
                            <input type="text" name="ayah_telepon" value="{{ old('ayah_telepon', $student->ayah_telepon) }}" placeholder="08xxxxxxxxxx" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 font-mono focus:border-emerald-600 bg-white outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pekerjaan</label>
                                <input type="text" name="ayah_pekerjaan" value="{{ old('ayah_pekerjaan', $student->ayah_pekerjaan) }}" placeholder="Pekerjaan ayah" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 focus:border-emerald-600 bg-white outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Penghasilan / Bulan</label>
                                <input type="text" name="ayah_penghasilan" value="{{ old('ayah_penghasilan', $student->ayah_penghasilan) }}" placeholder="Kisaran pendapatan" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 focus:border-emerald-600 bg-white outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Ibu Kandung -->
                    <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1.5">
                            Data Ibu Kandung
                        </h4>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Ibu</label>
                            <input type="text" name="ibu_nama" value="{{ old('ibu_nama', $student->ibu_nama) }}" placeholder="Nama sesuai KTP" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 focus:border-emerald-600 bg-white outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">NIK Ibu</label>
                            <input type="text" name="ibu_nik" value="{{ old('ibu_nik', $student->ibu_nik) }}" placeholder="16 Digit NIK Ibu" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 font-mono focus:border-emerald-600 bg-white outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nomor Telepon / WA Ibu</label>
                            <input type="text" name="ibu_telepon" value="{{ old('ibu_telepon', $student->ibu_telepon) }}" placeholder="08xxxxxxxxxx" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 font-mono focus:border-emerald-600 bg-white outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pekerjaan</label>
                                <input type="text" name="ibu_pekerjaan" value="{{ old('ibu_pekerjaan', $student->ibu_pekerjaan) }}" placeholder="Pekerjaan ibu" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 focus:border-emerald-600 bg-white outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Penghasilan / Bulan</label>
                                <input type="text" name="ibu_penghasilan" value="{{ old('ibu_penghasilan', $student->ibu_penghasilan) }}" placeholder="Kisaran pendapatan" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 focus:border-emerald-600 bg-white outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wali Santri -->
                <div class="p-4 rounded-xl bg-slate-50/50 border border-slate-200 space-y-3">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider pb-1 border-b border-slate-200">
                        Wali Santri (Jika ada)
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Wali</label>
                            <input type="text" name="nama_wali" value="{{ old('nama_wali', $student->nama_wali) }}" placeholder="Nama wali santri" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 focus:border-emerald-600 bg-white outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Hubungan Keluarga</label>
                            <input type="text" name="wali_hubungan" value="{{ old('wali_hubungan', $student->wali_hubungan) }}" placeholder="Paman, Kakek, dll" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 focus:border-emerald-600 bg-white outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nomor Telepon Wali</label>
                            <input type="text" name="wali_telepon" value="{{ old('wali_telepon', $student->wali_telepon) }}" placeholder="08xxxxxxxxxx" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 font-mono focus:border-emerald-600 bg-white outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs flex flex-col-reverse sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('santri.dashboard') }}" class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-xs font-semibold text-slate-700 transition">
                    &larr; Batal &amp; Kembali
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs sm:text-sm font-bold shadow-xs transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan &amp; Perbarui Data</span>
                </button>
            </div>
        </form>

    </main>

    <!-- Footer -->
    <footer class="mt-8 border-t border-slate-200 bg-white py-5 text-center text-xs text-slate-500">
        <div class="max-w-6xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Pondok Pesantren Hidayatullah Tuksongo Pringsurat Temanggung.</p>
        </div>
    </footer>

</body>
</html>

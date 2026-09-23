<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Santri — {{ $student->nama_lengkap }} (NIS: {{ $student->nis }})</title>
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
                <a href="{{ route('santri.dashboard') }}" class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-emerald-700 text-white text-xs font-semibold whitespace-nowrap shadow-xs">
                    Dasbor
                </a>
                <a href="{{ route('santri.profil') }}" class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 text-xs font-semibold whitespace-nowrap transition">
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

    <!-- Main Content -->
    <main class="flex-1 max-w-6xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-5 sm:space-y-6">

        @if(session('success'))
            <div class="p-3.5 sm:p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-3.5 sm:p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs space-y-1">
                @foreach($errors->all() as $err)
                    <p>• {{ $err }}</p>
                @endforeach
            </div>
        @endif

        <!-- Welcome Banner -->
        <div class="rounded-2xl bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 p-5 sm:p-7 text-white shadow-xs relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-medium text-emerald-200">
                        <span>Santri {{ $student->status }}</span>
                        <span>•</span>
                        <span>Angkatan {{ $student->tahun_masuk }}</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Ahlan wa Sahlan, {{ $student->nama_lengkap }}!</h1>
                    <p class="text-xs sm:text-sm text-emerald-100/90 max-w-xl leading-relaxed">
                        Portal Santri Mandiri Pondok Pesantren Hidayatullah Tuksongo. Pantau informasi tagihan, riwayat pembayaran, serta kelengkapan dokumen resmi Anda.
                    </p>
                </div>
                <div class="shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full md:w-auto">
                    <a href="{{ route('santri.pembayaran') }}" class="text-center px-4 py-2.5 rounded-xl bg-white text-emerald-800 hover:bg-emerald-50 text-xs font-bold shadow-xs transition">
                        Menu Tagihan &amp; Bayar
                    </a>
                    <button onclick="window.print()" class="text-center px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold border border-white/20 transition cursor-pointer">
                        Cetak Kartu Santri
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Financial Summary Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Informasi Keuangan &amp; Tagihan</h3>
                    @if($totalTunggakan > 0)
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            Ada Tunggakan
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Lunas
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500">
                    Total: <span class="font-semibold text-slate-800">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span> &bull;
                    Terbayar: <span class="font-semibold text-emerald-700">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</span> &bull;
                    Sisa: <span class="font-semibold {{ $totalTunggakan > 0 ? 'text-rose-600' : 'text-emerald-700' }}">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</span>
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('santri.pembayaran') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs transition">
                    Rincian Tagihan &rarr;
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">
            
            <!-- Left Column: Kartu Santri & Berkas -->
            <div class="lg:col-span-2 space-y-5 sm:space-y-6">

                @php
                    $docCount = 0;
                    if ($student->berkas_kk) $docCount++;
                    if ($student->berkas_ktp_ortu) $docCount++;
                    if ($student->berkas_akta) $docCount++;
                @endphp

                <!-- Status Berkas Dokumen Card (Clean Green & White Style) -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <h3 class="text-xs sm:text-sm font-bold text-slate-900">Arsip Berkas Dokumen (KK, KTP, Akta)</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $docCount === 3 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                {{ $docCount }}/3 Dokumen
                            </span>
                        </div>
                        <p class="text-xs text-slate-500">
                            @if($docCount === 3)
                                Seluruh berkas resmi (Kartu Keluarga, KTP Orang Tua, dan Akta Kelahiran) telah lengkap tersimpan.
                            @else
                                Lengkapi dokumen Kartu Keluarga, KTP Orang Tua, dan Akta Kelahiran untuk arsip pondok.
                            @endif
                        </p>
                    </div>
                    <div class="shrink-0">
                        <a href="{{ route('santri.profil') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 rounded-xl {{ $docCount === 3 ? 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' : 'bg-emerald-700 hover:bg-emerald-800 text-white' }} text-xs font-semibold shadow-xs transition">
                            {{ $docCount === 3 ? 'Lihat Berkas' : 'Kelola Dokumen' }} &rarr;
                        </a>
                    </div>
                </div>
                
                <!-- Official Digital Student ID Card -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5 sm:gap-3">
                            <img src="/logo.png" alt="Logo" class="w-8 h-8 sm:w-9 sm:h-9 object-contain">
                            <div>
                                <h3 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wider">Kartu Identitas Santri</h3>
                                <p class="text-[10px] sm:text-[11px] text-slate-400">Pondok Pesantren Hidayatullah Tuksongo Pringsurat</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md text-[10px] sm:text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                            {{ $student->status }}
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 items-center sm:items-start">
                        <!-- Foto Santri -->
                        <div class="w-24 h-32 sm:w-28 sm:h-36 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center shadow-xs">
                            @if($student->foto)
                                <img src="{{ asset($student->foto) }}" alt="{{ $student->nama_lengkap }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-emerald-800 text-white flex flex-col items-center justify-center font-bold text-2xl">
                                    {{ strtoupper(substr($student->nama_lengkap, 0, 1)) }}
                                    <span class="text-[10px] uppercase font-normal tracking-wider mt-1 opacity-80">Santri</span>
                                </div>
                            @endif
                        </div>

                        <!-- Biodata Detail -->
                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs w-full">
                            <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-100">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Nomor Induk Santri (NIS)</span>
                                <span class="text-xs font-mono font-bold text-slate-800">{{ $student->nis }}</span>
                            </div>

                            <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-100">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Username Login</span>
                                <span class="text-xs font-mono font-bold text-emerald-700">{{ $student->username ?: $student->nis }}</span>
                            </div>

                            <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-100">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Kelas Saat Ini</span>
                                <span class="text-xs font-bold text-slate-800">Kelas {{ $student->kelas }}</span>
                            </div>

                            <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-100">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Jenjang Pendidikan</span>
                                <span class="text-xs font-semibold text-slate-800">{{ $student->jenjang }}</span>
                            </div>

                            <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-100">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Tahun Masuk</span>
                                <span class="text-xs font-semibold text-slate-800">{{ $student->tahun_masuk }}</span>
                            </div>

                            <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-100">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Kamar Asrama</span>
                                <span class="text-xs font-semibold text-slate-800">{{ $student->kamar_asrama ?: 'Non-Asrama' }}</span>
                            </div>

                            <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-100 sm:col-span-2">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Wali Santri &amp; Kontak</span>
                                <span class="text-xs font-semibold text-slate-800">{{ $student->nama_wali ?: '-' }} ({{ $student->no_whatsapp ?: 'Belum terdata' }})</span>
                            </div>

                            <div class="p-2.5 bg-emerald-50/60 rounded-lg border border-emerald-100 sm:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <span class="text-emerald-800 uppercase tracking-wider font-semibold text-[10px] block">Wali Kelas</span>
                                    <span class="text-xs font-bold text-slate-900">{{ $student->classroom?->wali_kelas ?: 'Belum ditentukan' }}</span>
                                </div>
                                @if($student->classroom?->whatsapp_url)
                                    <a href="{{ $student->classroom->whatsapp_url }}" target="_blank" class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-semibold transition shrink-0">
                                        Hubungi Wali Kelas
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pembelajaran & Asrama -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs space-y-3 text-xs">
                    <h3 class="font-bold text-slate-800 text-xs sm:text-sm uppercase tracking-wider border-b border-slate-100 pb-2">
                        Jadwal &amp; Pembinaan Santri
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-slate-600">
                        <div class="p-3 rounded-lg border border-slate-200 bg-slate-50/60">
                            <span class="font-bold text-slate-900 block">Pagi (Ba'da Shubuh):</span>
                            <p class="mt-0.5 text-[11px] text-slate-500">Halaqah Tahfidzul Qur'an mutqin &amp; penambahan mufrodat bahasa.</p>
                        </div>
                        <div class="p-3 rounded-lg border border-slate-200 bg-slate-50/60">
                            <span class="font-bold text-slate-900 block">Siang (07:00 - 13:30):</span>
                            <p class="mt-0.5 text-[11px] text-slate-500">Kegiatan Belajar Mengajar (KBM) Madrasah Formal &amp; Kitab Kuning.</p>
                        </div>
                        <div class="p-3 rounded-lg border border-slate-200 bg-slate-50/60">
                            <span class="font-bold text-slate-900 block">Sore (Ba'da Ashar):</span>
                            <p class="mt-0.5 text-[11px] text-slate-500">Kajian Fiqih Ibadah, ekstrakurikuler kepanduan/silat, dan olahraga mandiri.</p>
                        </div>
                        <div class="p-3 rounded-lg border border-slate-200 bg-slate-50/60">
                            <span class="font-bold text-slate-900 block">Malam (Ba'da Maghrib):</span>
                            <p class="mt-0.5 text-[11px] text-slate-500">Mudarosah Al-Qur'an, muhadhoroh pidato 3 bahasa, dan belajar terbimbing.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Keamanan & Ganti Password -->
            <div class="space-y-5 sm:space-y-6">
                
                <!-- Ganti Password Card -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-slate-800 text-sm">Ganti Kata Sandi Akun</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Ubah kata sandi awal menjadi sandi pribadi yang aman.</p>
                    </div>

                    <form action="{{ route('santri.updatePassword') }}" method="POST" class="space-y-3.5 text-xs">
                        @csrf

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                            <input type="password" name="password_lama" required placeholder="Sandi lama / tanggal lahir (DDMMYYYY)"
                                   class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kata Sandi Baru</label>
                            <input type="password" name="password_baru" required minlength="6" placeholder="Minimal 6 karakter"
                                   class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_baru_confirmation" required minlength="6" placeholder="Ketik ulang sandi baru"
                                   class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none">
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                            Simpan Kata Sandi Baru
                        </button>
                    </form>
                </div>

                <!-- Bantuan / Kontak Admin Card -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs space-y-2 text-xs">
                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Butuh Bantuan?</h4>
                    <p class="text-slate-500 text-[11px] leading-relaxed">
                        Jika terdapat kendala pada data akademik atau tagihan, silakan menghubungi Sekretariat Pondok Pesantren Hidayatullah.
                    </p>
                    <div class="pt-2">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('kontak_wa', '081391109966')) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg border border-slate-200 text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 font-semibold text-xs transition">
                            Hubungi Admin via WhatsApp
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="mt-8 border-t border-slate-200 bg-white py-5 text-center text-xs text-slate-500">
        <div class="max-w-6xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Pondok Pesantren Hidayatullah Tuksongo Pringsurat Temanggung.</p>
        </div>
    </footer>

</body>
</html>

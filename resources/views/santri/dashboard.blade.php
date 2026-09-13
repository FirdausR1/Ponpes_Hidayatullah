<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Santri — {{ $student->nama_lengkap }} (NIS: {{ $student->nis }})</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts: Plus Jakarta Sans & EB Garamond -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=EB+Garamond:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
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
                            600: '#1e7d42',
                            700: '#1a6b38',
                            800: '#145a2e',
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
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs px-4 sm:px-8 py-3.5">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="/logo.png" alt="Logo" class="w-10 h-10 object-contain">
                <div>
                    <h2 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">Pondok Pesantren Hidayatullah</h2>
                    <span class="text-[11px] text-emerald-700 font-semibold tracking-wide">Portal Santri Mandiri</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('santri.pembayaran') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs font-bold hover:bg-emerald-100 shadow-2xs transition">
                    <span>💳</span>
                    <span class="hidden sm:inline">Tagihan &amp;</span> Pembayaran
                </a>
                <div class="hidden sm:flex flex-col text-right">
                    <span class="text-xs font-bold text-slate-900">{{ $student->nama_lengkap }}</span>
                    <span class="text-[11px] font-mono text-slate-400">NIS: {{ $student->nis }}</span>
                </div>
                <form action="{{ route('santri.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-300 text-xs font-semibold text-slate-700 transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-6xl w-full mx-auto p-4 sm:p-8 space-y-6">

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-medium shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs space-y-1">
                @foreach($errors->all() as $err)
                    <p>• {{ $err }}</p>
                @endforeach
            </div>
        @endif

        <!-- Welcome Banner -->
        <div class="rounded-3xl bg-gradient-to-r from-pondok-900 via-pondok-800 to-pondok-700 p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-emerald-200">
                        <span>Status: Santri {{ $student->status }}</span>
                        <span>•</span>
                        <span>Angkatan {{ $student->tahun_masuk }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Ahlan wa Sahlan, {{ $student->nama_lengkap }}!</h1>
                    <p class="text-xs sm:text-sm text-emerald-100 max-w-xl leading-relaxed">
                        Selamat datang di Portal Santri Resmi Pondok Pesantren Hidayatullah Tuksongo. Pantau status tagihan, riwayat pembayaran, serta data akademik Anda di sini.
                    </p>
                </div>
                <div class="shrink-0 flex flex-wrap items-center gap-3">
                    <a href="{{ route('santri.pembayaran') }}" class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-white text-xs font-bold shadow-md transition flex items-center gap-1.5">
                        <span>💳</span>
                        <span>Menu Tagihan &amp; Bayar</span>
                    </a>
                    <button onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-semibold backdrop-blur-sm border border-white/20 transition">
                        🖨️ Cetak Kartu
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Financial Summary Banner -->
        <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-2xl shrink-0">
                    💳
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Informasi Keuangan &amp; Tagihan Santri</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Total Tagihan: <strong class="text-slate-800">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</strong> &bull;
                        Terbayar: <strong class="text-emerald-700">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</strong> &bull;
                        Tunggakan: <strong class="{{ $totalTunggakan > 0 ? 'text-rose-600' : 'text-emerald-700' }}">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</strong> &bull;
                        Saldo Tabungan: <strong class="text-blue-600 font-bold">Rp {{ number_format($student->saldo_tabungan, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>
            <div class="shrink-0">
                <a href="{{ route('santri.pembayaran') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition">
                    <span>Lihat Rincian Tagihan &amp; Kwitansi</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Kartu Santri Digital -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Official Digital Student ID Card -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <img src="/logo.png" alt="Logo" class="w-10 h-10 object-contain">
                            <div>
                                <h3 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wider">Kartu Identitas Santri Digital</h3>
                                <p class="text-[11px] text-slate-400">Pondok Pesantren Hidayatullah Tuksongo Pringsurat</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                            {{ $student->status }}
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-6 items-start">
                        <!-- Foto Santri -->
                        <div class="w-32 h-44 rounded-2xl overflow-hidden bg-slate-100 border-2 border-slate-200 shadow-sm shrink-0 flex items-center justify-center">
                            @if($student->foto)
                                <img src="{{ $student->foto }}" alt="{{ $student->nama_lengkap }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-emerald-700 text-white flex flex-col items-center justify-center font-bold text-3xl">
                                    {{ strtoupper(substr($student->nama_lengkap, 0, 1)) }}
                                    <span class="text-[10px] uppercase font-normal tracking-wider mt-1 opacity-80">Santri</span>
                                </div>
                            @endif
                        </div>

                        <!-- Biodata Detail -->
                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Nomor Induk Santri (NIS)</span>
                                <span class="text-sm font-mono font-bold text-slate-800">{{ $student->nis }}</span>
                            </div>

                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Username Login</span>
                                <span class="text-sm font-mono font-bold text-emerald-700">{{ $student->username ?: $student->nis }}</span>
                            </div>

                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Tingkat Kelas Saat Ini</span>
                                <span class="text-sm font-bold text-blue-700">Kelas {{ $student->kelas }}</span>
                            </div>

                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Jenjang Pendidikan</span>
                                <span class="text-sm font-semibold text-slate-800">{{ $student->jenjang }}</span>
                            </div>

                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Tahun Masuk (Angkatan)</span>
                                <span class="text-sm font-semibold text-slate-800">Tahun {{ $student->tahun_masuk }}</span>
                            </div>

                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Kamar Asrama</span>
                                <span class="text-sm font-semibold text-slate-800">{{ $student->kamar_asrama ?: 'Non-Asrama (Laju)' }}</span>
                            </div>

                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 sm:col-span-2">
                                <span class="text-slate-400 uppercase tracking-wider font-semibold text-[10px] block">Wali Santri &amp; Kontak</span>
                                <span class="text-sm font-semibold text-slate-800">{{ $student->nama_wali ?: '-' }} ({{ $student->no_whatsapp ?: 'Belum terdata' }})</span>
                            </div>

                            <div class="p-3 bg-emerald-50/70 rounded-xl border border-emerald-200/80 sm:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <span class="text-emerald-800 uppercase tracking-wider font-bold text-[10px] block">Ustadz/ah Wali Kelas</span>
                                    <span class="text-sm font-bold text-slate-900">{{ $student->classroom?->wali_kelas ?: 'Belum ditentukan' }}</span>
                                    @if($student->classroom?->nip_wali)
                                        <span class="text-[10px] text-slate-400 font-mono block">NIP/Kode: {{ $student->classroom->nip_wali }}</span>
                                    @endif
                                </div>
                                @if($student->classroom?->whatsapp_url)
                                    <a href="{{ $student->classroom->whatsapp_url }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition shrink-0">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.585 1.961.905 2.796.905 3.184 0 5.769-2.587 5.77-5.766.001-3.18-2.584-5.766-5.77-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.802 0-3.486-.481-4.947-1.319l-7.053 1.847 1.879-6.864c-.934-1.524-1.479-3.32-1.479-5.264 0-5.514 4.486-10 10-10s10 4.486 10 10z"/></svg>
                                        <span>Hubungi Wali Kelas</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pembelajaran & Asrama -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4 text-xs">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <span>📖 Jadwal Halaqah &amp; Pembinaan Santri</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-slate-600">
                        <div class="p-3 rounded-xl border border-emerald-100 bg-emerald-50/40">
                            <span class="font-bold text-emerald-900 block">Pagi Ba'da Shubuh:</span>
                            <p class="mt-0.5 text-[11px]">Halaqah Tahfidzul Qur'an mutqin &amp; penambahan mufrodat 3 bahasa.</p>
                        </div>
                        <div class="p-3 rounded-xl border border-blue-100 bg-blue-50/40">
                            <span class="font-bold text-blue-900 block">Siang (07:00 - 13:30):</span>
                            <p class="mt-0.5 text-[11px]">Kegiatan Belajar Mengajar (KBM) Madrasah Formal &amp; Kitab Kuning.</p>
                        </div>
                        <div class="p-3 rounded-xl border border-amber-100 bg-amber-50/40">
                            <span class="font-bold text-amber-900 block">Sore Ba'da Ashar:</span>
                            <p class="mt-0.5 text-[11px]">Kajian Fiqih Ibadah, ekstrakurikuler kepanduan/silat, dan olahraga mandiri.</p>
                        </div>
                        <div class="p-3 rounded-xl border border-purple-100 bg-purple-50/40">
                            <span class="font-bold text-purple-900 block">Malam Ba'da Maghrib:</span>
                            <p class="mt-0.5 text-[11px]">Mudarosah Al-Qur'an, muhadhoroh pidato 3 bahasa, dan belajar terbimbing.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Keamanan & Ganti Password -->
            <div class="space-y-6">
                
                <!-- Ganti Password Card -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <span>🔒 Ganti Kata Sandi Akun</span>
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Ubah kata sandi awal (tanggal lahir) menjadi sandi pribadi Anda.</p>
                    </div>

                    <form action="{{ route('santri.updatePassword') }}" method="POST" class="space-y-3.5 text-xs">
                        @csrf

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                            <input type="password" name="password_lama" required placeholder="Sandi lama atau tanggal lahir (DDMMYYYY)"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-pondok-600 outline-none text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kata Sandi Baru</label>
                            <input type="password" name="password_baru" required minlength="6" placeholder="Minimal 6 karakter"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-pondok-600 outline-none text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_baru_confirmation" required minlength="6" placeholder="Ketik ulang sandi baru"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-pondok-600 outline-none text-xs">
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-pondok-700 hover:bg-pondok-800 text-white font-bold rounded-xl shadow-md transition">
                            Simpan Kata Sandi Baru
                        </button>
                    </form>
                </div>

                <!-- Bantuan & Hotline Pesantren -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-3 text-xs">
                    <span class="font-bold text-slate-800 block">📞 Bantuan Sekretariat &amp; Kesantrian</span>
                    <p class="text-slate-500 text-[11px] leading-relaxed">
                        Jika terdapat ketidaksesuaian biodata santri atau membutuhkan surat keterangan aktif, silakan menghubungi kantor kesantrian pesantren.
                    </p>
                    <div class="p-3 bg-slate-50 rounded-xl font-mono text-slate-700 text-xs flex items-center gap-2">
                        <span>💬 WA Kesantrian:</span>
                        <a href="https://wa.me/6285290429617" target="_blank" class="text-emerald-700 font-bold hover:underline">0852-9042-9617</a>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="py-4 text-center text-xs text-slate-400 border-t border-slate-200 bg-white">
        &copy; {{ date('Y') }} Pondok Pesantren Hidayatullah Tuksongo Pringsurat.
    </footer>

</body>
</html>

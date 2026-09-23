<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tutupJudul ?? 'Pendaftaran Santri Baru Telah Ditutup' }} — Ponpes Hidayatullah</title>
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
    </style>
</head>
<body class="min-h-screen flex flex-col bg-slate-50 antialiased text-slate-800">

    <!-- TOP HEADER -->
    <header class="bg-white border-b border-slate-200/90 sticky top-0 z-40 shadow-xs py-4 sm:py-5 px-4 sm:px-8 lg:px-16 transition-all">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3 sm:gap-4 group">
                <img src="/logo.png" alt="Logo Pondok Pesantren Hidayatullah" class="w-10 h-10 sm:w-12 sm:h-12 object-contain shrink-0 group-hover:scale-105 transition">
                <div class="flex flex-col justify-center">
                    <img src="/logo1.png" alt="معهد هداية الله للتربية الإسلامية" class="h-6 sm:h-7 w-auto object-contain object-left filter contrast-105">
                    <span class="font-sans text-[11px] sm:text-xs font-semibold text-slate-600 tracking-tight">Pondok Pesantren Hidayatullah Tuksongo</span>
                </div>
            </a>
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('psb.checkStatus') }}" class="inline-flex items-center gap-1.5 px-3 sm:px-4 py-2 sm:py-2.5 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 hover:text-pondok-800 transition shadow-2xs">
                    <svg class="icon-svg w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <span class="hidden sm:inline">Cek Status Pendaftaran</span>
                    <span class="sm:hidden">Cek Status</span>
                </a>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 sm:px-4 py-2 sm:py-2.5 rounded-full border border-emerald-200/80 bg-emerald-50/80 hover:bg-emerald-100 text-xs sm:text-sm font-semibold text-emerald-900 transition shadow-2xs">
                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span class="hidden sm:inline">Beranda</span>
                </a>
            </div>
        </div>
    </header>

    <!-- ALERT ERROR IF REDIRECTED FROM POST -->
    @if (session('error'))
    <div class="max-w-3xl mx-auto mt-6 px-4">
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-sm font-medium shadow-sm">
            <svg class="icon-svg w-5 h-5 text-rose-600 shrink-0" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- MAIN NOTIFICATION CARD -->
    <main class="flex-1 py-10 sm:py-16 px-4 sm:px-6 flex items-center justify-center">
        <div class="max-w-2xl w-full space-y-8">

            @php
                $isUpcoming = ($tutupState ?? '') === 'upcoming' || ($sched['status'] ?? '') === 'belum_buka';
            @endphp

            <!-- Card Utama -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden text-center relative">
                <!-- Top Accent Bar -->
                <div class="h-2.5 {{ $isUpcoming ? 'bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-600' : 'bg-gradient-to-r from-rose-500 via-amber-500 to-rose-600' }}"></div>

                <div class="p-6 sm:p-10 space-y-6">
                    <!-- Icon Status -->
                    @if($isUpcoming)
                        <div class="mx-auto w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shadow-inner">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                    @else
                        <div class="mx-auto w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shadow-inner">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                    @endif

                    <!-- Badge Status -->
                    @if($isUpcoming)
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            Pendaftaran Belum Dibuka
                            @if(!empty($sched['start_formatted']))
                                • Mulai {{ $sched['start_formatted'] }}
                            @endif
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            Pendaftaran PSB Saat Ini Ditutup
                        </div>
                    @endif

                    <!-- Judul Pengumuman -->
                    <div class="space-y-2">
                        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-slate-900 leading-snug">
                            {{ $tutupJudul ?? 'Penerimaan Santri Baru (PSB) Telah Ditutup' }}
                        </h1>
                        <p class="text-xs sm:text-sm font-medium text-emerald-800">
                            Tahun Ajaran {{ $tahunAjaran ?? \App\Models\Setting::get('tahun_ajaran', '2026/2027') }} &bull; {{ $sched['gelombang'] ?? \App\Models\Setting::get('psb_gelombang_aktif', 'Gelombang 1') }}
                        </p>
                    </div>

                    <!-- Deskripsi Pesan Resmi -->
                    <div class="bg-slate-50/80 rounded-2xl p-5 sm:p-6 border border-slate-200/70 text-left space-y-3">
                        <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200/60 pb-2">
                            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            Pemberitahuan Resmi Panitia
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $tutupPesan ?? "Mohon maaf, pendaftaran santri baru Pondok Pesantren Hidayatullah Tuksongo saat ini telah resmi ditutup. Untuk informasi pendaftaran gelombang berikutnya atau konsultasi pendidikan, silakan hubungi kontak panitia atau pantau pengumuman resmi website kami." }}
                        </p>
                    </div>

                    <!-- Informasi Santri yang Sudah Mendaftar -->
                    <div class="bg-emerald-50/70 rounded-2xl p-4 sm:p-5 border border-emerald-200/80 text-left flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <div class="space-y-1">
                            <h2 class="text-xs sm:text-sm font-bold text-emerald-950">Sudah Pernah Mengisi Pendaftaran?</h2>
                            <p class="text-xs text-emerald-800 leading-relaxed">
                                Seluruh data calon santri yang telah mendaftar tersimpan aman di sistem. Anda tetap dapat mengecek status verifikasi administrasi, mencetak kartu biodata santri, dan mengikuti ujian CBT.
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Aksi Penting -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <!-- Cek Status Pendaftaran -->
                        <a href="{{ route('psb.checkStatus') }}" class="w-full flex items-center justify-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3 px-4 rounded-xl shadow-md transition text-xs sm:text-sm">
                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <span>Cek Status Santri</span>
                        </a>

                        <!-- Portal Ujian CBT -->
                        <a href="{{ route('ujian.index') }}" class="w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold py-3 px-4 rounded-xl shadow-sm transition text-xs sm:text-sm">
                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                            <span>Portal Ujian Masuk (CBT)</span>
                        </a>

                        <!-- Hubungi Panitia via WA -->
                        @php
                            $cleanWa = preg_replace('/[^0-9]/', '', $tutupKontak ?? '081391109966');
                            if (str_starts_with($cleanWa, '0')) {
                                $cleanWa = '62' . substr($cleanWa, 1);
                            }
                        @endphp
                        <a href="https://wa.me/{{ $cleanWa }}?text={{ urlencode('Assalamu\'alaikum Panitia PSB Pondok Pesantren Hidayatullah, saya ingin menanyakan informasi mengenai pendaftaran santri baru...') }}" target="_blank" class="w-full flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-emerald-800 font-semibold py-3 px-4 rounded-xl border border-emerald-300 shadow-xs transition text-xs sm:text-sm">
                            <svg class="icon-svg w-4 h-4 text-emerald-600" viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                            <span>WhatsApp Panitia PSB</span>
                        </a>

                        <!-- Unduh Brosur Resmi -->
                        <a href="{{ \App\Models\Setting::get('brosur_file_url', '/uploads/settings/brosur_1788852849.jpeg') }}" target="_blank" class="w-full flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 font-semibold py-3 px-4 rounded-xl border border-slate-300 shadow-xs transition text-xs sm:text-sm">
                            <svg class="icon-svg w-4 h-4 text-slate-500" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                            <span>Unduh Brosur (PDF)</span>
                        </a>
                    </div>
                </div>

                <!-- Footer Card -->
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Hotline: {{ $tutupKontak ?? '0813-9110-9966' }}</span>
                    <a href="{{ route('home') }}" class="text-emerald-700 font-semibold hover:underline flex items-center gap-1">
                        <span>Kembali ke Beranda</span>
                        &rarr;
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-6 text-center text-xs text-slate-400 border-t border-slate-200/80 bg-white">
        <p>&copy; {{ date('Y') }} Pondok Pesantren Hidayatullah Tuksongo Pringsurat Temanggung. Hak Cipta Dilindungi.</p>
    </footer>

</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Akses Santri & Wali — Pondok Pesantren Hidayatullah Tuksongo</title>
    <link rel="icon" href="/logo.png" type="image/png">

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        santri: {
                            25: '#f4fbf7',
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#062016',
                        },
                        gray: {
                            25: '#fcfcfd',
                            50: '#f9fafb',
                            100: '#f2f4f7',
                            200: '#e4e7ec',
                            300: '#d0d5dd',
                            400: '#98a2b3',
                            500: '#667085',
                            600: '#475467',
                            700: '#344054',
                            800: '#1d2939',
                            900: '#101828',
                            950: '#0c111d',
                        },
                        error: {
                            50: '#fef3f2',
                            100: '#fee4e2',
                            500: '#f04438',
                            600: '#d92d20',
                            700: '#b42318',
                        },
                        success: {
                            50: '#ecfdf3',
                            100: '#d1fadf',
                            500: '#12b76a',
                            600: '#039855',
                            700: '#027a48',
                        }
                    },
                    boxShadow: {
                        'theme-xs': '0px 1px 2px 0px rgba(16, 24, 40, 0.05)',
                        'theme-sm': '0px 1px 3px 0px rgba(16, 24, 40, 0.10), 0px 1px 2px 0px rgba(16, 24, 40, 0.06)',
                        'theme-md': '0px 4px 8px -2px rgba(16, 24, 40, 0.10), 0px 2px 4px -2px rgba(16, 24, 40, 0.06)',
                        'theme-lg': '0px 12px 16px -4px rgba(16, 24, 40, 0.08), 0px 4px 6px -2px rgba(16, 24, 40, 0.03)',
                        'theme-xl': '0px 20px 24px -4px rgba(16, 24, 40, 0.08), 0px 8px 8px -4px rgba(16, 24, 40, 0.03)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .custom-checkbox:checked + div {
            background-color: #059669;
            border-color: #059669;
        }
        .custom-checkbox:checked + div svg {
            opacity: 1;
        }
    </style>
</head>

<body class="min-h-screen bg-white text-gray-800 antialiased selection:bg-santri-600 selection:text-white">

    <div class="relative flex flex-col justify-center min-h-screen w-full lg:flex-row">

        <!-- ================= KOLOM KIRI: FORM LOGIN SANTRI ================= -->
        <div class="flex flex-col flex-1 w-full lg:w-1/2 p-6 sm:p-10 lg:p-12 xl:p-16 min-h-screen justify-between bg-white z-10">

            <!-- Bar Atas: Navigasi Kembali & Switch ke Login Admin -->
            <div class="w-full max-w-md mx-auto flex items-center justify-between gap-4">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors group">
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-700 transition-transform group-hover:-translate-x-0.5"
                        viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5 5L7.5 10L12.5 15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Beranda Website</span>
                </a>

                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-santri-700 transition-colors py-1 px-2.5 rounded-lg hover:bg-gray-50">
                    <span>Masuk Pengurus/Admin</span>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Konten Tengah: Form Otentikasi Santri -->
            <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto py-8">
                
                <!-- Logo untuk Layar Mobile/Tablet (< lg) -->
                <div class="lg:hidden flex items-center gap-3.5 mb-6 pb-6 border-b border-gray-100">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-100 p-2 flex items-center justify-center shrink-0">
                        <img src="/logo.png" alt="Logo Ponpes" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <img src="/logo1.png" alt="معهد هداية الله" class="h-5 w-auto object-contain">
                        <p class="text-[11px] font-medium text-gray-500">Pondok Pesantren Hidayatullah Tuksongo</p>
                    </div>
                </div>

                <!-- Judul & Deskripsi Header -->
                <div class="mb-6 sm:mb-8">
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-santri-50 border border-santri-100 text-santri-700 text-xs font-semibold uppercase tracking-wider mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-santri-500"></span>
                        Portal Akses Santri &amp; Wali
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                        Masuk Portal Santri
                    </h1>
                    <p class="text-sm text-gray-500 mt-1.5">
                        Akses informasi tagihan syahriyah (SPP), upload bukti transfer bank, dan kartu santri digital.
                    </p>
                </div>

                <!-- Flash Alert: Sukses -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-success-50 border border-success-100 rounded-xl text-success-700 text-sm flex items-start gap-3 shadow-theme-xs">
                        <svg class="w-5 h-5 text-success-600 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Flash Alert: Validasi Gagal / Error -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-error-50 border border-error-100 rounded-xl text-error-700 text-sm space-y-1.5 shadow-theme-xs">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-start gap-2.5">
                                <svg class="w-5 h-5 text-error-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Form Login Santri -->
                <form action="{{ route('santri.login.post') }}" method="POST" class="space-y-4 sm:space-y-5">
                    @csrf

                    <!-- Input NIS / Username / NISN -->
                    <div>
                        <label for="identifier" class="mb-1.5 block text-sm font-medium text-gray-700">
                            NIS / Username / NISN <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="identifier"
                                name="identifier"
                                autocomplete="username"
                                required
                                autofocus
                                value="{{ old('identifier') }}"
                                placeholder="Contoh: 20260001 atau NISN atau No Registrasi"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-santri-600 focus:outline-none focus:ring-4 focus:ring-santri-600/10 transition"
                            />
                        </div>
                    </div>

                    <!-- Input Kata Sandi / Tanggal Lahir dengan Toggle TailAdmin -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Kata Sandi / Tanggal Lahir <span class="text-error-500">*</span>
                            </label>
                        </div>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                autocomplete="current-password"
                                required
                                placeholder="Format: DDMMYYYY (contoh: 15052010)"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-santri-600 focus:outline-none focus:ring-4 focus:ring-santri-600/10 transition"
                            />
                            <!-- Tombol Eye Toggle -->
                            <button
                                type="button"
                                id="togglePasswordBtn"
                                onclick="togglePasswordVisibility()"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition p-1"
                                title="Tampilkan / Sembunyikan Kata Sandi">
                                <!-- Eye Open Icon -->
                                <svg id="eyeOpenIcon" class="w-5 h-5 fill-current hidden" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z" fill="#98A2B3"/>
                                </svg>
                                <!-- Eye Closed / Slash Icon -->
                                <svg id="eyeClosedIcon" class="w-5 h-5 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z" fill="#98A2B3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Ingat Saya (TailAdmin Checkbox Style) -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember" class="flex items-center text-sm font-normal text-gray-700 cursor-pointer select-none">
                            <div class="relative flex items-center">
                                <input
                                    type="checkbox"
                                    id="remember"
                                    name="remember"
                                    class="custom-checkbox sr-only"
                                />
                                <div class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] border-gray-300 bg-transparent transition-all">
                                    <svg class="opacity-0 transition-opacity duration-150" width="12" height="12" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <span>Ingat saya di perangkat ini</span>
                        </label>
                        <span class="text-xs text-gray-400">Hubungi madrasah jika lupa data</span>
                    </div>

                    <!-- Tombol Masuk Utama -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="flex items-center justify-center w-full gap-2 px-4 py-3 text-sm font-semibold text-white transition-all rounded-lg bg-santri-600 shadow-theme-xs hover:bg-santri-700 active:scale-[0.99] focus:outline-none focus:ring-4 focus:ring-santri-600/20">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <span>Masuk ke Portal Santri</span>
                        </button>
                    </div>
                </form>

                <!-- Box Petunjuk Penggunaan Akun Santri -->
                <div class="mt-7 pt-5 border-t border-gray-100">
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/80 space-y-2 text-xs">
                        <div class="flex items-center gap-2 font-semibold text-gray-800">
                            <span class="px-2 py-0.5 rounded bg-santri-100 text-santri-800 font-bold text-[10px] tracking-wide uppercase">Petunjuk Akses</span>
                            <span>Panduan Login Santri</span>
                        </div>
                        <ul class="space-y-1.5 text-gray-600 text-[11px] leading-relaxed">
                            <li class="flex items-start gap-1.5">
                                <span class="text-santri-600 font-bold">&bull;</span>
                                <span><strong>Username:</strong> Masukkan Nomor Induk Santri (NIS), NISN, atau No. Registrasi pendaftaran PSB Anda.</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="text-santri-600 font-bold">&bull;</span>
                                <span><strong>Kata Sandi Default:</strong> Gunakan <strong>tanggal lahir</strong> 8 digit (format <code>DDMMYYYY</code>, contoh: lahir 15 Mei 2010 ketik <code>15052010</code>) atau sandi <code>santri123</code>.</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- Bar Bawah: Copyright -->
            <div class="w-full max-w-md mx-auto pt-6 text-center lg:text-left">
                <p class="text-xs text-gray-400">
                    &copy; 2026 Pondok Pesantren Hidayatullah Tuksongo. Sistem Portal Santri.
                </p>
            </div>

        </div>

        <!-- ================= KOLOM KANAN: BRANDING EMERALD HERO ================= -->
        <div class="relative hidden lg:flex lg:w-1/2 min-h-screen bg-[#072418] items-center justify-center overflow-hidden p-8 xl:p-16">

            <!-- TailAdmin Geometric Grid Shape Top-Right -->
            <div class="absolute right-0 top-0 pointer-events-none opacity-25 max-w-[280px] xl:max-w-[420px]">
                <img src="/tailadmin-vuejs-1.0.0/public/images/shape/grid-01.svg" alt="TailAdmin Grid" class="w-full h-auto">
            </div>

            <!-- TailAdmin Geometric Grid Shape Bottom-Left (Rotated 180deg) -->
            <div class="absolute bottom-0 left-0 pointer-events-none opacity-25 max-w-[280px] xl:max-w-[420px] rotate-180">
                <img src="/tailadmin-vuejs-1.0.0/public/images/shape/grid-01.svg" alt="TailAdmin Grid" class="w-full h-auto">
            </div>

            <!-- Ambient Radial Glow Effects (Emerald Theme) -->
            <div class="absolute w-[450px] h-[450px] bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute w-[250px] h-[250px] bg-teal-500/10 rounded-full blur-2xl pointer-events-none translate-x-20 -translate-y-20"></div>

            <!-- Konten Showcase Brand di Tengah -->
            <div class="relative z-10 flex flex-col items-center text-center max-w-lg mx-auto">

                <!-- Badge Logo Utama -->
                <div class="relative mb-6 group">
                    <div class="absolute -inset-1 rounded-3xl bg-gradient-to-tr from-emerald-500/40 to-teal-400/20 blur-lg opacity-70 group-hover:opacity-100 transition duration-500"></div>
                    <div class="relative w-24 h-24 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 p-4 shadow-2xl flex items-center justify-center">
                        <img src="/logo.png" alt="Logo Ponpes Hidayatullah" class="w-full h-full object-contain filter drop-shadow">
                    </div>
                </div>

                <!-- Calligraphy Logo (Arabic Inverted White) -->
                <div class="mb-4">
                    <img src="/logo1.png" alt="معهد هداية الله للتربية الإسلامية" class="h-9 w-auto object-contain mx-auto filter brightness-0 invert opacity-95">
                </div>

                <!-- Nama Pesantren -->
                <h2 class="text-2xl xl:text-3xl font-bold text-white tracking-tight">
                    Portal Santri &amp; Wali Santri
                </h2>
                <p class="text-xs font-semibold tracking-widest text-emerald-300 uppercase mt-1">
                    Pondok Pesantren Hidayatullah Tuksongo &bull; Temanggung
                </p>

                <!-- Kutipan / Tagline -->
                <p class="text-sm text-emerald-100/80 leading-relaxed italic max-w-md mx-auto mt-4">
                    &ldquo;Menuntut ilmu agama adalah jalan kemuliaan. Pantau perkembangan, administrasi, dan syahriyah secara mudah dan transparan.&rdquo;
                </p>

                <!-- 3 Fitur Unggulan Portal Santri -->
                <div class="flex flex-wrap items-center justify-center gap-2.5 mt-8 pt-8 border-t border-white/10 max-w-md mx-auto">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm text-xs font-medium text-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Cek Tagihan Syahriyah
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm text-xs font-medium text-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                        Upload Bukti Pembayaran
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm text-xs font-medium text-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                        Kartu Santri &amp; Kwitansi
                    </div>
                </div>

                <!-- Footer Tag -->
                <div class="mt-8 flex items-center gap-2 text-[11px] text-emerald-300/60">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Layanan Mandiri Santri Pondok Pesantren Hidayatullah</span>
                </div>

            </div>

        </div>

    </div>

    <!-- Script Interaktif untuk Password Toggle -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeOpenIcon = document.getElementById('eyeOpenIcon');
            const eyeClosedIcon = document.getElementById('eyeClosedIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpenIcon.classList.remove('hidden');
                eyeClosedIcon.classList.add('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpenIcon.classList.add('hidden');
                eyeClosedIcon.classList.remove('hidden');
            }
        }
    </script>

</body>

</html>

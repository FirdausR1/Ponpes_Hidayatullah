<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Panel — Hidayatullah Tuksongo (TailAdmin)</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecf3ff',
                            100: '#dde9ff',
                            500: '#465fff',
                            600: '#3641f5',
                            700: '#2a31d8',
                        },
                        dark: {
                            sidebar: '#1c2434',
                            sidebarHover: '#333a48',
                            body: '#f1f5f9',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f1f5f9; }
        .icon-svg {
            width: 20px; height: 20px; stroke: currentColor; stroke-width: 1.8;
            stroke-linecap: round; stroke-linejoin: round; fill: none;
            display: inline-block; vertical-align: middle;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-100 via-[#f1f5f9] to-slate-200">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <!-- Brand Logo -->
        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group mb-4">
            <div class="w-14 h-14 rounded-2xl bg-white shadow-md border border-slate-200/80 p-2.5 flex items-center justify-center group-hover:scale-105 transition">
                <img src="/logo.png" alt="Logo Ponpes Hidayatullah" class="w-full h-full object-contain">
            </div>
        </a>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">TailAdmin Panel</h2>
        <p class="text-xs text-slate-500 mt-1">Pondok Pesantren Hidayatullah Tuksongo — Temanggung</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 shadow-xl shadow-slate-200/60 rounded-2xl border border-slate-200/80 sm:px-10">
            
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-800">Masuk ke Akun Admin</h3>
                <p class="text-xs text-slate-400 mt-0.5">Kelola konten berita, pendaftar santri baru, dan informasi kampus.</p>
            </div>

            <!-- Flash Error / Success Alert -->
            @if(session('success'))
                <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs flex items-center gap-2">
                    <svg class="icon-svg w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <svg class="icon-svg w-4 h-4 text-rose-500 shrink-0" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form class="space-y-4" action="{{ route('login') }}" method="POST">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Alamat Email Admin
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </span>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email', 'admin@hidayatullah.ponpes.id') }}" placeholder="admin@hidayatullah.ponpes.id" class="block w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">
                            Kata Sandi
                        </label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </span>
                        <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="••••••••" class="block w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-brand-500 focus:ring-brand-500 border-slate-300 rounded cursor-pointer">
                        <label for="remember" class="ml-2 block text-xs text-slate-600 select-none cursor-pointer">
                            Ingat saya di perangkat ini
                        </label>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-brand-500 hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition">
                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                        Masuk ke Admin Panel
                    </button>
                </div>
            </form>

            <!-- Demo Hint Box -->
            <div class="mt-6 pt-5 border-t border-slate-100 bg-slate-50 -mx-6 -mb-8 px-6 py-4 rounded-b-2xl">
                <div class="flex items-start gap-2.5 text-xs text-slate-500">
                    <span class="inline-block p-1 bg-brand-100 text-brand-700 rounded-md font-semibold text-[10px] uppercase shrink-0">Default</span>
                    <div>
                        <p class="font-mono text-slate-700 font-semibold">admin@hidayatullah.ponpes.id</p>
                        <p class="font-mono text-slate-500">Password: <span class="text-slate-800 font-semibold">admin123</span></p>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-500 hover:text-brand-600 transition inline-flex items-center gap-1.5">
                <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Beranda Website
            </a>
        </div>
    </div>

</body>
</html>

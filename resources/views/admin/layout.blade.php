<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — Hidayatullah Tuksongo (TailAdmin)</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts: Outfit (TailAdmin Default) & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        body { font-family: 'Outfit', sans-serif; background-color: #f1f5f9; color: #1e293b; }
        .icon-svg {
            width: 20px; height: 20px; stroke: currentColor; stroke-width: 1.8;
            stroke-linecap: round; stroke-linejoin: round; fill: none;
            display: inline-block; vertical-align: middle;
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    @yield('styles')
</head>
<body class="bg-[#f1f5f9] text-slate-800 antialiased min-h-screen flex">

    <!-- SIDEBAR (TailAdmin Dark Style: #1c2434) -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#1c2434] text-slate-300 transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full flex flex-col justify-between shadow-xl">
        <div>
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-[#2e3a47]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="/logo.png" alt="Logo" class="w-9 h-9 object-contain">
                    <div>
                        <span class="text-white font-bold text-base tracking-wide block leading-tight">TailAdmin</span>
                        <span class="text-xs text-slate-400">Ponpes Hidayatullah</span>
                    </div>
                </a>
                <button id="sidebarClose" class="lg:hidden text-slate-400 hover:text-white">
                    <svg class="icon-svg" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <!-- Sidebar Menu -->
            <div class="px-4 py-6 overflow-y-auto">
                <span class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-3">Menu Utama</span>
                <nav class="space-y-1.5">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-brand-500 text-white shadow-md' : 'text-slate-300 hover:bg-[#333a48] hover:text-white' }}">
                        <svg class="icon-svg" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.berita.index') }}" 
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.berita.*') ? 'bg-brand-500 text-white shadow-md' : 'text-slate-300 hover:bg-[#333a48] hover:text-white' }}">
                        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        Kelola Berita
                    </a>

                    <a href="{{ route('admin.psb.index') }}" 
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.psb.*') ? 'bg-brand-500 text-white shadow-md' : 'text-slate-300 hover:bg-[#333a48] hover:text-white' }}">
                        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Pendaftar PSB Santri
                    </a>

                    <a href="{{ route('admin.settings.index') }}" 
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-brand-500 text-white shadow-md' : 'text-slate-300 hover:bg-[#333a48] hover:text-white' }}">
                        <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        Pengaturan Konten Web
                    </a>
                </nav>

                <span class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 block mt-8 mb-3">Tautan Luar</span>
                <nav class="space-y-1.5">
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-[#333a48] hover:text-white transition-colors">
                        <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                        Kunjungi Website
                        <svg class="icon-svg w-3.5 h-3.5 ml-auto text-slate-500" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                    <a href="{{ route('berita.index') }}" target="_blank" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-[#333a48] hover:text-white transition-colors">
                        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        Halaman Berita Publik
                    </a>
                    <a href="{{ \App\Models\Setting::get('brosur_file_url', '/NEW BUKU PANDUAN SANTRI TA 2025 (2).pdf') }}" target="_blank" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-[#333a48] hover:text-white transition-colors">
                        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        Brosur & Panduan PDF
                    </a>
                </nav>
            </div>
        </div>

        <!-- Sidebar User Badge & Logout -->
        <div class="p-4 border-t border-[#2e3a47] bg-[#171f2c]">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-brand-500 text-white font-bold flex items-center justify-center text-xs shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <h5 class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</h5>
                        <span class="text-[11px] text-slate-400 truncate block">{{ auth()->user()->email ?? 'admin@hidayatullah.ponpes.id' }}</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-[#333a48] rounded-lg transition" title="Keluar / Sign Out">
                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- BACKDROP MOBILE -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden hidden"></div>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0 lg:ml-64 min-h-screen">
        
        <!-- TOP NAVBAR -->
        <header class="h-16 bg-white border-b border-slate-200 sticky top-0 z-30 flex items-center justify-between px-4 lg:px-8 shadow-sm">
            <div class="flex items-center gap-4">
                <button id="sidebarToggle" class="lg:hidden text-slate-600 hover:text-slate-900 p-2 rounded-lg hover:bg-slate-100">
                    <svg class="icon-svg" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
                <div class="relative hidden sm:block">
                    <span class="text-slate-400 absolute left-3 top-2.5">
                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </span>
                    <input type="text" placeholder="Ketik untuk mencari..." class="w-64 pl-9 pr-4 py-1.5 text-sm bg-slate-100 border-0 rounded-lg focus:ring-2 focus:ring-brand-500 outline-none transition">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Aktif • TA 2025/2026
                </div>

                <!-- NOTIFIKASI PENDAFTARAN & PEMBAYARAN PSB -->
                @php
                    $navPendingPsb = \App\Models\PsbRegistration::where('status', 'Menunggu')->latest()->take(5)->get();
                    $navPendingCount = \App\Models\PsbRegistration::where('status', 'Menunggu')->count();
                    $navNeedFixCount = \App\Models\PsbRegistration::where('foto_status', 'Perlu Perbaikan')->count();
                    $totalNotifCount = $navPendingCount + $navNeedFixCount;
                @endphp
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
                    <button @click="open = !open" type="button" class="relative p-2 text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition" title="Notifikasi PSB">
                        <svg class="icon-svg w-5 h-5" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        @if($totalNotifCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white shadow-sm ring-2 ring-white animate-pulse">
                                {{ $totalNotifCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Panel Dropdown Notifikasi -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-xl shadow-2xl border border-slate-200 py-3 z-50 text-slate-800"
                         style="display: none;">
                        
                        <div class="px-4 pb-2.5 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-sm text-slate-800">Notifikasi PSB Masuk</h4>
                                <p class="text-[11px] text-slate-500">Pendaftar baru & konfirmasi transfer</p>
                            </div>
                            @if($navPendingCount > 0)
                                <span class="px-2 py-0.5 text-[11px] font-bold rounded-full bg-amber-100 text-amber-800">
                                    {{ $navPendingCount }} Pending
                                </span>
                            @endif
                        </div>

                        <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                            @forelse($navPendingPsb as $notif)
                                <a href="{{ route('admin.psb.index') }}" class="p-3 hover:bg-slate-50 transition flex items-start gap-3 group">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $notif->bukti_transfer ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        @if($notif->bukti_transfer)
                                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                        @else
                                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <span class="font-semibold text-xs text-slate-800 truncate group-hover:text-brand-600">{{ $notif->nama_lengkap }}</span>
                                            <span class="text-[10px] text-slate-400 shrink-0">{{ $notif->created_at ? $notif->created_at->diffForHumans() : '' }}</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                            Jalur {{ $notif->jalur }} • {{ $notif->jenjang }}
                                        </p>
                                        <div class="flex items-center gap-1.5 mt-1">
                                            @if($notif->bukti_transfer)
                                                <span class="inline-flex items-center gap-1 text-[10px] text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded font-medium">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Bukti Bayar Terlampir
                                                </span>
                                            @else
                                                <span class="text-[10px] text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded font-medium">
                                                    Belum upload transfer
                                                </span>
                                            @endif
                                            @if($notif->foto_status === 'Perlu Perbaikan')
                                                <span class="text-[10px] text-rose-700 bg-rose-50 px-1.5 py-0.5 rounded font-medium">
                                                    Foto perlu dicek
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="p-6 text-center text-slate-400">
                                    <svg class="icon-svg w-8 h-8 mx-auto mb-2 text-slate-300" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                                    <p class="text-xs">Tidak ada pendaftar baru yang menunggu verifikasi.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="px-4 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('admin.psb.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-800 transition">
                                Buka Semua Pendaftar PSB &rarr;
                            </a>
                            <form action="{{ route('admin.psb.autoVerify') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-[11px] text-emerald-600 hover:text-emerald-800 font-medium cursor-pointer" title="Verifikasi otomatis semua pas foto santri">
                                    ⚡ Verifikasi Foto Otomatis
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    Lihat Web
                </a>
            </div>
        </header>

        <!-- FLASH NOTIFICATION -->
        @if(session('success'))
            <div class="px-4 lg:px-8 pt-4">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="icon-svg text-emerald-600" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-lg leading-none">&times;</button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="px-4 lg:px-8 pt-4">
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg text-sm shadow-sm">
                    <strong class="block font-semibold mb-1">Perhatian: Silakan perbaiki kesalahan berikut:</strong>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- PAGE CONTENT -->
        <main class="flex-1 p-4 lg:p-8">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="py-4 px-8 bg-white border-t border-slate-200 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} Pondok Pesantren Hidayatullah Tuksongo. TailAdmin Template Integration.
        </footer>
    </div>

    <!-- SCRIPT TOGGLE SIDEBAR -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarBackdrop.classList.toggle('hidden');
        }

        sidebarToggle?.addEventListener('click', toggleSidebar);
        sidebarClose?.addEventListener('click', toggleSidebar);
        sidebarBackdrop?.addEventListener('click', toggleSidebar);
    </script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global Toast Notification for Flash Messages
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ addslashes(session('success')) }}"
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Perhatian / Error',
                text: "{{ addslashes(session('error')) }}",
                confirmButtonColor: '#465fff',
                confirmButtonText: 'Baik, Mengerti'
            });
        @endif
    </script>
    @yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Hidayatullah Tuksongo</title>
    <link rel="icon" href="/logo.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                fontFamily: { sans: ['Outfit','sans-serif'] },
                colors: {
                    brand:   { 50:'#ecf3ff',100:'#dde9ff',200:'#c3d4ff',300:'#9db4ff',500:'#465fff',600:'#3641f5',700:'#2a31d8' },
                    success: { 50:'#f0fdf4',100:'#dcfce7',500:'#22c55e',600:'#16a34a',700:'#15803d' },
                    error:   { 50:'#fef2f2',100:'#fee2e2',500:'#ef4444',600:'#dc2626',700:'#b91c1c' },
                    warning: { 50:'#fffbeb',100:'#fef3c7',500:'#f59e0b',600:'#d97706',700:'#b45309' },
                },
            }}
        }
    </script>
    <style>
        *{ box-sizing:border-box; }
        body{ font-family:'Outfit',sans-serif; }
        [x-cloak]{ display:none!important; }
        /* Menu item (TailAdmin sidebar) */
        .menu-item{ display:flex;align-items:center;gap:12px;border-radius:8px;padding:10px 12px;font-size:.875rem;font-weight:500;transition:background-color .15s,color .15s; }
        .menu-item-active{ background:#ecf3ff;color:#465fff; }
        .menu-item-inactive{ color:#374151; }
        .menu-item-inactive:hover{ background:#f3f4f6;color:#111827; }
        .mi-icon-active{ color:#465fff;flex-shrink:0; }
        .mi-icon-inactive{ color:#6b7280;flex-shrink:0; }
        .menu-item-inactive:hover .mi-icon-inactive{ color:#374151; }
        /* Scrollbar */
        ::-webkit-scrollbar{ width:5px;height:5px; }
        ::-webkit-scrollbar-thumb{ background:#d1d5db;border-radius:99px; }
        .no-scrollbar::-webkit-scrollbar{ display:none; }
        .no-scrollbar{ -ms-overflow-style:none;scrollbar-width:none; }
        /* Badge */
        .badge-success{ display:inline-flex;align-items:center;padding:2px 10px;border-radius:9999px;font-size:.75rem;font-weight:600;background:#f0fdf4;color:#16a34a; }
        .badge-error  { display:inline-flex;align-items:center;padding:2px 10px;border-radius:9999px;font-size:.75rem;font-weight:600;background:#fef2f2;color:#dc2626; }
        .badge-warning{ display:inline-flex;align-items:center;padding:2px 10px;border-radius:9999px;font-size:.75rem;font-weight:600;background:#fffbeb;color:#d97706; }
        .badge-primary{ display:inline-flex;align-items:center;padding:2px 10px;border-radius:9999px;font-size:.75rem;font-weight:600;background:#ecf3ff;color:#465fff; }
        .badge-gray   { display:inline-flex;align-items:center;padding:2px 10px;border-radius:9999px;font-size:.75rem;font-weight:600;background:#f3f4f6;color:#4b5563; }
        /* Buttons */
        .ta-btn-primary{ display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:8px;background:#465fff;color:#fff;padding:10px 20px;font-size:.875rem;font-weight:600;cursor:pointer;transition:background .15s;border:none; }
        .ta-btn-primary:hover{ background:#3641f5; }
        .ta-btn-outline{ display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:8px;background:#fff;color:#374151;padding:10px 20px;border:1px solid #d1d5db;font-size:.875rem;font-weight:600;cursor:pointer;transition:background .15s; }
        .ta-btn-outline:hover{ background:#f9fafb; }
        .ta-btn-sm-primary{ display:inline-flex;align-items:center;justify-content:center;gap:6px;border-radius:8px;background:#465fff;color:#fff;padding:8px 14px;font-size:.75rem;font-weight:600;cursor:pointer;transition:background .15s;border:none; }
        .ta-btn-sm-primary:hover{ background:#3641f5; }
        .ta-btn-sm-outline{ display:inline-flex;align-items:center;justify-content:center;gap:6px;border-radius:8px;background:#fff;color:#374151;padding:8px 14px;border:1px solid #d1d5db;font-size:.75rem;font-weight:600;cursor:pointer;transition:background .15s; }
        .ta-btn-sm-outline:hover{ background:#f9fafb; }
        .ta-btn-sm-danger{ display:inline-flex;align-items:center;justify-content:center;gap:6px;border-radius:8px;background:#ef4444;color:#fff;padding:8px 14px;font-size:.75rem;font-weight:600;cursor:pointer;transition:background .15s;border:none; }
        .ta-btn-sm-danger:hover{ background:#dc2626; }
        .ta-btn-sm-success{ display:inline-flex;align-items:center;justify-content:center;gap:6px;border-radius:8px;background:#22c55e;color:#fff;padding:8px 14px;font-size:.75rem;font-weight:600;cursor:pointer;transition:background .15s;border:none; }
        .ta-btn-sm-success:hover{ background:#16a34a; }
        .ta-btn-secondary{ display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:8px;background:#f3f4f6;color:#374151;padding:10px 20px;font-size:.875rem;font-weight:600;cursor:pointer;transition:background .15s;border:none; }
        .ta-btn-secondary:hover{ background:#e5e7eb; }
        .ta-btn-sm-secondary{ display:inline-flex;align-items:center;justify-content:center;gap:6px;border-radius:8px;background:#f3f4f6;color:#374151;padding:8px 14px;font-size:.75rem;font-weight:600;cursor:pointer;transition:background .15s;border:none; }
        .ta-btn-sm-secondary:hover{ background:#e5e7eb; }
        /* Card */
        .ta-card{ background:#fff;border-radius:12px;border:1px solid #e5e7eb;box-shadow:0 1px 2px 0 rgba(16,24,40,.05);overflow:hidden; }
        .ta-card-header{ padding:16px 20px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between; }
        .ta-card-body{ padding:20px; }
        /* Alert flash */
        .ta-alert{ display:flex;align-items:flex-start;gap:12px;border-radius:12px;padding:16px;border:1px solid; }
        .ta-alert-success{ background:#f0fdf4;border-color:#bbf7d0;color:#15803d; }
        .ta-alert-error  { background:#fef2f2;border-color:#fecaca;color:#b91c1c; }
        .ta-alert-warning{ background:#fffbeb;border-color:#fde68a;color:#b45309; }
        .ta-alert-info   { background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8; }
        /* Table */
        .ta-table{ width:100%;font-size:.875rem; }
        .ta-table thead th{ padding:12px 16px;text-align:left;font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#6b7280;background:#f9fafb;border-bottom:1px solid #f3f4f6; }
        .ta-table tbody td{ padding:14px 16px;color:#374151;border-bottom:1px solid #f3f4f6; }
        .ta-table tbody tr:hover{ background:#f9fafb; }
        /* Modal */
        .ta-modal-backdrop{ position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(17,24,39,.7);backdrop-filter:blur(4px);padding:16px; }
        .ta-modal{ position:relative;background:#fff;border-radius:16px;box-shadow:0 20px 24px -4px rgba(16,24,40,.08);width:100%;max-width:512px;max-height:90vh;display:flex;flex-direction:column;overflow:hidden; }
        .ta-modal-header{ display:flex;align-items:center;justify-content:space-between;padding:16px 24px;border-bottom:1px solid #f3f4f6;flex-shrink:0; }
        .ta-modal-body{ padding:20px 24px;overflow-y:auto;flex:1; }
        .ta-modal-footer{ display:flex;justify-content:flex-end;gap:12px;padding:16px 24px;border-top:1px solid #f3f4f6;flex-shrink:0; }
        /* Input */
        .ta-input{ width:100%;border-radius:8px;border:1px solid #d1d5db;padding:10px 14px;font-size:.875rem;color:#111827;outline:none;transition:border-color .15s,box-shadow .15s; }
        .ta-input::placeholder{ color:#9ca3af; }
        .ta-input:focus{ border-color:#465fff;box-shadow:0 0 0 3px rgba(70,95,255,.1); }
        /* Dropdown */
        .ta-dropdown{ position:absolute;z-index:50;margin-top:8px;border-radius:16px;border:1px solid #e5e7eb;background:#fff;padding:12px;box-shadow:0 12px 16px -4px rgba(16,24,40,.08); }
        /* Icon */
        .icon-svg{ width:20px;height:20px;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;fill:none;display:inline-block;vertical-align:middle; }
        @keyframes ping{ 75%,100%{ transform:scale(2);opacity:0; } }
        .animate-ping{ animation:ping 1s cubic-bezier(0,0,.2,1) infinite; }
        .animate-pulse{ animation:pulse 2s cubic-bezier(.4,0,.6,1) infinite; }
        @keyframes pulse{ 0%,100%{ opacity:1; } 50%{ opacity:.5; } }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-100 text-gray-900 antialiased min-h-screen flex" x-data="{sidebarOpen:false}">

    <!-- SIDEBAR (TailAdmin White Style) -->
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-[99999] w-[290px] bg-white border-r border-gray-200 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 shadow-sm overflow-y-auto no-scrollbar">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-5 h-16 border-b border-gray-100 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="/logo.png" alt="Logo" class="w-8 h-8 object-contain shrink-0">
                <div>
                    <img src="/logo1.png" alt="Hidayatullah" class="h-5 w-auto object-contain">
                    <span class="text-[9px] text-gray-400 font-semibold tracking-wide">Hidayatullah Tuksongo</span>
                </div>
            </a>
            <button @click="sidebarOpen=false" class="ml-auto lg:hidden text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Nav -->
        <div class="flex-1 px-4 py-5 overflow-y-auto no-scrollbar">
            @php $currentRole = auth()->user()->role ?? 'admin'; @endphp

            {{-- ===== DASHBOARD (always visible, top-level) ===== --}}
            <ul class="flex flex-col gap-0.5 mb-4">
                <li><a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'menu-item-active' : 'menu-item-inactive' }}">
                    <span class="{{ request()->routeIs('admin.dashboard') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25A2.25 2.25 0 003.25 5.5v3.5A2.25 2.25 0 005.5 11.25H9A2.25 2.25 0 0011.25 9V5.5A2.25 2.25 0 009 3.25H5.5zm9.25 0A2.25 2.25 0 0012.5 5.5v3.5A2.25 2.25 0 0014.75 11.25h3.5A2.25 2.25 0 0020.5 9V5.5a2.25 2.25 0 00-2.25-2.25h-3.5zm-9.25 9A2.25 2.25 0 003.25 14.5v3.5A2.25 2.25 0 005.5 20.25H9A2.25 2.25 0 0011.25 18v-3.5A2.25 2.25 0 009 12.25H5.5zm9.25 0A2.25 2.25 0 0012.5 14.5v3.5A2.25 2.25 0 0014.75 20.25h3.5a2.25 2.25 0 002.25-2.25v-3.5a2.25 2.25 0 00-2.25-2.25h-3.5z"/></svg></span>
                    Dashboard
                </a></li>
            </ul>

            @if($currentRole !== 'bendahara')
            {{-- ===== MENU UTAMA (Dropdown) ===== --}}
            <div class="mb-2" x-data="{ open: {{ request()->routeIs('admin.berita.*', 'admin.psb.*', 'admin.users.*', 'admin.settings.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors duration-150 group">
                    <span class="mi-icon-inactive group-hover:text-gray-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg></span>
                    <span class="flex-1 text-left">Menu Utama</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div x-show="open" x-collapse x-cloak>
                    <ul class="flex flex-col gap-0.5 mt-1 ml-3 pl-3 border-l-2 border-gray-100">
                        <li><a href="{{ route('admin.berita.index') }}" class="menu-item {{ request()->routeIs('admin.berita.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.berita.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg></span>
                            Kelola Berita
                        </a></li>
                        <li><a href="{{ route('admin.psb.index') }}" class="menu-item {{ request()->routeIs('admin.psb.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.psb.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></span>
                            Pendaftar PSB
                        </a></li>
                        @if(auth()->user() && auth()->user()->isSuperAdmin())
                        <li><a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.users.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                            Manajemen User
                        </a></li>
                        @endif
                        <li><a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.settings.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg></span>
                            Pengaturan Web
                        </a></li>
                        @if(auth()->user() && auth()->user()->isSuperAdmin())
                        <li><a href="{{ route('admin.backup.sql') }}" class="menu-item menu-item-inactive hover:text-emerald-700" title="Unduh File Salinan Database (.sql)">
                            <span class="mi-icon-inactive text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/><path d="M12 12v5m0 0l-2-2m2 2l2-2"/></svg></span>
                            Backup Database
                        </a></li>
                        @endif
                    </ul>
                </div>
            </div>
            @endif

            {{-- ===== MANAJEMEN SANTRI (Dropdown) ===== --}}
            <div class="mb-2" x-data="{ open: {{ request()->routeIs('admin.siswa.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors duration-150 group">
                    <span class="mi-icon-inactive group-hover:text-gray-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg></span>
                    <span class="flex-1 text-left">Manajemen Santri</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div x-show="open" x-collapse x-cloak>
                    <ul class="flex flex-col gap-0.5 mt-1 ml-3 pl-3 border-l-2 border-gray-100">
                        <li><a href="{{ route('admin.siswa.index') }}" class="menu-item {{ request()->routeIs('admin.siswa.index', 'admin.siswa.show', 'admin.siswa.create', 'admin.siswa.edit') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.siswa.index', 'admin.siswa.show', 'admin.siswa.create', 'admin.siswa.edit') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path d="M7 16.5v3.5"/></svg></span>
                            Data Santri Induk
                        </a></li>
                        <li><a href="{{ route('admin.siswa.penempatanKelas') }}" class="menu-item {{ request()->routeIs('admin.siswa.penempatanKelas*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.siswa.penempatanKelas*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></span>
                            Penempatan Santri Baru
                        </a></li>
                        <li><a href="{{ route('admin.siswa.asrama.index') }}" class="menu-item {{ request()->routeIs('admin.siswa.asrama.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.siswa.asrama.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></span>
                            Kelola Asrama &amp; Kamar
                        </a></li>
                        <li><a href="{{ route('admin.siswa.kelas.index') }}" class="menu-item {{ request()->routeIs('admin.siswa.kelas.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.siswa.kelas.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></span>
                            Master Jenjang &amp; Kelas
                        </a></li>
                        <li><a href="{{ route('admin.siswa.waliKelas.index') }}" class="menu-item {{ request()->routeIs('admin.siswa.waliKelas.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.siswa.waliKelas.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                            Atur Wali Kelas
                        </a></li>
                        <li><a href="{{ route('admin.siswa.kenaikanKelas') }}" class="menu-item {{ request()->routeIs('admin.siswa.kenaikanKelas*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.siswa.kenaikanKelas*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="17 11 12 6 7 11"></polyline><polyline points="17 18 12 13 7 18"></polyline></svg></span>
                            Kenaikan Kelas &amp; Promosi
                        </a></li>
                        <li><a href="{{ route('admin.siswa.mutasi.index') }}" class="menu-item {{ request()->routeIs('admin.siswa.mutasi.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.siswa.mutasi.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/><path d="M7 8l-4 4 4 4"/></svg></span>
                            Mutasi Santri
                        </a></li>
                        <li><a href="{{ route('admin.siswa.akunLogin') }}" class="menu-item {{ request()->routeIs('admin.siswa.akunLogin*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.siswa.akunLogin*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg></span>
                            Akun &amp; Login Santri
                        </a></li>
                    </ul>
                </div>
            </div>

            @if(auth()->user() && auth()->user()->canManagePayments())
            {{-- ===== KEUANGAN & PEMBAYARAN (Dropdown) ===== --}}
            <div class="mb-2" x-data="{ open: {{ request()->routeIs('admin.pembayaran.*', 'admin.pengeluaran.*', 'admin.arusKas.*', 'admin.laporanYayasan.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors duration-150 group">
                    <span class="mi-icon-inactive group-hover:text-gray-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></span>
                    <span class="flex-1 text-left">Keuangan</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div x-show="open" x-collapse x-cloak>
                    <ul class="flex flex-col gap-0.5 mt-1 ml-3 pl-3 border-l-2 border-gray-100">
                        <li><a href="{{ route('admin.pembayaran.index') }}" class="menu-item {{ request()->routeIs('admin.pembayaran.index') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.pembayaran.index') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></span>
                            Kasir Pembayaran
                        </a></li>
                        <li><a href="{{ route('admin.pembayaran.tarif.index') }}" class="menu-item {{ request()->routeIs('admin.pembayaran.tarif.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.pembayaran.tarif.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                            Tarif &amp; Biaya Pendidikan
                        </a></li>
                        <li><a href="{{ route('admin.pembayaran.rekapTunggakan') }}" class="menu-item {{ request()->routeIs('admin.pembayaran.rekapTunggakan*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.pembayaran.rekapTunggakan*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></span>
                            Rekap Tunggakan
                        </a></li>
                        <li><a href="{{ route('admin.pengeluaran.index') }}" class="menu-item {{ request()->routeIs('admin.pengeluaran.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.pengeluaran.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                            Kas Keluar (Beban)
                        </a></li>
                        <li><a href="{{ route('admin.arusKas.index') }}" class="menu-item {{ request()->routeIs('admin.arusKas.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.arusKas.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg></span>
                            Arus Kas (Cashflow)
                        </a></li>
                        <li><a href="{{ route('admin.pembayaran.tagihan.index') }}" class="menu-item {{ request()->routeIs('admin.pembayaran.tagihan.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.pembayaran.tagihan.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg></span>
                            Tagihan &amp; Tambahan
                        </a></li>
                        <li><a href="{{ route('admin.pembayaran.potongan.index') }}" class="menu-item {{ request()->routeIs('admin.pembayaran.potongan.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.pembayaran.potongan.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg></span>
                            Keringanan &amp; SKTM
                        </a></li>
                        <li><a href="{{ route('admin.pembayaran.jurnal.index') }}" class="menu-item {{ request()->routeIs('admin.pembayaran.jurnal.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.pembayaran.jurnal.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
                            Buku Kas Matriks
                        </a></li>
                        <li><a href="{{ route('admin.pembayaran.rekapKwitansi') }}" class="menu-item {{ request()->routeIs('admin.pembayaran.rekapKwitansi*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.pembayaran.rekapKwitansi*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
                            Rekap Kwitansi
                        </a></li>
                        <li><a href="{{ route('admin.laporanYayasan.index') }}" class="menu-item {{ request()->routeIs('admin.laporanYayasan.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.laporanYayasan.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/><path d="M5 21h14"/></svg></span>
                            Laporan Pimpinan Pondok Pesantren
                        </a></li>
                    </ul>
                </div>
            </div>
            @endif

            @if($currentRole !== 'bendahara')
            {{-- ===== UJIAN CBT (Dropdown) ===== --}}
            <div class="mb-2" x-data="{ open: {{ request()->routeIs('admin.cbt.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors duration-150 group">
                    <span class="mi-icon-inactive group-hover:text-gray-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span>
                    <span class="flex-1 text-left">Ujian Seleksi (CBT)</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div x-show="open" x-collapse x-cloak>
                    <ul class="flex flex-col gap-0.5 mt-1 ml-3 pl-3 border-l-2 border-gray-100">
                        <li><a href="{{ route('admin.cbt.soal.index') }}" class="menu-item {{ request()->routeIs('admin.cbt.soal.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.cbt.soal.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                            Bank &amp; Template Soal
                        </a></li>
                        <li><a href="{{ route('admin.cbt.pengaturan') }}" class="menu-item {{ request()->routeIs('admin.cbt.pengaturan*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.cbt.pengaturan*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
                            Pengaturan Ujian
                        </a></li>
                        <li><a href="{{ route('admin.cbt.monitoring.index') }}" class="menu-item {{ request()->routeIs('admin.cbt.monitoring.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.cbt.monitoring.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></span>
                            <span class="flex items-center gap-2">Live Monitor <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-green-500 text-white animate-pulse">LIVE</span></span>
                        </a></li>
                        <li><a href="{{ route('admin.cbt.hasil.index') }}" class="menu-item {{ request()->routeIs('admin.cbt.hasil.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            <span class="{{ request()->routeIs('admin.cbt.hasil.*') ? 'mi-icon-active' : 'mi-icon-inactive' }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 20V10M12 20V4M6 20v-6"/></svg></span>
                            Rekap Hasil &amp; Nilai
                        </a></li>
                        <li><a href="{{ route('admin.cbt.dongkrak.index') }}" class="menu-item {{ request()->routeIs('admin.cbt.dongkrak.*') ? 'bg-yellow-50 text-yellow-600' : 'text-yellow-600 hover:bg-yellow-50' }}">
                            <span class="flex-shrink-0 text-yellow-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></span>
                            <span class="flex items-center gap-2">Dongkrak Nilai <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-yellow-500 text-white">BIAR LULUS</span></span>
                        </a></li>
                    </ul>
                </div>
            </div>
            @endif

            {{-- ===== TAUTAN LUAR ===== --}}
            <div class="border-t border-gray-100 pt-4 mt-2">
                <p class="mb-2 px-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Tautan Luar</p>
                <a href="{{ route('home') }}" target="_blank" class="menu-item menu-item-inactive text-xs">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                    Kunjungi Website
                    <svg class="w-3 h-3 ml-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <a href="{{ route('ujian.index') }}" target="_blank" class="menu-item menu-item-inactive text-xs">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/></svg>
                    Portal Ujian CBT
                    <svg class="w-3 h-3 ml-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <a href="{{ route('santri.login') }}" target="_blank" class="menu-item menu-item-inactive text-xs text-emerald-700 hover:text-emerald-800">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Portal Santri (Login)
                    <svg class="w-3 h-3 ml-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>

        <!-- Bottom User Bar -->
        <div class="p-3.5 border-t border-gray-100 bg-gray-50 shrink-0">
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-2.5 flex-1 min-w-0 group" title="Buka Profil & Tanda Tangan Digital (TTD)">
                    <div class="w-9 h-9 rounded-full bg-brand-500 group-hover:bg-brand-600 text-white font-bold flex items-center justify-center text-sm shrink-0 transition">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800 truncate group-hover:text-brand-600 transition">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-[11px] text-gray-500 truncate">{{ auth()->user()?->jabatan ?: (auth()->user()?->email ?? '') }}</p>
                    </div>
                </a>
                <a href="{{ route('admin.profile') }}" class="p-1.5 text-gray-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition" title="Profil & TTD Digital">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Keluar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- BACKDROP MOBILE -->
    <div x-show="sidebarOpen" @click="sidebarOpen=false" x-cloak
         class="fixed inset-0 bg-gray-900/50 z-[99998] lg:hidden"
         x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0 lg:ml-[290px] min-h-screen">
        
        <!-- TOP NAVBAR (TailAdmin Style) -->
        <header class="sticky top-0 z-[999] flex w-full bg-white border-b border-gray-200" style="box-shadow:0 1px 2px 0 rgba(16,24,40,.05);">
            <div class="flex items-center justify-between w-full px-4 lg:px-6 h-16">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen=!sidebarOpen"
                        class="flex items-center justify-center w-10 h-10 text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-gray-700 transition">
                        <svg width="16" height="12" viewBox="0 0 16 12" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25L14.6666 10.25C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z" fill="currentColor"/></svg>
                    </button>
                    <div class="relative hidden sm:block">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.16667 3.33334C5.94501 3.33334 3.33334 5.94501 3.33334 9.16668C3.33334 12.3883 5.94501 15 9.16667 15C12.3883 15 15 12.3883 15 9.16668C15 5.94501 12.3883 3.33334 9.16667 3.33334ZM1.66667 9.16668C1.66667 5.02454 5.02454 1.66667 9.16667 1.66667C13.3088 1.66667 16.6667 5.02454 16.6667 9.16668C16.6667 10.9634 16.0336 12.6121 14.9755 13.9041L18.0355 16.9641C18.3609 17.2895 18.3609 17.8172 18.0355 18.1426C17.7101 18.468 17.1824 18.468 16.857 18.1426L13.797 15.0826C12.505 16.1407 10.8563 16.7733 9.16667 16.7733C5.02454 16.7733 1.66667 13.4155 1.66667 9.16668Z" fill="currentColor"/></svg>
                        </span>
                        <input type="text" placeholder="Ketik untuk mencari..." class="w-60 pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-transparent placeholder-gray-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 text-xs font-semibold text-green-600 bg-green-50 border border-green-200 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse inline-block"></span>
                        Sistem Aktif · TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}
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
        </div>
    </header>

        <!-- FLASH NOTIFICATION (TailAdmin Alert Style) -->
        @if(session('success'))
            <div class="px-4 lg:px-6 pt-4" x-data="{show:true}" x-show="show" x-transition>
                <div class="ta-alert ta-alert-success">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <p class="flex-1 text-sm font-medium">{{ session('success') }}</p>
                    <button @click="show=false" class="flex-shrink-0 text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="px-4 lg:px-6 pt-4" x-data="{show:true}" x-show="show" x-transition>
                <div class="ta-alert ta-alert-error">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <p class="flex-1 text-sm font-medium">{{ session('error') }}</p>
                    <button @click="show=false" class="flex-shrink-0 text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="px-4 lg:px-6 pt-4" x-data="{show:true}" x-show="show" x-transition>
                <div class="ta-alert ta-alert-error">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold mb-1">Perhatian! Terdapat kesalahan:</p>
                        <ul class="text-xs space-y-0.5 list-disc list-inside">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                    <button @click="show=false" class="flex-shrink-0 text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- PAGE CONTENT -->
        <main class="flex-1 p-4 lg:p-6">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="px-6 py-4 bg-white border-t border-gray-100 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Pondok Pesantren Hidayatullah Tuksongo. Sistem Informasi Pesantren.
        </footer>
    </div>

    <script>
        // SweetAlert2 Toast (TailAdmin rounded style)
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4500,
            timerProgressBar: true,
            customClass: { popup: 'rounded-xl text-sm font-sans' },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        @if(session('success'))
            Toast.fire({ icon:'success', title:"{{ addslashes(session('success')) }}" });
        @endif
        @if(session('error'))
            Swal.fire({ icon:'error', title:'Perhatian', text:"{{ addslashes(session('error')) }}", confirmButtonColor:'#465fff', confirmButtonText:'Mengerti', customClass:{popup:'rounded-2xl font-sans text-sm'} });
        @endif
        @if(session('warning'))
            Toast.fire({ icon:'warning', title:"{{ addslashes(session('warning')) }}" });
        @endif
        @if(session('info'))
            Toast.fire({ icon:'info', title:"{{ addslashes(session('info')) }}" });
        @endif
    </script>
    @yield('scripts')
</body>
</html>

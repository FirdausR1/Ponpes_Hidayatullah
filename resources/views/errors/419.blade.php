<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 — Sesi Kedaluwarsa | Pondok Pesantren Hidayatullah</title>
    <link rel="icon" href="/logo.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4 selection:bg-brand-500 selection:text-white">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-100 p-8 text-center">
        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <span class="inline-block px-3 py-1 bg-amber-100/70 text-amber-700 text-xs font-semibold rounded-full uppercase tracking-wider mb-2">
            Error 419 &bull; Sesi Kedaluwarsa
        </span>

        <h1 class="text-2xl font-bold text-slate-800 tracking-tight mt-1">
            Halaman Telah Kedaluwarsa
        </h1>

        <p class="text-sm text-slate-500 mt-3 leading-relaxed">
            Sesi keamanan form Anda telah habis karena halaman dibuka terlalu lama atau server baru saja dimuat ulang. Silakan refresh halaman untuk melanjutkan.
        </p>

        <div class="mt-6 space-y-2.5">
            <button onclick="window.location.reload();" 
                class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-md shadow-blue-500/20 transition active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Muat Ulang Halaman (Refresh)</span>
            </button>

            <a href="{{ route('login') }}" 
                class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium transition">
                <span>Kembali ke Halaman Login</span>
            </a>
        </div>

        <div class="mt-6 pt-5 border-t border-slate-100 text-xs text-slate-400">
            Pondok Pesantren Hidayatullah Tuksongo
        </div>
    </div>
</body>
</html>

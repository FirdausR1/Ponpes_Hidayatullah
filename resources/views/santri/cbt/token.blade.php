<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Ujian CBT — {{ $exam->mata_pelajaran }}</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4 font-sans antialiased text-slate-800">
    <div class="max-w-md w-full bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200 text-center space-y-6">
        <div class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-200 text-amber-700 mx-auto flex items-center justify-center text-3xl shadow-xs">
            🔑
        </div>

        <div>
            <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 uppercase tracking-wider">
                Verifikasi Sesi CBT Madrasah
            </span>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 mt-2">
                {{ $exam->mata_pelajaran }}
            </h1>
            <p class="text-xs text-slate-500 font-mono mt-1">{{ $exam->nama_ujian }}</p>
        </div>

        @if(session('error'))
        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-semibold">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs text-left space-y-2">
            <div class="flex justify-between text-slate-600">
                <span>Nama Santri:</span>
                <strong class="text-slate-900 font-bold uppercase">{{ $student->nama_lengkap }}</strong>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>NIS / Peserta:</span>
                <strong class="font-mono text-slate-900">{{ $student->nis }}</strong>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Durasi Ujian:</span>
                <strong class="font-mono text-slate-900">{{ $exam->durasi_menit }} Menit ({{ $exam->jumlah_soal }} Soal)</strong>
            </div>
        </div>

        <form action="{{ route('santri.cbt.room', $exam->id) }}" method="GET" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Masukkan Token Ujian dari Pengawas *
                </label>
                <input type="text" name="token" required maxlength="10" autofocus placeholder="Contoh: WX8K2M" class="w-full text-center text-2xl font-mono font-bold tracking-widest uppercase py-3 px-4 rounded-xl border-2 border-slate-300 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100 outline-none transition">
                <span class="text-[11px] text-slate-400 mt-1.5 block">Minta kode token kepada pengawas ujian di ruang kelas Anda.</span>
            </div>

            <div class="pt-2 flex items-center gap-2">
                <a href="{{ route('santri.cbt.index') }}" class="w-1/3 py-3 px-4 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-bold transition">
                    &larr; Batal
                </a>
                <button type="submit" class="w-2/3 py-3 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-md transition">
                    Verifikasi &amp; Masuk Ujian &rarr;
                </button>
            </div>
        </form>
    </div>
</body>
</html>

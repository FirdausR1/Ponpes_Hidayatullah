<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian CBT Madrasah — {{ $student->nama_lengkap }} (NIS: {{ $student->nis }})</title>
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
                <a href="{{ route('santri.cbt.index') }}" class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-emerald-700 text-white text-xs font-semibold whitespace-nowrap shadow-xs flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                    Ujian CBT
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
    <main class="flex-1 max-w-6xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        @if(session('success'))
            <div class="p-3.5 sm:p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 sm:p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Banner Header Santri -->
        <div class="rounded-3xl bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-semibold text-emerald-300">
                        <span>Portal CBT Siswa</span>
                        <span>•</span>
                        <span>Kurikulum Merdeka / Standar Ujian Madrasah</span>
                    </div>
                    <h1 class="text-xl sm:text-3xl font-extrabold tracking-tight">
                        Ujian Berbasis Komputer (CBT)
                    </h1>
                    <p class="text-xs sm:text-sm text-emerald-100/80 max-w-2xl leading-relaxed">
                        Selamat datang, ananda <strong class="text-white font-bold uppercase">{{ $student->nama_lengkap }}</strong>. Halaman ini memuat daftar sesi evaluasi dan ujian madrasah resmi yang ditugaskan khusus untuk kelas Anda.
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/15 text-xs space-y-1.5 shrink-0 min-w-[200px]">
                    <div class="flex justify-between gap-3 text-emerald-200">
                        <span>NIS Santri:</span>
                        <strong class="font-mono text-white">{{ $student->nis }}</strong>
                    </div>
                    <div class="flex justify-between gap-3 text-emerald-200">
                        <span>Kelas:</span>
                        <strong class="font-mono text-white">{{ $student->kelas ?: '-' }}</strong>
                    </div>
                    <div class="flex justify-between gap-3 text-emerald-200">
                        <span>Total Sesi:</span>
                        <strong class="font-bold text-emerald-300">{{ $results->count() }} Ujian</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aturan & Anti-Cheat Notice -->
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3 text-xs text-amber-900">
            <span class="text-xl">⚠️</span>
            <div class="space-y-1">
                <span class="font-bold block">Tata Tertib &amp; Sistem Pengawasan Otomatis (Anti-Cheat):</span>
                <p class="text-amber-800 leading-relaxed">
                    1. Ujian wajib dikerjakan dalam mode layar penuh (<em>Fullscreen</em>).<br>
                    2. Dilarang berpindah ke tab browser lain, membuka aplikasi lain, atau meminimalisir layar.<br>
                    3. Setiap indikasi keluar layar atau beralih aplikasi akan dicatat otomatis ke ruang pengawas ujian.<br>
                    4. Jika pelanggaran melebihi batas toleransi, ujian Anda akan otomatis dikunci oleh sistem dan harus melapor kepada pengawas ruang.
                </p>
            </div>
        </div>

        <!-- Daftar Sesi Ujian Madrasah untuk Santri Ini -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2 h-4 rounded-full bg-emerald-600"></span>
                Daftar Ujian Anda ({{ $results->count() }})
            </h2>

            @if($results->isEmpty())
            <div class="p-12 text-center bg-white rounded-3xl border border-slate-200 space-y-3">
                <svg class="w-12 h-12 text-slate-300 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <h3 class="text-sm font-bold text-slate-700">Belum Ada Sesi Ujian yang Ditugaskan</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    Saat ini belum ada jadwal ujian madrasah aktif untuk kelas Anda. Jadwal akan muncul otomatis saat admin atau guru pengampu mendaftarkan sesi ujian.
                </p>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($results as $res)
                @php
                    $exam = $res->exam;
                    $status = $res->status_pengerjaan ?? 'Belum Mulai';
                    $maxAttempts = $exam->max_attempts ?? 1;
                    $currentAttempt = $res->attempt_number ?? 0;
                    $isLocked = ($status === 'Terkunci' || ($res->jumlah_pelanggaran >= ($exam->max_violations ?? 3) && $status !== 'Selesai'));
                    $canTakeExam = ($exam->status === 'Aktif' && !$isLocked && ($status !== 'Selesai' || $currentAttempt < $maxAttempts));
                @endphp

                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-theme-xs hover:border-emerald-300 transition space-y-4 flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $exam->jenjang === 'MA' ? 'bg-emerald-100 text-emerald-800' : 'bg-sky-100 text-sky-800' }}">
                                Kelas {{ $exam->tingkat_kelas }} {{ $exam->jenjang }} &bull; {{ $exam->jurusan }}
                            </span>

                            @if($isLocked)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                    🔒 Terkunci ({{ $res->jumlah_pelanggaran }}x Pelanggaran)
                                </span>
                            @elseif($status === 'Mengerjakan')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-300 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-ping"></span>
                                    Sedang Berjalan
                                </span>
                            @elseif($status === 'Selesai')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    ✓ Selesai
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                    Belum Mulai
                                </span>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition">
                                {{ $exam->mata_pelajaran }}
                            </h3>
                            <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $exam->nama_ujian }}</p>
                            @if($exam->nama_guru)
                            <p class="text-[11px] text-slate-400 mt-1">Guru Penguji: <span class="text-slate-700 font-semibold">{{ $exam->nama_guru }}</span></p>
                            @endif
                        </div>

                        <!-- Info Grid Parameter -->
                        <div class="grid grid-cols-3 gap-2 bg-slate-50 p-3 rounded-2xl border border-slate-100 text-center">
                            <div>
                                <span class="text-[10px] text-slate-400 block uppercase font-semibold">Jumlah Soal</span>
                                <span class="text-xs font-bold font-mono text-slate-800">{{ $exam->jumlah_soal }} Butir</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block uppercase font-semibold">Durasi</span>
                                <span class="text-xs font-bold font-mono text-slate-800">{{ $exam->durasi_menit }} Menit</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block uppercase font-semibold">Percobaan</span>
                                <span class="text-xs font-bold font-mono text-purple-700">{{ $currentAttempt }}/{{ $maxAttempts }}x</span>
                            </div>
                        </div>

                        <!-- Hasil Nilai jika sudah selesai dan diizinkan tampil -->
                        @if($status === 'Selesai' && $exam->tampilkan_nilai && $res->nilai !== null)
                        <div class="p-3 rounded-2xl {{ $res->nilai >= $exam->kkm ? 'bg-emerald-50 border border-emerald-200 text-emerald-900' : 'bg-rose-50 border border-rose-200 text-rose-900' }} flex items-center justify-between text-xs">
                            <div>
                                <span class="text-[10px] font-semibold block uppercase">Skor Perolehan Anda:</span>
                                <strong class="text-lg font-mono font-bold">{{ number_format($res->nilai, 2) }}</strong>
                                <span class="text-[10px] ml-1 font-semibold">(KKM: {{ number_format($exam->kkm, 1) }})</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-xl text-xs font-bold {{ $res->nilai >= $exam->kkm ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                                {{ $res->nilai >= $exam->kkm ? 'TUNTAS / LULUS' : 'REMEDIAL' }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Tombol Aksi Masuk Ruang Ujian -->
                    <div class="pt-3 border-t border-slate-100">
                        @if($isLocked)
                            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-center space-y-1">
                                <span class="text-xs font-bold text-rose-700 block">Ujian Terkunci Otomatis</span>
                                <p class="text-[11px] text-rose-600">Terdeteksi melakukan pelanggaran aturan anti-cheat ({{ $res->jumlah_pelanggaran }}x). Segera hubungi pengawas ujian.</p>
                            </div>
                        @elseif($canTakeExam)
                            <a href="{{ route('santri.cbt.room', $exam->id) }}" class="w-full py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold text-center block shadow-md transition">
                                @if($status === 'Mengerjakan')
                                    Lanjutkan Pengerjaan Ujian &rarr;
                                @elseif($status === 'Selesai' && $currentAttempt < $maxAttempts)
                                    Mulai Percobaan ke-{{ $currentAttempt + 1 }} (Remedial) &rarr;
                                @else
                                    Mulai Kerjakan Ujian &rarr;
                                @endif
                            </a>
                        @else
                            <button type="button" disabled class="w-full py-2.5 px-4 rounded-xl bg-slate-100 text-slate-400 text-xs font-bold text-center block cursor-not-allowed">
                                Selesai (Batas Kesempatan Habis)
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 px-6 text-center text-xs text-slate-400 mt-12">
        &copy; {{ date('Y') }} Pondok Pesantren Hidayatullah Temanggung &bull; Sistem CBT Ujian Madrasah Online
    </footer>
</body>
</html>

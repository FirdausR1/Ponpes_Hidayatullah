<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pendaftaran Santri Baru — Ponpes Hidayatullah Tuksongo</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=EB+Garamond:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f6f9f7; color: #1e293b; }
        .icon-svg {
            width: 20px; height: 20px; stroke: currentColor; stroke-width: 2;
            stroke-linecap: round; stroke-linejoin: round; fill: none;
            display: inline-block; vertical-align: middle;
        }
    </style>
</head>
@php
    $hotlinePanitia = \App\Models\Setting::get('kontak_hotline', \App\Models\Setting::get('kontak_hotline_1', '0813-9110-9966'));
    $cleanHotline = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $hotlinePanitia));
@endphp
<body class="min-h-screen py-10 px-4 sm:px-6 flex flex-col items-center">

    <div class="max-w-3xl w-full space-y-6">

        <!-- Top Navigation -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-200/80">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="/logo.png" alt="Logo Pondok Pesantren Hidayatullah" class="w-10 h-10 object-contain shrink-0 group-hover:scale-105 transition">
                <div class="flex flex-col justify-center">
                    <img src="/logo1.png" alt="معهد هداية الله للتربية الإسلامية" class="h-7 w-auto object-contain object-left">
                    <span class="font-sans text-[10px] font-semibold text-slate-600 tracking-tight">Pondok Pesantren Hidayatullah Tuksongo</span>
                </div>
            </a>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 hover:text-emerald-800 transition shadow-2xs">
                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span>Kembali ke Beranda</span>
                </a>
                <a href="https://wa.me/{{ $cleanHotline }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-full border border-emerald-200 shadow-2xs hover:bg-emerald-100 transition">
                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    Bantuan Panitia
                </a>
            </div>
        </div>

        <!-- Header Card -->
        <div class="bg-gradient-to-r from-[#0d3b1e] to-[#1a6b38] text-white p-6 sm:p-8 rounded-3xl shadow-lg relative overflow-hidden text-center">
            <div class="flex items-center justify-center gap-3 mb-4">
                <img src="/logo.png" alt="Logo" class="w-12 h-12 object-contain">
                <div class="text-left flex flex-col justify-center">
                    <img src="/logo1.png" alt="معهد هداية الله" class="h-8 w-auto object-contain brightness-0 invert">
                    <span class="text-[10px] text-emerald-100 font-medium">Pondok Pesantren Hidayatullah Tuksongo</span>
                </div>
            </div>
            <span class="text-xs uppercase tracking-widest text-[#e8cc5a] font-bold block mb-1">Layanan Informasi Mandiri Santri Baru</span>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold">Cek Status Verifikasi Pendaftaran</h1>
            <p class="text-xs sm:text-sm text-emerald-100 mt-1 max-w-xl mx-auto">
                Pantau proses verifikasi berkas, pas foto, dan pembayaran pendaftaran calon santri baru secara *real-time* tanpa perlu login akun.
            </p>

            <!-- Search Form -->
            <form action="{{ route('psb.checkStatus') }}" method="GET" class="mt-6 max-w-xl mx-auto">
                <div class="flex flex-col sm:flex-row gap-2 bg-white/10 p-1.5 rounded-2xl backdrop-blur-md border border-white/20">
                    <input type="text" name="identifier" value="{{ $identifier ?? '' }}" required placeholder="Masukkan No. Registrasi (cth: PSB-2025-0001) atau No. WA..." class="flex-1 px-4 py-3 bg-white text-slate-800 placeholder:text-slate-400 text-xs sm:text-sm rounded-xl outline-none shadow-inner">
                    <button type="submit" class="px-6 py-3 bg-amber-400 hover:bg-amber-300 text-emerald-950 font-bold text-xs sm:text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2 shrink-0">
                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        Cek Status
                    </button>
                </div>
            </form>
        </div>

        @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-medium shadow-sm">
            <svg class="icon-svg w-5 h-5 text-emerald-600 shrink-0" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($searched && !$registration)
        <!-- Result Not Found -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm text-center space-y-4">
            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto">
                <svg class="icon-svg w-8 h-8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-800">Data Pendaftaran Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto leading-relaxed">
                    Nomor registrasi atau kontak "<strong>{{ $identifier }}</strong>" tidak cocok dengan data pendaftar kami. Pastikan nomor registrasi sesuai dengan tanda terima atau gunakan nomor WhatsApp yang diisi saat mendaftar.
                </p>
            </div>
            <div class="pt-2 flex flex-wrap justify-center gap-3">
                <a href="{{ route('psb.register') }}" class="px-4 py-2 bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs hover:bg-emerald-800 transition">
                    Daftar Santri Baru Sekarang
                </a>
                <a href="https://wa.me/{{ $cleanHotline }}?text={{ urlencode('Assalamu\'alaikum Panitia PSB, saya ingin menanyakan status pendaftaran dengan nomor: ' . $identifier) }}" target="_blank" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-semibold hover:bg-slate-200 transition">
                    Tanya ke Panitia via WhatsApp
                </a>
            </div>
        </div>
        @elseif($registration)
        <!-- Registration Found Result Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden space-y-6 p-6 sm:p-8">

            <!-- Registration Identification Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    @if($registration->pas_foto)
                    <div class="w-16 h-20 rounded-xl overflow-hidden border-2 {{ ($registration->foto_status ?? 'Sesuai') === 'Perlu Perbaikan' ? 'border-amber-500 ring-2 ring-amber-200' : 'border-red-600' }} shadow-sm bg-red-600 shrink-0">
                        <img src="{{ $registration->pas_foto }}" alt="Pas Foto Santri" class="w-full h-full object-cover">
                    </div>
                    @endif
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-[11px] font-mono font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                {{ $registration->no_registrasi }}
                            </span>
                            <span class="text-[11px] font-bold text-indigo-800 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-200">
                                {{ $registration->gelombang ?? 'Gelombang 1' }}
                            </span>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800 mt-1">{{ $registration->nama_lengkap }}</h2>
                        <p class="text-xs text-slate-500">Jalur: <strong class="text-slate-700">{{ $registration->jalur }}</strong> • Gelombang: <strong class="text-indigo-700 font-bold">{{ $registration->gelombang ?? 'Gelombang 1' }}</strong> • Jenjang: <strong class="text-slate-700">{{ $registration->jenjang }}</strong> • Tgl Daftar: {{ $registration->created_at ? $registration->created_at->format('d M Y') : '—' }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('psb.printCard', ['id' => $registration->id, 'mode' => 'cv']) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        🖨️ Cetak Kartu CV Santri
                    </a>
                    <a href="{{ route('psb.success', $registration->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold rounded-xl transition">
                        <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        Tanda Terima Pendaftaran
                    </a>
                </div>
            </div>

            <!-- BANNER CETAK & UNDUH KARTU CV SANTRI (MANDIRI) -->
            <div class="p-5 sm:p-6 bg-gradient-to-r from-[#0d3b1e] via-[#145a2e] to-[#1a6b38] text-white rounded-2xl shadow-md flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="space-y-1 text-center sm:text-left">
                    <span class="text-[11px] uppercase tracking-wider text-yellow-300 font-bold block flex items-center justify-center sm:justify-start gap-1.5">
                        <svg class="icon-svg w-3.5 h-3.5 text-yellow-300" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                        Kartu Registrasi Resmi Santri Baru
                    </span>
                    <h3 class="text-base sm:text-lg font-bold">Cetak / Unduh Kartu Biodata Santri (Format CV)</h3>
                    <p class="text-xs text-emerald-100 max-w-xl leading-relaxed">
                        Lengkap dengan pas foto 3x4, nomor registrasi, data diri, sekolah asal, data orang tua, dan stempel verifikasi online resmi pesantren. Dapat dicetak langsung atau disimpan ke file PDF.
                    </p>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-2 shrink-0">
                    <a href="{{ route('psb.printCard', ['id' => $registration->id, 'mode' => 'cv']) }}" target="_blank" class="px-4 py-2.5 bg-amber-400 hover:bg-amber-300 text-emerald-950 font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5">
                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        Cetak CV (1 Lembar)
                    </a>
                    <a href="{{ route('psb.printCard', ['id' => $registration->id, 'mode' => 'all']) }}" target="_blank" class="px-3.5 py-2.5 bg-white/15 hover:bg-white/25 text-white font-semibold text-xs rounded-xl border border-white/20 transition">
                        CV + Berkas
                    </a>
                </div>
            </div>

            <!-- STATUS BOX SECTION -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                <!-- 1. Status Seleksi Utama -->
                <div class="rounded-2xl p-5 border {{ $registration->status === 'Diterima' ? 'bg-emerald-50/80 border-emerald-200' : ($registration->status === 'Ditolak' ? 'bg-rose-50/80 border-rose-200' : 'bg-amber-50/80 border-amber-200') }} space-y-2.5">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Status Hasil Seleksi Santri Baru:</span>
                    @php
                        $publishScores = \App\Models\Setting::get('cbt_publish_scores', '0') === '1';
                    @endphp
                    <div class="flex items-start gap-2.5">
                        @if($registration->status === 'Diterima')
                        <div class="w-9 h-9 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                            <svg class="icon-svg w-5 h-5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="space-y-1">
                            <strong class="text-base font-bold text-emerald-900 block">ALHAMDULILLAH, DINYATAKAN DITERIMA</strong>
                            <p class="text-xs text-emerald-800 leading-relaxed">
                                Selamat! Ananda telah resmi diterima sebagai santri baru di Pondok Pesantren Hidayatullah Tuksongo. Silakan membaca petunjuk daftar ulang di bawah ini.
                            </p>
                        </div>
                        @elseif($registration->status === 'Ditolak')
                        <div class="w-9 h-9 rounded-full bg-rose-600 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                            <svg class="icon-svg w-5 h-5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </div>
                        <div class="space-y-1">
                            <strong class="text-base font-bold text-rose-900 block">MOHON MAAF, BELUM DITERIMA</strong>
                            <p class="text-xs text-rose-800 leading-relaxed">
                                Terima kasih atas partisipasi ananda. Hasil seleksi belum memenuhi kuota penerimaan tahun ajaran ini. Tetap semangat menuntut ilmu di tempat terbaik lainnya.
                            </p>
                        </div>
                        @else
                        <div class="w-9 h-9 rounded-full bg-amber-500 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                            <svg class="icon-svg w-5 h-5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div class="space-y-1">
                            <strong class="text-base font-bold text-amber-950 block">MENUNGGU VERIFIKASI / KELULUSAN</strong>
                            <p class="text-xs text-amber-900 leading-relaxed">
                                Berkas & pembayaran Anda sedang ditinjau panitia. Ikuti rangkaian tes CBT online jika belum melaksanakannya.
                            </p>
                            <div class="pt-1 flex flex-wrap gap-2 text-[11px] font-semibold">
                                <span class="px-2 py-0.5 rounded bg-white/70 border border-amber-300 text-amber-900">
                                    Tes CBT: {{ $registration->status_ujian ?: 'Belum Ujian' }}
                                </span>
                                @if($registration->status_pembayaran === 'Lunas')
                                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        Biaya Pendaftaran: Lunas
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-300">
                                        Biaya: {{ $registration->bukti_transfer ? 'Menunggu Konfirmasi' : 'Belum Bayar' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 2. Status Pas Foto (3x4) -->
                <div class="rounded-2xl p-5 border {{ ($registration->foto_status ?? 'Sesuai') === 'Perlu Perbaikan' ? 'bg-rose-50/80 border-rose-200' : 'bg-slate-50 border-slate-200' }} space-y-2 flex flex-col justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Status Pas Foto (3x4):</span>
                        <div class="flex items-start gap-2.5 mt-1">
                            @if(($registration->foto_status ?? 'Sesuai') === 'Perlu Perbaikan')
                            <div class="w-8 h-8 rounded-full bg-rose-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            </div>
                            <div>
                                <strong class="text-sm font-bold text-rose-900 block">Foto Perlu Diperbaiki</strong>
                                <p class="text-xs text-rose-700 leading-relaxed mt-0.5">
                                    {{ $registration->foto_catatan ?: 'Pas foto belum memenuhi ketentuan 3x4 / background merah.' }}
                                </p>
                            </div>
                            @else
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </div>
                            <div>
                                <strong class="text-sm font-bold text-slate-800 block">Pas Foto Sesuai Ketentuan</strong>
                                <p class="text-xs text-slate-500 leading-relaxed mt-0.5">
                                    {{ $registration->foto_catatan ?: 'Pas foto santri telah memenuhi aspek rasio 3x4 dan background merah resmi.' }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Toggle Ganti / Perbarui Pas Foto -->
                    <div class="pt-2 border-t border-slate-200/60">
                        <button type="button" onclick="const f = document.getElementById('form-ganti-foto'); f.classList.toggle('hidden'); if(!f.classList.contains('hidden')) f.scrollIntoView({behavior: 'smooth'});" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-900 transition">
                            <svg class="icon-svg w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                            <span>{{ ($registration->foto_status ?? '') === 'Perlu Perbaikan' ? 'Perbaiki Pas Foto Sekarang' : 'Ganti / Perbarui Pas Foto' }}</span>
                        </button>
                    </div>
                </div>

                <!-- 3. Status Dokumen Berkas (KK, KTP, Akta) -->
                @php
                    $isBerkasPerluPerbaikan = ($registration->berkas_status ?? '') === 'Perlu Perbaikan';
                @endphp
                <div class="rounded-2xl p-5 border {{ $isBerkasPerluPerbaikan ? 'bg-rose-50/80 border-rose-200' : 'bg-slate-50 border-slate-200' }} space-y-2 flex flex-col justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Status Dokumen (KK/KTP/Akta):</span>
                        <div class="flex items-start gap-2.5 mt-1">
                            @if($isBerkasPerluPerbaikan)
                            <div class="w-8 h-8 rounded-full bg-rose-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            </div>
                            <div>
                                <strong class="text-sm font-bold text-rose-900 block">Dokumen Perlu Diperbaiki</strong>
                                <p class="text-xs text-rose-700 leading-relaxed mt-0.5 whitespace-pre-line">
                                    {{ $registration->berkas_catatan ?: 'Terdapat dokumen yang buram, kosong, atau orientasi salah.' }}
                                </p>
                            </div>
                            @else
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </div>
                            <div>
                                <strong class="text-sm font-bold text-slate-800 block">Berkas Dokumen Sesuai</strong>
                                <p class="text-xs text-slate-500 leading-relaxed mt-0.5">
                                    {{ $registration->berkas_catatan ?: 'Seluruh berkas dokumen pendaftaran telah terverifikasi jelas & lengkap.' }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Toggle Ganti / Unggah Berkas -->
                    <div class="pt-2 border-t border-slate-200/60">
                        <button type="button" onclick="const f = document.getElementById('form-ganti-berkas'); f.classList.toggle('hidden'); if(!f.classList.contains('hidden')) f.scrollIntoView({behavior: 'smooth'});" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-900 transition">
                            <svg class="icon-svg w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            <span>{{ $isBerkasPerluPerbaikan ? 'Perbaiki / Unggah Ulang Dokumen' : 'Ganti / Perbarui Berkas Dokumen' }}</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- FORM GANTI PAS FOTO (COLLAPSIBLE) -->
            <form id="form-ganti-foto" action="{{ route('psb.reuploadFoto', $registration->id) }}" method="POST" enctype="multipart/form-data" class="hidden p-5 sm:p-6 bg-red-50/90 border-2 border-red-300 rounded-2xl space-y-4 transition">
                @csrf
                <div class="flex items-center justify-between pb-2 border-b border-red-200">
                    <h4 class="text-sm font-bold text-red-950 flex items-center gap-2">
                        <svg class="icon-svg w-4 h-4 text-red-600" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        Unggah Ulang Pas Foto 3x4 Santri (Revisi)
                    </h4>
                    <button type="button" onclick="document.getElementById('form-ganti-foto').classList.add('hidden')" class="text-xs text-red-600 hover:text-red-900 font-bold px-2 py-1 rounded bg-red-100 hover:bg-red-200 transition">
                        ✕ Tutup
                    </button>
                </div>
                <div class="p-3 bg-white rounded-xl border border-red-200 text-xs text-red-900 space-y-1">
                    <span class="font-bold block">Ketentuan Resmi Pas Foto Santri:</span>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px] text-red-800">
                        <li>Memakai baju kemeja putih rapi dan peci hitam (putra) / jilbab putih (putri)</li>
                        <li><strong>Latar Belakang / Background WAJIB MERAH</strong></li>
                        <li>Posisi tegak lurus proporsional 3x4 (bukan selfie/miring/landscape)</li>
                        <li>Format gambar JPG, PNG, atau WEBP (maksimal 10 MB)</li>
                    </ul>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1">Pilih File Pas Foto Baru:</label>
                    <input type="file" name="pas_foto" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700 file:cursor-pointer border border-slate-200 bg-white rounded-xl p-1.5">
                </div>
                <button type="submit" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-2">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    Simpan &amp; Verifikasi Pas Foto Baru
                </button>
            </form>

            <!-- FORM GANTI BERKAS DOKUMEN (COLLAPSIBLE) -->
            <form id="form-ganti-berkas" action="{{ route('psb.reuploadBerkas', $registration->id) }}" method="POST" enctype="multipart/form-data" class="hidden p-5 sm:p-6 bg-amber-50/90 border-2 border-amber-300 rounded-2xl space-y-4 transition">
                @csrf
                <div class="flex items-center justify-between pb-2 border-b border-amber-200">
                    <h4 class="text-sm font-bold text-amber-950 flex items-center gap-2">
                        <svg class="icon-svg w-4 h-4 text-amber-600" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        Unggah Ulang Berkas Dokumen (KK, KTP, Akta)
                    </h4>
                    <button type="button" onclick="document.getElementById('form-ganti-berkas').classList.add('hidden')" class="text-xs text-amber-700 hover:text-amber-900 font-bold px-2 py-1 rounded bg-amber-100 hover:bg-amber-200 transition">
                        ✕ Tutup
                    </button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Pilih Dokumen yang Ingin Diunggah Ulang / Diperbaiki: <span class="text-rose-500">*</span></label>
                        <select name="jenis_berkas" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="file_ktp_ortu">5. KTP Orang Tua / Wali (Foto fisik e-KTP mendatar / landscape)</option>
                            <option value="file_kk">4. Kartu Keluarga (KK) (Lembar lebar mendatar / landscape)</option>
                            <option value="file_akta_kelahiran">3. Akta Kelahiran (Posisi tegak / portrait)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Pilih File Dokumen Asli (JPG, PNG, WEBP, atau PDF): <span class="text-rose-500">*</span></label>
                        <input type="file" name="file_dokumen" accept="image/*,application/pdf" required class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 file:cursor-pointer border border-slate-200 bg-white rounded-xl p-1.5">
                        <span class="text-[11px] text-slate-500 mt-1 block">Pastikan foto tidak buram, ukuran file di atas 25 KB, dan posisi orientasi sesuai. Maks 10 MB.</span>
                    </div>
                    <button type="submit" class="w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-2">
                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        Simpan &amp; Verifikasi Dokumen Baru
                    </button>
                </div>
            </form>

            <!-- 3. BERKAS DOKUMEN HASIL UJIAN SELEKSI MASUK (CBT ONLINE) -->
            @php
                $cbtKkm = (int) \App\Models\Setting::get('cbt_passing_grade', 70);
                $examFinished = ($registration->status_ujian === 'Selesai' && $registration->nilai_ujian !== null);
                $examStarted = ($registration->status_ujian === 'Sedang Ujian');
                $kelulusanStatus = $registration->status_kelulusan;
                $evaluasi = $registration->evaluasiSyaratPenerimaan();
            @endphp

            <div class="rounded-3xl p-6 sm:p-7 border border-slate-200 bg-gradient-to-br from-slate-50/70 via-white to-emerald-50/30 shadow-sm space-y-4">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-lg bg-emerald-800 text-white shadow-xs">
                            <svg class="icon-svg w-5 h-5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Dokumen Resmi Pendaftaran</span>
                            <h3 class="text-base sm:text-lg font-serif font-bold text-slate-900">Berkas Lembar Hasil Ujian CBT &amp; Nilai Santri</h3>
                        </div>
                    </div>

                    @if($examFinished)
                        @if($publishScores)
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ $kelulusanStatus === 'Lulus' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : ($kelulusanStatus === 'Tidak Lulus' ? 'bg-rose-100 text-rose-800 border border-rose-300' : 'bg-amber-100 text-amber-800 border border-amber-300') }} flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full {{ $kelulusanStatus === 'Lulus' ? 'bg-emerald-600' : 'bg-rose-600' }}"></span>
                                {{ $kelulusanStatus === 'Lulus' ? 'Lulus CBT (Nilai ' . $registration->nilai_ujian . ')' : $kelulusanStatus }}
                            </span>
                        @else
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                Lembar Jawaban Terekam
                            </span>
                        @endif
                    @else
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ $examStarted ? 'bg-blue-100 text-blue-800' : 'bg-slate-200 text-slate-700' }} flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full {{ $examStarted ? 'bg-blue-500 animate-ping' : 'bg-slate-500' }}"></span>
                            {{ $examStarted ? 'Sedang Ujian' : 'Belum Ujian CBT' }}
                        </span>
                    @endif
                </div>

                @if($examFinished)
                    <!-- Kartu Berkas Hasil Ujian (Official Document Card) -->
                    <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-2xs space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-mono font-bold uppercase tracking-wider">
                                        Dokumen Sah CBT-{{ date('y') }}
                                    </span>
                                    @if($publishScores)
                                        <span class="text-xs text-slate-400">&bull;</span>
                                        <span class="text-xs text-slate-500 font-medium">Standar KKM: {{ $cbtKkm }} Poin</span>
                                    @endif
                                </div>
                                <h4 class="text-base font-bold text-slate-900">Lembar Bukti Ujian Seleksi Masuk (CBT Online)</h4>
                                <p class="text-xs text-slate-600 leading-relaxed max-w-xl">
                                    @if($publishScores)
                                        Nilai akhir ujian seleksi, rincian skor per mata pelajaran, serta stempel verifikasi online telah tersimpan dalam berkas resmi pendaftaran. Anda dapat melihat atau mengunduh lembar berkas ini dalam format PDF siap cetak.
                                    @else
                                        Ujian Masuk Seleksi Santri Baru telah berhasil Anda selesaikan dan lembar jawaban tersimpan aman di sistem pendaftaran. Nilai ujian dan status kelulusan saat ini <strong>dirahasiakan sementara</strong> dan akan dibuka serentak oleh Panitia PSB.
                                    @endif
                                </p>
                            </div>

                            <!-- Tombol Aksi Buka Berkas -->
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 shrink-0 w-full sm:w-auto">
                                @if($publishScores)
                                    <a href="{{ route('psb.printCard', ['id' => $registration->id, 'mode' => 'ujian']) }}" target="_blank" class="px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-2 text-center">
                                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                        <span>Cetak Lembar Nilai CBT</span>
                                    </a>
                                @endif
                                <a href="{{ route('psb.printCard', ['id' => $registration->id, 'mode' => 'all']) }}" target="_blank" class="px-3.5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl transition flex items-center justify-center gap-1.5 text-center">
                                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                    <span>{{ $publishScores ? 'Berkas Lengkap (CV + Nilai)' : 'Cetak Bukti Pendaftaran' }}</span>
                                </a>
                            </div>
                        </div>

                        <!-- Ringkasan Singkat Nilai Ujian (Compact) -->
                        <div class="pt-3 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs text-center">
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Status Nilai CBT</span>
                                @if($publishScores)
                                    <span class="text-2xl font-black font-serif {{ $registration->nilai_ujian >= $cbtKkm ? 'text-emerald-700' : 'text-rose-600' }}">
                                        {{ $registration->nilai_ujian }}
                                    </span>
                                @else
                                    <span class="text-sm font-bold text-amber-700 block mt-1">
                                        🔒 Dirahasiakan
                                    </span>
                                @endif
                            </div>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Status Kelulusan</span>
                                @if($publishScores)
                                    <span class="text-sm font-bold block mt-1 {{ $kelulusanStatus === 'Lulus' ? 'text-emerald-700' : 'text-rose-600' }}">
                                        {{ $kelulusanStatus }}
                                    </span>
                                @else
                                    <span class="text-xs font-semibold text-slate-500 block mt-1">
                                        Menunggu Panitia
                                    </span>
                                @endif
                            </div>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Status Pembayaran</span>
                                <span class="text-xs font-bold block mt-1 {{ $registration->bukti_transfer ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $registration->bukti_transfer ? '✓ Terverifikasi Lunas' : 'Belum Ada' }}
                                </span>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Waktu Selesai</span>
                                <span class="text-[11px] font-semibold text-slate-700 block mt-1">
                                    {{ $registration->ujian_selesai_at ? $registration->ujian_selesai_at->format('d/m/Y H:i') : '—' }} WIB
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Santri Belum Mengerjakan Ujian CBT -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/80 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-slate-800">Ujian Seleksi Masuk (CBT Online)</h4>
                                    @if($registration->status_pembayaran === 'Lunas')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            ✓ Pembayaran Terverifikasi (Siap Ujian)
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            ⏳ Menunggu Verifikasi Pembayaran Admin
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    @if($registration->status_pembayaran === 'Lunas')
                                        Bukti infaq pendaftaran (Rp 200.000) telah diverifikasi lunas oleh Admin Pesantren. Calon santri dipersilakan login dan mengikuti Ujian Seleksi Online (CBT).
                                    @else
                                        Ujian CBT akan terbuka secara otomatis setelah bukti pembayaran infaq pendaftaran (Rp 200.000) diverifikasi dan disetujui oleh Admin Pesantren.
                                    @endif
                                </p>
                            </div>
                            @if($registration->status_pembayaran === 'Lunas')
                                <a href="{{ route('ujian.index') }}?no_reg={{ urlencode($registration->no_registrasi) }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-md hover:shadow-lg transition shrink-0">
                                    <span>Mulai Ujian CBT Sekarang</span>
                                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                </a>
                            @else
                                <div class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs border border-slate-200 cursor-not-allowed shrink-0" title="Ujian CBT baru bisa diakses setelah admin memverifikasi bukti transfer Rp 200.000">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <span>Terkunci (Menunggu Verifikasi Admin)</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

            <!-- FORM UPLOAD ULANG FOTO (Tampil jika Perlu Perbaikan ATAU saat diklik tombol ganti foto) -->
            <div id="form-ganti-foto" class="{{ ($registration->foto_status ?? '') === 'Perlu Perbaikan' ? '' : 'hidden' }} p-5 bg-amber-50 rounded-2xl border border-amber-300 space-y-3 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-amber-950 font-bold text-sm">
                        <svg class="icon-svg w-4.5 h-4.5 text-amber-700" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        Formulir Unggah / Pergantian Pas Foto Santri:
                    </div>
                    @if(($registration->foto_status ?? '') !== 'Perlu Perbaikan')
                    <button type="button" onclick="document.getElementById('form-ganti-foto').classList.add('hidden')" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                        Tutup ✕
                    </button>
                    @endif
                </div>
                <p class="text-xs text-amber-800 leading-relaxed">
                    <strong>Ketentuan Pas Foto Resmi:</strong> Memakai baju putih, berpeci hitam (santri putra) atau berjilbab putih (santri putri), latar belakang (background) merah polos, orientasi tegak / 3x4 cm. Maksimal 10 MB (JPG, PNG, WEBP).
                </p>
                <form action="{{ route('psb.reuploadFoto', $registration->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3 items-center pt-1">
                    @csrf
                    <input type="file" name="pas_foto" accept="image/*" required class="w-full text-xs text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white hover:file:bg-amber-700 bg-white p-1 rounded-xl border border-amber-300 cursor-pointer shadow-inner">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-xs transition shrink-0 flex items-center justify-center gap-2">
                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        Unggah & Verifikasi Foto
                    </button>
                </form>
            </div>


            <!-- Rincian Status Dokumen Pendaftaran -->
            <div class="pt-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Status Dokumen &amp; Berkas Pendaftaran:</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1">
                        <span class="text-[10px] text-slate-500 uppercase font-bold block">1. Bukti Bayar (Rp 200rb)</span>
                        <div class="flex items-center gap-1.5">
                            @if($registration->status_pembayaran === 'Lunas')
                                <span class="text-emerald-700 font-bold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    Lunas Terverifikasi
                                </span>
                            @elseif($registration->bukti_transfer)
                                <span class="text-amber-700 font-semibold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    Menunggu Verifikasi Admin
                                </span>
                            @else
                                <span class="text-rose-600 font-medium">Belum Diunggah</span>
                            @endif
                        </div>
                    </div>

                    @php
                        $berkasDetail = json_decode($registration->berkas_detail_json ?? '[]', true) ?: [];
                        $aktaStatus = $berkasDetail['akta']['status'] ?? ($registration->file_akta_kelahiran ? 'Sesuai' : null);
                        $kkStatus = $berkasDetail['kk']['status'] ?? ($registration->file_kk ? 'Sesuai' : null);
                        $ktpStatus = $berkasDetail['ktp']['status'] ?? ($registration->file_ktp_ortu ? 'Sesuai' : null);
                    @endphp

                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1">
                        <span class="text-[10px] text-slate-500 uppercase font-bold block">2. Pas Foto 3x4 (Merah)</span>
                        <div class="flex items-center justify-between gap-1">
                            @if($registration->pas_foto)
                                @if(($registration->foto_status ?? '') === 'Perlu Perbaikan')
                                    <span class="text-rose-600 font-bold flex items-center gap-1 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        Perlu Revisi
                                    </span>
                                @else
                                    <span class="text-emerald-700 font-bold flex items-center gap-1 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Terverifikasi
                                    </span>
                                @endif
                                <a href="{{ $registration->pas_foto }}" target="_blank" class="text-[10px] text-emerald-700 underline font-medium">Lihat</a>
                            @else
                                <span class="text-rose-600 font-medium text-[11px]">Belum Diunggah</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1">
                        <span class="text-[10px] text-slate-500 uppercase font-bold block">3. Akta Kelahiran</span>
                        <div class="flex items-center justify-between gap-1">
                            @if($registration->file_akta_kelahiran)
                                @if($aktaStatus === 'Perlu Perbaikan')
                                    <span class="text-rose-600 font-bold flex items-center gap-1 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        Perlu Revisi
                                    </span>
                                @else
                                    <span class="text-emerald-700 font-bold flex items-center gap-1 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Terverifikasi
                                    </span>
                                @endif
                                <a href="{{ $registration->file_akta_kelahiran }}" target="_blank" class="text-[10px] text-emerald-700 underline font-medium">Lihat</a>
                            @else
                                <span class="text-slate-400 font-medium text-[11px]">Belum Diunggah</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1">
                        <span class="text-[10px] text-slate-500 uppercase font-bold block">4. Kartu Keluarga (KK)</span>
                        <div class="flex items-center justify-between gap-1">
                            @if($registration->file_kk)
                                @if($kkStatus === 'Perlu Perbaikan')
                                    <span class="text-rose-600 font-bold flex items-center gap-1 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        Perlu Revisi
                                    </span>
                                @else
                                    <span class="text-emerald-700 font-bold flex items-center gap-1 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Terverifikasi
                                    </span>
                                @endif
                                <a href="{{ $registration->file_kk }}" target="_blank" class="text-[10px] text-emerald-700 underline font-medium">Lihat</a>
                            @else
                                <span class="text-slate-400 font-medium text-[11px]">Belum Diunggah</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1">
                        <span class="text-[10px] text-slate-500 uppercase font-bold block">5. KTP Orang Tua / Wali</span>
                        <div class="flex items-center justify-between gap-1">
                            @if($registration->file_ktp_ortu)
                                @if($ktpStatus === 'Perlu Perbaikan')
                                    <span class="text-rose-600 font-bold flex items-center gap-1 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        Perlu Revisi
                                    </span>
                                @else
                                    <span class="text-emerald-700 font-bold flex items-center gap-1 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Terverifikasi
                                    </span>
                                @endif
                                <a href="{{ $registration->file_ktp_ortu }}" target="_blank" class="text-[10px] text-emerald-700 underline font-medium">Lihat</a>
                            @else
                                <span class="text-slate-400 font-medium text-[11px]">Belum Diunggah</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1">
                        <span class="text-[10px] text-slate-500 uppercase font-bold block">6. Prestasi / Bansos</span>
                        <div class="flex items-center gap-1.5 text-slate-600">
                            @if($registration->bukti_prestasi_tahfidz || $registration->bukti_bantuan_sosial)
                                <span class="text-emerald-700 font-bold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    Terlampir
                                </span>
                            @else
                                <span class="text-slate-400 font-medium">Jalur Reguler / Tidak Ada</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Petunjuk Daftar Ulang (Tampil jika Lulus / Diterima) -->
            @if($registration->status === 'Diterima' || $kelulusanStatus === 'Lulus')
            <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-3">
                <div class="flex items-center gap-2 text-emerald-950 font-bold text-sm">
                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Tahap Selanjutnya: Prosedur Daftar Ulang Santri Baru</span>
                </div>
                <p class="text-xs text-emerald-900 leading-relaxed">
                    Selamat! Calon santri telah dinyatakan <strong>LULUS / DITERIMA</strong>. Untuk menyelesaikan administrasi, silakan melakukan <strong>Daftar Ulang</strong> dan pelunasan biaya masuk pesantren di Kantor Sekretariat PSB Pesantren atau transfer administrasi bank resmi.
                </p>
                <div class="flex flex-wrap items-center gap-3 pt-1">
                    <a href="https://wa.me/{{ $cleanHotline }}?text={{ urlencode('Assalamu\'alaikum Panitia PSB, santri ananda ' . $registration->nama_lengkap . ' (No. Reg: ' . $registration->no_registrasi . ') telah Lulus. Saya ingin konfirmasi prosedur Daftar Ulang. Terima kasih.') }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-xs transition">
                        <span>Konfirmasi Daftar Ulang via WhatsApp</span>
                    </a>
                    <a href="{{ route('psb.printCard', ['id' => $registration->id, 'mode' => 'all']) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-slate-50 text-emerald-800 border border-emerald-300 rounded-xl text-xs font-semibold transition">
                        <span>Unduh Berkas Lengkap Santri</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Rincian Biodata Santri -->
            <div class="pt-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Ringkasan Data Pendaftar:</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2.5 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">NISN / NIK:</span>
                        <span class="font-mono font-semibold text-slate-800">{{ $registration->nisn }} / {{ $registration->nik ?: '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">Jenis Kelamin:</span>
                        <span class="font-semibold text-slate-800">{{ $registration->jenis_kelamin }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">Tempat, Tanggal Lahir:</span>
                        <span class="font-semibold text-slate-800">{{ $registration->tempat_lahir }}, {{ $registration->tanggal_lahir ? $registration->tanggal_lahir->format('d/m/Y') : '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">Nama Orang Tua / Wali:</span>
                        <span class="font-semibold text-slate-800">{{ $registration->ayah_nama ?: ($registration->ibu_nama ?: $registration->nama_wali) }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">Sekolah Asal:</span>
                        <span class="font-semibold text-slate-800">{{ $registration->nama_sekolah ?: $registration->asal_sekolah }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">No. WhatsApp Terdaftar:</span>
                        <span class="font-mono font-semibold text-slate-800">{{ $registration->no_whatsapp ?: $registration->ayah_telepon }}</span>
                    </div>
                </div>
            </div>

            <!-- Action WhatsApp Hubungi Panitia -->
            <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <span class="text-xs text-slate-500">Butuh bantuan cepat atau konfirmasi berkas?</span>
                <a href="https://wa.me/{{ $cleanHotline }}?text={{ urlencode('Assalamu\'alaikum Panitia PSB Hidayatullah Tuksongo, saya ingin konfirmasi status pendaftaran santri atas nama ' . $registration->nama_lengkap . ' (No. Reg: ' . $registration->no_registrasi . '). Terima kasih.') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    Hubungi Panitia via WhatsApp
                </a>
            </div>

        </div>
        @endif

        <!-- Petunjuk Card -->
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-xs space-y-3 text-xs text-slate-600 leading-relaxed">
            <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg class="icon-svg w-4 h-4 text-emerald-600" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                Informasi Prosedur Verifikasi PSB Online:
            </h4>
            <ul class="list-disc list-inside space-y-1 text-slate-500 pl-1">
                <li>Verifikasi berkas dan mutasi pembayaran dilakukan oleh panitia secara berkala (1x24 jam kerja).</li>
                <li>Calon santri tidak memerlukan akun login/password; simpan <strong>Nomor Registrasi</strong> untuk mengecek status sewaktu-waktu di halaman ini.</li>
                <li>Jika pas foto ditandai <em>"Perlu Perbaikan"</em>, silakan langsung unggah foto baru pada form yang tersedia di atas.</li>
                <li>Pengumuman kelulusan dan rincian daftar ulang akan otomatis muncul di halaman ini setelah status dinyatakan <strong>DITERIMA</strong>.</li>
            </ul>
        </div>

    </div>

</body>
</html>

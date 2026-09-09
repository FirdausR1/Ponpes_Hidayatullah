<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pendaftaran Santri Baru — {{ $registration->nama_lengkap }}</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts: Plus Jakarta Sans & EB Garamond -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=EB+Garamond:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8faf9; }
        .icon-svg {
            width: 20px; height: 20px; stroke: currentColor; stroke-width: 2;
            stroke-linecap: round; stroke-linejoin: round; fill: none;
            display: inline-block; vertical-align: middle;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .print-card { box-shadow: none !important; border: 1px solid #ccc !important; }
        }
    </style>
</head>
<body class="min-h-screen py-10 px-4 sm:px-6 flex flex-col items-center justify-center">

    <div class="max-w-2xl w-full space-y-6">

    <div class="max-w-2xl w-full space-y-6">

        <!-- Back to Home & Print Link -->
        <div class="no-print flex items-center justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-emerald-800 transition">
                <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Beranda
            </a>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('psb.printCard', ['id' => $registration->id, 'mode' => 'cv']) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-lg shadow-xs transition">
                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    🖨️ Cetak Kartu CV Santri
                </a>
                <a href="{{ route('psb.checkStatus', ['no_reg' => $registration->no_registrasi]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-lg hover:bg-emerald-100 transition">
                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Cek Status
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 shadow-xs hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-lg transition">
                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    Cetak Tanda Terima
                </button>
            </div>
        </div>

        <!-- Official Registration Receipt Card -->
        <div class="print-card bg-white rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden">
            
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-[#0d3b1e] via-[#145a2e] to-[#1a6b38] text-white p-6 sm:p-8 text-center relative">
                <div class="w-16 h-16 bg-white rounded-2xl p-2 mx-auto mb-3 shadow-md flex items-center justify-center">
                    <img src="/logo.png" alt="Logo" class="w-full h-full object-contain">
                </div>
                <span class="text-xs uppercase tracking-widest text-[#e8cc5a] font-bold block mb-1">Panitia Penerimaan Santri Baru (PSB)</span>
                <h1 class="text-2xl font-serif font-bold">Bukti Pendaftaran Sementara</h1>
                <p class="text-xs text-emerald-100 mt-1">Pondok Pesantren Hidayatullah Tuksongo Pringsurat Temanggung</p>
                
                <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                    <span class="bg-white/15 backdrop-blur-xs px-4 py-1.5 rounded-full border border-white/20 text-xs font-mono font-bold tracking-wider text-yellow-300">
                        NO. REGISTRASI: {{ $registration->no_registrasi }}
                    </span>
                    <span class="bg-amber-400 text-amber-950 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide flex items-center gap-1.5 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
                        Status: Menunggu Verifikasi
                    </span>
                </div>
            </div>

            <!-- Receipt Body -->
            <div class="p-6 sm:p-8 space-y-6">

                <!-- Alert Box - Status Verifikasi -->
                <div class="p-4 sm:p-5 bg-amber-50/80 rounded-2xl border border-amber-200/90 flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-xs">
                        <svg class="icon-svg w-4.5 h-4.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div class="space-y-1">
                        <strong class="text-sm text-amber-950 font-bold block">Pendaftaran Berhasil Dikirim — Menunggu Verifikasi Panitia</strong>
                        <p class="text-xs text-amber-900 leading-relaxed">
                            Data dan berkas formulir calon santri telah tersimpan di sistem. Saat ini panitia PSB sedang melakukan verifikasi data, kelayakan pas foto, dan bukti pembayaran secara manual.
                        </p>
                    </div>
                </div>

                <!-- How Applicants Get Verification Notice -->
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 text-xs space-y-2.5">
                    <div class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="icon-svg w-4 h-4 text-emerald-700" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        Bagaimana Mengetahui Status Hasil Verifikasi?
                    </div>
                    <div class="grid sm:grid-cols-2 gap-3 text-slate-600 pt-1">
                        <div class="bg-white p-3 rounded-xl border border-slate-200/60 space-y-1">
                            <span class="font-semibold text-emerald-800 block flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                1. Notifikasi WhatsApp Personal
                            </span>
                            <p class="text-[11px] leading-relaxed text-slate-500">
                                Panitia PSB akan mengirim pesan pemberitahuan langsung ke nomor WhatsApp Wali/Calon Santri (<strong>{{ $registration->ayah_telepon ?: $registration->no_whatsapp }}</strong>) mengenai persetujuan berkas atau jika ada foto yang perlu direvisi.
                            </p>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200/60 space-y-1">
                            <span class="font-semibold text-blue-800 block flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                2. Cek Status Mandiri (Tanpa Akun)
                            </span>
                            <p class="text-[11px] leading-relaxed text-slate-500">
                                Anda tidak perlu login akun! Cukup masukkan <strong>No. Registrasi ({{ $registration->no_registrasi }})</strong> atau No. WhatsApp di halaman cek status kami untuk melihat progres verifikasi kapan pun.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Santri Identity Details -->
                <div class="flex flex-col sm:flex-row gap-6 items-start pb-6 border-b border-slate-100">
                    @if($registration->pas_foto)
                        <div class="w-28 h-36 rounded-xl overflow-hidden border-2 border-red-600 shadow-md shrink-0 mx-auto sm:mx-0">
                            <img src="{{ $registration->pas_foto }}" alt="Pas Foto" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="flex-1 w-full space-y-2 text-xs">
                        <div class="grid grid-cols-3 gap-2 py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-semibold">Nama Lengkap</span>
                            <span class="col-span-2 font-bold text-slate-800 text-sm">{{ $registration->nama_lengkap }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-semibold">NISN & NIK</span>
                            <span class="col-span-2 font-mono text-slate-700">{{ $registration->nisn }} / {{ $registration->nik ?: '—' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-semibold">Jalur & Jenjang</span>
                            <span class="col-span-2 font-semibold text-emerald-800">
                                {{ $registration->jalur }} • {{ $registration->jenjang }}
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-semibold">Tempat, Tgl Lahir</span>
                            <span class="col-span-2 text-slate-700">
                                {{ $registration->tempat_lahir }}, {{ $registration->tanggal_lahir ? $registration->tanggal_lahir->translatedFormat('d F Y') : '—' }}
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-semibold">Sekolah Asal</span>
                            <span class="col-span-2 text-slate-700">{{ $registration->nama_sekolah ?: $registration->asal_sekolah }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-semibold">Nama Orang Tua/Wali</span>
                            <span class="col-span-2 text-slate-700">{{ $registration->ayah_nama ?: $registration->nama_wali }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-1.5">
                            <span class="text-slate-400 font-semibold">WhatsApp Terdaftar</span>
                            <span class="col-span-2 text-slate-700 font-medium">{{ $registration->ayah_telepon ?: $registration->no_whatsapp }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action CTAs -->
                @php
                    $waText = "Assalamu'alaikum Warahmatullahi Wabarakatuh,\n\nSaya wali santri dari:\n- Nama Calon Santri: " . $registration->nama_lengkap . "\n- No. Registrasi: " . $registration->no_registrasi . "\n- Jenjang: " . $registration->jenjang . "\n\nIngin mengonfirmasi bahwa pendaftaran online kami telah terkirim dan saat ini berstatus 'Menunggu Verifikasi'. Mohon informasi tahap berikutnya. Terima kasih.";
                @endphp
                <div class="no-print space-y-3 pt-2">
                    <a href="{{ route('psb.printCard', ['id' => $registration->id, 'mode' => 'cv']) }}" target="_blank" class="flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition text-xs">
                        <svg class="icon-svg w-4.5 h-4.5 text-yellow-300" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        <span>🖨️ Cetak / Unduh Kartu Biodata Santri (Format CV)</span>
                    </a>

                    <div class="grid sm:grid-cols-2 gap-3">
                        <a href="{{ route('psb.checkStatus', ['no_reg' => $registration->no_registrasi]) }}" class="flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-xl shadow-md transition text-xs">
                            <svg class="icon-svg w-4 h-4 text-emerald-400" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            Pantau Status Verifikasi
                        </a>
                        <a href="https://wa.me/6285290429617?text={{ urlencode($waText) }}" target="_blank" class="flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition text-xs">
                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            Konfirmasi via WhatsApp Panitia
                        </a>
                    </div>

                    <div class="text-center pt-2">
                        <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                            ← Selesai & Kembali ke Beranda
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>

</body>
</html>

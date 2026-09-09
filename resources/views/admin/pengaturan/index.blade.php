@extends('admin.layout')

@section('title', 'Pengaturan Konten Website')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengaturan Konten Website</h1>
            <p class="text-sm text-slate-500">Kelola Hero, Brosur, Buku Panduan, Falsafah, Kalam Pimpinan, Tabel Biaya PSB, Jadwal 24 Jam & Kontak.</p>
        </div>
        <div>
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
                <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                Lihat Hasil di Website
            </a>
        </div>
    </div>

    <!-- Alert Sukses -->
    @if (session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-sm font-medium shadow-sm">
        <svg class="icon-svg w-5 h-5 text-emerald-600 flex-shrink-0" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Alert Error Validasi / Upload -->
    @if ($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-start gap-3 text-sm font-medium shadow-sm">
        <svg class="icon-svg w-5 h-5 text-rose-600 shrink-0 mt-0.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div>
            <strong class="font-bold block mb-1">Gagal menyimpan data:</strong>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3 text-sm font-medium shadow-sm">
        <svg class="icon-svg w-5 h-5 text-rose-600 shrink-0" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- TAB NAVIGATION -->
    <div class="bg-white p-2 rounded-xl border border-slate-200/80 shadow-sm flex flex-wrap gap-2" id="settingsTabNav">
        <button type="button" onclick="switchTab('tab-hero')" id="btn-tab-hero" class="tab-btn px-4 py-2.5 rounded-lg text-sm font-semibold transition-all flex items-center gap-2 bg-brand-500 text-white shadow-sm">
            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            Hero, Brosur & Panduan
        </button>
        <button type="button" onclick="switchTab('tab-falsafah')" id="btn-tab-falsafah" class="tab-btn px-4 py-2.5 rounded-lg text-sm font-semibold transition-all flex items-center gap-2 text-slate-600 hover:bg-slate-100">
            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            Falsafah & Fondasi Hidup
        </button>
        <button type="button" onclick="switchTab('tab-profil')" id="btn-tab-profil" class="tab-btn px-4 py-2.5 rounded-lg text-sm font-semibold transition-all flex items-center gap-2 text-slate-600 hover:bg-slate-100">
            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            Profil & Kalam Pimpinan
        </button>
        <button type="button" onclick="switchTab('tab-biaya')" id="btn-tab-biaya" class="tab-btn px-4 py-2.5 rounded-lg text-sm font-semibold transition-all flex items-center gap-2 text-slate-600 hover:bg-slate-100">
            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            Tabel Biaya PSB
        </button>
        <button type="button" onclick="switchTab('tab-jadwal')" id="btn-tab-jadwal" class="tab-btn px-4 py-2.5 rounded-lg text-sm font-semibold transition-all flex items-center gap-2 text-slate-600 hover:bg-slate-100">
            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            Jadwal & Agenda Santri
        </button>
        <button type="button" onclick="switchTab('tab-kontak')" id="btn-tab-kontak" class="tab-btn px-4 py-2.5 rounded-lg text-sm font-semibold transition-all flex items-center gap-2 text-slate-600 hover:bg-slate-100">
            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            Media Sosial & Kontak
        </button>
        <button type="button" onclick="switchTab('tab-ttd')" id="btn-tab-ttd" class="tab-btn px-4 py-2.5 rounded-lg text-sm font-semibold transition-all flex items-center gap-2 text-slate-600 hover:bg-slate-100">
            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path></svg>
            TTD Digital & Stempel PSB
        </button>
    </div>

    <!-- ==================== TAB 1: HERO, BROSUR & PANDUAN ==================== -->
    <div id="tab-hero" class="tab-content space-y-6">

        <!-- Card 1: Teks Utama Hero Section -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-hero">
            <input type="hidden" name="section_name" value="Teks Hero Section">

            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">1</div>
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Teks Utama Hero Section</h2>
                    <p class="text-xs text-slate-500">Atur teks badge, headline utama, dan subjudul pembuka di halaman depan website.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Teks Badge Hero</label>
                    <input type="text" name="hero_badge" value="{{ old('hero_badge', $settings['hero_badge'] ?? 'PSB TA 2025/2026 Telah Dibuka') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Badge berkedip kecil di atas judul utama (misal: "PSB TA 2025/2026 Telah Dibuka").</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Judul Utama (Headline)</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? "Membentuk Generasi <em>Qur'ani</em>, Berakhlak Mulia & Berwawasan Global") }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Gunakan tag &lt;em&gt;...&lt;/em&gt; untuk memberikan aksen tulisan miring/emas pada kata kunci.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Subjudul / Deskripsi Pembuka</label>
                    <textarea name="hero_subtitle" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none">{{ old('hero_subtitle', $settings['hero_subtitle'] ?? 'Sinergi pendidikan integral Pondok Pesantren Hidayatullah Tuksongo yang memadukan bimbingan Tahfidzul Qur\'an mutqin, kurikulum formal Kemenag, pembinaan akhlak santri 24 jam, dan kemandirian hidup.') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Teks Hero
                </button>
            </div>
        </form>

        <!-- Card 2: Foto Utama Kampus (Hero Visual) -->
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-hero">
            <input type="hidden" name="section_name" value="Foto Utama Kampus">

            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">2</div>
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Foto Utama Kampus (Hero Visual)</h2>
                    <p class="text-xs text-slate-500">Foto besar kampus atau gerbang pondok yang tampil di sebelah kanan hero beranda.</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-5 items-start">
                <div class="w-full sm:w-56 h-36 rounded-xl overflow-hidden border border-slate-200 shadow-sm bg-slate-100 shrink-0 relative group">
                    <img id="heroImagePreview" src="{{ !empty($settings['hero_image']) ? asset($settings['hero_image']) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuCGYaU4q4nIXeNSx0KvqvdTCXvhP1950A1c7sA345MHgqC0koDMg-pDDQnBy9NpclcSa0PcDMfQidKumWL-n9GMZ9qrXrvAEwL9U3hdTPo2-0eXAokLKZ11EVIOzlck9D1C9LwbnjOu8N6NjiOSLbZCN3122S-MJBUjjFqSj9UoSN7s74Zg-Yc4FBDioEDu2ACO-pOvjP9mhQLY9aUyH4HtA-4GCzA_H_DcQHTZ5Binpb7M2nk8bVSb' }}" alt="Preview Hero" class="w-full h-full object-cover">
                    <div id="heroImageStatus" class="absolute bottom-1 right-1 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded">Foto Aktif</div>
                </div>
                <div class="flex-1 space-y-3 w-full">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Foto Baru dari Komputer / HP</label>
                        <input type="file" name="hero_image_file" id="heroImageFileInput" onchange="previewHeroImage(this)" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 border border-slate-200 rounded-lg p-1.5 bg-slate-50/50 cursor-pointer">
                        <p class="text-[11px] text-slate-400 mt-1">Mendukung format JPG, JPEG, PNG, WEBP. Maksimal ukuran 15 MB. Preview langsung muncul seketika di samping kiri.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Atau Gunakan Link / URL Gambar Langsung</label>
                        <input type="text" name="hero_image" id="heroImageUrlInput" oninput="previewHeroImageUrl(this.value)" value="{{ old('hero_image', $settings['hero_image'] ?? '') }}" placeholder="https://... atau /uploads/settings/foto.jpg" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-brand-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan Foto (Caption)</label>
                    <input type="text" name="hero_caption" value="{{ old('hero_caption', $settings['hero_caption'] ?? 'Kampus Alam Tuksongo Madani') }}" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Sub-keterangan Lokasi</label>
                    <input type="text" name="hero_subcaption" value="{{ old('hero_subcaption', $settings['hero_subcaption'] ?? "Dusun Tuksongo, Nglorog, Pringsurat — Asri, hening, dan kondusif untuk tholabul 'ilmi") }}" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-brand-500 outline-none">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-5 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Foto Kampus
                </button>
            </div>
        </form>

        <!-- Card 3: Upload Brosur PSB (PDF / Gambar) -->
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-hero">
            <input type="hidden" name="section_name" value="File Brosur PSB">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">3</div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-base">File Brosur PSB & Pondok Pesantren (PDF / Gambar)</h2>
                        <p class="text-xs text-slate-500">File brosur yang otomatis terunduh saat pengunjung menekan tombol "Unduh Brosur (PDF)" di Hero beranda.</p>
                    </div>
                </div>
                <div>
                    @if (!empty($settings['brosur_file_url']))
                    <a href="{{ $settings['brosur_file_url'] }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3.5 py-1.5 rounded-lg border border-emerald-200 shadow-2xs">
                        <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Lihat Brosur Aktif
                    </a>
                    @else
                    <span class="inline-flex items-center gap-1.5 text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                        Menggunakan File Bawaan
                    </span>
                    @endif
                </div>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unggah File Brosur Baru (PDF / JPG / PNG / WebP)</label>
                    <input type="file" name="brosur_file" id="brosurFileInput" onchange="previewBrosurFile(this)" accept=".pdf,image/jpeg,image/png,image/jpg,image/webp" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 border border-slate-200 rounded-lg p-1.5 bg-slate-50/50 cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">Maksimal 25 MB. Mendukung file dokumen PDF maupun poster brosur gambar.</p>
                    <div id="brosurFileStatus" class="hidden mt-2 p-2.5 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg text-xs font-semibold"></div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Atau Gunakan Link / URL Brosur</label>
                    <input type="text" name="brosur_file_url" value="{{ old('brosur_file_url', $settings['brosur_file_url'] ?? '') }}" placeholder="/uploads/settings/brosur.pdf atau https://..." class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-brand-500 outline-none">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan File Brosur
                </button>
            </div>
        </form>

        <!-- Card 4: Upload Buku Panduan Santri (PDF Resmi) -->
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-hero">
            <input type="hidden" name="section_name" value="Buku Panduan Santri">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">4</div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-base">File Dokumen Buku Panduan Santri (PDF Resmi)</h2>
                        <p class="text-xs text-slate-500">File dokumen buku panduan lengkap (81 halaman) yang diunduh pada banner dokumen resmi dan menu navigasi.</p>
                    </div>
                </div>
                <div>
                    <a href="{{ $settings['panduan_file_url'] ?? '/NEW BUKU PANDUAN SANTRI TA 2025 (2).pdf' }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 px-3.5 py-1.5 rounded-lg border border-blue-200 shadow-2xs">
                        <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Lihat Buku Panduan Aktif
                    </a>
                </div>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Dokumen Buku Panduan Baru (Hanya PDF)</label>
                    <input type="file" name="panduan_file" id="panduanFileInput" onchange="previewPanduanFile(this)" accept=".pdf,application/pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 border border-slate-200 rounded-lg p-1.5 bg-slate-50/50 cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">Maksimal 35 MB. Format wajib PDF.</p>
                    <div id="panduanFileStatus" class="hidden mt-2 p-2.5 bg-blue-50 text-blue-800 border border-blue-200 rounded-lg text-xs font-semibold"></div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Atau Gunakan Link / URL Buku Panduan</label>
                    <input type="text" name="panduan_file_url" value="{{ old('panduan_file_url', $settings['panduan_file_url'] ?? '/NEW BUKU PANDUAN SANTRI TA 2025 (2).pdf') }}" placeholder="/NEW BUKU PANDUAN SANTRI TA 2025 (2).pdf" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-brand-500 outline-none">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan File Buku Panduan
                </button>
            </div>
        </form>

    </div>

    <!-- ==================== TAB 2: FALSAFAH & FONDASI HIDUP ==================== -->
    <div id="tab-falsafah" class="tab-content hidden space-y-6">

        <!-- Card 1: Panca Jiwa Pondok Pesantren -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-5">
            @csrf
            <input type="hidden" name="active_tab" value="tab-falsafah">
            <input type="hidden" name="section_name" value="Panca Jiwa Pondok Pesantren">

            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">1</div>
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Panca Jiwa Pondok Pesantren Hidayatullah</h2>
                    <p class="text-xs text-slate-500">Lima nilai pokok pembentukan karakter dan jati diri santri (Keikhlasan, Kesederhanaan, Berdikari, Ukhuwah Islamiyah, Kebebasan Positif).</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Judul Bagian</label>
                    <input type="text" name="falsafah_judul" value="{{ old('falsafah_judul', $settings['falsafah_judul'] ?? 'Panca Jiwa Pondok Pesantren Hidayatullah') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Deskripsi Ringkas</label>
                    <input type="text" name="falsafah_subjudul" value="{{ old('falsafah_subjudul', $settings['falsafah_subjudul'] ?? 'Lima nilai pokok yang menjadi ruh dan pedoman pembentukan karakter setiap santri selama menuntut ilmu.') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Daftar 5 Nilai Panca Jiwa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($pancaJiwa as $index => $item)
                    <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/60 space-y-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-brand-500 text-white flex items-center justify-center text-xs font-bold shrink-0">{{ $item['nomor'] ?? sprintf('%02d', $index + 1) }}</span>
                            <input type="hidden" name="panca_nomor[]" value="{{ $item['nomor'] ?? sprintf('%02d', $index + 1) }}">
                            <input type="text" name="panca_judul[]" value="{{ $item['judul'] ?? '' }}" placeholder="Nama Jiwa" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-800 focus:border-brand-500 outline-none">
                        </div>
                        <textarea name="panca_deskripsi[]" rows="3" placeholder="Makna & uraian karakter..." class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 focus:border-brand-500 outline-none">{{ $item['deskripsi'] ?? '' }}</textarea>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Panca Jiwa
                </button>
            </div>
        </form>

        <!-- Card 2: Makna Filosofi Lambang Pesantren -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-5">
            @csrf
            <input type="hidden" name="active_tab" value="tab-falsafah">
            <input type="hidden" name="section_name" value="Makna Filosofi Lambang Pesantren">

            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">2</div>
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Makna Filosofi Lambang Pesantren</h2>
                    <p class="text-xs text-slate-500">Penjelasan filosofis setiap elemen, bentuk, dan warna pada logo resmi Pondok Pesantren Hidayatullah.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Judul Filosofi Lambang</label>
                    <input type="text" name="filosofi_judul" value="{{ old('filosofi_judul', $settings['filosofi_judul'] ?? 'Makna Filosofi Lambang Pesantren') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Deskripsi Ringkas</label>
                    <input type="text" name="filosofi_subjudul" value="{{ old('filosofi_subjudul', $settings['filosofi_subjudul'] ?? 'Setiap goresan simbol dan warna dalam logo pesantren mencerminkan visi luhur perjuangan dakwah dan pendidikan.') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">8 Elemen Simbol & Makna</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                    @foreach ($filosofiLambang as $item)
                    <div class="border border-slate-200 rounded-xl p-3 bg-slate-50/60 space-y-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Elemen / Simbol</label>
                            <input type="text" name="filosofi_elemen[]" value="{{ $item['elemen'] ?? '' }}" placeholder="Nama Elemen" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-800 focus:border-brand-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1">Makna Simbolis</label>
                            <textarea name="filosofi_makna[]" rows="3" placeholder="Makna..." class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-700 focus:border-brand-500 outline-none">{{ $item['makna'] ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Filosofi Lambang
                </button>
            </div>
        </form>

        <!-- Card 3: Fondasi Pendidikan Integral (4 Pilar) -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-5">
            @csrf
            <input type="hidden" name="active_tab" value="tab-falsafah">
            <input type="hidden" name="section_name" value="Fondasi Pendidikan Integral (4 Pilar)">

            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">3</div>
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Fondasi Pendidikan Integral Berkarakter Islami (Pilar Pengasuhan)</h2>
                    <p class="text-xs text-slate-500">4 Pilar pengasuhan utama santri yang tampil pada bagian program & khazanah di beranda.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Judul Bagian</label>
                    <input type="text" name="pilar_title" value="{{ old('pilar_title', $settings['pilar_title'] ?? 'Fondasi Pendidikan Integral Berkarakter Islami') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Deskripsi Ringkas</label>
                    <input type="text" name="pilar_subtitle" value="{{ old('pilar_subtitle', $settings['pilar_subtitle'] ?? 'Pilar pendidikan di Pesantren Hidayatullah Tuksongo dirancang seimbang antara kesucian ruhani, kedalaman ilmu syar\'i, kecerdasan intelek, dan kemandirian hidup.') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                @foreach ($pilarPendidikan as $index => $item)
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/60 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-md border border-brand-200">Pilar #{{ $index + 1 }}</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Pilar</label>
                            <input type="text" name="pilar_judul[]" value="{{ $item['judul'] ?? '' }}" placeholder="Nama Pilar" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-800 focus:border-brand-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Tag / Slogan Pilar</label>
                            <input type="text" name="pilar_tag[]" value="{{ $item['tag'] ?? '' }}" placeholder="Tag (cth: Tarbiyah 24 Jam)" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 focus:border-brand-500 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Uraian & Pembelajaran</label>
                        <textarea name="pilar_deskripsi[]" rows="3" placeholder="Penjelasan pilar..." class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 focus:border-brand-500 outline-none">{{ $item['deskripsi'] ?? '' }}</textarea>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Pilar Pendidikan
                </button>
            </div>
        </form>

        <!-- Card 4: Kutipan Al-Qur'an (Surah Al-Mujadilah: 11 / Banner Khazanah) -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-5">
            @csrf
            <input type="hidden" name="active_tab" value="tab-falsafah">
            <input type="hidden" name="section_name" value="Kutipan Al-Qur'an">

            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">4</div>
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Kutipan Al-Qur'an (Banner Khazanah Ilmu & Tauhid)</h2>
                    <p class="text-xs text-slate-500">Kartu hijau berarsitektur islami di beranda yang menampilkan dalil kemuliaan ilmu dan tauhid.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Sumber Surah & Ayat (Label Atas)</label>
                    <input type="text" name="quran_label" id="quranLabelInput" oninput="document.getElementById('quranLabelPreview').textContent = this.value" value="{{ old('quran_label', $settings['quran_label'] ?? 'Al-Qur\'an Surah Al-Mujadilah : 11') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Teks Ayat / Terjemah Utama</label>
                    <textarea name="quran_quote" id="quranQuoteInput" oninput="document.getElementById('quranQuotePreview').textContent = this.value" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none">{{ old('quran_quote', $settings['quran_quote'] ?? '"Allah akan meninggikan orang-orang yang beriman di antaramu dan orang-orang yang diberi ilmu pengetahuan beberapa derajat."') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Refleksi Nilai & Prinsip Pesantren (Deskripsi Bawah)</label>
                    <textarea name="quran_desc" id="quranDescInput" oninput="document.getElementById('quranDescPreview').textContent = this.value" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none">{{ old('quran_desc', $settings['quran_desc'] ?? 'Prinsip keselarasan antara kemurnian tauhid dan kedalaman ilmu pengetahuan yang menuntun setiap langkah pengasuhan di Pondok Pesantren Hidayatullah Tuksongo.') }}</textarea>
                </div>

                <!-- Live Preview Banner Hijau -->
                <div class="pt-2">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Pratinjau Tampilan di Beranda:</label>
                    <div class="rounded-xl p-5 text-white shadow-md flex items-center justify-between gap-4" style="background: linear-gradient(135deg, #0d3b1e 0%, #1a6b38 100%);">
                        <div class="space-y-2 flex-1">
                            <div class="inline-flex items-center gap-1.5 text-xs text-amber-300 font-bold uppercase tracking-wider">
                                <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                <span id="quranLabelPreview">{{ $settings['quran_label'] ?? 'Al-Qur\'an Surah Al-Mujadilah : 11' }}</span>
                            </div>
                            <blockquote id="quranQuotePreview" class="text-sm md:text-base font-serif italic text-emerald-50 leading-relaxed">
                                {{ $settings['quran_quote'] ?? '"Allah akan meninggikan orang-orang yang beriman di antaramu dan orang-orang yang diberi ilmu pengetahuan beberapa derajat."' }}
                            </blockquote>
                            <p id="quranDescPreview" class="text-xs text-emerald-200/90 leading-relaxed">
                                {{ $settings['quran_desc'] ?? 'Prinsip keselarasan antara kemurnian tauhid dan kedalaman ilmu pengetahuan yang menuntun setiap langkah pengasuhan di Pondok Pesantren Hidayatullah Tuksongo.' }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/20 text-emerald-200">
                            <svg class="icon-svg w-6 h-6" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Kutipan Al-Qur'an
                </button>
            </div>
        </form>

    </div>

    <!-- ==================== TAB 3: PROFIL & KALAM PIMPINAN ==================== -->
    <div id="tab-profil" class="tab-content hidden space-y-6">

        <!-- Card 1: Kalam Pengasuh & Pimpinan -->
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-profil">
            <input type="hidden" name="section_name" value="Kalam Pimpinan Pesantren">

            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">1</div>
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Kalam Pengasuh & Pimpinan Pesantren</h2>
                    <p class="text-xs text-slate-500">Teks kutipan hikmah, amanat pengasuhan, dan foto pimpinan berlatar kartu hijau emas di beranda.</p>
                </div>
            </div>

            <!-- Upload & Preview Foto Pimpinan -->
            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/60 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Foto Pimpinan Pesantren</h3>
                <div class="flex flex-col sm:flex-row gap-4 items-start">
                    <div class="w-28 h-36 rounded-xl overflow-hidden border border-slate-200 shadow-sm bg-slate-200 shrink-0 relative group">
                        <img id="sambutanFotoPreview" src="{{ !empty($settings['sambutan_foto']) ? asset($settings['sambutan_foto']) : '/pimpinan.jpg' }}" alt="Foto Pimpinan" class="w-full h-full object-cover">
                        <div id="sambutanFotoStatus" class="absolute bottom-1 right-1 bg-black/60 text-white text-[9px] px-1 py-0.5 rounded">Foto Aktif</div>
                    </div>
                    <div class="flex-1 space-y-2 w-full">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Unggah Foto Pimpinan Baru</label>
                            <input type="file" name="sambutan_foto_file" id="sambutanFotoFileInput" onchange="previewSambutanFoto(this)" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 border border-slate-200 rounded-lg p-1 bg-white cursor-pointer">
                            <p class="text-[11px] text-slate-400 mt-0.5">Rekomendasi: format portrait dengan pencahayaan jelas. Maks 12 MB. Preview seketika muncul di samping.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Atau Gunakan Link / URL Gambar</label>
                            <input type="text" name="sambutan_foto" id="sambutanFotoUrlInput" oninput="previewSambutanFotoUrl(this.value)" value="{{ old('sambutan_foto', $settings['sambutan_foto'] ?? '') }}" placeholder="https://..." class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-brand-500 outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Kutipan Utama (Quotes Hikmah)</label>
                    <textarea name="sambutan_quote" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">{{ old('sambutan_quote', $settings['sambutan_quote'] ?? 'Pondok Pesantren bukan sekadar tempat menuntut ilmu, melainkan kawah candradimuka yang menempa jiwa keikhlasan, kesederhanaan, kemandirian, dan ukhuwah. Sebesar keinsyafan seseorang, sebesar itu pula keuntungan hidup yang diraihnya.') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Amanat Pengasuhan Singkat</label>
                    <textarea name="sambutan_amanat" rows="2" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">{{ old('sambutan_amanat', $settings['sambutan_amanat'] ?? 'Amanat Pengasuhan Pondok Pesantren Hidayatullah Tuksongo bagi seluruh asatidz, santri, dan wali santri dalam mewujudkan generasi penerus peradaban Islam yang tangguh.') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Nama Pimpinan / Pengasuh</label>
                        <input type="text" name="sambutan_nama" value="{{ old('sambutan_nama', $settings['sambutan_nama'] ?? 'Pimpinan Pondok Pesantren Hidayatullah Tuksongo') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Jabatan / Keterangan</label>
                        <input type="text" name="sambutan_jabatan" value="{{ old('sambutan_jabatan', $settings['sambutan_jabatan'] ?? 'Pimpinan Pesantren') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Kalam Pimpinan
                </button>
            </div>
        </form>

        <!-- Card 2: Profil & Sejarah -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-profil">
            <input type="hidden" name="section_name" value="Profil & Sejarah Pesantren">

            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">2</div>
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Profil & Sejarah Singkat Pesantren</h2>
                    <p class="text-xs text-slate-500">Narasi sejarah berdirinya pondok pesantren pada tanah wakaf dan legalitas kelembagaan.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Paragraf 1: Awal Mula & Tanah Wakaf (1999)</label>
                    <textarea name="sejarah_paragraf_1" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">{{ old('sejarah_paragraf_1', $settings['sejarah_paragraf_1'] ?? 'Pondok Pesantren Hidayatullah Tuksongo didirikan pada tahun 1999 di atas tanah wakaf bersertifikat seluas 2.000 m² di Dusun Tuksongo RT 01/RW 01, Desa Nglorog, Kecamatan Pringsurat, Kabupaten Temanggung, Jawa Tengah.') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Paragraf 2: Kurikulum & Visi Pendidikan</label>
                    <textarea name="sejarah_paragraf_2" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">{{ old('sejarah_paragraf_2', $settings['sejarah_paragraf_2'] ?? 'Mengintegrasikan Kurikulum Kementerian Agama (Kemenag) jenjang Madrasah Tsanawiyah (MTs) dan Madrasah Aliyah (MA) dengan Kurikulum Kepesantrenan Modern Kulliyatul Mu\'allimin Al-Islamiyyah (KMI/TMI) serta tradisi salafiyah kajian kitab kuning turats.') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Status Tanah</label>
                        <input type="text" name="status_tanah" value="{{ old('status_tanah', $settings['status_tanah'] ?? 'Wakaf 2.000 m²') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">NSPP Pesantren</label>
                        <input type="text" name="nspp" value="{{ old('nspp', $settings['nspp'] ?? '510033230045') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">NPSN MTs</label>
                        <input type="text" name="mts_npsn" value="{{ old('mts_npsn', $settings['mts_npsn'] ?? '20363291') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">NPSN MA</label>
                        <input type="text" name="ma_npsn" value="{{ old('ma_npsn', $settings['ma_npsn'] ?? '69982710') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Profil & Sejarah
                </button>
            </div>
        </form>

        <!-- Card 3: Visi & Misi Pesantren -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-profil">
            <input type="hidden" name="section_name" value="Visi & Misi Pesantren">

            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">3</div>
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Visi & Misi Pesantren</h2>
                    <p class="text-xs text-slate-500">Arah strategis pembinaan santri dan pencapaian mutu pendidikan.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Visi Utama</label>
                    <textarea name="visi" rows="2" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">{{ old('visi', $settings['visi'] ?? 'Mewujudkan lembaga pendidikan Islam terpadu yang melahirkan generasi santri mutafaqqih fid-din, hafidz Al-Qur\'an, berbudi luhur, berbadan sehat, berpengetahuan luas, dan berjiwa mandiri.') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Misi Pesantren (Pisahkan dengan baris baru)</label>
                    <textarea name="misi" rows="5" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition font-mono text-xs">{{ old('misi', $settings['misi'] ?? "- Menyelenggarakan bimbingan Tahfidzul Qur'an mutqin dengan sanad keilmuan yang bersambung.\n- Memadukan kurikulum Kemenag dengan kepesantrenan TMI yang mengasah kemahiran dwi bahasa (Arab & Inggris).\n- Menanamkan karakter Panca Jiwa (keikhlasan, kesederhanaan, kemandirian, ukhuwah islamiyah, kebebasan terpimpin).\n- Membekali santri dengan kecakapan hidup (life skill), kepemimpinan (leadership), dan kepanduan Pramuka wajib.\n- Mencetak kader da'i dan muballigh yang siap berkhidmat untuk kejayaan agama, nusa, dan bangsa.") }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Visi & Misi
                </button>
            </div>
        </form>

    </div>

    <!-- ==================== TAB 4: TABEL BIAYA PSB ==================== -->
    <div id="tab-biaya" class="tab-content hidden space-y-6">

        <!-- Card 1: Tabel Biaya Awal -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-biaya">
            <input type="hidden" name="section_name" value="Tabel Biaya Awal PSB">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Tabel Biaya Awal Masuk (Daftar Ulang Santri Baru)</h2>
                    <p class="text-xs text-slate-500">Rincian biaya satu kali saat santri baru diterima (Uang Pangkal, Gedung, Fasilitas Asrama, dsb).</p>
                </div>
                <button type="button" onclick="addBiayaAwalRow()" class="inline-flex items-center gap-1.5 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200 px-3.5 py-2 rounded-lg text-xs font-bold transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    + Tambah Baris Komponen
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" id="tableBiayaAwal">
                    <thead>
                        <tr class="text-xs font-semibold uppercase tracking-wider text-slate-400 bg-slate-50 border-y border-slate-200">
                            <th class="py-2.5 px-3 min-w-[200px]">Komponen Biaya</th>
                            <th class="py-2.5 px-3 w-32">MTs Mukim</th>
                            <th class="py-2.5 px-3 w-32">MTs Laju</th>
                            <th class="py-2.5 px-3 w-32">MA Mukim</th>
                            <th class="py-2.5 px-3 w-32">MA Laju</th>
                            <th class="py-2.5 px-3 text-center w-20">Total?</th>
                            <th class="py-2.5 px-3 text-center w-14">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="biayaAwalRowsContainer" class="divide-y divide-slate-100">
                        @foreach ($biayaAwal as $row)
                        <tr class="hover:bg-slate-50/80 transition {{ !empty($row['is_total']) ? 'bg-emerald-50/50 font-bold' : '' }}">
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_awal_komponen[]" value="{{ $row['komponen'] ?? '' }}" placeholder="Nama Komponen" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_awal_mts_mukim[]" value="{{ $row['mts_mukim'] ?? '' }}" placeholder="Rp 0" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_awal_mts_laju[]" value="{{ $row['mts_laju'] ?? '' }}" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_awal_ma_mukim[]" value="{{ $row['ma_mukim'] ?? '' }}" placeholder="Rp 0" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_awal_ma_laju[]" value="{{ $row['ma_laju'] ?? '' }}" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-2 px-3 text-center">
                                <input type="hidden" name="biaya_awal_is_total[]" value="{{ !empty($row['is_total']) ? '1' : '0' }}">
                                <input type="checkbox" {{ !empty($row['is_total']) ? 'checked' : '' }} onchange="this.previousElementSibling.value = this.checked ? '1' : '0'" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4" title="Tandai sebagai baris ringkasan total">
                            </td>
                            <td class="py-2 px-3 text-center">
                                <button type="button" onclick="removeBiayaRow(this)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">* Centang kotak "Total?" jika baris merupakan kalkulasi akumulasi total.</span>
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Tabel Biaya Awal
                </button>
            </div>
        </form>

        <!-- Card 2: Tabel Biaya Bulanan -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-biaya">
            <input type="hidden" name="section_name" value="Tabel Biaya Bulanan (SPP)">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Tabel Iuran Rutin Bulanan (SPP, Syahriyah & Makan)</h2>
                    <p class="text-xs text-slate-500">Rincian SPP bulanan, biaya konsumsi 3x sehari santri mukim, dan tabungan wajib santri.</p>
                </div>
                <button type="button" onclick="addBiayaBulananRow()" class="inline-flex items-center gap-1.5 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200 px-3.5 py-2 rounded-lg text-xs font-bold transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    + Tambah Baris Komponen
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" id="tableBiayaBulanan">
                    <thead>
                        <tr class="text-xs font-semibold uppercase tracking-wider text-slate-400 bg-slate-50 border-y border-slate-200">
                            <th class="py-2.5 px-3 min-w-[200px]">Komponen Iuran</th>
                            <th class="py-2.5 px-3 w-32">MTs Mukim</th>
                            <th class="py-2.5 px-3 w-32">MTs Laju</th>
                            <th class="py-2.5 px-3 w-32">MA Mukim</th>
                            <th class="py-2.5 px-3 w-32">MA Laju</th>
                            <th class="py-2.5 px-3 text-center w-20">Total?</th>
                            <th class="py-2.5 px-3 text-center w-14">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="biayaBulananRowsContainer" class="divide-y divide-slate-100">
                        @foreach ($biayaBulanan as $row)
                        <tr class="hover:bg-slate-50/80 transition {{ !empty($row['is_total']) ? 'bg-emerald-50/50 font-bold' : '' }}">
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_bulanan_komponen[]" value="{{ $row['komponen'] ?? '' }}" placeholder="Nama Komponen" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_bulanan_mts_mukim[]" value="{{ $row['mts_mukim'] ?? '' }}" placeholder="Rp 0" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_bulanan_mts_laju[]" value="{{ $row['mts_laju'] ?? '' }}" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_bulanan_ma_mukim[]" value="{{ $row['ma_mukim'] ?? '' }}" placeholder="Rp 0" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_bulanan_ma_laju[]" value="{{ $row['ma_laju'] ?? '' }}" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-2 px-3 text-center">
                                <input type="hidden" name="biaya_bulanan_is_total[]" value="{{ !empty($row['is_total']) ? '1' : '0' }}">
                                <input type="checkbox" {{ !empty($row['is_total']) ? 'checked' : '' }} onchange="this.previousElementSibling.value = this.checked ? '1' : '0'" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4" title="Tandai sebagai baris ringkasan total">
                            </td>
                            <td class="py-2 px-3 text-center">
                                <button type="button" onclick="removeBiayaRow(this)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">* Pembayaran administrasi rutin disetorkan selambat-lambatnya sebelum tanggal 10 setiap bulannya.</span>
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Tabel Biaya Bulanan
                </button>
            </div>
        </form>

    </div>

    <!-- ==================== TAB 5: JADWAL & AGENDA SANTRI ==================== -->
    <div id="tab-jadwal" class="tab-content hidden space-y-6">

        <!-- Card 1: Jadwal Rutinitas Santri 24 Jam -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-jadwal">
            <input type="hidden" name="section_name" value="Jadwal Rutinitas Santri 24 Jam">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Jadwal Rutinitas Santri 24 Jam</h2>
                    <p class="text-xs text-slate-500">Kelola runutan kegiatan harian santri mulai dari bangun tahajud hingga istirahat malam.</p>
                </div>
                <button type="button" onclick="addJadwalRow()" class="inline-flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 px-3.5 py-2 rounded-lg text-xs font-bold transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    + Tambah Agenda Jam
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" id="tableJadwal">
                    <thead>
                        <tr class="text-xs font-semibold uppercase tracking-wider text-slate-400 bg-slate-50 border-y border-slate-200">
                            <th class="py-3 px-4 w-44">Waktu (Jam)</th>
                            <th class="py-3 px-4 w-64">Nama Kegiatan</th>
                            <th class="py-3 px-4">Deskripsi / Keterangan</th>
                            <th class="py-3 px-4 text-center w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="jadwalRowsContainer" class="divide-y divide-slate-100">
                        @forelse ($jadwalSantri as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 align-top">
                                <input type="text" name="jadwal_waktu[]" value="{{ $item['waktu'] ?? '' }}" placeholder="03.00 - 04.30" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-3 px-4 align-top">
                                <input type="text" name="jadwal_judul[]" value="{{ $item['judul'] ?? '' }}" placeholder="Nama Kegiatan" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-3 px-4 align-top">
                                <input type="text" name="jadwal_keterangan[]" value="{{ $item['keterangan'] ?? '' }}" placeholder="Keterangan singkat kegiatan santri..." class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-3 px-4 text-center align-top">
                                <button type="button" onclick="removeJadwalRow(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 align-top">
                                <input type="text" name="jadwal_waktu[]" value="03.00 - 04.30" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-3 px-4 align-top">
                                <input type="text" name="jadwal_judul[]" value="Qiyamul Lail & Shalat Subuh" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-3 px-4 align-top">
                                <input type="text" name="jadwal_keterangan[]" value="Bangun pagi, tahajud, doa mustajab & shalat subuh berjamaah" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-3 px-4 text-center align-top">
                                <button type="button" onclick="removeJadwalRow(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">* Perubahan jadwal santri akan langsung terupdate di halaman depan.</span>
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Jadwal 24 Jam
                </button>
            </div>
        </form>

        <!-- Card 2: Agenda Berkala Santri -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-jadwal">
            <input type="hidden" name="section_name" value="Agenda Berkala Santri">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Agenda Berkala Santri (Mingguan, Bulanan & Tahunan)</h2>
                    <p class="text-xs text-slate-500">Kegiatan berkala santri seperti tasmi' Ahad, upacara Senin, muhadhoroh Kamis, mujahadah Jumat, pramuka Sabtu, dan selapanan.</p>
                </div>
                <button type="button" onclick="addAgendaBerkalaRow()" class="inline-flex items-center gap-1.5 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200 px-3.5 py-2 rounded-lg text-xs font-bold transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    + Tambah Agenda Berkala
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" id="tableAgendaBerkala">
                    <thead>
                        <tr class="text-xs font-semibold uppercase tracking-wider text-slate-400 bg-slate-50 border-y border-slate-200">
                            <th class="py-3 px-4 w-44">Hari / Waktu</th>
                            <th class="py-3 px-4 w-64">Nama Kegiatan</th>
                            <th class="py-3 px-4">Deskripsi / Keterangan</th>
                            <th class="py-3 px-4 text-center w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="agendaBerkalaRowsContainer" class="divide-y divide-slate-100">
                        @forelse ($agendaBerkala as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 align-top">
                                <input type="text" name="agenda_hari[]" value="{{ $item['hari'] ?? '' }}" placeholder="Ahad" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-3 px-4 align-top">
                                <input type="text" name="agenda_kegiatan[]" value="{{ $item['kegiatan'] ?? '' }}" placeholder="Nama Kegiatan" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-3 px-4 align-top">
                                <input type="text" name="agenda_keterangan[]" value="{{ $item['keterangan'] ?? '' }}" placeholder="Keterangan singkat kegiatan..." class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-3 px-4 text-center align-top">
                                <button type="button" onclick="removeAgendaBerkalaRow(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 align-top">
                                <input type="text" name="agenda_hari[]" value="Ahad" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-3 px-4 align-top">
                                <input type="text" name="agenda_kegiatan[]" value="Simakan Al-Qur'an & Silat" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-3 px-4 align-top">
                                <input type="text" name="agenda_keterangan[]" value="Tasmi' Al-Qur'an dan latihan bela diri" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:bg-white focus:border-brand-500 outline-none">
                            </td>
                            <td class="py-3 px-4 text-center align-top">
                                <button type="button" onclick="removeAgendaBerkalaRow(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">* Perubahan agenda berkala akan langsung diupdate pada kartu Agenda Berkala di beranda.</span>
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Agenda Berkala
                </button>
            </div>
        </form>

    </div>

    <!-- ==================== TAB 6: MEDIA SOSIAL & KONTAK ==================== -->
    <div id="tab-kontak" class="tab-content hidden space-y-6">

        <!-- Card 1: Media Sosial -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-kontak">
            <input type="hidden" name="section_name" value="Tautan Media Sosial">

            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">1</div>
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Tautan Akun Media Sosial Resmi</h2>
                    <p class="text-xs text-slate-500">Link profil Instagram, YouTube channel, Facebook page, dan TikTok pondok.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Instagram URL</label>
                    <input type="url" name="sosmed_instagram" value="{{ old('sosmed_instagram', $settings['sosmed_instagram'] ?? '') }}" placeholder="https://instagram.com/username" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">YouTube Channel</label>
                    <input type="url" name="sosmed_youtube" value="{{ old('sosmed_youtube', $settings['sosmed_youtube'] ?? '') }}" placeholder="https://youtube.com/@channel" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Facebook Page</label>
                    <input type="url" name="sosmed_facebook" value="{{ old('sosmed_facebook', $settings['sosmed_facebook'] ?? '') }}" placeholder="https://facebook.com/pesantren" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">TikTok Official</label>
                    <input type="url" name="sosmed_tiktok" value="{{ old('sosmed_tiktok', $settings['sosmed_tiktok'] ?? '') }}" placeholder="https://tiktok.com/@pesantren" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Media Sosial
                </button>
            </div>
        </form>

        <!-- Card 2: Kontak & Hotline -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            <input type="hidden" name="active_tab" value="tab-kontak">
            <input type="hidden" name="section_name" value="Hotline & Rekening Bank">

            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">2</div>
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Hotline Informasi, WhatsApp & Rekening Resmi</h2>
                    <p class="text-xs text-slate-500">Nomor WhatsApp panitia PSB, email resmi pesantren, dan rekening bank resmi.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Hotline 1 (Ustadz)</label>
                        <input type="text" name="kontak_hotline_1" value="{{ old('kontak_hotline_1', $settings['kontak_hotline_1'] ?? '0812-3456-7890') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Hotline 2 (Sekretariat)</label>
                        <input type="text" name="kontak_hotline_2" value="{{ old('kontak_hotline_2', $settings['kontak_hotline_2'] ?? '0857-1234-5678') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Email Resmi</label>
                        <input type="email" name="kontak_email" value="{{ old('kontak_email', $settings['kontak_email'] ?? 'info@hidayatullahtuksongo.ponpes.id') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Rekening Bank BRI</label>
                        <input type="text" name="kontak_rek_bri" value="{{ old('kontak_rek_bri', $settings['kontak_rek_bri'] ?? '0146-01-001234-53-8 (BRI a.n. Diky Fachri Husein)') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Rekening Bank BCA</label>
                        <input type="text" name="kontak_rek_bca" value="{{ old('kontak_rek_bca', $settings['kontak_rek_bca'] ?? '122-098-7654 (BCA a.n. Diky Fachri Husein)') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Alamat Lengkap Kampus Pesantren</label>
                    <textarea name="alamat_kampus" rows="2" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">{{ old('alamat_kampus', $settings['alamat_kampus'] ?? 'Dusun Tuksongo RT 01/RW 01, Desa Nglorog, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah 56272') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Kontak & Rekening
                </button>
            </div>
        </form>

    </div>

    <!-- ==================== TAB 7: TTD DIGITAL & STEMPEL PSB ==================== -->
    <div id="tab-ttd" class="tab-content space-y-6 hidden">

        <!-- Info Header Banner -->
        <div class="bg-gradient-to-r from-[#0d3b1e] via-[#145a2e] to-[#1a6b38] text-white p-6 rounded-2xl shadow-md">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="text-xs uppercase tracking-widest text-[#e8cc5a] font-bold block">Pengesahan Resmi Dokumen PSB</span>
                    <h2 class="text-xl font-serif font-bold">Tanda Tangan Digital Pengurus & Stempel Resmi Pesantren</h2>
                    <p class="text-xs text-emerald-100 max-w-2xl leading-relaxed">
                        Tanda tangan digital dan stempel yang diatur di sini akan otomatis dicetak pada lembar <strong>Kartu Biodata Santri Baru (Format CV)</strong> dan berkas verifikasi PSB, baik saat dicetak oleh admin maupun saat diunduh mandiri oleh pendaftar / santri.
                    </p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('admin.psb.print', ['mode' => 'cv']) }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 bg-amber-400 hover:bg-amber-300 text-emerald-950 font-bold text-xs rounded-xl shadow transition">
                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        Lihat Contoh di Kartu Santri
                    </a>
                </div>
            </div>
        </div>

        <!-- Live Mockup Visualizer & Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Kolom Kiri: Live Preview Mockup TTD di Kartu -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                    <svg class="icon-svg w-4 h-4 text-emerald-700" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    <h3 class="font-bold text-slate-800 text-sm">Simulasi Tampilan di Kartu CV:</h3>
                </div>

                <!-- Box Tanda Tangan Simulasi Cetak -->
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-center space-y-1 relative overflow-hidden">
                    <p class="text-[11px] text-slate-600 leading-tight">
                        <span id="preview-kota">{{ $settings['ttd_digital_kota'] ?? 'Pringsurat' }}</span>, {{ date('d F Y') }}<br>
                        <strong id="preview-jabatan" class="text-slate-900 uppercase text-[11px]">{{ $settings['ttd_digital_jabatan'] ?? 'Ketua Panitia PSB TA 2025/2026' }}</strong><br>
                        <span class="text-[9.5px] text-slate-500">Pondok Pesantren Hidayatullah Tuksongo</span>
                    </p>

                    <!-- Wadah Gambar TTD + Stempel -->
                    <div class="relative h-24 my-1 flex items-center justify-center">
                        <!-- Stempel Background -->
                        <img id="preview-stempel-img" src="{{ $settings['ttd_digital_stempel_image'] ?? '/uploads/settings/default_stempel_pesantren.png' }}" alt="Stempel" class="absolute w-24 h-24 object-contain opacity-80 left-4 pointer-events-none transform -rotate-6 transition">

                        <!-- TTD Gambar -->
                        <img id="preview-ttd-img" src="{{ $settings['ttd_digital_pengurus_image'] ?? '/uploads/settings/default_ttd_pengurus.png' }}" alt="TTD Pengurus" class="relative z-10 h-20 max-w-[170px] object-contain mx-auto transition">
                    </div>

                    <!-- Nama Terang & NIP -->
                    <div>
                        <p id="preview-nama" class="font-bold text-slate-900 border-b border-slate-400 inline-block px-4 pb-0.5 text-xs">
                            {{ $settings['ttd_digital_nama'] ?? 'Ust. Ahmad Fauzi, S.Pd.I' }}
                        </p>
                        <span id="preview-nip" class="text-[9.5px] font-mono text-slate-500 block mt-0.5">
                            {{ $settings['ttd_digital_nip'] ?? 'NIY. 19850412 201001 1 003' }}
                        </span>
                    </div>
                </div>

                <div class="text-[11px] text-slate-500 space-y-1 bg-emerald-50/70 p-3 rounded-xl border border-emerald-200/60">
                    <p class="font-semibold text-emerald-900 flex items-center gap-1.5">
                        <svg class="icon-svg w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Keunggulan TTD Digital:
                    </p>
                    <ul class="list-disc list-inside space-y-0.5 pl-1 text-[10.5px]">
                        <li>Format gambar transparan membuat cap stempel dan coretan tanda tangan terlihat sangat realistis.</li>
                        <li>Dapat diunggah berupa scan foto asli ataupun digoreskan langsung di layar (canvas).</li>
                        <li>Otomatis terekam saat santri mengunduh berkas PDF.</li>
                    </ul>
                </div>
            </div>

            <!-- Kolom Kanan: Formulir Pengaturan TTD & Stempel -->
            <div class="lg:col-span-2 space-y-6">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-6" id="form-ttd-digital">
                    @csrf
                    <input type="hidden" name="active_tab" value="tab-ttd">
                    <input type="hidden" name="section_name" value="Tanda Tangan Digital & Stempel PSB">
                    <input type="hidden" name="ttd_digital_pengurus_canvas" id="ttd_digital_pengurus_canvas" value="">

                    <!-- Section 1: Upload File Gambar Tanda Tangan -->
                    <div class="space-y-3 pb-6 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                                1. Unggah File Tanda Tangan Pengurus (Scan / PNG Transparan)
                            </label>
                            <span class="text-[11px] text-slate-400 font-mono">PNG, JPG, WEBP, SVG (Maks. 10MB)</span>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 items-center">
                            <div class="w-36 h-20 bg-slate-50 rounded-xl border-2 border-dashed border-slate-300 p-2 flex items-center justify-center relative overflow-hidden shrink-0 shadow-inner">
                                <img id="ttdFileThumb" src="{{ $settings['ttd_digital_pengurus_image'] ?? '/uploads/settings/default_ttd_pengurus.png' }}" alt="TTD Saat Ini" class="max-h-full max-w-full object-contain">
                            </div>
                            <div class="flex-1 w-full space-y-1.5">
                                <input type="file" name="ttd_digital_pengurus_file" id="ttd_digital_pengurus_file" accept="image/*" onchange="previewTtdFileInput(this)" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 bg-slate-50 p-1 rounded-xl border border-slate-200 cursor-pointer">
                                <p class="text-[11px] text-slate-500 leading-tight">
                                    💡 <em>Tips:</em> Gunakan file berlatar transparan (format PNG) agar tanda tangan tampak menyatu alami dengan stempel di atas kertas cetak.
                                </p>
                            </div>
                        </div>

                        <!-- Accordion Pad: Atau Gores Langsung di Layar (Canvas Signature Pad) -->
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <button type="button" onclick="toggleSignatureCanvas()" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-600 hover:text-brand-800 transition">
                                <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path></svg>
                                <span>✍️ Atau Buat Tanda Tangan Langsung di Layar (Digital Canvas)</span>
                            </button>

                            <div id="signature-canvas-box" class="hidden mt-3 p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2.5">
                                <p class="text-xs text-slate-600">Gunakan mouse, touchscreen smartphone, atau stylus pen pada kotak di bawah:</p>
                                <div class="bg-white rounded-lg border-2 border-dashed border-slate-300 inline-block overflow-hidden shadow-inner">
                                    <canvas id="sigCanvas" width="380" height="140" class="cursor-crosshair block touch-none"></canvas>
                                </div>
                                <div class="flex items-center gap-2 pt-1">
                                    <button type="button" onclick="clearSignatureCanvas()" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-xs font-semibold transition">
                                        Bersihkan Coretan
                                    </button>
                                    <button type="button" onclick="applySignatureCanvas()" class="px-3 py-1.5 bg-brand-500 hover:bg-brand-600 text-white rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                                        <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Gunakan Tanda Tangan Ini
                                    </button>
                                    <span id="canvasAppliedStatus" class="hidden text-xs text-emerald-700 font-bold">✓ Goresan siap disimpan!</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Upload File Stempel Resmi Pesantren -->
                    <div class="space-y-3 pb-6 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                                2. Unggah File Gambar Stempel Resmi Pesantren
                            </label>
                            <span class="text-[11px] text-slate-400 font-mono">PNG Transparan (Maks. 10MB)</span>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 items-center">
                            <div class="w-24 h-24 bg-slate-50 rounded-xl border-2 border-dashed border-slate-300 p-2 flex items-center justify-center relative overflow-hidden shrink-0 shadow-inner">
                                <img id="stempelFileThumb" src="{{ $settings['ttd_digital_stempel_image'] ?? '/uploads/settings/default_stempel_pesantren.png' }}" alt="Stempel Saat Ini" class="max-h-full max-w-full object-contain">
                            </div>
                            <div class="flex-1 w-full space-y-2">
                                <input type="file" name="ttd_digital_stempel_file" id="ttd_digital_stempel_file" accept="image/*" onchange="previewStempelFileInput(this)" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 bg-slate-50 p-1 rounded-xl border border-slate-200 cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="ttd_digital_show_stempel" id="ttd_digital_show_stempel" value="1" {{ ($settings['ttd_digital_show_stempel'] ?? '1') == '1' ? 'checked' : '' }} onchange="toggleStempelPreview(this.checked)" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                                    <label for="ttd_digital_show_stempel" class="text-xs font-semibold text-slate-700 cursor-pointer">
                                        Tampilkan stempel resmi ini di latar belakang tanda tangan
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Data Teks Pejabat Penandatangan -->
                    <div class="space-y-4">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                            3. Data Pejabat Penandatangan (Pengurus / Panitia PSB)
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap & Gelar Pejabat</label>
                                <input type="text" name="ttd_digital_nama" id="input_ttd_nama" value="{{ old('ttd_digital_nama', $settings['ttd_digital_nama'] ?? 'Ust. Ahmad Fauzi, S.Pd.I') }}" oninput="document.getElementById('preview-nama').textContent = this.value || 'Nama Pejabat'" required placeholder="Contoh: Ust. Ahmad Fauzi, S.Pd.I" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Jabatan Penandatangan</label>
                                <input type="text" name="ttd_digital_jabatan" id="input_ttd_jabatan" value="{{ old('ttd_digital_jabatan', $settings['ttd_digital_jabatan'] ?? 'Ketua Panitia PSB TA 2025/2026') }}" oninput="document.getElementById('preview-jabatan').textContent = this.value.toUpperCase() || 'PENGURUS PESANTREN'" required placeholder="Contoh: Ketua Panitia PSB TA 2025/2026" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">NIP / NIY / NBM (Opsional)</label>
                                <input type="text" name="ttd_digital_nip" id="input_ttd_nip" value="{{ old('ttd_digital_nip', $settings['ttd_digital_nip'] ?? 'NIY. 19850412 201001 1 003') }}" oninput="document.getElementById('preview-nip').textContent = this.value" placeholder="Contoh: NIY. 19850412 201001 1 003" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Kota / Tempat Pengesahan</label>
                                <input type="text" name="ttd_digital_kota" id="input_ttd_kota" value="{{ old('ttd_digital_kota', $settings['ttd_digital_kota'] ?? 'Pringsurat') }}" oninput="document.getElementById('preview-kota').textContent = this.value || 'Pringsurat'" required placeholder="Contoh: Pringsurat" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition">
                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            Simpan Pengaturan Tanda Tangan & Stempel
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    function switchTab(tabId) {
        localStorage.setItem('admin_settings_active_tab', tabId);

        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        const target = document.getElementById(tabId);
        if (target) {
            target.classList.remove('hidden');
        }

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-brand-500', 'text-white', 'shadow-sm');
            btn.classList.add('text-slate-600', 'hover:bg-slate-100');
        });

        const activeBtn = document.getElementById('btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.remove('text-slate-600', 'hover:bg-slate-100');
            activeBtn.classList.add('bg-brand-500', 'text-white', 'shadow-sm');
        }
    }

    // Dynamic Row Jadwal 24 Jam
    function addJadwalRow() {
        const tbody = document.getElementById('jadwalRowsContainer');
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50/80 transition';
        tr.innerHTML = `
            <td class="py-3 px-4 align-top">
                <input type="text" name="jadwal_waktu[]" placeholder="Contoh: 03.00 - 04.30" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-3 px-4 align-top">
                <input type="text" name="jadwal_judul[]" placeholder="Nama Agenda" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-3 px-4 align-top">
                <input type="text" name="jadwal_keterangan[]" placeholder="Deskripsi kegiatan..." class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-3 px-4 text-center align-top">
                <button type="button" onclick="removeJadwalRow(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function removeJadwalRow(btn) {
        const row = btn.closest('tr');
        const tbody = document.getElementById('jadwalRowsContainer');
        if (tbody.children.length > 1) {
            row.remove();
        } else {
            alert('Minimal harus ada satu jadwal kegiatan santri.');
        }
    }

    // Dynamic Row Agenda Berkala
    function addAgendaBerkalaRow() {
        const tbody = document.getElementById('agendaBerkalaRowsContainer');
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50/80 transition';
        tr.innerHTML = `
            <td class="py-3 px-4 align-top">
                <input type="text" name="agenda_hari[]" placeholder="Hari / Periode" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-3 px-4 align-top">
                <input type="text" name="agenda_kegiatan[]" placeholder="Nama Kegiatan" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-3 px-4 align-top">
                <input type="text" name="agenda_keterangan[]" placeholder="Keterangan singkat..." class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-3 px-4 text-center align-top">
                <button type="button" onclick="removeAgendaBerkalaRow(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function removeAgendaBerkalaRow(btn) {
        const row = btn.closest('tr');
        const tbody = document.getElementById('agendaBerkalaRowsContainer');
        if (tbody.children.length > 1) {
            row.remove();
        } else {
            alert('Minimal harus ada satu agenda berkala santri.');
        }
    }

    // Dynamic Row Biaya Awal
    function addBiayaAwalRow() {
        const tbody = document.getElementById('biayaAwalRowsContainer');
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50/80 transition';
        tr.innerHTML = `
            <td class="py-2 px-3">
                <input type="text" name="biaya_awal_komponen[]" placeholder="Nama Komponen" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_awal_mts_mukim[]" placeholder="Rp 0" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_awal_mts_laju[]" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_awal_ma_mukim[]" placeholder="Rp 0" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_awal_ma_laju[]" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-2 px-3 text-center">
                <input type="hidden" name="biaya_awal_is_total[]" value="0">
                <input type="checkbox" onchange="this.previousElementSibling.value = this.checked ? '1' : '0'" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4" title="Tandai sebagai baris ringkasan total">
            </td>
            <td class="py-2 px-3 text-center">
                <button type="button" onclick="removeBiayaRow(this)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    // Dynamic Row Biaya Bulanan
    function addBiayaBulananRow() {
        const tbody = document.getElementById('biayaBulananRowsContainer');
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50/80 transition';
        tr.innerHTML = `
            <td class="py-2 px-3">
                <input type="text" name="biaya_bulanan_komponen[]" placeholder="Nama Komponen" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_bulanan_mts_mukim[]" placeholder="Rp 0" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_bulanan_mts_laju[]" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_bulanan_ma_mukim[]" placeholder="Rp 0" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_bulanan_ma_laju[]" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none">
            </td>
            <td class="py-2 px-3 text-center">
                <input type="hidden" name="biaya_bulanan_is_total[]" value="0">
                <input type="checkbox" onchange="this.previousElementSibling.value = this.checked ? '1' : '0'" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4" title="Tandai sebagai baris ringkasan total">
            </td>
            <td class="py-2 px-3 text-center">
                <button type="button" onclick="removeBiayaRow(this)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function removeBiayaRow(btn) {
        const row = btn.closest('tr');
        const tbody = row.closest('tbody');
        if (tbody.children.length > 1) {
            row.remove();
        } else {
            alert('Minimal harus ada satu komponen rincian biaya.');
        }
    }

    // Live Preview & Validation for Hero Image
    function previewHeroImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (!file.type.startsWith('image/')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Format Tidak Didukung',
                    text: 'File yang dipilih bukan format gambar valid. Harap pilih gambar JPG, PNG, atau WEBP.',
                    confirmButtonColor: '#465fff'
                });
                input.value = '';
                return;
            }
            if (file.size > 15 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran Terlalu Besar',
                    text: 'Ukuran file gambar (' + (file.size / 1024 / 1024).toFixed(1) + ' MB) melebihi batas maksimal 15 MB.',
                    confirmButtonColor: '#465fff'
                });
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('heroImagePreview');
                if (img) img.src = e.target.result;
                const status = document.getElementById('heroImageStatus');
                if (status) {
                    status.textContent = 'Foto Baru: ' + (file.size / 1024).toFixed(0) + ' KB';
                    status.className = 'absolute bottom-1 right-1 bg-emerald-600 text-white text-[10px] px-1.5 py-0.5 rounded font-bold';
                }
            };
            reader.readAsDataURL(file);
        }
    }

    function previewHeroImageUrl(url) {
        if (url && (url.startsWith('http://') || url.startsWith('https://') || url.startsWith('/'))) {
            const img = document.getElementById('heroImagePreview');
            if (img) img.src = url;
            const status = document.getElementById('heroImageStatus');
            if (status) {
                status.textContent = 'Link URL Gambar';
                status.className = 'absolute bottom-1 right-1 bg-blue-600 text-white text-[10px] px-1.5 py-0.5 rounded font-bold';
            }
        }
    }

    // Live Preview & Validation for Foto Pimpinan
    function previewSambutanFoto(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (!file.type.startsWith('image/')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Bukan Gambar',
                    text: 'Harap pilih file gambar (JPG, PNG, WEBP).',
                    confirmButtonColor: '#465fff'
                });
                input.value = '';
                return;
            }
            if (file.size > 12 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran Terlalu Besar',
                    text: 'Ukuran file foto pimpinan melebihi 12 MB.',
                    confirmButtonColor: '#465fff'
                });
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('sambutanFotoPreview');
                if (img) img.src = e.target.result;
                const status = document.getElementById('sambutanFotoStatus');
                if (status) {
                    status.textContent = 'Foto Baru';
                    status.className = 'absolute bottom-1 right-1 bg-emerald-600 text-white text-[9px] px-1 py-0.5 rounded font-bold';
                }
            };
            reader.readAsDataURL(file);
        }
    }

    function previewSambutanFotoUrl(url) {
        if (url && (url.startsWith('http://') || url.startsWith('https://') || url.startsWith('/'))) {
            const img = document.getElementById('sambutanFotoPreview');
            if (img) img.src = url;
        }
    }

    // Live Preview Brosur File
    function previewBrosurFile(input) {
        const statusBox = document.getElementById('brosurFileStatus');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > 25 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file brosur (' + (file.size / 1024 / 1024).toFixed(1) + ' MB) melebihi batas 25 MB.',
                    confirmButtonColor: '#465fff'
                });
                input.value = '';
                if (statusBox) statusBox.classList.add('hidden');
                return;
            }
            if (statusBox) {
                statusBox.textContent = '✓ File Brosur Baru Dipilih: ' + file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB) — Klik "Simpan File Brosur" untuk mengunggah.';
                statusBox.classList.remove('hidden');
            }
        } else if (statusBox) {
            statusBox.classList.add('hidden');
        }
    }

    // Live Preview Panduan File
    function previewPanduanFile(input) {
        const statusBox = document.getElementById('panduanFileStatus');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (!file.name.toLowerCase().endsWith('.pdf') && file.type !== 'application/pdf') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Wajib PDF',
                    text: 'Dokumen Buku Panduan resmi wajib dalam format PDF.',
                    confirmButtonColor: '#465fff'
                });
                input.value = '';
                if (statusBox) statusBox.classList.add('hidden');
                return;
            }
            if (file.size > 35 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file buku panduan (' + (file.size / 1024 / 1024).toFixed(1) + ' MB) melebihi batas 35 MB.',
                    confirmButtonColor: '#465fff'
                });
                input.value = '';
                if (statusBox) statusBox.classList.add('hidden');
                return;
            }
            if (statusBox) {
                statusBox.textContent = '✓ Dokumen Panduan Baru Dipilih: ' + file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB) — Klik "Simpan File Buku Panduan" untuk mengunggah.';
                statusBox.classList.remove('hidden');
            }
        } else if (statusBox) {
            statusBox.classList.add('hidden');
        }
    }

    // Live Preview TTD File
    function previewTtdFileInput(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const thumb = document.getElementById('ttdFileThumb');
                if (thumb) thumb.src = e.target.result;
                const previewImg = document.getElementById('preview-ttd-img');
                if (previewImg) previewImg.src = e.target.result;
                // Kosongkan canvas data jika upload file
                const canvasInput = document.getElementById('ttd_digital_pengurus_canvas');
                if (canvasInput) canvasInput.value = '';
                const canvasStatus = document.getElementById('canvasAppliedStatus');
                if (canvasStatus) canvasStatus.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    // Live Preview Stempel File
    function previewStempelFileInput(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const thumb = document.getElementById('stempelFileThumb');
                if (thumb) thumb.src = e.target.result;
                const previewImg = document.getElementById('preview-stempel-img');
                if (previewImg) previewImg.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Toggle Stempel Preview on Mockup
    function toggleStempelPreview(show) {
        const previewImg = document.getElementById('preview-stempel-img');
        if (previewImg) {
            previewImg.style.display = show ? 'block' : 'none';
        }
    }

    // Canvas Signature Pad
    let sigCanvas, sigCtx, isDrawing = false, hasDrawn = false;

    function initSignaturePad() {
        sigCanvas = document.getElementById('sigCanvas');
        if (!sigCanvas) return;
        sigCtx = sigCanvas.getContext('2d');
        sigCtx.strokeStyle = '#0f2b5c'; // classic fountain pen ink
        sigCtx.lineWidth = 3;
        sigCtx.lineCap = 'round';
        sigCtx.lineJoin = 'round';

        function getPos(e) {
            const rect = sigCanvas.getBoundingClientRect();
            const clientX = e.clientX || (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
            const clientY = e.clientY || (e.touches && e.touches[0] ? e.touches[0].clientY : 0);
            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        }

        function startDraw(e) {
            e.preventDefault();
            isDrawing = true;
            hasDrawn = true;
            const pos = getPos(e);
            sigCtx.beginPath();
            sigCtx.moveTo(pos.x, pos.y);
        }

        function draw(e) {
            if (!isDrawing) return;
            e.preventDefault();
            const pos = getPos(e);
            sigCtx.lineTo(pos.x, pos.y);
            sigCtx.stroke();
        }

        function stopDraw(e) {
            if (isDrawing) {
                isDrawing = false;
                sigCtx.closePath();
            }
        }

        sigCanvas.addEventListener('mousedown', startDraw);
        sigCanvas.addEventListener('mousemove', draw);
        window.addEventListener('mouseup', stopDraw);

        sigCanvas.addEventListener('touchstart', startDraw, { passive: false });
        sigCanvas.addEventListener('touchmove', draw, { passive: false });
        window.addEventListener('touchend', stopDraw);
    }

    function toggleSignatureCanvas() {
        const box = document.getElementById('signature-canvas-box');
        if (box) {
            box.classList.toggle('hidden');
            if (!box.classList.contains('hidden')) {
                if (!sigCtx) initSignaturePad();
            }
        }
    }

    function clearSignatureCanvas() {
        if (!sigCanvas || !sigCtx) return;
        sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        hasDrawn = false;
        const canvasStatus = document.getElementById('canvasAppliedStatus');
        if (canvasStatus) canvasStatus.classList.add('hidden');
    }

    function applySignatureCanvas() {
        if (!sigCanvas || !hasDrawn) {
            Swal.fire({
                icon: 'warning',
                title: 'Belum Ada Coretan',
                text: 'Silakan goreskan tanda tangan Anda pada kotak canvas terlebih dahulu.',
                confirmButtonColor: '#465fff'
            });
            return;
        }

        const dataUrl = sigCanvas.toDataURL('image/png');
        document.getElementById('ttd_digital_pengurus_canvas').value = dataUrl;

        // Reset file input agar canvas diutamakan
        const fileInput = document.getElementById('ttd_digital_pengurus_file');
        if (fileInput) fileInput.value = '';

        // Update live preview
        const thumb = document.getElementById('ttdFileThumb');
        if (thumb) thumb.src = dataUrl;
        const previewImg = document.getElementById('preview-ttd-img');
        if (previewImg) previewImg.src = dataUrl;

        const canvasStatus = document.getElementById('canvasAppliedStatus');
        if (canvasStatus) canvasStatus.classList.remove('hidden');

        Swal.fire({
            icon: 'success',
            title: 'Tanda Tangan Diterapkan',
            text: 'Goresan tanda tangan berhasil dipasang ke preview. Klik "Simpan Pengaturan Tanda Tangan & Stempel" untuk menyimpan permanen.',
            confirmButtonColor: '#10b981',
            timer: 2500
        });
    }

    // Restore active tab on load
    document.addEventListener('DOMContentLoaded', () => {
        const sessionTab = '{{ session("active_tab") }}';
        const savedTab = sessionTab || localStorage.getItem('admin_settings_active_tab') || 'tab-hero';
        if (document.getElementById(savedTab)) {
            switchTab(savedTab);
        }
    });
</script>
@endsection

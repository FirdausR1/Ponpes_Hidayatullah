@extends('admin.layout')

@section('title', 'Tulis Berita Baru')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.berita.index') }}" class="hover:text-brand-500 transition">Kelola Berita</a>
                <span>/</span>
                <span class="text-slate-600 font-medium">Tulis Berita</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Tulis Berita & Warta Baru</h1>
            <p class="text-sm text-slate-500">Publikasikan informasi, kajian, prestasi, atau pengumuman pesantren.</p>
        </div>
        <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-lg transition">
            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Kembali ke Daftar
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm space-y-1">
            <strong class="font-semibold block">Terdapat kesalahan pengisian formulir:</strong>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card (TailAdmin Style) -->
    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column: Main Fields (2 cols) -->
            <div class="md:col-span-2 space-y-5">
                <div>
                    <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Artikel <span class="text-rose-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Kajian Ba'da Subuh: Menyelami Kitab Riyadhus Shalihin" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition text-sm font-medium">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori <span class="text-rose-500">*</span></label>
                        <select id="category" name="category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="author" class="block text-sm font-semibold text-slate-700 mb-1.5">Penulis / Kontributor</label>
                        <input type="text" id="author" name="author" value="{{ old('author', 'Humas Pesantren') }}" placeholder="Contoh: Ust. Hamdan / Humas" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition text-sm">
                    </div>
                </div>

                <div>
                    <label for="excerpt" class="block text-sm font-semibold text-slate-700 mb-1.5">Ringkasan Singkat (Lead Paragraph)</label>
                    <textarea id="excerpt" name="excerpt" rows="3" placeholder="Ringkasan 1-2 kalimat untuk preview di kartu berita. Jika dikosongkan, sistem akan mengambil otomatis dari isi konten." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition text-sm">{{ old('excerpt') }}</textarea>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="content" class="block text-sm font-semibold text-slate-700">Isi Lengkap Berita <span class="text-rose-500">*</span></label>
                        <span class="text-xs text-slate-400">Mendukung paragraf teks & HTML dasar</span>
                    </div>
                    <textarea id="content" name="content" rows="12" required placeholder="Tuliskan berita lengkap di sini... Anda dapat menggunakan paragraf biasa maupun tag HTML seperti <b>, <p>, <ul>, <blockquote>." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition text-sm font-mono leading-relaxed">{{ old('content') }}</textarea>
                </div>
            </div>

            <!-- Right Column: Media & Publication (1 col) -->
            <div class="space-y-5">
                <div class="p-5 bg-slate-50 rounded-xl border border-slate-200/80 space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Status Publikasi</h3>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-600 mb-1">Status Rilis</label>
                        <select id="status" name="status" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publikasikan Langsung (Published)</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Simpan sebagai Draf (Draft)</option>
                        </select>
                    </div>

                    <p class="text-xs text-slate-500">
                        Artikel berstatus <strong>Published</strong> akan langsung muncul di halaman utama dan arsip berita publik.
                    </p>
                </div>

                <div class="p-5 bg-slate-50 rounded-xl border border-slate-200/80 space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Gambar Utama (Thumbnail)</h3>

                    <div>
                        <label for="image_file" class="block text-xs font-semibold text-slate-600 mb-1">Upload File Gambar</label>
                        <input type="file" id="image_file" name="image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-500 file:text-white hover:file:bg-brand-600 file:cursor-pointer border border-slate-200 bg-white rounded-lg p-1">
                        <span class="text-[11px] text-slate-400 mt-1 block">Format: JPG, PNG, WEBP. Maks 3MB.</span>
                    </div>

                    <div class="relative flex py-1 items-center">
                        <div class="flex-grow border-t border-slate-200"></div>
                        <span class="flex-shrink mx-2 text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Atau</span>
                        <div class="flex-grow border-t border-slate-200"></div>
                    </div>

                    <div>
                        <label for="image" class="block text-xs font-semibold text-slate-600 mb-1">URL Gambar Eksternal</label>
                        <input type="url" id="image" name="image" value="{{ old('image') }}" placeholder="https://images.unsplash.com/..." class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 text-xs placeholder-slate-400 focus:ring-2 focus:ring-brand-500 outline-none">
                        <span class="text-[11px] text-slate-400 mt-1 block">Bisa gunakan link gambar Unsplash atau CDN.</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2 pt-2">
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold py-3 px-4 rounded-xl shadow-md transition text-sm">
                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Simpan & Terbitkan
                    </button>
                    <a href="{{ route('admin.berita.index') }}" class="w-full block text-center py-2.5 text-xs font-medium text-slate-500 hover:text-slate-700 transition">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection

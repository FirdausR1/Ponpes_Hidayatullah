@extends('admin.layout')

@section('title', 'Edit Berita: ' . $article->title)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.berita.index') }}" class="hover:text-brand-500 transition">Kelola Berita</a>
                <span>/</span>
                <span class="text-slate-600 font-medium">Edit Berita #{{ $article->id }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Berita</h1>
            <p class="text-sm text-slate-500 truncate max-w-xl">Memperbarui: "{{ $article->title }}"</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('berita.show', $article->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 px-3 py-2 rounded-lg transition">
                <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                Lihat di Web
            </a>
            <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2 rounded-lg transition">
                <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali
            </a>
        </div>
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
    <form action="{{ route('admin.berita.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column: Main Fields (2 cols) -->
            <div class="md:col-span-2 space-y-5">
                <div>
                    <label for="title" class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Judul Artikel <span class="text-rose-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs sm:text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Kategori <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select id="category" name="category" required class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 pr-10 text-xs sm:text-sm text-gray-800 shadow-theme-xs focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $article->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label for="author" class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Penulis / Kontributor</label>
                        <input type="text" id="author" name="author" value="{{ old('author', $article->author) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs sm:text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition">
                    </div>
                </div>

                <div>
                    <label for="excerpt" class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Ringkasan Singkat (Lead Paragraph)</label>
                    <textarea id="excerpt" name="excerpt" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent p-3.5 text-xs sm:text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition">{{ old('excerpt', $article->excerpt) }}</textarea>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="content" class="block text-xs sm:text-sm font-medium text-gray-700">Isi Lengkap Berita <span class="text-rose-500">*</span></label>
                        <span class="text-xs text-gray-400">Mendukung teks & HTML dasar</span>
                    </div>
                    <textarea id="content" name="content" rows="14" required class="w-full rounded-lg border border-gray-300 bg-transparent p-4 text-xs sm:text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition leading-relaxed">{{ old('content', $article->content) }}</textarea>
                </div>
            </div>

            <!-- Right Column: Media & Publication (1 col) -->
            <div class="space-y-5">
                <div class="p-5 bg-gray-50/70 rounded-xl border border-gray-200 space-y-4">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Status & Metadata</h3>

                    <div>
                        <label for="status" class="mb-1.5 block text-xs font-medium text-gray-700">Status Rilis</label>
                        <div class="relative">
                            <select id="status" name="status" class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 pr-10 text-xs sm:text-sm text-gray-800 shadow-theme-xs focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition">
                                <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Publikasikan Langsung (Published)</option>
                                <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Simpan sebagai Draf (Draft)</option>
                            </select>
                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </span>
                        </div>
                    </div>

                    <div class="text-xs text-gray-500 space-y-1 pt-2 border-t border-gray-200">
                        <div class="flex justify-between">
                            <span>Slug URL:</span>
                            <span class="font-mono text-gray-700 truncate max-w-[140px]">{{ $article->slug }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Pembaca:</span>
                            <span class="font-semibold text-gray-700">{{ number_format($article->views) }} kali</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Dibuat pada:</span>
                            <span class="text-gray-700">{{ $article->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-5 bg-gray-50/70 rounded-xl border border-gray-200 space-y-4">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Gambar Utama (Thumbnail)</h3>

                    @if($article->image)
                        <div class="relative rounded-lg overflow-hidden border border-gray-200 aspect-video bg-gray-100">
                            <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                            <span class="absolute bottom-1 right-1 bg-black/60 backdrop-blur-xs text-[10px] text-white px-2 py-0.5 rounded">Gambar Saat Ini</span>
                        </div>
                    @endif

                    <div>
                        <label for="image_file" class="mb-1.5 block text-xs font-medium text-gray-700">Ganti dengan Upload File</label>
                        <input type="file" id="image_file" name="image_file" accept="image/*" class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        <span class="text-xs text-gray-400 mt-1 block">Format: JPG, PNG, WEBP. Maks 3MB.</span>
                    </div>

                    <div class="relative flex py-1 items-center">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="flex-shrink mx-2 text-[10px] uppercase tracking-wider text-gray-400 font-semibold">Atau</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    <div>
                        <label for="image" class="mb-1.5 block text-xs font-medium text-gray-700">Ganti via URL Gambar</label>
                        <input type="url" id="image" name="image" value="{{ old('image', $article->image) }}" placeholder="https://images.unsplash.com/..." class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:ring-brand-500/10 focus:border-brand-500 outline-none transition shadow-theme-xs">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2 pt-2">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-5 py-3 text-xs sm:text-sm font-medium text-white shadow-theme-xs hover:bg-emerald-700 transition">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.7071 5.29289C17.0976 5.68342 17.0976 6.31658 16.7071 6.70711L8.70711 14.7071C8.31658 15.0976 7.68342 15.0976 7.29289 14.7071L3.29289 10.7071C2.90237 10.3166 2.90237 9.68342 3.29289 9.29289C3.68342 8.90237 4.31658 8.90237 4.70711 9.29289L8 12.5858L15.2929 5.29289C15.6834 4.90237 16.3166 4.90237 16.7071 5.29289Z" fill="currentColor"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.berita.index') }}" class="w-full block text-center py-2.5 text-xs font-medium text-gray-500 hover:text-gray-700 transition">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection

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
                        <label for="content" class="block text-xs sm:text-sm font-medium text-gray-700">Isi Lengkap Berita (Description) <span class="text-rose-500">*</span></label>
                        <span class="text-xs text-gray-400">Editor visual lengkap &amp; otomatis paragraf</span>
                    </div>

                    <textarea id="content" name="content" rows="16" class="w-full rounded-lg border border-gray-300 bg-white p-4 text-xs sm:text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition leading-relaxed">{{ old('content', $article->content) }}</textarea>

                    <div class="mt-2.5 p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-1.5 shadow-sm">
                        <div class="font-bold flex items-center gap-1.5 text-slate-900">
                            <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Panduan Penulisan &amp; Format Berita:</span>
                        </div>
                        <ul class="list-disc pl-5 space-y-1 text-slate-600">
                            <li><strong>Cara Buat Kutipan / Quote Narasumber:</strong> Sorot/blok teks perkataan narasumber, lalu klik tombol <strong>Blockquote (ikon tanda petik &ldquo;&rdquo;)</strong> di toolbar editor. Teks otomatis diberi kotak kartu kutipan elegan dengan lambang tanda kutip seperti berita media nasional.</li>
                            <li><strong>Paragraf Baru:</strong> Tekan <strong>Enter</strong> untuk paragraf baru <code>&lt;p&gt;</code>, atau <strong>Shift + Enter</strong> untuk turun baris rapat <code>&lt;br&gt;</code>.</li>
                        </ul>
                    </div>
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
                        <label for="image" class="mb-1.5 block text-xs font-medium text-gray-700">Ganti via URL Gambar / Link Google Drive</label>
                        <input type="url" id="image" name="image" value="{{ old('image', $article->image) }}" placeholder="https://drive.google.com/... atau https://..." class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:ring-brand-500/10 focus:border-brand-500 outline-none transition shadow-theme-xs">
                        
                        <div class="mt-2.5 p-3 rounded-lg bg-blue-50/80 border border-blue-200 text-[11px] text-blue-900 space-y-1.5">
                            <div class="font-bold flex items-center gap-1.5 text-blue-950">
                                <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                <span>Petunjuk Penggunaan Link Gambar:</span>
                            </div>
                            <p class="leading-relaxed">
                                &bull; <strong>Google Drive:</strong> Bagikan file foto di Drive dengan akses <em>"Siapa saja yang memiliki link"</em>, lalu paste link share-nya di sini. Sistem akan otomatis mengonversinya menjadi gambar.
                            </p>
                            <p class="leading-relaxed text-amber-900 bg-amber-100/70 p-2 rounded-md border border-amber-200">
                                ⚠️ <strong>Medsos (Instagram / Facebook):</strong> Tautan medsos (seperti <code>instagram.com/p/...</code>) adalah tautan halaman web postingan dan diblokir oleh Instagram jika dipasang langsung. Untuk foto dari Instagram, silakan <strong>simpan/screenshot foto</strong> dari Instagram, lalu gunakan tombol <strong>"Ganti dengan Upload File"</strong> di atas.
                            </p>
                        </div>
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

@section('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    // Konfigurasi CKEditor 4 sesuai tampilan screenshot user
    CKEDITOR.config.versionCheck = false;
    CKEDITOR.config.extraCss = `
        body { font-family: 'Plus Jakarta Sans', Arial, sans-serif; font-size: 14px; line-height: 1.7; color: #1e293b; padding: 12px; }
        blockquote {
            position: relative;
            background: #f3f5f8;
            border: 1px solid #e5e9f0;
            border-radius: 14px;
            padding: 20px 22px 20px 64px;
            margin: 20px 0;
            font-style: italic;
            color: #334155;
            font-size: 14.5px;
            line-height: 1.75;
        }
        blockquote::before {
            content: "";
            position: absolute;
            left: 18px;
            top: 18px;
            width: 32px;
            height: 32px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23cbd5e1'%3E%3Cpath d='M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.95;
        }
        blockquote p { margin: 0 0 8px 0; color: inherit; font-style: italic; }
        blockquote p:last-child { margin-bottom: 0; }
    `;
    CKEDITOR.replace('content', {
        language: 'id',
        height: 420,
        toolbar: [
            { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
            { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
            { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
            { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
            { name: 'tools', items: [ 'Maximize', 'Source' ] },
            '/',
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
            { name: 'styles', items: [ 'Styles', 'Format' ] },
            { name: 'about', items: [ 'About' ] }
        ],
        removePlugins: 'exportpdf',
        format_tags: 'p;h1;h2;h3;pre',
    });

    // Validasi form saat disubmit
    const articleForm = document.querySelector('form');
    if (articleForm) {
        articleForm.addEventListener('submit', function(e) {
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            const contentVal = document.getElementById('content').value.trim();
            if (!contentVal || contentVal === '<p></p>' || contentVal === '<p>&nbsp;</p>') {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Isi Berita Masih Kosong',
                    text: 'Silakan tulis isi berita terlebih dahulu sebelum menyimpan.',
                    confirmButtonColor: '#16a34a'
                });
                CKEDITOR.instances['content'].focus();
            }
        });
    }
</script>
@endsection

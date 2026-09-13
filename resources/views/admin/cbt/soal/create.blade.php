@extends('admin.layout')

@section('title', 'Tambah Soal CBT')

@section('styles')
<!-- KaTeX CSS untuk rumus Matematika -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
<!-- Google Fonts: Amiri & Scheherazade New untuk Teks Arab -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
<style>
    .arabic-input {
        font-family: 'Amiri', 'Scheherazade New', serif;
        direction: rtl;
        text-align: right;
        font-size: 1.3rem;
        line-height: 2;
    }
    .katex { font-size: 1.15em !important; }
</style>
@endsection

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Soal Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Tulis butir soal seleksi lengkap dengan rumus matematika KaTeX, gambar ilustrasi, dan teks Arab.</p>
        </div>
        <a href="{{ route('admin.cbt.soal.index') }}" class="ta-btn-outline">
            Kembali ke Bank Soal
        </a>
    </div>

    <!-- Form Container -->
    <form action="{{ route('admin.cbt.soal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="ta-card p-6 space-y-5">
            <!-- Row: Kategori & Bobot -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kategori Soal <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <select id="kategoriSelect" onchange="applyKategori(this.value)" class="w-1/2 px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none">
                            <option value="">-- Pilih Template Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                            <option value="custom">+ Kategori Lainnya...</option>
                        </select>
                        <input type="text" name="kategori" id="kategoriInput" value="{{ old('kategori', 'Matematika') }}" required placeholder="Contoh: Matematika, Bahasa Arab"
                               class="w-1/2 px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Bobot Poin <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="bobot" value="{{ old('bobot', 1) }}" min="1" max="100" required
                           class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none font-medium">
                </div>
            </div>

            <!-- Format Badges & Helper Toggles -->
            <div class="flex flex-wrap items-center gap-6 p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="is_math" id="isMathToggle" value="1" {{ old('is_math', '1') ? 'checked' : '' }} onchange="updatePreviews()" class="rounded text-brand-600 focus:ring-brand-500 w-4 h-4">
                    <span class="font-semibold text-slate-700">Soal Mengandung Rumus Matematika (KaTeX)</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="is_arabic" id="isArabicToggle" value="1" {{ old('is_arabic') ? 'checked' : '' }} onchange="toggleArabicMode(this.checked)" class="rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                    <span class="font-semibold text-emerald-800">Soal Mengandung Bahasa Arab (Font Amiri / RTL)</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer select-none ml-auto">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded text-brand-600 focus:ring-brand-500 w-4 h-4">
                    <span class="font-semibold text-slate-700">Aktifkan Soal</span>
                </label>
            </div>

            <!-- TOOLBAR RUMUS MATEMATIKA LENGKAP & FLEKSIBEL -->
            <div id="mathToolbar" class="rounded-xl border border-purple-200 bg-purple-50/40 p-3.5 space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-purple-100 pb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-purple-600 text-white font-bold text-xs">LaTeX KaTeX</span>
                        <span class="text-xs font-bold text-purple-950">Toolbar Sisip Rumus Lengkap:</span>
                    </div>
                    <div class="text-[11px] text-purple-700">
                        Klik tombol untuk menyisipkan kode rumus ke kursor aktif
                    </div>
                </div>

                <!-- Tabs Kategori Rumus -->
                <div class="flex flex-wrap gap-1 border-b border-purple-200/60 pb-1.5 text-xs font-semibold">
                    <button type="button" onclick="switchMathTab('dasar')" id="tab-btn-dasar" class="math-tab-btn px-2.5 py-1 rounded-md bg-purple-600 text-white shadow-xs">Dasar & Pecahan</button>
                    <button type="button" onclick="switchMathTab('aljabar')" id="tab-btn-aljabar" class="math-tab-btn px-2.5 py-1 rounded-md bg-white text-purple-800 hover:bg-purple-100 border border-purple-200">Aljabar & Kurung</button>
                    <button type="button" onclick="switchMathTab('trigono')" id="tab-btn-trigono" class="math-tab-btn px-2.5 py-1 rounded-md bg-white text-purple-800 hover:bg-purple-100 border border-purple-200">Geometri & Sudut</button>
                    <button type="button" onclick="switchMathTab('kalkulus')" id="tab-btn-kalkulus" class="math-tab-btn px-2.5 py-1 rounded-md bg-white text-purple-800 hover:bg-purple-100 border border-purple-200">Matriks & Kalkulus</button>
                    <button type="button" onclick="switchMathTab('yunani')" id="tab-btn-yunani" class="math-tab-btn px-2.5 py-1 rounded-md bg-white text-purple-800 hover:bg-purple-100 border border-purple-200">Simbol & Yunani</button>
                    <button type="button" onclick="switchMathTab('custom')" id="tab-btn-custom" class="math-tab-btn px-2.5 py-1 rounded-md bg-white text-purple-800 hover:bg-purple-100 border border-purple-200 font-bold">+ Rumus Kustom</button>
                </div>

                <!-- Tab 1: Dasar & Pecahan -->
                <div id="math-tab-dasar" class="math-tab-content flex flex-wrap gap-1.5 text-xs">
                    <button type="button" onclick="insertSnippet('$$\\frac{a}{b}$$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Pecahan $\frac{a}{b}$</button>
                    <button type="button" onclick="insertSnippet('$$\\sqrt{x}$$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Akar $\sqrt{x}$</button>
                    <button type="button" onclick="insertSnippet('$$\\sqrt[n]{x}$$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Akar-n $\sqrt[n]{x}$</button>
                    <button type="button" onclick="insertSnippet('$x^2$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Pangkat $x^2$</button>
                    <button type="button" onclick="insertSnippet('$x_1$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Indeks $x_1$</button>
                    <button type="button" onclick="insertSnippet('$\\times$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Kali $\times$</button>
                    <button type="button" onclick="insertSnippet('$\\div$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Bagi $\div$</button>
                    <button type="button" onclick="insertSnippet('$\\pm$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Plus-Minus $\pm$</button>
                    <button type="button" onclick="insertSnippet('$\\neq$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Tidak Sama $\neq$</button>
                    <button type="button" onclick="insertSnippet('$\\le$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Kurang Sama $\le$</button>
                    <button type="button" onclick="insertSnippet('$\\ge$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Lebih Sama $\ge$</button>
                    <button type="button" onclick="insertSnippet('$\\%$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Persen $\%$</button>
                </div>

                <!-- Tab 2: Aljabar & Kurung -->
                <div id="math-tab-aljabar" class="math-tab-content hidden flex flex-wrap gap-1.5 text-xs">
                    <button type="button" onclick="insertSnippet('$$\\left( \\frac{a}{b} \\right)$$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Kurung Besar $\left(\frac{a}{b}\right)$</button>
                    <button type="button" onclick="insertSnippet('$$|x|$$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Nilai Mutlak $|x|$</button>
                    <button type="button" onclick="insertSnippet('$$x = \\frac{-b \\pm \\sqrt{b^2 - 4ac}}{2a}$$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Rumus ABC Kuadrat</button>
                    <button type="button" onclick="insertSnippet('$f(x) = ax^2 + bx + c$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Persamaan Kuadrat $f(x)$</button>
                    <button type="button" onclick="insertSnippet('$A \\cap B$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Irisan $A \cap B$</button>
                    <button type="button" onclick="insertSnippet('$A \\cup B$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Gabungan $A \cup B$</button>
                    <button type="button" onclick="insertSnippet('$x \\in S$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Elemen $x \in S$</button>
                    <button type="button" onclick="insertSnippet('$\\infty$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Tak Hingga $\infty$</button>
                </div>

                <!-- Tab 3: Geometri & Sudut -->
                <div id="math-tab-trigono" class="math-tab-content hidden flex flex-wrap gap-1.5 text-xs">
                    <button type="button" onclick="insertSnippet('$90^\\circ$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Derajat $90^\circ$</button>
                    <button type="button" onclick="insertSnippet('$\\sin(\\theta)$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Sinus $\sin(\theta)$</button>
                    <button type="button" onclick="insertSnippet('$\\cos(\\theta)$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Kosinus $\cos(\theta)$</button>
                    <button type="button" onclick="insertSnippet('$\\tan(\\theta)$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Tangen $\tan(\theta)$</button>
                    <button type="button" onclick="insertSnippet('$\\Delta ABC$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Segitiga $\Delta ABC$</button>
                    <button type="button" onclick="insertSnippet('$\\angle ABC$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Sudut $\angle ABC$</button>
                    <button type="button" onclick="insertSnippet('$\\pi$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Pi $\pi$</button>
                    <button type="button" onclick="insertSnippet('$\\theta$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Theta $\theta$</button>
                </div>

                <!-- Tab 4: Matriks & Kalkulus -->
                <div id="math-tab-kalkulus" class="math-tab-content hidden flex flex-wrap gap-1.5 text-xs">
                    <button type="button" onclick="insertSnippet('$$\\begin{pmatrix} a & b \\\\ c & d \\end{pmatrix}$$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Matriks 2x2</button>
                    <button type="button" onclick="insertSnippet('$$\\begin{vmatrix} a & b \\\\ c & d \\end{vmatrix}$$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Determinan $|M|$</button>
                    <button type="button" onclick="insertSnippet('$$\\int_{a}^{b} f(x)\\,dx$$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Integral $\int_a^b$</button>
                    <button type="button" onclick="insertSnippet('$$\\sum_{i=1}^{n} x_i$$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Sigma $\sum$</button>
                    <button type="button" onclick="insertSnippet('$$\\lim_{x \\to 0} f(x)$$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Limit $\lim$</button>
                    <button type="button" onclick="insertSnippet('$$\\frac{df}{dx}$$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Turunan $\frac{df}{dx}$</button>
                    <button type="button" onclick="insertSnippet('$\\log_a(b)$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Logaritma $\log$</button>
                    <button type="button" onclick="insertSnippet('$\\vec{v}$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Vektor $\vec{v}$</button>
                </div>

                <!-- Tab 5: Simbol & Huruf Yunani -->
                <div id="math-tab-yunani" class="math-tab-content hidden flex flex-wrap gap-1.5 text-xs">
                    <button type="button" onclick="insertSnippet('$\\alpha$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Alpha $\alpha$</button>
                    <button type="button" onclick="insertSnippet('$\\beta$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Beta $\beta$</button>
                    <button type="button" onclick="insertSnippet('$\\gamma$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Gamma $\gamma$</button>
                    <button type="button" onclick="insertSnippet('$\\lambda$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Lambda $\lambda$</button>
                    <button type="button" onclick="insertSnippet('$\\mu$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Mu $\mu$</button>
                    <button type="button" onclick="insertSnippet('$\\sigma$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Sigma $\sigma$</button>
                    <button type="button" onclick="insertSnippet('$\\Omega$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Ohm $\Omega$</button>
                    <button type="button" onclick="insertSnippet('$\\rightarrow$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Panah $\rightarrow$</button>
                    <button type="button" onclick="insertSnippet('$\\rightleftharpoons$')" class="px-2.5 py-1 bg-white hover:bg-purple-100 border border-purple-200 rounded font-mono text-purple-900 shadow-2xs">Reaksi $\rightleftharpoons$</button>
                </div>

                <!-- Tab 6: Custom Formula / Panduan Bebas -->
                <div id="math-tab-custom" class="math-tab-content hidden space-y-2 text-xs">
                    <div class="flex items-center gap-2">
                        <input type="text" id="customKatexInput" placeholder="Ketik rumus LaTeX bebas di sini, contoh: \sqrt{x^2 + y^2}"
                               class="flex-1 px-3 py-1.5 text-xs font-mono bg-white border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 outline-none">
                        <button type="button" onclick="insertCustomFormula(false)" class="px-3 py-1.5 bg-purple-700 hover:bg-purple-800 text-white rounded-lg font-bold shadow-2xs">
                            Sisip Inline ($...$)
                        </button>
                        <button type="button" onclick="insertCustomFormula(true)" class="px-3 py-1.5 bg-purple-900 hover:bg-black text-white rounded-lg font-bold shadow-2xs">
                            Sisip Blok Baris ($$...$$)
                        </button>
                    </div>
                    <p class="text-[11px] text-purple-800 leading-normal">
                        💡 <strong>Cara Menulis Rumus Bebas:</strong> Anda bisa langsung mengetik rumus matematika apa saja menggunakan standar LaTeX di dalam textarea soal. Gunakan <code>$kode$</code> untuk sebaris kalimat atau <code>$$kode$$</code> untuk rumus di tengah baris sendiri. Sistem otomatis me-render KaTeX!
                    </p>
                </div>
            </div>

            <!-- Teks Pertanyaan / Soal -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Isi Teks Soal <span class="text-rose-500">*</span>
                </label>
                <textarea name="soal" id="soalTextarea" rows="4" required oninput="updatePreviews()"
                          placeholder="Tuliskan teks pertanyaan di sini. Anda dapat menggunakan format LaTeX KaTeX (seperti $...$ atau $$...$$) untuk rumus matematika atau teks Arab."
                          class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition">{{ old('soal') }}</textarea>
            </div>

            <!-- UNGGAH GAMBAR / ILUSTRASI SOAL -->
            <div class="pt-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                    <span>Gambar / Ilustrasi Soal (Opsional)</span>
                    <span class="text-[11px] font-normal text-slate-400">Cocok untuk diagram, grafik, geometri, atau potongan teks</span>
                </label>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                    <input type="file" name="gambar_file" id="gambarInput" accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                           onchange="handleImagePreview(event)"
                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                    <span class="text-[11px] text-slate-400 whitespace-nowrap">Format: JPG, PNG, WEBP (Maks 2MB)</span>
                </div>

                <!-- Preview Gambar yang Diunggah -->
                <div id="imagePreviewContainer" class="hidden mt-3 relative inline-block">
                    <img id="imagePreview" src="#" alt="Preview Gambar Soal" class="max-h-48 max-w-full rounded-lg border border-slate-200 shadow-xs object-contain bg-white p-1">
                    <button type="button" onclick="removeImageUpload()" class="absolute -top-2 -right-2 bg-rose-600 text-white rounded-full p-1 shadow hover:bg-rose-700 transition" title="Batal unggah gambar">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <span class="text-[11px] text-slate-500 block mt-1">Gambar ini akan tampil di lembar ujian santri</span>
                </div>
            </div>

            <!-- Live Preview Box -->
            <div class="p-4 bg-slate-50/80 border border-dashed border-slate-300 rounded-xl space-y-2">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Live Preview Tampilan Soal:</span>
                <div id="soalLivePreview" class="text-slate-800 text-sm md:text-base leading-relaxed p-3 bg-white rounded-lg border border-slate-200 min-h-[56px] space-y-3">
                    <span class="text-slate-400 italic text-xs">Preview soal akan muncul di sini secara otomatis...</span>
                </div>
            </div>

            <!-- OPSI JAWABAN (A, B, C, D, E) -->
            <div class="pt-4 border-t border-slate-200 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Pilihan Ganda & Kunci Jawaban</h3>
                    <span class="text-[11px] text-slate-400">Klik huruf lingkaran atau radio untuk memilih kunci yang benar</span>
                </div>

                <div class="space-y-3">
                    @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                        <div class="flex items-start gap-3 p-3 rounded-lg border {{ old('kunci_jawaban', 'A') === $opt ? 'bg-emerald-50/50 border-emerald-300' : 'bg-white border-slate-200' }}" id="opt-container-{{ $opt }}">
                            <div class="flex items-center gap-2 pt-2 shrink-0">
                                <input type="radio" name="kunci_jawaban" value="{{ $opt }}" id="kunci_{{ $opt }}" {{ old('kunci_jawaban', 'A') === $opt ? 'checked' : '' }}
                                       onchange="handleKunciChange('{{ $opt }}')"
                                       class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                <label for="kunci_{{ $opt }}" class="w-6 h-6 rounded-full bg-slate-800 text-white flex items-center justify-center text-xs font-bold cursor-pointer">
                                    {{ $opt }}
                                </label>
                            </div>
                            <div class="flex-1">
                                <input type="text" name="opsi_{{ strtolower($opt) }}" id="opsi_{{ strtolower($opt) }}" value="{{ old('opsi_' . strtolower($opt)) }}" {{ in_array($opt, ['A','B','C','D']) ? 'required' : '' }} oninput="updatePreviews()"
                                       placeholder="Isi teks pilihan {{ $opt }} {{ $opt === 'E' ? '(Opsional)' : '' }}"
                                       class="formula-target w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none">
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-slate-500 italic">* Pilih tombol radio lingkaran di sebelah kiri huruf untuk menentukan <strong>Kunci Jawaban yang Benar</strong>.</p>
            </div>

            <!-- Pembahasan / Catatan -->
            <div class="pt-4 border-t border-slate-200">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Pembahasan Jawaban (Opsional)
                </label>
                <textarea name="pembahasan" rows="2" placeholder="Catatan solusi atau cara pengerjaan untuk arsip guru penguji..."
                          class="w-full px-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none">{{ old('pembahasan') }}</textarea>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.cbt.soal.index') }}" class="ta-btn-outline">
                Batal
            </a>
            <button type="submit" class="ta-btn-primary">
                Simpan Soal ke Bank Soal
            </button>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
<script>
    let lastFocusedInput = null;

    document.addEventListener('DOMContentLoaded', () => {
        const textarea = document.getElementById('soalTextarea');
        lastFocusedInput = textarea;

        // Track focus on all inputs
        document.querySelectorAll('input[type="text"], textarea').forEach(el => {
            el.addEventListener('focus', function() {
                lastFocusedInput = this;
            });
        });

        setTimeout(updatePreviews, 300);
    });

    function applyKategori(val) {
        if (val && val !== 'custom') {
            document.getElementById('kategoriInput').value = val;
            if (val === 'Matematika') {
                document.getElementById('isMathToggle').checked = true;
                document.getElementById('isArabicToggle').checked = false;
                toggleArabicMode(false);
            } else if (val === 'Bahasa Arab') {
                document.getElementById('isArabicToggle').checked = true;
                document.getElementById('isMathToggle').checked = false;
                toggleArabicMode(true);
            }
            updatePreviews();
        }
    }

    function toggleArabicMode(isArabic) {
        const textarea = document.getElementById('soalTextarea');
        const preview = document.getElementById('soalLivePreview');
        if (isArabic) {
            textarea.classList.add('arabic-input');
            preview.classList.add('arabic-input');
        } else {
            textarea.classList.remove('arabic-input');
            preview.classList.remove('arabic-input');
        }
    }

    function switchMathTab(tabName) {
        document.querySelectorAll('.math-tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.math-tab-btn').forEach(btn => {
            btn.classList.remove('bg-purple-600', 'text-white', 'shadow-xs');
            btn.classList.add('bg-white', 'text-purple-800', 'border', 'border-purple-200');
        });

        const activeContent = document.getElementById('math-tab-' + tabName);
        const activeBtn = document.getElementById('tab-btn-' + tabName);
        if (activeContent) activeContent.classList.remove('hidden');
        if (activeBtn) {
            activeBtn.classList.remove('bg-white', 'text-purple-800', 'border', 'border-purple-200');
            activeBtn.classList.add('bg-purple-600', 'text-white', 'shadow-xs');
        }
    }

    function insertSnippet(snippet) {
        const target = lastFocusedInput || document.getElementById('soalTextarea');
        const start = target.selectionStart ?? target.value.length;
        const end = target.selectionEnd ?? target.value.length;
        const text = target.value;
        target.value = text.substring(0, start) + snippet + text.substring(end);
        target.focus();
        target.selectionStart = target.selectionEnd = start + snippet.length;
        document.getElementById('isMathToggle').checked = true;
        updatePreviews();
    }

    function insertCustomFormula(isBlock) {
        const input = document.getElementById('customKatexInput');
        const rawVal = input.value.trim();
        if (!rawVal) return;
        const snippet = isBlock ? `$$${rawVal}$$` : `$${rawVal}$`;
        insertSnippet(snippet);
        input.value = '';
    }

    function handleImagePreview(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('imagePreview');
                previewImg.src = e.target.result;
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
                updatePreviews();
            };
            reader.readAsDataURL(file);
        }
    }

    function removeImageUpload() {
        const input = document.getElementById('gambarInput');
        input.value = '';
        document.getElementById('imagePreviewContainer').classList.add('hidden');
        document.getElementById('imagePreview').src = '#';
        updatePreviews();
    }

    function handleKunciChange(opt) {
        ['A', 'B', 'C', 'D', 'E'].forEach(o => {
            const container = document.getElementById('opt-container-' + o);
            if (container) {
                if (o === opt) {
                    container.classList.add('bg-emerald-50/50', 'border-emerald-300');
                    container.classList.remove('bg-white', 'border-slate-200');
                } else {
                    container.classList.remove('bg-emerald-50/50', 'border-emerald-300');
                    container.classList.add('bg-white', 'border-slate-200');
                }
            }
        });
    }

    function updatePreviews() {
        const text = document.getElementById('soalTextarea').value;
        const preview = document.getElementById('soalLivePreview');
        const imagePreview = document.getElementById('imagePreview');
        const hasImage = !document.getElementById('imagePreviewContainer').classList.contains('hidden');

        if (!text.trim() && !hasImage) {
            preview.innerHTML = '<span class="text-slate-400 italic text-xs">Preview soal akan muncul di sini secara otomatis...</span>';
            return;
        }

        let html = '';
        if (text.trim()) {
            html += `<div>${text.replace(/\n/g, '<br>')}</div>`;
        }
        if (hasImage) {
            html += `<div><img src="${imagePreview.src}" class="max-h-48 max-w-full rounded-lg border border-slate-200 shadow-xs object-contain my-2"></div>`;
        }

        preview.innerHTML = html;

        // Render KaTeX if available
        if (typeof renderMathInElement === 'function') {
            renderMathInElement(preview, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},
                    {left: '\\[', right: '\\]', display: true}
                ],
                throwOnError: false
            });
        }
    }
</script>
@endsection

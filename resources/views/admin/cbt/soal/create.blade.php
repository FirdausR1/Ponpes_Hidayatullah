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
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Tambah Soal Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Tulis butir soal lengkap dengan rumus KaTeX, gambar ilustrasi, dan teks Arab.</p>
        </div>
        <a href="{{ route('admin.cbt.soal.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
            &larr; Kembali
        </a>
    </div>

    <!-- Form Container -->
    <form action="{{ route('admin.cbt.soal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="ta-card p-6 space-y-5">
            <!-- Row: Jenjang, Kategori & Bobot -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jenjang Sasaran <span class="text-rose-500">*</span>
                    </label>
                    <select name="jenjang" required class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none font-medium text-gray-800">
                        <option value="Semua" {{ old('jenjang', 'Semua') === 'Semua' ? 'selected' : '' }}>Semua (MTs &amp; MA)</option>
                        <option value="MTs" {{ old('jenjang') === 'MTs' ? 'selected' : '' }}>Khusus MTs</option>
                        <option value="MA" {{ old('jenjang') === 'MA' ? 'selected' : '' }}>Khusus MA</option>
                    </select>
                </div>

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

            <!-- Pengaturan Soal -->
            <div class="flex flex-wrap items-center gap-5 px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="is_math" id="isMathToggle" value="1" {{ old('is_math', '1') ? 'checked' : '' }} onchange="updatePreviews()" class="rounded text-brand-600 focus:ring-brand-500 w-4 h-4">
                    <span class="font-medium text-gray-700">Ada rumus matematika</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="is_arabic" id="isArabicToggle" value="1" {{ old('is_arabic') ? 'checked' : '' }} onchange="toggleArabicMode(this.checked)" class="rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                    <span class="font-medium text-gray-700">Ada teks Bahasa Arab</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer select-none ml-auto">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded text-brand-600 focus:ring-brand-500 w-4 h-4">
                    <span class="font-medium text-gray-700">Aktifkan soal</span>
                </label>
            </div>

            <!-- TOOLBAR RUMUS VISUAL (MUDAH) -->
            <div id="mathToolbar" class="rounded-xl border border-gray-200 bg-gray-50 overflow-hidden">
                <div class="flex items-center justify-between px-4 py-2.5 border-b border-gray-200 bg-white">
                    <span class="text-xs font-bold text-gray-700">Sisipkan Simbol / Rumus</span>
                    <span class="text-[11px] text-gray-400">Klik tombol di bawah → simbol langsung masuk ke teks soal</span>
                </div>

                <!-- Grid Tombol Visual -->
                <div class="p-3 space-y-2">

                    <!-- Baris 1: Operasi Dasar -->
                    <div class="flex flex-wrap gap-1.5">
                        <span class="text-[10px] font-bold text-gray-400 uppercase self-center mr-1 w-full">Dasar</span>

                        <button type="button" onclick="insertSnippet('$\\frac{a}{b}$')" title="Pecahan a/b"
                                class="math-vis-btn">
                            <span class="math-render">\(\frac{a}{b}\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\sqrt{x}$')" title="Akar kuadrat"
                                class="math-vis-btn">
                            <span class="math-render">\(\sqrt{x}\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\sqrt[n]{x}$')" title="Akar ke-n"
                                class="math-vis-btn">
                            <span class="math-render">\(\sqrt[n]{x}\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$x^{2}$')" title="Pangkat / eksponen"
                                class="math-vis-btn">
                            <span class="math-render">\(x^{2}\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$x_{1}$')" title="Indeks bawah"
                                class="math-vis-btn">
                            <span class="math-render">\(x_{1}\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\times$')" title="Tanda kali"
                                class="math-vis-btn">
                            <span class="math-render">\(\times\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\div$')" title="Tanda bagi"
                                class="math-vis-btn">
                            <span class="math-render">\(\div\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\pm$')" title="Plus minus"
                                class="math-vis-btn">
                            <span class="math-render">\(\pm\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\neq$')" title="Tidak sama dengan"
                                class="math-vis-btn">
                            <span class="math-render">\(\neq\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\leq$')" title="Kurang dari atau sama dengan"
                                class="math-vis-btn">
                            <span class="math-render">\(\leq\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\geq$')" title="Lebih dari atau sama dengan"
                                class="math-vis-btn">
                            <span class="math-render">\(\geq\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\%$')" title="Persen"
                                class="math-vis-btn">
                            <span class="math-render">\(\%\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\pi$')" title="Pi (3.14...)"
                                class="math-vis-btn">
                            <span class="math-render">\(\pi\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\infty$')" title="Tak hingga"
                                class="math-vis-btn">
                            <span class="math-render">\(\infty\)</span>
                        </button>
                    </div>

                    <!-- Baris 2: Rumus Siap Pakai -->
                    <div class="flex flex-wrap gap-1.5">
                        <span class="text-[10px] font-bold text-gray-400 uppercase self-center mr-1 w-full">Rumus Siap Pakai</span>

                        <button type="button" onclick="insertSnippet('$$x = \\frac{-b \\pm \\sqrt{b^2-4ac}}{2a}$$')" title="Rumus ABC (persamaan kuadrat)"
                                class="math-vis-btn px-3">
                            <span class="math-render text-[11px]">\(x=\frac{-b\pm\sqrt{b^2-4ac}}{2a}\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$$\\rho = \\frac{m}{V}$$')" title="Massa jenis"
                                class="math-vis-btn px-3">
                            <span class="math-render">\(\rho=\frac{m}{V}\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$$v = \\frac{s}{t}$$')" title="Kecepatan"
                                class="math-vis-btn px-3">
                            <span class="math-render">\(v=\frac{s}{t}\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$$F = m \\cdot a$$')" title="Gaya (Hukum Newton)"
                                class="math-vis-btn px-3">
                            <span class="math-render">\(F=m\cdot a\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$$E_k = \\frac{1}{2}mv^2$$')" title="Energi kinetik"
                                class="math-vis-btn px-3">
                            <span class="math-render">\(E_k=\frac{1}{2}mv^2\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$$P = \\frac{F}{A}$$')" title="Tekanan"
                                class="math-vis-btn px-3">
                            <span class="math-render">\(P=\frac{F}{A}\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$V = I \\times R$')" title="Hukum Ohm"
                                class="math-vis-btn px-3">
                            <span class="math-render">\(V=I\times R\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$90^{\\circ}$')" title="Derajat (sudut)"
                                class="math-vis-btn">
                            <span class="math-render">\(90^{\circ}\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\sin\\theta$')" title="Sinus"
                                class="math-vis-btn">
                            <span class="math-render">\(\sin\theta\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\cos\\theta$')" title="Kosinus"
                                class="math-vis-btn">
                            <span class="math-render">\(\cos\theta\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\text{H}_2\\text{O}$')" title="Air (kimia)"
                                class="math-vis-btn px-3">
                            <span class="math-render">\(\text{H}_2\text{O}\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\text{CO}_2$')" title="Karbon dioksida"
                                class="math-vis-btn px-3">
                            <span class="math-render">\(\text{CO}_2\)</span>
                        </button>
                        <button type="button" onclick="insertSnippet('$\\rightarrow$')" title="Panah reaksi kimia"
                                class="math-vis-btn">
                            <span class="math-render">\(\rightarrow\)</span>
                        </button>
                    </div>

                    <!-- Input Rumus Bebas -->
                    <div class="flex items-center gap-2 pt-1 border-t border-gray-200">
                        <span class="text-[11px] text-gray-500 font-medium shrink-0">Rumus lain:</span>
                        <input type="text" id="customKatexInput"
                               placeholder='Ketik kode LaTeX, contoh: \log_a(b) lalu klik Sisip'
                               class="flex-1 px-3 py-1.5 text-xs font-mono bg-white border border-gray-200 rounded-lg focus:border-brand-500 outline-none">
                        <button type="button" onclick="insertCustomFormula(false)"
                                class="px-3 py-1.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-xs font-semibold transition shrink-0">
                            Sisip
                        </button>
                    </div>
                </div>
            </div>

            <style>
                .math-vis-btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 42px;
                    min-height: 36px;
                    padding: 4px 8px;
                    background: white;
                    border: 1px solid #e5e7eb;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 13px;
                    transition: background 0.15s, border-color 0.15s;
                    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
                }
                .math-vis-btn:hover {
                    background: #f3f4f6;
                    border-color: #9ca3af;
                }
                .math-vis-btn .math-render {
                    pointer-events: none;
                }
            </style>

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
            <div class="pt-4 border-t border-gray-200 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Pilihan Ganda &amp; Kunci Jawaban</h3>
                    <span class="text-[11px] text-gray-400">Klik radio untuk memilih jawaban yang benar</span>
                </div>

                <div class="space-y-2">
                    @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                        <div class="flex items-center gap-3 p-3 rounded-lg border transition {{ old('kunci_jawaban', 'A') === $opt ? 'bg-emerald-50 border-emerald-300' : 'bg-white border-gray-200 hover:border-gray-300' }}" id="opt-container-{{ $opt }}">
                            <input type="radio" name="kunci_jawaban" value="{{ $opt }}" id="kunci_{{ $opt }}"
                                   {{ old('kunci_jawaban', 'A') === $opt ? 'checked' : '' }}
                                   onchange="handleKunciChange('{{ $opt }}')"
                                   class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 cursor-pointer shrink-0">
                            <label for="kunci_{{ $opt }}"
                                   class="w-6 h-6 rounded-full border-2 {{ old('kunci_jawaban', 'A') === $opt ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-gray-300 bg-white text-gray-600' }} flex items-center justify-center text-xs font-bold cursor-pointer shrink-0">
                                {{ $opt }}
                            </label>
                            <input type="text" name="opsi_{{ strtolower($opt) }}" id="opsi_{{ strtolower($opt) }}"
                                   value="{{ old('opsi_' . strtolower($opt)) }}"
                                   {{ in_array($opt, ['A','B','C','D']) ? 'required' : '' }}
                                   oninput="updatePreviews()"
                                   placeholder="Isi teks pilihan {{ $opt }}{{ $opt === 'E' ? ' (opsional)' : '' }}"
                                   class="formula-target flex-1 px-3.5 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none">
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 italic">* Klik radio di kiri untuk menentukan kunci jawaban yang benar. Opsi E bersifat opsional.</p>
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

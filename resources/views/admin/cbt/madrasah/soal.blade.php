@extends('admin.layout')

@section('title', 'Kelola Butir Soal Ujian: ' . $exam->mata_pelajaran)

@section('content')
<div class="space-y-6" x-data="{ modalTambah: false, modalImport: false, editMode: false, currentQuestion: {} }">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.cbt.madrasah.show', $exam->id) }}" class="text-xs text-emerald-700 hover:underline font-semibold flex items-center gap-1">
                    &larr; Kembali ke Kelola Nilai
                </a>
                <span class="text-gray-300">•</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">
                    Bank Butir Soal CBT
                </span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800">
                    {{ $exam->jurusan }}
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-snug">
                Bank Soal: {{ $exam->mata_pelajaran }}
            </h1>
            <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $exam->nama_ujian }} &bull; Target: <strong class="text-gray-700">{{ $exam->jumlah_soal }} Butir</strong></p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button type="button" @click="editMode = false; currentQuestion = {}; modalTambah = true" class="ta-btn-primary text-xs px-3.5 py-2.5 flex items-center gap-1.5 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                + Tambah Soal Manual
            </button>
            <button type="button" @click="modalImport = true" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition shadow-xs">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import Excel
            </button>
            <a href="{{ route('admin.cbt.madrasah.soal.template', $exam->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-gray-600 hover:text-gray-900 border border-gray-200 text-xs font-semibold hover:bg-gray-50 transition" title="Unduh Format Excel Soal">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh Template
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="ta-alert ta-alert-success flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">&times;</button>
    </div>
    @endif
    @if(session('error'))
    <div class="ta-alert ta-alert-danger flex items-center justify-between">
        <span>{{ session('error') }}</span>
        <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 font-bold">&times;</button>
    </div>
    @endif

    <!-- Kartu Status Butir Soal -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Soal Tersedia</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-bold font-mono text-gray-900">{{ $exam->questions->count() }}</span>
                <span class="text-xs text-gray-400">/ {{ $exam->jumlah_soal }} butir target</span>
            </div>
            @if($exam->questions->count() < $exam->jumlah_soal)
                <span class="text-[11px] text-amber-600 font-semibold block mt-1">Kurang {{ $exam->jumlah_soal - $exam->questions->count() }} soal dari target sesi ujian.</span>
            @else
                <span class="text-[11px] text-emerald-600 font-semibold block mt-1">✓ Butir soal telah terpenuhi lengkap.</span>
            @endif
        </div>

        <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Total Bobot Nilai</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-bold font-mono text-purple-700">{{ $exam->questions->sum('bobot') ?: $exam->questions->count() }}</span>
                <span class="text-xs text-gray-400">poin evaluasi</span>
            </div>
            <span class="text-[11px] text-gray-400 block mt-1">Sistem otomatis menghitung skala nilai 0 - 100.</span>
        </div>

        <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-theme-xs">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Format Soal</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-bold font-mono text-blue-700">Pilihan Ganda</span>
            </div>
            <span class="text-[11px] text-gray-400 block mt-1">5 Opsi (A, B, C, D, E) khas CBT Ujian Madrasah.</span>
        </div>
    </div>

    <!-- Daftar Butir Soal -->
    <div class="space-y-4">
        @forelse($exam->questions as $q)
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-theme-xs space-y-4">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-gray-900 text-white font-mono font-bold flex items-center justify-center text-xs shrink-0">
                        {{ $q->nomor_urut }}
                    </span>
                    <div class="space-y-2">
                        <div class="text-sm font-semibold text-gray-900 leading-relaxed">
                            {!! nl2br(e($q->pertanyaan)) !!}
                        </div>
                        @if($q->gambar)
                        <div>
                            <img src="{{ $q->gambar }}" alt="Gambar Soal #{{ $q->nomor_urut }}" class="max-h-48 rounded-xl border border-gray-200 object-contain bg-gray-50 p-1">
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="button" 
                            @click="editMode = true; currentQuestion = {{ json_encode($q) }}; modalTambah = true" 
                            class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                        Edit
                    </button>
                    <form action="{{ route('admin.cbt.madrasah.soal.destroy', [$exam->id, $q->id]) }}" method="POST" onsubmit="return confirm('Hapus butir soal #{{ $q->nomor_urut }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Opsi Jawaban (A - E) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-3 border-t border-gray-100">
                @php
                    $kunci = strtoupper($q->kunci_jawaban);
                @endphp
                @foreach(['A' => $q->opsi_a, 'B' => $q->opsi_b, 'C' => $q->opsi_c, 'D' => $q->opsi_d, 'E' => $q->opsi_e] as $key => $val)
                    @if(!empty($val))
                    <div class="p-2.5 rounded-xl border text-xs flex items-center gap-2.5 {{ $key === $kunci ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-900' : 'bg-gray-50/50 border-gray-200 text-gray-700' }}">
                        <span class="w-6 h-6 rounded-lg {{ $key === $kunci ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-700' }} flex items-center justify-center font-mono font-bold text-xs shrink-0">
                            {{ $key }}
                        </span>
                        <span class="flex-1">{{ $val }}</span>
                        @if($key === $kunci)
                            <span class="text-[10px] text-emerald-700 uppercase font-bold px-1.5 py-0.5 rounded bg-white border border-emerald-200 shrink-0">Kunci Benar</span>
                        @endif
                    </div>
                    @endif
                @endforeach
            </div>

            <div class="flex items-center justify-between text-[11px] text-gray-400 pt-2 border-t border-gray-50">
                <span>Bobot Skor: <strong class="text-gray-700 font-mono">{{ $q->bobot ?: 1 }}</strong></span>
                @if($q->pembahasan)
                <span class="text-gray-500 italic">Pembahasan: {{ Str::limit($q->pembahasan, 60) }}</span>
                @endif
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-gray-400 text-xs bg-white rounded-2xl border border-gray-200">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p class="font-bold text-gray-600 mb-1">Belum Ada Soal</p>
            <p class="mb-4">Sesi ujian ini belum memiliki butir soal online. Silakan klik tombol di bawah untuk menambahkan soal.</p>
            <div class="flex items-center justify-center gap-2">
                <button type="button" @click="editMode = false; currentQuestion = {}; modalTambah = true" class="ta-btn-primary text-xs px-4 py-2">
                    + Tambah Soal Pertama
                </button>
                <button type="button" @click="modalImport = true" class="ta-btn-secondary text-xs px-4 py-2">
                    Import dari Excel
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Modal Tambah / Edit Soal -->
    <div x-show="modalTambah" style="display: none;" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto" @click.outside="modalTambah = false">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900" x-text="editMode ? 'Edit Butir Soal #' + (currentQuestion.nomor_urut || '') : 'Tambah Butir Soal Baru'"></h3>
                <button type="button" @click="modalTambah = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            </div>

            <form action="{{ route('admin.cbt.madrasah.soal.store', $exam->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="question_id" :value="currentQuestion.id || ''">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Nomor Urut</label>
                        <input type="number" name="nomor_urut" :value="currentQuestion.nomor_urut || '{{ ($exam->questions->max('nomor_urut') ?? 0) + 1 }}'" class="ta-input text-xs font-mono font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Bobot Soal</label>
                        <input type="number" step="0.01" name="bobot" :value="currentQuestion.bobot || 1.00" class="ta-input text-xs font-mono font-bold">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Teks Pertanyaan *</label>
                    <textarea name="pertanyaan" required rows="4" placeholder="Tuliskan pertanyaan soal di sini..." class="ta-input text-xs font-medium" x-text="currentQuestion.pertanyaan || ''"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Sisipkan Gambar (Opsional)</label>
                    <input type="file" name="gambar_file" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>

                <div class="space-y-2 pt-2 border-t border-gray-100">
                    <label class="block font-bold text-gray-800 uppercase tracking-wider text-[11px]">Pilihan Opsi Jawaban (A - E) *</label>
                    
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-gray-100 text-gray-700 font-mono font-bold flex items-center justify-center text-xs shrink-0">A</span>
                        <input type="text" name="opsi_a" required placeholder="Pilihan Jawaban A" :value="currentQuestion.opsi_a || ''" class="ta-input text-xs">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-gray-100 text-gray-700 font-mono font-bold flex items-center justify-center text-xs shrink-0">B</span>
                        <input type="text" name="opsi_b" required placeholder="Pilihan Jawaban B" :value="currentQuestion.opsi_b || ''" class="ta-input text-xs">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-gray-100 text-gray-700 font-mono font-bold flex items-center justify-center text-xs shrink-0">C</span>
                        <input type="text" name="opsi_c" required placeholder="Pilihan Jawaban C" :value="currentQuestion.opsi_c || ''" class="ta-input text-xs">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-gray-100 text-gray-700 font-mono font-bold flex items-center justify-center text-xs shrink-0">D</span>
                        <input type="text" name="opsi_d" required placeholder="Pilihan Jawaban D" :value="currentQuestion.opsi_d || ''" class="ta-input text-xs">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-gray-100 text-gray-700 font-mono font-bold flex items-center justify-center text-xs shrink-0">E</span>
                        <input type="text" name="opsi_e" placeholder="Pilihan Jawaban E (Opsional)" :value="currentQuestion.opsi_e || ''" class="ta-input text-xs">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-gray-100">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Kunci Jawaban Benar *</label>
                        <select name="kunci_jawaban" required class="ta-input text-xs font-bold font-mono">
                            <option value="A" :selected="currentQuestion.kunci_jawaban === 'A'">A</option>
                            <option value="B" :selected="currentQuestion.kunci_jawaban === 'B'">B</option>
                            <option value="C" :selected="currentQuestion.kunci_jawaban === 'C'">C</option>
                            <option value="D" :selected="currentQuestion.kunci_jawaban === 'D'">D</option>
                            <option value="E" :selected="currentQuestion.kunci_jawaban === 'E'">E</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Pembahasan / Catatan (Opsional)</label>
                        <input type="text" name="pembahasan" placeholder="Penjelasan jawaban..." :value="currentQuestion.pembahasan || ''" class="ta-input text-xs">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2">
                    <button type="button" @click="modalTambah = false" class="ta-btn-secondary text-xs">Batal</button>
                    <button type="submit" class="ta-btn-primary text-xs px-5 py-2 font-bold shadow-md">
                        Simpan Butir Soal &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Import Soal Excel -->
    <div x-show="modalImport" style="display: none;" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4" @click.outside="modalImport = false">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900">Import Soal dari File Excel</h3>
                <button type="button" @click="modalImport = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            </div>

            <form action="{{ route('admin.cbt.madrasah.soal.import', $exam->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-gray-700 mb-1.5">Pilih File Excel (.xlsx / .xls)</label>
                    <input type="file" name="excel_file" required accept=".xlsx,.xls" class="ta-input text-xs">
                    <span class="text-[11px] text-gray-400 mt-1 block">Pastikan format kolom sesuai dengan template standar.</span>
                </div>

                <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs flex items-center justify-between">
                    <div>
                        <span class="font-bold block">Belum punya template?</span>
                        <span class="text-[11px] text-blue-700">Unduh format template soal resmi.</span>
                    </div>
                    <a href="{{ route('admin.cbt.madrasah.soal.template', $exam->id) }}" class="px-2.5 py-1 bg-white hover:bg-blue-100 rounded-lg text-[11px] font-bold text-blue-800 border border-blue-200 shrink-0">
                        Unduh
                    </a>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                    <button type="button" @click="modalImport = false" class="ta-btn-secondary text-xs">Batal</button>
                    <button type="submit" class="ta-btn-primary text-xs px-4 py-2 font-bold shadow-md">
                        Mulai Import Soal &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

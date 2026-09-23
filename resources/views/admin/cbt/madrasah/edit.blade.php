@extends('admin.layout')

@section('title', 'Edit Sesi Ujian Madrasah')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Edit Sesi Ujian: {{ $exam->mata_pelajaran }}</h1>
            <p class="text-xs text-gray-500 mt-0.5">{{ $exam->nama_ujian }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.cbt.madrasah.show', $exam->id) }}" class="ta-btn-secondary text-xs">
                &larr; Ke Kelola Nilai
            </a>
            <form action="{{ route('admin.cbt.madrasah.destroy', $exam->id) }}" method="POST" onsubmit="return confirm('Hapus sesi ujian ini beserta seluruh data nilai yang ada? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg text-xs font-semibold transition">
                    Hapus Ujian
                </button>
            </form>
        </div>
    </div>

    @if($errors->any())
    <div class="ta-alert ta-alert-danger">
        <ul class="list-disc list-inside text-xs space-y-1">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-theme-xs">
        <form action="{{ route('admin.cbt.madrasah.update', $exam->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Jenjang & Sasaran -->
            <div>
                <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs">1</span>
                    Sasaran &amp; Tingkat Kelas
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Jenjang Madrasah *</label>
                        <select name="jenjang" class="ta-input text-xs font-bold">
                            <option value="MA" {{ $exam->jenjang === 'MA' ? 'selected' : '' }}>Madrasah Aliyah (MA)</option>
                            <option value="MTs" {{ $exam->jenjang === 'MTs' ? 'selected' : '' }}>Madrasah Tsanawiyah (MTs)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tingkat Kelas *</label>
                        <select name="tingkat_kelas" class="ta-input text-xs font-bold">
                            <option value="12" {{ $exam->tingkat_kelas == '12' ? 'selected' : '' }}>Kelas 12 (Tingkat Akhir MA)</option>
                            <option value="9" {{ $exam->tingkat_kelas == '9' ? 'selected' : '' }}>Kelas 9 (Tingkat Akhir MTs)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Jurusan / Program *</label>
                        <select name="jurusan" class="ta-input text-xs font-bold">
                            <option value="KEAGAMAAN" {{ $exam->jurusan === 'KEAGAMAAN' ? 'selected' : '' }}>KEAGAMAAN</option>
                            <option value="MIPA" {{ $exam->jurusan === 'MIPA' ? 'selected' : '' }}>MIPA / IPA</option>
                            <option value="IPS" {{ $exam->jurusan === 'IPS' ? 'selected' : '' }}>IPS</option>
                            <option value="UMUM" {{ $exam->jurusan === 'UMUM' ? 'selected' : '' }}>UMUM</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Mata Pelajaran & Ujian -->
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-purple-100 text-purple-800 flex items-center justify-center text-xs">2</span>
                    Identitas Mata Pelajaran &amp; Pengampu
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Mata Pelajaran *</label>
                        <input type="text" name="mata_pelajaran" required value="{{ old('mata_pelajaran', $exam->mata_pelajaran) }}" class="ta-input text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Guru Pengampu / Penguji</label>
                        <input type="text" name="nama_guru" value="{{ old('nama_guru', $exam->nama_guru) }}" class="ta-input text-xs uppercase font-medium">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Sesi Ujian (Tampil di Kop Laporan) *</label>
                        <input type="text" name="nama_ujian" required value="{{ old('nama_ujian', $exam->nama_ujian) }}" class="ta-input text-xs font-mono font-bold">
                    </div>
                </div>
            </div>

            <!-- Section 3: Waktu, Soal & KKM -->
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-sky-100 text-sky-800 flex items-center justify-center text-xs">3</span>
                    Parameter Evaluasi &amp; Waktu
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Jumlah Soal *</label>
                        <input type="number" name="jumlah_soal" required min="1" max="200" value="{{ old('jumlah_soal', $exam->jumlah_soal) }}" class="ta-input text-xs font-mono font-bold text-center">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Durasi (Menit) *</label>
                        <input type="number" name="durasi_menit" required min="10" max="300" value="{{ old('durasi_menit', $exam->durasi_menit) }}" class="ta-input text-xs font-mono font-bold text-center">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Standar KKM *</label>
                        <input type="number" step="0.01" name="kkm" required min="0" max="100" value="{{ old('kkm', $exam->kkm) }}" class="ta-input text-xs font-mono font-bold text-center">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Status Ujian *</label>
                        <select name="status" class="ta-input text-xs font-bold">
                            <option value="Aktif" {{ $exam->status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Selesai" {{ $exam->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Draft" {{ $exam->status === 'Draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tahun Ajaran *</label>
                        <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $exam->tahun_ajaran) }}" class="ta-input text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Semester *</label>
                        <select name="semester" class="ta-input text-xs font-semibold">
                            <option value="Genap" {{ $exam->semester === 'Genap' ? 'selected' : '' }}>Genap (Akhir Tahun)</option>
                            <option value="Ganjil" {{ $exam->semester === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Ujian (Opsional)</label>
                        <input type="date" name="tanggal_ujian" value="{{ old('tanggal_ujian', optional($exam->tanggal_ujian)->format('Y-m-d')) }}" class="ta-input text-xs">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.cbt.madrasah.show', $exam->id) }}" class="ta-btn-secondary text-xs">Batal</a>
                <button type="submit" class="ta-btn-primary px-6 py-2.5 text-xs font-bold shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('admin.layout')

@section('title', 'Buat Sesi Ujian Madrasah Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Buat Sesi Ujian Madrasah Baru</h1>
            <p class="text-xs text-gray-500 mt-0.5">Daftarkan ujian akhir madrasah untuk santri Kelas 12 (MA) atau Kelas 9 (MTs).</p>
        </div>
        <a href="{{ route('admin.cbt.madrasah.index') }}" class="ta-btn-secondary text-xs">
            &larr; Kembali
        </a>
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
        <form action="{{ route('admin.cbt.madrasah.store') }}" method="POST" id="formExam" class="space-y-6">
            @csrf

            <!-- Section 1: Jenjang & Sasaran -->
            <div>
                <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs">1</span>
                    Sasaran &amp; Tingkat Kelas
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Jenjang Madrasah *</label>
                        <select name="jenjang" id="selJenjang" onchange="syncTingkatFromJenjang()" class="ta-input text-xs font-bold">
                            <option value="MA" selected>Madrasah Aliyah (MA)</option>
                            <option value="MTs">Madrasah Tsanawiyah (MTs)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tingkat Kelas *</label>
                        <select name="tingkat_kelas" id="selTingkat" onchange="autoGenerateNamaUjian()" class="ta-input text-xs font-bold">
                            <option value="12" selected>Kelas 12 (Tingkat Akhir MA)</option>
                            <option value="9">Kelas 9 (Tingkat Akhir MTs)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Jurusan / Program *</label>
                        <select name="jurusan" id="selJurusan" onchange="autoGenerateNamaUjian()" class="ta-input text-xs font-bold">
                            <option value="KEAGAMAAN" selected>KEAGAMAAN</option>
                            <option value="MIPA">MIPA / IPA</option>
                            <option value="IPS">IPS</option>
                            <option value="UMUM">UMUM</option>
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
                        <input type="text" name="mata_pelajaran" id="inputMapel" required value="{{ old('mata_pelajaran', 'Al Quran Hadis') }}" placeholder="Contoh: Al Quran Hadis, Fiqih, Bahasa Arab" oninput="autoGenerateNamaUjian()" class="ta-input text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Guru Pengampu / Penguji</label>
                        <input type="text" name="nama_guru" value="{{ old('nama_guru', 'LATIF MAHMUD A') }}" placeholder="Contoh: LATIF MAHMUD A" class="ta-input text-xs uppercase font-medium">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Sesi Ujian (Tampil di Kop Laporan) *</label>
                        <input type="text" name="nama_ujian" id="inputNamaUjian" required value="{{ old('nama_ujian', '[12] - [KEAGAMAAN] - UJIAN MADRASAH MA HIDAYATULLAH') }}" placeholder="Contoh: [12] - [KEAGAMAAN] - UJIAN MADRASAH MA HIDAYATULLAH" class="ta-input text-xs font-mono font-bold">
                        <span class="text-[11px] text-gray-400 mt-1 block">Otomatis disinkronkan sesuai tingkat kelas, jurusan, dan nama madrasah.</span>
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
                        <input type="number" name="jumlah_soal" required min="1" max="200" value="{{ old('jumlah_soal', 50) }}" class="ta-input text-xs font-mono font-bold text-center">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Durasi (Menit) *</label>
                        <input type="number" name="durasi_menit" required min="10" max="300" value="{{ old('durasi_menit', 90) }}" class="ta-input text-xs font-mono font-bold text-center">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Standar KKM *</label>
                        <input type="number" step="0.01" name="kkm" required min="0" max="100" value="{{ old('kkm', 75.00) }}" class="ta-input text-xs font-mono font-bold text-center">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Status Ujian *</label>
                        <select name="status" class="ta-input text-xs font-bold">
                            <option value="Aktif" selected>Aktif</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tahun Ajaran *</label>
                        <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $tahunAjaran) }}" class="ta-input text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Semester *</label>
                        <select name="semester" class="ta-input text-xs font-semibold">
                            <option value="Genap" selected>Genap (Akhir Tahun)</option>
                            <option value="Ganjil">Ganjil</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Ujian (Opsional)</label>
                        <input type="date" name="tanggal_ujian" value="{{ old('tanggal_ujian', date('Y-m-d')) }}" class="ta-input text-xs">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.cbt.madrasah.index') }}" class="ta-btn-secondary text-xs">Batal</a>
                <button type="submit" class="ta-btn-primary px-6 py-2.5 text-xs font-bold shadow-md">
                    Simpan &amp; Lanjut Kelola Nilai &rarr;
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function syncTingkatFromJenjang() {
        const j = document.getElementById('selJenjang').value;
        const selTingkat = document.getElementById('selTingkat');
        if (j === 'MA') {
            selTingkat.value = '12';
        } else {
            selTingkat.value = '9';
        }
        autoGenerateNamaUjian();
    }

    function autoGenerateNamaUjian() {
        const tingkat = document.getElementById('selTingkat').value;
        const jurusan = document.getElementById('selJurusan').value;
        const jenjang = document.getElementById('selJenjang').value;
        const namaMadrasah = (jenjang === 'MA') ? 'MA HIDAYATULLAH' : 'MTS HIDAYATULLAH';
        
        const auto = `[${tingkat}] - [${jurusan}] - UJIAN MADRASAH ${namaMadrasah}`;
        document.getElementById('inputNamaUjian').value = auto;
    }
</script>
@endsection

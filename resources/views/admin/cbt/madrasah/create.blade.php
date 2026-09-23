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
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-bold text-gray-700">Jurusan / Program *</label>
                            <span class="text-[10px] text-emerald-600 font-semibold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Bisa Diedit Bebas</span>
                        </div>
                        <input type="text" name="jurusan" id="selJurusan" list="jurusanPresets" required value="{{ old('jurusan', 'KEAGAMAAN') }}" oninput="autoGenerateNamaUjian()" class="ta-input text-xs font-bold uppercase" placeholder="Ketik atau pilih jurusan...">
                        <datalist id="jurusanPresets">
                            <option value="KEAGAMAAN">KEAGAMAAN (Klasik)</option>
                            <option value="MIPA">MIPA / IPA</option>
                            <option value="IPS">IPS</option>
                            <option value="UMUM">UMUM</option>
                            <option value="FASE F - PEMINATAN IPA">FASE F - PEMINATAN IPA (Kurikulum Merdeka)</option>
                            <option value="FASE F - PEMINATAN IPS">FASE F - PEMINATAN IPS (Kurikulum Merdeka)</option>
                            <option value="FASE F - KEAGAMAAN">FASE F - KEAGAMAAN (Kurikulum Merdeka)</option>
                            <option value="BAHASA & BUDAYA">BAHASA & BUDAYA</option>
                        </datalist>
                        <span class="text-[11px] text-gray-400 mt-1 block">Sesuai Kurikulum Merdeka atau K13. Bebas ketik program studi kustom.</span>
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

            <!-- Section 4: Pengaturan CBT Online, Anti-Cheat & Kesempatan Mengerjakan -->
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-xs">4</span>
                    Aturan CBT Online, Anti-Cheat &amp; Kesempatan Pengerjaan
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Berapa Kali Mengerjakan (Max Attempts) *</label>
                        <select name="max_attempts" class="ta-input text-xs font-bold">
                            <option value="1" selected>1 Kali (Standar Ujian Resmi)</option>
                            <option value="2">2 Kali (Remedial / Perbaikan)</option>
                            <option value="3">3 Kali</option>
                            <option value="5">5 Kali</option>
                            <option value="10">10 Kali (Latihan Bebas)</option>
                        </select>
                        <span class="text-[11px] text-gray-400 mt-1 block">Batas kesempatan santri mengerjakan ujian ini.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Toleransi Pelanggaran Anti-Cheat *</label>
                        <div class="flex items-center gap-2">
                            <select name="max_violations" class="ta-input text-xs font-bold">
                                <option value="1">1 Kali (Sangat Ketat - 1x Pelanggaran Langsung Kunci)</option>
                                <option value="2">2 Kali Toleransi</option>
                                <option value="3" selected>3 Kali Toleransi (Standar CBT Indonesia)</option>
                                <option value="5">5 Kali Toleransi</option>
                                <option value="10">10 Kali Toleransi</option>
                            </select>
                        </div>
                        <span class="text-[11px] text-gray-400 mt-1 block">Deteksi otomatis: Pindah tab browser, keluar fullscreen, shortcut copy.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Token Ujian Masuk (Opsional)</label>
                        <div class="flex items-center gap-1.5">
                            <input type="text" name="token_ujian" id="inputToken" maxlength="10" placeholder="Contoh: WX8K2M" value="{{ old('token_ujian') }}" class="ta-input text-xs font-mono font-bold uppercase tracking-wider">
                            <button type="button" onclick="generateRandomToken()" class="px-2.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold whitespace-nowrap transition cursor-pointer" title="Acak Token">
                                🎲 Acak
                            </button>
                        </div>
                        <span class="text-[11px] text-gray-400 mt-1 block">Jika diisi, santri wajib input token sebelum mulai ujian.</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 bg-gray-50 p-3.5 rounded-xl border border-gray-200">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="acak_soal" value="1" checked class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                        <div>
                            <span class="text-xs font-bold text-gray-800 block">Acak Urutan Soal</span>
                            <span class="text-[11px] text-gray-500">Urutan soal antar santri berbeda.</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="acak_opsi" value="1" checked class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                        <div>
                            <span class="text-xs font-bold text-gray-800 block">Acak Pilihan Opsi (A-E)</span>
                            <span class="text-[11px] text-gray-500">Pilihan ganda diacak otomatis.</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="tampilkan_nilai" value="1" checked class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                        <div>
                            <span class="text-xs font-bold text-gray-800 block">Tampilkan Nilai ke Siswa</span>
                            <span class="text-[11px] text-gray-500">Nilai tampil saat siswa selesai.</span>
                        </div>
                    </label>
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
        const jurusan = document.getElementById('selJurusan').value || 'UMUM';
        const jenjang = document.getElementById('selJenjang').value;
        const namaMadrasah = (jenjang === 'MA') ? 'MA HIDAYATULLAH' : 'MTS HIDAYATULLAH';
        
        const auto = `[${tingkat}] - [${jurusan.toUpperCase()}] - UJIAN MADRASAH ${namaMadrasah}`;
        document.getElementById('inputNamaUjian').value = auto;
    }

    function generateRandomToken() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        let res = '';
        for (let i = 0; i < 6; i++) {
            res += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('inputToken').value = res;
    }
</script>
@endsection

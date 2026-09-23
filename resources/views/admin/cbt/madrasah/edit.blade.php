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
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-bold text-gray-700">Jurusan / Program *</label>
                            <span class="text-[10px] text-emerald-600 font-semibold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Bisa Diedit Bebas</span>
                        </div>
                        <input type="text" name="jurusan" id="selJurusan" list="jurusanPresets" required value="{{ old('jurusan', $exam->jurusan) }}" class="ta-input text-xs font-bold uppercase" placeholder="Ketik atau pilih jurusan...">
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
                        <span class="text-[11px] text-gray-400 mt-1 block">Bisa disesuaikan dengan Kurikulum Merdeka atau kebutuhan madrasah.</span>
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
                            <option value="1" {{ ($exam->max_attempts ?? 1) == 1 ? 'selected' : '' }}>1 Kali (Standar Ujian Resmi)</option>
                            <option value="2" {{ ($exam->max_attempts ?? 1) == 2 ? 'selected' : '' }}>2 Kali (Remedial / Perbaikan)</option>
                            <option value="3" {{ ($exam->max_attempts ?? 1) == 3 ? 'selected' : '' }}>3 Kali</option>
                            <option value="5" {{ ($exam->max_attempts ?? 1) == 5 ? 'selected' : '' }}>5 Kali</option>
                            <option value="10" {{ ($exam->max_attempts ?? 1) == 10 ? 'selected' : '' }}>10 Kali (Latihan Bebas)</option>
                        </select>
                        <span class="text-[11px] text-gray-400 mt-1 block">Batas kesempatan santri mengerjakan ujian ini.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Toleransi Pelanggaran Anti-Cheat *</label>
                        <div class="flex items-center gap-2">
                            <select name="max_violations" class="ta-input text-xs font-bold">
                                <option value="1" {{ ($exam->max_violations ?? 3) == 1 ? 'selected' : '' }}>1 Kali (Sangat Ketat - 1x Pelanggaran Langsung Kunci)</option>
                                <option value="2" {{ ($exam->max_violations ?? 3) == 2 ? 'selected' : '' }}>2 Kali Toleransi</option>
                                <option value="3" {{ ($exam->max_violations ?? 3) == 3 ? 'selected' : '' }}>3 Kali Toleransi (Standar CBT Indonesia)</option>
                                <option value="5" {{ ($exam->max_violations ?? 3) == 5 ? 'selected' : '' }}>5 Kali Toleransi</option>
                                <option value="10" {{ ($exam->max_violations ?? 3) == 10 ? 'selected' : '' }}>10 Kali Toleransi</option>
                            </select>
                        </div>
                        <span class="text-[11px] text-gray-400 mt-1 block">Deteksi otomatis: Pindah tab browser, keluar fullscreen, shortcut copy.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Token Ujian Masuk (Opsional)</label>
                        <div class="flex items-center gap-1.5">
                            <input type="text" name="token_ujian" id="inputToken" maxlength="10" placeholder="Contoh: WX8K2M" value="{{ old('token_ujian', $exam->token_ujian) }}" class="ta-input text-xs font-mono font-bold uppercase tracking-wider">
                            <button type="button" onclick="generateRandomToken()" class="px-2.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold whitespace-nowrap transition cursor-pointer" title="Acak Token">
                                🎲 Acak
                            </button>
                        </div>
                        <span class="text-[11px] text-gray-400 mt-1 block">Jika diisi, santri wajib input token sebelum mulai ujian.</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 bg-gray-50 p-3.5 rounded-xl border border-gray-200">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="acak_soal" value="1" {{ $exam->acak_soal ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                        <div>
                            <span class="text-xs font-bold text-gray-800 block">Acak Urutan Soal</span>
                            <span class="text-[11px] text-gray-500">Urutan soal antar santri berbeda.</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="acak_opsi" value="1" {{ $exam->acak_opsi ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                        <div>
                            <span class="text-xs font-bold text-gray-800 block">Acak Pilihan Opsi (A-E)</span>
                            <span class="text-[11px] text-gray-500">Pilihan ganda diacak otomatis.</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="tampilkan_nilai" value="1" {{ $exam->tampilkan_nilai ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                        <div>
                            <span class="text-xs font-bold text-gray-800 block">Tampilkan Nilai ke Siswa</span>
                            <span class="text-[11px] text-gray-500">Nilai tampil saat siswa selesai.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.cbt.madrasah.show', $exam->id) }}" class="ta-btn-secondary text-xs">Batal</a>
                <button type="submit" class="ta-btn-primary px-6 py-2.5 text-xs font-bold shadow-md">
                    Simpan Perubahan &rarr;
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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

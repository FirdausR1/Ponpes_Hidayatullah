@extends('admin.layout')

@section('title', 'Edit Data Lengkap Santri: ' . $student->nama_lengkap)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.siswa.index') }}" class="text-xs font-medium text-emerald-600 hover:underline">Manajemen Siswa</a>
                <span class="text-xs text-gray-400">/</span>
                <span class="text-xs font-medium text-gray-500">Edit Data Lengkap</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Perbarui Biodata Lengkap Santri</h1>
            <p class="text-xs text-gray-500 mt-0.5">Memperbarui data <strong>{{ $student->nama_lengkap }}</strong> (NIS: {{ $student->nis }} &bull; Kelas: {{ $student->kelas }}).</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.siswa.show', $student->id) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3.5 py-2 text-xs font-medium text-emerald-700 shadow-theme-xs hover:bg-emerald-100 transition">
                Lihat Profil Santri
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                &larr; Kembali
            </a>
        </div>
    </div>

    <!-- Form Body (TailAdmin DefaultInputs Style) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-theme-xs">
        <form method="POST" action="{{ route('admin.siswa.update', $student->id) }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Identitas Pokok & Akademik -->
            <div>
                <div class="flex items-center gap-2 pb-2.5 border-b border-gray-100 mb-4">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-xs font-bold text-emerald-700">1</span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800">Identitas Pokok &amp; Data Akademik</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                            Nama Lengkap Santri <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $student->nama_lengkap) }}" required class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                            Jenis Kelamin <span class="text-rose-500">*</span>
                        </label>
                        <select name="jenis_kelamin" required class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                            <option value="Laki-laki" {{ old('jenis_kelamin', $student->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki (Santriwan)</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $student->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>Perempuan (Santriwati)</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                            Nomor Induk Santri (NIS) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" required class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 font-mono font-bold focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                            Username Login Santri
                        </label>
                        <input type="text" name="username" value="{{ old('username', $student->username) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 font-mono focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                            Nomor Stambuk
                        </label>
                        <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}" placeholder="Nomor Stambuk / NISN santri" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 font-mono focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                            Jenjang Pendidikan <span class="text-rose-500">*</span>
                        </label>
                        <select name="jenjang" required class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                            <option value="MTs Mukim" {{ old('jenjang', $student->jenjang) === 'MTs Mukim' ? 'selected' : '' }}>MTs Mukim (Asrama)</option>
                            <option value="MTs Laju" {{ old('jenjang', $student->jenjang) === 'MTs Laju' ? 'selected' : '' }}>MTs Laju (Non-Asrama)</option>
                            <option value="MA Mukim" {{ old('jenjang', $student->jenjang) === 'MA Mukim' ? 'selected' : '' }}>MA Mukim (Asrama)</option>
                            <option value="MA Laju" {{ old('jenjang', $student->jenjang) === 'MA Laju' ? 'selected' : '' }}>MA Laju (Non-Asrama)</option>
                            <option value="Tahfidz Mukim" {{ old('jenjang', $student->jenjang) === 'Tahfidz Mukim' ? 'selected' : '' }}>Tahfidz Mukim (Pondok Qur'an - Asrama / Rp 250k)</option>
                            <option value="Tahfidz Laju" {{ old('jenjang', $student->jenjang) === 'Tahfidz Laju' ? 'selected' : '' }}>Tahfidz Laju (Pondok Qur'an - Non Asrama)</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                            Rombel / Kelas <span class="text-rose-500">*</span>
                        </label>
                        <select name="kelas" required class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                            @foreach($allClasses as $c)
                                <option value="{{ $c }}" {{ old('kelas', $student->kelas) === $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                            Tahun Masuk (Angkatan) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="tahun_masuk" value="{{ old('tahun_masuk', $student->tahun_masuk) }}" required class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Kamar Asrama</label>
                        <input type="text" name="kamar_asrama" value="{{ old('kamar_asrama', $student->kamar_asrama) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Status Keaktifan <span class="text-rose-500">*</span></label>
                        <select name="status" required class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                            <option value="Aktif" {{ old('status', $student->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Alumni" {{ old('status', $student->status) === 'Alumni' ? 'selected' : '' }}>Alumni</option>
                            <option value="Mutasi" {{ old('status', $student->status) === 'Mutasi' ? 'selected' : '' }}>Mutasi (Pindah)</option>
                            <option value="Cuti" {{ old('status', $student->status) === 'Cuti' ? 'selected' : '' }}>Cuti</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Pribadi Santri Lengkap -->
            <div>
                <div class="flex items-center gap-2 pb-2.5 border-b border-gray-100 mb-4">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-xs font-bold text-emerald-700">2</span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800">Data Pribadi Santri</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">NIK Santri (KTP/KIA)</label>
                        <input type="text" name="nik" value="{{ old('nik', $student->nik) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 font-mono focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Nomor Kartu Keluarga (KK)</label>
                        <input type="text" name="nomor_kk" value="{{ old('nomor_kk', $student->nomor_kk) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 font-mono focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Golongan Darah</label>
                        <select name="golongan_darah" class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                            <option value="">-- Pilih --</option>
                            <option value="A" {{ old('golongan_darah', $student->golongan_darah) === 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('golongan_darah', $student->golongan_darah) === 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ old('golongan_darah', $student->golongan_darah) === 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ old('golongan_darah', $student->golongan_darah) === 'O' ? 'selected' : '' }}>O</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $student->tempat_lahir) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $student->tanggal_lahir) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                    </div>
                    <div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Anak Ke-</label>
                                <input type="number" name="anak_ke" value="{{ old('anak_ke', $student->anak_ke) }}" min="1" class="h-11 w-full rounded-lg border border-gray-300 px-3 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Dari Jml Sdr</label>
                                <input type="number" name="jumlah_saudara" value="{{ old('jumlah_saudara', $student->jumlah_saudara) }}" min="1" class="h-11 w-full rounded-lg border border-gray-300 px-3 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Hobi / Minat Bakat</label>
                        <input type="text" name="hobi" value="{{ old('hobi', $student->hobi) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Riwayat Penyakit / Alergi</label>
                        <input type="text" name="riwayat_penyakit" value="{{ old('riwayat_penyakit', $student->riwayat_penyakit) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <!-- Section 3: Data Asal Sekolah -->
            <div>
                <div class="flex items-center gap-2 pb-2.5 border-b border-gray-100 mb-4">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-xs font-bold text-emerald-700">3</span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800">Riwayat Asal Sekolah Santri</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Nama Sekolah Asal</label>
                        <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $student->nama_sekolah) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Tahun Kelulusan</label>
                        <input type="text" name="tahun_lulus" value="{{ old('tahun_lulus', $student->tahun_lulus) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Alamat Sekolah Asal</label>
                        <input type="text" name="alamat_sekolah" value="{{ old('alamat_sekolah', $student->alamat_sekolah) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <!-- Section 4: Data Orang Tua Kandung -->
            <div>
                <div class="flex items-center gap-2 pb-2.5 border-b border-gray-100 mb-4">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-xs font-bold text-emerald-700">4</span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800">Data Orang Tua Kandung</h3>
                </div>
                
                <!-- Data Ayah -->
                <div class="p-4 rounded-xl bg-gray-50/75 border border-gray-100 mb-4 space-y-4">
                    <h4 class="text-xs font-bold text-gray-900 uppercase">Data Ayah Kandung</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Ayah</label>
                            <input type="text" name="ayah_nama" value="{{ old('ayah_nama', $student->ayah_nama) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">NIK Ayah</label>
                            <input type="text" name="ayah_nik" value="{{ old('ayah_nik', $student->ayah_nik) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 font-mono focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">No. HP / WhatsApp Ayah</label>
                            <input type="text" name="ayah_telepon" value="{{ old('ayah_telepon', $student->ayah_telepon) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 font-mono focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Pekerjaan Ayah</label>
                            <input type="text" name="ayah_pekerjaan" value="{{ old('ayah_pekerjaan', $student->ayah_pekerjaan) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Pendidikan Terakhir Ayah</label>
                            <input type="text" name="ayah_pendidikan" value="{{ old('ayah_pendidikan', $student->ayah_pendidikan) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Penghasilan Per Bulan</label>
                            <input type="text" name="ayah_penghasilan" value="{{ old('ayah_penghasilan', $student->ayah_penghasilan) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Data Ibu -->
                <div class="p-4 rounded-xl bg-gray-50/75 border border-gray-100 space-y-4">
                    <h4 class="text-xs font-bold text-gray-900 uppercase">Data Ibu Kandung</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Ibu</label>
                            <input type="text" name="ibu_nama" value="{{ old('ibu_nama', $student->ibu_nama) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">NIK Ibu</label>
                            <input type="text" name="ibu_nik" value="{{ old('ibu_nik', $student->ibu_nik) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 font-mono focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">No. HP / WhatsApp Ibu</label>
                            <input type="text" name="ibu_telepon" value="{{ old('ibu_telepon', $student->ibu_telepon) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 font-mono focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Pekerjaan Ibu</label>
                            <input type="text" name="ibu_pekerjaan" value="{{ old('ibu_pekerjaan', $student->ibu_pekerjaan) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Pendidikan Terakhir Ibu</label>
                            <input type="text" name="ibu_pendidikan" value="{{ old('ibu_pendidikan', $student->ibu_pendidikan) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Penghasilan Per Bulan</label>
                            <input type="text" name="ibu_penghasilan" value="{{ old('ibu_penghasilan', $student->ibu_penghasilan) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Data Wali Santri (Bila Ada) -->
            <div>
                <div class="flex items-center gap-2 pb-2.5 border-b border-gray-100 mb-4">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-xs font-bold text-emerald-700">5</span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800">Data Wali Santri</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Wali</label>
                        <input type="text" name="nama_wali" value="{{ old('nama_wali', $student->nama_wali) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Hubungan Keluarga</label>
                        <input type="text" name="wali_hubungan" value="{{ old('wali_hubungan', $student->wali_hubungan) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">No. HP / WhatsApp Wali</label>
                        <input type="text" name="wali_telepon" value="{{ old('wali_telepon', $student->wali_telepon) }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 font-mono focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <!-- Section 6: Alamat Lengkap, Foto & Ganti Password -->
            <div>
                <div class="flex items-center gap-2 pb-2.5 border-b border-gray-100 mb-4">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-xs font-bold text-emerald-700">6</span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800">Alamat, Foto &amp; Kata Sandi</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" rows="2" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">{{ old('alamat_lengkap', $student->alamat_lengkap ?: $student->alamat) }}</textarea>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Nomor WhatsApp / HP Santri</label>
                        <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $student->no_whatsapp) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 font-mono focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                            Ganti Pas Foto Santri
                        </label>
                        <input type="file" name="foto_file" accept="image/*" class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700">
                        @if($student->foto)
                            <p class="text-[11px] text-gray-500 mt-1">Foto saat ini tersimpan: <a href="{{ asset($student->foto) }}" target="_blank" class="text-emerald-600 underline">Lihat Foto</a></p>
                        @endif
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                            Ganti Kata Sandi Santri (Kosongkan jika tidak ingin mengubah)
                        </label>
                        <input type="password" name="password_baru" placeholder="Masukkan password baru (minimal 6 karakter)" class="h-11 w-full rounded-lg border border-gray-300 px-4 text-xs sm:text-sm text-gray-800 focus:border-emerald-500">
                        <p class="text-[11px] text-gray-400 mt-1">Password default santri adalah format tanggal lahir 8 digit (DDMMYYYY).</p>
                    </div>
                </div>
            </div>

            <!-- Section 7: Dokumen & Berkas Santri (KK, KTP, Akte) -->
            <div>
                <div class="flex items-center gap-2 pb-2.5 border-b border-gray-100 mb-4">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-xs font-bold text-emerald-700">7</span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800">Dokumen &amp; Berkas Santri (KK, KTP, Akte)</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Kartu Keluarga (KK) -->
                    <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 space-y-2.5 hover:border-indigo-200 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </span>
                                <div>
                                    <label class="block text-xs font-bold text-gray-800">Kartu Keluarga (KK)</label>
                                    <span class="text-[10px] text-gray-500">PDF / JPG / PNG (Maks 10MB)</span>
                                </div>
                            </div>
                        </div>

                        @if($student->berkas_kk)
                            <div class="flex items-center justify-between rounded-lg bg-indigo-50/80 border border-indigo-100 px-3 py-1.5 text-xs text-indigo-800">
                                <span class="inline-flex items-center gap-1 font-semibold text-[11px]">
                                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Berkas Tersimpan
                                </span>
                                <a href="{{ asset($student->berkas_kk) }}" target="_blank" class="font-bold underline text-indigo-700 hover:text-indigo-900 text-[11px]">
                                    Buka File &rarr;
                                </a>
                            </div>
                        @else
                            <div class="rounded-lg bg-gray-100 px-3 py-1.5 text-[11px] text-gray-500">
                                Belum ada berkas KK
                            </div>
                        @endif

                        <input type="file" name="file_kk" accept="image/*,application/pdf" class="w-full text-xs text-gray-700 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-200 bg-white rounded-lg p-1">
                        <p class="text-[10px] text-gray-400">Pilih file baru jika ingin mengganti/memperbarui.</p>
                    </div>

                    <!-- KTP Orang Tua / Wali -->
                    <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 space-y-2.5 hover:border-emerald-200 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                </span>
                                <div>
                                    <label class="block text-xs font-bold text-gray-800">KTP Orang Tua / Wali</label>
                                    <span class="text-[10px] text-gray-500">PDF / JPG / PNG (Maks 10MB)</span>
                                </div>
                            </div>
                        </div>

                        @if($student->berkas_ktp_ortu)
                            <div class="flex items-center justify-between rounded-lg bg-emerald-50/80 border border-emerald-100 px-3 py-1.5 text-xs text-emerald-800">
                                <span class="inline-flex items-center gap-1 font-semibold text-[11px]">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Berkas Tersimpan
                                </span>
                                <a href="{{ asset($student->berkas_ktp_ortu) }}" target="_blank" class="font-bold underline text-emerald-700 hover:text-emerald-900 text-[11px]">
                                    Buka File &rarr;
                                </a>
                            </div>
                        @else
                            <div class="rounded-lg bg-gray-100 px-3 py-1.5 text-[11px] text-gray-500">
                                Belum ada berkas KTP
                            </div>
                        @endif

                        <input type="file" name="file_ktp_ortu" accept="image/*,application/pdf" class="w-full text-xs text-gray-700 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-200 bg-white rounded-lg p-1">
                        <p class="text-[10px] text-gray-400">Pilih file baru jika ingin mengganti/memperbarui.</p>
                    </div>

                    <!-- Akta Kelahiran -->
                    <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 space-y-2.5 hover:border-amber-200 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </span>
                                <div>
                                    <label class="block text-xs font-bold text-gray-800">Akta Kelahiran Santri</label>
                                    <span class="text-[10px] text-gray-500">PDF / JPG / PNG (Maks 10MB)</span>
                                </div>
                            </div>
                        </div>

                        @if($student->berkas_akta)
                            <div class="flex items-center justify-between rounded-lg bg-amber-50/80 border border-amber-100 px-3 py-1.5 text-xs text-amber-800">
                                <span class="inline-flex items-center gap-1 font-semibold text-[11px]">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Berkas Tersimpan
                                </span>
                                <a href="{{ asset($student->berkas_akta) }}" target="_blank" class="font-bold underline text-amber-700 hover:text-amber-900 text-[11px]">
                                    Buka File &rarr;
                                </a>
                            </div>
                        @else
                            <div class="rounded-lg bg-gray-100 px-3 py-1.5 text-[11px] text-gray-500">
                                Belum ada berkas Akta
                            </div>
                        @endif

                        <input type="file" name="file_akta_kelahiran" accept="image/*,application/pdf" class="w-full text-xs text-gray-700 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 border border-gray-200 bg-white rounded-lg p-1">
                        <p class="text-[10px] text-gray-400">Pilih file baru jika ingin mengganti/memperbarui.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="pt-5 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-xs sm:text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-xs sm:text-sm font-medium text-white shadow-theme-xs hover:bg-emerald-700 transition">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M16.7071 5.29289C17.0976 5.68342 17.0976 6.31658 16.7071 6.70711L8.70711 14.7071C8.31658 15.0976 7.68342 15.0976 7.29289 14.7071L3.29289 10.7071C2.90237 10.3166 2.90237 9.68342 3.29289 9.29289C3.68342 8.90237 4.31658 8.90237 4.70711 9.29289L8 12.5858L15.2929 5.29289C15.6834 4.90237 16.3166 4.90237 16.7071 5.29289Z"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

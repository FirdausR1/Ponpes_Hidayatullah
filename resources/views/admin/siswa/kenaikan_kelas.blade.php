@extends('admin.layout')

@section('title', 'Kenaikan Kelas & Mutasi Santri')

@section('content')
<div class="space-y-6">

    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.siswa.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">&larr; Kembali ke Data Santri</a>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">Tahun Ajaran Baru</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kenaikan Kelas &amp; Mutasi Santri</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Promosikan seluruh santri pada satu kelas ke tingkatan kelas berikutnya secara serentak, atau ubah status ke Alumni.</p>
        </div>
        <div>
            <a href="{{ route('admin.siswa.index') }}" class="ta-btn-outline">
                Lihat Semua Santri
            </a>
        </div>
    </div>

    <!-- Petunjuk Dewan Asatidz / Admin -->
    <div class="ta-alert ta-alert-info">
        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <div class="text-xs leading-relaxed">
            <span class="font-bold text-gray-800">Petunjuk Promosi Kenaikan Kelas:</span>
            Pilih <strong>Kelas Asal</strong> terlebih dahulu. Sistem akan menampilkan seluruh santri yang saat ini berada di kelas tersebut. Anda dapat memilih <strong>Kelas Tujuan</strong> (misal dari Kelas VII-A naik ke Kelas VIII-A, atau Kelas X naik ke Kelas XI). 
            <br>
            <span class="font-semibold text-emerald-800">Bagi santri yang tinggal kelas:</span> Cukup <strong>hilangkan tanda centang</strong> pada baris santri tersebut. Santri yang tidak dicentang akan tetap berada di kelas asal.
        </div>
    </div>

    <!-- Filter Pilih Kelas Asal -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
        <form method="GET" action="{{ route('admin.siswa.kenaikanKelas') }}" class="flex flex-col sm:flex-row sm:items-end gap-3.5">
            <div class="flex-1 max-w-sm">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Pilih Kelas Asal yang Ingin Dipromosikan</label>
                <select name="kelas_asal" onchange="this.form.submit()" class="ta-input text-xs font-semibold cursor-pointer">
                    <option value="">-- Pilih Kelas Asal --</option>
                    @foreach($allClasses as $kls)
                        <option value="{{ $kls }}" {{ $kelasAsal == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="ta-btn-primary">
                Tampilkan Daftar Santri
            </button>
        </form>
    </div>

    @if(!empty($kelasAsal))
        @if($students->isNotEmpty())
        <!-- Form Kenaikan Kelas Massal -->
        <form action="{{ route('admin.siswa.kenaikanKelas.process') }}" method="POST">
            @csrf
            <input type="hidden" name="kelas_asal" value="{{ $kelasAsal }}">

            <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden space-y-6 p-5 sm:p-6">
                <!-- Panel Opsi Kenaikan / Kelulusan -->
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-2">Tindakan Kenaikan Kelas</label>
                        <div class="space-y-2 text-xs">
                            <label class="flex items-center gap-2 p-2.5 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-emerald-50/50">
                                <input type="radio" name="aksi_tujuan" value="naik_kelas" checked onchange="toggleAksiMode('naik')" class="text-emerald-600">
                                <span class="font-semibold text-gray-800">Naik ke Kelas Baru (Tingkat Berikutnya)</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-purple-50/50">
                                <input type="radio" name="aksi_tujuan" value="lulus_alumni" onchange="toggleAksiMode('alumni')" class="text-purple-600">
                                <span class="font-semibold text-gray-800">Tingkat Akhir: Lulus / Alihkan ke Alumni</span>
                            </label>
                        </div>
                    </div>

                    <!-- Target Kelas Baru -->
                    <div id="targetKelasWrap">
                        <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-1.5">Pilih Kelas Tujuan Baru</label>
                        <select name="kelas_tujuan" id="selectKelasTujuan" class="ta-input text-xs font-bold">
                            <option value="">-- Pilih Kelas Baru --</option>
                            @foreach($allClasses as $kls)
                                @if($kls !== $kelasAsal)
                                    <option value="{{ $kls }}">Kelas {{ $kls }}</option>
                                @endif
                            @endforeach
                        </select>
                        <span class="text-[11px] text-gray-400 mt-1 block">Misal santri kelas VII-A naik ke VIII-A, atau kelas X-A naik ke XI-A.</span>
                    </div>
                </div>

                <!-- Panel Opsi Tagihan Kenaikan & Infaq Pengembangan Pondok (Opsional) -->
                <div id="tagihanKenaikanWrap" class="p-4 bg-emerald-50/50 border border-emerald-200 rounded-xl space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-emerald-950 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Penerbitan Tagihan Kenaikan Kelas &amp; Infaq Pengembangan (Opsional)
                        </span>
                        <span class="text-[10px] text-emerald-700 bg-white px-2 py-0.5 rounded-full border border-emerald-200 font-semibold">Tercatat di Uang Masuk Kas</span>
                    </div>
                    <p class="text-xs text-gray-600">Centang opsi di bawah jika santri yang naik kelas ingin otomatis diterbitkan tagihan herregistrasi/pengembangan yang langsung muncul di Kasir POS &amp; Arus Kas Keuangan.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div class="p-3 bg-white border border-gray-200 rounded-lg space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="terbitkan_tagihan_kenaikan" value="1" onchange="document.getElementById('inputNomKenaikan').disabled = !this.checked; if(this.checked) document.getElementById('inputNomKenaikan').focus();" class="rounded text-emerald-600 focus:ring-emerald-500">
                                <span class="font-bold text-xs text-gray-800">Biaya Kenaikan Kelas</span>
                            </label>
                            <div>
                                <label class="text-[11px] text-gray-500 block mb-1">Nominal Biaya Kenaikan (Rp):</label>
                                <input type="number" name="nominal_tagihan_kenaikan" id="inputNomKenaikan" placeholder="Contoh: 150000" disabled class="w-full text-xs font-mono rounded-lg border border-gray-300 px-3 py-1.5 focus:border-emerald-500 outline-none disabled:bg-gray-100 disabled:text-gray-400">
                            </div>
                        </div>
                        <div class="p-3 bg-white border border-gray-200 rounded-lg space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="terbitkan_tagihan_pengembangan" value="1" onchange="document.getElementById('inputNomPengembangan').disabled = !this.checked; if(this.checked) document.getElementById('inputNomPengembangan').focus();" class="rounded text-emerald-600 focus:ring-emerald-500">
                                <span class="font-bold text-xs text-gray-800">Infaq Pengembangan Pondok</span>
                            </label>
                            <div>
                                <label class="text-[11px] text-gray-500 block mb-1">Nominal Infaq Pengembangan (Rp):</label>
                                <input type="number" name="nominal_tagihan_pengembangan" id="inputNomPengembangan" placeholder="Contoh: 500000" disabled class="w-full text-xs font-mono rounded-lg border border-gray-300 px-3 py-1.5 focus:border-emerald-500 outline-none disabled:bg-gray-100 disabled:text-gray-400">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Checklist Santri -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-gray-900">Daftar Santri Kelas {{ $kelasAsal }}</span>
                            <span class="badge-primary">{{ $students->count() }} Santri</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <button type="button" onclick="checkAll(true)" class="text-emerald-700 hover:underline font-semibold">Centang Semua</button>
                            <span class="text-gray-300">•</span>
                            <button type="button" onclick="checkAll(false)" class="text-gray-500 hover:underline font-medium">Batal Pilih</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-xl">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-600">
                                    <th class="p-3 w-10 text-center">
                                        <input type="checkbox" id="masterCheckbox" checked onchange="toggleMaster(this)" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                    </th>
                                    <th class="p-3 font-semibold uppercase">NIS / Username</th>
                                    <th class="p-3 font-semibold uppercase">Nama Lengkap Santri</th>
                                    <th class="p-3 font-semibold uppercase">Gender</th>
                                    <th class="p-3 font-semibold uppercase">Angkatan</th>
                                    <th class="p-3 font-semibold uppercase">Status Saat Ini</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($students as $st)
                                    <tr class="hover:bg-gray-50/70 transition">
                                        <td class="p-3 text-center">
                                            <input type="checkbox" name="student_ids[]" value="{{ $st->id }}" checked class="student-item-cb w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer" onchange="updateSelectedCount()">
                                        </td>
                                        <td class="p-3 font-mono font-bold text-gray-800">
                                            {{ $st->nis }}
                                            <span class="text-[10px] text-gray-400 font-normal block">{{ $st->username }}</span>
                                        </td>
                                        <td class="p-3 font-semibold text-gray-900">
                                            {{ $st->nama_lengkap }}
                                        </td>
                                        <td class="p-3 text-gray-600">
                                            {{ $st->jenis_kelamin }}
                                        </td>
                                        <td class="p-3 text-gray-600">
                                            Angkatan {{ $st->tahun_masuk }}
                                        </td>
                                        <td class="p-3">
                                            <span class="badge-success">Aktif di {{ $st->kelas }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer Submit Action -->
                <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <span class="text-xs text-gray-500">
                        Santri terpilih yang akan dipromosikan: <strong id="countSelected" class="text-emerald-700 font-bold">{{ $students->count() }}</strong> dari {{ $students->count() }} santri.
                    </span>
                    <button type="submit" class="ta-btn-primary px-6 py-3 font-bold text-sm shadow-md" onclick="return confirm('Apakah Anda yakin ingin memproses kenaikan kelas untuk santri yang telah dicentang?')">
                        Proses Kenaikan Kelas Sekarang &rarr;
                    </button>
                </div>
            </div>
        </form>
        @else
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center text-gray-400">
            <p class="text-sm font-semibold text-gray-700">Tidak ada santri aktif di kelas {{ $kelasAsal }}.</p>
            <p class="text-xs text-gray-400 mt-1">Silakan pilih kelas lain atau tambahkan santri ke kelas ini.</p>
        </div>
        @endif
    @endif

</div>

<script>
    function toggleAksiMode(mode) {
        const wrap = document.getElementById('targetKelasWrap');
        const billWrap = document.getElementById('tagihanKenaikanWrap');
        const sel = document.getElementById('selectKelasTujuan');
        if (mode === 'alumni') {
            wrap.classList.add('hidden');
            if (billWrap) billWrap.classList.add('hidden');
            sel.required = false;
        } else {
            wrap.classList.remove('hidden');
            if (billWrap) billWrap.classList.remove('hidden');
            sel.required = true;
        }
    }

    function toggleMaster(master) {
        document.querySelectorAll('.student-item-cb').forEach(cb => cb.checked = master.checked);
        updateSelectedCount();
    }

    function checkAll(val) {
        document.querySelectorAll('.student-item-cb').forEach(cb => cb.checked = val);
        const master = document.getElementById('masterCheckbox');
        if (master) master.checked = val;
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const count = document.querySelectorAll('.student-item-cb:checked').length;
        const countEl = document.getElementById('countSelected');
        if (countEl) countEl.innerText = count;
    }
</script>
@endsection

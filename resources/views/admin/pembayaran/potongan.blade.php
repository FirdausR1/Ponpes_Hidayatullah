@extends('admin.layout')

@section('title', 'Keringanan Biaya, SKTM & Beasiswa')

@section('content')
<div class="space-y-6" x-data="{ 
    modalTambah: false,
    tipeNilai: 'nominal',
    previewFotoUrl: '',
    modalPreview: false
}">

    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200">Keuangan &amp; Subsidi</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">Program Keringanan</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Keringanan Biaya, SKTM &amp; Beasiswa</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Atur subsidi santri kurang mampu berbasis Surat Keterangan Tidak Mampu (SKTM) desa dan beasiswa santri berprestasi / tahfidz.</p>
        </div>

        <!-- Action Buttons (TailAdmin Standard) -->
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-emerald-700 transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <span>Kasir Pembayaran</span>
            </a>
            <button type="button" @click="modalTambah = true" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-emerald-700 transition">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25012 3C5.25012 2.58579 5.58591 2.25 6.00012 2.25C6.41433 2.25 6.75012 2.58579 6.75012 3V5.25012L9.00034 5.25012C9.41455 5.25012 9.75034 5.58591 9.75034 6.00012C9.75034 6.41433 9.41455 6.75012 9.00034 6.75012H6.75012V9.00034C6.75012 9.41455 6.41433 9.75034 6.00012 9.75034C5.58591 9.75034 5.25012 9.41455 5.25012 9.00034L5.25012 6.75012H3C2.58579 6.75012 2.25 6.41433 2.25 6.00012C2.25 5.58591 2.58579 5.25012 3 5.25012H5.25012V3Z" fill="currentColor"/>
                </svg>
                <span>Tambah Keringanan / SKTM</span>
            </button>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-3.5 text-xs text-emerald-900 shadow-theme-xs flex items-center gap-2.5">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-lg border border-rose-200 bg-rose-50 p-3.5 text-xs text-rose-900 flex items-center gap-2.5 shadow-theme-xs">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Stats Cards (TailAdmin Standard) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Metric 1 -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block">Total Penerima</span>
                    <h4 class="mt-2 text-2xl font-bold text-gray-800">{{ $totalPenerima }} <span class="text-xs font-normal text-gray-500">Santri</span></h4>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gray-100 text-gray-600">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-gray-500">
                <span class="font-medium text-emerald-600">Aktif</span> menerima subsidi potongan
            </div>
        </div>

        <!-- Metric 2 -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-amber-600 uppercase tracking-wider block">Subsidi SKTM</span>
                    <h4 class="mt-2 text-2xl font-bold text-amber-700">{{ $totalSktm }} <span class="text-xs font-normal text-gray-500">Berkas</span></h4>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-amber-600">
                <span class="font-medium">Surat Tidak Mampu</span> desa/kelurahan
            </div>
        </div>

        <!-- Metric 3 -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-emerald-600 uppercase tracking-wider block">Beasiswa Prestasi</span>
                    <h4 class="mt-2 text-2xl font-bold text-emerald-700">{{ $totalPrestasi }} <span class="text-xs font-normal text-gray-500">Santri</span></h4>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-emerald-600">
                <span class="font-medium">Tahfidz &amp; Ranking</span> akademik
            </div>
        </div>
    </div>

    <!-- Tabel Data Keringanan (TailAdmin Format) -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="font-bold text-gray-900 text-sm sm:text-base">Daftar Keringanan Santri Terdaftar</h3>
                <p class="text-xs text-gray-500 mt-0.5">Potongan ini otomatis memotong saat Bendahara menerbitkan tagihan bulanan atau saat kasir menagih</p>
            </div>
            <div class="inline-flex items-center h-8 px-3 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-600 font-medium whitespace-nowrap">
                Total:&nbsp;<strong class="text-gray-900 font-bold">{{ $discounts->total() }}</strong>&nbsp;Data
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/70">
                        <th class="px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center w-12">No</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Santri &amp; Rombel</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis &amp; No. Surat</th>
                        <th class="px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Berkas SKTM</th>
                        <th class="px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Nilai Potongan</th>
                        <th class="px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pos Sasaran</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Masa Berlaku</th>
                        <th class="px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($discounts as $idx => $d)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-4 text-xs font-medium text-gray-400 text-center">
                                {{ $discounts->firstItem() + $idx }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-gray-900 text-xs sm:text-sm">{{ $d->student->nama_lengkap ?? '—' }}</div>
                                <div class="flex items-center gap-2 mt-0.5 text-xs text-gray-500">
                                    <span class="font-mono">NIS: {{ $d->student->nis ?? '—' }}</span>
                                    <span>•</span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Kelas {{ $d->student->kelas ?? '—' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ str_contains($d->jenis_potongan, 'Miskin') || str_contains($d->jenis_potongan, 'SKTM') ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                    {{ $d->jenis_potongan }}
                                </span>
                                @if($d->no_surat_miskin)
                                    <div class="text-xs font-mono text-gray-600 mt-1">No: {{ $d->no_surat_miskin }}</div>
                                @endif
                                @if($d->catatan)
                                    <div class="text-[11px] text-gray-400 italic mt-0.5">{{ $d->catatan }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($d->file_surat_miskin)
                                    @php
                                        $ext = pathinfo($d->file_surat_miskin, PATHINFO_EXTENSION);
                                    @endphp
                                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                                        <button type="button" @click="previewFotoUrl = '{{ asset($d->file_surat_miskin) }}'; modalPreview = true" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 font-semibold text-xs hover:bg-blue-100 transition">
                                            Lihat Bukti
                                        </button>
                                    @else
                                        <a href="{{ asset($d->file_surat_miskin) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 font-semibold text-xs hover:bg-rose-100 transition">
                                            Buka PDF
                                        </a>
                                    @endif
                                @else
                                    <span class="text-gray-300 italic text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-bold text-xs text-purple-800">
                                @if($d->tipe_nilai === 'persen')
                                    {{ rtrim(rtrim(number_format($d->nilai, 2, ',', '.'), '0'), ',') }} %
                                @else
                                    Rp {{ number_format($d->nilai, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $d->pos_biaya }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-600">
                                @if($d->berlaku_mulai || $d->berlaku_sampai)
                                    {{ $d->berlaku_mulai ? date('d/m/Y', strtotime($d->berlaku_mulai)) : '—' }} s/d {{ $d->berlaku_sampai ? date('d/m/Y', strtotime($d->berlaku_sampai)) : 'Seterusnya' }}
                                @else
                                    <span class="text-gray-400">Selama Menjadi Santri</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($d->status === 'Aktif')
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-600">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.pembayaran.potongan.destroy', $d->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data keringanan santri ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Keringanan" class="p-2 text-gray-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-12 text-gray-400 text-xs">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <p class="font-medium text-gray-500">Belum ada data keringanan santri atau SKTM.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $discounts->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH KERINGANAN (TailAdmin Modal Style) -->
    <div x-show="modalTambah" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalTambah = false" class="ta-modal max-w-lg">
            <div class="ta-modal-header">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Form Keringanan Santri / SKTM</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Penetapan subsidi miskin atau beasiswa pintar bendahara</p>
                </div>
                <button type="button" @click="modalTambah = false" class="text-gray-400 hover:text-gray-600 transition text-lg">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.pembayaran.potongan.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="ta-modal-body space-y-4 text-xs">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Pilih Santri Penerima *</label>
                        <select name="student_id" required class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-semibold text-gray-800 focus:border-brand-500 outline-none">
                            <option value="">-- Pilih Santri --</option>
                            @foreach($activeStudents as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_lengkap }} (NIS: {{ $s->nis }} - Kelas {{ $s->kelas }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Jenis Keringanan *</label>
                            <select name="jenis_potongan" required class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none">
                                <option value="Surat Keterangan Tidak Mampu (SKTM)">Surat Miskin (SKTM Desa/Kelurahan)</option>
                                <option value="Beasiswa Prestasi Akademik">Beasiswa Santri Pintar (Ranking)</option>
                                <option value="Beasiswa Tahfidz Al-Qur'an">Beasiswa Tahfidz Al-Qur'an</option>
                                <option value="Santri Yatim / Dhuafa">Santri Yatim / Dhuafa</option>
                                <option value="Kebijakan Khusus Pengasuh">Kebijakan Khusus Pengasuh</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Nomor Surat SKTM / Bukti</label>
                            <input type="text" name="no_surat_miskin" placeholder="Nomor surat (opsional)..." class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Upload Berkas Bukti (Surat Miskin / Sertifikat)</label>
                        <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                        <p class="text-[11px] text-gray-400 mt-1">Format PDF, JPG, atau PNG (Maks 5 MB).</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Bentuk Potongan *</label>
                            <select name="tipe_nilai" x-model="tipeNilai" required class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none">
                                <option value="nominal">Nominal Rupiah Tetap (Rp)</option>
                                <option value="persen">Persentase (%)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700 mb-1">
                                Besaran Potongan <span x-text="tipeNilai === 'nominal' ? '(Rp)' : '(%)'"></span> *
                            </label>
                            <input type="number" name="nilai" min="1" step="any" required placeholder="0" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-bold text-gray-900 focus:border-brand-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Target Pos Biaya yang Dipotong *</label>
                        <select name="pos_biaya" required class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-semibold text-gray-800 focus:border-brand-500 outline-none">
                            <option value="SEMUA">SEMUA POS BULANAN (Syahriyah, Makan, dll.)</option>
                            <option value="SYAHRIYAH">Khusus SYAHRIYAH (SPP Pondok)</option>
                            <option value="MAKAN">Khusus UANG MAKAN</option>
                            <option value="SOT">Khusus SOT</option>
                            <option value="TAB">Khusus TABUNGAN WAJIB</option>
                            @foreach($posBiayaList as $k => $l)
                                @if(!in_array($k, ['SEMUA', 'SYAHRIYAH', 'MAKAN', 'SOT', 'TAB']))
                                    <option value="{{ $k }}">{{ $k }}</option>
                                @endif
                            @endforeach
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1">Pilih SEMUA jika potongan berlaku untuk total tagihan rutin bulanan santri.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Berlaku Mulai</label>
                            <input type="date" name="berlaku_mulai" value="{{ date('Y-m-01') }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Berlaku Sampai</label>
                            <input type="date" name="berlaku_sampai" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none">
                            <p class="text-[10px] text-gray-400 mt-0.5">Kosongkan jika berlaku seterusnya.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Catatan / Alasan Dispensasi</label>
                        <input type="text" name="catatan" placeholder="Catatan keringanan (opsional)..." class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none">
                    </div>
                </div>

                <div class="ta-modal-footer">
                    <button type="button" @click="modalTambah = false" class="ta-btn-outline text-xs">
                        Batal
                    </button>
                    <button type="submit" class="ta-btn-primary text-xs bg-emerald-600 hover:bg-emerald-700">
                        Simpan Keringanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PREVIEW SURAT -->
    <div x-show="modalPreview" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalPreview = false" class="ta-modal max-w-2xl">
            <div class="ta-modal-header">
                <h3 class="text-base font-bold text-gray-900">Preview Berkas Surat Miskin / SKTM</h3>
                <button type="button" @click="modalPreview = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>
            <div class="ta-modal-body flex justify-center bg-gray-50">
                <img :src="previewFotoUrl" alt="Berkas Surat" class="max-h-[70vh] object-contain rounded-lg border border-gray-200">
            </div>
            <div class="ta-modal-footer">
                <button type="button" @click="modalPreview = false" class="ta-btn-secondary text-xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

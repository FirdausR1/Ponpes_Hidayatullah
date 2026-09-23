@extends('admin.layout')

@section('title', 'Master Tarif & Biaya Pendidikan')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <h1 class="text-xl font-bold text-gray-900">Master Tarif & Biaya Pendidikan</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1">Kelola struktur tarif biaya awal masuk (daftar ulang) dan iuran rutin bulanan (SPP) pesantren secara terpusat.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('biaya.index') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 text-xs font-semibold hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Lihat Tampilan Web (/biaya)
            </a>
            <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Ke Kasir Pembayaran
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi / Integrasi Sistem -->
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4 shadow-sm">
        <div class="flex items-start gap-3.5">
            <div class="p-2 bg-emerald-500 text-white rounded-xl shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-1 text-xs text-emerald-900 leading-relaxed">
                <p class="font-bold text-sm text-emerald-950 mb-1">Otomatis Terhubung ke Seluruh Sistem Pesantren</p>
                <p>Setiap perubahan tarif yang disimpan di halaman ini akan <strong>langsung aktif secara otomatis</strong> pada:</p>
                <ul class="list-disc list-inside mt-1.5 space-y-0.5 text-emerald-800 font-medium">
                    <li>Halaman publik rincian biaya resmi pesantren (<a href="{{ route('biaya.index') }}" target="_blank" class="underline font-bold hover:text-emerald-950">/biaya</a>) untuk calon wali santri.</li>
                    <li>Formulir PSB &amp; pratinjau kalkulasi rincian daftar ulang calon santri baru.</li>
                    <li>Pembuatan tagihan otomatis (<em>StudentBill</em>) ketika santri baru diterima &amp; diimpor ke santri aktif.</li>
                    <li>Pencatatan kasir bendahara, kwitansi resmi, dan penerbitan SPP bulanan.</li>
                </ul>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="rounded-xl border border-emerald-300 bg-emerald-100/80 px-4 py-3 text-xs text-emerald-900 font-semibold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950">&times;</button>
    </div>
    @endif

    <!-- Card 1: Tabel Biaya Awal Masuk (Daftar Ulang) -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <form action="{{ route('admin.pembayaran.tarif.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs">1</span>
                        <h2 class="font-bold text-gray-900 text-base">Tabel Biaya Awal Masuk (Daftar Ulang Santri Baru)</h2>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Biaya yang dibayarkan satu kali saat santri baru diterima (Uang Pangkal, Gedung, Fasilitas Asrama, dsb).</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="addBiayaAwalRow()" class="inline-flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 px-3.5 py-2 rounded-xl text-xs font-bold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        + Tambah Baris Komponen
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" id="tableBiayaAwal">
                    <thead>
                        <tr class="text-xs font-semibold uppercase tracking-wider text-gray-500 bg-gray-50 border-y border-gray-200">
                            <th class="py-3 px-3 min-w-[220px]">Nama Komponen Biaya</th>
                            <th class="py-3 px-3 w-36">MTs Mukim</th>
                            <th class="py-3 px-3 w-36">MTs Laju</th>
                            <th class="py-3 px-3 w-36">MA Mukim</th>
                            <th class="py-3 px-3 w-36">MA Laju</th>
                            <th class="py-3 px-3 text-center w-24">Total?</th>
                            <th class="py-3 px-3 text-center w-14">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="biayaAwalRowsContainer" class="divide-y divide-gray-100">
                        @foreach ($biayaAwal as $row)
                        <tr class="hover:bg-gray-50/80 transition {{ !empty($row['is_total']) ? 'bg-emerald-50/60 font-bold' : '' }}">
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_awal_komponen[]" value="{{ $row['komponen'] ?? '' }}" placeholder="Nama Komponen" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-medium text-gray-800 focus:bg-white focus:border-emerald-500 outline-none">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_awal_mts_mukim[]" value="{{ $row['mts_mukim'] ?? '' }}" placeholder="Rp 0" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_awal_mts_laju[]" value="{{ $row['mts_laju'] ?? '' }}" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_awal_ma_mukim[]" value="{{ $row['ma_mukim'] ?? '' }}" placeholder="Rp 0" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_awal_ma_laju[]" value="{{ $row['ma_laju'] ?? '' }}" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
                            </td>
                            <td class="py-2 px-3 text-center">
                                <input type="hidden" name="biaya_awal_is_total[]" value="{{ !empty($row['is_total']) ? '1' : '0' }}">
                                <input type="checkbox" {{ !empty($row['is_total']) ? 'checked' : '' }} onchange="this.previousElementSibling.value = this.checked ? '1' : '0'; this.closest('tr').classList.toggle('bg-emerald-50/60', this.checked); this.closest('tr').classList.toggle('font-bold', this.checked);" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 cursor-pointer" title="Tandai sebagai baris ringkasan total">
                            </td>
                            <td class="py-2 px-3 text-center">
                                <button type="button" onclick="removeBiayaRow(this)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <span class="text-xs text-gray-500 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Centang kolom <strong>"Total?"</strong> pada baris akumulasi ringkasan akhir. Gunakan tanda <code>—</code> untuk komponen non-asrama (Laju).
                </span>
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm hover:shadow transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Perubahan Biaya Awal
                </button>
            </div>
        </form>
    </div>

    <!-- Card 2: Tabel Biaya Bulanan (SPP & Makan) -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <form action="{{ route('admin.pembayaran.tarif.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-xs">2</span>
                        <h2 class="font-bold text-gray-900 text-base">Tabel Iuran Rutin Bulanan (SPP, Syahriyah & Makan)</h2>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Rincian SPP bulanan, biaya makan 3x sehari santri mukim, dan tabungan santri per bulan.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="addBiayaBulananRow()" class="inline-flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 px-3.5 py-2 rounded-xl text-xs font-bold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        + Tambah Baris Komponen
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" id="tableBiayaBulanan">
                    <thead>
                        <tr class="text-xs font-semibold uppercase tracking-wider text-gray-500 bg-gray-50 border-y border-gray-200">
                            <th class="py-3 px-3 min-w-[220px]">Komponen Iuran Bulanan</th>
                            <th class="py-3 px-3 w-36">MTs Mukim</th>
                            <th class="py-3 px-3 w-36">MTs Laju</th>
                            <th class="py-3 px-3 w-36">MA Mukim</th>
                            <th class="py-3 px-3 w-36">MA Laju</th>
                            <th class="py-3 px-3 text-center w-24">Total?</th>
                            <th class="py-3 px-3 text-center w-14">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="biayaBulananRowsContainer" class="divide-y divide-gray-100">
                        @foreach ($biayaBulanan as $row)
                        <tr class="hover:bg-gray-50/80 transition {{ !empty($row['is_total']) ? 'bg-emerald-50/60 font-bold' : '' }}">
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_bulanan_komponen[]" value="{{ $row['komponen'] ?? '' }}" placeholder="Nama Komponen" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-medium text-gray-800 focus:bg-white focus:border-emerald-500 outline-none">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_bulanan_mts_mukim[]" value="{{ $row['mts_mukim'] ?? '' }}" placeholder="Rp 0" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_bulanan_mts_laju[]" value="{{ $row['mts_laju'] ?? '' }}" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_bulanan_ma_mukim[]" value="{{ $row['ma_mukim'] ?? '' }}" placeholder="Rp 0" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" name="biaya_bulanan_ma_laju[]" value="{{ $row['ma_laju'] ?? '' }}" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
                            </td>
                            <td class="py-2 px-3 text-center">
                                <input type="hidden" name="biaya_bulanan_is_total[]" value="{{ !empty($row['is_total']) ? '1' : '0' }}">
                                <input type="checkbox" {{ !empty($row['is_total']) ? 'checked' : '' }} onchange="this.previousElementSibling.value = this.checked ? '1' : '0'; this.closest('tr').classList.toggle('bg-emerald-50/60', this.checked); this.closest('tr').classList.toggle('font-bold', this.checked);" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 cursor-pointer" title="Tandai sebagai baris ringkasan total">
                            </td>
                            <td class="py-2 px-3 text-center">
                                <button type="button" onclick="removeBiayaRow(this)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <span class="text-xs text-gray-500 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Nominal iuran bulanan ini akan menjadi acuan saat bendahara menerbitkan tagihan bulanan santri (SPP).
                </span>
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm hover:shadow transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Perubahan Biaya Bulanan
                </button>
            </div>
        </form>
    </div>

    <!-- Card 3: Pengaturan Widget Ringkasan Biaya di Halaman Depan (Landing Page) -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <form action="{{ route('admin.pembayaran.tarif.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-amber-50 text-amber-600 font-bold text-xs">3</span>
                        <h2 class="font-bold text-gray-900 text-base">Pratinjau Ringkasan Biaya di Halaman Depan (Landing Page)</h2>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Ubah judul, narasi singkat, dan 4 kartu highlight biaya yang tampil pada bagian depan website utama.</p>
                </div>
                <a href="{{ route('home') }}#biaya-section" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-xs font-semibold hover:bg-gray-100 transition">
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Lihat di Landing Page
                </a>
            </div>

            <!-- Teks Judul & Deskripsi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Judul Utama Bagian Biaya</label>
                    <input type="text" name="biaya_preview_title" value="{{ \App\Models\Setting::get('biaya_preview_title', 'Transparansi Biaya Pendidikan Santri Baru MTs & MA') }}" class="w-full rounded-lg border border-gray-200 bg-gray-50/50 px-3.5 py-2.5 text-xs font-bold text-gray-800 focus:bg-white focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Teks Tombol Tautan</label>
                    <input type="text" name="biaya_preview_btn_text" value="{{ \App\Models\Setting::get('biaya_preview_btn_text', 'Lihat Rincian Biaya Lengkap') }}" class="w-full rounded-lg border border-gray-200 bg-gray-50/50 px-3.5 py-2.5 text-xs font-semibold text-gray-800 focus:bg-white focus:border-emerald-500 outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Deskripsi / Penjelasan Singkat</label>
                    <textarea name="biaya_preview_desc" rows="2" class="w-full rounded-lg border border-gray-200 bg-gray-50/50 px-3.5 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none">{{ \App\Models\Setting::get('biaya_preview_desc', 'Seluruh rincian pembiayaan awal (uang pangkal, seragam, kasur/kamar santri mukim) serta syahriyah bulanan disajikan secara rinci, transparan, dan dapat diunduh pada halaman khusus biaya kami.') }}</textarea>
                </div>
            </div>

            <!-- 4 Kartu Highlight Biaya -->
            <div>
                <label class="block text-xs font-bold text-gray-800 mb-2.5">4 Kartu Sorotan / Highlight (Kotak Bawah):</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Kartu 1 -->
                    <div class="p-3.5 rounded-xl border border-gray-200 bg-gray-50/50 space-y-2">
                        <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Kartu 1</span>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Label Atas</label>
                            <input type="text" name="biaya_card1_label" value="{{ \App\Models\Setting::get('biaya_card1_label', 'Biaya Masuk Pertama') }}" class="w-full rounded border border-gray-200 px-2.5 py-1.5 text-xs bg-white text-gray-700 outline-none focus:border-emerald-500 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Nominal / Teks Utama</label>
                            <input type="text" name="biaya_card1_value" value="{{ \App\Models\Setting::get('biaya_card1_value', 'Terjangkau & Jelas') }}" class="w-full rounded border border-gray-200 px-2.5 py-1.5 text-xs bg-white text-emerald-800 outline-none focus:border-emerald-500 font-bold">
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Keterangan Bawah</label>
                            <input type="text" name="biaya_card1_sub" value="{{ \App\Models\Setting::get('biaya_card1_sub', 'Sudah termasuk fasilitas kamar') }}" class="w-full rounded border border-gray-200 px-2.5 py-1.5 text-xs bg-white text-gray-600 outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    <!-- Kartu 2 -->
                    <div class="p-3.5 rounded-xl border border-gray-200 bg-gray-50/50 space-y-2">
                        <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Kartu 2</span>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Label Atas</label>
                            <input type="text" name="biaya_card2_label" value="{{ \App\Models\Setting::get('biaya_card2_label', 'Syahriyah Bulanan') }}" class="w-full rounded border border-gray-200 px-2.5 py-1.5 text-xs bg-white text-gray-700 outline-none focus:border-emerald-500 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Nominal / Teks Utama</label>
                            <input type="text" name="biaya_card2_value" value="{{ \App\Models\Setting::get('biaya_card2_value', 'Mulai Rp 80.000/bln') }}" class="w-full rounded border border-gray-200 px-2.5 py-1.5 text-xs bg-white text-emerald-800 outline-none focus:border-emerald-500 font-bold">
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Keterangan Bawah</label>
                            <input type="text" name="biaya_card2_sub" value="{{ \App\Models\Setting::get('biaya_card2_sub', 'Untuk santri laju non-asrama') }}" class="w-full rounded border border-gray-200 px-2.5 py-1.5 text-xs bg-white text-gray-600 outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    <!-- Kartu 3 -->
                    <div class="p-3.5 rounded-xl border border-gray-200 bg-gray-50/50 space-y-2">
                        <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Kartu 3</span>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Label Atas</label>
                            <input type="text" name="biaya_card3_label" value="{{ \App\Models\Setting::get('biaya_card3_label', 'Makan Asrama 3x Sehari') }}" class="w-full rounded border border-gray-200 px-2.5 py-1.5 text-xs bg-white text-gray-700 outline-none focus:border-emerald-500 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Nominal / Teks Utama</label>
                            <input type="text" name="biaya_card3_value" value="{{ \App\Models\Setting::get('biaya_card3_value', 'Rp 300.000/bln') }}" class="w-full rounded border border-gray-200 px-2.5 py-1.5 text-xs bg-white text-emerald-800 outline-none focus:border-emerald-500 font-bold">
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Keterangan Bawah</label>
                            <input type="text" name="biaya_card3_sub" value="{{ \App\Models\Setting::get('biaya_card3_sub', 'Menu sehat bergizi & higienis') }}" class="w-full rounded border border-gray-200 px-2.5 py-1.5 text-xs bg-white text-gray-600 outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    <!-- Kartu 4 -->
                    <div class="p-3.5 rounded-xl border border-gray-200 bg-gray-50/50 space-y-2">
                        <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Kartu 4</span>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Label Atas</label>
                            <input type="text" name="biaya_card4_label" value="{{ \App\Models\Setting::get('biaya_card4_label', 'Bantuan / Beasiswa') }}" class="w-full rounded border border-gray-200 px-2.5 py-1.5 text-xs bg-white text-gray-700 outline-none focus:border-emerald-500 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Nominal / Teks Utama</label>
                            <input type="text" name="biaya_card4_value" value="{{ \App\Models\Setting::get('biaya_card4_value', 'Tersedia') }}" class="w-full rounded border border-gray-200 px-2.5 py-1.5 text-xs bg-white text-amber-600 outline-none focus:border-emerald-500 font-bold">
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Keterangan Bawah</label>
                            <input type="text" name="biaya_card4_sub" value="{{ \App\Models\Setting::get('biaya_card4_sub', 'Bagi dhuafa & santri berprestasi') }}" class="w-full rounded border border-gray-200 px-2.5 py-1.5 text-xs bg-white text-gray-600 outline-none focus:border-emerald-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <span class="text-xs text-gray-500 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Perubahan teks dan nominal kartu di atas akan seketika tampil di landing page depan setelah disimpan.
                </span>
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm hover:shadow transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Tampilan Biaya Landing Page
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Dynamic Row Biaya Awal
    function addBiayaAwalRow() {
        const tbody = document.getElementById('biayaAwalRowsContainer');
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50/80 transition';
        tr.innerHTML = `
            <td class="py-2 px-3">
                <input type="text" name="biaya_awal_komponen[]" placeholder="Nama Komponen" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-medium text-gray-800 focus:bg-white focus:border-emerald-500 outline-none">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_awal_mts_mukim[]" placeholder="Rp 0" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_awal_mts_laju[]" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_awal_ma_mukim[]" placeholder="Rp 0" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_awal_ma_laju[]" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
            </td>
            <td class="py-2 px-3 text-center">
                <input type="hidden" name="biaya_awal_is_total[]" value="0">
                <input type="checkbox" onchange="this.previousElementSibling.value = this.checked ? '1' : '0'; this.closest('tr').classList.toggle('bg-emerald-50/60', this.checked); this.closest('tr').classList.toggle('font-bold', this.checked);" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 cursor-pointer" title="Tandai sebagai baris ringkasan total">
            </td>
            <td class="py-2 px-3 text-center">
                <button type="button" onclick="removeBiayaRow(this)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    // Dynamic Row Biaya Bulanan
    function addBiayaBulananRow() {
        const tbody = document.getElementById('biayaBulananRowsContainer');
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50/80 transition';
        tr.innerHTML = `
            <td class="py-2 px-3">
                <input type="text" name="biaya_bulanan_komponen[]" placeholder="Nama Komponen" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-medium text-gray-800 focus:bg-white focus:border-emerald-500 outline-none">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_bulanan_mts_mukim[]" placeholder="Rp 0" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_bulanan_mts_laju[]" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_bulanan_ma_mukim[]" placeholder="Rp 0" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
            </td>
            <td class="py-2 px-3">
                <input type="text" name="biaya_bulanan_ma_laju[]" placeholder="Rp 0 atau —" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:border-emerald-500 outline-none font-mono">
            </td>
            <td class="py-2 px-3 text-center">
                <input type="hidden" name="biaya_bulanan_is_total[]" value="0">
                <input type="checkbox" onchange="this.previousElementSibling.value = this.checked ? '1' : '0'; this.closest('tr').classList.toggle('bg-emerald-50/60', this.checked); this.closest('tr').classList.toggle('font-bold', this.checked);" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 cursor-pointer" title="Tandai sebagai baris ringkasan total">
            </td>
            <td class="py-2 px-3 text-center">
                <button type="button" onclick="removeBiayaRow(this)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function removeBiayaRow(btn) {
        const row = btn.closest('tr');
        const tbody = row.closest('tbody');
        if (tbody.children.length > 1) {
            row.remove();
        } else {
            alert('Minimal harus ada satu baris rincian komponen biaya.');
        }
    }
</script>
@endsection

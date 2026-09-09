@extends('admin.layout')

@section('title', 'Data Pendaftar PSB')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Penerimaan Santri Baru (PSB)</h1>
            <p class="text-sm text-slate-500">Kelola dan seleksi calon santri baru Ponpes Hidayatullah Tuksongo TA 2025/2026.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Auto-Verifikasi Semua Foto Santri -->
            <form action="{{ route('admin.psb.autoVerify') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-3 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition cursor-pointer" title="Pindai dan verifikasi otomatis seluruh foto santri (aspek rasio 3x4 & background merah)">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    ⚡ Auto-Verifikasi Foto
                </button>
            </form>

            <!-- Cetak Masal Kartu CV Santri (Filter Terpilih) -->
            <a href="{{ route('admin.psb.print', request()->all()) }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-lg bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold shadow-sm transition" title="Cetak kartu CV santri dan berkas bukti sesuai filter aktif">
                <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                🖨️ Cetak Kartu CV Santri
            </a>

            <!-- Download Data Santri (Format Excel .xls Rapi) -->
            <a href="{{ route('admin.psb.export', request()->all()) }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition" title="Unduh rekapitulasi data pendaftar dalam format Excel (.xls) berformat tabel rapi">
                <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Export Excel (.xls)
            </a>

            <!-- Atur TTD Digital & Stempel PSB -->
            <a href="{{ route('admin.settings.index') }}" onclick="localStorage.setItem('admin_settings_active_tab', 'tab-ttd')" class="inline-flex items-center gap-2 px-3 py-2.5 rounded-lg bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold shadow-xs transition" title="Kelola file gambar tanda tangan pengurus dan stempel resmi pesantren">
                <svg class="icon-svg w-3.5 h-3.5 text-brand-600" viewBox="0 0 24 24"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path></svg>
                ✍️ Atur TTD Digital
            </a>

            <!-- Buka Formulir Publik -->
            <a href="{{ route('psb.register') }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2.5 rounded-lg bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold shadow-xs transition">
                <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                Formulir Pendaftaran
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-sm font-medium shadow-sm">
        <svg class="icon-svg w-5 h-5 text-emerald-600 flex-shrink-0" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Filter & Search Card -->
    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex flex-col lg:flex-row gap-3 items-center justify-between">
        <form action="{{ route('admin.psb.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
            <div class="relative flex-1 sm:w-56">
                <span class="text-slate-400 absolute left-3 top-2.5">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, No Reg, NISN, WA..." class="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-brand-500 outline-none transition">
            </div>

            <!-- Filter Tahun Daftar -->
            <select name="tahun" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 outline-none font-medium">
                <option value="">Semua Tahun Daftar</option>
                @foreach($years ?? [] as $yr)
                    <option value="{{ $yr }}" {{ request('tahun') == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                @endforeach
            </select>

            <!-- Filter Jenis Kelamin (Putra / Putri) -->
            <select name="jenis_kelamin" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 outline-none font-medium">
                <option value="">Semua Gender</option>
                <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki (Putra)</option>
                <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan (Putri)</option>
            </select>

            <!-- Filter Jenjang -->
            <select name="jenjang" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 outline-none font-medium">
                <option value="">Semua Jenjang</option>
                <option value="MTs Mukim" {{ request('jenjang') == 'MTs Mukim' ? 'selected' : '' }}>MTs Mukim</option>
                <option value="MTs Laju" {{ request('jenjang') == 'MTs Laju' ? 'selected' : '' }}>MTs Laju</option>
                <option value="MA Mukim" {{ request('jenjang') == 'MA Mukim' ? 'selected' : '' }}>MA Mukim</option>
                <option value="MA Laju" {{ request('jenjang') == 'MA Laju' ? 'selected' : '' }}>MA Laju</option>
            </select>

            <!-- Filter Status Seleksi -->
            <select name="status" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 outline-none font-medium">
                <option value="">Semua Status</option>
                <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>

            @if(request()->filled('q') || request()->filled('jenjang') || request()->filled('status') || request()->filled('jenis_kelamin') || request()->filled('tahun'))
                <a href="{{ route('admin.psb.index') }}" class="text-xs text-rose-600 hover:text-rose-800 font-semibold px-2 py-1 rounded hover:bg-rose-50 transition flex items-center gap-1" title="Reset Semua Filter">
                    ✕ Reset Filter
                </a>
            @endif
        </form>

        <div class="flex items-center gap-2.5 self-end lg:self-center">
            <!-- Direct Download Excel for Current Filter -->
            <a href="{{ route('admin.psb.export', request()->all()) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-lg text-xs font-bold transition shadow-xs" title="Unduh file Excel (.xls) rapi sesuai filter yang aktif">
                <svg class="icon-svg w-3.5 h-3.5 text-emerald-700" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                <span>Download Excel</span>
            </a>
            <span class="text-xs text-slate-500 whitespace-nowrap">
                Total: <strong>{{ $registrations->total() }}</strong> Santri
            </span>
        </div>
    </div>

    <!-- Applicants Table (TailAdmin Style) -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-xs tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Pas Foto & Identitas</th>
                        <th class="px-4 py-3.5">Jalur & Jenjang</th>
                        <th class="px-4 py-3.5">Upload Dokumen</th>
                        <th class="px-4 py-3.5">Wali & WhatsApp</th>
                        <th class="px-4 py-3.5">Status Seleksi</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($registrations as $reg)
                        <tr class="hover:bg-slate-50/70 transition">
                            <!-- Foto & Calon Santri -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="relative shrink-0">
                                        @if($reg->pas_foto)
                                            <div class="w-12 h-16 rounded-lg overflow-hidden border-2 {{ ($reg->foto_status ?? 'Sesuai') === 'Perlu Perbaikan' ? 'border-amber-500' : 'border-red-500' }} shadow-sm bg-red-600 cursor-pointer" onclick="showApplicantModal({{ json_encode($reg) }})">
                                                <img src="{{ $reg->pas_foto }}" alt="{{ $reg->nama_lengkap }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-12 h-16 rounded-lg border-2 border-dashed border-slate-300 flex flex-col items-center justify-center font-bold text-xs bg-slate-100 text-slate-400">
                                                <svg class="icon-svg w-5 h-5 text-slate-400" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                <span class="text-[9px] mt-0.5">3x4</span>
                                            </div>
                                        @endif

                                        @if(($reg->foto_status ?? 'Sesuai') === 'Perlu Perbaikan')
                                            <span class="absolute -bottom-2 -left-1 px-1.5 py-0.5 bg-amber-500 text-white rounded text-[9px] font-bold shadow-xs whitespace-nowrap" title="{{ $reg->foto_catatan ?: 'Foto miring / tidak ketentuan' }}">
                                                Miring / Ulang
                                            </span>
                                        @else
                                            <span class="absolute -bottom-2 -left-1 px-1.5 py-0.5 bg-emerald-600 text-white rounded text-[9px] font-bold shadow-xs whitespace-nowrap">
                                                Foto OK
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <button type="button" onclick="showApplicantModal({{ json_encode($reg) }})" class="font-bold text-slate-900 hover:text-brand-600 block text-left leading-tight transition">
                                            {{ $reg->nama_lengkap }}
                                        </button>
                                        <span class="text-[11px] font-mono text-brand-600 font-semibold block mt-0.5">{{ $reg->no_registrasi ?: ('ID #' . $reg->id) }}</span>
                                        <span class="text-xs text-slate-400 block">NISN: {{ $reg->nisn ?: '—' }} | NIK: {{ $reg->nik ?: '—' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Jalur & Jenjang -->
                            <td class="px-4 py-4">
                                <div class="space-y-1">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ 
                                        $reg->jalur === 'Prestasi' ? 'bg-amber-100 text-amber-800' : 
                                        ($reg->jalur === 'Tahfidz' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700') 
                                    }}">
                                        Jalur {{ $reg->jalur ?: 'Reguler' }}
                                    </span>
                                    <span class="text-xs font-semibold text-slate-700 block">
                                        {{ $reg->jenjang }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 block truncate max-w-[160px]" title="{{ $reg->nama_sekolah ?: $reg->asal_sekolah }}">
                                        {{ $reg->nama_sekolah ?: $reg->asal_sekolah ?: '—' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Upload Dokumen / Berkas -->
                            <td class="px-4 py-4">
                                <div class="flex flex-col gap-1.5">
                                    <!-- Bukti Transfer -->
                                    @if($reg->bukti_transfer)
                                        <a href="{{ $reg->bukti_transfer }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-md text-[11px] font-semibold transition w-fit">
                                            <svg class="icon-svg w-3 h-3 text-emerald-600" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                                            Transfer Rp 200rb
                                        </a>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Belum transfer</span>
                                    @endif

                                    <!-- Bukti Prestasi -->
                                    @if($reg->bukti_prestasi_tahfidz)
                                        <a href="{{ $reg->bukti_prestasi_tahfidz }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-md text-[11px] font-semibold transition w-fit">
                                            <svg class="icon-svg w-3 h-3 text-amber-600" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                                            Prestasi / Tahfidz
                                        </a>
                                    @endif

                                    <!-- Bukti Bansos -->
                                    @if($reg->bukti_bantuan_sosial)
                                        <a href="{{ $reg->bukti_bantuan_sosial }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-md text-[11px] font-semibold transition w-fit">
                                            <svg class="icon-svg w-3 h-3 text-blue-600" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line></svg>
                                            Berkas Bansos
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <!-- Wali & WA -->
                            <td class="px-4 py-4">
                                <span class="text-xs font-medium text-slate-800 block">
                                    {{ $reg->ayah_nama ?: $reg->nama_wali ?: 'Wali Santri' }}
                                </span>
                                @php
                                    $phone = $reg->ayah_telepon ?: $reg->no_whatsapp;
                                @endphp
                                @if($phone)
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                                        if(str_starts_with($cleanPhone, '0')) {
                                            $cleanPhone = '62' . substr($cleanPhone, 1);
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanPhone }}?text=Assalamu%27alaikum%2C+kami+dari+Panitia+PSB+Ponpes+Hidayatullah+Tuksongo+mengonfirmasi+pendaftaran+ananda+{{ urlencode($reg->nama_lengkap) }}+({{ $reg->no_registrasi }})." 
                                       target="_blank" 
                                       class="inline-flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-700 font-medium mt-0.5">
                                        <svg class="icon-svg w-3 h-3 text-emerald-500" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                        {{ $phone }}
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Status & Quick Switcher -->
                            <td class="px-4 py-4">
                                <form action="{{ route('admin.psb.updateStatus', $reg->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-semibold rounded-lg px-2.5 py-1 border transition cursor-pointer outline-none {{ 
                                        $reg->status == 'Diterima' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 
                                        ($reg->status == 'Ditolak' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200') 
                                    }}">
                                        <option value="Menunggu" {{ $reg->status == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                                        <option value="Diterima" {{ $reg->status == 'Diterima' ? 'selected' : '' }}>✓ Diterima</option>
                                        <option value="Ditolak" {{ $reg->status == 'Ditolak' ? 'selected' : '' }}>✕ Ditolak</option>
                                    </select>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Print CV & Documents Button -->
                                    <a href="{{ route('admin.psb.print', ['ids' => $reg->id]) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="🖨️ Cetak Kartu CV & Dokumen Santri">
                                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                    </a>

                                    <!-- Personal WhatsApp Notification Button -->
                                    @php
                                        $targetPhone = $reg->ayah_telepon ?: $reg->no_whatsapp;
                                        $cleanTargetPhone = preg_replace('/[^0-9]/', '', $targetPhone);
                                        if(str_starts_with($cleanTargetPhone, '0')) {
                                            $cleanTargetPhone = '62' . substr($cleanTargetPhone, 1);
                                        }
                                        $checkStatusUrl = route('psb.checkStatus', ['no_reg' => $reg->no_registrasi]);
                                        $waMsg = "Assalamu'alaikum Wr. Wb.\n\nBpk/Ibu Wali dari ananda *{$reg->nama_lengkap}*,\n\nKami dari Panitia PSB Pondok Pesantren Hidayatullah Tuksongo menginformasikan status verifikasi pendaftaran:\n- No. Registrasi: *{$reg->no_registrasi}*\n- Jenjang: *{$reg->jenjang}*\n- Status Seleksi: *{$reg->status}*\n- Status Pas Foto: *{$reg->foto_status}*" . ($reg->foto_catatan ? " ({$reg->foto_catatan})" : "") . "\n\nAnda dapat memantau progres atau memperbarui berkas mandiri melalui tautan berikut:\n{$checkStatusUrl}\n\nJazakumullah khairan katsiran.\nPanitia PSB Ponpes Hidayatullah Tuksongo";
                                    @endphp
                                    @if($cleanTargetPhone)
                                    <a href="https://wa.me/{{ $cleanTargetPhone }}?text={{ urlencode($waMsg) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Kirim Pesan WhatsApp Personal Status Pendaftaran">
                                        <svg class="icon-svg w-4 h-4 text-emerald-600" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    @endif

                                    <!-- View Details Modal Trigger -->
                                    <button type="button" onclick="showApplicantModal({{ json_encode($reg) }})" class="p-1.5 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition" title="Lihat Berkas & Detail Lengkap">
                                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>

                                    <!-- View/Print Receipt Page -->
                                    <a href="{{ route('psb.success', $reg->id) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Buka Tanda Terima Pendaftaran">
                                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                    </a>

                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.psb.destroy', $reg->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendaftar {{ $reg->nama_lengkap }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Pendaftar">
                                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-sm">
                                Belum ada calon santri yang mendaftar atau cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($registrations->hasPages())
            <div class="px-5 py-4 bg-slate-50 border-t border-slate-100">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>

</div>

<!-- COMPREHENSIVE APPLICANT DETAIL & VERIFICATION MODAL -->
<div id="applicantModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full overflow-hidden border border-slate-200">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div>
                <span class="text-[11px] font-mono text-brand-600 font-bold uppercase tracking-wider" id="modalNoReg">PSB-25-0000</span>
                <h3 class="font-bold text-slate-800 text-base" id="modalNama">Detail Calon Santri</h3>
            </div>
            <button onclick="closeApplicantModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="icon-svg w-5 h-5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <!-- Modal Content (Scrollable) -->
        <div class="p-6 space-y-6 max-h-[78vh] overflow-y-auto text-xs text-slate-700">
            
            <!-- Foto 3x4 & Manajemen Kesesuaian Foto -->
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 pb-2.5">
                    <span class="font-bold text-slate-800 uppercase tracking-wider text-xs flex items-center gap-2">
                        <svg class="icon-svg w-4 h-4 text-brand-600" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        Verifikasi Pas Foto Santri (Ketentuan: Baju Putih, Peci Hitam, Latar Merah, 3x4 Tegak)
                    </span>
                    <span id="modalFotoBadge" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold"></span>
                </div>

                <div class="flex flex-col sm:flex-row gap-5 items-start">
                    <!-- Preview Foto 3x4 -->
                    <div id="modalFotoWrapper" class="w-28 h-36 bg-red-600 rounded-xl overflow-hidden border-2 border-red-500 shadow-md shrink-0 relative group">
                        <img id="modalFotoImg" src="" alt="Pas Foto Santri" class="w-full h-full object-cover">
                        <a id="modalFotoEnlargeLink" href="" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-semibold transition">
                            Perbesar Foto
                        </a>
                    </div>

                    <!-- Panel Evaluasi & Aksi Foto -->
                    <div class="flex-1 space-y-3">
                        <div class="p-3 bg-white rounded-lg border border-slate-200">
                            <span class="font-semibold text-slate-700 block mb-1">Status Kepatuhan Ketentuan Foto:</span>
                            <form id="formUpdateFotoStatus" action="" method="POST" class="flex flex-wrap items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="foto_status" id="modalInputFotoStatus" class="text-xs font-bold rounded-lg border border-slate-200 px-3 py-1.5 outline-none bg-slate-50">
                                    <option value="Sesuai">✓ Foto Sesuai Ketentuan</option>
                                    <option value="Perlu Perbaikan">⚠ Perlu Perbaikan (Miring / Bukan Background Merah)</option>
                                </select>
                                <input type="text" name="foto_catatan" id="modalInputFotoCatatan" placeholder="Catatan perbaikan (misal: foto miring, belum pakai peci)" class="text-xs rounded-lg border border-slate-200 px-3 py-1.5 flex-1 min-w-[200px] outline-none">
                                <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-bold transition">
                                    Simpan Status
                                </button>
                            </form>
                        </div>

                        <!-- Hubungi Wali via WhatsApp jika Foto Bermasalah -->
                        <div class="flex flex-wrap items-center gap-2">
                            <a id="modalBtnWaFoto" href="#" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-xs">
                                <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                Hubungi Wali Santri via WA (Minta Kirim Ulang Foto)
                            </a>

                            <!-- Upload Pengganti Langsung oleh Admin -->
                            <form id="formAdminUploadFoto" action="" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                                @csrf
                                <label class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-lg text-xs font-semibold transition">
                                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                    <span>Ganti Foto Santri</span>
                                    <input type="file" name="pas_foto" accept="image/*" class="hidden" onchange="this.form.submit()">
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identitas Inti Calon Santri -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div>
                    <span class="text-slate-400 block font-semibold">JALUR & JENJANG</span>
                    <span class="font-bold text-slate-800 text-sm" id="modalJalurJenjang">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">STATUS SELEKSI</span>
                    <span class="font-semibold text-emerald-700 text-sm" id="modalStatus">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">NISN & NIK</span>
                    <span class="font-mono text-slate-800" id="modalNisnNik">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">NO. KARTU KELUARGA (KK)</span>
                    <span class="font-mono text-slate-800" id="modalKk">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">TEMPAT, TGL LAHIR</span>
                    <span class="text-slate-800" id="modalTtl">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">ANAK KE / SAUDARA</span>
                    <span class="text-slate-800" id="modalAnakSaudara">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">HOBI SANTRI</span>
                    <span class="text-slate-800" id="modalHobi">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">PROGRAM BANSOS</span>
                    <span class="text-slate-800 font-semibold" id="modalBansos">-</span>
                </div>
            </div>

            <!-- Alamat Lengkap -->
            <div>
                <span class="text-slate-400 block font-semibold mb-1">ALAMAT LENGKAP SANTRI</span>
                <p class="p-3 bg-slate-50 rounded-lg border border-slate-200 font-medium text-slate-800" id="modalAlamat">-</p>
            </div>

            <!-- Sekolah Asal -->
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <span class="font-bold text-slate-900 uppercase tracking-wider block">Sekolah Asal</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <span class="text-slate-400 block">Nama Sekolah:</span>
                        <strong class="text-slate-800" id="modalSekolahNama">-</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Tahun Lulus:</span>
                        <span class="text-slate-800" id="modalSekolahLulus">-</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Alamat Sekolah:</span>
                        <span class="text-slate-800" id="modalSekolahAlamat">-</span>
                    </div>
                </div>
            </div>

            <!-- Data Orang Tua (Ayah & Ibu) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Ayah -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                    <span class="font-bold text-slate-800 block border-b border-slate-200 pb-1">Data Ayah Kandung</span>
                    <p><strong>Nama:</strong> <span id="modalAyahNama">-</span></p>
                    <p><strong>NIK / Th Lahir:</strong> <span id="modalAyahNik">-</span></p>
                    <p><strong>No HP/WA:</strong> <span id="modalAyahTelp">-</span></p>
                    <p><strong>Pendidikan:</strong> <span id="modalAyahPendidikan">-</span></p>
                    <p><strong>Pekerjaan:</strong> <span id="modalAyahPekerjaan">-</span></p>
                    <p><strong>Penghasilan:</strong> <span id="modalAyahPenghasilan">-</span></p>
                </div>
                <!-- Ibu -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                    <span class="font-bold text-slate-800 block border-b border-slate-200 pb-1">Data Ibu Kandung</span>
                    <p><strong>Nama:</strong> <span id="modalIbuNama">-</span></p>
                    <p><strong>NIK / Th Lahir:</strong> <span id="modalIbuNik">-</span></p>
                    <p><strong>No HP/WA:</strong> <span id="modalIbuTelp">-</span></p>
                    <p><strong>Pendidikan:</strong> <span id="modalIbuPendidikan">-</span></p>
                    <p><strong>Pekerjaan:</strong> <span id="modalIbuPekerjaan">-</span></p>
                    <p><strong>Penghasilan:</strong> <span id="modalIbuPenghasilan">-</span></p>
                </div>
            </div>

            <!-- HASIL UPLOAD DOKUMEN & BERKAS (VISUAL LIGHTBOX / THUMBNAILS) -->
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3">
                <span class="font-bold text-slate-900 uppercase tracking-wider block flex items-center gap-2">
                    <svg class="icon-svg w-4 h-4 text-brand-600" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    Hasil Upload Dokumen & Berkas Santri
                </span>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="modalDokumenCards">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <span class="text-xs text-slate-400">Verifikasi berkas fisik dan ijazah asli dilakukan saat kedatangan santri di kampus.</span>
            <div class="flex items-center gap-2">
                <a id="modalBtnPrint" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    <span>Cetak CV & Berkas Santri Ini</span>
                </a>
                <button onclick="closeApplicantModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg transition">
                    Tutup Jendela
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showApplicantModal(reg) {
    document.getElementById('modalNoReg').textContent = reg.no_registrasi || ('ID #' + reg.id);
    document.getElementById('modalNama').textContent = reg.nama_lengkap;
    document.getElementById('modalJalurJenjang').textContent = (reg.jalur || 'Reguler') + ' • ' + reg.jenjang;
    document.getElementById('modalStatus').textContent = reg.status || 'Menunggu';
    document.getElementById('modalNisnNik').textContent = (reg.nisn || '—') + ' / ' + (reg.nik || '—');
    document.getElementById('modalKk').textContent = reg.nomor_kk || '—';
    document.getElementById('modalTtl').textContent = (reg.tempat_lahir || '') + (reg.tanggal_lahir ? ', ' + reg.tanggal_lahir : '—');
    document.getElementById('modalAnakSaudara').textContent = 'Anak ke-' + (reg.anak_ke || '—') + ' dari ' + (reg.jumlah_saudara || '0') + ' saudara';
    document.getElementById('modalAlamat').textContent = reg.alamat_lengkap || reg.alamat || 'Tidak ada alamat lengkap.';
    document.getElementById('modalHobi').textContent = reg.hobi || '—';
    document.getElementById('modalBansos').textContent = reg.bantuan_sosial || 'Tidak Memiliki';

    // Sekolah
    document.getElementById('modalSekolahNama').textContent = reg.nama_sekolah || reg.asal_sekolah || '—';
    document.getElementById('modalSekolahLulus').textContent = reg.tahun_lulus || '—';
    document.getElementById('modalSekolahAlamat').textContent = reg.alamat_sekolah || '—';

    // Ayah
    document.getElementById('modalAyahNama').textContent = reg.ayah_nama || reg.nama_wali || '—';
    document.getElementById('modalAyahNik').textContent = (reg.ayah_nik || '—') + ' (' + (reg.ayah_tahun_lahir || '—') + ')';
    document.getElementById('modalAyahTelp').textContent = reg.ayah_telepon || reg.no_whatsapp || '—';
    document.getElementById('modalAyahPendidikan').textContent = reg.ayah_pendidikan || '—';
    document.getElementById('modalAyahPekerjaan').textContent = reg.ayah_pekerjaan || '—';
    document.getElementById('modalAyahPenghasilan').textContent = reg.ayah_penghasilan || '—';

    // Ibu
    document.getElementById('modalIbuNama').textContent = reg.ibu_nama || '—';
    document.getElementById('modalIbuNik').textContent = (reg.ibu_nik || '—') + ' (' + (reg.ibu_tahun_lahir || '—') + ')';
    document.getElementById('modalIbuTelp').textContent = reg.ibu_telepon || '—';
    document.getElementById('modalIbuPendidikan').textContent = reg.ibu_pendidikan || '—';
    document.getElementById('modalIbuPekerjaan').textContent = reg.ibu_pekerjaan || '—';
    document.getElementById('modalIbuPenghasilan').textContent = reg.ibu_penghasilan || '—';

    // FOTO EVALUATION & ACTIONS
    const fotoWrap = document.getElementById('modalFotoWrapper');
    const fotoImg = document.getElementById('modalFotoImg');
    const fotoLink = document.getElementById('modalFotoEnlargeLink');
    const fotoBadge = document.getElementById('modalFotoBadge');
    const fotoStatus = reg.foto_status || 'Sesuai';

    if (reg.pas_foto) {
        fotoImg.src = reg.pas_foto;
        fotoLink.href = reg.pas_foto;
        fotoWrap.classList.remove('hidden');
    } else {
        fotoImg.src = '/logo.png';
        fotoLink.href = '#';
    }

    if (fotoStatus === 'Perlu Perbaikan') {
        fotoBadge.textContent = 'Perlu Perbaikan Foto';
        fotoBadge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-300';
    } else {
        fotoBadge.textContent = 'Foto Sesuai Ketentuan';
        fotoBadge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300';
    }

    // Set Form Action URLs & Print Link
    document.getElementById('formUpdateFotoStatus').action = '/admin/psb/' + reg.id + '/foto-status';
    document.getElementById('formAdminUploadFoto').action = '/admin/psb/' + reg.id + '/upload-foto';
    document.getElementById('modalBtnPrint').href = '/admin/psb/print?ids=' + reg.id;
    document.getElementById('modalInputFotoStatus').value = fotoStatus;
    document.getElementById('modalInputFotoCatatan').value = reg.foto_catatan || '';

    // WhatsApp Direct Button for Photo Correction
    const waPhone = reg.ayah_telepon || reg.no_whatsapp || '';
    let cleanWa = waPhone.replace(/[^0-9]/g, '');
    if (cleanWa.startsWith('0')) cleanWa = '62' + cleanWa.substring(1);

    const waMsg = encodeURIComponent(
        `Assalamu'alaikum Wr. Wb. Bapak/Ibu Wali dari ananda ${reg.nama_lengkap} (${reg.no_registrasi || 'Calon Santri'}).\n\n` +
        `Kami dari Panitia PSB Pondok Pesantren Hidayatullah Tuksongo menginformasikan perihal Pas Foto 3x4 yang diunggah saat pendaftaran.\n\n` +
        `Mohon berkenan untuk mengirimkan ulang file Pas Foto yang sesuai ketentuan:\n` +
        `1. Memakai kemeja/baju putih rapi\n` +
        `2. Memakai peci hitam (putra) / jilbab putih (putri)\n` +
        `3. Latar belakang (background) berwarna MERAH\n` +
        `4. Posisi foto tegak lurus (tidak miring/selfie) ukuran 3x4.\n\n` +
        `Catatan Panitia: ${reg.foto_catatan || 'Foto perlu disesuaikan dengan ketentuan di atas'}.\n\n` +
        `Terima kasih. Jazakumullahu khairan.`
    );
    document.getElementById('modalBtnWaFoto').href = cleanWa ? `https://wa.me/${cleanWa}?text=${waMsg}` : '#';

    // DOKUMEN CARDS (BUKTI TRANSFER, PRESTASI, BANSOS)
    const docContainer = document.getElementById('modalDokumenCards');
    docContainer.innerHTML = '';

    // 1. Bukti Transfer Rp 200rb
    if (reg.bukti_transfer) {
        docContainer.innerHTML += `
            <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-xs flex flex-col justify-between space-y-2">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block mb-1">Bukti Transfer Rp 200rb</span>
                    <a href="${reg.bukti_transfer}" target="_blank" class="block aspect-video rounded-md overflow-hidden bg-slate-100 border border-slate-200 relative group">
                        <img src="${reg.bukti_transfer}" alt="Bukti Transfer" class="w-full h-full object-cover">
                        <span class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white font-semibold transition">Buka Dokumen</span>
                    </a>
                </div>
                <a href="${reg.bukti_transfer}" target="_blank" class="w-full text-center px-2 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded text-[11px] font-semibold transition">
                    Lihat Bukti Transfer
                </a>
            </div>
        `;
    } else {
        docContainer.innerHTML += `
            <div class="bg-white p-3 rounded-lg border border-dashed border-slate-200 text-center flex flex-col items-center justify-center min-h-[120px]">
                <span class="text-slate-400 text-xs">Belum ada bukti transfer</span>
            </div>
        `;
    }

    // 2. Bukti Prestasi / Tahfidz
    if (reg.bukti_prestasi_tahfidz) {
        docContainer.innerHTML += `
            <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-xs flex flex-col justify-between space-y-2">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 block mb-1">Sertifikat Prestasi / Tahfidz</span>
                    <a href="${reg.bukti_prestasi_tahfidz}" target="_blank" class="block aspect-video rounded-md overflow-hidden bg-slate-100 border border-slate-200 relative group">
                        <img src="${reg.bukti_prestasi_tahfidz}" alt="Bukti Prestasi" class="w-full h-full object-cover">
                        <span class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white font-semibold transition">Buka Dokumen</span>
                    </a>
                </div>
                <a href="${reg.bukti_prestasi_tahfidz}" target="_blank" class="w-full text-center px-2 py-1 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded text-[11px] font-semibold transition">
                    Lihat Sertifikat
                </a>
            </div>
        `;
    } else {
        docContainer.innerHTML += `
            <div class="bg-white p-3 rounded-lg border border-dashed border-slate-200 text-center flex flex-col items-center justify-center min-h-[120px]">
                <span class="text-slate-400 text-xs">Tidak ada bukti prestasi</span>
            </div>
        `;
    }

    // 3. Bukti Bansos
    if (reg.bukti_bantuan_sosial) {
        docContainer.innerHTML += `
            <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-xs flex flex-col justify-between space-y-2">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 block mb-1">Kartu Bantuan Sosial</span>
                    <a href="${reg.bukti_bantuan_sosial}" target="_blank" class="block aspect-video rounded-md overflow-hidden bg-slate-100 border border-slate-200 relative group">
                        <img src="${reg.bukti_bantuan_sosial}" alt="Bukti Bansos" class="w-full h-full object-cover">
                        <span class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white font-semibold transition">Buka Dokumen</span>
                    </a>
                </div>
                <a href="${reg.bukti_bantuan_sosial}" target="_blank" class="w-full text-center px-2 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded text-[11px] font-semibold transition">
                    Lihat Kartu Bansos
                </a>
            </div>
        `;
    } else {
        docContainer.innerHTML += `
            <div class="bg-white p-3 rounded-lg border border-dashed border-slate-200 text-center flex flex-col items-center justify-center min-h-[120px]">
                <span class="text-slate-400 text-xs">Bukan penerima bansos</span>
            </div>
        `;
    }

    document.getElementById('applicantModal').classList.remove('hidden');
}

function closeApplicantModal() {
    document.getElementById('applicantModal').classList.add('hidden');
}
</script>
@endsection

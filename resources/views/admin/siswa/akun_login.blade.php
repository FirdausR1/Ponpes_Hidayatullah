@extends('admin.layout')

@section('title', 'Kelola Akun & Akses Login Santri')

@section('content')
<div class="space-y-6" x-data="{ 
    modalUbahPass: false,
    selectedId: '',
    selectedName: '',
    selectedNis: '',
    selectedBirthdatePass: ''
}">

    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.siswa.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">&larr; Kembali ke Data Santri</a>
                <span class="text-xs text-gray-400">&bull;</span>
                <span class="text-xs font-medium text-gray-500">Portal Akses Santri</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kelola Akun &amp; Akses Login Santri</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Daftar akun login, username kustom, ubah kata sandi santri bebas, dan reset ke format tanggal lahir.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('santri.login') }}" target="_blank" class="ta-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                <span>Buka Portal Santri</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
    <div class="rounded-xl border border-emerald-200 bg-emerald-50/90 p-4 text-xs sm:text-sm text-emerald-800 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif
    @if($errors->any())
    <div class="rounded-xl border border-rose-200 bg-rose-50/90 p-4 text-xs sm:text-sm text-rose-800">
        <p class="font-bold mb-1">Gagal menyimpan kata sandi:</p>
        <ul class="list-disc list-inside space-y-0.5 text-xs">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Informasi Akses Login Santri -->
    <div class="ta-alert ta-alert-info">
        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <div class="text-xs leading-relaxed space-y-1">
            <p><span class="font-bold text-gray-800">Petunjuk Login Santri:</span> Santri dapat mengakses portal login di alamat <code>{{ url('/santri/login') }}</code>.</p>
            <p><strong>Username Santri:</strong> Bebas, bisa menggunakan <strong>NIS</strong> (Nomor Induk Santri), <strong>Username Kustom</strong>, atau <strong>Nomor Stambuk</strong>.</p>
            <p><strong>Kata Sandi:</strong> Secara default otomatis dari <strong>tanggal lahir</strong> format <code>DDMMYYYY</code> (contoh: lahir 15 Mei 2010 maka passwordnya <code>15052010</code>). Admin kini dapat <strong>mengubah kata sandi santri kapan saja</strong> atau meresetnya kembali.</p>
        </div>
    </div>

    <!-- Filter & Pencarian Akun -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
        <form method="GET" action="{{ route('admin.siswa.akunLogin') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama santri, NIS, username..." class="ta-input text-xs">
            </div>
            <div class="w-full sm:w-48">
                <select name="kelas" onchange="this.form.submit()" class="ta-input text-xs">
                    <option value="">Semua Kelas</option>
                    @foreach($allClasses as $kls)
                        <option value="{{ $kls }}" {{ request('kelas') == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="ta-btn-primary">
                Cari Akun
            </button>
            @if(request()->anyFilled(['q', 'kelas']))
                <a href="{{ route('admin.siswa.akunLogin') }}" class="ta-btn-outline">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Akun Login Santri -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 font-bold uppercase text-[11px]">
                        <th class="p-4">Nama Lengkap Santri</th>
                        <th class="p-4">Username Login</th>
                        <th class="p-4">NIS &amp; No. Stambuk</th>
                        <th class="p-4">Kelas</th>
                        <th class="p-4">Format Tgl Lahir</th>
                        <th class="p-4">Terakhir Login</th>
                        <th class="p-4 text-right">Aksi Password</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $st)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="p-4">
                                <span class="font-bold text-gray-900 block text-sm">{{ $st->nama_lengkap }}</span>
                                <span class="text-gray-400 text-[11px]">{{ $st->jenis_kelamin }} &bull; Angkatan {{ $st->tahun_masuk }}</span>
                            </td>
                            <td class="p-4 font-mono font-bold text-emerald-800">
                                {{ $st->username ?: $st->nis }}
                            </td>
                            <td class="p-4 font-mono text-gray-700">
                                <div>NIS: <strong>{{ $st->nis }}</strong></div>
                                <div class="text-gray-400 text-[11px]">No. Stambuk: {{ $st->nisn ?: '-' }}</div>
                            </td>
                            <td class="p-4">
                                <span class="badge-primary">Kelas {{ $st->kelas }}</span>
                            </td>
                            <td class="p-4 font-mono">
                                <span class="px-2 py-1 rounded bg-gray-100 text-gray-800 border border-gray-200 font-bold">
                                    {{ $st->getFormattedBirthdatePassword() }}
                                </span>
                                @if($st->tanggal_lahir)
                                    <span class="text-[10px] text-gray-400 block mt-1">({{ $st->tanggal_lahir }})</span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-500">
                                @if($st->last_login_at)
                                    <span class="text-emerald-700 font-medium block">{{ $st->last_login_at->format('d M Y, H:i') }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $st->last_login_at->diffForHumans() }}</span>
                                @else
                                    <span class="text-gray-400 italic">Belum pernah login</span>
                                @endif
                            </td>
                            <td class="p-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Tombol Ubah Password Bebas -->
                                    <button type="button" 
                                            @click="selectedId = '{{ $st->id }}'; selectedName = '{{ addslashes($st->nama_lengkap) }}'; selectedNis = '{{ $st->nis }}'; selectedBirthdatePass = '{{ $st->getFormattedBirthdatePassword() }}'; modalUbahPass = true"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 text-xs font-semibold shadow-theme-xs transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Ubah Password
                                    </button>

                                    <!-- Tombol Cepat Reset ke Tanggal Lahir -->
                                    <form action="{{ route('admin.siswa.resetPassword', $st->id) }}" method="POST" class="inline" onsubmit="return confirm('Reset kata sandi santri {{ $st->nama_lengkap }} ke format tanggal lahir ({{ $st->getFormattedBirthdatePassword() }})?')">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-100 text-gray-600 text-xs font-semibold transition" title="Kembalikan ke password tanggal lahir">
                                            Reset Tgl Lahir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-400">
                                Tidak ada data akun santri ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $students->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL UBAH KATA SANDI SANTRI OLEH ADMIN -->
    <div x-show="modalUbahPass" x-cloak class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div @click.away="modalUbahPass = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-gray-100">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Ubah Kata Sandi Santri</h3>
                        <p class="text-xs text-gray-500" x-text="selectedName + ' (NIS: ' + selectedNis + ')'"></p>
                    </div>
                </div>
                <button @click="modalUbahPass = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <!-- Form Ubah Password Custom -->
            <form :action="'{{ url('/admin/siswa/akun-login') }}/' + selectedId + '/update-password'" method="POST" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kata Sandi Baru <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span></label>
                    <input type="password" name="password_confirmation" required minlength="6" placeholder="Ulangi kata sandi baru" class="h-10 w-full rounded-xl border border-gray-300 px-3 text-xs text-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10">
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                    <!-- Tombol Reset ke Tanggal Lahir -->
                    <button type="submit" name="mode" value="reset_birthdate" class="text-xs font-semibold text-emerald-700 hover:underline flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Set Tanggal Lahir (<span x-text="selectedBirthdatePass"></span>)
                    </button>

                    <div class="flex items-center gap-2">
                        <button type="button" @click="modalUbahPass = false" class="px-3.5 py-2 rounded-xl border border-gray-300 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                            Simpan Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

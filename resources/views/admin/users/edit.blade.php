@extends('admin.layout')

@section('title', 'Edit Pengguna: ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-emerald-600 hover:underline">Manajemen Pengguna</a>
                <span class="text-xs text-gray-400">/</span>
                <span class="text-xs font-medium text-gray-500">Edit Akun</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Perbarui Data Pengguna</h1>
            <p class="text-xs text-gray-500 mt-0.5">Memperbarui akun <strong>{{ $user->name }}</strong> ({{ $user->email }}).</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
            &larr; Kembali
        </a>
    </div>

    <!-- Form Body (TailAdmin DefaultInputs Style) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-theme-xs">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs sm:text-sm text-gray-800 shadow-theme-xs focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition">
            </div>

            <div>
                <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                    Alamat Email <span class="text-rose-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs sm:text-sm text-gray-800 shadow-theme-xs font-mono focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition">
            </div>

            @if(auth()->user()->isSuperAdmin())
                <div>
                    <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                        Peran Akses (Role) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="role" required class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 pr-10 text-xs sm:text-sm text-gray-800 shadow-theme-xs focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition">
                            <option value="bendahara" {{ old('role', $user->role) === 'bendahara' ? 'selected' : '' }}>Bendahara (Hanya Kelola Data Santri &amp; Manajemen Pembayaran / Keuangan)</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Kelola Santri, PSB, CBT, &amp; Berita)</option>
                            <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>Superadmin (Akses Penuh Seluruh Menu &amp; Kelola Pengguna)</option>
                        </select>
                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </span>
                    </div>
                </div>
            @endif

            <div>
                <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">
                    Jabatan / Posisi Petugas
                </label>
                <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" placeholder="Contoh: Bendahara Pesantren / Kasir Keuangan" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs sm:text-sm text-gray-800 shadow-theme-xs focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition">
                <span class="text-[11px] text-gray-400 mt-1 block">Jabatan ini tercetak pada kwitansi pembayaran di bawah nama petugas.</span>
            </div>

            <div class="pt-5 border-t border-gray-100">
                <span class="block text-xs font-bold text-gray-800 mb-1">Ubah Kata Sandi (Opsional)</span>
                <p class="text-xs text-gray-400 mb-3">Kosongkan kolom sandi jika Anda tidak bermaksud mengubah kata sandi akun ini.</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Kata Sandi Baru</label>
                        <input type="password" name="password" minlength="6" placeholder="Minimal 6 karakter" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs sm:text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs sm:text-sm font-medium text-gray-700">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" minlength="6" placeholder="Ulangi kata sandi baru" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs sm:text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-500 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition">
                    </div>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-xs sm:text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-xs sm:text-sm font-medium text-white shadow-theme-xs hover:bg-emerald-700 transition">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16.7071 5.29289C17.0976 5.68342 17.0976 6.31658 16.7071 6.70711L8.70711 14.7071C8.31658 15.0976 7.68342 15.0976 7.29289 14.7071L3.29289 10.7071C2.90237 10.3166 2.90237 9.68342 3.29289 9.29289C3.68342 8.90237 4.31658 8.90237 4.70711 9.29289L8 12.5858L15.2929 5.29289C15.6834 4.90237 16.3166 4.90237 16.7071 5.29289Z" fill="currentColor"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

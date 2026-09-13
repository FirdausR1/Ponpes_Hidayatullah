@extends('admin.layout')

@section('title', 'Data Pendaftar PSB')

@section('content')
<div class="space-y-6">

    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 border border-emerald-200">Portal Penerimaan</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Penerimaan Santri Baru (PSB)</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Kelola data pendaftaran, hasil ujian CBT, dan seleksi calon santri baru.</p>
        </div>

        <!-- Action Bar (TailAdmin Button Group) -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- 1. Tombol Buka Modal Seleksi Otomatis -->
            <button type="button" onclick="document.getElementById('modalAutoSeleksi').classList.remove('hidden')" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-2.5 text-xs font-semibold shadow-theme-xs transition cursor-pointer" title="Jalankan proses seleksi otomatis berdasarkan kriteria nilai CBT / pembayaran / kuota">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.7071 5.29289C17.0976 5.68342 17.0976 6.31658 16.7071 6.70711L8.70711 14.7071C8.31658 15.0976 7.68342 15.0976 7.29289 14.7071L3.29289 10.7071C2.90237 10.3166 2.90237 9.68342 3.29289 9.29289C3.68342 8.90237 4.31658 8.90237 4.70711 9.29289L8 12.5858L15.2929 5.29289C15.6834 4.90237 16.3166 4.90237 16.7071 5.29289Z" fill="currentColor"/>
                </svg>
                <span>Seleksi Otomatis</span>
            </button>

            <!-- 2. Cetak Masal Kartu CV Santri (Filter Terpilih) -->
            <a href="{{ route('admin.psb.print', request()->all()) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-3.5 py-2.5 text-xs font-medium shadow-theme-xs transition" title="Cetak kartu CV santri dan berkas bukti sesuai filter aktif">
                <svg class="w-4 h-4 text-gray-500 fill-current" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6 2C4.89543 2 4 2.89543 4 4V8H20V4C20 2.89543 19.1046 2 18 2H6ZM18 14V19C18 20.1046 17.1046 21 16 21H8C6.89543 21 6 20.1046 6 19V14H18ZM20 10H4C2.89543 10 2 10.8954 2 12V16C2 17.1046 2.89543 18 4 18H4.5V14C4.5 12.8954 5.39543 12 6.5 12H17.5C18.6046 12 19.5 12.8954 19.5 14V18H20C21.1046 18 22 17.1046 22 16V12C22 10.8954 21.1046 10 20 10Z" fill="currentColor"/>
                </svg>
                <span>Cetak Masal CV</span>
            </a>

            <!-- 3. TailAdmin Dropdown: Menu Opsi Lainnya -->
            <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-3.5 py-2.5 text-xs font-medium shadow-theme-xs transition cursor-pointer">
                    <span>Menu Lainnya</span>
                    <svg class="w-4 h-4 text-gray-400 transition transform" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-60 rounded-xl border border-gray-200 bg-white p-1.5 shadow-lg z-30" style="display: none;">
                    
                    <!-- Impor Massal yang Diterima ke Siswa Aktif -->
                    <button type="button" onclick="openModalBulkImportPsb()" class="w-full text-left flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition cursor-pointer">
                        <svg class="w-4 h-4 text-emerald-600 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                        <span>Add Massal ke Siswa Aktif</span>
                    </button>

                    <!-- Auto-Verifikasi Foto -->
                    <form action="{{ route('admin.psb.autoVerify') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition cursor-pointer">
                            <svg class="w-4 h-4 text-indigo-600 fill-current" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.574l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.574l7-10a1 1 0 011.12-.38z" fill="currentColor"/></svg>
                            <span>Auto-Verifikasi Foto 3x4</span>
                        </button>
                    </form>

                    <!-- Dongkrak Nilai CBT -->
                    <a href="{{ route('admin.cbt.dongkrak.index') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition">
                        <svg class="w-4 h-4 text-amber-500 fill-current" viewBox="0 0 20 20" fill="none"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>Dongkrak Nilai CBT</span>
                    </a>

                    <!-- Atur TTD Digital & Stempel -->
                    <a href="{{ route('admin.settings.index') }}" onclick="localStorage.setItem('admin_settings_active_tab', 'tab-ttd')" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition">
                        <svg class="w-4 h-4 text-emerald-600 fill-current" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.5858 2.58579C14.3668 1.80474 15.6332 1.80474 16.4142 2.58579L17.4142 3.58579C18.1953 4.36683 18.1953 5.63316 17.4142 6.41421L7.41421 16.4142C7.03914 16.7893 6.53043 17 6 17H3C2.44772 17 2 16.5523 2 16V13C2 12.4696 2.21071 11.9609 2.58579 11.5858L13.5858 2.58579ZM14.9999 4L15.9999 5L14.9999 6L13.9999 5L14.9999 4ZM12.5858 6.41421L4 15H3.5V14.5L12.0858 5.91421L12.5858 6.41421Z" fill="currentColor"/></svg>
                        <span>Atur TTD &amp; Stempel</span>
                    </a>

                    <div class="my-1 border-t border-gray-100"></div>

                    <!-- Buka Data Siswa Induk -->
                    <a href="{{ route('admin.siswa.index') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition">
                        <svg class="w-4 h-4 text-blue-600 fill-current" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a10.99 10.99 0 00-.25 2.31c0 .24.01.478.03.713a7.973 7.973 0 00-.03.926v3.125a1 1 0 001 1h8a1 1 0 001-1v-3.125a7.973 7.973 0 00-.03-.926c.02-.235.03-.473.03-.713 0-.796-.09-1.57-.25-2.31l2.644-1.131a1 1 0 000-1.84l-7-3z"/></svg>
                        <span>Ke Data Santri Induk</span>
                    </a>

                    <!-- Buka Formulir Pendaftaran Publik -->
                    <a href="{{ route('psb.register') }}" target="_blank" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition">
                        <svg class="w-4 h-4 text-gray-500 fill-current" viewBox="0 0 20 20" fill="none"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" fill="currentColor"/><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" fill="currentColor"/></svg>
                        <span>Buka Formulir Publik</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-medium shadow-theme-xs">
        <svg class="w-5 h-5 text-emerald-600 shrink-0 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.7071 5.29289C17.0976 5.68342 17.0976 6.31658 16.7071 6.70711L8.70711 14.7071C8.31658 15.0976 7.68342 15.0976 7.29289 14.7071L3.29289 10.7071C2.90237 10.3166 2.90237 9.68342 3.29289 9.29289C3.68342 8.90237 4.31658 8.90237 4.70711 9.29289L8 12.5858L15.2929 5.29289C15.6834 4.90237 16.3166 4.90237 16.7071 5.29289Z" fill="currentColor"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Filter & Search Card (TailAdmin Form Style: DefaultInputs) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs flex flex-col xl:flex-row gap-3 items-stretch xl:items-center justify-between">
        <form action="{{ route('admin.psb.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 flex-1">
            <!-- Search Input Box -->
            <div class="relative flex-1 min-w-[240px] max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.16667 3.33334C5.94501 3.33334 3.33334 5.94501 3.33334 9.16668C3.33334 12.3883 5.94501 15 9.16667 15C12.3883 15 15 12.3883 15 9.16668C15 5.94501 12.3883 3.33334 9.16667 3.33334ZM1.66667 9.16668C1.66667 5.02454 5.02454 1.66667 9.16667 1.66667C13.3088 1.66667 16.6667 5.02454 16.6667 9.16668C16.6667 10.9634 16.0336 12.6121 14.9755 13.9041L18.0355 16.9641C18.3609 17.2895 18.3609 17.8172 18.0355 18.1426C17.7101 18.468 17.1824 18.468 16.857 18.1426L13.797 15.0826C12.505 16.1407 10.8563 16.7733 9.16667 16.7733C5.02454 16.7733 1.66667 13.4155 1.66667 9.16668Z" fill="currentColor"/>
                    </svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama santri, No Reg, NISN, WA..." class="h-11 w-full pl-10 pr-9 text-xs sm:text-sm text-gray-800 bg-white border border-gray-300 rounded-lg focus:ring-3 focus:ring-brand-500/10 focus:border-brand-500 outline-none transition placeholder:text-gray-400 shadow-theme-xs">
                @if(request('q'))
                    <a href="{{ route('admin.psb.index', request()->except('q')) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-rose-500 transition" title="Hapus teks pencarian">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.29289 4.29289C4.68342 3.90237 5.31658 3.90237 5.70711 4.29289L10 8.58579L14.2929 4.29289C14.6834 3.90237 15.3166 3.90237 15.7071 4.29289C16.0976 4.68342 16.0976 5.31658 15.7071 5.70711L11.4142 10L15.7071 14.2929C16.0976 14.6834 16.0976 15.3166 15.7071 15.7071C15.3166 16.0976 14.6834 16.0976 14.2929 15.7071L10 11.4142L5.70711 15.7071C5.31658 16.0976 4.68342 16.0976 4.29289 15.7071C3.90237 15.3166 3.90237 14.6834 4.29289 14.2929L8.58579 10L4.29289 5.70711C3.90237 5.31658 3.90237 4.68342 4.29289 4.29289Z" fill="currentColor"/>
                        </svg>
                    </a>
                @endif
            </div>

            <!-- Filter Tahun Daftar -->
            <div class="relative">
                <select name="tahun" onchange="this.form.submit()" class="h-11 appearance-none text-xs sm:text-sm rounded-lg px-3.5 pr-8 border border-gray-300 outline-none transition cursor-pointer shadow-theme-xs {{ request('tahun') ? 'bg-emerald-50 border-emerald-300 text-emerald-700 font-bold' : 'bg-white text-gray-700' }}">
                    <option value="">Semua Tahun</option>
                    @foreach($years ?? [] as $yr)
                        <option value="{{ $yr }}" {{ request('tahun') == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                    @endforeach
                </select>
                <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </span>
            </div>

            <!-- Filter Jenis Kelamin (Putra / Putri) -->
            <div class="relative">
                <select name="jenis_kelamin" onchange="this.form.submit()" class="h-11 appearance-none text-xs sm:text-sm rounded-lg px-3.5 pr-8 border border-gray-300 outline-none transition cursor-pointer shadow-theme-xs {{ request('jenis_kelamin') ? 'bg-emerald-50 border-emerald-300 text-emerald-700 font-bold' : 'bg-white text-gray-700' }}">
                    <option value="">Semua Gender</option>
                    <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki (Putra)</option>
                    <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan (Putri)</option>
                </select>
                <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </span>
            </div>

            <!-- Filter Jenjang -->
            <div class="relative">
                <select name="jenjang" onchange="this.form.submit()" class="h-11 appearance-none text-xs sm:text-sm rounded-lg px-3.5 pr-8 border border-gray-300 outline-none transition cursor-pointer shadow-theme-xs {{ request('jenjang') ? 'bg-emerald-50 border-emerald-300 text-emerald-700 font-bold' : 'bg-white text-gray-700' }}">
                    <option value="">Semua Jenjang</option>
                    <option value="MTs Mukim" {{ request('jenjang') == 'MTs Mukim' ? 'selected' : '' }}>MTs Mukim</option>
                    <option value="MTs Laju" {{ request('jenjang') == 'MTs Laju' ? 'selected' : '' }}>MTs Laju</option>
                    <option value="MA Mukim" {{ request('jenjang') == 'MA Mukim' ? 'selected' : '' }}>MA Mukim</option>
                    <option value="MA Laju" {{ request('jenjang') == 'MA Laju' ? 'selected' : '' }}>MA Laju</option>
                </select>
                <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </span>
            </div>

            <!-- Filter Status Seleksi -->
            <div class="relative">
                <select name="status" onchange="this.form.submit()" class="h-11 appearance-none text-xs sm:text-sm rounded-lg px-3.5 pr-8 border border-gray-300 outline-none transition cursor-pointer shadow-theme-xs {{ request('status') ? 'bg-emerald-50 border-emerald-300 text-emerald-700 font-bold' : 'bg-white text-gray-700' }}">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </span>
            </div>

            <!-- Tombol Reset Filter jika aktif -->
            @if(request()->filled('q') || request()->filled('jenjang') || request()->filled('status') || request()->filled('jenis_kelamin') || request()->filled('tahun'))
                <a href="{{ route('admin.psb.index') }}" class="inline-flex items-center gap-1.5 h-11 px-3 text-xs font-medium text-rose-600 hover:text-white bg-rose-50 hover:bg-rose-600 border border-rose-200 hover:border-rose-600 rounded-lg transition shadow-theme-xs" title="Reset Semua Filter">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.29289 4.29289C4.68342 3.90237 5.31658 3.90237 5.70711 4.29289L10 8.58579L14.2929 4.29289C14.6834 3.90237 15.3166 3.90237 15.7071 4.29289C16.0976 4.68342 16.0976 5.31658 15.7071 5.70711L11.4142 10L15.7071 14.2929C16.0976 14.6834 16.0976 15.3166 15.7071 15.7071C15.3166 16.0976 14.6834 16.0976 14.2929 15.7071L10 11.4142L5.70711 15.7071C5.31658 16.0976 4.68342 16.0976 4.29289 15.7071C3.90237 15.3166 3.90237 14.6834 4.29289 14.2929L8.58579 10L4.29289 5.70711C3.90237 5.31658 3.90237 4.68342 4.29289 4.29289Z" fill="currentColor"/>
                    </svg>
                    <span>Reset</span>
                </a>
            @endif
        </form>

        <!-- Aksi Kanan: Export Excel & Total Data -->
        <div class="flex items-center gap-2.5 self-end xl:self-center shrink-0 pt-1 xl:pt-0">
            <a href="{{ route('admin.psb.export', request()->all()) }}" class="inline-flex items-center gap-2 h-11 px-4 rounded-lg bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-300 hover:border-emerald-600 text-xs font-medium transition shadow-theme-xs" title="Unduh file Excel (.xls) rapi sesuai filter yang aktif">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" fill="currentColor"/>
                </svg>
                <span>Download Excel</span>
            </a>
            <div class="inline-flex items-center h-11 px-4 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-600 font-medium whitespace-nowrap shadow-theme-xs">
                Total:&nbsp;<strong class="text-gray-900 font-bold">{{ $registrations->total() }}</strong>&nbsp;Santri
            </div>
        </div>
    </div>

    <!-- Applicants Table (TailAdmin BasicTableOne Format) -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full text-left">
                <thead class="bg-gray-50/70 text-gray-500 uppercase font-medium text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3.5 sm:px-6">Pas Foto & Identitas</th>
                        <th class="px-5 py-3.5">Jalur & Jenjang</th>
                        <th class="px-5 py-3.5">Upload Dokumen</th>
                        <th class="px-5 py-3.5">Wali & WhatsApp</th>
                        <th class="px-5 py-3.5">Hasil Ujian CBT</th>
                        <th class="px-5 py-3.5">Status Seleksi</th>
                        <th class="px-5 py-3.5 sm:px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
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

                            <!-- Hasil Ujian CBT & Status Kelulusan -->
                            <td class="px-4 py-4">
                                @if($reg->status_ujian === 'Selesai' && $reg->nilai_ujian !== null)
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs font-black {{ $reg->nilai_ujian >= 70 ? 'text-emerald-700' : 'text-rose-600' }}">
                                                {{ $reg->nilai_ujian }}
                                            </span>
                                            <span class="text-[10px] text-slate-400">/ 100</span>
                                        </div>
                                        @php
                                            $stKelulusan = $reg->status_kelulusan;
                                        @endphp
                                        @if($stKelulusan === 'Lulus')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-2xs">
                                                ✓ Lulus
                                            </span>
                                        @elseif($stKelulusan === 'Tidak Lulus')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200 shadow-2xs">
                                                ✕ Tidak Lulus
                                            </span>
                                        @elseif($stKelulusan === 'Lulus Bersyarat')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200 shadow-2xs">
                                                ⚠️ Bersyarat
                                            </span>
                                        @elseif($stKelulusan === 'Cadangan')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200 shadow-2xs">
                                                Cadangan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                                {{ $stKelulusan }}
                                            </span>
                                        @endif
                                    </div>
                                @elseif($reg->status_ujian === 'Sedang Ujian')
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-800 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                        Sedang Ujian
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">
                                        Belum Ujian
                                    </span>
                                @endif
                            </td>

                            <!-- Status & Quick Switcher -->
                            <td class="px-4 py-4">
                                <form action="{{ route('admin.psb.updateStatus', $reg->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-semibold rounded-lg px-2.5 py-1.5 border transition cursor-pointer outline-none shadow-2xs {{ 
                                        $reg->status == 'Diterima' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 
                                        ($reg->status == 'Ditolak' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200') 
                                    }}">
                                        <option value="Menunggu" {{ $reg->status == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                                        <option value="Diterima" {{ $reg->status == 'Diterima' ? 'selected' : '' }}>✓ Diterima</option>
                                        <option value="Ditolak" {{ $reg->status == 'Ditolak' ? 'selected' : '' }}>✕ Ditolak</option>
                                    </select>
                                </form>
                                @php
                                    $evalReg = $reg->evaluasiSyaratPenerimaan();
                                @endphp
                                <div class="mt-1">
                                    @if($evalReg['memenuhi'])
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200" title="Memenuhi kriteria: Nilai ujian >= KKM dan bukti pembayaran terunggah">
                                            ⚡ Nilai &amp; Bayar OK
                                        </span>
                                    @elseif($reg->bukti_transfer && !$evalReg['sudah_ujian'])
                                        <span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-500 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200">
                                            Bayar OK &bull; Belum Ujian
                                        </span>
                                    @elseif($evalReg['sudah_ujian'] && !$reg->bukti_transfer)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-medium text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">
                                            Nilai OK &bull; Belum Bayar
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Actions (TailAdmin Compact Style) -->
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center justify-end gap-1">
                                    <!-- 1. View Details & Berkas Modal Trigger -->
                                    <button type="button" onclick="showApplicantModal({{ json_encode($reg) }})" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Lihat Detail & Berkas Santri">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 12.5C11.3807 12.5 12.5 11.3807 12.5 10C12.5 8.61929 11.3807 7.5 10 7.5C8.61929 7.5 7.5 8.61929 7.5 10C7.5 11.3807 8.61929 12.5 10 12.5Z" fill="currentColor"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.833252 10C1.94436 5.83333 5.55547 2.5 9.99992 2.5C14.4444 2.5 18.0555 5.83333 19.1666 10C18.0555 14.1667 14.4444 17.5 9.99992 17.5C5.55547 17.5 1.94436 14.1667 0.833252 10ZM14.1666 10C14.1666 12.3012 12.3011 14.1667 9.99992 14.1667C7.69873 14.1667 5.83325 12.3012 5.83325 10C5.83325 7.69882 7.69873 5.83333 9.99992 5.83333C12.3011 5.83333 14.1666 7.69882 14.1666 10Z" fill="currentColor"/>
                                        </svg>
                                    </button>

                                    <!-- 2. Cetak Kartu CV & Dokumen Santri -->
                                    <a href="{{ route('admin.psb.print', ['ids' => $reg->id]) }}" target="_blank" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition" title="Cetak Berkas CV & Nilai">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6 2C4.89543 2 4 2.89543 4 4V8H20V4C20 2.89543 19.1046 2 18 2H6ZM18 14V19C18 20.1046 17.1046 21 16 21H8C6.89543 21 6 20.1046 6 19V14H18ZM20 10H4C2.89543 10 2 10.8954 2 12V16C2 17.1046 2.89543 18 4 18H4.5V14C4.5 12.8954 5.39543 12 6.5 12H17.5C18.6046 12 19.5 12.8954 19.5 14V18H20C21.1046 18 22 17.1046 22 16V12C22 10.8954 21.1046 10 20 10Z" fill="currentColor"/>
                                        </svg>
                                    </a>

                                    <!-- 3. WhatsApp Notification Button (if available) -->
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
                                    <a href="https://wa.me/{{ $cleanTargetPhone }}?text={{ urlencode($waMsg) }}" target="_blank" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Hubungi Wali via WA">
                                        <svg class="w-4 h-4 fill-current text-emerald-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2ZM12.04 20.16C10.56 20.16 9.11 19.76 7.85 19.01L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 14.99 3.8 13.47 3.8 11.91C3.8 7.37 7.5 3.67 12.04 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.16 12.04 20.16ZM16.56 14.41C16.31 14.29 15.1 13.69 14.88 13.61C14.65 13.53 14.49 13.49 14.32 13.74C14.16 13.99 13.69 14.54 13.55 14.71C13.4 14.87 13.26 14.89 13.01 14.77C12.76 14.64 11.97 14.38 11.03 13.55C10.3 12.9 9.8 12.1 9.68 11.89C9.55 11.69 9.67 11.58 9.79 11.46C9.9 11.35 10.04 11.17 10.16 11.03C10.28 10.89 10.32 10.79 10.41 10.63C10.49 10.46 10.45 10.32 10.39 10.2C10.33 10.07 9.84 8.87 9.63 8.38C9.43 7.9 9.23 7.96 9.08 7.95C8.94 7.95 8.77 7.94 8.61 7.94C8.45 7.94 8.18 8 7.96 8.25C7.73 8.49 7.1 9.08 7.1 10.29C7.1 11.5 7.98 12.67 8.11 12.83C8.23 13 9.85 15.49 12.33 16.56C12.92 16.82 13.38 16.97 13.74 17.08C14.33 17.27 14.87 17.24 15.3 17.18C15.77 17.11 16.76 16.58 16.96 16.01C17.17 15.44 17.17 14.95 17.11 14.85C17.04 14.75 16.88 14.54 16.56 14.41Z" fill="currentColor"/>
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- 4. TailAdmin Kebab Dropdown Menu -->
                                    <div class="relative inline-block text-left" x-data="{ rowOpen: false }" @click.outside="rowOpen = false">
                                        <button @click="rowOpen = !rowOpen" type="button" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition" title="Menu Opsi Lainnya">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 6C10.8284 6 11.5 5.32843 11.5 4.5C11.5 3.67157 10.8284 3 10 3C9.17157 3 8.5 3.67157 8.5 4.5C8.5 5.32843 9.17157 6 10 6Z" fill="currentColor"/>
                                                <path d="M10 11.5C10.8284 11.5 11.5 10.8284 11.5 10C11.5 9.17157 10.8284 8.5 10 8.5C9.17157 8.5 8.5 9.17157 8.5 10C8.5 10.8284 9.17157 11.5 10 11.5Z" fill="currentColor"/>
                                                <path d="M10 17C10.8284 17 11.5 16.3284 11.5 15.5C11.5 14.6716 10.8284 14 10 14C9.17157 14 8.5 14.6716 8.5 15.5C8.5 16.3284 9.17157 17 10 17Z" fill="currentColor"/>
                                            </svg>
                                        </button>

                                        <div x-show="rowOpen" x-transition class="absolute right-0 mt-1 w-48 rounded-xl border border-gray-200 bg-white p-1.5 shadow-lg z-30 text-left" style="display: none;">
                                            @if($reg->status === 'Diterima')
                                            <form action="{{ route('admin.siswa.importPsb', $reg->id) }}" method="POST" onsubmit="return confirm('Daftarkan ananda {{ $reg->nama_lengkap }} sebagai Siswa/Santri Aktif?')">
                                                @csrf
                                                <button type="submit" class="w-full text-left flex items-center gap-2 px-3 py-2 text-xs font-medium text-emerald-700 hover:bg-emerald-50 rounded-lg transition">
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" fill="currentColor"/></svg>
                                                    <span>Jadikan Siswa Aktif</span>
                                                </button>
                                            </form>
                                            @endif

                                            <a href="{{ route('psb.success', $reg->id) }}" target="_blank" class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                                <svg class="w-4 h-4 text-gray-500 fill-current" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                                <span>Bukti Registrasi</span>
                                            </a>

                                            <a href="{{ route('psb.printCard', ['id' => $reg->id, 'mode' => 'ujian']) }}" target="_blank" class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                                <svg class="w-4 h-4 text-emerald-600 fill-current" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                                                <span>Lembar Nilai CBT</span>
                                            </a>

                                            <div class="my-1 border-t border-gray-100"></div>

                                            <form action="{{ route('admin.psb.destroy', $reg->id) }}" method="POST" onsubmit="return confirm('Hapus data pendaftar {{ $reg->nama_lengkap }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left flex items-center gap-2 px-3 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                    <span>Hapus Data</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm-1-9a1 1 0 012 0v3a1 1 0 11-2 0V7zm1 6a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-700">Belum ada calon santri yang mendaftar atau cocok dengan filter.</p>
                                <p class="text-xs text-gray-400 mt-1">Coba sesuaikan filter tahun, jenjang, atau reset kata kunci pencarian.</p>
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

            <!-- HASIL EVALUASI UJIAN CBT & STATUS KELULUSAN (MODAL) -->
            <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/40 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-900 uppercase tracking-wider block flex items-center gap-2 text-xs">
                        <svg class="icon-svg w-4 h-4 text-emerald-600" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        Hasil Ujian Seleksi (CBT Online) & Kelulusan
                    </span>
                    <div class="flex items-center gap-2">
                        <a id="modalCbtDongkrakLink" href="#" class="text-xs font-semibold text-amber-800 bg-amber-100 hover:bg-amber-200 px-2.5 py-1 rounded-md transition border border-amber-300">
                            Dongkrak Nilai Santri Ini &rarr;
                        </a>
                        <a id="modalCbtLink" href="#" target="_blank" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 transition">
                            Buka Rekap CBT &rarr;
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div class="p-2.5 bg-white rounded-lg border border-slate-200">
                        <span class="text-slate-400 block font-semibold text-[10px]">NILAI CBT</span>
                        <span class="text-base font-black text-slate-800" id="modalCbtNilai">-</span>
                    </div>
                    <div class="p-2.5 bg-white rounded-lg border border-slate-200">
                        <span class="text-slate-400 block font-semibold text-[10px]">STATUS KELULUSAN</span>
                        <span class="font-bold text-slate-800" id="modalCbtKelulusan">-</span>
                    </div>
                    <div class="p-2.5 bg-white rounded-lg border border-slate-200">
                        <span class="text-slate-400 block font-semibold text-[10px]">TEGURAN CONTEK</span>
                        <span class="font-bold text-slate-800" id="modalCbtCurang">-</span>
                    </div>
                    <div class="p-2.5 bg-white rounded-lg border border-slate-200">
                        <span class="text-slate-400 block font-semibold text-[10px]">STATUS PENGERJAAN</span>
                        <span class="font-bold text-slate-800" id="modalCbtStatus">-</span>
                    </div>
                </div>
                <div id="modalCbtCatatanWrap" class="text-xs text-slate-600 bg-white p-2.5 rounded-lg border border-slate-200 hidden">
                    <strong>Catatan Dewan Penguji:</strong> <span id="modalCbtCatatan">-</span>
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
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <span class="text-xs text-slate-400 text-center sm:text-left">Verifikasi berkas fisik dan ijazah asli dilakukan saat kedatangan santri di kampus.</span>
            <div class="flex flex-wrap items-center justify-center gap-2 w-full sm:w-auto">
                <a id="modalBtnPrintUjian" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-theme-xs transition">
                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    <span>Cetak Lembar Nilai CBT</span>
                </a>
                <a id="modalBtnPrint" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-900 hover:bg-gray-800 text-white text-xs font-medium rounded-lg shadow-theme-xs transition">
                    <svg class="icon-svg w-3.5 h-3.5" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    <span>Cetak CV &amp; Berkas</span>
                </a>
                <button onclick="closeApplicantModal()" class="px-4 py-2 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 text-xs font-medium rounded-lg transition">
                    Tutup
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
    document.getElementById('modalBtnPrintUjian').href = '/pendaftaran/cetak-kartu/' + reg.id + '?mode=ujian';
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

    // POPULATE HASIL UJIAN CBT & STATUS KELULUSAN
    const cbtNilai = document.getElementById('modalCbtNilai');
    const cbtKelulusan = document.getElementById('modalCbtKelulusan');
    const cbtCurang = document.getElementById('modalCbtCurang');
    const cbtStatus = document.getElementById('modalCbtStatus');
    const cbtCatatanWrap = document.getElementById('modalCbtCatatanWrap');
    const cbtCatatan = document.getElementById('modalCbtCatatan');
    const cbtLink = document.getElementById('modalCbtLink');
    const cbtDongkrakLink = document.getElementById('modalCbtDongkrakLink');

    cbtLink.href = '{{ route("admin.cbt.hasil.index") }}?q=' + encodeURIComponent(reg.no_registrasi || reg.nama_lengkap);
    cbtDongkrakLink.href = '{{ route("admin.cbt.dongkrak.index") }}?q=' + encodeURIComponent(reg.no_registrasi || reg.nama_lengkap);

    if (reg.nilai_ujian !== null && reg.nilai_ujian !== undefined) {
        cbtNilai.textContent = reg.nilai_ujian + ' / 100';
        cbtNilai.className = 'text-base font-black ' + (reg.nilai_ujian >= 70 ? 'text-emerald-700' : 'text-rose-600');
    } else {
        cbtNilai.textContent = 'Belum Ada';
        cbtNilai.className = 'text-sm font-semibold text-slate-400';
    }

    const kelulusan = reg.status_kelulusan_override || (reg.status_ujian === 'Selesai' && reg.nilai_ujian !== null ? (reg.nilai_ujian >= 70 ? 'Lulus' : 'Tidak Lulus') : 'Belum Ada Hasil');
    cbtKelulusan.textContent = kelulusan;
    if (kelulusan === 'Lulus') {
        cbtKelulusan.className = 'font-bold text-emerald-700';
    } else if (kelulusan === 'Tidak Lulus') {
        cbtKelulusan.className = 'font-bold text-rose-600';
    } else if (kelulusan === 'Lulus Bersyarat') {
        cbtKelulusan.className = 'font-bold text-amber-600';
    } else {
        cbtKelulusan.className = 'font-bold text-slate-700';
    }

    cbtCurang.textContent = (reg.pelanggaran_curang_count || 0) + 'x Teguran';
    cbtCurang.className = (reg.pelanggaran_curang_count > 0) ? 'font-bold text-rose-600' : 'font-bold text-emerald-700';

    cbtStatus.textContent = reg.status_ujian || 'Belum Ujian';

    if (reg.catatan_penguji) {
        cbtCatatanWrap.classList.remove('hidden');
        cbtCatatan.textContent = reg.catatan_penguji;
    } else {
        cbtCatatanWrap.classList.add('hidden');
    }

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

function openModalBulkImportPsb() {
    document.getElementById('modalBulkImportPsb').classList.remove('hidden');
}

function closeModalBulkImportPsb() {
    document.getElementById('modalBulkImportPsb').classList.add('hidden');
}

function closeModalAutoSeleksi() {
    document.getElementById('modalAutoSeleksi').classList.add('hidden');
}
</script>

<!-- MODAL 1: KONFIGURASI SELEKSI OTOMATIS PSB -->
<div id="modalAutoSeleksi" class="ta-modal-backdrop hidden">
    <div class="ta-modal max-w-lg">
        <div class="ta-modal-header">
            <div>
                <h3 class="font-bold text-gray-800 text-base">Konfigurasi Seleksi Otomatis PSB</h3>
                <p class="text-xs text-gray-500 mt-0.5">Tentukan kriteria penerimaan calon santri baru secara serentak.</p>
            </div>
            <button type="button" onclick="closeModalAutoSeleksi()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <form action="{{ route('admin.psb.autoSeleksi') }}" method="POST">
            @csrf
            <div class="ta-modal-body space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-2">1. Kriteria Penerimaan (DITERIMA)</label>
                    <div class="space-y-2">
                        <label class="flex items-start gap-2.5 p-3 rounded-xl border border-emerald-200 bg-emerald-50/50 cursor-pointer hover:bg-emerald-50 transition">
                            <input type="radio" name="kriteria" value="nilai_cbt" checked class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <span class="font-bold text-emerald-950 block">Kelulusan Nilai CBT Saja (Direkomendasikan)</span>
                                <span class="text-gray-600 block mt-0.5">Semua santri yang nilai ujiannya memenuhi KKM (>= 70) atau dinyatakan Lulus oleh dewan penguji akan otomatis <strong>Diterima</strong>.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-2.5 p-3 rounded-xl border border-gray-200 bg-white cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="kriteria" value="nilai_dan_bayar" class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <span class="font-bold text-gray-900 block">Nilai CBT Lulus &amp; Bukti Pembayaran Terunggah</span>
                                <span class="text-gray-500 block mt-0.5">Hanya menerima calon santri yang lulus CBT DAN sudah mengunggah bukti transfer biaya pendaftaran.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-2.5 p-3 rounded-xl border border-gray-200 bg-white cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="kriteria" value="kuota" class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                            <div class="flex-1">
                                <span class="font-bold text-gray-900 block">Berdasarkan Kuota Nilai Tertinggi (Top Ranking)</span>
                                <span class="text-gray-500 block mt-0.5">Menerima sejumlah pendaftar dengan nilai ujian terbaik:</span>
                                <div class="flex items-center gap-2 mt-2">
                                    <input type="number" name="kuota_count" value="50" min="1" class="ta-input w-24 py-1 text-xs">
                                    <span class="text-gray-500 text-[11px]">Calon santri terbaik</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-2">2. Status Calon Santri yang Tidak Memenuhi Syarat</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border border-gray-200 bg-white cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status_tidak_lulus" value="tetap" checked class="text-emerald-600">
                            <span class="text-gray-700 font-medium">Tetap "Menunggu"</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border border-gray-200 bg-white cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status_tidak_lulus" value="tolak" class="text-rose-600">
                            <span class="text-gray-700 font-medium">Ubah jadi "Ditolak"</span>
                        </label>
                    </div>
                </div>

                <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 flex items-start gap-2 text-[11px] leading-relaxed">
                    <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span><strong>Bisa Diedit Kembali Kapan Saja:</strong> Seluruh status hasil seleksi otomatis tidak bersifat permanen. Admin tetap dapat mengubah atau menyesuaikan kembali status setiap santri satu per satu melalui dropdown tabel maupun detail berkas santri.</span>
                </div>
            </div>

            <div class="ta-modal-footer">
                <button type="button" onclick="closeModalAutoSeleksi()" class="ta-btn-sm-outline">
                    Batal
                </button>
                <button type="submit" class="ta-btn-sm-primary">
                    Jalankan Seleksi Otomatis Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: IMPOR MASSAL KE SISWA AKTIF -->
<div id="modalBulkImportPsb" class="ta-modal-backdrop hidden">
    <div class="ta-modal max-w-md">
        <div class="ta-modal-header">
            <div>
                <h3 class="font-bold text-gray-800 text-base">Add Massal ke Siswa Aktif</h3>
                <p class="text-xs text-gray-500 mt-0.5">Pindahkan semua calon santri yang berstatus DITERIMA menjadi Santri Aktif.</p>
            </div>
            <button type="button" onclick="closeModalBulkImportPsb()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <form action="{{ route('admin.siswa.bulkImportPsb') }}" method="POST">
            @csrf
            <div class="ta-modal-body space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Tahun Masuk / Angkatan</label>
                    <input type="text" name="tahun_masuk" value="{{ date('Y') }}" required class="ta-input font-bold">
                    <span class="text-[11px] text-gray-400 mt-1 block">Nomor Induk Santri (NIS) akan otomatis digenerate berurutan: {{ date('Y') }}0001, {{ date('Y') }}0002, dst.</span>
                </div>

                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl space-y-1.5 text-emerald-950 text-[11px] leading-relaxed">
                    <div class="font-bold flex items-center gap-1.5 text-emerald-800">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Otomatisasi Akun Login &amp; Kelas:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-emerald-900">
                        <li><strong>Kelas Awal:</strong> Santri MTs otomatis masuk kelas <strong>VII-A</strong>, dan MA masuk kelas <strong>X-A</strong>.</li>
                        <li><strong>Username Santri:</strong> Menggunakan NISN atau NIS.</li>
                        <li><strong>Password Login:</strong> Otomatis menggunakan <strong>tanggal lahir</strong> santri (format DDMMYYYY, misal 15052010).</li>
                    </ul>
                    <div class="pt-2 border-t border-emerald-200/60 mt-2">
                        <p class="text-emerald-800 font-medium">
                            💡 Ingin membagi santri baru ke kelas 7 apa atau 10 apa sesuai peringkat CBT atau suka-suka admin? Silakan gunakan menu 
                            <a href="{{ route('admin.siswa.penempatanKelas') }}" class="font-bold underline text-emerald-900 hover:text-emerald-950">Penempatan Kelas Santri Baru</a>.
                        </p>
                    </div>
                </div>
            </div>

            <div class="ta-modal-footer">
                <button type="button" onclick="closeModalBulkImportPsb()" class="ta-btn-sm-outline">
                    Batal
                </button>
                <button type="submit" class="ta-btn-sm-primary">
                    Proses Impor Massal Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


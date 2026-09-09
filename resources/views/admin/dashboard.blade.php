@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">

        <!-- Page Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Dashboard Utama</h1>
                <p class="text-sm text-slate-500">Ringkasan statistik data santri, warta kampus, dan aktivitas
                    kepesantrenan.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.berita.create') }}"
                    class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tulis Berita Baru
                </a>
                <a href="{{ route('admin.psb.index') }}"
                    class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
                    Data PSB
                </a>
            </div>
        </div>

        <!-- STATS CARDS (TailAdmin Style) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Stat Card 1 -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-1">Berita
                        Terbit</span>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $publishedArticles }}</h3>
                    <span class="text-xs text-slate-500 mt-1 block">Dari total {{ $totalArticles }} artikel</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="icon-svg w-6 h-6" viewBox="0 0 24 24">
                        <path
                            d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-1">Pendaftar
                        PSB</span>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $totalPsb }}</h3>
                    <span class="text-xs text-emerald-600 font-medium mt-1 block">Calon Santri TA 2025/2026</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="icon-svg w-6 h-6" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-1">Total
                        Pembaca</span>
                    <h3 class="text-2xl font-bold text-slate-800">{{ number_format($totalViews) }}</h3>
                    <span class="text-xs text-slate-500 mt-1 block">Akumulasi views warta</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="icon-svg w-6 h-6" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-1">Status
                        Kampus</span>
                    <h3 class="text-lg font-bold text-emerald-600">Aktif & Beroperasi</h3>
                    <span class="text-xs text-slate-500 mt-1 block">NSPP: 512032304095</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="icon-svg w-6 h-6" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- RECENT ACTIVITY GRIDS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Recent News (TailAdmin Table Card) -->
            <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Warta & Berita Terbaru</h3>
                        <p class="text-xs text-slate-500">Artikel terakhir yang diterbitkan oleh humas</p>
                    </div>
                    <a href="{{ route('admin.berita.index') }}"
                        class="text-xs font-semibold text-brand-500 hover:text-brand-700">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead
                            class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-5 py-3">Judul Berita</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Views</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentArticles as $art)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-5 py-3.5">
                                        <div class="font-semibold text-slate-800 truncate max-w-xs">{{ $art->title }}</div>
                                        <span
                                            class="text-[11px] text-slate-400">{{ $art->published_at ? $art->published_at->format('d M Y') : $art->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span
                                            class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">{{ $art->category }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 font-medium text-slate-700">{{ $art->views }}</td>
                                    <td class="px-4 py-3.5 text-right space-x-2">
                                        <a href="{{ route('berita.show', $art->slug) }}" target="_blank"
                                            class="text-slate-500 hover:text-slate-800">Lihat</a>
                                        <a href="{{ route('admin.berita.edit', $art->id) }}"
                                            class="text-brand-600 hover:text-brand-800 font-semibold">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-6 text-center text-slate-400">Belum ada artikel yang
                                        diterbitkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent PSB Applicants -->
            <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Pendaftar PSB Terbaru</h3>
                        <p class="text-xs text-slate-500">Calon santri baru TA 2025/2026</p>
                    </div>
                    <a href="{{ route('admin.psb.index') }}"
                        class="text-xs font-semibold text-brand-500 hover:text-brand-700">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead
                            class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-5 py-3">Nama Santri</th>
                                <th class="px-4 py-3">Jenjang</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Kontak</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentPsb as $reg)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-5 py-3.5">
                                        <div class="font-semibold text-slate-800">{{ $reg->nama_lengkap }}</div>
                                        <span class="text-[11px] text-slate-400">Wali: {{ $reg->nama_wali }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 font-medium">{{ $reg->jenjang }}</td>
                                    <td class="px-4 py-3.5">
                                        @if($reg->status === 'Diterima')
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Diterima</span>
                                        @elseif($reg->status === 'Ditolak')
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Menunggu</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-right">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $reg->no_whatsapp) }}"
                                            target="_blank"
                                            class="text-emerald-600 hover:text-emerald-800 font-semibold inline-flex items-center gap-1">
                                            WA
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-6 text-center text-slate-400">Belum ada pendaftaran masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
@endsection
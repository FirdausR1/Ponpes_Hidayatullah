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

        <!-- STATS CARDS (TailAdmin EcommerceMetrics Structure) -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
            <!-- Metric 1: Berita -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.5 19.75C19.5 20.9926 18.4926 22 17.25 22H6.75C5.50736 22 4.5 20.9926 4.5 19.75V9.62105C4.5 9.02455 4.73686 8.45247 5.15851 8.03055L10.5262 2.65951C10.9482 2.23725 11.5207 2 12.1177 2H17.25C18.4926 2 19.5 3.00736 19.5 4.25V19.75ZM17.25 20.5C17.6642 20.5 18 20.1642 18 19.75V4.25C18 3.83579 17.6642 3.5 17.25 3.5H12.248L12.2509 7.49913C12.2518 8.7424 11.2442 9.75073 10.0009 9.75073H6V19.75C6 20.1642 6.33579 20.5 6.75 20.5H17.25ZM7.05913 8.25073L10.7488 4.55876L10.7509 7.5002C10.7512 7.91462 10.4153 8.25073 10.0009 8.25073H7.05913ZM8.25 14.5C8.25 14.0858 8.58579 13.75 9 13.75H15C15.4142 13.75 15.75 14.0858 15.75 14.5C15.75 14.9142 15.4142 15.25 15 15.25H9C8.58579 15.25 8.25 14.9142 8.25 14.5ZM8.25 17.5C8.25 17.0858 8.58579 16.75 9 16.75H12C12.4142 16.75 12.75 17.0858 12.75 17.5C12.75 17.9142 12.4142 18.25 12 18.25H9C8.58579 18.25 8.25 17.9142 8.25 17.5Z" fill="currentColor"/></svg>
                </div>
                <div class="flex items-end justify-between mt-5">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block">Berita Terbit</span>
                        <h4 class="mt-1 font-bold text-gray-800 text-2xl">{{ $publishedArticles }}</h4>
                    </div>
                    <span class="rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 border border-blue-200">
                        Total {{ $totalArticles }}
                    </span>
                </div>
            </div>

            <!-- Metric 2: PSB -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
                <div class="flex items-center justify-center w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-6 h-6" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.33633 4.79297C6.39425 4.79297 5.63054 5.55668 5.63054 6.49876C5.63054 7.44084 6.39425 8.20454 7.33633 8.20454C8.27841 8.20454 9.04212 7.44084 9.04212 6.49876C9.04212 5.55668 8.27841 4.79297 7.33633 4.79297ZM4.13054 6.49876C4.13054 4.72825 5.56582 3.29297 7.33633 3.29297C9.10684 3.29297 10.5421 4.72825 10.5421 6.49876C10.5421 8.26926 9.10684 9.70454 7.33633 9.70454C5.56582 9.70454 4.13054 8.26926 4.13054 6.49876ZM4.24036 12.7602C3.61952 13.3265 3.28381 14.0575 3.10504 14.704C3.06902 14.8343 3.09994 14.9356 3.17904 15.0229C3.26864 15.1218 3.4319 15.2073 3.64159 15.2073H10.9411C11.1507 15.2073 11.314 15.1218 11.4036 15.0229C11.4827 14.9356 11.5136 14.8343 11.4776 14.704C11.2988 14.0575 10.9631 13.3265 10.3423 12.7602C9.73639 12.2075 8.7967 11.7541 7.29132 11.7541C5.78595 11.7541 4.84626 12.2075 4.24036 12.7602ZM3.22949 11.652C4.14157 10.82 5.4544 10.2541 7.29132 10.2541C9.12825 10.2541 10.4411 10.82 11.3532 11.652C12.2503 12.4703 12.698 13.4893 12.9234 14.3042C13.1054 14.9627 12.9158 15.5879 12.5152 16.03C12.1251 16.4605 11.5496 16.7073 10.9411 16.7073H3.64159C3.03301 16.7073 2.45751 16.4605 2.06745 16.03C1.66689 15.5879 1.47723 14.9627 1.65929 14.3042C1.88464 13.4893 2.33237 12.4703 3.22949 11.652ZM12.7529 9.70454C12.1654 9.70454 11.6148 9.54648 11.1412 9.27055C11.4358 8.86714 11.6676 8.4151 11.8226 7.92873C12.0902 8.10317 12.4097 8.20454 12.7529 8.20454C13.695 8.20454 14.4587 7.44084 14.4587 6.49876C14.4587 5.55668 13.695 4.79297 12.7529 4.79297C12.4097 4.79297 12.0901 4.89435 11.8226 5.0688C11.6676 4.58243 11.4357 4.13039 11.1412 3.72698C11.6147 3.45104 12.1654 3.29297 12.7529 3.29297C14.5235 3.29297 15.9587 4.72825 15.9587 6.49876C15.9587 8.26926 14.5235 9.70454 12.7529 9.70454ZM16.3577 16.7072H13.8902C14.1962 16.2705 14.4012 15.7579 14.4688 15.2072H16.3577C16.5674 15.2072 16.7307 15.1217 16.8203 15.0228C16.8994 14.9355 16.9303 14.8342 16.8943 14.704C16.7155 14.0574 16.3798 13.3264 15.759 12.7601C15.2556 12.301 14.5219 11.9104 13.425 11.7914C13.1434 11.3621 12.7952 10.9369 12.3641 10.5437C12.2642 10.4526 12.1611 10.3643 12.0548 10.2791C12.2648 10.2626 12.4824 10.2541 12.708 10.2541C14.5449 10.2541 15.8577 10.82 16.7698 11.6519C17.6669 12.4702 18.1147 13.4892 18.34 14.3042C18.5221 14.9626 18.3324 15.5879 17.9319 16.03C17.5418 16.4605 16.9663 16.7072 16.3577 16.7072Z" fill="currentColor"/></svg>
                </div>
                <div class="flex items-end justify-between mt-5">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block">Pendaftar PSB</span>
                        <h4 class="mt-1 font-bold text-gray-800 text-2xl">{{ $totalPsb }}</h4>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 border border-emerald-200">
                        TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}
                    </span>
                </div>
            </div>

            <!-- Metric 3: Views -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
                <div class="flex items-center justify-center w-12 h-12 bg-amber-50 text-amber-600 rounded-xl">
                    <svg class="w-6 h-6" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.85954 4.0835C9.5834 4.0835 9.35954 4.30735 9.35954 4.5835V15.4161C9.35954 15.6922 9.5834 15.9161 9.85954 15.9161H10.1373C10.4135 15.9161 10.6373 15.6922 10.6373 15.4161V4.5835C10.6373 4.30735 10.4135 4.0835 10.1373 4.0835H9.85954ZM7.85954 4.5835C7.85954 3.47893 8.75497 2.5835 9.85954 2.5835H10.1373C11.2419 2.5835 12.1373 3.47893 12.1373 4.5835V15.4161C12.1373 16.5206 11.2419 17.4161 10.1373 17.4161H9.85954C8.75497 17.4161 7.85954 16.5206 7.85954 15.4161V4.5835ZM4.58203 8.9598C4.30589 8.9598 4.08203 9.18366 4.08203 9.4598V15.4168C4.08203 15.693 4.30589 15.9168 4.58203 15.9168H4.85981C5.13595 15.9168 5.35981 15.693 5.35981 15.4168V9.4598C5.35981 9.18366 5.13595 8.9598 4.85981 8.9598H4.58203ZM2.58203 9.4598C2.58203 8.35523 3.47746 7.4598 4.58203 7.4598H4.85981C5.96438 7.4598 6.85981 8.35523 6.85981 9.4598V15.4168C6.85981 16.5214 5.96438 17.4168 4.85981 17.4168H4.58203C3.47746 17.4168 2.58203 16.5214 2.58203 15.4168V9.4598ZM14.637 12.435C14.637 12.1589 14.8609 11.935 15.137 11.935H15.4148C15.691 11.935 15.9148 12.1589 15.9148 12.435V15.4168C15.9148 15.693 15.691 15.9168 15.4148 15.9168H15.137C14.8609 15.9168 14.637 15.693 14.637 15.4168V12.435ZM15.137 10.435C14.0325 10.435 13.137 11.3304 13.137 12.435V15.4168C13.137 16.5214 14.0325 17.4168 15.137 17.4168H15.4148C16.5194 17.4168 17.4148 16.5214 17.4148 15.4168V12.435C17.4148 11.3304 16.5194 10.435 15.4148 10.435H15.137Z" fill="currentColor"/></svg>
                </div>
                <div class="flex items-end justify-between mt-5">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block">Total Pembaca</span>
                        <h4 class="mt-1 font-bold text-gray-800 text-2xl">{{ number_format($totalViews) }}</h4>
                    </div>
                    <span class="rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 border border-amber-200">
                        Views
                    </span>
                </div>
            </div>

            <!-- Metric 4: Status -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
                <div class="flex items-center justify-center w-12 h-12 bg-purple-50 text-purple-600 rounded-xl">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z" fill="currentColor"/></svg>
                </div>
                <div class="flex items-end justify-between mt-5">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block">Status Kampus</span>
                        <h4 class="mt-1 font-bold text-emerald-600 text-lg">Aktif & Beroperasi</h4>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 border border-emerald-200">
                        NSPP: 512032304095
                    </span>
                </div>
            </div>
        </div>

        <!-- RECENT ACTIVITY GRIDS (TailAdmin BasicTableOne Integration) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Recent News -->
            <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800 text-base">Warta & Berita Terbaru</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Artikel terakhir yang diterbitkan oleh humas</p>
                    </div>
                    <a href="{{ route('admin.berita.index') }}"
                        class="text-xs font-semibold text-brand-600 hover:text-brand-800 transition">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/70 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Judul Berita</th>
                                <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Kategori</th>
                                <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Views</th>
                                <th class="px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentArticles as $art)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-6 py-3.5">
                                        <div class="font-semibold text-gray-800 text-sm truncate max-w-xs">{{ $art->title }}</div>
                                        <span class="text-xs text-gray-400">{{ $art->published_at ? $art->published_at->format('d M Y') : $art->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">{{ $art->category }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 font-medium text-gray-700 text-xs">{{ $art->views }}</td>
                                    <td class="px-6 py-3.5 text-right space-x-2 text-xs">
                                        <a href="{{ route('berita.show', $art->slug) }}" target="_blank"
                                            class="text-gray-500 hover:text-gray-800">Lihat</a>
                                        <a href="{{ route('admin.berita.edit', $art->id) }}"
                                            class="text-brand-600 hover:text-brand-800 font-semibold">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-xs">Belum ada artikel yang diterbitkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent PSB Applicants -->
            <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800 text-base">Pendaftar PSB Terbaru</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Calon santri baru TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}</p>
                    </div>
                    <a href="{{ route('admin.psb.index') }}"
                        class="text-xs font-semibold text-brand-600 hover:text-brand-800 transition">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/70 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Nama Santri</th>
                                <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Jenjang</th>
                                <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider text-right">Kontak</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentPsb as $reg)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-6 py-3.5">
                                        <div class="font-semibold text-gray-800 text-sm">{{ $reg->nama_lengkap }}</div>
                                        <span class="text-xs text-gray-400">Wali: {{ $reg->nama_wali }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 font-medium text-gray-700 text-xs">{{ $reg->jenjang }}</td>
                                    <td class="px-4 py-3.5">
                                        @if($reg->status === 'Diterima')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Diterima</span>
                                        @elseif($reg->status === 'Ditolak')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">Menunggu</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-right text-xs">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $reg->no_whatsapp) }}"
                                            target="_blank"
                                            class="text-emerald-600 hover:text-emerald-800 font-semibold inline-flex items-center gap-1">
                                            WhatsApp
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-xs">Belum ada pendaftaran masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
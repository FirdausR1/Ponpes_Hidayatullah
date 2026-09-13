@extends('admin.layout')

@section('title', 'Kelola Berita')

@section('content')
<div class="space-y-6">

    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 border border-emerald-200">Publikasi Dakwah</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">Warta Pesantren</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kelola Warta & Berita</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Daftar seluruh artikel warta, liputan dakwah, dan agenda santri pesantren.</p>
        </div>
        <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-medium text-white shadow-theme-xs hover:bg-emerald-700 transition">
            <!-- TailAdmin PlusIcon -->
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25012 3C5.25012 2.58579 5.58591 2.25 6.00012 2.25C6.41433 2.25 6.75012 2.58579 6.75012 3V5.25012L9.00034 5.25012C9.41455 5.25012 9.75034 5.58591 9.75034 6.00012C9.75034 6.41433 9.41455 6.75012 9.00034 6.75012H6.75012V9.00034C6.75012 9.41455 6.41433 9.75034 6.00012 9.75034C5.58591 9.75034 5.25012 9.41455 5.25012 9.00034L5.25012 6.75012H3C2.58579 6.75012 2.25 6.41433 2.25 6.00012C2.25 5.58591 2.58579 5.25012 3 5.25012H5.25012V3Z" fill="currentColor"/>
            </svg>
            Tulis Berita Baru
        </a>
    </div>

    <!-- Filter & Search Card (TailAdmin Form Style: DefaultInputs) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
        <form action="{{ route('admin.berita.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 flex-1">
            <!-- Search Input Box -->
            <div class="relative flex-1 min-w-[220px] max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.16667 3.33334C5.94501 3.33334 3.33334 5.94501 3.33334 9.16668C3.33334 12.3883 5.94501 15 9.16667 15C12.3883 15 15 12.3883 15 9.16668C15 5.94501 12.3883 3.33334 9.16667 3.33334ZM1.66667 9.16668C1.66667 5.02454 5.02454 1.66667 9.16667 1.66667C13.3088 1.66667 16.6667 5.02454 16.6667 9.16668C16.6667 10.9634 16.0336 12.6121 14.9755 13.9041L18.0355 16.9641C18.3609 17.2895 18.3609 17.8172 18.0355 18.1426C17.7101 18.468 17.1824 18.468 16.857 18.1426L13.797 15.0826C12.505 16.1407 10.8563 16.7733 9.16667 16.7733C5.02454 16.7733 1.66667 13.4155 1.66667 9.16668Z" fill="currentColor"/>
                    </svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau penulis..." class="h-11 w-full pl-10 pr-9 text-xs sm:text-sm text-gray-800 bg-white border border-gray-300 rounded-lg focus:ring-3 focus:ring-brand-500/10 focus:border-brand-500 outline-none transition placeholder:text-gray-400 shadow-theme-xs">
                @if(request('q'))
                    <a href="{{ route('admin.berita.index', request()->except('q')) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-rose-500 transition" title="Hapus teks pencarian">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.29289 4.29289C4.68342 3.90237 5.31658 3.90237 5.70711 4.29289L10 8.58579L14.2929 4.29289C14.6834 3.90237 15.3166 3.90237 15.7071 4.29289C16.0976 4.68342 16.0976 5.31658 15.7071 5.70711L11.4142 10L15.7071 14.2929C16.0976 14.6834 16.0976 15.3166 15.7071 15.7071C15.3166 16.0976 14.6834 16.0976 14.2929 15.7071L10 11.4142L5.70711 15.7071C5.31658 16.0976 4.68342 16.0976 4.29289 15.7071C3.90237 15.3166 3.90237 14.6834 4.29289 14.2929L8.58579 10L4.29289 5.70711C3.90237 5.31658 3.90237 4.68342 4.29289 4.29289Z" fill="currentColor"/>
                        </svg>
                    </a>
                @endif
            </div>

            <!-- Select Kategori -->
            <div class="relative">
                <select name="kategori" onchange="this.form.submit()" class="h-11 appearance-none text-xs sm:text-sm rounded-lg px-3.5 pr-8 border border-gray-300 outline-none transition cursor-pointer shadow-theme-xs {{ request('kategori') ? 'bg-emerald-50 border-emerald-300 text-emerald-700 font-bold' : 'bg-white text-gray-700' }}">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('kategori') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </span>
            </div>

            <!-- Select Status -->
            <div class="relative">
                <select name="status" onchange="this.form.submit()" class="h-11 appearance-none text-xs sm:text-sm rounded-lg px-3.5 pr-8 border border-gray-300 outline-none transition cursor-pointer shadow-theme-xs {{ request('status') ? 'bg-emerald-50 border-emerald-300 text-emerald-700 font-bold' : 'bg-white text-gray-700' }}">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </span>
            </div>

            <!-- Tombol Reset -->
            @if(request()->filled('q') || request()->filled('kategori') || request()->filled('status'))
                <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center gap-1.5 h-11 px-3 text-xs font-medium text-rose-600 hover:text-white bg-rose-50 hover:bg-rose-600 border border-rose-200 hover:border-rose-600 rounded-lg transition shadow-theme-xs" title="Reset Semua Filter">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.29289 4.29289C4.68342 3.90237 5.31658 3.90237 5.70711 4.29289L10 8.58579L14.2929 4.29289C14.6834 3.90237 15.3166 3.90237 15.7071 4.29289C16.0976 4.68342 16.0976 5.31658 15.7071 5.70711L11.4142 10L15.7071 14.2929C16.0976 14.6834 16.0976 15.3166 15.7071 15.7071C15.3166 16.0976 14.6834 16.0976 14.2929 15.7071L10 11.4142L5.70711 15.7071C5.31658 16.0976 4.68342 16.0976 4.29289 15.7071C3.90237 15.3166 3.90237 14.6834 4.29289 14.2929L8.58579 10L4.29289 5.70711C3.90237 5.31658 3.90237 4.68342 4.29289 4.29289Z" fill="currentColor"/>
                    </svg>
                    <span>Reset</span>
                </a>
            @endif
        </form>

        <div class="inline-flex items-center h-11 px-4 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-600 font-medium whitespace-nowrap self-end sm:self-center shadow-theme-xs">
            Total:&nbsp;<strong class="text-gray-900 font-bold">{{ $articles->total() }}</strong>&nbsp;Artikel
        </div>
    </div>

    <!-- Articles Table (TailAdmin BasicTableOne Format) -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full text-left">
                <thead class="bg-gray-50/70 text-gray-500 uppercase font-medium text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3.5 sm:px-6">Artikel & Thumbnail</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Penulis</th>
                        <th class="px-5 py-3.5">Views</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5 sm:px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($articles as $art)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-14 h-14 rounded-lg bg-gray-100 overflow-hidden shrink-0 border border-gray-200">
                                        <img src="{{ $art->image ?: 'https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?w=100' }}" alt="" class="w-full h-full object-cover">
                                    </div>
                                    <div class="max-w-md">
                                        <a href="{{ route('berita.show', $art->slug) }}" target="_blank" class="font-semibold text-gray-900 hover:text-emerald-700 text-sm transition block line-clamp-1">
                                            {{ $art->title }}
                                        </a>
                                        <p class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $art->excerpt }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $art->category }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs font-medium text-gray-800">
                                {{ $art->author }}
                            </td>
                            <td class="px-5 py-4 text-xs font-semibold text-gray-700">
                                {{ number_format($art->views) }}
                            </td>
                            <td class="px-5 py-4">
                                @if($art->status === 'published')
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-500">
                                {{ $art->published_at ? $art->published_at->format('d M Y') : $art->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 sm:px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('berita.show', $art->slug) }}" target="_blank" title="Lihat Artikel" class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 12.5C11.3807 12.5 12.5 11.3807 12.5 10C12.5 8.61929 11.3807 7.5 10 7.5C8.61929 7.5 7.5 8.61929 7.5 10C7.5 11.3807 8.61929 12.5 10 12.5Z" fill="currentColor"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.833252 10C1.94436 5.83333 5.55547 2.5 9.99992 2.5C14.4444 2.5 18.0555 5.83333 19.1666 10C18.0555 14.1667 14.4444 17.5 9.99992 17.5C5.55547 17.5 1.94436 14.1667 0.833252 10ZM14.1666 10C14.1666 12.3012 12.3011 14.1667 9.99992 14.1667C7.69873 14.1667 5.83325 12.3012 5.83325 10C5.83325 7.69882 7.69873 5.83333 9.99992 5.83333C12.3011 5.83333 14.1666 7.69882 14.1666 10Z" fill="currentColor"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.berita.edit', $art->id) }}" title="Edit Berita" class="p-2 text-gray-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.5858 2.58579C14.3668 1.80474 15.6332 1.80474 16.4142 2.58579L17.4142 3.58579C18.1953 4.36683 18.1953 5.63316 17.4142 6.41421L7.41421 16.4142C7.03914 16.7893 6.53043 17 6 17H3C2.44772 17 2 16.5523 2 16V13C2 12.4696 2.21071 11.9609 2.58579 11.5858L13.5858 2.58579ZM14.9999 4L15.9999 5L14.9999 6L13.9999 5L14.9999 4ZM12.5858 6.41421L4 15H3.5V14.5L12.0858 5.91421L12.5858 6.41421Z" fill="currentColor"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.berita.destroy', $art->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Berita" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.54118 3.7915C6.54118 2.54886 7.54854 1.5415 8.79118 1.5415H11.2078C12.4505 1.5415 13.4578 2.54886 13.4578 3.7915V4.0415H15.6249H16.6658C17.08 4.0415 17.4158 4.37729 17.4158 4.7915C17.4158 5.20572 17.08 5.5415 16.6658 5.5415H16.3749V8.24638V13.2464V16.2082C16.3749 17.4508 15.3676 18.4582 14.1249 18.4582H5.87492C4.63228 18.4582 3.62492 17.4508 3.62492 16.2082V13.2464V8.24638V5.5415H3.33325C2.91904 5.5415 2.58325 5.20572 2.58325 4.7915C2.58325 4.37729 2.91904 4.0415 3.33325 4.0415H4.37492H6.54118V3.7915ZM14.8749 13.2464V8.24638V5.5415H13.4578H12.7078H7.29118H6.54118H5.12492V8.24638V13.2464V16.2082C5.12492 16.6224 5.46071 16.9582 5.87492 16.9582H14.1249C14.5391 16.9582 14.8749 16.6224 14.8749 16.2082V13.2464ZM8.04118 4.0415H11.9578V3.7915C11.9578 3.37729 11.6221 3.0415 11.2078 3.0415H8.79118C8.37696 3.0415 8.04118 3.37729 8.04118 3.7915V4.0415ZM8.33325 7.99984C8.74747 7.99984 9.08325 8.33562 9.08325 8.74984V13.7498C9.08325 14.1641 8.74747 14.4998 8.33325 14.4998C7.91904 14.4998 7.58325 14.1641 7.58325 13.7498V8.74984C7.58325 8.33562 7.91904 7.99984 8.33325 7.99984ZM12.4166 8.74984C12.4166 8.33562 12.0808 7.99984 11.6666 7.99984C11.2524 7.99984 10.9166 8.33562 10.9166 8.74984V13.7498C10.9166 14.1641 11.2524 14.4998 11.6666 14.4998C12.0808 14.4998 12.4166 14.1641 12.4166 13.7498V8.74984Z" fill="currentColor"/>
                                            </svg>
                                        </button>
                                    </form>
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
                                <p class="text-sm font-semibold text-gray-700">Belum ada artikel warta yang cocok dengan filter atau pencarian.</p>
                                <p class="text-xs text-gray-400 mt-1">Coba gunakan kata kunci lain atau tulis berita baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                {{ $articles->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

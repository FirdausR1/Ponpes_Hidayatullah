@extends('admin.layout')

@section('title', 'Kelola Berita')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Kelola Warta & Berita</h1>
            <p class="text-sm text-slate-500">Daftar seluruh artikel, kajian, dan liputan kegiatan pesantren.</p>
        </div>
        <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tulis Berita Baru
        </a>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row gap-3 items-center justify-between">
        <form action="{{ route('admin.berita.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="relative flex-1 sm:w-64">
                <span class="text-slate-400 absolute left-3 top-2.5">
                    <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau penulis..." class="w-full pl-9 pr-3 py-1.5 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-brand-500 outline-none transition">
            </div>

            <select name="kategori" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('kategori') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </form>

        <span class="text-xs text-slate-500 self-end sm:self-center">
            Menampilkan <strong>{{ $articles->total() }}</strong> artikel
        </span>
    </div>

    <!-- Articles Table (TailAdmin Style) -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-xs tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Artikel & Thumbnail</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="px-4 py-3.5">Penulis</th>
                        <th class="px-4 py-3.5">Views</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($articles as $art)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-14 h-14 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                                        <img src="{{ $art->image ?: 'https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?w=100' }}" alt="" class="w-full h-full object-cover">
                                    </div>
                                    <div class="max-w-md">
                                        <a href="{{ route('berita.show', $art->slug) }}" target="_blank" class="font-bold text-slate-800 hover:text-brand-600 transition block line-clamp-1">
                                            {{ $art->title }}
                                        </a>
                                        <p class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ $art->excerpt }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-block px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">
                                    {{ $art->category }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs font-medium text-slate-700">
                                {{ $art->author }}
                            </td>
                            <td class="px-4 py-4 text-xs font-semibold text-slate-600">
                                {{ number_format($art->views) }}
                            </td>
                            <td class="px-4 py-4">
                                @if($art->status === 'published')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500">
                                {{ $art->published_at ? $art->published_at->format('d M Y') : $art->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('berita.show', $art->slug) }}" target="_blank" title="Lihat Artikel" class="p-1.5 text-slate-400 hover:text-slate-700 rounded hover:bg-slate-100">
                                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>
                                    <a href="{{ route('admin.berita.edit', $art->id) }}" title="Edit Berita" class="p-1.5 text-blue-600 hover:text-blue-800 rounded hover:bg-blue-50">
                                        <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.berita.destroy', $art->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Berita" class="p-1.5 text-rose-500 hover:text-rose-700 rounded hover:bg-rose-50">
                                            <svg class="icon-svg w-4 h-4" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                                Belum ada artikel yang cocok dengan filter atau pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $articles->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

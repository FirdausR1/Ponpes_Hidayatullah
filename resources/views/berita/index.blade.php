<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warta & Berita Santri — Pondok Pesantren Hidayatullah Tuksongo</title>
    <meta name="description" content="Kabar terkini, kegiatan dakwah, prestasi santri, kajian kitab kuning, dan informasi resmi Pondok Pesantren Hidayatullah Tuksongo Pringsurat Temanggung.">
    <link rel="icon" href="/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1a1c2b;
            background: #fafbfc;
            line-height: 1.6;
        }
        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; display: block; }

        :root {
            --green-950: #082412;
            --green-900: #0d3b1e;
            --green-800: #145a2e;
            --green-700: #1a6b38;
            --green-600: #1e7d42;
            --green-500: #228b4c;
            --green-200: #a3e4b8;
            --green-50: #edfbf2;
            --gold-500: #c8a415;
            --gold-400: #dbb930;
            --gold-300: #e8cc5a;
            --text-primary: #0f1a12;
            --text-secondary: #3d4f42;
            --text-muted: #637168;
            --border: #dfe6e0;
            --border-light: #eef2ef;
            --radius-xs: 4px;
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --gradient-primary: linear-gradient(135deg, #145a2e 0%, #228b4c 100%);
            --shadow-sm: 0 1px 3px rgba(13,59,30,0.06);
            --shadow-md: 0 4px 16px rgba(13,59,30,0.08);
            --shadow-lg: 0 8px 32px rgba(13,59,30,0.1);
        }

        .icon-svg {
            width: 18px; height: 18px;
            stroke: currentColor; stroke-width: 2;
            stroke-linecap: round; stroke-linejoin: round; fill: none;
            display: inline-block; vertical-align: middle; flex-shrink: 0;
        }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        @media (max-width: 768px) { .container { padding: 0 16px; } }

        /* NAVBAR */
        .navbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(255,255,255,0.96); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 12px 0;
        }
        .navbar-inner { display: flex; align-items: center; justify-content: space-between; }
        .navbar-brand { display: flex; align-items: center; gap: 12px; }
        .navbar-brand img { width: 42px; height: 42px; object-fit: contain; }
        .navbar-brand strong { font-family: 'EB Garamond', serif; font-size: 19px; color: var(--green-900); display: block; line-height: 1.1; }
        .navbar-brand span { font-size: 11px; color: var(--text-muted); }
        .nav-actions { display: flex; align-items: center; gap: 12px; }
        .btn-nav {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 600; color: #fff;
            background: var(--gradient-primary); padding: 8px 18px;
            border-radius: var(--radius-sm); transition: all 0.2s;
        }
        .btn-nav:hover { transform: translateY(-1px); box-shadow: var(--shadow-md); }
        .btn-link { font-size: 13px; font-weight: 500; color: var(--text-secondary); padding: 6px 12px; border-radius: var(--radius-xs); }
        .btn-link:hover { color: var(--green-800); background: var(--green-50); }

        /* HEADER BANNER */
        .news-header {
            background: linear-gradient(160deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
            padding: 48px 0 36px; border-bottom: 1px solid var(--border);
        }
        .breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); margin-bottom: 16px; }
        .breadcrumb a:hover { color: var(--green-800); }
        .page-title {
            font-family: 'EB Garamond', serif;
            font-size: clamp(32px, 4vw, 44px);
            font-weight: 600; color: var(--green-950);
            line-height: 1.2; margin-bottom: 10px;
        }
        .page-desc { font-size: 15px; color: #2b3d30; max-width: 600px; line-height: 1.7; }

        /* SEARCH & FILTER BAR */
        .filter-section {
            background: #fff; padding: 20px 0;
            border-bottom: 1px solid var(--border);
            position: sticky; top: 67px; z-index: 90;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .filter-inner {
            display: flex; align-items: center; justify-content: space-between;
            gap: 20px; flex-wrap: wrap;
        }
        .category-tabs { display: flex; align-items: center; gap: 6px; overflow-x: auto; padding-bottom: 4px; }
        .cat-tab {
            font-size: 12px; font-weight: 600; color: var(--text-secondary);
            padding: 6px 14px; border-radius: var(--radius-xs);
            background: var(--surface-dim); border: 1px solid var(--border);
            white-space: nowrap; transition: all 0.2s;
        }
        .cat-tab:hover { background: var(--green-50); border-color: var(--green-300); color: var(--green-800); }
        .cat-tab.active { background: var(--green-800); color: #fff; border-color: var(--green-800); }
        .search-box {
            display: flex; align-items: center; gap: 8px;
            background: var(--surface-dim); border: 1px solid var(--border);
            border-radius: var(--radius-sm); padding: 6px 14px;
            width: 100%; max-width: 320px;
        }
        .search-box input {
            border: none; background: transparent; outline: none;
            font-family: inherit; font-size: 13px; width: 100%; color: var(--text-primary);
        }

        /* FEATURED ARTICLE */
        .featured-card {
            margin: 36px 0 48px;
            background: #fff; border: 1px solid var(--border);
            border-radius: var(--radius-md); overflow: hidden;
            display: grid; grid-template-columns: 1.2fr 1fr;
            box-shadow: var(--shadow-sm); transition: all 0.3s;
        }
        .featured-card:hover { box-shadow: var(--shadow-lg); border-color: var(--green-300); }
        .featured-img { height: 100%; min-height: 280px; overflow: hidden; position: relative; }
        .featured-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .featured-card:hover .featured-img img { transform: scale(1.04); }
        .featured-body { padding: 36px; display: flex; flex-direction: column; justify-content: center; }
        .featured-tag {
            font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
            color: var(--green-700); background: var(--green-50);
            padding: 4px 10px; border-radius: var(--radius-xs);
            display: inline-block; width: fit-content; margin-bottom: 12px;
            border: 1px solid var(--green-200);
        }
        .featured-body h2 {
            font-family: 'EB Garamond', serif; font-size: 26px;
            color: var(--green-950); line-height: 1.3; margin-bottom: 12px;
        }
        .featured-body p { font-size: 14px; color: var(--text-muted); line-height: 1.7; margin-bottom: 20px; }
        .meta-row { display: flex; align-items: center; gap: 16px; font-size: 12px; color: var(--text-muted); }

        /* ARTICLE GRID */
        .article-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 24px; margin-bottom: 48px;
        }
        .news-card {
            background: #fff; border: 1px solid var(--border);
            border-radius: var(--radius-md); overflow: hidden;
            display: flex; flex-direction: column; transition: all 0.3s;
        }
        .news-card:hover { box-shadow: var(--shadow-md); transform: translateY(-4px); border-color: var(--green-200); }
        .news-card-img { height: 190px; overflow: hidden; position: relative; background: #f0f0f0; }
        .news-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
        .news-card:hover .news-card-img img { transform: scale(1.05); }
        .news-card-tag {
            position: absolute; top: 12px; left: 12px;
            font-size: 11px; font-weight: 600; color: var(--green-800);
            background: rgba(255,255,255,0.92); backdrop-filter: blur(8px);
            padding: 3px 10px; border-radius: var(--radius-xs);
        }
        .news-card-body { padding: 20px; flex: 1; display: flex; flex-direction: column; }
        .news-date { font-size: 11px; color: var(--text-muted); margin-bottom: 8px; display: flex; align-items: center; gap: 4px; }
        .news-card-body h3 {
            font-family: 'EB Garamond', serif; font-size: 19px;
            font-weight: 600; color: var(--text-primary);
            line-height: 1.35; margin-bottom: 8px;
        }
        .news-card-body h3 a:hover { color: var(--green-700); }
        .news-card-body p {
            font-size: 13px; color: var(--text-muted); line-height: 1.6;
            margin-bottom: 16px; flex: 1;
            display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
        }
        .news-card-footer {
            padding: 12px 20px; border-top: 1px solid var(--border-light);
            display: flex; align-items: center; justify-content: space-between;
            font-size: 12px;
        }
        .read-more { font-weight: 600; color: var(--green-700); display: inline-flex; align-items: center; gap: 4px; }
        .read-more:hover { color: var(--green-900); }
        .views-count { color: var(--text-muted); display: flex; align-items: center; gap: 4px; font-size: 11px; }

        /* PAGINATION */
        .pagination-wrap { display: flex; justify-content: center; margin: 40px 0 60px; }
        .pagination-wrap .pagination { display: flex; gap: 6px; }
        .pagination-wrap .page-item .page-link {
            padding: 8px 14px; border: 1px solid var(--border);
            border-radius: var(--radius-xs); background: #fff;
            color: var(--text-secondary); font-size: 13px; font-weight: 600;
        }
        .pagination-wrap .page-item.active .page-link {
            background: var(--green-800); color: #fff; border-color: var(--green-800);
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center; padding: 60px 20px; background: #fff;
            border: 1px dashed var(--border); border-radius: var(--radius-md);
            margin: 40px 0;
        }
        .empty-state h3 { font-family: 'EB Garamond', serif; font-size: 24px; color: var(--green-900); margin-bottom: 8px; }
        .empty-state p { font-size: 14px; color: var(--text-muted); margin-bottom: 16px; }

        /* FOOTER */
        .footer { background: var(--green-950); color: rgba(255,255,255,0.7); padding: 48px 0 24px; }
        .footer-inner { display: flex; align-items: center; justify-content: space-between; font-size: 13px; }
        @media (max-width: 900px) {
            .featured-card { grid-template-columns: 1fr; }
            .article-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .article-grid { grid-template-columns: 1fr; }
            .filter-inner { flex-direction: column; align-items: stretch; }
            .search-box { max-width: 100%; }
            .footer-inner { flex-direction: column; gap: 12px; text-align: center; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="container">
            <div class="navbar-inner">
                <a href="{{ route('home') }}" class="navbar-brand">
                    <img src="/logo.png" alt="Logo Pondok Hidayatullah">
                    <div>
                        <strong>Hidayatullah Tuksongo</strong>
                        <span>Pringsurat Temanggung</span>
                    </div>
                </a>
                <div class="nav-actions">
                    <a href="{{ route('home') }}" class="btn-link">← Beranda Utama</a>
                    <a href="{{ route('admin.dashboard') }}" class="btn-link" target="_blank">Admin Panel</a>
                    <a href="{{ route('home') }}#psb" class="btn-nav">
                        Pendaftaran Santri
                        <svg class="icon-svg" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- HEADER -->
    <section class="news-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span>›</span>
                <span>Warta & Berita Santri</span>
            </div>
            <h1 class="page-title">Warta Kampus & Informasi Pesantren</h1>
            <p class="page-desc">Dokumentasi kegiatan tholabul 'ilmi harian, syiar dakwah, prestasi perlombaan, kajian kitab turats, dan agenda resmi Pesantren Hidayatullah Tuksongo.</p>
        </div>
    </section>

    <!-- SEARCH & FILTER -->
    <section class="filter-section">
        <div class="container">
            <div class="filter-inner">
                <div class="category-tabs">
                    @foreach($categories as $cat)
                        <a href="{{ route('berita.index', array_merge(request()->except('page'), ['kategori' => $cat])) }}"
                           class="cat-tab {{ (request('kategori') == $cat || (!request('kategori') && $cat == 'Semua')) ? 'active' : '' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
                <form action="{{ route('berita.index') }}" method="GET" class="search-box">
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    <svg class="icon-svg" style="color: var(--text-muted);" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari berita atau kegiatan santri...">
                </form>
            </div>
        </div>
    </section>

    <main class="container">

        <!-- FEATURED ARTICLE -->
        @if($featured && !request('q') && (!request('kategori') || request('kategori') == 'Semua'))
            <article class="featured-card">
                <div class="featured-img">
                    <img src="{{ $featured->image ?: 'https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?w=800' }}" alt="{{ $featured->title }}">
                </div>
                <div class="featured-body">
                    <span class="featured-tag">★ Sorotan Utama • {{ $featured->category }}</span>
                    <h2><a href="{{ route('berita.show', $featured->slug) }}">{{ $featured->title }}</a></h2>
                    <p>{{ $featured->excerpt }}</p>
                    <div class="meta-row">
                        <span>
                            <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 15 15"></polyline></svg>
                            {{ $featured->published_at ? $featured->published_at->format('d M Y') : $featured->created_at->format('d M Y') }}
                        </span>
                        <span>•</span>
                        <span>{{ $featured->author }}</span>
                        <span>•</span>
                        <span>
                            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            {{ $featured->views }} dibaca
                        </span>
                    </div>
                </div>
            </article>
        @endif

        <!-- ARTICLE GRID -->
        @if($articles->count() > 0)
            <div class="article-grid">
                @foreach($articles as $article)
                    <article class="news-card">
                        <div class="news-card-img">
                            <img src="{{ $article->image ?: 'https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?w=600' }}" alt="{{ $article->title }}" loading="lazy">
                            <span class="news-card-tag">{{ $article->category }}</span>
                        </div>
                        <div class="news-card-body">
                            <div class="news-date">
                                <svg class="icon-svg" style="width:14px;height:14px;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 15 15"></polyline></svg>
                                {{ $article->published_at ? $article->published_at->diffForHumans() : $article->created_at->diffForHumans() }}
                            </div>
                            <h3><a href="{{ route('berita.show', $article->slug) }}">{{ $article->title }}</a></h3>
                            <p>{{ $article->excerpt }}</p>
                        </div>
                        <div class="news-card-footer">
                            <a href="{{ route('berita.show', $article->slug) }}" class="read-more">
                                Baca Lengkap
                                <svg class="icon-svg" style="width:14px;height:14px;" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </a>
                            <span class="views-count">
                                <svg class="icon-svg" style="width:14px;height:14px;" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                {{ $article->views }}
                            </span>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- PAGINATION -->
            <div class="pagination-wrap">
                {{ $articles->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg class="icon-svg" style="width:48px;height:48px;color:var(--green-500);margin-bottom:12px;" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <h3>Tidak Ada Berita Ditemukan</h3>
                <p>Belum ada artikel untuk kata kunci atau kategori yang Anda pilih.</p>
                <a href="{{ route('berita.index') }}" class="btn-nav">Lihat Semua Berita</a>
            </div>
        @endif

    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-inner">
                <div>
                    <strong>Pondok Pesantren Hidayatullah Tuksongo</strong>
                    <p style="font-size:12px;color:rgba(255,255,255,0.5);">Pringsurat, Temanggung, Jawa Tengah</p>
                </div>
                <div>
                    <a href="{{ route('home') }}" style="color:var(--gold-300);margin-right:16px;">Beranda Utama</a>
                    <a href="/NEW BUKU PANDUAN SANTRI TA 2025 (2).pdf" target="_blank" style="color:rgba(255,255,255,0.6);">Buku Panduan PDF</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

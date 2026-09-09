<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} — Pondok Pesantren Hidayatullah Tuksongo</title>
    <meta name="description" content="{{ $article->excerpt ?: Str::limit(strip_tags($article->content), 160) }}">
    <link rel="icon" href="/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1a1c2b;
            background: #fafbfc;
            line-height: 1.75;
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

        .container { max-width: 1100px; margin: 0 auto; padding: 0 24px; }
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

        /* BREADCRUMB */
        .breadcrumb-wrap { padding: 24px 0 12px; }
        .breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); flex-wrap: wrap; }
        .breadcrumb a:hover { color: var(--green-800); }
        .breadcrumb .current { color: var(--text-primary); font-weight: 600; }

        /* ARTICLE LAYOUT */
        .article-layout {
            display: grid; grid-template-columns: 1fr 320px;
            gap: 48px; padding: 24px 0 80px;
        }
        @media (max-width: 900px) {
            .article-layout { grid-template-columns: 1fr; }
        }

        /* ARTICLE HEADER */
        .article-header { margin-bottom: 28px; }
        .category-badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
            color: var(--green-800); background: var(--green-50);
            padding: 4px 12px; border-radius: var(--radius-xs);
            border: 1px solid var(--green-200); margin-bottom: 14px;
        }
        .article-title {
            font-family: 'EB Garamond', serif;
            font-size: clamp(28px, 3.8vw, 42px);
            font-weight: 600; color: var(--green-950);
            line-height: 1.25; margin-bottom: 18px;
        }
        .article-meta {
            display: flex; align-items: center; gap: 16px;
            font-size: 13px; color: var(--text-muted);
            padding-bottom: 18px; border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
        }
        .article-meta .meta-item { display: flex; align-items: center; gap: 6px; }

        /* FEATURED IMAGE */
        .featured-img-wrap {
            border-radius: var(--radius-md); overflow: hidden;
            margin-bottom: 32px; box-shadow: var(--shadow-md);
            border: 1px solid var(--border); background: #eee;
        }
        .featured-img-wrap img { width: 100%; max-height: 520px; object-fit: cover; }
        .img-caption {
            padding: 10px 16px; font-size: 12px; color: var(--text-muted);
            background: #fff; border-top: 1px solid var(--border-light); font-style: italic;
        }

        /* ARTICLE CONTENT */
        .article-content {
            font-size: 16px; color: #273b2d;
            line-height: 1.85; margin-bottom: 40px;
        }
        .article-content p { margin-bottom: 20px; }
        .article-content em { font-family: 'EB Garamond', serif; font-size: 18px; color: var(--green-900); }
        .article-content h2, .article-content h3 {
            font-family: 'EB Garamond', serif; color: var(--green-950);
            margin: 32px 0 14px; font-weight: 600;
        }
        .article-content h2 { font-size: 26px; }
        .article-content h3 { font-size: 22px; }
        .article-content blockquote {
            border-left: 3px solid var(--green-500);
            background: var(--green-50); padding: 18px 24px;
            margin: 24px 0; border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
            font-family: 'EB Garamond', serif; font-size: 19px; font-style: italic;
            color: var(--green-950);
        }

        /* SHARE BUTTONS */
        .share-box {
            background: var(--surface-dim); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 20px 24px;
            margin: 40px 0; display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 16px;
        }
        .share-box strong { font-size: 14px; color: var(--green-950); display: flex; align-items: center; gap: 8px; }
        .share-btns { display: flex; align-items: center; gap: 10px; }
        .btn-share {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; font-weight: 600; padding: 8px 14px;
            border-radius: var(--radius-xs); transition: all 0.2s;
        }
        .btn-share.wa { background: #25d366; color: #fff; }
        .btn-share.wa:hover { background: #1ebd5a; }
        .btn-share.fb { background: #1877f2; color: #fff; }
        .btn-share.fb:hover { background: #0c63d4; }
        .btn-share.copy { background: #fff; border: 1px solid var(--border); color: var(--text-primary); }
        .btn-share.copy:hover { background: var(--green-50); border-color: var(--green-300); }

        /* AUTHOR BOX */
        .author-box {
            background: #fff; border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 24px;
            display: flex; align-items: center; gap: 18px; margin-bottom: 40px;
        }
        .author-avatar {
            width: 54px; height: 54px; border-radius: var(--radius-sm);
            background: var(--green-50); border: 1px solid var(--green-200);
            display: flex; align-items: center; justify-content: center;
            color: var(--green-700); flex-shrink: 0;
        }
        .author-info strong { display: block; font-size: 15px; color: var(--green-950); margin-bottom: 2px; }
        .author-info p { font-size: 12px; color: var(--text-muted); line-height: 1.5; margin: 0; }

        /* SIDEBAR WIDGETS */
        .sidebar { display: flex; flex-direction: column; gap: 24px; }
        .widget-card {
            background: #fff; border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 24px; box-shadow: var(--shadow-sm);
        }
        .widget-title {
            font-family: 'EB Garamond', serif; font-size: 20px;
            font-weight: 600; color: var(--green-950);
            padding-bottom: 12px; margin-bottom: 16px;
            border-bottom: 2px solid var(--green-100);
            display: flex; align-items: center; gap: 8px;
        }
        .related-list { display: flex; flex-direction: column; gap: 16px; }
        .related-item { display: flex; gap: 12px; align-items: flex-start; }
        .related-thumb {
            width: 72px; height: 72px; border-radius: var(--radius-xs);
            overflow: hidden; flex-shrink: 0; background: #eee;
        }
        .related-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .related-content h4 {
            font-size: 13px; font-weight: 600; line-height: 1.4; margin-bottom: 4px;
        }
        .related-content h4 a:hover { color: var(--green-700); }
        .related-content span { font-size: 11px; color: var(--text-muted); }

        /* PSB CALLOUT IN SIDEBAR */
        .psb-callout {
            background: var(--gradient-primary); color: #fff;
            border-radius: var(--radius-md); padding: 28px 22px; text-align: center;
        }
        .psb-callout h4 {
            font-family: 'EB Garamond', serif; font-size: 22px;
            margin-bottom: 8px; line-height: 1.3;
        }
        .psb-callout p { font-size: 12px; color: rgba(255,255,255,0.8); line-height: 1.6; margin-bottom: 18px; }
        .btn-callout {
            display: inline-block; width: 100%; background: var(--gold-500);
            color: var(--green-950); font-weight: 700; font-size: 13px;
            padding: 10px; border-radius: var(--radius-xs); transition: all 0.2s;
        }
        .btn-callout:hover { background: var(--gold-400); }

        /* FOOTER */
        .footer { background: var(--green-950); color: rgba(255,255,255,0.7); padding: 48px 0 24px; }
        .footer-inner { display: flex; align-items: center; justify-content: space-between; font-size: 13px; }
        @media (max-width: 600px) {
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
                    <a href="{{ route('berita.index') }}" class="btn-link">← Semua Berita</a>
                    <a href="{{ route('home') }}" class="btn-link">Beranda Utama</a>
                    <a href="{{ route('home') }}#psb" class="btn-nav">
                        Pendaftaran Santri
                        <svg class="icon-svg" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- BREADCRUMB -->
        <div class="breadcrumb-wrap">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span>›</span>
                <a href="{{ route('berita.index') }}">Warta & Berita</a>
                <span>›</span>
                <span class="current">{{ Str::limit($article->title, 40) }}</span>
            </div>
        </div>

        <div class="article-layout">
            <!-- MAIN ARTICLE BODY -->
            <article>
                <header class="article-header">
                    <span class="category-badge">
                        <svg class="icon-svg" style="width:13px;height:13px;" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        {{ $article->category }}
                    </span>
                    <h1 class="article-title">{{ $article->title }}</h1>
                    <div class="article-meta">
                        <div class="meta-item">
                            <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 15 15"></polyline></svg>
                            <span>{{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : $article->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                        <span>•</span>
                        <div class="meta-item">
                            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span>{{ $article->author }}</span>
                        </div>
                        <span>•</span>
                        <div class="meta-item">
                            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <span>{{ $article->views }} kali dibaca</span>
                        </div>
                    </div>
                </header>

                @if($article->image)
                    <div class="featured-img-wrap">
                        <img src="{{ $article->image }}" alt="{{ $article->title }}">
                        <div class="img-caption">Dokumentasi: {{ $article->title }} — Pondok Pesantren Hidayatullah Tuksongo.</div>
                    </div>
                @endif

                <div class="article-content">
                    {!! $article->content !!}
                </div>

                <!-- SHARE BUTTONS -->
                <div class="share-box">
                    <strong>
                        <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                        Bagikan Kabar Baik Ini:
                    </strong>
                    <div class="share-btns">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' - ' . url()->current()) }}" target="_blank" class="btn-share wa">
                            WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn-share fb">
                            Facebook
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berita berhasil disalin!');" class="btn-share copy">
                            Salin Tautan
                        </button>
                    </div>
                </div>

                <!-- AUTHOR INFO -->
                <div class="author-box">
                    <div class="author-avatar">
                        <svg class="icon-svg" style="width:28px;height:28px;" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <div class="author-info">
                        <strong>{{ $article->author }}</strong>
                        <p>Redaksi Warta & Publikasi Dakwah Pondok Pesantren Hidayatullah Tuksongo, Pringsurat, Temanggung.</p>
                    </div>
                </div>
            </article>

            <!-- SIDEBAR -->
            <aside class="sidebar">
                <!-- RELATED ARTICLES WIDGET -->
                <div class="widget-card">
                    <h3 class="widget-title">
                        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                        Berita Terkait
                    </h3>
                    <div class="related-list">
                        @forelse($related as $rel)
                            <div class="related-item">
                                <div class="related-thumb">
                                    <img src="{{ $rel->image ?: 'https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?w=200' }}" alt="{{ $rel->title }}">
                                </div>
                                <div class="related-content">
                                    <h4><a href="{{ route('berita.show', $rel->slug) }}">{{ Str::limit($rel->title, 55) }}</a></h4>
                                    <span>{{ $rel->published_at ? $rel->published_at->format('d M Y') : $rel->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        @empty
                            <p style="font-size:12px;color:var(--text-muted);">Belum ada berita terkait lainnya.</p>
                        @endforelse
                    </div>
                </div>

                <!-- PSB CALLOUT -->
                <div class="psb-callout">
                    <span style="font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--gold-300);display:block;margin-bottom:6px;">PSB TA 2025/2026</span>
                    <h4>Daftarkan Putra-Putri Anda di Hidayatullah Tuksongo</h4>
                    <p>Pendidikan tahfidz 30 juz mutqin, kurikulum formal Kemenag MTs & MA, dan lingkungan asrama representatif.</p>
                    <a href="{{ route('home') }}#psb" class="btn-callout">Lihat Rincian Biaya & Syarat →</a>
                </div>

                <!-- PANDUAN SANTRI WIDGET -->
                <div class="widget-card" style="text-align:center;">
                    <div style="width:48px;height:48px;border-radius:var(--radius-sm);background:var(--green-50);border:1px solid var(--green-200);color:var(--green-700);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <svg class="icon-svg" style="width:24px;height:24px;" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    </div>
                    <h4 style="font-family:'EB Garamond',serif;font-size:18px;color:var(--green-950);margin-bottom:6px;">Buku Panduan Santri 2025</h4>
                    <p style="font-size:12px;color:var(--text-muted);margin-bottom:16px;">Unduh dokumen resmi 81 halaman format PDF.</p>
                    <a href="/NEW BUKU PANDUAN SANTRI TA 2025 (2).pdf" target="_blank" style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:600;color:var(--green-700);border:1px solid var(--green-300);padding:8px 16px;border-radius:var(--radius-xs);">
                        Unduh PDF (Resmi)
                    </a>
                </div>
            </aside>
        </div>
    </div>

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
                    <a href="{{ route('berita.index') }}" style="color:rgba(255,255,255,0.6);margin-right:16px;">Semua Berita</a>
                    <a href="/NEW BUKU PANDUAN SANTRI TA 2025 (2).pdf" target="_blank" style="color:rgba(255,255,255,0.6);">Buku Panduan PDF</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

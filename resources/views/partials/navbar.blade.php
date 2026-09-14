@php
    $isHome = request()->routeIs('home') || ($isLanding ?? false);
    $homeUrl = route('home');
@endphp

<!-- ===== SHARED OFFICIAL NAVBAR (GONTOR-INSPIRED DESIGN SYSTEM) ===== -->
<header class="official-navbar" id="siteNavbar">
    <div class="official-navbar-container">
        <div class="official-navbar-inner">
            
            <!-- BRAND IDENTITY -->
            <a href="{{ $homeUrl }}" class="official-brand" title="Pondok Pesantren Hidayatullah Tuksongo">
                <img src="/logo.png" alt="Logo Ponpes Hidayatullah" class="official-brand-crest">
                <div class="official-brand-text">
                    <img src="/logo1.png" alt="معهد هداية الله للتربية الإسلامية" class="official-brand-calligraphy">
                    <span class="official-brand-sub">Pondok Pesantren Hidayatullah Tuksongo</span>
                </div>
            </a>

            <!-- DESKTOP NAVIGATION MENU -->
            <nav class="official-nav-links" id="desktopNavLinks">
                
                <!-- 1. Beranda -->
                <a href="{{ $isHome ? '#beranda' : $homeUrl }}" class="official-nav-link {{ $isHome ? 'active' : '' }}">
                    Beranda
                </a>

                <!-- 2. Tentang Pesantren (Profile) -->
                <div class="official-nav-item">
                    <button type="button" class="official-nav-trigger" aria-haspopup="true" aria-expanded="false">
                        Tentang Pesantren
                        <svg class="official-chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="official-dropdown">
                        <a href="{{ $isHome ? '#profil' : $homeUrl . '#profil' }}" class="official-dropdown-item">
                            <div class="off-dd-icon">
                                <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            </div>
                            <div class="off-dd-text">
                                <strong>Profil & Sejarah</strong>
                                <span>Berdiri sejak 1999 di tanah wakaf Tuksongo</span>
                            </div>
                        </a>
                        <a href="{{ $isHome ? '#profil' : $homeUrl . '#profil' }}" class="official-dropdown-item">
                            <div class="off-dd-icon">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            </div>
                            <div class="off-dd-text">
                                <strong>Panca Jiwa & Nilai</strong>
                                <span>Keikhlasan, kesederhanaan & kemandirian</span>
                            </div>
                        </a>
                        <a href="{{ $isHome ? '#fasilitas' : $homeUrl . '#fasilitas' }}" class="official-dropdown-item">
                            <div class="off-dd-icon">
                                <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            </div>
                            <div class="off-dd-text">
                                <strong>Fasilitas Kampus</strong>
                                <span>Masjid jami', asrama sehat & laboratorium</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 3. Unit Pendidikan -->
                <div class="official-nav-item">
                    <button type="button" class="official-nav-trigger {{ request()->routeIs('biaya.*') ? 'active' : '' }}" aria-haspopup="true" aria-expanded="false">
                        Unit Pendidikan
                        <svg class="official-chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="official-dropdown">
                        <a href="{{ $isHome ? '#program' : $homeUrl . '#program' }}" class="official-dropdown-item">
                            <div class="off-dd-icon">
                                <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            </div>
                            <div class="off-dd-text">
                                <strong>MTs Hidayatullah</strong>
                                <span>Madrasah Tsanawiyah terakreditasi Kemenag</span>
                            </div>
                        </a>
                        <a href="{{ $isHome ? '#program' : $homeUrl . '#program' }}" class="official-dropdown-item">
                            <div class="off-dd-icon">
                                <svg viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                            </div>
                            <div class="off-dd-text">
                                <strong>MA Hidayatullah</strong>
                                <span>Madrasah Aliyah persiapan perguruan tinggi</span>
                            </div>
                        </a>
                        <a href="{{ route('biaya.index') }}" class="official-dropdown-item {{ request()->routeIs('biaya.*') ? 'selected-dd' : '' }}">
                            <div class="off-dd-icon" style="background:#e8f5ed; border-color:#228b4c; color:#0d3b1e;">
                                <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                            </div>
                            <div class="off-dd-text">
                                <strong style="color:#0d3b1e;">Rincian Biaya Pendidikan</strong>
                                <span>Transparansi biaya masuk & SPP bulanan</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 4. Warta & Berita -->
                <a href="{{ route('berita.index') }}" class="official-nav-link {{ request()->routeIs('berita.*') ? 'active' : '' }}">
                    Warta
                </a>

                <!-- 5. Pendaftaran -->
                <div class="official-nav-item">
                    <button type="button" class="official-nav-trigger {{ request()->routeIs('psb.*') ? 'active' : '' }}" aria-haspopup="true" aria-expanded="false">
                        Pendaftaran
                        <svg class="official-chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="official-dropdown official-dropdown-right">
                        <a href="{{ route('psb.register') }}" class="official-dropdown-item">
                            <div class="off-dd-icon" style="background:#e8f5ed; border-color:#228b4c; color:#0d3b1e;">
                                <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                            </div>
                            <div class="off-dd-text">
                                <strong style="color:#0d3b1e;">Formulir Pendaftaran Online</strong>
                                <span>Penerimaan Santri Baru TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}</span>
                            </div>
                        </a>
                        <a href="{{ route('psb.checkStatus') }}" class="official-dropdown-item">
                            <div class="off-dd-icon">
                                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </div>
                            <div class="off-dd-text">
                                <strong>Cek Status Verifikasi & Berkas</strong>
                                <span>Periksa hasil seleksi administrasi santri</span>
                            </div>
                        </a>
                        <a href="{{ route('ujian.index') }}" class="official-dropdown-item">
                            <div class="off-dd-icon">
                                <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                            </div>
                            <div class="off-dd-text">
                                <strong>Portal Ujian Seleksi (CBT)</strong>
                                <span>Tes seleksi online santri baru</span>
                            </div>
                        </a>
                        <a href="{{ route('biaya.index') }}" class="official-dropdown-item">
                            <div class="off-dd-icon">
                                <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                            </div>
                            <div class="off-dd-text">
                                <strong>Rincian Biaya Masuk & SPP</strong>
                                <span>Uang pangkal, seragam & syahriyah</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 6. Kontak & Alamat -->
                <a href="{{ $isHome ? '#kontak' : $homeUrl . '#kontak' }}" class="official-nav-link">
                    Kontak & Alamat
                </a>

                <!-- 7. LOGIN SANTRI BUTTON -->
                <a href="{{ route('santri.login') }}" class="official-nav-santri-btn" title="Masuk Portal Santri">
                    <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span>Portal Santri</span>
                </a>

                <!-- 8. SEARCH BUTTON (MAGNIFYING GLASS) -->
                <button type="button" class="official-nav-search-btn" id="officialSearchTrigger" aria-label="Buka Pencarian" title="Pencarian Berita & Informasi">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </nav>

            <!-- MOBILE HAMBURGER BUTTON -->
            <button type="button" class="official-mobile-btn" id="officialMobileBtn" aria-label="Menu Navigasi Mobile">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

<!-- MOBILE NAVIGATION DRAWER -->
<div class="official-mobile-nav" id="officialMobileNav">
    <div class="official-mobile-nav-header">
        <div class="official-brand">
            <img src="/logo.png" alt="Logo" class="official-brand-crest" style="width:32px; height:32px;">
            <div class="official-brand-text">
                <img src="/logo1.png" alt="Calligraphy" class="official-brand-calligraphy" style="height:20px;">
                <span class="official-brand-sub" style="font-size:8.5px;">Ponpes Hidayatullah</span>
            </div>
        </div>
        <button type="button" class="official-mobile-close" id="officialMobileClose" aria-label="Tutup Menu">✕</button>
    </div>

    <div class="official-mobile-body">
        <a href="{{ $isHome ? '#beranda' : $homeUrl }}" onclick="closeOfficialMobileNav()" class="official-mobile-link font-bold">
            Beranda
        </a>

        <div class="official-mobile-group">Tentang Pesantren</div>
        <div class="official-mobile-sub">
            <a href="{{ $isHome ? '#profil' : $homeUrl . '#profil' }}" onclick="closeOfficialMobileNav()">• Profil & Sejarah Pesantren</a>
            <a href="{{ $isHome ? '#profil' : $homeUrl . '#profil' }}" onclick="closeOfficialMobileNav()">• Panca Jiwa & Nilai Luhur</a>
            <a href="{{ $isHome ? '#fasilitas' : $homeUrl . '#fasilitas' }}" onclick="closeOfficialMobileNav()">• Fasilitas Kampus & Asrama</a>
        </div>

        <div class="official-mobile-group">Unit Pendidikan</div>
        <div class="official-mobile-sub">
            <a href="{{ $isHome ? '#program' : $homeUrl . '#program' }}" onclick="closeOfficialMobileNav()">• Madrasah Tsanawiyah (MTs)</a>
            <a href="{{ $isHome ? '#program' : $homeUrl . '#program' }}" onclick="closeOfficialMobileNav()">• Madrasah Aliyah (MA)</a>
            <a href="{{ route('biaya.index') }}" onclick="closeOfficialMobileNav()" style="color:#006837; font-weight:700;">• Rincian Biaya Pendidikan</a>
        </div>

        <div class="official-mobile-group">Warta & Berita</div>
        <div class="official-mobile-sub">
            <a href="{{ route('berita.index') }}" onclick="closeOfficialMobileNav()">• Warta Kabar Kegiatan Santri</a>
            <a href="{{ \App\Models\Setting::get('brosur_file_url', '/uploads/settings/brosur_1788852849.jpeg') }}" target="_blank" onclick="closeOfficialMobileNav()">• Unduh Brosur Resmi (PDF)</a>
        </div>

        <div class="official-mobile-group">Penerimaan Santri Baru (PSB)</div>
        <div class="official-mobile-sub">
            <a href="{{ route('psb.register') }}" onclick="closeOfficialMobileNav()" style="color:#006837; font-weight:700;">• Formulir Pendaftaran Online</a>
            <a href="{{ route('psb.checkStatus') }}" onclick="closeOfficialMobileNav()">• Cek Status Verifikasi & Berkas</a>
            <a href="{{ route('ujian.index') }}" onclick="closeOfficialMobileNav()">• Portal Ujian Masuk (CBT)</a>
        </div>

        <a href="{{ $isHome ? '#kontak' : $homeUrl . '#kontak' }}" onclick="closeOfficialMobileNav()" class="official-mobile-link">
            Kontak & Alamat
        </a>

        <a href="{{ route('santri.login') }}" onclick="closeOfficialMobileNav()" class="official-mobile-santri-link">
            <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;fill:none;flex-shrink:0;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>Portal Santri</span>
            <span class="official-badge-santri">Login</span>
        </a>

        <a href="{{ route('admin.dashboard') }}" target="_blank" onclick="closeOfficialMobileNav()" class="official-mobile-admin-link">
            <span>Masuk TailAdmin</span>
            <span class="official-badge-admin">Admin</span>
        </a>

        <a href="{{ route('psb.register') }}" onclick="closeOfficialMobileNav()" class="official-mobile-cta">
            Daftar Santri Baru TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}
        </a>
    </div>
</div>

<!-- SEARCH MODAL OVERLAY -->
<div class="official-search-overlay" id="officialSearchModal" role="dialog" aria-modal="true" aria-label="Kotak Pencarian">
    <div class="official-search-backdrop" id="officialSearchBackdrop"></div>
    <div class="official-search-card">
        <div class="official-search-card-header">
            <div class="official-search-title">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <span>Pencarian Resmi Pesantren</span>
            </div>
            <button type="button" class="official-search-close" id="officialSearchClose" aria-label="Tutup Pencarian">✕</button>
        </div>
        <form action="{{ route('berita.index') }}" method="GET" class="official-search-form">
            <input type="text" name="q" placeholder="Ketik kata kunci berita, kurikulum, atau info PSB..." class="official-search-input" autofocus autocomplete="off">
            <button type="submit" class="official-search-submit">Cari</button>
        </form>
        <div class="official-search-hints">
            <span>Pencarian populer:</span>
            <a href="{{ route('berita.index') }}?q=tahfidz">Tahfidz</a>
            <a href="{{ route('biaya.index') }}">Rincian Biaya</a>
            <a href="{{ route('psb.register') }}">Pendaftaran</a>
            <a href="{{ route('berita.index') }}?q=prestasi">Prestasi Santri</a>
        </div>
    </div>
</div>

<!-- STYLES FOR THE UNIFIED NAVBAR -->
<style>
    /* Google Fonts Guarantee */
    @import url('https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&family=Grenze:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    .official-navbar {
        position: sticky;
        top: 0;
        left: 0;
        width: 100%;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        z-index: 999;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    .official-navbar-container {
        max-width: 1360px;
        margin: 0 auto;
        padding: 0 34px;
    }

    .official-navbar-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 78px;
    }

    /* BRAND LOGO */
    .official-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        flex-shrink: 0;
    }

    .official-brand-crest {
        width: 38px;
        height: 38px;
        object-fit: contain;
        flex-shrink: 0;
        transition: transform 0.25s ease;
    }

    .official-brand:hover .official-brand-crest {
        transform: scale(1.05);
    }

    .official-brand-text {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        gap: 1.5px;
    }

    .official-brand-calligraphy {
        height: 25px;
        width: auto;
        max-width: 205px;
        object-fit: contain;
        object-position: left center;
        filter: contrast(1.12);
        transition: opacity 0.2s ease;
    }

    .official-brand:hover .official-brand-calligraphy {
        opacity: 0.85;
    }

    .official-brand-sub {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        font-size: 9px;
        font-weight: 600;
        color: #475569;
        letter-spacing: 0.2px;
        white-space: nowrap;
        line-height: 1.15;
    }

    .official-brand:hover .official-brand-sub {
        color: #006837;
    }

    /* DESKTOP NAV LINKS */
    .official-nav-links {
        display: flex;
        align-items: center;
        gap: 22px;
    }

    .official-nav-link,
    .official-nav-trigger {
        font-family: 'Grenze', Georgia, serif;
        font-size: 18px;
        font-weight: 500;
        color: #111111;
        text-decoration: none;
        background: transparent;
        border: none;
        padding: 8px 4px;
        cursor: pointer;
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: color 0.2s ease;
        letter-spacing: 0.45px;
        white-space: nowrap;
    }

    .official-nav-link:hover,
    .official-nav-trigger:hover,
    .official-nav-item:hover .official-nav-trigger {
        color: #006837;
    }

    .official-nav-link.active,
    .official-nav-trigger.active {
        color: #006837;
        font-weight: 600;
    }

    .official-nav-link.active::after,
    .official-nav-trigger.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2.5px;
        background-color: #006837;
        border-radius: 9999px;
    }

    /* CHEVRON ICON */
    .official-chevron {
        width: 12px;
        height: 12px;
        stroke: currentColor;
        stroke-width: 2.2;
        stroke-linecap: round;
        stroke-linejoin: round;
        fill: none;
        color: #64748b;
        transition: transform 0.25s ease, color 0.2s ease;
    }

    .official-nav-item:hover .official-chevron {
        transform: rotate(180deg);
        color: #006837;
    }

    /* SEARCH BUTTON */
    .official-nav-search-btn {
        background: transparent;
        border: none;
        color: #1e293b;
        cursor: pointer;
        padding: 6px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        margin-left: 2px;
    }

    .official-nav-search-btn svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        stroke-width: 2.2;
        stroke-linecap: round;
        stroke-linejoin: round;
        fill: none;
    }

    .official-nav-search-btn:hover {
        color: #006837;
        background: #f1f5f9;
        transform: scale(1.08);
    }

    /* DROPDOWNS */
    .official-nav-item {
        position: relative;
        display: inline-block;
    }

    .official-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 280px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px;
        box-shadow: 0 14px 35px rgba(0, 0, 0, 0.09);
        opacity: 0;
        visibility: hidden;
        transform: translateY(8px);
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
    }

    .official-dropdown-right {
        left: auto;
        right: 0;
    }

    .official-nav-item:hover .official-dropdown,
    .official-nav-item:focus-within .official-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(2px);
    }

    .official-dropdown-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 9px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: #1e293b;
        transition: all 0.2s ease;
    }

    .official-dropdown-item:hover {
        background: #f0fdf4;
    }

    .official-dropdown-item.selected-dd {
        background: #edfbf2;
        border-left: 3px solid #006837;
    }

    .off-dd-icon {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #006837;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
        transition: all 0.2s;
    }

    .off-dd-icon svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        fill: none;
    }

    .official-dropdown-item:hover .off-dd-icon {
        background: #006837;
        color: #ffffff;
        border-color: #006837;
    }

    .off-dd-text strong {
        display: block;
        font-family: 'Grenze', Georgia, serif;
        font-size: 16px;
        font-weight: 600;
        letter-spacing: 0.3px;
        color: #0f172a;
        line-height: 1.25;
    }

    .off-dd-text span {
        display: block;
        font-size: 11px;
        color: #64748b;
        margin-top: 2px;
        line-height: 1.35;
    }

    /* MOBILE BUTTON */
    .official-mobile-btn {
        display: none;
        flex-direction: column;
        gap: 5px;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 6px;
    }

    .official-mobile-btn span {
        display: block;
        width: 22px;
        height: 2px;
        background-color: #1e293b;
        border-radius: 2px;
        transition: all 0.25s ease;
    }

    @media (max-width: 1024px) {
        .official-nav-links {
            display: none !important;
        }
        .official-mobile-btn {
            display: flex !important;
        }
    }

    /* MOBILE DRAWER */
    .official-mobile-nav {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 310px;
        max-width: 85vw;
        background: #ffffff;
        box-shadow: -8px 0 30px rgba(0, 0, 0, 0.15);
        z-index: 1001;
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .official-mobile-nav.open {
        transform: translateX(0);
    }

    .official-mobile-nav-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .official-mobile-close {
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        color: #475569;
        cursor: pointer;
    }

    .official-mobile-body {
        padding: 20px;
        overflow-y: auto;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .official-mobile-link {
        font-family: 'Grenze', Georgia, serif;
        font-size: 18.5px;
        font-weight: 600;
        letter-spacing: 0.4px;
        color: #111111;
        text-decoration: none;
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .official-mobile-group {
        font-family: 'Grenze', Georgia, serif;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #006837;
        margin-top: 12px;
        margin-bottom: 2px;
    }

    .official-mobile-sub {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding-left: 6px;
    }

    .official-mobile-sub a {
        font-family: 'Grenze', Georgia, serif;
        font-size: 16px;
        font-weight: 500;
        letter-spacing: 0.2px;
        color: #334155;
        text-decoration: none;
        padding: 5px 0;
    }

    .official-mobile-admin-link {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px dashed #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600;
        color: #006837;
        text-decoration: none;
    }

    .official-badge-admin {
        font-size: 10px;
        padding: 2px 7px;
        border-radius: 4px;
        background: #e8f5ed;
        color: #006837;
        font-weight: 700;
    }

    /* PORTAL SANTRI BUTTON */
    .official-nav-santri-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        font-size: 12.5px;
        font-weight: 600;
        color: #006837;
        background: #edfbf2;
        border: 1.5px solid #a3e4b8;
        padding: 7px 14px;
        border-radius: 9999px;
        text-decoration: none;
        transition: all 0.22s ease;
        white-space: nowrap;
        letter-spacing: 0.1px;
    }

    .official-nav-santri-btn svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        fill: none;
        flex-shrink: 0;
    }

    .official-nav-santri-btn:hover {
        background: #006837;
        color: #ffffff;
        border-color: #006837;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 104, 55, 0.25);
    }

    /* MOBILE PORTAL SANTRI LINK */
    .official-mobile-santri-link {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px dashed #cbd5e1;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #006837;
        text-decoration: none;
    }

    .official-mobile-santri-link span:first-of-type {
        flex: 1;
    }

    .official-badge-santri {
        font-size: 10px;
        padding: 2px 7px;
        border-radius: 4px;
        background: #006837;
        color: #ffffff;
        font-weight: 700;
    }

    .official-mobile-cta {
        margin-top: 16px;
        display: block;
        text-align: center;
        background: #006837;
        color: #ffffff;
        font-weight: 600;
        font-size: 13.5px;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
    }

    /* SEARCH MODAL */
    .official-search-overlay {
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: none;
        align-items: flex-start;
        justify-content: center;
        padding-top: 90px;
    }

    .official-search-overlay.open {
        display: flex;
    }

    .official-search-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
    }

    .official-search-card {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 580px;
        margin: 0 16px;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.2);
        padding: 24px;
        animation: searchSlideIn 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes searchSlideIn {
        from { opacity: 0; transform: translateY(-16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .official-search-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .official-search-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .official-search-title svg {
        width: 16px;
        height: 16px;
        stroke: #006837;
        stroke-width: 2.2;
        fill: none;
    }

    .official-search-close {
        background: #f1f5f9;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: #64748b;
    }

    .official-search-form {
        display: flex;
        align-items: center;
        gap: 8px;
        border: 2px solid #006837;
        border-radius: 10px;
        padding: 4px;
        background: #ffffff;
    }

    .official-search-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 8px 12px;
        font-size: 14px;
        font-family: inherit;
        color: #0f172a;
    }

    .official-search-submit {
        background: #006837;
        color: #ffffff;
        border: none;
        padding: 8px 18px;
        border-radius: 7px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .official-search-submit:hover {
        background: #0d3b1e;
    }

    .official-search-hints {
        margin-top: 14px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #64748b;
    }

    .official-search-hints a {
        background: #f1f5f9;
        color: #334155;
        padding: 3px 10px;
        border-radius: 9999px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .official-search-hints a:hover {
        background: #e8f5ed;
        color: #006837;
    }
</style>

<!-- SCRIPT FOR UNIFIED NAVBAR -->
<script>
    function openOfficialMobileNav() {
        var el = document.getElementById('officialMobileNav');
        if (el) el.classList.add('open');
    }
    function closeOfficialMobileNav() {
        var el = document.getElementById('officialMobileNav');
        if (el) el.classList.remove('open');
    }

    document.addEventListener('DOMContentLoaded', function() {
        var btnOpen = document.getElementById('officialMobileBtn');
        var btnClose = document.getElementById('officialMobileClose');
        if (btnOpen) btnOpen.addEventListener('click', openOfficialMobileNav);
        if (btnClose) btnClose.addEventListener('click', closeOfficialMobileNav);

        // Search modal toggle
        var searchTrigger = document.getElementById('officialSearchTrigger');
        var searchModal = document.getElementById('officialSearchModal');
        var searchBackdrop = document.getElementById('officialSearchBackdrop');
        var searchClose = document.getElementById('officialSearchClose');

        function openSearch() {
            if (searchModal) {
                searchModal.classList.add('open');
                var inp = searchModal.querySelector('input');
                if (inp) setTimeout(function() { inp.focus(); }, 50);
            }
        }
        function closeSearch() {
            if (searchModal) searchModal.classList.remove('open');
        }

        if (searchTrigger) searchTrigger.addEventListener('click', openSearch);
        if (searchBackdrop) searchBackdrop.addEventListener('click', closeSearch);
        if (searchClose) searchClose.addEventListener('click', closeSearch);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSearch();
                closeOfficialMobileNav();
            }
        });
    });
</script>

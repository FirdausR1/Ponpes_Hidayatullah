<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Pesantren Hidayatullah Tuksongo — Pringsurat Temanggung</title>
    <meta name="description"
        content="Website Resmi Pondok Pesantren Hidayatullah Tuksongo, Pringsurat, Temanggung. Memadukan kurikulum Kemenag (MTs-MA) dan tradisi kepesantrenan modern, tahfidz bersanad, bahasa Arab-Inggris aktif.">
    <link rel="icon" href="/logo.png" type="image/png">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap"
        rel="stylesheet">
    <style>
        /* ===== CSS RESET & BASE ===== */
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1a1c2b;
            background: #fafbfc;
            overflow-x: hidden;
            line-height: 1.6;
        }

        img {
            max-width: 100%;
            display: block;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul,
        ol {
            list-style: none;
        }

        /* ===== COLOR VARIABLES ===== */
        :root {
            --green-950: #082412;
            --green-900: #0d3b1e;
            --green-800: #145a2e;
            --green-700: #1a6b38;
            --green-600: #1e7d42;
            --green-500: #228b4c;
            --green-400: #34a85e;
            --green-300: #5cc87c;
            --green-200: #a3e4b8;
            --green-100: #d4f5de;
            --green-50: #edfbf2;
            --gold-600: #b5920d;
            --gold-500: #c8a415;
            --gold-400: #dbb930;
            --gold-300: #e8cc5a;
            --gold-50: #fefce8;
            --text-primary: #0f1a12;
            --text-secondary: #3d4f42;
            --text-muted: #637168;
            --surface: #ffffff;
            --surface-dim: #f3f5f4;
            --border: #dfe6e0;
            --border-light: #eef2ef;
            --gradient-primary: linear-gradient(135deg, #145a2e 0%, #228b4c 100%);
            --gradient-emerald: linear-gradient(135deg, #0d3b1e 0%, #1a6b38 100%);
            --gradient-gold: linear-gradient(135deg, #c8a415 0%, #e8cc5a 100%);
            --gradient-hero: linear-gradient(160deg, #f0fdf4 0%, #dcfce7 40%, #bbf7d0 100%);
            --gradient-subtle: linear-gradient(180deg, #edfbf2 0%, #fafbfc 100%);
            --shadow-sm: 0 1px 3px rgba(13, 59, 30, 0.06);
            --shadow-md: 0 4px 16px rgba(13, 59, 30, 0.08);
            --shadow-lg: 0 8px 32px rgba(13, 59, 30, 0.1);
            --shadow-xl: 0 16px 48px rgba(13, 59, 30, 0.12);
            --radius-xs: 4px;
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
        }

        /* ===== OUTLINE SVG ICONS ===== */
        .icon-svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            display: inline-block;
            vertical-align: middle;
            flex-shrink: 0;
        }

        .icon-svg-xs {
            width: 14px;
            height: 14px;
            stroke-width: 2;
        }

        .icon-svg-sm {
            width: 16px;
            height: 16px;
            stroke-width: 2;
        }

        .icon-svg-md {
            width: 22px;
            height: 22px;
            stroke-width: 1.8;
        }

        .icon-svg-lg {
            width: 28px;
            height: 28px;
            stroke-width: 1.8;
        }

        .icon-svg-xl {
            width: 36px;
            height: 36px;
            stroke-width: 1.7;
        }

        /* ===== UTILITY ===== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 16px;
            }
        }

        .section-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--green-600);
            margin-bottom: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .section-title {
            font-family: 'EB Garamond', serif;
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .section-desc {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 600px;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            padding: 12px 0;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 2px 14px rgba(13, 59, 30, 0.08);
            border-bottom: 1px solid rgba(223, 230, 224, 0.9);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 16px;
            color: var(--text-primary);
        }

        .navbar-brand img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .navbar-brand-text strong {
            display: block;
            font-family: 'EB Garamond', serif;
            font-size: 19px;
            color: var(--green-900);
            line-height: 1.1;
        }

        .navbar-brand-text span {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-muted);
            letter-spacing: 0.04em;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-links>a,
        .nav-dropdown-trigger {
            font-size: 13px;
            font-weight: 500;
            color: #273b2d;
            padding: 7px 12px;
            border-radius: var(--radius-xs);
            transition: all 0.2s;
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            background: none;
            border: none;
            font-family: inherit;
            text-decoration: none;
        }

        .nav-links>a:hover,
        .nav-dropdown-trigger:hover,
        .nav-item-dropdown:hover .nav-dropdown-trigger {
            color: var(--green-800);
            background: var(--green-50);
        }

        .nav-links>a.active,
        .nav-dropdown-trigger.active {
            color: var(--green-800);
            font-weight: 600;
            background: var(--green-50);
        }

        .nav-dropdown-trigger .chevron-icon {
            transition: transform 0.25s ease;
            color: #556b5c;
        }

        .nav-item-dropdown:hover .chevron-icon {
            transform: rotate(180deg);
            color: var(--green-700);
        }

        /* DROPDOWN MENU */
        .nav-item-dropdown {
            position: relative;
            display: inline-block;
        }

        .nav-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 270px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 8px;
            box-shadow: 0 12px 36px rgba(13, 59, 30, 0.13);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 120;
        }

        .nav-item-dropdown:hover .nav-dropdown-menu,
        .nav-item-dropdown:focus-within .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(4px);
        }

        .nav-dropdown-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 12px;
            border-radius: var(--radius-xs);
            text-decoration: none;
            transition: all 0.2s;
            color: var(--text-primary);
        }

        .nav-dropdown-item:hover {
            background: var(--green-50);
        }

        .nav-dropdown-item .dd-icon {
            width: 30px;
            height: 30px;
            border-radius: var(--radius-xs);
            background: #fff;
            border: 1px solid var(--green-200);
            color: var(--green-700);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
            transition: all 0.2s;
        }

        .nav-dropdown-item:hover .dd-icon {
            background: var(--green-700);
            color: #fff;
            border-color: var(--green-700);
        }

        .nav-dropdown-item .dd-text strong {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--green-950);
            line-height: 1.3;
        }

        .nav-dropdown-item .dd-text span {
            display: block;
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.4;
            margin-top: 2px;
        }

        /* MOBILE NAV GROUPING */
        .mobile-group-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--green-700);
            padding: 12px 0 4px;
            border-bottom: 1px solid var(--border-light);
        }

        .mobile-sublinks {
            display: flex;
            flex-direction: column;
            padding-left: 8px;
        }

        .mobile-sublinks a {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            padding: 8px 0;
            border-bottom: 1px dashed var(--border-light);
        }

        .nav-cta-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            background: var(--gradient-primary);
            padding: 8px 18px;
            border-radius: var(--radius-sm);
            box-shadow: 0 2px 8px rgba(13, 59, 30, 0.22);
            transition: all 0.25s;
        }

        .nav-cta:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(13, 59, 30, 0.28);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: 1px solid var(--border);
            padding: 8px 10px;
            border-radius: var(--radius-xs);
            cursor: pointer;
            flex-direction: column;
            gap: 4px;
        }

        .mobile-menu-btn span {
            width: 20px;
            height: 2px;
            background: var(--green-800);
            display: block;
            transition: 0.3s;
        }

        /* ===== MOBILE NAV ===== */
        .mobile-nav {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.98);
            z-index: 200;
            flex-direction: column;
            padding: 80px 32px 32px;
            gap: 12px;
            backdrop-filter: blur(16px);
            overflow-y: auto;
        }

        .mobile-nav.open {
            display: flex;
        }

        .mobile-nav a {
            font-size: 17px;
            font-weight: 600;
            color: var(--text-primary);
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mobile-nav-close {
            position: absolute;
            top: 20px;
            right: 24px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--text-primary);
        }

        /* ===== HERO SECTION ===== */
        .hero {
            padding: 140px 0 80px;
            position: relative;
            overflow: hidden;
            background: var(--gradient-hero);
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: center;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid var(--green-300);
            padding: 5px 14px;
            border-radius: var(--radius-xs);
            font-size: 12px;
            font-weight: 600;
            color: var(--green-800);
            margin-bottom: 20px;
            box-shadow: 0 1px 4px rgba(13, 59, 30, 0.06);
        }

        .hero-badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--green-500);
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.6);
                opacity: 0.5;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .hero-title {
            font-family: 'EB Garamond', serif;
            font-size: clamp(34px, 4.2vw, 48px);
            font-weight: 600;
            color: var(--green-950);
            line-height: 1.16;
            margin-bottom: 18px;
            letter-spacing: -0.02em;
        }

        .hero-title em {
            font-style: italic;
            color: var(--green-700);
            position: relative;
        }

        .hero-subtitle {
            font-size: 15px;
            color: #2b3d30;
            line-height: 1.75;
            margin-bottom: 30px;
            max-width: 520px;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: var(--gradient-primary);
            padding: 13px 26px;
            border-radius: var(--radius-sm);
            box-shadow: 0 4px 14px rgba(13, 59, 30, 0.25);
            transition: all 0.25s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 59, 30, 0.32);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--green-800);
            background: #fff;
            border: 1px solid var(--green-300);
            padding: 12px 24px;
            border-radius: var(--radius-sm);
            transition: all 0.25s;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .btn-secondary:hover {
            background: var(--green-50);
            border-color: var(--green-500);
            transform: translateY(-2px);
        }

        .hero-visual {
            position: relative;
        }

        .hero-img-wrap {
            position: relative;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.8);
            aspect-ratio: 4/3;
            background: var(--surface-dim);
        }

        .hero-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-img-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 24px;
            background: linear-gradient(to top, rgba(8, 36, 18, 0.92) 0%, rgba(8, 36, 18, 0.4) 70%, transparent 100%);
            color: #fff;
        }

        .hero-img-overlay-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .badge-verified {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-xs);
            background: var(--green-500);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            flex-shrink: 0;
        }

        .hero-img-overlay-content strong {
            display: block;
            font-size: 14px;
            font-weight: 600;
        }

        .hero-img-overlay-content small {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
        }

        /* ===== STATS BAR ===== */
        .stats-bar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 0;
            box-shadow: var(--shadow-sm);
            position: relative;
            z-index: 5;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }

        .stat-item {
            padding: 24px 20px;
            text-align: center;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-item .stat-icon {
            color: var(--green-600);
            margin-bottom: 4px;
        }

        .stat-value {
            font-family: 'EB Garamond', serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--green-900);
            line-height: 1.1;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-item {
                padding: 18px 12px;
            }

            .stat-item:nth-child(2) {
                border-right: none;
            }

            .stat-item:nth-child(1),
            .stat-item:nth-child(2) {
                border-bottom: 1px solid var(--border);
            }
        }

        /* ===== BERITA SECTION (FIRST SECTION AFTER STATS) ===== */
        .section-berita {
            padding: 72px 0;
            background: #fff;
        }

        .berita-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 36px;
            gap: 24px;
        }

        .berita-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .berita-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            min-height: 420px;
            height: 420px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            box-shadow: 0 10px 28px rgba(8, 36, 18, 0.16);
            border: 1px solid rgba(8, 36, 18, 0.12);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease, border-color 0.4s ease;
            background: #082412;
            text-decoration: none;
        }

        .berita-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 42px rgba(8, 36, 18, 0.35);
            border-color: rgba(200, 164, 21, 0.45);
        }

        .berita-card-bg-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 0;
        }

        .berita-card:hover .berita-card-bg-img {
            transform: scale(1.08);
        }

        .berita-card-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(8, 36, 18, 0.10) 0%, rgba(8, 36, 18, 0.40) 40%, rgba(6, 30, 15, 0.88) 72%, #051a0d 100%);
            z-index: 1;
            transition: background 0.4s ease;
        }

        .berita-card:hover .berita-card-gradient {
            background: linear-gradient(180deg, rgba(8, 36, 18, 0.05) 0%, rgba(8, 36, 18, 0.50) 35%, rgba(6, 30, 15, 0.95) 70%, #03140a 100%);
        }

        .berita-card-top {
            position: absolute;
            top: 18px;
            left: 18px;
            right: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2;
        }

        .berita-card-tag {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            color: #082412;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.03em;
            padding: 5px 14px;
            border-radius: 9999px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .berita-card-content {
            position: relative;
            z-index: 2;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .berita-card-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
        }

        .berita-card-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .berita-card-title {
            font-family: 'EB Garamond', serif;
            font-size: 21px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.35;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .berita-card-excerpt {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.55;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .berita-card-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: var(--gold-300);
            margin-top: 4px;
            transition: gap 0.2s ease, color 0.2s ease;
        }

        .berita-card:hover .berita-card-action {
            gap: 9px;
            color: #ffffff;
        }

        /* ===== PROFIL & SEJARAH (FROM BUKU PANDUAN) ===== */
        .section-profil {
            padding: 80px 0;
            background: var(--surface-dim);
        }

        .profil-header {
            text-align: center;
            margin-bottom: 44px;
        }

        .profil-header .section-desc {
            margin: 10px auto 0;
        }

        .profil-lead-card {
            background: #fff;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            padding: 36px;
            margin-bottom: 32px;
            box-shadow: var(--shadow-sm);
        }

        .profil-lead-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 36px;
            align-items: center;
        }

        .profil-sejarah h3 {
            font-family: 'EB Garamond', serif;
            font-size: 24px;
            color: var(--green-900);
            margin-bottom: 12px;
        }

        .profil-sejarah p {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.75;
            margin-bottom: 14px;
        }

        .legalitas-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 16px;
        }

        .legalitas-chip {
            font-size: 11px;
            font-weight: 600;
            color: var(--green-800);
            background: var(--green-50);
            border: 1px solid var(--green-200);
            padding: 4px 10px;
            border-radius: var(--radius-xs);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .visi-misi-box {
            background: var(--gradient-subtle);
            border: 1px solid var(--green-200);
            border-radius: var(--radius-md);
            padding: 24px;
        }

        .visi-misi-box .visi-tag {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--gold-600);
            margin-bottom: 6px;
        }

        .visi-misi-box .visi-quote {
            font-family: 'EB Garamond', serif;
            font-size: 20px;
            font-weight: 600;
            color: var(--green-900);
            line-height: 1.3;
            margin-bottom: 16px;
            font-style: italic;
        }

        .misi-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .misi-list li {
            font-size: 12px;
            color: var(--text-secondary);
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.5;
        }

        .misi-list li .bullet {
            color: var(--green-500);
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* PANCA JIWA */
        .panca-title-wrap {
            margin: 40px 0 20px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
        }

        .panca-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 40px;
        }

        .panca-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 22px 18px;
            transition: all 0.3s;
            position: relative;
        }

        .panca-card:hover {
            border-color: var(--green-400);
            box-shadow: var(--shadow-md);
            transform: translateY(-3px);
        }

        .panca-num {
            font-family: 'EB Garamond', serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--green-300);
            margin-bottom: 6px;
        }

        .panca-card h4 {
            font-size: 15px;
            font-weight: 700;
            color: var(--green-900);
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .panca-card p {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* LOGO FILOSOFI */
        .logo-filosofi-box {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 32px;
        }

        .logo-filosofi-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .logo-filosofi-header img {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }

        .logo-filosofi-header h3 {
            font-family: 'EB Garamond', serif;
            font-size: 22px;
            color: var(--green-900);
        }

        .logo-filosofi-header p {
            font-size: 13px;
            color: var(--text-muted);
        }

        .filosofi-items-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .filosofi-item {
            padding: 14px;
            border-radius: var(--radius-xs);
            background: var(--surface-dim);
            border: 1px solid var(--border-light);
        }

        .filosofi-item strong {
            display: block;
            font-size: 12px;
            color: var(--green-800);
            margin-bottom: 3px;
        }

        .filosofi-item span {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.4;
            display: block;
        }

        /* ===== PILAR PENDIDIKAN SECTION ===== */
        .section-pilar {
            padding: 80px 0;
            background: #fff;
        }

        .pilar-header {
            text-align: center;
            margin-bottom: 44px;
        }

        .pilar-header .section-desc {
            margin: 10px auto 0;
        }

        .pilar-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .pilar-card {
            background: #fff;
            padding: 26px 20px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .pilar-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .pilar-card:hover {
            border-color: var(--green-200);
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
        }

        .pilar-card:hover::before {
            opacity: 1;
        }

        .pilar-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            background: var(--green-50);
            color: var(--green-700);
            border: 1px solid var(--green-100);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .pilar-card h3 {
            font-family: 'EB Garamond', serif;
            font-size: 19px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .pilar-card p {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.65;
        }

        .pilar-tag {
            display: inline-block;
            margin-top: 14px;
            font-size: 11px;
            font-weight: 600;
            color: var(--green-600);
            letter-spacing: 0.04em;
        }

        /* ===== KEHIDUPAN & JADWAL SANTRI 24 JAM (FROM PDF) ===== */
        .section-kehidupan {
            padding: 80px 0;
            background: var(--surface-dim);
        }

        .kehidupan-header {
            text-align: center;
            margin-bottom: 44px;
        }

        .kehidupan-header .section-desc {
            margin: 10px auto 0;
        }

        .kehidupan-layout {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 28px;
        }

        .timeline-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 32px;
            box-shadow: var(--shadow-sm);
        }

        .timeline-card h3 {
            font-family: 'EB Garamond', serif;
            font-size: 22px;
            color: var(--green-900);
            margin-bottom: 4px;
        }

        .timeline-card .subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 24px;
            display: block;
        }

        .timeline-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            position: relative;
        }

        .timeline-list::before {
            content: '';
            position: absolute;
            left: 17px;
            top: 12px;
            bottom: 12px;
            width: 2px;
            background: var(--green-100);
        }

        .timeline-entry {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            position: relative;
            z-index: 2;
        }

        .timeline-bullet {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-xs);
            background: #fff;
            border: 2px solid var(--green-400);
            color: var(--green-700);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .timeline-detail {
            flex: 1;
            padding-top: 2px;
        }

        .timeline-time {
            font-size: 11px;
            font-weight: 700;
            color: var(--green-700);
            letter-spacing: 0.04em;
        }

        .timeline-desc {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-top: 1px;
        }

        .timeline-sub {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* AGENDA MINGGUAN */
        .agenda-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 32px;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .agenda-card h3 {
            font-family: 'EB Garamond', serif;
            font-size: 22px;
            color: var(--green-900);
        }

        .agenda-items {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .agenda-item {
            padding: 14px;
            border-radius: var(--radius-sm);
            background: var(--surface-dim);
            border-left: 3px solid var(--green-500);
        }

        .agenda-day {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--green-700);
            margin-bottom: 2px;
        }

        .agenda-activity {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .agenda-note {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* ===== TATA TERTIB & DISIPLIN (FROM PDF) ===== */
        .section-tertib {
            padding: 80px 0;
            background: #fff;
        }

        .tertib-header {
            text-align: center;
            margin-bottom: 44px;
        }

        .tertib-header .section-desc {
            margin: 10px auto 0;
        }

        .tertib-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .tertib-card {
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 24px 20px;
            background: #fff;
            transition: all 0.3s;
        }

        .tertib-card:hover {
            border-color: var(--green-300);
            box-shadow: var(--shadow-md);
            transform: translateY(-3px);
        }

        .tertib-icon {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-sm);
            background: var(--green-50);
            color: var(--green-700);
            border: 1px solid var(--green-200);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .tertib-card h3 {
            font-family: 'EB Garamond', serif;
            font-size: 19px;
            font-weight: 600;
            color: var(--green-900);
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .tertib-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .tertib-list li {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.55;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .tertib-list li .t-icon {
            color: var(--green-500);
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* ===== QUOTE BANNER ===== */
        .section-quote {
            padding: 48px 0;
        }

        .quote-banner {
            background: var(--gradient-emerald);
            border-radius: var(--radius-md);
            padding: 40px 48px;
            display: flex;
            align-items: center;
            gap: 36px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .quote-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(200, 164, 21, 0.12) 0%, transparent 70%);
            border-radius: 50%;
        }

        .quote-content {
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .quote-content .label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--gold-300);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .quote-content blockquote {
            font-family: 'EB Garamond', serif;
            font-size: clamp(18px, 2.5vw, 26px);
            font-style: italic;
            color: rgba(255, 255, 255, 0.95);
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .quote-content .quote-ref {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
        }

        .quote-icon {
            flex-shrink: 0;
            width: 72px;
            height: 72px;
            border-radius: var(--radius-md);
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-300);
            position: relative;
            z-index: 2;
        }

        @media (max-width: 768px) {
            .quote-banner {
                flex-direction: column;
                padding: 28px 20px;
                text-align: center;
            }

            .quote-icon {
                display: none;
            }
        }

        /* ===== FASILITAS ===== */
        .section-fasilitas {
            padding: 80px 0;
            background: var(--surface-dim);
        }

        .fasilitas-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 36px;
            gap: 24px;
        }

        .fasilitas-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .fasilitas-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            min-height: 380px;
            height: 380px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            box-shadow: 0 10px 28px rgba(8, 36, 18, 0.16);
            border: 1px solid rgba(8, 36, 18, 0.12);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease, border-color 0.4s ease;
            background: #082412;
            text-decoration: none;
        }

        .fasilitas-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 42px rgba(8, 36, 18, 0.35);
            border-color: rgba(200, 164, 21, 0.45);
        }

        .fasilitas-card-bg-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 0;
        }

        .fasilitas-card:hover .fasilitas-card-bg-img {
            transform: scale(1.08);
        }

        .fasilitas-card-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(8, 36, 18, 0.10) 0%, rgba(8, 36, 18, 0.40) 38%, rgba(6, 30, 15, 0.88) 72%, #051a0d 100%);
            z-index: 1;
            transition: background 0.4s ease;
        }

        .fasilitas-card:hover .fasilitas-card-gradient {
            background: linear-gradient(180deg, rgba(8, 36, 18, 0.05) 0%, rgba(8, 36, 18, 0.50) 32%, rgba(6, 30, 15, 0.95) 68%, #03140a 100%);
        }

        .fasilitas-card-top {
            position: absolute;
            top: 18px;
            left: 18px;
            right: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2;
        }

        .fasilitas-card-tag {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            color: #082412;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.03em;
            padding: 5px 14px;
            border-radius: 9999px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .fasilitas-card-content {
            position: relative;
            z-index: 2;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .fasilitas-card-title {
            font-family: 'EB Garamond', serif;
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.3;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.35);
        }

        .fasilitas-card-desc {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.55;
            margin: 0;
        }

        /* ===== PSB & BIAYA PENDIDIKAN TA 2025/2026 (FROM PDF) ===== */
        .section-psb {
            padding: 80px 0;
            background: #fff;
        }

        .psb-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .psb-header .section-desc {
            margin: 10px auto 0;
        }

        .psb-steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .psb-step {
            text-align: center;
            background: var(--surface-dim);
            padding: 24px 18px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
        }

        .psb-step-num {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-xs);
            background: var(--gradient-primary);
            color: #fff;
            font-family: 'EB Garamond', serif;
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }

        .psb-step h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .psb-step p {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .psb-step .period {
            font-size: 11px;
            font-weight: 600;
            color: var(--green-600);
            margin-top: 6px;
            display: inline-block;
        }

        /* TABEL BIAYA RESMI */
        .biaya-section-wrap {
            margin-top: 36px;
            background: var(--surface-dim);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            padding: 32px;
        }

        .biaya-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 20px;
        }

        .biaya-header h3 {
            font-family: 'EB Garamond', serif;
            font-size: 24px;
            color: var(--green-900);
        }

        .biaya-table-responsive {
            overflow-x: auto;
            border-radius: var(--radius-sm);
            background: #fff;
            border: 1px solid var(--border);
            margin-bottom: 24px;
        }

        .biaya-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }

        .biaya-table th {
            background: var(--green-900);
            color: #fff;
            padding: 12px 16px;
            font-weight: 600;
            white-space: nowrap;
        }

        .biaya-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-secondary);
        }

        .biaya-table tr:hover td {
            background: var(--green-50);
        }

        .biaya-table tr.total-row td {
            font-weight: 700;
            color: var(--green-900);
            background: var(--green-50);
            border-top: 2px solid var(--green-300);
        }

        /* REKENING & PERSYARATAN GRID */
        .psb-bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-top: 24px;
        }

        .rek-card {
            background: #fff;
            border: 1px solid var(--green-200);
            border-radius: var(--radius-sm);
            padding: 24px;
        }

        .rek-card h4 {
            font-family: 'EB Garamond', serif;
            font-size: 20px;
            color: var(--green-900);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rek-bank-badge {
            display: inline-block;
            background: #00529b;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: var(--radius-xs);
            margin-bottom: 8px;
        }

        .rek-number {
            font-family: monospace;
            font-size: 18px;
            font-weight: 700;
            color: var(--green-950);
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .rek-owner {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 14px;
        }

        .rek-wa-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #25d366;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: var(--radius-xs);
            transition: all 0.2s;
        }

        .rek-wa-btn:hover {
            background: #1ebd5a;
            transform: translateY(-1px);
        }

        .syarat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 24px;
        }

        .syarat-card h4 {
            font-family: 'EB Garamond', serif;
            font-size: 20px;
            color: var(--green-900);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .syarat-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .syarat-list li {
            font-size: 12px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .syarat-list li .s-check {
            color: var(--green-600);
            flex-shrink: 0;
        }

        /* ===== BUKU PANDUAN BANNER ===== */
        .section-panduan {
            padding: 72px 0;
            background: #fff;
        }

        .panduan-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
            background: var(--gradient-emerald);
            border-radius: var(--radius-md);
            padding: 44px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .panduan-card::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(200, 164, 21, 0.12) 0%, transparent 70%);
            border-radius: 50%;
        }

        .panduan-text {
            position: relative;
            z-index: 2;
        }

        .panduan-text .label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--gold-300);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .panduan-text h2 {
            font-family: 'EB Garamond', serif;
            font-size: clamp(24px, 3vw, 34px);
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 14px;
        }

        .panduan-text p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .panduan-features {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 24px;
        }

        .panduan-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
        }

        .panduan-features li .check-icon {
            color: var(--gold-300);
            flex-shrink: 0;
        }

        .btn-gold {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--green-950);
            background: var(--gradient-gold);
            padding: 12px 28px;
            border-radius: var(--radius-sm);
            transition: all 0.25s;
            box-shadow: 0 4px 16px rgba(200, 164, 21, 0.3);
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(200, 164, 21, 0.4);
        }

        .panduan-visual {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .panduan-pdf-preview {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: var(--radius-md);
            padding: 28px;
            text-align: center;
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 300px;
        }

        .panduan-pdf-icon-wrap {
            width: 68px;
            height: 84px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-xs);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--gold-300);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .panduan-pdf-preview h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .panduan-pdf-preview p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.55);
            margin-bottom: 0;
        }

        /* ===== PESAN PIMPINAN ===== */
        .section-pimpinan {
            padding: 80px 0;
            background: var(--surface-dim);
        }

        .pimpinan-card {
            background: #fff;
            border-radius: var(--radius-md);
            padding: 40px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            max-width: 900px;
            margin: 0 auto;
        }

        .pimpinan-inner {
            display: flex;
            gap: 36px;
            align-items: center;
        }

        .pimpinan-photo {
            flex-shrink: 0;
            text-align: center;
        }

        .pimpinan-photo-img {
            width: 110px;
            height: 110px;
            border-radius: var(--radius-md);
            overflow: hidden;
            margin: 0 auto 10px;
            background: var(--surface-dim);
            border: 2px solid var(--green-200);
        }

        .pimpinan-photo-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pimpinan-photo strong {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            display: block;
        }

        .pimpinan-photo span {
            font-size: 11px;
            font-weight: 600;
            color: var(--green-600);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .pimpinan-quote {
            flex: 1;
        }

        .pimpinan-quote .quote-mark {
            font-size: 44px;
            line-height: 1;
            color: var(--green-200);
            font-family: 'EB Garamond', serif;
        }

        .pimpinan-quote blockquote {
            font-family: 'EB Garamond', serif;
            font-size: clamp(17px, 2.2vw, 22px);
            font-style: italic;
            color: var(--text-primary);
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .pimpinan-quote .note {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* ===== KONTAK RESMI & FOOTER ===== */
        .section-kontak-wrap {
            background: #fff;
            padding: 72px 0 0;
            border-top: 1px solid var(--border);
        }

        .kontak-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .kontak-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 48px;
        }

        .kontak-box {
            background: var(--surface-dim);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 22px 18px;
            transition: all 0.3s;
        }

        .kontak-box:hover {
            border-color: var(--green-300);
            background: #fff;
            box-shadow: var(--shadow-sm);
            transform: translateY(-2px);
        }

        .kontak-box .k-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-xs);
            background: #fff;
            border: 1px solid var(--green-200);
            color: var(--green-700);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .kontak-box h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--green-900);
            margin-bottom: 4px;
        }

        .kontak-box p {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 10px;
        }

        .kontak-box .phone-link {
            font-size: 13px;
            font-weight: 700;
            color: var(--green-700);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .kontak-box .phone-link:hover {
            color: var(--green-900);
        }

        .footer {
            background: var(--green-950);
            color: rgba(255, 255, 255, 0.7);
            padding: 56px 0 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 32px;
            margin-bottom: 40px;
        }

        .footer-brand strong {
            font-family: 'EB Garamond', serif;
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            display: block;
            margin-bottom: 10px;
        }

        .footer-brand p {
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 14px;
        }

        .footer-accreditation {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 6px 14px;
            border-radius: var(--radius-xs);
            font-size: 11px;
            font-weight: 600;
            color: var(--gold-300);
        }

        .footer-col h4 {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 14px;
        }

        .footer-col a {
            display: block;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            padding: 3px 0;
            transition: color 0.2s;
        }

        .footer-col a:hover {
            color: var(--gold-300);
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .footer-contact-item .icon-svg {
            flex-shrink: 0;
            margin-top: 2px;
            color: var(--green-300);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 18px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
        }

        .footer-bottom-links {
            display: flex;
            gap: 16px;
        }

        .footer-bottom-links a {
            color: rgba(255, 255, 255, 0.4);
            transition: color 0.2s;
        }

        .footer-bottom-links a:hover {
            color: var(--gold-300);
        }

        /* ===== RESPONSIVE MEDIA QUERIES ===== */
        @media (max-width: 800px) {
            .sambutan-inner-grid {
                grid-template-columns: 1fr !important;
                text-align: center;
            }
        }

        @media (max-width: 1024px) {
            .hero-grid {
                gap: 32px;
            }

            .panca-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .filosofi-items-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .kontak-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 900px) {
            .hero-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-subtitle {
                margin: 0 auto 30px;
            }

            .hero-actions {
                justify-content: center;
            }

            .berita-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .berita-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .profil-lead-grid {
                grid-template-columns: 1fr;
            }

            .pilar-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .kehidupan-layout {
                grid-template-columns: 1fr;
            }

            .tertib-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .fasilitas-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .psb-steps {
                grid-template-columns: repeat(2, 1fr);
            }

            .psb-bottom-grid {
                grid-template-columns: 1fr;
            }

            .panduan-card {
                grid-template-columns: 1fr;
                padding: 32px 24px;
            }

            .panduan-visual {
                order: -1;
            }

            .nav-links,
            .nav-cta-wrap {
                display: none;
            }

            .mobile-menu-btn {
                display: flex;
            }
        }

        @media (max-width: 600px) {
            .berita-grid {
                grid-template-columns: 1fr;
            }

            .panca-grid {
                grid-template-columns: 1fr;
            }

            .filosofi-items-grid {
                grid-template-columns: 1fr;
            }

            .pilar-grid {
                grid-template-columns: 1fr;
            }

            .tertib-grid {
                grid-template-columns: 1fr;
            }

            .fasilitas-grid {
                grid-template-columns: 1fr;
            }

            .psb-steps {
                grid-template-columns: 1fr;
            }

            .kontak-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }
        }

        /* ===== ANIMATIONS ===== */
        .anim-fade-up {
            opacity: 0;
            transform: translateY(24px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .anim-fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ===== SCROLL PROGRESS ===== */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: var(--gradient-primary);
            z-index: 999;
            transition: width 0.1s linear;
        }
    </style>
</head>

<body>
    <!-- Scroll Progress -->
    <div class="scroll-progress" id="scrollProgress"></div>

    <!-- NAVBAR -->
    <header class="navbar" id="navbar">
        <div class="container">
            <div class="navbar-inner">
                <a href="#" class="navbar-brand">
                    <img src="/logo.png" alt="Logo Pondok Pesantren Hidayatullah Tuksongo">
                    <div class="navbar-brand-text">
                        <strong>Hidayatullah Tuksongo</strong>
                        <span>Pringsurat Temanggung</span>
                    </div>
                </a>
                <nav class="nav-links">
                    <a href="#beranda" class="active">Beranda</a>
                    <a href="#berita">Berita</a>

                    <!-- DROPDOWN 1: TENTANG PONDOK -->
                    <div class="nav-item-dropdown">
                        <button class="nav-dropdown-trigger" id="navDropdownTentang" aria-haspopup="true">
                            Tentang Pondok
                            <svg class="icon-svg icon-svg-xs chevron-icon" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="nav-dropdown-menu">
                            <a href="#profil" class="nav-dropdown-item">
                                <div class="dd-icon">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    </svg>
                                </div>
                                <div class="dd-text">
                                    <strong>Profil & Sejarah</strong>
                                    <span>Berdiri sejak 1999 di tanah wakaf Tuksongo</span>
                                </div>
                            </a>
                            <a href="#profil" class="nav-dropdown-item">
                                <div class="dd-icon">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg>
                                </div>
                                <div class="dd-text">
                                    <strong>Panca Jiwa & Nilai</strong>
                                    <span>Keikhlasan, kesederhanaan & kemandirian</span>
                                </div>
                            </a>
                            <a href="#fasilitas" class="nav-dropdown-item">
                                <div class="dd-icon">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                </div>
                                <div class="dd-text">
                                    <strong>Fasilitas Kampus</strong>
                                    <span>Masjid jami', asrama sehat & laboratorium</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- DROPDOWN 2: KEPESANTRENAN -->
                    <div class="nav-item-dropdown">
                        <button class="nav-dropdown-trigger" id="navDropdownPesantren" aria-haspopup="true">
                            Kepesantrenan
                            <svg class="icon-svg icon-svg-xs chevron-icon" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="nav-dropdown-menu">
                            <a href="#program" class="nav-dropdown-item">
                                <div class="dd-icon">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg>
                                </div>
                                <div class="dd-text">
                                    <strong>Pilar Pendidikan</strong>
                                    <span>Tahfidz bersanad, sains & dwibahasa aktif</span>
                                </div>
                            </a>
                            <a href="#kehidupan" class="nav-dropdown-item">
                                <div class="dd-icon">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </div>
                                <div class="dd-text">
                                    <strong>Santri 24 Jam</strong>
                                    <span>Jadwal rutinitas harian dari 03.00—22.00</span>
                                </div>
                            </a>
                            <a href="#tata-tertib" class="nav-dropdown-item">
                                <div class="dd-icon">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                </div>
                                <div class="dd-text">
                                    <strong>Tata Tertib & Disiplin</strong>
                                    <span>Kedisiplinan ibadah, adab & bebas gadget</span>
                                </div>
                            </a>
                            <a href="{{ \App\Models\Setting::get('brosur_file_url', '/uploads/settings/brosur_1788852849.jpeg') }}" target="_blank" class="nav-dropdown-item">
                                <div class="dd-icon">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                </div>
                                <div class="dd-text">
                                    <strong>Brosur PSB (PDF / Gambar)</strong>
                                    <span>Unduh brosur resmi penerimaan santri</span>
                                </div>
                            </a>
                            <a href="{{ \App\Models\Setting::get('panduan_file_url', '/NEW BUKU PANDUAN SANTRI TA 2025 (2).pdf') }}" target="_blank" class="nav-dropdown-item">
                                <div class="dd-icon">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                    </svg>
                                </div>
                                <div class="dd-text">
                                    <strong>Buku Panduan Santri (PDF)</strong>
                                    <span>Buku pedoman resmi santri (81 hlm)</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <a href="#psb">Biaya & PSB</a>
                    <a href="#kontak">Kontak</a>
                </nav>
                <div class="nav-cta-wrap">
                    <a href="{{ route('psb.checkStatus') }}" class="btn-cek-status-nav" style="display:inline-flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:var(--green-900); background:#fff; border:1px solid var(--green-300); padding:7px 14px; border-radius:var(--radius-sm); transition:all 0.2s; text-decoration:none;" title="Pantau status verifikasi berkas tanpa akun login">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <span>Cek Status</span>
                    </a>
                    <a class="nav-cta" href="{{ route('psb.register') }}">
                        Daftar Santri Baru
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Buka Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- MOBILE NAV -->
    <div class="mobile-nav" id="mobileNav">
        <button class="mobile-nav-close" id="mobileNavClose" aria-label="Tutup Menu">✕</button>
        <a href="#beranda" onclick="closeMobileNav()">Beranda</a>
        <a href="#berita" onclick="closeMobileNav()">Warta Berita</a>

        <div class="mobile-group-title">Tentang Pondok</div>
        <div class="mobile-sublinks">
            <a href="#profil" onclick="closeMobileNav()">• Profil, Sejarah & Panca Jiwa</a>
            <a href="#fasilitas" onclick="closeMobileNav()">• Fasilitas Kampus</a>
        </div>

        <div class="mobile-group-title">Kepesantrenan</div>
        <div class="mobile-sublinks">
            <a href="#program" onclick="closeMobileNav()">• Pilar Pendidikan Integral</a>
            <a href="#kehidupan" onclick="closeMobileNav()">• Kehidupan Santri 24 Jam</a>
            <a href="#tata-tertib" onclick="closeMobileNav()">• Tata Tertib & Disiplin</a>
            <a href="{{ \App\Models\Setting::get('brosur_file_url', '/uploads/settings/brosur_1788852849.jpeg') }}" target="_blank" onclick="closeMobileNav()">• Unduh Brosur PSB (PDF / Gambar)</a>
            <a href="{{ \App\Models\Setting::get('panduan_file_url', '/NEW BUKU PANDUAN SANTRI TA 2025 (2).pdf') }}" target="_blank" onclick="closeMobileNav()">• Unduh Buku Panduan Santri (PDF)</a>
        </div>

        <a href="#psb" onclick="closeMobileNav()">Biaya & Pendaftaran PSB</a>
        <a href="{{ route('psb.checkStatus') }}" onclick="closeMobileNav()" style="font-weight:700; color:var(--green-900); display:flex; align-items:center; gap:8px;">
            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            Cek Status Pendaftaran Mandiri
        </a>
        <a href="#kontak" onclick="closeMobileNav()">Kontak & Hotline</a>
        <a href="{{ route('admin.dashboard') }}" target="_blank" onclick="closeMobileNav()"
            style="color: var(--green-700); font-size: 14px; font-weight: 700; border-top: 1px dashed var(--border); padding-top: 12px; display: flex; align-items: center; justify-content: space-between;">
            <span>Masuk TailAdmin</span>
            <span
                style="font-size: 11px; padding: 2px 8px; border-radius: 4px; background: var(--green-100); color: var(--green-800);">Admin</span>
        </a>
        <a class="nav-cta" href="{{ route('psb.register') }}" onclick="closeMobileNav()"
            style="margin-top:16px; justify-content:center;">
            Daftar Santri Baru
            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </div>

    <main>
        <!-- ===== HERO ===== -->
        <section class="hero" id="beranda">
            <div class="container">
                <div class="hero-grid">
                    <div class="hero-content">
                        <div class="hero-badge">
                            <div class="hero-badge-dot"></div>
                            <span>{!! \App\Models\Setting::get('hero_badge', 'PSB TA 2025/2026 Telah Dibuka') !!}</span>
                        </div>
                        <h1 class="hero-title">
                            {!! \App\Models\Setting::get('hero_title', "Membentuk Generasi <em>Qur'ani</em>, Berakhlak Mulia & Berwawasan Global") !!}
                        </h1>
                        <p class="hero-subtitle">
                            {{ \App\Models\Setting::get('hero_subtitle', 'Sinergi pendidikan integral Pondok Pesantren Hidayatullah Tuksongo yang memadukan bimbingan Tahfidzul Qur\'an mutqin, kurikulum formal Kemenag, pembinaan akhlak santri 24 jam, dan kemandirian hidup.') }}
                        </p>
                        <div class="hero-actions">
                            <a class="btn-primary" href="{{ route('psb.register') }}">
                                Daftar Santri Baru
                                <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                            <a class="btn-secondary" href="{{ \App\Models\Setting::get('brosur_file_url', '/uploads/settings/brosur_1788852849.jpeg') }}" target="_blank">
                                <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                                Unduh Brosur (PDF)
                            </a>
                        </div>
                    </div>
                    <div class="hero-visual">
                        <div class="hero-img-wrap">
                            <img src="{{ \App\Models\Setting::get('hero_image', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCGYaU4q4nIXeNSx0KvqvdTCXvhP1950A1c7sA345MHgqC0koDMg-pDDQnBy9NpclcSa0PcDMfQidKumWL-n9GMZ9qrXrvAEwL9U3hdTPo2-0eXAokLKZ11EVIOzlck9D1C9LwbnjOu8N6NjiOSLbZCN3122S-MJBUjjFqSj9UoSN7s74Zg-Yc4FBDioEDu2ACO-pOvjP9mhQLY9aUyH4HtA-4GCzA_H_DcQHTZ5Binpb7M2nk8bVSb') }}"
                                alt="Kampus Terpadu Pondok Pesantren Hidayatullah Tuksongo">
                            <div class="hero-img-overlay">
                                <div class="hero-img-overlay-content">
                                    <div class="badge-verified">
                                        <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                    <div>
                                        <strong>{{ \App\Models\Setting::get('hero_caption', 'Kampus Alam Tuksongo Madani') }}</strong>
                                        <small>{{ \App\Models\Setting::get('hero_subcaption', "Dusun Tuksongo, Nglorog, Pringsurat — Asri, hening, dan kondusif untuk tholabul 'ilmi") }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== STATS BAR ===== -->
        <section class="stats-bar">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <svg class="icon-svg icon-svg-md" viewBox="0 0 24 24">
                                <path d="M3 21h18"></path>
                                <path d="M5 21V9l7-6 7 6v12"></path>
                                <path d="M9 21v-6a3 3 0 0 1 6 0v6"></path>
                            </svg>
                        </div>
                        <div class="stat-value">Tahun 1999</div>
                        <div class="stat-label">Berdiri di Atas Tanah Wakaf</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <svg class="icon-svg icon-svg-md" viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <div class="stat-value">Terakreditasi B</div>
                        <div class="stat-label">BAN-SM Kemenag MTs & MA</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <svg class="icon-svg icon-svg-md" viewBox="0 0 24 24">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                        </div>
                        <div class="stat-value">6 Tahun TMI</div>
                        <div class="stat-label">Pondok Alumni Gontor & Salaf</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <svg class="icon-svg icon-svg-md" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div class="stat-value">24 Jam</div>
                        <div class="stat-label">Pengasuhan & Keteladanan Intensif</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== BERITA (FIRST SECTION AFTER STATS) ===== -->
        <section class="section-berita" id="berita">
            <div class="container">
                <div class="berita-header anim-fade-up">
                    <div>
                        <span class="section-label">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 14 14"></polyline>
                            </svg>
                            Warta Kampus & Kegiatan Santri
                        </span>
                        <h2 class="section-title">Kilas Berita & Agenda Harian Santri</h2>
                    </div>
                    <p class="section-desc">Kabar terbaru kegiatan kepesantrenan, prestasi santri di ajang kompetisi,
                        halaqah kajian kitab, dan dinamika dakwah di Pesantren Hidayatullah Tuksongo.</p>
                </div>
                <div class="berita-grid">
                    @forelse($latestArticles as $art)
                        <a href="{{ route('berita.show', $art->slug) }}" class="berita-card anim-fade-up">
                            <img class="berita-card-bg-img"
                                src="{{ $art->image ?: 'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?q=80&w=800&auto=format&fit=crop' }}"
                                alt="{{ $art->title }}" loading="lazy">
                            <div class="berita-card-gradient"></div>
                            <div class="berita-card-top">
                                <span class="berita-card-tag">{{ $art->category ?: 'Warta Pesantren' }}</span>
                            </div>
                            <div class="berita-card-content">
                                <div class="berita-card-meta">
                                    <span>
                                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 15 15"></polyline>
                                        </svg>
                                        {{ $art->published_at ? $art->published_at->translatedFormat('d M Y') : $art->created_at->translatedFormat('d M Y') }}
                                    </span>
                                    <span>•</span>
                                    <span>{{ $art->author ?: 'Humas Pondok' }}</span>
                                </div>
                                <h3 class="berita-card-title">{{ $art->title }}</h3>
                                <p class="berita-card-excerpt">{{ Str::limit($art->excerpt, 110) }}</p>
                                <div class="berita-card-action">
                                    Baca Selengkapnya
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div
                            style="grid-column: 1 / -1; text-align: center; padding: 48px; background: #fff; border-radius: var(--radius-md); border: 1px dashed var(--border);">
                            <p style="color: var(--text-muted); font-size: 15px;">Belum ada artikel berita yang
                                dipublikasikan.</p>
                        </div>
                    @endforelse
                </div>

                <div style="text-align: center; margin-top: 40px;" class="anim-fade-up">
                    <a href="{{ route('berita.index') }}" class="btn-secondary"
                        style="background: #ffffff; border: 1.5px solid var(--green-600); color: var(--green-800); box-shadow: var(--shadow-sm); font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                        <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                            <path
                                d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                        Buka Seluruh Arsip Warta & Berita
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- ===== PROFIL & SEJARAH PONDOK (DATA BUKU PANDUAN & CMS) ===== -->
        <section class="section-profil" id="profil">
            <div class="container">
                <!-- Sambutan Dewan Pengasuh & Pimpinan Pesantren -->
                <div class="sambutan-card anim-fade-up"
                    style="background: linear-gradient(135deg, #082412 0%, #145a2e 50%, #1a6b38 100%); color: #fff; padding: 36px 32px; border-radius: var(--radius-md); margin-bottom: 48px; box-shadow: 0 16px 36px rgba(13,59,30,0.18); position: relative; overflow: hidden; border: 1px solid rgba(200,164,21,0.3);">
                    <div style="position: absolute; right: -30px; bottom: -40px; opacity: 0.05; pointer-events: none;">
                        <svg style="width: 280px; height: 280px;" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; gap: 32px; align-items: center; position: relative; z-index: 1;"
                        class="sambutan-inner-grid">
                        <!-- Foto Pimpinan -->
                        <div style="text-align: center;">
                            <div
                                style="width: 180px; height: 180px; margin: 0 auto; border-radius: 50%; overflow: hidden; border: 4px solid var(--gold-400); box-shadow: 0 10px 24px rgba(0,0,0,0.35); position: relative; background: #082412;">
                                <img src="{{ \App\Models\Setting::get('sambutan_foto', '/pimpinan.jpg') }}"
                                    alt="Pimpinan Pesantren Hidayatullah Tuksongo"
                                    style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                            </div>
                            <div style="margin-top: 14px;">
                                <strong
                                    style="font-family: 'EB Garamond', serif; font-size: 19px; color: #fff; display: block; line-height: 1.2;">{{ \App\Models\Setting::get('sambutan_nama', 'Pimpinan Pesantren') }}</strong>
                                <span
                                    style="font-size: 12px; font-weight: 700; color: var(--gold-300); letter-spacing: 0.06em; text-transform: uppercase; display: block; margin-top: 2px;">{{ \App\Models\Setting::get('sambutan_jabatan', 'Hidayatullah Tuksongo') }}</span>
                            </div>
                        </div>

                        <!-- Quote & Amanat Pengasuhan -->
                        <div style="display: flex; flex-direction: column; gap: 14px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span
                                    style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--gold-300); background: rgba(200,164,21,0.15); border: 1px solid rgba(200,164,21,0.3); padding: 4px 12px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 6px;">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <path
                                            d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z">
                                        </path>
                                        <path
                                            d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z">
                                        </path>
                                    </svg>
                                    Kalam Pengasuh & Pimpinan Pesantren
                                </span>
                            </div>

                            <blockquote
                                style="font-family: 'EB Garamond', serif; font-size: 21px; font-style: italic; line-height: 1.6; color: #ffffff; margin: 0; position: relative;">
                                "{{ \App\Models\Setting::get('sambutan_quote', 'Pondok Pesantren bukan sekadar tempat menuntut ilmu, melainkan kawah candradimuka yang menempa jiwa keikhlasan, kesederhanaan, kemandirian, dan ukhuwah. Sebesar keinsyafan seseorang, sebesar itu pula keuntungan hidup yang diraihnya.') }}"
                            </blockquote>

                            <p
                                style="font-size: 13px; color: rgba(255,255,255,0.85); line-height: 1.7; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 12px; margin: 0;">
                                {{ \App\Models\Setting::get('sambutan_amanat', 'Amanat Pengasuhan Pondok Pesantren Hidayatullah Tuksongo bagi seluruh asatidz, santri, dan wali santri dalam mewujudkan generasi penerus peradaban Islam yang tangguh.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="profil-header anim-fade-up">
                    <span class="section-label">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        Sejarah & Identitas Resmi
                    </span>
                    <h2 class="section-title">
                        {{ \App\Models\Setting::get('profil_judul', 'Profil Pesantren Hidayatullah Tuksongo') }}</h2>
                    <p class="section-desc">Lembaga pendidikan Islam integral di bawah naungan Yayasan Hidayatullah
                        Tuksongo, mendidik generasi berilmu amaliah, beramal ilmiah, dan berakhlak karimah.</p>
                </div>

                <div class="profil-lead-card anim-fade-up">
                    <div class="profil-lead-grid">
                        <div class="profil-sejarah">
                            <h3>Sejarah Pendirian & Khidmat Dakwah</h3>
                            <p>{{ \App\Models\Setting::get('sejarah_paragraf_1', 'Pondok Pesantren Hidayatullah Tuksongo didirikan pada tahun 1999 di atas tanah wakaf bersertifikat seluas 2.000 m² di Dusun Tuksongo RT 01/RW 01, Desa Nglorog, Kecamatan Pringsurat, Kabupaten Temanggung, Jawa Tengah.') }}
                            </p>
                            <p>{{ \App\Models\Setting::get('sejarah_paragraf_2', "Berawal dari majelis pengajian kyai bersama santri mukim yang terus bertambah, pesantren berkembang menjadi pusat peradaban ilmu. Pesantren ini dipimpin oleh para asatidz alumni Pondok Modern Darussalam Gontor yang memadukan kedisiplinan modern, tradisi Khutbatul 'Arsy (Pekan Perkenalan), panggung ekspresi santri, penguasaan dwibahasa Arab-Inggris, serta kekayaan kitab turats salafiyah.") }}
                            </p>
                            <div class="legalitas-chips">
                                <span class="legalitas-chip">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    NSPP: {{ \App\Models\Setting::get('nspp', '512032304095') }}
                                </span>
                                <span class="legalitas-chip">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    MTs NPSN: {{ \App\Models\Setting::get('mts_npsn', '69994052') }}
                                </span>
                                <span class="legalitas-chip">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    MA NPSN: {{ \App\Models\Setting::get('ma_npsn', '69881435') }}
                                </span>
                                <span class="legalitas-chip">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Status Tanah: {{ \App\Models\Setting::get('status_tanah', 'Wakaf 2.000 m²') }}
                                </span>
                            </div>
                        </div>
                        <div class="visi-misi-box">
                            <div class="visi-tag">Visi Utama Pesantren</div>
                            <div class="visi-quote">
                                "{{ \App\Models\Setting::get('visi', 'Unggul dalam Keislaman, Keilmuan, dan Kemasyarakatan') }}"
                            </div>
                            <ul class="misi-list">
                                @php
                                    $rawMisi = \App\Models\Setting::get('misi', "Terwujudnya kurikulum pesantren berbasis integrasi keagamaan dan kurikulum nasional yang unggul.
                                    Peningkatan prestasi akademik sains dan non-akademik keagamaan.
                                    Terbentuknya santri berkarakter kokoh, beradab mulia, dan siap guna di tengah masyarakat luas.
                                    Menjadi lembaga pendidikan berkualitas dengan jejaring dalam maupun luar negeri.");
                                    $misiItems = array_filter(array_map('trim', explode("
                                    ", $rawMisi)));
                                @endphp
                                @foreach($misiItems as $misiPoint)
                                    <li>
                                        <svg class="icon-svg icon-svg-xs bullet" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span>{{ ltrim($misiPoint, '-•* ') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- PANCA JIWA PESANTREN -->
                @php
                    $pancaJiwaList = json_decode(\App\Models\Setting::get('panca_jiwa_json', '[]'), true) ?: [
                        ['nomor' => '01', 'judul' => 'Keikhlasan', 'deskripsi' => "Beramal semata-mata karena Allah (lillahi ta'ala), bebas dari pamrih keduniaan, menjaga kemurnian niat dalam menuntut ilmu dan berkhidmat."],
                        ['nomor' => '02', 'judul' => 'Kesederhanaan', 'deskripsi' => "Pola hidup wajar, bersahaja, hemat, dan terukur. Sederhana bukan berarti melarat, melainkan keteguhan jiwa yang menghindarkan sikap berlebihan."],
                        ['nomor' => '03', 'judul' => 'Berdikari (Mandiri)', 'deskripsi' => "Sanggup menolong diri sendiri (Zelf Berdruiping System). Santri dididik mengurus kebutuhan sendiri, disiplin, dan pantang berpangku tangan."],
                        ['nomor' => '04', 'judul' => 'Ukhuwah Islamiyah', 'deskripsi' => "Persaudaraan akrab yang menembus sekat kesukuan dan kedaerahan. Kesulitan ditanggung bersama, kesenangan dirasakan bersama."],
                        ['nomor' => '05', 'judul' => 'Kebebasan Positif', 'deskripsi' => "Bebas berpikir dan berbuat dalam bingkai syariat, disiplin positif, dan bertanggung jawab penuh tanpa diperbudak hawa nafsu."],
                    ];
                @endphp
                <div class="panca-title-wrap anim-fade-up">
                    <div>
                        <span class="section-label">Falsafah Hidup Pondok</span>
                        <h3 class="section-title" style="font-size: 28px;">{{ \App\Models\Setting::get('falsafah_judul', 'Panca Jiwa Pondok Pesantren Hidayatullah') }}</h3>
                    </div>
                    <p class="section-desc" style="font-size: 13px;">{{ \App\Models\Setting::get('falsafah_subjudul', 'Lima nilai pokok yang menjadi ruh dan pedoman pembentukan karakter setiap santri selama menuntut ilmu.') }}</p>
                </div>
                <div class="panca-grid">
                    @foreach ($pancaJiwaList as $index => $item)
                    <div class="panca-card anim-fade-up">
                        <div class="panca-num">{{ $item['nomor'] ?? sprintf('%02d', $index + 1) }}</div>
                        <h4>{{ $item['judul'] ?? '' }}</h4>
                        <p>{!! nl2br(e($item['deskripsi'] ?? '')) !!}</p>
                    </div>
                    @endforeach
                </div>

                <!-- FILOSOFI LOGO -->
                @php
                    $filosofiLambangList = json_decode(\App\Models\Setting::get('filosofi_lambang_json', '[]'), true) ?: [
                        ['elemen' => 'Segi Lima Luar', 'makna' => 'Rukun Islam sebagai landasan kokoh pembinaan kepribadian santri.'],
                        ['elemen' => 'Kubah Masjid', 'makna' => 'Rukun Iman dan keterikatan batin pada masjid sebagai pusat ibadah.'],
                        ['elemen' => 'Bintang Satu', 'makna' => 'Derajat Ihsan dan cita-cita luhur menerangi ummat dengan petunjuk Ilahi.'],
                        ['elemen' => 'Kitab & Pena', 'makna' => "Berdasar pada Al-Qur'an dan As-Sunnah serta kegigihan menuntut ilmu."],
                        ['elemen' => 'Toga Wisuda', 'makna' => 'Pencapaian prestasi akademik dan kelulusan santri yang bermartabat.'],
                        ['elemen' => 'Warna Hijau & Merah', 'makna' => 'Hijau melambangkan Keislaman; Merah melambangkan Semangat Kemasyarakatan.'],
                        ['elemen' => 'Warna Putih & Kuning', 'makna' => 'Putih simbol Keilmuan & Kesucian; Kuning simbol Kejayaan dan Kemuliaan Ummat.'],
                        ['elemen' => 'Semboyan Hidup', 'makna' => '"Sebesar keinsyafan seseorang, sebesar itu pula keuntungan yang diraihnya."'],
                    ];
                @endphp
                <div class="logo-filosofi-box anim-fade-up">
                    <div class="logo-filosofi-header">
                        <img src="/logo.png" alt="Logo Pondok Pesantren Hidayatullah">
                        <div>
                            <h3>{{ \App\Models\Setting::get('filosofi_judul', 'Makna Filosofi Lambang Pesantren') }}</h3>
                            <p>{{ \App\Models\Setting::get('filosofi_subjudul', 'Setiap goresan simbol dan warna dalam logo pesantren mencerminkan visi luhur perjuangan dakwah dan pendidikan.') }}</p>
                        </div>
                    </div>
                    <div class="filosofi-items-grid">
                        @foreach ($filosofiLambangList as $fItem)
                        <div class="filosofi-item">
                            <strong>{{ $fItem['elemen'] ?? '' }}</strong>
                            <span>{!! nl2br(e($fItem['makna'] ?? '')) !!}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== PILAR PENDIDIKAN INTEGRAL ===== -->
        @php
            $pilarPendidikanList = json_decode(\App\Models\Setting::get('pilar_pendidikan_json', '[]'), true) ?: [
                [
                    'judul' => 'Tauhid & Akhlakul Karimah',
                    'deskripsi' => "Penanaman akidah shahihah bermanhaj ahlussunnah wal jama'ah, bimbingan adab nabawi di asrama, birrul walidain, serta keteladanan akhlak 24 jam bersama dewan ustadz.",
                    'tag' => 'Tarbiyah 24 Jam'
                ],
                [
                    'judul' => "Tahfidzul Qur'an & Turats",
                    'deskripsi' => "Bimbingan tahfidz intensif, simakan Al-Qur'an mingguan, kajian tajwid, serta pendalaman kitab turats (Nahwu, Shorof, Fiqih Ghoyatu Taqrib, Hadits Mukhtarul Hadits).",
                    'tag' => 'Tahfidz & Kitab Kuning'
                ],
                [
                    'judul' => 'Bahasa Arab & Inggris Aktif',
                    'deskripsi' => "Disiplin komunikasi dwibahasa harian di lingkungan pesantren, pemberian mufrodat setiap pagi ba'da subuh, serta latihan khitobah (Muhadhoroh) tiga bahasa.",
                    'tag' => 'Bilingual Daily Life'
                ],
                [
                    'judul' => 'Kemandirian & Kepemimpinan',
                    'deskripsi' => "Wadah kaderisasi OSPPH (Organisasi Santri Pondok Pesantren Hidayatullah), kepanduan Pramuka wajib, beladiri pencak silat, dan pembekalan khidmat masyarakat.",
                    'tag' => 'Leadership & OSPPH'
                ],
            ];
        @endphp
        <section class="section-pilar" id="program">
            <div class="container">
                <div class="pilar-header anim-fade-up">
                    <span class="section-label">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                        </svg>
                        Khazanah & Pola Pengasuhan
                    </span>
                    <h2 class="section-title">{{ \App\Models\Setting::get('pilar_title', 'Fondasi Pendidikan Integral Berkarakter Islami') }}</h2>
                    <p class="section-desc">{{ \App\Models\Setting::get('pilar_subtitle', "Pilar pendidikan di Pesantren Hidayatullah Tuksongo dirancang seimbang antara kesucian ruhani, kedalaman ilmu syar'i, kecerdasan intelek, dan kemandirian hidup.") }}</p>
                </div>
                <div class="pilar-grid">
                    @foreach ($pilarPendidikanList as $index => $item)
                    <div class="pilar-card anim-fade-up">
                        <div class="pilar-icon">
                            @if ($index % 4 === 0)
                            <svg class="icon-svg icon-svg-md" viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                            @elseif ($index % 4 === 1)
                            <svg class="icon-svg icon-svg-md" viewBox="0 0 24 24">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                            @elseif ($index % 4 === 2)
                            <svg class="icon-svg icon-svg-md" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                            @else
                            <svg class="icon-svg icon-svg-md" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                            </svg>
                            @endif
                        </div>
                        <h3>{{ $item['judul'] ?? '' }}</h3>
                        <p>{!! nl2br(e($item['deskripsi'] ?? '')) !!}</p>
                        @if (!empty($item['tag']))
                        <span class="pilar-tag">{{ $item['tag'] }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ===== KEHIDUPAN & JADWAL SANTRI 24 JAM (FROM BUKU PANDUAN) ===== -->
        <section class="section-kehidupan" id="kehidupan">
            <div class="container">
                <div class="kehidupan-header anim-fade-up">
                    <span class="section-label">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Ritme Kehidupan Pesantren
                    </span>
                    <h2 class="section-title">24 Jam Aktivitas & Rutinitas Harian Santri</h2>
                    <p class="section-desc">Jadwal harian resmi sebagaimana diatur dalam Buku Panduan Santri untuk
                        menanamkan kedisiplinan waktu, konsistensi ibadah, dan produktivitas belajar.</p>
                </div>

                <div class="kehidupan-layout">
                    <div class="timeline-card anim-fade-up">
                        <h3>Jadwal Harian Santri (Senin — Ahad)</h3>
                        <span class="subtitle">Dari sepertiga malam hingga istirahat teratur di bawah bimbingan asatidz
                            pembina</span>
                        <div class="timeline-list">
                            @php
                                $rawJadwal = \App\Models\Setting::get('jadwal_santri_json');
                                $listJadwal = json_decode($rawJadwal, true) ?: [];
                                if (empty($listJadwal)) {
                                    $listJadwal = [
                                        ["waktu" => "03.00 — 04.30 WIB", "judul" => "Bangun Pagi, Qiyamul Lail & Persiapan Shubuh", "keterangan" => "Tahajud mandiri, tilawah Al-Qur'an, dan persiapan menuju masjid sebelum adzan"],
                                        ["waktu" => "04.30 — 05.30 WIB", "judul" => "Sholat Shubuh Berjamaah & Tadarus Al-Qur'an", "keterangan" => "Dzikir ba'da shalat, halaqah tadarus, dan tasmi' hafalan Al-Qur'an santri"],
                                        ["waktu" => "05.30 — 06.45 WIB", "judul" => "Pemberian Mufrodat Bahasa & Piket Mandiri", "keterangan" => "Penambahan kosakata Arab/Inggris harian, kebersihan lingkungan kamar, MCK, dan sarapan"],
                                        ["waktu" => "06.45 — 07.30 WIB", "judul" => "Apel Pagi & Sholat Dhuha Berjamaah", "keterangan" => "Pemeriksaan kerapian seragam madrasah dan doa bersama sebelum jam pelajaran"],
                                        ["waktu" => "07.30 — 14.00 WIB", "judul" => "Kegiatan Belajar Mengajar (KBM) Formal MTs & MA", "keterangan" => "Pelajaran kurikulum Kemenag dan kurikulum pesantren TMI, diselingi shalat Zhuhur berjamaah"],
                                        ["waktu" => "14.00 — 16.45 WIB", "judul" => "Ekstrakurikuler, Sholat Ashar & Olahraga Sore", "keterangan" => "Pengembangan minat bakat, sholat Ashar berjamaah, pencak silat, panahan, dan kebersihan diri"],
                                        ["waktu" => "16.45 — 20.30 WIB", "judul" => "Sholat Maghrib, Makan Malam & Ngaji Kitab Kuning", "keterangan" => "Tadarus Al-Qur'an, makan malam tertib, shalat Isya berjamaah, dan pengajian turats (Ngaji Malam)"],
                                        ["waktu" => "20.30 — 21.45 WIB", "judul" => "Belajar Mandiri Terbimbing (Muthola'ah)", "keterangan" => "Mempersiapkan pelajaran esok hari, hafalan mufradat dan bimbingan musyrif kamar"],
                                        ["waktu" => "21.45 — 22.00 WIB", "judul" => "Absensi Malam & Istirahat Tidur", "keterangan" => "Pemeriksaan kehadiran santri oleh pengasuhan dan istirahat malam tertib"]
                                    ];
                                }
                            @endphp
                            @foreach($listJadwal as $jItem)
                                <div class="timeline-entry">
                                    <div class="timeline-bullet">
                                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </div>
                                    <div class="timeline-detail">
                                        <div class="timeline-time">{{ $jItem['waktu'] }}</div>
                                        <div class="timeline-desc">{{ $jItem['judul'] }}</div>
                                        @if(!empty($jItem['keterangan']))
                                            <div class="timeline-sub">{{ $jItem['keterangan'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @php
                        $agendaBerkalaList = json_decode(\App\Models\Setting::get('agenda_berkala_json', '[]'), true) ?: [
                            ['hari' => 'Ahad', 'kegiatan' => "Simakan Al-Qur'an & Latihan Pencak Silat", 'keterangan' => "Tasmi' Al-Qur'an santri putri ba'da Shubuh, silat pagi, dan Sholawat Simtudduror malam"],
                            ['hari' => 'Senin', 'kegiatan' => 'Upacara Bendera Kebangsaan', 'keterangan' => 'Pembinaan kedisiplinan dan rasa cinta tanah air di halaman kampus'],
                            ['hari' => 'Kamis', 'kegiatan' => 'Muhadhoroh 3 Bahasa & Ziarah', 'keterangan' => "Latihan pidato (Arab, Inggris, Indonesia), ziarah makam, dan pembacaan Diba'"],
                            ['hari' => 'Jumat', 'kegiatan' => 'Mujahadah Rutin Bersama Masyarakat', 'keterangan' => 'Doa bersama warga sekitar pondok mempererat jalinan ukhuwah sosial'],
                            ['hari' => 'Sabtu', 'kegiatan' => 'Kepanduan Pramuka Wajib', 'keterangan' => 'Latihan keterampilan survival, pionering, dan kepemimpinan di alam terbuka'],
                            ['hari' => 'Bulanan & Tahunan', 'kegiatan' => "Selapanan Wali Santri & Khutbatul 'Arsy", 'keterangan' => 'Silaturahmi Ahad Legi, Panggung Gembira (PG) kelas 6 TMI, dan wisuda hafidz'],
                        ];
                    @endphp
                    <div class="agenda-card anim-fade-up">
                        <div>
                            <h3>Agenda Berkala Santri</h3>
                            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Kegiatan mingguan,
                                bulanan, dan tahunan yang melatih keberanian, syiar, dan wawasan santri.</p>
                        </div>
                        <div class="agenda-items">
                            @foreach ($agendaBerkalaList as $agenda)
                            <div class="agenda-item">
                                <div class="agenda-day">{{ $agenda['hari'] ?? '' }}</div>
                                <div class="agenda-activity">{{ $agenda['kegiatan'] ?? '' }}</div>
                                <div class="agenda-note">{!! nl2br(e($agenda['keterangan'] ?? '')) !!}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== TATA TERTIB & DISIPLIN SANTRI (FROM BUKU PANDUAN) ===== -->
        <section class="section-tertib" id="tata-tertib">
            <div class="container">
                <div class="tertib-header anim-fade-up">
                    <span class="section-label">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        Pedoman Kedisiplinan
                    </span>
                    <h2 class="section-title">Tata Tertib & Kode Etik Kehidupan Santri</h2>
                    <p class="section-desc">Ketentuan resmi Buku Panduan Santri Bab I — XIV untuk menciptakan iklim
                        belajar yang khusyuk, aman, bersih, dan beradab luhur.</p>
                </div>

                <div class="tertib-grid">
                    <div class="tertib-card anim-fade-up">
                        <div class="tertib-icon">
                            <svg class="icon-svg icon-svg-md" viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                <polyline points="9 12 11 14 15 10"></polyline>
                            </svg>
                        </div>
                        <h3>Disiplin Ibadah & Masjid</h3>
                        <ul class="tertib-list">
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Wajib shalat fardhu berjamaah 5 waktu tepat waktu di masjid.</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Tiba di masjid 5 menit sebelum adzan berkumandang.</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Mengamalkan dzikir ma'tsurat, shalat sunnah rawatib & shalat dhuha.</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Shalat tarawih berjamaah selama bulan suci Ramadhan.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="tertib-card anim-fade-up">
                        <div class="tertib-icon">
                            <svg class="icon-svg icon-svg-md" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path
                                    d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                </path>
                            </svg>
                        </div>
                        <h3>Disiplin Bahasa Resmi</h3>
                        <ul class="tertib-list">
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Wajib berkomunikasi dwibahasa aktif (Bahasa Arab dan Inggris).</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Bergantian jadwal bahasa resmi setiap pekan di lingkungan asrama.</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Mengikuti pemberian mufrodat kosakata harian ba'da subuh.</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Wajib tampil dalam Muhadhoroh pidato 3 bahasa secara bergiliran.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="tertib-card anim-fade-up">
                        <div class="tertib-icon">
                            <svg class="icon-svg icon-svg-md" viewBox="0 0 24 24">
                                <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"></path>
                                <line x1="16" y1="8" x2="2" y2="22"></line>
                                <line x1="17.5" y1="15" x2="9" y2="15"></line>
                            </svg>
                        </div>
                        <h3>Adab & Kerapian Busana</h3>
                        <ul class="tertib-list">
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Berbusana syar'i, menutup aurat, sopan, bersih, dan berpeci/berjilbab rapi.</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Rambut rapi standar santri, kuku bersih, dan menjaga adab bertutur kata.</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Dilarang pakaian ketat, berbahan jeans, atau bergambar makhluk bernyawa.</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Menghormati pimpinan pondok, asatidz, asatidzah, serta sesama santri.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="tertib-card anim-fade-up">
                        <div class="tertib-icon">
                            <svg class="icon-svg icon-svg-md" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <h3>Regulasi Gadget & Perizinan</h3>
                        <ul class="tertib-list">
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Dilarang keras membawa smartphone/gadget pribadi di lingkungan pondok.</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Komunikasi orang tua melalui layanan resmi pesantren atau jadwal telepon
                                    teratur.</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Bebas dari rokok, perundungan, senjata tajam, dan perbuatan indisipliner.</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-xs t-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Perizinan keluar pondok harus melalui izin tertulis bagian Pengasuhan
                                    Santri.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== QUOTE BANNER ===== -->
        <section class="section-quote">
            <div class="container">
                <div class="quote-banner anim-fade-up">
                    <div class="quote-content">
                        <div class="label">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                            {!! \App\Models\Setting::get('quran_label', 'Al-Qur\'an Surah Al-Mujadilah : 11') !!}
                        </div>
                        <blockquote>{!! \App\Models\Setting::get('quran_quote', '"Allah akan meninggikan orang-orang yang beriman di antaramu dan orang-orang yang diberi ilmu pengetahuan beberapa derajat."') !!}</blockquote>
                        <p class="quote-ref">{{ \App\Models\Setting::get('quran_desc', 'Prinsip keselarasan antara kemurnian tauhid dan kedalaman ilmu pengetahuan yang menuntun setiap langkah pengasuhan di Pondok Pesantren Hidayatullah Tuksongo.') }}</p>
                    </div>
                    <div class="quote-icon">
                        <svg class="icon-svg icon-svg-xl" viewBox="0 0 24 24">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== FASILITAS ===== -->
        <section class="section-fasilitas" id="fasilitas">
            <div class="container">
                <div class="fasilitas-header anim-fade-up">
                    <div>
                        <span class="section-label">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            Sarana & Prasarana Kampus
                        </span>
                        <h2 class="section-title">Lingkungan Pembelajaran Representatif</h2>
                    </div>
                    <p class="section-desc">Fasilitas terpadu berarsitektur islami di area asri Pringsurat Temanggung
                        yang menunjang ketenangan spiritual, kesehatan fisik, dan prestasi akademik santri.</p>
                </div>
                <div class="fasilitas-grid">
                    <!-- 1. Masjid Jami' Hidayatullah -->
                    <div class="fasilitas-card anim-fade-up">
                        <img class="fasilitas-card-bg-img" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAU6a2L-sDyoOwD_72rgteFhrsAe8symZKQSYIa4qFEcX_x_0urIjNrwIq3gKCYYSWQMViH-sIYhzZkjwLeKZYtA0h99F2ZNQIy89TBXob6RrVInKoij1Pklw1sIhPQR_Dytb1XlvsZLNmFwCeh1jHMyRlG-pJhDilfgIfiVT9KbgclTW_oorWofU9BoyAw3M5oihmSeX4mJM_OpdNIwKLugn0Icz7_QgEB7ev6T7fMhIM4W6RsAqbH" alt="Masjid Jami' Hidayatullah" loading="lazy">
                        <div class="fasilitas-card-gradient"></div>
                        <div class="fasilitas-card-top">
                            <span class="fasilitas-card-tag">
                                <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                                Masjid Jami' Hidayatullah
                            </span>
                        </div>
                        <div class="fasilitas-card-content">
                            <h3 class="fasilitas-card-title">Masjid Jami' Pesantren</h3>
                            <p class="fasilitas-card-desc">Pusat peribadatan utama shalat berjamaah 5 waktu, halaqah tasmi' Al-Qur'an, kajian hadits nabawi, dan pembacaan maulid mingguan.</p>
                        </div>
                    </div>

                    <!-- 2. Asrama Santri Sehat -->
                    <div class="fasilitas-card anim-fade-up">
                        <img class="fasilitas-card-bg-img" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCAdIufyUcLU26xaZ61J8_dbSfX-yPAG5ljh4y-di7Qqra8jK-JVkKDj-gBeDDI20axF6hvx7_wSSN3meuUuNZ7wHTriZV5srouq1o_BC9sjfRL5BkHT4M1O_4oQ6Z8XdNBBMRZQ1DurexAIsGNY_ZFnSJaAhZv_oajAAF8YA0asSodQhgePBVvbtLnw0QM5NRPg9i4aLPQam6rO6_VNKTaVQs4Ku1lngNeYAIR8ztkvLK5gFmt9y_R" alt="Asrama Santri Sehat" loading="lazy">
                        <div class="fasilitas-card-gradient"></div>
                        <div class="fasilitas-card-top">
                            <span class="fasilitas-card-tag">
                                <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
                                Asrama Santri Sehat
                            </span>
                        </div>
                        <div class="fasilitas-card-content">
                            <h3 class="fasilitas-card-title">Asrama Putra & Putri Terpisah</h3>
                            <p class="fasilitas-card-desc">Kamar asrama berpenerangan optimal, ventilasi alami perbukitan Pringsurat, almari santri individu, dan pendampingan wali kamar 24 jam.</p>
                        </div>
                    </div>

                    <!-- 3. Laboratorium Sains -->
                    <div class="fasilitas-card anim-fade-up">
                        <img class="fasilitas-card-bg-img" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCRLDfaZnczeAOBMNdv3-O4fNJHuDHanm-hedGFiVj5hrUReVRsvzSgPxwKkbDQARwwCVj_-x201mE14Tj0x4r9wU1TIAWEkbejJX13PHjnbc0EMVq0strXuHCkm6RrQ7tXgFYIMTenYyYWuIW9h3zuoLAIWOfO-5Ihuu7qRkCD85qKWlmjCKqaQQFUDKhO25Yb18hCopf6DEGrLFi6iHqD7S18tpGA2HC8TgOqEcsZZocUAxzsq0lq" alt="Laboratorium Sains" loading="lazy">
                        <div class="fasilitas-card-gradient"></div>
                        <div class="fasilitas-card-top">
                            <span class="fasilitas-card-tag">
                                <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24"><path d="M10 2v7.31L4.41 19A2 2 0 0 0 6 22h12a2 2 0 0 0 1.59-3L14 9.31V2"></path></svg>
                                Laboratorium Sains
                            </span>
                        </div>
                        <div class="fasilitas-card-content">
                            <h3 class="fasilitas-card-title">Laboratorium Sains & Komputer</h3>
                            <p class="fasilitas-card-desc">Sarana praktikum fisika, biologi, kimia, dan lab komputer digital untuk mengasah literasi teknologi santri madrasah.</p>
                        </div>
                    </div>

                    <!-- 4. Perpustakaan & Turats -->
                    <div class="fasilitas-card anim-fade-up">
                        <img class="fasilitas-card-bg-img" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBgackmlJNWMj8ykst-J3dloc8Vc0gC7YhqnQ7Isv4vtgrT4r0vG1hcaUPgEdGIR0XDtSV96zh7RqzBkDml_Qz7BJheMnpFqFUGx4a9nyCyDAVejEON4xLGZZFwRbxsT08deMXku5kRQ290jNBhBTOLWOgXYIyaXLSCq6g-QW7_P1wDAQKlzs0ZA-95wrmKpVgtgTuZKUMhPZF1Bb31F6SqtbEvhnYYy7JGhI8kbuCd4stp5rmiCfgz" alt="Perpustakaan & Turats" loading="lazy">
                        <div class="fasilitas-card-gradient"></div>
                        <div class="fasilitas-card-top">
                            <span class="fasilitas-card-tag">
                                <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                Perpustakaan & Turats
                            </span>
                        </div>
                        <div class="fasilitas-card-content">
                            <h3 class="fasilitas-card-title">Perpustakaan Kitab Turats & Pengetahuan</h3>
                            <p class="fasilitas-card-desc">Koleksi kitab rujukan bahasa Arab, buku referensi kurikulum Kemenag, ruang baca nyaman, dan pusat kajian muallimin.</p>
                        </div>
                    </div>

                    <!-- 5. Kompleks Olahraga -->
                    <div class="fasilitas-card anim-fade-up">
                        <img class="fasilitas-card-bg-img" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCm0uFhcuuSGhojqfIRGrLu2zQVLvVBvOIjUyEU5_FgEcO5WlCVLRZn5PxszCuJAgfMErlt0IWbKB_FZudBb7QA4jVi-Joa0fMqphmkE_WzeajuRZ22kcPwzd9sz7tgWEdSbm0-2zEgjneNqLNyeBzGBCGy-HyhslYe7UQ6lWNLn0XewuZliSWthzr4qygbQfE5qHFKraLRMESrRI8VJ1MJSxc1R1Owf3JMMpyx8l4fEC27K_GXy7BX" alt="Kompleks Olahraga" loading="lazy">
                        <div class="fasilitas-card-gradient"></div>
                        <div class="fasilitas-card-top">
                            <span class="fasilitas-card-tag">
                                <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
                                Kompleks Olahraga
                            </span>
                        </div>
                        <div class="fasilitas-card-content">
                            <h3 class="fasilitas-card-title">Lapangan Olahraga & Bela Diri</h3>
                            <p class="fasilitas-card-desc">Area futsal, bulutangkis, bola voli, arena latihan pencak silat santri, dan kegiatan kepanduan Pramuka.</p>
                        </div>
                    </div>

                    <!-- 6. Pos Kesehatan Pesantren -->
                    <div class="fasilitas-card anim-fade-up">
                        <img class="fasilitas-card-bg-img" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBZHeIxAIcdIvaizS-iBy0iQtNF5rbrGlmdxDVcLS9wReOGG81d7womhqurfN39NuFAU24BNsukyrd6qc5U_XcTdHPGieMWHTVf7Z4236LXr88zHDdDXRLZ3VKSMUBvDcwjE4RXtbZdpJZcPo5HQysIIsEnscHm6fbWL4nab8tqO5YrpRAhDr-7Fam7Acma7ZJUqbIFpMMtRbigI76LnJU6OQhg5EuEil7-tLdBStcwSrm9l_fs6Sxo" alt="Pos Kesehatan Pesantren" loading="lazy">
                        <div class="fasilitas-card-gradient"></div>
                        <div class="fasilitas-card-top">
                            <span class="fasilitas-card-tag">
                                <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                                Pos Kesehatan Pesantren
                            </span>
                        </div>
                        <div class="fasilitas-card-content">
                            <h3 class="fasilitas-card-title">Pos Kesehatan Santri (Poskestren)</h3>
                            <p class="fasilitas-card-desc">Penanganan pertama medis, koordinasi puskesmas setempat, ruang isolasi santri saat sakit, dan pencatatan riwayat kesehatan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== PSB & RINCIAN BIAYA RESMI TA 2025/2026 (FROM BUKU PANDUAN) ===== -->
        <section class="section-psb" id="psb">
            <div class="container">
                <div class="psb-header anim-fade-up">
                    <span class="section-label">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        Penerimaan Santri Baru TA 2025/2026
                    </span>
                    <h2 class="section-title">Informasi Pendaftaran & Rincian Biaya Transparan</h2>
                    <p class="section-desc">Rincian resmi ketetapan pimpinan Pondok Pesantren Hidayatullah Tuksongo
                        tahun 2025 untuk jenjang MTs dan MA secara terbuka dan amanah.</p>
                </div>

                <div class="psb-steps anim-fade-up">
                    <div class="psb-step">
                        <div class="psb-step-num">1</div>
                        <h4>Pendaftaran & Berkas</h4>
                        <p>Mengisi data formulir resmi, memilih jenjang MTs atau MA (Mukim/Laju), dan menyerahkan
                            dokumen persyaratan.</p>
                        <span class="period">Gelombang 1: Dibuka Sekarang</span>
                    </div>
                    <div class="psb-step">
                        <div class="psb-step-num">2</div>
                        <h4>Tes Seleksi & Wawancara</h4>
                        <p>Tes lisan baca Al-Qur'an, tes kemampuan dasar, dan wawancara kesiapan santri serta orang tua.
                        </p>
                        <span class="period">Ujian Terjadwal</span>
                    </div>
                    <div class="psb-step">
                        <div class="psb-step-num">3</div>
                        <h4>Pengumuman Kelulusan</h4>
                        <p>Hasil kelulusan diinformasikan langsung oleh panitia PSB via WhatsApp dan sekretariat
                            madrasah.</p>
                        <span class="period">Pasca Ujian Seleksi</span>
                    </div>
                    <div class="psb-step">
                        <div class="psb-step-num">4</div>
                        <h4>Registrasi Ulang</h4>
                        <p>Penyelesaian administrasi keuangan, pengambilan perlengkapan asrama, dan persiapan masuk
                            kampus.</p>
                        <span class="period">Sesuai Kalender Akademik</span>
                    </div>
                </div>

                <!-- TABEL RINCIAN BIAYA DARI PDF -->
                <div class="biaya-section-wrap anim-fade-up">
                    <div class="biaya-header">
                        <div>
                            <span class="section-label" style="color: var(--green-700);">Rincian Biaya Resmi Masuk
                                Santri Baru TA 2025/2026</span>
                            <h3>Tabel Rincian Biaya Awal (Masuk Pertama Kali)</h3>
                        </div>
                        <p style="font-size: 13px; color: var(--text-muted); max-width: 400px;">Biaya sudah mencakup
                            infaq pangkal, gedung, kasur/almari santri mukim, kertas/ujian 1 th, kesehatan 1 th,
                            kegiatan 1 th, serta syahriyah & uang makan bulan pertama.</p>
                    </div>

                    @php
                        $biayaAwalData = json_decode(\App\Models\Setting::get('biaya_awal_json', 'null'), true) ?: [
                            ['komponen' => 'Uang Pangkal', 'mts_mukim' => 'Rp 1.200.000', 'mts_laju' => 'Rp 1.200.000', 'ma_mukim' => 'Rp 1.400.000', 'ma_laju' => 'Rp 1.400.000', 'is_total' => false],
                            ['komponen' => 'Uang Gedung', 'mts_mukim' => 'Rp 500.000', 'mts_laju' => 'Rp 500.000', 'ma_mukim' => 'Rp 500.000', 'ma_laju' => 'Rp 500.000', 'is_total' => false],
                            ['komponen' => 'Almari & Fasilitas Kamar', 'mts_mukim' => 'Rp 350.000', 'mts_laju' => '—', 'ma_mukim' => 'Rp 350.000', 'ma_laju' => '—', 'is_total' => false],
                            ['komponen' => 'Kertas / Evaluasi Belajar (1 Tahun)', 'mts_mukim' => 'Rp 140.000', 'mts_laju' => 'Rp 140.000', 'ma_mukim' => 'Rp 160.000', 'ma_laju' => 'Rp 160.000', 'is_total' => false],
                            ['komponen' => 'Kesehatan Santri (1 Tahun)', 'mts_mukim' => 'Rp 200.000', 'mts_laju' => 'Rp 200.000', 'ma_mukim' => 'Rp 200.000', 'ma_laju' => 'Rp 200.000', 'is_total' => false],
                            ['komponen' => 'Kegiatan Santri (1 Tahun)', 'mts_mukim' => 'Rp 300.000', 'mts_laju' => 'Rp 300.000', 'ma_mukim' => 'Rp 300.000', 'ma_laju' => 'Rp 300.000', 'is_total' => false],
                            ['komponen' => 'Syahriyah (Bulan Pertama)', 'mts_mukim' => 'Rp 85.000', 'mts_laju' => 'Rp 55.000', 'ma_mukim' => 'Rp 105.000', 'ma_laju' => 'Rp 75.000', 'is_total' => false],
                            ['komponen' => 'Uang Makan (Bulan Pertama)', 'mts_mukim' => 'Rp 300.000', 'mts_laju' => '—', 'ma_mukim' => 'Rp 300.000', 'ma_laju' => '—', 'is_total' => false],
                            ['komponen' => 'TOTAL BIAYA DAFTAR ULANG', 'mts_mukim' => 'Rp 3.225.000', 'mts_laju' => 'Rp 2.395.000', 'ma_mukim' => 'Rp 3.315.000', 'ma_laju' => 'Rp 2.635.000', 'is_total' => true],
                        ];

                        $biayaBulananData = json_decode(\App\Models\Setting::get('biaya_bulanan_json', 'null'), true) ?: [
                            ['komponen' => 'Uang Makan 3x Sehari', 'mts_mukim' => 'Rp 300.000', 'mts_laju' => '—', 'ma_mukim' => 'Rp 300.000', 'ma_laju' => '—', 'is_total' => false],
                            ['komponen' => 'Syahriyah Pendidikan', 'mts_mukim' => 'Rp 85.000', 'mts_laju' => 'Rp 55.000', 'ma_mukim' => 'Rp 105.000', 'ma_laju' => 'Rp 75.000', 'is_total' => false],
                            ['komponen' => 'Tabungan Wajib Santri', 'mts_mukim' => 'Rp 25.000', 'mts_laju' => 'Rp 25.000', 'ma_mukim' => 'Rp 25.000', 'ma_laju' => 'Rp 25.000', 'is_total' => false],
                            ['komponen' => 'TOTAL IURAN BULANAN', 'mts_mukim' => 'Rp 410.000 / bln', 'mts_laju' => 'Rp 80.000 / bln', 'ma_mukim' => 'Rp 430.000 / bln', 'ma_laju' => 'Rp 100.000 / bln', 'is_total' => true],
                        ];
                    @endphp

                    <div class="biaya-table-responsive">
                        <table class="biaya-table">
                            <thead>
                                <tr>
                                    <th>Komponen Pembayaran</th>
                                    <th>MTs Mukim (Asrama)</th>
                                    <th>MTs Laju (Non-Asrama)</th>
                                    <th>MA Mukim (Asrama)</th>
                                    <th>MA Laju (Non-Asrama)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($biayaAwalData as $row)
                                    <tr class="{{ !empty($row['is_total']) ? 'total-row' : '' }}">
                                        <td>{{ $row['komponen'] ?? '' }}</td>
                                        <td>{{ $row['mts_mukim'] ?? '—' }}</td>
                                        <td>{{ $row['mts_laju'] ?? '—' }}</td>
                                        <td>{{ $row['ma_mukim'] ?? '—' }}</td>
                                        <td>{{ $row['ma_laju'] ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- TABEL BIAYA BULANAN -->
                    <div style="margin-top: 10px; margin-bottom: 24px;">
                        <h4
                            style="font-family: 'EB Garamond', serif; font-size: 20px; color: var(--green-900); margin-bottom: 8px;">
                            Rincian Biaya Bulanan (SPP/Syahriyah Rutin)</h4>
                        <div class="biaya-table-responsive">
                            <table class="biaya-table">
                                <thead>
                                    <tr>
                                        <th>Rincian Rutin Bulanan</th>
                                        <th>MTs Mukim</th>
                                        <th>MTs Laju</th>
                                        <th>MA Mukim</th>
                                        <th>MA Laju</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($biayaBulananData as $row)
                                        <tr class="{{ !empty($row['is_total']) ? 'total-row' : '' }}">
                                            <td>{{ $row['komponen'] ?? '' }}</td>
                                            <td>{{ $row['mts_mukim'] ?? '—' }}</td>
                                            <td>{{ $row['mts_laju'] ?? '—' }}</td>
                                            <td>{{ $row['ma_mukim'] ?? '—' }}</td>
                                            <td>{{ $row['ma_laju'] ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p style="font-size: 11px; color: var(--text-muted); font-style: italic;">* Pembayaran
                            administrasi bulanan disetorkan selambat-lambatnya sebelum tanggal 10 setiap bulannya.</p>
                    </div>

                    <!-- REKENING DAN PERSYARATAN -->
                    <div class="psb-bottom-grid">
                        <div class="rek-card">
                            <div class="rek-bank-badge">BANK BRI RESMI PESANTREN</div>
                            <h4>
                                <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                Pembayaran Transfer / Online
                            </h4>
                            <div class="rek-number">0102-01-022009-53-7</div>
                            <div class="rek-owner">Atas Nama: <strong>DIKY FACHRI HUSEIN</strong></div>
                            <p
                                style="font-size: 12px; color: var(--text-secondary); line-height: 1.5; margin-bottom: 14px;">
                                Setelah melakukan transfer, silakan konfirmasi bukti transfer dengan format: <em>(Nama
                                    Santri, Foto/Screenshot Struk, dan Jenis Pembayaran)</em> kepada bendahara pondok.
                            </p>
                            <a class="rek-wa-btn"
                                href="https://wa.me/6285290429617?text=Assalamu'alaikum%2C%20saya%20ingin%20konfirmasi%20pembayaran%20administrasi%20santri%20Pondok%20Hidayatullah%20Tuksongo."
                                target="_blank">
                                <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                    <path
                                        d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                                    </path>
                                </svg>
                                Konfirmasi via WhatsApp (0852-9042-9617)
                            </a>
                        </div>

                        <div class="syarat-card">
                            <h4>
                                <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                                Persyaratan Berkas Santri Baru
                            </h4>
                            <ul class="syarat-list">
                                <li>
                                    <svg class="icon-svg icon-svg-xs s-check" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span>Fotokopi Nomor Induk Siswa Nasional (NISN)</span>
                                </li>
                                <li>
                                    <svg class="icon-svg icon-svg-xs s-check" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span>Fotokopi Akta Kelahiran santri</span>
                                </li>
                                <li>
                                    <svg class="icon-svg icon-svg-xs s-check" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span>Fotokopi Kartu Keluarga (KK) orang tua / wali</span>
                                </li>
                                <li>
                                    <svg class="icon-svg icon-svg-xs s-check" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span>Fotokopi Ijazah & SKHUN terakhir dilegalisir</span>
                                </li>
                                <li>
                                    <svg class="icon-svg icon-svg-xs s-check" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span>Surat Keterangan Sehat dari dokter / puskesmas</span>
                                </li>
                                <li>
                                    <svg class="icon-svg icon-svg-xs s-check" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span>Fotokopi Kartu KIP / PKH (bagi yang memiliki)</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- OFFICIAL ONLINE PSB REGISTRATION CTA CARD -->
                    <div id="formulir-daftar" class="anim-fade-up"
                        style="margin-top: 48px; background: linear-gradient(135deg, #0d3b1e 0%, #1a6b38 100%); border-radius: var(--radius-md); padding: 48px 36px; box-shadow: var(--shadow-lg); text-align: center; color: #ffffff; position: relative; overflow: hidden;">
                        <div style="position: relative; z-index: 2; max-width: 720px; margin: 0 auto;">
                            <span class="section-label"
                                style="color: var(--gold-300); background: rgba(255,255,255,0.12); padding: 5px 16px; border-radius: 9999px; border: 1px solid rgba(255,255,255,0.2); font-size: 11px; letter-spacing: 0.12em;">PENERIMAAN
                                SANTRI BARU TA 2025/2026</span>
                            <h3
                                style="font-family: 'EB Garamond', serif; font-size: clamp(26px, 3.5vw, 34px); font-weight: 700; margin-top: 14px; margin-bottom: 12px; color: #ffffff; line-height: 1.25;">
                                Formulir Pendaftaran Resmi Santri Baru</h3>
                            <p
                                style="font-size: 14px; color: rgba(255,255,255,0.85); line-height: 1.7; margin-bottom: 28px;">
                                Daftarkan calon santri baru secara online melalui formulir digital terpadu pesantren.
                                Mendukung pilihan <strong>Jalur Reguler</strong>, <strong>Jalur Prestasi</strong>, dan
                                <strong>Jalur Tahfidz</strong> dengan sistem verifikasi berkas, pas foto 3x4, dan
                                konfirmasi WhatsApp langsung.
                            </p>
                            <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                                <a href="{{ route('psb.register') }}" class="btn-gold"
                                    style="font-size: 15px; padding: 14px 30px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 700;">
                                    <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                    Buka Formulir Pendaftaran Online
                                </a>
                                <a href="{{ route('psb.checkStatus') }}" class="btn-secondary"
                                    style="background: #ffffff; color: var(--green-950); font-weight: 700; border: none; display: inline-flex; align-items: center; gap: 8px; padding: 14px 24px; box-shadow: var(--shadow-md);" title="Cek status verifikasi formulir dan foto calon santri cukup dengan No. Registrasi atau WhatsApp">
                                    <svg class="icon-svg icon-svg-sm" style="color: var(--green-700);" viewBox="0 0 24 24">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                    🔍 Cek Status Pendaftaran
                                </a>
                                <a href="https://wa.me/6285290429617?text=Assalamu%27alaikum%2C+saya+ingin+konsultasi+mengenai+Penerimaan+Santri+Baru+Ponpes+Hidayatullah+Tuksongo."
                                    target="_blank" class="btn-secondary"
                                    style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.3); color: #ffffff; display: inline-flex; align-items: center; gap: 8px; padding: 14px 22px;">
                                    <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                        </path>
                                    </svg>
                                    Konsultasi Panitia PSB
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== BUKU PANDUAN BANNER ===== -->
        <section class="section-panduan" id="panduan">
            <div class="container">
                <div class="panduan-card anim-fade-up">
                    <div class="panduan-text">
                        <div class="label">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            Dokumen Resmi Pesantren
                        </div>
                        <h2>Buku Panduan Santri TA 2025/2026</h2>
                        <p>Unduh buku pedoman resmi setebal 81 halaman yang mencakup tata tertib, kurikulum mata
                            pelajaran MTs & MA, jadwal harian lengkap, daftar perlengkapan santri putra & putri, serta
                            nomor penting pengasuhan.</p>
                        <ul class="panduan-features">
                            <li>
                                <svg class="icon-svg icon-svg-sm check-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Tata Tertib, Kode Etik & Deskripsi Poin Disiplin Santri</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-sm check-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Jadwal Harian 24 Jam & Agenda Kegiatan Berkala</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-sm check-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Daftar Ceklis Perlengkapan Wajib Santri Putra & Putri</span>
                            </li>
                            <li>
                                <svg class="icon-svg icon-svg-sm check-icon" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Kurikulum Mapel Kemenag & Muatan Lokal Turats TMI</span>
                            </li>
                        </ul>
                        <a class="btn-gold" href="{{ \App\Models\Setting::get('panduan_file_url', '/NEW BUKU PANDUAN SANTRI TA 2025 (2).pdf') }}" target="_blank">
                            <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Unduh Buku Panduan Resmi (PDF)
                        </a>
                    </div>
                    <div class="panduan-visual">
                        <div class="panduan-pdf-preview">
                            <div class="panduan-pdf-icon-wrap">
                                <svg class="icon-svg icon-svg-xl" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                            </div>
                            <h4>Buku Panduan Santri</h4>
                            <p>Tahun Ajaran 2025/2026</p>
                            <p style="margin-top: 10px; color: rgba(255,255,255,0.4); font-size: 11px;">Format Dokumen PDF Resmi
                                • 81 Halaman</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!-- ===== KONTAK & LAYANAN RESMI (FROM BUKU PANDUAN) ===== -->
        <section class="section-kontak-wrap" id="kontak">
            <div class="container">
                <div class="kontak-header anim-fade-up">
                    <span class="section-label">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                            </path>
                        </svg>
                        Layanan Komunikasi
                    </span>
                    <h2 class="section-title">Nomor Penting & Layanan Resmi Pesantren</h2>
                    <p class="section-desc">Daftar kontak pengurus dan dewan pengasuhan resmi sebagaimana tercantum pada
                        buku panduan untuk kemudahan komunikasi orang tua / wali santri.</p>
                </div>

                <div class="kontak-grid anim-fade-up">
                    <div class="kontak-box">
                        <div class="k-icon">
                            <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </div>
                        <h4>Customer Service Pondok</h4>
                        <p>Pusat informasi umum dan penerimaan santri baru Tuksongo</p>
                        @php
                            $csWa = \App\Models\Setting::get('kontak_hotline', '0813-9110-9966');
                            $cleanCsWa = preg_replace('/[^0-9]/', '', $csWa);
                            if (str_starts_with($cleanCsWa, '0'))
                                $cleanCsWa = '62' . substr($cleanCsWa, 1);
                        @endphp
                        <a class="phone-link" href="https://wa.me/{{ $cleanCsWa }}" target="_blank">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                            {{ $csWa }}
                        </a>
                    </div>

                    <div class="kontak-box">
                        <div class="k-icon">
                            <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h4>Pengasuhan Putra & Putri</h4>
                        <p>Ustd. Khoerul Rokhim & Ustdzh. Binti Isnaini</p>
                        <a class="phone-link" href="https://wa.me/6288215217462" target="_blank"
                            style="display:block; margin-bottom:4px;">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                            Putra: 0882-1521-7462
                        </a>
                        <a class="phone-link" href="https://wa.me/6282136318239" target="_blank">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                            Putri: 0821-3631-8239
                        </a>
                    </div>

                    <div class="kontak-box">
                        <div class="k-icon">
                            <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                        </div>
                        <h4>Bidang Pengajaran & KBM</h4>
                        <p>Ustd. Didi Utama & Ustdzh. Laela Fitroti</p>
                        <a class="phone-link" href="https://wa.me/6285609201330" target="_blank"
                            style="display:block; margin-bottom:4px;">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                            Putra: 0856-0920-1330
                        </a>
                        <a class="phone-link" href="https://wa.me/6281326174337" target="_blank">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                            Putri: 0813-2617-4337
                        </a>
                    </div>

                    <div class="kontak-box">
                        <div class="k-icon">
                            <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                        </div>
                        <h4>Keuangan & Infaq Wakaf</h4>
                        <p>Konfirmasi SPP bulanan, tabungan & infaq</p>
                        <a class="phone-link" href="https://wa.me/6285290429617" target="_blank"
                            style="display:block; margin-bottom:4px;">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                            Keuangan: 0852-9042-9617
                        </a>
                        <a class="phone-link" href="https://wa.me/6282133425328" target="_blank">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                            Infaq/Saku: 0821-3342-5328
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <strong>Pondok Pesantren Hidayatullah Tuksongo</strong>
                    <p>Membentuk generasi Qur'ani yang berakhlak mulia, berwawasan global, mandiri, dan berakar kuat
                        pada nilai-nilai Panca Jiwa Pesantren.</p>
                    <div class="footer-accreditation">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Terakreditasi B (BAN-SM Kemenag) • NSPP: 512032304095
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Navigasi Utama</h4>
                    <a href="#beranda">Beranda</a>
                    <a href="{{ route('berita.index') }}">Warta & Arsip Berita</a>
                    <a href="#profil">Profil & Sejarah</a>
                    <a href="#program">Pilar Pendidikan</a>
                    <a href="#kehidupan">Santri 24 Jam</a>
                    <a href="#tata-tertib">Tata Tertib</a>
                    <a href="#psb">Biaya & Pendaftaran PSB</a>
                    <a href="{{ route('admin.dashboard') }}" target="_blank"
                        style="color: var(--gold-300); font-weight: 600; margin-top: 4px;">• TailAdmin Panel</a>
                </div>
                <div class="footer-col">
                    <h4>Alamat Lembaga</h4>
                    <div class="footer-contact-item">
                        <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span>{{ \App\Models\Setting::get('alamat_kampus', 'Dusun Tuksongo RT 01 / RW 01, Desa Nglorog, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah 56272') }}</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                            </path>
                        </svg>
                        <span>Hotline: 0813-9110-9966</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                            </path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <span>tuksongo.ponpes.id</span>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Media Sosial & Syiar</h4>
                    <p style="font-size:13px; margin-bottom:12px; color:rgba(255,255,255,0.6);">Ikuti syiar dakwah
                        harian, khutbah pekanan, dan prestasi para santri.</p>
                    <a href="{{ \App\Models\Setting::get('sosmed_instagram', '#') }}" target="_blank"
                        style="display:flex; align-items:center; gap:6px;">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                        Instagram Pesantren
                    </a>
                    <a href="{{ \App\Models\Setting::get('sosmed_youtube', '#') }}" target="_blank"
                        style="display:flex; align-items:center; gap:6px;">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <path
                                d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z">
                            </path>
                            <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                        </svg>
                        YouTube Official
                    </a>
                    <a href="{{ \App\Models\Setting::get('sosmed_facebook', '#') }}" target="_blank"
                        style="display:flex; align-items:center; gap:6px;">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                        Facebook Official
                    </a>
                    <a href="{{ \App\Models\Setting::get('sosmed_tiktok', '#') }}" target="_blank"
                        style="display:flex; align-items:center; gap:6px;">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
                        </svg>
                        TikTok Pondok
                    </a>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© 2025 Yayasan Hidayatullah Tuksongo Pringsurat Temanggung. Hak Cipta Dilindungi.</span>
                <div class="footer-bottom-links">
                    <a href="{{ \App\Models\Setting::get('brosur_file_url', '/uploads/settings/brosur_1788852849.jpeg') }}" target="_blank">Unduh Brosur PSB</a>
                    <a href="{{ \App\Models\Setting::get('panduan_file_url', '/NEW BUKU PANDUAN SANTRI TA 2025 (2).pdf') }}" target="_blank">Buku Panduan Santri (PDF)</a>
                    <a href="#tata-tertib">Tata Tertib Santri</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Scroll progress
        const scrollProgress = document.getElementById('scrollProgress');
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            scrollProgress.style.width = (scrollTop / docHeight) * 100 + '%';
        });

        // Mobile menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        const mobileNavClose = document.getElementById('mobileNavClose');
        mobileMenuBtn.addEventListener('click', () => mobileNav.classList.add('open'));
        mobileNavClose.addEventListener('click', () => mobileNav.classList.remove('open'));
        function closeMobileNav() { mobileNav.classList.remove('open'); }

        // Scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('visible'), i * 70);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });
        document.querySelectorAll('.anim-fade-up').forEach(el => observer.observe(el));

        // Active nav on scroll
        const sections = document.querySelectorAll('section[id]');
        const topNavLinks = document.querySelectorAll('.nav-links > a');
        const triggerTentang = document.getElementById('navDropdownTentang');
        const triggerPesantren = document.getElementById('navDropdownPesantren');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                if (window.scrollY >= section.offsetTop - 130) current = section.getAttribute('id');
            });
            topNavLinks.forEach(link => {
                link.classList.toggle('active', link.getAttribute('href') === '#' + current);
            });
            // highlight parent dropdowns if sub-section is active
            if (triggerTentang) {
                triggerTentang.classList.toggle('active', ['profil', 'fasilitas'].includes(current));
            }
            if (triggerPesantren) {
                triggerPesantren.classList.toggle('active', ['program', 'kehidupan', 'tata-tertib', 'panduan'].includes(current));
            }
        });
    </script>
</body>

</html>
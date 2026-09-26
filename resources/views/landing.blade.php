<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Pesantren Hidayatullah Tuksongo — Pringsurat Temanggung</title>
    <meta name="description"
        content="Website Resmi Pondok Pesantren Hidayatullah Tuksongo, Pringsurat, Temanggung. Memadukan kurikulum Kemenag (MTs-MA) dan tradisi kepesantrenan modern, tahfidz bersanad, bahasa Arab-Inggris aktif.">
    <!-- Favicon & Touch Icons (Google Search Standard) -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700&family=Grenze:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
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
            --gradient-hero: radial-gradient(circle at 18% 18%, rgba(34, 139, 76, 0.16) 0%, transparent 45%), radial-gradient(circle at 82% 80%, rgba(200, 164, 21, 0.12) 0%, transparent 40%), linear-gradient(145deg, #f0fdf4 0%, #dcfce7 32%, #edfbf2 65%, #fefce8 100%);
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
            font-family: 'Grenze', Georgia, serif;
            font-size: 13.5px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--green-700);
            margin-bottom: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .section-title {
            font-family: 'Grenze', Georgia, serif;
            font-size: clamp(30px, 4.2vw, 44px);
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
            letter-spacing: -0.01em;
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

        .navbar .container {
            max-width: 1280px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .navbar-brand .navbar-brand-crest {
            display: block;
            width: 38px;
            height: 38px;
            object-fit: contain;
            flex-shrink: 0;
            transition: transform 0.25s ease;
        }

        .navbar-brand:hover .navbar-brand-crest {
            transform: scale(1.05);
        }

        .navbar-brand-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            gap: 2px;
        }

        .navbar-brand .navbar-brand-calligraphy {
            height: 25px;
            width: auto;
            max-width: 205px;
            object-fit: contain;
            object-position: left center;
            filter: contrast(1.12);
            transition: all 0.25s ease;
        }

        .navbar-brand:hover .navbar-brand-calligraphy {
            opacity: 0.88;
        }

        .brand-latin-sub {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-size: 9px;
            font-weight: 600;
            color: #475569;
            letter-spacing: 0.25px;
            white-space: nowrap;
            line-height: 1.15;
            transition: color 0.2s ease;
        }

        .navbar-brand:hover .brand-latin-sub {
            color: var(--green-800);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-links>a,
        .nav-link-item,
        .nav-dropdown-trigger {
            font-family: 'Grenze', Georgia, serif;
            font-size: 18px;
            font-weight: 500;
            color: #111111;
            padding: 6px 4px;
            transition: all 0.2s ease;
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            background: none;
            border: none;
            text-decoration: none;
            letter-spacing: 0.45px;
            white-space: nowrap;
        }

        .nav-links>a:hover,
        .nav-link-item:hover,
        .nav-dropdown-trigger:hover,
        .nav-item-dropdown:hover .nav-dropdown-trigger {
            color: #006837;
            background: transparent;
        }

        .nav-links>a.active,
        .nav-link-item.active,
        .nav-dropdown-trigger.active {
            color: #006837;
            font-weight: 600;
            background: transparent;
        }

        .nav-links>a.active::after,
        .nav-link-item.active::after,
        .nav-dropdown-trigger.active::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 5%;
            width: 90%;
            height: 2.5px;
            background-color: #006837;
            border-radius: 9999px;
        }

        .nav-dropdown-trigger .chevron-icon {
            transition: transform 0.25s ease;
            color: #64748b;
            width: 12px;
            height: 12px;
            margin-left: 2px;
        }

        .nav-item-dropdown:hover .chevron-icon {
            transform: rotate(180deg);
            color: #006837;
        }

        /* PENDAFTARAN BUTTON (PENGGANTI PSB SESUAI DESAIN GONTOR) */
        .btn-nav-pendaftaran {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #0d3b1e;
            color: #ffffff !important;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-weight: 600;
            font-size: 13.5px;
            letter-spacing: 0.2px;
            padding: 7px 18px;
            border-radius: 9999px;
            box-shadow: 0 3px 10px rgba(13, 59, 30, 0.2);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            border: none;
            cursor: pointer;
            margin-left: 6px;
        }

        .btn-nav-pendaftaran:hover {
            background: #006837;
            box-shadow: 0 5px 14px rgba(0, 104, 55, 0.28);
            transform: translateY(-1px);
            color: #ffffff !important;
        }

        .btn-nav-pendaftaran .chevron-icon {
            width: 11px;
            height: 11px;
            stroke-width: 2.4;
            transition: transform 0.25s ease;
            color: rgba(255, 255, 255, 0.85);
        }

        .nav-item-dropdown:hover .btn-nav-pendaftaran .chevron-icon {
            transform: rotate(180deg);
            color: #ffffff;
        }

        .nav-dropdown-menu-right {
            left: auto !important;
            right: 0 !important;
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
            white-space: nowrap;
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
            font-family: 'Grenze', Georgia, serif;
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
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 60px -15px rgba(13, 59, 30, 0.32), 0 10px 25px -5px rgba(0, 0, 0, 0.08);
            border: none !important;
            /* Tanpa border/batasan sesuai permintaan */
            aspect-ratio: 4/3;
            background: #0d3b1e;
        }

        .hero-slider {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .hero-slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.8s;
            will-change: opacity, transform;
            z-index: 1;
        }

        .hero-slide.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            z-index: 2;
        }

        .hero-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Animasi Model: Zoom */
        .hero-slider[data-animation="zoom"] .hero-slide {
            transform: scale(1.08);
            transition: opacity 0.9s ease, transform 6s ease-out;
        }

        .hero-slider[data-animation="zoom"] .hero-slide.active {
            transform: scale(1);
        }

        /* Animasi Model: Slide */
        .hero-slider[data-animation="slide"] .hero-slide {
            transform: translateX(100%);
        }

        .hero-slider[data-animation="slide"] .hero-slide.active {
            transform: translateX(0);
        }

        .hero-slider[data-animation="slide"] .hero-slide.prev-out {
            transform: translateX(-100%);
            opacity: 0;
        }

        /* Navigation Arrows */
        .hero-slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: none;
            color: #0d3b1e;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            opacity: 0;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
        }

        .hero-slider:hover .hero-slider-btn {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .hero-slider-btn {
                opacity: 0.85 !important;
            }
        }

        .hero-slider-btn:hover {
            background: #ffffff;
            color: #006837;
            transform: translateY(-50%) scale(1.1);
        }

        .hero-slider-btn.prev {
            left: 14px;
        }

        .hero-slider-btn.next {
            right: 14px;
        }

        /* Navigation Dots */
        .hero-slider-dots {
            position: absolute;
            bottom: 14px;
            right: 18px;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 10;
            background: rgba(8, 36, 18, 0.45);
            padding: 5px 10px;
            border-radius: 9999px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        .hero-slider-dot {
            width: 8px;
            height: 8px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.45);
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
            padding: 0;
        }

        .hero-slider-dot.active {
            width: 20px;
            background: #e8cc5a;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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

        /* ===== PRESTASI SANTRI & LEMBAGA ===== */
        .section-prestasi,
        .section-fasilitas {
            padding: 85px 0;
            background: var(--surface-dim);
            position: relative;
        }

        .prestasi-header,
        .fasilitas-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 40px;
            gap: 24px;
        }

        .prestasi-grid,
        .fasilitas-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 26px;
        }

        .prestasi-card,
        .fasilitas-card {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            min-height: 400px;
            height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            box-shadow: 0 10px 28px rgba(8, 36, 18, 0.14);
            border: 1px solid rgba(8, 36, 18, 0.12);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease, border-color 0.4s ease;
            background: #082412;
            text-decoration: none;
        }

        .prestasi-card:hover,
        .fasilitas-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 46px rgba(8, 36, 18, 0.32);
            border-color: rgba(200, 164, 21, 0.6);
        }

        .prestasi-card-bg-img,
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

        .prestasi-card:hover .prestasi-card-bg-img,
        .fasilitas-card:hover .fasilitas-card-bg-img {
            transform: scale(1.08);
        }

        .prestasi-card-gradient,
        .fasilitas-card-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(8, 36, 18, 0.15) 0%, rgba(8, 36, 18, 0.48) 36%, rgba(6, 30, 15, 0.90) 70%, #04160b 100%);
            z-index: 1;
            transition: background 0.4s ease;
        }

        .prestasi-card:hover .prestasi-card-gradient,
        .fasilitas-card:hover .fasilitas-card-gradient {
            background: linear-gradient(180deg, rgba(8, 36, 18, 0.08) 0%, rgba(8, 36, 18, 0.52) 30%, rgba(6, 30, 15, 0.96) 66%, #020f07 100%);
        }

        .prestasi-card-top,
        .fasilitas-card-top {
            position: absolute;
            top: 18px;
            left: 18px;
            right: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            z-index: 2;
        }

        .prestasi-card-tag,
        .fasilitas-card-tag {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(8px);
            color: #082412;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.03em;
            padding: 5px 13px;
            border-radius: 9999px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .prestasi-card-level {
            background: rgba(200, 164, 21, 0.92);
            color: #082412;
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 9999px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .prestasi-card-content,
        .fasilitas-card-content {
            position: relative;
            z-index: 2;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .prestasi-card-title,
        .fasilitas-card-title {
            font-family: 'Grenze', Georgia, serif;
            font-size: 23px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.28;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.45);
        }

        .prestasi-card-desc,
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
        }

        .pimpinan-quote blockquote {
            font-family: 'Grenze', Georgia, serif;
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
            font-family: 'Grenze', Georgia, serif;
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

            .prestasi-grid,
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

            .prestasi-grid,
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
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
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

    <!-- OFFICIAL NAVBAR (SHARED WITH BIAYA & PUBLIC PAGES) -->
    @include('partials.navbar', ['isLanding' => true])

    <main>
        <!-- ===== HERO ===== -->
        <section class="hero" id="beranda">
            <div class="container">
                <div class="hero-grid">
                    <div class="hero-content">
                        @php
                            $psbSched = \App\Models\Setting::getPsbSchedule();
                            $isPsbOpen = $psbSched['is_open'];
                            $psbBadgeText = $psbSched['badge'];
                        @endphp
                        @if($isPsbOpen)
                            <div class="hero-badge">
                                <div class="hero-badge-dot"></div>
                                <span>{!! \App\Models\Setting::get('hero_badge', 'PSB TA ' . \App\Models\Setting::get('tahun_ajaran', '2026/2027') . ' Telah Dibuka') !!} &bull; {{ $psbSched['gelombang'] ?? 'Gelombang 1' }}</span>
                            </div>
                        @elseif($psbSched['status'] === 'belum_buka')
                            <div class="hero-badge" style="background: rgba(245, 158, 11, 0.18); border-color: rgba(245, 158, 11, 0.4); color: #fde68a;">
                                <div class="hero-badge-dot" style="background: #f59e0b; box-shadow: 0 0 10px #f59e0b;"></div>
                                <span>Pendaftaran Dibuka: {{ $psbSched['start_formatted'] ?? 'Segera' }} ({{ $psbSched['gelombang'] ?? 'Gelombang 1' }})</span>
                            </div>
                        @endif
                        <h1 class="hero-title">
                            {!! \App\Models\Setting::get('hero_title', "Membentuk Generasi <em>Qur'ani</em>, Berakhlak Mulia & Berwawasan Global") !!}
                        </h1>
                        <p class="hero-subtitle">
                            {{ \App\Models\Setting::get('hero_subtitle', 'Sinergi pendidikan integral Pondok Pesantren Hidayatullah Tuksongo yang memadukan bimbingan Tahfidzul Qur\'an mutqin, kurikulum formal Kemenag, pembinaan akhlak santri 24 jam, dan kemandirian hidup.') }}
                        </p>
                        <div class="hero-actions">
                            @if($isPsbOpen)
                                <a class="btn-primary" href="{{ route('psb.register') }}">
                                    Daftar ({{ $psbSched['gelombang'] ?? 'Gelombang 1' }})
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </a>
                            @else
                                <a class="btn-primary" href="{{ route('psb.register') }}" style="background: linear-gradient(135deg, #475569, #334155); border-color: #64748b; color: #ffffff;">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    Pendaftaran ({{ $psbBadgeText }})
                                </a>
                            @endif
                            <a class="btn-secondary"
                                href="{{ \App\Models\Setting::get('brosur_file_url', '/uploads/settings/brosur_1788852849.jpeg') }}"
                                target="_blank">
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
                        @php
                            $heroSlideCount = (int) \App\Models\Setting::get('hero_slide_count', '5');
                            if ($heroSlideCount < 3)
                                $heroSlideCount = 3;
                            if ($heroSlideCount > 5)
                                $heroSlideCount = 5;
                            $heroAnimation = \App\Models\Setting::get('hero_slider_animation', 'fade');
                            $heroDuration = (int) \App\Models\Setting::get('hero_slider_duration', '5');
                            if ($heroDuration < 2)
                                $heroDuration = 5;

                            $rawSlides = \App\Models\Setting::get('hero_slides_json');
                            $heroSlidesList = [];
                            if (!empty($rawSlides)) {
                                $heroSlidesList = is_string($rawSlides) ? json_decode($rawSlides, true) : $rawSlides;
                            }
                            if (empty($heroSlidesList) || !is_array($heroSlidesList)) {
                                $heroSlidesList = [
                                    1 => ['image' => '/uploads/settings/hero_slide_1.jpg', 'caption' => 'Kampus Alam Tuksongo Madani', 'subcaption' => "Asri, hening, dan kondusif untuk tholabul 'ilmi", 'active' => true],
                                    2 => ['image' => '/uploads/settings/hero_slide_2.jpg', 'caption' => "Halaqah Tahfidzul Qur'an Mutqin", 'subcaption' => 'Bimbingan intensif 30 juz bersanad muttashil', 'active' => true],
                                    3 => ['image' => '/uploads/settings/hero_slide_3.jpg', 'caption' => "Kompleks Asrama & Masjid Jami'", 'subcaption' => 'Lingkungan mukim santri yang bersih, tertib, dan islami', 'active' => true],
                                    4 => ['image' => '/uploads/settings/hero_slide_4.jpg', 'caption' => 'Majelis Asatidz & Dewan Pembina', 'subcaption' => 'Pendidik berdedikasi mengawal sanad akhlak dan adab', 'active' => true],
                                    5 => ['image' => '/uploads/settings/hero_slide_5.jpg', 'caption' => 'Laboratorium Komputer CBT', 'subcaption' => 'Fasilitas digital modern untuk seleksi & pembelajaran santri', 'active' => true],
                                ];
                            }
                            $displaySlides = [];
                            $slotIdx = 1;
                            foreach ($heroSlidesList as $s) {
                                if ($slotIdx > $heroSlideCount)
                                    break;
                                if (!isset($s['active']) || $s['active']) {
                                    $displaySlides[] = $s;
                                    $slotIdx++;
                                }
                            }
                            if (empty($displaySlides)) {
                                $displaySlides = array_slice(array_values($heroSlidesList), 0, $heroSlideCount);
                            }
                        @endphp
                        <div class="hero-img-wrap">
                            <div class="hero-slider" id="heroSlider" data-animation="{{ $heroAnimation }}"
                                data-duration="{{ $heroDuration * 1000 }}">
                                @foreach($displaySlides as $index => $slide)
                                    <div class="hero-slide {{ $index === 0 ? 'active' : '' }}"
                                        data-slide-index="{{ $index }}">
                                        <img src="{{ asset($slide['image']) }}"
                                            alt="{{ $slide['caption'] ?? 'Foto Kampus Hidayatullah Tuksongo' }}"
                                            loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                        <div class="hero-img-overlay">
                                            <div class="hero-img-overlay-content">
                                                <div class="badge-verified">
                                                    <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <strong>{{ $slide['caption'] ?? 'Pondok Pesantren Hidayatullah' }}</strong>
                                                    <small>{{ $slide['subcaption'] ?? 'Tuksongo — Pringsurat, Temanggung' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if(count($displaySlides) > 1)
                                    <button class="hero-slider-btn prev" id="heroPrevBtn" aria-label="Slide Sebelumnya">
                                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                            <polyline points="15 18 9 12 15 6"></polyline>
                                        </svg>
                                    </button>
                                    <button class="hero-slider-btn next" id="heroNextBtn" aria-label="Slide Berikutnya">
                                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </button>

                                    <div class="hero-slider-dots" id="heroSliderDots">
                                        @foreach($displaySlides as $index => $slide)
                                            <button class="hero-slider-dot {{ $index === 0 ? 'active' : '' }}"
                                                data-slide-target="{{ $index }}"
                                                aria-label="Lihat Slide {{ $index + 1 }}"></button>
                                        @endforeach
                                    </div>
                                @endif
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
                                    style="font-family: 'Grenze', Georgia, serif; font-size: 19px; color: #fff; display: block; line-height: 1.2;">{{ \App\Models\Setting::get('sambutan_nama', 'Pimpinan Pesantren') }}</strong>
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
                                style="font-family: 'Grenze', Georgia, serif; font-size: 21px; font-style: italic; line-height: 1.6; color: #ffffff; margin: 0; position: relative;">
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
                        {{ \App\Models\Setting::get('profil_judul', 'Profil Pesantren Hidayatullah Tuksongo') }}
                    </h2>
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
                        <h3 class="section-title" style="font-size: 28px;">
                            {{ \App\Models\Setting::get('falsafah_judul', 'Panca Jiwa Pondok Pesantren Hidayatullah') }}
                        </h3>
                    </div>
                    <p class="section-desc" style="font-size: 13px;">
                        {{ \App\Models\Setting::get('falsafah_subjudul', 'Lima nilai pokok yang menjadi ruh dan pedoman pembentukan karakter setiap santri selama menuntut ilmu.') }}
                    </p>
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
                            <h3>{{ \App\Models\Setting::get('filosofi_judul', 'Makna Filosofi Lambang Pesantren') }}
                            </h3>
                            <p>{{ \App\Models\Setting::get('filosofi_subjudul', 'Setiap goresan simbol dan warna dalam logo pesantren mencerminkan visi luhur perjuangan dakwah dan pendidikan.') }}
                            </p>
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
                    <h2 class="section-title">
                        {{ \App\Models\Setting::get('pilar_title', 'Fondasi Pendidikan Integral Berkarakter Islami') }}
                    </h2>
                    <p class="section-desc">
                        {{ \App\Models\Setting::get('pilar_subtitle', "Pilar pendidikan di Pesantren Hidayatullah Tuksongo dirancang seimbang antara kesucian ruhani, kedalaman ilmu syar'i, kecerdasan intelek, dan kemandirian hidup.") }}
                    </p>
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
                                        <path
                                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                        </path>
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
                        <blockquote>
                            {!! \App\Models\Setting::get('quran_quote', '"Allah akan meninggikan orang-orang yang beriman di antaramu dan orang-orang yang diberi ilmu pengetahuan beberapa derajat."') !!}
                        </blockquote>
                        <p class="quote-ref">
                            {{ \App\Models\Setting::get('quran_desc', 'Prinsip keselarasan antara kemurnian tauhid dan kedalaman ilmu pengetahuan yang menuntun setiap langkah pengasuhan di Pondok Pesantren Hidayatullah Tuksongo.') }}
                        </p>
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

        @php
            $defaultPrestasiList = [
                [
                    'tag' => "Tahfidzul Qur'an",
                    'level' => 'Tingkat Provinsi',
                    'judul' => "Juara 1 Musabaqah Hifdzil Qur'an (MHQ) 30 Juz Bersanad",
                    'deskripsi' => "Prestasi santri dalam kompetisi hafalan Al-Qur'an tingkat regional Jawa Tengah dengan pengujian ketepatan tajwid, fashahah, dan kelancaran hafalan mutqin.",
                    'image' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?q=80&w=800&auto=format&fit=crop',
                ],
                [
                    'tag' => 'Sains & Riset Madrasah',
                    'level' => 'Medali Emas',
                    'judul' => 'Medali Emas KSM Bidang Matematika & Sains Terintegrasi',
                    'deskripsi' => 'Pembuktian kompetensi sains santri madrasah dalam menyelesaikan riset dan problem solving matematika yang disinergikan dengan pemahaman dalil keislaman.',
                    'image' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=800&auto=format&fit=crop',
                ],
                [
                    'tag' => 'Bahasa Arab Aktif',
                    'level' => 'Tingkat Nasional',
                    'judul' => 'Juara 1 Khitobah Pidato Bahasa Arab & Debat Ilmiah',
                    'deskripsi' => 'Kecakapan orasi, fashahah balaghah, dan kefasihan berbicara bahasa Arab santri di hadapan dewan juri festival bahasa pesantren nasional se-Indonesia.',
                    'image' => 'https://images.unsplash.com/photo-1577495508048-b635879837f1?q=80&w=800&auto=format&fit=crop',
                ],
                [
                    'tag' => 'Seni & Olahraga Santri',
                    'level' => 'Juara Umum',
                    'judul' => 'Juara Umum POSPEDA Kaligrafi Islam & Pencak Silat',
                    'deskripsi' => "Sinergi ketangkasan fisik pendekar santri melalui pencak silat serta keindahan estetika mushaf Al-Qur'an lewat goresan khath kaligrafi murni.",
                    'image' => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?q=80&w=800&auto=format&fit=crop',
                ],
                [
                    'tag' => 'Kiprah & Studi Alumni',
                    'level' => 'Internasional',
                    'judul' => 'Kelulusan Santri Tembus Al-Azhar Mesir & PTKIN Favorit',
                    'deskripsi' => 'Lulusan 6 tahun TMI Hidayatullah berhasil lolos seleksi beasiswa kuliah ke Universitas Al-Azhar Kairo serta perguruan tinggi keagamaan negeri bergengsi di Indonesia.',
                    'image' => 'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?q=80&w=800&auto=format&fit=crop',
                ],
                [
                    'tag' => 'Standarisasi Mutu',
                    'level' => 'Terakreditasi',
                    'judul' => "Akreditasi Unggul Madrasah & Sanad Tahfidz Muttashil",
                    'deskripsi' => "Standarisasi kurikulum formal Kemenag untuk MTs & MA Hidayatullah serta legalitas ijazah sanad tahfidz Al-Qur'an 30 juz bersambung sanadnya hingga Rasulullah SAW.",
                    'image' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=800&auto=format&fit=crop',
                ],
            ];

            $defaultPrestasiStatsList = [
                ['angka' => '150+', 'label' => 'Juara & Penghargaan Prestasi'],
                ['angka' => '35+', 'label' => 'Santri Mutqin Hafidz 30 Juz'],
                ['angka' => '100%', 'label' => 'Alumni Lolos Kuliah & Khidmat'],
            ];

            $prestasiItems = json_decode(\App\Models\Setting::get('prestasi_items_json', 'null'), true) ?: $defaultPrestasiList;
            $prestasiStats = json_decode(\App\Models\Setting::get('prestasi_stats_json', 'null'), true) ?: $defaultPrestasiStatsList;
            $prestasiBadge = \App\Models\Setting::get('prestasi_badge', 'Raihan Prestasi & Kejuaraan Santri');
            $prestasiJudul = \App\Models\Setting::get('prestasi_judul', 'Rekam Jejak Keunggulan Akademik & Spiritual');
            $prestasiSubjudul = \App\Models\Setting::get('prestasi_subjudul', 'Ikhtiar pembinaan santri secara integral dan berkesinambungan mengantarkan santri Pondok Pesantren Hidayatullah Tuksongo mengukir deretan prestasi gemilang di tingkat kabupaten, provinsi, hingga kancah nasional.');
        @endphp

        <!-- ===== PRESTASI SANTRI & LEMBAGA ===== -->
        <section class="section-prestasi" id="prestasi">
            <span id="fasilitas" style="position: absolute; top: -80px; visibility: hidden;"></span>
            <div class="container">
                <div class="prestasi-header anim-fade-up">
                    <div>
                        <span class="section-label">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="7"></circle>
                                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                            </svg>
                            {{ $prestasiBadge }}
                        </span>
                        <h2 class="section-title">{{ $prestasiJudul }}</h2>
                    </div>
                    <p class="section-desc">{{ $prestasiSubjudul }}</p>
                </div>

                @if(!empty($prestasiStats) && count($prestasiStats) > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 36px;" class="anim-fade-up">
                        @foreach($prestasiStats as $st)
                            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 18px 20px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                                <div style="font-size: 28px; font-weight: 800; color: #15803d; font-family: 'EB Garamond', serif; line-height: 1.1;">{{ $st['angka'] ?? '' }}</div>
                                <div style="font-size: 12px; font-weight: 600; color: #64748b; margin-top: 4px;">{{ $st['label'] ?? '' }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="prestasi-grid">
                    @foreach($prestasiItems as $p)
                        <div class="prestasi-card anim-fade-up">
                            <img class="prestasi-card-bg-img"
                                src="{{ asset($p['image'] ?? '') }}"
                                alt="{{ $p['judul'] ?? 'Prestasi Santri' }}" loading="lazy">
                            <div class="prestasi-card-gradient"></div>
                            <div class="prestasi-card-top">
                                <span class="prestasi-card-tag">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <circle cx="12" cy="8" r="7"></circle>
                                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                    </svg>
                                    {{ $p['tag'] ?? 'Prestasi' }}
                                </span>
                                @if(!empty($p['level']))
                                    <span class="prestasi-card-level">{{ $p['level'] }}</span>
                                @endif
                            </div>
                            <div class="prestasi-card-content">
                                <h3 class="prestasi-card-title">{{ $p['judul'] ?? '' }}</h3>
                                <p class="prestasi-card-desc">{{ $p['deskripsi'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
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
                        Penerimaan Santri Baru TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}
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

                <!-- PREVIEW BIAYA RINGKAS & TAUTAN KE HALAMAN BIAYA LENGKAP -->
                <div class="biaya-section-wrap anim-fade-up">
                    <div class="anim-fade-up"
                        style="background: #ffffff; border: 1px solid #d4ebd8; border-radius: var(--radius-md); padding: 36px; margin-top: 10px; box-shadow: var(--shadow-md);">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                            <div style="max-width: 650px;">
                                <span class="section-label"
                                    style="color: var(--green-700); background: var(--green-50); padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                    Rincian Biaya Resmi TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}
                                </span>
                                <h3
                                    style="font-family: 'Grenze', Georgia, serif; font-size: clamp(22px, 3vw, 28px); color: var(--green-950); margin-top: 10px; margin-bottom: 8px;">
                                    {{ \App\Models\Setting::get('biaya_preview_title', 'Transparansi Biaya Pendidikan Santri Baru MTs & MA') }}
                                </h3>
                                <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.6;">
                                    {{ \App\Models\Setting::get('biaya_preview_desc', 'Seluruh rincian pembiayaan awal (uang pangkal, seragam, kasur/kamar santri mukim) serta syahriyah bulanan disajikan secara rinci, transparan, dan dapat diunduh pada halaman khusus biaya kami.') }}
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('biaya.index') }}" class="btn-gold"
                                    style="display: inline-flex; align-items: center; gap: 10px; padding: 14px 28px; font-size: 15px; font-weight: 700; border-radius: var(--radius-sm); text-decoration: none; box-shadow: var(--shadow-md);">
                                    <span>{{ \App\Models\Setting::get('biaya_preview_btn_text', 'Lihat Rincian Biaya Lengkap') }}</span>
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-top: 24px; padding-top: 20px; border-top: 1px dashed var(--border);">
                            <div
                                style="background: var(--surface-dim); padding: 14px 18px; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                                <div
                                    style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">
                                    {{ \App\Models\Setting::get('biaya_card1_label', 'Biaya Masuk Pertama') }}</div>
                                <div style="font-size: 18px; font-weight: 800; color: var(--green-800); margin: 3px 0;">
                                    {{ \App\Models\Setting::get('biaya_card1_value', 'Terjangkau & Jelas') }}</div>
                                <div style="font-size: 12px; color: var(--text-secondary);">
                                    {{ \App\Models\Setting::get('biaya_card1_sub', 'Sudah termasuk fasilitas kamar') }}</div>
                            </div>
                            <div
                                style="background: var(--surface-dim); padding: 14px 18px; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                                <div
                                    style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">
                                    {{ \App\Models\Setting::get('biaya_card2_label', 'Syahriyah Bulanan') }}</div>
                                <div style="font-size: 18px; font-weight: 800; color: var(--green-800); margin: 3px 0;">
                                    {{ \App\Models\Setting::get('biaya_card2_value', 'Mulai Rp 80.000/bln') }}</div>
                                <div style="font-size: 12px; color: var(--text-secondary);">
                                    {{ \App\Models\Setting::get('biaya_card2_sub', 'Untuk santri laju non-asrama') }}</div>
                            </div>
                            <div
                                style="background: var(--surface-dim); padding: 14px 18px; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                                <div
                                    style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">
                                    {{ \App\Models\Setting::get('biaya_card3_label', 'Makan Asrama 3x Sehari') }}</div>
                                <div style="font-size: 18px; font-weight: 800; color: var(--green-800); margin: 3px 0;">
                                    {{ \App\Models\Setting::get('biaya_card3_value', 'Rp 300.000/bln') }}</div>
                                <div style="font-size: 12px; color: var(--text-secondary);">
                                    {{ \App\Models\Setting::get('biaya_card3_sub', 'Menu sehat bergizi & higienis') }}</div>
                            </div>
                            <div
                                style="background: var(--surface-dim); padding: 14px 18px; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                                <div
                                    style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">
                                    {{ \App\Models\Setting::get('biaya_card4_label', 'Bantuan / Beasiswa') }}</div>
                                <div style="font-size: 18px; font-weight: 800; color: var(--gold-600); margin: 3px 0;">
                                    {{ \App\Models\Setting::get('biaya_card4_value', 'Tersedia') }}</div>
                                <div style="font-size: 12px; color: var(--text-secondary);">
                                    {{ \App\Models\Setting::get('biaya_card4_sub', 'Bagi dhuafa & santri berprestasi') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- OFFICIAL ONLINE PSB REGISTRATION CTA CARD -->
                    <div id="formulir-daftar" class="anim-fade-up"
                        style="margin-top: 48px; background: linear-gradient(135deg, #0d3b1e 0%, #1a6b38 100%); border-radius: var(--radius-md); padding: 48px 36px; box-shadow: var(--shadow-lg); text-align: center; color: #ffffff; position: relative; overflow: hidden;">
                        <div style="position: relative; z-index: 2; max-width: 720px; margin: 0 auto;">
                            <span class="section-label"
                                style="color: var(--gold-300); background: rgba(255,255,255,0.12); padding: 5px 16px; border-radius: 9999px; border: 1px solid rgba(255,255,255,0.2); font-size: 11px; letter-spacing: 0.12em;">PENERIMAAN
                                SANTRI BARU TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}</span>
                            <h3
                                style="font-family: 'Grenze', Georgia, serif; font-size: clamp(26px, 3.5vw, 34px); font-weight: 700; margin-top: 14px; margin-bottom: 12px; color: #ffffff; line-height: 1.25;">
                                Formulir Pendaftaran Resmi Santri Baru</h3>
                            <p
                                style="font-size: 14px; color: rgba(255,255,255,0.85); line-height: 1.7; margin-bottom: 28px;">
                                Daftarkan calon santri baru secara online melalui formulir digital terpadu pesantren.
                                Mendukung pilihan <strong>Jalur Reguler</strong>, <strong>Jalur Prestasi</strong>, dan
                                <strong>Jalur Tahfidz</strong> dengan sistem verifikasi berkas, pas foto 3x4, dan
                                konfirmasi WhatsApp langsung.
                            </p>
                            <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                                @if($isPsbOpen)
                                    <a href="{{ route('psb.register') }}" class="btn-gold"
                                        style="font-size: 15px; padding: 14px 30px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 700;">
                                        <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                            <line x1="22" y1="2" x2="11" y2="13"></line>
                                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                        </svg>
                                        Buka Formulir Pendaftaran Online
                                    </a>
                                @else
                                    <a href="{{ route('psb.register') }}" class="btn-gold"
                                        style="font-size: 15px; padding: 14px 28px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 700; background: linear-gradient(135deg, #dc2626, #b91c1c); color: #ffffff; border-color: #ef4444;">
                                        <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                        Pendaftaran ({{ $psbBadgeText }}) • Lihat Informasi
                                    </a>
                                @endif
                                <a href="{{ route('psb.checkStatus') }}" class="btn-secondary"
                                    style="background: #ffffff; color: var(--green-950); font-weight: 700; border: none; display: inline-flex; align-items: center; gap: 8px; padding: 14px 24px; box-shadow: var(--shadow-md);"
                                    title="Cek status verifikasi formulir dan foto calon santri cukup dengan No. Registrasi atau WhatsApp">
                                    <svg class="icon-svg icon-svg-sm" style="color: var(--green-700);"
                                        viewBox="0 0 24 24">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                    🔍 Cek Status Pendaftaran
                                </a>
                                @php
                                    $konsultasiWa = \App\Models\Setting::get('kontak_hotline', \App\Models\Setting::get('kontak_hotline_1', '0813-9110-9966'));
                                    $cleanKonsultasiWa = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $konsultasiWa));
                                @endphp
                                <a href="https://wa.me/{{ $cleanKonsultasiWa }}?text={{ urlencode('Assalamu\'alaikum, saya ingin konsultasi mengenai Penerimaan Santri Baru Ponpes Hidayatullah Tuksongo.') }}"
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
                    <!-- Kotak 1: Customer Service Pondok -->
                    <div class="kontak-box">
                        <div class="k-icon">
                            <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </div>
                        <h4>Customer Service Pondok</h4>
                        <p>{{ \App\Models\Setting::get('kontak_cs_desc', 'Pusat informasi umum dan penerimaan santri baru Tuksongo') }}</p>
                        @php
                            $csWa = \App\Models\Setting::get('kontak_hotline', '0813-9110-9966');
                            $cleanCsWa = preg_replace('/[^0-9]/', '', $csWa);
                            if (str_starts_with($cleanCsWa, '0')) {
                                $cleanCsWa = '62' . substr($cleanCsWa, 1);
                            }
                        @endphp
                        <a class="phone-link" href="https://wa.me/{{ $cleanCsWa }}" target="_blank">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            {{ $csWa }}
                        </a>
                    </div>

                    <!-- Kotak 2: Pengasuhan Putra & Putri -->
                    <div class="kontak-box">
                        <div class="k-icon">
                            <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h4>Pengasuhan Putra &amp; Putri</h4>
                        <p>{{ \App\Models\Setting::get('kontak_pengasuhan_nama', 'Ustd. Khoerul Rokhim & Ustdzh. Binti Isnaini') }}</p>
                        @php
                            $waPengasuhanPutra = \App\Models\Setting::get('kontak_pengasuhan_putra', '0882-1521-7462');
                            $cleanPengasuhanPutra = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $waPengasuhanPutra));
                            $waPengasuhanPutri = \App\Models\Setting::get('kontak_pengasuhan_putri', '0821-3631-8239');
                            $cleanPengasuhanPutri = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $waPengasuhanPutri));
                        @endphp
                        <a class="phone-link" href="https://wa.me/{{ $cleanPengasuhanPutra }}" target="_blank" style="display:block; margin-bottom:4px;">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            Putra: {{ $waPengasuhanPutra }}
                        </a>
                        <a class="phone-link" href="https://wa.me/{{ $cleanPengasuhanPutri }}" target="_blank">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            Putri: {{ $waPengasuhanPutri }}
                        </a>
                    </div>

                    <!-- Kotak 3: Bidang Pengajaran & KBM -->
                    <div class="kontak-box">
                        <div class="k-icon">
                            <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                        </div>
                        <h4>Bidang Pengajaran &amp; KBM</h4>
                        <p>{{ \App\Models\Setting::get('kontak_kbm_nama', 'Ustd. Didi Utama & Ustdzh. Laela Fitroti') }}</p>
                        @php
                            $waKbmPutra = \App\Models\Setting::get('kontak_kbm_putra', '0856-0920-1330');
                            $cleanKbmPutra = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $waKbmPutra));
                            $waKbmPutri = \App\Models\Setting::get('kontak_kbm_putri', '0813-2617-4337');
                            $cleanKbmPutri = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $waKbmPutri));
                        @endphp
                        <a class="phone-link" href="https://wa.me/{{ $cleanKbmPutra }}" target="_blank" style="display:block; margin-bottom:4px;">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            Putra: {{ $waKbmPutra }}
                        </a>
                        <a class="phone-link" href="https://wa.me/{{ $cleanKbmPutri }}" target="_blank">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            Putri: {{ $waKbmPutri }}
                        </a>
                    </div>

                    <!-- Kotak 4: Keuangan & Infaq Wakaf -->
                    <div class="kontak-box">
                        <div class="k-icon">
                            <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                        </div>
                        <h4>Keuangan &amp; Infaq Wakaf</h4>
                        <p>{{ \App\Models\Setting::get('kontak_keuangan_desc', 'Konfirmasi SPP bulanan, tabungan & infaq') }}</p>
                        @php
                            $waKeuangan = \App\Models\Setting::get('kontak_keuangan_spp', '0852-9042-9617');
                            $cleanKeuangan = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $waKeuangan));
                            $waInfaqSaku = \App\Models\Setting::get('kontak_infaq_saku', '0821-3342-5328');
                            $cleanInfaqSaku = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $waInfaqSaku));
                        @endphp
                        <a class="phone-link" href="https://wa.me/{{ $cleanKeuangan }}" target="_blank" style="display:block; margin-bottom:4px;">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            Keuangan: {{ $waKeuangan }}
                        </a>
                        <a class="phone-link" href="https://wa.me/{{ $cleanInfaqSaku }}" target="_blank">
                            <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            Infaq/Saku: {{ $waInfaqSaku }}
                        </a>
                    </div>
                </div>

                <!-- PUSAT INFORMASI REKENING RESMI & FORMAT KONFIRMASI (SESUAI DESAIN KARTU RESMI) -->
                <div class="anim-fade-up" style="margin-top: 10px; margin-bottom: 30px;">
                    <div style="background: var(--surface-dim); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 28px 22px;">
                        <!-- Header Box -->
                        <div style="text-align: center; margin-bottom: 24px;">
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #ffffff; color: var(--green-800); padding: 4px 14px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid var(--green-200); margin-bottom: 8px;">
                                🏦 Pusat Informasi Rekening Resmi Pesantren
                            </span>
                            <h3 style="font-size: 20px; font-weight: 800; color: var(--green-900); margin: 0 0 6px;">Rekening Pembayaran Administrasi &amp; Uang Saku</h3>
                            <p style="font-size: 12.5px; color: var(--text-muted); max-width: 680px; margin: 0 auto;">
                                Pastikan melakukan transfer hanya ke nomor rekening resmi pesantren di bawah ini sesuai peruntukan pembayaran.
                            </p>
                        </div>

                        <!-- 3 Kolom Rekening dengan Model & Warna yang Selaras (.kontak-box) -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 22px;">
                            
                            <!-- 1. Pembayaran Administrasi (SPP, Syahriyah, SOT, PSB) -->
                            <div class="kontak-box" style="background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 22px 18px; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                                        <div class="k-icon" style="margin-bottom: 0;">
                                            <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                        </div>
                                        <span style="background: var(--green-50); color: var(--green-800); border: 1px solid var(--green-200); font-size: 10.5px; font-weight: 800; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">
                                            ADMINISTRASI &amp; PSB
                                        </span>
                                    </div>
                                    <h4>Pembayaran Administrasi</h4>
                                    <p>Untuk SPP bulanan, syahriyah, SOT &amp; pendaftaran santri baru</p>

                                    <div style="background: var(--surface-dim); border: 1px solid var(--border); border-radius: 8px; padding: 10px 12px; margin-bottom: 8px;">
                                        <div style="font-size: 11px; font-weight: 700; color: var(--green-800);">BANK BRI</div>
                                        <div style="font-size: 15px; font-weight: 800; font-family: monospace; color: var(--green-950); letter-spacing: 0.5px;">
                                            {{ \App\Models\Setting::get('rek_admin_bri_no', '010201022009537') }}
                                        </div>
                                        <div style="font-size: 11px; color: var(--text-muted);">a.n. <strong>{{ \App\Models\Setting::get('rek_admin_bri_an', 'DIKY FACHRI HUSEIN') }}</strong></div>
                                    </div>

                                    <div style="background: var(--surface-dim); border: 1px solid var(--border); border-radius: 8px; padding: 10px 12px; margin-bottom: 14px;">
                                        <div style="font-size: 11px; font-weight: 700; color: var(--green-800);">BANK BCA</div>
                                        <div style="font-size: 15px; font-weight: 800; font-family: monospace; color: var(--green-950); letter-spacing: 0.5px;">
                                            {{ \App\Models\Setting::get('rek_admin_bca_no', '1221220167') }}
                                        </div>
                                        <div style="font-size: 11px; color: var(--text-muted);">a.n. <strong>{{ \App\Models\Setting::get('rek_admin_bca_an', 'DIKY FACHRI HUSEIN') }}</strong></div>
                                    </div>
                                </div>

                                @php
                                    $konfPhone = \App\Models\Setting::get('rek_admin_konfirmasi_phone', '085290429617');
                                    $cleanKonfPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $konfPhone));
                                @endphp
                                <a class="phone-link" href="https://wa.me/{{ $cleanKonfPhone }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: var(--green-700);">
                                    <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    <span>Konfirmasi: {{ $konfPhone }} ({{ \App\Models\Setting::get('rek_admin_konfirmasi_nama', 'Ustdh. Harsih Nur A') }})</span>
                                </a>
                            </div>

                            <!-- 2. Uang Saku Putra -->
                            <div class="kontak-box" style="background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 22px 18px; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                                        <div class="k-icon" style="margin-bottom: 0;">
                                            <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        </div>
                                        <span style="background: var(--green-50); color: var(--green-800); border: 1px solid var(--green-200); font-size: 10.5px; font-weight: 800; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">
                                            UANG SAKU PUTRA
                                        </span>
                                    </div>
                                    <h4>Uang Saku Santri Putra</h4>
                                    <p>Khusus titipan uang jajan / saku santri putra</p>

                                    <div style="background: var(--surface-dim); border: 1px solid var(--border); border-radius: 8px; padding: 10px 12px; margin-bottom: 8px;">
                                        <div style="font-size: 11px; font-weight: 700; color: var(--green-800);">BANK BRI (Putra 1)</div>
                                        <div style="font-size: 15px; font-weight: 800; font-family: monospace; color: var(--green-950); letter-spacing: 0.5px;">
                                            {{ \App\Models\Setting::get('rek_saku_putra_1_no', '366701032851533') }}
                                        </div>
                                        <div style="font-size: 11px; color: var(--text-muted);">a.n. <strong>{{ \App\Models\Setting::get('rek_saku_putra_1_an', 'ILHAM AKBAR ARIFIN') }}</strong></div>
                                        @php
                                            $saku1Phone = \App\Models\Setting::get('rek_saku_putra_1_phone', '0821-3342-5328');
                                            $cleanSaku1 = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $saku1Phone));
                                        @endphp
                                        <a class="phone-link" href="https://wa.me/{{ $cleanSaku1 }}" target="_blank" style="margin-top: 4px; font-size: 11px; display: inline-flex;">
                                            WA: {{ $saku1Phone }} ({{ \App\Models\Setting::get('rek_saku_putra_1_nama', 'Ustad Ilham') }})
                                        </a>
                                    </div>

                                    <div style="background: var(--surface-dim); border: 1px solid var(--border); border-radius: 8px; padding: 10px 12px; margin-bottom: 14px;">
                                        <div style="font-size: 11px; font-weight: 700; color: var(--green-800);">BANK BRI (Putra 2)</div>
                                        <div style="font-size: 15px; font-weight: 800; font-family: monospace; color: var(--green-950); letter-spacing: 0.5px;">
                                            {{ \App\Models\Setting::get('rek_saku_putra_2_no', '025101062991507') }}
                                        </div>
                                        <div style="font-size: 11px; color: var(--text-muted);">a.n. <strong>{{ \App\Models\Setting::get('rek_saku_putra_2_an', 'ATA NUR RIFQI') }}</strong></div>
                                        @php
                                            $saku2Phone = \App\Models\Setting::get('rek_saku_putra_2_phone', '0858-6539-5879');
                                            $cleanSaku2 = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $saku2Phone));
                                        @endphp
                                        <a class="phone-link" href="https://wa.me/{{ $cleanSaku2 }}" target="_blank" style="margin-top: 4px; font-size: 11px; display: inline-flex;">
                                            WA: {{ $saku2Phone }} ({{ \App\Models\Setting::get('rek_saku_putra_2_nama', 'Ustad Ata') }})
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Uang Saku Putri -->
                            <div class="kontak-box" style="background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 22px 18px; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                                        <div class="k-icon" style="margin-bottom: 0;">
                                            <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        </div>
                                        <span style="background: var(--green-50); color: var(--green-800); border: 1px solid var(--green-200); font-size: 10.5px; font-weight: 800; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">
                                            UANG SAKU PUTRI
                                        </span>
                                    </div>
                                    <h4>Uang Saku Santri Putri</h4>
                                    <p>Khusus titipan uang jajan / saku santri putri</p>

                                    <div style="background: var(--surface-dim); border: 1px solid var(--border); border-radius: 8px; padding: 10px 12px; margin-bottom: 8px;">
                                        <div style="font-size: 11px; font-weight: 700; color: var(--green-800);">BANK BRI (Putri)</div>
                                        <div style="font-size: 15px; font-weight: 800; font-family: monospace; color: var(--green-950); letter-spacing: 0.5px;">
                                            {{ \App\Models\Setting::get('rek_saku_putri_no', '366701040613539') }}
                                        </div>
                                        <div style="font-size: 11px; color: var(--text-muted);">a.n. <strong>{{ \App\Models\Setting::get('rek_saku_putri_an', 'LAELATUL MUNAWAROH') }}</strong></div>
                                        @php
                                            $sakuPutriPhone = \App\Models\Setting::get('rek_saku_putri_phone', '0851-8484-2869');
                                            $cleanSakuPutri = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $sakuPutriPhone));
                                        @endphp
                                        <a class="phone-link" href="https://wa.me/{{ $cleanSakuPutri }}" target="_blank" style="margin-top: 4px; font-size: 11px; display: inline-flex;">
                                            WA: {{ $sakuPutriPhone }} ({{ \App\Models\Setting::get('rek_saku_putri_nama', 'Ustdh. Defi Nofita') }})
                                        </a>
                                    </div>

                                    <div style="background: var(--green-50); border: 1px dashed var(--green-300); border-radius: 8px; padding: 10px 12px; font-size: 11px; color: var(--green-800); line-height: 1.4;">
                                        💡 <em>Catatan:</em> Untuk kelancaran penyaluran saku putri, mohon kirim bukti transfer ke kontak Ustdh. Defi Nofita.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Format Konfirmasi & Warning Box -->
                        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 16px 18px; font-size: 12px; color: var(--text-primary); line-height: 1.5;">
                            <div style="font-weight: 700; color: var(--green-900); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px;">
                                <span>📋</span>
                                <span>Konfirmasi Bukti Transfer Dengan Format:</span>
                            </div>
                            <div style="font-family: monospace; background: var(--surface-dim); border: 1px solid var(--border); border-radius: 6px; padding: 10px 14px; margin-bottom: 10px; color: var(--text-primary); font-size: 12px;">
                                <strong>NAMA</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: (diisi nama lengkap santri)<br>
                                <strong>KELAS</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: (diisi kelas santri)<br>
                                <strong>JENIS PEMBAYARAN</strong> : (diisi jenis pembayaran + bulan) <em>contoh: Syahriyah Bulan Juli</em><br>
                                <strong>NOMINAL</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: (diisi nominal pembayaran)
                            </div>
                            <div style="font-size: 11.5px; color: #854d0e; background: #fefce8; border: 1px solid #fef08a; border-radius: 6px; padding: 8px 12px;">
                                <strong>⚠️ Wajib Melakukan Konfirmasi:</strong> {{ \App\Models\Setting::get('rek_konfirmasi_petunjuk', 'Demi terciptanya komunikasi yang tertib dan menghindari miskomunikasi, setiap keperluan harap selalu diawali dengan konfirmasi kepada pihak terkait.') }}
                            </div>
                        </div>
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
                    <strong>{{ \App\Models\Setting::get('footer_title', 'Pondok Pesantren Hidayatullah Tuksongo') }}</strong>
                    <p>{{ \App\Models\Setting::get('footer_description', 'Membentuk generasi Qur\'ani yang berakhlak mulia, berwawasan global, mandiri, dan berakar kuat pada nilai-nilai Panca Jiwa Pesantren.') }}
                    </p>
                    <div class="footer-accreditation">
                        <svg class="icon-svg icon-svg-xs" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        {{ \App\Models\Setting::get('footer_accreditation', 'Terakreditasi B (BAN-SM Kemenag) • NSPP: 512032304095') }}
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
                        <span>Hotline:
                            {{ \App\Models\Setting::get('footer_hotline', \App\Models\Setting::get('kontak_hotline', '0813-9110-9966')) }}</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                            </path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <span>{{ \App\Models\Setting::get('footer_website', 'tuksongo.ponpes.id') }}</span>
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
                <span>{{ \App\Models\Setting::get('footer_copyright', '© 2026 Yayasan Hidayatullah Tuksongo Pringsurat Temanggung. Hak Cipta Dilindungi.') }}</span>
                <div class="footer-bottom-links">
                    <a href="{{ \App\Models\Setting::get('brosur_file_url', '/uploads/settings/brosur_1788852849.jpeg') }}"
                        target="_blank">Unduh Brosur PSB</a>
                    <a href="{{ \App\Models\Setting::get('panduan_file_url', '/NEW BUKU PANDUAN SANTRI TA 2025 (2).pdf') }}"
                        target="_blank">Buku Panduan Santri (PDF)</a>
                    <a href="#tata-tertib">Tata Tertib Santri</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Scroll progress
        const scrollProgress = document.getElementById('scrollProgress');
        if (scrollProgress) {
            window.addEventListener('scroll', () => {
                const scrollTop = window.scrollY;
                const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                if (docHeight > 0) {
                    scrollProgress.style.width = (scrollTop / docHeight) * 100 + '%';
                }
            }, { passive: true });
        }

        // Mobile menu safe fallback (official mobile nav is managed by partials.navbar)
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        const mobileNavClose = document.getElementById('mobileNavClose');
        if (mobileMenuBtn && mobileNav) {
            mobileMenuBtn.addEventListener('click', () => mobileNav.classList.add('open'));
        }
        if (mobileNavClose && mobileNav) {
            mobileNavClose.addEventListener('click', () => mobileNav.classList.remove('open'));
        }
        function closeMobileNav() { if (mobileNav) mobileNav.classList.remove('open'); }

        // Scroll animations
        const animElements = document.querySelectorAll('.anim-fade-up');
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, i) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => entry.target.classList.add('visible'), i * 70);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.05 });
            animElements.forEach(el => observer.observe(el));
        } else {
            animElements.forEach(el => el.classList.add('visible'));
        }
        setTimeout(() => {
            animElements.forEach(el => el.classList.add('visible'));
        }, 300);

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
            if (triggerTentang) {
                triggerTentang.classList.toggle('active', ['profil', 'fasilitas'].includes(current));
            }
            if (triggerPesantren) {
                triggerPesantren.classList.toggle('active', ['program', 'kehidupan', 'tata-tertib', 'panduan'].includes(current));
            }
        }, { passive: true });

        // Hero Photo Slider (3-5 Photos Auto Rotate & Interactive)
        (function initHeroSlider() {
            const heroSlider = document.getElementById('heroSlider');
            if (!heroSlider) return;

            const slides = heroSlider.querySelectorAll('.hero-slide');
            const dots = heroSlider.querySelectorAll('.hero-slider-dot');
            const prevBtn = document.getElementById('heroPrevBtn');
            const nextBtn = document.getElementById('heroNextBtn');
            const duration = parseInt(heroSlider.dataset.duration, 10) || 5000;
            const animation = heroSlider.dataset.animation || 'fade';
            let currentIndex = 0;
            let slideInterval = null;

            if (slides.length <= 1) return;

            function showSlide(index) {
                if (index < 0) index = slides.length - 1;
                if (index >= slides.length) index = 0;

                slides.forEach((slide, idx) => {
                    slide.classList.remove('active', 'prev-out');
                    if (animation === 'slide' && idx === currentIndex && idx !== index) {
                        slide.classList.add('prev-out');
                    }
                });
                dots.forEach(dot => dot.classList.remove('active'));

                currentIndex = index;
                slides[currentIndex].classList.add('active');
                if (dots[currentIndex]) dots[currentIndex].classList.add('active');
            }

            function nextSlide() {
                showSlide(currentIndex + 1);
            }

            function prevSlide() {
                showSlide(currentIndex - 1);
            }

            function startAutoSlide() {
                stopAutoSlide();
                slideInterval = setInterval(nextSlide, duration);
            }

            function stopAutoSlide() {
                if (slideInterval) {
                    clearInterval(slideInterval);
                    slideInterval = null;
                }
            }

            function resetTimer() {
                stopAutoSlide();
                startAutoSlide();
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    nextSlide();
                    resetTimer();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    prevSlide();
                    resetTimer();
                });
            }

            dots.forEach(dot => {
                dot.addEventListener('click', (e) => {
                    e.preventDefault();
                    const target = parseInt(dot.dataset.slideTarget, 10);
                    if (!isNaN(target)) {
                        showSlide(target);
                        resetTimer();
                    }
                });
            });

            // Touch Swipe Support for Mobile
            let touchStartX = 0;
            let touchEndX = 0;
            heroSlider.addEventListener('touchstart', (e) => {
                if (e.changedTouches && e.changedTouches.length > 0) {
                    touchStartX = e.changedTouches[0].screenX;
                }
                stopAutoSlide();
            }, { passive: true });

            heroSlider.addEventListener('touchend', (e) => {
                if (e.changedTouches && e.changedTouches.length > 0) {
                    touchEndX = e.changedTouches[0].screenX;
                    const diff = touchEndX - touchStartX;
                    if (Math.abs(diff) > 40) {
                        if (diff < 0) nextSlide();
                        else prevSlide();
                    }
                }
                startAutoSlide();
            }, { passive: true });

            // Pause on hover
            heroSlider.addEventListener('mouseenter', stopAutoSlide);
            heroSlider.addEventListener('mouseleave', startAutoSlide);

            // Start auto sliding
            startAutoSlide();
        })();
    </script>
</body>

</html>
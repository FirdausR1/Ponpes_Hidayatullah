<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <title>{{ $examTitle ?? 'Portal Ujian Masuk Seleksi Santri Baru (CBT)' }} - Ponpes Hidayatullah</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts: EB Garamond & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #15803d;
            --primary-dark: #166534;
            --primary-light: #f0fdf4;
            --accent: #d97706;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-600: #475569;
            --slate-500: #64748b;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        html {
            font-size: 16px;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 50% 0%, #184c2f 0%, #0e2f1c 45%, #07170e 100%);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: clamp(12px, 3vw, 32px) clamp(10px, 3vw, 20px);
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Container Card */
        .portal-card {
            background: #ffffff;
            color: var(--slate-900);
            width: 100%;
            max-width: 580px;
            margin: auto;
            border-radius: clamp(16px, 3.5vw, 22px);
            box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.12);
            overflow: hidden;
            animation: fadeInCard 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeInCard {
            from {
                opacity: 0;
                transform: translateY(14px) scale(0.99);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Header */
        .card-header {
            background: linear-gradient(135deg, #15803d, #166534);
            padding: clamp(22px, 4.5vw, 32px) clamp(16px, 4.5vw, 28px);
            text-align: center;
            color: #ffffff;
            position: relative;
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(8px, 2.5vw, 14px);
            margin-bottom: clamp(8px, 2vw, 12px);
        }

        .logo-wrap img.logo-crest {
            width: clamp(38px, 8vw, 50px);
            height: auto;
            aspect-ratio: 1/1;
            object-fit: contain;
            flex-shrink: 0;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .logo-wrap img.logo-calligraphy {
            height: clamp(26px, 5.5vw, 38px);
            max-width: calc(100% - 60px);
            object-fit: contain;
            filter: brightness(0) invert(1);
            opacity: 0.95;
            flex-shrink: 1;
        }

        .card-header h1 {
            font-family: 'EB Garamond', Georgia, serif;
            font-size: clamp(19px, 4.2vw, 25px);
            font-weight: 700;
            letter-spacing: 0.3px;
            margin-bottom: 4px;
            line-height: 1.25;
            word-wrap: break-word;
        }

        .card-header p {
            font-size: clamp(11.5px, 2.6vw, 13px);
            color: #bbf7d0;
            font-weight: 400;
            line-height: 1.45;
        }

        .status-badge-wrap {
            margin-top: 10px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 9999px;
            font-size: clamp(10px, 2.3vw, 11px);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            line-height: 1.35;
            max-width: 100%;
            white-space: normal;
            text-align: center;
        }

        .status-badge.buka {
            background: #22c55e;
            color: #052e16;
            box-shadow: 0 2px 6px rgba(34, 197, 94, 0.3);
        }

        .status-badge.tutup {
            background: #ef4444;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);
        }

        .status-badge.upcoming {
            background: #fef3c7;
            color: #92400e;
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.2);
        }

        .status-dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Body */
        .card-body {
            padding: clamp(18px, 4vw, 28px) clamp(16px, 4vw, 26px);
        }

        /* Responsive Banners */
        .schedule-banner {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 12px;
            padding: clamp(10px, 2.5vw, 13px) clamp(12px, 3vw, 16px);
            margin-bottom: clamp(14px, 3vw, 18px);
            font-size: clamp(11.5px, 2.5vw, 12.5px);
            color: #166534;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            line-height: 1.45;
        }

        .schedule-banner-icon {
            font-size: clamp(18px, 4vw, 22px);
            line-height: 1;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .info-banner {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 12px;
            padding: clamp(12px, 3vw, 14px) clamp(12px, 3vw, 16px);
            margin-bottom: clamp(14px, 3vw, 18px);
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .info-banner-title {
            display: flex;
            align-items: center;
            gap: 7px;
            font-weight: 700;
            color: #166534;
            font-size: clamp(12px, 2.8vw, 13px);
            margin-bottom: 4px;
            line-height: 1.35;
        }

        .info-banner-desc {
            font-size: clamp(11px, 2.5vw, 12px);
            color: #14532d;
            line-height: 1.5;
        }

        .status-alert {
            padding: clamp(12px, 3vw, 14px) clamp(12px, 3vw, 16px);
            border-radius: 12px;
            margin-bottom: clamp(14px, 3vw, 18px);
            font-size: clamp(11.5px, 2.6vw, 13px);
            line-height: 1.5;
        }

        .status-alert.upcoming {
            background: #fffbeb;
            border: 1.5px solid #fde68a;
            color: #92400e;
        }

        .status-alert.ended {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            color: #991b1b;
        }

        .status-alert-title {
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }

        /* Spec Grid */
        .spec-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: clamp(6px, 1.8vw, 10px);
            margin-bottom: clamp(14px, 3vw, 18px);
        }

        .spec-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: clamp(8px, 2vw, 10px) clamp(4px, 1.5vw, 8px);
            border-radius: 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: transform 0.15s ease;
        }

        .spec-item .label {
            font-size: clamp(9px, 2vw, 10px);
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .spec-item .val {
            font-size: clamp(13px, 3vw, 15.5px);
            font-weight: 800;
            color: #15803d;
            margin-top: 2px;
            line-height: 1.25;
        }

        /* Anti-Cheat & Notice Alert */
        .anti-cheat-alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: clamp(9px, 2.2vw, 11px) clamp(10px, 2.5vw, 13px);
            border-radius: 10px;
            font-size: clamp(10.5px, 2.5vw, 11.5px);
            margin-bottom: clamp(14px, 3vw, 18px);
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.45;
        }

        .anti-cheat-alert svg {
            flex-shrink: 0;
            width: 17px;
            height: 17px;
            margin-top: 1px;
        }

        .notice-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: clamp(11px, 2.5vw, 13px) clamp(12px, 3vw, 15px);
            border-radius: 10px;
            font-size: clamp(11px, 2.5vw, 12px);
            margin-bottom: clamp(16px, 3.5vw, 20px);
            line-height: 1.55;
        }

        .notice-box .rules-title {
            font-weight: 700;
            color: #1e3a8a;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 5px;
            font-size: clamp(11.5px, 2.7vw, 12.5px);
        }

        .notice-box .rules-title svg {
            flex-shrink: 0;
            width: 15px;
            height: 15px;
        }

        .notice-box-content {
            white-space: pre-line;
            word-wrap: break-word;
        }

        /* Error Alert */
        .alert-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
            padding: 11px 13px;
            border-radius: 10px;
            font-size: clamp(11.5px, 2.5vw, 12px);
            margin-bottom: 16px;
            line-height: 1.5;
        }

        /* Form Group */
        .form-group {
            margin-bottom: clamp(14px, 3vw, 18px);
        }

        .form-group label {
            display: block;
            font-size: clamp(12.5px, 2.7vw, 13.5px);
            font-weight: 600;
            color: var(--slate-800);
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .form-group input {
            width: 100%;
            min-height: 46px;
            padding: 11px clamp(12px, 2.5vw, 16px);
            font-size: 16px; /* 16px prevents iOS Safari automatic page zoom */
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
            background: #ffffff;
            color: var(--slate-900);
        }

        @media (min-width: 640px) {
            .form-group input {
                font-size: 14.5px;
            }
        }

        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.15);
        }

        .form-group input:disabled {
            background: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            min-height: 48px;
            padding: 12px 16px;
            background: linear-gradient(135deg, #15803d, #166534);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: clamp(13.5px, 3vw, 15px);
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: 0.3px;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(21, 128, 61, 0.25);
            white-space: normal;
            text-align: center;
            line-height: 1.35;
        }

        .btn-submit:hover:not(:disabled) {
            background: linear-gradient(135deg, #166534, #14532d);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(21, 128, 61, 0.35);
        }

        .btn-submit:active:not(:disabled) {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(21, 128, 61, 0.25);
        }

        .btn-submit:disabled {
            background: #94a3b8 !important;
            cursor: not-allowed;
            box-shadow: none !important;
            opacity: 0.75;
        }

        .btn-submit svg {
            flex-shrink: 0;
        }

        /* Footer */
        .portal-footer {
            text-align: center;
            padding: clamp(14px, 3vw, 18px) clamp(14px, 3vw, 24px);
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            font-size: clamp(11.5px, 2.5vw, 12.5px);
            color: var(--slate-600);
            line-height: 1.6;
        }

        .portal-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            padding: 2px 0;
        }

        .portal-footer a:hover {
            text-decoration: underline;
        }

        /* Extra Small Screen Adjustments */
        @media (max-width: 360px) {
            body {
                padding: 8px;
            }

            .logo-wrap {
                gap: 6px;
            }

            .card-header h1 {
                font-size: 18px;
            }

            .spec-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 4px;
            }

            .spec-item {
                padding: 6px 2px;
            }

            .spec-item .label {
                font-size: 8.5px;
            }

            .spec-item .val {
                font-size: 12.5px;
            }
        }
    </style>
</head>

<body>
    <div class="portal-card">
        <!-- Header -->
        <div class="card-header">
            <div class="logo-wrap">
                <img src="/logo.png" alt="Emblem Hidayatullah" class="logo-crest">
                <img src="/logo1.png" alt="Ma'had Hidayatullah" class="logo-calligraphy">
            </div>
            <h1>{{ $examTitle ?? 'Portal Ujian Masuk Seleksi Santri' }}</h1>
            <p>Computer Based Test (CBT) Online • TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}</p>
            
            @php
                $isAllowed = (($status ?? 'buka') === 'buka') && (($scheduleStatus ?? 'open') !== 'upcoming') && (($scheduleStatus ?? 'open') !== 'ended');
            @endphp

            <div class="status-badge-wrap">
                @if(($status ?? 'buka') !== 'buka')
                    <span class="status-badge tutup">
                        <span class="status-dot" style="background:#ffffff;"></span>
                        Sesi Ujian Ditutup Oleh Panitia
                    </span>
                @elseif(($scheduleStatus ?? 'open') === 'upcoming')
                    <span class="status-badge upcoming">
                        <span class="status-dot" style="background:#d97706;"></span>
                        Jadwal Belum Dimulai
                    </span>
                @elseif(($scheduleStatus ?? 'open') === 'ended')
                    <span class="status-badge tutup">
                        <span class="status-dot" style="background:#ffffff;"></span>
                        Jadwal Telah Berakhir
                    </span>
                @else
                    <span class="status-badge buka">
                        <span class="status-dot" style="background:#14532d;"></span>
                        Sesi Ujian Aktif / Sedang Berlangsung
                    </span>
                @endif
            </div>
        </div>

        <!-- Body Form -->
        <div class="card-body">

            @if(!empty($enableSchedule) && !empty($startDateFormatted) && !empty($endDateFormatted))
                <!-- Rentang Jadwal Pelaksanaan Banner -->
                <div class="schedule-banner">
                    <span class="schedule-banner-icon">🗓️</span>
                    <div>
                        <strong style="display:block; font-size: 11.5px; color: #14532d; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px;">Rentang Waktu Pengerjaan Ujian (CBT):</strong>
                        <span>{{ $startDateFormatted }}</span> s/d <span>{{ $endDateFormatted }}</span>
                    </div>
                </div>
            @endif

            <!-- Unified Exam Info Banner -->
            <div class="info-banner">
                <div class="info-banner-title">
                    <span>📚</span> Ujian Seleksi Masuk Terpadu (Satu Sesi)
                </div>
                <p class="info-banner-desc">
                    Seluruh materi seleksi dikerjakan sekaligus dalam satu sesi ujian terpadu. Soal disesuaikan secara otomatis dengan jenjang pendaftaran Anda (<strong>MTs</strong> atau <strong>MA</strong>) dan urutan soal diacak untuk setiap peserta.
                </p>
            </div>

            @if(($scheduleStatus ?? 'open') === 'upcoming')
                <div class="status-alert upcoming">
                    <div class="status-alert-title">
                        <span>⏳</span> Ujian Seleksi Belum Dimulai
                    </div>
                    Portal pengerjaan soal baru akan dibuka pada <strong>{{ $startDateFormatted }}</strong>. Mohon persiapkan diri dan perangkat Anda, lalu kembali saat waktu ujian telah tiba.
                </div>
            @elseif(($scheduleStatus ?? 'open') === 'ended')
                <div class="status-alert ended">
                    <div class="status-alert-title">
                        <span>🛑</span> Masa Pengerjaan Ujian Telah Berakhir
                    </div>
                    Batas waktu pengerjaan soal CBT telah ditutup pada <strong>{{ $endDateFormatted }}</strong>. Silakan hubungi panitia PSB jika Anda memerlukan bantuan atau pengajuan ujian susulan.
                </div>
            @endif

            <!-- Exam Specs -->
            <div class="spec-grid">
                <div class="spec-item">
                    <div class="label">Durasi Waktu</div>
                    <div class="val">{{ $duration ?? 60 }} Menit</div>
                </div>
                <div class="spec-item">
                    <div class="label">Total Soal</div>
                    <div class="val">{{ $totalQuestions ?? 0 }} Butir</div>
                </div>
                <div class="spec-item">
                    <div class="label">Batas KKM</div>
                    <div class="val">{{ $passingGrade ?? 70 }}</div>
                </div>
            </div>

            <!-- Anti Cheat Banner -->
            <div class="anti-cheat-alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div>
                    <strong>Sistem Pengawas Anti-Curang Aktif:</strong> Perpindahan tab browser, keluar layar penuh, copy-paste, dan shortcut terlarang akan terdeteksi otomatis dan terekam di sistem penguji.
                </div>
            </div>

            <!-- Rules / Notice Box -->
            <div class="notice-box">
                <div class="rules-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>Tata Tertib &amp; Petunjuk Ujian:</span>
                </div>
                <div class="notice-box-content">{!! nl2br(e($instructions ?? "1. Berdoalah sebelum mengerjakan soal.\n2. Kerjakan dengan jujur secara mandiri.\n3. Ujian otomatis terkunci jika batas toleransi pelanggaran terlampaui.")) !!}</div>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $err)
                        <p>{{ $err }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('warning'))
                <div class="alert-error" style="background:#fffbeb; border-color:#fef3c7; color:#b45309;">
                    {{ session('warning') }}
                </div>
            @endif

            <form method="POST" action="{{ route('ujian.login') }}">
                @csrf

                <div class="form-group">
                    <label for="no_registrasi">Nomor Pendaftaran / Registrasi</label>
                    <input type="text" id="no_registrasi" name="no_registrasi" value="{{ old('no_registrasi', request('no_reg')) }}" required placeholder="Contoh: PSB-26-0001" autocomplete="off" autofocus style="font-family:monospace; text-transform:uppercase;" {{ !$isAllowed ? 'disabled' : '' }}>
                    <div id="jenjangDetectorBadge" style="display: none; margin-top: 8px; padding: 7px 12px; border-radius: 8px; font-size: 11.5px; font-weight: 600; align-items: center; gap: 6px; transition: all 0.2s;">
                        <span id="jenjangDetectorIcon"></span>
                        <span id="jenjangDetectorText"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap Calon Santri</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Nama lengkap sesuai formulir pendaftaran" autocomplete="name" {{ !$isAllowed ? 'disabled' : '' }}>
                </div>

                <button type="submit" class="btn-submit" {{ !$isAllowed ? 'disabled' : '' }}>
                    @if(!$isAllowed)
                        @if(($status ?? 'buka') !== 'buka')
                            <span>Sesi Ujian Ditutup Oleh Panitia</span>
                        @elseif(($scheduleStatus ?? 'open') === 'upcoming')
                            <span>Ujian Belum Dimulai (Buka {{ $startDateFormatted }})</span>
                        @elseif(($scheduleStatus ?? 'open') === 'ended')
                            <span>Masa Ujian Telah Berakhir</span>
                        @endif
                    @else
                        <span>Masuk Ruang Ujian</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    @endif
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="portal-footer">
            <p>Belum memiliki nomor pendaftaran? <a href="{{ route('psb.register') }}">Daftar Santri Baru</a></p>
            <p style="margin-top: 6px;"><a href="{{ route('home') }}">&larr; Kembali ke Beranda Utama</a> • <a href="{{ route('psb.checkStatus') }}">Cek Status Berkas</a></p>
        </div>
    </div>

    <script>
    (function() {
        const noRegInput = document.getElementById('no_registrasi');
        const namaInput = document.getElementById('nama_lengkap');
        const badge = document.getElementById('jenjangDetectorBadge');
        const icon = document.getElementById('jenjangDetectorIcon');
        const text = document.getElementById('jenjangDetectorText');
        let debounceTimer = null;

        function checkCandidateAuto(val) {
            val = (val || '').trim();
            if (val.length < 3) {
                if (badge) badge.style.display = 'none';
                return;
            }

            fetch('{{ route('ujian.checkCandidate') }}?no_reg=' + encodeURIComponent(val))
                .then(res => res.json())
                .then(data => {
                    if (data && data.found) {
                        if (badge) {
                            badge.style.display = 'flex';
                            if (data.tipe_jenjang === 'MTs') {
                                badge.style.background = '#ecfeff';
                                badge.style.border = '1px solid #a5f3fc';
                                badge.style.color = '#0e7490';
                                icon.textContent = '🏫';
                                text.innerHTML = 'Terdeteksi: Calon Santri <strong>MTs</strong> (' + data.jenjang + ')';
                            } else if (data.tipe_jenjang === 'MA') {
                                badge.style.background = '#faf5ff';
                                badge.style.border = '1px solid #e9d5ff';
                                badge.style.color = '#7e22ce';
                                icon.textContent = '🎓';
                                text.innerHTML = 'Terdeteksi: Calon Santri <strong>MA</strong> (' + data.jenjang + ')';
                            } else {
                                badge.style.background = '#f0fdf4';
                                badge.style.border = '1px solid #bbf7d0';
                                badge.style.color = '#15803d';
                                icon.textContent = '✨';
                                text.innerHTML = 'Terdeteksi: Calon Santri (' + data.jenjang + ')';
                            }
                        }
                        if (namaInput && !namaInput.value.trim() && data.nama_lengkap) {
                            namaInput.value = data.nama_lengkap;
                        }
                    } else {
                        if (badge) badge.style.display = 'none';
                    }
                })
                .catch(() => {
                    if (badge) badge.style.display = 'none';
                });
        }

        if (noRegInput) {
            noRegInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => checkCandidateAuto(this.value), 300);
            });
            noRegInput.addEventListener('blur', function() {
                checkCandidateAuto(this.value);
            });
            if (noRegInput.value.trim().length >= 3) {
                checkCandidateAuto(noRegInput.value);
            }
        }
    })();
    </script>
</body>

</html>

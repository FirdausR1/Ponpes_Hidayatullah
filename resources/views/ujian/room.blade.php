<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ruang Ujian Seleksi CBT — {{ $reg->nama_lengkap }}</title>
    <link rel="icon" href="/logo.png" type="image/png">

    <!-- Google Fonts: EB Garamond, Amiri, Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=EB+Garamond:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- KaTeX CSS untuk render rumus Matematika -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">

    <style>
        :root {
            --primary: #15803d;
            --primary-dark: #166534;
            --primary-light: #f0fdf4;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Anti-cheat: disable selection on entire body */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            color: var(--slate-900);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        /* Arabic Text Class */
        .arabic-text {
            font-family: 'Amiri', serif;
            direction: rtl;
            text-align: right;
            font-size: 1.35rem;
            line-height: 2.3;
        }

        .katex { font-size: 1.15em !important; }

        /* Top Bar */
        .exam-header {
            background: #ffffff;
            border-bottom: 1px solid var(--slate-200);
            position: sticky;
            top: 0;
            z-index: 40;
            padding: 12px 24px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-info img.crest {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }

        .brand-info img.calligraphy {
            height: 24px;
            object-fit: contain;
        }

        .candidate-badge {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .candidate-meta {
            text-align: right;
        }

        .candidate-meta .name {
            font-size: 13px;
            font-weight: 700;
            color: var(--slate-800);
            display: block;
        }

        .candidate-meta .reg {
            font-size: 11px;
            color: var(--slate-500);
            font-family: monospace;
        }

        .timer-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 6px 14px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 1px 2px rgba(239, 68, 68, 0.1);
        }

        .anti-cheat-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #047857;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        /* Container */
        .exam-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 310px;
            gap: 24px;
            flex: 1;
            width: 100%;
        }

        @media (max-width: 900px) {
            .exam-container {
                grid-template-columns: 1fr;
            }
            .candidate-meta {
                display: none;
            }
        }

        /* Question Area */
        .question-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
        }

        .q-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--slate-100);
        }

        .q-num {
            font-family: 'EB Garamond', Georgia, serif;
            font-size: 19px;
            font-weight: 700;
            color: var(--primary);
        }

        .q-tag {
            font-size: 11px;
            font-weight: 700;
            background: #eff6ff;
            color: #1d4ed8;
            padding: 3px 10px;
            border-radius: 6px;
            border: 1px solid #bfdbfe;
        }

        .q-text {
            font-size: 15px;
            line-height: 1.7;
            color: var(--slate-800);
            margin-bottom: 20px;
            font-weight: 500;
        }

        .options-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .option-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border: 1.5px solid var(--slate-200);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s;
            background: #ffffff;
        }

        .option-item:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .option-item input[type="radio"] {
            display: none;
        }

        .option-item.selected {
            background: #f0fdf4;
            border-color: var(--primary);
            box-shadow: 0 0 0 1px var(--primary);
        }

        .option-key {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f1f5f9;
            color: var(--slate-700);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
            transition: all 0.15s;
        }

        .option-item.selected .option-key {
            background: var(--primary);
            color: white;
        }

        .option-label {
            font-size: 14px;
            color: var(--slate-800);
            flex: 1;
        }

        /* Subject Header & Tabs */
        .category-tabs-bar {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 2px 0 14px 0;
            margin-bottom: 6px;
            scrollbar-width: thin;
        }

        .category-tabs-bar::-webkit-scrollbar {
            height: 4px;
        }
        .category-tabs-bar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .cat-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: #ffffff;
            border: 1.5px solid var(--slate-200);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            color: var(--slate-700);
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.15s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .cat-pill:hover {
            border-color: var(--primary);
            background: #f0fdf4;
            color: var(--primary);
            transform: translateY(-1px);
        }

        .subject-banner {
            background: linear-gradient(135deg, #15803d, #166534);
            color: #ffffff;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(21, 128, 61, 0.16);
            scroll-margin-top: 80px;
        }

        .sidebar-categories {
            max-height: 320px;
            overflow-y: auto;
            padding-right: 4px;
            margin-bottom: 16px;
        }

        .sidebar-categories::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-categories::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .sidebar-cat-group {
            margin-bottom: 14px;
        }

        .sidebar-cat-group:last-child {
            margin-bottom: 4px;
        }

        .sidebar-cat-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--slate-600);
            margin-bottom: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Sidebar Navigation */
        .sidebar-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            position: sticky;
            top: 76px;
        }

        .sidebar-card h3 {
            font-size: 14px;
            font-weight: 700;
            color: var(--slate-800);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .progress-indicator {
            font-size: 11px;
            color: var(--primary);
            font-weight: 600;
        }

        .grid-nav {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 7px;
            margin-bottom: 0;
        }

        .nav-btn {
            height: 36px;
            border-radius: 7px;
            border: 1px solid var(--slate-200);
            background: #ffffff;
            color: var(--slate-700);
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
            text-decoration: none;
        }

        .nav-btn:hover {
            border-color: var(--primary);
            background: #f8fafc;
        }

        .nav-btn.answered {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .btn-finish {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #15803d, #166534);
            color: white;
            border: none;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(21, 128, 61, 0.25);
        }

        .btn-finish:hover {
            background: linear-gradient(135deg, #166534, #14532d);
            transform: translateY(-1px);
        }

        /* Anti-Cheat Overlay Modals */
        .cbt-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.82);
            backdrop-filter: blur(6px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .cbt-modal-card {
            background: #ffffff;
            border-radius: 16px;
            max-width: 480px;
            width: 100%;
            padding: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            text-align: center;
        }

        .cbt-modal-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cbt-modal-icon.warning {
            background: #fef2f2;
            color: #ef4444;
            border: 2px solid #fecaca;
        }

        .cbt-modal-icon.info {
            background: #ecfdf5;
            color: #059669;
            border: 2px solid #a7f3d0;
        }
    </style>
</head>

<body>

    <!-- TOP HEADER -->
    <header class="exam-header">
        <div class="header-inner">
            <div class="brand-info">
                <img src="/logo.png" alt="Logo" class="crest">
                <img src="/logo1.png" alt="معهد هداية الله" class="calligraphy">
            </div>

            <div class="candidate-badge">
                <div class="anti-cheat-badge" title="Sistem Pengawas Anti-Contek Aktif">
                    <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <span>Anti-Contek Aktif</span>
                </div>

                <div class="candidate-meta">
                    <span class="name">{{ $reg->nama_lengkap }}</span>
                    <span class="reg">{{ $reg->no_registrasi }} • {{ $reg->jenjang }}</span>
                </div>

                <div class="timer-box" id="timerBox">
                    <svg style="width:18px; height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span id="countdownTimer">--:--</span>
                </div>
            </div>
        </div>
    </header>

    <!-- FORM EXAM -->
    <form id="examForm" action="{{ route('ujian.submit') }}" method="POST">
        @csrf
        <div class="exam-container">
            
            <!-- Questions Column -->
            <div class="questions-column" id="questionsContainer">

                @if(isset($categoriesGrouped) && count($categoriesGrouped) > 1)
                    <!-- Quick Jump Subject Tabs -->
                    <div class="category-tabs-bar">
                        @foreach($categoriesGrouped as $catName => $grp)
                            <a href="#cat-section-{{ Str::slug($catName) }}" class="cat-pill">
                                <span>{{ $catName }}</span>
                                <span style="font-size: 10.5px; background: #f1f5f9; color: #64748b; padding: 1px 7px; border-radius: 10px;">{{ count($grp['items']) }} Soal</span>
                            </a>
                        @endforeach
                    </div>
                @endif

                @if(isset($categoriesGrouped) && count($categoriesGrouped) > 0)
                    @foreach($categoriesGrouped as $catName => $grp)
                        <div class="subject-section" id="cat-section-{{ Str::slug($catName) }}" style="margin-bottom: 24px;">
                            <!-- Subject Banner -->
                            <div class="subject-banner">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="font-size: 18px;">📚</span>
                                    <div>
                                        <h2 style="font-size: 15px; font-weight: 700; margin: 0; line-height: 1.2;">Mata Pelajaran: {{ $catName }}</h2>
                                        <span style="font-size: 11.5px; opacity: 0.9;">Soal Nomor {{ $grp['startIndex'] }} s/d {{ $grp['endIndex'] }} &bull; Diacak untuk Anda</span>
                                    </div>
                                </div>
                                <span style="font-size: 11px; font-weight: 700; background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 20px; white-space: nowrap;">
                                    {{ count($grp['items']) }} Butir Soal
                                </span>
                            </div>

                            @foreach($grp['items'] as $item)
                                @php
                                    $q = $item['question'];
                                    $globalNum = $item['globalIndex'];
                                    $savedVal = $savedAnswers[$q->id] ?? null;
                                @endphp
                                <div class="question-card" id="q-block-{{ $q->id }}">
                                    <div class="q-header">
                                        <span class="q-num">Nomor {{ $globalNum }}</span>
                                        <span class="q-tag">{{ $q->kategori }}</span>
                                    </div>

                                    <div class="q-text render-math {{ $q->is_arabic ? 'arabic-text' : '' }}">
                                        {!! nl2br(e($q->soal)) !!}
                                    </div>

                                    @if(!empty($q->gambar))
                                        <div class="q-image-wrap" style="margin: 14px 0 18px 0;">
                                            <img src="{{ asset($q->gambar) }}" alt="Gambar Soal Nomor {{ $globalNum }}" 
                                                 style="max-height: 280px; max-width: 100%; border-radius: 10px; border: 1.5px solid #e2e8f0; background: #ffffff; padding: 4px; object-fit: contain; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.06);"
                                                 onclick="window.open(this.src, '_blank')" title="Klik untuk membuka gambar ukuran penuh">
                                            <span style="font-size: 11px; color: #64748b; display: block; margin-top: 4px;">🔍 Klik gambar untuk memperbesar</span>
                                        </div>
                                    @endif

                                    @php
                                        $optionsArray = [
                                            'A' => $q->opsi_a,
                                            'B' => $q->opsi_b,
                                            'C' => $q->opsi_c,
                                            'D' => $q->opsi_d,
                                        ];
                                        if (!empty($q->opsi_e)) {
                                            $optionsArray['E'] = $q->opsi_e;
                                        }
                                    @endphp

                                    <div class="options-list">
                                        @foreach($optionsArray as $key => $val)
                                            <label class="option-item {{ $savedVal === $key ? 'selected' : '' }}" onclick="selectOption({{ $q->id }}, '{{ $key }}')">
                                                <input type="radio" name="jawaban[{{ $q->id }}]" value="{{ $key }}" id="opt-{{ $q->id }}-{{ $key }}" {{ $savedVal === $key ? 'checked' : '' }}>
                                                <div class="option-key">{{ $key }}</div>
                                                <div class="option-label render-math {{ $q->is_arabic ? 'arabic-text' : '' }}">{!! $val !!}</div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @else
                    @foreach($questions as $index => $q)
                        @php $savedVal = $savedAnswers[$q->id] ?? null; @endphp
                        <div class="question-card" id="q-block-{{ $q->id }}">
                            <div class="q-header">
                                <span class="q-num">Nomor {{ $index + 1 }}</span>
                                <span class="q-tag">{{ $q->kategori }}</span>
                            </div>

                            <div class="q-text render-math {{ $q->is_arabic ? 'arabic-text' : '' }}">
                                {!! nl2br(e($q->soal)) !!}
                            </div>

                            @if(!empty($q->gambar))
                                <div class="q-image-wrap" style="margin: 14px 0 18px 0;">
                                    <img src="{{ asset($q->gambar) }}" alt="Gambar Soal Nomor {{ $index + 1 }}" 
                                         style="max-height: 280px; max-width: 100%; border-radius: 10px; border: 1.5px solid #e2e8f0; background: #ffffff; padding: 4px; object-fit: contain; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.06);"
                                         onclick="window.open(this.src, '_blank')" title="Klik untuk membuka gambar ukuran penuh">
                                    <span style="font-size: 11px; color: #64748b; display: block; margin-top: 4px;">🔍 Klik gambar untuk memperbesar</span>
                                </div>
                            @endif

                            @php
                                $optionsArray = [
                                    'A' => $q->opsi_a,
                                    'B' => $q->opsi_b,
                                    'C' => $q->opsi_c,
                                    'D' => $q->opsi_d,
                                ];
                                if (!empty($q->opsi_e)) {
                                    $optionsArray['E'] = $q->opsi_e;
                                }
                            @endphp

                            <div class="options-list">
                                @foreach($optionsArray as $key => $val)
                                    <label class="option-item {{ $savedVal === $key ? 'selected' : '' }}" onclick="selectOption({{ $q->id }}, '{{ $key }}')">
                                        <input type="radio" name="jawaban[{{ $q->id }}]" value="{{ $key }}" id="opt-{{ $q->id }}-{{ $key }}" {{ $savedVal === $key ? 'checked' : '' }}>
                                        <div class="option-key">{{ $key }}</div>
                                        <div class="option-label render-math {{ $q->is_arabic ? 'arabic-text' : '' }}">{!! $val !!}</div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Sidebar Column -->
            <div class="sidebar-column">
                <div class="sidebar-card">
                    <h3>
                        <span>Navigasi Soal</span>
                        <span class="progress-indicator" id="progressCount">0 / {{ count($questions) }} Terjawab</span>
                    </h3>

                    @if(isset($categoriesGrouped) && count($categoriesGrouped) > 0)
                        <div class="sidebar-categories">
                            @foreach($categoriesGrouped as $catName => $grp)
                                <div class="sidebar-cat-group">
                                    <div class="sidebar-cat-title">
                                        <span>{{ $catName }}</span>
                                        <span style="font-size: 10px; color: #94a3b8;">{{ count($grp['items']) }} Soal</span>
                                    </div>
                                    <div class="grid-nav">
                                        @foreach($grp['items'] as $item)
                                            <a href="#q-block-{{ $item['question']->id }}" class="nav-btn {{ isset($savedAnswers[$item['question']->id]) ? 'answered' : '' }}" id="nav-btn-{{ $item['question']->id }}">
                                                {{ $item['globalIndex'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="grid-nav" style="max-height: 280px; overflow-y: auto; margin-bottom: 20px;">
                            @foreach($questions as $index => $q)
                                <a href="#q-block-{{ $q->id }}" class="nav-btn {{ isset($savedAnswers[$q->id]) ? 'answered' : '' }}" id="nav-btn-{{ $q->id }}">
                                    {{ $index + 1 }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div style="font-size: 11.5px; color: var(--slate-600); margin-bottom: 18px; line-height: 1.5; background:#f8fafc; padding:10px; border-radius:8px; border:1px solid #e2e8f0;">
                        <span style="font-weight:700; color:#0f172a; display:block; margin-bottom:2px;">Aturan Selama Ujian:</span>
                        &bull; Dilarang berpindah tab atau membuka aplikasi lain.<br>
                        &bull; Ujian otomatis disubmit saat waktu habis atau jika batas peringatan terlampaui.
                    </div>

                    <button type="button" onclick="openConfirmFinishModal()" class="btn-finish">
                        <svg style="width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Selesai & Kumpulkan Ujian
                    </button>
                </div>
            </div>

        </div>
    </form>

    <!-- MODAL 1: START FULLSCREEN OVERLAY (MANDATORY) -->
    <div id="startModal" class="cbt-modal-overlay">
        <div class="cbt-modal-card">
            <div class="cbt-modal-icon info">
                <svg style="width:32px; height:32px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </div>
            <h3 style="font-size:18px; font-weight:700; color:#0f172a; margin-bottom:8px;">Portal Ujian CBT Terproteksi</h3>
            <p style="font-size:13px; color:#475569; line-height:1.6; margin-bottom:20px;">
                Demi menjaga integritas dan kejujuran seleksi masuk santri baru, ujian ini dilengkapi dengan <strong>Sistem Pengawas Anti-Contek Otomatis</strong>. Ujian wajib dikerjakan dalam mode <strong>Layar Penuh (Fullscreen)</strong>.
            </p>
            <button type="button" onclick="startExamFullscreen()" style="width:100%; padding:13px; background:#15803d; color:white; border:none; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(21,128,61,0.3);">
                Mulai Ujian Sekarang (Layar Penuh)
            </button>
        </div>
    </div>

    <!-- MODAL 2: CHEAT VIOLATION WARNING -->
    <div id="cheatWarningModal" class="cbt-modal-overlay" style="display: none;">
        <div class="cbt-modal-card">
            <div class="cbt-modal-icon warning">
                <svg style="width:32px; height:32px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            </div>
            <h3 style="font-size:18px; font-weight:800; color:#b91c1c; margin-bottom:8px;">Peringatan Kecurangan Terdeteksi!</h3>
            <p id="cheatWarningText" style="font-size:13.5px; color:#334155; line-height:1.6; margin-bottom:16px;">
                Anda terdeteksi beralih ke jendela/tab browser lain. Aktivitas ini tercatat secara otomatis oleh sistem pengawas!
            </p>
            <div id="cheatCountBadge" style="display:inline-block; font-size:12px; font-weight:700; background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; padding:4px 14px; border-radius:20px; margin-bottom:20px;">
                Peringatan 1 / {{ $maxViolations }}
            </div>
            <button type="button" onclick="dismissCheatWarning()" style="width:100%; padding:12px; background:#b91c1c; color:white; border:none; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer;">
                Kembali ke Lembar Ujian (Layar Penuh)
            </button>
        </div>
    </div>

    <!-- MODAL 3: CONFIRM FINISH -->
    <div id="confirmFinishModal" class="cbt-modal-overlay" style="display: none;">
        <div class="cbt-modal-card">
            <div class="cbt-modal-icon info">
                <svg style="width:32px; height:32px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <h3 style="font-size:18px; font-weight:700; color:#0f172a; margin-bottom:8px;">Kumpulkan Hasil Ujian?</h3>
            <p id="finishModalText" style="font-size:13px; color:#475569; line-height:1.6; margin-bottom:20px;">
                Apakah Anda yakin ingin menyelesaikan ujian ini sekarang? Setelah dikumpulkan, Anda tidak dapat mengubah jawaban lagi.
            </p>
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="document.getElementById('confirmFinishModal').style.display = 'none'" style="flex:1; padding:12px; background:#f1f5f9; color:#475569; border:none; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer;">
                    Periksa Lagi
                </button>
                <button type="button" onclick="submitExamNow()" style="flex:1; padding:12px; background:#15803d; color:white; border:none; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer;">
                    Ya, Kumpulkan
                </button>
            </div>
        </div>
    </div>

    <!-- KaTeX Scripts for live formula rendering -->
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"
            onload="renderMathInElement(document.getElementById('questionsContainer'), {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},
                    {left: '\\[', right: '\\]', display: true}
                ],
                throwOnError: false
            });"></script>

    <!-- ANTI-CHEAT & TIMER JAVASCRIPT CORE -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let remainingSeconds = {{ $remainingSeconds }};
        const totalQuestions = {{ count($questions) }};
        let examStarted = false;
        let isAutoSubmitting = false;

        // 1. COUNTDOWN TIMER
        function updateTimer() {
            if (remainingSeconds <= 0) {
                document.getElementById('countdownTimer').innerText = '00:00:00';
                if (!isAutoSubmitting) {
                    isAutoSubmitting = true;
                    alert('Waktu ujian Anda telah habis! Jawaban Anda akan otomatis dikirimkan.');
                    document.getElementById('examForm').submit();
                }
                return;
            }

            const h = Math.floor(remainingSeconds / 3600);
            const m = Math.floor((remainingSeconds % 3600) / 60);
            const s = remainingSeconds % 60;

            const strH = String(h).padStart(2, '0');
            const strM = String(m).padStart(2, '0');
            const strS = String(s).padStart(2, '0');

            document.getElementById('countdownTimer').innerText = `${strH}:${strM}:${strS}`;
            remainingSeconds--;
        }

        setInterval(updateTimer, 1000);
        updateTimer();

        // 2. QUESTION SELECTION & PROGRESS
        function selectOption(qId, key) {
            const card = document.getElementById('q-block-' + qId);
            const items = card.querySelectorAll('.option-item');
            items.forEach(el => el.classList.remove('selected'));

            const radio = document.getElementById('opt-' + qId + '-' + key);
            if (radio) {
                radio.checked = true;
                radio.closest('.option-item').classList.add('selected');
            }

            const navBtn = document.getElementById('nav-btn-' + qId);
            if (navBtn) navBtn.classList.add('answered');

            updateAnswerProgress();
        }

        function updateAnswerProgress() {
            const answeredCount = document.querySelectorAll('.nav-btn.answered').length;
            document.getElementById('progressCount').innerText = `${answeredCount} / ${totalQuestions} Terjawab`;
        }
        updateAnswerProgress();

        function openConfirmFinishModal() {
            const answeredCount = document.querySelectorAll('.nav-btn.answered').length;
            const text = `Anda telah menjawab ${answeredCount} dari ${totalQuestions} soal. Apakah Anda yakin ingin mengumpulkan ujian sekarang?`;
            document.getElementById('finishModalText').innerText = text;
            document.getElementById('confirmFinishModal').style.display = 'flex';
        }

        function submitExamNow() {
            isAutoSubmitting = true;
            document.getElementById('examForm').submit();
        }

        // 3. FULLSCREEN MODE
        function startExamFullscreen() {
            examStarted = true;
            document.getElementById('startModal').style.display = 'none';

            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log('Fullscreen request ignored:', err);
                });
            }
        }

        function dismissCheatWarning() {
            document.getElementById('cheatWarningModal').style.display = 'none';
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen().catch(() => {});
            }
        }

        // 4. ANTI-CHEAT DETECTION (Tab switch, Blur, Exit Fullscreen)
        function reportCheatViolation(type, message) {
            if (!examStarted || isAutoSubmitting) return;

            fetch('{{ route("ujian.logViolation") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ type: type, message: message })
            })
            .then(res => res.json())
            .then(data => {
                if (data.auto_submit) {
                    isAutoSubmitting = true;
                    alert('BATAS PELANGGARAN KECURANGAN TELAH HABIS!\nUjian Anda langsung dikunci dan dikirim secara otomatis ke pengawas.');
                    document.getElementById('examForm').submit();
                } else {
                    document.getElementById('cheatWarningText').innerText = data.message || 'Anda terdeteksi beralih aplikasi atau tab browser lain!';
                    document.getElementById('cheatCountBadge').innerText = `Pelanggaran ${data.violation_count} / ${data.max_violations}`;
                    document.getElementById('cheatWarningModal').style.display = 'flex';
                }
            })
            .catch(err => console.log('Violation report error:', err));
        }

        // Detect Visibility Change (Tab switch)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && examStarted) {
                reportCheatViolation('tab_switch', 'Beralih ke tab browser lain atau meminimalkan browser.');
            }
        });

        // Detect Window Blur (Switch to another desktop application)
        window.addEventListener('blur', () => {
            if (examStarted && !isAutoSubmitting) {
                reportCheatViolation('blur', 'Membuka aplikasi lain di luar browser ujian.');
            }
        });

        // Detect Fullscreen Exit
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement && examStarted && !isAutoSubmitting) {
                reportCheatViolation('fullscreen_exit', 'Keluar dari mode layar penuh (Fullscreen).');
            }
        });

        // 5. BLOCK CHEAT KEYS & CONTEXT MENU
        document.addEventListener('contextmenu', e => e.preventDefault());

        document.addEventListener('keydown', e => {
            // Block F12 (Inspect Element)
            if (e.key === 'F12') {
                e.preventDefault();
                reportCheatViolation('devtools', 'Mencoba membuka Developer Tools (F12).');
                return false;
            }

            // Block Ctrl+C (Copy), Ctrl+V (Paste), Ctrl+X (Cut), Ctrl+U (View Source), Ctrl+P (Print)
            if (e.ctrlKey || e.metaKey) {
                const k = e.key.toLowerCase();
                if (['c', 'v', 'x', 'u', 'p', 's', 'a'].includes(k)) {
                    e.preventDefault();
                    return false;
                }
                // Block Ctrl+Shift+I / J / C (Devtools)
                if (e.shiftKey && ['i', 'j', 'c'].includes(k)) {
                    e.preventDefault();
                    reportCheatViolation('devtools', 'Mencoba membuka inspect element.');
                    return false;
                }
            }
        });

        // Block drag and select
        document.addEventListener('dragstart', e => e.preventDefault());
    </script>
</body>

</html>

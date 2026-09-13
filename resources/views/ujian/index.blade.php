<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            --slate-100: #f1f5f9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0b1f14 0%, #143522 50%, #06170d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: #fff;
        }

        .portal-card {
            background: #ffffff;
            color: var(--slate-900);
            width: 100%;
            max-width: 560px;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            background: linear-gradient(135deg, #15803d, #166534);
            padding: 30px 28px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .logo-wrap img.logo-crest {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }

        .logo-wrap img.logo-calligraphy {
            height: 38px;
            filter: brightness(0) invert(1);
            opacity: 0.95;
        }

        .card-header h1 {
            font-family: 'EB Garamond', Georgia, serif;
            font-size: 25px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .card-header p {
            font-size: 13px;
            color: #bbf7d0;
            font-weight: 400;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .status-badge.buka {
            background: #22c55e;
            color: #052e16;
        }

        .status-badge.tutup {
            background: #ef4444;
            color: #ffffff;
        }

        .card-body {
            padding: 28px 26px;
        }

        /* Spec Meta Pills */
        .spec-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .spec-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px;
            border-radius: 12px;
            text-align: center;
        }

        .spec-item .label {
            font-size: 10px;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
        }

        .spec-item .val {
            font-size: 16px;
            font-weight: 800;
            color: #15803d;
            margin-top: 2px;
        }

        /* Notice Box */
        .notice-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 14px;
            border-radius: 12px;
            font-size: 12px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .notice-box .rules-title {
            font-weight: 700;
            color: #1e3a8a;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .anti-cheat-alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 11px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            line-height: 1.4;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--slate-800);
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s;
            font-family: inherit;
        }

        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #15803d, #166534);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(21, 128, 61, 0.25);
        }

        .btn-submit:hover:not(:disabled) {
            background: linear-gradient(135deg, #166534, #14532d);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(21, 128, 61, 0.35);
        }

        .btn-submit:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            box-shadow: none;
        }

        .alert-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 12px;
            margin-bottom: 18px;
            line-height: 1.5;
        }

        .portal-footer {
            text-align: center;
            padding: 16px 28px;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            font-size: 12px;
            color: var(--slate-600);
        }

        .portal-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .portal-footer a:hover {
            text-decoration: underline;
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

            <div>
                @if(($status ?? 'buka') !== 'buka')
                    <span class="status-badge tutup">
                        <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#ffffff;"></span>
                        Sesi Ujian Ditutup Oleh Panitia
                    </span>
                @elseif(($scheduleStatus ?? 'open') === 'upcoming')
                    <span class="status-badge" style="background:#fef3c7; color:#92400e;">
                        <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#d97706;"></span>
                        Jadwal Belum Dimulai
                    </span>
                @elseif(($scheduleStatus ?? 'open') === 'ended')
                    <span class="status-badge tutup">
                        <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#ffffff;"></span>
                        Jadwal Telah Berakhir
                    </span>
                @else
                    <span class="status-badge buka">
                        <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#14532d;"></span>
                        Sesi Ujian Aktif / Sedang Berlangsung
                    </span>
                @endif
            </div>
        </div>

        <!-- Body Form -->
        <div class="card-body">

            @if(!empty($enableSchedule) && !empty($startDateFormatted) && !empty($endDateFormatted))
                <!-- Rentang Jadwal Pelaksanaan Banner -->
                <div style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; font-size: 12px; color: #166534; display: flex; align-items: center; gap: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                    <span style="font-size: 22px; flex-shrink: 0;">🗓️</span>
                    <div>
                        <strong style="display:block; font-size: 12px; color: #14532d; text-transform: uppercase; letter-spacing: 0.3px;">Rentang Waktu Pengerjaan Ujian (CBT):</strong>
                        <span style="font-weight: 700; color: #166534;">{{ $startDateFormatted }}</span> s/d <span style="font-weight: 700; color: #166534;">{{ $endDateFormatted }}</span>
                    </div>
                </div>
            @endif

            <!-- Jadwal Per Materi Ujian (Matematika, Bahasa Arab, dll) -->
            @if(!empty($categorySchedules) && count($categorySchedules) > 0)
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 16px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <strong style="font-size: 13px; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                            <span>📑</span> Jadwal Ujian Per Materi Pelajaran
                        </strong>
                        <span style="font-size: 11px; color: #64748b; font-weight: 600;">{{ count($categorySchedules) }} Materi</span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 8px; max-height: 220px; overflow-y: auto; padding-right: 4px;">
                        @foreach($categorySchedules as $catName => $cData)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 12px;">
                            <div>
                                <strong style="color: #0f172a; display: block; font-size: 12px;">{{ $catName }}</strong>
                                <span style="font-size: 11px; color: #64748b;">
                                    @if($cData['enable_schedule'] && $cData['start_date_formatted'])
                                        {{ $cData['start_date_formatted'] }} - {{ $cData['end_date_formatted'] }}
                                    @else
                                        Sesuai Jadwal Global
                                    @endif
                                    • {{ $cData['duration_minutes'] }} Menit
                                </span>
                            </div>

                            <div>
                                @if(($cData['status'] ?? 'buka') === 'tutup')
                                    <span style="background: #fee2e2; color: #991b1b; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px;">Ditutup</span>
                                @elseif($cData['sched_state'] === 'upcoming')
                                    <span style="background: #fef3c7; color: #92400e; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px;">Belum Buka</span>
                                @elseif($cData['sched_state'] === 'ended')
                                    <span style="background: #e2e8f0; color: #475569; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px;">Berakhir</span>
                                @else
                                    <span style="background: #dcfce7; color: #166534; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px;">🟢 Aktif</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(($scheduleStatus ?? 'open') === 'upcoming')
                <div style="background: #fffbeb; border: 1.5px solid #fde68a; color: #92400e; padding: 14px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; line-height: 1.5;">
                    <div style="font-weight: 800; display: flex; items-center; gap: 6px; margin-bottom: 4px;">
                        <span>⏳</span> Ujian Seleksi Belum Dimulai
                    </div>
                    Portal pengerjaan soal baru akan dibuka pada <strong>{{ $startDateFormatted }}</strong>. Mohon persiapkan diri dan perangkat Anda, lalu kembali saat waktu ujian telah tiba.
                </div>
            @elseif(($scheduleStatus ?? 'open') === 'ended')
                <div style="background: #fef2f2; border: 1.5px solid #fecaca; color: #991b1b; padding: 14px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; line-height: 1.5;">
                    <div style="font-weight: 800; display: flex; items-center; gap: 6px; margin-bottom: 4px;">
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
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
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
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>Tata Tertib & Petunjuk Ujian:</span>
                </div>
                <div style="white-space: pre-line;">{!! nl2br(e($instructions ?? "1. Berdoalah sebelum mengerjakan soal.\n2. Kerjakan dengan jujur secara mandiri.\n3. Ujian otomatis terkunci jika batas toleransi pelanggaran terlampaui.")) !!}</div>
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
                    <input type="text" id="no_registrasi" name="no_registrasi" value="{{ old('no_registrasi', request('no_reg')) }}" required placeholder="Contoh: PSB-25-0001" autocomplete="off" autofocus style="font-family:monospace; text-transform:uppercase;" {{ !$isAllowed ? 'disabled' : '' }}>
                </div>

                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap Calon Santri</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Nama lengkap sesuai pendaftaran" autocomplete="name" {{ !$isAllowed ? 'disabled' : '' }}>
                </div>

                <div class="form-group">
                    <label for="kategori">Materi Ujian yang Akan Dikerjakan</label>
                    <select id="kategori" name="kategori" style="width: 100%; padding: 12px 16px; font-size: 14px; border: 1.5px solid #cbd5e1; border-radius: 10px; outline: none; background: #ffffff; color: var(--slate-800); font-family: inherit; cursor: pointer;" {{ !$isAllowed ? 'disabled' : '' }}>
                        <option value="all">📚 Semua Materi Terpadu (Keseluruhan Soal)</option>
                        @if(!empty($categorySchedules))
                            @foreach($categorySchedules as $catName => $cData)
                                @php
                                    $isCatDisabled = (($cData['status'] ?? 'buka') === 'tutup') || ($cData['sched_state'] === 'ended');
                                @endphp
                                <option value="{{ $catName }}" {{ $isCatDisabled ? 'style=color:#94a3b8;' : '' }}>
                                    📖 {{ $catName }} ({{ $cData['total_questions'] }} Soal • {{ $cData['duration_minutes'] }} Menit)
                                    @if(($cData['status'] ?? 'buka') === 'tutup')
                                        [🔴 Ditutup]
                                    @elseif($cData['sched_state'] === 'upcoming')
                                        [⏳ Mulai: {{ $cData['start_date_formatted'] }}]
                                    @elseif($cData['sched_state'] === 'ended')
                                        [🛑 Selesai: {{ $cData['end_date_formatted'] }}]
                                    @else
                                        [🟢 Aktif]
                                    @endif
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <span style="display: block; font-size: 11px; color: #64748b; margin-top: 4px;">Pilih materi sesuai jadwal pengawas ujian Anda.</span>
                </div>

                <button type="submit" class="btn-submit" {{ !$isAllowed ? 'disabled style=opacity:0.65;cursor:not-allowed;background:#64748b;' : '' }}>
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
</body>

</html>

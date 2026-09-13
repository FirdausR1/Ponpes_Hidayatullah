<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Hasil Ujian Seleksi Masuk - {{ $reg->nama_lengkap }}</title>
    <link rel="icon" href="/logo.png" type="image/png">
    <!-- Google Fonts: EB Garamond & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #15803d;
            --primary-dark: #166534;
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

        .result-card {
            background: #ffffff;
            color: var(--slate-900);
            width: 100%;
            max-width: 680px;
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
            padding: 28px;
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
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .logo-wrap img.logo-calligraphy {
            height: 36px;
            filter: brightness(0) invert(1);
        }

        .card-header h1 {
            font-family: 'EB Garamond', Georgia, serif;
            font-size: 26px;
            font-weight: 700;
        }

        .card-header p {
            font-size: 13px;
            color: #bbf7d0;
            margin-top: 2px;
        }

        .card-body {
            padding: 30px 28px;
        }

        /* Status Banner */
        .status-banner {
            padding: 16px 20px;
            border-radius: 16px;
            text-align: center;
            margin-bottom: 24px;
            border: 2px solid transparent;
        }

        .status-banner.lulus {
            background: #f0fdf4;
            border-color: #86efac;
            color: #166534;
        }

        .status-banner.tidak-lulus {
            background: #fff1f2;
            border-color: #fecdd3;
            color: #9f1239;
        }

        .status-banner.lulus-bersyarat {
            background: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }

        .status-banner.cadangan {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #334155;
        }

        .status-banner .title {
            font-size: 20px;
            font-weight: 800;
            font-family: 'EB Garamond', Georgia, serif;
            letter-spacing: 0.5px;
        }

        .status-banner .desc {
            font-size: 13px;
            margin-top: 4px;
            opacity: 0.9;
        }

        /* Score Overview */
        .score-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .score-item {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 12px;
            text-align: center;
        }

        .score-item .lbl {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 4px;
        }

        .score-item .num {
            font-size: 32px;
            font-weight: 800;
            line-height: 1.1;
            font-family: 'EB Garamond', Georgia, serif;
            color: #1e293b;
        }

        .score-item.highlight {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .score-item.highlight .num {
            color: #15803d;
        }

        /* Details Table */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 24px;
        }

        .details-table tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .details-table td {
            padding: 10px 4px;
        }

        .details-table td:first-child {
            color: var(--slate-600);
            width: 44%;
        }

        .details-table td:last-child {
            font-weight: 600;
            color: var(--slate-800);
            text-align: right;
        }

        .alert-box {
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 12px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .alert-cheat {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-note {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .action-btns {
            display: flex;
            gap: 12px;
        }

        .btn-print {
            flex: 1;
            padding: 12px;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            color: var(--slate-800);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-print:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .btn-home {
            flex: 1;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-home:hover {
            background: var(--primary-dark);
        }

        /* Print Specific Styling */
        .print-signature {
            display: none;
            margin-top: 30px;
            justify-content: space-between;
            color: #000;
            font-size: 12px;
        }

        @media print {
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
            .action-btns, .card-header .logo-calligraphy, .no-print {
                display: none !important;
            }
            .result-card {
                box-shadow: none !important;
                border: 1px solid #000 !important;
                max-width: 100% !important;
            }
            .card-header {
                background: #fff !important;
                color: #000 !important;
                border-bottom: 2px solid #000 !important;
                padding: 15px !important;
            }
            .card-header h1 {
                color: #000 !important;
            }
            .card-header p {
                color: #555 !important;
            }
            .print-signature {
                display: flex !important;
            }
            .score-item {
                border-color: #999 !important;
            }
            .subject-breakdown-card {
                border: 1px solid #777 !important;
                box-shadow: none !important;
                page-break-inside: avoid;
            }
            .mapel-table th, .mapel-table td {
                border-bottom: 1px solid #ccc !important;
                color: #000 !important;
            }
        }

        /* Subject Breakdown Table */
        .subject-breakdown-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            background: #ffffff;
            margin-bottom: 24px;
        }

        .mapel-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .mapel-table th {
            background: #f8fafc;
            padding: 10px 14px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }

        .mapel-table td {
            padding: 11px 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .mapel-table tr:last-child td {
            border-bottom: none;
        }

        .badge-tuntas {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .badge-belum {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            background: #fff1f2;
            color: #9f1239;
            border: 1px solid #fecdd3;
        }
    </style>
</head>

<body>
    <div class="result-card">
        <!-- Header -->
        <div class="card-header">
            <div class="logo-wrap">
                <img src="/logo.png" alt="Logo" class="logo-crest">
                <img src="/logo1.png" alt="Ma'had Hidayatullah" class="logo-calligraphy">
            </div>
            <h1>Kartu Hasil Ujian Seleksi Masuk</h1>
            <p>Pondok Pesantren Hidayatullah Tuksongo • TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}</p>
        </div>

        <!-- Body -->
        <div class="card-body">
            @php
                $statusKelulusan = $reg->status_kelulusan;
                $bannerClass = match($statusKelulusan) {
                    'Lulus' => 'lulus',
                    'Lulus Bersyarat' => 'lulus-bersyarat',
                    'Cadangan' => 'cadangan',
                    default => 'tidak-lulus',
                };
            @endphp

            <!-- Status Kelulusan Banner -->
            <div class="status-banner {{ $bannerClass }}">
                <div class="title">
                    @if($statusKelulusan === 'Lulus')
                        ALHAMDULILLAH, DINYATAKAN LULUS SELEKSI
                    @elseif($statusKelulusan === 'Lulus Bersyarat')
                        DINYATAKAN LULUS BERSYARAT
                    @elseif($statusKelulusan === 'Cadangan')
                        STATUS: SANTRI CADANGAN
                    @else
                        MOHON MAAF, BELUM MEMENUHI SYARAT KELULUSAN
                    @endif
                </div>
                <div class="desc">
                    @if($statusKelulusan === 'Lulus')
                        Selamat! Calon santri telah memenuhi kriteria akademik dan syarat kelulusan seleksi penerimaan santri baru.
                    @elseif($statusKelulusan === 'Lulus Bersyarat')
                        Calon santri diterima dengan persyaratan pembinaan khusus / matrikulasi keagamaan.
                    @elseif($statusKelulusan === 'Cadangan')
                        Calon santri masuk daftar tunggu seleksi. Panitia akan menginfokan ketersediaan kuota.
                    @else
                        Tetap semangat dan terus belajar. Keputusan panitia penerimaan santri baru bersifat mutlak.
                    @endif
                </div>
            </div>

            <!-- Score Overview -->
            <div class="score-grid">
                <div class="score-item highlight">
                    <div class="lbl">Nilai Akhir (CBT)</div>
                    <div class="num">{{ $reg->nilai_ujian ?? 0 }}</div>
                </div>
                <div class="score-item">
                    <div class="lbl">Standar KKM</div>
                    <div class="num">{{ $kkm ?? 70 }}</div>
                </div>
                <div class="score-item">
                    <div class="lbl">Soal Dijawab Benar</div>
                    <div class="num">{{ $correctCount ?? 0 }} <span style="font-size:16px; font-weight:500; color:#64748b;">/ {{ $totalQuestions ?? 0 }}</span></div>
                </div>
            </div>

            @php
                $mapelData = $subjectResults ?? ($reg->nilai_per_mapel_array ?? []);
            @endphp

            <!-- Rincian Nilai Per Mata Pelajaran -->
            <div class="subject-breakdown-card">
                <div style="background: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg style="width: 17px; height: 17px; color: #15803d;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        <span style="font-size: 13px; font-weight: 700; color: #1e293b;">Rincian Nilai Per Mata Pelajaran</span>
                    </div>
                    <span style="font-size: 11px; font-weight: 600; color: #64748b;">Standar KKM: {{ $kkm ?? 70 }}</span>
                </div>

                <div style="overflow-x: auto;">
                    <table class="mapel-table">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 36px;">No</th>
                                <th style="text-align: left;">Mata Pelajaran</th>
                                <th style="text-align: center; width: 80px;">Soal</th>
                                <th style="text-align: center; width: 80px;">Benar</th>
                                <th style="text-align: center; width: 85px;">Nilai</th>
                                <th style="text-align: center; width: 105px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mapelData as $m)
                                @php
                                    $targetKkm = $m['kkm'] ?? ($kkm ?? 70);
                                    $isPassed = ($m['skor'] ?? 0) >= $targetKkm;
                                @endphp
                                <tr>
                                    <td style="text-align: center; color: #64748b; font-weight: 600;">{{ $loop->iteration }}</td>
                                    <td style="font-weight: 600; color: #1e293b;">
                                        {{ $m['kategori'] }}
                                    </td>
                                    <td style="text-align: center; color: #475569;">
                                        {{ $m['total_soal'] }}
                                    </td>
                                    <td style="text-align: center; font-weight: 600; color: {{ ($m['benar'] ?? 0) > 0 ? '#15803d' : '#64748b' }};">
                                        {{ $m['benar'] ?? 0 }} / {{ $m['total_soal'] }}
                                    </td>
                                    <td style="text-align: center;">
                                        <span style="font-size: 14px; font-weight: 800; font-family: 'EB Garamond', Georgia, serif; color: {{ $isPassed ? '#15803d' : '#b91c1c' }};">
                                            {{ $m['skor'] ?? 0 }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        @if($isPassed)
                                            <span class="badge-tuntas">Tuntas</span>
                                        @else
                                            <span class="badge-belum">Belum Tuntas</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding: 16px; text-align: center; color: #94a3b8;">
                                        Rincian nilai per mata pelajaran belum tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes or Anti-cheat if any -->
            @if($reg->pelanggaran_curang_count > 0)
                <div class="alert-box alert-cheat">
                    <strong>Catatan Pengawas Anti-Curang:</strong> Terdeteksi {{ $reg->pelanggaran_curang_count }} kali indikasi kecurangan (berpindah jendela browser / tab / keluar layar penuh) selama pengerjaan ujian.
                </div>
            @endif

            @if(!empty($reg->catatan_penguji))
                <div class="alert-box alert-note">
                    <strong>Catatan Tim Penguji / Panitia:</strong><br>
                    {{ $reg->catatan_penguji }}
                </div>
            @endif

            <!-- Attempt Status -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 18px; height: 18px; color: #4f46e5;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span style="font-size: 13px; font-weight: 700; color: #1e293b;">Riwayat Kesempatan Ujian:</span>
                </div>
                <span style="font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 9999px; {{ ($maxAttempts == 0 || $attemptsCount < $maxAttempts) ? 'background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;' : 'background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;' }}">
                    @if($maxAttempts == 0)
                        Percobaan ke-{{ $attemptsCount }} (Mode Tryout / Bebas)
                    @else
                        Percobaan ke-{{ $attemptsCount }} dari {{ $maxAttempts }} kali kesempatan
                    @endif
                </span>
            </div>

            <!-- Candidate Details -->
            <table class="details-table">
                <tr>
                    <td>Nomor Pendaftaran</td>
                    <td style="font-family: monospace; font-weight:700;">{{ $reg->no_registrasi }}</td>
                </tr>
                <tr>
                    <td>Nama Lengkap Santri</td>
                    <td>{{ $reg->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td>Jenjang Pendidikan</td>
                    <td>{{ $reg->jenjang }}</td>
                </tr>
                <tr>
                    <td>Jalur Pendaftaran</td>
                    <td>{{ $reg->jalur }}</td>
                </tr>
                <tr>
                    <td>Nama Orang Tua / Wali</td>
                    <td>{{ $reg->nama_wali }}</td>
                </tr>
                <tr>
                    <td>Waktu Selesai Ujian</td>
                    <td>{{ $reg->ujian_selesai_at ? \Carbon\Carbon::parse($reg->ujian_selesai_at)->translatedFormat('d F Y, H:i') . ' WIB' : date('d F Y, H:i') . ' WIB' }}</td>
                </tr>
                <tr>
                    <td>Status Berkas Registrasi</td>
                    <td>
                        <span style="color: {{ $reg->status === 'Diterima' ? '#15803d' : ($reg->status === 'Ditolak' ? '#be123c' : '#b45309') }}; font-weight:700;">
                            {{ $reg->status }}
                        </span>
                    </td>
                </tr>
            </table>

            <!-- Official Signature Block for Print -->
            <div class="print-signature">
                <div style="text-align: center;">
                    <p>Orang Tua / Calon Santri,</p>
                    <div style="height: 50px;"></div>
                    <p style="text-decoration: underline; font-weight: 700;">{{ $reg->nama_wali ?: $reg->nama_lengkap }}</p>
                </div>
                <div style="text-align: center;">
                    <p>Temanggung, {{ date('d F Y') }}<br>Ketua Panitia Ujian Seleksi,</p>
                    <div style="height: 50px;"></div>
                    <p style="text-decoration: underline; font-weight: 700;">Ustadz Panitia PSB</p>
                    <p style="font-size: 10px; color: #666;">NIP: 198507122010011005</p>
                </div>
            </div>

            <!-- Retake Button if Attempts Remain -->
            @if($maxAttempts == 0 || $attemptsCount < $maxAttempts)
                <div style="margin-top: 20px; margin-bottom: 12px;" class="action-btns">
                    <form action="{{ route('ujian.retake') }}" method="POST" style="width: 100%;" onsubmit="return confirm('Mulai kesempatan ujian berikutnya? Anda akan diarahkan kembali ke ruang ujian CBT untuk mengerjakan soal.')">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 13px; background: linear-gradient(135deg, #4f46e5, #4338ca); color: #ffffff; border: none; border-radius: 10px; font-weight: 700; font-size: 13.5px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); transition: all 0.2s;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                            <span>Ujian Ulang / Mulai Kesempatan Berikutnya (Sisa: {{ $maxAttempts > 0 ? ($maxAttempts - $attemptsCount) . ' Kali' : 'Tanpa Batas' }})</span>
                        </button>
                    </form>
                </div>
            @endif

            <!-- Berkas Dokumen Resmi Ujian & Kelulusan -->
            <div class="no-print" style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1.5px solid #a7f3d0; border-radius: 14px; padding: 16px; margin-top: 22px; margin-bottom: 20px; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.08);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #059669; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            📁
                        </div>
                        <div>
                            <div style="font-size: 13.5px; font-weight: 800; color: #065f46;">Berkas Arsip Resmi Seleksi & Nilai CBT</div>
                            <div style="font-size: 11.5px; color: #047857;">Hasil ujian terarsip resmi ke dalam Berkas Santri dengan Kop Pondok & Stempel Sah.</div>
                        </div>
                    </div>
                    <span style="font-size: 10.5px; font-weight: 700; background: #d1fae5; color: #065f46; padding: 3px 10px; border-radius: 9999px; border: 1px solid #a7f3d0;">
                        Terintegrasi Berkas
                    </span>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px;">
                    <a href="{{ route('psb.printCard', ['id' => $reg->id, 'mode' => 'all']) }}" target="_blank" style="padding: 11px 14px; background: #059669; color: #ffffff; border-radius: 10px; font-size: 12.5px; font-weight: 700; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25); transition: all 0.2s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        <span>Cetak Berkas Lengkap (CV & Nilai)</span>
                    </a>
                    <a href="{{ route('psb.printCard', ['id' => $reg->id, 'mode' => 'ujian']) }}" target="_blank" style="padding: 11px 14px; background: #ffffff; color: #065f46; border: 1.5px solid #a7f3d0; border-radius: 10px; font-size: 12.5px; font-weight: 700; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        <span>Cetak Lembar Nilai CBT Resmi</span>
                    </a>
                </div>
            </div>

            <!-- Actions -->
            <div class="action-btns">
                <button type="button" class="btn-print" onclick="window.print()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    <span>Cetak Kartu Hasil</span>
                </button>
                <a href="{{ route('psb.checkStatus', ['no_reg' => $reg->no_registrasi]) }}" class="btn-home">
                    <span>Pantau Pengumuman</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</body>

</html>

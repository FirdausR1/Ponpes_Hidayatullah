<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekapitulasi Nilai CBT — {{ $title }}</title>
    <link rel="icon" href="/logo.png" type="image/png">
    <!-- Google Fonts: EB Garamond, Plus Jakarta Sans, Amiri -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=EB+Garamond:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #111;
            background: #f8fafc;
            padding: 24px;
            font-size: 12px;
            line-height: 1.5;
        }
        .sheet {
            background: #fff;
            max-width: 960px;
            margin: 0 auto;
            padding: 36px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .header-kop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #111;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .kop-logo {
            width: 68px;
            height: 68px;
            object-fit: contain;
        }
        .kop-text {
            text-align: center;
            flex: 1;
            padding: 0 16px;
        }
        .kop-text h2 {
            font-family: 'EB Garamond', serif;
            font-size: 19px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0d3b1e;
        }
        .kop-text .arabic-sub {
            font-family: 'Amiri', serif;
            font-size: 17px;
            direction: rtl;
            color: #145a2e;
            margin-top: -2px;
        }
        .kop-text p {
            font-size: 10.5px;
            color: #444;
            margin-top: 2px;
        }
        .title-block {
            text-align: center;
            margin-bottom: 18px;
        }
        .title-block h3 {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .title-block p {
            font-size: 11px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
            margin-bottom: 24px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 7px 10px;
        }
        th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10.5px;
            color: #334155;
            text-align: left;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-lulus {
            font-weight: 700;
            color: #15803d;
        }
        .badge-gagal {
            font-weight: 700;
            color: #b91c1c;
        }
        .sign-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            margin-top: 36px;
            page-break-inside: avoid;
            font-size: 11.5px;
        }
        .sign-box { text-align: center; width: 220px; }
        .sign-space { height: 60px; }
        .no-print-bar {
            max-width: 960px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .btn-print {
            background: #0d3b1e;
            color: #fff;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .sheet { box-shadow: none; padding: 10px; max-width: 100%; }
            .no-print-bar { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <a href="{{ route('admin.cbt.hasil.index') }}" style="text-decoration:none; color:#475569; font-size:12px; font-weight:600;">&larr; Kembali ke Hasil CBT</a>
        <button onclick="window.print()" class="btn-print">
            <svg style="width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Cetak Rekapitulasi (Print / PDF)
        </button>
    </div>

    <div class="sheet">
        <!-- KOP SURAT RESMI (PONDOK TUKSONGO) -->
        <div style="text-align: center; margin-bottom: 12px; border-bottom: 2.5px solid #0f172a; padding-bottom: 8px;">
            <img src="/images/kop_psb.png" alt="Kop Surat Resmi Pondok Pesantren Hidayatullah Tuksongo" style="max-height: 95px; width: auto; max-width: 100%; object-fit: contain;">
        </div>

        <div class="title-block">
            <h3>{{ $title }}</h3>
            <p>Standar Kriteria Ketuntasan Minimal (KKM): <strong>{{ $kkm }}</strong> • @if(!empty($jenjang)) <span style="color:#0d3b1e; font-weight:700;">Kelompok: Jenjang {{ $jenjang }}</span> • @endif Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }} WIB</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 36px;">No</th>
                    <th style="width: 130px;">No. Registrasi</th>
                    <th>Nama Calon Santri</th>
                    <th style="width: 75px;">Jenjang</th>
                    <th class="text-center" style="width: 70px;">Nilai CBT</th>
                    <th class="text-center" style="width: 100px;">Status Kelulusan</th>
                    <th>Catatan Panitia / Penguji</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $idx => $s)
                    @php $statusKel = $s->status_kelulusan; @endphp
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td style="font-family: monospace; font-weight:600;">{{ $s->no_registrasi }}</td>
                        <td style="font-weight: 600;">{{ $s->nama_lengkap }}</td>
                        <td>{{ $s->jenjang }}</td>
                        <td class="text-center" style="font-weight:700; font-size:12px;">{{ $s->nilai_ujian ?? '-' }}</td>
                        <td class="text-center">
                            @if($statusKel === 'Lulus')
                                <span class="badge-lulus">LULUS</span>
                            @elseif($statusKel === 'Tidak Lulus')
                                <span class="badge-gagal">TIDAK LULUS</span>
                            @else
                                <span style="font-weight:600; color:#b45309;">{{ strtoupper($statusKel) }}</span>
                            @endif
                        </td>
                        <td style="font-size: 10.5px; color:#555;">
                            {{ $s->catatan_penguji ?: '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 24px; color:#888;">Belum ada santri yang menyelesaikan ujian CBT.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tanda Tangan Panitia & Pimpinan -->
        <div class="sign-grid">
            <div class="sign-box">
                <p>Mengetahui,</p>
                <p style="font-weight:600;">Pimpinan Pondok Pesantren,</p>
                <div class="sign-space"></div>
                <p style="font-weight:700; text-decoration:underline;">( K.H. Dewan Pengasuh )</p>
                <p style="font-size:10px; color:#555;">Pondok Pesantren Hidayatullah</p>
            </div>

            <div class="sign-box" style="margin-left:auto;">
                <p>Temanggung, {{ now()->translatedFormat('d F Y') }}</p>
                <p style="font-weight:600;">Ketua Panitia PSB & CBT,</p>
                <div class="sign-space"></div>
                <p style="font-weight:700; text-decoration:underline;">( Ustadz Panitia Seleksi )</p>
                <p style="font-size:10px; color:#555;">NIY: 2026.09.PSB.001</p>
            </div>
        </div>
    </div>

</body>
</html>

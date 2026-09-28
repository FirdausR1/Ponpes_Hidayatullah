<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actual Daily Report PSB TA {{ $tahunAjaran }} — Per Tanggal {{ $tanggalFormatted }}</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=EB+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0d3b1e;
            --primary-light: #166534;
            --accent: #ca8a04;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --slate-500: #64748b;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1e293b;
            background: #e2e8f0;
            padding: 24px 12px;
            font-size: 11px;
            line-height: 1.45;
        }

        /* Top Action Bar (Hanya Muncul di Layar Monitor) */
        .no-print-bar {
            max-width: 960px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            background: #0d3b1e;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(13, 59, 30, 0.25);
            transition: all 0.2s;
        }

        .btn-print:hover {
            background: #166534;
        }

        .filter-form {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }

        .filter-form select, .filter-form input {
            font-size: 11.5px;
            font-weight: 600;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 4px 8px;
            color: #0f172a;
            background: #fff;
        }

        /* Lembar Dokumen Resmi Kertas A4 */
        .report-sheet {
            background: #ffffff;
            max-width: 960px;
            margin: 0 auto;
            padding: 32px 36px;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        /* Kop Surat Resmi */
        .kop-wrapper {
            text-align: center;
            margin-bottom: 4px;
        }

        .kop-wrapper img {
            max-height: 100px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
        }

        .kop-line {
            border-top: 3px double #0f172a;
            margin-top: 8px;
            margin-bottom: 18px;
        }

        /* Judul Dokumen */
        .doc-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .doc-header h1 {
            font-family: 'EB Garamond', Georgia, serif;
            font-size: 19px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #0d3b1e;
        }

        .doc-header h2 {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 2px;
            letter-spacing: 0.02em;
        }

        .doc-header .meta {
            font-size: 11px;
            color: #475569;
            margin-top: 4px;
        }

        /* Daily Report Box Format (Format Sesuai Permintaan) */
        .daily-report-box {
            border: 1.5px solid #0d3b1e;
            border-radius: 8px;
            background: #fcfdfa;
            padding: 16px 20px;
            margin-bottom: 22px;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 11px;
            line-height: 1.6;
            color: #0f172a;
        }

        .daily-report-box .title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #0d3b1e;
            margin-bottom: 8px;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 6px;
        }

        .daily-report-box .section-title {
            font-weight: 700;
            margin-top: 8px;
            margin-bottom: 3px;
            color: #0f172a;
        }

        .daily-report-box .item-row {
            padding-left: 14px;
        }

        .daily-report-box .total-row {
            padding-left: 14px;
            font-weight: 700;
            margin-top: 2px;
            margin-bottom: 6px;
            border-top: 1px dotted #e2e8f0;
            padding-top: 2px;
        }

        .daily-report-box .grand-section {
            border-top: 1.5px solid #0d3b1e;
            padding-top: 8px;
            margin-top: 10px;
            font-weight: 700;
            font-size: 11.5px;
        }

        /* 4 Executive KPI Cards */
        .exec-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 22px;
        }

        .exec-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            background: #f8fafc;
        }

        .exec-card.primary {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .exec-card.ma {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .exec-card.mundur {
            background: #fff1f2;
            border-color: #fecdd3;
        }

        .exec-card .lbl {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        .exec-card .val {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 2px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .exec-card .sub {
            font-size: 9.5px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Tabel Rekapitulasi */
        table.rekap-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 10.5px;
        }

        table.rekap-table th, table.rekap-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
        }

        table.rekap-table th {
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9.5px;
            letter-spacing: 0.03em;
            text-align: center;
        }

        table.rekap-table tr.row-total {
            background: #f8fafc;
            font-weight: 700;
        }

        table.rekap-table tr.row-grand {
            background: #0d3b1e;
            color: #ffffff;
            font-weight: 800;
            font-size: 11px;
        }

        table.rekap-table tr.row-grand td {
            border-color: #0d3b1e;
        }

        /* Signatures Grid */
        .sign-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 28px;
            page-break-inside: avoid;
            text-align: center;
            font-size: 11px;
            max-width: 680px;
            margin-left: auto;
            margin-right: auto;
        }

        .sign-box {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 120px;
        }

        .sign-box .role-title {
            font-weight: 700;
            color: #0f172a;
        }

        .sign-box .name {
            font-weight: 800;
            text-decoration: underline;
            color: #0f172a;
            font-size: 11.5px;
        }

        .sign-box .desc {
            font-size: 9.5px;
            color: #64748b;
            margin-top: 2px;
        }

        .sign-img-container {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 4px 0;
            position: relative;
        }

        .sign-img-container img {
            max-height: 55px;
            max-width: 140px;
            object-fit: contain;
        }

        .sign-stamp {
            position: absolute;
            max-height: 60px;
            opacity: 0.85;
            left: 50%;
            transform: translateX(-30%);
            pointer-events: none;
        }

        /* Print Media Styles */
        @page {
            size: A4 portrait;
            margin: 12mm 14mm;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                color: #000000 !important;
            }

            .no-print-bar {
                display: none !important;
            }

            .report-sheet {
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
                border-radius: 0 !important;
            }

            table.rekap-table th {
                background: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .exec-card.primary {
                background: #f0fdf4 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .exec-card.ma {
                background: #eff6ff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .exec-card.mundur {
                background: #fff1f2 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table.rekap-table tr.row-grand {
                background: #0d3b1e !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .daily-report-box {
                background: #fcfdfa !important;
                border-color: #0d3b1e !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Navigasi Aksi (Hanya Tampil di Layar Monitor) -->
    <div class="no-print-bar">
        <a href="{{ route('admin.psb.laporan', request()->all()) }}" class="btn-back">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke Laporan
        </a>

        <!-- Form Filter Periode Cut-Off Langsung -->
        <form method="GET" action="{{ route('admin.psb.laporan.cetak') }}" class="filter-form">
            <input type="hidden" name="mode_data" value="{{ $modeData }}">
            <span style="font-size:11px; font-weight:700; color:#334155; text-transform:uppercase;">Cut-Off:</span>
            <input type="date" name="per_tanggal" value="{{ $perTanggal }}" onchange="this.form.submit()">
            
            <span style="font-size:11px; font-weight:700; color:#334155; text-transform:uppercase; margin-left:6px;">TA:</span>
            <input type="text" name="tahun_ajaran" value="{{ $tahunAjaran }}" style="width:75px;" onchange="this.form.submit()">

            <select name="mode_data" onchange="this.form.submit()">
                <option value="contoh" {{ $modeData === 'contoh' ? 'selected' : '' }}>Contoh (256)</option>
                <option value="real" {{ $modeData === 'real' ? 'selected' : '' }}>Data Riil ({{ $realCount }})</option>
            </select>
        </form>

        <button onclick="window.print()" class="btn-print">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Cetak Dokumen (PDF / Print)
        </button>
    </div>

    <!-- Lembar Dokumen Resmi Kertas A4 -->
    <div class="report-sheet">

        <!-- KOP SURAT RESMI PONDOK PESANTREN HIDAYATULLAH TUKSONGO -->
        <div class="kop-wrapper">
            <img src="/images/kop_psb.png" alt="Kop Surat Resmi Pondok Pesantren Hidayatullah Tuksongo">
        </div>
        <div class="kop-line"></div>

        <!-- HEADER DOKUMEN -->
        <div class="doc-header">
            <h1>Laporan Harian Penerimaan Santri Baru (PSB)</h1>
            <h2>ACTUAL DAILY REPORT TAHUN AJARAN {{ $tahunAjaran }}</h2>
            <div class="meta">
                Kepada Yth. <strong>Pimpinan Pondok Pesantren Hidayatullah</strong> • Posisi Pelaporan: <strong>{{ $tanggalLengkapFormatted }}</strong>
            </div>
        </div>

        <!-- BOX FORMAT PERSIS SESUAI CONTOH PERMINTAAN USER -->
        <div class="daily-report-box">
            <div class="title">"Actual daily report PSB Tahun Ajaran {{ $tahunAjaran }}"</div>
            
            <div style="font-weight:700; margin-bottom:6px;">
                * Per tanggal: {{ $tanggalFormatted }}
            </div>

            <!-- MTs -->
            <div class="section-title">* Jumlah pendaftar MTs</div>
            <div class="item-row">° Gel. 1 = {{ $mtsData['items']['gel_1']['total'] }} anak</div>
            <div class="item-row">° Gel. 2 = {{ $mtsData['items']['gel_2']['total'] }} anak</div>
            @if(($mtsData['items']['internal']['total'] ?? 0) > 0)
                <div class="item-row">° Internal = {{ $mtsData['items']['internal']['total'] }} anak</div>
            @endif
            <div class="item-row" style="color:#b91c1c;">° Mengundurkan diri = {{ $mtsData['mengundurkan_diri']['total'] }} anak</div>
            <div class="total-row">Total MTs = {{ $mtsData['total_bersih'] }} anak</div>

            <!-- MA -->
            <div class="section-title">• Jumlah pendaftar MA</div>
            <div class="item-row">
                ° Internal = {{ $maData['items']['internal']['total'] }} anak 
                <span style="font-weight:600; color:#1e40af;">(putri : {{ $maData['items']['internal']['putri'] }} anak) (Putra : {{ $maData['items']['internal']['putra'] }} anak)</span>
            </div>
            <div class="item-row">° Gel. 1 = {{ $maData['items']['gel_1']['total'] }} anak</div>
            <div class="item-row">° Gel. 2 = {{ $maData['items']['gel_2']['total'] }} anak</div>
            <div class="item-row" style="color:#b91c1c;">° Mengundurkan diri = {{ $maData['mengundurkan_diri']['total'] }} anak</div>
            <div class="total-row">Total MA = {{ $maData['total_bersih'] }} anak</div>

            <!-- Grand Totals -->
            <div class="grand-section">
                <div>~ Grand Total Pendaftar = {{ $grandTotal['pendaftar'] }} anak</div>
                <div style="color:#b91c1c;">~ Grand Total Mengundurkan diri = {{ $grandTotal['mengundurkan_diri'] }} anak</div>
                <div style="color:#15803d; font-size:12px; margin-top:2px;">~ Grand Total Keseluruhan = {{ $grandTotal['keseluruhan'] }} anak</div>
            </div>
        </div>

        <!-- 4 SUMMARY CARDS -->
        <div class="exec-grid">
            <div class="exec-card primary">
                <div class="lbl">Total MTs (Bersih)</div>
                <div class="val" style="color:#15803d;">{{ $mtsData['total_bersih'] }}</div>
                <div class="sub">Gel 1: {{ $mtsData['items']['gel_1']['total'] }} | Gel 2: {{ $mtsData['items']['gel_2']['total'] }}</div>
            </div>

            <div class="exec-card ma">
                <div class="lbl">Total MA (Bersih)</div>
                <div class="val" style="color:#1d4ed8;">{{ $maData['total_bersih'] }}</div>
                <div class="sub">Internal: {{ $maData['items']['internal']['total'] }} | Reguler: {{ $maData['items']['gel_1']['total'] + $maData['items']['gel_2']['total'] }}</div>
            </div>

            <div class="exec-card mundur">
                <div class="lbl">Mengundurkan Diri</div>
                <div class="val" style="color:#b91c1c;">{{ $grandTotal['mengundurkan_diri'] }}</div>
                <div class="sub">MTs: {{ $mtsData['mengundurkan_diri']['total'] }} | MA: {{ $maData['mengundurkan_diri']['total'] }}</div>
            </div>

            <div class="exec-card primary" style="border-width:2px; border-color:#0d3b1e;">
                <div class="lbl">Grand Total Keseluruhan</div>
                <div class="val" style="color:#0d3b1e;">{{ $grandTotal['keseluruhan'] }}</div>
                <div class="sub">Putra: {{ $grandTotal['putra'] }} | Putri: {{ $grandTotal['putri'] }}</div>
            </div>
        </div>

        <!-- TABEL REKAPITULASI RESMI -->
        <table class="rekap-table">
            <thead>
                <tr>
                    <th style="text-align:left; width:160px;">Jenjang Madrasah</th>
                    <th style="text-align:left;">Gelombang / Jalur</th>
                    <th style="width:65px;">Putra</th>
                    <th style="width:65px;">Putri</th>
                    <th style="width:85px;">Gross</th>
                    <th style="width:75px; color:#b91c1c;">Mundur</th>
                    <th style="width:95px;">Total Bersih</th>
                    <th style="width:75px; text-align:right;">% Proporsi</th>
                </tr>
            </thead>
            <tbody>
                <!-- MTs Gel 1 -->
                <tr>
                    <td rowspan="3" style="font-weight:700; color:#0d3b1e; background:#f0fdf4; vertical-align:top;">
                        Madrasah Tsanawiyah (MTs)
                    </td>
                    <td>Gelombang 1</td>
                    <td style="text-align:center;">{{ $mtsData['items']['gel_1']['putra'] }}</td>
                    <td style="text-align:center;">{{ $mtsData['items']['gel_1']['putri'] }}</td>
                    <td style="text-align:center; font-weight:700;">{{ $mtsData['items']['gel_1']['total'] }}</td>
                    <td style="text-align:center; color:#64748b;">—</td>
                    <td style="text-align:center; font-weight:700; color:#15803d;">{{ $mtsData['items']['gel_1']['total'] }}</td>
                    <td style="text-align:right;">{{ $grandTotal['keseluruhan'] > 0 ? round(($mtsData['items']['gel_1']['total'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%</td>
                </tr>

                <!-- MTs Gel 2 -->
                <tr>
                    <td>Gelombang 2</td>
                    <td style="text-align:center;">{{ $mtsData['items']['gel_2']['putra'] }}</td>
                    <td style="text-align:center;">{{ $mtsData['items']['gel_2']['putri'] }}</td>
                    <td style="text-align:center; font-weight:700;">{{ $mtsData['items']['gel_2']['total'] }}</td>
                    <td style="text-align:center; color:#64748b;">—</td>
                    <td style="text-align:center; font-weight:700; color:#15803d;">{{ $mtsData['items']['gel_2']['total'] }}</td>
                    <td style="text-align:right;">{{ $grandTotal['keseluruhan'] > 0 ? round(($mtsData['items']['gel_2']['total'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%</td>
                </tr>

                <!-- MTs Subtotal -->
                <tr class="row-total" style="background:#f0fdf4;">
                    <td style="color:#b91c1c; font-style:italic;">Pengurangan: Mengundurkan Diri</td>
                    <td style="text-align:center; color:#b91c1c;">{{ $mtsData['mengundurkan_diri']['putra'] }}</td>
                    <td style="text-align:center; color:#b91c1c;">{{ $mtsData['mengundurkan_diri']['putri'] }}</td>
                    <td style="text-align:center;">{{ $mtsData['total_pendaftar_kotor'] }}</td>
                    <td style="text-align:center; font-weight:800; color:#b91c1c;">-{{ $mtsData['mengundurkan_diri']['total'] }}</td>
                    <td style="text-align:center; font-weight:800; color:#15803d; font-size:12px;">{{ $mtsData['total_bersih'] }}</td>
                    <td style="text-align:right; font-weight:700;">{{ $grandTotal['keseluruhan'] > 0 ? round(($mtsData['total_bersih'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%</td>
                </tr>

                <!-- MA Internal -->
                <tr>
                    <td rowspan="4" style="font-weight:700; color:#1d4ed8; background:#eff6ff; vertical-align:top;">
                        Madrasah Aliyah (MA)
                    </td>
                    <td>
                        Internal (Lanjutan MTs)
                        <span style="font-size:9.5px; color:#475569; display:block;">Putri: {{ $maData['items']['internal']['putri'] }}, Putra: {{ $maData['items']['internal']['putra'] }}</span>
                    </td>
                    <td style="text-align:center;">{{ $maData['items']['internal']['putra'] }}</td>
                    <td style="text-align:center;">{{ $maData['items']['internal']['putri'] }}</td>
                    <td style="text-align:center; font-weight:700;">{{ $maData['items']['internal']['total'] }}</td>
                    <td style="text-align:center; color:#64748b;">—</td>
                    <td style="text-align:center; font-weight:700; color:#1d4ed8;">{{ $maData['items']['internal']['total'] }}</td>
                    <td style="text-align:right;">{{ $grandTotal['keseluruhan'] > 0 ? round(($maData['items']['internal']['total'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%</td>
                </tr>

                <!-- MA Gel 1 -->
                <tr>
                    <td>Gelombang 1</td>
                    <td style="text-align:center;">{{ $maData['items']['gel_1']['putra'] }}</td>
                    <td style="text-align:center;">{{ $maData['items']['gel_1']['putri'] }}</td>
                    <td style="text-align:center; font-weight:700;">{{ $maData['items']['gel_1']['total'] }}</td>
                    <td style="text-align:center; color:#64748b;">—</td>
                    <td style="text-align:center; font-weight:700; color:#1d4ed8;">{{ $maData['items']['gel_1']['total'] }}</td>
                    <td style="text-align:right;">{{ $grandTotal['keseluruhan'] > 0 ? round(($maData['items']['gel_1']['total'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%</td>
                </tr>

                <!-- MA Gel 2 -->
                <tr>
                    <td>Gelombang 2</td>
                    <td style="text-align:center;">{{ $maData['items']['gel_2']['putra'] }}</td>
                    <td style="text-align:center;">{{ $maData['items']['gel_2']['putri'] }}</td>
                    <td style="text-align:center; font-weight:700;">{{ $maData['items']['gel_2']['total'] }}</td>
                    <td style="text-align:center; color:#64748b;">—</td>
                    <td style="text-align:center; font-weight:700; color:#1d4ed8;">{{ $maData['items']['gel_2']['total'] }}</td>
                    <td style="text-align:right;">{{ $grandTotal['keseluruhan'] > 0 ? round(($maData['items']['gel_2']['total'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%</td>
                </tr>

                <!-- MA Subtotal -->
                <tr class="row-total" style="background:#eff6ff;">
                    <td style="color:#b91c1c; font-style:italic;">Pengurangan: Mengundurkan Diri</td>
                    <td style="text-align:center; color:#b91c1c;">{{ $maData['mengundurkan_diri']['putra'] }}</td>
                    <td style="text-align:center; color:#b91c1c;">{{ $maData['mengundurkan_diri']['putri'] }}</td>
                    <td style="text-align:center;">{{ $maData['total_pendaftar_kotor'] }}</td>
                    <td style="text-align:center; font-weight:800; color:#b91c1c;">-{{ $maData['mengundurkan_diri']['total'] }}</td>
                    <td style="text-align:center; font-weight:800; color:#1d4ed8; font-size:12px;">{{ $maData['total_bersih'] }}</td>
                    <td style="text-align:right; font-weight:700;">{{ $grandTotal['keseluruhan'] > 0 ? round(($maData['total_bersih'] / $grandTotal['keseluruhan']) * 100, 1) : 0 }}%</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="row-grand">
                    <td colspan="2" style="text-align:left; padding-left:12px;">GRAND TOTAL KESELURUHAN</td>
                    <td style="text-align:center;">{{ $grandTotal['putra'] }}</td>
                    <td style="text-align:center;">{{ $grandTotal['putri'] }}</td>
                    <td style="text-align:center;">{{ $grandTotal['pendaftar_kotor'] }}</td>
                    <td style="text-align:center; color:#fca5a5;">-{{ $grandTotal['mengundurkan_diri'] }}</td>
                    <td style="text-align:center; font-size:13px; font-weight:900; color:#4ade80;">{{ $grandTotal['keseluruhan'] }}</td>
                    <td style="text-align:right;">100.0%</td>
                </tr>
            </tfoot>
        </table>

        <!-- BAGIAN TANDA TANGAN & PENGESAHAN DOKUMEN -->
        <div style="display:flex; justify-content:flex-end; margin-bottom:12px; font-size:11px;">
            <span>Magelang, {{ $tanggalFormatted }}</span>
        </div>

        <div class="sign-grid">
            <!-- Kolom Kiri: Pimpinan Pesantren -->
            <div class="sign-box">
                <div>
                    <div class="role-title">Mengetahui,</div>
                    <div style="font-size:10.5px; color:#475569;">Pimpinan Pondok Pesantren Hidayatullah</div>
                </div>

                <div class="sign-img-container">
                    @if(Setting::get('ttd_pimpinan_image'))
                        <img src="{{ Setting::get('ttd_pimpinan_image') }}" alt="TTD Pimpinan">
                    @endif
                </div>

                <div>
                    <div class="name">{{ Setting::get('nama_pimpinan', 'K.H. Pimpinan Pondok Pesantren') }}</div>
                    <div class="desc">Pimpinan Pondok Pesantren</div>
                </div>
            </div>

            <!-- Kolom Kanan: Ketua Panitia PSB -->
            <div class="sign-box">
                <div>
                    <div class="role-title">Panitia Penerimaan Santri Baru,</div>
                    <div style="font-size:10.5px; color:#475569;">Ketua Panitia PSB TA {{ $tahunAjaran }}</div>
                </div>

                <div class="sign-img-container">
                    @if(Setting::get('ttd_digital_stempel_image') && Setting::get('ttd_digital_show_stempel') == '1')
                        <img src="{{ Setting::get('ttd_digital_stempel_image') }}" alt="Stempel Resmi" class="sign-stamp">
                    @endif
                    @if(Setting::get('ttd_digital_pengurus_image'))
                        <img src="{{ Setting::get('ttd_digital_pengurus_image') }}" alt="TTD Ketua Panitia">
                    @endif
                </div>

                <div>
                    <div class="name">{{ Setting::get('ttd_digital_nama', 'Ust. Panitia PSB Pondok') }}</div>
                    <div class="desc">{{ Setting::get('ttd_digital_jabatan', 'Ketua Panitia PSB TA ' . $tahunAjaran) }}</div>
                </div>
            </div>
        </div>

        <!-- Footnote Dokumen -->
        <div style="margin-top:28px; padding-top:8px; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; font-size:9px; color:#94a3b8;">
            <span>Dokumen Sistem Resmi Informasi Manajemen PSB Pondok Pesantren Hidayatullah Tuksongo</span>
            <span>Dicetak secara otomatis pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
        </div>

    </div>

</body>
</html>

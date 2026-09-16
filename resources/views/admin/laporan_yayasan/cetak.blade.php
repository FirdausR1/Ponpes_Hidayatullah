<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Pimpinan Pondok Pesantren Periode {{ $labelBulan }} {{ $tahunStr }} — Pondok Pesantren Hidayatullah</title>
    <link rel="icon" href="/logo.png" type="image/png">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=EB+Garamond:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0d3b1e;
            --primary-light: #166534;
            --accent: #ca8a04;
            --slate-900: #0f172a;
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

        .no-print-bar {
            max-width: 1040px;
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
            padding: 10px 20px;
            background: #0d3b1e;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(13, 59, 30, 0.25);
            transition: all 0.2s;
        }

        .btn-print:hover {
            background: #166534;
        }

        .report-sheet {
            background: #ffffff;
            max-width: 1040px;
            margin: 0 auto;
            padding: 36px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        /* Kop Surat Resmi */
        .kop-wrapper {
            text-align: center;
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .kop-wrapper img {
            max-height: 95px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
        }

        /* Judul Dokumen */
        .doc-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .doc-header h1 {
            font-family: 'EB Garamond', Georgia, serif;
            font-size: 21px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #0d3b1e;
        }

        .doc-header h2 {
            font-size: 13.5px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 2px;
            letter-spacing: 0.02em;
        }

        .doc-header p {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Executive Summary Cards */
        .exec-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 24px;
        }

        .exec-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
            background: #f8fafc;
        }

        .exec-card.primary {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .exec-card.highlight {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .exec-card .lbl {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        .exec-card .val {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 4px;
            font-family: 'EB Garamond', Georgia, serif;
        }

        .exec-card .sub {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Section Title */
        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 24px;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1.5px solid #0d3b1e;
        }

        .section-title h3 {
            font-size: 13px;
            font-weight: 800;
            color: #0d3b1e;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .section-title .badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 9999px;
            background: #f1f5f9;
            color: #475569;
        }

        /* Tables */
        table.finance-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-bottom: 12px;
        }

        table.finance-table th, table.finance-table td {
            border: 1px solid #cbd5e1;
            padding: 5.5px 8px;
        }

        table.finance-table th {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9.5px;
            letter-spacing: 0.02em;
        }

        table.finance-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        table.finance-table td.text-center { text-align: center; }
        table.finance-table td.text-right { text-align: right; }
        table.finance-table th.text-center { text-align: center; }
        table.finance-table th.text-right { text-align: right; }

        .row-total {
            font-weight: 800;
            background: #e2e8f0 !important;
            color: #0f172a;
        }

        /* Badge Metode */
        .badge-tunai {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9.5px;
            font-weight: 700;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .badge-transfer {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9.5px;
            font-weight: 700;
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        /* Santri & Chart Container */
        .santri-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 16px;
            align-items: start;
        }

        .chart-box {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px;
            background: #ffffff;
            text-align: center;
        }

        .chart-box h4 {
            font-size: 11.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #334155;
            margin-bottom: 10px;
        }

        /* Signatures */
        .sign-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 36px;
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
            min-height: 140px;
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

        /* Print Media Styles */
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

            .page-break {
                page-break-before: always;
            }

            table.finance-table th {
                background: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .row-total {
                background: #e2e8f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .exec-card.primary {
                background: #f0fdf4 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .exec-card.highlight {
                background: #eff6ff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Navigasi Aksi (Hanya Tampil di Layar Monitor) -->
    <div class="no-print-bar">
        <a href="{{ route('admin.laporanYayasan.index', ['bulan' => $bulanStr, 'tahun' => $tahunStr]) }}" class="btn-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke Menu Laporan
        </a>

        <!-- Form Filter Ganti Periode Langsung -->
        <form method="GET" action="{{ route('admin.laporanYayasan.cetak') }}" style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:11px; font-weight:700; color:#334155; text-transform:uppercase; letter-spacing:0.04em;">Pilih Periode:</span>
            <select name="bulan" onchange="this.form.submit()" style="padding:6px 12px; border-radius:6px; border:1px solid #cbd5e1; font-size:12px; font-weight:700; background:#fff; cursor:pointer; color:#0f172a;">
                @foreach($namaBulanList as $num => $nama)
                    <option value="{{ $num }}" {{ $bulanStr === $num ? 'selected' : '' }}>{{ $num }} — {{ $nama }}</option>
                @endforeach
            </select>
            <select name="tahun" onchange="this.form.submit()" style="padding:6px 12px; border-radius:6px; border:1px solid #cbd5e1; font-size:12px; font-weight:700; background:#fff; cursor:pointer; color:#0f172a;">
                @for($y = 2024; $y <= 2028; $y++)
                    <option value="{{ $y }}" {{ (int)$tahunStr === $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>

        <div style="display:flex; align-items:center; gap:8px;">
            <button onclick="window.print()" class="btn-print">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Dokumen Resmi (PDF / Print)
            </button>
        </div>
    </div>

    <!-- Lembar Dokumen Resmi Laporan Keuangan -->
    <div class="report-sheet">

        <!-- KOP SURAT RESMI PONDOK PESANTREN HIDAYATULLAH TUKSONGO -->
        <div class="kop-wrapper">
            <img src="/images/kop_psb.png" alt="Kop Surat Resmi Pondok Pesantren Hidayatullah Tuksongo">
        </div>

        <!-- HEADER DOKUMEN -->
        <div class="doc-header">
            <h1>Laporan Keuangan Bulanan</h1>
            <h2>Pondok Pesantren Hidayatullah Tuksongo</h2>
            <p>Kepada Yth. <strong>Pimpinan Pondok Pesantren Hidayatullah</strong> • Periode Pelaporan: <strong>Bulan {{ $labelBulan }} {{ $tahunStr }}</strong></p>
        </div>

        <!-- RINGKASAN EKSEKUTIF (EXECUTIVE DASHBOARD) -->
        <div class="exec-grid">
            <div class="exec-card primary">
                <div class="lbl">Total Penerimaan Kas</div>
                <div class="val" style="color: #15803d;">Rp {{ number_format($grandTotalPenerimaan, 0, ',', '.') }}</div>
                <div class="sub">Tunai: Rp {{ number_format($totPenerimaanTunai, 0, ',', '.') }} | Bank: Rp {{ number_format($totPenerimaanTransfer, 0, ',', '.') }}</div>
            </div>

            <div class="exec-card">
                <div class="lbl">Total Beban Pengeluaran</div>
                <div class="val" style="color: #b91c1c;">Rp {{ number_format($grandTotalPengeluaran, 0, ',', '.') }}</div>
                <div class="sub">{{ count($pengeluaran) }} Transaksi Operasional &amp; PAT</div>
            </div>

            <div class="exec-card highlight">
                <div class="lbl">Surplus Operasional</div>
                <div class="val" style="color: #1d4ed8;">Rp {{ number_format($grandTotalPenerimaan - $grandTotalPengeluaran, 0, ',', '.') }}</div>
                <div class="sub">Surplus Bersih Kas Bulan Ini</div>
            </div>

            <div class="exec-card">
                <div class="lbl">Posisi Saldo Kas Akhir</div>
                <div class="val" style="color: #0d3b1e;">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</div>
                <div class="sub">Posisi per {{ $tglAkhir }} {{ $labelBulan }} {{ $tahunStr }}</div>
            </div>
        </div>

        <!-- 1. REKAP PENERIMAAN BULAN -->
        <div class="section-title">
            <h3>1. Rekap Penerimaan Bulan {{ $labelBulan }} {{ $tahunStr }}</h3>
            <span class="badge">{{ count($penerimaan) }} Transaksi</span>
        </div>

        <table class="finance-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 38px;">No</th>
                    <th class="text-center" style="width: 100px;">Tanggal</th>
                    <th>Keterangan</th>
                    <th class="text-center" style="width: 110px;">Metode</th>
                    <th class="text-right" style="width: 140px;">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penerimaan as $idx => $row)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $row['tanggal'] }}</td>
                    <td>{{ $row['keterangan'] }}</td>
                    <td class="text-center">
                        @if($row['metode'] === 'Tunai')
                            <span class="badge-tunai">Tunai</span>
                        @else
                            <span class="badge-transfer">Transfer Bank</span>
                        @endif
                    </td>
                    <td class="text-right" style="font-weight: 600;">Rp {{ number_format($row['jumlah'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="row-total">
                    <td colspan="4" class="text-right">TOTAL PENERIMAAN KAS TUNAI:</td>
                    <td class="text-right" style="color: #065f46;">Rp {{ number_format($totPenerimaanTunai, 0, ',', '.') }}</td>
                </tr>
                <tr class="row-total">
                    <td colspan="4" class="text-right">TOTAL PENERIMAAN KAS TRANSFER:</td>
                    <td class="text-right" style="color: #1e40af;">Rp {{ number_format($totPenerimaanTransfer, 0, ',', '.') }}</td>
                </tr>
                <tr class="row-total" style="background: #cbd5e1 !important; font-size: 11px;">
                    <td colspan="4" class="text-right">GRAND TOTAL PENERIMAAN BULAN {{ strtoupper($labelBulan) }} {{ $tahunStr }}:</td>
                    <td class="text-right" style="color: #0d3b1e; font-size: 12px;">Rp {{ number_format($grandTotalPenerimaan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- 2. REKAP PENGELUARAN BULAN -->
        <div class="section-title page-break">
            <h3>2. Rekap Pengeluaran Bulan {{ $labelBulan }} {{ $tahunStr }}</h3>
            <span class="badge">{{ count($pengeluaran) }} Butir Transaksi</span>
        </div>

        <table class="finance-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 38px;">No</th>
                    <th class="text-center" style="width: 100px;">Tanggal</th>
                    <th>Keterangan / Uraian Beban</th>
                    <th class="text-center" style="width: 110px;">Metode</th>
                    <th class="text-right" style="width: 140px;">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengeluaran as $idx => $row)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $row['tanggal'] }}</td>
                    <td>{{ $row['keterangan'] }}</td>
                    <td class="text-center">
                        @if($row['metode'] === 'Tunai')
                            <span class="badge-tunai">Tunai</span>
                        @else
                            <span class="badge-transfer">Transfer Bank</span>
                        @endif
                    </td>
                    <td class="text-right" style="font-weight: 600;">Rp {{ number_format($row['jumlah'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="row-total">
                    <td colspan="4" class="text-right">SUBTOTAL PENGELUARAN TUNAI:</td>
                    <td class="text-right">Rp {{ number_format($totPengeluaranTunai, 0, ',', '.') }}</td>
                </tr>
                <tr class="row-total">
                    <td colspan="4" class="text-right">SUBTOTAL PENGELUARAN TRANSFER:</td>
                    <td class="text-right">Rp {{ number_format($totPengeluaranTransfer, 0, ',', '.') }}</td>
                </tr>
                <tr class="row-total" style="background: #cbd5e1 !important; font-size: 11px;">
                    <td colspan="4" class="text-right">TOTAL SELURUH PENGELUARAN BULAN {{ strtoupper($labelBulan) }} {{ $tahunStr }}:</td>
                    <td class="text-right" style="color: #b91c1c; font-size: 12px;">Rp {{ number_format($grandTotalPengeluaran, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- 3. LAPORAN ARUS KAS HARIAN -->
        <div class="section-title page-break">
            <h3>3. Laporan Buku Kas Harian Bulan {{ $labelBulan }} {{ $tahunStr }}</h3>
            <span class="badge">Saldo Awal: Rp {{ number_format($saldoAwal, 0, ',', '.') }}</span>
        </div>

        <table class="finance-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 38px;">No</th>
                    <th class="text-center" style="width: 100px;">Tanggal</th>
                    <th class="text-right">Penerimaan</th>
                    <th class="text-right">Pengeluaran</th>
                    <th class="text-right">Saldo Harian</th>
                    <th class="text-right" style="width: 150px;">Jumlah Saldo Kumulatif</th>
                </tr>
            </thead>
            <tbody>
                @foreach($harian as $idx => $h)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $h['tgl'] }}</td>
                    <td class="text-right" style="{{ $h['masuk'] > 0 ? 'font-weight:600; color:#15803d;' : 'color:#94a3b8;' }}">
                        {{ $h['masuk'] > 0 ? 'Rp ' . number_format($h['masuk'], 0, ',', '.') : 'Rp 0' }}
                    </td>
                    <td class="text-right" style="{{ $h['keluar'] > 0 ? 'font-weight:600; color:#b91c1c;' : 'color:#94a3b8;' }}">
                        {{ $h['keluar'] > 0 ? 'Rp ' . number_format($h['keluar'], 0, ',', '.') : 'Rp 0' }}
                    </td>
                    <td class="text-right" style="{{ $h['saldo_harian'] < 0 ? 'color:#b91c1c; font-weight:600;' : ($h['saldo_harian'] > 0 ? 'color:#15803d; font-weight:600;' : 'color:#94a3b8;') }}">
                        {{ $h['saldo_harian'] < 0 ? '-Rp ' . number_format(abs($h['saldo_harian']), 0, ',', '.') : 'Rp ' . number_format($h['saldo_harian'], 0, ',', '.') }}
                    </td>
                    <td class="text-right" style="font-weight: 700; color: #0f172a; background: {{ $idx % 2 == 0 ? '#f8fafc' : '#ffffff' }};">
                        Rp {{ number_format($h['saldo'], 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
                <tr class="row-total" style="background: #e2e8f0 !important;">
                    <td colspan="2" class="text-right">TOTAL BULAN {{ strtoupper($labelBulan) }}:</td>
                    <td class="text-right" style="color:#15803d;">Rp {{ number_format(array_sum(array_column($harian, 'masuk')), 0, ',', '.') }}</td>
                    <td class="text-right" style="color:#b91c1c;">Rp {{ number_format(array_sum(array_column($harian, 'keluar')), 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format(array_sum(array_column($harian, 'saldo_harian')), 0, ',', '.') }}</td>
                    <td class="text-right" style="font-size: 11.5px; color:#0d3b1e;">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- 4. REKAP PINJAMAN UANG SOT & SALDO AKHIR -->
        <div class="section-title">
            <h3>4. Rekap Pinjaman Uang SOT &amp; Posisi Kas Akhir</h3>
            <span class="badge">Nihil Pinjaman</span>
        </div>

        <table class="finance-table" style="margin-bottom: 8px;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 38px;">No</th>
                    <th class="text-center" style="width: 100px;">Tanggal</th>
                    <th>Keterangan Pinjaman</th>
                    <th class="text-right" style="width: 130px;">Uang Makan</th>
                    <th class="text-right" style="width: 130px;">Syahriyah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td>Tidak ada pinjaman uang SOT (Nihil)</td>
                    <td class="text-right">Rp 0</td>
                    <td class="text-right">Rp 0</td>
                </tr>
            </tbody>
        </table>

        <table class="finance-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 38px;">No</th>
                    <th class="text-center" style="width: 100px;">Tanggal Acuan</th>
                    <th>Uraian Saldo Kas</th>
                    <th class="text-right" style="width: 180px;">Nominal Saldo</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background: #f0fdf4;">
                    <td class="text-center" style="font-weight: 700;">1</td>
                    <td class="text-center">{{ $tglAkhir }} {{ $labelBulan }} {{ $tahunStr }}</td>
                    <td style="font-weight: 700; color: #0d3b1e;">Saldo Akhir Kas Operasional Bulan {{ $labelBulan }} {{ $tahunStr }}</td>
                    <td class="text-right" style="font-size: 13px; font-weight: 800; color: #15803d; font-family: 'EB Garamond', Georgia, serif;">
                        Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- 5. REKAP PEMBAYARAN SANTRI & DIAGRAM PIE LUNAS -->
        <div class="section-title page-break">
            <h3>5. Rekapitulasi Pembayaran Santri Per Kelas</h3>
            <span class="badge">{{ $totalSantriLunas }} dari {{ $grandTotalSantri }} Santri Lunas (55%)</span>
        </div>

        <div class="santri-grid">
            <!-- Tabel Kelunasan -->
            <div>
                <table class="finance-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 70px;">Kelas</th>
                            <th class="text-center" style="width: 90px; color: #15803d;">Lunas</th>
                            <th class="text-center" style="width: 100px; color: #b91c1c;">Belum Lunas</th>
                            <th class="text-center" style="width: 90px;">Jumlah</th>
                            <th class="text-center" style="width: 80px;">% Lunas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($santriStats as $s)
                        <tr>
                            <td class="text-center" style="font-weight: 700;">Kelas {{ $s['kelas'] }}</td>
                            <td class="text-center" style="font-weight: 700; color: #15803d;">{{ $s['lunas'] }}</td>
                            <td class="text-center" style="color: #b91c1c;">{{ $s['belum_lunas'] }}</td>
                            <td class="text-center" style="font-weight: 600;">{{ $s['total'] }}</td>
                            <td class="text-center">
                                <span style="font-weight: 700; color: #0f172a;">{{ $s['persen_lunas'] }}%</span>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="row-total" style="background: #cbd5e1 !important; font-size: 11px;">
                            <td class="text-center">JUMLAH</td>
                            <td class="text-center" style="color: #15803d; font-size: 12px;">{{ $totalSantriLunas }}</td>
                            <td class="text-center" style="color: #b91c1c; font-size: 12px;">{{ $totalSantriBelum }}</td>
                            <td class="text-center" style="font-size: 12px;">{{ $grandTotalSantri }}</td>
                            <td class="text-center">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Visual Diagram Pie 3D (Seperti Gambar Lampiran User) -->
            <div class="chart-box">
                <h4>JUMLAH SANTRI YANG SUDAH LUNAS</h4>
                <div style="display: flex; justify-content: center; margin-bottom: 8px;">
                    <!-- SVG Pie Chart Berwarna Sesuai Gambar Lampiran -->
                    <svg viewBox="0 0 200 135" width="230" height="155">
                        <defs>
                            <!-- Filter Bayangan 3D -->
                            <filter id="shadow3d" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="0" dy="7" stdDeviation="4" flood-opacity="0.25"/>
                            </filter>
                        </defs>
                        <!-- Efek Tebal 3D Dasar -->
                        <g transform="translate(100, 72) scale(1, 0.58)">
                            <!-- Sisi 3D bawah -->
                            <path d="M 0 0 L 70 0 A 70 70 0 1 1 -70 0 Z" fill="#475569" opacity="0.3" transform="translate(0, 12)"/>
                            <!-- Slice 1 (Kelas 1: 28% - Biru #3b82f6) -->
                            <path d="M 0 0 L 70 0 A 70 70 0 0 1 12 69 Z" fill="#2563eb" filter="url(#shadow3d)"/>
                            <!-- Slice 2 (Kelas 2: 22% - Oranye #f97316) -->
                            <path d="M 0 0 L 12 69 A 70 70 0 0 1 -63 31 Z" fill="#ea580c"/>
                            <!-- Slice 3 (Kelas 3: 20% - Abu-abu #94a3b8) -->
                            <path d="M 0 0 L -63 31 A 70 70 0 0 1 -67 -20 Z" fill="#64748b"/>
                            <!-- Slice 4 (Kelas 4: 14% - Kuning #eab308) -->
                            <path d="M 0 0 L -67 -20 A 70 70 0 0 1 -28 -64 Z" fill="#eab308"/>
                            <!-- Slice 5 (Kelas 5: 9% - Biru Muda #38bdf8) -->
                            <path d="M 0 0 L -28 -64 A 70 70 0 0 1 17 -68 Z" fill="#38bdf8"/>
                            <!-- Slice 6 (Kelas 6: 7% - Hijau #22c55e) -->
                            <path d="M 0 0 L 17 -68 A 70 70 0 0 1 70 0 Z" fill="#16a34a"/>
                        </g>
                        <!-- Label Persentase -->
                        <text x="145" y="48" font-size="10" font-weight="700" fill="#1d4ed8" text-anchor="middle">1 (28%)</text>
                        <text x="135" y="112" font-size="10" font-weight="700" fill="#c2410c" text-anchor="middle">2 (22%)</text>
                        <text x="40" y="118" font-size="10" font-weight="700" fill="#475569" text-anchor="middle">3 (20%)</text>
                        <text x="18" y="66" font-size="10" font-weight="700" fill="#a16207" text-anchor="middle">4 (14%)</text>
                        <text x="50" y="22" font-size="10" font-weight="700" fill="#0284c7" text-anchor="middle">5 (9%)</text>
                        <text x="96" y="14" font-size="10" font-weight="700" fill="#15803d" text-anchor="middle">6 (7%)</text>
                    </svg>
                </div>
                <div style="font-size: 9.5px; color: #64748b; line-height: 1.3;">
                    Diagram persentase distribusi santri yang telah lunas administrasi per kelas (Basis: 450 santri lunas).
                </div>
            </div>
        </div>

        <!-- 6. LAPORAN PENERIMAAN MELALUI TRANSFER BANK -->
        <div class="section-title">
            <h3>6. Laporan Penerimaan Pembayaran Melalui Transfer Bank</h3>
            <span class="badge">Rekonsiliasi Rekening Pesantren</span>
        </div>

        <table class="finance-table">
            <thead>
                <tr>
                    <th class="text-right" style="width: 33.33%;">Total Uang Masuk Bank</th>
                    <th class="text-right" style="width: 33.33%;">Sudah Ditarik ke Kas</th>
                    <th class="text-right" style="width: 33.33%; background: #e0f2fe; color: #0369a1;">Sisa Saldo Belum Ditarik</th>
                </tr>
            </thead>
            <tbody>
                <tr style="font-size: 13px; font-weight: 800; font-family: 'EB Garamond', Georgia, serif;">
                    <td class="text-right" style="color: #0f172a;">Rp {{ number_format($bankStats['uang_masuk'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #475569;">Rp {{ number_format($bankStats['sudah_ditarik'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #0369a1; background: #f0f9ff;">Rp {{ number_format($bankStats['belum_ditarik'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- LEMBAR PENGESAHAN DOKUMEN -->
        <div class="sign-grid">
            <div class="sign-box">
                <p class="role-title">Dibuat Oleh:</p>
                <div style="height: 60px;"></div>
                <div>
                    <p class="name">{{ $pejabat['pembuat_nama'] }}</p>
                    <p class="desc">{{ $pejabat['pembuat_jabatan'] }}</p>
                </div>
            </div>

            <div class="sign-box">
                <p class="role-title">Disetujui Oleh:</p>
                <div style="height: 60px;"></div>
                <div>
                    <p class="name">{{ $pejabat['pimpinan_nama'] }}</p>
                    <p class="desc">{{ $pejabat['pimpinan_jabatan'] }}</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Ujian - {{ $exam->mata_pelajaran }}</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #e2e8f0;
            color: #000;
            font-size: 11px;
            line-height: 1.35;
        }

        /* Non-print Floating Bar */
        .print-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #1e293b;
            color: white;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 9999;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.2);
        }

        .btn-print {
            background: #10b981;
            color: white;
            font-weight: bold;
            padding: 7px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-print:hover {
            background: #059669;
        }

        .btn-close {
            background: #475569;
            color: white;
            padding: 7px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
        }

        /* A4 Page Container */
        .page-container {
            width: 210mm;
            min-height: 297mm;
            margin: 60px auto 40px;
            background: #fff;
            padding: 15mm 15mm 15mm 15mm;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            position: relative;
        }

        /* KOP SURAT */
        .kop-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 12px;
        }

        .kop-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .kop-logo {
            width: 82px;
            height: 82px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-title h1 {
            font-size: 19px;
            font-weight: 900;
            color: #06170d;
            letter-spacing: 0.5px;
            line-height: 1.15;
            margin-bottom: 2px;
        }

        .kop-title p.motto {
            font-family: 'Times New Roman', Times, serif;
            font-size: 13px;
            font-style: italic;
            color: #166534;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .kop-bar {
            height: 6px;
            background: #15803d;
            border-radius: 2px;
            width: 100%;
            max-width: 260px;
        }

        .kop-right {
            background: #064e3b;
            color: #ffffff;
            padding: 10px 14px;
            border-radius: 2px;
            text-align: left;
            font-size: 9.5px;
            line-height: 1.45;
            min-width: 280px;
            max-width: 320px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .kop-right .npsn-nsm {
            font-weight: bold;
            font-size: 10.5px;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .kop-right .kontak {
            font-size: 9px;
            color: #e2e8f0;
            margin-bottom: 3px;
        }

        .kop-right .alamat {
            font-family: 'Times New Roman', Times, serif;
            font-style: italic;
            font-size: 9.5px;
            color: #f8fafc;
        }

        .kop-separator {
            border: none;
            border-top: 3px solid #000;
            margin: 6px 0 16px;
        }

        /* SECTION HEADINGS */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #000;
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }

        /* TABLE DETAIL UJIAN */
        .table-detail {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-detail td {
            border: 1px solid #000;
            padding: 3.5px 8px;
            font-size: 11px;
        }

        .table-detail td.td-label {
            width: 180px;
            font-weight: normal;
            background: #fff;
        }

        .table-detail td.td-val {
            font-weight: bold;
            background: #fff;
        }

        /* TABLE HASIL UJIAN */
        .table-hasil {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .table-hasil th {
            border: 1px solid #000;
            background: #fff;
            padding: 5px 6px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
        }

        .table-hasil td {
            border: 1px solid #000;
            padding: 3px 6px;
            font-size: 10.5px;
            vertical-align: middle;
        }

        .table-hasil td.col-no {
            width: 38px;
            text-align: center;
        }

        .table-hasil td.col-nama {
            text-align: left;
            font-weight: 500;
            text-transform: uppercase;
        }

        .table-hasil td.col-benar {
            width: 105px;
            text-align: center;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }

        .table-hasil td.col-nilai {
            width: 85px;
            text-align: center;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }

        .table-hasil td.col-kelas {
            width: 65px;
            text-align: center;
            font-weight: 500;
        }

        .table-hasil td.col-jurusan {
            width: 105px;
            text-align: center;
            font-weight: 500;
            text-transform: uppercase;
        }

        /* FOOTER CETAK */
        .print-footer {
            margin-top: 14px;
            font-size: 9px;
            color: #555;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* PRINT STYLES */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #fff;
                color: #000;
                font-size: 10px;
                padding: 0;
                margin: 0;
            }

            .page-container {
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                min-height: auto;
            }

            .kop-right {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .table-hasil th, .table-detail td, .table-hasil td {
                border-color: #000 !important;
            }

            @page {
                size: A4 portrait;
                margin: 12mm 15mm 12mm 15mm;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Print Controls (Hidden on Print) -->
    <div class="print-toolbar no-print">
        <div style="display:flex; align-items:center; gap:10px;">
            <img src="/logo.png" style="height:26px; width:auto;" alt="Logo">
            <span style="font-weight:bold; font-size:13px;">Format Cetak Resmi Laporan Hasil Ujian Madrasah</span>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <button type="button" onclick="window.print()" class="btn-print">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Cetak / Simpan PDF
            </button>
            <a href="{{ route('admin.cbt.madrasah.show', $exam->id) }}" class="btn-close">
                Tutup
            </a>
        </div>
    </div>

    <!-- Paper Container -->
    <div class="page-container">

        <!-- KOP SURAT (SESUAI DOKUMEN RESMI FOTO KLIEN) -->
        <div class="kop-wrapper">
            <div class="kop-left">
                <img src="/logo.png" class="kop-logo" alt="Logo Madrasah">
                <div class="kop-title">
                    <h1>{{ $namaMadrasah }}</h1>
                    <p class="motto">Unggul Dalam Keislaman, Keilmuan, Dan Kemasyarakatan</p>
                    <div class="kop-bar"></div>
                </div>
            </div>

            <div class="kop-right">
                <div class="npsn-nsm">NPSN : {{ $npsn }} NSM : {{ $nsm }}</div>
                <div class="kontak">Email: {{ $email }} Telp : {{ $telepon }}</div>
                <div class="alamat">{{ $alamat }}</div>
            </div>
        </div>

        <hr class="kop-separator">

        <!-- SECTION: DETAIL UJIAN -->
        <div class="section-title">Detail Ujian</div>
        <table class="table-detail">
            <tr>
                <td class="td-label">Mata Pelajaran</td>
                <td class="td-val">{{ $exam->mata_pelajaran }}</td>
            </tr>
            <tr>
                <td class="td-label">Nama Guru</td>
                <td class="td-val">{{ $exam->nama_guru ?: '-' }}</td>
            </tr>
            <tr>
                <td class="td-label">Nama Ujian</td>
                <td class="td-val">{{ $exam->nama_ujian }}</td>
            </tr>
            <tr>
                <td class="td-label">Jumlah Soal</td>
                <td class="td-val">{{ $exam->jumlah_soal }}</td>
            </tr>
            <tr>
                <td class="td-label">Waktu</td>
                <td class="td-val">{{ $exam->durasi_menit }} menit</td>
            </tr>
            <tr>
                <td class="td-label">Tertinggi</td>
                <td class="td-val">{{ number_format($statTertinggi, 2) }}</td>
            </tr>
            <tr>
                <td class="td-label">Terendah</td>
                <td class="td-val">{{ number_format($statTerendah, 2) }}</td>
            </tr>
            <tr>
                <td class="td-label">Rata-rata</td>
                <td class="td-val">{{ round($statRataRata) }}</td>
            </tr>
        </table>

        <!-- SECTION: HASIL UJIAN -->
        <div class="section-title">Hasil Ujian</div>
        <table class="table-hasil">
            <thead>
                <tr>
                    <th style="width: 38px;">No</th>
                    <th>Nama Peserta</th>
                    <th style="width: 105px;">Jumlah Benar</th>
                    <th style="width: 85px;">Nilai</th>
                    <th style="width: 65px;">Kelas</th>
                    <th style="width: 105px;">Jurusan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $index => $row)
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td class="col-nama">{{ $row->nama_peserta }}</td>
                    <td class="col-benar">{{ $row->jumlah_benar }}</td>
                    <td class="col-nilai">{{ number_format($row->nilai, 2) }}</td>
                    <td class="col-kelas">{{ $row->kelas }}</td>
                    <td class="col-jurusan">{{ $row->jurusan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 18px; color: #888;">
                        Tidak ada data hasil peserta ujian.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- FOOTER HALAMAN -->
        <div class="print-footer">
            <span style="font-family: monospace;">{{ url()->current() }}</span>
            <span>Laporan Hasil Ujian &bull; {{ $namaMadrasah }}</span>
        </div>

    </div>

</body>
</html>

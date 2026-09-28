<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian PSB Tahun Ajaran {{ $tahunAjaran }} — Per Tanggal {{ $tanggalFormatted }}</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
            background: #e5e7eb;
            padding: 20px 10px;
            font-size: 12px;
            line-height: 1.5;
        }

        /* Bar Aksi Atas (Hanya Tampil di Layar Monitor) */
        .no-print-bar {
            max-width: 900px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-kembali {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: #ffffff;
            color: #374151;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-kembali:hover {
            background: #f9fafb;
        }

        .btn-cetak {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            background: #15803d;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-cetak:hover {
            background: #166534;
        }

        .filter-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 12px;
        }

        .filter-box select, .filter-box input {
            font-size: 12px;
            padding: 3px 6px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }

        /* Lembar Cetak Dokumen Kertas */
        .lembar-cetak {
            background: #ffffff;
            max-width: 900px;
            margin: 0 auto;
            padding: 35px 45px;
            border-radius: 4px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Kop Surat Resmi */
        .kop-surat {
            text-align: center;
            margin-bottom: 2px;
        }

        .kop-surat img {
            max-height: 95px;
            width: auto;
            max-width: 100%;
        }

        .garis-kop {
            border-top: 3px double #111827;
            margin-top: 6px;
            margin-bottom: 18px;
        }

        /* Judul Laporan */
        .judul-laporan {
            text-align: center;
            margin-bottom: 18px;
        }

        .judul-laporan h1 {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #111827;
        }

        .judul-laporan h2 {
            font-size: 13px;
            font-weight: bold;
            color: #1f2937;
            margin-top: 3px;
        }

        .judul-laporan p {
            font-size: 11.5px;
            color: #4b5563;
            margin-top: 3px;
        }

        /* Kotak Teks Laporan Sesuai Format Permintaan */
        .kotak-laporan {
            border: 1px solid #9ca3af;
            border-radius: 6px;
            background: #fafafa;
            padding: 14px 18px;
            margin-bottom: 20px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 11.5px;
            line-height: 1.55;
            color: #111827;
        }

        .kotak-laporan .judul {
            font-weight: bold;
            margin-bottom: 6px;
        }

        .kotak-laporan .subjudul {
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 2px;
        }

        .kotak-laporan .baris {
            padding-left: 14px;
        }

        .kotak-laporan .total {
            padding-left: 14px;
            font-weight: bold;
            margin-top: 2px;
            margin-bottom: 6px;
        }

        .kotak-laporan .grand-total {
            border-top: 1px solid #111827;
            padding-top: 6px;
            margin-top: 8px;
            font-weight: bold;
        }

        /* Tabel Rekapitulasi Resmi */
        table.tabel-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
            font-size: 11px;
        }

        table.tabel-data th, table.tabel-data td {
            border: 1px solid #4b5563;
            padding: 5px 8px;
        }

        table.tabel-data th {
            background: #f3f4f6;
            color: #111827;
            font-weight: bold;
            text-align: center;
        }

        table.tabel-data tr.subtotal {
            background: #f9fafb;
            font-weight: bold;
        }

        table.tabel-data tr.grand-total {
            background: #f3f4f6;
            font-weight: bold;
            font-size: 11.5px;
        }

        /* Kolom Tanda Tangan */
        .area-ttd {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .tanggal-surat {
            text-align: right;
            margin-bottom: 12px;
            font-size: 11.5px;
        }

        .kolom-ttd {
            display: flex;
            justify-content: space-between;
            text-align: center;
            max-width: 650px;
            margin: 0 auto;
        }

        .kotak-ttd {
            width: 250px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 110px;
        }

        .kotak-ttd .jabatan {
            font-weight: bold;
            font-size: 11.5px;
        }

        .kotak-ttd .nama {
            font-weight: bold;
            text-decoration: underline;
            font-size: 11.5px;
        }

        /* Pengaturan Cetak / Print */
        @page {
            size: A4 portrait;
            margin: 15mm 15mm;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }

            .no-print-bar {
                display: none !important;
            }

            .lembar-cetak {
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }

            .kotak-laporan {
                background: #fafafa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table.tabel-data th, table.tabel-data tr.grand-total {
                background: #f3f4f6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Navigasi (Hanya di Layar) -->
    <div class="no-print-bar">
        <a href="{{ route('admin.psb.laporan', request()->all()) }}" class="btn-kembali">
            &larr; Kembali ke Laporan
        </a>

        <form method="GET" action="{{ route('admin.psb.laporan.cetak') }}" class="filter-box">
            <input type="hidden" name="mode_data" value="{{ $modeData }}">
            <span>Tanggal:</span>
            <input type="date" name="per_tanggal" value="{{ $perTanggal }}" onchange="this.form.submit()">
            
            <span>Tahun Ajaran:</span>
            <input type="text" name="tahun_ajaran" value="{{ $tahunAjaran }}" style="width:75px;" onchange="this.form.submit()">

            <select name="mode_data" onchange="this.form.submit()">
                <option value="contoh" {{ $modeData === 'contoh' ? 'selected' : '' }}>Contoh (256)</option>
                <option value="real" {{ $modeData === 'real' ? 'selected' : '' }}>Data Database ({{ $realCount }})</option>
            </select>
        </form>

        <button onclick="window.print()" class="btn-cetak">
            Cetak Dokumen (Print / PDF)
        </button>
    </div>

    <!-- Lembar Dokumen Fisik -->
    <div class="lembar-cetak">

        <!-- KOP SURAT RESMI PONDOK PESANTREN HIDAYATULLAH TUKSONGO -->
        <div class="kop-surat">
            <img src="/images/kop_psb.png" alt="Kop Surat Resmi Pondok Pesantren Hidayatullah Tuksongo">
        </div>
        <div class="garis-kop"></div>

        <!-- JUDUL LAPORAN -->
        <div class="judul-laporan">
            <h1>Laporan Harian Penerimaan Santri Baru (PSB)</h1>
            <h2>Tahun Ajaran {{ $tahunAjaran }}</h2>
            <p>Posisi Per Tanggal: <strong>{{ $tanggalLengkapFormatted }}</strong></p>
        </div>

        <!-- KOTAK FORMAT LAPORAN HARIAN (PERSIS SESUAI CONTOH PERMINTAAN) -->
        <div class="kotak-laporan">
            <div class="judul">"Actual daily report PSB Tahun Ajaran {{ $tahunAjaran }}"</div>
            <div>* Per tanggal: {{ $tanggalFormatted }}</div>

            <!-- MTs -->
            <div class="subjudul">* Jumlah pendaftar MTs</div>
            <div class="baris">° Gel. 1 = {{ $mtsData['items']['gel_1']['total'] }} anak</div>
            <div class="baris">° Gel. 2 = {{ $mtsData['items']['gel_2']['total'] }} anak</div>
            @if(($mtsData['items']['internal']['total'] ?? 0) > 0)
                <div class="baris">° Internal = {{ $mtsData['items']['internal']['total'] }} anak</div>
            @endif
            <div class="baris">° Mengundurkan diri = {{ $mtsData['mengundurkan_diri']['total'] }} anak</div>
            <div class="total">Total MTs = {{ $mtsData['total_bersih'] }} anak</div>

            <!-- MA -->
            <div class="subjudul">• Jumlah pendaftar MA</div>
            <div class="baris">
                ° Internal = {{ $maData['items']['internal']['total'] }} anak 
                @if(!empty($maData['items']['internal']['catatan']))
                    <span>(putri : {{ $maData['items']['internal']['putri'] }} anak, putra : {{ $maData['items']['internal']['putra'] }} anak)</span>
                @endif
            </div>
            <div class="baris">° Gel. 1 = {{ $maData['items']['gel_1']['total'] }} anak</div>
            <div class="baris">° Gel. 2 = {{ $maData['items']['gel_2']['total'] }} anak</div>
            <div class="baris">° Mengundurkan diri = {{ $maData['mengundurkan_diri']['total'] }} anak</div>
            <div class="total">Total MA = {{ $maData['total_bersih'] }} anak</div>

            <!-- Grand Total -->
            <div class="grand-total">
                <div>~ Grand Total Pendaftar = {{ $grandTotal['pendaftar'] }} anak</div>
                <div>~ Grand Total Mengundurkan diri = {{ $grandTotal['mengundurkan_diri'] }} anak</div>
                <div style="font-size:12px; margin-top:2px;">~ Grand Total Keseluruhan = {{ $grandTotal['keseluruhan'] }} anak</div>
            </div>
        </div>

        <!-- TABEL RINCIAN JUMLAH PENDAFTAR -->
        <table class="tabel-data">
            <thead>
                <tr>
                    <th style="text-align:left; width:130px;">Jenjang</th>
                    <th style="text-align:left;">Gelombang / Jalur</th>
                    <th style="width:70px;">Putra</th>
                    <th style="width:70px;">Putri</th>
                    <th style="width:90px;">Jumlah Daftar</th>
                    <th style="width:80px;">Mundur</th>
                    <th style="width:100px;">Total Diterima</th>
                </tr>
            </thead>
            <tbody>
                <!-- MTs Gel 1 -->
                <tr>
                    <td rowspan="3" style="font-weight:bold; vertical-align:top;">MTs</td>
                    <td>Gelombang 1</td>
                    <td style="text-align:center;">{{ $mtsData['items']['gel_1']['putra'] }}</td>
                    <td style="text-align:center;">{{ $mtsData['items']['gel_1']['putri'] }}</td>
                    <td style="text-align:center;">{{ $mtsData['items']['gel_1']['total'] }}</td>
                    <td style="text-align:center;">—</td>
                    <td style="text-align:center; font-weight:bold;">{{ $mtsData['items']['gel_1']['total'] }}</td>
                </tr>
                <!-- MTs Gel 2 -->
                <tr>
                    <td>Gelombang 2</td>
                    <td style="text-align:center;">{{ $mtsData['items']['gel_2']['putra'] }}</td>
                    <td style="text-align:center;">{{ $mtsData['items']['gel_2']['putri'] }}</td>
                    <td style="text-align:center;">{{ $mtsData['items']['gel_2']['total'] }}</td>
                    <td style="text-align:center;">—</td>
                    <td style="text-align:center; font-weight:bold;">{{ $mtsData['items']['gel_2']['total'] }}</td>
                </tr>
                <!-- MTs Subtotal -->
                <tr class="subtotal">
                    <td>Subtotal MTs (dikurangi mundur)</td>
                    <td style="text-align:center;">{{ $mtsData['items']['gel_1']['putra'] + $mtsData['items']['gel_2']['putra'] }}</td>
                    <td style="text-align:center;">{{ $mtsData['items']['gel_1']['putri'] + $mtsData['items']['gel_2']['putri'] }}</td>
                    <td style="text-align:center;">{{ $mtsData['total_pendaftar_kotor'] }}</td>
                    <td style="text-align:center; color:#b91c1c;">-{{ $mtsData['mengundurkan_diri']['total'] }}</td>
                    <td style="text-align:center; font-weight:bold;">{{ $mtsData['total_bersih'] }} anak</td>
                </tr>

                <!-- MA Internal -->
                <tr>
                    <td rowspan="4" style="font-weight:bold; vertical-align:top;">MA</td>
                    <td>
                        Internal (Alumni MTs)
                        <div style="font-size:10px; color:#4b5563;">Putri: {{ $maData['items']['internal']['putri'] }}, Putra: {{ $maData['items']['internal']['putra'] }}</div>
                    </td>
                    <td style="text-align:center;">{{ $maData['items']['internal']['putra'] }}</td>
                    <td style="text-align:center;">{{ $maData['items']['internal']['putri'] }}</td>
                    <td style="text-align:center;">{{ $maData['items']['internal']['total'] }}</td>
                    <td style="text-align:center;">—</td>
                    <td style="text-align:center; font-weight:bold;">{{ $maData['items']['internal']['total'] }}</td>
                </tr>
                <!-- MA Gel 1 -->
                <tr>
                    <td>Gelombang 1</td>
                    <td style="text-align:center;">{{ $maData['items']['gel_1']['putra'] }}</td>
                    <td style="text-align:center;">{{ $maData['items']['gel_1']['putri'] }}</td>
                    <td style="text-align:center;">{{ $maData['items']['gel_1']['total'] }}</td>
                    <td style="text-align:center;">—</td>
                    <td style="text-align:center; font-weight:bold;">{{ $maData['items']['gel_1']['total'] }}</td>
                </tr>
                <!-- MA Gel 2 -->
                <tr>
                    <td>Gelombang 2</td>
                    <td style="text-align:center;">{{ $maData['items']['gel_2']['putra'] }}</td>
                    <td style="text-align:center;">{{ $maData['items']['gel_2']['putri'] }}</td>
                    <td style="text-align:center;">{{ $maData['items']['gel_2']['total'] }}</td>
                    <td style="text-align:center;">—</td>
                    <td style="text-align:center; font-weight:bold;">{{ $maData['items']['gel_2']['total'] }}</td>
                </tr>
                <!-- MA Subtotal -->
                <tr class="subtotal">
                    <td>Subtotal MA (dikurangi mundur)</td>
                    <td style="text-align:center;">{{ $maData['items']['internal']['putra'] + $maData['items']['gel_1']['putra'] + $maData['items']['gel_2']['putra'] }}</td>
                    <td style="text-align:center;">{{ $maData['items']['internal']['putri'] + $maData['items']['gel_1']['putri'] + $maData['items']['gel_2']['putri'] }}</td>
                    <td style="text-align:center;">{{ $maData['total_pendaftar_kotor'] }}</td>
                    <td style="text-align:center; color:#b91c1c;">-{{ $maData['mengundurkan_diri']['total'] }}</td>
                    <td style="text-align:center; font-weight:bold;">{{ $maData['total_bersih'] }} anak</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="grand-total">
                    <td colspan="2">GRAND TOTAL KESELURUHAN</td>
                    <td style="text-align:center;">{{ $grandTotal['putra'] }}</td>
                    <td style="text-align:center;">{{ $grandTotal['putri'] }}</td>
                    <td style="text-align:center;">{{ $grandTotal['pendaftar_kotor'] }}</td>
                    <td style="text-align:center; color:#b91c1c;">-{{ $grandTotal['mengundurkan_diri'] }}</td>
                    <td style="text-align:center; font-weight:bold;">{{ $grandTotal['keseluruhan'] }} anak</td>
                </tr>
            </tfoot>
        </table>

        <!-- KOLOM TANDA TANGAN RESMI -->
        <div class="area-ttd">
            <div class="tanggal-surat">
                Magelang, {{ $tanggalFormatted }}
            </div>

            <div class="kolom-ttd">
                <div class="kotak-ttd">
                    <div class="jabatan">
                        Mengetahui,<br>
                        Pimpinan Pondok Pesantren
                    </div>
                    <div class="nama">
                        {{ Setting::get('nama_pimpinan', 'K.H. Pimpinan Pondok Pesantren') }}
                    </div>
                </div>

                <div class="kotak-ttd">
                    <div class="jabatan">
                        Panitia Penerimaan Santri Baru,<br>
                        Ketua Panitia PSB
                    </div>
                    <div class="nama">
                        {{ Setting::get('ttd_digital_nama', 'Ketua Panitia PSB') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>

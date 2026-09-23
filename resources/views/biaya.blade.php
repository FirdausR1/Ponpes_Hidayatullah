<!DOCTYPE html>
<html lang="id">
@php
    $biayaAwalData = $biayaAwalData ?? [];
    $biayaBulananData = $biayaBulananData ?? [];
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rincian Biaya Pendidikan & Masuk Santri Baru TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }} - Pondok Pesantren Hidayatullah</title>
    <link rel="icon" href="/logo.png" type="image/png">
    <!-- Google Fonts: EB Garamond & Plus Jakarta Sans (Gontor Typographic Standard) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Grenze:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600&family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --green-950: #06170d;
            --green-900: #0d2818;
            --green-800: #143d23;
            --green-700: #1a5632;
            --green-600: #227344;
            --green-500: #2d9157;
            --green-100: #e8f5ed;
            --green-50: #f2f9f5;
            --gold-500: #d4a017;
            --gold-600: #b8860b;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-600: #475569;
            --slate-200: #e2e8f0;
            --slate-100: #f8fafc;
            --radius-md: 12px;
            --radius-lg: 18px;
            --font-serif: 'Grenze', Georgia, serif;
            --font-sans: 'Plus Jakarta Sans', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background: #fbfdfc;
            color: var(--slate-800);
            line-height: 1.6;
        }

        /* Note: Official Navbar CSS is encapsulated within partials.navbar */

        /* Hero Banner */
        .page-hero {
            background: linear-gradient(135deg, var(--green-950) 0%, var(--green-800) 100%);
            color: white;
            padding: 56px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .page-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(212, 160, 23, 0.15) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.4;
        }

        .hero-inner {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-blur: 4px;
            padding: 4px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            color: #bbf7d0;
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .page-hero h1 {
            font-family: var(--font-serif);
            font-size: 38px;
            font-weight: 700;
            letter-spacing: 0.5px;
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .page-hero p {
            font-size: 15px;
            color: #dcfce7;
            max-width: 680px;
            margin: 0 auto 20px;
            font-weight: 300;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 12px;
            color: #86efac;
        }

        .breadcrumb a {
            color: #ffffff;
            text-decoration: none;
            opacity: 0.85;
        }

        .breadcrumb a:hover {
            opacity: 1;
            text-decoration: underline;
        }

        /* Content Container */
        .content-wrap {
            max-width: 1240px;
            margin: 40px auto;
            padding: 0 24px;
        }

        .section-header {
            margin-bottom: 24px;
        }

        .section-header h2 {
            font-family: var(--font-serif);
            font-size: 26px;
            font-weight: 700;
            color: var(--green-900);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-header p {
            font-size: 13.5px;
            color: var(--slate-600);
            margin-top: 4px;
        }

        /* Table Card */
        .table-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            margin-bottom: 40px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table.biaya-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13.5px;
        }

        table.biaya-table thead {
            background: linear-gradient(135deg, var(--green-900), var(--green-800));
            color: #ffffff;
        }

        table.biaya-table th {
            padding: 16px 20px;
            font-family: var(--font-serif);
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.3px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        table.biaya-table th:last-child {
            border-right: none;
        }

        table.biaya-table td {
            padding: 14px 20px;
            border-bottom: 1px solid var(--slate-200);
            border-right: 1px solid var(--slate-100);
            color: var(--slate-700);
        }

        table.biaya-table td:last-child {
            border-right: none;
        }

        table.biaya-table tbody tr:hover {
            background-color: var(--green-50);
        }

        table.biaya-table tr.total-row {
            background: #f0fdf4;
            font-weight: 700;
            color: var(--green-900);
        }

        table.biaya-table tr.total-row td {
            border-top: 2px solid var(--green-600);
            border-bottom: 2px solid var(--green-600);
            font-size: 14.5px;
            color: var(--green-900);
            font-weight: 700;
        }

        /* Note Cards Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
            margin-bottom: 48px;
        }

        .info-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-md);
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .info-card h3 {
            font-family: var(--font-serif);
            font-size: 18px;
            font-weight: 700;
            color: var(--green-900);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-card p, .info-card ul {
            font-size: 13px;
            color: var(--slate-600);
            line-height: 1.6;
        }

        .info-card ul {
            padding-left: 18px;
            margin-top: 8px;
        }

        .bank-box {
            background: var(--green-50);
            border: 1px dashed var(--green-500);
            border-radius: 8px;
            padding: 14px;
            margin-top: 12px;
        }

        .bank-box strong {
            color: var(--green-900);
            display: block;
            font-size: 14px;
        }

        .bank-box .account-num {
            font-family: monospace;
            font-size: 17px;
            font-weight: 700;
            color: var(--green-700);
            letter-spacing: 1px;
            margin: 4px 0;
            display: block;
        }

        /* Bottom CTA */
        .cta-banner {
            background: linear-gradient(135deg, var(--green-900), var(--green-800));
            color: white;
            border-radius: var(--radius-lg);
            padding: 44px 32px;
            text-align: center;
            margin-bottom: 60px;
            box-shadow: 0 10px 30px rgba(26, 86, 50, 0.2);
        }

        .cta-banner h3 {
            font-family: var(--font-serif);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .cta-banner p {
            font-size: 14px;
            color: #bbf7d0;
            max-width: 600px;
            margin: 0 auto 24px;
        }

        .cta-btn-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
            color: #ffffff;
            font-family: var(--font-serif);
            font-size: 16px;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(212, 160, 23, 0.3);
            transition: all 0.2s;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 160, 23, 0.4);
        }

        .btn-outline-white {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            font-family: var(--font-serif);
            font-size: 16px;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-outline-white:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #ffffff;
        }

        /* Footer */
        footer {
            background: var(--green-950);
            color: #94a3b8;
            padding: 32px 24px;
            text-align: center;
            font-size: 13px;
        }

        footer a {
            color: #86efac;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- Official Unified Header Navbar -->
    @include('partials.navbar', ['isLanding' => false])

    <!-- Page Hero Banner -->
    <section class="page-hero">
        <div class="hero-inner">
            <div class="hero-badge">Transparan • Amanah • Terjangkau</div>
            <h1>Rincian Biaya Resmi Pendidikan & Pendaftaran</h1>
            <p>Ketetapan resmi pembiayaan Pondok Pesantren Hidayatullah Tuksongo Temanggung Tahun Akademik {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }} untuk jenjang MTs dan MA secara terbuka dan penuh barokah.</p>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span>&rsaquo;</span>
                <span>Rincian Biaya Pendidikan</span>
            </div>
        </div>
    </section>

    <!-- Content Area -->
    <main class="content-wrap">
        <!-- 1. Biaya Awal Masuk -->
        <div class="section-header">
            <h2>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--green-700)">
                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                    <line x1="6" y1="8" x2="6.01" y2="8"></line>
                    <line x1="10" y1="8" x2="18" y2="8"></line>
                    <line x1="6" y1="12" x2="18" y2="12"></line>
                    <line x1="6" y1="16" x2="14" y2="16"></line>
                </svg>
                1. Rincian Biaya Awal Masuk (Daftar Ulang Pertama Kali)
            </h2>
            <p>Biaya yang dibayarkan satu kali saat dinyatakan diterima dan melakukan registrasi ulang santri baru.</p>
        </div>

        <div class="table-card">
            <div class="table-responsive">
                <table class="biaya-table">
                    <thead>
                        <tr>
                            <th style="width: 32%;">Komponen Pembayaran</th>
                            <th style="width: 17%;">MTs Mukim (Asrama)</th>
                            <th style="width: 17%;">MTs Laju (Non-Asrama)</th>
                            <th style="width: 17%;">MA Mukim (Asrama)</th>
                            <th style="width: 17%;">MA Laju (Non-Asrama)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($biayaAwalData as $row)
                            <tr class="{{ !empty($row['is_total']) ? 'total-row' : '' }}">
                                <td>
                                    <strong>{{ $row['komponen'] }}</strong>
                                </td>
                                <td>{{ $row['mts_mukim'] }}</td>
                                <td>{{ $row['mts_laju'] }}</td>
                                <td>{{ $row['ma_mukim'] }}</td>
                                <td>{{ $row['ma_laju'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="background: #f0fdf4; border-top: 1px solid #bbf7d0; padding: 14px 20px; font-size: 12.5px; color: #14532d; display: flex; align-items: center; gap: 10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="shrink: 0; color: #15803d;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                <span><strong>Catatan Daftar Ulang:</strong> Biaya pendaftaran Rp 200.000 dibayarkan di awal saat pendaftaran online. Santri yang dinyatakan diterima cukup melunasi <strong>sisa tagihan daftar ulang</strong>: <strong>MTs Mukim: Rp 3.220.000</strong> &bull; <strong>MTs Laju: Rp 2.840.000</strong> &bull; <strong>MA Mukim: Rp 3.440.000</strong> &bull; <strong>MA Laju: Rp 3.160.000</strong>.</span>
            </div>
        </div>

        <!-- 2. Iuran Bulanan -->
        <div class="section-header">
            <h2>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--green-700)">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                2. Rincian Iuran Rutin Bulanan (Syahriyah & Uang Makan)
            </h2>
            <p>Kewajiban bulanan santri mencakup konsumsi makan 3x sehari (santri mukim), pembinaan asrama, dan tabungan santri.</p>
        </div>

        <div class="table-card">
            <div class="table-responsive">
                <table class="biaya-table">
                    <thead>
                        <tr>
                            <th style="width: 32%;">Komponen Iuran Bulanan</th>
                            <th style="width: 17%;">MTs Mukim (Asrama)</th>
                            <th style="width: 17%;">MTs Laju (Non-Asrama)</th>
                            <th style="width: 17%;">MA Mukim (Asrama)</th>
                            <th style="width: 17%;">MA Laju (Non-Asrama)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($biayaBulananData as $row)
                            <tr class="{{ !empty($row['is_total']) ? 'total-row' : '' }}">
                                <td>
                                    <strong>{{ $row['komponen'] }}</strong>
                                </td>
                                <td>{{ $row['mts_mukim'] }}</td>
                                <td>{{ $row['mts_laju'] }}</td>
                                <td>{{ $row['ma_mukim'] }}</td>
                                <td>{{ $row['ma_laju'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. Kartu Informasi & Prosedur Pembayaran -->
        <div class="info-grid">
            <!-- Rekening Pembayaran -->
            <div class="info-card">
                <h3>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--green-700)">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Rekening Resmi Pesantren
                </h3>
                <p>Seluruh transaksi pembayaran biaya pendaftaran dan daftar ulang hanya disalurkan melalui rekening resmi bendahara madrasah:</p>
                
                <!-- Rekening Pembayaran Resmi -->
                <div class="bank-box">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                        <strong>Bank Rakyat Indonesia (BRI)</strong>
                        <span style="font-size: 10px; font-weight: 700; background: var(--green-100); color: var(--green-900); padding: 2px 8px; border-radius: 4px;">Administrasi / PSB</span>
                    </div>
                    <span class="account-num">{{ \App\Models\Setting::get('rek_admin_bri_no', '010201022009537') }}</span>
                    <span style="font-size: 12px; color:var(--slate-600);">a.n. <strong>{{ \App\Models\Setting::get('rek_admin_bri_an', 'DIKY FACHRI HUSEIN') }}</strong></span>
                </div>

                <div class="bank-box" style="margin-top: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                        <strong>Bank Central Asia (BCA)</strong>
                        <span style="font-size: 10px; font-weight: 700; background: var(--green-100); color: var(--green-900); padding: 2px 8px; border-radius: 4px;">Administrasi / PSB</span>
                    </div>
                    <span class="account-num">{{ \App\Models\Setting::get('rek_admin_bca_no', '1221220167') }}</span>
                    <span style="font-size: 12px; color:var(--slate-600);">a.n. <strong>{{ \App\Models\Setting::get('rek_admin_bca_an', 'DIKY FACHRI HUSEIN') }}</strong></span>
                </div>

                @php
                    $konfPhoneBiaya = \App\Models\Setting::get('rek_admin_konfirmasi_phone', '085290429617');
                    $cleanKonfPhoneBiaya = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $konfPhoneBiaya));
                @endphp
                <div style="margin-top: 10px;">
                    <a href="https://wa.me/{{ $cleanKonfPhoneBiaya }}?text={{ urlencode('Assalamu\'alaikum, saya ingin konfirmasi mengenai biaya administrasi / pendaftaran PSB.') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: var(--green-800); background: var(--green-50); border: 1px solid var(--green-200); padding: 8px 12px; border-radius: 8px; text-decoration: none;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <span>Konfirmasi WA Keuangan: {{ $konfPhoneBiaya }} ({{ \App\Models\Setting::get('rek_admin_konfirmasi_nama', 'Ustdh. Harsih Nur A') }})</span>
                    </a>
                </div>
                <p style="font-size: 11.5px; color:#64748b; margin-top:8px;">* Harap simpan dan unggah struk bukti transfer saat mengisi formulir atau konfirmasi ke Panitia PSB.</p>
            </div>

            <!-- Program Beasiswa & Dispensasi -->
            <div class="info-card">
                <h3>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--green-700)">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                    </svg>
                    Program Beasiswa & Keringanan
                </h3>
                <p>Pondok Pesantren Hidayatullah memegang prinsip bahwa tidak boleh ada anak yang terhalang menuntut ilmu agama karena faktor ekonomi:</p>
                <ul>
                    <li><strong>Beasiswa Santri Yatim / Dhuafa</strong>: Keringanan hingga bebas biaya syahriyah bagi santri berprestasi dari keluarga pra-sejahtera (melampirkan SKTM / KIP / PKH).</li>
                    <li><strong>Jalur Prestasi Tahfidz Al-Qur'an</strong>: Keringanan khusus bagi calon santri yang memiliki hafalan Al-Qur'an minimal 5 Juz bersanad / teruji.</li>
                    <li><strong>Sistem Pembayaran Bertahap</strong>: Biaya awal daftar ulang dapat dicicil hingga 3 kali pembayaran sesuai perjanjian dengan pimpinan madrasah.</li>
                </ul>
            </div>
        </div>

        <!-- 4. CTA Banner -->
        <div class="cta-banner">
            @php
                $psbSched = \App\Models\Setting::getPsbSchedule();
                $isPsbOpen = $psbSched['is_open'];
                $psbBadgeText = $psbSched['badge'];
            @endphp
            @if($isPsbOpen)
                <h3>Siap Mendaftarkan Putra-Putri Anda?</h3>
                <p>Penerimaan Santri Baru TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }} sedang berlangsung. Kuota asrama terbatas untuk menjaga kualitas pembinaan intensif.</p>
                <div class="cta-btn-group">
                    <a href="{{ route('psb.register') }}" class="btn-gold">
                        Isi Formulir Pendaftaran Sekarang &rarr;
                    </a>
                    <a href="{{ route('psb.checkStatus') }}" class="btn-outline-white">
                        Cek Status Pendaftaran
                    </a>
                </div>
            @else
                <h3>{{ $psbSched['title'] }}</h3>
                <p>{{ $psbSched['pesan'] }}</p>
                <div class="cta-btn-group">
                    <a href="{{ route('psb.register') }}" class="btn-gold" style="background: linear-gradient(135deg, #dc2626, #b91c1c); border-color: #ef4444; color: #ffffff;">
                        Pendaftaran ({{ $psbBadgeText }}) • Info Lengkap &rarr;
                    </a>
                    <a href="{{ route('psb.checkStatus') }}" class="btn-outline-white">
                        Cek Status Pendaftaran
                    </a>
                </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} Pondok Pesantren Hidayatullah Tuksongo Pringsurat Temanggung. All rights reserved.</p>
        <p style="margin-top: 6px;"><a href="{{ route('home') }}">Beranda</a> • <a href="{{ route('biaya.index') }}">Rincian Biaya</a> • <a href="{{ route('ujian.index') }}">Portal Ujian CBT</a> • <a href="{{ route('psb.checkStatus') }}">Cek Status</a></p>
    </footer>
</body>

</html>

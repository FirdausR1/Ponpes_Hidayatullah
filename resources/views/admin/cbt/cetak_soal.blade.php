<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Lembar Soal — {{ $title }}</title>
    <link rel="icon" href="/logo.png" type="image/png">
    <!-- Google Fonts: EB Garamond, Amiri, Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=EB+Garamond:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- KaTeX CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #111;
            background: #f8fafc;
            padding: 24px;
            font-size: 13px;
            line-height: 1.6;
        }
        .sheet {
            background: #fff;
            max-width: 840px;
            margin: 0 auto;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .header-kop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #111;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }
        .kop-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
        }
        .kop-text {
            text-align: center;
            flex: 1;
            padding: 0 16px;
        }
        .kop-text h2 {
            font-family: 'EB Garamond', serif;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0d3b1e;
        }
        .kop-text .arabic-sub {
            font-family: 'Amiri', serif;
            font-size: 18px;
            direction: rtl;
            color: #145a2e;
            margin-top: -2px;
        }
        .kop-text p {
            font-size: 11px;
            color: #444;
            margin-top: 2px;
        }
        .meta-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            border: 1px solid #ddd;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 24px;
            background: #fbfdfc;
            font-size: 12px;
        }
        .meta-col div { margin-bottom: 4px; }
        .meta-label { font-weight: 600; width: 130px; display: inline-block; color: #555; }
        .exam-instructions {
            background: #fdfdfd;
            border-left: 3px solid #0d3b1e;
            padding: 8px 14px;
            font-size: 11px;
            margin-bottom: 24px;
            color: #555;
        }
        .question-item {
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 1px dashed #e2e8f0;
            page-break-inside: avoid;
        }
        .question-header {
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: baseline;
            gap: 8px;
        }
        .q-number {
            font-weight: 700;
            color: #0d3b1e;
        }
        .q-text {
            flex: 1;
            font-size: 13.5px;
        }
        .arabic-text {
            font-family: 'Amiri', serif;
            direction: rtl;
            text-align: right;
            font-size: 16px;
            line-height: 2.2;
        }
        .options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 18px;
            margin-left: 24px;
            font-size: 12.5px;
        }
        .option-choice {
            display: flex;
            align-items: baseline;
            gap: 6px;
        }
        .option-choice .letter {
            font-weight: 700;
            color: #222;
        }
        .no-print-bar {
            max-width: 840px;
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
        <a href="{{ route('admin.cbt.soal.index') }}" style="text-decoration:none; color:#475569; font-size:12px; font-weight:600;">&larr; Kembali ke Admin</a>
        <button onclick="window.print()" class="btn-print">
            <svg style="width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Cetak Dokumen (Print / PDF)
        </button>
    </div>

    <div class="sheet" id="printSheet">
        <!-- KOP SURAT RESMI -->
        <div class="header-kop">
            <img src="/logo.png" alt="Logo" class="kop-logo">
            <div class="kop-text">
                <h2>Pondok Pesantren Hidayatullah Tuksongo</h2>
                <div class="arabic-sub">معهد هداية الله للتربية الإسلامية — برينغسورات تيمانتغونغ</div>
                <p>NSPP: 512032304095 • Terakreditasi B (BAN-SM Kemenag MTs & MA)</p>
                <p>Alamat: Dusun Tuksongo RT 01/RW 01, Nglorog, Pringsurat, Kab. Temanggung, Jawa Tengah 56272</p>
            </div>
            <img src="/logo.png" alt="Logo" class="kop-logo" style="visibility:hidden;">
        </div>

        <!-- Identitas Ujian & Lembar Santri -->
        <div style="text-align: center; margin-bottom: 16px;">
            <h3 style="font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                {{ $title }}
            </h3>
            <span style="font-size: 11px; color: #555;">TAHUN AJARAN {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}</span>
        </div>

        <div class="meta-box">
            <div class="meta-col">
                <div><span class="meta-label">Nama Lengkap Santri</span>: ..............................................................</div>
                <div><span class="meta-label">Nomor Registrasi / Ujian</span>: ..............................................................</div>
            </div>
            <div class="meta-col">
                <div><span class="meta-label">Jenjang Pendidikan</span>: [ ] MTs &nbsp;&nbsp;&nbsp; [ ] MA</div>
                <div><span class="meta-label">Tanggal Pelaksanaan</span>: ..............................................................</div>
            </div>
        </div>

        <div class="exam-instructions">
            <strong>Petunjuk Pengerjaan:</strong>
            Pilihlah salah satu jawaban yang paling tepat dengan memberi tanda silang (X) atau menghitamkan bulatan pada huruf A, B, C, D, atau E pada lembar jawaban yang tersedia.
        </div>

        <!-- DAFTAR SOAL -->
        <div class="questions-wrapper">
            @forelse($questions as $index => $q)
                <div class="question-item">
                    <div class="question-header">
                        <span class="q-number">{{ $index + 1 }}.</span>
                        <div class="q-text render-math {{ $q->is_arabic ? 'arabic-text' : '' }}">
                            {!! nl2br(e($q->soal)) !!}
                            @if(!empty($q->gambar))
                                <div style="margin: 8px 0;">
                                    <img src="{{ asset($q->gambar) }}" alt="Gambar Soal {{ $index + 1 }}" style="max-height: 180px; max-width: 100%; object-fit: contain; border: 1px solid #e2e8f0; border-radius: 4px; padding: 2px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="options-grid">
                        @foreach(['A' => $q->opsi_a, 'B' => $q->opsi_b, 'C' => $q->opsi_c, 'D' => $q->opsi_d, 'E' => $q->opsi_e] as $opt => $val)
                            @if(!empty($val))
                                <div class="option-choice">
                                    <span class="letter">{{ $opt }}.</span>
                                    <span class="render-math {{ $q->is_arabic ? 'arabic-text' : '' }}">{{ $val }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @empty
                <p style="text-align:center; padding: 40px; color:#888;">Belum ada soal pada kategori ini.</p>
            @endforelse
        </div>

        <!-- Tanda Tangan Pengawas -->
        <div style="margin-top: 36px; display: flex; justify-content: flex-end; page-break-inside: avoid;">
            <div style="text-align: center; width: 220px; font-size: 12px;">
                <p>Temanggung, .......................... {{ date('Y') }}</p>
                <p style="margin-top: 4px; font-weight: 600;">Pengawas Ujian,</p>
                <div style="height: 60px;"></div>
                <p style="font-weight: 700; text-decoration: underline;">( .................................................... )</p>
                <p style="font-size: 10px; color:#555;">NIP/NIY: .......................................</p>
            </div>
        </div>
    </div>

    <!-- KaTeX Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"
            onload="renderMathInElement(document.getElementById('printSheet'), {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},
                    {left: '\\[', right: '\\]', display: true}
                ],
                throwOnError: false
            });"></script>
</body>
</html>

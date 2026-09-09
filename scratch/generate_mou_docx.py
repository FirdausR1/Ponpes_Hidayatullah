import os
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement
from docx.oxml.ns import qn

def set_cell_background(cell, hex_color):
    """Set background color of a table cell."""
    tcPr = cell._element.get_or_add_tcPr()
    shd = OxmlElement('w:shd')
    shd.set(qn('w:val'), 'clear')
    shd.set(qn('w:color'), 'auto')
    shd.set(qn('w:fill'), hex_color)
    tcPr.append(shd)

def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
    """Set inner margins of a table cell."""
    tcPr = cell._element.get_or_add_tcPr()
    tcMar = OxmlElement('w:tcMar')
    for m, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        node = OxmlElement(f'w:{m}')
        node.set(qn('w:w'), str(val))
        node.set(qn('w:type'), 'dxa')
        tcMar.append(node)
    tcPr.append(tcMar)

def set_table_borders(table, color="CCCCCC", sz="4", val="single"):
    """Set thin clean borders for a table."""
    tblPr = table._element.xpath('w:tblPr')
    if tblPr:
        borders = OxmlElement('w:tblBorders')
        for border_name in ['top', 'left', 'bottom', 'right', 'insideH']:
            border = OxmlElement(f'w:{border_name}')
            border.set(qn('w:val'), val)
            border.set(qn('w:sz'), sz)
            border.set(qn('w:space'), '0')
            border.set(qn('w:color'), color)
            borders.append(border)
        tblPr[0].append(borders)

doc = Document()

# Page Setup: A4, 2.54 cm margins
for section in doc.sections:
    section.top_margin = Inches(1)
    section.bottom_margin = Inches(1)
    section.left_margin = Inches(1)
    section.right_margin = Inches(1)
    section.page_width = Inches(8.27)  # A4 width
    section.page_height = Inches(11.69) # A4 height

# Primary Colors
DARK_GREEN = RGBColor(13, 59, 30)
GOLD = RGBColor(180, 140, 20)
GRAY_TEXT = RGBColor(80, 80, 80)
BLACK = RGBColor(20, 20, 20)

# ==========================================
# 1. KOP SURAT RESMI
# ==========================================
kop_table = doc.add_table(rows=1, cols=2)
kop_table.alignment = WD_TABLE_ALIGNMENT.CENTER
kop_table.autofit = False

logo_cell = kop_table.cell(0, 0)
logo_cell.width = Inches(1.2)
text_cell = kop_table.cell(0, 1)
text_cell.width = Inches(5.0)

# Logo
logo_path = os.path.abspath('public/logo.png')
if os.path.exists(logo_path):
    p_logo = logo_cell.paragraphs[0]
    p_logo.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run_logo = p_logo.add_run()
    run_logo.add_picture(logo_path, width=Inches(1.0))

p_kop = text_cell.paragraphs[0]
p_kop.alignment = WD_ALIGN_PARAGRAPH.CENTER
r1 = p_kop.add_run("YAYASAN PONDOK PESANTREN HIDAYATULLAH TEMANGGUNG\n")
r1.font.name = "Times New Roman"
r1.font.size = Pt(10)
r1.font.bold = True

r2 = p_kop.add_run("PONDOK PESANTREN HIDAYATULLAH TUKSONGO\n")
r2.font.name = "Times New Roman"
r2.font.size = Pt(14)
r2.font.bold = True
r2.font.color.rgb = DARK_GREEN

r3 = p_kop.add_run("Madrasah Tsanawiyah (MTs) • Madrasah Aliyah (MA) Tahfidz & Sains Al-Qur'an\n")
r3.font.name = "Times New Roman"
r3.font.size = Pt(9.5)
r3.font.bold = True

r4 = p_kop.add_run("Dusun Tuksongo RT 01/RW 01, Desa Nglorog, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah 56272\nHotline PSB / WA: 0852-9042-9617 • Website: ponpeshidayatullahtuksongo.com")
r4.font.name = "Times New Roman"
r4.font.size = Pt(8.5)
r4.font.color.rgb = GRAY_TEXT

# Separator Line
p_line = doc.add_paragraph()
p_line.paragraph_format.space_before = Pt(4)
p_line.paragraph_format.space_after = Pt(12)
p_line_border = p_line.add_run("―" * 58)
p_line_border.font.size = Pt(14)
p_line_border.font.bold = True
p_line_border.font.color.rgb = DARK_GREEN
p_line.alignment = WD_ALIGN_PARAGRAPH.CENTER

# ==========================================
# 2. JUDUL MOU
# ==========================================
p_title = doc.add_paragraph()
p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_title.paragraph_format.space_after = Pt(2)
r_title = p_title.add_run("SURAT PERJANJIAN KERJASAMA (MEMORANDUM OF UNDERSTANDING)\n")
r_title.font.name = "Times New Roman"
r_title.font.size = Pt(12)
r_title.font.bold = True
r_title.font.underline = True

r_sub = p_title.add_run("PENGEMBANGAN WEBSITE PROFIL RESMI & SISTEM INFORMASI PSB ONLINE TERPADU\n")
r_sub.font.name = "Times New Roman"
r_sub.font.size = Pt(11)
r_sub.font.bold = True
r_sub.font.color.rgb = DARK_GREEN

r_nomor = p_title.add_run("Nomor: 017/SPK-IT/PPH-FR/IX/2026")
r_nomor.font.name = "Times New Roman"
r_nomor.font.size = Pt(10)
r_nomor.font.italic = True

# Pembukaan
p_open = doc.add_paragraph()
p_open.paragraph_format.space_before = Pt(10)
p_open.paragraph_format.space_after = Pt(6)
p_open.paragraph_format.line_spacing = 1.15
r_open = p_open.add_run(
    "Pada hari ini, Selasa, tanggal Delapan bulan September tahun Dua Ribu Dua Puluh Enam (08-09-2026), "
    "bertempat di Temanggung, telah disepakati dan ditandatangani Perjanjian Kerjasama Pengadaan Jasa "
    "Pengembangan Sistem Perangkat Lunak Web dan Layanan Cloud Hosting antara pihak-pihak:"
)
r_open.font.name = "Times New Roman"
r_open.font.size = Pt(10.5)

# Pihak 1 & 2
t_pihak = doc.add_table(rows=2, cols=2)
t_pihak.autofit = False
t_pihak.alignment = WD_TABLE_ALIGNMENT.CENTER

c00 = t_pihak.cell(0, 0)
c00.width = Inches(1.8)
c01 = t_pihak.cell(0, 1)
c01.width = Inches(4.4)

c10 = t_pihak.cell(1, 0)
c10.width = Inches(1.8)
c11 = t_pihak.cell(1, 1)
c11.width = Inches(4.4)

c00.paragraphs[0].add_run("1. PIHAK PERTAMA (KLIEN)").font.bold = True
c00.paragraphs[0].runs[0].font.size = Pt(10)
c00.paragraphs[0].runs[0].font.name = "Times New Roman"

p_c01 = c01.paragraphs[0]
p_c01.paragraph_format.line_spacing = 1.15
r_p1 = p_c01.add_run(
    "PONDOK PESANTREN HIDAYATULLAH TUKSONGO\n"
    "Alamat: Dusun Tuksongo RT 01/RW 01, Desa Nglorog, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah 56272\n"
    "Dalam hal ini diwakili secara sah oleh Pimpinan / Pengurus Yayasan, selanjutnya bertindak atas nama Pondok Pesantren Hidayatullah Tuksongo."
)
r_p1.font.size = Pt(10)
r_p1.font.name = "Times New Roman"

c10.paragraphs[0].add_run("2. PIHAK KEDUA (PENGEMBANG)").font.bold = True
c10.paragraphs[0].runs[0].font.size = Pt(10)
c10.paragraphs[0].runs[0].font.name = "Times New Roman"

p_c11 = c11.paragraphs[0]
p_c11.paragraph_format.line_spacing = 1.15
r_p2 = p_c11.add_run(
    "FIRDAUS ROMANDHANU, S.Kom.\n"
    "Profesi: Konsultan Teknologi Informasi / Full-Stack Web Developer\n"
    "Keahlian: Pengembangan Sistem Informasi Berbasis Web & Software Engineering\n"
    "Selanjutnya bertindak selaku penyedia jasa perancangan, pengembangan, dan implementasi sistem."
)
r_p2.font.size = Pt(10)
r_p2.font.name = "Times New Roman"

p_sep = doc.add_paragraph()
p_sep.paragraph_format.space_before = Pt(6)
p_sep.paragraph_format.space_after = Pt(6)
r_sep = p_sep.add_run("Kedua belah pihak bersepakat untuk mengikatkan diri dalam Surat Perjanjian Kerjasama (MoU) dengan pasal-pasal ketentuan sebagai berikut:")
r_sep.font.name = "Times New Roman"
r_sep.font.size = Pt(10.5)

# ==========================================
# PASAL-PASAL
# ==========================================
articles = [
    (
        "PASAL 1: RUANG LINGKUP PEKERJAAN (SCOPE OF WORK)",
        "PIHAK PERTAMA memberikan mandat pekerjaan kepada PIHAK KEDUA, dan PIHAK KEDUA menerima tugas tersebut untuk merancang, membangun, dan meluncurkan sistem berbasis web yang mencakup:\n"
        "1. Website Profil Resmi Pesantren: Landing page modern bernuansa Islami, responsive multi-device (mobile/tablet/PC), Hero Banner dinamis, Profil Yayasan, Sambutan Pimpinan, Falsafah Panca Jiwa, Fondasi 4 Pilar Pendidikan, Sarana & Prasarana Kampus, Jadwal 24 Jam Santri, Agenda Berkala, serta Portal Berita & Warta Pesantren.\n"
        "2. Sistem Informasi PSB Online Terpadu: Formulir pendaftaran multi-jalur (Reguler, Prestasi, Tahfidz; MTs & MA; Mukim & Laju); validasi kelengkapan berkas dan bantuan sosial; fitur verifikasi pas foto otomatis 3x4 dan deteksi background merah; layanan cek status verifikasi mandiri tanpa akun; formulir unggah ulang foto bermasalah; serta generator cetak mandiri Kartu Biodata Santri (Format CV) standar kedinasan berstempel digital online.\n"
        "3. Panel Administrasi Pengelola (TailAdmin Dashboard): Manajemen pendaftar santri baru (seleksi status, perbaikan foto/berkas), filter multi-kriteria (Tahun, Gender, Jenjang, Status), fitur ekspor data santri ke Microsoft Excel (.xls) dengan proteksi format angka NIK 16 digit utuh, cetak massal kartu CV santri, modul pengaturan tanda tangan digital pengurus (upload PNG transparan & canvas sign pad), serta pengaturan konten website dinamis.\n"
        "4. Infrastruktur Cloud Hosting & Domain: Konfigurasi nama domain resmi pesantren selama 1 (satu) tahun dan Cloud SSD Web Hosting berkecepatan tinggi dengan sertifikat SSL (HTTPS) aktif selama 1 (satu) tahun."
    ),
    (
        "PASAL 2: NILAI BIAYA INVESTASI PENGEMBANGAN",
        "1. Nilai investasi untuk pekerjaan pembuatan sistem/program dan pengadaan hosting/domain adalah sebesar:\n"
        "   - Biaya Pengembangan Sistem & Program Perangkat Lunak Web: Rp 6.000.000,- (Enam Juta Rupiah).\n"
        "   - Biaya Pengadaan Domain Resmi & Cloud SSD Web Hosting (1 Tahun): Rp 1.000.000,- (Satu Juta Rupiah).\n"
        "   - Total Nilai Kontrak Keseluruhan: Rp 7.000.000,- (Tujuh Juta Rupiah).\n"
        "2. Biaya di atas bersifat all-in (sudah mencakup lisensi aplikasi, setup server, dan seluruh fitur pada Pasal 1) tanpa adanya biaya tersembunyi."
    ),
    (
        "PASAL 3: PAKET LAYANAN GRATIS & BENEFIT KHUSUS (VALUE-ADDED PROMOTION)",
        "Sebagai bentuk apresiasi dan komitmen penuh PIHAK KEDUA dalam menyukseskan digitalisasi Pondok Pesantren Hidayatullah Tuksongo, PIHAK KEDUA memberikan PAKET LAYANAN GRATIS senilai total Rp 4.500.000,- yang diberikan secara CUMA-CUMA, meliputi:\n"
        "1. GRATIS Garansi Pemeliharaan Sistem & Bug Fixing selama 6 (ENAM) BULAN PENUH (Senilai Rp 1.500.000,- → FREE).\n"
        "2. GRATIS Layanan Pendampingan & Bantuan Input Data Santri serta Publikasi Berita Awal selama 3 (TIGA) BULAN PERTAMA (Senilai Rp 750.000,- → FREE).\n"
        "3. GRATIS Konsultasi Teknis & Hotline WhatsApp Prioritas 24/7 selama Masa PSB Berjalan (Senilai Rp 1.000.000,- → FREE).\n"
        "4. GRATIS Pelatihan Staf Admin & Operator Pesantren (Tatap Muka / Online) + Penyusunan Buku Panduan Operasional Sistem (SOP) Lengkap (Senilai Rp 500.000,- → FREE).\n"
        "5. GRATIS Layanan Backup Database Otomatis Bulanan ke Cloud Penyimpanan Terpisah selama 1 (Satu) Tahun (Senilai Rp 500.000,- → FREE).\n"
        "6. GRATIS Konfigurasi Sertifikat Keamanan SSL/TLS Grade A (HTTPS) & Optimalisasi Mesin Pencari Google (SEO) (Senilai Rp 250.000,- → FREE)."
    ),
    (
        "PASAL 4: SKEMA TATA CARA PEMBAYARAN",
        "Pembayaran dilakukan oleh PIHAK PERTAMA kepada PIHAK KEDUA melalui transfer bank secara bertahap dalam 3 (tiga) termin:\n"
        "1. Termin I (Uang Muka / Down Payment - 40%): Sebesar Rp 2.800.000,- (Dua Juta Delapan Ratus Ribu Rupiah) dibayarkan pada saat penandatanganan MoU untuk aktivasi hosting, nama domain resmi, dan inisiasi arsitektur sistem.\n"
        "2. Termin II (Progress Development & Deployment - 40%): Sebesar Rp 2.800.000,- (Dua Juta Delapan Ratus Ribu Rupiah) dibayarkan setelah seluruh sistem online terpasang di server hosting dan lolos uji coba fungsional bersama.\n"
        "3. Termin III (Pelunasan & Serah Terima Akhir - 20%): Sebesar Rp 1.400.000,- (Satu Juta Empat Ratus Ribu Rupiah) dibayarkan pada saat serah terima akses penuh administrator, pelatihan admin, dan penandatanganan Berita Acara Serah Terima (BAST)."
    ),
    (
        "PASAL 5: JANGKA WAKTU PENGERJAAN & SERAH TERIMA",
        "1. Waktu pengerjaan sistem diselesaikan dalam waktu 14 (empat belas) sampai 21 (dua puluh satu) hari kerja terhitung sejak diterimanya pembayaran Termin I.\n"
        "2. Serah terima sistem dinyatakan sah ditandai dengan penyerahan hak akses admin cPanel, source code, database, dan penandatanganan Berita Acara Serah Terima (BAST)."
    ),
    (
        "PASAL 6: HAK KEPEMILIKAN & KERAHASIAAN DATA",
        "1. Seluruh data santri, data wali, dokumen berkas pendaftaran, serta konten website adalah HAK MILIK MUTLAK PIHAK PERTAMA.\n"
        "2. PIHAK KEDUA berkewajiban menjamin kerahasiaan data dan tidak berhak mempublikasikan, menggandakan, atau memindahtangankan data santri kepada pihak mana pun.\n"
        "3. Kode program diserahkan sepenuhnya kepada PIHAK PERTAMA untuk dipergunakan secara bebas tanpa batas waktu bagi kemajuan operasional Pondok Pesantren Hidayatullah Tuksongo."
    )
]

for title, content in articles:
    p_art = doc.add_paragraph()
    p_art.paragraph_format.space_before = Pt(8)
    p_art.paragraph_format.space_after = Pt(3)
    r_art = p_art.add_run(title)
    r_art.font.name = "Times New Roman"
    r_art.font.size = Pt(10.5)
    r_art.font.bold = True
    r_art.font.color.rgb = DARK_GREEN

    p_body = doc.add_paragraph()
    p_body.paragraph_format.space_before = Pt(0)
    p_body.paragraph_format.space_after = Pt(4)
    p_body.paragraph_format.line_spacing = 1.15
    r_body = p_body.add_run(content)
    r_body.font.name = "Times New Roman"
    r_body.font.size = Pt(10)

# Penutup
p_close = doc.add_paragraph()
p_close.paragraph_format.space_before = Pt(8)
p_close.paragraph_format.space_after = Pt(16)
r_close = p_close.add_run("Demikian Surat Perjanjian Kerjasama ini dibuat dalam rangkap 2 (dua) bermeterai cukup dan memiliki kekuatan hukum yang sah mengikat kedua belah pihak sejak tanggal ditandatangani.")
r_close.font.name = "Times New Roman"
r_close.font.size = Pt(10)

# Tanda Tangan MoU
t_ttd = doc.add_table(rows=1, cols=2)
t_ttd.autofit = False
t_ttd.alignment = WD_TABLE_ALIGNMENT.CENTER

t_ttd.cell(0, 0).width = Inches(3.1)
t_ttd.cell(0, 1).width = Inches(3.1)

# Pihak 1
p_ttd1 = t_ttd.cell(0, 0).paragraphs[0]
p_ttd1.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_ttd1.paragraph_format.line_spacing = 1.15
r_t1 = p_ttd1.add_run("PIHAK PERTAMA,\nPondok Pesantren Hidayatullah Tuksongo\n\n\n\n\n")
r_t1.font.name = "Times New Roman"
r_t1.font.size = Pt(10)
r_t1.font.bold = True

r_t1_name = p_ttd1.add_run("( .............................................................. )\n")
r_t1_name.font.name = "Times New Roman"
r_t1_name.font.size = Pt(10)
r_t1_name.font.bold = True
r_t1_name.font.underline = True

r_t1_sub = p_ttd1.add_run("Pimpinan / Pengurus Yayasan")
r_t1_sub.font.name = "Times New Roman"
r_t1_sub.font.size = Pt(9)

# Pihak 2
p_ttd2 = t_ttd.cell(0, 1).paragraphs[0]
p_ttd2.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_ttd2.paragraph_format.line_spacing = 1.15
r_t2 = p_ttd2.add_run("PIHAK KEDUA,\nKonsultan TI & Pengembang Sistem\n\n\n\n\n")
r_t2.font.name = "Times New Roman"
r_t2.font.size = Pt(10)
r_t2.font.bold = True

r_t2_name = p_ttd2.add_run("FIRDAUS ROMANDHANU, S.Kom.\n")
r_t2_name.font.name = "Times New Roman"
r_t2_name.font.size = Pt(10)
r_t2_name.font.bold = True
r_t2_name.font.underline = True

r_t2_sub = p_ttd2.add_run("IT Consultant & Full-Stack Developer")
r_t2_sub.font.name = "Times New Roman"
r_t2_sub.font.size = Pt(9)

# ==========================================
# LEMBAR 2: RENCANA ANGGARAN BIAYA (RAB)
# ==========================================
doc.add_page_break()

p_rab_title = doc.add_paragraph()
p_rab_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_rab_title.paragraph_format.space_after = Pt(2)
r_rt1 = p_rab_title.add_run("LAMPIRAN: RENCANA ANGGARAN BIAYA (RAB)\n")
r_rt1.font.name = "Times New Roman"
r_rt1.font.size = Pt(13)
r_rt1.font.bold = True
r_rt1.font.underline = True

r_rt2 = p_rab_title.add_run("PENGEMBANGAN WEBSITE PROFIL RESMI & SISTEM PSB ONLINE TERPADU\n")
r_rt2.font.name = "Times New Roman"
r_rt2.font.size = Pt(11)
r_rt2.font.bold = True
r_rt2.font.color.rgb = DARK_GREEN

r_rt3 = p_rab_title.add_run("Pondok Pesantren Hidayatullah Tuksongo Pringsurat Temanggung • TA 2026")
r_rt3.font.name = "Times New Roman"
r_rt3.font.size = Pt(9.5)
r_rt3.font.italic = True

p_line2 = doc.add_paragraph()
p_line2.paragraph_format.space_before = Pt(4)
p_line2.paragraph_format.space_after = Pt(10)
p_line2_border = p_line2.add_run("―" * 58)
p_line2_border.font.size = Pt(12)
p_line2_border.font.bold = True
p_line2_border.font.color.rgb = DARK_GREEN
p_line2.alignment = WD_ALIGN_PARAGRAPH.CENTER

# Tabel RAB Detail
rab_items = [
    ("I", "BIAYA PENGADAAN DOMAIN & CLOUD HOSTING (1 TAHUN)", "", "", "", "1.000.000", True),
    ("1.1", "Sewa Nama Domain Resmi Pesantren (.sch.id / .com / .org) durasi 1 Tahun", "1", "Paket", "250.000", "250.000", False),
    ("1.2", "Cloud SSD NVMe Web Hosting (High-Speed, Unlimited Bandwidth, cPanel, Auto-Backup) 1 Tahun", "1", "Paket", "650.000", "650.000", False),
    ("1.3", "Konfigurasi Sertifikat Keamanan SSL/TLS (HTTPS Grade A 256-Bit Encryption)", "1", "Setup", "100.000", "100.000", False),
    
    ("II", "BIAYA DESAIN UI/UX & PENGEMBANGAN SISTEM/PROGRAM", "", "", "", "6.000.000", True),
    ("2.1", "Desain UI/UX Nuansa Islami Modern, Responsive Multi-Device (Mobile First, Tablet, PC)", "1", "Sistem", "1.000.000", "1.000.000", False),
    ("2.2", "Front-End Landing Page (Hero, Profil, Kalam Pimpinan, Falsafah Panca Jiwa, 4 Pilar, Fasilitas, Jadwal 24 Jam)", "1", "Modul", "1.500.000", "1.500.000", False),
    ("2.3", "Portal Berita, Artikel & Dokumentasi Kegiatan Pesantren Dinamis", "1", "Modul", "500.000", "500.000", False),
    ("2.4", "Formulir Pendaftaran PSB Multi-Jalur (Reguler, Prestasi, Tahfidz; MTs & MA) & Upload Berkas", "1", "Modul", "1.000.000", "1.000.000", False),
    ("2.5", "Sistem Auto-Verifikasi Pas Foto AI (Validasi Rasio 3x4 Tegak & Deteksi Background Merah)", "1", "Modul", "800.000", "800.000", False),
    ("2.6", "Layanan Tracking Status Verifikasi Mandiri Tanpa Akun & Form Perbaikan Pas Foto", "1", "Modul", "500.000", "500.000", False),
    ("2.7", "Generator Cetak Kartu Biodata Santri Baru (Format CV Kedinasan) & Export PDF Mandiri", "1", "Modul", "700.000", "700.000", False),
    
    ("III", "PAKET BONUS & LAYANAN GRATIS KHUSUS (VALUE ADDED)", "", "", "", "GRATIS", True),
    ("3.1", "GRATIS Garansi Pemeliharaan & Bug Fixing Penuh selama 6 Bulan (Normal: Rp 1.500.000,-)", "6", "Bulan", "0", "0 (FREE)", False),
    ("3.2", "GRATIS Pendampingan & Bantuan Input Data Santri Awal selama 3 Bulan (Normal: Rp 750.000,-)", "3", "Bulan", "0", "0 (FREE)", False),
    ("3.3", "GRATIS Konsultasi Teknis & Hotline WhatsApp Prioritas 24/7 selama PSB (Normal: Rp 1.000.000,-)", "1", "Paket", "0", "0 (FREE)", False),
    ("3.4", "GRATIS Pelatihan Staf Admin Pesantren + Buku Panduan SOP Sistem (Normal: Rp 500.000,-)", "1", "Sesi", "0", "0 (FREE)", False),
    ("3.5", "GRATIS Backup Database Cloud Otomatis Bulanan selama 1 Tahun (Normal: Rp 500.000,-)", "1", "Tahun", "0", "0 (FREE)", False),
    ("3.6", "GRATIS Optimalisasi Mesin Pencari Google (SEO) & Kecepatan Akses (Normal: Rp 250.000,-)", "1", "Paket", "0", "0 (FREE)", False),
]

table_rab = doc.add_table(rows=len(rab_items) + 2, cols=6)
table_rab.autofit = False
table_rab.alignment = WD_TABLE_ALIGNMENT.CENTER
set_table_borders(table_rab)

col_widths = [Inches(0.4), Inches(3.2), Inches(0.4), Inches(0.6), Inches(0.9), Inches(0.9)]

# Header Row
hdr_cells = table_rab.rows[0].cells
hdr_titles = ["No", "Komponen Pekerjaan & Spesifikasi Layanan", "Vol", "Satuan", "Harga (Rp)", "Total (Rp)"]
for idx, title in enumerate(hdr_titles):
    hdr_cells[idx].width = col_widths[idx]
    set_cell_background(hdr_cells[idx], "0D3B1E")
    set_cell_margins(hdr_cells[idx], top=80, bottom=80, left=100, right=100)
    p = hdr_cells[idx].paragraphs[0]
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r = p.add_run(title)
    r.font.name = "Times New Roman"
    r.font.size = Pt(9)
    r.font.bold = True
    r.font.color.rgb = RGBColor(255, 255, 255)

# Populate Data Rows
for r_idx, item in enumerate(rab_items, start=1):
    row_cells = table_rab.rows[r_idx].cells
    for c_idx in range(6):
        row_cells[c_idx].width = col_widths[c_idx]
        set_cell_margins(row_cells[c_idx], top=50, bottom=50, left=80, right=80)
        p = row_cells[c_idx].paragraphs[0]
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(0)
        
        val_text = item[c_idx]
        is_category = item[6]
        
        if is_category:
            set_cell_background(row_cells[c_idx], "EAF3ED")
            r = p.add_run(val_text)
            r.font.bold = True
            r.font.name = "Times New Roman"
            r.font.size = Pt(9)
            if c_idx in [0, 2, 3]:
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            elif c_idx in [4, 5]:
                p.alignment = WD_ALIGN_PARAGRAPH.RIGHT
                r.font.color.rgb = DARK_GREEN
        else:
            r = p.add_run(val_text)
            r.font.name = "Times New Roman"
            r.font.size = Pt(8.5)
            if c_idx in [0, 2, 3]:
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            elif c_idx in [4, 5]:
                p.alignment = WD_ALIGN_PARAGRAPH.RIGHT
                if "FREE" in val_text:
                    r.font.bold = True
                    r.font.color.rgb = RGBColor(16, 140, 60)

# Total Row
tot_cells = table_rab.rows[len(rab_items) + 1].cells
for c_idx in range(6):
    tot_cells[c_idx].width = col_widths[c_idx]
    set_cell_background(tot_cells[c_idx], "0D3B1E")
    set_cell_margins(tot_cells[c_idx], top=80, bottom=80, left=100, right=100)

tot_cells[0].merge(tot_cells[4])
p_tot = tot_cells[0].paragraphs[0]
p_tot.alignment = WD_ALIGN_PARAGRAPH.RIGHT
r_tot_lbl = p_tot.add_run("TOTAL KESELURUHAN BIAYA INVESTASI (PROGRAM + HOSTING):")
r_tot_lbl.font.name = "Times New Roman"
r_tot_lbl.font.size = Pt(9.5)
r_tot_lbl.font.bold = True
r_tot_lbl.font.color.rgb = RGBColor(255, 255, 255)

p_val = tot_cells[5].paragraphs[0]
p_val.alignment = WD_ALIGN_PARAGRAPH.RIGHT
r_tot_val = p_val.add_run("Rp 7.000.000")
r_tot_val.font.name = "Times New Roman"
r_tot_val.font.size = Pt(10)
r_tot_val.font.bold = True
r_tot_val.font.color.rgb = RGBColor(245, 220, 90)

# Terbilang
p_terbilang = doc.add_paragraph()
p_terbilang.paragraph_format.space_before = Pt(8)
p_terbilang.paragraph_format.space_after = Pt(12)
r_tb = p_terbilang.add_run("Terbilang: \"Tujuh Juta Rupiah\" (Program Rp 6.000.000,- + Hosting Domain 1 Tahun Rp 1.000.000,- + BONUS Layanan Senilai Rp 4.500.000,-)")
r_tb.font.name = "Times New Roman"
r_tb.font.size = Pt(9.5)
r_tb.font.italic = True
r_tb.font.bold = True

# Tanda Tangan RAB
t_ttd_rab = doc.add_table(rows=1, cols=2)
t_ttd_rab.autofit = False
t_ttd_rab.alignment = WD_TABLE_ALIGNMENT.CENTER

t_ttd_rab.cell(0, 0).width = Inches(3.1)
t_ttd_rab.cell(0, 1).width = Inches(3.1)

# Pihak 1
p_tr1 = t_ttd_rab.cell(0, 0).paragraphs[0]
p_tr1.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_tr1.paragraph_format.line_spacing = 1.15
r_tr1 = p_tr1.add_run("Menyetujui,\nPondok Pesantren Hidayatullah Tuksongo\n\n\n\n\n")
r_tr1.font.name = "Times New Roman"
r_tr1.font.size = Pt(10)
r_tr1.font.bold = True

r_tr1_name = p_tr1.add_run("( .............................................................. )\n")
r_tr1_name.font.name = "Times New Roman"
r_tr1_name.font.size = Pt(10)
r_tr1_name.font.bold = True
r_tr1_name.font.underline = True

r_tr1_sub = p_tr1.add_run("Pimpinan / Pengurus Yayasan")
r_tr1_sub.font.name = "Times New Roman"
r_tr1_sub.font.size = Pt(9)

# Pihak 2
p_tr2 = t_ttd_rab.cell(0, 1).paragraphs[0]
p_tr2.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_tr2.paragraph_format.line_spacing = 1.15
r_tr2 = p_tr2.add_run("Temanggung, 08 September 2026\nDiajukan Oleh,\n\n\n\n\n")
r_tr2.font.name = "Times New Roman"
r_tr2.font.size = Pt(10)
r_tr2.font.bold = True

r_tr2_name = p_tr2.add_run("FIRDAUS ROMANDHANU, S.Kom.\n")
r_tr2_name.font.name = "Times New Roman"
r_tr2_name.font.size = Pt(10)
r_tr2_name.font.bold = True
r_tr2_name.font.underline = True

r_tr2_sub = p_tr2.add_run("Konsultan TI & Pengembang Web")
r_tr2_sub.font.name = "Times New Roman"
r_tr2_sub.font.size = Pt(9)

# Save Document
target_root = os.path.abspath('MOU_DAN_RAB_WEBSITE_HIDAYATULLAH.docx')
doc.save(target_root)
print(f"Saved to: {target_root}")

target_public = os.path.abspath('public/MOU_DAN_RAB_WEBSITE_HIDAYATULLAH.docx')
doc.save(target_public)
print(f"Saved to: {target_public}")

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsbRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_registrasi',
        'jalur',
        'bukti_transfer',
        'bukti_prestasi_tahfidz',
        'nama_lengkap',
        'pas_foto',
        'file_akta_kelahiran',
        'file_kk',
        'file_ktp_ortu',
        'foto_status',
        'foto_catatan',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'nomor_kk',
        'anak_ke',
        'jumlah_saudara',
        'hobi',
        'alamat_lengkap',
        'bantuan_sosial',
        'bukti_bantuan_sosial',
        'jenjang',
        'jenis_kelamin',
        'nama_wali',
        'no_whatsapp',
        'asal_sekolah',
        'nama_sekolah',
        'alamat_sekolah',
        'tahun_lulus',
        'ayah_nama',
        'ayah_nik',
        'ayah_tahun_lahir',
        'ayah_alamat',
        'ayah_telepon',
        'ayah_pendidikan',
        'ayah_pekerjaan',
        'ayah_penghasilan',
        'ibu_nama',
        'ibu_nik',
        'ibu_tahun_lahir',
        'ibu_alamat',
        'ibu_telepon',
        'ibu_pendidikan',
        'ibu_pekerjaan',
        'ibu_penghasilan',
        'wali_hubungan',
        'wali_nama',
        'wali_nik',
        'wali_tahun_lahir',
        'wali_alamat',
        'wali_telepon',
        'wali_pendidikan',
        'wali_pekerjaan',
        'wali_penghasilan',
        'alamat',
        'status',
        'status_ujian',
        'cbt_attempts_count',
        'nilai_ujian',
        'ujian_selesai_at',
        'pelanggaran_curang_count',
        'pelanggaran_curang_log',
        'status_kelulusan_override',
        'catatan_penguji',
        'jawaban_santri_json',
        'cbt_last_activity_at',
        'cbt_extra_time_minutes',
        'cbt_proctor_message',
        'cbt_ragu_json',
        'cbt_current_question_index',
        'cbt_soal_urutan_json',
        'cbt_nilai_per_mapel_json',
        'status_pembayaran',
        'nominal_pembayaran',
        'metode_pembayaran',
        'tanggal_bayar',
        'catatan_pembayaran',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'ujian_selesai_at' => 'datetime',
        'cbt_last_activity_at' => 'datetime',
        'cbt_attempts_count' => 'integer',
        'cbt_extra_time_minutes' => 'integer',
        'cbt_current_question_index' => 'integer',
        'pelanggaran_curang_count' => 'integer',
        'nilai_ujian' => 'integer',
    ];

    /**
     * Dapatkan status kelulusan (otomatis berdasarkan KKM atau hasil override dewan penguji)
     */
    public function getStatusKelulusanAttribute(): string
    {
        if (!empty($this->status_kelulusan_override)) {
            return $this->status_kelulusan_override;
        }

        if ($this->status_ujian === 'Selesai' && $this->nilai_ujian !== null) {
            $kkm = (int) Setting::get('cbt_passing_grade', 70);
            return $this->nilai_ujian >= $kkm ? 'Lulus' : 'Tidak Lulus';
        }

        return 'Belum Ada Hasil';
    }

    /**
     * Evaluasi pemenuhan syarat kelulusan/penerimaan santri baru
     * Kriteria resmi: Nilai CBT >= KKM (Nilai Tinggi) DAN Bukti Transfer Pembayaran terunggah
     */
    public function evaluasiSyaratPenerimaan(): array
    {
        $kkm = (int) Setting::get('cbt_passing_grade', 70);
        $sudahBayar = !empty($this->bukti_transfer);
        $sudahUjian = ($this->status_ujian === 'Selesai' && $this->nilai_ujian !== null);
        $nilaiTinggi = $sudahUjian && ($this->nilai_ujian >= $kkm);

        $memenuhi = $sudahBayar && $nilaiTinggi;

        $alasan = [];
        if (!$sudahBayar) {
            $alasan[] = 'Bukti pembayaran pendaftaran belum diunggah.';
        }
        if (!$sudahUjian) {
            $alasan[] = 'Belum menyelesaikan ujian seleksi masuk (CBT Online).';
        } elseif (!$nilaiTinggi) {
            $alasan[] = "Nilai ujian CBT ({$this->nilai_ujian}) belum mencapai standar KKM ({$kkm}).";
        }

        return [
            'memenuhi' => $memenuhi,
            'sudah_bayar' => $sudahBayar,
            'sudah_ujian' => $sudahUjian,
            'nilai_tinggi' => $nilaiTinggi,
            'kkm' => $kkm,
            'nilai_ujian' => $this->nilai_ujian,
            'alasan' => $alasan,
        ];
    }

    /**
     * Aksesor apakah santri memenuhi syarat penerimaan otomatis
     */
    public function getMemenuhiSyaratPenerimaanAttribute(): bool
    {
        return $this->evaluasiSyaratPenerimaan()['memenuhi'];
    }

    /**
     * Dapatkan log pelanggaran kecurangan dalam format array
     */
    public function getPelanggaranLogArrayAttribute(): array
    {
        if (empty($this->pelanggaran_curang_log)) {
            return [];
        }
        $decoded = json_decode($this->pelanggaran_curang_log, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Dapatkan rincian nilai per mata pelajaran dalam format array
     */
    public function getNilaiPerMapelArrayAttribute(): array
    {
        if (!empty($this->cbt_nilai_per_mapel_json)) {
            $decoded = json_decode($this->cbt_nilai_per_mapel_json, true);
            if (is_array($decoded) && !empty($decoded)) {
                return $decoded;
            }
        }

        // Jika belum ada di cbt_nilai_per_mapel_json, hitung dinamis dari jawaban_santri_json
        $rawAnswers = !empty($this->jawaban_santri_json) ? json_decode($this->jawaban_santri_json, true) : [];
        if (empty($rawAnswers)) {
            return [];
        }

        $qIds = array_keys($rawAnswers);
        $questions = Question::whereIn('id', $qIds)->get()->keyBy('id');
        $kkm = (int) Setting::get('cbt_passing_grade', 70);

        $perMapel = [];
        foreach ($rawAnswers as $qId => $ans) {
            $q = $questions[$qId] ?? null;
            $kategori = $ans['kategori'] ?? ($q ? $q->kategori : 'Umum');
            $bobot = $q ? max(1, (int)$q->bobot) : 1;
            $isBenar = !empty($ans['benar']);

            if (!isset($perMapel[$kategori])) {
                $perMapel[$kategori] = [
                    'kategori' => $kategori,
                    'total_soal' => 0,
                    'benar' => 0,
                    'salah' => 0,
                    'bobot_earned' => 0,
                    'bobot_max' => 0,
                ];
            }

            $perMapel[$kategori]['total_soal']++;
            $perMapel[$kategori]['bobot_max'] += $bobot;
            if ($isBenar) {
                $perMapel[$kategori]['benar']++;
                $perMapel[$kategori]['bobot_earned'] += $bobot;
            } else {
                $perMapel[$kategori]['salah']++;
            }
        }

        foreach ($perMapel as $k => &$stat) {
            $stat['skor'] = $stat['bobot_max'] > 0 ? round(($stat['bobot_earned'] / $stat['bobot_max']) * 100) : 0;
            $stat['kkm'] = $kkm;
            $stat['status'] = $stat['skor'] >= $kkm ? 'Tuntas' : 'Belum Tuntas';
        }

        return $perMapel;
    }

    /**
     * Relasi ke Santri Aktif jika sudah diimpor/didaftarkan.
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'psb_registration_id');
    }

    /**
     * Relasi ke riwayat transaksi pembayaran PSB.
     */
    public function payments()
    {
        return $this->hasMany(StudentPayment::class, 'psb_registration_id');
    }

    /**
     * Hitung struktur tarif resmi berdasarkan jenjang & hunian (Mukim vs Laju).
     * Dinamis membaca konfigurasi tarif resmi yang diatur Bendahara di Pengaturan Tarif / Web.
     */
    public static function getTarifBreakdown($jenjang)
    {
        $jenjangUpper = strtoupper($jenjang ?? '');
        $isMa = str_contains($jenjangUpper, 'MA');
        $isLaju = str_contains($jenjangUpper, 'LAJU');

        $jenjangLabel = $isMa ? 'MA' : 'MTs';
        $hunianLabel = $isLaju ? 'Laju' : 'Mukim';
        $fullLabel = "{$jenjangLabel} " . ($isLaju ? 'Laju (Non-Asrama)' : 'Mukim (Asrama)');

        $fieldKey = ($isMa ? 'ma_' : 'mts_') . ($isLaju ? 'laju' : 'mukim');

        // 1. Ambil Data Tarif Biaya Awal / Daftar Ulang Dinamis
        $rawBiayaAwal = Setting::get('biaya_awal_json');
        $biayaAwalList = json_decode($rawBiayaAwal ?? '[]', true) ?: [];

        $itemsDaftarUlang = [];
        $totalMasuk = 0;
        $biayaPendaftaran = 200000;

        if (!empty($biayaAwalList)) {
            foreach ($biayaAwalList as $row) {
                if (!empty($row['is_total'])) continue;
                $nama = trim($row['komponen'] ?? '');
                if (empty($nama)) continue;

                $valStr = $row[$fieldKey] ?? '0';
                $nom = (float) preg_replace('/[^0-9]/', '', $valStr);

                $itemsDaftarUlang[] = [
                    'nama' => $nama,
                    'nominal' => $nom,
                    'keterangan' => ($isLaju && stripos($nama, 'almari') !== false) ? 'Khusus Santri Mukim' : null,
                ];
                $totalMasuk += $nom;

                if (stripos($nama, 'pendaftaran') !== false && $nom > 0) {
                    $biayaPendaftaran = $nom;
                }
            }
            $sisaDaftarUlang = max(0, $totalMasuk - $biayaPendaftaran);
        } else {
            // Standar brosur resmi sebagai fallback
            $kts = 60000;
            $pangkal = $isMa 
                ? ($isLaju ? 1800000 : 1400000) 
                : ($isLaju ? 1500000 : 1200000);
            $kertas = 200000;
            $syahriahJuli = $isMa 
                ? ($isLaju ? 100000 : 430000) 
                : ($isLaju ? 80000 : 410000);
            $kesehatan = 200000;
            $kegiatan = 300000;
            $almari = $isLaju ? 0 : 350000;
            $gedung = 500000;
            $pendaftaran = 200000;

            $totalMasuk = $kts + $pangkal + $kertas + $syahriahJuli + $kesehatan + $kegiatan + $almari + $gedung + $pendaftaran;
            $sisaDaftarUlang = $totalMasuk - $pendaftaran;
            $biayaPendaftaran = $pendaftaran;

            $itemsDaftarUlang = [
                ['nama' => 'Santri Baru KTS', 'nominal' => $kts],
                ['nama' => 'Uang Pangkal Masuk', 'nominal' => $pangkal],
                ['nama' => 'Kertas / Evaluasi Belajar (1 Th)', 'nominal' => $kertas],
                ['nama' => 'Syahriah Bulan Juli', 'nominal' => $syahriahJuli],
                ['nama' => 'Kesehatan Santri (1 Th)', 'nominal' => $kesehatan],
                ['nama' => 'Kegiatan Santri (1 Th)', 'nominal' => $kegiatan],
                ['nama' => 'Pembelian Almari', 'nominal' => $almari, 'keterangan' => $isLaju ? 'Khusus Santri Mukim' : 'Fasilitas Kamar'],
                ['nama' => 'Uang Gedung & Sarpras', 'nominal' => $gedung],
                ['nama' => 'Biaya Pendaftaran PSB', 'nominal' => $pendaftaran],
            ];
        }

        // 2. Ambil Data Tarif Iuran Bulanan Dinamis
        $rawBiayaBulanan = Setting::get('biaya_bulanan_json');
        $biayaBulananList = json_decode($rawBiayaBulanan ?? '[]', true) ?: [];

        $itemsBulanan = [];
        $totalBulanan = 0;

        if (!empty($biayaBulananList)) {
            foreach ($biayaBulananList as $row) {
                if (!empty($row['is_total'])) continue;
                $nama = trim($row['komponen'] ?? '');
                if (empty($nama)) continue;

                $valStr = $row[$fieldKey] ?? '0';
                $nom = (float) preg_replace('/[^0-9]/', '', $valStr);

                $itemsBulanan[] = [
                    'nama' => $nama,
                    'nominal' => $nom,
                ];
                $totalBulanan += $nom;
            }
        } else {
            $uangMakan = $isLaju ? 0 : 300000;
            $syahriahBulanan = $isMa 
                ? ($isLaju ? 75000 : 105000) 
                : ($isLaju ? 55000 : 85000);
            $tabunganWajib = 25000;
            $totalBulanan = $uangMakan + $syahriahBulanan + $tabunganWajib;

            $itemsBulanan = [
                ['nama' => 'Uang Makan 3x Sehari', 'nominal' => $uangMakan],
                ['nama' => 'Syahriah Pendidikan', 'nominal' => $syahriahBulanan],
                ['nama' => 'Tabungan Wajib Santri', 'nominal' => $tabunganWajib],
            ];
        }

        return [
            'jenjang_short' => $jenjangLabel,
            'hunian' => $hunianLabel,
            'kategori_label' => $fullLabel,
            'biaya_pendaftaran' => $biayaPendaftaran,
            'total_biaya_masuk' => $totalMasuk,
            'sisa_daftar_ulang' => $sisaDaftarUlang,
            'total_bulanan' => $totalBulanan,
            'items_daftar_ulang' => $itemsDaftarUlang,
            'items_bulanan' => $itemsBulanan,
        ];
    }

    public function getTarifDetailsAttribute()
    {
        return static::getTarifBreakdown($this->jenjang);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->no_registrasi) {
                $academicYear = Setting::get('tahun_ajaran', date('Y'));
                $yearCode = '26';
                if (preg_match('/20(\d{2})/', $academicYear, $matches)) {
                    $yearCode = $matches[1];
                } elseif (date('y')) {
                    $yearCode = date('y');
                }
                $prefix = "PSB-{$yearCode}-";
                $count = static::where('no_registrasi', 'like', "{$prefix}%")->count() + 1;
                $model->no_registrasi = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
            if (!$model->nama_wali) {
                $model->nama_wali = $model->ayah_nama ?: $model->ibu_nama ?: $model->wali_nama ?: '-';
            }
            if (!$model->no_whatsapp) {
                $model->no_whatsapp = $model->ayah_telepon ?: $model->ibu_telepon ?: $model->wali_telepon ?: '-';
            }
            if (!$model->asal_sekolah) {
                $model->asal_sekolah = $model->nama_sekolah ?: '-';
            }
            if (!$model->alamat) {
                $model->alamat = $model->alamat_lengkap ?: '-';
            }
        });
    }
}

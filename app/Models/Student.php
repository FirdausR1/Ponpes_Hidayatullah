<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'psb_registration_id',
        'nis',
        'username',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'nik',
        'nomor_kk',
        'anak_ke',
        'jumlah_saudara',
        'golongan_darah',
        'hobi',
        'riwayat_penyakit',
        'alamat_lengkap',
        'jenjang',
        'kelas',
        'kamar_asrama',
        'dormitory_id',
        'tahun_masuk',
        'status',
        'nama_sekolah',
        'alamat_sekolah',
        'tahun_lulus',
        'ayah_nama',
        'ayah_nik',
        'ayah_telepon',
        'ayah_pekerjaan',
        'ayah_penghasilan',
        'ayah_pendidikan',
        'ibu_nama',
        'ibu_nik',
        'ibu_telepon',
        'ibu_pekerjaan',
        'ibu_penghasilan',
        'ibu_pendidikan',
        'nama_wali',
        'wali_nik',
        'wali_telepon',
        'wali_hubungan',
        'wali_pekerjaan',
        'wali_penghasilan',
        'no_whatsapp',
        'alamat',
        'foto',
        'file_kk',
        'file_ktp_ortu',
        'file_akta_kelahiran',
        'catatan',
        'password',
        'last_login_at',
    ];

    /**
     * Relasi ke riwayat pembayaran santri
     */
    public function payments()
    {
        return $this->hasMany(StudentPayment::class, 'student_id')->latest();
    }

    /**
     * Relasi ke seluruh tagihan santri (SPP bulanan, daftar ulang, ziarah, wisuda, dll.)
     */
    public function bills()
    {
        return $this->hasMany(StudentBill::class, 'student_id')->latest();
    }

    /**
     * Relasi ke potongan / beasiswa / SKTM santri
     */
    public function discounts()
    {
        return $this->hasMany(StudentDiscount::class, 'student_id');
    }

    /**
     * Relasi ke riwayat mutasi santri
     */
    public function mutations()
    {
        return $this->hasMany(StudentMutation::class, 'student_id')->latest('tanggal_mutasi');
    }

    /**
     * Relasi ke catatan mutasi terakhir
     */
    public function latestMutation()
    {
        return $this->hasOne(StudentMutation::class, 'student_id')->latestOfMany('tanggal_mutasi');
    }

    /**
     * Total tagihan santri saat ini.
     */
    public function getTotalTagihanAttribute(): float
    {
        return (float) $this->bills()->sum('nominal_tagihan');
    }

    /**
     * Total yang sudah dibayar oleh santri.
     */
    public function getTotalTerbayarAttribute(): float
    {
        return (float) $this->bills()->sum('nominal_bayar');
    }

    /**
     * Total sisa tunggakan santri aktif (tidak termasuk penangguhan wisuda).
     */
    public function getTotalTunggakanAttribute(): float
    {
        return (float) $this->bills()
            ->where('status', '!=', 'Lunas')
            ->where('penangguhan_wisuda', false)
            ->sum('sisa_tagihan');
    }

    /**
     * Total saldo tabungan santri (Setoran TAB - Penarikan AMBIL TABUNGAN).
     */
    public function getSaldoTabunganAttribute(): float
    {
        $setoran = \App\Models\StudentPaymentItem::whereHas('payment', function($q) {
            $q->where('student_id', $this->id)->where('status', 'Lunas');
        })->whereIn('pos_biaya', ['TAB', 'TABUNGAN', 'Tabungan Wajib', 'TABUNGAN WAJIB'])->sum('nominal');

        $tarikan = \App\Models\StudentPaymentItem::whereHas('payment', function($q) {
            $q->where('student_id', $this->id)->where('status', 'Lunas');
        })->whereIn('pos_biaya', ['AMBIL TABUNGAN', 'TARIK TABUNGAN', 'Pengambilan Tabungan'])->sum('nominal');

        return (float) ($setoran - $tarikan);
    }

    /**
     * Dapatkan jenjang ringkas santri (MTs atau MA)
     */
    public function getJenjangShortAttribute(): string
    {
        $str = strtoupper($this->jenjang ?? '');
        if (str_contains($str, 'MA')) {
            return 'MA';
        }
        return 'MTs';
    }

    /**
     * Dapatkan status hunian santri (Mukim atau Laju)
     */
    public function getHunianAttribute(): string
    {
        $jenjangStr = strtolower($this->jenjang ?? '');
        if (str_contains($jenjangStr, 'laju')) {
            return 'Laju';
        }
        if (str_contains($jenjangStr, 'mukim') || str_contains($jenjangStr, 'pondok')) {
            return 'Mukim';
        }
        if (!empty($this->dormitory_id)) {
            return 'Mukim';
        }
        $asramaStr = strtolower($this->kamar_asrama ?? '');
        if (!empty($asramaStr) && !str_contains($asramaStr, 'laju') && !str_contains($asramaStr, 'non-asrama') && $asramaStr !== '—' && $asramaStr !== '-') {
            return 'Mukim';
        }
        return 'Laju';
    }

    /**
     * Dapatkan label kategori lengkap (e.g. MTs Mukim (Asrama) atau MA Laju (Non-Asrama))
     */
    public function getKategoriLabelAttribute(): string
    {
        $jenjang = $this->jenjang_short;
        $hunian = $this->hunian;
        return $jenjang . ' ' . $hunian . ($hunian === 'Mukim' ? ' (Asrama)' : ' (Non-Asrama)');
    }

    /**
     * Rincian tarif bulanan resmi sesuai Brosur SK Pondok Pesantren
     * Tabel 2: Uang Makan 3X, Syahriah, Tabungan Wajib
     */
    public function getTarifBulananAttribute(): array
    {
        return self::getTarifBulananByKategori($this->jenjang_short, $this->hunian);
    }

    /**
     * Helper statis kalkulasi tarif bulanan resmi
     */
    public static function getTarifBulananByKategori(string $jenjang, string $hunian): array
    {
        $isMA = strtoupper($jenjang) === 'MA' || str_contains(strtoupper($jenjang), 'MA');
        $isMukim = strtolower($hunian) === 'mukim' || str_contains(strtolower($hunian), 'mukim');

        if ($isMA) {
            if ($isMukim) {
                return [
                    'jenjang' => 'MA',
                    'hunian' => 'Mukim',
                    'kategori_label' => 'MA Mukim (Asrama)',
                    'uang_makan' => 300000,
                    'syahriah' => 30000,
                    'sot' => 75000,
                    'tabungan_wajib' => 25000,
                    'total_bulanan' => 430000,
                    'items' => [
                        ['nama' => 'Uang Makan 3x Sehari', 'nominal' => 300000],
                        ['nama' => 'Syahriah Pendidikan', 'nominal' => 30000],
                        ['nama' => 'Iuran SOT', 'nominal' => 75000],
                        ['nama' => 'Tabungan Wajib Santri', 'nominal' => 25000],
                    ],
                ];
            } else {
                return [
                    'jenjang' => 'MA',
                    'hunian' => 'Laju',
                    'kategori_label' => 'MA Laju (Non-Asrama)',
                    'uang_makan' => 0,
                    'syahriah' => 0,
                    'sot' => 75000,
                    'tabungan_wajib' => 25000,
                    'total_bulanan' => 100000,
                    'items' => [
                        ['nama' => 'Uang Makan 3x Sehari', 'nominal' => 0, 'keterangan' => 'Santri Laju'],
                        ['nama' => 'Iuran SOT', 'nominal' => 75000],
                        ['nama' => 'Tabungan Wajib Santri', 'nominal' => 25000],
                    ],
                ];
            }
        } else {
            if ($isMukim) {
                return [
                    'jenjang' => 'MTs',
                    'hunian' => 'Mukim',
                    'kategori_label' => 'MTs Mukim (Asrama)',
                    'uang_makan' => 300000,
                    'syahriah' => 30000,
                    'sot' => 55000,
                    'tabungan_wajib' => 25000,
                    'total_bulanan' => 410000,
                    'items' => [
                        ['nama' => 'Uang Makan 3x Sehari', 'nominal' => 300000],
                        ['nama' => 'Syahriah Pendidikan', 'nominal' => 30000],
                        ['nama' => 'Iuran SOT', 'nominal' => 55000],
                        ['nama' => 'Tabungan Wajib Santri', 'nominal' => 25000],
                    ],
                ];
            } else {
                return [
                    'jenjang' => 'MTs',
                    'hunian' => 'Laju',
                    'kategori_label' => 'MTs Laju (Non-Asrama)',
                    'uang_makan' => 0,
                    'syahriah' => 0,
                    'sot' => 55000,
                    'tabungan_wajib' => 25000,
                    'total_bulanan' => 80000,
                    'items' => [
                        ['nama' => 'Uang Makan 3x Sehari', 'nominal' => 0, 'keterangan' => 'Santri Laju'],
                        ['nama' => 'Iuran SOT', 'nominal' => 55000],
                        ['nama' => 'Tabungan Wajib Santri', 'nominal' => 25000],
                    ],
                ];
            }
        }
    }



    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'last_login_at' => 'datetime',
    ];

    /**
     * Relasi ke pendaftaran PSB asal
     */
    public function psbRegistration()
    {
        return $this->belongsTo(PsbRegistration::class, 'psb_registration_id');
    }

    /**
     * Relasi ke kamar asrama
     */
    public function dormitory()
    {
        return $this->belongsTo(Dormitory::class, 'dormitory_id');
    }

    /**
     * Relasi ke data rombel kelas & wali kelas
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'kelas', 'nama_kelas');
    }


    /**
     * Dapatkan format tampilan tanggal lahir yang aman (mendukung d/m/Y, Y-m-d, d-m-Y, dsb.)
     */
    public function getTanggalLahirFormattedAttribute(): string
    {
        if (empty($this->tanggal_lahir)) {
            return '—';
        }

        try {
            $rawTgl = trim($this->tanggal_lahir);
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $rawTgl)) {
                return Carbon::createFromFormat('d/m/Y', $rawTgl)->translatedFormat('d M Y');
            }
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $rawTgl, $m)) {
                return Carbon::createFromDate((int)$m[3], (int)$m[2], (int)$m[1])->translatedFormat('d M Y');
            }
            return Carbon::parse($rawTgl)->translatedFormat('d M Y');
        } catch (\Throwable $e) {
            return explode(' ', trim($this->tanggal_lahir))[0];
        }
    }

    /**
     * Dapatkan format tanggal lahir standar untuk password default santri (DDMMYYYY)
     */
    public function getFormattedBirthdatePassword(): string
    {
        if (empty($this->tanggal_lahir)) {
            return 'santri123';
        }

        try {
            $rawTgl = trim($this->tanggal_lahir);
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $rawTgl)) {
                return Carbon::createFromFormat('d/m/Y', $rawTgl)->format('dmY');
            }
            $parsed = Carbon::parse($rawTgl);
            return $parsed->format('dmY'); // Contoh: 15052010
        } catch (\Throwable $e) {
            // Jika format tanggal disimpan sebagai string langsung bersihkan karakter non-angka
            $clean = preg_replace('/[^0-9]/', '', $this->tanggal_lahir);
            return !empty($clean) ? $clean : 'santri123';
        }
    }

    /**
     * Dapatkan file Kartu Keluarga (KK), fallback ke berkas PSB jika ada
     */
    public function getBerkasKkAttribute(): ?string
    {
        return $this->file_kk ?: ($this->psbRegistration?->file_kk ?? null);
    }

    /**
     * Dapatkan file KTP Orang Tua, fallback ke berkas PSB jika ada
     */
    public function getBerkasKtpOrtuAttribute(): ?string
    {
        return $this->file_ktp_ortu ?: ($this->psbRegistration?->file_ktp_ortu ?? null);
    }

    /**
     * Dapatkan file Akta Kelahiran, fallback ke berkas PSB jika ada
     */
    public function getBerkasAktaAttribute(): ?string
    {
        return $this->file_akta_kelahiran ?: ($this->psbRegistration?->file_akta_kelahiran ?? null);
    }
}


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
     * Total sisa tunggakan santri.
     */
    public function getTotalTunggakanAttribute(): float
    {
        return (float) $this->bills()->where('status', '!=', 'Lunas')->sum('sisa_tagihan');
    }

    /**
     * Total saldo tabungan santri (Setoran TAB - Penarikan AMBIL TABUNGAN).
     */
    public function getSaldoTabunganAttribute(): float
    {
        $setoran = \App\Models\StudentPaymentItem::whereHas('payment', function($q) {
            $q->where('student_id', $this->id)->where('status', 'Lunas');
        })->where('pos_biaya', 'TAB')->sum('nominal');

        $tarikan = \App\Models\StudentPaymentItem::whereHas('payment', function($q) {
            $q->where('student_id', $this->id)->where('status', 'Lunas');
        })->where('pos_biaya', 'AMBIL TABUNGAN')->sum('nominal');

        return (float) ($setoran - $tarikan);
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
     * Dapatkan format tanggal lahir standar untuk password default santri (DDMMYYYY)
     */
    public function getFormattedBirthdatePassword(): string
    {
        if (empty($this->tanggal_lahir)) {
            return 'santri123';
        }

        try {
            $parsed = Carbon::parse($this->tanggal_lahir);
            return $parsed->format('dmY'); // Contoh: 15052010
        } catch (\Throwable $e) {
            // Jika format tanggal disimpan sebagai string langsung bersihkan karakter non-angka
            $clean = preg_replace('/[^0-9]/', '', $this->tanggal_lahir);
            return !empty($clean) ? $clean : 'santri123';
        }
    }
}


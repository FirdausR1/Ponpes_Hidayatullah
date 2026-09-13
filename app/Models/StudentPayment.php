<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'psb_registration_id',
        'user_id',
        'no_transaksi',
        'jenis_pembayaran',
        'bulan',
        'tahun',
        'nominal',
        'tanggal_bayar',
        'metode_pembayaran',
        'status',
        'bukti_bayar',
        'catatan',
        'penerima_nama',
    ];

    protected $casts = [
        'nominal' => 'float',
        'tanggal_bayar' => 'date',
    ];

    /**
     * Relasi ke user / bendahara pembuat transaksi.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke santri pembayar.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Relasi ke pendaftaran santri baru (PSB).
     */
    public function psbRegistration()
    {
        return $this->belongsTo(PsbRegistration::class, 'psb_registration_id');
    }

    /**
     * Relasi ke rincian pos biaya yang dibayar dalam transaksi ini.
     */
    public function items()
    {
        return $this->hasMany(StudentPaymentItem::class, 'payment_id');
    }

    /**
     * Format nominal Rupiah.
     */
    public function getFormattedNominalAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'kategori',
        'pos_biaya',
        'judul_tagihan',
        'bulan',
        'tahun',
        'nominal_asli',
        'nominal_potongan',
        'alasan_potongan',
        'nominal_tagihan',
        'nominal_bayar',
        'sisa_tagihan',
        'status',
        'jatuh_tempo',
        'created_by',
        'penangguhan_wisuda',
        'catatan_penangguhan',
        'ditangguhkan_at',
        'catatan_pembebasan',
        'status_dispensasi',
        'jenis_surat_diminta',
        'instruksi_surat',
        'file_surat_dispensasi',
        'surat_diminta_at',
        'surat_uploaded_at',
        'alasan_penolakan_surat',
    ];

    protected $casts = [
        'nominal_asli' => 'float',
        'nominal_potongan' => 'float',
        'nominal_tagihan' => 'float',
        'nominal_bayar' => 'float',
        'sisa_tagihan' => 'float',
        'jatuh_tempo' => 'date',
        'penangguhan_wisuda' => 'boolean',
        'ditangguhkan_at' => 'datetime',
        'surat_diminta_at' => 'datetime',
        'surat_uploaded_at' => 'datetime',
    ];

    /**
     * Relasi ke santri pemilik tagihan.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Relasi ke item pembayaran yang melunasi tagihan ini.
     */
    public function paymentItems()
    {
        return $this->hasMany(StudentPaymentItem::class, 'student_bill_id');
    }

    /**
     * Format Rupiah untuk nominal tagihan.
     */
    public function getFormattedTagihanAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal_tagihan, 0, ',', '.');
    }

    public function getFormattedBayarAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal_bayar, 0, ',', '.');
    }

    public function getFormattedSisaAttribute(): string
    {
        return 'Rp ' . number_format($this->sisa_tagihan, 0, ',', '.');
    }

    public function getFormattedPotonganAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal_potongan, 0, ',', '.');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMutation extends Model
{
    use HasFactory;

    protected $table = 'student_mutations';

    protected $fillable = [
        'student_id',
        'jenis_mutasi',
        'tanggal_mutasi',
        'alasan',
        'kelas_dari',
        'sekolah_asal_tujuan',
        'kelas_ke',
        'status_sebelumnya',
        'keterangan',
        'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];

    /**
     * Relasi ke santri yang dimutasi.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Label jenis mutasi dengan warna badge.
     */
    public function getJenisBadgeClassAttribute(): string
    {
        return $this->jenis_mutasi === 'Keluar' ? 'badge-error' : 'badge-success';
    }

    /**
     * Daftar alasan mutasi yang tersedia.
     */
    public static function alasanOptions(): array
    {
        return [
            'Pindah Sekolah'        => 'Pindah Sekolah',
            'Drop Out (DO)'         => 'Drop Out (DO)',
            'Meninggal Dunia'       => 'Meninggal Dunia',
            'Mengundurkan Diri'     => 'Mengundurkan Diri',
            'Habis Masa Studi'      => 'Habis Masa Studi',
            'Lainnya'               => 'Lainnya',
        ];
    }
}

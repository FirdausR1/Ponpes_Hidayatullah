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
        'user_id',
        'nama_santri',
        'nis',
        'jenjang',
        'jenis_mutasi',
        'tanggal_mutasi',
        'alasan',
        'kelas_dari',
        'kamar_dari',
        'sekolah_asal_tujuan',
        'kelas_ke',
        'kamar_ke',
        'status_sebelumnya',
        'keterangan',
        'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];

    /**
     * Relasi ke data santri utama.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Relasi ke admin / user yang mencatat mutasi.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke kelas asal santri.
     */
    public function classroomDari()
    {
        return $this->belongsTo(Classroom::class, 'kelas_dari', 'nama_kelas');
    }

    /**
     * Relasi ke kelas tujuan santri (untuk mutasi masuk).
     */
    public function classroomKe()
    {
        return $this->belongsTo(Classroom::class, 'kelas_ke', 'nama_kelas');
    }

    /**
     * Snapshot nama santri yang aman jika data santri terhapus.
     */
    public function getNamaSantriDisplayAttribute(): string
    {
        return $this->student->nama_lengkap ?? $this->nama_santri ?? 'Santri';
    }

    /**
     * Snapshot NIS santri.
     */
    public function getNisDisplayAttribute(): string
    {
        return $this->student->nis ?? $this->nis ?? '-';
    }

    /**
     * Snapshot Jenjang santri.
     */
    public function getJenjangDisplayAttribute(): string
    {
        return $this->student->jenjang ?? $this->jenjang ?? '-';
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

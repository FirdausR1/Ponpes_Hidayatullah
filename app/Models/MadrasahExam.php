<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MadrasahExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ujian',
        'mata_pelajaran',
        'nama_guru',
        'jenjang',
        'tingkat_kelas',
        'jurusan',
        'tahun_ajaran',
        'semester',
        'jumlah_soal',
        'durasi_menit',
        'max_attempts',
        'max_violations',
        'token_ujian',
        'acak_soal',
        'acak_opsi',
        'tampilkan_nilai',
        'kkm',
        'tanggal_ujian',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_soal' => 'integer',
        'durasi_menit' => 'integer',
        'max_attempts' => 'integer',
        'max_violations' => 'integer',
        'acak_soal' => 'boolean',
        'acak_opsi' => 'boolean',
        'tampilkan_nilai' => 'boolean',
        'kkm' => 'decimal:2',
        'tanggal_ujian' => 'date',
    ];

    public function questions()
    {
        return $this->hasMany(MadrasahExamQuestion::class, 'madrasah_exam_id')->orderBy('nomor_urut', 'asc');
    }

    public function results()
    {
        return $this->hasMany(MadrasahExamResult::class, 'madrasah_exam_id')->orderBy('nilai', 'desc')->orderBy('nama_peserta', 'asc');
    }

    /**
     * Statistik Nilai Tertinggi
     */
    public function getNilaiTertinggiAttribute(): float
    {
        return (float) ($this->results()->max('nilai') ?? 0);
    }

    /**
     * Statistik Nilai Terendah
     */
    public function getNilaiTerendahAttribute(): float
    {
        return (float) ($this->results()->min('nilai') ?? 0);
    }

    /**
     * Statistik Nilai Rata-rata
     */
    public function getNilaiRataRataAttribute(): float
    {
        $avg = $this->results()->avg('nilai');
        return $avg !== null ? round((float) $avg, 2) : 0.00;
    }

    /**
     * Total Peserta Ujian
     */
    public function getTotalPesertaAttribute(): int
    {
        return $this->results()->count();
    }
}

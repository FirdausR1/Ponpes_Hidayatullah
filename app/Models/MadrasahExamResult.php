<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MadrasahExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'madrasah_exam_id',
        'student_id',
        'nomor_peserta',
        'nama_peserta',
        'kelas',
        'jurusan',
        'jumlah_soal',
        'jumlah_benar',
        'jumlah_salah',
        'nilai',
        'status',
        'catatan',
    ];

    protected $casts = [
        'jumlah_soal' => 'integer',
        'jumlah_benar' => 'integer',
        'jumlah_salah' => 'integer',
        'nilai' => 'decimal:2',
    ];

    public function exam()
    {
        return $this->belongsTo(MadrasahExam::class, 'madrasah_exam_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Hitung nilai otomatis berdasarkan jumlah benar & jumlah soal
     */
    public static function hitungNilai(int $benar, int $totalSoal): float
    {
        if ($totalSoal <= 0) {
            return 0.00;
        }
        return round(($benar / $totalSoal) * 100, 2);
    }
}

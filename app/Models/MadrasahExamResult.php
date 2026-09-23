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
        'attempt_number',
        'waktu_mulai',
        'waktu_selesai',
        'sisa_detik',
        'answers_json',
        'ragu_ragu_json',
        'jumlah_pelanggaran',
        'log_pelanggaran_json',
        'catatan',
    ];

    protected $casts = [
        'jumlah_soal' => 'integer',
        'jumlah_benar' => 'integer',
        'jumlah_salah' => 'integer',
        'nilai' => 'decimal:2',
        'attempt_number' => 'integer',
        'sisa_detik' => 'integer',
        'jumlah_pelanggaran' => 'integer',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function getAnswersArrayAttribute(): array
    {
        if (empty($this->answers_json)) {
            return [];
        }
        $arr = json_decode($this->answers_json, true);
        return is_array($arr) ? $arr : [];
    }

    public function getRaguArrayAttribute(): array
    {
        if (empty($this->ragu_ragu_json)) {
            return [];
        }
        $arr = json_decode($this->ragu_ragu_json, true);
        return is_array($arr) ? $arr : [];
    }

    public function getLogPelanggaranArrayAttribute(): array
    {
        if (empty($this->log_pelanggaran_json)) {
            return [];
        }
        $arr = json_decode($this->log_pelanggaran_json, true);
        return is_array($arr) ? $arr : [];
    }

    /**
     * Hitung berapa banyak soal yang sudah dijawab
     */
    public function getTerjawabCountAttribute(): int
    {
        return count(array_filter($this->answers_array, fn($v) => !empty($v)));
    }

    /**
     * Catat pelanggaran baru (Pindah Tab, Blur, Keluar Fullscreen)
     */
    public function tambahPelanggaran(string $alasan): int
    {
        $logs = $this->log_pelanggaran_array;
        $logs[] = [
            'waktu' => now()->format('H:i:s d/m/Y'),
            'alasan' => $alasan,
        ];

        $this->jumlah_pelanggaran = ($this->jumlah_pelanggaran ?? 0) + 1;
        $this->log_pelanggaran_json = json_encode($logs);
        
        // Cek batas pelanggaran ujian
        $maxViolations = $this->exam->max_violations ?? 3;
        if ($this->jumlah_pelanggaran >= $maxViolations) {
            $this->status = 'terkunci';
        }
        
        $this->save();
        return $this->jumlah_pelanggaran;
    }

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

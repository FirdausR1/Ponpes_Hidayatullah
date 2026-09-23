<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MadrasahExamQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'madrasah_exam_id',
        'nomor_urut',
        'pertanyaan',
        'gambar',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'opsi_e',
        'kunci_jawaban',
        'bobot',
        'pembahasan',
    ];

    protected $casts = [
        'nomor_urut' => 'integer',
        'bobot' => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(MadrasahExam::class, 'madrasah_exam_id');
    }
}

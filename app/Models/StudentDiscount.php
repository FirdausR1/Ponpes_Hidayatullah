<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'jenis_potongan',
        'no_surat_miskin',
        'file_surat_miskin',
        'tipe_nilai',
        'nilai',
        'pos_biaya',
        'berlaku_mulai',
        'berlaku_sampai',
        'status',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'nilai' => 'float',
        'berlaku_mulai' => 'date',
        'berlaku_sampai' => 'date',
    ];

    /**
     * Relasi ke santri penerima keringanan / beasiswa.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Format nilai (Rupiah atau Persen).
     */
    public function getFormattedNilaiAttribute(): string
    {
        if ($this->tipe_nilai === 'persen') {
            return rtrim(rtrim(number_format($this->nilai, 2, ',', '.'), '0'), ',') . '%';
        }
        return 'Rp ' . number_format($this->nilai, 0, ',', '.');
    }
}

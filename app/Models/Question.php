<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori',
        'soal',
        'gambar',
        'is_math',
        'is_arabic',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'opsi_e',
        'kunci_jawaban',
        'bobot',
        'pembahasan',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_math' => 'boolean',
        'is_arabic' => 'boolean',
        'is_active' => 'boolean',
        'bobot' => 'integer',
        'urutan' => 'integer',
    ];

    /**
     * Get options as associative array.
     */
    public function getOptionsAttribute(): array
    {
        $opts = [
            'A' => $this->opsi_a,
            'B' => $this->opsi_b,
            'C' => $this->opsi_c,
            'D' => $this->opsi_d,
        ];
        if (!empty($this->opsi_e)) {
            $opts['E'] = $this->opsi_e;
        }
        return $opts;
    }
}

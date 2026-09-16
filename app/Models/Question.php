<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenjang',
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
     * Bersihkan teks dari karakter escape literal seperti \n, \r\n tanpa merusak command LaTeX (seperti \neq).
     */
    public static function cleanFormattingText(?string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // 1. Amankan command LaTeX resmi yang berawalan \n
        $latexTokens = [
            '\neq'    => '___LATEX_NEQ___',
            '\neg'    => '___LATEX_NEG___',
            '\nabla'  => '___LATEX_NABLA___',
            '\notin'  => '___LATEX_NOTIN___',
            '\nu'     => '___LATEX_NU___',
        ];
        foreach ($latexTokens as $cmd => $token) {
            $text = str_replace($cmd, $token, $text);
        }

        // 2. Ubah literal \r\n, \r, \n menjadi karakter baris baru riil
        $text = str_replace(['\r\n', '\r', '\n'], "\n", $text);

        // 3. Kembalikan command LaTeX
        foreach ($latexTokens as $cmd => $token) {
            $text = str_replace($token, $cmd, $text);
        }

        return $text;
    }

    /**
     * Accessor untuk kolom soal agar selalu bersih saat dibaca.
     */
    public function getSoalAttribute($value): string
    {
        return self::cleanFormattingText($value);
    }

    /**
     * Mutator untuk kolom soal agar tersimpan bersih.
     */
    public function setSoalAttribute($value): void
    {
        $this->attributes['soal'] = self::cleanFormattingText($value);
    }

    /**
     * Accessor untuk opsi dan pembahasan
     */
    public function getPembahasanAttribute($value): ?string
    {
        return $value !== null ? self::cleanFormattingText($value) : null;
    }

    public function setPembahasanAttribute($value): void
    {
        $this->attributes['pembahasan'] = $value !== null ? self::cleanFormattingText($value) : null;
    }

    /**
     * Get options as associative array.
     */
    public function getOptionsAttribute(): array
    {
        $opts = [
            'A' => self::cleanFormattingText($this->opsi_a),
            'B' => self::cleanFormattingText($this->opsi_b),
            'C' => self::cleanFormattingText($this->opsi_c),
            'D' => self::cleanFormattingText($this->opsi_d),
        ];
        if (!empty($this->opsi_e)) {
            $opts['E'] = self::cleanFormattingText($this->opsi_e);
        }
        return $opts;
    }
}

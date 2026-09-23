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
     * Pastikan string UTF-8 valid dan perbaiki double-encoding / mojibake UTF-8 (misal: Ø§Ù„ -> ال)
     */
    public static function fixUtf8Mojibake(?string $str): string
    {
        if ($str === null || $str === '') {
            return '';
        }

        // Jika bukan UTF-8 valid (misal ANSI Windows-1256 atau Windows-1252 dari Excel lama)
        if (!mb_check_encoding($str, 'UTF-8')) {
            $from1256 = @iconv('CP1256', 'UTF-8//IGNORE', $str);
            if ($from1256 !== false && preg_match('/[\x{0600}-\x{06FF}]/u', $from1256)) {
                $str = $from1256;
            } else {
                $str = mb_convert_encoding($str, 'UTF-8', 'Windows-1252');
            }
        }

        // Deteksi pola umum mojibake UTF-8 yang terbaca sebagai Windows-1252 / ISO-8859-1
        if (preg_match('/[ØÙ]/u', $str)) {
            $converted = @iconv('UTF-8', 'Windows-1252//IGNORE', $str);
            if ($converted !== false) {
                $cleaned = @iconv('UTF-8', 'UTF-8//IGNORE', $converted);
                if ($cleaned !== false && preg_match('/[\x{0600}-\x{06FF}]/u', $cleaned)) {
                    return $cleaned;
                }
            }
            $convertedMb = @mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
            if ($convertedMb !== false) {
                $cleanedMb = @iconv('UTF-8', 'UTF-8//IGNORE', $convertedMb);
                if ($cleanedMb !== false && preg_match('/[\x{0600}-\x{06FF}]/u', $cleanedMb)) {
                    return $cleanedMb;
                }
            }
        }

        return $str;
    }

    /**
     * Bersihkan teks dari karakter escape literal seperti \n, \r\n tanpa merusak command LaTeX (seperti \neq),
     * serta otomatis memulihkan teks jika terjadi mojibake encoding.
     */
    public static function cleanFormattingText(?string $text): string
    {
        if (empty($text)) {
            return '';
        }

        $text = self::fixUtf8Mojibake($text);

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
     * Accessor & Mutator untuk kolom soal agar selalu bersih saat dibaca dan disimpan.
     */
    public function getSoalAttribute($value): string
    {
        return self::cleanFormattingText($value);
    }

    public function setSoalAttribute($value): void
    {
        $this->attributes['soal'] = self::cleanFormattingText($value);
    }

    /**
     * Accessor & Mutator untuk opsi pilihan ganda
     */
    public function getOpsiAAttribute($value): ?string
    {
        return $value !== null ? self::cleanFormattingText($value) : null;
    }

    public function setOpsiAAttribute($value): void
    {
        $this->attributes['opsi_a'] = $value !== null ? self::cleanFormattingText($value) : null;
    }

    public function getOpsiBAttribute($value): ?string
    {
        return $value !== null ? self::cleanFormattingText($value) : null;
    }

    public function setOpsiBAttribute($value): void
    {
        $this->attributes['opsi_b'] = $value !== null ? self::cleanFormattingText($value) : null;
    }

    public function getOpsiCAttribute($value): ?string
    {
        return $value !== null ? self::cleanFormattingText($value) : null;
    }

    public function setOpsiCAttribute($value): void
    {
        $this->attributes['opsi_c'] = $value !== null ? self::cleanFormattingText($value) : null;
    }

    public function getOpsiDAttribute($value): ?string
    {
        return $value !== null ? self::cleanFormattingText($value) : null;
    }

    public function setOpsiDAttribute($value): void
    {
        $this->attributes['opsi_d'] = $value !== null ? self::cleanFormattingText($value) : null;
    }

    public function getOpsiEAttribute($value): ?string
    {
        return $value !== null ? self::cleanFormattingText($value) : null;
    }

    public function setOpsiEAttribute($value): void
    {
        $this->attributes['opsi_e'] = $value !== null ? self::cleanFormattingText($value) : null;
    }

    /**
     * Accessor & Mutator untuk pembahasan
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

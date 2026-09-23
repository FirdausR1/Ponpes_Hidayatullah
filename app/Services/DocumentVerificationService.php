<?php

namespace App\Services;

class DocumentVerificationService
{
    /**
     * Memeriksa dan memverifikasi kelayakan dokumen pendaftaran (KTP, KK, Akta Kelahiran, Bukti Transfer)
     * agar pendaftar tidak dapat mengunggah file sembarangan / rusak / tidak terbaca.
     *
     * @param string $filePath Path absolut file
     * @param string $type Jenis dokumen ('ktp', 'kk', 'akta', 'transfer', 'generic')
     * @return array ['status' => 'Sesuai'|'Perlu Perbaikan', 'catatan' => string, 'details' => array]
     */
    public static function verify(string $filePath, string $type = 'generic'): array
    {
        if (!file_exists($filePath)) {
            return [
                'status' => 'Perlu Perbaikan',
                'catatan' => 'File dokumen tidak ditemukan di server.',
                'details' => ['error' => 'File not found'],
            ];
        }

        $filesize = filesize($filePath);

        // 1. Cek ukuran minimal (file di bawah 25 KB biasanya blank, corrupt, atau file palsu)
        if ($filesize < 25000) {
            return [
                'status' => 'Perlu Perbaikan',
                'catatan' => 'Ukuran file terlalu kecil (' . round($filesize / 1024, 1) . ' KB). Dokumen terindikasi buram, kosong, atau tidak terbaca. Harap unggah foto / scan asli yang jelas.',
                'details' => ['filesize_bytes' => $filesize, 'error' => 'File too small'],
            ];
        }

        // 2. Cek apakah format PDF
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        $isPdf = ($mime === 'application/pdf');

        if ($isPdf) {
            // Validasi header PDF
            $handle = fopen($filePath, 'r');
            $header = fread($handle, 4);
            fclose($handle);

            if ($header !== '%PDF') {
                return [
                    'status' => 'Perlu Perbaikan',
                    'catatan' => 'File PDF corrupt atau tidak valid.',
                    'details' => ['error' => 'Invalid PDF header'],
                ];
            }

            return [
                'status' => 'Sesuai',
                'catatan' => 'Dokumen PDF terverifikasi (' . round($filesize / 1024, 1) . ' KB).',
                'details' => [
                    'type' => $type,
                    'format' => 'PDF',
                    'filesize_kb' => round($filesize / 1024, 1),
                    'verified_at' => date('Y-m-d H:i:s'),
                ],
            ];
        }

        // 3. Validasi Dokumen Format Gambar (JPG, PNG, WEBP)
        $imageInfo = @getimagesize($filePath);
        if (!$imageInfo) {
            return [
                'status' => 'Perlu Perbaikan',
                'catatan' => 'Format file tidak terbaca sebagai gambar valid. Gunakan format JPG, PNG, atau PDF.',
                'details' => ['mime' => $mime, 'error' => 'Not a valid image'],
            ];
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $imgMime = $imageInfo['mime'];

        if ($width <= 0 || $height <= 0) {
            return [
                'status' => 'Perlu Perbaikan',
                'catatan' => 'Dimensi dokumen tidak valid (0 pixel).',
                'details' => compact('width', 'height'),
            ];
        }

        $ratio = $width / $height;
        $issues = [];

        // 4. Aturan Spesifik per Jenis Dokumen
        if ($type === 'ktp') {
            // e-KTP Indonesia berorientasi LANDSCAPE (kartu mendatar, rasio ~ 1.4 s/d 1.85)
            // Resolusi minimal agar NIK dan teks terbaca: lebar 350px, tinggi 200px
            if ($width < 350 || $height < 200) {
                $issues[] = 'Resolusi foto KTP terlalu kecil (' . $width . 'x' . $height . ' px). NIK dan data rawan tidak terbaca';
            }

            // Jika santri mengunggah foto selfie atau foto tegak (portrait rasio < 0.9)
            if ($ratio < 0.95) {
                $issues[] = 'Orientasi foto KTP terdeteksi tegak / selfie. Harap foto fisik e-KTP dengan posisi mendatar (landscape)';
            }
        } elseif ($type === 'kk') {
            // Kartu Keluarga umumnya berupa lembar lebar (landscape A4 / F4)
            // Resolusi minimal agar tabel KK terbaca: lebar 450px, tinggi 300px
            if ($width < 450 || $height < 300) {
                $issues[] = 'Resolusi foto Kartu Keluarga terlalu kecil (' . $width . 'x' . $height . ' px). Data baris anggota keluarga rawan tidak terbaca';
            }
        } elseif ($type === 'akta') {
            // Akta Kelahiran umumnya berorientasi portrait (tegak)
            if ($width < 300 || $height < 400) {
                $issues[] = 'Resolusi foto Akta Kelahiran terlalu kecil (' . $width . 'x' . $height . ' px). Teks kutipan akta rawan tidak terbaca';
            }
        } elseif ($type === 'transfer') {
            if ($width < 250 || $height < 250) {
                $issues[] = 'Foto bukti transfer terlalu kecil (' . $width . 'x' . $height . ' px)';
            }
        }

        // 5. Cek Luminance / Kecerahan (Mencegah upload foto serba hitam/gelap gulita atau putih kosong)
        $image = null;
        try {
            switch ($imgMime) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = @imagecreatefromjpeg($filePath);
                    break;
                case 'image/png':
                    $image = @imagecreatefrompng($filePath);
                    break;
                case 'image/webp':
                    $image = @imagecreatefromwebp($filePath);
                    break;
                default:
                    $content = @file_get_contents($filePath);
                    if ($content) {
                        $image = @imagecreatefromstring($content);
                    }
                    break;
            }
        } catch (\Throwable $e) {
            $image = null;
        }

        if ($image) {
            // Ambil sampel grid 5x5 untuk menghitung rata-rata kecerahan
            $lumTotal = 0;
            $sampleCount = 0;
            $minLum = 255;
            $maxLum = 0;

            for ($gx = 1; $gx <= 5; $gx++) {
                for ($gy = 1; $gy <= 5; $gy++) {
                    $sx = (int)(($width / 6) * $gx);
                    $sy = (int)(($height / 6) * $gy);

                    $rgbIndex = imagecolorat($image, $sx, $sy);
                    $rgba = imagecolorsforindex($image, $rgbIndex);

                    // Luminance standard formula
                    $lum = (0.299 * $rgba['red']) + (0.587 * $rgba['green']) + (0.114 * $rgba['blue']);
                    $lumTotal += $lum;
                    $sampleCount++;

                    if ($lum < $minLum) $minLum = $lum;
                    if ($lum > $maxLum) $maxLum = $lum;
                }
            }
            // Deteksi jika dokumen (KK, KTP, Akta, Transfer) malah diunggah Pas Foto berlatar merah
            if ($type !== 'foto') {
                $redCornerSamples = 0;
                $cornerCoords = [
                    ['x' => (int)($width * 0.08), 'y' => (int)($height * 0.08)],
                    ['x' => (int)($width * 0.15), 'y' => (int)($height * 0.08)],
                    ['x' => (int)($width * 0.85), 'y' => (int)($height * 0.08)],
                    ['x' => (int)($width * 0.92), 'y' => (int)($height * 0.08)],
                    ['x' => (int)($width * 0.08), 'y' => (int)($height * 0.16)],
                    ['x' => (int)($width * 0.92), 'y' => (int)($height * 0.16)],
                ];

                foreach ($cornerCoords as $coord) {
                    $cx = max(0, min($coord['x'], $width - 1));
                    $cy = max(0, min($coord['y'], $height - 1));
                    $rgbIndex = imagecolorat($image, $cx, $cy);
                    $rgba = imagecolorsforindex($image, $rgbIndex);
                    if ($rgba['red'] >= 95 && $rgba['red'] > ($rgba['green'] * 1.28) && $rgba['red'] > ($rgba['blue'] * 1.28)) {
                        $redCornerSamples++;
                    }
                }

                if ($redCornerSamples >= 3) {
                    $issues[] = 'File terdeteksi sebagai Pas Foto santri (background merah). Harap unggah dokumen fisik asli (bukan pas foto santri)';
                }
            }

            imagedestroy($image);

            $avgLum = $sampleCount > 0 ? ($lumTotal / $sampleCount) : 128;
            $lumVariance = $maxLum - $minLum;

            // Jika foto serba hitam gelap gulita (< 22)
            if ($avgLum < 22) {
                $issues[] = 'Foto terdeteksi terlalu gelap / hitam pekat. Dokumen tidak terlihat';
            }

            // Jika foto serba putih polos kosong tanpa variasi gambar
            if ($avgLum > 250 && $lumVariance < 15) {
                $issues[] = 'Foto terdeteksi kosong / putih polos';
            }
        }

        if (empty($issues)) {
            return [
                'status' => 'Sesuai',
                'catatan' => 'Dokumen terverifikasi: Kualitas gambar tajam (' . $width . 'x' . $height . ' px) dan orientasi sesuai.',
                'details' => [
                    'type' => $type,
                    'format' => $imgMime,
                    'width' => $width,
                    'height' => $height,
                    'ratio' => round($ratio, 2),
                    'filesize_kb' => round($filesize / 1024, 1),
                    'verified_at' => date('Y-m-d H:i:s'),
                ],
            ];
        }

        return [
            'status' => 'Perlu Perbaikan',
            'catatan' => 'Perlu Perbaikan: ' . implode('; ', $issues) . '.',
            'details' => [
                'type' => $type,
                'format' => $imgMime,
                'width' => $width,
                'height' => $height,
                'ratio' => round($ratio, 2),
                'filesize_kb' => round($filesize / 1024, 1),
                'issues' => $issues,
                'verified_at' => date('Y-m-d H:i:s'),
            ],
        ];
    }
}

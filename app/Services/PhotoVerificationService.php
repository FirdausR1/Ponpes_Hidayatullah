<?php

namespace App\Services;

class PhotoVerificationService
{
    /**
     * Memeriksa dan memverifikasi kelayakan pas foto santri secara otomatis:
     * 1. Rasio orientasi (3x4 tegak / portrait, mendeteksi foto miring / landscape)
     * 2. Warna latar belakang (background merah dominan di area sudut atas)
     * 
     * @param string $imageFullPath Path absolut file gambar
     * @return array ['status' => 'Sesuai'|'Perlu Perbaikan', 'catatan' => string, 'details' => array]
     */
    public static function verify(string $imageFullPath): array
    {
        if (!file_exists($imageFullPath)) {
            return [
                'status' => 'Perlu Perbaikan',
                'catatan' => 'File pas foto tidak ditemukan di server.',
                'details' => ['error' => 'File not found'],
            ];
        }

        $imageInfo = @getimagesize($imageFullPath);
        if (!$imageInfo) {
            return [
                'status' => 'Perlu Perbaikan',
                'catatan' => 'Format file bukan gambar yang valid (dukungan: JPG, PNG, WEBP).',
                'details' => ['error' => 'Invalid image format'],
            ];
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'];

        if ($width <= 0 || $height <= 0) {
            return [
                'status' => 'Perlu Perbaikan',
                'catatan' => 'Dimensi gambar tidak valid.',
                'details' => compact('width', 'height'),
            ];
        }

        $ratio = $width / $height;
        $isPortrait = ($ratio >= 0.60 && $ratio <= 0.88);
        $isLandscapeOrSquare = ($ratio >= 0.92);

        // Load image GD resource
        $image = null;
        try {
            switch ($mime) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = @imagecreatefromjpeg($imageFullPath);
                    break;
                case 'image/png':
                    $image = @imagecreatefrompng($imageFullPath);
                    break;
                case 'image/webp':
                    $image = @imagecreatefromwebp($imageFullPath);
                    break;
                default:
                    $content = @file_get_contents($imageFullPath);
                    if ($content) {
                        $image = @imagecreatefromstring($content);
                    }
                    break;
            }
        } catch (\Throwable $e) {
            $image = null;
        }

        $redSamplesCount = 0;
        $totalSamples = 0;
        $sampleColors = [];

        if ($image) {
            // Sampel titik koordinat latar belakang di sudut atas (kiri & kanan)
            $sampleCoords = [
                ['x' => (int)($width * 0.08), 'y' => (int)($height * 0.08)],
                ['x' => (int)($width * 0.15), 'y' => (int)($height * 0.08)],
                ['x' => (int)($width * 0.08), 'y' => (int)($height * 0.16)],
                ['x' => (int)($width * 0.15), 'y' => (int)($height * 0.16)],
                ['x' => (int)($width * 0.85), 'y' => (int)($height * 0.08)],
                ['x' => (int)($width * 0.92), 'y' => (int)($height * 0.08)],
                ['x' => (int)($width * 0.85), 'y' => (int)($height * 0.16)],
                ['x' => (int)($width * 0.92), 'y' => (int)($height * 0.16)],
            ];

            foreach ($sampleCoords as $coord) {
                $x = max(0, min($coord['x'], $width - 1));
                $y = max(0, min($coord['y'], $height - 1));

                $rgbIndex = imagecolorat($image, $x, $y);
                $rgba = imagecolorsforindex($image, $rgbIndex);

                $r = $rgba['red'];
                $g = $rgba['green'];
                $b = $rgba['blue'];

                $sampleColors[] = "rgb($r,$g,$b)";
                $totalSamples++;

                // Kriteria warna merah dominan: R > 90 dan R signifikan lebih tinggi daripada G dan B
                if ($r >= 95 && $r > ($g * 1.32) && $r > ($b * 1.32)) {
                    $redSamplesCount++;
                }
            }

            imagedestroy($image);
        }

        $redRatio = $totalSamples > 0 ? ($redSamplesCount / $totalSamples) : 0;
        $isRedBg = ($redRatio >= 0.50); // Setidaknya 50% sampel sudut atas berwarna merah dominan

        // Klasifikasi
        $issues = [];
        if (!$isPortrait) {
            if ($isLandscapeOrSquare) {
                $issues[] = 'Foto terdeteksi miring / landscape (posisi tidak tegak 3x4)';
            } else {
                $issues[] = 'Rasio foto tidak proporsional untuk pas foto 3x4';
            }
        }

        if (!$isRedBg) {
            $issues[] = 'Latar belakang (background) belum terdeteksi merah sesuai ketentuan PSB';
        }

        if (empty($issues)) {
            return [
                'status' => 'Sesuai',
                'catatan' => 'Terverifikasi otomatis: Posisi tegak proporsional (3x4) dan background merah terdeteksi.',
                'details' => [
                    'width' => $width,
                    'height' => $height,
                    'ratio' => round($ratio, 2),
                    'red_match' => round($redRatio * 100) . '%',
                    'verified_at' => date('Y-m-d H:i:s'),
                ],
            ];
        } else {
            return [
                'status' => 'Perlu Perbaikan',
                'catatan' => 'Perlu Perbaikan: ' . implode(', ', $issues) . '.',
                'details' => [
                    'width' => $width,
                    'height' => $height,
                    'ratio' => round($ratio, 2),
                    'red_match' => round($redRatio * 100) . '%',
                    'issues' => $issues,
                    'verified_at' => date('Y-m-d H:i:s'),
                ],
            ];
        }
    }
}

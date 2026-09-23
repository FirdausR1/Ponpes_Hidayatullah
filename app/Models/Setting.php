<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Get setting value by key, or return default.
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if ($setting !== null && $setting->value !== null) {
            return $setting->value;
        }
        return $default;
    }

    /**
     * Set or update setting value by key.
     */
    public static function set(string $key, $value, string $group = 'general')
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    /**
     * Cek status jadwal PSB (buka/tutup/belum buka berdasarkan tanggal).
     */
    public static function getPsbSchedule(): array
    {
        $mode = static::get('psb_mode', 'jadwal');
        $gelombang = static::get('psb_gelombang_aktif', 'Gelombang 1');
        $now = now();

        // 1. Per-Wave Schedule Definitions
        $waveDefinitions = [
            [
                'name'  => 'Gelombang 1',
                'start' => static::get('psb_g1_start', ''),
                'end'   => static::get('psb_g1_end', ''),
            ],
            [
                'name'  => 'Gelombang 2',
                'start' => static::get('psb_g2_start', ''),
                'end'   => static::get('psb_g2_end', ''),
            ],
            [
                'name'  => 'Gelombang 3',
                'start' => static::get('psb_g3_start', ''),
                'end'   => static::get('psb_g3_end', ''),
            ],
            [
                'name'  => 'Gelombang Khusus',
                'start' => static::get('psb_gkhusus_start', ''),
                'end'   => static::get('psb_gkhusus_end', ''),
            ],
        ];

        // Generic fallback dates
        $startDateStr = static::get('psb_start_date', '');
        $endDateStr = static::get('psb_end_date', '');
        $startDate = !empty($startDateStr) ? \Carbon\Carbon::parse($startDateStr) : null;
        $endDate = !empty($endDateStr) ? \Carbon\Carbon::parse($endDateStr) : null;
        $startFormatted = $startDate ? $startDate->format('d M Y, H:i') . ' WIB' : null;
        $endFormatted = $endDate ? $endDate->format('d M Y, H:i') . ' WIB' : null;

        // Check if multi-wave dates are configured
        $hasMultiWave = false;
        foreach ($waveDefinitions as &$w) {
            $w['start_dt'] = !empty($w['start']) ? \Carbon\Carbon::parse($w['start']) : null;
            $w['end_dt']   = !empty($w['end']) ? \Carbon\Carbon::parse($w['end']) : null;
            if ($w['start_dt'] && $w['end_dt']) {
                $hasMultiWave = true;
            }
        }
        unset($w);

        // 1. Mode Paksa Buka
        if ($mode === 'buka') {
            return [
                'is_open' => true,
                'status' => 'buka',
                'state' => 'manual_open',
                'gelombang' => $gelombang,
                'badge' => $gelombang . ' • Buka',
                'badge_class' => 'bg-emerald-600 text-white',
                'title' => 'Pendaftaran Santri Baru (' . $gelombang . ') Dibuka',
                'pesan' => 'Pendaftaran santri baru ' . $gelombang . ' sedang dibuka.',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'start_formatted' => $startFormatted,
                'end_formatted' => $endFormatted,
                'mode' => 'buka',
                'has_multi_wave' => $hasMultiWave,
            ];
        }

        // 2. Mode Paksa Tutup
        if ($mode === 'tutup') {
            return [
                'is_open' => false,
                'status' => 'tutup',
                'state' => 'manual_closed',
                'gelombang' => $gelombang,
                'badge' => $gelombang . ' • Ditutup',
                'badge_class' => 'bg-rose-600 text-white',
                'title' => static::get('psb_tutup_judul', 'Penerimaan Santri Baru (' . $gelombang . ') Telah Ditutup'),
                'pesan' => static::get('psb_tutup_pesan', 'Pendaftaran santri baru ' . $gelombang . ' saat ini sedang ditutup.'),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'start_formatted' => $startFormatted,
                'end_formatted' => $endFormatted,
                'mode' => 'tutup',
                'has_multi_wave' => $hasMultiWave,
            ];
        }

        // 3. Mode Jadwal Otomatis Multi-Gelombang (jika diisi)
        if ($hasMultiWave) {
            // A. Cari gelombang yang sedang AKTIF saat ini
            foreach ($waveDefinitions as $w) {
                if ($w['start_dt'] && $w['end_dt'] && $now->between($w['start_dt'], $w['end_dt'])) {
                    $wStartFmt = $w['start_dt']->format('d M Y, H:i') . ' WIB';
                    $wEndFmt = $w['end_dt']->format('d M Y, H:i') . ' WIB';
                    return [
                        'is_open' => true,
                        'status' => 'buka',
                        'state' => 'active',
                        'gelombang' => $w['name'],
                        'badge' => $w['name'] . ' • Dibuka',
                        'badge_class' => 'bg-emerald-600 text-white',
                        'title' => 'Pendaftaran ' . $w['name'] . ' Sedang Berlangsung',
                        'pesan' => "Pendaftaran {$w['name']} dibuka s/d {$wEndFmt}.",
                        'start_date' => $w['start_dt'],
                        'end_date' => $w['end_dt'],
                        'start_formatted' => $wStartFmt,
                        'end_formatted' => $wEndFmt,
                        'mode' => 'jadwal',
                        'has_multi_wave' => true,
                    ];
                }
            }

            // B. Jika tidak ada yang aktif saat ini, cari gelombang MENDATANG berikutnya yang terdekat
            $upcomingWave = null;
            foreach ($waveDefinitions as $w) {
                if ($w['start_dt'] && $now->lt($w['start_dt'])) {
                    if ($upcomingWave === null || $w['start_dt']->lt($upcomingWave['start_dt'])) {
                        $upcomingWave = $w;
                    }
                }
            }

            if ($upcomingWave) {
                $wStartFmt = $upcomingWave['start_dt']->format('d M Y, H:i') . ' WIB';
                $wEndFmt = $upcomingWave['end_dt'] ? $upcomingWave['end_dt']->format('d M Y, H:i') . ' WIB' : null;
                return [
                    'is_open' => false,
                    'status' => 'belum_buka',
                    'state' => 'upcoming',
                    'gelombang' => $upcomingWave['name'],
                    'badge' => $upcomingWave['name'] . ' • Belum Dibuka',
                    'badge_class' => 'bg-amber-500 text-white',
                    'title' => 'Pendaftaran ' . $upcomingWave['name'] . ' Belum Dibuka',
                    'pesan' => "Pendaftaran {$upcomingWave['name']} akan dibuka pada tanggal {$wStartFmt}.",
                    'start_date' => $upcomingWave['start_dt'],
                    'end_date' => $upcomingWave['end_dt'],
                    'start_formatted' => $wStartFmt,
                    'end_formatted' => $wEndFmt,
                    'mode' => 'jadwal',
                    'has_multi_wave' => true,
                ];
            }

            // C. Jika semua gelombang sudah berakhir
            return [
                'is_open' => false,
                'status' => 'tutup',
                'state' => 'ended',
                'gelombang' => $gelombang,
                'badge' => 'Pendaftaran Ditutup',
                'badge_class' => 'bg-rose-600 text-white',
                'title' => static::get('psb_tutup_judul', 'Penerimaan Santri Baru Telah Resmi Ditutup'),
                'pesan' => "Seluruh gelombang pendaftaran santri baru telah berakhir. Silakan pantau pengumuman tahun ajaran berikutnya atau hubungi panitia.",
                'start_date' => $startDate,
                'end_date' => $endDate,
                'start_formatted' => $startFormatted,
                'end_formatted' => $endFormatted,
                'mode' => 'jadwal',
                'has_multi_wave' => true,
            ];
        }

        // 4. Fallback ke rentang tanggal tunggal standar (jika per-gelombang belum diisi)
        if ($startDate && $endDate) {
            if ($now->lt($startDate)) {
                return [
                    'is_open' => false,
                    'status' => 'belum_buka',
                    'state' => 'upcoming',
                    'gelombang' => $gelombang,
                    'badge' => $gelombang . ' • Belum Dibuka',
                    'badge_class' => 'bg-amber-500 text-white',
                    'title' => 'Pendaftaran ' . $gelombang . ' Belum Dibuka',
                    'pesan' => "Penerimaan Santri Baru TA " . static::get('tahun_ajaran', '2026/2027') . " ({$gelombang}) belum dimulai. Pendaftaran akan dibuka pada tanggal {$startFormatted}.",
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'start_formatted' => $startFormatted,
                    'end_formatted' => $endFormatted,
                    'mode' => 'jadwal',
                    'has_multi_wave' => false,
                ];
            } elseif ($now->gt($endDate)) {
                return [
                    'is_open' => false,
                    'status' => 'tutup',
                    'state' => 'ended',
                    'gelombang' => $gelombang,
                    'badge' => $gelombang . ' • Ditutup',
                    'badge_class' => 'bg-rose-600 text-white',
                    'title' => static::get('psb_tutup_judul', 'Penerimaan Santri Baru (' . $gelombang . ') Telah Resmi Ditutup'),
                    'pesan' => "Masa penerimaan santri baru ({$gelombang}) telah berakhir pada tanggal {$endFormatted}. Silakan pantau pengumuman gelombang berikutnya atau hubungi panitia.",
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'start_formatted' => $startFormatted,
                    'end_formatted' => $endFormatted,
                    'mode' => 'jadwal',
                    'has_multi_wave' => false,
                ];
            } else {
                return [
                    'is_open' => true,
                    'status' => 'buka',
                    'state' => 'active',
                    'gelombang' => $gelombang,
                    'badge' => $gelombang . ' • Dibuka',
                    'badge_class' => 'bg-emerald-600 text-white',
                    'title' => 'Pendaftaran ' . $gelombang . ' Sedang Berlangsung',
                    'pesan' => "Pendaftaran {$gelombang} dibuka sampai {$endFormatted}.",
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'start_formatted' => $startFormatted,
                    'end_formatted' => $endFormatted,
                    'mode' => 'jadwal',
                    'has_multi_wave' => false,
                ];
            }
        }

        // 4. Fallback jika tanggal belum diset
        $legacyStatus = static::get('psb_status', 'buka');
        $isOpen = ($legacyStatus === 'buka');
        return [
            'is_open' => $isOpen,
            'status' => $legacyStatus,
            'state' => $isOpen ? 'active' : 'manual_closed',
            'gelombang' => $gelombang,
            'badge' => $gelombang . ' • ' . ($isOpen ? 'Buka' : 'Ditutup'),
            'badge_class' => $isOpen ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white',
            'title' => $isOpen ? 'Pendaftaran ' . $gelombang . ' Dibuka' : static::get('psb_tutup_judul', 'Penerimaan Santri Baru (' . $gelombang . ') Telah Ditutup'),
            'pesan' => $isOpen ? 'Pendaftaran santri baru ' . $gelombang . ' sedang dibuka.' : static::get('psb_tutup_pesan', 'Pendaftaran santri baru ' . $gelombang . ' saat ini sedang ditutup.'),
            'start_date' => null,
            'end_date' => null,
            'start_formatted' => null,
            'end_formatted' => null,
            'mode' => 'jadwal',
        ];
    }

    /**
     * Helper boolean cepat untuk cek apakah pendaftaran PSB sedang buka.
     */
    public static function isPsbOpen(): bool
    {
        return static::getPsbSchedule()['is_open'];
    }
}

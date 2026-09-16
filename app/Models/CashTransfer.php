<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CashTransfer extends Model
{
    use HasFactory;

    protected $table = 'cash_transfers';

    protected $fillable = [
        'no_transfer',
        'tanggal',
        'dari_kas',
        'ke_kas',
        'nominal',
        'keterangan',
        'bukti_file',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    /**
     * Relasi ke Bendahara / Petugas yang mencatat mutasi kas
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Format Rupiah untuk nominal mutasi
     */
    public function getFormattedNominalAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    /**
     * Format tanggal Indonesia
     */
    public function getFormattedTanggalAttribute(): string
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    /**
     * Label arah mutasi dana
     */
    public function getArahLabelAttribute(): string
    {
        if ($this->dari_kas === 'Transfer Bank' && $this->ke_kas === 'Kas Tunai') {
            return 'Tarik Tunai Bank → Kas Fisik';
        } elseif ($this->dari_kas === 'Kas Tunai' && $this->ke_kas === 'Transfer Bank') {
            return 'Setor Kas Fisik → Rekening Bank';
        }
        return "{$this->dari_kas} → {$this->ke_kas}";
    }

    /**
     * Generate Nomor Bukti Mutasi Kas Unik otomatis
     */
    public static function generateNoTransfer($tanggal = null): string
    {
        $date = $tanggal ? Carbon::parse($tanggal) : now();
        $prefix = 'MUT-' . $date->format('Ym') . '-';

        $last = self::where('no_transfer', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNum = (int) substr($last->no_transfer, -4);
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }

        return $prefix . $nextNum;
    }
}

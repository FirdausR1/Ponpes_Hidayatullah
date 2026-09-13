<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OperationalExpense extends Model
{
    use HasFactory;

    protected $table = 'operational_expenses';

    protected $fillable = [
        'no_referensi',
        'kategori',
        'judul_pengeluaran',
        'nominal',
        'tanggal_keluar',
        'metode_kas',
        'penerima_dana',
        'user_id',
        'bukti_nota',
        'catatan',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
        'nominal' => 'decimal:2',
    ];

    const KATEGORI_LIST = [
        'Dapur & Konsumsi Santri' => 'Dapur & Konsumsi Santri (Beras, Lauk, Gas, Dsb)',
        'Listrik, Air PAM & Internet' => 'Listrik PLN, Air PAM & WiFi Pondok',
        'Gaji / Honorarium Ustadz & Pengasuh' => 'Gaji / Honorarium Ustadz & Tenaga Pendidik',
        'Pemeliharaan Gedung & Sarana Asrama' => 'Pemeliharaan Gedung, Kamar Santri & Sarana',
        'Kesehatan & Pengobatan Santri' => 'Kesehatan Santri & Perlengkapan UKS',
        'Operasional Kantor & ATK' => 'Operasional Kantor, Kertas, ATK & Cetak',
        'Kegiatan & PHBI Pesantren' => 'Kegiatan Santri, Lomba & Peringatan Hari Besar',
        'Biaya Insidental & Lain-lain' => 'Biaya Insidental & Pengeluaran Lainnya',
    ];

    /**
     * Relasi ke Petugas / Bendahara yang mencatat
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Accessor format Rupiah
     */
    public function getFormattedNominalAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    /**
     * Accessor format Tanggal Indonesia
     */
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal_keluar ? $this->tanggal_keluar->format('d/m/Y') : '-';
    }

    /**
     * Generate Nomor Bukti Kas Keluar otomatis unik
     */
    public static function generateNoReferensi($tanggal = null)
    {
        $date = $tanggal ? Carbon::parse($tanggal) : now();
        $prefix = 'BKK-' . $date->format('Ym') . '-';
        
        $last = self::where('no_referensi', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNum = (int) substr($last->no_referensi, -4);
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }

        return $prefix . $nextNum;
    }
}

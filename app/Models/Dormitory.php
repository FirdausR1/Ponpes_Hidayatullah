<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dormitory extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_asrama',
        'kamar',
        'gender',
        'kapasitas',
        'musyrif',
        'lokasi',
        'keterangan',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
    ];

    /**
     * Santri yang menempati kamar asrama ini.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'dormitory_id');
    }

    /**
     * Santri aktif yang menempati kamar asrama ini.
     */
    public function activeStudents()
    {
        return $this->hasMany(Student::class, 'dormitory_id')->where('status', 'Aktif');
    }

    /**
     * Jumlah santri aktif yang saat ini menghuni kamar.
     */
    public function getTerisiCountAttribute(): int
    {
        return $this->activeStudents()->count();
    }

    /**
     * Sisa ranjang/bed kosong yang tersedia.
     */
    public function getSisaKapasitasAttribute(): int
    {
        return max(0, $this->kapasitas - $this->terisi_count);
    }

    /**
     * Persentase keterisian kamar (0 - 100%).
     */
    public function getPersentaseTerisiAttribute(): int
    {
        if ($this->kapasitas <= 0) return 0;
        return (int) min(100, round(($this->terisi_count / $this->kapasitas) * 100));
    }

    /**
     * Cek apakah kamar sudah penuh.
     */
    public function getIsPenuhAttribute(): bool
    {
        return $this->terisi_count >= $this->kapasitas;
    }

    /**
     * Label lengkap nama asrama dan kamar.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->nama_asrama} - {$this->kamar}";
    }
}

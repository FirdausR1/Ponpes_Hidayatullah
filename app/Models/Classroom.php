<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenjang',
        'tingkat',
        'nama_kelas',
        'wali_kelas',
        'kontak_wali',
        'nip_wali',
        'kapasitas',
        'keterangan',
    ];

    /**
     * Dapatkan link WhatsApp langsung ke wali kelas.
     */
    public function getWhatsappUrlAttribute(): ?string
    {
        if (empty($this->kontak_wali)) {
            return null;
        }
        $phone = preg_replace('/[^0-9]/', '', $this->kontak_wali);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }
        $text = urlencode("Assalamu'alaikum Warahmatullahi Wabarakatuh Ustadz/Ustadzah {$this->wali_kelas}, perihal santri kelas {$this->nama_kelas} Pondok Pesantren Hidayatullah.");
        return "https://wa.me/{$phone}?text={$text}";
    }

    /**
     * Relasi ke santri yang berada di kelas ini.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'kelas', 'nama_kelas');
    }

    /**
     * Hitung santri aktif di kelas ini.
     */
    public function getActiveStudentsCountAttribute()
    {
        return $this->students()->where('status', 'Aktif')->count();
    }
}

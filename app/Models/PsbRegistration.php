<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsbRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_registrasi',
        'jalur',
        'bukti_transfer',
        'bukti_prestasi_tahfidz',
        'nama_lengkap',
        'pas_foto',
        'foto_status',
        'foto_catatan',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'nomor_kk',
        'anak_ke',
        'jumlah_saudara',
        'hobi',
        'alamat_lengkap',
        'bantuan_sosial',
        'bukti_bantuan_sosial',
        'jenjang',
        'jenis_kelamin',
        'nama_wali',
        'no_whatsapp',
        'asal_sekolah',
        'nama_sekolah',
        'alamat_sekolah',
        'tahun_lulus',
        'ayah_nama',
        'ayah_nik',
        'ayah_tahun_lahir',
        'ayah_alamat',
        'ayah_telepon',
        'ayah_pendidikan',
        'ayah_pekerjaan',
        'ayah_penghasilan',
        'ibu_nama',
        'ibu_nik',
        'ibu_tahun_lahir',
        'ibu_alamat',
        'ibu_telepon',
        'ibu_pendidikan',
        'ibu_pekerjaan',
        'ibu_penghasilan',
        'wali_hubungan',
        'wali_nama',
        'wali_nik',
        'wali_tahun_lahir',
        'wali_alamat',
        'wali_telepon',
        'wali_pendidikan',
        'wali_pekerjaan',
        'wali_penghasilan',
        'alamat',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->no_registrasi) {
                $count = static::count() + 1;
                $model->no_registrasi = 'PSB-25-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
            if (!$model->nama_wali) {
                $model->nama_wali = $model->ayah_nama ?: $model->ibu_nama ?: $model->wali_nama ?: '-';
            }
            if (!$model->no_whatsapp) {
                $model->no_whatsapp = $model->ayah_telepon ?: $model->ibu_telepon ?: $model->wali_telepon ?: '-';
            }
            if (!$model->asal_sekolah) {
                $model->asal_sekolah = $model->nama_sekolah ?: '-';
            }
            if (!$model->alamat) {
                $model->alamat = $model->alamat_lengkap ?: '-';
            }
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('psb_registrations', function (Blueprint $table) {
            $table->string('no_registrasi')->nullable()->unique();
            $table->string('jalur')->default('Reguler'); // Reguler, Prestasi, Tahfidz
            $table->string('bukti_transfer')->nullable();
            $table->string('bukti_prestasi_tahfidz')->nullable();

            // Data Diri Santri
            $table->string('pas_foto')->nullable();
            $table->string('nik')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nomor_kk')->nullable();
            $table->integer('anak_ke')->nullable();
            $table->integer('jumlah_saudara')->nullable();
            $table->string('hobi')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->text('bantuan_sosial')->nullable();
            $table->string('bukti_bantuan_sosial')->nullable();

            // Asal Sekolah
            $table->string('nama_sekolah')->nullable();
            $table->text('alamat_sekolah')->nullable();
            $table->string('tahun_lulus')->nullable();

            // Ayah Kandung
            $table->string('ayah_nama')->nullable();
            $table->string('ayah_nik')->nullable();
            $table->string('ayah_tahun_lahir')->nullable();
            $table->text('ayah_alamat')->nullable();
            $table->string('ayah_telepon')->nullable();
            $table->string('ayah_pendidikan')->nullable();
            $table->string('ayah_pekerjaan')->nullable();
            $table->string('ayah_penghasilan')->nullable();

            // Ibu Kandung
            $table->string('ibu_nama')->nullable();
            $table->string('ibu_nik')->nullable();
            $table->string('ibu_tahun_lahir')->nullable();
            $table->text('ibu_alamat')->nullable();
            $table->string('ibu_telepon')->nullable();
            $table->string('ibu_pendidikan')->nullable();
            $table->string('ibu_pekerjaan')->nullable();
            $table->string('ibu_penghasilan')->nullable();

            // Wali Opsional
            $table->string('wali_hubungan')->nullable();
            $table->string('wali_nama')->nullable();
            $table->string('wali_nik')->nullable();
            $table->string('wali_tahun_lahir')->nullable();
            $table->text('wali_alamat')->nullable();
            $table->string('wali_telepon')->nullable();
            $table->string('wali_pendidikan')->nullable();
            $table->string('wali_pekerjaan')->nullable();
            $table->string('wali_penghasilan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'no_registrasi', 'jalur', 'bukti_transfer', 'bukti_prestasi_tahfidz',
                'pas_foto', 'nik', 'tempat_lahir', 'tanggal_lahir', 'nomor_kk',
                'anak_ke', 'jumlah_saudara', 'hobi', 'alamat_lengkap', 'bantuan_sosial', 'bukti_bantuan_sosial',
                'nama_sekolah', 'alamat_sekolah', 'tahun_lulus',
                'ayah_nama', 'ayah_nik', 'ayah_tahun_lahir', 'ayah_alamat', 'ayah_telepon', 'ayah_pendidikan', 'ayah_pekerjaan', 'ayah_penghasilan',
                'ibu_nama', 'ibu_nik', 'ibu_tahun_lahir', 'ibu_alamat', 'ibu_telepon', 'ibu_pendidikan', 'ibu_pekerjaan', 'ibu_penghasilan',
                'wali_hubungan', 'wali_nama', 'wali_nik', 'wali_tahun_lahir', 'wali_alamat', 'wali_telepon', 'wali_pendidikan', 'wali_pekerjaan', 'wali_penghasilan',
            ]);
        });
    }
};

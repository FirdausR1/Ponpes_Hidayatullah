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
            $table->string('no_registrasi')->nullable()->unique()->after('id');
            $table->string('jalur')->default('Reguler')->after('no_registrasi'); // Reguler, Prestasi, Tahfidz
            $table->string('bukti_transfer')->nullable()->after('jalur');
            $table->string('bukti_prestasi_tahfidz')->nullable()->after('bukti_transfer');

            // Data Diri Santri
            $table->string('pas_foto')->nullable()->after('nama_lengkap');
            $table->string('nik')->nullable()->after('pas_foto');
            $table->string('tempat_lahir')->nullable()->after('nik');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('nomor_kk')->nullable()->after('tanggal_lahir');
            $table->integer('anak_ke')->nullable()->after('nomor_kk');
            $table->integer('jumlah_saudara')->nullable()->after('anak_ke');
            $table->string('hobi')->nullable()->after('jumlah_saudara');
            $table->text('alamat_lengkap')->nullable()->after('hobi');
            $table->text('bantuan_sosial')->nullable()->after('alamat_lengkap');
            $table->string('bukti_bantuan_sosial')->nullable()->after('bantuan_sosial');

            // Asal Sekolah
            $table->string('nama_sekolah')->nullable()->after('asal_sekolah');
            $table->text('alamat_sekolah')->nullable()->after('nama_sekolah');
            $table->string('tahun_lulus')->nullable()->after('alamat_sekolah');

            // Ayah Kandung
            $table->string('ayah_nama')->nullable()->after('tahun_lulus');
            $table->string('ayah_nik')->nullable()->after('ayah_nama');
            $table->string('ayah_tahun_lahir')->nullable()->after('ayah_nik');
            $table->text('ayah_alamat')->nullable()->after('ayah_tahun_lahir');
            $table->string('ayah_telepon')->nullable()->after('ayah_alamat');
            $table->string('ayah_pendidikan')->nullable()->after('ayah_telepon');
            $table->string('ayah_pekerjaan')->nullable()->after('ayah_pendidikan');
            $table->string('ayah_penghasilan')->nullable()->after('ayah_pekerjaan');

            // Ibu Kandung
            $table->string('ibu_nama')->nullable()->after('ayah_penghasilan');
            $table->string('ibu_nik')->nullable()->after('ibu_nama');
            $table->string('ibu_tahun_lahir')->nullable()->after('ibu_nik');
            $table->text('ibu_alamat')->nullable()->after('ibu_tahun_lahir');
            $table->string('ibu_telepon')->nullable()->after('ibu_alamat');
            $table->string('ibu_pendidikan')->nullable()->after('ibu_telepon');
            $table->string('ibu_pekerjaan')->nullable()->after('ibu_pendidikan');
            $table->string('ibu_penghasilan')->nullable()->after('ibu_pekerjaan');

            // Wali Opsional
            $table->string('wali_hubungan')->nullable()->after('ibu_penghasilan');
            $table->string('wali_nama')->nullable()->after('wali_hubungan');
            $table->string('wali_nik')->nullable()->after('wali_nama');
            $table->string('wali_tahun_lahir')->nullable()->after('wali_nik');
            $table->text('wali_alamat')->nullable()->after('wali_tahun_lahir');
            $table->string('wali_telepon')->nullable()->after('wali_alamat');
            $table->string('wali_pendidikan')->nullable()->after('wali_telepon');
            $table->string('wali_pekerjaan')->nullable()->after('wali_pendidikan');
            $table->string('wali_penghasilan')->nullable()->after('wali_pekerjaan');
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

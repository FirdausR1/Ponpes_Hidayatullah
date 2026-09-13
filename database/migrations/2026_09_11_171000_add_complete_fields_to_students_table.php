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
        Schema::table('students', function (Blueprint $table) {
            // Data Pribadi Tambahan
            if (!Schema::hasColumn('students', 'nik')) {
                $table->string('nik', 30)->nullable();
            }
            if (!Schema::hasColumn('students', 'nomor_kk')) {
                $table->string('nomor_kk', 30)->nullable();
            }
            if (!Schema::hasColumn('students', 'tempat_lahir')) {
                $table->string('tempat_lahir', 100)->nullable();
            }
            if (!Schema::hasColumn('students', 'anak_ke')) {
                $table->integer('anak_ke')->nullable();
            }
            if (!Schema::hasColumn('students', 'jumlah_saudara')) {
                $table->integer('jumlah_saudara')->nullable();
            }
            if (!Schema::hasColumn('students', 'golongan_darah')) {
                $table->string('golongan_darah', 10)->nullable(); // A, B, AB, O
            }
            if (!Schema::hasColumn('students', 'hobi')) {
                $table->string('hobi', 150)->nullable();
            }
            if (!Schema::hasColumn('students', 'riwayat_penyakit')) {
                $table->text('riwayat_penyakit')->nullable();
            }
            if (!Schema::hasColumn('students', 'alamat_lengkap')) {
                $table->text('alamat_lengkap')->nullable();
            }

            // Asal Sekolah
            if (!Schema::hasColumn('students', 'nama_sekolah')) {
                $table->string('nama_sekolah')->nullable();
            }
            if (!Schema::hasColumn('students', 'alamat_sekolah')) {
                $table->text('alamat_sekolah')->nullable();
            }
            if (!Schema::hasColumn('students', 'tahun_lulus')) {
                $table->string('tahun_lulus', 10)->nullable();
            }

            // Data Ayah Kandung
            if (!Schema::hasColumn('students', 'ayah_nama')) {
                $table->string('ayah_nama')->nullable();
            }
            if (!Schema::hasColumn('students', 'ayah_nik')) {
                $table->string('ayah_nik', 30)->nullable();
            }
            if (!Schema::hasColumn('students', 'ayah_telepon')) {
                $table->string('ayah_telepon', 30)->nullable();
            }
            if (!Schema::hasColumn('students', 'ayah_pekerjaan')) {
                $table->string('ayah_pekerjaan', 100)->nullable();
            }
            if (!Schema::hasColumn('students', 'ayah_penghasilan')) {
                $table->string('ayah_penghasilan', 50)->nullable();
            }
            if (!Schema::hasColumn('students', 'ayah_pendidikan')) {
                $table->string('ayah_pendidikan', 50)->nullable();
            }

            // Data Ibu Kandung
            if (!Schema::hasColumn('students', 'ibu_nama')) {
                $table->string('ibu_nama')->nullable();
            }
            if (!Schema::hasColumn('students', 'ibu_nik')) {
                $table->string('ibu_nik', 30)->nullable();
            }
            if (!Schema::hasColumn('students', 'ibu_telepon')) {
                $table->string('ibu_telepon', 30)->nullable();
            }
            if (!Schema::hasColumn('students', 'ibu_pekerjaan')) {
                $table->string('ibu_pekerjaan', 100)->nullable();
            }
            if (!Schema::hasColumn('students', 'ibu_penghasilan')) {
                $table->string('ibu_penghasilan', 50)->nullable();
            }
            if (!Schema::hasColumn('students', 'ibu_pendidikan')) {
                $table->string('ibu_pendidikan', 50)->nullable();
            }

            // Data Wali
            if (!Schema::hasColumn('students', 'wali_nik')) {
                $table->string('wali_nik', 30)->nullable();
            }
            if (!Schema::hasColumn('students', 'wali_telepon')) {
                $table->string('wali_telepon', 30)->nullable();
            }
            if (!Schema::hasColumn('students', 'wali_hubungan')) {
                $table->string('wali_hubungan', 50)->nullable();
            }
            if (!Schema::hasColumn('students', 'wali_pekerjaan')) {
                $table->string('wali_pekerjaan', 100)->nullable();
            }
            if (!Schema::hasColumn('students', 'wali_penghasilan')) {
                $table->string('wali_penghasilan', 50)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'nomor_kk', 'tempat_lahir', 'anak_ke', 'jumlah_saudara', 'golongan_darah', 'hobi', 'riwayat_penyakit', 'alamat_lengkap',
                'nama_sekolah', 'alamat_sekolah', 'tahun_lulus',
                'ayah_nama', 'ayah_nik', 'ayah_telepon', 'ayah_pekerjaan', 'ayah_penghasilan', 'ayah_pendidikan',
                'ibu_nama', 'ibu_nik', 'ibu_telepon', 'ibu_pekerjaan', 'ibu_penghasilan', 'ibu_pendidikan',
                'wali_nik', 'wali_telepon', 'wali_hubungan', 'wali_pekerjaan', 'wali_penghasilan',
            ]);
        });
    }
};

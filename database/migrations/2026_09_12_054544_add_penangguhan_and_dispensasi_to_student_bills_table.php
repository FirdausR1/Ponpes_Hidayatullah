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
        Schema::table('student_bills', function (Blueprint $table) {
            $table->boolean('penangguhan_wisuda')->default(false);
            $table->text('catatan_penangguhan')->nullable();
            $table->timestamp('ditangguhkan_at')->nullable();
            $table->text('catatan_pembebasan')->nullable();
            $table->string('status_dispensasi', 30)->default('none'); // none, diminta_surat, surat_diunggah, disetujui, ditolak
            $table->string('jenis_surat_diminta')->nullable();
            $table->text('instruksi_surat')->nullable();
            $table->string('file_surat_dispensasi')->nullable();
            $table->timestamp('surat_diminta_at')->nullable();
            $table->timestamp('surat_uploaded_at')->nullable();
            $table->text('alasan_penolakan_surat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_bills', function (Blueprint $table) {
            $table->dropColumn([
                'penangguhan_wisuda',
                'catatan_penangguhan',
                'ditangguhkan_at',
                'catatan_pembebasan',
                'status_dispensasi',
                'jenis_surat_diminta',
                'instruksi_surat',
                'file_surat_dispensasi',
                'surat_diminta_at',
                'surat_uploaded_at',
                'alasan_penolakan_surat',
            ]);
        });
    }
};

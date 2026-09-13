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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('psb_registration_id')->nullable()->constrained('psb_registrations')->nullOnDelete();
            $table->string('nis', 30)->unique();
            $table->string('nisn', 30)->nullable();
            $table->string('nama_lengkap');
            $table->string('jenis_kelamin', 20)->default('Laki-laki');
            $table->string('jenjang', 50); // MTs Mukim, MTs Laju, MA Mukim, MA Laju
            $table->string('kelas', 30)->default('VII');
            $table->string('kamar_asrama', 50)->nullable();
            $table->string('tahun_masuk', 10)->default('2025');
            $table->string('status', 30)->default('Aktif'); // Aktif, Alumni, Mutasi, Cuti
            $table->string('nama_wali')->nullable();
            $table->string('no_whatsapp', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('psb_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nisn')->nullable();
            $table->string('jenjang'); // MTs Mukim, MTs Laju, MA Mukim, MA Laju
            $table->string('jenis_kelamin')->default('Laki-laki'); // Laki-laki, Perempuan
            $table->string('nama_wali');
            $table->string('no_whatsapp');
            $table->string('asal_sekolah')->nullable();
            $table->text('alamat')->nullable();
            $table->string('status')->default('Menunggu'); // Menunggu, Diterima, Ditolak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('psb_registrations');
    }
};

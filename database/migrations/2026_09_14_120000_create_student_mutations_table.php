<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_mutations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable(); // nullable agar data tetap ada jika santri dihapus
            $table->foreign('student_id')->references('id')->on('students')->onDelete('set null');

            $table->enum('jenis_mutasi', ['Keluar', 'Masuk'])->default('Keluar');
            $table->date('tanggal_mutasi');

            // Untuk Mutasi Keluar: alasan santri keluar
            $table->string('alasan')->nullable(); // dropdown: Pindah Sekolah, Drop Out, Meninggal, Lainnya
            $table->string('kelas_dari')->nullable(); // kelas saat mutasi terjadi

            // Untuk Mutasi Masuk: data sekolah asal
            $table->string('sekolah_asal_tujuan')->nullable(); // nama sekolah asal (masuk) / tujuan (keluar)
            $table->string('kelas_ke')->nullable(); // kelas yang dituju (untuk mutasi masuk)

            $table->string('status_sebelumnya')->nullable(); // status student sebelum dimutasi, untuk restore
            $table->text('keterangan')->nullable(); // catatan bebas admin
            $table->string('dicatat_oleh')->nullable(); // nama admin yang mencatat

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_mutations');
    }
};

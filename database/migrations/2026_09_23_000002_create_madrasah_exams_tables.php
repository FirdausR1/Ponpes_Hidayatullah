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
        Schema::create('madrasah_exams', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ujian'); // Contoh: [12] - [KEAGAMAAN] - UJIAN MADRASAH MA HIDAYATULLAH
            $table->string('mata_pelajaran'); // Contoh: Al Quran Hadis
            $table->string('nama_guru')->nullable(); // Contoh: LATIF MAHMUD A
            $table->string('jenjang')->default('MA'); // MA atau MTs
            $table->string('tingkat_kelas')->default('12'); // 12 atau 9
            $table->string('jurusan')->default('KEAGAMAAN'); // KEAGAMAAN, IPA, IPS, UMUM
            $table->string('tahun_ajaran')->default('2025/2026');
            $table->string('semester')->default('Genap');
            $table->integer('jumlah_soal')->default(50);
            $table->integer('durasi_menit')->default(90);
            $table->decimal('kkm', 5, 2)->default(75.00);
            $table->date('tanggal_ujian')->nullable();
            $table->enum('status', ['Draft', 'Aktif', 'Selesai'])->default('Aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('madrasah_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('madrasah_exam_id')->constrained('madrasah_exams')->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->string('nomor_peserta')->nullable();
            $table->string('nama_peserta');
            $table->string('kelas')->default('12');
            $table->string('jurusan')->default('KEAGAMAAN');
            $table->integer('jumlah_soal')->default(50);
            $table->integer('jumlah_benar')->default(0);
            $table->integer('jumlah_salah')->default(0);
            $table->decimal('nilai', 5, 2)->default(0.00);
            $table->string('status')->default('Hadir'); // Hadir, Susulan, Tidak Hadir
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('madrasah_exam_results');
        Schema::dropIfExists('madrasah_exams');
    }
};

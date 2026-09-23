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
        // 1. Tambah kolom konfigurasi CBT pada tabel madrasah_exams
        Schema::table('madrasah_exams', function (Blueprint $table) {
            if (!Schema::hasColumn('madrasah_exams', 'max_attempts')) {
                $table->integer('max_attempts')->default(1);
            }
            if (!Schema::hasColumn('madrasah_exams', 'max_violations')) {
                $table->integer('max_violations')->default(3); // Batas toleransi pindah tab / keluar fullscreen
            }
            if (!Schema::hasColumn('madrasah_exams', 'token_ujian')) {
                $table->string('token_ujian', 20)->nullable(); // Token ujian khas CBT
            }
            if (!Schema::hasColumn('madrasah_exams', 'acak_soal')) {
                $table->boolean('acak_soal')->default(true);
            }
            if (!Schema::hasColumn('madrasah_exams', 'acak_opsi')) {
                $table->boolean('acak_opsi')->default(false);
            }
            if (!Schema::hasColumn('madrasah_exams', 'tampilkan_nilai')) {
                $table->boolean('tampilkan_nilai')->default(true);
            }
        });

        // 2. Tambah kolom pengerjaan & monitoring live anti-cheat pada madrasah_exam_results
        Schema::table('madrasah_exam_results', function (Blueprint $table) {
            if (!Schema::hasColumn('madrasah_exam_results', 'attempt_number')) {
                $table->integer('attempt_number')->default(1);
            }
            if (!Schema::hasColumn('madrasah_exam_results', 'waktu_mulai')) {
                $table->timestamp('waktu_mulai')->nullable();
            }
            if (!Schema::hasColumn('madrasah_exam_results', 'waktu_selesai')) {
                $table->timestamp('waktu_selesai')->nullable();
            }
            if (!Schema::hasColumn('madrasah_exam_results', 'sisa_detik')) {
                $table->integer('sisa_detik')->nullable();
            }
            if (!Schema::hasColumn('madrasah_exam_results', 'answers_json')) {
                $table->longText('answers_json')->nullable(); // Jawaban per nomor
            }
            if (!Schema::hasColumn('madrasah_exam_results', 'ragu_ragu_json')) {
                $table->text('ragu_ragu_json')->nullable(); // Daftar nomor ragu-ragu
            }
            if (!Schema::hasColumn('madrasah_exam_results', 'jumlah_pelanggaran')) {
                $table->integer('jumlah_pelanggaran')->default(0); // Counter tab switch / fullscreen exit
            }
            if (!Schema::hasColumn('madrasah_exam_results', 'log_pelanggaran_json')) {
                $table->longText('log_pelanggaran_json')->nullable(); // Riwayat detail pelanggaran
            }
            if (!Schema::hasColumn('madrasah_exam_results', 'status_pengerjaan')) {
                $table->string('status_pengerjaan', 30)->default('Belum Mulai');
            }
        });

        // 3. Buat tabel butir soal digital untuk ujian madrasah
        if (!Schema::hasTable('madrasah_exam_questions')) {
            Schema::create('madrasah_exam_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('madrasah_exam_id')->constrained('madrasah_exams')->cascadeOnDelete();
                $table->integer('nomor_urut')->default(1);
                $table->text('pertanyaan');
                $table->string('gambar')->nullable();
                $table->text('opsi_a');
                $table->text('opsi_b');
                $table->text('opsi_c');
                $table->text('opsi_d');
                $table->text('opsi_e')->nullable();
                $table->string('kunci_jawaban', 5)->default('A'); // A, B, C, D, E
                $table->integer('bobot')->default(1);
                $table->text('pembahasan')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('madrasah_exam_questions');

        Schema::table('madrasah_exam_results', function (Blueprint $table) {
            $table->dropColumn([
                'attempt_number',
                'waktu_mulai',
                'waktu_selesai',
                'sisa_detik',
                'answers_json',
                'ragu_ragu_json',
                'jumlah_pelanggaran',
                'log_pelanggaran_json',
            ]);
        });

        Schema::table('madrasah_exams', function (Blueprint $table) {
            $table->dropColumn([
                'max_attempts',
                'max_violations',
                'token_ujian',
                'acak_soal',
                'acak_opsi',
                'tampilkan_nilai',
            ]);
        });
    }
};

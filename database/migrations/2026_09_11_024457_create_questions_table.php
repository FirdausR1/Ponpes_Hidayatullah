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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 100)->default('Umum'); // Matematika, Bahasa Arab, Bahasa Indonesia, Bahasa Inggris, PAI & Fiqih, Tajwid & Al-Qur'an
            $table->text('soal');
            $table->boolean('is_math')->default(false);
            $table->boolean('is_arabic')->default(false);
            $table->text('opsi_a');
            $table->text('opsi_b');
            $table->text('opsi_c');
            $table->text('opsi_d');
            $table->text('opsi_e')->nullable();
            $table->string('kunci_jawaban', 5)->default('A'); // A, B, C, D, E
            $table->integer('bobot')->default(1);
            $table->text('pembahasan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

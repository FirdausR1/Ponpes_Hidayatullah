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
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('no_transaksi', 40)->unique();
            $table->string('jenis_pembayaran', 50); // SPP Bulanan, Uang Pangkal, Uang Gedung, Daftar Ulang, Kegiatan, Lainnya
            $table->string('bulan', 20)->nullable(); // Juli, Agustus, dll.
            $table->string('tahun', 10)->default('2026');
            $table->decimal('nominal', 12, 2)->default(0);
            $table->date('tanggal_bayar')->nullable();
            $table->string('metode_pembayaran', 30)->default('Tunai'); // Tunai, Transfer Bank
            $table->string('status', 30)->default('Lunas'); // Lunas, Belum Lunas, Cicilan
            $table->string('bukti_bayar')->nullable();
            $table->text('catatan')->nullable();
            $table->string('penerima_nama')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};

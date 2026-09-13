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
        Schema::create('operational_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('no_referensi')->unique(); // Contoh: BKK-202609-0001
            $table->string('kategori'); // Dapur & Konsumsi Santri, Listrik & Air PAM, Gaji & Honorarium, dll
            $table->string('judul_pengeluaran'); // misal: "Belanja Beras & Sayuran 1 Minggu"
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_keluar');
            $table->string('metode_kas')->default('Kas Tunai'); // Kas Tunai, Transfer Bank
            $table->string('penerima_dana')->nullable(); // Toko Sayur, Ustadz X, PLN, dll
            $table->unsignedBigInteger('user_id')->nullable(); // Petugas / Bendahara yang mencatat
            $table->string('bukti_nota')->nullable(); // Path foto nota / kwitansi
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['kategori', 'tanggal_keluar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operational_expenses');
    }
};

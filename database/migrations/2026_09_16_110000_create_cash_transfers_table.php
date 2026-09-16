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
        Schema::create('cash_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('no_transfer')->unique(); // Contoh: MUT-202609-0001
            $table->date('tanggal');
            $table->string('dari_kas'); // 'Transfer Bank' atau 'Kas Tunai'
            $table->string('ke_kas');   // 'Kas Tunai' atau 'Transfer Bank'
            $table->decimal('nominal', 15, 2);
            $table->string('keterangan')->nullable(); // misal: "Tarik Tunai Bank untuk Belanja Dapur Santri"
            $table->string('bukti_file')->nullable(); // Foto slip ATM / struk mutasi bank
            $table->unsignedBigInteger('user_id')->nullable(); // Petugas / Bendahara yang mencatat
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['tanggal', 'dari_kas', 'ke_kas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_transfers');
    }
};

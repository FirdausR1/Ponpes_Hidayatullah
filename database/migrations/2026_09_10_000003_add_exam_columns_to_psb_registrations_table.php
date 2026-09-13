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
        Schema::table('psb_registrations', function (Blueprint $table) {
            $table->string('status_ujian', 30)->default('Belum Ujian'); // Belum Ujian, Sedang Ujian, Selesai
            $table->integer('nilai_ujian')->nullable();
            $table->timestamp('ujian_selesai_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_registrations', function (Blueprint $table) {
            $table->dropColumn(['status_ujian', 'nilai_ujian', 'ujian_selesai_at']);
        });
    }
};

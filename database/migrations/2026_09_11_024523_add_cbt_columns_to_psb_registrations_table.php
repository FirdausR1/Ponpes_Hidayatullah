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
            $table->integer('pelanggaran_curang_count')->default(0);
            $table->text('pelanggaran_curang_log')->nullable();
            $table->string('status_kelulusan_override', 50)->nullable(); // Lulus, Tidak Lulus, Lulus Bersyarat, Cadangan
            $table->text('catatan_penguji')->nullable();
            $table->text('jawaban_santri_json')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'pelanggaran_curang_count',
                'pelanggaran_curang_log',
                'status_kelulusan_override',
                'catatan_penguji',
                'jawaban_santri_json'
            ]);
        });
    }
};

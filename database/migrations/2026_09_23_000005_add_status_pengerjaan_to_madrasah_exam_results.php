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
        Schema::table('madrasah_exam_results', function (Blueprint $table) {
            if (!Schema::hasColumn('madrasah_exam_results', 'status_pengerjaan')) {
                $table->string('status_pengerjaan', 30)->default('Belum Mulai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('madrasah_exam_results', function (Blueprint $table) {
            if (Schema::hasColumn('madrasah_exam_results', 'status_pengerjaan')) {
                $table->dropColumn('status_pengerjaan');
            }
        });
    }
};

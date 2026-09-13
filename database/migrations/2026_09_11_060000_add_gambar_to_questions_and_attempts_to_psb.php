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
        if (Schema::hasTable('questions') && !Schema::hasColumn('questions', 'gambar')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->string('gambar')->nullable()->after('soal');
            });
        }

        if (Schema::hasTable('psb_registrations') && !Schema::hasColumn('psb_registrations', 'cbt_attempts_count')) {
            Schema::table('psb_registrations', function (Blueprint $table) {
                $table->integer('cbt_attempts_count')->default(0)->after('status_ujian');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('questions') && Schema::hasColumn('questions', 'gambar')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('gambar');
            });
        }

        if (Schema::hasTable('psb_registrations') && Schema::hasColumn('psb_registrations', 'cbt_attempts_count')) {
            Schema::table('psb_registrations', function (Blueprint $table) {
                $table->dropColumn('cbt_attempts_count');
            });
        }
    }
};

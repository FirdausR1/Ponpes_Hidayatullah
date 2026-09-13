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
            if (!Schema::hasColumn('psb_registrations', 'cbt_soal_urutan_json')) {
                $table->longText('cbt_soal_urutan_json')->nullable();
            }
        });

        Schema::table('psb_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('psb_registrations', 'cbt_nilai_per_mapel_json')) {
                $table->longText('cbt_nilai_per_mapel_json')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('psb_registrations', 'cbt_soal_urutan_json')) {
                $table->dropColumn('cbt_soal_urutan_json');
            }
            if (Schema::hasColumn('psb_registrations', 'cbt_nilai_per_mapel_json')) {
                $table->dropColumn('cbt_nilai_per_mapel_json');
            }
        });
    }
};

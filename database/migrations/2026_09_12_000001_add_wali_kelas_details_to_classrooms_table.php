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
        if (!Schema::hasColumn('classrooms', 'kontak_wali')) {
            Schema::table('classrooms', function (Blueprint $table) {
                $table->string('kontak_wali', 50)->nullable()->after('wali_kelas');
            });
        }
        if (!Schema::hasColumn('classrooms', 'nip_wali')) {
            Schema::table('classrooms', function (Blueprint $table) {
                $table->string('nip_wali', 50)->nullable()->after('wali_kelas');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            if (Schema::hasColumn('classrooms', 'nip_wali')) {
                $table->dropColumn('nip_wali');
            }
            if (Schema::hasColumn('classrooms', 'kontak_wali')) {
                $table->dropColumn('kontak_wali');
            }
        });
    }
};

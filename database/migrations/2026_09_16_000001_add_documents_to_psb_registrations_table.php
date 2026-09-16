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
            if (!Schema::hasColumn('psb_registrations', 'file_akta_kelahiran')) {
                $table->string('file_akta_kelahiran')->nullable();
            }
            if (!Schema::hasColumn('psb_registrations', 'file_kk')) {
                $table->string('file_kk')->nullable();
            }
            if (!Schema::hasColumn('psb_registrations', 'file_ktp_ortu')) {
                $table->string('file_ktp_ortu')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_registrations', function (Blueprint $table) {
            $table->dropColumn(['file_akta_kelahiran', 'file_kk', 'file_ktp_ortu']);
        });
    }
};

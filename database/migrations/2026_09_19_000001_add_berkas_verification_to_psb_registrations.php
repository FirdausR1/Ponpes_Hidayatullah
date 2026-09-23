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
            if (!Schema::hasColumn('psb_registrations', 'berkas_status')) {
                $table->string('berkas_status')->default('Belum Diperiksa');
            }
            if (!Schema::hasColumn('psb_registrations', 'berkas_catatan')) {
                $table->text('berkas_catatan')->nullable();
            }
            if (!Schema::hasColumn('psb_registrations', 'berkas_detail_json')) {
                $table->text('berkas_detail_json')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_registrations', function (Blueprint $table) {
            $table->dropColumn(['berkas_status', 'berkas_catatan', 'berkas_detail_json']);
        });
    }
};

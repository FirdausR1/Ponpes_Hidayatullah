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
        // 1. Create settings table for CMS content
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general'); // profil, jadwal, sosmed, kontak
            $table->timestamps();
        });

        // 2. Add photo compliance status to psb_registrations
        Schema::table('psb_registrations', function (Blueprint $table) {
            $table->string('foto_status')->default('Belum Diperiksa')->after('pas_foto'); // Belum Diperiksa, Sesuai, Perlu Perbaikan
            $table->string('foto_catatan')->nullable()->after('foto_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');

        Schema::table('psb_registrations', function (Blueprint $table) {
            $table->dropColumn(['foto_status', 'foto_catatan']);
        });
    }
};

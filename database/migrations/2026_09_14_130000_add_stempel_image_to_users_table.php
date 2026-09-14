<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Stempel cap resmi per-bendahara untuk kwitansi (terpisah dari stempel PSB global)
            $table->string('stempel_image')->nullable()->after('signature_image');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('stempel_image');
        });
    }
};

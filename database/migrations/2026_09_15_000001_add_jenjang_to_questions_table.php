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
        if (Schema::hasTable('questions') && !Schema::hasColumn('questions', 'jenjang')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->string('jenjang', 50)->default('Semua')->after('kategori');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('questions') && Schema::hasColumn('questions', 'jenjang')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('jenjang');
            });
        }
    }
};

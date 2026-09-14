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
        Schema::table('operational_expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('operational_expenses', 'jenjang')) {
                $table->string('jenjang', 20)->default('Semua/Umum')->after('kategori');
            }
        });

        Schema::table('operational_expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('operational_expenses', 'sumber_pos')) {
                $table->string('sumber_pos', 50)->nullable()->after('kategori');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operational_expenses', function (Blueprint $table) {
            if (Schema::hasColumn('operational_expenses', 'sumber_pos')) {
                $table->dropColumn('sumber_pos');
            }
            if (Schema::hasColumn('operational_expenses', 'jenjang')) {
                $table->dropColumn('jenjang');
            }
        });
    }
};

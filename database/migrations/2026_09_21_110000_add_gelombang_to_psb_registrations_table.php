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
        if (Schema::hasTable('psb_registrations') && !Schema::hasColumn('psb_registrations', 'gelombang')) {
            Schema::table('psb_registrations', function (Blueprint $table) {
                $table->string('gelombang')->nullable()->default('Gelombang 1')->after('jalur');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('psb_registrations') && Schema::hasColumn('psb_registrations', 'gelombang')) {
            Schema::table('psb_registrations', function (Blueprint $table) {
                $table->dropColumn('gelombang');
            });
        }
    }
};

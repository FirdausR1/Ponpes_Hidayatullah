<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement('ALTER TABLE student_payments MODIFY student_id BIGINT UNSIGNED NULL;');
        } catch (\Throwable $e) {}

        if (!Schema::hasColumn('student_payments', 'psb_registration_id')) {
            Schema::table('student_payments', function (Blueprint $table) {
                $table->foreignId('psb_registration_id')->nullable()->after('student_id')->constrained('psb_registrations')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_payments', function (Blueprint $table) {
            if (Schema::hasColumn('student_payments', 'psb_registration_id')) {
                $table->dropConstrainedForeignId('psb_registration_id');
            }
        });
    }
};

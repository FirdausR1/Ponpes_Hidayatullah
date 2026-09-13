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
        if (!Schema::hasColumn('users', 'signature_image')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('signature_image')->nullable()->after('role');
            });
        }

        if (!Schema::hasColumn('users', 'jabatan')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('jabatan')->nullable()->after('signature_image');
            });
        }

        if (!Schema::hasColumn('student_payments', 'user_id')) {
            Schema::table('student_payments', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('student_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'jabatan')) {
                $table->dropColumn('jabatan');
            }
            if (Schema::hasColumn('users', 'signature_image')) {
                $table->dropColumn('signature_image');
            }
        });

        Schema::table('student_payments', function (Blueprint $table) {
            if (Schema::hasColumn('student_payments', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};

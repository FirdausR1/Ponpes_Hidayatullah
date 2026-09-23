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
        if (Schema::hasTable('students')) {
            if (!Schema::hasColumn('students', 'file_kk')) {
                Schema::table('students', function (Blueprint $table) {
                    $table->string('file_kk')->nullable();
                });
            }
            if (!Schema::hasColumn('students', 'file_ktp_ortu')) {
                Schema::table('students', function (Blueprint $table) {
                    $table->string('file_ktp_ortu')->nullable();
                });
            }
            if (!Schema::hasColumn('students', 'file_akta_kelahiran')) {
                Schema::table('students', function (Blueprint $table) {
                    $table->string('file_akta_kelahiran')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (Schema::hasColumn('students', 'file_akta_kelahiran')) {
                    $table->dropColumn('file_akta_kelahiran');
                }
                if (Schema::hasColumn('students', 'file_ktp_ortu')) {
                    $table->dropColumn('file_ktp_ortu');
                }
                if (Schema::hasColumn('students', 'file_kk')) {
                    $table->dropColumn('file_kk');
                }
            });
        }
    }
};

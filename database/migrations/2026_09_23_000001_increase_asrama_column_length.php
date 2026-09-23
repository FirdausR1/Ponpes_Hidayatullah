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
        // 1. Ubah dormitories.nama_asrama menjadi VARCHAR(255)
        if (Schema::hasTable('dormitories') && Schema::hasColumn('dormitories', 'nama_asrama')) {
            try {
                DB::statement('ALTER TABLE dormitories MODIFY COLUMN nama_asrama VARCHAR(255) NOT NULL');
            } catch (\Throwable $e) {
                Schema::table('dormitories', function (Blueprint $table) {
                    $table->string('nama_asrama', 255)->change();
                });
            }
        }

        // 2. Ubah students.kamar_asrama menjadi VARCHAR(255)
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'kamar_asrama')) {
            try {
                DB::statement('ALTER TABLE students MODIFY COLUMN kamar_asrama VARCHAR(255) NULL');
            } catch (\Throwable $e) {
                Schema::table('students', function (Blueprint $table) {
                    $table->string('kamar_asrama', 255)->nullable()->change();
                });
            }
        }

        // 3. Ubah student_mutations.kamar_dari dan kamar_ke menjadi VARCHAR(255)
        if (Schema::hasTable('student_mutations')) {
            if (Schema::hasColumn('student_mutations', 'kamar_dari')) {
                try {
                    DB::statement('ALTER TABLE student_mutations MODIFY COLUMN kamar_dari VARCHAR(255) NULL');
                } catch (\Throwable $e) {
                    Schema::table('student_mutations', function (Blueprint $table) {
                        $table->string('kamar_dari', 255)->nullable()->change();
                    });
                }
            }
            if (Schema::hasColumn('student_mutations', 'kamar_ke')) {
                try {
                    DB::statement('ALTER TABLE student_mutations MODIFY COLUMN kamar_ke VARCHAR(255) NULL');
                } catch (\Throwable $e) {
                    Schema::table('student_mutations', function (Blueprint $table) {
                        $table->string('kamar_ke', 255)->nullable()->change();
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('dormitories') && Schema::hasColumn('dormitories', 'nama_asrama')) {
            try {
                DB::statement('ALTER TABLE dormitories MODIFY COLUMN nama_asrama VARCHAR(100) NOT NULL');
            } catch (\Throwable $e) {}
        }
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'kamar_asrama')) {
            try {
                DB::statement('ALTER TABLE students MODIFY COLUMN kamar_asrama VARCHAR(100) NULL');
            } catch (\Throwable $e) {}
        }
        if (Schema::hasTable('student_mutations')) {
            try {
                DB::statement('ALTER TABLE student_mutations MODIFY COLUMN kamar_dari VARCHAR(100) NULL');
                DB::statement('ALTER TABLE student_mutations MODIFY COLUMN kamar_ke VARCHAR(100) NULL');
            } catch (\Throwable $e) {}
        }
    }
};

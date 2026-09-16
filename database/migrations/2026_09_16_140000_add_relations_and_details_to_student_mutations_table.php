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
        Schema::table('student_mutations', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('student_id');
            $table->string('nama_santri')->nullable();
            $table->string('nis', 30)->nullable();
            $table->string('jenjang', 50)->nullable();
            $table->string('kamar_dari', 100)->nullable();
            $table->string('kamar_ke', 100)->nullable();
        });

        Schema::table('student_mutations', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_mutations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'nama_santri',
                'nis',
                'jenjang',
                'kamar_dari',
                'kamar_ke',
            ]);
        });
    }
};

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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'username')) {
                $table->string('username', 50)->nullable()->unique();
            }
            if (!Schema::hasColumn('students', 'tanggal_lahir')) {
                $table->string('tanggal_lahir', 30)->nullable();
            }
            if (!Schema::hasColumn('students', 'password')) {
                $table->string('password')->nullable();
            }
            if (!Schema::hasColumn('students', 'remember_token')) {
                $table->rememberToken();
            }
            if (!Schema::hasColumn('students', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['username', 'tanggal_lahir', 'password', 'remember_token', 'last_login_at']);
        });
    }
};

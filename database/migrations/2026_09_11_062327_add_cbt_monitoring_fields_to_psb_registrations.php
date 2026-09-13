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
        Schema::table('psb_registrations', function (Blueprint $table) {
            $table->timestamp('cbt_last_activity_at')->nullable();
            $table->integer('cbt_extra_time_minutes')->default(0);
            $table->text('cbt_proctor_message')->nullable();
            $table->longText('cbt_ragu_json')->nullable();
            $table->integer('cbt_current_question_index')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'cbt_last_activity_at',
                'cbt_extra_time_minutes',
                'cbt_proctor_message',
                'cbt_ragu_json',
                'cbt_current_question_index',
            ]);
        });
    }
};

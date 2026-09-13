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
        if (!Schema::hasColumn('psb_registrations', 'metode_pembayaran')) {
            Schema::table('psb_registrations', function (Blueprint $table) {
                $table->string('metode_pembayaran', 30)->nullable()->default('Transfer Bank')->after('status_pembayaran');
            });
        }

        if (!Schema::hasColumn('psb_registrations', 'tanggal_bayar')) {
            Schema::table('psb_registrations', function (Blueprint $table) {
                $table->date('tanggal_bayar')->nullable()->after('metode_pembayaran');
            });
        }

        if (!Schema::hasColumn('psb_registrations', 'catatan_pembayaran')) {
            Schema::table('psb_registrations', function (Blueprint $table) {
                $table->text('catatan_pembayaran')->nullable()->after('tanggal_bayar');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('psb_registrations', 'catatan_pembayaran')) {
                $table->dropColumn('catatan_pembayaran');
            }
            if (Schema::hasColumn('psb_registrations', 'tanggal_bayar')) {
                $table->dropColumn('tanggal_bayar');
            }
            if (Schema::hasColumn('psb_registrations', 'metode_pembayaran')) {
                $table->dropColumn('metode_pembayaran');
            }
        });
    }
};

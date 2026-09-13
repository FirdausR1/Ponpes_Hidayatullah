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
        if (!Schema::hasColumn('psb_registrations', 'status_pembayaran')) {
            Schema::table('psb_registrations', function (Blueprint $table) {
                $table->string('status_pembayaran', 50)->default('Menunggu Verifikasi')->after('bukti_transfer');
            });
        }

        if (!Schema::hasColumn('psb_registrations', 'nominal_pembayaran')) {
            Schema::table('psb_registrations', function (Blueprint $table) {
                $table->decimal('nominal_pembayaran', 14, 2)->default(3225000)->after('status_pembayaran');
            });
        }

        // Inisialisasi status pembayaran untuk data yang sudah ada
        DB::table('psb_registrations')
            ->whereNotNull('bukti_transfer')
            ->update([
                'status_pembayaran' => 'Lunas',
                'nominal_pembayaran' => 3225000
            ]);

        DB::table('psb_registrations')
            ->whereNull('bukti_transfer')
            ->update([
                'status_pembayaran' => 'Belum Bayar',
                'nominal_pembayaran' => 0
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_registrations', function (Blueprint $table) {
            $table->dropColumn(['status_pembayaran', 'nominal_pembayaran']);
        });
    }
};

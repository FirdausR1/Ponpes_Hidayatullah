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
        // 1. Tabel Tagihan Santri (Student Bills / Invoices)
        if (!Schema::hasTable('student_bills')) {
            Schema::create('student_bills', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->string('kategori', 30)->default('bulanan'); // sekali_bayar, bulanan, tahunan, tambahan
                $table->string('pos_biaya', 50); // MAKAN, SYAHRIYAH, SOT, TAB, ZIARAH, WISUDA, PANGKAL, GEDUNG, dll.
                $table->string('judul_tagihan', 150); // Contoh: Syahriyah Mei 2026, Ziarah Religi Kelas X
                $table->string('bulan', 20)->nullable(); // Mei, Juni, dll.
                $table->string('tahun', 10)->default('2026');
                $table->decimal('nominal_asli', 12, 2)->default(0);
                $table->decimal('nominal_potongan', 12, 2)->default(0);
                $table->string('alasan_potongan')->nullable(); // SKTM No. 401/DS/2026 / Beasiswa Prestasi
                $table->decimal('nominal_tagihan', 12, 2)->default(0); // nominal_asli - nominal_potongan
                $table->decimal('nominal_bayar', 12, 2)->default(0);
                $table->decimal('sisa_tagihan', 12, 2)->default(0);
                $table->string('status', 30)->default('Belum Bayar'); // Belum Bayar, Cicilan, Lunas
                $table->date('jatuh_tempo')->nullable();
                $table->string('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 2. Tabel Rincian Pos per Transaksi Pembayaran (Student Payment Items)
        if (!Schema::hasTable('student_payment_items')) {
            Schema::create('student_payment_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_id')->constrained('student_payments')->cascadeOnDelete();
                $table->unsignedBigInteger('student_bill_id')->nullable();
                $table->string('pos_biaya', 50); // MAKAN, SYAHRIYAH, SOT, TAB, ZIARAH, dll.
                $table->decimal('nominal', 12, 2)->default(0);
                $table->string('keterangan')->nullable();
                $table->timestamps();

                $table->foreign('student_bill_id')->references('id')->on('student_bills')->nullOnDelete();
            });
        }

        // 3. Tabel Keringanan & Potongan Biaya (Discounts / Scholarships / SKTM)
        if (!Schema::hasTable('student_discounts')) {
            Schema::create('student_discounts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->string('jenis_potongan', 50); // SKTM / Miskin, Prestasi, Tahfidz, Kebijakan Pengasuh
                $table->string('no_surat_miskin', 100)->nullable();
                $table->string('file_surat_miskin')->nullable();
                $table->string('tipe_nilai', 20)->default('nominal'); // nominal, persen
                $table->decimal('nilai', 12, 2)->default(0); // Misal Rp 50.000 atau 50%
                $table->string('pos_biaya', 50)->default('Semua Bulanan'); // Semua Bulanan, MAKAN, SYAHRIYAH, GEDUNG, dll.
                $table->date('berlaku_mulai')->nullable();
                $table->date('berlaku_sampai')->nullable();
                $table->string('status', 20)->default('Aktif'); // Aktif, Nonaktif
                $table->text('catatan')->nullable();
                $table->string('created_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_discounts');
        Schema::dropIfExists('student_payment_items');
        Schema::dropIfExists('student_bills');
    }
};

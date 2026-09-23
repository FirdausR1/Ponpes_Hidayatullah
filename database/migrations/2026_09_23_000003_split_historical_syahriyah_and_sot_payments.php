<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\StudentPayment;
use App\Models\StudentPaymentItem;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Retroactively split historical payments where Syahriyah included SOT.
     */
    public function up(): void
    {
        try {
            $payments = StudentPayment::with(['items', 'student'])->get();

            foreach ($payments as $payment) {
                // Lewati jika sudah memiliki pos SOT
                $hasSot = $payment->items->contains(function ($it) {
                    return stripos($it->pos_biaya ?? '', 'SOT') !== false;
                });

                if ($hasSot) {
                    continue;
                }

                $syahItem = $payment->items->first(function ($it) {
                    return stripos($it->pos_biaya ?? '', 'SYAHRIYAH') !== false || stripos($it->pos_biaya ?? '', 'SPP') !== false;
                });

                if (!$syahItem) {
                    continue;
                }

                $nom = (float) $syahItem->nominal;
                $isMa = stripos($payment->student->jenjang ?? '', 'MA') !== false;
                $sotPerMonth = $isMa ? 75000 : 55000;
                $syahPerMonth = 30000;
                $stdTotal = $sotPerMonth + $syahPerMonth; // 85k (MTs) atau 105k (MA)

                if ($nom >= $stdTotal) {
                    $months = max(1, floor($nom / $stdTotal));
                    $sotPart = $months * $sotPerMonth;
                    $syahPart = $nom - $sotPart;
                } elseif ($nom > $syahPerMonth) {
                    $syahPart = $syahPerMonth;
                    $sotPart = $nom - $syahPerMonth;
                } else {
                    $syahPart = $nom;
                    $sotPart = 0;
                }

                if ($sotPart > 0) {
                    // Update nominal Syahriyah ke nilai murni
                    $syahItem->nominal = $syahPart;
                    $syahItem->pos_biaya = 'SYAHRIYAH';
                    $syahItem->save();

                    // Buat baris baru untuk SOT
                    StudentPaymentItem::create([
                        'payment_id' => $payment->id,
                        'pos_biaya'  => 'SOT',
                        'nominal'    => $sotPart,
                        'keterangan' => 'Pemisahan Iuran SOT (RAB Keuangan) dari pembayaran Syahriyah',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Log error tanpa menghentikan migrasi bila tabel belum siap
            logger()->error('Gagal migrasi pemisahan SOT: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback tidak diperlukan karena pemisahan pos biaya adalah perbaikan data
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPaymentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'student_bill_id',
        'pos_biaya',
        'nominal',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'float',
    ];

    /**
     * Relasi ke kwitansi / transaksi induk pembayaran.
     */
    public function payment()
    {
        return $this->belongsTo(StudentPayment::class, 'payment_id');
    }

    /**
     * Relasi ke tagihan asal (jika ada).
     */
    public function bill()
    {
        return $this->belongsTo(StudentBill::class, 'student_bill_id');
    }

    /**
     * Format Rupiah.
     */
    public function getFormattedNominalAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}

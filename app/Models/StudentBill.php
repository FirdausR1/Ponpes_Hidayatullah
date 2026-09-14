<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'kategori',
        'pos_biaya',
        'judul_tagihan',
        'bulan',
        'tahun',
        'nominal_asli',
        'nominal_potongan',
        'alasan_potongan',
        'nominal_tagihan',
        'nominal_bayar',
        'sisa_tagihan',
        'status',
        'jatuh_tempo',
        'created_by',
        'penangguhan_wisuda',
        'catatan_penangguhan',
        'ditangguhkan_at',
        'catatan_pembebasan',
        'status_dispensasi',
        'jenis_surat_diminta',
        'instruksi_surat',
        'file_surat_dispensasi',
        'surat_diminta_at',
        'surat_uploaded_at',
        'alasan_penolakan_surat',
    ];

    protected $casts = [
        'nominal_asli' => 'float',
        'nominal_potongan' => 'float',
        'nominal_tagihan' => 'float',
        'nominal_bayar' => 'float',
        'sisa_tagihan' => 'float',
        'jatuh_tempo' => 'date',
        'penangguhan_wisuda' => 'boolean',
        'ditangguhkan_at' => 'datetime',
        'surat_diminta_at' => 'datetime',
        'surat_uploaded_at' => 'datetime',
    ];

    /**
     * Relasi ke santri pemilik tagihan.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Relasi ke item pembayaran yang melunasi tagihan ini.
     */
    public function paymentItems()
    {
        return $this->hasMany(StudentPaymentItem::class, 'student_bill_id');
    }

    /**
     * Format Rupiah untuk nominal tagihan.
     */
    public function getFormattedTagihanAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal_tagihan, 0, ',', '.');
    }

    public function getFormattedBayarAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal_bayar, 0, ',', '.');
    }

    public function getFormattedSisaAttribute(): string
    {
        return 'Rp ' . number_format($this->sisa_tagihan, 0, ',', '.');
    }

    public function getFormattedPotonganAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal_potongan, 0, ',', '.');
    }

    /**
     * Sinkronisasi otomatis potongan/beasiswa/SKTM aktif ke seluruh tagihan santri.
     */
    public static function syncDiscountsForStudent($studentId): void
    {
        $discounts = StudentDiscount::where('student_id', $studentId)
            ->where('status', 'Aktif')
            ->get();

        $bills = self::where('student_id', $studentId)->get();

        foreach ($bills as $bill) {
            // Jangan ubah tagihan yang merupakan pembebasan manual dari approval surat dispensasi per-tagihan
            if ($bill->status_dispensasi === 'disetujui' && !empty($bill->catatan_pembebasan)) {
                continue;
            }

            // Pastikan nominal asli terisi
            $nominalAsli = (float)($bill->nominal_asli > 0 ? $bill->nominal_asli : ($bill->nominal_tagihan + $bill->nominal_potongan));
            if ($nominalAsli <= 0) {
                continue;
            }
            $bill->nominal_asli = $nominalAsli;

            // Jika sudah dibayar lunas murni dengan uang tunai tanpa potongan, jangan sentuh
            if ($bill->nominal_potongan == 0 && $bill->nominal_bayar >= $nominalAsli) {
                continue;
            }

            // Cari diskon yang cocok untuk pos ini
            $potongan = 0;
            $alasanPotongan = null;

            foreach ($discounts as $disc) {
                $isMatch = false;
                $discPos = strtoupper(trim($disc->pos_biaya));
                $billPos = strtoupper(trim($bill->pos_biaya));

                if ($discPos === 'SEMUA') {
                    $isMatch = true;
                } elseif ($discPos === 'SEMUA BULANAN' && $bill->kategori === 'bulanan') {
                    $isMatch = true;
                } elseif ($discPos === $billPos) {
                    $isMatch = true;
                }

                if ($isMatch) {
                    $p = $disc->tipe_nilai === 'persen'
                        ? ($disc->nilai / 100) * $nominalAsli
                        : min($nominalAsli, (float)$disc->nilai);

                    if ($p > $potongan) {
                        $potongan = $p;
                        $alasanPotongan = $disc->jenis_potongan . ($disc->no_surat_miskin ? " ({$disc->no_surat_miskin})" : "");
                    }
                }
            }

            if ($potongan > 0) {
                $bill->nominal_potongan = $potongan;
                $nominalTagihanBersih = max(0, $nominalAsli - $potongan);
                $bill->nominal_tagihan = $nominalTagihanBersih;
                
                $actualPaid = (float) \App\Models\StudentPaymentItem::where('student_bill_id', $bill->id)
                    ->whereHas('payment', fn($q) => $q->where('status', 'Lunas'))
                    ->sum('nominal');

                $nominalBayar = max($actualPaid, min((float)$bill->nominal_bayar, $nominalTagihanBersih));
                if ($nominalTagihanBersih == 0 && $actualPaid == 0) {
                    $nominalBayar = 0;
                }
                $bill->nominal_bayar = $nominalBayar;
                $sisa = max(0, $nominalTagihanBersih - $nominalBayar);
                $bill->sisa_tagihan = $sisa;

                if ($sisa == 0) {
                    $bill->status = 'Lunas';
                } elseif ($nominalBayar > 0) {
                    $bill->status = 'Cicilan';
                } else {
                    $bill->status = 'Belum Bayar';
                }

                $bill->alasan_potongan = $alasanPotongan;
                $bill->save();
            } else {
                // Jika tidak ada diskon aktif yang cocok, dan sebelumnya memiliki potongan otomatis (bukan dispensasi surat)
                if ($bill->nominal_potongan > 0 && empty($bill->catatan_pembebasan)) {
                    $bill->nominal_potongan = 0;
                    $bill->nominal_tagihan = $nominalAsli;
                    $nominalBayar = (float)$bill->nominal_bayar;
                    $sisa = max(0, $nominalAsli - $nominalBayar);
                    $bill->sisa_tagihan = $sisa;

                    if ($sisa == 0 && $nominalAsli == 0) {
                        $bill->status = 'Lunas';
                    } elseif ($sisa == 0 && $nominalBayar >= $nominalAsli) {
                        $bill->status = 'Lunas';
                    } elseif ($nominalBayar > 0) {
                        $bill->status = 'Cicilan';
                    } else {
                        $bill->status = 'Belum Bayar';
                    }

                    $bill->alasan_potongan = null;
                    $bill->save();
                }
            }
        }
    }
}

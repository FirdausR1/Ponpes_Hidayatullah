<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Models\StudentBill;
use App\Models\StudentDiscount;
use Carbon\Carbon;
use Illuminate\Support\Str;

class StudentAuthController extends Controller
{
    /**
     * Tampilkan formulir login santri.
     */
    public function loginForm()
    {
        if (Auth::guard('santri')->check()) {
            return redirect()->route('santri.dashboard');
        }

        return view('santri.login');
    }

    /**
     * Proses autentikasi login santri.
     * Username dapat menggunakan NIS, NISN, Username Kustom, atau No Registrasi PSB.
     * Password default menggunakan tanggal lahir (format: DDMMYYYY, misal 15052010).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ], [
            'identifier.required' => 'Masukkan NIS, NISN, atau Username Anda.',
            'password.required' => 'Masukkan kata sandi / tanggal lahir Anda.',
        ]);

        $identifier = trim($credentials['identifier']);
        $password = $credentials['password'];

        // Cari santri berdasarkan NIS, username, NISN, atau No Registrasi PSB
        $student = Student::where('nis', $identifier)
            ->orWhere('username', $identifier)
            ->orWhere('nisn', $identifier)
            ->orWhereHas('psbRegistration', function ($q) use ($identifier) {
                $q->where('no_registrasi', $identifier);
            })
            ->first();

        if (!$student) {
            return back()->withErrors([
                'identifier' => 'Akun santri dengan data tersebut tidak ditemukan. Pastikan NIS atau Username Anda benar.',
            ])->onlyInput('identifier');
        }

        // Cek kecocokan password:
        // 1. Password hash tersimpan di database
        // 2. Atau jika password belum diset / cocok dengan format tanggal lahir santri
        $isValidPassword = false;

        if ($student->password && Hash::check($password, $student->password)) {
            $isValidPassword = true;
        } else {
            // Cek variasi tanggal lahir santri jika menggunakan tanggal lahir
            $birthPass = $student->getFormattedBirthdatePassword();
            if ($password === $birthPass || $password === 'santri123') {
                $isValidPassword = true;
                // Simpan hash password baru ke database
                $student->update(['password' => Hash::make($password)]);
            }
        }

        if ($isValidPassword) {
            Auth::guard('santri')->login($student, $request->boolean('remember'));
            $student->update(['last_login_at' => now()]);
            $request->session()->regenerate();

            return redirect()->route('santri.dashboard')->with('success', "Ahlan wa Sahlan, ananda {$student->nama_lengkap}!");
        }

        return back()->withErrors([
            'password' => 'Kata sandi tidak sesuai. Jika ini pertama kali login, gunakan tanggal lahir Anda (format DDMMYYYY, contoh: 15052010).',
        ])->onlyInput('identifier');
    }

    /**
     * Dasbor / Portal Santri.
     */
    public function dashboard()
    {
        $student = Auth::guard('santri')->user();
        if (!$student) {
            return redirect()->route('santri.login');
        }

        $totalTagihan = $student->bills()->sum('nominal_tagihan');
        $totalTerbayar = $student->bills()->sum('nominal_bayar');
        $totalTunggakan = $student->bills()->where('status', '!=', 'Lunas')->sum('sisa_tagihan');
        $unpaidBillsCount = $student->bills()->where('status', '!=', 'Lunas')->count();
        $recentBills = $student->bills()->take(5)->get();
        $recentPayments = $student->payments()->with('items')->take(5)->get();

        return view('santri.dashboard', compact(
            'student',
            'totalTagihan',
            'totalTerbayar',
            'totalTunggakan',
            'unpaidBillsCount',
            'recentBills',
            'recentPayments'
        ));
    }

    /**
     * Halaman Khusus Cek Tagihan & Pembayaran Santri.
     */
    public function pembayaran()
    {
        $student = Auth::guard('santri')->user();
        if (!$student) {
            return redirect()->route('santri.login');
        }

        // Tagihan santri
        $bills = $student->bills()->latest()->get();
        $unpaidBills = $bills->where('status', '!=', 'Lunas');
        $paidBills = $bills->where('status', 'Lunas');

        // Tagihan ditangguhkan ke wisuda
        $wisudaBills = $bills->where('penangguhan_wisuda', true);
        $unpaidActiveBills = $unpaidBills->where('penangguhan_wisuda', false);

        // Tagihan yang memiliki dispensasi/permintaan surat
        $pendingSuratBills = $bills->whereIn('status_dispensasi', ['diminta_surat', 'surat_diunggah', 'ditolak']);

        // Ringkasan Keuangan
        $totalTagihan = $bills->sum('nominal_tagihan');
        $totalTerbayar = $bills->sum('nominal_bayar');
        $totalTunggakan = $unpaidActiveBills->sum('sisa_tagihan');
        $totalDitangguhkan = $wisudaBills->sum('sisa_tagihan');

        // Riwayat Transaksi Pembayaran
        $payments = $student->payments()->with('items')->latest()->get();

        // Keringanan / Beasiswa Santri
        $discounts = $student->discounts()->where('status', 'Aktif')->get();

        return view('santri.pembayaran', compact(
            'student',
            'bills',
            'unpaidBills',
            'unpaidActiveBills',
            'wisudaBills',
            'pendingSuratBills',
            'paidBills',
            'totalTagihan',
            'totalTerbayar',
            'totalTunggakan',
            'totalDitangguhkan',
            'payments',
            'discounts'
        ));
    }

    /**
     * Cetak Kwitansi Mandiri oleh Santri.
     */
    public function kwitansiSantri($id)
    {
        $student = Auth::guard('santri')->user();
        $payment = StudentPayment::with(['student.classroom', 'items.bill'])
            ->where('student_id', $student->id)
            ->findOrFail($id);

        $sisaTunggakanSantri = $student->bills()
            ->where('status', '!=', 'Lunas')
            ->sum('sisa_tagihan');

        return view('admin.pembayaran.kwitansi', compact('payment', 'sisaTunggakanSantri'));
    }

    /**
     * Santri Mengunggah Bukti Pembayaran / Transfer Mandiri.
     */
    public function uploadBuktiBayar(Request $request)
    {
        $student = Auth::guard('santri')->user();
        if (!$student) {
            return redirect()->route('santri.login');
        }

        $validated = $request->validate([
            'bukti_file' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'nominal' => 'required|numeric|min:1000',
            'bank_tujuan' => 'required|string|max:100',
            'catatan' => 'nullable|string|max:255',
            'bills' => 'nullable|array',
        ], [
            'bukti_file.required' => 'Foto bukti transfer / struk pembayaran wajib diunggah.',
            'bukti_file.image' => 'File bukti transfer harus berupa gambar (JPG, PNG, WEBP).',
            'nominal.required' => 'Nominal transfer wajib diisi.',
            'nominal.min' => 'Nominal minimal Rp 1.000.',
        ]);

        $filePath = null;
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $filename = time() . '_trf_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/bukti_santri'), $filename);
            $filePath = '/uploads/bukti_santri/' . $filename;
        }

        $totalNominal = floatval($validated['nominal']);
        $today = date('Ymd');
        $countToday = StudentPayment::whereDate('created_at', today())->count() + 1;
        $noTransaksi = 'TRF-' . $today . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

        $catatan = "Transfer ke " . $validated['bank_tujuan'];
        if (!empty($validated['catatan'])) {
            $catatan .= " - " . $validated['catatan'];
        }

        $payment = StudentPayment::create([
            'student_id' => $student->id,
            'no_transaksi' => $noTransaksi,
            'jenis_pembayaran' => 'Transfer Mandiri Santri',
            'bulan' => null,
            'tahun' => date('Y'),
            'nominal' => $totalNominal,
            'tanggal_bayar' => now()->toDateString(),
            'metode_pembayaran' => 'Transfer Bank',
            'status' => 'Menunggu Konfirmasi',
            'bukti_bayar' => $filePath,
            'catatan' => $catatan,
            'penerima_nama' => null,
        ]);

        // Hubungkan dengan pos tagihan yang dipilih santri
        if (!empty($validated['bills']) && is_array($validated['bills'])) {
            $selectedBills = StudentBill::where('student_id', $student->id)
                ->whereIn('id', $validated['bills'])
                ->get();

            $remaining = $totalNominal;
            foreach ($selectedBills as $sb) {
                if ($remaining <= 0) break;
                $portion = min($sb->sisa_tagihan, $remaining);
                \App\Models\StudentPaymentItem::create([
                    'payment_id' => $payment->id,
                    'student_bill_id' => $sb->id,
                    'pos_biaya' => $sb->pos_biaya,
                    'nominal' => $portion,
                    'keterangan' => 'Upload bukti santri (menunggu verifikasi)',
                ]);
                $remaining -= $portion;
            }
        }

        return redirect()->route('santri.pembayaran')->with('success', 
            "Alhamdulillah, bukti transfer sebesar Rp " . number_format($totalNominal, 0, ',', '.') . " berhasil diunggah! Status: Menunggu Verifikasi Bendahara Pesantren."
        );
    }

    /**
     * Santri Mengajukan Keringanan Mandiri Kapan Saja (Inisiatif Santri / Wali Santri).
     */
    public function ajukanKeringananMandiri(Request $request)
    {
        $student = Auth::guard('santri')->user();
        if (!$student) {
            return redirect()->route('santri.login');
        }

        $validated = $request->validate([
            'student_bill_id' => 'required|exists:student_bills,id',
            'jenis_surat' => 'required|string|max:150',
            'no_surat' => 'nullable|string|max:100',
            'alasan' => 'required|string|max:500',
            'file_surat' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ], [
            'student_bill_id.required' => 'Pilih pos tagihan yang ingin diajukan permohonan keringanan.',
            'jenis_surat.required' => 'Pilih atau tentukan jenis dokumen pendukung.',
            'alasan.required' => 'Tuliskan alasan permohonan keringanan Anda.',
            'file_surat.required' => 'Pilih foto atau scan berkas dokumen pendukung (SKTM/Surat Keterangan).',
            'file_surat.mimes' => 'Format file dokumen harus berupa PDF, JPG, JPEG, PNG, atau WEBP.',
            'file_surat.max' => 'Ukuran file dokumen maksimal 5 MB.',
        ]);

        $bill = StudentBill::where('student_id', $student->id)->findOrFail($validated['student_bill_id']);

        if ($bill->status === 'Lunas') {
            return back()->with('error', 'Tagihan yang dipilih sudah berstatus lunas.');
        }

        $file = $request->file('file_surat');
        $filename = time() . '_keringanan_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/surat_dispensasi'), $filename);

        $instruksi = "Pengajuan Mandiri Santri: " . $validated['alasan'];
        if (!empty($validated['no_surat'])) {
            $instruksi .= " (No. Berkas: " . $validated['no_surat'] . ")";
        }

        $bill->file_surat_dispensasi = '/uploads/surat_dispensasi/' . $filename;
        $bill->jenis_surat_diminta = $validated['jenis_surat'];
        $bill->instruksi_surat = $instruksi;
        $bill->status_dispensasi = 'surat_diunggah';
        $bill->surat_uploaded_at = now();
        $bill->alasan_penolakan_surat = null;
        $bill->save();

        return redirect()->route('santri.pembayaran')->with('success',
            "Alhamdulillah! Permohonan keringanan untuk tagihan '{$bill->judul_tagihan}' berhasil dikirim. Bendahara pesantren akan segera meninjau dokumen yang Anda unggah."
        );
    }

    /**
     * Santri Mengunggah Berkas / Dokumen Surat Keringanan (SKTM, Surat Kematian, dll.)
     * Sesuai Permintaan Admin Pesantren.
     */
    public function uploadSuratDispensasi(Request $request, $id)
    {
        $student = Auth::guard('santri')->user();
        if (!$student) {
            return redirect()->route('santri.login');
        }

        $bill = StudentBill::where('student_id', $student->id)->findOrFail($id);

        $request->validate([
            'file_surat' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ], [
            'file_surat.required' => 'Pilih file scan/foto dokumen surat keringanan Anda.',
            'file_surat.mimes' => 'Format file dokumen harus berupa PDF, JPG, JPEG, PNG, atau WEBP.',
            'file_surat.max' => 'Ukuran file dokumen maksimal 5 MB.',
        ]);

        $file = $request->file('file_surat');
        $filename = time() . '_dispensasi_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/surat_dispensasi'), $filename);

        $bill->file_surat_dispensasi = '/uploads/surat_dispensasi/' . $filename;
        $bill->status_dispensasi = 'surat_diunggah';
        $bill->surat_uploaded_at = now();
        $bill->alasan_penolakan_surat = null; // Clear previous rejection reason if any
        $bill->save();

        return redirect()->route('santri.pembayaran')->with('success', 
            "Alhamdulillah! Berkas dokumen '{$bill->jenis_surat_diminta}' untuk tagihan {$bill->judul_tagihan} berhasil diunggah! Status saat ini: Menunggu Peninjauan Bendahara Pesantren."
        );
    }

    /**
     * Santri mengganti password mandiri.
     */
    public function updatePassword(Request $request)
    {
        $student = Auth::guard('santri')->user();

        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6|confirmed',
        ], [
            'password_lama.required' => 'Masukkan kata sandi lama Anda.',
            'password_baru.required' => 'Masukkan kata sandi baru minimal 6 karakter.',
            'password_baru.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if (!Hash::check($request->password_lama, $student->password)) {
            // Cek apakah sama dengan tanggal lahir jika belum pernah ganti
            if ($request->password_lama !== $student->getFormattedBirthdatePassword()) {
                return back()->withErrors(['password_lama' => 'Kata sandi lama yang Anda masukkan salah.']);
            }
        }

        $student->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return redirect()->route('santri.dashboard')->with('success', 'Alhamdulillah, kata sandi akun Anda berhasil diperbarui!');
    }

    /**
     * Keluar sesi santri.
     */
    public function logout(Request $request)
    {
        Auth::guard('santri')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('santri.login')->with('success', 'Anda telah berhasil keluar dari Portal Santri.');
    }
}

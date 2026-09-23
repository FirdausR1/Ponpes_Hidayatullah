<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PsbController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiayaController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\AdminCbtController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\BackupController;
use App\Models\Article;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama (Landing Page)
Route::get('/', function () {
    $latestArticles = Article::published()->latest('published_at')->take(3)->get();
    return view('landing', compact('latestArticles'));
})->name('home');

// Halaman Publik Biaya Pendidikan Mandiri
Route::get('/biaya', [BiayaController::class, 'index'])->name('biaya.index');

// Halaman Publik Berita
Route::get('/berita', [ArticleController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [ArticleController::class, 'show'])->name('berita.show');

// Pendaftaran Santri Baru (PSB) Online
Route::get('/pendaftaran', [PsbController::class, 'create'])->name('psb.register');
Route::post('/pendaftaran', [PsbController::class, 'store'])->name('psb.store');
Route::get('/pendaftaran/sukses/{id}', [PsbController::class, 'success'])->name('psb.success');
Route::get('/pendaftaran/cek-status', [PsbController::class, 'checkStatus'])->name('psb.checkStatus');
Route::post('/pendaftaran/cek-status', [PsbController::class, 'checkStatus'])->name('psb.checkStatus.submit');
Route::post('/pendaftaran/reupload-foto/{id}', [PsbController::class, 'reuploadFoto'])->name('psb.reuploadFoto');
Route::post('/pendaftaran/reupload-berkas/{id}', [PsbController::class, 'reuploadBerkas'])->name('psb.reuploadBerkas');
Route::get('/pendaftaran/cetak-kartu/{id}', [PsbController::class, 'printCard'])->name('psb.printCard');

// Portal Ujian Masuk Seleksi Santri Baru (CBT Online)
Route::get('/ujian', [ExamController::class, 'index'])->name('ujian.index');
Route::post('/ujian/masuk', [ExamController::class, 'login'])->name('ujian.login');
Route::get('/ujian/ruang', [ExamController::class, 'room'])->name('ujian.room');
Route::post('/ujian/autosave', [ExamController::class, 'autosave'])->name('ujian.autosave');
Route::post('/ujian/ping', [ExamController::class, 'ping'])->name('ujian.ping');
Route::post('/ujian/pelanggaran', [ExamController::class, 'logViolation'])->name('ujian.logViolation');
Route::post('/ujian/selesai', [ExamController::class, 'submit'])->name('ujian.submit');
Route::post('/ujian/ulang', [ExamController::class, 'retake'])->name('ujian.retake');
Route::get('/ujian/hasil', [ExamController::class, 'result'])->name('ujian.result');
Route::get('/ujian/cek-peserta', [ExamController::class, 'checkCandidate'])->name('ujian.checkCandidate');

// Autentikasi Admin TailAdmin
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/admin/login', function () {
    return redirect()->route('login');
});

// Admin Panel (Protected by Auth Middleware)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Manajemen Siswa / Santri Aktif (Akses: Superadmin, Admin, Bendahara)
    Route::get('/siswa', [StudentController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/create', [StudentController::class, 'create'])->name('siswa.create');
    Route::post('/siswa', [StudentController::class, 'store'])->name('siswa.store');
    Route::get('/siswa/template-excel', [StudentController::class, 'downloadTemplate'])->name('siswa.downloadTemplate');
    Route::get('/siswa/export-excel', [StudentController::class, 'exportExcel'])->name('siswa.exportExcel');
    Route::post('/siswa/import-excel', [StudentController::class, 'importExcel'])->name('siswa.importExcel');
    Route::post('/siswa/bulk-import-psb', [StudentController::class, 'bulkImportFromPsb'])->name('siswa.bulkImportPsb');
    Route::get('/siswa/kenaikan-kelas', [StudentController::class, 'kenaikanKelasIndex'])->name('siswa.kenaikanKelas');
    Route::post('/siswa/kenaikan-kelas', [StudentController::class, 'kenaikanKelasProcess'])->name('siswa.kenaikanKelas.process');
    Route::get('/siswa/akun-login', [StudentController::class, 'akunLoginIndex'])->name('siswa.akunLogin');
    Route::post('/siswa/akun-login/{id}/reset', [StudentController::class, 'resetPasswordSantri'])->name('siswa.resetPassword');
    Route::post('/siswa/akun-login/{id}/update-password', [StudentController::class, 'updatePasswordByAdmin'])->name('siswa.updatePassword');
    
    // Master Jenjang & Kelas (Tambah, Ubah & Hapus Kelas)
    Route::get('/siswa-kelas', [StudentController::class, 'kelasIndex'])->name('siswa.kelas.index');
    Route::post('/siswa-kelas', [StudentController::class, 'kelasStore'])->name('siswa.kelas.store');
    Route::put('/siswa-kelas/{id}', [StudentController::class, 'kelasUpdate'])->name('siswa.kelas.update');
    Route::delete('/siswa-kelas/{id}', [StudentController::class, 'kelasDestroy'])->name('siswa.kelas.destroy');

    // Pengaturan & Manajemen Wali Kelas (Homeroom Teachers)
    Route::get('/siswa-wali-kelas', [StudentController::class, 'waliKelasIndex'])->name('siswa.waliKelas.index');
    Route::put('/siswa-wali-kelas/{id}', [StudentController::class, 'waliKelasUpdate'])->name('siswa.waliKelas.update');
    Route::post('/siswa-wali-kelas/bulk', [StudentController::class, 'waliKelasBulkUpdate'])->name('siswa.waliKelas.bulkUpdate');

    // Penempatan Kelas & Asrama Santri Baru (Berdasarkan Peringkat CBT atau Pilihan Bebas Admin)
    Route::get('/siswa/penempatan-kelas', [StudentController::class, 'penempatanKelasIndex'])->name('siswa.penempatanKelas');
    Route::post('/siswa/penempatan-kelas/ranking', [StudentController::class, 'penempatanKelasRanking'])->name('siswa.penempatanKelas.ranking');
    Route::post('/siswa/penempatan-kelas/manual', [StudentController::class, 'penempatanKelasManual'])->name('siswa.penempatanKelas.manual');
    Route::post('/siswa/penempatan-kelas/quick-update', [StudentController::class, 'penempatanKelasQuickUpdate'])->name('siswa.penempatanKelas.quickUpdate');
    Route::post('/siswa/penempatan-kelas/auto-asrama', [StudentController::class, 'penempatanKelasAutoAsrama'])->name('siswa.penempatanKelas.autoAsrama');

    // Manajemen Asrama & Kamar Santri
    Route::get('/siswa-asrama', [StudentController::class, 'asramaIndex'])->name('siswa.asrama.index');
    Route::post('/siswa-asrama', [StudentController::class, 'asramaStore'])->name('siswa.asrama.store');
    Route::put('/siswa-asrama/{id}', [StudentController::class, 'asramaUpdate'])->name('siswa.asrama.update');
    Route::delete('/siswa-asrama/{id}', [StudentController::class, 'asramaDestroy'])->name('siswa.asrama.destroy');
    Route::post('/siswa-asrama/assign', [StudentController::class, 'asramaAssignStudent'])->name('siswa.asrama.assign');
    Route::post('/siswa-asrama/remove/{studentId}', [StudentController::class, 'asramaRemoveStudent'])->name('siswa.asrama.remove');

    // Mutasi Santri
    Route::get('/siswa/mutasi', [StudentController::class, 'mutasiIndex'])->name('siswa.mutasi.index');
    Route::post('/siswa/mutasi', [StudentController::class, 'mutasiStore'])->name('siswa.mutasi.store');
    Route::delete('/siswa/mutasi/{id}', [StudentController::class, 'mutasiDestroy'])->whereNumber('id')->name('siswa.mutasi.destroy');
    Route::get('/siswa/mutasi-search', [StudentController::class, 'mutasiSearch'])->name('siswa.mutasi.search');

    Route::get('/siswa/{id}', [StudentController::class, 'show'])->whereNumber('id')->name('siswa.show');
    Route::get('/siswa/{id}/edit', [StudentController::class, 'edit'])->whereNumber('id')->name('siswa.edit');
    Route::put('/siswa/{id}', [StudentController::class, 'update'])->whereNumber('id')->name('siswa.update');
    Route::delete('/siswa/{id}', [StudentController::class, 'destroy'])->whereNumber('id')->name('siswa.destroy');
    Route::post('/siswa/import-psb/{psbId}', [StudentController::class, 'importFromPsb'])->whereNumber('psbId')->name('siswa.importPsb');


    // Manajemen Pembayaran & Keuangan (Hanya Superadmin & Bendahara)
    Route::middleware('role:superadmin,bendahara')->group(function () {
        Route::get('/pembayaran', [PaymentController::class, 'index'])->name('pembayaran.index');
        Route::post('/pembayaran/psb/{id}/verify', [PaymentController::class, 'psbVerify'])->name('pembayaran.psbVerify');
        Route::post('/pembayaran/psb/bulk-verify', [PaymentController::class, 'psbBulkVerify'])->name('pembayaran.psbBulkVerify');
        Route::get('/pembayaran/psb/{id}/kwitansi', [PaymentController::class, 'kwitansiPsb'])->name('pembayaran.psb.kwitansi');
        Route::post('/pembayaran/santri', [PaymentController::class, 'storeStudentPayment'])->name('pembayaran.store');
        Route::post('/pembayaran/konfirmasi/{id}', [PaymentController::class, 'konfirmasiStudentPayment'])->name('pembayaran.konfirmasi');
        Route::post('/pembayaran/tolak/{id}', [PaymentController::class, 'tolakStudentPayment'])->name('pembayaran.tolak');
        Route::delete('/pembayaran/santri/{id}', [PaymentController::class, 'destroyStudentPayment'])->name('pembayaran.destroy');
        Route::get('/pembayaran/kwitansi/{id}', [PaymentController::class, 'kwitansi'])->name('pembayaran.kwitansi');
        Route::get('/pembayaran/kwitansi-massal', [PaymentController::class, 'kwitansiMassal'])->name('pembayaran.kwitansiMassal');
        Route::get('/pembayaran/rekap-kwitansi', [PaymentController::class, 'rekapKwitansi'])->name('pembayaran.rekapKwitansi');
        Route::get('/pembayaran/rekap-kwitansi/export', [PaymentController::class, 'exportRekapKwitansiExcel'])->name('pembayaran.rekapKwitansi.export');
        Route::get('/pembayaran/ajax-tagihan/{studentId}', [PaymentController::class, 'getStudentBillsAjax'])->name('pembayaran.ajaxTagihan');
        Route::get('/pembayaran/ajax-psb/{psbId}', [PaymentController::class, 'getPsbBillsAjax'])->name('pembayaran.ajaxPsb');

        // Kelola Tagihan & Penerbitan Tagihan (Bulanan & Tambahan Insidental)
        Route::get('/pembayaran-tagihan', [PaymentController::class, 'tagihanIndex'])->name('pembayaran.tagihan.index');
        Route::get('/pembayaran-tagihan/template-excel', [StudentController::class, 'downloadTemplate'])->name('pembayaran.downloadTemplate');
        Route::post('/pembayaran-tagihan/import-excel', [StudentController::class, 'importExcel'])->name('pembayaran.importExcel');
        Route::post('/pembayaran-tagihan/bulanan', [PaymentController::class, 'terbitkanTagihanBulanan'])->name('pembayaran.tagihan.bulanan');
        Route::post('/pembayaran-tagihan/tambahan', [PaymentController::class, 'terbitkanTagihanTambahan'])->name('pembayaran.tagihan.tambahan');
        Route::delete('/pembayaran-tagihan/{id}', [PaymentController::class, 'destroyBill'])->name('pembayaran.tagihan.destroy');

        // Penangguhan Tagihan ke Wisuda, Pemutihan (Nol-kan Tagihan), dan Permintaan Surat Dispensasi
        Route::post('/pembayaran-tagihan/{id}/tangguhkan', [PaymentController::class, 'tangguhkanKeWisuda'])->name('pembayaran.tagihan.tangguhkan');
        Route::post('/pembayaran-tagihan/{id}/batalkan-tangguh', [PaymentController::class, 'batalkanPenangguhan'])->name('pembayaran.tagihan.batalkanTangguh');
        Route::post('/pembayaran-tagihan/{id}/nolkan', [PaymentController::class, 'nolkanTagihan'])->name('pembayaran.tagihan.nolkan');
        Route::post('/pembayaran-tagihan/{id}/minta-surat', [PaymentController::class, 'mintaSuratDispensasi'])->name('pembayaran.tagihan.mintaSurat');
        Route::post('/pembayaran-tagihan/{id}/setujui-surat', [PaymentController::class, 'setujuiSuratDispensasi'])->name('pembayaran.tagihan.setujuiSurat');
        Route::post('/pembayaran-tagihan/{id}/tolak-surat', [PaymentController::class, 'tolakSuratDispensasi'])->name('pembayaran.tagihan.tolakSurat');

        // Keringanan & Potongan Biaya (SKTM / Beasiswa Pintar)
        Route::get('/pembayaran-potongan', [PaymentController::class, 'potonganIndex'])->name('pembayaran.potongan.index');
        Route::post('/pembayaran-potongan', [PaymentController::class, 'potonganStore'])->name('pembayaran.potongan.store');
        Route::delete('/pembayaran-potongan/{id}', [PaymentController::class, 'potonganDestroy'])->name('pembayaran.potongan.destroy');

        // Buku Kas & Jurnal Matriks Spreadsheet Bendahara
        Route::get('/pembayaran-jurnal', [PaymentController::class, 'jurnalIndex'])->name('pembayaran.jurnal.index');
        Route::get('/pembayaran-jurnal/export', [PaymentController::class, 'exportJurnalExcel'])->name('pembayaran.jurnal.export');
        Route::get('/pembayaran-jurnal/export-tahunan', [PaymentController::class, 'exportJurnalTahunanExcel'])->name('pembayaran.jurnal.exportTahunan');

        // Rekap Keuangan Tunggakan & Kekurangan Santri
        Route::get('/pembayaran-rekap-tunggakan', [PaymentController::class, 'rekapTunggakan'])->name('pembayaran.rekapTunggakan');
        Route::get('/pembayaran-rekap-tunggakan/export', [PaymentController::class, 'exportRekapTunggakanExcel'])->name('pembayaran.rekapTunggakan.export');

        // Pencatatan Kas Keluar & Beban Operasional Pesantren
        Route::get('/pengeluaran', [ExpenseController::class, 'index'])->name('pengeluaran.index');
        Route::get('/pengeluaran/export', [ExpenseController::class, 'exportPengeluaranExcel'])->name('pengeluaran.export');
        Route::post('/pengeluaran', [ExpenseController::class, 'store'])->name('pengeluaran.store');
        Route::put('/pengeluaran/{id}', [ExpenseController::class, 'update'])->name('pengeluaran.update');
        Route::delete('/pengeluaran/{id}', [ExpenseController::class, 'destroy'])->name('pengeluaran.destroy');

        // Laporan Arus Kas Terpadu (Cashflow Statement)
        Route::get('/arus-kas', [ExpenseController::class, 'cashflow'])->name('arusKas.index');
        Route::get('/arus-kas/export', [ExpenseController::class, 'exportCashflowExcel'])->name('arusKas.export');
        Route::post('/arus-kas/transfer', [ExpenseController::class, 'cashTransferStore'])->name('arusKas.transferStore');
        Route::delete('/arus-kas/transfer/{id}', [ExpenseController::class, 'cashTransferDestroy'])->name('arusKas.transferDestroy');
        Route::post('/arus-kas/saldo-awal', [ExpenseController::class, 'saldoAwalStore'])->name('arusKas.saldoAwalStore');

        // Laporan Keuangan Bulanan Resmi untuk Ketua Yayasan
        Route::get('/laporan-yayasan', [ExpenseController::class, 'laporanYayasanIndex'])->name('laporanYayasan.index');
        Route::get('/laporan-yayasan/cetak', [ExpenseController::class, 'laporanYayasanCetak'])->name('laporanYayasan.cetak');

        // Master Tarif & Biaya Pendidikan (Daftar Ulang/Awal Masuk & SPP Bulanan)
        Route::get('/pembayaran-tarif', [PaymentController::class, 'tarifIndex'])->name('pembayaran.tarif.index');
        Route::post('/pembayaran-tarif', [PaymentController::class, 'tarifUpdate'])->name('pembayaran.tarif.update');
    });

    // Manajemen PSB (Hanya Superadmin & Admin Biasa)
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::get('/psb', [AdminController::class, 'psbIndex'])->name('psb.index');
        Route::get('/psb/export', [AdminController::class, 'psbExport'])->name('psb.export');
        Route::get('/psb/print', [AdminController::class, 'psbPrint'])->name('psb.print');
        Route::patch('/psb/{id}/status', [AdminController::class, 'psbUpdateStatus'])->name('psb.updateStatus');
        Route::patch('/psb/{id}/foto-status', [AdminController::class, 'psbUpdateFotoStatus'])->name('psb.updateFotoStatus');
        Route::post('/psb/{id}/upload-foto', [AdminController::class, 'psbUploadFoto'])->name('psb.uploadFoto');
        Route::patch('/psb/{id}/berkas-status', [AdminController::class, 'psbUpdateBerkasStatus'])->name('psb.updateBerkasStatus');
        Route::post('/psb/{id}/upload-berkas', [AdminController::class, 'psbAdminUploadBerkas'])->name('psb.adminUploadBerkas');
        Route::post('/psb/auto-verify', [AdminController::class, 'psbAutoVerifyAll'])->name('psb.autoVerify');
        Route::post('/psb/bulk-verify', [PaymentController::class, 'psbBulkVerify'])->name('psb.bulkVerify');
        Route::post('/psb/auto-seleksi', [AdminController::class, 'psbAutoSeleksi'])->name('psb.autoSeleksi');
        Route::post('/psb/toggle-status', [AdminController::class, 'psbToggleStatus'])->name('psb.toggleStatus');
        Route::delete('/psb/{id}', [AdminController::class, 'psbDestroy'])->name('psb.destroy');
    });

    // Menu yang DIBATASI (Hanya Superadmin & Admin Biasa, Bendahara TIDAK BISA AKSES)
    Route::middleware('role:superadmin,admin')->group(function () {
        // Manajemen Berita
        Route::get('/berita', [AdminController::class, 'articleIndex'])->name('berita.index');
        Route::get('/berita/create', [AdminController::class, 'articleCreate'])->name('berita.create');
        Route::post('/berita', [AdminController::class, 'articleStore'])->name('berita.store');
        Route::get('/berita/{id}/edit', [AdminController::class, 'articleEdit'])->name('berita.edit');
        Route::put('/berita/{id}', [AdminController::class, 'articleUpdate'])->name('berita.update');
        Route::delete('/berita/{id}', [AdminController::class, 'articleDestroy'])->name('berita.destroy');

        // CBT / Ujian Masuk Seleksi Santri Baru
        Route::get('/cbt/soal', [AdminCbtController::class, 'soalIndex'])->name('cbt.soal.index');
        Route::get('/cbt/soal/create', [AdminCbtController::class, 'soalCreate'])->name('cbt.soal.create');
        Route::post('/cbt/soal', [AdminCbtController::class, 'soalStore'])->name('cbt.soal.store');
        Route::get('/cbt/soal/download-template', [AdminCbtController::class, 'downloadTemplate'])->name('cbt.soal.downloadTemplate');
        Route::get('/cbt/soal/export', [AdminCbtController::class, 'soalExport'])->name('cbt.soal.export');
        Route::get('/cbt/soal/{id}/edit', [AdminCbtController::class, 'soalEdit'])->name('cbt.soal.edit');
        Route::put('/cbt/soal/{id}', [AdminCbtController::class, 'soalUpdate'])->name('cbt.soal.update');
        Route::delete('/cbt/soal/{id}', [AdminCbtController::class, 'soalDestroy'])->name('cbt.soal.destroy');
        Route::post('/cbt/soal/bulk-delete', [AdminCbtController::class, 'soalBulkDestroy'])->name('cbt.soal.bulkDestroy');
        Route::post('/cbt/soal/load-template', [AdminCbtController::class, 'loadTemplate'])->name('cbt.soal.loadTemplate');

        Route::get('/cbt/pengaturan', [AdminCbtController::class, 'pengaturan'])->name('cbt.pengaturan');
        Route::post('/cbt/pengaturan', [AdminCbtController::class, 'pengaturanUpdate'])->name('cbt.pengaturan.update');
        Route::get('/cbt/hasil', [AdminCbtController::class, 'hasilIndex'])->name('cbt.hasil.index');
        Route::post('/cbt/hasil/toggle-publish', [AdminCbtController::class, 'togglePublishScores'])->name('cbt.hasil.togglePublish');
        Route::get('/cbt/hasil/export-nilai', [AdminCbtController::class, 'exportNilai'])->name('cbt.hasil.exportNilai');
        Route::get('/cbt/hasil/export-lulus', [AdminCbtController::class, 'exportLulus'])->name('cbt.hasil.exportLulus');
        Route::post('/cbt/hasil/{id}', [AdminCbtController::class, 'hasilUpdate'])->name('cbt.hasil.update');
        Route::post('/cbt/hasil/{id}/reset', [AdminCbtController::class, 'resetUjian'])->name('cbt.hasil.reset');
        Route::post('/cbt/hasil-bulk-dongkrak', [AdminCbtController::class, 'bulkDongkrak'])->name('cbt.hasil.bulkDongkrak');
        Route::get('/cbt/dongkrak', [AdminCbtController::class, 'dongkrakIndex'])->name('cbt.dongkrak.index');
        Route::get('/cbt/cetak-soal', [AdminCbtController::class, 'cetakSoal'])->name('cbt.cetakSoal');
        Route::get('/cbt/cetak-nilai', [AdminCbtController::class, 'cetakNilai'])->name('cbt.cetakNilai');
        Route::get('/cbt/monitoring', [AdminCbtController::class, 'monitoringIndex'])->name('cbt.monitoring.index');
        Route::get('/cbt/monitoring/data', [AdminCbtController::class, 'monitoringData'])->name('cbt.monitoring.data');
        Route::post('/cbt/monitoring/action', [AdminCbtController::class, 'monitoringAction'])->name('cbt.monitoring.action');
        Route::post('/cbt/kategori', [AdminCbtController::class, 'kategoriStore'])->name('cbt.kategori.store');
        Route::put('/cbt/kategori', [AdminCbtController::class, 'kategoriUpdate'])->name('cbt.kategori.update');
        Route::delete('/cbt/kategori', [AdminCbtController::class, 'kategoriDestroy'])->name('cbt.kategori.destroy');
        Route::post('/cbt/soal/import', [AdminCbtController::class, 'soalImport'])->name('cbt.soal.import');

        // Pengaturan Konten Web (CMS)
        Route::get('/pengaturan', [AdminController::class, 'settingsIndex'])->name('settings.index');
        Route::post('/pengaturan', [AdminController::class, 'settingsUpdate'])->name('settings.update');
    });

    // Manajemen Pengguna (Role: Superadmin Saja)
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'userCreate'])->name('users.create');
        Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'userEdit'])->name('users.edit');
        Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');

        // Backup & Unduh Database (.sql)
        Route::get('/backup-database', [BackupController::class, 'downloadSql'])->name('backup.sql');
    });

    // Profil & Ganti Password Saya (Bisa untuk Semua Role)
    Route::get('/profil', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profil', [AdminController::class, 'profileUpdate'])->name('profile.update');

});

// Portal Santri (Login, Dasbor & Biodata)
Route::get('/santri/login', [StudentAuthController::class, 'loginForm'])->name('santri.login');
Route::post('/santri/login', [StudentAuthController::class, 'login'])->name('santri.login.post');
Route::middleware('auth:santri')->prefix('santri')->name('santri.')->group(function () {
    Route::get('/dashboard', [StudentAuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [StudentAuthController::class, 'profil'])->name('profil');
    Route::post('/profil', [StudentAuthController::class, 'updateProfil'])->name('profil.update');
    Route::get('/pembayaran', [StudentAuthController::class, 'pembayaran'])->name('pembayaran');
    Route::post('/pembayaran/upload', [StudentAuthController::class, 'uploadBuktiBayar'])->name('pembayaran.upload');
    Route::post('/pembayaran/ajukan-keringanan', [StudentAuthController::class, 'ajukanKeringananMandiri'])->name('pembayaran.ajukanKeringanan');
    Route::post('/pembayaran/tagihan/{id}/upload-surat', [StudentAuthController::class, 'uploadSuratDispensasi'])->name('pembayaran.uploadSurat');
    Route::get('/pembayaran/kwitansi/{id}', [StudentAuthController::class, 'kwitansiSantri'])->name('pembayaran.kwitansi');
    Route::post('/ganti-password', [StudentAuthController::class, 'updatePassword'])->name('updatePassword');
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
});



<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PsbController;
use App\Http\Controllers\AuthController;
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
Route::get('/pendaftaran/cetak-kartu/{id}', [PsbController::class, 'printCard'])->name('psb.printCard');

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

    // Manajemen Berita
    Route::get('/berita', [AdminController::class, 'articleIndex'])->name('berita.index');
    Route::get('/berita/create', [AdminController::class, 'articleCreate'])->name('berita.create');
    Route::post('/berita', [AdminController::class, 'articleStore'])->name('berita.store');
    Route::get('/berita/{id}/edit', [AdminController::class, 'articleEdit'])->name('berita.edit');
    Route::put('/berita/{id}', [AdminController::class, 'articleUpdate'])->name('berita.update');
    Route::delete('/berita/{id}', [AdminController::class, 'articleDestroy'])->name('berita.destroy');

    // Manajemen PSB
    Route::get('/psb', [AdminController::class, 'psbIndex'])->name('psb.index');
    Route::get('/psb/export', [AdminController::class, 'psbExport'])->name('psb.export');
    Route::get('/psb/print', [AdminController::class, 'psbPrint'])->name('psb.print');
    Route::patch('/psb/{id}/status', [AdminController::class, 'psbUpdateStatus'])->name('psb.updateStatus');
    Route::patch('/psb/{id}/foto-status', [AdminController::class, 'psbUpdateFotoStatus'])->name('psb.updateFotoStatus');
    Route::post('/psb/{id}/upload-foto', [AdminController::class, 'psbUploadFoto'])->name('psb.uploadFoto');
    Route::post('/psb/auto-verify', [AdminController::class, 'psbAutoVerifyAll'])->name('psb.autoVerify');
    Route::delete('/psb/{id}', [AdminController::class, 'psbDestroy'])->name('psb.destroy');

    // Pengaturan Konten Web (CMS: Profil, Sejarah, Jadwal 24 Jam, Sosmed & Kontak)
    Route::get('/pengaturan', [AdminController::class, 'settingsIndex'])->name('settings.index');
    Route::post('/pengaturan', [AdminController::class, 'settingsUpdate'])->name('settings.update');
});

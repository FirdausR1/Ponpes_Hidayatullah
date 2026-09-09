<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PsbRegistration;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PsbController;

echo "=== 1. TEST PSB STATUS CHECK & RE-UPLOAD FLOW ===\n";
$firstReg = PsbRegistration::first();
if ($firstReg) {
    echo "Found student: " . $firstReg->nama_lengkap . " (" . $firstReg->no_registrasi . ")\n";

    $psbCtrl = new PsbController();
    $req = Request::create('/pendaftaran/cek-status', 'GET', ['keyword' => $firstReg->no_registrasi]);
    $resp = $psbCtrl->checkStatus($req);
    $html = $resp->render();
    echo "Check status by no_reg rendered HTML length: " . strlen($html) . " bytes\n";

    $reqWa = Request::create('/pendaftaran/cek-status', 'GET', ['keyword' => $firstReg->no_whatsapp ?: $firstReg->ayah_telepon]);
    $respWa = $psbCtrl->checkStatus($reqWa);
    echo "Check status by WA rendered HTML length: " . strlen($respWa->render()) . " bytes\n";
} else {
    echo "No PSB records found in DB.\n";
}

echo "\n=== 2. TEST PSB SUCCESS PAGE ===\n";
if ($firstReg) {
    $respSuccess = $psbCtrl->success($firstReg->id);
    echo "Success page rendered HTML length: " . strlen($respSuccess->render()) . " bytes\n";
}

echo "\n=== 3. TEST ADMIN EXPORT EXCEL (.xls) ===\n";
$adminCtrl = new AdminController();
$reqExport = Request::create('/admin/psb/export', 'GET');
$respExport = $adminCtrl->psbExport($reqExport);
echo "Export response type: " . get_class($respExport) . "\n";
echo "Content-Type header: " . $respExport->headers->get('Content-Type') . "\n";
echo "Content-Disposition: " . $respExport->headers->get('Content-Disposition') . "\n";

echo "\n=== 4. TEST ADMIN PRINT (CV & BERKAS) ===\n";
$reqPrintAll = Request::create('/admin/psb/print', 'GET', ['mode' => 'all']);
$respPrintAll = $adminCtrl->psbPrint($reqPrintAll);
echo "Print All rendered HTML length: " . strlen($respPrintAll->render()) . " bytes\n";

if ($firstReg) {
    $reqPrintSingle = Request::create('/admin/psb/print', 'GET', ['ids' => $firstReg->id, 'mode' => 'cv']);
    $respPrintSingle = $adminCtrl->psbPrint($reqPrintSingle);
    echo "Print Single CV rendered HTML length: " . strlen($respPrintSingle->render()) . " bytes\n";
}

echo "\n=== 5. TEST QUR'AN SETTINGS ===\n";
echo "quran_label: " . Setting::get('quran_label', 'DEFAULT') . "\n";
echo "quran_quote: " . Setting::get('quran_quote', 'DEFAULT') . "\n";
echo "quran_desc: " . Setting::get('quran_desc', 'DEFAULT') . "\n";

echo "\nAll functional checks completed successfully!\n";

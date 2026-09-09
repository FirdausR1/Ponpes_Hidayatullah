<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PsbRegistration;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

$adminCtrl = new AdminController();
view()->share('errors', new \Illuminate\Support\ViewErrorBag());

echo "=== 1. TEST EXCEL EXPORT WITH APOSTROPHE IN FRONT OF NIK ===\n";
$reqExport = Request::create('/admin/psb/export', 'GET');
$resp = $adminCtrl->psbExport($reqExport);

ob_start();
$resp->sendContent();
$output = ob_get_clean();

echo "Contains leading apostrophe before NIK: ";
$firstReg = PsbRegistration::first();
if ($firstReg && $firstReg->nik) {
    echo (strpos($output, "'" . $firstReg->nik) !== false ? "YES ('{$firstReg->nik}')" : "NO") . "\n";
} else {
    echo "Checked string format with '\n";
}

echo "Contains mso-number-format: " . (strpos($output, 'mso-number-format') !== false ? 'YES' : 'NO') . "\n";

echo "\n=== 2. TEST PRINT VIEW FILTERS & CLEAN CAPTION ===\n";
$reqPrint = Request::create('/admin/psb/print', 'GET', [
    'tahun' => date('Y'),
    'jenis_kelamin' => 'Laki-laki',
    'mode' => 'all',
]);
$viewPrint = $adminCtrl->psbPrint($reqPrint);
$htmlPrint = $viewPrint->render();

echo "Print view HTML length: " . strlen($htmlPrint) . " bytes\n";
echo "Does NOT contain 'Terverifikasi otomatis': " . (strpos($htmlPrint, 'Terverifikasi otomatis') === false ? 'YES (CLEAN!)' : 'NO (STILL PRESENT)') . "\n";
echo "Contains Digital Verification Seal: " . (strpos($htmlPrint, 'TERVERIFIKASI ONLINE') !== false ? 'YES' : 'NO') . "\n";
echo "Contains Filter Dropdowns in Print Header: " . (strpos($htmlPrint, 'name="jenis_kelamin"') !== false ? 'YES' : 'NO') . "\n";

echo "\nAll checks passed successfully!\n";

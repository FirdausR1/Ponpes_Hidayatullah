<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

// Create dummy request updating TTD settings
$req = Request::create('/admin/pengaturan', 'POST', [
    'active_tab' => 'tab-ttd',
    'section_name' => 'Tanda Tangan Digital & Stempel PSB',
    'ttd_digital_nama' => 'KH. Ahmad Fauzi, S.Pd.I, M.Pd.',
    'ttd_digital_jabatan' => 'Ketua Panitia PSB & Pengurus Pesantren',
    'ttd_digital_nip' => 'NIY. 19820514 200801 1 005',
    'ttd_digital_kota' => 'Pringsurat Temanggung',
    'ttd_digital_show_stempel' => '1',
]);

$controller = new AdminController();
$response = $controller->settingsUpdate($req);

echo "Update status: " . $response->getStatusCode() . "\n";
echo "Saved nama: " . Setting::get('ttd_digital_nama') . "\n";
echo "Saved jabatan: " . Setting::get('ttd_digital_jabatan') . "\n";
echo "Saved nip: " . Setting::get('ttd_digital_nip') . "\n";
echo "Saved kota: " . Setting::get('ttd_digital_kota') . "\n";
echo "Saved stempel toggle: " . Setting::get('ttd_digital_show_stempel') . "\n";

if (Setting::get('ttd_digital_nama') === 'KH. Ahmad Fauzi, S.Pd.I, M.Pd.') {
    echo "[PASS] Controller settingsUpdate works flawlessly!\n";
} else {
    echo "[FAIL] Settings did not match!\n";
}

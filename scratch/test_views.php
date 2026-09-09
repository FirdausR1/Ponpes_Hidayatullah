<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PsbRegistration;
use App\Models\Setting;

$reg = PsbRegistration::first();
if (!$reg) {
    echo "NO_REG\n";
    exit;
}

echo "1. Testing psb.print_card (Public Santri CV)...\n";
$view1 = view('psb.print_card', ['reg' => $reg, 'mode' => 'cv'])->render();
echo "[PASS] psb.print_card (mode=cv) bytes: " . strlen($view1) . "\n";

echo "2. Testing admin.psb.print (Mass Print with TTD Digital)...\n";
$view2 = view('admin.psb.print', ['registrations' => [$reg], 'mode' => 'cv', 'years' => [2025, 2026]])->render();
echo "[PASS] admin.psb.print bytes: " . strlen($view2) . "\n";

echo "3. Testing psb.check_status...\n";
$view3 = view('psb.check_status', ['registration' => $reg, 'searched' => true, 'identifier' => $reg->no_registrasi])->render();
echo "[PASS] psb.check_status bytes: " . strlen($view3) . "\n";

echo "4. Testing admin.pengaturan.index (Settings with TTD Digital Tab)...\n";
$settings = Setting::all()->pluck('value', 'key');
$jadwalSantri = [];
$biayaAwal = [];
$biayaBulanan = [];
$pancaJiwa = [];
$filosofiLambang = [];
$pilarPendidikan = [];
$agendaBerkala = [];
$errors = new \Illuminate\Support\ViewErrorBag();
$view4 = view('admin.pengaturan.index', compact('settings', 'jadwalSantri', 'biayaAwal', 'biayaBulanan', 'pancaJiwa', 'filosofiLambang', 'pilarPendidikan', 'agendaBerkala', 'errors'))->render();
echo "[PASS] admin.pengaturan.index bytes: " . strlen($view4) . "\n";

echo "\nALL 4 VIEWS RENDERED WITH ZERO ERRORS!\n";

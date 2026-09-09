<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

Setting::updateOrCreate(
    ['key' => 'ttd_digital_pengurus_image'],
    ['value' => '/uploads/settings/default_ttd_pengurus.png', 'group' => 'psb']
);
Setting::updateOrCreate(
    ['key' => 'ttd_digital_stempel_image'],
    ['value' => '/uploads/settings/default_stempel_pesantren.png', 'group' => 'psb']
);
Setting::updateOrCreate(
    ['key' => 'ttd_digital_nama'],
    ['value' => 'Ust. Ahmad Fauzi, S.Pd.I', 'group' => 'psb']
);
Setting::updateOrCreate(
    ['key' => 'ttd_digital_jabatan'],
    ['value' => 'Ketua Panitia PSB TA 2025/2026', 'group' => 'psb']
);
Setting::updateOrCreate(
    ['key' => 'ttd_digital_nip'],
    ['value' => 'NIY. 19850412 201001 1 003', 'group' => 'psb']
);
Setting::updateOrCreate(
    ['key' => 'ttd_digital_kota'],
    ['value' => 'Pringsurat', 'group' => 'psb']
);
Setting::updateOrCreate(
    ['key' => 'ttd_digital_show_stempel'],
    ['value' => '1', 'group' => 'psb']
);

echo "TTD Settings seeded successfully!\n";

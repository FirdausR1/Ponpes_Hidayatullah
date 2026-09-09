<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

Setting::updateOrCreate(
    ['key' => 'quran_label'],
    ['value' => "Kutipan Wahyu & Semangat Tholabul 'Ilmi", 'group' => 'profil']
);

Setting::updateOrCreate(
    ['key' => 'quran_quote'],
    ['value' => 'يَرْفَعِ اللّٰهُ الَّذِيْنَ اٰمَنُوْا مِنْكُمْ ۙ وَالَّذِيْنَ اُوْتُوا الْعِلْمَ دَرَجٰتٍ', 'group' => 'profil']
);

Setting::updateOrCreate(
    ['key' => 'quran_desc'],
    ['value' => '"...Allah akan meninggikan orang-orang yang beriman di antaramu dan orang-orang yang diberi ilmu pengetahuan beberapa derajat. Dan Allah Maha Mengetahui apa yang kamu kerjakan." (QS. Al-Mujadilah [58]: 11)', 'group' => 'profil']
);

echo "Quran settings initialized successfully!\n";

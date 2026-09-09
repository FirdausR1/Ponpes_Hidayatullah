<?php

// Script to create high quality transparent default signature and official stamp
$destDir = __DIR__ . '/../public/uploads/settings';
if (!file_exists($destDir)) {
    mkdir($destDir, 0777, true);
}

// 1. Create Default TTD (Signature) with transparent background
$w = 400;
$h = 160;
$imTtd = imagecreatetruecolor($w, $h);
imagesavealpha($imTtd, true);
$trans = imagecolorallocatealpha($imTtd, 0, 0, 0, 127);
imagefill($imTtd, 0, 0, $trans);

$inkColor = imagecolorallocatealpha($imTtd, 15, 30, 80, 15); // Dark blue fountain pen ink

imagesetthickness($imTtd, 3);

// Draw an elegant signature curve sequence
$points = [
    // Initial loop & swoop
    [40, 110], [55, 60], [70, 30], [80, 45], [85, 95], [88, 125],
    [92, 70], [105, 50], [120, 85], [130, 110],
    // Mid wave
    [140, 75], [150, 65], [165, 80], [180, 60], [195, 75], [210, 65], [230, 85],
    // High loop
    [245, 40], [260, 25], [270, 35], [265, 80], [275, 115],
    // Tail flourish
    [290, 100], [320, 95], [355, 90], [370, 88]
];

for ($i = 0; $i < count($points) - 1; $i++) {
    imageline($imTtd, $points[$i][0], $points[$i][1], $points[$i+1][0], $points[$i+1][1], $inkColor);
    // Smooth width
    imagesetthickness($imTtd, ($i % 3 == 0) ? 4 : 3);
}

// Add horizontal underline flourish
imagesetthickness($imTtd, 3);
$underFlourish = [
    [50, 125], [90, 132], [150, 135], [230, 133], [320, 128], [375, 122]
];
for ($i = 0; $i < count($underFlourish) - 1; $i++) {
    imageline($imTtd, $underFlourish[$i][0], $underFlourish[$i][1], $underFlourish[$i+1][0], $underFlourish[$i+1][1], $inkColor);
}

// Dot at end
imagefilledellipse($imTtd, 375, 110, 6, 6, $inkColor);

imagepng($imTtd, $destDir . '/default_ttd_pengurus.png');
imagedestroy($imTtd);
echo "Default TTD generated: default_ttd_pengurus.png\n";

// 2. Create Default Stempel Resmi Pesantren (Circular Stamp with transparent background)
$sw = 300;
$sh = 300;
$imStamp = imagecreatetruecolor($sw, $sh);
imagesavealpha($imStamp, true);
$transStamp = imagecolorallocatealpha($imStamp, 0, 0, 0, 127);
imagefill($imStamp, 0, 0, $transStamp);

// Stamp color: Classic emerald green / indigo violet official stamp
$stampColor = imagecolorallocatealpha($imStamp, 16, 120, 60, 20); // semi-translucent green stamp

imagesetthickness($imStamp, 4);
// Outer circle
imageellipse($imStamp, 150, 150, 270, 270, $stampColor);
// Inner circle
imagesetthickness($imStamp, 2);
imageellipse($imStamp, 150, 150, 252, 252, $stampColor);
imageellipse($imStamp, 150, 150, 180, 180, $stampColor);

// Center divider line
imageline($imStamp, 60, 125, 240, 125, $stampColor);
imageline($imStamp, 60, 175, 240, 175, $stampColor);

// Text in center
imagestring($imStamp, 5, 105, 135, "PANITIA PSB", $stampColor);
imagestring($imStamp, 3, 75, 105, "PONDOK PESANTREN", $stampColor);
imagestring($imStamp, 3, 85, 180, "HIDAYATULLAH", $stampColor);
imagestring($imStamp, 2, 95, 200, "TUKSONGO - TMG", $stampColor);

// Small decorative stars
imagefilledellipse($imStamp, 45, 150, 8, 8, $stampColor);
imagefilledellipse($imStamp, 255, 150, 8, 8, $stampColor);

imagepng($imStamp, $destDir . '/default_stempel_pesantren.png');
imagedestroy($imStamp);
echo "Default Stempel generated: default_stempel_pesantren.png\n";

<?php
// إنشاء صورة بخلفية بيضاء
$width = 400;
$height = 100;
$image = imagecreatetruecolor($width, $height);
$white = imagecolorallocate($image, 255, 255, 255);
$blue = imagecolorallocate($image, 41, 128, 185);
$darkBlue = imagecolorallocate($image, 44, 62, 80);

// ملء الخلفية باللون الأبيض
imagefill($image, 0, 0, $white);

// إضافة نص اسم التطبيق
$font = 5; // حجم الخط المدمج
$text = "متجر الأدوية الطبية";
$text_width = imagefontwidth($font) * strlen($text);
$text_height = imagefontheight($font);
$x = ($width - $text_width) / 2;
$y = ($height - $text_height) / 2;

// رسم مستطيل أزرق كخلفية للنص
imagefilledrectangle($image, $x - 10, $y - 10, $x + $text_width + 10, $y + $text_height + 10, $blue);

// كتابة النص باللون الأبيض
imagestring($image, $font, $x, $y, $text, $white);

// حفظ الصورة
imagepng($image, __DIR__ . '/logo.png');
imagedestroy($image);

// إنشاء أيقونة مفضلة بسيطة
$favicon = imagecreatetruecolor(32, 32);
imagefill($favicon, 0, 0, $darkBlue);
$text = "MS";
$text_width = imagefontwidth(3) * strlen($text);
$text_height = imagefontheight(3);
$x = (32 - $text_width) / 2;
$y = (32 - $text_height) / 2;
imagestring($favicon, 3, $x, $y, $text, $white);
imagepng($favicon, __DIR__ . '/favicon.ico');
imagedestroy($favicon);

echo "تم إنشاء اللوجو والأيقونة المفضلة بنجاح!";
?>

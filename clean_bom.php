<?php
$files = [
    "app/Http/Middleware/AdminMiddleware.php",
    "app/Http/Kernel.php",
    "app/Http/Controllers/ProfileController.php"
];

foreach ($files as $f) {
    if (!file_exists($f)) continue;
    $s = file_get_contents($f);
    $s = preg_replace("/^\xEF\xBB\xBF/", "", $s);
    file_put_contents($f, $s);
    echo "cleaned: $f\n";
}

<?php
$f = 'app/Http/Kernel.php';
$s = file_get_contents($f);
$s2 = preg_replace('/^\xEF\xBB\xBF/', '', $s);
if ($s !== $s2) file_put_contents($f, $s2) && print("BOM removed\n");
else print("No BOM\n");

<?php
$file = "d:/laragon/www/becks-apparel/app/Filament/Resources/ApiSettingResource.php";
$content = file_get_contents($file);

$content = str_replace("->placeholder(\x27pk_sand_...\x27)", "->placeholder(\x27pk_sand_...\x27),", $content);
$content = str_replace("->placeholder(\x27pk_live_...\x27)", "->placeholder(\x27pk_live_...\x27),", $content);

// For the ones ending in extraInputAttributes
$content = preg_replace("/->extraInputAttributes\(\[\x27autocomplete\x27 => \x27new-password\x27\]\)\s*\n(\s*)Forms\\\\/", "->extraInputAttributes([\x27autocomplete\x27 => \x27new-password\x27]),\n$1Forms\\\\", $content);

file_put_contents($file, $content);
echo "Commas fixed!";
?>

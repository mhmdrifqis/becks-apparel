<?php
$file = "d:/laragon/www/becks-apparel/app/Filament/Resources/ApiSettingResource.php";
$content = file_get_contents($file);

$content = preg_replace("/API 1: .* Paywuz Payment Gateway/", "Paywuz Payment Gateway", $content);
$content = preg_replace("/API 2: .* RajaOngkir Shipping API/", "RajaOngkir Shipping API", $content);
$content = preg_replace("/API 3: .* Fonnte WhatsApp Gateway/", "Fonnte WhatsApp Gateway", $content);
$content = preg_replace("/API 4: .* FastAPI NLP Chatbot/", "FastAPI NLP Chatbot", $content);
$content = preg_replace("/API 5: .* Google Gemini AI API/", "Google Gemini AI API", $content);
$content = preg_replace("/API 6: .* Google OAuth Social Login/", "Google OAuth Social Login", $content);
$content = preg_replace("/API 7: .* Biteship Logistics & Tracking/", "Biteship Logistics & Tracking", $content);
$content = preg_replace("/API 8: .* SMTP Mail Gateway Service/", "SMTP Mail Gateway Service", $content);

$content = preg_replace("/\s*->helperText\(\x27Klik ikon mata .* menyembunyikan.*\x27\),/u", "", $content);

file_put_contents($file, $content);
echo "Cleaned!";
?>

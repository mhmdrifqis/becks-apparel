<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Normalisasi nomor telepon Indonesia ke format standar 08xxxxxxxxxx
     *
     * @param string|null $phone
     * @return string
     */
    public static function normalize(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        // Hapus semua karakter non-angka
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika diawali 628... ubah menjadi 08...
        if (str_starts_with($phone, '628')) {
            $phone = '08' . substr($phone, 3);
        }
        // Jika diawali 6208... ubah menjadi 08...
        elseif (str_starts_with($phone, '6208')) {
            $phone = '08' . substr($phone, 4);
        }

        return $phone;
    }
}

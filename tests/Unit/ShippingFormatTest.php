<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ShippingFormatTest extends TestCase
{
    public function test_sorts_shipping_options_by_cheapest_cost(): void
    {
        $options = [
            ['courier' => 'pos', 'service' => 'REG', 'cost' => 28000],
            ['courier' => 'jne', 'service' => 'REG', 'cost' => 20000],
            ['courier' => 'tiki', 'service' => 'REG', 'cost' => 22000],
            ['courier' => 'jne', 'service' => 'YES', 'cost' => 38000],
        ];

        usort($options, fn($a, $b) => $a['cost'] <=> $b['cost']);

        $cheapest = $options[0];

        $this->assertEquals(20000, $cheapest['cost']);
        $this->assertEquals('jne', $cheapest['courier']);
        $this->assertEquals(38000, end($options)['cost']);
    }

    public function test_normalizes_courier_code_correctly(): void
    {
        $normalizeCourier = function (string $courierInput): string {
            $courier = strtolower($courierInput);
            if (str_contains($courier, 'jne')) return 'jne';
            if (str_contains($courier, 'sicepat')) return 'sicepat';
            if (str_contains($courier, 'jnt') || str_contains($courier, 'j&t')) return 'jnt';
            if (str_contains($courier, 'pos')) return 'pos';
            if (str_contains($courier, 'tiki')) return 'tiki';
            return 'jne';
        };

        $this->assertEquals('jne', $normalizeCourier('JNE Express'));
        $this->assertEquals('sicepat', $normalizeCourier('SiCepat'));
        $this->assertEquals('jnt', $normalizeCourier('J&T Express'));
        $this->assertEquals('pos', $normalizeCourier('POS Indonesia'));
        $this->assertEquals('tiki', $normalizeCourier('TIKI ONS'));
        $this->assertEquals('jne', $normalizeCourier('Kurir Tidak Dikenal'));
    }
}

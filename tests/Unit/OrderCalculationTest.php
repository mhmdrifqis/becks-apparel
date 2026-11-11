<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class OrderCalculationTest extends TestCase
{
    /**
     * Hitung surcharge ukuran: XXL +5.000, XXXL +10.000, ukuran normal 0.
     */
    public function test_calculates_size_surcharges_correctly(): void
    {
        $calculateSizeSurcharge = function (string $size): int {
            if ($size === 'XXL') return 5000;
            if ($size === 'XXXL') return 10000;
            return 0;
        };

        $this->assertEquals(0, $calculateSizeSurcharge('S'));
        $this->assertEquals(0, $calculateSizeSurcharge('M'));
        $this->assertEquals(0, $calculateSizeSurcharge('L'));
        $this->assertEquals(0, $calculateSizeSurcharge('XL'));
        $this->assertEquals(5000, $calculateSizeSurcharge('XXL'));
        $this->assertEquals(10000, $calculateSizeSurcharge('XXXL'));
    }

    /**
     * Hitung subtotal item pesanan:
     * ((base_price + material_additional_price) * quantity) + total_surcharges
     */
    public function test_calculates_item_subtotal_with_material_and_surcharges(): void
    {
        $basePrice = 130000; // Paket C
        $materialAdditional = 10000; // Bahan Premium
        $quantity = 3;

        // Player 1: L (0)
        // Player 2: XXL (+5000)
        // Player 3: XXXL + Lengan Panjang (+10000 + 20000 = +30000)
        $rosterSurcharges = 0 + 5000 + 30000; // 35000

        $subtotal = (($basePrice + $materialAdditional) * $quantity) + $rosterSurcharges;

        // (140.000 * 3) + 35.000 = 420.000 + 35.000 = 455.000
        $this->assertEquals(455000, $subtotal);
    }

    /**
     * Hitung grand total: sum(subtotal item) + ongkir
     */
    public function test_calculates_grand_total_with_shipping(): void
    {
        $item1Subtotal = 455000;
        $item2Subtotal = 180000;
        $shippingCost = 25000;

        $grandTotal = $item1Subtotal + $item2Subtotal + $shippingCost;

        $this->assertEquals(660000, $grandTotal);
    }

    /**
     * Hitung pelunasan (rest payment): total - dp
     */
    public function test_calculates_rest_payment_amount(): void
    {
        $totalAmount = 660000;
        $depositAmount = 330000; // 50% DP

        $restAmount = $totalAmount - $depositAmount;

        $this->assertEquals(330000, $restAmount);
    }
}

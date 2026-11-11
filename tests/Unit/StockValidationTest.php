<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class StockValidationTest extends TestCase
{
    public function test_validates_material_stock_availability(): void
    {
        $currentStock = 50.0; // 50 meter

        $isStockSufficient = function (float $available, float $required): bool {
            return $available >= $required && $required > 0;
        };

        // Kebutuhan 30 meter -> cukup
        $this->assertTrue($isStockSufficient($currentStock, 30.0));

        // Kebutuhan persis 50 meter -> cukup
        $this->assertTrue($isStockSufficient($currentStock, 50.0));

        // Kebutuhan 55 meter -> kurang
        $this->assertFalse($isStockSufficient($currentStock, 55.0));

        // Kebutuhan 0 atau minus -> tidak valid
        $this->assertFalse($isStockSufficient($currentStock, 0));
        $this->assertFalse($isStockSufficient($currentStock, -5.0));
    }

    public function test_deducts_material_stock_correctly(): void
    {
        $initialStock = 100.0;
        $usage = 24.5;

        $remainingStock = $initialStock - $usage;

        $this->assertEquals(75.5, $remainingStock);
    }
}

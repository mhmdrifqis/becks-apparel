<?php

namespace Tests\Unit;

use App\Helpers\PhoneHelper;
use PHPUnit\Framework\TestCase;

class PhoneHelperTest extends TestCase
{
    public function test_normalizes_standard_indonesian_phone_number(): void
    {
        $normalized = PhoneHelper::normalize('081234567890');
        $this->assertEquals('081234567890', $normalized);
    }

    public function test_normalizes_international_format_starting_with_628(): void
    {
        $normalized = PhoneHelper::normalize('6281234567890');
        $this->assertEquals('081234567890', $normalized);
    }

    public function test_normalizes_phone_with_plus_sign_and_spaces(): void
    {
        $normalized = PhoneHelper::normalize('+62 812-3456-7890');
        $this->assertEquals('081234567890', $normalized);
    }

    public function test_handles_null_and_empty_phone_gracefully(): void
    {
        $this->assertEquals('', PhoneHelper::normalize(null));
        $this->assertEquals('', PhoneHelper::normalize(''));
        $this->assertEquals('', PhoneHelper::normalize('   '));
    }
}

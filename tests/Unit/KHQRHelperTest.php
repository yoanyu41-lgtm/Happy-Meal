<?php

namespace Tests\Unit;

use App\Helpers\KHQRHelper;
use PHPUnit\Framework\TestCase;

class KHQRHelperTest extends TestCase
{
    /**
     * Test CRC16 calculation.
     */
    public function test_calculate_crc(): void
    {
        // Example string with known CRC
        $data = "00020101021129420013user@bakong01090123456780208DevBank5204599953031165802KH5911John Doe6010Phnom Penh6304";
        $crc = KHQRHelper::calculateCRC($data);
        $this->assertEquals("5647", $crc);
    }

    /**
     * Test EMVCo KHQR string generation.
     */
    public function test_generate_khqr_string(): void
    {
        $payload = KHQRHelper::generateKHQR(
            'wing_khqr@wing',
            '100764918',
            'Wing Bank',
            'YU YOAN',
            'Phnom Penh',
            5.00
        );

        $this->assertStringStartsWith('00020101021229', $payload);
        $this->assertStringContainsString('5303840', $payload); // USD currency code
        $this->assertStringContainsString('54045.00', $payload); // Amount tag
        $this->assertStringContainsString('5802KH', $payload);   // Country code
        $this->assertStringContainsString('5907YUYOAN', preg_replace('/\s+/', '', $payload)); // Merchant name
        $this->assertStringContainsString('6010PhnomPenh', preg_replace('/\s+/', '', $payload)); // City name
        $this->assertStringContainsString('6304', $payload);    // CRC tag
    }
}

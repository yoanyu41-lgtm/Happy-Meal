<?php

namespace App\Helpers;

class KHQRHelper
{
    /**
     * Calculate CRC16 CCITT (FALSE) checksum.
     */
    public static function calculateCRC($data)
    {
        $crc = 0xFFFF;
        $polynomial = 0x1021;
        $len = strlen($data);
        for ($i = 0; $i < $len; $i++) {
            $crc ^= (ord($data[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if (($crc & 0x8000) !== 0) {
                    $crc = (($crc << 1) ^ $polynomial) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }
        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Generate a valid EMVCo-compliant KHQR string for Bakong/member banks.
     */
    public static function generateKHQR($accountId, $accountNumber, $bankName, $merchantName, $city, $amount, $currency = 'USD')
    {
        // 00: Payload Format Indicator (01)
        $payload = '000201';
        
        // 01: Point of Initiation Method (12 for dynamic)
        $payload .= '010212';
        
        // 29: Merchant Account Information
        $sub00 = '00' . str_pad(strlen($accountId), 2, '0', STR_PAD_LEFT) . $accountId;
        $sub01 = '01' . str_pad(strlen($accountNumber), 2, '0', STR_PAD_LEFT) . $accountNumber;
        $sub02 = '02' . str_pad(strlen($bankName), 2, '0', STR_PAD_LEFT) . $bankName;
        $val29 = $sub00 . $sub01 . $sub02;
        $payload .= '29' . str_pad(strlen($val29), 2, '0', STR_PAD_LEFT) . $val29;
        
        // 52: Merchant Category Code (5999 for personal/individual)
        $payload .= '52045999';
        
        // 53: Transaction Currency (840 for USD, 116 for KHR)
        $currCode = ($currency === 'KHR') ? '116' : '840';
        $payload .= '5303' . $currCode;
        
        // 54: Transaction Amount
        $formattedAmount = number_format($amount, 2, '.', '');
        $payload .= '54' . str_pad(strlen($formattedAmount), 2, '0', STR_PAD_LEFT) . $formattedAmount;
        
        // 58: Country Code (KH)
        $payload .= '5802KH';
        
        // 59: Merchant Name
        $cleanMerchantName = substr(preg_replace('/[^A-Za-z0-9 ]/', '', $merchantName), 0, 25);
        $payload .= '59' . str_pad(strlen($cleanMerchantName), 2, '0', STR_PAD_LEFT) . $cleanMerchantName;
        
        // 60: Merchant City
        $cleanCity = substr(preg_replace('/[^A-Za-z0-9 ]/', '', $city), 0, 15);
        $payload .= '60' . str_pad(strlen($cleanCity), 2, '0', STR_PAD_LEFT) . $cleanCity;
        
        // 63: CRC
        $payload .= '6304';
        
        // Compute CRC
        $crc = self::calculateCRC($payload);
        
        return $payload . $crc;
    }
}

<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    NetReviews SAS <contact@avis-verifies.com>
 * @copyright 2012-2024 NetReviews SAS
 * @license   NetReviews
 *
 * @version   Release: $Revision: 9.0.0
 *
 * @date      22/08/2024
 * International Registered Trademark & Property of NetReviews SAS
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class Base32
{
    /**
     * @var array<string>
     */
    private static $map = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
        'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
        'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31
        '=',  // padding char
    ];

    /**
     * @var array<int|string, string>
     */
    private static $flippedMap = [
        'A' => '0', 'B' => '1', 'C' => '2', 'D' => '3', 'E' => '4', 'F' => '5', 'G' => '6', 'H' => '7',
        'I' => '8', 'J' => '9', 'K' => '10', 'L' => '11', 'M' => '12', 'N' => '13', 'O' => '14', 'P' => '15',
        'Q' => '16', 'R' => '17', 'S' => '18', 'T' => '19', 'U' => '20', 'V' => '21', 'W' => '22', 'X' => '23',
        'Y' => '24', 'Z' => '25', '2' => '26', '3' => '27', '4' => '28', '5' => '29', '6' => '30', '7' => '31',
    ];

    /**
     *    Use padding false when encoding for urls
     *
     * @param string $input
     * @param bool $padding
     *
     * @return string base32 encoded
     *
     * @author Bryan Ruiz
     **/
    public static function encode(string $input, bool $padding = true)
    {
        if (empty($input)) {
            return '';
        }
        $input = str_split($input);
        $binaryString = '';
        for ($i = 0; $i < count($input); ++$i) {
            /* @phpstan-ignore-next-line */
            $binaryString .= str_pad(base_convert(ord($input[$i]), 10, 2), 8, '0', STR_PAD_LEFT);
        }
        $fiveBitBinaryArray = str_split($binaryString, 5);
        $base32 = '';
        $i = 0;
        while ($i < count($fiveBitBinaryArray)) {
            $base32 .= self::$map[base_convert(str_pad($fiveBitBinaryArray[$i], 5, '0'), 2, 10)];
            ++$i;
        }
        if ($padding && ($x = strlen($binaryString) % 40) != 0) {
            if ($x == 8) {
                $base32 .= str_repeat(self::$map[32], 6);
            } elseif ($x == 16) {
                $base32 .= str_repeat(self::$map[32], 4);
            } elseif ($x == 24) {
                $base32 .= str_repeat(self::$map[32], 3);
            } elseif ($x == 32) {
                $base32 .= self::$map[32];
            }
        }

        return $base32;
    }

    /**
     * decode base 32
     *
     * @param string $input
     *
     * @return string|bool
     */
    public static function decode($input)
    {
        if (empty($input)) {
            return '';
        }
        $paddingCharCount = substr_count($input, self::$map[32]);
        $allowedValues = [6, 4, 3, 1, 0];
        if (!in_array($paddingCharCount, $allowedValues)) {
            return false;
        }
        for ($i = 0; $i < 4; ++$i) {
            if ($paddingCharCount == $allowedValues[$i]
                && substr($input, -$allowedValues[$i]) != str_repeat(self::$map[32], $allowedValues[$i])) {
                return false;
            }
        }
        $input = str_replace('=', '', $input);
        $input = str_split($input);
        $binaryString = '';
        for ($i = 0; $i < count($input); $i = $i + 8) {
            $x = '';
            if (!in_array($input[$i], self::$map)) {
                return false;
            }
            for ($j = 0; $j < 8; ++$j) {
                $x .= str_pad(base_convert(@self::$flippedMap[@$input[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }
            $eightBits = str_split($x, 8);
            for ($z = 0; $z < count($eightBits); ++$z) {
                /* @phpstan-ignore-next-line */
                $binaryString .= (($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48) ? $y : '';
            }
        }

        return $binaryString;
    }
}

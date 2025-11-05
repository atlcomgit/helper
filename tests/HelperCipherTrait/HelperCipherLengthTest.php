<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCipherTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест минимальной длины шифрованных значений
 * @see \Atlcom\Traits\HelperCipherTrait
 */
final class HelperCipherLengthTest extends TestCase
{
    /**
     * Проверяет, что короткие значения кодируются компактно
     * @see \Atlcom\Traits\HelperCipherTrait::cipherEncode()
     *
     * @return void
     */
    #[Test]
    public function cipherShortValues(): void
    {
        $password = 'length-check';
        $samples = [
            'abc',
            '1',
            true,
            false,
            null,
            123,
        ];

        foreach ($samples as $value) {
            $encoded = Helper::cipherEncode($value, $password);
            $legacy = Helper::cryptEncode($value, $password);

            $this->assertNotSame('', $encoded);
            $this->assertSame($value, Helper::cipherDecode($encoded, $password));
            $this->assertLessThan(strlen($legacy), strlen($encoded));
            $this->assertMatchesRegularExpression('/^[1-9A-HJ-NP-Za-km-z]+$/', $encoded);
        }
    }


    /**
     * Проверяет, что длинные значения при необходимости сжимаются
     * @see \Atlcom\Traits\HelperCipherTrait::cipherEncode()
     *
     * @return void
     */
    #[Test]
    public function cipherCompressedValues(): void
    {
        $password = 'length-check';
        $longValue = str_repeat('0123456789ABCDEF', 32);
        $encoded = Helper::cipherEncode($longValue, $password);
        $legacy = Helper::cryptEncode($longValue, $password, true);

        $this->assertNotSame('', $encoded);
        $this->assertSame($longValue, Helper::cipherDecode($encoded, $password));
        $this->assertLessThan(strlen($legacy), strlen($encoded));
        $this->assertMatchesRegularExpression('/^[1-9A-HJ-NP-Za-km-z]+$/', $encoded);
    }
}

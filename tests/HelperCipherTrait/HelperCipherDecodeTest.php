<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCipherTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода расшифровки
 * @see \Atlcom\Traits\HelperCipherTrait::cipherDecode()
 */
final class HelperCipherDecodeTest extends TestCase
{
    /**
     * Тест расшифровки и обработки ошибок
     * @see \Atlcom\Traits\HelperCipherTrait::cipherDecode()
     *
     * @return void
     */
    #[Test]
    public function cipherDecode(): void
    {
        $password = 'My$ecretPass123';
        $value = 'Конфиденциальные данные';
        $encoded = Helper::cipherEncode($value, $password);
        $decoded = Helper::cipherDecode($encoded, $password);
        $this->assertSame($value, $decoded);

        $wrongDecoded = Helper::cipherDecode($encoded, 'My$ecretPass124');
        $this->assertNull($wrongDecoded);

        $this->assertNull(Helper::cipherDecode(null, $password));
        $this->assertNull(Helper::cipherDecode('', $password));
        $this->assertNull(Helper::cipherDecode('@@not-base64@@', $password));

        $corrupted = substr($encoded, 0, -4) . 'abcd';
        $this->assertNull(Helper::cipherDecode($corrupted, $password));

        $values = [
            'Ещё одна строка',
            [1, 2, 3, 'value' => 'тест'],
            123456,
            false,
            3.14,
        ];

        foreach ($values as $index => $item) {
            $pass = $password . $index;
            $token = Helper::cipherEncode($item, $pass);
            $decodedItem = Helper::cipherDecode($token, $pass);
            $this->assertEquals($item, $decodedItem);
            $this->assertNull(Helper::cipherDecode($token, $pass . '!'));
        }

        for ($i = 0; $i < 5; $i++) {
            $source = random_bytes(10 + $i);
            $pass = 'Pass' . $i . '#';
            $token = Helper::cipherEncode($source, $pass);
            $this->assertSame($source, Helper::cipherDecode($token, $pass));
            $this->assertNull(Helper::cipherDecode($token, $pass . '1'));
        }
    }
}

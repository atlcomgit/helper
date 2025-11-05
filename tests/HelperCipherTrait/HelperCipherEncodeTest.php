<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCipherTrait;

use Atlcom\Helper;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

class CustomObjectCipherEncode
{
    public string $name;
    public Carbon $date;
}

/**
 * Тест метода шифрования
 * @see \Atlcom\Traits\HelperCipherTrait::cipherEncode()
 */
final class HelperCipherEncodeTest extends TestCase
{
    /**
     * Тест шифрования нескольких значений
     * @see \Atlcom\Traits\HelperCipherTrait::cipherEncode()
     *
     * @return void
     */
    #[Test]
    public function cipherEncode(): void
    {
        $password = 'Str0ng!Pass';
        $simpleValue = 'Привет, мир!';
        $encoded = Helper::cipherEncode($simpleValue, $password);
        $this->assertNotSame('', $encoded);
        $this->assertMatchesRegularExpression('/^[1-9A-HJ-NP-Za-km-z]+$/', $encoded);
        $legacySimple = Helper::cryptEncode($simpleValue, $password);
        $this->assertGreaterThan(strlen($encoded), strlen($legacySimple));
        $decoded = Helper::cipherDecode($encoded, $password);
        $this->assertSame($simpleValue, $decoded);

        $anotherEncoded = Helper::cipherEncode($simpleValue, $password);
        $this->assertNotSame($encoded, $anotherEncoded);

        $longValue = str_repeat('Шифрование данных ', 32);
        $legacyEncoded = Helper::cryptEncode($longValue, $password, true);
        $cipherEncoded = Helper::cipherEncode($longValue, $password);
        $this->assertNotSame('', $cipherEncoded);
        $this->assertLessThan(strlen($legacyEncoded), strlen($cipherEncoded));
        $this->assertSame($longValue, Helper::cipherDecode($cipherEncoded, $password));

        $values = [
            null,
            '',
            'ASCII',
            'Юникод строка',
            0,
            42,
            3.1415,
            -199.99,
            true,
            false,
            ['name' => 'Алиса', 'age' => 30, 'nested' => ['flag' => true]],
        ];

        foreach ($values as $value) {
            $encodedValue = Helper::cipherEncode($value, $password);
            $this->assertNotSame('', $encodedValue);
            $this->assertMatchesRegularExpression('/^[1-9A-HJ-NP-Za-km-z]+$/', $encodedValue);
            $decodedValue = Helper::cipherDecode($encodedValue, $password);
            $this->assertEquals($value, $decodedValue);
        }

        $object = new stdClass();
        $object->name = 'Иванов Иван';
        $object->createdAt = Carbon::parse('2025-01-02 03:04:05');
        $encodedObject = Helper::cipherEncode($object, $password);
        $decodedObject = Helper::cipherDecode($encodedObject, $password);
        $this->assertInstanceOf(stdClass::class, $decodedObject);
        $this->assertSame($object->name, $decodedObject->name);
        $this->assertInstanceOf(Carbon::class, $decodedObject->createdAt);
        $this->assertSame('2025-01-02 03:04:05', $decodedObject->createdAt->format('Y-m-d H:i:s'));

        $customObject = new CustomObjectCipherEncode();
        $customObject->name = 'Пользователь';
        $customObject->date = Carbon::parse('2025-11-05 10:15:30');
        $encodedCustom = Helper::cipherEncode($customObject, $password);
        $decodedCustom = Helper::cipherDecode($encodedCustom, $password);
        $this->assertInstanceOf(CustomObjectCipherEncode::class, $decodedCustom);
        $this->assertSame('Пользователь', $decodedCustom->name);
        $this->assertInstanceOf(Carbon::class, $decodedCustom->date);
        $this->assertSame('2025-11-05 10:15:30', $decodedCustom->date->format('Y-m-d H:i:s'));

        for ($index = 0; $index < 5; $index++) {
            $source = bin2hex(random_bytes(16 + $index));
            $pass = 'Pw' . $index . '!';
            $token = Helper::cipherEncode($source, $pass);
            $this->assertNotSame('', $token);
            $this->assertMatchesRegularExpression('/^[1-9A-HJ-NP-Za-km-z]+$/', $token);
            $decodedSource = Helper::cipherDecode($token, $pass);
            $this->assertSame($source, $decodedSource);
        }
    }
}

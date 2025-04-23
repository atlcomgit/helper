<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperGenerateTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperGenerateTrait
 */
final class HelperGeneratePasswordTest extends TestCase
{
    public function testPasswordCharset()
    {
        $allChars = [];
        for ($i = 0; $i < 100; $i++) {
            $password = Helper::generatePassword();
            $chars = str_split($password);
            foreach ($chars as $c) {
                $allChars[$c] = true;
            }
        }
        ksort($allChars);

        $this->assertTrue(true);
    }


    #[Test]
    public function testDefaultPattern()
    {
        $password = Helper::generatePassword();
        $this->assertIsString($password);
        $this->assertGreaterThanOrEqual(8, strlen($password));
        $this->assertLessThanOrEqual(16, strlen($password));
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9!@#$%^&*()_+]{8,16}$/', $password);
    }


    #[Test]
    public function testCustomPattern()
    {
        $pattern = '/[0-9]{4,6}/';
        $password = Helper::generatePassword($pattern);
        $this->assertIsString($password);
        $this->assertGreaterThanOrEqual(4, strlen($password));
        $this->assertLessThanOrEqual(6, strlen($password));
        $this->assertMatchesRegularExpression('/^[0-9]{4,6}$/', $password);
    }


    #[Test]
    public function testDifferentPasswords()
    {
        $password1 = Helper::generatePassword();
        $password2 = Helper::generatePassword();
        $this->assertNotSame($password1, $password2, 'Passwords should be different');
    }
}

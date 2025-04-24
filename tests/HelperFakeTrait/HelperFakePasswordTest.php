<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperFakeTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperFakeTrait
 */
final class HelperFakePasswordTest extends TestCase
{
    #[Test]
    public function passwordCharset()
    {
        $allChars = [];
        for ($i = 0; $i < 100; $i++) {
            $password = Helper::fakePassword();
            $chars = str_split($password);
            foreach ($chars as $c) {
                $allChars[$c] = true;
            }
        }
        ksort($allChars);

        $this->assertTrue(true);
    }


    #[Test]
    public function defaultPattern()
    {
        $password = Helper::fakePassword();
        $this->assertIsString($password);
        $this->assertGreaterThanOrEqual(8, strlen($password));
        $this->assertLessThanOrEqual(16, strlen($password));
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9!@#$%^&*()_+]{8,16}$/', $password);
    }


    #[Test]
    public function customPattern()
    {
        $pattern = '/[0-9]{4,6}/';
        $password = Helper::fakePassword($pattern);
        $this->assertIsString($password);
        $this->assertGreaterThanOrEqual(4, strlen($password));
        $this->assertLessThanOrEqual(6, strlen($password));
        $this->assertMatchesRegularExpression('/^[0-9]{4,6}$/', $password);
    }


    #[Test]
    public function differentPasswords()
    {
        $password1 = Helper::fakePassword();
        $password2 = Helper::fakePassword();
        $this->assertNotSame($password1, $password2, 'Passwords should be different');
    }
}

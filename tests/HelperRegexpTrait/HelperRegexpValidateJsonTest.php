<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperRegexpTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperRegexpTrait
 */
final class HelperRegexpValidateJsonTest extends TestCase
{
    #[Test]
    public function regexpValidateJson(): void
    {
        $string = '';
        $boolean = Helper::regexpValidateJson($string);
        $this->assertTrue(json_decode($string, true) === null);
        $this->assertTrue($boolean === false);

        $string = '1';
        $boolean = Helper::regexpValidateJson($string);
        $this->assertTrue(json_decode($string, true) === 1);
        $this->assertTrue($boolean === false);

        $string = '"1"';
        $boolean = Helper::regexpValidateJson($string);
        $this->assertTrue(json_decode($string, true) === '1');
        $this->assertTrue($boolean === false);

        $string = '[1]';
        $boolean = Helper::regexpValidateJson($string);
        $this->assertTrue(json_decode($string, true) === [1]);
        $this->assertTrue($boolean === true);

        $string = '{1}';
        $boolean = Helper::regexpValidateJson($string);
        $this->assertTrue(json_decode($string, true) === null);
        $this->assertTrue($boolean === false);

        $string = '{"a":1}';
        $boolean = Helper::regexpValidateJson($string);
        $this->assertTrue(json_decode($string, true) === ['a' => 1]);
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateJson(null);
        $this->assertTrue($boolean === false);
    }
}

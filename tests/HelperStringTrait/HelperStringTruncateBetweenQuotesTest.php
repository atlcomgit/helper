<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperStringTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringTruncateBetweenQuotesTest extends TestCase
{
    /**
     * Тест усечения строковых значений в JSON
     * @see \Atlcom\Traits\HelperStringTrait::stringTruncateBetweenQuotes()
     *
     * @return void
     */
    #[Test]
    public function stringTruncateBetweenQuotes(): void
    {
        $string = Helper::stringTruncateBetweenQuotes('{"a": "12345", "b":"c2defXyz", "c": 12345}');
        $this->assertEquals($string, '{"a": "12…", "b":"c2…", "c": 12345}');

        $string = Helper::stringTruncateBetweenQuotes('{"a": "12"}');
        $this->assertEquals($string, '{"a": "12"}');

        $string = Helper::stringTruncateBetweenQuotes('{"a":"abcdef"}', 5);
        $this->assertEquals($string, '{"a":"abcd…"}');

        $string = Helper::stringTruncateBetweenQuotes('{"a": "abcdef"}', 0);
        $this->assertEquals($string, '{"a": ""}');

        $string = Helper::stringTruncateBetweenQuotes('{"a": "abcdef"}', 1);
        $this->assertEquals($string, '{"a": "…"}');

        $string = Helper::stringTruncateBetweenQuotes('{"abcdef": "abcdef"}', 1);
        $this->assertEquals($string, '{"abcdef": "…"}');

        $string = Helper::stringTruncateBetweenQuotes('{"text": "ab\"cd"}', 3);
        $this->assertEquals($string, '{"text": "ab…"}');

        $string = Helper::stringTruncateBetweenQuotes(null, null);
        $this->assertEquals($string, '');
    }
}

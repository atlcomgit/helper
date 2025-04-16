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
final class HelperStringPadSuffixTest extends TestCase
{
    #[Test]
    public function stringPadSuffix(): void
    {
        $string = Helper::stringPadSuffix('abc', 'def', true);
        $this->assertTrue($string === 'abcdef');

        $string = Helper::stringPadSuffix('abc', 'def');
        $this->assertTrue($string === 'abcdef');

        $string = Helper::stringPadSuffix('', 'def');
        $this->assertTrue($string === '');

        $string = Helper::stringPadSuffix(null, null);
        $this->assertTrue($string === '');
    }
}

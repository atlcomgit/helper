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
final class HelperStringPadPrefixTest extends TestCase
{
    #[Test]
    public function stringPadPrefix(): void
    {
        $string = Helper::stringPadPrefix('def', 'abc', true);
        $this->assertTrue($string === 'abcdef');
    }
}

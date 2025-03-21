<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperIntervalTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тесты трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringAddPrefixTest extends TestCase
{
    #[Test]
    public function stringAddPrefix(): void
    {
        $string = Helper::stringAddPrefix('def', 'abc', true);
        $this->assertTrue($string === 'abcdef');
    }
}

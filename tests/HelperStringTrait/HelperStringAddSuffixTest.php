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
final class HelperStringAddSuffixTest extends TestCase
{
    #[Test]
    public function stringAddSuffix(): void
    {
        $string = Helper::stringAddSuffix('abc', 'def', true);
        $this->assertTrue($string === 'abcdef');
    }
}

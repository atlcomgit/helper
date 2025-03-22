<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperIntervalTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringPluralTest extends TestCase
{
    #[Test]
    public function stringPlural(): void
    {
        $string = Helper::stringPlural(0, ['штук', 'штука', 'штуки']);
        $this->assertTrue($string === '0 штук');

        $string = Helper::stringPlural(1, ['штук', 'штука', 'штуки']);
        $this->assertTrue($string === '1 штука');

        $string = Helper::stringPlural(2, ['штук', 'штука', 'штуки']);
        $this->assertTrue($string === '2 штуки');

        $string = Helper::stringPlural(1, ['штук', 'штука', 'штуки'], false);
        $this->assertTrue($string === 'штука');
    }
}

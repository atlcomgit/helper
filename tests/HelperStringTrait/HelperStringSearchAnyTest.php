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
final class HelperStringSearchAnyTest extends TestCase
{
    #[Test]
    public function stringSearchAny(): void
    {
        $array = Helper::stringSearchAny('abcd', 'bc');
        $this->assertTrue($array === ['bc']);

        $array = Helper::stringSearchAny('abcd', 'f');
        $this->assertTrue($array === []);

        $array = Helper::stringSearchAny('abcd', ['f', 'b']);
        $this->assertTrue($array === ['b']);

        $array = Helper::stringSearchAny('Иванов Иван Иванович', ['name1' => 'Петр', 'name2' => 'Иван']);
        $this->assertTrue($array === ['Иван']);
    }
}

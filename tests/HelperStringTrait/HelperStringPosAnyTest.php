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
final class HelperStringPosAnyTest extends TestCase
{
    #[Test]
    public function stringPosAny(): void
    {
        $array = Helper::stringPosAny('abcd', 'bc');
        $this->assertTrue($array === ['bc' => 1]);

        $array = Helper::stringPosAny('abcd', 'f');
        $this->assertTrue($array === []);

        $array = Helper::stringPosAny('abcd', ['f', 'b', 'c']);
        $this->assertTrue($array === ['b' => 1]);

        $array = Helper::stringPosAny('Иванов Иван Иванович и Петр', ['name1' => 'Петр', 'name2' => 'Иван']);
        $this->assertTrue($array === ['Петр' => 23]);
    }
}

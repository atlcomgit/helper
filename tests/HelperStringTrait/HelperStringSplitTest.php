<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperStringTrait;

use Atlcom\Consts\HelperConsts;
use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringSplitTest extends TestCase
{
    #[Test]
    public function stringSplit(): void
    {
        $array = Helper::stringSplit('abc,def', ',');
        $this->assertTrue($array === ['abc', 'def']);

        $array = Helper::stringSplit(',abc,def,', ',');
        $this->assertTrue($array === ['', 'abc', 'def', '']);

        $array = Helper::stringSplit('abc,def', [',']);
        $this->assertTrue($array === ['abc', 'def']);

        $array = Helper::stringSplit('abc,,def/xyz', [',', '/']);
        $this->assertTrue($array === ['abc', '', 'def', 'xyz']);

        $array = Helper::stringSplit('abc def', ',');
        $this->assertTrue($array === ['abc def']);

        $array = Helper::stringSplit('abc def', [',', '/']);
        $this->assertTrue($array === ['abc def']);

        $string = Helper::stringSplit('abc def', ' ', 0);
        $this->assertTrue($string === 'abc');

        $string = Helper::stringSplit('abc def', ' ', 1);
        $this->assertTrue($string === 'def');

        $string = Helper::stringSplit('abc def', ' ', -1);
        $this->assertTrue($string === 'def');

        $string = Helper::stringSplit('abc def', ' ', 2);
        $this->assertTrue($string === null);

        $string = Helper::stringSplit('abc def', ' ', -3);
        $this->assertTrue($string === null);

        $string = Helper::stringSplit('abc', '');
        $this->assertTrue($string === ['abc']);

        $string = Helper::stringSplit(
            "UPDATE table_a SET level=1, message = 'text' WHERE id IN NOT NULL OR deleted_at IS NULL;",
            [...HelperConsts::SQL_RESERVED_WORDS, ...HelperConsts::SQL_OPERATORS],
        );
        $this->assertSame([
            '',
            ' table_a ',
            ' level',
            '1',
            ' message ',
            " 'text' ",
            ' id ',
            ' ',
            ' ',
            ' ',
            ' deleted_at ',
            ' ',
            '',
            '',
        ], $string);

        $string = Helper::stringSplit(null, ' ');
        $this->assertTrue($string === []);
    }
}

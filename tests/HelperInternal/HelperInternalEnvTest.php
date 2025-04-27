<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperInternal;

use Atlcom\Helper;
use Atlcom\Internal\HelperInternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода
 * @see \Atlcom\Internal\HelperInternal
 */
class HelperInternalEnvTest extends TestCase
{
    #[Test]
    public function internalEnv()
    {
        Helper::cacheRuntimeClear();

        $this->assertSame('Atlcom Helper', HelperInternal::internalEnv('APP_ENV'));
        $this->assertSame('', HelperInternal::internalEnv('EMPTY_1'));
        $this->assertSame('', HelperInternal::internalEnv('EMPTY_2'));

        $this->assertSame(true, HelperInternal::internalEnv('BOOL_TRUE_1'));
        $this->assertSame(true, HelperInternal::internalEnv('BOOL_TRUE_2'));

        $this->assertSame(false, HelperInternal::internalEnv('BOOL_FALSE_1'));
        $this->assertSame(false, HelperInternal::internalEnv('BOOL_FALSE_2'));

        $this->assertSame(0, HelperInternal::internalEnv('INT_1'));
        $this->assertSame(1, HelperInternal::internalEnv('INT_2'));
        $this->assertSame(-1, HelperInternal::internalEnv('INT_3'));
        $this->assertSame('10000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000', HelperInternal::internalEnv('INT_4'));
        $this->assertIsString(HelperInternal::internalEnv('INT_4'));
        $this->assertSame('123', HelperInternal::internalEnv('INT_5'));

        $this->assertSame(1.2, HelperInternal::internalEnv('FLOAT_1'));
        $this->assertSame(-1.2, HelperInternal::internalEnv('FLOAT_2'));
        $this->assertSame('1111111111.222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222', HelperInternal::internalEnv('FLOAT_3'));
        $this->assertIsString(HelperInternal::internalEnv('FLOAT_3'));

        $this->assertSame('string', HelperInternal::internalEnv('STRING_1'));
        $this->assertSame('string', HelperInternal::internalEnv('STRING_2'));
        $this->assertSame('true', HelperInternal::internalEnv('STRING_3'));
        $this->assertSame('(true)', HelperInternal::internalEnv('STRING_4'));
        $this->assertSame('false', HelperInternal::internalEnv('STRING_5'));
        $this->assertSame('(false)', HelperInternal::internalEnv('STRING_6'));
        $this->assertSame('empty', HelperInternal::internalEnv('STRING_7'));
        $this->assertSame('(empty)', HelperInternal::internalEnv('STRING_8'));
        $this->assertSame('Примерный текст', HelperInternal::internalEnv('STRING_9'));
        $this->assertSame('Примерный текст', HelperInternal::internalEnv('STRING_10'));
        $this->assertSame('', HelperInternal::internalEnv('STRING_11'));
        $this->assertSame('', HelperInternal::internalEnv('STRING_12'));

        $this->assertSame(null, HelperInternal::internalEnv('NULL_1'));
        $this->assertSame(null, HelperInternal::internalEnv('NULL_2'));
        $this->assertSame(null, HelperInternal::internalEnv('NULL_3'));

        $this->assertSame('Atlcom Helper', HelperInternal::internalEnv('VAR_1'));
        $this->assertSame('Atlcom Helper', HelperInternal::internalEnv('VAR_2'));
        $this->assertSame('Atlcom Helper', HelperInternal::internalEnv('VAR_3'));
        $this->assertSame(null, HelperInternal::internalEnv('VAR_4'));
        $this->assertSame(null, HelperInternal::internalEnv('VAR_5'));
        $this->assertSame(null, HelperInternal::internalEnv('VAR_6'));
        $this->assertSame('a', HelperInternal::internalEnv('VAR_7'));
        $this->assertSame('"a"', HelperInternal::internalEnv('VAR_8'));
        $this->assertSame('$a', HelperInternal::internalEnv('VAR_9'));
        $this->assertSame('$a', HelperInternal::internalEnv('VAR_10'));

        $this->assertSame('comment', HelperInternal::internalEnv('COMMENT_1'));
        $this->assertSame(null, HelperInternal::internalEnv('COMMENT_2'));
        $this->assertSame('comment', HelperInternal::internalEnv('COMMENT_3'));

        $this->assertSame(123, HelperInternal::internalEnv('SPACE_1'));
        $this->assertSame('abc', HelperInternal::internalEnv('SPACE_2'));
        $this->assertSame(' abc ', HelperInternal::internalEnv('SPACE_3'));
        $this->assertSame(' abc ', HelperInternal::internalEnv('SPACE_4'));
        $this->assertSame(1, HelperInternal::internalEnv('SPACE_5'));
        $this->assertSame('abc', HelperInternal::internalEnv('SPACE_6'));
        $this->assertSame('	a	', HelperInternal::internalEnv('SPACE_7'));
        $this->assertSame('	a	', HelperInternal::internalEnv('SPACE_8'));
        $this->assertSame('"	a	"', HelperInternal::internalEnv('SPACE_9'));

        $this->assertSame("a\nb\n\nc", HelperInternal::internalEnv('DATA_1'));
        $this->assertSame("a\nb\n\nc", HelperInternal::internalEnv('DATA_2'));
        $this->assertSame("a\nb\n\na\nb\n\nc", HelperInternal::internalEnv('DATA_3'));
        $this->assertSame("a\nb\n\tc\nd", HelperInternal::internalEnv('DATA_4'));
        $this->assertSame("a\nb\n\tc\nd", HelperInternal::internalEnv('DATA_4'));

        $this->assertSame([], HelperInternal::internalEnv('ARRAY_1'));
        $this->assertSame([], HelperInternal::internalEnv('ARRAY_2'));
        $this->assertSame([0 => 1, 1 => 2], HelperInternal::internalEnv('ARRAY_3'));
        $this->assertSame([0 => '1', 1 => '2'], HelperInternal::internalEnv('ARRAY_4'));
        $this->assertSame("['1', '2']", HelperInternal::internalEnv('ARRAY_5'));
        $this->assertSame([], HelperInternal::internalEnv('ARRAY_6'));
        $this->assertSame(['a' => 1, 'b' => 2, 'c' => '3'], HelperInternal::internalEnv('ARRAY_7'));
        $this->assertSame('{\"a\":1, \"b\":2, \"c\":\"3\"}', HelperInternal::internalEnv('ARRAY_8'));
        $this->assertSame('{"a":1, "b":2, "c":"3"}', HelperInternal::internalEnv('ARRAY_9'));
        $this->assertSame(['a' => 1, 'b' => 2, 'c' => '3'], HelperInternal::internalEnv('ARRAY_10'));

        $this->assertSame('value2', HelperInternal::internalEnv('DUPLICATE'));

    }
}

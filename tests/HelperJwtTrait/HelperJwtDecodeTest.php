<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperJwtTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperJwtTrait
 */
final class HelperJwtDecodeTest extends TestCase
{
    #[Test]
    public function jwtDecode(): void
    {
        $array = Helper::jwtDecode('eyJhbGciOiJTSEE1MTIiLCJ0eXAiOiJKV1QifQ.W10.KwfjBPnnk-A2zALCVXbcfn8UQaPuvFEOgQuFR7Xp-YfSFoVoIdDcjCYydfOBYMTRpI1v8ZY1dsFoaSuaO70QaA');
        $this->assertEquals([
            'header' => ['alg' => 'SHA512', 'typ' => 'JWT'],
            'body' => [],
        ], $array);

        $array = Helper::jwtDecode('eyJhbGciOiJTSEE1MTIiLCJ0eXAiOiJKV1QifQ.eyJpZCI6MX0.l8fbKNjJWSsZLjVfE5aUOpEhIqbMAxIcon8nZ8NYyPikf_AtjrrN0Y3cPTy1A0U3etLMCbZ6RE00FqFYLZBc7A');
        $this->assertEquals([
            'header' => ['alg' => 'SHA512', 'typ' => 'JWT'],
            'body' => ['id' => 1],
        ], $array);

        $array = Helper::jwtDecode('eyJhZG1pbiI6dHJ1ZSwiYWxnIjoiU0hBNTEyIiwidHlwIjoiSldUIn0.eyJpZCI6MX0._YBXyeFWWC7uixHQTBKzvEYoVTzYOeJzKoDRy7T1AnzKZK-7Blfd84gkN3abgiCMeNasbYVh_NICHa-s_Px3lg');
        $this->assertEquals([
            'header' => ['alg' => 'SHA512', 'typ' => 'JWT', 'admin' => true],
            'body' => ['id' => 1],
        ], $array);

        $array = Helper::jwtDecode('eyJhbGciOiJTSEEyNTYiLCJ0eXAiOiJKV1QifQ.eyJpZCI6MX0.jeH2xt5EOGgPBH5u1nsrMDoWtNBd_BmiD44m-wlKDeo');
        $this->assertNull($array);

        $array = Helper::jwtDecode(
            'eyJhbGciOiJTSEEyNTYiLCJ0eXAiOiJKV1QifQ.eyJpZCI6MX0.jeH2xt5EOGgPBH5u1nsrMDoWtNBd_BmiD44m-wlKDeo',
            signKey: '123',
        );
        $this->assertEquals([
            'header' => ['alg' => 'SHA256', 'typ' => 'JWT'],
            'body' => ['id' => 1],
        ], $array);

        $array = Helper::jwtDecode(null, null);
        $this->assertTrue($array === null);
    }
}

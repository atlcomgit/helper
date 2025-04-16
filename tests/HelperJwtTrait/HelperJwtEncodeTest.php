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
final class HelperJwtEncodeTest extends TestCase
{
    #[Test]
    public function jwtEncode(): void
    {
        $string = Helper::jwtEncode([]);
        $this->assertEquals('eyJhbGciOiJTSEE1MTIiLCJ0eXAiOiJKV1QifQ.W10.KwfjBPnnk-A2zALCVXbcfn8UQaPuvFEOgQuFR7Xp-YfSFoVoIdDcjCYydfOBYMTRpI1v8ZY1dsFoaSuaO70QaA', $string);

        $string = Helper::jwtEncode(['id' => 1]);
        $this->assertEquals('eyJhbGciOiJTSEE1MTIiLCJ0eXAiOiJKV1QifQ.eyJpZCI6MX0.l8fbKNjJWSsZLjVfE5aUOpEhIqbMAxIcon8nZ8NYyPikf_AtjrrN0Y3cPTy1A0U3etLMCbZ6RE00FqFYLZBc7A', $string);

        $string = Helper::jwtEncode(['id' => 1], ['admin' => true]);
        $this->assertEquals('eyJhZG1pbiI6dHJ1ZSwiYWxnIjoiU0hBNTEyIiwidHlwIjoiSldUIn0.eyJpZCI6MX0._YBXyeFWWC7uixHQTBKzvEYoVTzYOeJzKoDRy7T1AnzKZK-7Blfd84gkN3abgiCMeNasbYVh_NICHa-s_Px3lg', $string);

        $string = Helper::jwtEncode(['id' => 1], signKey: '123', hash: 'sha256');
        $this->assertEquals('eyJhbGciOiJTSEEyNTYiLCJ0eXAiOiJKV1QifQ.eyJpZCI6MX0.jeH2xt5EOGgPBH5u1nsrMDoWtNBd_BmiD44m-wlKDeo', $string);

        $string = Helper::jwtEncode(null, null);
        $this->assertEquals('eyJhbGciOiJTSEE1MTIiLCJ0eXAiOiJKV1QifQ.W10.KwfjBPnnk-A2zALCVXbcfn8UQaPuvFEOgQuFR7Xp-YfSFoVoIdDcjCYydfOBYMTRpI1v8ZY1dsFoaSuaO70QaA', $string);
    }
}

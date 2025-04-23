<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperUrlTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperUrlTrait
 */
final class HelperUrlParseTest extends TestCase
{
    #[Test]
    public function urlParse(): void
    {
        $array = Helper::urlParse('http://a.com/d/?e=f');
        $this->assertEquals([
            'user' => null,
            'password' => null,
            'scheme' => 'http',
            'host' => 'a.com',
            'path' => '/d',
            'query' => ['e' => 'f'],
            'anchor' => null,
        ], $array);

        $array = Helper::urlParse('http://u:p@a.com/d/?e=f#g');
        $this->assertEquals([
            'user' => 'u',
            'password' => 'p',
            'scheme' => 'http',
            'host' => 'a.com',
            'path' => '/d',
            'query' => ['e' => 'f'],
            'anchor' => 'g',
        ], $array);

        $array = Helper::urlParse('ftp://a.com/c/?s1=f&s2=&s3=Абв&i1=123&i2=1&f1=1.2&b1=true&b2=false');
        $this->assertEquals([
            'scheme' => 'ftp',
            'user' => null,
            'password' => null,
            'host' => 'a.com',
            'path' => '/c',
            'query' => [
                's1' => 'f',
                's2' => '',
                's3' => 'Абв',
                'i1' => 123,
                'i2' => 1,
                'f1' => 1.2,
                'b1' => true,
                'b2' => false,
            ],
            'anchor' => null,
        ], $array);

        $array = Helper::urlParse('https://domain.com/path/subpath?param1=test1&param2=test2');
        $this->assertEquals([
            'scheme' => 'https',
            'user' => null,
            'password' => null,
            'host' => 'domain.com',
            'path' => '/path/subpath',
            'query' => [
                'param1' => 'test1',
                'param2' => 'test2',
            ],
            'anchor' => null,
        ], $array);

        $array = Helper::urlParse('domain.com');
        $this->assertEquals([
            'scheme' => null,
            'user' => null,
            'password' => null,
            'host' => 'domain.com',
            'path' => null,
            'query' => [],
            'anchor' => null,
        ], $array);

        $array = Helper::urlParse('domain.com?param1=test1');
        $this->assertEquals([
            'scheme' => null,
            'user' => null,
            'password' => null,
            'host' => 'domain.com',
            'path' => null,
            'query' => ['param1' => 'test1'],
            'anchor' => null,
        ], $array);

        $array = Helper::urlParse('domain.com?param1=test1#abc');
        $this->assertEquals([
            'scheme' => null,
            'user' => null,
            'password' => null,
            'host' => 'domain.com',
            'path' => null,
            'query' => ['param1' => 'test1'],
            'anchor' => 'abc',
        ], $array);

        $array = Helper::urlParse('/path/subpath?a=b');
        $this->assertEquals([
            'scheme' => null,
            'user' => null,
            'password' => null,
            'host' => null,
            'path' => '/path/subpath',
            'query' => ['a' => 'b'],
            'anchor' => null,
        ], $array);

        $array = Helper::urlParse('tel://79001112233');
        $this->assertEquals([
            'scheme' => 'tel',
            'user' => null,
            'password' => null,
            'host' => '79001112233',
            'path' => null,
            'query' => [],
            'anchor' => null,
        ], $array);

        $array = Helper::urlParse('http://xn--80acd.xn--c1acd/её?%D0%B6=%D0%B7');
        $this->assertEquals([
            'scheme' => 'http',
            'user' => null,
            'password' => null,
            'host' => 'абв.где',
            'path' => '/её',
            'query' => ['ж' => 'з'],
            'anchor' => null,
        ], $array);

        $array = Helper::urlParse('?a=b');
        $this->assertEquals([
            'scheme' => null,
            'user' => null,
            'password' => null,
            'host' => null,
            'path' => null,
            'query' => ['a' => 'b'],
            'anchor' => null,
        ], $array);

        $array = Helper::urlParse('?a=b#def');
        $this->assertEquals([
            'scheme' => null,
            'user' => null,
            'password' => null,
            'host' => null,
            'path' => null,
            'query' => ['a' => 'b'],
            'anchor' => 'def',
        ], $array);

        $array = Helper::urlParse('');
        $this->assertEquals([
            'scheme' => null,
            'user' => null,
            'password' => null,
            'host' => null,
            'path' => null,
            'query' => [],
            'anchor' => null,
        ], $array);

        $array = Helper::urlParse(null);
        $this->assertEquals([
            'scheme' => null,
            'user' => null,
            'password' => null,
            'host' => null,
            'path' => null,
            'query' => [],
            'anchor' => null,
        ], $array);
    }
}

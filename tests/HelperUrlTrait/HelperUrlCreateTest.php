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
final class HelperUrlCreateTest extends TestCase
{
    #[Test]
    public function urlCreate(): void
    {
        $string = Helper::urlCreate(scheme: 'https', host: 'a.com', path: 'b', query: ['c' => 1]);
        $this->assertEquals('https://a.com/b?c=1', $string);

        $string = Helper::urlCreate(scheme: 'ftp://', host: '/a.com/', path: '/b/', query: ['c' => 'd/e']);
        $this->assertEquals('ftp://a.com/b?c=d%2Fe', $string);

        $string = Helper::urlCreate(scheme: 'http://', host: 'абв.Где/', path: '/её/', query: ['ж' => 'з']);
        $this->assertEquals('http://xn--80acd.xn--c1acd/её?%D0%B6=%D0%B7', $string);

        $string = Helper::urlCreate(
            scheme: 'http://',
            host: 'абв.Где/',
            path: '/её/',
            query: ['ж' => 'з'],
            anchor: 'def',
        );
        $this->assertEquals('http://xn--80acd.xn--c1acd/её?%D0%B6=%D0%B7#def', $string);

        $string = Helper::urlCreate(
            user: 'u',
            password: 'p',
            scheme: 'https',
            host: 'a.com',
            path: 'b',
            query: ['c' => 1],
            anchor: 'def',
        );
        $this->assertEquals('https://u:p@a.com/b?c=1#def', $string);
    }
}

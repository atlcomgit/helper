<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperRegexpTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperRegexpTrait
 */
final class HelperRegexpValidateUrlTest extends TestCase
{
    #[Test]
    public function regexpValidateUrl(): void
    {
        $this->assertTrue(Helper::regexpValidateUrl('http://abc.def'));
        $this->assertTrue(Helper::regexpValidateUrl('http://abc.com/def?klm#xyz'));
    }


    #[Test]
    public function testRegexpValidateUrlWithStandardDomain(): void
    {
        $this->assertTrue(Helper::regexpValidateUrl('http://example.com'));
        $this->assertTrue(Helper::regexpValidateUrl('https://sub.domain.org'));
        $this->assertTrue(Helper::regexpValidateUrl('ftp://my-site.ru'));
    }


    #[Test]
    public function testRegexpValidateUrlWithPath(): void
    {
        $this->assertTrue(Helper::regexpValidateUrl('http://example.com/path/to/page'));
        $this->assertTrue(Helper::regexpValidateUrl('https://domain.org/'));
        $this->assertTrue(Helper::regexpValidateUrl('https://domain.org/some-path/123'));
    }


    #[Test]
    public function testRegexpValidateUrlWithQuery(): void
    {
        $this->assertTrue(Helper::regexpValidateUrl('http://example.com?param=value'));
        $this->assertTrue(Helper::regexpValidateUrl('https://domain.org/path?foo=bar&baz=qux'));
    }


    #[Test]
    public function testRegexpValidateUrlWithAnchor(): void
    {
        $this->assertTrue(Helper::regexpValidateUrl('http://example.com#anchor'));
        $this->assertTrue(Helper::regexpValidateUrl('https://domain.org/path#section-2'));
        $this->assertTrue(Helper::regexpValidateUrl('https://domain.org/path?foo=bar#section-2'));
    }


    #[Test]
    public function testRegexpValidateUrlWithIp(): void
    {
        $this->assertTrue(Helper::regexpValidateUrl('http://127.0.0.1'));
        $this->assertTrue(Helper::regexpValidateUrl('https://192.168.1.1/path'));
        $this->assertTrue(Helper::regexpValidateUrl('ftp://8.8.8.8?dns=1'));
        $this->assertTrue(Helper::regexpValidateUrl('http://10.0.0.1#anchor'));
    }


    #[Test]
    public function testRegexpValidateUrlWithPort(): void
    {
        $this->assertTrue(Helper::regexpValidateUrl('http://example.com:8080'));
        $this->assertTrue(Helper::regexpValidateUrl('https://127.0.0.1:443/path'));
    }


    #[Test]
    public function testRegexpValidateUrlWithUserInfo(): void
    {
        $this->assertTrue(Helper::regexpValidateUrl('http://user:pass@example.com'));
        $this->assertTrue(Helper::regexpValidateUrl('ftp://user@8.8.8.8/path'));
    }


    #[Test]
    public function testRegexpValidateUrlInvalidCases(): void
    {
        $this->assertFalse(Helper::regexpValidateUrl('not a url'));
        $this->assertFalse(Helper::regexpValidateUrl('http:/example.com'));
        $this->assertFalse(Helper::regexpValidateUrl('http://'));
        $this->assertFalse(Helper::regexpValidateUrl('http://.com'));
        $this->assertFalse(Helper::regexpValidateUrl('http://256.256.256.256'));
        $this->assertFalse(Helper::regexpValidateUrl('ftp://user@:80'));
    }
}

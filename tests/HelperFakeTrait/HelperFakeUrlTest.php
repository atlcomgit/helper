<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperFakeTrait;

use Atlcom\Enums\HelperRegexpEnum;
use Atlcom\Helper;
use Atlcom\Hlp;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperFakeTrait
 */
final class HelperFakeUrlTest extends TestCase
{
    #[Test]
    public function fakeUrlReturnsString(): void
    {
        $url = Hlp::fakeUrl();
        $this->assertIsString($url);
    }


    #[Test]
    public function fakeUrlHasProtocol(): void
    {
        $url = Helper::fakeUrl();
        $this->assertMatchesRegularExpression(HelperRegexpEnum::Url->value, $url);
    }


    #[Test]
    public function fakeUrlHasDomain(): void
    {
        $url = Helper::fakeUrl();
        $this->assertMatchesRegularExpression('/^https?:\/\/[a-z0-9\-]+(\.[a-z0-9\-]+)+(\/|\?|#|$)/i', $url);
    }


    #[Test]
    public function fakeUrlHasPath(): void
    {
        $url = Helper::fakeUrl();
        $this->assertMatchesRegularExpression('/^https?:\/\/[a-z0-9\-]+(\.[a-z0-9\-]+)+\/[^\s\?]*/i', $url);
    }


    #[Test]
    public function fakeUrlWithQueryParams(): void
    {
        $url = Helper::fakeUrl();
        if (strpos($url, '?') !== false) {
            $query = parse_url($url, PHP_URL_QUERY);
            $this->assertNotEmpty($query);
            foreach (array_filter(explode('&', $query)) as $param) {
                $this->assertMatchesRegularExpression('/^[a-z]{1,8}=[a-z0-9]{1,8}$/', $param);
            }
        } else {
            $this->assertTrue(true, 'No query params is also valid');
        }
    }


    #[Test]
    public function fakeUrlMultipleTimesUnique(): void
    {
        $urls = [];
        for ($i = 0; $i < 10; $i++) {
            $urls[] = Helper::fakeUrl();
        }
        $this->assertGreaterThan(1, count(array_unique($urls)));
    }


    #[Test]
    public function fakeUrlCustomParams(): void
    {
        $url = Helper::fakeUrl(
            ['http'],
            ['testdomain'],
            ['xyz'],
            ['custompath'],
            ['abc=1&def=2'],
        );
        $this->assertStringContainsString('http://testdomain.xyz/custompath?', $url);
        $query = parse_url($url, PHP_URL_QUERY);
        $this->assertNotEmpty($query);
        $this->assertCount(2, explode('&', $query));
    }


    #[Test]
    public function fakeUrlWithAnchor(): void
    {
        // Якорь задан явно
        $url = Helper::fakeUrl(
            null,
            null,
            null,
            null,
            null,
            ['myanchor'],
        );
        $this->assertStringContainsString('#myanchor', $url);
        $this->assertMatchesRegularExpression('/#myanchor$/', $url);
    }


    #[Test]
    public function fakeUrlWithRandomAnchor(): void
    {
        // Якорь не задан, должен быть сгенерирован случайно
        $url = Helper::fakeUrl();
        // Если якорь есть, он должен соответствовать формату
        if (strpos($url, '#') !== false) {
            $this->assertMatchesRegularExpression('/#([a-z0-9\-]{0,10})$/', $url);
        } else {
            $this->assertTrue(true, 'Anchor is optional');
        }
    }


    #[Test]
    public function fakeUrlWithNull(): void
    {
        // Якорь не задан, должен быть сгенерирован случайно
        $url = Helper::fakeUrl(
            protocols: null,
            domainNames: null,
            domainZones: null,
            paths: null,
            queries: null,
            anchors: null,
        );

        $this->assertMatchesRegularExpression(HelperRegexpEnum::Url->value, $url);
    }
}

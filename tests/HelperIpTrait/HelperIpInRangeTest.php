<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperIpTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperIpTrait
 */
final class HelperIpInRangeTest extends TestCase
{
    #[Test]
    public function ipInRangeTrue(): void
    {
        $array = Helper::ipInRange('192.168.1.1', '192.168.1.0/24');
        $this->assertTrue($array === ['192.168.1.0/24']);

        $array = Helper::ipInRange('192.168.1.1', '192.168.1.1');
        $this->assertTrue($array === ['192.168.1.1']);

        $array = Helper::ipInRange('192.168.1.1', '192.168.1.0', '192.168.1.1');
        $this->assertTrue($array === ['192.168.1.1']);

        $array = Helper::ipInRange('192.168.1.1', ['192.168.1.1', '192.168.1.2']);
        $this->assertTrue($array === ['192.168.1.1']);

        $array = Helper::ipInRange('192.168.1.1', ['192.168.1.0/24', '192.168.0.0/16']);
        $this->assertTrue($array === ['192.168.1.0/24', '192.168.0.0/16']);
    }


    #[Test]
    public function ipInRangeFalse(): void
    {
        $array = Helper::ipInRange('192.168.1.1', '192.168.1.2');
        $this->assertTrue($array === []);

        $array = Helper::ipInRange('192.168.1.1', '192.168.1.2', '192.168.1.3');
        $this->assertTrue($array === []);

        $array = Helper::ipInRange('192.168.1.1', ['192.168.1.2', '192.168.1.3']);
        $this->assertTrue($array === []);

        $array = Helper::ipInRange('192.167.1.1', ['192.168.1.0/24', '192.168.0.0/16']);
        $this->assertTrue($array === []);
    }
}
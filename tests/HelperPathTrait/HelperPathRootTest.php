<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperIntervalTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperPathTrait
 */
final class HelperPathRootTest extends TestCase
{
    #[Test]
    public function pathRoot(): void
    {
        $_SERVER['DOCUMENT_ROOT'] = '/home/path';
        $string = Helper::pathRoot();
        $this->assertTrue($string === '/home/path');

        $_SERVER['DOCUMENT_ROOT'] = '';
        $string = Helper::pathRoot();
        $this->assertTrue($string === realpath(''));

        $string = Helper::pathRoot('/home/path/test');
        $this->assertTrue($string === '/home/path/test');
    }
}

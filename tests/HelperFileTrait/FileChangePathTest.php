<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperFileTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperFileTrait::fileChangePath()
 */
final class FileChangePathTest extends TestCase
{
    /**
     * Тест метода трейта
     * @see \Atlcom\Traits\HelperFileTrait::fileChangePath()
     *
     * @return void
     */
    #[Test]
    public function fileChangePath(): void
    {
        // Изменение пути файла
        $result = Helper::fileChangePath('/var/www/html/test.php', '/home/user');
        $this->assertSame('/home/user/test.php', $result);

        // Новый путь с завершающим слешем
        $result = Helper::fileChangePath('/var/www/html/test.php', '/home/user/');
        $this->assertSame('/home/user/test.php', $result);

        // Только имя файла, новый путь
        $result = Helper::fileChangePath('test.php', '/var/www');
        $this->assertSame('/var/www/test.php', $result);

        // Пустой новый путь - возвращает только имя файла
        $result = Helper::fileChangePath('/var/www/html/test.php', '');
        $this->assertSame('test.php', $result);

        // Null новый путь - возвращает только имя файла
        $result = Helper::fileChangePath('/var/www/html/test.php', null);
        $this->assertSame('test.php', $result);

        // Пустая строка файла
        $result = Helper::fileChangePath('', '/home/user');
        $this->assertSame('', $result);

        // Null файла
        $result = Helper::fileChangePath(null, '/home/user');
        $this->assertSame('', $result);

        // Относительный путь
        $result = Helper::fileChangePath('folder/test.txt', 'another/path');
        $this->assertSame('another/path/test.txt', $result);

        // Путь точка
        $result = Helper::fileChangePath('./test.txt', '/new/path');
        $this->assertSame('/new/path/test.txt', $result);
    }
}

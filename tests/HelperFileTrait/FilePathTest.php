<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperFileTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperFileTrait::filePath()
 */
final class FilePathTest extends TestCase
{
    /**
     * Тест метода трейта
     * @see \Atlcom\Traits\HelperFileTrait::filePath()
     *
     * @return void
     */
    #[Test]
    public function filePath(): void
    {
        // Полный путь
        $result = Helper::filePath('/var/www/html/test.php');
        $this->assertSame('/var/www/html', $result);

        // Относительный путь
        $result = Helper::filePath('folder/subfolder/file.txt');
        $this->assertSame('folder/subfolder', $result);

        // Только имя файла без пути
        $result = Helper::filePath('file.txt');
        $this->assertSame('', $result);

        // Пустая строка
        $result = Helper::filePath('');
        $this->assertSame('', $result);

        // Null
        $result = Helper::filePath(null);
        $this->assertSame('', $result);

        // Путь без расширения
        $result = Helper::filePath('/home/user/document');
        $this->assertSame('/home/user', $result);

        // Файл с точкой в начале
        $result = Helper::filePath('/etc/.bashrc');
        $this->assertSame('/etc', $result);
    }
}

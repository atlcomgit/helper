<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperFileTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperFileTrait::fileName()
 */
final class FileNameTest extends TestCase
{
    /**
     * Тест метода трейта
     * @see \Atlcom\Traits\HelperFileTrait::fileName()
     *
     * @return void
     */
    #[Test]
    public function fileName(): void
    {
        // Полный путь
        $result = Helper::fileName('/var/www/html/test.php');
        $this->assertSame('test.php', $result);

        // Относительный путь
        $result = Helper::fileName('folder/subfolder/file.txt');
        $this->assertSame('file.txt', $result);

        // Только имя файла
        $result = Helper::fileName('document.pdf');
        $this->assertSame('document.pdf', $result);

        // Пустая строка
        $result = Helper::fileName('');
        $this->assertSame('', $result);

        // Null
        $result = Helper::fileName(null);
        $this->assertSame('', $result);

        // Файл без расширения
        $result = Helper::fileName('/home/user/README');
        $this->assertSame('README', $result);

        // Файл с точкой в начале
        $result = Helper::fileName('/etc/.bashrc');
        $this->assertSame('.bashrc', $result);

        // Файл с несколькими точками
        $result = Helper::fileName('/path/to/file.name.tar.gz');
        $this->assertSame('file.name.tar.gz', $result);
    }
}

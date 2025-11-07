<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperFileTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperFileTrait::fileExtension()
 */
final class FileExtensionTest extends TestCase
{
    /**
     * Тест метода трейта
     * @see \Atlcom\Traits\HelperFileTrait::fileExtension()
     *
     * @return void
     */
    #[Test]
    public function fileExtension(): void
    {
        // Обычное расширение
        $result = Helper::fileExtension('/var/www/html/test.php');
        $this->assertSame('php', $result);

        // Расширение txt
        $result = Helper::fileExtension('folder/subfolder/file.txt');
        $this->assertSame('txt', $result);

        // Расширение PDF (uppercase)
        $result = Helper::fileExtension('document.PDF');
        $this->assertSame('PDF', $result);

        // Файл без расширения
        $result = Helper::fileExtension('/home/user/README');
        $this->assertSame('', $result);

        // Пустая строка
        $result = Helper::fileExtension('');
        $this->assertSame('', $result);

        // Null
        $result = Helper::fileExtension(null);
        $this->assertSame('', $result);

        // Файл с точкой в начале - считается расширением
        $result = Helper::fileExtension('/etc/.bashrc');
        $this->assertSame('bashrc', $result);

        // Файл с несколькими точками - берется последнее расширение
        $result = Helper::fileExtension('/path/to/file.name.tar.gz');
        $this->assertSame('gz', $result);

        // Только имя файла с расширением
        $result = Helper::fileExtension('test.json');
        $this->assertSame('json', $result);
    }
}

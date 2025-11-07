<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperFileTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperFileTrait::fileChangeExtension()
 */
final class FileChangeExtensionTest extends TestCase
{
    /**
     * Тест метода трейта
     * @see \Atlcom\Traits\HelperFileTrait::fileChangeExtension()
     *
     * @return void
     */
    #[Test]
    public function fileChangeExtension(): void
    {
        // Изменение расширения файла
        $result = Helper::fileChangeExtension('/var/www/html/test.php', 'txt');
        $this->assertSame('/var/www/html/test.txt', $result);

        // Изменение расширения с точкой в новом расширении
        $result = Helper::fileChangeExtension('/var/www/html/test.php', '.json');
        $this->assertSame('/var/www/html/test.json', $result);

        // Только имя файла
        $result = Helper::fileChangeExtension('document.pdf', 'docx');
        $this->assertSame('document.docx', $result);

        // Удаление расширения (пустое новое расширение)
        $result = Helper::fileChangeExtension('/var/www/html/test.php', '');
        $this->assertSame('/var/www/html/test', $result);

        // Удаление расширения (null новое расширение)
        $result = Helper::fileChangeExtension('/var/www/html/test.php', null);
        $this->assertSame('/var/www/html/test', $result);

        // Файл без расширения - добавление расширения
        $result = Helper::fileChangeExtension('/home/user/README', 'md');
        $this->assertSame('/home/user/README.md', $result);

        // Файл без расширения - без нового расширения
        $result = Helper::fileChangeExtension('/home/user/README', '');
        $this->assertSame('/home/user/README', $result);

        // Пустая строка файла
        $result = Helper::fileChangeExtension('', 'txt');
        $this->assertSame('', $result);

        // Null файла
        $result = Helper::fileChangeExtension(null, 'txt');
        $this->assertSame('', $result);

        // Относительный путь
        $result = Helper::fileChangeExtension('folder/subfolder/file.txt', 'csv');
        $this->assertSame('folder/subfolder/file.csv', $result);

        // Файл с несколькими точками
        $result = Helper::fileChangeExtension('/path/to/file.name.tar.gz', 'zip');
        $this->assertSame('/path/to/file.name.tar.zip', $result);

        // Скрытый файл с расширением
        $result = Helper::fileChangeExtension('/etc/.config.yml', 'yaml');
        $this->assertSame('/etc/.config.yaml', $result);
    }
}

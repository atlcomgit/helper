<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperFileTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperFileTrait::fileChangeName()
 */
final class FileChangeNameTest extends TestCase
{
    /**
     * Тест метода трейта
     * @see \Atlcom\Traits\HelperFileTrait::fileChangeName()
     *
     * @return void
     */
    #[Test]
    public function fileChangeName(): void
    {
        // Изменение имени файла с путем
        $result = Helper::fileChangeName('/var/www/html/test.php', 'newfile.php');
        $this->assertSame('/var/www/html/newfile.php', $result);

        // Изменение имени файла без расширения
        $result = Helper::fileChangeName('/var/www/html/test.php', 'document.txt');
        $this->assertSame('/var/www/html/document.txt', $result);

        // Только имя файла
        $result = Helper::fileChangeName('test.php', 'renamed.txt');
        $this->assertSame('renamed.txt', $result);

        // Пустое новое имя - возвращает исходный файл
        $result = Helper::fileChangeName('/var/www/html/test.php', '');
        $this->assertSame('/var/www/html/test.php', $result);

        // Null новое имя - возвращает исходный файл
        $result = Helper::fileChangeName('/var/www/html/test.php', null);
        $this->assertSame('/var/www/html/test.php', $result);

        // Пустая строка файла
        $result = Helper::fileChangeName('', 'newfile.txt');
        $this->assertSame('', $result);

        // Null файла
        $result = Helper::fileChangeName(null, 'newfile.txt');
        $this->assertSame('', $result);

        // Относительный путь
        $result = Helper::fileChangeName('folder/subfolder/old.txt', 'new.txt');
        $this->assertSame('folder/subfolder/new.txt', $result);

        // Имя без расширения
        $result = Helper::fileChangeName('/home/user/README', 'LICENSE');
        $this->assertSame('/home/user/LICENSE', $result);
    }
}

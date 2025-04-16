<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperTranslitTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperTranslitTrait
 */
final class HelperTranslitStringTest extends TestCase
{
    #[Test]
    public function translitString(): void
    {
        $string = Helper::translitString('абвгдеёжзийклмнопрстуфхцчшщъыьэюя');
        $this->assertEquals('abvgdejojziyklmnoprstufhcchshsch"yi\'yeyuya', $string);
        $string = Helper::translitString($string);
        $this->assertEquals('абвгдеёжзийклмнопрстуфхцчшщъыьэюя', $string);

        $string = Helper::translitString('АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ');
        $this->assertEquals('ABVGDEJoJZIYKLMNOPRSTUFHCChShSch"Yi\'YeYuYa', $string);
        $string = Helper::translitString($string);
        $this->assertEquals('АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩъЫьЭЮЯ', $string);

        $string = Helper::translitString('abcdefghijklmnopqrstuvwxyz');
        $this->assertEquals('абцдефгхижклмнопqрстувwxйз', $string);
        $string = Helper::translitString($string);
        $this->assertEquals('abcdefghijklmnopqrstuvwxyz', $string);

        $string = Helper::translitString('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $this->assertEquals('АБЦДЕФГХИЖКЛМНОПQРСТУВWXЙЗ', $string);
        $string = Helper::translitString($string);
        $this->assertEquals('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $string);

        $string = Helper::translitString('Эй, жюри! Съешь хрящ ещё — бьёт ценой флегматика вюзжик');
        $this->assertEquals('Yey, jyuri! S"esh\' hryasch eschjo — b\'jot cenoy flegmatika vyuzjik', $string);
        $string = Helper::translitString($string);
        $this->assertEquals('Эй, жюри! Съешь хрящ ещё — бьёт ценой флегматика вюзжик', $string);

        $string = Helper::translitString('Съешь ещё этих мягких французских булок, да выпей чаю с йогуртом');
        $this->assertEquals('S"esh\' eschjo yetih myagkih francuzskih bulok, da vyipey chayu s yogurtom', $string);
        $string = Helper::translitString($string);
        $this->assertEquals('Съешь ещё этих мягких французских булок, да выпей чаю с йогуртом', $string);

        $string = Helper::translitString(null);
        $this->assertEquals('', $string);
    }
}

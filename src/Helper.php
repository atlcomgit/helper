<?php

declare(strict_types=1);

namespace Atlcom;

use Atlcom\Traits\HelperArrayTrait;
use Atlcom\Traits\HelperCacheTrait;
use Atlcom\Traits\HelperCaseTrait;
use Atlcom\Traits\HelperColorTrait;
use Atlcom\Traits\HelperCryptTrait;
use Atlcom\Traits\HelperExceptionTrait;
use Atlcom\Traits\HelperHashTrait;
use Atlcom\Traits\HelperIntervalTrait;
use Atlcom\Traits\HelperIpTrait;
use Atlcom\Traits\HelperJwtTrait;
use Atlcom\Traits\HelperPathTrait;
use Atlcom\Traits\HelperRegexpTrait;
use Atlcom\Traits\HelperSizeTrait;
use Atlcom\Traits\HelperStringTrait;
use Atlcom\Traits\HelperTelegramTrait;
use Atlcom\Traits\HelperTimeTrait;
use Atlcom\Traits\HelperTransformTrait;

/**
 * Абстрактный класс Helper
 * @abstract
 * @version 1.03
 * 
 * @see ../../README.md
 * @link https://github.com/atlcomgit/helper
 * 
 * @group Работа с массивами
 * @method @see static::arrayExcludeTraceVendor()
 * @method @see static::arrayFirst()
 * @method @see static::arrayGet()
 * @method @see static::arrayLast()
 * @method @see static::arrayMappingKeys()
 * @method @see static::arraySearchKeys()
 * @method @see static::arraySearchKeysAndValues()
 * @method @see static::arraySearchValues()
 * 
 * @group Работа с кешем
 * @method @see static::cacheRuntimeGet()
 * @method @see static::cacheRuntimeSet()
 * 
 * @group Работа с цветами
 * @method @see static::colorHexToRgb()
 * @method @see static::colorRgbToHex()
 * 
 * @group Работа с шифрацией
 * @method @see static::cryptDecode()
 * @method @see static::cryptEncode()
 * 
 * @group Работа с исключениями
 * @method @see static::exceptionToString()
 * 
 * @group Работа с хешами
 * @method @see static::hashXxh128()
 * 
 * @group Работа с интервалами
 * @method @see static::intervalAround()
 * @method @see static::intervalBetween()
 * @method @see static::intervalCollection()
 * @method @see static::intervalOverlap()
 * 
 * @group Работа с ip адресами
 * @method @see static::ipInRange()
 * 
 * @group Работа с jwt токенами
 * @method @see static::jwtDecode()
 * @method @see static::jwtEncode()
 * 
 * @group Работа с путями
 * @method @see static::pathClassName()
 * @method @see static::pathRoot()
 * 
 * @group Работа с валидацией
 * @method @see static::regexpValidateAscii()
 * @method @see static::regexpValidateEmail()
 * @method @see static::regexpValidateJson()
 * @method @see static::regexpValidatePattern()
 * @method @see static::regexpValidatePhone()
 * @method @see static::regexpValidateUnicode()
 * @method @see static::regexpValidateUuid()
 * 
 * @group Работа с размерами
 * @method @see static::sizeBytesToString()
 * @method @see static::sizeStringToBytes()
 * 
 * @group Работа со строками
 * @method @see static::stringPadPrefix()
 * @method @see static::stringPadSuffix()
 * @method @see static::stringBreakByLength()
 * @method @see static::stringChange()
 * @method @see static::stringConcat()
 * @method @see static::stringCopy()
 * @method @see static::stringCount()
 * @method @see static::stringCut()
 * @method @see static::stringDelete()
 * @method @see static::stringEnds()
 * @method @see static::stringLength()
 * @method @see static::stringLower()
 * @method @see static::stringMerge()
 * @method @see static::stringPaste()
 * @method @see static::stringPlural()
 * @method @see static::stringRepeat()
 * @method @see static::stringReplace()
 * @method @see static::stringReverse()
 * @method @see static::stringSearchAll()
 * @method @see static::stringSearchAny()
 * @method @see static::stringStarts()
 * @method @see static::stringUpper()
 * 
 * @group Работа с сообщениями телеграм
 * @method @see static::telegramBreakMessage()
 * 
 * @group Работа со временем
 * @method @see static::timePeriodBetweenDatesToArray()
 * @method @see static::timePeriodBetweenDatesToString()
 * @method @see static::timeSecondsToArray()
 * @method @see static::timeSecondsToString()
 * 
 * @group Работа с трансформацией
 * @method @see static::transformToArray()
 */
abstract class Helper
{
    use HelperArrayTrait;
    use HelperCacheTrait;
    use HelperCaseTrait;
    use HelperColorTrait;
    use HelperCryptTrait;
    use HelperExceptionTrait;
    use HelperHashTrait;
    use HelperIntervalTrait;
    use HelperIpTrait;
    use HelperJwtTrait;
    use HelperPathTrait;
    use HelperRegexpTrait;
    use HelperSizeTrait;
    use HelperStringTrait;
    use HelperTelegramTrait;
    use HelperTimeTrait;
    use HelperTransformTrait;
}

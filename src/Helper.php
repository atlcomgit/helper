<?php

declare(strict_types=1);

namespace Atlcom;

use Atlcom\Traits\HelperArrayTrait;
use Atlcom\Traits\HelperBracketTrait;
use Atlcom\Traits\HelperCacheTrait;
use Atlcom\Traits\HelperCaseTrait;
use Atlcom\Traits\HelperColorTrait;
use Atlcom\Traits\HelperCryptTrait;
use Atlcom\Traits\HelperDateTrait;
use Atlcom\Traits\HelperEnumTrait;
use Atlcom\Traits\HelperEnvTrait;
use Atlcom\Traits\HelperExceptionTrait;
use Atlcom\Traits\HelperHashTrait;
use Atlcom\Traits\HelperIntervalTrait;
use Atlcom\Traits\HelperIpTrait;
use Atlcom\Traits\HelperJsonTrait;
use Atlcom\Traits\HelperJwtTrait;
use Atlcom\Traits\HelperNumberTrait;
use Atlcom\Traits\HelperPathTrait;
use Atlcom\Traits\HelperPhoneTrait;
use Atlcom\Traits\HelperRegexpTrait;
use Atlcom\Traits\HelperSizeTrait;
use Atlcom\Traits\HelperStringTrait;
use Atlcom\Traits\HelperTelegramTrait;
use Atlcom\Traits\HelperTimeTrait;
use Atlcom\Traits\HelperTransformTrait;
use Atlcom\Traits\HelperTranslitTrait;
use Atlcom\Traits\HelperUrlTrait;

/**
 * Абстрактный класс Helper
 * @abstract
 * @version 1.13
 * 
 * @see ../README.md
 * @link https://github.com/atlcomgit/helper
 * 
 * @group array - Работа с массивами
 * @method @see static::arrayDeleteKeys()
 * @method @see static::arrayDot()
 * @method @see static::arrayExcludeTraceVendor()
 * @method @see static::arrayFirst()
 * @method @see static::arrayGet()
 * @method @see static::arrayLast()
 * @method @see static::arrayMappingKeys()
 * @method @see static::arraySearchKeys()
 * @method @see static::arraySearchKeysAndValues()
 * @method @see static::arraySearchValues()
 * @method @see static::arrayUnDot()
 * 
 * @group bracket - Работа со скобками
 * @method @see static::bracketChange()
 * @method @see static::bracketCopy()
 * @method @see static::bracketDelete()
 * @method @see static::bracketReplace()
 * 
 * @group cache - Работа с кешем
 * @method @see static::cacheRuntime()
 * @method @see static::cacheRuntimeClear()
 * @method @see static::cacheRuntimeDelete()
 * @method @see static::cacheRuntimeExists()
 * @method @see static::cacheRuntimeGet()
 * @method @see static::cacheRuntimeSet()
 * 
 * @group case - Работа со стилем
 * @method @see static::caseCamel()
 * @method @see static::caseKebab()
 * @method @see static::casePascal()
 * @method @see static::caseSnake()
 * 
 * @group color - Работа с цветами
 * @method @see static::colorHexToRgb()
 * @method @see static::colorRgbToHex()
 * 
 * @group crypt - Работа с шифрацией
 * @method @see static::cryptArrayDecode()
 * @method @see static::cryptArrayEncode()
 * @method @see static::cryptDecode()
 * @method @see static::cryptEncode()
 * 
 * @group date - Работа с датами
 * @method @see static::dateDayName()
 * @method @see static::dateFromString()
 * @method @see static::dateToString()
 * 
 * @group enum - Работа с перечислениями
 * @method @see static::enumExists()
 * @method @see static::enumFrom()
 * @method @see static::enumLabel()
 * @method @see static::enumLabels()
 * @method @see static::enumName()
 * @method @see static::enumNames()
 * @method @see static::enumRandom()
 * @method @see static::enumToArray()
 * @method @see static::enumValue()
 * @method @see static::enumValues()
 * 
 * @group env - Работа с окружением
 * @method @see static::envDev()
 * @method @see static::envLocal()
 * @method @see static::envProd()
 * @method @see static::envStage()
 * @method @see static::envTest()
 * @method @see static::envTesting()
 * 
 * @group exception - Работа с исключениями
 * @method @see static::exceptionToString()
 * 
 * @group hash - Работа с хешами
 * @method @see static::hashXxh128()
 * 
 * @group interval - Работа с интервалами
 * @method @see static::intervalAround()
 * @method @see static::intervalBetween()
 * @method @see static::intervalCollection()
 * @method @see static::intervalOverlap()
 * 
 * @group ip - Работа с ip адресами
 * @method @see static::ipInRange()
 * 
 * @group json - Работа с json строками
 * @method @see static::jsonFlags()
 * 
 * @group jwt - Работа с jwt токенами
 * @method @see static::jwtDecode()
 * @method @see static::jwtEncode()
 * 
 * @group number - Работа с числами
 * @method @see static::numberFromString()
 * @method @see static::numberToString()
 * 
 * @group path - Работа с путями
 * @method @see static::pathClassName()
 * @method @see static::pathRoot()
 * 
 * @group phone - Работа с телефонами
 * @method @see static::phoneFormat()
 * @method @see static::phoneNumber()
 * 
 * @group regexp - Работа с валидацией
 * @method @see static::regexpValidateAscii()
 * @method @see static::regexpValidateDate()
 * @method @see static::regexpValidateEmail()
 * @method @see static::regexpValidateJson()
 * @method @see static::regexpValidatePattern()
 * @method @see static::regexpValidatePhone()
 * @method @see static::regexpValidateUnicode()
 * @method @see static::regexpValidateUuid()
 * 
 * @group size - Работа с размерами
 * @method @see static::sizeBytesToString()
 * @method @see static::sizeStringToBytes()
 * 
 * @group string - Работа со строками
 * @method @see static::stringPadPrefix()
 * @method @see static::stringPadSuffix()
 * @method @see static::stringBreakByLength()
 * @method @see static::stringChange()
 * @method @see static::stringConcat()
 * @method @see static::stringCopy()
 * @method @see static::stringCount()
 * @method @see static::stringCut()
 * @method @see static::stringDelete()
 * @method @see static::stringDeleteMultiples()
 * @method @see static::stringEnds()
 * @method @see static::stringLength()
 * @method @see static::stringLower()
 * @method @see static::stringLowerFirst()
 * @method @see static::stringMerge()
 * @method @see static::stringPaste()
 * @method @see static::stringPlural()
 * @method @see static::stringPosAll()
 * @method @see static::stringPosAny()
 * @method @see static::stringRandom()
 * @method @see static::stringRepeat()
 * @method @see static::stringReplace()
 * @method @see static::stringReverse()
 * @method @see static::stringSearchAll()
 * @method @see static::stringSearchAny()
 * @method @see static::stringSegment()
 * @method @see static::stringSplit()
 * @method @see static::stringStarts()
 * @method @see static::stringUpper()
 * @method @see static::stringUpperFirst()
 * @method @see static::stringUpperFirstAll()
 * 
 * @group telegram - Работа с сообщениями телеграм
 * @method @see static::telegramBreakMessage()
 * 
 * @group time - Работа со временем
 * @method @see static::timeBetweenDatesToArray()
 * @method @see static::timeBetweenDatesToString()
 * @method @see static::timeSecondsToArray()
 * @method @see static::timeSecondsToString()
 * 
 * @group transform - Работа с трансформацией
 * @method @see static::transformToArray()
 * 
 * @group translit - Работа с транслитерацией
 * @method @see static::translitFromSlug()
 * @method @see static::translitString()
 * @method @see static::translitToSlug()
 * 
 * @group url - Работа с url адресами
 * @method @see static::urlCreate()
 * @method @see static::urlDomainDecode()
 * @method @see static::urlDomainEncode()
 * @method @see static::urlParse()
 */
abstract class Helper
{
    use HelperArrayTrait;
    use HelperBracketTrait;
    use HelperCacheTrait;
    use HelperCaseTrait;
    use HelperColorTrait;
    use HelperCryptTrait;
    use HelperDateTrait;
    use HelperEnumTrait;
    use HelperEnvTrait;
    use HelperExceptionTrait;
    use HelperHashTrait;
    use HelperIntervalTrait;
    use HelperIpTrait;
    use HelperJsonTrait;
    use HelperJwtTrait;
    use HelperNumberTrait;
    use HelperPathTrait;
    use HelperPhoneTrait;
    use HelperRegexpTrait;
    use HelperSizeTrait;
    use HelperStringTrait;
    use HelperTelegramTrait;
    use HelperTimeTrait;
    use HelperTransformTrait;
    use HelperTranslitTrait;
    use HelperUrlTrait;
}

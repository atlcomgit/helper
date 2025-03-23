<?php

declare(strict_types=1);

namespace Atlcom;

use Atlcom\Traits\HelperArrayTrait;
use Atlcom\Traits\HelperCacheTrait;
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

/**
 * Абстрактный класс Helper
 * @abstract
 * @version 1.02
 * 
 * @see ../../README.md
 * @link https://github.com/atlcomgit/helper
 * 
 * 
 * @method @see static::arrayExcludeTraceVendor()
 * @method @see static::arrayMappingKeys()
 * 
 * @method @see static::cacheRuntimeGet()
 * @method @see static::cacheRuntimeSet()
 * 
 * @method @see static::cryptDecode()
 * @method @see static::cryptEncode()
 * 
 * @method @see static::exceptionToString()
 * 
 * @method @see static::hashXxh128()
 * 
 * @method @see static::intervalAround()
 * @method @see static::intervalBetween()
 * @method @see static::intervalCollection()
 * @method @see static::intervalOverlap()
 * 
 * @method @see static::ipInRange()
 * 
 * @method @see static::pathClassName()
 * @method @see static::pathRoot()
 * 
 * @method @see static::sizeBytesToString()
 * @method @see static::sizeStringToBytes()
 * 
 * @method @see static::stringAddPrefix()
 * @method @see static::stringAddSuffix()
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
 * @method @see static::stringReplace()
 * @method @see static::stringSearchAll()
 * @method @see static::stringSearchAny()
 * @method @see static::stringStarts()
 * @method @see static::stringUpper()
 * 
 * @method @see static::telegramBreakMessage()
 * 
 * @method @see static::timePeriodBetweenDatesToArray()
 * @method @see static::timePeriodBetweenDatesToString()
 * @method @see static::timeSecondsToArray()
 * @method @see static::timeSecondsToString()
 */
abstract class Helper
{
    use HelperArrayTrait;
    use HelperCacheTrait;
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
}

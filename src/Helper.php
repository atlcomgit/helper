<?php

declare(strict_types=1);

namespace Atlcom;

use Atlcom\Traits\HelperArrayTrait;
use Atlcom\Traits\HelperCryptTrait;
use Atlcom\Traits\HelperExceptionTrait;
use Atlcom\Traits\HelperIntervalTrait;
use Atlcom\Traits\HelperIpTrait;
use Atlcom\Traits\HelperSizeTrait;
use Atlcom\Traits\HelperStringTrait;
use Atlcom\Traits\HelperTimeTrait;

/**
 * Абстрактный класс Helper
 * @abstract
 * @version 1.01
 * 
 * @see \Atlcom\Tests\HelperTest
 * @see ../../README.md
 * @link https://github.com/atlcomgit/helper
 * 
 * @method @see static::intervalBetween()
 * @method @see static::intervalOverlap()
 */
abstract class Helper
{
    use HelperArrayTrait;
    use HelperCryptTrait;
    use HelperExceptionTrait;
    use HelperIntervalTrait;
    use HelperIpTrait;
    use HelperSizeTrait;
    use HelperStringTrait;
    use HelperTimeTrait;
}

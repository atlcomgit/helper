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
final class HelperTranslitFromSlugTest extends TestCase
{
    #[Test]
    public function translitFromSlug(): void
    {
        $string = Helper::translitFromSlug('abv_gde_123');
        $this->assertEquals('абв где 123', $string);

        $string = Helper::translitFromSlug('yey_jyuri_sesh_hryasch_eschjo_bjot_cenoy_flegmatika_vyuzjik');
        $this->assertEquals('эй жюри сеш хрящ ещё бёт ценой флегматика вюзжик', $string);

        $string = Helper::translitFromSlug('sesh_eschjo_yetih_myagkih_francuzskih_bulok_da_vyipey_chayu_s_yogurtom');
        $this->assertEquals('сеш ещё этих мягких французских булок да выпей чаю с йогуртом', $string);

        $string = Helper::translitFromSlug('abv-gde-123', '-');
        $this->assertEquals('абв где 123', $string);
    }
}
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
final class HelperTranslitToSlugTest extends TestCase
{
    #[Test]
    public function translitToSlug(): void
    {
        $string = Helper::translitToSlug('абв Где!.123');
        $this->assertEquals('abv_gde_123', $string);

        $string = Helper::translitToSlug('Эй, жюри! Съешь хрящ ещё — бьёт ценой флегматика вюзжик');
        $this->assertEquals('yey_jyuri_sesh_hryasch_eschjo_bjot_cenoy_flegmatika_vyuzjik', $string);

        $string = Helper::translitToSlug('Съешь ещё этих мягких французских булок, да выпей чаю с йогуртом');
        $this->assertEquals('sesh_eschjo_yetih_myagkih_francuzskih_bulok_da_vyipey_chayu_s_yogurtom', $string);

        $string = Helper::translitToSlug('абв Где 123', '-');
        $this->assertEquals('abv-gde-123', $string);
    }
}
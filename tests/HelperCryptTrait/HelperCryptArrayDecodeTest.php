<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCryptTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperCryptTrait
 */
final class HelperCryptArrayDecodeTest extends TestCase
{
    #[Test]
    public function cryptArrayDecode(): void
    {
        $array = Helper::cryptArrayDecode([
            'name' => '4eb57b6012812660046a2239d877eaa7:63tb9aH6j161mc5dPfHf5eSff05795ReWdb4463bj65fX0L229HfDc3948qa66x75pGC24939u6T3Rz6uo6Jp5in',
            'phone' => '2ea0a0a0697a43b6550a48135ed1df58:64ta92Heja6e9bZ9T3F5udFf4fm93b4f3aS74e4b92n1Wfv6R25f3fH0X24860M9Nq453659MCET3Rz6uo6Jp5in',
        ]);
        $this->assertTrue($array === ['name' => 'Иван', 'phone' => '79001112233']);

        $array = Helper::cryptArrayDecode([
            'a' => 'df3ce784d856334d65cd25028f98f158:44l36eibecra962cq1o93452aa4f9842p4Ue5b2b48c6H53b6fA1Hd534dm2ed62en',
            'b' => [
                '12d8bdd17f74de858c40219a46b9f81b:4dl26bi2e9r8972cq9o6343e9e42549347j7w33a513b9fE8L95cA9H4544dm0ed6en',
                '56a841f9102d5ff745f80274c9c7a7ca:4bl969ibedra9a2eqfocq8s2Sd4fj3wc34563f94E0Le5dA9H0564cmde962ednf',
            ],
            'e' => [
                'f' => 'df3ce784d856334d65cd25028f98f158:48l564i8ecrd9126q0o43d58ac4c984fp754F82343c749Uc52A8Hd594dm7ea6aen',
                'g' => 'dd8307607436e2c77045c205f5ef6a66:41ld6ai8efr99a20qco93c5fa3S14763Z53a54533a9aEbLd53A2Hd5541m0eb62en',
                'i' => '99aa06d3014798d86001c324468d497f:4flf61iae9r3902aq4M63858a1S5TfQ436y64cccHb3e66AaH45d43m8e961e1n6',
            ],
        ], 'password');
        $this->assertTrue($array === [
            'a' => 1,
            'b' => ['c', 'd'],
            'e' => [
                'f' => true,
                'g' => 'h',
                'i' => null,
            ],
        ]);

        $array = Helper::cryptArrayDecode([
            'a' => [
                'name' => '06b05ab6733a618578af5f94892f3950:3eg3374c37wb3f6dcb244aUff2Cf2eya466047934cybq0rfX0pd31K85do3XcO3W49Cy3Rgn',
            ],
        ], 'password');
        $this->assertEquals($array, [
            'a' => [
                'name' => 'abc',
            ],
        ]);

        $array = Helper::cryptArrayDecode(null, null);
        $this->assertEquals($array, []);
    }
}

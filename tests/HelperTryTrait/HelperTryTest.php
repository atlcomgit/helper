<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperVarTrait;

use Atlcom\Enums\HelperTryStatusEnum;
use Atlcom\Helper;
use Atlcom\Internal\HelperTry;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperTryTrait
 */
final class HelperTryTest extends TestCase
{
    #[Test]
    public function try(): void
    {
        $result = Helper::try(static fn () => 1)
            ->catch(static fn ($e) => 2)
            ->success(static fn () => 3)
            ->getResult()
        ;
        $this->assertSame(3, $result);

        $result = Helper::try(static fn () => throw new Exception())
            ->catch(static fn ($e) => 2)
            ->success(static fn () => 3)
            ->getResult()
        ;
        $this->assertSame(2, $result);

        $result = Helper::try(static fn () => throw new Exception())
            ->catch(static fn ($e) => 2)
            ->success(static fn () => 3)
            ->finally(static fn () => 4)
        ;
        $this->assertSame(4, $result);

        $result = Helper::try(static fn () => 1)
            ->catch(static fn ($e) => 2)
            ->success(static fn () => 3)
            ->finally(static fn () => 4)
        ;
        $this->assertSame(4, $result);

        $a = 0;
        $result = Helper::try(static function () use (&$a) {
            $a++;
        })
            ->repeat(true, 1)
        ;
        $this->assertSame(2, $a);

        $a = 0;
        $result = Helper::try(static fn () => throw new Exception())
            ->catch(static function () use (&$a) {
                $a++;
            })
            ->success(static fn () => 3)
            ->repeatIfException(1)
        ;
        $this->assertSame(2, $a);

        $result = Helper::try(static fn () => 1)
            ->catch(static fn () => 3)
            ->success(static fn () => 3)
        ;
        $this->assertSame(HelperTryStatusEnum::Success, $result->getStatus());

        $result = Helper::try(static fn () => throw new Exception())
            ->catch(static fn () => 3)
            ->success(static fn () => 3)
        ;
        $this->assertSame(HelperTryStatusEnum::Exception, $result->getStatus());

        $result = Helper::try(fn (HelperTry $try) => $try->getResult() ?? 1)
            ->catch(fn (HelperTry $try) => $try->getResult() + 1)
            ->success(fn (HelperTry $try) => $try->getResult() + 1)
            ->finally(fn (HelperTry $try) => $try->getResult() + 1)
        ;
        $this->assertSame(3, $result);

        $result = Helper::try(static fn () => throw new Exception())
            ->catch(static fn () => 1, Exception::class)
            ->catch(static fn () => 2, null)
            ->catch(static fn () => 3, 'UnknownClass')
            ->success(static fn () => 4)
        ;
        $this->assertSame(2, $result->getResult());

        $result = Helper::try(static fn () => 1)
            ->catch()
            ->success()
            ->finally()
        ;
        $this->assertSame(1, $result);
    }
}

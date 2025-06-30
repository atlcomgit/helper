<?php

declare(strict_types=1);

namespace Atlcom\Internal;

use Atlcom\Enums\HelperTryStatusEnum;
use Atlcom\Helper;
use Closure;
use Throwable;

/**
 * // @internal
 * Класс для вызова блока try через цепочку
 */
class HelperTry
{
    protected ?Closure $try = null;
    protected array $catches = [];
    protected ?Closure $success = null;
    protected ?Throwable $exception = null;
    protected ?HelperTryStatusEnum $status = null;
    protected mixed $result = null;
    protected mixed $resultTry = null;
    protected mixed $resultCatch = null;
    protected mixed $resultSuccess = null;
    protected mixed $resultFinally = null;


    /**
     * Выполняет callable в блоке try
     *
     * @param callable $callable
     * @return static
     */
    public function try(callable $callable): static
    {
        try {
            $this->result = $this->resultTry = call_user_func(
                $this->try = Closure::fromCallable($callable),
                $this,
            );
            $this->status = HelperTryStatusEnum::Success;

        } catch (Throwable $exception) {
            $this->status = HelperTryStatusEnum::Exception;
            $this->exception = $exception;
        }

        return $this;
    }


    /**
     * Выполняет callable при ошибке в блоке try
     *
     * @param callable|null $callable
     * @param string|null $exceptionClass
     * @return static
     */
    public function catch(?callable $callable = null, ?string $exceptionClass = null): static
    {
        if (!is_null($callable) && $this->status === HelperTryStatusEnum::Exception) {
            $this->catches[] = ['callable' => $callable, 'exceptionClass' => $exceptionClass];

            if (
                empty($exceptionClass)
                || $exceptionClass === $this->exception::class
                || $this->exception instanceof $exceptionClass
            ) {
                try {
                    $this->result = $this->resultCatch = call_user_func(
                        $this->catch = Closure::fromCallable($callable),
                        $this->exception,
                        $this,
                    );

                } catch (Throwable $exception) {
                    $this->status = HelperTryStatusEnum::Exception;
                    $this->exception = $exception;

                    throw $exception;
                }
            }
        }

        return $this;
    }


    /**
     * Выполняет список callable при ошибке в блоке try
     *
     * @param array $catches
     * @return static
     */
    protected function catches(array $catches): static
    {
        foreach ($catches as $catch) {
            $this->catch($catch['callable'], $catch['exceptionClass']);
        }

        return $this;
    }


    /**
     * Выполняет callable при успешном выполнении блока try
     *
     * @param callable|null $callable
     * @return static
     */
    public function success(?callable $callable = null): static
    {
        if (!is_null($callable) && $this->status === HelperTryStatusEnum::Success) {
            try {
                $this->result = $this->resultSuccess = call_user_func(
                    $this->success = Closure::fromCallable($callable),
                    $this,
                );

            } catch (Throwable $exception) {
                $this->status = HelperTryStatusEnum::Exception;
                $this->exception = $exception;

                throw $exception;
            }
        }

        return $this;
    }


    /**
     * Повторяет выполнение блока try указанное количество раз при выполнении условия
     *
     * @param mixed $condition
     * @param int $maxCount
     * @return static
     */
    public function repeat(mixed $condition, int $maxCount = 1): static
    {
        while (
            $maxCount > 0
            && match (true) {
                is_callable($condition) => call_user_func(Closure::fromCallable($condition), $this),

                default => Helper::castToBool($condition),
            }
        ) {
            $maxCount--;

            $try = static::try($this->try)->catches($this->catches)->success($this->success);
            $this->result = $try->getResult();
            $this->status = $try->getStatus();
        }

        return $this;
    }


    /**
     * Повторяет выполнение блока try указанное количество раз при ошибке в блоке try
     *
     * @param int $maxCount
     * @return static
     */
    public function repeatIfException(int $maxCount = 1): static
    {
        while ($maxCount > 0 && $this->status === HelperTryStatusEnum::Exception) {
            $maxCount--;

            $this->repeat(true, 1);
        }

        return $this;
    }


    /**
     * Возвращает последнее исключение блока try
     *
     * @return Throwable|null
     */
    public function getException(): ?Throwable
    {
        return $this->exception;
    }


    /**
     * Возвращает последний статус выполнения цепочки try
     *
     * @return HelperTryStatusEnum
     */
    public function getStatus(): HelperTryStatusEnum
    {
        return $this->status;
    }


    /**
     * Возвращает последний результат выполнения цепочки try
     *
     * @return mixed
     */
    public function getResult(): mixed
    {
        return $this->result;
    }


    /**
     * Выполняет callable при любов статусе выполнения блока try и возвращает последний результат
     *
     * @param callable|null $callable
     * @return mixed
     */
    public function finally(?callable $callable = null): mixed
    {
        if (!is_null($callable)) {
            try {
                $this->result = $this->resultFinally = call_user_func(
                    Closure::fromCallable($callable),
                    $this,
                );

            } catch (Throwable $exception) {
                $this->status = HelperTryStatusEnum::Exception;
                $this->exception = $exception;

                throw $exception;
            }
        }

        return $this->result;
    }
}

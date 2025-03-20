<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с массивами
 */
trait HelperArrayTrait
{
    /**
     * Исключает из массива трассировки пакетные ошибки
     *
     * @param array $trace
     * @return array
     */
    public static function arrayExcludeVendorTrace(array $value, ?string $basePath = null): array
    {
        $basePath ??= static::basePath();
        $ignoreFiles = [
            '/vendor/',
        ];
        $ignoreClasses = [
            'Illuminate',
        ];

        $resultTrace = [];
        foreach ($value as $item) {
            if (
                !static::stringStarts($item['file'] ?? '', $ignoreFiles)
                &&
                !static::stringStarts($item['class'] ?? '', $ignoreClasses)
            ) {
                $file = trim(str_replace($basePath, '', $item['file'] ?? ''), '/');
                $resultTrace[] = [
                    'file' => $file . ':' . ($item['line'] ?? ''),
                    'func' => static::baseNameClass($item['class'] ?? '')
                        . ($item['type'] ?? '')
                        . $item['function'] . '()',
                ];
            }
        }

        return $resultTrace;
    }
}

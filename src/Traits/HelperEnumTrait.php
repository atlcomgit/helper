<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Helper;
use BackedEnum;

/**
 * Трейт для работы с перечислениями
 * @mixin \Atlcom\Helper
 */
trait HelperEnumTrait
{
    /**
     * Возвращает имена ключей перечисления enum
     * @see ../../tests/HelperEnumTrait/HelperEnumNamesTest.php
     *
     * @param class-string|null $value
     * @return array<string>
     */
    public static function enumNames(?string $value = null): array
    {
        return ($enumClass = $value ?? (is_subclass_of(self::class, BackedEnum::class) ? self::class : null))
            ? array_column($enumClass::cases(), 'name')
            : [];
    }


    /**
     * Возвращает значения ключей перечисления enum
     * @see ../../tests/HelperEnumTrait/HelperEnumValuesTest.php
     *
     * @param class-string|null $value
     * @return array<mixed>
     */
    public static function enumValues(?string $value = null): array
    {
        return ($enumClass = $value ?? (is_subclass_of(self::class, BackedEnum::class) ? self::class : null))
            ? array_column($enumClass::cases(), 'value')
            : [];
    }


    /**
     * Возвращает массив ключей и значений enum
     * @see ../../tests/HelperEnumTrait/HelperEnumToArrayTest.php
     *
     * @param BackedEnum|class-string|null $value
     * @return array<string, mixed>
     */
    public static function enumToArray(BackedEnum|string|null $value = null): array
    {
        return match (true) {
            $value instanceof BackedEnum => [
                'name' => $value->name,
                'value' => $value->value,
            ],
            is_string($value) => array_combine($value::enumNames(), $value::enumValues()),

            default => ($enumClass = is_subclass_of(self::class, BackedEnum::class) ? self::class : null)
            ? array_combine($enumClass::enumNames(), $enumClass::enumValues())
            : [],
        };
    }


    /**
     * Возвращает ключ перечисления
     * @see ../../tests/HelperEnumTrait/HelperEnumNameTest.php
     *
     * @param BackedEnum|string|null $value
     * @return string|null
     */
    public static function enumName(BackedEnum|string|null $value): ?string
    {
        return static::enumFrom($value)?->name;
    }


    /**
     * Возвращает значение перечисления
     * @see ../../tests/HelperEnumTrait/HelperEnumValueTest.php
     *
     * @param BackedEnum|string|null $value
     * @return mixed
     */
    public static function enumValue(BackedEnum|string|null $value): mixed
    {
        return static::enumFrom($value)?->value;
    }


    /**
     * Возвращает случайное перечисление
     * @see ../../tests/HelperEnumTrait/HelperEnumRandomTest.php
     *
     * @param class-string|null $value
     * @return static|BackedEnum|null
     * @psalm-return static|null
     * @phpstan-return static|null
     */
    public static function enumRandom(?string $value = null): static|BackedEnum|null
    {
        $enumClass = $value ?: (is_subclass_of(static::class, BackedEnum::class) ? static::class : null);
        $names = is_subclass_of($enumClass, BackedEnum::class) ? $enumClass::cases() : [];

        return $names ? $names[array_rand($names, 1)] : null;
    }


    /**
     * Возвращает первое перечисление
     * @see ../../tests/HelperEnumTrait/HelperEnumFirstTest.php
     *
     * @return static|BackedEnum|null
     */
    public static function enumFirst(): static|BackedEnum|null
    {
        // Получаем список вариантов перечисления, если текущий класс поддерживает BackedEnum
        $enums = is_subclass_of(static::class, BackedEnum::class) ? static::cases() : [];

        // Возвращаем первый элемент или null
        return $enums[0] ?? null;
    }


    /**
     * Возвращает последнее перечисление
     * @see ../../tests/HelperEnumTrait/HelperEnumLastTest.php
     *
     * @return static|BackedEnum|null
     */
    public static function enumLast(): static|BackedEnum|null
    {
        // Получаем список вариантов перечисления, если текущий класс поддерживает BackedEnum
        $enums = is_subclass_of(static::class, BackedEnum::class) ? static::cases() : [];

        // Возвращаем последний элемент или null
        return $enums ? end($enums) : null;
    }


    /**
     * Возвращает найденное перечисление по имени, по значению или по перечислению
     * @see ../../tests/HelperEnumTrait/HelperEnumFromTest.php
     *
     * @param static|BackedEnum|string|null $value
     * @param bool $caseInsensitive
     * @return static|BackedEnum|null
     * @psalm-return static|null
     * @phpstan-return static|null
     */
    public static function enumFrom(mixed $value, bool $caseInsensitive = false): static|BackedEnum|null
    {
        return match (true) {
            $value instanceof BackedEnum => $value,
            is_string($value) && defined($enum = static::class . "::{$value}") => constant($enum),
            is_subclass_of(static::class, BackedEnum::class) => (
                static function ($value) use ($caseInsensitive) {
                        $valueInsensitive = $caseInsensitive ? Helper::stringLower($value) : $value;

                        foreach (static::cases() as $enum) {
                            if ($enum->name === $value || $enum->value === $value) {
                                return $enum;
                            } else if ($caseInsensitive) {
                                if (
                                Helper::stringLower($enum->name) === $valueInsensitive
                                || Helper::stringLower($enum->value) === $valueInsensitive
                                || Helper::stringLower($enum->label()) === $valueInsensitive
                                ) {
                                    return $enum;
                                }
                            }
                        }

                        return null;
                    })($value),

            default => null,
        };
    }


    /**
     * Проверяет на существование перечисления по значению и возвращает true/false
     * @see ../../tests/HelperEnumTrait/HelperEnumExistsTest.php
     *
     * @param mixed $value
     * @return bool
     */
    public static function enumExists(mixed $value): bool
    {
        return (bool)(static::enumFrom($value) ?? false);
    }


    /**
     * Возвращает описание перечисления
     * @see ../../tests/HelperEnumTrait/HelperEnumLabelTest.php
     *
     * @param BackedEnum|null $value
     * @return string|null
     */
    public static function enumLabel(?BackedEnum $enum): ?string
    {
        return $enum ? "{$enum->name} ({$enum->value})" : null;
    }


    /**
     * Возвращает описание перечисления
     * @see ../../tests/HelperEnumTrait/HelperEnumLabelTest.php
     *
     * @return string|null
     */
    public function label(): ?string
    {
        return static::enumLabel($this);
    }


    /**
     * Возвращает список перечислений для ресурса
     * @see ../../tests/HelperEnumTrait/HelperEnumLabelsTest.php
     *
     * @param string|null $value
     * @param string|null $label
     * @return array
     */
    public static function enumLabels(?string $value = 'value', ?string $label = 'label'): array
    {
        $result = [];
        $enums = is_subclass_of(static::class, BackedEnum::class) ? static::cases() : [];

        foreach ($enums as $enum) {
            $result[] = [
                ($value ?? 'value') => $enum->value,
                ($label ?? 'label') => static::enumLabel($enum),
            ];
        }

        return $result;
    }


    /**
     * Возвращает массив ключей и значений enum
     * @see ../../tests/HelperEnumTrait/HelperEnumToArrayTest.php
     *
     * @return array
     */
    public function toArray(): array
    {
        return static::enumToArray($this);
    }


    /**
     * Возвращает вариант enum по умолчанию
     * @see ../../tests/HelperEnumTrait/HelperEnumDefaultTest.php
     *
     * @return mixed
     */
    public static function enumDefault(): mixed
    {
        return null;
    }
}

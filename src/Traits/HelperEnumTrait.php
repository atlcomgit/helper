<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use BackedEnum;

/**
 * Трейт для работы с перечислениями
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
        return array_column(($value ?? self::class)::cases(), 'name');
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
        return array_column(($value ?? self::class)::cases(), 'value');
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

            default => array_combine(self::enumNames(), self::enumValues()),
        };
    }


    /**
     * Возвращает ключ перечисления
     * @see ../../tests/HelperEnumTrait/HelperEnumNameTest.php
     *
     * @param BackedEnum|string $value
     * @return string|null
     */
    public static function enumName(BackedEnum|string $value): ?string
    {
        return static::enumFrom($value)?->name;
    }


    /**
     * Возвращает значение перечисления
     * @see ../../tests/HelperEnumTrait/HelperEnumValueTest.php
     *
     * @param BackedEnum|string $value
     * @return mixed
     */
    public static function enumValue(BackedEnum|string $value): mixed
    {
        return static::enumFrom($value)?->value;
    }


    /**
     * Возвращает случайное перечисление
     * @see ../../tests/HelperEnumTrait/HelperEnumRandomTest.php
     *
     * @param class-string|null $value
     * @return BackedEnum|null
     */
    public static function enumRandom(?string $value = null): ?BackedEnum
    {
        $enum = $value ?: static::class;
        $names = is_subclass_of($enum, BackedEnum::class) ? $enum::cases() : [];

        return $names ? $names[array_rand($names, 1)] : null;
    }


    /**
     * Возвращает найденное перечисление по имени, по значению или по перечислению
     * @see ../../tests/HelperEnumTrait/HelperEnumFromTest.php
     *
     * @param BackedEnum|string|null $value
     * @return BackedEnum|null
     */
    public static function enumFrom(mixed $value): ?BackedEnum
    {
        return match (true) {
            $value instanceof BackedEnum => $value,
            is_string($value) && defined($enum = static::class . "::{$value}") => constant($enum),
            is_subclass_of(static::class, BackedEnum::class) => (
                static function ($value) {
                        foreach (static::cases() as $enum) {
                            if ($enum->name === $value || $enum->value === $value) {
                                return $enum;
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
    public static function enumLabel(?BackedEnum $value): ?string
    {
        $enum = static::enumFrom($value);

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
     * @param string $value
     * @param string $label
     * @return array
     */
    public static function enumLabels(string $value = 'value', string $label = 'label'): array
    {
        $result = [];
        $enums = is_subclass_of(static::class, BackedEnum::class) ? static::cases() : [];

        foreach ($enums as $enum) {
            $result[] = [
                $value => $enum->value,
                $label => static::enumLabel($enum),
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
}

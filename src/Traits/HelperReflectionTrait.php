<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Exceptions\HelperException;
use Atlcom\Internal\HelperInternal;
use ReflectionClass;
use Throwable;

/**
 * Трейт для работы с рефлексией
 * @mixin \Atlcom\Helper
 */
trait HelperReflectionTrait
{
    /**
     * Возвращает массив аттрибутов у класса
     * @see ../../tests/HelperReflectionTrait/HelperReflectionClassAttributesTest.php
     *
     * @param object|class-string|null $class
     * @return array
     */
    public static function reflectionClassAttributes(object|string|null $class): array
    {
        if (is_null($class)) {
            return [];
        }

        $cacheKey = __CLASS__ . __FUNCTION__ . (is_string($class) ? $class : $class::class);
        $attributes = static::cacheRuntime($cacheKey, static function () use ($class) {
            try {
                $reflectionClass = new ReflectionClass($class);
                $attributes = $reflectionClass->getAttributes();
                $result = [];

                foreach ($attributes as $attribute) {
                    $result[] = [
                        'attribute' => $attribute->getName(),
                        'arguments' => $attribute->getArguments(),
                        'instance' => $attribute->newInstance(),
                    ];
                }

                return $result;

            } catch (Throwable $e) {
                throw new HelperException($e->getMessage(), 500, $e);
            }
        });

        return $attributes;
    }


    /**
     * Возвращает массив аттрибутов у свойств класса
     * @see ../../tests/HelperReflectionTrait/HelperReflectionPropertyAttributesTest.php
     *
     * @param object|class-string|null $class
     * @param string|null $property
     * @return array
     */
    public static function reflectionPropertyAttributes(object|string|null $class, ?string $property = null): array
    {
        if (is_null($class)) {
            return [];
        }

        $cacheKey = __CLASS__ . __FUNCTION__ . (is_string($class) ? $class : $class::class);
        $attributes = static::cacheRuntime($cacheKey, static function () use ($class) {
            try {
                $reflectionClass = new ReflectionClass($class);
                $properties = $reflectionClass->getProperties();

                $result = [];

                foreach ($properties as $property) {
                    $propertyName = $property->getName();
                    $attributes = $property->getAttributes();
                    $detailedAttributes = [];

                    foreach ($attributes as $attribute) {
                        $detailedAttributes[] = [
                            'attribute' => $attribute->getName(),
                            'arguments' => $attribute->getArguments(),
                            'instance' => $attribute->newInstance(),
                        ];
                    }

                    $result[$propertyName] = $detailedAttributes;
                }

                return $result;

            } catch (Throwable $e) {
                throw new HelperException($e->getMessage(), 500, $e);
            }
        });

        return $property ? ($attributes[$property] ?? []) : $attributes;
    }


    /**
     * Возвращает массив аттрибутов у методов класса
     * @see ../../tests/HelperReflectionTrait/HelperReflectionMethodAttributesTest.php
     *
     * @param object|class-string|null $class
     * @param string|null $method
     * @return array
     */
    public static function reflectionMethodAttributes(object|string|null $class, ?string $method = null): array
    {
        if (is_null($class)) {
            return [];
        }

        $cacheKey = __CLASS__ . __FUNCTION__ . (is_string($class) ? $class : $class::class);
        $attributes = static::cacheRuntime($cacheKey, static function () use ($class) {
            try {
                $reflectionClass = new ReflectionClass($class);
                $methods = $reflectionClass->getMethods();

                $result = [];

                foreach ($methods as $method) {
                    $methodName = $method->getName();
                    $attributes = $method->getAttributes();
                    $detailedAttributes = [];

                    foreach ($attributes as $attribute) {
                        $detailedAttributes[] = [
                            'attribute' => $attribute->getName(),
                            'arguments' => $attribute->getArguments(),
                            'instance' => $attribute->newInstance(),
                        ];
                    }

                    $result[$methodName] = $detailedAttributes;
                }

                return $result;

            } catch (Throwable $e) {
                throw new HelperException($e->getMessage(), 500, $e);
            }
        });

        return $method ? ($attributes[$method] ?? []) : $attributes;
    }


    /**
     * Возвращает массив аттрибутов у аргументов метода класса
     * @see ../../tests/HelperReflectionTrait/HelperReflectionArgumentAttributesTest.php
     *
     * @param object|class-string|null $class
     * @param string|null $method
     * @param string|null $argument
     * @return array
     */
    public static function reflectionArgumentAttributes(
        object|string|null $class,
        ?string $method = null,
        ?string $argument = null,
    ): array {
        if (is_null($class)) {
            return [];
        }

        $cacheKey = __CLASS__ . __FUNCTION__ . (is_string($class) ? $class : $class::class);
        $attributes = static::cacheRuntime($cacheKey, static function () use ($class) {
            try {
                $reflectionClass = new ReflectionClass($class);
                $methods = $reflectionClass->getMethods();

                $result = [];

                foreach ($methods as $method) {
                    $methodName = $method->getName();
                    $parameters = $method->getParameters();

                    $methodParameters = [];

                    foreach ($parameters as $parameter) {
                        $paramName = $parameter->getName();
                        $attributes = $parameter->getAttributes();
                        $detailedAttributes = [];

                        foreach ($attributes as $attribute) {
                            $detailedAttributes[] = [
                                'attribute' => $attribute->getName(),
                                'arguments' => $attribute->getArguments(),
                                'instance' => $attribute->newInstance(),
                            ];
                        }

                        $methodParameters[$paramName] = $detailedAttributes;
                    }

                    $result[$methodName] = $methodParameters;
                }

                return $result;

            } catch (Throwable $e) {
                throw new HelperException($e->getMessage(), 500, $e);
            }
        });

        return ($method && $argument)
            ? ($attributes[$method][$argument] ?? [])
            : ($method ? ($attributes[$method] ?? []) : $attributes);
    }


    /**
     * Возвращает массив с разложенными параметрами из всех phpdoc блоков класса
     * @see ../../tests/HelperReflectionTrait/HelperReflectionPhpDocTest.php
     *
     * @param object|class-string|null $class
     * @param string|null $method
     * @param string|null $argument
     * @return array
     */
    public static function reflectionPhpDoc(object|string|null $class): array
    {
        if (is_null($class)) {
            return [];
        }

        $cacheKey = __CLASS__ . __FUNCTION__ . (is_string($class) ? $class : $class::class);
        $phpdoc = static::cacheRuntime($cacheKey, static function () use ($class) {
            try {
                $reflection = new ReflectionClass($class);

                $result = [
                    'name' => $reflection->getName(),
                    'doc' => HelperInternal::internalParsePhpDoc($reflection->getDocComment()),
                    'constants' => [],
                    'properties' => [],
                    'methods' => [],
                ];

                // Константы
                foreach ($reflection->getReflectionConstants() as $const) {
                    $result['constants'][$const->getName()] = [
                        'value' => $const->getValue(),
                        'doc' => HelperInternal::internalParsePhpDoc($const->getDocComment()),
                    ];
                }

                // Свойства
                foreach ($reflection->getProperties() as $property) {
                    $result['properties'][$property->getName()] = [
                        'visibility' => HelperInternal::internalVisibility($property),
                        'static' => $property->isStatic(),
                        'doc' => HelperInternal::internalParsePhpDoc($property->getDocComment()),
                    ];
                }

                // Методы
                foreach ($reflection->getMethods() as $method) {
                    $methodData = [
                        'visibility' => HelperInternal::internalVisibility($method),
                        'static' => $method->isStatic(),
                        'doc' => HelperInternal::internalParsePhpDoc($method->getDocComment()),
                        'params' => [],
                    ];

                    foreach ($method->getParameters() as $param) {
                        $paramType = $param->getType();
                        $methodData['params'][$param->getName()] = [
                            'type' => $paramType ? $paramType->getName() : null,
                            'optional' => $param->isOptional(),
                            'default' => $param->isOptional() && $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null,
                        ];
                    }

                    $result['methods'][$method->getName()] = $methodData;
                }

                return $result;
            } catch (Throwable $e) {
                throw new HelperException($e->getMessage(), 500, $e);
            }
        });

        return $phpdoc;
    }
}

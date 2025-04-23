<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с окружением
 * @mixin \Atlcom\Helper
 */
trait HelperEnvTrait
{
    /**
     * Проверяет окружение приложения на локальное и возвращает true/false
     * @see ../../tests/HelperEnvTrait/HelperEnvLocalTest.php
     *
     * @return bool
     */
    public static function envLocal(): bool
    {
        return in_array(static::stringLower(getenv('APP_ENV')), ['local']);
    }


    /**
     * Проверяет окружение приложения на разработку и возвращает true/false
     * @see ../../tests/HelperEnvTrait/HelperEnvDevTest.php
     *
     * @return bool
     */
    public static function envDev(): bool
    {
        return in_array(static::stringLower(getenv('APP_ENV')), ['dev', 'develop', 'development']);
    }


    /**
     * Проверяет окружение приложения на тестовое и возвращает true/false
     * @see ../../tests/HelperEnvTrait/HelperEnvTestTest.php
     *
     * @return bool
     */
    public static function envTest(): bool
    {
        return in_array(static::stringLower(getenv('APP_ENV')), ['test']);
    }


    /**
     * Проверяет окружение приложения на авто-тестирование и возвращает true/false
     * @see ../../tests/HelperEnvTrait/HelperEnvTestingTest.php
     *
     * @return bool
     */
    public static function envTesting(): bool
    {
        return in_array(static::stringLower(getenv('APP_ENV')), ['testing']);
    }


    /**
     * Проверяет окружение приложения на пред боевое и возвращает true/false
     * @see ../../tests/HelperEnvTrait/HelperEnvStageTest.php
     *
     * @return bool
     */
    public static function envStage(): bool
    {
        return in_array(static::stringLower(getenv('APP_ENV')), ['stage', 'staging', 'preprod']);
    }


    /**
     * Проверяет окружение приложения на боевое и возвращает true/false
     * @see ../../tests/HelperEnvTrait/HelperEnvProdTest.php
     *
     * @return bool
     */
    public static function envProd(): bool
    {
        return in_array(static::stringLower(getenv('APP_ENV')), ['prod', 'production']);
    }
}

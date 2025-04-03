# Helper

Класс помощник для работы со строками, числами, массивами.

<hr style="border:1px solid black">

## История изменений

[Открыть историю](CHANGELOG.md)

## Установка

```
composer require atlcom/helper
```

## Описание методов

---

#### Array
Работа с массивами

---
##### [arrayDot(\$value): array](./tests/HelperArrayTrait/HelperArrayDotTest.php)
Возвращает одномерный массив из многомерного
```php
$array = Helper::arrayDot(['a' => ['b' => 2, 'c' => 3]]); // $array = ['a.b' => 2, 'a.c' => 3]
```
---
##### [arrayExcludeTraceVendor(\$value, \$basePath): array](./tests/HelperArrayTrait/HelperArrayExcludeTraceVendorTest.php)
Исключает из массива трассировки пакетные ошибки
```php
$array = Helper::arrayExcludeTraceVendor(debug_backtrace()); // $array = []
```
---
##### [arrayFirst(\$value, \$key): mixed](./tests/HelperArrayTrait/HelperArrayFirstTest.php)
Возвращает значение первого элемента массива
```php
$mixed = Helper::arrayFirst(['a', 'b']); // $mixed === 'a'
$mixed = Helper::arrayFirst(['a' => 1, 'b' => 2]); // $mixed === 1
```
---
##### [arrayGet(\$value, \$key): mixed](./tests/HelperArrayTrait/HelperArrayGetTest.php)
Возвращает значение из массива по имению ключа
```php
$mixed = Helper::arrayGet(['a', 'b'], 0); // $mixed === 'a'
$mixed = Helper::arrayGet(['a' => ['b' => 2, 'c' => 3], 'b' => 4], 'a.b'); // $mixed === 2
$mixed = Helper::arrayGet(['a.b' => 1, 'b' => 2], 'a.b'); // $mixed === 1
```
---
##### [arrayLast(\$value, \$key): mixed](./tests/HelperArrayTrait/HelperArrayLastTest.php)
Возвращает значение последнего элемента массива
```php
$mixed = Helper::arrayLast(['a', 'b']); // $mixed === 'b'
$mixed = Helper::arrayLast(['a' => 1, 'b' => 2]); // $mixed === 2
```
---
##### [arrayMappingKeys(\$value, \$from, \$to): array](./tests/HelperArrayTrait/HelperArrayMappingKeysTest.php)
Возвращает массив с маппингом ключей
```php
$array = Helper::arrayMappingKeys(['a' => 1, 'b' => 2], 'a', 'c'); // $array = ['c' => 1, 'b' => 2]
$array = Helper::arrayMappingKeys([['a' => 1], ['a' => 2]], ['a' => 'c']); // $array = [['c' => 1], ['c' => 2]]
```
---
##### [arraySearchKeys(\$value, ...\$searches): array](./tests/HelperArrayTrait/HelperArraySearchKeysTest.php)
Возвращает массив найденных ключей в искомом массиве
```php
$array = Helper::arraySearchKeys(['a' => 1, 'b' => 2], 'a'); // $array = ['a' => 1]
$array = Helper::arraySearchKeys(['a' => ['b' => 2, 'c' => 3]], '*.b'); // $array = ['a.b' => 2]
```
---
##### [arraySearchKeysAndValues(\$value, ...\$keys, ...\$values): array](./tests/HelperArrayTrait/HelperArraySearchKeysAndValuesTest.php)
Возвращает массив найденных ключей в искомом массиве
```php
$array = Helper::arraySearchKeys(['a' => 1, 'b' => 2], 'a'); // $array = ['a' => 1]
$array = Helper::arraySearchKeys(['a' => ['b' => 2, 'c' => 3]], '*.b'); // $array = ['a.b' => 2]
```
---
##### [arraySearchValues(\$value, ...\$searches): array](./tests/HelperArrayTrait/HelperArraySearchValuesTest.php)
Возвращает массив найденных ключей в искомом массиве
```php
$array = Helper::arraySearchValues(['a', 'b'], 'a'); // $array = ['a']
$array = Helper::arraySearchValues(['abc', 'def'], ['a*', '*f']); // $array = ['abc', 'def']
```
---
##### [arrayUnDot(\$value): array): array](./tests/HelperArrayTrait/HelperArrayUnDotTest.php)
Возвращает многомерный массив из одномерного
```php
$array = Helper::arrayUnDot(['a.b' => 2, 'a.c' => 3]); // $array = ['a' => ['b' => 2, 'c' => 3]]
```
---

#### Cache
Работа с кешем

---
[cacheRuntime(\$key, \$callback, \$cacheEnabled): mixed](./tests/HelperCacheTrait/HelperCacheRuntimeTest.php)
Сохраняет значение value в кеше по ключу key или возвращает значение при его наличии\
Если cacheEnabled выключен, то кеш не используется
```php
$mixed = Helper::cacheRuntime('key', fn () => 1); // $mixed = 1
$mixed = Helper::cacheRuntimeGet('key'); // $mixed = 1
```
---
[cacheRuntimeExists(\$key): bool](./tests/HelperCacheTrait/HelperCacheRuntimeExistsTest.php)
Проверяет существование ключа в кеше и возвращает true/false
```php
$boolean = Helper::cacheRuntimeExists('key'); // $boolean = false
```
---
##### [cacheRuntimeGet(\$key, \$default): mixed](./tests/HelperCacheTrait/HelperCacheRuntimeGetTest.php)
Возвращает значение из кеша по ключу key или возвращает значение по умолчанию default
```php
$mixed = Helper::cacheRuntimeGet('key', 'value'); // $mixed = 'value'
```
---
##### [cacheRuntimeSet(\$key, \$value): mixed](./tests/HelperCacheTrait/HelperCacheRuntimeSetTest.php)
Сохраняет значение value в кеше по ключу key
```php
$mixed = Helper::cacheRuntimeSet('key', 'value'); // $mixed = 'value'
```
---

#### Case
Работа со стилем

---
##### [caseCamel(\$value): string](./tests/HelperCaseTrait/HelperCaseCamelTest.php)
Возвращает строку в стиле camelCase
```php
$string = Helper::caseCamel('abc-def'); // $string = 'abcDef'
```
---
##### [caseKebab(\$value): string](./tests/HelperCaseTrait/HelperCaseKebabTest.php)
Возвращает строку в стиле kebab-case
```php
$string = Helper::caseKebab('abcDef'); // $string = 'abc-Def'
```
---
##### [casePascal(\$value): string](./tests/HelperCaseTrait/HelperCasePascalTest.php)
Возвращает строку в стиле PascalCase
```php
$string = Helper::casePascal('abc-def'); // $string = 'AbcDef'
```
---
##### [caseSnake(\$value): string](./tests/HelperCaseTrait/HelperCaseSnakeTest.php)
Возвращает строку в стиле snake_case
```php
$string = Helper::caseSnake('abcDef'); // $string = 'abc_Def'
```
---

#### Color
Работа с цветами

---
##### [colorHexToRgb(\$value): array](./tests/HelperColorTrait/HelperColorHexToRgbTest.php)
Возвращает массив с RGB цветом из цвета HEX строки
```php
$array = Helper::colorHexToRgb('#000'); // $array = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => null]
$array = Helper::colorHexToRgb('#000000'); // $array = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => null]
$array = Helper::colorHexToRgb('#00000000'); // $array = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]
```
---
##### [colorRgbToHex(\$red, \$green, \$blue, \$alpha): string](./tests/HelperColorTrait/HelperColorRgbToHexTest.php)
Возвращает HEX строку из RGB цвета
```php
$string = Helper::colorRgbToHex(0, 0, 0); // $string = '#000000'
$string = Helper::colorRgbToHex(0, 0, 0, 0); // $string = '#00000000'
```
---

#### Crypt
Работа с шифрацией

---
##### [cryptArrayDecode(\$value, \$password): array](./tests/HelperCryptTrait/HelperCryptArrayDecodeTest.php)
Дешифрует все значения элементов массива вида 'hash:crypt'
```php
$array = Helper::cryptArrayDecode(['a' => 'hash:crypt']); // $array = ['a' => 1]
```
---
##### [cryptArrayEncode(\$value, \$password, \$random)](./tests/HelperCryptTrait/HelperCryptArrayEncodeTest.php)
Шифрует все значения элементов массива с добавлением хеша значения 'hash:crypt'
```php
$array = Helper::cryptArrayDecode(['a' => 1]); // $array = ['a' => 'hash:crypt']
```
---
##### [cryptDecode(\$value, \$password): string](./tests/HelperCryptTrait/HelperCryptDecodeTest.php)
Дешифрует строку с паролем
```php
$string = Helper::cryptDecode('nh93432NibR3td26', 'password'); // $string = 'abc'
```
---
##### [cryptEncode(\$value, \$password, \$random): string](./tests/HelperCryptTrait/HelperCryptEncodeTest.php)
Шифрует строку с паролем
```php
$string = Helper::cryptEncode('abc', 'password'); // $string = 'nh93432NibR3td26'
```
---

#### Date
Работа с датами

---
##### [dateFromString(\$value): ?Carbon](./tests/HelperDateTrait/HelperDateFromStringTest.php)
Возвращает объект Carbon с распознанной датой или null
```php
$date = Helper::dateFromString('в следующий понедельник'); // $date->format('d.m.Y') = 'XX.XX.XXXX'
$date = Helper::dateFromString('через 2 месяца'); // $date->format('d.m.Y') = 'XX.XX.XXXX'
```
---

#### Enum
Работа с перечислениями

---
##### [enumExists(\$value): bool](./tests/HelperEnumTrait/HelperEnumExistsTest.php)
Проверяет на существование перечисления по значению и возвращает true/false
```php
$boolean = MyEnum::enumExists('Name1'); // $boolean = true/false
```
---
##### [enumFrom(\$value): ?BackedEnum](./tests/HelperEnumTrait/HelperEnumFromTest.php)
Возвращает найденное перечисление по имени, по значению или по перечислению
```php
$enum = MyEnum::enumFrom('Name1'); // $enum = MyEnum::Name1
```
---
##### [enumLabel(\$value): ?string](./tests/HelperEnumTrait/HelperEnumLabelTest.php)
Возвращает описание перечисления (необходимо реализовать метод enumLabel)
```php
$string = MyEnum::enumLabel(MyEnum::Name1); // $string = 'Name1 (value1)'
```
---
##### [enumLabels(\$value, \$label): array](./tests/HelperEnumTrait/HelperEnumLabelsTest.php)
Возвращает список перечислений для ресурса
```php
$array = MyEnum::enumLabels(); // $array = [['value' => 'value1', 'label' => 'Описание 1']]
```
---
##### [enumName(\$value): ?string](./tests/HelperEnumTrait/HelperEnumNameTest.php)
Возвращает ключ перечисления
```php
$string = Helper::enumName(MyEnum::Name1); // $string = 'Name1'
```
---
##### [enumNames(\$value): array](./tests/HelperEnumTrait/HelperEnumNamesTest.php)
Возвращает имена ключей перечисления enum
```php
$array = MyEnum::enumNames(); // $array = ['Name1']
```
---
##### [enumRandom(\$value = null): ?BackedEnum](./tests/HelperEnumTrait/HelperEnumRandomTest.php)
Возвращает случайное перечисление
```php
$enum = Helper::enumRandom(MyEnum::class); // $enum = MyEnum::Name1
```
---
##### [enumToArray(\$value): array](./tests/HelperEnumTrait/HelperEnumToArrayTest.php)
Возвращает массив ключей и значений enum
```php
$array = MyEnum::enumToArray(); // $array = ['Name1' => 'value1']
```
---
##### [enumValue(\$value): mixed](./tests/HelperEnumTrait/HelperEnumValueTest.php)
Возвращает значение перечисления
```php
$mixed = Helper::enumValue(HelperEnumValueEnum::Name1); // $mixed = 'value1'
```
---
##### [enumValues(\$value = null): array](./tests/HelperEnumTrait/HelperEnumValuesTest.php)
Возвращает значения ключей перечисления enum
```php
$array = MyEnum::enumValues(); // $array = ['value1']
```
---

#### Env
Работа с окружением

---
##### [envDev(): bool](./tests/HelperEnvTrait/HelperEnvDevTest.php)
Проверяет окружение приложения на разработку и возвращает true/false
```php
putenv('APP_ENV=Dev');
$boolean = Helper::envDev(); // $boolean = true
```
---
##### [envLocal(): bool](./tests/HelperEnvTrait/HelperEnvLocalTest.php)
Проверяет окружение приложения на локальное и возвращает true/false
```php
putenv('APP_ENV=Local');
$boolean = Helper::envLocal(); // $boolean = true
```
---
##### [envProd(): bool](./tests/HelperEnvTrait/HelperEnvProdTest.php)
Проверяет окружение приложения на боевое и возвращает true/false
```php
putenv('APP_ENV=Prod');
$boolean = Helper::envProd(); // $boolean = true
```
---
##### [envStage(): bool](./tests/HelperEnvTrait/HelperEnvStageTest.php)
Проверяет окружение приложения на пред боевое и возвращает true/false
```php
putenv('APP_ENV=Stage');
$boolean = Helper::envStage(); // $boolean = true
```
---
##### [envTest(): bool](./tests/HelperEnvTrait/HelperEnvTestTest.php)
Проверяет окружение приложения на тестовое и возвращает true/false
```php
putenv('APP_ENV=Test');
$boolean = Helper::envTest(); // $boolean = true
```
---
##### [envTesting(): bool](./tests/HelperEnvTrait/HelperEnvTestingTest.php)
Проверяет окружение приложения на авто-тестирование и возвращает true/false
```php
putenv('APP_ENV=Testing');
$boolean = Helper::envTesting(); // $boolean = true
```
---

#### Exception
Работа с исключениями

---
##### [exceptionToString(\$value): string](./tests/HelperExceptionTrait/HelperExceptionToStringTest.php)
Возвращает исключение в виде строки json
```php
$string = Helper::exceptionToString(new Exception('message', 400)); // $string = '{"code": 400, ...}}'
```
---

#### Hash
Работа с хешами

---
##### [hashXxh128(\$value): string](./tests/HelperHashTrait/HelperHashXxh128Test.php)
Возвращает xxh128 хеш значения
```php
$string = Helper::hashXxh128('abc'); // $string = '06b05ab6733a618578af5f94892f3950'
```
---

#### Interval
Работа с интервалами

---
##### [intervalAround(\$value, \$min, \$max): int](./tests/HelperIntervalTrait/HelperIntervalAroundTest.php)
Возвращает число со смещением по кругу в заданном интервале
```php
$integer = Helper::intervalAround(3 + 1, 1, 3); // $integer = 1
$integer = Helper::intervalAround(1 - 1, 1, 3); // $integer = 3
```
---
##### [intervalBetween(\$value, ...\$intervals): array](./tests/HelperIntervalTrait/HelperIntervalBetweenTest.php)
Проверяет значение на вхождение в интервал(ы)
```php
$bool = (bool)Helper::intervalBetween(2, [1, 10]); // $bool = true
$bool = (bool)Helper::intervalBetween(12, '1..10, 13..20'); // $bool = false
$bool = (bool)Helper::intervalBetween('2025.01.02', '2025.01.01, 2025.01.03..2025.01.04'); // $bool = false
$bool = (bool)Helper::intervalBetween('2025.01.02', ['2025.01.01', '2025.01.03']); // $bool = true
```
---
##### [intervalCollection(...\$intervals): array](./tests/HelperIntervalTrait/HelperIntervalCollectionTest.php)
Возвращает число со смещением по кругу в заданном интервале
```php
$array = Helper::intervalCollection(1, 3); // $array = [['equal' => 1], ['equal' => 3]]
$array = Helper::intervalCollection('1..3'); // $array = [['min' => '1', 'max' => '3']]
$array = Helper::intervalCollection([1, 3], 5); // $array = [['min' => 1, 'max' => 3], ['equal' => 5]]
```
---
##### [intervalOverlap(...\$intervals): array](./tests/HelperIntervalTrait/HelperIntervalOverlapTest.php)
Проверяет пересечение интервалов
```php
$bool = (bool)Helper::intervalOverlap([1, 3], [2, 4]); // $bool = true
$bool = (bool)Helper::intervalOverlap('1..3', '2..4'); // $bool = false
```
---

#### Ip
Работа с ip адресами

---
##### [ipInRange(\$value, ...\$masks): array](./tests/HelperIpTrait/HelperIpInRangeTest.php)
Возвращает xxh128 хеш значения
```php
$array = Helper::ipInRange('192.168.1.1', '192.168.1.0/24'); // $array = ['192.168.1.0/24']
```
---

#### Jwt
Работа с jwt токенами

---
##### [jwtDecode(\$value, ...\$masks): array](./tests/HelperJwtTrait/HelperJwtDecodeTest.php)
Возвращает массив с данными из декодированного jwt токена
```php
$array = Helper::jwtDecode('eyJhbGciOiJTSEE1MTIiLCJ0eXAiOiJKV1QifQ.eyJpZCI6MX0.l8fbKNjJWSsZLjVfE5aUOpEhIqbMAxIcon8nZ8NYyPikf_AtjrrN0Y3cPTy1A0U3etLMCbZ6RE00FqFYLZBc7A'); // $array = ['header' => ['alg' => 'SHA512', 'typ' => 'JWT'], 'body' => ['id' => 1]]
```
---
##### [jwtEncode(\$value, ...\$masks): array](./tests/HelperJwtTrait/HelperJwtEncodeTest.php)
Возвращает строку с jwt токеном из закодированных данных
```php
$string = Helper::jwtEncode(['id' => 1]); // $string = 'eyJhbGciOiJTSEE1MTIiLCJ0eXAiOiJKV1QifQ.eyJpZCI6MX0.l8fbKNjJWSsZLjVfE5aUOpEhIqbMAxIcon8nZ8NYyPikf_AtjrrN0Y3cPTy1A0U3etLMCbZ6RE00FqFYLZBc7A'
```
---

#### Number
Работа с числами

---
##### [numberToString(\$value, \$declension, \$gender, \$enumeration): string](./tests/HelperNumberTrait/HelperNumberToStringTest.php)
Возвращает число прописью на русском языке с учетом склонения по падежу, роду и числительному перечислению
```php
$string = Helper::numberToString(123.456); // $string = 'сто двадцать три'
```
---

#### Path
Работа с путями

---
##### [pathClassName(\$value): string](./tests/HelperPathTrait/HelperPathClassNameTest.php)
Возвращает имя класса без namespace
```php
$string = Helper::pathClassName('/test/Test.php'); // $string = 'Test'
```
---
##### [pathRoot(\$value = null): string](./tests/HelperPathTrait/HelperPathRootTest.php)
Возвращает путь домашней папки
```php
$string = Helper::pathRoot(); // $string = '/home/path'
```
---

#### Regexp
Работа с валидацией

---
##### [regexpValidateAscii(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidateAsciiTest.php)
Проверяет значение строки на формат ascii (латинский алфавита и цифры)
```php
$boolean = Helper::regexpValidateAscii('0-9 AZ az'); // $boolean = true
```
---
##### [regexpValidateEmail(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidateEmailTest.php)
Проверяет значение строки на формат электронной почты
```php
$boolean = Helper::regexpValidateEmail('Test.example_1@domain.com'); // $boolean = true
```
---
##### [regexpValidateJson(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidateJsonTest.php)
Проверяет значение строки на формат json
```php
Helper::regexpValidateJson('{"a":1}'); // $boolean = true
```
---
##### [regexpValidatePattern(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidatePatternTest.php)
Проверяет значение строки на формат регулярного выражения
```php
$boolean = Helper::regexpValidatePattern('/test/'); // $boolean = true
```
---
##### [regexpValidatePhone(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidatePhoneTest.php)
Проверяет значение строки на формат номера телефона
```php
$boolean = Helper::regexpValidatePhone('+79001234567'); // $boolean = true
```
---
##### [regexpValidateUnicode(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidateUnicodeTest.php)
Проверяет значение строки на формат юникода
```php
$boolean = Helper::regexpValidateUnicode('01 AZ az АЯ ая 😀'); // $boolean = true
```
---
##### [regexpValidateUuid(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidateUuidTest.php)
Проверяет значение строки на формат идентификатора uuid
```php
$boolean = Helper::regexpValidateUuid('04d19f50-2fab-417a-815d-306b6a6f67ec'); // $boolean = true
```
---

#### Size
Работа с размерами

---
##### [sizeBytesToString(\$value): string](./tests/HelperSizeTrait/HelperSizeBytesToStringTest.php)
Возвращает размер в байтах как строку
```php
$string = Helper::sizeBytesToString(1000); // $string = '1 Кб'
```
---
##### [sizeStringToBytes(\$value): string](./tests/HelperSizeTrait/HelperSizeStringToBytesTest.php)
Возвращает размер в байтах из строки размера
```php
$integer = Helper::sizeStringToBytes('1 Килобайт'); // $integer = 1000
```
---

#### String
Работа со строками

---
##### [stringBreakByLength(\$value, \$breakType, \$partLengthMax, \$firstPartLength): array](./tests/HelperStringTrait/HelperStringBreakByLengthTest.php)
Разбивает текст на части указанной длины по словам или строкам и возвращает массив строк
```php
$array = Helper::stringBreakByLength('Иванов Иван Иванович', HelperStringBreakTypeEnum::Word, 10); // $array = ['Иванов', 'Иван', 'Иванович']
```
---
##### [stringChange(\&\$value, \$change, \$start): string](./tests/HelperStringTrait/HelperStringChangeTest.php)
Заменяет на подстроку в строке с позиции start
```php
$string = Helper::stringChange('abc', 'd', 2); // $string = 'abd'
$string = Helper::stringChange('abcefg', 'xyz', -1); // $string = 'abcefxyz'
```
---
##### [stringConcat(\$delimiter, ...\$values): string](./tests/HelperStringTrait/HelperStringConcatTest.php)
Соединяет значения values через разделитель delimiter
```php
$string = Helper::stringConcat(' ', 'Иванов', 'Иван', 'Иванович'); // $string = 'Иванов Иван Иванович'
$string = Helper::stringConcat(' ', ['Иванов', 'Иван', 'Иванович']); // $string = 'Иванов Иван Иванович'
```
---
##### [stringCopy(\$value, \$start, \$length): string](./tests/HelperStringTrait/HelperStringCopyTest.php)
Копирует подстроку из строки с позиции start длиной length
```php
$string = Helper::stringCopy('abc', 1, 1); // $string = 'b'
$string = Helper::stringCopy('abc', -2); // $string = 'bc'
```
---
##### [stringCount(\$value, ...\$searches): string](./tests/HelperStringTrait/HelperStringCountTest.php)
Возвращает количество найденных вхождений подстрок в строке
```php
$integer = Helper::stringCount('abc', 'a'); // $integer = 1
$integer = Helper::stringCount('abc', ['a', 'b']); // $integer = 2
```
---
##### [stringCut(\&\$value, \$start, \$length): string](./tests/HelperStringTrait/HelperStringCutTest.php)
Вырезает подстроку из строки с позиции start длиной length
```php
$source = 'abc';
$string = Helper::stringCut($source, 1, 1); // $source = 'ac', $string = 'b'
```
---
##### [stringDelete(\$value, \$start, \$length): string](./tests/HelperStringTrait/HelperStringDeleteTest.php)
Удаляет из строки с позиции start длиной length
```php
$string = Helper::stringDelete('abc', 1, 1); // $string = 'ac'
$string = Helper::stringDelete('abc', -2); // $string = 'a'
```
---
##### [stringDeleteMultiples(\$value, ...\$multiples): string](./tests/HelperStringTrait/HelperStringDeleteMultiplesTest.php)
Возвращает строку значения удаляя последовательные повторы подстрок
```php
$string = Helper::stringDeleteMultiples('abcabc', 'abc'); // $string = 'abc'
$string = Helper::stringDeleteMultiples('a--bb', ['-', 'b']); // $string = 'a-b'
```
---
##### [stringEnds(\$value, ...\$searches): string](./tests/HelperStringTrait/HelperStringEndsTest.php)
Проверяет конец строки на совпадение подстрок и возвращает найденную подстроку или null
```php
$string = Helper::stringEnds('abc', 'c'); // $string = 'c'
```
---
##### [stringLength(\$value): int](./tests/HelperStringTrait/HelperStringLengthTest.php)
Возвращает длину строки
```php
$integer = Helper::stringLength('123'); // $integer = 3
```
---
##### [stringLower(\$value): string](./tests/HelperStringTrait/HelperStringLowerTest.php)
Переводит строку в нижний регистр
```php
$string = Helper::stringLower('ABC'); // $string = 'abc'
```
---
##### [stringLowerFirst(\$value): string](./tests/HelperStringTrait/HelperStringLowerFirstTest.php)
Возвращает строку с первый символом в нижнем регистре
```php
$string = Helper::stringLowerFirst('Abc'); // $string = 'abc'
```
---
##### [stringLowerFirstAll(\$value): string](./tests/HelperStringTrait/HelperStringLowerFirstAllTest.php)
Возвращает строку со всеми словами в нижнем регистре
```php
$string = Helper::stringLowerFirstAll('Abc def Ghi-Jkl_Mno'); // $string = 'abc def ghi-jkl_mno'
```
---
##### [stringMerge(...\$values): string](./tests/HelperStringTrait/HelperStringMergeTest.php)
Объединяет значения в одну строку с заменой предыдущих символов
```php
$string = Helper::stringMerge('abc', 'de'); // $string = 'dec'
```
---
##### [stringPadPrefix(\$value, \$prefix, \$condition): string](./tests/HelperStringTrait/HelperstringPadPrefixTest.php)
Добавляет в начало строки префикс при выполнении условия condition
```php
$string = Helper::stringPadPrefix('def', 'abc', true); // $string = 'abcdef'
```
---
##### [stringPadSuffix(\$value, \$suffix, \$condition): string](./tests/HelperStringTrait/HelperstringPadSuffixTest.php)
Добавляет в конец строки суффикс при выполнении условия condition
```php
$string = Helper::stringPadSuffix('abc', 'def', true); // $string = 'abcdef'
```
---
##### [stringPaste(\&\$value, \$paste, \$start): string](./tests/HelperStringTrait/HelperStringPasteTest.php)
Вставляет подстроку в строку с позиции start
```php
$string = Helper::stringPaste('abc', 'd', 2); // $string = 'abdc'
```
---
##### [stringPlural(\$value, \$plurals, \$includeValue): string](./tests/HelperStringTrait/HelperStringPluralTest.php)
Возвращает число с числительным названием из массива с тремя вариантами для [0|5..9, 1, 2..4]
```php
$string = Helper::stringPlural(1, ['штук', 'штука', 'штуки']); // $string = '1 штука'
```
---
##### [stringPosAll(\$value, ...\$searches): array](./tests/HelperStringTrait/HelperStringPosAllTest.php)
Возвращает массив всех позиций искомых подстрок найденных в строке
```php
$array = Helper::stringPosAll('abcd', 'bc', 'd'); // $array = ['bc' => [1], 'd' => [3]]
```
---
##### [stringPosAny(\$value, ...\$searches): array](./tests/HelperStringTrait/HelperStringPosAnyTest.php)
Возвращает массив позиции первой искомой подстроки найденной в строке
```php
$array = Helper::stringPosAny('abcd', ['f', 'b', 'c']); // $array = ['b' => 1]
```
---
##### [stringRandom(\$pattern): string](./tests/HelperStringTrait/HelperStringRandomTest.php)
Возвращает случайную строку по шаблону регулярного выражения
```php
$string = Helper::stringRandom('/[a-z]{10}/'); // $string = 'sfosdjocsh'
```
---
##### [stringRepeat(\$value, \$count): string](./tests/HelperStringTrait/HelperStringRepeatTest.php)
Возвращает повторяющуюся строку значения указанное количество раз
```php
$string = Helper::stringRepeat('a', 3); // $string = 'aaa'
$string = Helper::stringRepeat(1, 3); // $string = '111'
```
---
##### [stringReplace(\$value, \$searches, \$replaces): string](./tests/HelperStringTrait/HelperStringReplaceTest.php)
Заменяет подстроки в строке
```php
$string = Helper::stringReplace('abcd', 'd', 'x'); // $string = 'abcx'
$string = Helper::stringReplace('abcd', ['d' => 'x']); // $string = 'abcx'
```
---
##### [stringReverse(\$value): string](./tests/HelperStringTrait/HelperStringReverseTest.php)
Возвращает реверсивную строку значения
```php
$string = Helper::stringReverse('abc'); // $string = 'cba'
$string = Helper::stringReverse(123); // $string = '321'
```
---
##### [stringSearchAll(\$value, ...\$searches): array](./tests/HelperStringTrait/HelperStringSearchAllTest.php)
Возвращает массив всех искомых подстрок найденных в строке
```php
$array = Helper::stringSearchAll('abcd', 'bc', 'd'); // $array = ['bc', 'd']
$array = Helper::stringSearchAll('Иванов Иван Иванович', ['*Иван*']); // $array = ['*Иван*']
```
---
##### [stringSearchAny(\$value, ...\$searches): array](./tests/HelperStringTrait/HelperStringSearchAnyTest.php)
Возвращает массив первой искомой подстроки найденной в строке
```php
$string = Helper::stringSearchAny('abcd', 'bc'); // $string = ['bc']
$string = Helper::stringSearchAny('abcd', ['ab', 'bc']); // $string = ['ab']
$string = Helper::stringSearchAny('Иванов Иван Иванович', '*Иван*'); // $string = ['*Иван*']
```
---
##### [stringSplit(\$value, \$delimiter, \$index, \$cacheEnabled): array](./tests/HelperStringTrait/HelperStringSplitTest.php)
Возвращает массив подстрок разбитых на части между разделителем в строке
```php
$array = Helper::stringSplit('abc,,def/xyz', [',', '/']); // $array = ['abc', '', 'def', 'xyz']
```
---
##### [stringStarts(\$value, ...\$searches): string](./tests/HelperStringTrait/HelperStringStartsTest.php)
Проверяет начало строки на совпадение подстрок и возвращает найденную подстроку
```php
$string = Helper::stringStarts('abc', 'a'); // $string = 'a'
```
---
##### [stringUpper(\$value): string](./tests/HelperStringTrait/HelperStringUpperTest.php)
Переводит строку в верхний регистр
```php
$string = Helper::stringUpper('abc'); // $string = 'ABC'
```
---
##### [stringUpperFirst(\$value): string](./tests/HelperStringTrait/HelperStringUpperFirstTest.php)
Возвращает строку с первый символом в верхнем регистре
```php
$string = Helper::stringUpperFirst('abc'); // $string = 'Abc'
```
---
##### [stringUpperFirstAll(\$value): string](./tests/HelperStringTrait/HelperStringUpperFirstAllTest.php)
Возвращает строку со всеми словами в верхнем регистре
```php
$string = Helper::stringUpperFirstAll('abc Def ghi-jkl_mno'); // $string = 'Abc Def Ghi-Jkl_Mno'
```
---

#### Telegram
Работа с сообщениями телеграм

---
##### [telegramBreakMessage(\$value, \$hasAttach): array](./tests/HelperTelegramTrait/HelperTelegramBreakMessageTest.php)
Разбивает текст сообщения на части для отправки в телеграм
```php
$array = Helper::telegramBreakMessage('Иванов Иван Иванович', false); // $array = ['Иванов Иван Иванович']
```
---

#### Time
Работа со временем

---
##### [timeBetweenDatesToArray(\$dateFrom, \$dateTo): array](./tests/HelperTimeTrait/HelperTimeBetweenDatesToArrayTest.php)
Возвращает массив периодов между датами
```php
$array = Helper::timeBetweenDatesToArray('01.01.2025 00:00:00', '02.02.2026 01:02:03'); // $array = ['years' => 1, 'months' => 1, 'days' => 1, 'hours' => 1, 'minutes' => 2, 'seconds' => 3]
```
---
##### [timeBetweenDatesToString(\$dateFrom, \$dateTo, \$withTime, \$time24): array](./tests/HelperTimeTrait/HelperTimeBetweenDatesToStringTest.php)
Возвращает массив периодов между датами
```php
$string = Helper::timeBetweenDatesToString('01.01.2025 00:00:00', '02.02.2026 01:02:03', true); // $string = '1 год 1 месяц 1 день 1 час 2 минуты 3 секунды'
```
---
##### [timeSecondsToArray(\$value): array](./tests/HelperTimeTrait/HelperTimeSecondsToArrayTest.php)
Возвращает массив периодов из количества секунд
```php
$array = Helper::timeSecondsToArray(1); // $array = ['years' => 0, 'days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 1, 'milliseconds' => 0]
```
---
##### [timeSecondsToString(\$value, \$withZero, \$withMilliseconds, \$pluralNames): array](./tests/HelperTimeTrait/HelperTimeSecondsToStringTest.php)
Возвращает период из количества секунд в виде строки YDHM
```php
$string = Helper::timeSecondsToString(123); // $string = '2 минуты 3 секунды'
```
---

#### Transform
Работа с трансформацией

---
##### [transformToArray(\$value): array](./tests/HelperTransformTrait/HelperTransformToArrayTest.php)
Возвращает массив из значения
```php
$array = Helper::transformToArray(['a', 'b']); // $array = ['a', 'b']
$array = Helper::transformToArray('a'); // $array = ['a']
```
---

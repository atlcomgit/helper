# Helper

Класс помощник работы со строками, числами, массивами.

<hr style="border:1px solid black">

## История изменений

[Открыть историю](CHANGELOG.md)

## Установка

```
composer require atlcom/helper
```

## Описание методов

---
**[Helper::arrayExcludeTraceVendor](./tests/HelperArrayTrait/HelperArrayExcludeTraceVendorTest.php)**(\$value, \$basePath): array\
Исключает из массива трассировки пакетные ошибки
```php
$array = Helper::arrayExcludeTraceVendor(debug_backtrace()); // $array = []
```
---
**[Helper::arrayFirst](./tests/HelperArrayTrait/HelperArrayFirstTest.php)**(\$value, \$key): mixed\
Возвращает значение первого элемента массива
```php
$mixed$ = Helper::arrayFirst(['a', 'b']); // $mixed === 'a'
$mixed = Helper::arrayFirst(['a' => 1, 'b' => 2]); // $mixed === 1
```
---
**[Helper::arrayGet](./tests/HelperArrayTrait/HelperArrayGetTest.php)**(\$value, \$key): mixed\
Возвращает значение из массива по имению ключа
```php
$mixed = Helper::arrayGet(['a', 'b'], 0); // $mixed === 'a'
$mixed = Helper::arrayGet(['a' => ['b' => 2, 'c' => 3], 'b' => 4], 'a.b'); // $mixed === 2
$mixed = Helper::arrayGet(['a.b' => 1, 'b' => 2], 'a.b'); // $mixed === 1
```
---
**[Helper::arrayLast](./tests/HelperArrayTrait/HelperArrayLastTest.php)**(\$value, \$key): mixed\
Возвращает значение последнего элемента массива
```php
$mixed$ = Helper::arrayLast(['a', 'b']); // $mixed === 'b'
$mixed = Helper::arrayLast(['a' => 1, 'b' => 2]); // $mixed === 2
```
---
**[Helper::arrayMappingKeys](./tests/HelperArrayTrait/HelperArrayMappingKeysTest.php)**(\$value, \$from, \$to): array\
Возвращает массив с маппингом ключей
```php
$array = Helper::arrayMappingKeys(['a' => 1, 'b' => 2], 'a', 'c'); // $array = ['c' => 1, 'b' => 2]
$array = Helper::arrayMappingKeys([['a' => 1], ['a' => 2]], ['a' => 'c']); // $array = [['c' => 1], ['c' => 2]]
```
---
**[Helper::arraySearchKeys](./tests/HelperArrayTrait/HelperArraySearchKeysTest.php)**(\$value, ...\$searches): array\
Возвращает массив найденных ключей в искомом массиве
```php
$array = Helper::arraySearchKeys(['a' => 1, 'b' => 2], 'a'); // $array = ['a' => 1]
$array = Helper::arraySearchKeys(['a' => ['b' => 2, 'c' => 3]], '*.b'); // $array = ['a.b' => 2]
```
---
**[Helper::arraySearchKeysAndValues](./tests/HelperArrayTrait/HelperArraySearchKeysAndValuesTest.php)**(\$value, ...\$keys, ...\$values): array\
Возвращает массив найденных ключей в искомом массиве
```php
$array = Helper::arraySearchKeys(['a' => 1, 'b' => 2], 'a'); // $array = ['a' => 1]
$array = Helper::arraySearchKeys(['a' => ['b' => 2, 'c' => 3]], '*.b'); // $array = ['a.b' => 2]
```
---
**[Helper::arraySearchValues](./tests/HelperArrayTrait/HelperArraySearchValuesTest.php)**(\$value, ...\$searches): array\
Возвращает массив найденных ключей в искомом массиве
```php
$array = Helper::arraySearchValues(['a', 'b'], 'a'); // $array = ['a']
$array = Helper::arraySearchValues(['abc', 'def'], ['a*', '*f']); // $array = ['abc', 'def']
```
---
**[Helper::arrayValueToArray](./tests/HelperArrayTrait/HelperArrayValueToArrayTest.php)**(\$value): array\
Возвращает массив из значения
```php
$array = Helper::arrayValueToArray(['a', 'b']); // $array = ['a', 'b']
$array = Helper::arrayValueToArray('a'); // $array = ['a']
```
---
**[Helper::cacheRuntimeGet](./tests/HelperCacheTrait/HelperCacheRuntimeGetTest.php)**(\$key, \$default): mixed\
Возвращает значение из кеша по ключу key или возвращает значение по умолчанию default
```php
$mixed = Helper::cacheRuntimeGet('key', 'value'); // $mixed = 'value'
```
---
**[Helper::cacheRuntimeSet](./tests/HelperCacheTrait/HelperCacheRuntimeSetTest.php)**(\$key, \$value): void\
Сохраняет значение value в кеше по ключу key
```php
$array = Helper::cacheRuntimeSet('key', 'value'); // 
```
---
**[Helper::colorHexToRgb](./tests/HelperColorTrait/HelperColorHexToRgbTest.php)**(\$value): array\
Возвращает массив с RGB цветом из цвета HEX строки
```php
$array = Helper::colorHexToRgb('#000'); // $array = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => null]
$array = Helper::colorHexToRgb('#000000'); // $array = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => null]
$array = Helper::colorHexToRgb('#00000000'); // $array = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]
```
---
**[Helper::colorRgbToHex](./tests/HelperColorTrait/HelperColorRgbToHexTest.php)**(\$red, \$green, \$blue, \$alpha): string\
Возвращает HEX строку из RGB цвета
```php
$string = Helper::colorRgbToHex(0, 0, 0); // $string = '#000000'
$string = Helper::colorRgbToHex(0, 0, 0, 0); // $string = '#00000000'
```
---
**[Helper::cryptDecode](./tests/HelperCryptTrait/HelperCryptDecodeTest.php)**(\$value, \$password): string\
Дешифрует строку с паролем
```php
$string = Helper::cryptDecode('nh93432NibR3td26', 'password'); // $string = 'abc'
```
---
**[Helper::cryptEncode](./tests/HelperCryptTrait/HelperCryptEncodeTest.php)**(\$value, \$password, \$random): string\
Шифрует строку с паролем
```php
$string = Helper::cryptEncode('abc', 'password'); // $string = 'nh93432NibR3td26'
```
---
**[Helper::exceptionToString](./tests/HelperExceptionTrait/HelperExceptionToStringTest.php)**(\$value): string\
Возвращает исключение в виде строки json
```php
$string = Helper::exceptionToString(new Exception('message', 400)); // $string = '{"code": 400, ...}}'
```
---
**[Helper::hashXxh128](./tests/HelperHashTrait/HelperHashXxh128Test.php)**(\$value): string\
Возвращает xxh128 хеш значения
```php
$string = Helper::hashXxh128('abc'); // $string = '06b05ab6733a618578af5f94892f3950'
```
---
**[Helper::intervalAround](./tests/HelperIntervalTrait/HelperIntervalAroundTest.php)**(\$value, \$min, \$max): int\
Возвращает число со смещением по кругу в заданном интервале
```php
$integer = Helper::intervalAround(3 + 1, 1, 3); // $integer = 1
$integer = Helper::intervalAround(1 - 1, 1, 3); // $integer = 3
```
---
**[Helper::intervalBetween](./tests/HelperIntervalTrait/HelperIntervalBetweenTest.php)**(\$value, ...\$intervals): array\
Проверяет значение на вхождение в интервал(ы)
```php
$bool = (bool)Helper::intervalBetween(2, [1, 10]); // $bool = true
$bool = (bool)Helper::intervalBetween(12, '1..10, 13..20'); // $bool = false
$bool = (bool)Helper::intervalBetween('2025.01.02', '2025.01.01, 2025.01.03..2025.01.04'); // $bool = false
$bool = (bool)Helper::intervalBetween('2025.01.02', ['2025.01.01', '2025.01.03']); // $bool = true
```
---
**[Helper::intervalCollection](./tests/HelperIntervalTrait/HelperIntervalCollectionTest.php)**(...\$intervals): array\
Возвращает число со смещением по кругу в заданном интервале
```php
$array = Helper::intervalCollection(1, 3); // $array = [['equal' => 1], ['equal' => 3]]
$array = Helper::intervalCollection('1..3'); // $array = [['min' => '1', 'max' => '3']]
$array = Helper::intervalCollection([1, 3], 5); // $array = [['min' => 1, 'max' => 3], ['equal' => 5]]
```
---
**[Helper::intervalOverlap](./tests/HelperIntervalTrait/HelperIntervalOverlapTest.php)**(...\$intervals): array\
Проверяет пересечение интервалов
```php
$bool = (bool)Helper::intervalOverlap([1, 3], [2, 4]); // $bool = true
$bool = (bool)Helper::intervalOverlap('1..3', '2..4'); // $bool = false
```
---
**[Helper::ipInRange](./tests/HelperIpTrait/HelperIpInRangeTest.php)**(\$value, ...\$masks): array\
Возвращает xxh128 хеш значения
```php
$array = Helper::ipInRange('192.168.1.1', '192.168.1.0/24'); // $array = ['192.168.1.0/24']
```
---
**[Helper::jwtDecode](./tests/HelperJwtTrait/HelperJwtDecodeTest.php)**(\$value, ...\$masks): array\
Возвращает массив с данными из декодированного jwt токена
```php
$array = Helper::jwtDecode('eyJhbGciOiJTSEE1MTIiLCJ0eXAiOiJKV1QifQ.eyJpZCI6MX0.l8fbKNjJWSsZLjVfE5aUOpEhIqbMAxIcon8nZ8NYyPikf_AtjrrN0Y3cPTy1A0U3etLMCbZ6RE00FqFYLZBc7A'); // $array = ['header' => ['alg' => 'SHA512', 'typ' => 'JWT'], 'body' => ['id' => 1]]
```
---
**[Helper::jwtEncode](./tests/HelperJwtTrait/HelperJwtEncodeTest.php)**(\$value, ...\$masks): array\
Возвращает строку с jwt токеном из закодированных данных
```php
$string = Helper::jwtEncode(['id' => 1]); // $string = 'eyJhbGciOiJTSEE1MTIiLCJ0eXAiOiJKV1QifQ.eyJpZCI6MX0.l8fbKNjJWSsZLjVfE5aUOpEhIqbMAxIcon8nZ8NYyPikf_AtjrrN0Y3cPTy1A0U3etLMCbZ6RE00FqFYLZBc7A'
```
---
**[Helper::pathClassName](./tests/HelperPathTrait/HelperPathClassNameTest.php)**(\$value): string\
Возвращает имя класса без namespace
```php
$string = Helper::pathClassName('/test/Test.php'); // $string = 'Test'
```
---
**[Helper::pathRoot](./tests/HelperPathTrait/HelperPathRootTest.php)**(\$value = null): string\
Возвращает путь домашней папки
```php
$string = Helper::pathRoot(); // $string = '/home/path'
```
---
**[Helper::regexpValidateAscii](./tests/HelperRegexpTrait/HelperRegexpValidateAsciiTest.php)**(\$value): bool\
Проверяет значение строки на формат ascii (латинский алфавита и цифры)
```php
$boolean = Helper::regexpValidateAscii('0-9 AZ az'); // $boolean = true
```
---
**[Helper::regexpValidateEmail](./tests/HelperRegexpTrait/HelperRegexpValidateEmailTest.php)**(\$value): bool\
Проверяет значение строки на формат электронной почты
```php
$boolean = Helper::regexpValidateEmail('Test.example_1@domain.com'); // $boolean = true
```
---
**[Helper::regexpValidateJson](./tests/HelperRegexpTrait/HelperRegexpValidateJsonTest.php)**(\$value): bool\
Проверяет значение строки на формат json
```php
Helper::regexpValidateJson('{"a":1}'); // $boolean = true
```
---
**[Helper::regexpValidatePattern](./tests/HelperRegexpTrait/HelperRegexpValidatePatternTest.php)**(\$value): bool\
Проверяет значение строки на формат регулярного выражения
```php
$boolean = Helper::regexpValidatePattern('/test/'); // $boolean = true
```
---
**[Helper::regexpValidatePhone](./tests/HelperRegexpTrait/HelperRegexpValidatePhoneTest.php)**(\$value): bool\
Проверяет значение строки на формат номера телефона
```php
$boolean = Helper::regexpValidatePhone('+79001234567'); // $boolean = true
```
---
**[Helper::regexpValidateUnicode](./tests/HelperRegexpTrait/HelperRegexpValidateUnicodeTest.php)**(\$value): bool\
Проверяет значение строки на формат юникода
```php
$boolean = Helper::regexpValidateUnicode('01 AZ az АЯ ая 😀'); // $boolean = true
```
---
**[Helper::regexpValidateUuid](./tests/HelperRegexpTrait/HelperRegexpValidateUuidTest.php)**(\$value): bool\
Проверяет значение строки на формат идентификатора uuid
```php
$boolean = Helper::regexpValidateUuid('04d19f50-2fab-417a-815d-306b6a6f67ec'); // $boolean = true
```
---
**[Helper::sizeBytesToString](./tests/HelperSizeTrait/HelperSizeBytesToStringTest.php)**(\$value): string\
Возвращает размер в байтах как строку
```php
$string = Helper::sizeBytesToString(1000); // $string = '1 Кб'
```
---
**[Helper::sizeStringToBytes](./tests/HelperSizeTrait/HelperSizeStringToBytesTest.php)**(\$value): string\
Возвращает размер в байтах из строки размера
```php
$integer = Helper::sizeStringToBytes('1 Килобайт'); // $integer = 1000
```
---
**[Helper::stringBreakByLength](./tests/HelperStringTrait/HelperStringBreakByLengthTest.php)**(\$value, \$breakType, \$partLengthMax, \$firstPartLength): array\
Разбивает текст на части указанной длины по словам или строкам и возвращает массив строк
```php
$array = Helper::stringBreakByLength('Иванов Иван Иванович', HelperStringBreakTypeEnum::Word, 10); // $array = ['Иванов', 'Иван', 'Иванович']
```
---
**[Helper::stringChange](./tests/HelperStringTrait/HelperStringChangeTest.php)**(\&\$value, \$change, \$start): string\
Заменяет на подстроку в строке с позиции start
```php
$string = Helper::stringChange('abc', 'd', 2); // $string = 'abd'
$string = Helper::stringChange('abcefg', 'xyz', -1); // $string = 'abcefxyz'
```
---
**[Helper::stringConcat](./tests/HelperStringTrait/HelperStringConcatTest.php)**(\$delimiter, ...\$values): string\
Соединяет значения values через разделитель delimiter
```php
$string = Helper::stringConcat(' ', 'Иванов', 'Иван', 'Иванович'); // $string = 'Иванов Иван Иванович'
$string = Helper::stringConcat(' ', ['Иванов', 'Иван', 'Иванович']); // $string = 'Иванов Иван Иванович'
```
---
**[Helper::stringCopy](./tests/HelperStringTrait/HelperStringCopyTest.php)**(\$value, \$start, \$length): string\
Копирует подстроку из строки с позиции start длиной length
```php
$string = Helper::stringCopy('abc', 1, 1); // $string = 'b'
$string = Helper::stringCopy('abc', -2); // $string = 'bc'
```
---
**[Helper::stringCount](./tests/HelperStringTrait/HelperStringCountTest.php)**(\$value, ...\$searches): string\
Возвращает количество найденных вхождений подстрок в строке
```php
$integer = Helper::stringCount('abc', 'a'); // $integer = 1
$integer = Helper::stringCount('abc', ['a', 'b']); // $integer = 2
```
---
**[Helper::stringCut](./tests/HelperStringTrait/HelperStringCutTest.php)**(\&\$value, \$start, \$length): string\
Вырезает подстроку из строки с позиции start длиной length
```php
$source = 'abc';
$string = Helper::stringCut($source, 1, 1); // $source = 'ac', $string = 'b'
```
---
**[Helper::stringDelete](./tests/HelperStringTrait/HelperStringDeleteTest.php)**(\$value, \$start, \$length): string\
Удаляет из строки с позиции start длиной length
```php
$string = Helper::stringDelete('abc', 1, 1); // $string = 'ac'
$string = Helper::stringDelete('abc', -2); // $string = 'a'
```
---
**[Helper::stringEnds](./tests/HelperStringTrait/HelperStringEndsTest.php)**(\$value, ...\$searches): string\
Проверяет конец строки на совпадение подстрок и возвращает найденную подстроку или null
```php
$string = Helper::stringEnds('abc', 'c'); // $string = 'c'
```
---
**[Helper::stringLength](./tests/HelperStringTrait/HelperStringLengthTest.php)**(\$value): int\
Возвращает длину строки
```php
$integer = Helper::stringLength('123'); // $integer = 3
```
---
**[Helper::stringLower](./tests/HelperStringTrait/HelperStringLowerTest.php)**(\$value): string\
Переводит строку в нижний регистр
```php
$string = Helper::stringLower('ABC'); // $string = 'abc'
```
---
**[Helper::stringMerge](./tests/HelperStringTrait/HelperStringMergeTest.php)**(...\$values): string\
Объединяет значения в одну строку с заменой предыдущих символов
```php
$string = Helper::stringMerge('abc', 'de'); // $string = 'dec'
```
---
**[Helper::stringPadPrefix](./tests/HelperStringTrait/HelperstringPadPrefixTest.php)**(\$value, \$prefix, \$condition): string\
Добавляет в начало строки префикс при выполнении условия condition
```php
$string = Helper::stringPadPrefix('def', 'abc', true); // $string = 'abcdef'
```
---
**[Helper::stringPadSuffix](./tests/HelperStringTrait/HelperstringPadSuffixTest.php)**(\$value, \$suffix, \$condition): string\
Добавляет в конец строки суффикс при выполнении условия condition
```php
$string = Helper::stringPadSuffix('abc', 'def', true); // $string = 'abcdef'
```
---
**[Helper::stringPaste](./tests/HelperStringTrait/HelperStringPasteTest.php)**(\&\$value, \$paste, \$start): string\
Вставляет подстроку в строку с позиции start
```php
$string = Helper::stringPaste('abc', 'd', 2); // $string = 'abdc'
```
---
**[Helper::stringPlural](./tests/HelperStringTrait/HelperStringPluralTest.php)**(\$value, \$plurals, \$includeValue): string\
Возвращает число с числительным названием
```php
$string = Helper::stringPlural(1, ['штук', 'штука', 'штуки']); // $string = '1 штука'
```
---
**[Helper::stringRepeat](./tests/HelperStringTrait/HelperStringRepeatTest.php)**(\$value, \$count): string\
Возвращает повторяющуюся строку значения указанное количество раз
```php
$string = Helper::stringRepeat('a', 3); // $string = 'aaa'
$string = Helper::stringRepeat(1, 3); // $string = '111'
```
---
**[Helper::stringReplace](./tests/HelperStringTrait/HelperStringReplaceTest.php)**(\$value, \$searches, \$replaces): string\
Заменяет подстроки в строке
```php
$string = Helper::stringReplace('abcd', 'd', 'x'); // $string = 'abcx'
$string = Helper::stringReplace('abcd', ['d' => 'x']); // $string = 'abcx'
```
---
**[Helper::stringReverse](./tests/HelperStringTrait/HelperStringReverseTest.php)**(\$value): string\
Возвращает реверсивную строку значения
```php
$string = Helper::stringReverse('abc'); // $string = 'cba'
$string = Helper::stringReverse(123); // $string = '321'
```
---
**[Helper::stringSearchAll](./tests/HelperStringTrait/HelperStringSearchAllTest.php)**(\$value, ...\$searches): array\
Проверяет вхождение подстрок в строке и возвращает все найденные искомые значения
```php
$array = Helper::stringSearchAll('abcd', 'bc', 'd'); // $array = ['bc', 'd']
$string = Helper::stringSearchAll('Иванов Иван Иванович', ['*Иван*']); // $string = ['*Иван*']
```
---
**[Helper::stringSearchAny](./tests/HelperStringTrait/HelperStringSearchAnyTest.php)**(\$value, ...\$searches): ?string\
Проверяет вхождение подстрок в строке и возвращает первое найденное искомое значение или null
```php
$string = Helper::stringSearchAny('abcd', 'bc'); // $string = 'bc'
$string = Helper::stringSearchAny('abcd', ['ab', 'bc']); // $string = 'ab'
$string = Helper::stringSearchAny('Иванов Иван Иванович', '*Иван*'); // $string = '*Иван*'
```
---
**[Helper::stringStarts](./tests/HelperStringTrait/HelperStringStartsTest.php)**(\$value, ...\$searches): string\
Проверяет начало строки на совпадение подстрок и возвращает найденную подстроку
```php
$string = Helper::stringStarts('abc', 'a'); // $string = 'a'
```
---
**[Helper::stringUpper](./tests/HelperStringTrait/HelperStringUpperTest.php)**(\$value): string\
Переводит строку в верхний регистр
```php
$string = Helper::stringUpper('abc'); // $string = 'ABC'
```
---
**[Helper::telegramBreakMessage](./tests/HelperTelegramTrait/HelperTelegramBreakMessageTest.php)**(\$value, \$hasAttach): array\
Разбивает текст сообщения на части для отправки в телеграм
```php
$array = Helper::telegramBreakMessage('Иванов Иван Иванович', false); // $array = ['Иванов Иван Иванович']
```
---
**[Helper::timePeriodBetweenDatesToArray](./tests/HelperTimeTrait/HelperTimePeriodBetweenDatesToArrayTest.php)**(\$dateFrom, \$dateTo): array\
Возвращает массив периодов между датами
```php
$array = Helper::timePeriodBetweenDatesToArray('01.01.2025 00:00:00', '02.02.2026 01:02:03'); // $array = ['years' => 1, 'months' => 1, 'days' => 1, 'hours' => 1, 'minutes' => 2, 'seconds' => 3]
```
---
**[Helper::timePeriodBetweenDatesToString](./tests/HelperTimeTrait/HelperTimePeriodBetweenDatesToStringTest.php)**(\$dateFrom, \$dateTo, \$withTime, \$time24): array\
Возвращает массив периодов между датами
```php
$string = Helper::timePeriodBetweenDatesToString('01.01.2025 00:00:00', '02.02.2026 01:02:03', true); // $string = '1 год 1 месяц 1 день 1 час 2 минуты 3 секунды'
```
---
**[Helper::timeSecondsToArray](./tests/HelperTimeTrait/HelperTimeSecondsToArrayTest.php)**(\$value): array\
Возвращает массив периодов из количества секунд
```php
$array = Helper::timeSecondsToArray(1); // $array = ['years' => 0, 'days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 1, 'milliseconds' => 0]
```
---
**[Helper::timeSecondsToString](./tests/HelperTimeTrait/HelperTimeSecondsToStringTest.php)**(\$value, \$withZero, \$withMilliseconds, \$pluralNames): array\
Возвращает период из количества секунд в виде строки YDHM
```php
$string = Helper::timeSecondsToString(123); // $string = '2 минуты 3 секунды'
```
---

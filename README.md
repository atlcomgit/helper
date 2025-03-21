# Helper

Класс помощник работы со строками, числами, массивами.

Версия 1.02
- Добавлен метод Helper::arrayExcludeTraceVendor
- Добавлен метод Helper::cacheRuntimeGet
- Добавлен метод Helper::cacheRuntimeSet
- Добавлен метод Helper::cryptDecode
- Добавлен метод Helper::cryptEncode
- Добавлен метод Helper::exceptionToString
- Добавлен метод Helper::hashXxh128
- Добавлен метод Helper::intervalAround
- Добавлен метод Helper::intervalCollection
- Добавлен метод Helper::ipInRange
- Добавлен метод Helper::pathClassName
- Добавлен метод Helper::pathRoot
- Добавлен метод Helper::sizeBytesToString
- Добавлен метод Helper::sizeStringToBytes
- Добавлен метод Helper::stringAddPrefix
- Добавлен метод Helper::stringAddSuffix
- Добавлен метод Helper::stringBreakByLength
- Добавлен метод Helper::stringChange
- Добавлен метод Helper::stringConcat
- Добавлен метод Helper::stringCopy
- Добавлен метод Helper::stringCut
- Добавлен метод Helper::stringDelete
- Добавлен метод Helper::stringEnds
- Добавлен метод Helper::stringLength
- Добавлен метод Helper::stringLower
- Добавлен метод Helper::stringMerge
- Добавлен метод Helper::stringPaste
- Добавлен метод Helper::stringReplace
- Добавлен метод Helper::stringSearchAll
- Добавлен метод Helper::stringSearchAny
- Добавлен метод Helper::stringStarts
- Добавлен метод Helper::stringUpper
- Добавлен метод Helper::telegramBreakMessage
- Добавлен метод Helper::timePeriodBetweenDatesToArray
- Добавлен метод Helper::timePeriodBetweenDatesToString
- Добавлен метод Helper::timeSecondsToArray
- Добавлен метод Helper::timeSecondsToString

Версия 1.01
- Добавлен метод Helper::intervalBetween
- Добавлен метод Helper::intervalOverlap
- Метод Helper::inInterval переименован в Helper::intervalBetween

Версия 1.00
- Добавлен метод Helper::inInterval

<hr style="border:1px solid black">

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
**[Helper::stringAddPrefix](./tests/HelperStringTrait/HelperStringAddPrefixTest.php)**(\$value, \$prefix, \$condition): string\
Добавляет в начало строки префикс при выполнении условия condition
```php
$string = Helper::stringAddPrefix('def', 'abc', true); // $string = 'abcdef'
```
---
**[Helper::stringAddSuffix](./tests/HelperStringTrait/HelperStringAddSuffixTest.php)**(\$value, \$suffix, \$condition): string\
Добавляет в конец строки суффикс при выполнении условия condition
```php
$string = Helper::stringAddSuffix('abc', 'def', true); // $string = 'abcdef'
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
**[Helper::stringReplace](./tests/HelperStringTrait/HelperStringReplaceTest.php)**(\$value, \$searches, \$replaces): string\
Заменяет подстроки в строке
```php
$string = Helper::stringReplace('abcd', 'd', 'x'); // $string = 'abcx'
$string = Helper::stringReplace('abcd', ['d' => 'x']); // $string = 'abcx'
```
---
**[Helper::stringSearchAll](./tests/HelperStringTrait/HelperStringSearchAllTest.php)**(\$value, ...\$searches): array\
Проверяет вхождение подстрок в строке и возвращает все найденные искомые значения
```php
$array = Helper::stringSearchAll('abcd', 'bc', 'd'); // $array = ['bc', 'd']
```
---
**[Helper::stringSearchAny](./tests/HelperStringTrait/HelperStringSearchAnyTest.php)**(\$value, ...\$searches): ?string\
Проверяет вхождение подстрок в строке и возвращает первое найденное искомое значение или null
```php
$string = Helper::stringSearchAny('abcd', 'bc'); // $string = 'bc'
$string = Helper::stringSearchAny('abcd', ['ab', 'bc']); // $string = 'ab'
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

# Helper

Класс помощник работы со строками, числами, массивами.

Версия 1.02
- Добавлен метод Helper::intervalAround
- Добавлен метод Helper::stringLength
- Добавлен метод Helper::stringUpper
- Добавлен метод Helper::stringLower
- Добавлен метод Helper::stringCopy
- Добавлен метод Helper::stringDelete
- Добавлен метод Helper::stringCut
- Добавлен метод Helper::stringPaste
- Добавлен метод Helper::stringChange
- Добавлен метод Helper::stringReplace
- Добавлен метод Helper::stringConcat
- Добавлен метод Helper::stringAddPrefix
- Добавлен метод Helper::stringAddSuffix
- Добавлен метод Helper::stringMerge
- Добавлен метод Helper::stringSearchAny
- Добавлен метод Helper::stringSearchAll
- Добавлен метод Helper::arrayExcludeTraceVendor
- Добавлен метод Helper::cacheRuntimeSet
- Добавлен метод Helper::cacheRuntimeGet
- Добавлен метод Helper::cryptEncode
- Добавлен метод Helper::cryptDecode

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
**[Helper::intervalBetween](./tests/HelperIntervalTrait/HelperIntervalBetweenTest.php)**(\$value, ...\$intervals): array\
Проверяет значение на вхождение в интервал(ы)
```php
$bool = (bool)Helper::intervalBetween(2, [1, 10]); // $bool = true
$bool = (bool)Helper::intervalBetween(12, '1..10, 13..20'); // $bool = false
$bool = (bool)Helper::intervalBetween('2025.01.02', '2025.01.01, 2025.01.03..2025.01.04'); // $bool = false
$bool = (bool)Helper::intervalBetween('2025.01.02', ['2025.01.01', '2025.01.03']); // $bool = true
```
---
**[Helper::intervalOverlap](./tests/HelperIntervalTrait/HelperIntervalOverlapTest.php)**(...\$intervals): array\
Проверяет пересечение интервалов
```php
$bool = (bool)Helper::intervalOverlap([1, 3], [2, 4]); // $bool = true
$bool = (bool)Helper::intervalOverlap('1..3', '2..4'); // $bool = false
```
---
**[Helper::intervalAround](./tests/HelperIntervalTrait/HelperIntervalAroundTest.php)**(\$value, \$min, \$max): int\
Возвращает число со смещением по кругу в заданном интервале
```php
$integer = Helper::intervalAround(3 + 1, 1, 3); // $integer = 1
$integer = Helper::intervalAround(1 - 1, 1, 3); // $integer = 3
```
---
**[Helper::stringLength](./tests/HelperStringTrait/HelperStringLengthTest.php)**(\$value): int\
Возвращает длину строки
```php
$integer = Helper::stringLength('123'); // $integer = 3
```
---
**[Helper::stringUpper](./tests/HelperStringTrait/HelperStringUpperTest.php)**(\$value): string\
Переводит строку в верхний регистр
```php
$string = Helper::stringUpper('abc'); // $string = 'ABC'
```
---
**[Helper::stringLower](./tests/HelperStringTrait/HelperStringLowerTest.php)**(\$value): string\
Переводит строку в нижний регистр
```php
$string = Helper::stringLower('ABC'); // $string = 'abc'
```
---
**[Helper::stringCopy](./tests/HelperStringTrait/HelperStringCopyTest.php)**(\$value, \$start, \$length): string\
Копирует подстроку из строки с позиции start длиной length
```php
$string = Helper::stringCopy('abc', 1, 1); // $string = 'b'
$string = Helper::stringCopy('abc', -2); // $string = 'bc'
```
---
**[Helper::stringDelete](./tests/HelperStringTrait/HelperStringDeleteTest.php)**(\$value, \$start, \$length): string\
Удаляет из строки с позиции start длиной length
```php
$string = Helper::stringDelete('abc', 1, 1); // $string = 'ac'
$string = Helper::stringDelete('abc', -2); // $string = 'a'
```
---
**[Helper::stringCut](./tests/HelperStringTrait/HelperStringCutTest.php)**(\&\$value, \$start, \$length): string\
Вырезает подстроку из строки с позиции start длиной length
```php
$source = 'abc';
$string = Helper::stringCut($source, 1, 1); // $source = 'ac', $string = 'b'
```
---
**[Helper::stringPaste](./tests/HelperStringTrait/HelperStringPasteTest.php)**(\&\$value, \$paste, \$start): string\
Вставляет подстроку в строку с позиции start
```php
$string = Helper::stringPaste('abc', 'd', 2); // $string = 'abdc'
```
---
**[Helper::stringChange](./tests/HelperStringTrait/HelperStringChangeTest.php)**(\&\$value, \$change, \$start): string\
Заменяет на подстроку в строке с позиции start
```php
$string = Helper::stringChange('abc', 'd', 2); // $string = 'abd'
$string = Helper::stringChange('abcefg', 'xyz', -1); // $string = 'abcefxyz'
```
---
**[Helper::stringReplace](./tests/HelperStringTrait/HelperStringReplaceTest.php)**(\$value, \$searches, \$replaces): string\
Заменяет подстроки в строке
```php
$string = Helper::stringReplace('abcd', 'd', 'x'); // $string = 'abcx'
$string = Helper::stringReplace('abcd', ['d' => 'x']); // $string = 'abcx'
```
---
**[Helper::stringPlural](./tests/HelperStringTrait/HelperStringPluralTest.php)**(\$value, \$plurals, \$includeValue): string\
Возвращает число с числительным названием
```php
$string = Helper::stringPlural(1, ['штук', 'штука', 'штуки']); // $string = '1 штука'
```
---
**[Helper::stringConcat](./tests/HelperStringTrait/HelperStringConcatTest.php)**(\$delimiter, ...\$values): string\
Соединяет значения values через разделитель delimiter
```php
$string = Helper::stringConcat(' ', 'Иванов', 'Иван', 'Иванович'); // $string = 'Иванов Иван Иванович'
$string = Helper::stringConcat(' ', ['Иванов', 'Иван', 'Иванович']); // $string = 'Иванов Иван Иванович'
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
**[Helper::stringMerge](./tests/HelperStringTrait/HelperStringMergeTest.php)**(...\$values): string\
Объединяет значения в одну строку с заменой предыдущих символов
```php
$string = Helper::stringMerge('abc', 'de'); // $string = 'dec'
```
---
**[Helper::stringSearchAny](./tests/HelperStringTrait/HelperStringSearchAnyTest.php)**(\$value, ...\$searches): array\
Проверяет вхождение подстрок в строке и возвращает первое найденное искомое значение
```php
$array = Helper::stringSearchAny('abcd', 'bc'); // $array = ['bc']
```
---
**[Helper::stringSearchAll](./tests/HelperStringTrait/HelperStringSearchAllTest.php)**(\$value, ...\$searches): array\
Проверяет вхождение подстрок в строке и возвращает все найденные искомые значения
```php
$array = Helper::stringSearchAll('abcd', 'bc', 'd'); // $array = ['bc', 'd']
```
---
**[Helper::arrayExcludeTraceVendor](./tests/HelperArrayTrait/HelperArrayExcludeTraceVendorTest.php)**(\$value, \$basePath): array\
Исключает из массива трассировки пакетные ошибки
```php
$array = Helper::arrayExcludeTraceVendor(debug_backtrace()); // $array = []
```
---
**[Helper::cacheRuntimeSet](./tests/HelperCacheTrait/HelperCacheRuntimeSetTest.php)**(\$key, \$value): void\
Сохраняет значение value в кеше по ключу key
```php
$array = Helper::cacheRuntimeSet('key', 'value'); // 
```
---
**[Helper::cacheRuntimeGet](./tests/HelperCacheTrait/HelperCacheRuntimeGetTest.php)**(\$key, \$default): mixed\
Возвращает значение из кеша по ключу key или возвращает значение по умолчанию default
```php
$mixed = Helper::cacheRuntimeGet('key', 'value'); // $mixed = 'value'
```
---
**[Helper::cryptEncode](./tests/HelperCryptTrait/HelperCryptEncodeTest.php)**(\$value, \$password, \$random): string\
Шифрует строку с паролем
```php
$string = Helper::cryptEncode('abc', 'password'); // $string = 'nh93432NibR3td26'
```
---
**[Helper::cryptDecode](./tests/HelperCryptTrait/HelperCryptDecodeTest.php)**(\$value, \$password): string\
Дешифрует строку с паролем
```php
$string = Helper::cryptDecode('nh93432NibR3td26', 'password'); // $string = 'abc'
```
---

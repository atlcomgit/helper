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
##### [arrayDeleteKeys(\$value, ...\$searches): array](./tests/HelperArrayTrait/HelperArrayDeleteKeysTest.php)
Возвращает массив с удаленными ключами
```php
$array = Helper::arrayDeleteKeys(['a' => 1, 'b' => 2], 'a'); // $array = ['b' => 2]
```
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
##### [arrayUnDot(\$value): array](./tests/HelperArrayTrait/HelperArrayUnDotTest.php)
Возвращает многомерный массив из одномерного
```php
$array = Helper::arrayUnDot(['a.b' => 2, 'a.c' => 3]); // $array = ['a' => ['b' => 2, 'c' => 3]]
```
---

#### Bracket
Работа со скобками/тегами

---
##### [bracketChange(\$value, \$left, \$right, \$index): string](./tests/HelperBracketTrait/HelperBracketChangeTest.php)
Возвращает строку с заменой содержимого внутри блока скобок под индексом index на строку change
```php
$string = Helper::bracketChange('(a)(b)', '(', ')', 0, 'c'); // $string = '(c)(b)'
```
---
##### [bracketCopy(\$value, \$left, \$right, \$index): string](./tests/HelperBracketTrait/HelperBracketCopyTest.php)
Возвращает содержимое внутри блока скобок под индексом index
```php
$string = Helper::bracketCopy('(a)(b)', '(', ')', 0); // $string = 'a'
```
---
##### [bracketCount(\$value, \$left, \$right): int](./tests/HelperBracketTrait/HelperBracketCountTest.php)
Возвращает количество блоков скобок
```php
$integer = Helper::bracketCount('(a)(b)', '(', ')'); // $integer = 2
```
---
##### [bracketDelete(\$value, \$left, \$right, \$index): string](./tests/HelperBracketTrait/HelperBracketDeleteTest.php)
Возвращает строку с удалением блока скобок под индексом index
```php
$string = Helper::bracketDelete('(a)(b)', '(', ')', 0); // $string = '(b)'
```
---
##### [bracketReplace(\$value, \$left, \$right, \$index, \$replace): string](./tests/HelperBracketTrait/HelperBracketReplaceTest.php)
Возвращает строку с заменой блока скобок под индексом index на строку replace
```php
$string = Helper::bracketReplace('(a)(b)', '(', ')', 0, '(c)'); // $string = '(c)(b)'
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
[cacheRuntimeClear(): void](./tests/HelperCacheTrait/HelperCacheRuntimeClearTest.php)
Очищает весь кеш
```php
Helper::cacheRuntimeClear(); // 
```
---
[cacheRuntimeDelete(\$key): void](./tests/HelperCacheTrait/HelperCacheRuntimeDeleteTest.php)
Удаляет значение из кеша по ключу key
```php
Helper::cacheRuntimeDelete('key'); // 
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

#### Cast
Работа с типами

---
##### [castToArray(\$value): ?array](./tests/HelperCastTrait/HelperCastToArrayTest.php)
Возвращает преобразование значения к массиву или null
```php
$array = Helper::castToArray(1); // $array = [1]
```
---
##### [castToBool(\$value): ?bool](./tests/HelperCastTrait/HelperCastToBoolTest.php)
Возвращает преобразование значения к логическому типу или null
```php
$boolean = Helper::castToBool(1); // $boolean = true
```
---
##### [castToCallable(\$value): ?callable](./tests/HelperCastTrait/HelperCastToCallableTest.php)
Возвращает преобразование значения к статичной функции с результатом самого значения или null
```php
$callable = Helper::castToCallable(1); // $callable() = 1
```
---
##### [castToFloat(\$value): ?float](./tests/HelperCastTrait/HelperCastToFloatTest.php)
Возвращает преобразование значения к вещественному типу или null
```php
$float = Helper::castToFloat(1); // $float = 1.0
```
---
##### [castToJson(\$value): ?string](./tests/HelperCastTrait/HelperCastToJsonTest.php)
Возвращает преобразование значения к строке json или null
```php
$json = Helper::castToJson(1); // $json = '"1"'
```
---
##### [castToInt(\$value): ?int](./tests/HelperCastTrait/HelperCastToIntTest.php)
Возвращает преобразование значения к целочисленному типу или null
```php
$integer = Helper::castToInt('1'); // $integer = 1
```
---
##### [castToObject(\$value): ?object](./tests/HelperCastTrait/HelperCastToObjectTest.php)
Возвращает преобразование значения к объекту или null
```php
$object = Helper::castToObject(1); // $object = (object)[1]
```
---
##### [castToString(\$value): ?string](./tests/HelperCastTrait/HelperCastToStringTest.php)
Возвращает преобразование значения к строковому типу или null
```php
$string = Helper::castToString(1); // $string = '1'
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
Работа с шифрованием

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
##### [dateDayName(\$value): string](./tests/HelperDateTrait/HelperDateDayNameTest.php)
Возвращает название дня недели переданного значения
```php
$string = Helper::dateDayName('31.12.2025'); // $string = 'среда'
```
---
##### [dateFromString(\$value): ?Carbon](./tests/HelperDateTrait/HelperDateFromStringTest.php)
Возвращает объект Carbon с распознанной датой или null
```php
$date = Helper::dateFromString('в следующий понедельник'); // $date->format('d.m.Y') = 'XX.XX.XXXX'
$date = Helper::dateFromString('через 2 месяца'); // $date->format('d.m.Y') = 'XX.XX.XXXX'
```
---
##### [dateToString(\$value, \$declension): ?Carbon](./tests/HelperDateTrait/HelperDateToStringTest.php)
Возвращает дату прописью на русском языке с учетом склонения по падежу
```php
$string = Helper::dateToString(Carbon::parse('01.02.2025')); // $string = 'первое февраля две тысячи двадцать пятый год'
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
$$boolean$ = Helper::envDev(); // $boolean = true/false
```
---
##### [envGet(\$value, \$file): mixed](./tests/HelperEnvTrait/HelperEnvGetTest.php)
Возвращает значение ключа из файла .env
```php
$mixed = Helper::envDev('APP_ENV', '.env.example'); // $mixed = mixed
```
---
##### [envLocal(): bool](./tests/HelperEnvTrait/HelperEnvLocalTest.php)
Проверяет окружение приложения на локальное и возвращает true/false
```php
$boolean = Helper::envLocal(); // $boolean = true/false
```
---
##### [envProd(): bool](./tests/HelperEnvTrait/HelperEnvProdTest.php)
Проверяет окружение приложения на боевое и возвращает true/false
```php
$boolean = Helper::envProd(); // $boolean = true/false
```
---
##### [envStage(): bool](./tests/HelperEnvTrait/HelperEnvStageTest.php)
Проверяет окружение приложения на пред боевое и возвращает true/false
```php
$boolean = Helper::envStage(); // $boolean = true/false
```
---
##### [envTest(): bool](./tests/HelperEnvTrait/HelperEnvTestTest.php)
Проверяет окружение приложения на тестовое и возвращает true/false
```php
$boolean = Helper::envTest(); // $boolean = true/false
```
---
##### [envTesting(): bool](./tests/HelperEnvTrait/HelperEnvTestingTest.php)
Проверяет окружение приложения на авто-тестирование и возвращает true/false
```php
$boolean = Helper::envTesting(); // $boolean = true/false
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

#### Fake
Работа с генерацией данных

---
##### [fakeEmail(): string](./tests/HelperFakeTrait/HelperFakeEmailTest.php)
Возвращает случайно сгенерированный email
```php
$string = Helper::fakeEmail(); // $string = 'abc@asd.ru'
```
---
##### [fakeIp4(): string](./tests/HelperFakeTrait/HelperFakeIp4Test.php)
Возвращает случайно сгенерированный ip адрес v4
```php
$string = Helper::fakeIp4(); // $string = '111.222.33.44'
```
---
##### [fakeIp6(): string](./tests/HelperFakeTrait/HelperFakeIp6Test.php)
Возвращает случайно сгенерированный ip адрес v6
```php
$string = Helper::fakeIp6(); // $string = '2001:0db8:0058:0074:1c60:2c18:1025:2b5f'
```
---
##### [fakeName(\$locale, \$surnames, \$firstNames, \$patronymics): string](./tests/HelperFakeTrait/HelperFakeNameTest.php)
Возвращает случайно сгенерированное ФИО
```php
$string = Helper::fakeName(); // $string = 'Иванов Иван Иванович'
```
---
##### [fakePassword(): string](./tests/HelperFakeTrait/HelperFakePasswordTest.php)
Возвращает случайно сгенерированный пароль
```php
$string = Helper::fakePassword(); // $string = 'af3usn5f'
```
---
##### [fakePhone(): string](./tests/HelperFakeTrait/HelperFakePhoneTest.php)
Возвращает случайно сгенерированный номер телефона с/без кодом страны
```php
$string = Helper::fakePhone(); // $string = '79645831465'
```
---
##### [fakeUrl(\$protocols, \$domainNames, \$domainZones, \$paths, \$queries, \$anchors): string](./tests/HelperFakeTrait/HelperFakeUrlTest.php)
Возвращает случайно сгенерированный url
```php
$string = Helper::fakeUrl(); // $string = 'http://abc.com/def?a=b#xyz'
```
---
##### [fakeUuid4(): string](./tests/HelperFakeTrait/HelperFakeUuid4Test.php)
Возвращает случайно сгенерированный UUID v4
```php
$string = Helper::fakeUuid4(); // $string = 'f1f27725-7a51-4581-a6b8-45f236e50d81'
```
---
##### [fakeUuid7(): string](./tests/HelperFakeTrait/HelperFakeUuid7Test.php)
Возвращает случайно сгенерированный UUID v7
```php
$string = Helper::fakeUuid7(); // $string = '019668c4-7b6c-7333-9563-d10b583c4046'
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
Возвращает массив найденных масок подсетей, в которые входит ip адрес
```php
$array = Helper::ipInRange('192.168.1.1', '192.168.1.0/24'); // $array = ['192.168.1.0/24']
```
---

#### Json
Работа с json строками

---
##### [jsonFlags(): int](./tests/HelperJsonTrait/HelperJsonFlagsTest.php)
Возвращает флаг для формирования json строки
```php
$integer = Helper::jsonFlags(); // $integer = 3146048
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
##### [numberCalculate(\$value, \$precision): string](./tests/HelperNumberTrait/HelperNumberCalculateTest.php)
Возвращает строку с результатом калькуляции больших числовых значений в строке
```php
$string = Helper::numberCalculate('1 + 2'); // $string = '3'
```
---
##### [numberDecimalDigits(\$value): int](./tests/HelperNumberTrait/HelperNumberDecimalDigitsTest.php)
Возвращает количество знаков дробной части числа
```php
$integer = Helper::numberDecimalDigits(1.123); // $integer = 3
```
---
##### [numberFormat(\$value, \$decimals, \$separator): string](./tests/HelperNumberTrait/HelperNumberFormatTest.php)
Возвращает число в виде строки с форматированием
```php
$string = Helper::numberFormat(1000.123); // $string = '1 000,123'
```
---
##### [numberFromString(\$value): int|float](./tests/HelperNumberTrait/HelperNumberFromStringTest.php)
Возвращает число из строки с числом прописью на русском языке
```php
$integer = Helper::numberFromString('сто двадцать три'); // $integer = 123
```
---
##### [numberSwap(\$value1, \$value2): void](./tests/HelperNumberTrait/HelperNumberSwapTest.php)
Меняет местами значения value1 и value2
```php
$integer1 = 1; $integer2 = 2;
Helper::numberSwap($integer1, $integer2); // $integer1 = 2, $integer2 = 1
```
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

#### Phone
Работа с телефонами

---
##### [phoneFormat(\$value, \$countryNumber, \$format): string](./tests/HelperPhoneTrait/HelperPhoneFormatTest.php)
Возвращает отформатированный номер телефона
```php
$string = Helper::phoneFormat('79001112233'); // $string = '+7 (900) 111-22-33'
```
---
##### [phoneNumber(\$value, \$countryNumber): string](./tests/HelperPhoneTrait/HelperPhoneNumberTest.php)
Возвращает только цифры номера телефона
```php
$string = Helper::phoneNumber('+7 (900) 111-22-33'); // $string = '79001112233'
```
---

#### Regexp
Работа с валидацией

---
##### [reflectionArgumentAttributes(\$class, \$method, \$argument): array](./tests/HelperReflectionTrait/HelperReflectionArgumentAttributesTest.php)
Возвращает массив аттрибутов у аргументов метода класса
```php
class Example {public function methodName(#[ExampleAttribute] $paramName)}
$array = Helper::reflectionArgumentAttributes(Example::class, 'methodName', 'paramName'); // $array = [['attribute' => [...], 'arguments' => [...], 'instance' => {...}]]
```
---
##### [reflectionClassAttributes(\$class): array](./tests/HelperReflectionTrait/HelperReflectionClassAttributesTest.php)
Возвращает массив аттрибутов у класса
```php
#[ExampleAttribute]
class Example {}
$array = Helper::reflectionClassAttributes(Example::class); // $array = [['attribute' => [...], 'arguments' => [...], 'instance' => {...}]]
```
---
##### [reflectionMethodAttributes(\$class, \$method): array](./tests/HelperReflectionTrait/HelperReflectionMethodAttributesTest.php)
Возвращает массив аттрибутов у методов класса
```php
#[ExampleAttribute]
class Example {#[ExampleAttribute] public function methodName($param)}
$array = Helper::reflectionMethodAttributes(Example::class, 'methodName'); // $array = [['attribute' => [...], 'arguments' => [...], 'instance' => {...}]]
```
---
##### [reflectionPhpDoc(\$class): array](./tests/HelperReflectionTrait/HelperReflectionPhpDocTest.php)
Возвращает массив с разложенными параметрами из всех phpdoc блоков класса
```php
/** description class*/
class Example {
	/** @var string */ public string $propertyName
	/** description method */ public function methodName() {}
}
$array = Helper::reflectionPhpDoc(Example::class); // $array = [...]
```
---
##### [reflectionPropertyAttributes(\$class, \$property): array](./tests/HelperReflectionTrait/HelperReflectionPropertyAttributesTest.php)
Возвращает массив аттрибутов у свойств класса
```php
class Example {#[ExampleAttribute] public string $propertyName}
$array = Helper::reflectionPropertyAttributes(Example::class, 'propertyName'); // $array = [['attribute' => [...], 'arguments' => [...], 'instance' => {...}]]
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
##### [regexpValidateDate(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidateDateTest.php)
Проверяет значение строки на формат даты
```php
$boolean = Helper::regexpValidateDate('01.01.2025'); // $boolean = true
```
---
##### [regexpValidateEmail(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidateEmailTest.php)
Проверяет значение строки на формат электронной почты
```php
$boolean = Helper::regexpValidateEmail('Test.example_1@domain.com'); // $boolean = true
```
---
##### [regexpValidateIp4(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidateIp4Test.php)
Проверяет значение строки на формат ip адреса v4
```php
$boolean = Helper::regexpValidateIp4('111.222.33.44'); // $boolean = true
```
---
##### [regexpValidateIp6(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidateIp6Test.php)
Проверяет значение строки на формат ip адреса v6
```php
$boolean = Helper::regexpValidateIp6('2001:0db8:0058:0074:1c60:2c18:1025:2b5f'); // $boolean = true
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
##### [regexpValidateUrl(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidateUrlTest.php)
Проверяет значение строки на формат url
```php
$boolean = Helper::regexpValidateUrl('http://abc.com/def?klm#xyz'); // $boolean = true
```
---
##### [regexpValidateUuid4(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidateUuid4Test.php)
Проверяет значение строки на формат идентификатора uuid v4
```php
$boolean = Helper::regexpValidateUuid4('04d19f50-2fab-417a-815d-306b6a6f67ec'); // $boolean = true
```
---
##### [regexpValidateUuid7(\$value): bool](./tests/HelperRegexpTrait/HelperRegexpValidateUuid7Test.php)
Проверяет значение строки на формат идентификатора uuid v7
```php
$boolean = Helper::regexpValidateUuid7('019668a4-02a1-7f64-9d90-52760464b9ce'); // $boolean = true
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
##### [stringSegment(\$value): string](./tests/HelperStringTrait/HelperStringSegmentTest.php)
Возвращает строку с разбиением слитных слов и цифр
```php
$string = Helper::stringSegment('abc2defXyz'); // $string = 'abc 2 def Xyz'
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
##### [timeFromSeconds(\$value): string](./tests/HelperTimeTrait/HelperTimeFromSecondsTest.php)
Возвращает строку вида ##:##:## из переданного количества секунд
```php
$string = Helper::timeFromSeconds(3661); // $string = '01:01:01'
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

#### Translit
Работа с транслитерацией

---
##### [translitFromSlug(\$value, \$slugSeparator = '_'): string](./tests/HelperTranslitTrait/HelperTransformToArrayTest.php)
Возвращает строку из slug
```php
$string = Helper::translitFromSlug('abv_gde_123'); // $string = 'абв где 123'
```
---
##### [translitString(\$value, \$direction): string](./tests/HelperTranslitTrait/HelperTranslitStringTest.php)
Возвращает транслитерацию строки
```php
$string = Helper::translitString('абвгд'); // $string = 'abvgd'
```
---
##### [translitToSlug(\$value, \$slugSeparator = '_'): string](./tests/HelperTranslitTrait/HelperTranslitToSlugTest.php)
Возвращает транслитерацию строки
```php
$string = Helper::translitToSlug('абв Где!.123'); // $string = 'abv_gde_123'
```
---

#### Url
Работа с url адресом

---
##### [urlCreate(\$scheme, \$host, \$path, \$query): array](./tests/HelperUrlTrait/HelperUrlCreateTest.php)
Возвращает строку адреса url
```php
$string = Helper::urlCreate(scheme: 'https', host: 'a.com', path: 'b', query: ['c' => 1]); // $string = 'https://a.com/b?c=1'
```
---
##### [urlDomainDecode(\$value): string](./tests/HelperUrlTrait/HelperUrlDomainDecodeTest.php)
Возвращает декодированный русский домен вида xn--
```php
$string = Helper::urlDomainDecode('xn--e1afmkfd.xn--p1ai'); // $string = 'пример.рф'
```
---
##### [urlDomainEncode(\$value): string](./tests/HelperUrlTrait/HelperUrlDomainEncodeTest.php)
Возвращает кодированный русский домен в виде xn--
```php
$string = Helper::urlDomainEncode('пример.рф'); // $string = 'xn--e1afmkfd.xn--p1ai'
```
---
##### [urlParse(\$value): array](./tests/HelperUrlTrait/HelperUrlParseTest.php)
Возвращает массив частей адреса url
```php
$array = Helper::urlParse('http://a.com/d/?e=f'); // $array = ['scheme' => 'http', 'host' => 'a.com', 'path' => '/d', 'query' => ['e' => 'f']]
```
---

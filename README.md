# Helper

Класс помощник работы со строками, числами, массивами.

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

**[Helper::intervalBetween](./tests/HelperIntervalTrait/HelperIntervalBetweenTest.php)**(mixed \$value, mixed ...\$intervals): bool\
Проверяет значение на вхождение в интервал(ы)

Пример
```php
$bool = (bool)Helper::intervalBetween(2, [1, 10]); // $bool = true
$bool = (bool)Helper::intervalBetween(12, '1..10, 13..20'); // $bool = false
$bool = (bool)Helper::intervalBetween('2025.01.02', '2025.01.01, 2025.01.03..2025.01.04'); // $bool = false
$bool = (bool)Helper::intervalBetween('2025.01.02', ['2025.01.01', '2025.01.03']); // $bool = true

```

---


**[Helper::intervalOverlap](./tests/HelperIntervalTrait/HelperIntervalBetweenTest.php)**(mixed \$value, mixed ...\$intervals): bool\
Проверяет пересечение интервалов

Пример
```php
$bool = (bool)Helper::intervalOverlap([1, 3], [2, 4]); // $bool = true
$bool = (bool)Helper::intervalOverlap('1..3', '2..4'); // $bool = false

```

---


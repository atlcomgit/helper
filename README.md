# Helper

Класс помощник работы со строками, числами, массивами.

Версия 1.01
- Добавлен метод Helper::intervalBetween

Версия 1.00
- Добавлен метод Helper::inInterval

<hr style="border:1px solid black">

## Установка

```
composer require atlcom/helper
```

## Описание методов

---

**[Helper::inInterval](./tests/HelperString/InIntervalTest.php)**(mixed \$value, mixed ...\$intervals): bool\
Проверяет значение на вхождение в интервал(ы)

Пример
```php
$bool = Helper::intervalBetween(2, [1, 10]); // $bool = true
$bool = Helper::intervalBetween(12, '1..10, 13..20'); // $bool = false
$bool = Helper::intervalBetween('2025.01.02', '2025.01.01, 2025.01.03..2025.01.04'); // $bool = false
$bool = Helper::intervalBetween('2025.01.02', ['2025.01.01', '2025.01.03']); // $bool = true

```

---


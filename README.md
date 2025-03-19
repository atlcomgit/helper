# Helper

Класс помощник работы со строками, числами, массивами.
Версия 1.00

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
Helper::inInterval(2, 2)); // true
Helper::inInterval(2, 1, 2, 3); // true
Helper::inInterval(2, 1, 3); // false

Helper::inInterval(2, [1, 10]); // true
Helper::inInterval(2, [1, 10], [11, 20]); // true
Helper::inInterval(12, [1, 10], [11, 20]); // true
Helper::inInterval(12, '1..10, 11..20'); // true
Helper::inInterval(12, '1..10, 13..20'); // false

Helper::inInterval('2025.01.02', '2025.01.01..2025.01.03'); // true
Helper::inInterval('2025.01.02', '2025.01.01, 2025.01.02, 2025.01.03'); // true
Helper::inInterval('2025.01.02', '2025.01.01, 2025.01.03, 2025.01.04'); // false

```

---


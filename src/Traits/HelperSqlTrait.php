<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Consts\HelperConsts as Consts;

/**
 * Трейт для sql запросами
 * @mixin \Atlcom\Helper
 */
trait HelperSqlTrait
{
    /**
     * Проверяет запрос на наличие операторов изменения INSERT, UPDATE, DELETE, TRUNCATE
     * @see ../../tests/HelperSqlTrait/HelperSqlHasWriteTest.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function sqlHasWrite(?string $value): bool
    {
        // Удаляем многострочные комментарии /* ... */
        $sql = preg_replace('~/\*.*?\*/~s', '', $value ?? '') ?? '';

        // Удаляем однострочные комментарии --
        $sql = preg_replace('/--.*$/m', '', $sql) ?? '';

        // Разбиваем SQL по ;
        $queries = array_filter(array_map('trim', explode(';', strtolower($sql))));

        // Команды, считающиеся пишущими/опасными
        $writeCommands = [
            'insert',
            'update',
            'delete',
            'truncate',
            'drop table',
            'drop database',
            'alter table',
        ];

        foreach ($queries as $query) {
            foreach ($writeCommands as $command) {
                if (preg_match('/^' . preg_quote($command, '/') . '\b/', $query)) {
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * Подставляет значения из массива $bindings вместо плейсхолдеров ? в запросе
     * @see ../../tests/HelperSqlTrait/HelperSqlBindingsTest.php
     *
     * @param string|null $value
     * @param array $bindings
     * @return string
     */
    public static function sqlBindings(?string $value, ?array $bindings = []): string
    {
        $bindings ??= [];

        // Удаляем многострочные комментарии /* ... */
        $sql = preg_replace('~/\*.*?\*/~s', '', $value ?? '') ?? '';
        // Удаляем однострочные комментарии --
        $sql = preg_replace('/--.*$/m', '', $sql) ?? '';

        // Считаем количество плейсхолдеров
        $placeholders = substr_count($value ?? '', '?');
        $bindingsFilled = $bindings;
        if ($placeholders > count($bindings)) {
            // Заполняем недостающие значения null
            $bindingsFilled = array_merge($bindings, array_fill(0, $placeholders - count($bindings), null));
        }

        return static::stringReplace(
            vsprintf(
                static::stringReplace($value, [
                    '%' => '!PERCENT!',
                    '?' => '%s',
                    '\\\"' => '!DOUBLE_QUOTE!',
                    '\\\'' => '!SINGLE_QUOTE!',
                    '"' => '',
                    "'" => "",
                ]),
                array_map(
                    callback: static fn ($binding) => match (true) {
                        is_null($binding) => 'NULL',
                        is_bool($binding) => $binding ? '1' : '0',
                        is_numeric($binding) => $binding,
                        is_string($binding) => "'" . addslashes($binding) . "'",
                        is_array($binding), is_object($binding) => json_encode($binding, static::jsonFlags()),

                        default => $binding,
                    },
                    array: $bindingsFilled,
                ),
            ),
            [
                '!DOUBLE_QUOTE!' => '\\\"',
                "!SINGLE_QUOTE!" => '\\\'',
                '!PERCENT!' => '%',
            ],
        );
    }


    /**
     * Возвращает массив используемых названий полей в запросе
     * @see ../../tests/HelperSqlTrait/HelperSqlFieldsTest.php
     *
     * @param string|null $value
     * @param bool $sort - сортирует массив полей
     * @return array
     */
    public static function sqlFields(?string $value, bool $sort = true): array
    {
        if (empty($value) || !is_string($value)) {
            return [];
        }

        $result = [];
        $sqlExtractNames = static::sqlExtractNames($value, $sort);
        $fields = static::arraySearchKeys($sqlExtractNames, 'databases.*.tables.*.fields.*');

        foreach ($fields as $key => $v) {
            $keys = explode('.', $key);
            $db = $keys[1] ?? '';
            $table = $keys[3] ?? '';
            !$table ?: $result[] = static::stringConcat('.', $db, $table, $v);
        }

        return array_values(array_filter(array_unique($result)));
    }


    /**
     * Возвращает массив используемых таблиц в запросе
     * @see ../../tests/HelperSqlTrait/HelperSqlTablesTest.php
     *
     * @param string|null $value
     * @return array<string>
     */
    public static function sqlTables(?string $value): array
    {
        if (empty($value) || !is_string($value)) {
            return [];
        }

        $result = [];
        $sqlExtractNames = static::sqlExtractNames($value);
        $tables = static::arraySearchKeys($sqlExtractNames, 'databases.*.tables.*');

        foreach (array_keys($tables) as $key) {
            $keys = explode('.', $key);
            $db = $keys[1] ?? '';
            $table = $keys[3] ?? '';
            !$table ?: $result[] = static::stringConcat('.', $db, $table);
        }

        return array_values(array_filter(array_unique($result)));
    }


    /**
     * Возвращает массив разобранного запроса на используемые названия баз данных, таблиц, полей
     * @see ../../tests/HelperSqlTrait/HelperSqlExtractNamesTest.php
     *
     * @param string|null $value
     * @param bool $sort - сортирует массив полей
     * @return array
     */
    public static function sqlExtractNames(?string $value, bool $sort = true): array
    {
        // Удаляем комментарии и нормализуем SQL
        $sql = preg_replace('/--.*$/m', '', $value ?? '') ?? '';
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql) ?? '';
        $sql = preg_replace('/\[(\w+)\]/', '$1', $sql) ?? '';
        $sql = preg_replace('/`(\w+)`/', '$1', $sql) ?? '';

        // 1. Собираем все элементы
        $elements = [];
        $reserved = [
            ...array_map(static fn ($word) => static::stringLower($word), Consts::SQL_RESERVED_WORDS),
            ...array_map(static fn ($word) => static::stringUpper($word), Consts::SQL_RESERVED_WORDS),
        ];

        // Таблицы и алиасы
        preg_match_all('/(?:UPDATE|FROM|JOIN|TRUNCATE|DROP\s+DATABASE)\s+(?:(\w+)\.)?(\w+)(?:\s+(?:AS\s+)?(\w+))?/i', $sql, $tableMatches, PREG_SET_ORDER);
        foreach ($tableMatches as $match) {
            $database = $match[1] ?? null;
            $table = $match[2];
            $alias = (!in_array($match[3] ?? '', $reserved) ? ($match[3] ?? '') : '') ?: $match[2];
            if (static::stringSearchAny($match[0], '*DROP*DATABASE*')) {
                $database = $table;
                $table = $alias = '';
            }
            (in_array($database, $reserved) || in_array($table, $reserved))
                ?: $elements[] = [
                    'type' => 'table',
                    'database' => $database,
                    'name' => $table,
                    'alias' => $alias,
                ];
        }

        // preg_match_all('/(?:INSERT\s+INTO|INSERT)\s+(?:(\w+)\.)?(\w+)(?:\s+(?:AS\s+)?(\w+))?/i', $sql, $tableMatches, PREG_SET_ORDER);
        preg_match_all('/(?:INSERT\s+INTO|INSERT)\s+(?:(?:"|`)?(\w+)(?:"|`)?\.)?(?:"|`)?(\w+)(?:"|`)?(?:\s+(?:AS\s+)?(?:"|`)?(\w+)(?:"|`)?)?/i', $sql, $tableMatches, PREG_SET_ORDER);
        foreach ($tableMatches as $match) {
            $database = $match[1] ?? null;
            $table = $match[2];
            $alias = (!in_array($match[3] ?? '', $reserved) ? ($match[3] ?? '') : '') ?: $match[2];
            (in_array($database, $reserved) || in_array($table, $reserved))
                ?: $elements[] = [
                    'type' => 'table',
                    'database' => $database,
                    'name' => $table,
                    'alias' => $alias,
                ];
        }

        // Разбиваем на отдельные запросы по ; и \n
        $queries = preg_split('/[;\n]+/', $sql);
        // Массив паттернов: [регулярка, номер группы]
        $patterns = [
            // группа базы данных
            ['/^drop\s+database\b/i', null],

            // группа таблиц
            ['/^drop\s+table\s+(?:if\s+exists\s+)?([`"\[\]]?[\w.]+[`"\]\[]?)/i', 1],
            ['/^alter\s+table\s+([`"\[\]]?[\w.]+[`"\]\[]?)/i', 1],
            ['/^truncate\s+table\s+([`"\[\]]?[\w.]+[`"\]\[]?)/i', 1],
            ['/^insert\s+into\s+([`"\[\]]?[\w.]+[`"\]\[]?)/i', 1],
            ['/^update\s+([`"\[\]]?[\w.]+[`"\]\[]?)/i', 1],
            ['/^delete\s+from\s+([`"\[\]]?[\w.]+[`"\]\[]?)/i', 1],
            ['/from\s+([`"\[\]]?[\w.]+[`"\]\[]?)/i', 1], // SELECT ... FROM (ищем from не только в начале)
        ];
        foreach ($queries as $query) {
            $query = trim($query);
            if ($query === '') {
                continue;
            }

            foreach ($patterns as [$pattern, $group]) {
                if ($group === null) {
                    if (preg_match($pattern, $query)) {
                        // drop database — пропускаем
                        continue 2;
                    }

                } else if (preg_match($pattern, $query, $m)) {
                    $database = null;
                    $table = trim($m[$group], '`"[] ');
                    if (count($tableSplit = static::stringSplit($table, '.')) > 1) {
                        $database = $tableSplit[0];
                        $table = $tableSplit[1];
                    }

                    (
                        in_array($database, $reserved)
                        || in_array($table, $reserved)
                        || in_array($table, array_values(static::arraySearchKeys($elements, '*.name')))
                    )
                        ?: $elements[] = [
                            'type' => 'table',
                            'database' => $database,
                            'name' => $table,
                            'alias' => $table,
                        ];
                    break;
                }
            }
        }


        // Строим структуру результата
        $result = ['databases' => []];
        $tableAliases = [];
        $mainDatabase = '';
        $mainTable = '';

        // Сначала обрабатываем таблицы
        foreach ($elements as $element) {
            if ($element['type'] === 'table') {
                $database = $element['database'];
                $table = $element['name'];
                $alias = $element['alias'];

                if ($database && !$mainDatabase) {
                    $mainDatabase = $database;
                }

                $tableAliases[$alias] = [
                    'database' => $database,
                    'table' => $table,
                ];

                if ($table !== $alias) {
                    $tableAliases[$table] = [
                        'database' => $database,
                        'table' => $table,
                    ];
                }

                $targetDb = $database ?: $mainDatabase;
                if (!isset($result['databases'][$targetDb])) {
                    $result['databases'][$targetDb] = ['tables' => []];
                }
                if (!isset($result['databases'][$targetDb]['tables'][$table])) {
                    $result['databases'][$targetDb]['tables'][$table] = ['fields' => []];
                }
            }
        }

        // Поля с указанием таблицы
        preg_match_all('/(\w+)[\"\'\`]{0,1}\.[\"\'\`]{0,1}(\w+|\*)/', $sql, $fieldMatches, PREG_SET_ORDER);
        foreach ($fieldMatches as $match) {
            $db = explode('.', $match[0])[0];
            $tableAlias = $match[1];
            $field = $match[2];
            (
                in_array($tableAlias, $reserved)
                || in_array($field, $reserved)
                || isset($result['databases'][$db]['tables'][$tableAlias])
                || isset($result['databases'][$db]['tables'][$field])
            )
                ?: $elements[] = [
                    'type' => 'field',
                    'table_alias' => $tableAlias,
                    'name' => $field,
                ];
        }

        // Поля без указания таблицы (в подзапросах)
        // preg_match_all('/\b(?!' . implode('|', $reserved) . ')(\w+)\b(?!\.)/i', $sql, $simpleFields);
        // Получаем все слова, не содержащие точку и не входящие в зарезервированные слова
        // preg_match_all('/\b(?!' . implode('|', $reserved) . ')\w+\b(?!\s*\.)/i', $sql, $simpleFields);
        preg_match_all(
            '/\b(?:SELECT|WHERE|GROUP BY|ORDER BY|HAVING|ON|AND|OR|IN)\s+([a-z0-9_\.][\w]*|[\*])/i',
            static::stringReplace($sql, ['(' => ' ', ')' => ' ']),
            $simpleFields,
        );
        foreach ($simpleFields[1] as $field) {
            (
                !preg_match('/^([a-z\_\*]+)$/i', $field)
                || in_array($field, $reserved)
                || in_array($field, array_keys($tableAliases))
            )
                ?: $elements[] = [
                    'type' => 'simple_field',
                    'database' => null,
                    'table' => null,
                    'name' => $field,
                ];
        }

        // insert into
        $a = 0;
        preg_match_all('/\bINSERT\s+INTO\s+[^\(]+\(\s*([^)]+)\)/i', $sql, $simpleFields);
        foreach ($simpleFields[1] ?? [] as $fields) {
            foreach (explode(',', $fields) as $field) {
                // preg_match_all('/(?:INSERT\s+INTO|INSERT)\s+(?:(\w+)\.)?(\w+)(?:\s+(?:AS\s+)?(\w+))?/i', $simpleFields[0][$a] ?? '', $tableMatches, PREG_SET_ORDER);
                preg_match_all('/(?:INSERT\s+INTO|INSERT)\s+(?:(?:"|`)?(\w+)(?:"|`)?\.)?(?:"|`)?(\w+)(?:"|`)?(?:\s+(?:AS\s+)?(?:"|`)?(\w+)(?:"|`)?)?/i', $simpleFields[0][$a] ?? '', $tableMatches, PREG_SET_ORDER);
                $db = $tableMatches[0][1] ?? '';
                $table = $tableMatches[0][2] ?? '';
                $field = trim($field, " \r\n\t\"\'");
                (
                    in_array($db, $reserved)
                    || in_array($table, $reserved)
                    || in_array($field, $reserved)
                    || in_array($field, array_keys($tableAliases))
                )
                    ?: $elements[] = [
                        'type' => 'simple_field',
                        'database' => $db,
                        'table' => $table,
                        'name' => $field,
                    ];
            }
            $a++;
        }

        $a = 0; //level=1, message = 'text' WHERE id IN NOT NULL OR deleted_at IS NULL
        preg_match_all('/UPDATE\s+(?:\w+\.)?\w+\s+SET\s+([^=]+=[^,]+(?:,\s*[^=]+=[^,]+)*)(?=\s+WHERE\s+|;|\s*$)/i', $sql, $simpleFields);
        foreach ($simpleFields[1] ?? [] as $fields) {
            // (?:^|\bUPDATE\b|\bSET\b|\bWHERE\b|\bIN\b|\bOR\b)(.*?)(?=\bUPDATE\b|\bSET\b|\bWHERE\b|\bIN\b|\bOR\b|$)
            // $fields = static::arrayTrimValues(static::stringSplit($fields, [...$reserved, ...Consts::SQL_OPERATORS]));

            $words = static::stringSplit($fields, [" ", "\n", "\r", "\t", "\v", ...Consts::SQL_OPERATORS]);
            $fields = [];
            foreach ($words as $word) {
                (
                    !preg_match('/^([a-z\_]+)$/i', $word)
                    || in_array($word, $reserved)
                    || in_array($word, Consts::SQL_OPERATORS)
                )
                    ?: $fields[] = $word;
            }

            foreach ($fields as $field) {
                preg_match_all('/(?:UPDATE)\s+(?:(\w+)\.)?(\w+)(?:\s+(?:AS\s+)?(\w+))?/i', $simpleFields[0][$a] ?? '', $tableMatches, PREG_SET_ORDER);
                $db = $tableMatches[0][1] ?? null;
                $table = $tableMatches[0][2] ?? null;
                $field = trim($field);
                (in_array($field, $reserved) || in_array($field, array_keys($tableAliases)))
                    ?: $elements[] = [
                        'type' => 'simple_field',
                        'database' => $db,
                        'table' => $table,
                        'name' => $field,
                    ];
            }
            $a++;
        }

        $a = 0; //level=1, message = 'text' WHERE id IN NOT NULL OR deleted_at IS NULL
        preg_match_all('/\bDELETE\s+FROM\b.+?\bWHERE\b\s+(.*)/i', $sql, $simpleFields);
        foreach ($simpleFields[1] ?? [] as $fields) {
            // (?:^|\bUPDATE\b|\bSET\b|\bWHERE\b|\bIN\b|\bOR\b)(.*?)(?=\bUPDATE\b|\bSET\b|\bWHERE\b|\bIN\b|\bOR\b|$)
            // $fields = static::arrayTrimValues(static::stringSplit($fields, [...$reserved, ...Consts::SQL_OPERATORS]));

            $words = static::stringSplit($fields, [" ", "\n", "\r", "\t", "\v", ...Consts::SQL_OPERATORS]);
            $fields = [];
            foreach ($words as $word) {
                (
                    !preg_match('/^([a-z\_]+)$/i', $word)
                    || in_array($word, $reserved)
                    || in_array($word, Consts::SQL_OPERATORS)
                )
                    ?: $fields[] = $word;
            }

            foreach ($fields as $field) {
                preg_match_all('/(?:DELETE\s+FROM)\s+(?:(\w+)\.)?(\w+)(?:\s+(?:AS\s+)?(\w+))?/i', $simpleFields[0][$a] ?? '', $tableMatches, PREG_SET_ORDER);
                $db = $tableMatches[0][1] ?? null;
                $table = $tableMatches[0][2] ?? null;
                $field = trim($field);
                (in_array($field, $reserved) || in_array($field, array_keys($tableAliases)))
                    ?: $elements[] = [
                        'type' => 'simple_field',
                        'database' => $db,
                        'table' => $table,
                        'name' => $field,
                    ];
            }
            $a++;
        }

        // Определяем основную таблицу в запросе
        $patterns = [
            '/select\s+.*?\sfrom\s+([`"\[\]]?[\w.]+[`"\]\[]?)/is', // SELECT ... FROM table
            '/insert\s+into\s+([`"\[\]]?[\w.]+[`"\]\[]?)/is',      // INSERT INTO table
            '/insert\s+([`"\[\]]?[\w.]+[`"\]\[]?)/is',             // INSERT table
            '/update\s+([`"\[\]]?[\w.]+[`"\]\[]?)/is',             // UPDATE table
            '/delete\s+from\s+([`"\[\]]?[\w.]+[`"\]\[]?)/is',      // DELETE FROM table
            '/truncate\s+table\s+([`"\[\]]?[\w.]+[`"\]\[]?)/is',   // TRUNCATE TABLE table
            '/alter\s+table\s+([`"\[\]]?[\w.]+[`"\]\[]?)/is',      // ALTER TABLE table
            '/create\s+table\s+([`"\[\]]?[\w.]+[`"\]\[]?)/is',     // CREATE TABLE table
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $sql, $m)) {
                // Проверяем, содержит ли имя таблицы точку (database.table)
                $mainTableRaw = trim($m[1], '`"[] ');
                if (strpos($mainTableRaw, '.') !== false) {
                    [$db, $table] = explode('.', $mainTableRaw, 2);
                    $mainTable = $table;
                    if (!$mainDatabase) {
                        $mainDatabase = $db;
                    }
                } else {
                    $mainTable = $mainTableRaw;
                }
                break;
            }
        }

        // Затем обрабатываем поля
        $sqlSearch = static::stringReplace($sql, ['*' => '%STAR%']);
        foreach ($elements as $element) {
            if ($element['type'] === 'field') {
                if (isset($tableAliases[$element['table_alias']])) {
                    $alias = $element['table_alias'];
                    $field = $element['name'];
                    $db = $tableAliases[$alias]['database'] ?: $mainDatabase;
                    $table = $tableAliases[$alias]['table'];

                    if ($table && !in_array($field, $result['databases'][$db]['tables'][$table]['fields'] ?? [])) {
                        $result['databases'][$db]['tables'][$table]['fields'] ??= [];
                        $result['databases'][$db]['tables'][$table]['fields'][] = $field;
                    }
                }
            } elseif ($element['type'] === 'simple_field') {
                // Для простых полей используем первую таблицу
                $db = $element['database'] ?? null;
                $table = $element['table'] ?? null;
                $field = $element['name'];
                if (!empty($tableAliases)) {
                    $fieldSearch = static::stringReplace($field, ['*' => '%STAR%']);
                    $bracketSearch = static::bracketSearch($sqlSearch, '(', ')', $fieldSearch);
                    $bracketIndex = static::arrayLast($bracketSearch[$fieldSearch] ?? []);
                    $bracketSql = is_null($bracketIndex) ? $sql : static::bracketCopy($sql, '(', ')', $bracketIndex);
                    !static::stringSearchAny($bracketSql, ['SELECT ', 'INSERT ', 'UPDATE ', 'DELETE '])
                        ?: $bracketSql = static::stringSplit(
                            $sql,
                            static::stringSplitSearch($bracketSql, ';', $fieldSearch)[$fieldSearch][0] ?? 0,
                        )[0] ?? '';

                    $table ??= preg_match(
                        '/(?:FROM|JOIN|INSERT\s+INTO|UPDATE)\s+(?:`?(\w+)`?\.)?`?(\w+)`?(?:\s+AS\s+(\w+))?/i',
                        $bracketSql,
                        $tableMatch,
                    )
                        ? ($tableMatch[2] ?: $mainTable) : $mainTable;
                    $table = $tableAliases[$table]['table'] ?: $table;
                    $db = $tableAliases[$table]['database'] ?? $mainDatabase;

                    if ($table && !in_array($field, $result['databases'][$db]['tables'][$table]['fields'] ?? [])) {
                        $result['databases'][$db]['tables'][$table]['fields'] ??= [];
                        $result['databases'][$db]['tables'][$table]['fields'][] = $field;
                    }
                }
            }
        }

        // 3. Специальная обработка JOIN
        if (preg_match('/JOIN\s+(?:(\w+)\.)?(\w+)\s+(?:AS\s+)?(\w+)\s+ON\s+(.*?)(?=\s+(?:WHERE|JOIN|$))/i', $sql, $joinMatch)) {
            $db = $joinMatch[1] ?: $mainDatabase;
            $table = $joinMatch[2];
            $alias = $joinMatch[3];
            if (isset($result['databases'][$db]['tables'][$table])) {
                if (preg_match_all('/' . preg_quote($alias, '/') . '\.(\w+)/', $joinMatch[4], $fieldMatches)) {
                    foreach ($fieldMatches[1] as $field) {
                        if (!in_array($field, $result['databases'][$db]['tables'][$table]['fields'] ?? [])) {
                            $result['databases'][$db]['tables'][$table]['fields'] ??= [];
                            $result['databases'][$db]['tables'][$table]['fields'][] = $field;
                        }
                    }
                }
            }
        }

        // 4. Специальная обработка под-запроса
        if (
            preg_match(
                '/EXISTS\s*\(\s*SELECT\s+(.*?)\s+FROM\s+(?:(\w+)\.)?(\w+)\s+WHERE\s+(.*?)\s*(?:ORDER BY|LIMIT|\))/i',
                $sql,
                $existsMatch,
            )
        ) {
            $db = $existsMatch[2] ?: $mainDatabase;
            $table = $existsMatch[3];
            if (isset($result['databases'][$db]['tables'][$table])) {
                // Поля из SELECT
                if (strpos($existsMatch[1], '*') !== false) {
                    if (!in_array('*', $result['databases'][$db]['tables'][$table]['fields'])) {
                        array_unshift($result['databases'][$db]['tables'][$table]['fields'], '*');
                    }
                }

                // Поля из WHERE
                if (preg_match_all('/' . preg_quote($table, '/') . '\.(\w+)/', $existsMatch[4], $condMatches)) {
                    foreach ($condMatches[1] as $field) {
                        if (!in_array($field, $result['databases'][$db]['tables'][$table]['fields'])) {
                            $result['databases'][$db]['tables'][$table]['fields'][] = $field;
                        }
                    }
                }

                // Поле из ORDER BY
                if (preg_match('/ORDER BY\s+(\w+)/i', $existsMatch[0], $orderMatch)) {
                    $field = $orderMatch[1];
                    if (!in_array($field, $result['databases'][$db]['tables'][$table]['fields'])) {
                        $result['databases'][$db]['tables'][$table]['fields'][] = $field;
                    }
                }
            }
        }

        // 5. Удаляем дубликаты полей
        foreach ($result['databases'] as $db => &$dbData) {
            foreach ($dbData['tables'] as $table => &$tableData) {
                $tableData['fields'] = array_values(array_unique($tableData['fields']));
                !$sort ?: sort($tableData['fields']);
            }
        }

        return $result;
    }


    /**
     * Возвращает массив затрагиваемых полей при операции INSERT
     * @see ../../tests/HelperSqlTrait/HelperSqlFieldsInsertTest.php
     *
     * @param string|null $value
     * @return array
     */
    public static function sqlFieldsInsert(?string $value): array
    {
        $value = (string)$value;

        if (preg_match('/insert\s+into\s+\S+\s*\(([^)]+)\)/i', $value, $matches)) {
            $fieldsStr = $matches[1];

            // Разбить по запятым и очистить от кавычек и пробелов
            $fields = array_map(fn ($field) => trim($field, " `\"'"), explode(',', $fieldsStr));

            return $fields;
        }

        return [];
    }


    /**
     * Возвращает массив затрагиваемых полей при операции UPDATE
     * @see ../../tests/HelperSqlTrait/HelperSqlFieldsUpdateTest.php
     *
     * @param string|null $value
     * @return array
     */
    public static function sqlFieldsUpdate(?string $value): array
    {
        $value = (string)$value;

        if (
            preg_match('/update\s+\S+\s+set\s+(.*?)\s+where\s+/is', $value, $matches)
            || preg_match('/update\s+\S+\s+set\s+(.*)$/is', $value, $matches)
        ) {

            $setPart = $matches[1];

            // Разделить по запятым, но только на верхнем уровне (внутри скобок может быть JSON и т.п.)
            $assignments = preg_split('/,(?![^\(\)]*\))/', $setPart);

            $fields = array_map(function ($assignment) {
                // Разделить по "=" и взять левую часть (название поля)
                $parts = explode('=', $assignment, 2);
                return trim(trim($parts[0]), "`\"' ");
            }, $assignments);

            return array_filter($fields);
        }

        return [];
    }
}
